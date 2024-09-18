<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MX_Model
{

	var $pos = [];
	var $cek_tgl = TRUE;
	var $miti_aktual = [];
	public function __construct()
	{
		parent::__construct();
	}

	function get_detail_char()
	{
		switch (intval($this->pos['data']['type_chat'])) {
			case 1:
				$rows = $this->detail_lap_mitigasi();
				break;
			case 2:
				$rows = $this->detail_lap_ketepatan();
				break;
			case 3:
				$rows = $this->detail_lap_komitment();
			default:
				break;
		}

		return $rows;
	}

	function detail_lap_mitigasi($mode = [])
	{
		$this->filter_data();
		$rows_progres = $this->db->select('rcsa_mitigasi_id, rcsa_mitigasi_detail_id, created_at, target, aktual')->order_by('rcsa_mitigasi_detail_id, created_at desc')->get(_TBL_VIEW_RCSA_MITIGASI_PROGRES)->result_array();
		$progres      = [];
		$id           = 0;
		$tgl          = '2000/01/01';
		foreach ($rows_progres as $row) {
			if ($id !== $row['rcsa_mitigasi_detail_id']) {
				$progres[$row['rcsa_mitigasi_detail_id']] = $row;
			} elseif ($row['created_at'] > $tgl) {
				$progres[$row['rcsa_mitigasi_detail_id']] = $row;
				$tgl                                      = $row['created_at'];
			}
			$id = $row['rcsa_mitigasi_detail_id'];
		}

		$rows = [];
		// dumps($progres);
		foreach ($progres as $key => $row) {
			if (array_key_exists($row['rcsa_mitigasi_id'], $rows)) {
				$rows[$row['rcsa_mitigasi_id']]['target'] += floatval($row['target']);
				$rows[$row['rcsa_mitigasi_id']]['aktual'] += floatval($row['aktual']);
				++$rows[$row['rcsa_mitigasi_id']]['jml'];
			} else {
				$rows[$row['rcsa_mitigasi_id']]['target'] = floatval($row['target']);
				$rows[$row['rcsa_mitigasi_id']]['aktual'] = floatval($row['aktual']);
				$rows[$row['rcsa_mitigasi_id']]['jml']    = 1;
			}
		}
		foreach ($rows as $key => &$row) {
			$row['target'] = floatval($row['target']) / $row['jml'];
			$row['aktual'] = floatval($row['aktual']) / $row['jml'];
		}
		unset($row);

		$mitigasi    = [];
		$mitigasi[1] = ['category' => 'Selesai', 'nilai' => 0];
		$mitigasi[2] = ['category' => 'Belum Selesai, On Schdule', 'nilai' => 0];
		$mitigasi[3] = ['category' => 'Belum Selesai, Terlambat', 'nilai' => 0];
		$mitigasi[4] = ['category' => 'Belum Dilaksanakan', 'nilai' => 0];

		$id   = [];
		$id[] = 0;
		foreach ($rows as $key => $row) {
			if ($row['target'] >= 100 && $row['aktual'] >= 100) {
				if (intval($this->pos['data']['param_id']) == 1) {
					$id[] = $key;
				}
			} elseif ($row['target'] == $row['aktual'] && floatval($row['target']) != 100) {
				if (intval($this->pos['data']['param_id']) == 2) {
					$id[] = $key;
				}
			} elseif ($row['target'] > 0 && floatval($row['aktual']) == 0) {
				if (intval($this->pos['data']['param_id']) == 3) {
					$id[] = $key;
				}
			} elseif (intval($this->pos['data']['param_id']) == 4) {
				$id[] = $key;
			}
		}
		// dumps($id);

		$mit = $this->db->where_in('id', $id)->get(_TBL_VIEW_RCSA_MITIGASI)->result_array();
		foreach ($mit as &$row) {
			$row['target'] = $rows[$row['id']]['target'];
			$row['aktual'] = $rows[$row['id']]['aktual'];
		}
		unset($row);
		$hasil['data'] = $mit;

		return $hasil;
	}

	function detail_lap_ketepatan()
	{
		$owner = [];
		$rows  = $this->db->select('*, 0 as target, 0 as aktual , "" as tgl_propose, "" as file  ')->where('owner_code<>', '')->get(_TBL_OWNER)->result_array();
		foreach ($rows as $row) {
			$owner[$row['id']] = $row;
		}

		unset($this->pos['tgl1']);
		$this->filter_data();
		$this->db->where('kode_dept<>', '');
		$rows           = $this->db->select('owner_id as id, kode_dept as owner_code, owner_name, 0 as status, tgl_propose, minggu_id')->group_by(['owner_id', 'kode_dept', 'owner_name'])->get(_TBL_VIEW_RCSA_APPROVAL_MITIGASI)->result_array();
		$tmp            = [];
		$seratussepuluh = [];
		$seratus        = [];
		$semilanpuluh   = [];
		$tujuhlima      = [];
		$nol            = [];
		foreach ($rows as $row) {
			$tgl  = $this->get_minggu($row['minggu_id']);
			$time = strtotime($tgl);

			$newformat = date('Y-m', $time);
			$deadline  = $newformat . '-05';

			$date1      = date_create($row['tgl_propose']);
			$date2      = date_create($deadline);
			$diffo      = date_diff($date2, $date1);
			$nilai_diff = intval($diffo->format("%R%a"));
			$diff       = $this->kepatuhan($nilai_diff);


			if ($diff == 110) {
				$seratussepuluh[] = $row['id'];
				$tmp[$row['id']]  = $row;
			} elseif ($diff == 100) {
				$seratus[]       = $row['id'];
				$tmp[$row['id']] = $row;
			} elseif ($diff == 90) {
				$semilanpuluh[]  = $row['id'];
				$tmp[$row['id']] = $row;
			} elseif ($diff == 75) {
				$tujuhlima[]     = $row['id'];
				$tmp[$row['id']] = $row;
			}
		}
		$id = [];
		foreach ($owner as $key => $row) {
			if (array_key_exists($key, $tmp)) {
				unset($owner[$key]);
				$nol[] = $key;
			}
		}

		if (intval($this->pos['data']['param_id']) == 1) {
			$id = $tujuhlima;
		} elseif (intval($this->pos['data']['param_id']) == 2) {
			$id = $semilanpuluh;
		} elseif (intval($this->pos['data']['param_id']) == 3) {
			$id = $seratus;
		} elseif (intval($this->pos['data']['param_id']) == 4) {
			$id = $seratussepuluh;
		}

		unset($row);
		if (intval($this->pos['data']['param_id']) == 0) {
			$rows = $owner;
		} else {
			if (isset($this->pos['data']['owner'])) {
				$id = $this->pos['data']['owner'];
			}
			$this->filter_data(FALSE, 'il_view_rcsa_approval_mitigasi');

			if (count($id)) {
				$this->db->where_in('il_view_rcsa_approval_mitigasi.owner_id', $id);
			}
			$this->db->where('minggu_id', $this->pos['minggu']);


			$this->db->join('il_view_rcsa_mitigasi_detail', 'il_view_rcsa_mitigasi_detail.rcsa_id = il_view_rcsa_approval_mitigasi.rcsa_id');
			$rows = $this->db->select('il_view_rcsa_approval_mitigasi.owner_id, il_view_rcsa_approval_mitigasi.kode_dept as owner_code, il_view_rcsa_approval_mitigasi.owner_name,il_view_rcsa_approval_mitigasi.minggu_id, il_view_rcsa_approval_mitigasi.tgl_propose, 0 as status, avg(target) as target, avg(aktual) as aktual,  file_att as file  ')
				->group_by(['il_view_rcsa_approval_mitigasi.owner_id', 'il_view_rcsa_approval_mitigasi.kode_dept', 'il_view_rcsa_approval_mitigasi.owner_name'])
				// ->get_compiled_select(_TBL_VIEW_RCSA_APPROVAL_MITIGASI);
				->get(_TBL_VIEW_RCSA_APPROVAL_MITIGASI)->result_array();
		}
		// doi::dump($rows);die;
		// dumps($rows);
		// die();
		$hasil['data'] = $rows;

		return $hasil;
	}

	function detail_lap_komitment()
	{
		$owner = [];
		$rows  = $this->db->select('*, 0 as target, 0 as aktual , "" as tgl_propose, "" as file ')->where('owner_code<>', '')->get(_TBL_OWNER)->result_array();
		foreach ($rows as $row) {
			$owner[$row['id']] = $row;
		}

		$this->filter_data();
		$rows = $this->db->select('owner_id, status_lengkap, 0 as status')->group_by(['owner_id', 'status_lengkap'])->get(_TBL_VIEW_RCSA_APPROVAL_MITIGASI)->result_array();
		$tmp  = [];
		foreach ($rows as $row) {
			$tmp[$row['owner_id']] = $row;
		}
		$id = [];
		foreach ($owner as $key => &$row) {
			if (array_key_exists($key, $tmp)) {
				unset($owner[$key]);
				if (intval($this->pos['data']['param_id']) == 1 && $tmp[$key]['status_lengkap'] == 1) {
					$id[] = $key;
				} elseif (intval($this->pos['data']['param_id']) == 2 && $tmp[$key]['status_lengkap'] == 2) {
					$id[] = $key;
				}
			} else {
				if (intval($this->pos['data']['param_id']) == 0) {
					$id[] = $key;
				}
			}
		}
		if (! $id) {
			$id[] = 0;
		}
		if (intval($this->pos['data']['param_id']) > 0) {
			$this->filter_data();
			if (count($id)) {
				$this->db->where_in('owner_id', $id);
			}
			$rows = $this->db->select('owner_id, kode_dept as owner_code, owner_name, tgl_propose, 0 as status, 0 as target, 0 as aktual, file_att as file  ')->get(_TBL_VIEW_RCSA_APPROVAL_MITIGASI)->result_array();
		} else {
			$rows = $owner;
		}
		$hasil['data'] = $rows;

		return $hasil;
	}


	function filter_data($custom = FALSE, $field = '')
	{

		$minggu = [];
		if ($this->cek_tgl) {
			if (isset($this->pos['minggu'])) {
				if (intval($this->pos['minggu']) && $custom == FALSE) {

					$rows              = $this->db->select('*')->where('id', intval($this->pos['minggu']))->get(_TBL_COMBO)->row_array();
					$this->pos['tgl1'] = $rows['param_date'];
					$this->pos['tgl2'] = $rows['param_date_after'];
				} else {

					if ($custom == TRUE && intval($this->pos['minggu']) == 0) {
						$rows = $this->db->select('*')->where('id', $this->pos['term'])->get(_TBL_COMBO)->row();
						$tgl1 = date('Y-m-d');
						$tgl2 = date('Y-m-d');
						if ($rows) {
							$tgl1 = $rows->param_date;
							$tgl2 = $rows->param_date_after;
						}
						$bulan  = $this->db->select('id')->where('kelompok', 'minggu')->where('param_date>=', $tgl1)->where('param_date_after<=', $tgl2)->get(_TBL_COMBO)->result_array();
						$minggu = array_column($bulan, 'id');
					}
				}
			}

			if (! isset($this->pos['tgl1'])) {
				if (isset($this->pos['term'])) {
					if (intval($this->pos['term'])) {
						$rows              = $this->db->select('*')->where('id', intval($this->pos['term']))->get(_TBL_COMBO)->row_array();
						$this->pos['tgl1'] = $rows['param_date'];
						$this->pos['tgl2'] = $rows['param_date_after'];
					}
				}
			}
		}
		if ($this->pos) {
			if ($this->pos['owner']) {
				if (count($this->owner_child)) {
					if ($field) {
						$this->db->where_in($field . '.owner_id', $this->owner_child);
					} else {
						$this->db->where_in('owner_id', $this->owner_child);
					}
				}
			}
			if ($this->pos['type_ass']) {
				if ($field) {
					$this->db->where_in($field . '.type_ass_id', $this->pos['type_ass']);
				} else {
					$this->db->where_in('type_ass_id', $this->pos['type_ass']);
				}
			}
			if ($this->pos['period']) {
				if ($field) {
					$this->db->where_in($field . '.period_id', $this->pos['period']);
				} else {
					$this->db->where_in('period_id', $this->pos['period']);
				}
			}
			// // if ($this->pos['term']){
			// // 	if ($field) {
			// // 		$this->db->where_in($field.'.term_id', $this->pos['term']);
			// // 	} else {
			// // 		$this->db->where_in('term_id', $this->pos['term']);
			// // 	}
			// // 	}

			// if (isset($this->pos['tgl1']) && $custom==false){
			// 	$this->db->where('tgl_mulai_minggu>=', $this->pos['tgl1']);
			// 	$this->db->where('tgl_akhir_minggu<=', $this->pos['tgl2']);
			// }elseif (isset($this->pos['minggu'])){
			// 	$this->db->where('minggu_id', $this->pos['minggu']);

			// 	if (count($minggu)>0) {
			// 		$this->db->where_in('minggu_id', $minggu);
			// 	}elseif(intval($this->pos['minggu']) > 0){
			// 		$this->db->where('minggu_id', $this->pos['minggu']);
			// 	}
			// }
		} else {
			$this->db->where('period_id', _TAHUN_ID_);
			// $this->db->where('term_id', _TERM_ID_);
		}
	}
	function filter_data_mon($custom = FALSE, $field = '')
	{

		if ($this->pos) {
			if ($this->pos['owner'] != "") {
				if (count($this->owner_child)) {
					$this->db->where_in('owner_id', $this->owner_child);
				}
			}
			if ($this->pos['type_ass'] != "") {
				$this->db->where_in('type_ass_id', $this->pos['type_ass']);
			}
			if ($this->pos['period'] != "") {
				$this->db->where_in('period_id', $this->pos['period']);
			}
			if (! empty($this->pos['minggu']) && $this->pos['minggu'] != "") {
				$this->db->where('bulan_id', $this->pos['minggu']);
			}
			// if ($this->pos['term']!=""){
			// 	$this->db->where_in('term_id', $this->pos['term']);
			// 	}
		} else {
			$this->db->where('period_id', _TAHUN_ID_);
			// $this->db->where('term_id', _TERM_ID_);
		}
	}


	function grap_mitigasi_old()
	{
		$this->filter_data(TRUE);
		$rows_progres = $this->db->select('rcsa_mitigasi_id, rcsa_mitigasi_detail_id, created_at, target, aktual')->order_by('rcsa_mitigasi_detail_id, created_at desc')
			// ->get_compiled_select(_TBL_VIEW_RCSA_MITIGASI_PROGRES);
			->get(_TBL_VIEW_RCSA_MITIGASI_PROGRES)->result_array();
		// dumps($rows_progres);
		// die();
		$progres = [];
		$id      = 0;
		$tgl     = '2000/01/01';
		foreach ($rows_progres as $row) {
			if ($id !== $row['rcsa_mitigasi_detail_id']) {
				$progres[$row['rcsa_mitigasi_detail_id']] = $row;
			} elseif ($row['created_at'] > $tgl) {
				$progres[$row['rcsa_mitigasi_detail_id']] = $row;
				$tgl                                      = $row['created_at'];
			}
			$id = $row['rcsa_mitigasi_detail_id'];
		}

		$rows = [];
		foreach ($progres as $key => $row) {
			if (array_key_exists($row['rcsa_mitigasi_id'], $rows)) {
				$rows[$row['rcsa_mitigasi_id']]['target'] += floatval($row['target']);
				$rows[$row['rcsa_mitigasi_id']]['aktual'] += floatval($row['aktual']);
				++$rows[$row['rcsa_mitigasi_id']]['jml'];
			} else {
				$rows[$row['rcsa_mitigasi_id']]['target'] = floatval($row['target']);
				$rows[$row['rcsa_mitigasi_id']]['aktual'] = floatval($row['aktual']);
				$rows[$row['rcsa_mitigasi_id']]['jml']    = 1;
			}
		}
		foreach ($rows as $key => &$row) {
			$row['target'] = floatval($row['target']) / $row['jml'];
			$row['aktual'] = floatval($row['aktual']) / $row['jml'];
		}
		unset($row);

		$mitigasi    = [];
		$mitigasi[1] = ['category' => 'Selesai', 'nilai' => 0];
		$mitigasi[2] = ['category' => 'Belum Selesai, On Schdule', 'nilai' => 0];
		$mitigasi[3] = ['category' => 'Belum Selesai, Terlambat', 'nilai' => 0];
		$mitigasi[4] = ['category' => 'Belum Dilaksanakan', 'nilai' => 0];
		foreach ($rows as $key => $row) {
			if ($row['target'] >= 100 && $row['aktual'] >= 100) {
				++$mitigasi[1]['nilai'];
			} elseif ($row['target'] == $row['aktual'] && floatval($row['target']) != 100) {
				++$mitigasi[2]['nilai'];
			} elseif ($row['target'] > 0 && floatval($row['aktual']) == 0) {
				++$mitigasi[3]['nilai'];
			} else {
				++$mitigasi[4]['nilai'];
			}
		}
		return $mitigasi;
	}

	function grap_mitigasi()
	{
		$this->filter_data(TRUE);
		$rows_progres   = $this->db->select('rcsa_detail_id,rcsa_id')->group_by('rcsa_detail_id')
			->get(_TBL_VIEW_RCSA_MITIGASI_PROGRES)->result_array();
		$jmlmiti        = 0;
		$seratussepuluh = 0;
		$seratus        = 0;
		$semilanpuluh   = 0;
		$tujuhlima      = 0;
		$nol            = 0;
		$owner_id110 = [];
		$owner_id100 = [];
		$owner_id90 = [];
		$owner_id75 = [];
		$seratussepuluh = 0;
		$seratus = 0;
		$semilanpuluh = 0;
		$tujuhlima = 0;
		$nol = 0;
		$jmlmiti = 0;

		foreach ($rows_progres as $key => $value) {
			$histori = $this->db->where('rcsa_id', $value['rcsa_id'])
				->where('tipe_log', 2)
				->where('keterangan like', '%final%')
				->order_by('tanggal', 'desc')
				->get(_TBL_VIEW_LOG_APPROVAL)
				->row_array();

			if (isset($histori['tanggal'])) {
				$tgl_final = $histori['tanggal'];

				$this->db->where('rcsa_detail_id', $value['rcsa_detail_id']);
				$rows = $this->db->select('owner_id, rcsa_detail_id as id, aktual, created_at, batas_waktu')
					->get(_TBL_VIEW_RCSA_MITIGASI_DETAIL)
					->result_array();

				$jmlmiti += count($rows);
				foreach ($rows as $row) {
					$deadline   = date('Y-m-d', strtotime($row['batas_waktu']));
					$tgl_finalx = date('Y-m-d', strtotime($tgl_final));

					$date1      = date_create($tgl_finalx);
					$date2      = date_create($deadline);
					$diffo      = date_diff($date2, $date1);
					$nilai_diff = intval($diffo->format("%R%a"));

					$diff = $this->kepatuhan_mitigasi($nilai_diff);

					if (intval($row['aktual']) == 0) {
						$nol += 1;
					} elseif ($diff == 110) {
						$seratussepuluh += 1;
						$owner_id110[] = $row['owner_id'];
					} elseif ($diff == 100) {
						$owner_id100[] = $row['owner_id'];
						$seratus += 1;
					} elseif ($diff == 90) {
						$owner_id90[] = $row['owner_id'];
						$semilanpuluh += 1;
					} elseif ($diff == 75) {
						$owner_id75[] = $row['owner_id'];
						$tujuhlima += 1;
					}
				}
			}
		}

		$hasil['owner_id110'] = $owner_id110;
		$hasil['owner_id100'] = $owner_id100;
		$hasil['owner_id90'] = $owner_id90;
		$hasil['owner_id75'] = $owner_id75;
		$hasil['total'] = $jmlmiti;
		$hasil['110'] = $seratussepuluh;
		$hasil['100'] = $seratus;
		$hasil['90'] = $semilanpuluh;
		$hasil['75'] = $tujuhlima;
		$hasil['0'] = $nol;

		$hasil['110%']  = ($jmlmiti > 0) ? number_format(($seratussepuluh / $jmlmiti) * 100, 2) : 0;
		$hasil['100%']  = ($jmlmiti > 0) ? number_format(($seratus / $jmlmiti) * 100, 2) : 0;
		$hasil['90%']   = ($jmlmiti > 0) ? number_format(($semilanpuluh / $jmlmiti) * 100, 2) : 0;
		$hasil['75%']   = ($jmlmiti > 0) ? number_format(($tujuhlima / $jmlmiti) * 100, 2) : 0;
		$hasil['0%']    = ($jmlmiti > 0) ? number_format(($nol / $jmlmiti) * 100, 2) : 0;

		return $hasil;
	}

	function kepatuhan_mitigasi($nilai)
	{
		if ($nilai < 0) {
			$hasil = "110";
		} elseif ($nilai == 0) {
			$hasil = "100";
		} elseif ($nilai <= 30) {
			$hasil = "90";
		} elseif ($nilai > 30) {
			$hasil = "75";
		}

		return $hasil;
	}

	function get_data_grap()
	{

		$mitigasi = $this->grap_mitigasi();
		// $mitigasi = $this->grap_mitigasi();
		// dumps($mitigasi);
		// die();
		$hasil['mitigasi'] = $mitigasi;
		$owner             = [];
		$rows              = $this->db->where('owner_code<>', '')->get(_TBL_OWNER)->result_array();
		foreach ($rows as $row) {
			$owner[$row['id']] = $row;
		}

		unset($this->pos['tgl1']);
		$this->filter_data();
		$this->db->where('kode_dept<>', '');
		$rows = $this->db->select('owner_id, kode_dept, owner_name, 0 as status, tgl_propose, minggu_id')->group_by(['owner_id', 'kode_dept', 'owner_name'])
			// ->get_compiled_select(_TBL_VIEW_RCSA_APPROVAL_MITIGASI);
			->get(_TBL_VIEW_RCSA_APPROVAL_MITIGASI)->result_array();

		$tmp            = [];
		$seratussepuluh = 0;
		$seratus        = 0;
		$semilanpuluh   = 0;
		$tujuhlima      = 0;
		$nol            = 0;
		$owner_id110 = [];
		$owner_id100 = [];
		$owner_id90 = [];
		$owner_id75 = [];
		foreach ($rows as $row) {
			$tgl  = $this->get_minggu($row['minggu_id']);
			$time = strtotime($tgl);

			$newformat = date('Y-m', $time);
			$deadline  = $newformat . '-05';

			$date1      = date_create($row['tgl_propose']);
			$date2      = date_create($deadline);
			$diffo      = date_diff($date2, $date1);
			$nilai_diff = intval($diffo->format("%R%a"));
			$diff       = $this->kepatuhan($nilai_diff);


			if ($diff == 110) {
				$seratussepuluh += 1;
				$owner_id110[] = $row['owner_id'];
			} elseif ($diff == 100) {
				$owner_id100[] = $row['owner_id'];
				$seratus += 1;
			} elseif ($diff == 90) {
				$semilanpuluh += 1;
				$owner_id90[] = $row['owner_id'];
			} elseif ($diff == 75) {
				$owner_id75[] = $row['owner_id'];
				$tujuhlima += 1;
			}

			$row['nilai']          = $diff . "%";
			$tmp[$row['owner_id']] = $row;
		}


		$ownerx = $owner;
		foreach ($ownerx as $key => &$row) {
			if (! array_key_exists($key, $tmp)) {
				$nol += 1;
				$row['nilai'] = "0%";
			} else {
				$row['nilai'] = $tmp[$key]['nilai'];
			}
		}
		unset($row);
		// dumps($ownerx);
		// die();
		$hasil['tepat']['owner_id110'] = $owner_id110;
		$hasil['tepat']['owner_id100'] = $owner_id100;
		$hasil['tepat']['owner_id90'] = $owner_id90;
		$hasil['tepat']['owner_id75'] = $owner_id75;
		$hasil['tepat']['owner'] = $ownerx;
		$hasil['tepat']['total'] = count($owner);
		$hasil['tepat']['110']   = $seratussepuluh;
		$hasil['tepat']['100']   = $seratus;
		$hasil['tepat']['90']    = $semilanpuluh;
		$hasil['tepat']['75']    = $tujuhlima;
		$hasil['tepat']['0']     = $nol;
		$hasil['tepat']['110%']  = number_format(($seratussepuluh / count($owner)) * 100, 2);
		$hasil['tepat']['100%']  = number_format(($seratus / count($owner)) * 100, 2);
		$hasil['tepat']['90%']   = number_format(($semilanpuluh / count($owner)) * 100, 2);
		$hasil['tepat']['75%']   = number_format(($tujuhlima / count($owner)) * 100, 2);
		$hasil['tepat']['0%']    = number_format(($nol / count($owner)) * 100, 2);


		// if ($this->pos){
		// 	if ($this->pos['period']){
		// 		$this->db->where('period_id', $this->pos['period']);
		// 	}
		// 	if ($this->pos['term']){
		// 		$this->db->where('term_id', $this->pos['term']);
		// 	}
		// 	if ($this->pos['minggu']){
		// 		$this->db->where('minggu_id', $this->pos['minggu']);
		// 	}
		// }
		$this->filter_data();
		$rows = $this->db->select('owner_id, status_lengkap, 0 as status')->group_by(['owner_id', 'status_lengkap'])->get(_TBL_VIEW_RCSA_APPROVAL_MITIGASI)->result_array();
		$tmp  = [];
		foreach ($rows as $row) {
			$tmp[$row['owner_id']] = $row;
		}
		foreach ($owner as $key => &$row) {
			if (array_key_exists($key, $tmp)) {
				$row['status'] = $tmp[$key]['status_lengkap'];
			} else {
				$row['status'] = 0;
			}
		}
		unset($row);
		$stat[2] = ['category' => 'Dibicarakan rutin setiap minggu dengan Evidence', 'nilai' => 0];
		$stat[1] = ['category' => 'Dibicarakan rutin setiap minggu dengan Evidence tidak lengkap', 'nilai' => 0];
		$stat[0] = ['category' => 'Tidak dibicarakan', 'nilai' => 0];
		// dumps($owner);die();
		foreach ($owner as $key => $row) {
			++$stat[$row['status']]['nilai'];
		}

		$hasil['komitment']['owner'] = $owner;
		$hasil['komitment']['total'] = count($owner);
		$hasil['komitment']['data']  = $stat;
		return $hasil;
	}

	function get_minggu($id)
	{
		$minggu = $this->crud->combo_select(['id', 'param_date'])->combo_where('kelompok', 'minggu')->combo_where('id', $id)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		unset($minggu[""]);

		return (isset($minggu[$id])) ? $minggu[$id] : 0;
	}

	function kepatuhan($nilai)
	{
		if ($nilai < 0) {
			$hasil = "110";
		} elseif ($nilai == 0) {
			$hasil = "100";
		} elseif ($nilai == 1) {
			$hasil = "90";
		} elseif ($nilai == 2) {
			$hasil = "90";
		} elseif ($nilai >= 3) {
			$hasil = "75";
		}

		return $hasil;
	}

	function grap_taksonomi($lib)
	{
		$taskTonomi = $this->db->select('id, data, param_string')->where('kelompok', $lib)->where('active', 1)->order_by('data')->get(_TBL_COMBO)->result_array();
		$dat['tasktonomi'] = $taskTonomi;
		$total = 0;
		foreach ($taskTonomi as $q) {
			if ($this->pos['owner']) {
				$ownerChld[] = intval($this->pos['owner']);
				$this->data->get_owner_child(intval($this->pos['owner']));
				$ownerChldRes = $ownerChld;
				if (count($ownerChldRes)) {
					$this->db->where_in('owner_id', $ownerChldRes);
				}
			}
			
			if ($this->pos['period']) {
				$this->db->where('period_id', $this->pos['period']);
			}

			 
			$r = $this->db->select('id')->where('tasktonomi_no', $q['id'])->get(_TBL_VIEW_RCSA_DETAIL)->result_array();
			$detailxxx[$q['data']] = count($r);
			$total += count($r);
		}
		$dat['detail'] = $detailxxx;
		$dat['total'] = $total;
		return $dat;
	}

	function detail_stsmon()
	{

		if ($this->pos['owner']) {
			$ownerChld[] = intval($this->pos['owner']);
			$this->data->get_owner_child(intval($this->pos['owner']));
			$ownerChldRes = $ownerChld;
			if (count($ownerChldRes)) {
				$this->db->where_in('owner_id', $ownerChldRes);
			}
		}
		
		if ($this->pos['id']) {
			if ($this->pos['id'] == 'Done') {
				$sts = 2;
			}
			if ($this->pos['id'] == 'Porgress') {
				$sts = 1;
			}
			if ($this->pos['id'] == 'Not Yet') {
				$sts = 0;
			}
			$this->db->where('sts_mon', $sts);
		}

		$this->db->where('period_id', $this->pos['period']);
		 
		$this->db->where('status_final', 1);

		$detail = $this->db->get(_TBL_VIEW_RCSA_DETAIL)->result_array();

		$hasil['data'] = $detail;

		return $hasil;
	}

	function detail_taks_tipe()
	{

		if ($this->pos['owner']) {
			$ownerChld[] = intval($this->pos['owner']);
			$this->data->get_owner_child(intval($this->pos['owner']));
			$ownerChldRes = $ownerChld;
			if (count($ownerChldRes)) {
				$this->db->where_in('owner_id', $ownerChldRes);
			}
		}

		if ($this->pos['param_id'] == 1) {
			$this->db->where('tasktonomi_no', $this->pos['id']);
		}

		if ($this->pos['param_id'] == 2) {
			$this->db->where('klasifikasi_risiko_id', $this->pos['id']);
		}

		$this->db->where('period_id', $this->pos['period']);

		$detail = $this->db->get(_TBL_VIEW_RCSA_DETAIL)->result_array();

		$hasil['data'] = $detail;

		return $hasil;
	}

	function get_data_grap_mitigasi()
	{
		// doi::dump($this->pos); die;
		$r = $this->db->select('id, month, level_color, color_text, color as bg, rcsa_detail_id')
			->get("il_update_residual")->result_array();

		$this->db->where('period_id', $this->pos['period']);
		if ($this->pos['owner'] != "") {
			if (count($this->owner_child)) {
				$this->db->where_in('owner_id', $this->owner_child);
			}
		}
		$this->db->where('status_final', 1);
		$detail = $this->db->select('period_id, owner_name, id, sts_mon')->get(_TBL_VIEW_RCSA_DETAIL)->result_array();


		$dat['levelRisiko'] = $r;
		$dat['detail'] = $detail;
		return $dat;
	}
}


/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */
