<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MX_Model
{

	var $pos = [];
	var $cek_tgl = true;
	var $miti_aktual = [];
	public function __construct()
	{
		parent::__construct();
		$this->nm_tbl = "profil_risiko";
	}

	function checklist($owner = 0, $period = 0)
	{
		$checklist = [];
		// if ($period!=0) {
		// 	$this->db->where('period_id', $period);
		// }
		// if ($term!=0) {
		// 	$this->db->where('term_id', $term);
		// }

		// if ($owner!=0) {
		$this->db->where('owner_id', $owner);
		if ($period != 0) {
			$this->db->where('period_id', $period);
		}
		// }

		$check = $this->db->select("rcsa_detail_id, kode_risiko_dept, kode_aktifitas, kode_dept, period_id")
			->get($this->nm_tbl)->result_array();

		foreach ($check as $key => $value) {
			// $urut=str_pad($value['kode_risiko_dept'],3,0,STR_PAD_LEFT );
			$kode = $value['kode_dept'] . '-' . $value['kode_aktifitas'] . '-' . $value['kode_risiko_dept'] . '-' . $value['period_id'];
			$checklist[] = $kode;
		}

		return $checklist;
	}

	function filter_data($dtuser)
	{

		if (isset($this->pos['owner'])) {
			$check = $this->checklist($this->pos['owner'], $this->pos['period']);
		} else {
			$this->super_user = intval($dtuser['is_admin']);
			$this->ownerx = intval(($this->super_user == 0) ? $dtuser['owner_id'] : 0);
			$check = $this->checklist($this->ownerx, _TAHUN_ID_);
		}
		$ck = [];
		if (count($check) > 0) {
			foreach ($check as $key => $value) {
				$k = explode('-', $value);
				$ck[] = $k[0] . '-' . $k[1] . '-' . str_pad($k[2], 3, 0, STR_PAD_LEFT);
			}
		}

		if ($this->cek_tgl) {
			if (isset($this->pos['minggu'])) {
				if (intval($this->pos['minggu'])) {
					$rows = $this->db->select('*')->where('id', intval($this->pos['minggu']))->get(_TBL_COMBO)->row_array();
					$this->pos['tgl1'] = $rows['param_date'];
					$this->pos['tgl2'] = $rows['param_date_after'];
				}
			}

			if (!isset($this->pos['tgl1'])) {
				if (isset($this->pos['term_mulai'])) {
					if (intval($this->pos['term_mulai'])) {
						$rows = $this->db->select('*')->where('id', intval($this->pos['term_mulai']))->get(_TBL_COMBO)->row_array();
						$this->pos['tgl1'] = $rows['param_date'];
						// $this->pos['tgl2']=$rows['param_date_after'];
					}
				}

				if (isset($this->pos['term_akhir'])) {
					if (intval($this->pos['term_akhir'])) {
						$rows = $this->db->select('*')->where('id', intval($this->pos['term_akhir']))->get(_TBL_COMBO)->row_array();
						// $this->pos['tgl1']=$rows['param_date'];
						$this->pos['tgl2'] = $rows['param_date_after'];
					}
				}
			}
		}

		if (count($ck) > 0) {
			$this->db->where_in('kode_risk', $ck);
		} else {
			$this->db->where('kode_risk', '-1');
		}

		if ($this->pos) {
			if ($this->pos['owner']) {
				if ($this->owner_child) {
					$this->db->where_in('owner_id', $this->owner_child);
				}
			}

			if ($this->pos['type_ass']) {
				$this->db->where('type_ass_id', $this->pos['type_ass']);
			}

			if ($this->pos['period']) {
				$this->db->where('period_id', $this->pos['period']);
			}

			if (isset($this->pos['tgl1'])) {
				$this->db->where('tgl_mulai_minggu', $this->pos['tgl1']);
			}

			if (isset($this->pos['tgl2'])) {
				$this->db->or_where('tgl_akhir_minggu', $this->pos['tgl2']);
			}
			if ($this->pos['owner']) {
				if ($this->owner_child) {
					$this->db->where_in('owner_id', $this->owner_child);
				}
			}

			if ($this->pos['type_ass']) {
				$this->db->where('type_ass_id', $this->pos['type_ass']);
			}

			if ($this->pos['period']) {
				$this->db->where('period_id', $this->pos['period']);
			}


			// elseif ($this->pos['minggu']){
			// 	// $this->db->where('minggu_id', $this->pos['minggu']);
			// }
		} else {
			$this->db->where('period_id', _TAHUN_ID_);
			$this->db->where('term_id', _TERM_ID_);
		}

		if (count($ck) > 0) {
			$this->db->where_in('kode_risk', $ck);
		} else {
			$this->db->where('kode_risk', '-1');
		}
	}

	function get_data_map($dtuser)
	{
		// $data['rcsa'] = $this->db->where('id', $this->pos['id'])->get(_TBL_VIEW_RCSA)->row_array();
		$rows = $this->db->select('rcsa_detail_id as id, count(rcsa_detail_id) as jml')->group_by(['rcsa_detail_id'])->get(_TBL_VIEW_RCSA_MITIGASI)->result_array();

		$miti = [];
		foreach ($rows as $row) {
			$miti[$row['id']] = $row['jml'];
		}
		$rows = $this->db->select('rcsa_detail_id as id, count(rcsa_detail_id) as jml, avg(aktual) as aktual')->group_by(['rcsa_detail_id'])
			// ->get_compiled_select(_TBL_VIEW_RCSA_MITIGASI_DETAIL);
			->get(_TBL_VIEW_RCSA_MITIGASI_DETAIL)->result_array();
		$aktifitas = [];
		$avgaktifitas = [];
		// dumps($rows);
		// die();

		foreach ($rows as $row) {
			$aktifitas[$row['id']] = $row['jml'];
			$avgaktifitas[$row['id']] = $row['aktual'];
		}
		$rows = $this->db->select('rcsa_detail_id as id, count(rcsa_detail_id) as jml')->group_by(['rcsa_detail_id'])
			->get(_TBL_VIEW_RCSA_MITIGASI_PROGRES)->result_array();

		$progres = [];

		foreach ($rows as $row) {
			$progres[$row['id']] = $row['jml'];
		}
		// $this->filter_data();
		$this->filter_data_all(_TBL_VIEW_RCSA_DETAIL, $dtuser, true);


		// dumps("cek");
		// die();
		$rows = $this->db->order_by('tgl_mulai_minggu')->get(_TBL_VIEW_RCSA_DETAIL)->result_array();
		// $rows=$this->db->get_compiled_select(_TBL_VIEW_RCSA_DETAIL);
		// dumps($rows);
		foreach ($rows as &$row) {
			if (array_key_exists($row['id'], $miti)) {
				$row['jml'] = $miti[$row['id']];
			}
			$row['jml2'] = 0;
			$row['avg2'] = 0;

			if (array_key_exists($row['id'], $aktifitas)) {
				$row['jml2'] = $aktifitas[$row['id']];
				$row['avg2'] = number_format($avgaktifitas[$row['id']], 2);
			}

			$row['jml3'] = 0;
			if (array_key_exists($row['id'], $progres)) {
				$row['jml3'] = $progres[$row['id']];
			}
		}
		unset($row);
		$data['detail'] = $rows;
		return $data;
	}

	function filter_data_all($customfield = '', $dtuser, $range = false)
	{
	
		if (isset($this->pos['owner'])) {
			$check = $this->checklist($this->pos['owner'], $this->pos['period']);
		} else {
			$this->super_user = intval($dtuser['is_admin']);
			$this->ownerx = intval(($this->super_user == 0) ? $dtuser['owner_id'] : 0);
			$check = $this->checklist($this->ownerx, _TAHUN_ID_);
		}
		$ck = [];
		if (count($check) > 0) {
			foreach ($check as $key => $value) {
				$k = explode('-', $value);
				$ck[] = $k[0] . '-' . $k[1] . '-' . str_pad($k[2], 3, 0, STR_PAD_LEFT);
			}
		}
		$field = ($customfield != '') ? $customfield . "." : '';
		if ($this->cek_tgl) {
			if (isset($this->pos['minggu'])) {
				if (intval($this->pos['minggu'])) {
					$rows = $this->db->select('*')->where('id', intval($this->pos['minggu']))->get(_TBL_COMBO)->row_array();
					$this->pos['tgl1'] = $rows['param_date'];
					$this->pos['tgl2'] = $rows['param_date_after'];
				}
			}

			if (!isset($this->pos['tgl1'])) {
				if (isset($this->pos['term_mulai'])) {
					if (intval($this->pos['term_mulai'])) {
						$rows = $this->db->select('*')->where('id', intval($this->pos['term_mulai']))->get(_TBL_COMBO)->row_array();
						$this->pos['tgl1'] = $rows['param_date'];
						// $this->pos['tgl2']=$rows['param_date_after'];
					}
				}

				if (isset($this->pos['term_akhir'])) {
					if (intval($this->pos['term_akhir'])) {
						$rows = $this->db->select('*')->where('id', intval($this->pos['term_akhir']))->get(_TBL_COMBO)->row_array();
						// $this->pos['tgl1']=$rows['param_date'];
						$this->pos['tgl2'] = $rows['param_date_after'];
					}
				}
			}
		}

		if ($this->pos) {
			if ($this->pos['owner']) {
				if ($this->pos['owner'] != 0 && $this->pos['owner'] != 1) {
					$this->owner_child[] = intval($this->pos['owner']);
					$this->get_owner_child(intval($this->pos['owner']));

					$this->db->where_in($field . 'owner_id', $this->owner_child);
				}
			}

			if (count($ck) > 0) {
				$this->db->where_in('kode_risk', $ck);
			} else {
				$this->db->where('kode_risk', '-1');
			}


			if ($this->pos['type_ass']) {
				$this->db->where($field . 'type_ass_id', $this->pos['type_ass']);
			}
			if ($this->pos['period']) {
				$this->db->where($field . 'period_id', $this->pos['period']);
			}

			if ($range) {
				if (isset($this->pos['tgl1'])) {
					$this->db->where('tgl_mulai_minggu>=', $this->pos['tgl1']);
				}
				if (isset($this->pos['tgl2'])) {
					$this->db->where('tgl_akhir_minggu<=', $this->pos['tgl2']);
				}
			} else {

				if (isset($this->pos['tgl1'])) {
					$this->db->where('tgl_mulai_minggu', $this->pos['tgl1']);
				}

				if (isset($this->pos['tgl2'])) {
					$this->db->or_where('tgl_akhir_minggu', $this->pos['tgl2']);
				}
			}

			if (!$range) {
				if ($this->pos['owner']) {
					if ($this->pos['owner'] != 0 && $this->pos['owner'] != 1) {

						$this->db->where_in($field . 'owner_id', $this->owner_child);
					}
				}

				if ($this->pos['type_ass']) {
					$this->db->where($field . 'type_ass_id', $this->pos['type_ass']);
				}
				if ($this->pos['period']) {
					$this->db->where($field . 'period_id', $this->pos['period']);
				}
			}
		} else {
			$this->db->where($field . 'period_id', _TAHUN_ID_);
			$this->db->where($field . 'term_id', _TERM_ID_);
			// $c =$this->session->userdata('data_user');
			// if ($c['group']['param']['privilege_owner']>=2){
			// 	if ($c['owner']){
			// 		$this->db->where_in($field.'owner_id', $c['owner']);

			// 	}
			// }
		}
		if (!$range) {
			if (isset($this->pos['type_ass'])) {
				if (intval($this->pos['type_ass'])) {
					$this->db->where('type_ass_id', $this->pos['type_ass']);
				}
			}

			if (count($ck) > 0) {
				$this->db->where_in('kode_risk', $ck);
			} else {
				$this->db->where('kode_risk', '-1');
			}
		}



		// $this->db->where('type_ass_id', 128);
	}


	function delete_data($id, $owner)
	{
		$this->db->where_in('id', $id);
		$this->db->where('owner_id', $owner);
		$this->db->delete($this->nm_tbl);
		$jml = $this->db->affected_rows();
		return $jml;
	}

	function simpan_data($data, $owner, $ori)
	{

		$del = array_diff($ori, $data);

		if (count($del) > 0) {
			foreach ($del as $key => $value) {
				if ($value != "") {
					$kodeX = explode('-', $value);
					$this->db->where('kode_dept', $kodeX[0]);
					$this->db->where('kode_aktifitas', $kodeX[1]);
					$this->db->where('kode_risiko_dept', $kodeX[2]);
					$this->db->where('period_id', $kodeX[3]);
					$this->db->where('owner_id', $owner);
					$this->db->delete($this->nm_tbl);
				}
			}
		}

		$newdata = [];
		if (count($data)) {
			foreach ($data as $key => $value) {
				if ($value) {
					$kodeX = explode('-', $value);
					// $old = $this->checklist($owner, $kodeX[3]);
		
					$this->db->where('kode_dept', $kodeX[0]);
					$this->db->where('kode_aktifitas', $kodeX[1]);
					$this->db->where('kode_risiko_dept', $kodeX[2]);
					$this->db->where('period_id', $kodeX[3]);
					$this->db->where('owner_id', $owner);
					$cek = $this->db->get($this->nm_tbl)->row_array();
		
					if ($cek == null) {
						$newdata[] = [
							'kode_dept' => $kodeX[0],
							'kode_aktifitas' => $kodeX[1],
							'kode_risiko_dept' => $kodeX[2],
							'period_id' => $kodeX[3],
							'owner_id' => $owner,
						];
					}
				}
			}
		}

		if (count($newdata)) {
			$this->db->insert_batch($this->nm_tbl, $newdata);
		}
	}

	function simpan_datax($data, $owner)
	{
		// $this->db->empty_table('profil_risiko');
		$old = $this->checklist($owner);

		$new = [];
		$del = [];
		$newdata = [];
		foreach ($old as $key => $value) {
			if (!in_array($value, $data)) {
				$del[] = $value;
			}
		}
		// dumps($old);
		// dumps($del);
		// die();
		foreach ($data as $key => $value) {
			if (!in_array($value, $old)) {
				$new[] = $value;
			}
		}

		if (count($del) > 0) {
			foreach ($del as $key => $value) {
				$kodeX = explode('-', $value);

				$this->db->where('period_id', $kodeX[3]);
				$this->db->where('owner_id', $owner);
				$cek = $this->db->get($this->nm_tbl)->result_array();

				if (count($cek) > 0) {
					foreach ($cek as $key => $value) {
						$this->db->where('kode_dept', $value['kode_dept']);
						$this->db->where('kode_aktifitas', $value['kode_aktifitas']);
						$this->db->where('kode_risiko_dept', $value['kode_risiko_dept']);
						$this->db->where('period_id', $value['period_id']);
						$this->db->where('owner_id', $owner);
						$this->db->delete($this->nm_tbl);
					}
				}
			}
		}

		if (count($new) > 0) {
			foreach ($new as $key => $value) {
				$kodeX = explode('-', $value);

				$this->db->where('kode_dept', $kodeX[0]);
				$this->db->where('kode_aktifitas', $kodeX[1]);
				$this->db->where('kode_risiko_dept', $kodeX[2]);
				$this->db->where('period_id', $kodeX[3]);
				$this->db->where('owner_id', $owner);
				$cek = $this->db->get($this->nm_tbl)->row_array();

				if ($cek == null) {
					$newdata[] = [
						'kode_dept' => $kodeX[0],
						'kode_aktifitas' => $kodeX[1],
						'kode_risiko_dept' => $kodeX[2],
						'period_id' => $kodeX[3],
						'owner_id' => $owner,
					];
				}
			}

			$this->db->insert_batch($this->nm_tbl, $newdata);
		}

		return true;
	}

	function get_data_minggu($id)
	{
		$rows = $this->db->select('*')->where('id', $id)->get(_TBL_COMBO)->row();
		$tgl1 = date('Y-m-d');
		$tgl2 = date('Y-m-d');
		if ($rows) {
			$tgl1 = $rows->param_date;
			$tgl2 = $rows->param_date_after;
		}
		$rows = $this->db->select('*')->where('kelompok', 'minggu')->where('param_date>=', $tgl1)->where('param_date_after<=', $tgl2)->get(_TBL_COMBO)->result();
		$option[""] = _l('cbo_select');
		foreach ($rows as $row) {
			$option[$row->id] = $row->param_string;
			// .' ('.date('d-m-Y',strtotime($row->param_date)).' s.d '.date('d-m-Y',strtotime($row->param_date_after)).')';
		}

		return $option;
	}

	function get_detail_data($dtuser)
	{

		$bulan = [1, 12];


		// if (isset($this->pos['term_mulai'])){
		// 	if (intval($this->pos['term_mulai'])){
		// 		$rows= $this->db->select('*')->where('id', intval($this->pos['term_mulai']))->get(_TBL_COMBO)->row_array();

		// 		$bulan[0]=date('n',strtotime($rows['param_date']));
		// 	}
		// }

		// if (isset($this->pos['term_akhir'])){
		// 	if (intval($this->pos['term_akhir'])){
		// 		$rows= $this->db->select('*')->where('id', intval($this->pos['term_akhir']))->get(_TBL_COMBO)->row_array();
		// 		$bulan[1]=date('n',strtotime($rows['param_date_after']));
		// 	}
		// }

		// $period=date('Y');
		// if (intval($this->pos['period'])>0){
		// 	$period = $this->pos['period'];
		// }


		$owner = 0;
		$parent = [];
		$owner_name = ' All Departement ';
		// if (intval($this->pos['owner'])>0){
		// 	$owner = $this->pos['owner'];
		// 	$parent = $this->db->where('id', $owner)->get(_TBL_OWNER)->row_array();
		// 	$owner_name = $parent['owner_name'];
		// }
		$minggu = 0;
		// $minggu = $this->pos['minggu'];
		// if ($minggu>0) {
		// 	$this->db->where('minggu_id', $minggu);
		// }
		$this->filter_data_all(_TBL_VIEW_RCSA_DETAIL, $dtuser, true);
		$rcsa = $this->db->select('rcsa_id')->group_by('rcsa_id')
			->get(_TBL_VIEW_RCSA_DETAIL)->result_array();

		$rcsa_id = [];
		foreach ($rcsa as $key => $value) {
			$rcsa_id[] = $value['rcsa_id'];
		}
		if (count($rcsa_id) > 0) {
			$this->db->where_in('rcsa_id', $rcsa_id);
		} else {
			$this->db->where('rcsa_id', '-1');
		}

		$rows = $this->db
			// ->get_compiled_select(_TBL_VIEW_RCSA_KPI);
			->get(_TBL_VIEW_RCSA_KPI)->result_array();
		// dumps($rows);
		// die();
		$lap2 = [];
		foreach ($rows as $row) {
			$tmp = [];
			$d = $this->db->where('kpi_id', $row['id'])->get(_TBL_VIEW_RCSA_KPI_DETAIL)->result_array();
			$detail = [];
			foreach ($d as $dd) {
				$detail[] = $dd;
			}
			$tmp = $row;
			$tmp['detail'] = $detail;
			$lap2[] = $tmp;
		}
		// dumps($lap2);die();

		$detail = [];
		foreach ($rows as $row) {
			$d = $this->db->where('kpi_id', $row['id'])->get(_TBL_VIEW_RCSA_KPI_DETAIL)->result_array();
			foreach ($d as $dd) {
				$detail[$row['id']]['detail'][$dd['id']] = $dd;
			}
		}

		$x = [];
		// dumps($detail);die();
		foreach ($detail as $key => $row) {
			$owner_id = 0;
			$xx = [];
			foreach ($row['detail'] as $k => $d) {
				$idi = '-1';
				if ($owner_id !== $key) {
					// dumps($d);
					$xx[$k]['name'] = $d['owner_name'];
					$xx[$k]['satuan'] = $d['satuan'];
					$xx[$k]['title'] = trim($d['title']);
					$xx[$k]['indikator'] = $d['indikator'];
					$owner_id = $d['kpi_id'];
					$idi = $key;
				}
				// if (count($rcsa_id)>0) {
				// }else{
				// 	$this->db->where('rcsa_id', '-1');
				// }
				$this->db->where_in('kpi_id', $idi);
				$dd = $this->db->where('minggu_type', 1)
					// ->where('bulan_int>=',$bulan[0]) 
					// ->where('bulan_int<=',$bulan[1])
					// ->where('period_id',$period)
					->where('title like ', "%" . $d['title'])


					// ->get_compiled_select(_TBL_VIEW_RCSA_KPI_DETAIL);
					->get(_TBL_VIEW_RCSA_KPI_DETAIL)->result_array();
				// dumps($idi);
				// die();
				// dumps($dd);

				foreach ($dd as $ke => $va) {
					$xx[$k]['bulan'][$va['bulan_int']] = $va;
				}
				// if ($minggu==0) {
				// } else {
				// 	$xx[$k]['bulan'][$d['bulan_int']]=$d;
				// }


			}
			$x[$key] = $xx;
		}
		// dumps($x);
		// die();
		unset($row);
		$y = [];
		$owner_id = 0;
		foreach ($rows as $key => $row) {
			$idi = 0;

			if ($owner_id !== $row['id']) {
				$y[$row['id']]['name'] = $row['owner_name'];
				$y[$row['id']]['satuan'] = $row['satuan'];
				$y[$row['id']]['title'] = trim($row['title']);
				$y[$row['id']]['indikator'] = $row['indikator'];
				$owner_id = $row['id'];
				$idi = $d['id'];
			}
			if (count($rcsa_id) > 0) {
				$this->db->where_in('rcsa_id', $rcsa_id);
			} else {
				$this->db->where('rcsa_id', '-1');
			}
			$dd = $this->db->where('minggu_type', 1)
				// ->where('bulan_int>=',$bulan[0])
				// ->where('bulan_int<=',$bulan[1])
				// ->where('period_id',$period)

				->where('title like ', "%" . $row['title'])
				->get(_TBL_VIEW_RCSA_KPI)->result_array();

			foreach ($dd as $key => $value) {
				$y[$row['id']]['bulan'][$value['bulan_int']] = $value;
			}
			// if ($minggu==0) {

			// }else{
			// 	$y[$row['id']]['bulan'][$row['bulan_int']]=$row;
			// }
		}

		unset($row);
		// dumps($x);
		foreach ($y as $key => &$row) {
			if (array_key_exists($key, $x)) {
				// dumps('xxx');
				$row['detail'] = $x[$key];
			} else {
				$row['detail'] = [];
			}
		}

		// dumps($x);
		// die();
		unset($row);

		$hasil['bulan'] = $bulan;
		$hasil['data'] = $y;
		$hasil['lap2'] = $lap2;
		$hasil['parent'] = $parent;
		$hasil['owner_name'] = $owner_name;
		return $hasil;
	}

	function get_data_kompilasi($dtuser)
	{

		$rcsa = [];
		$rcsa_detail = [];
		$this->filter_data_all(_TBL_VIEW_RCSA_DETAIL, $dtuser, true);
		$rows = $this->db->order_by('tgl_mulai_minggu')->get(_TBL_VIEW_RCSA_DETAIL)->result_array();

		foreach ($rows as $key => $value) {
			if (!in_array($value['id'], $rcsa_detail)) {
				$rcsa_detail[] = $value['id'];
			}

			if (!in_array($value['rcsa_id'], $rcsa)) {
				$rcsa[] = $value['rcsa_id'];
			}
		}


		$rows = $this->db->where_in('rcsa_detail_id', $rcsa_detail)
			// ->get_compiled_select(_TBL_VIEW_RCSA_MITIGASI_PROGRES);
			->get(_TBL_VIEW_RCSA_MITIGASI_PROGRES)->result_array();

		// dumps($rows);
		// die();
		$mit = [];
		$jml['aktif'] = [];
		foreach ($rows as $row) {
			$mit[$row['penyebab_id']][] = $row;
			$jml['aktif'][$row['rcsa_mitigasi_detail_id']][] = $row['id'];
		}
		$hasil = $jml;
		$rows = $this->db->where_in('id', $rcsa)->get(_TBL_VIEW_RCSA)->row_array();
		$parent = $rows;
		$mitigasi = $mit;
		$rows = $this->db->where_in('rcsa_detail_id', $rcsa_detail)->get(_TBL_VIEW_MONITORING)->result_array();
		$rows = $this->convert_owner->set_data($rows)->set_param(['penanggung_jawab' => 'penanggung_jawab_detail_id', 'koordinator' => 'koordinator_detail_id'])->draw();
		$rowsx = $rows;

		$jml['miti'] = [];
		$jml['identi'] = [];
		foreach ($rows as $row) {
			$jml['identi'][$row['rcsa_detail_id']][] = $row['id'];
			$jml['miti'][$row['mitigasi_id']][] = $row['id'];
		}
		$hasil = $jml;
		$hasil['rows'] = $rowsx;

		$hasil['parent'] = $parent;
		$hasil['mitigasi'] = $mit;
		$hasil['minggu'] = $this->crud->combo_select(['id', 'concat(param_string) as minggu'])->combo_where('kelompok', 'minggu')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		return $hasil;
	}

	function get_data_grap($rcsa, $id)
	{
		$histori = $this->db->where('rcsa_id', $rcsa)->where('tipe_log', 2)->where('keterangan like', '%final%')->order_by('tanggal', 'desc')->get(_TBL_VIEW_LOG_APPROVAL)->row_array();
		if ($histori) {
			$tgl_final = ($histori) ? $histori['tanggal'] : 0;
		} else {
			return [];
		}

		$this->db->where('rcsa_detail_id', $id);
		$rows = $this->db->select('rcsa_detail_id as id, aktual, created_at, batas_waktu')
			->get(_TBL_VIEW_RCSA_MITIGASI_DETAIL)->result_array();

		$tmp = [];
		$seratussepuluh = 0;
		$seratus = 0;
		$semilanpuluh = 0;
		$tujuhlima = 0;
		$nol = 0;
		
		foreach ($rows as $row) {
			$deadline = date(
				'Y-m-d',
				strtotime($row['batas_waktu'])
			);
			$tgl_finalx = date('Y-m-d', strtotime($tgl_final));

			$date1 = date_create($tgl_finalx);
			$date2 = date_create($deadline);
			$diffo = date_diff($date2, $date1);
			$nilai_diff = intval($diffo->format("%R%a"));

			$diff = $this->kepatuhan($nilai_diff);
			
			if (intval($row['aktual'])==0) {
				$nol += 1;
			}elseif ($diff == 110) {
				$seratussepuluh += 1;
			} elseif ($diff == 100) {
				$seratus += 1;
			} elseif ($diff == 90) {
				$semilanpuluh += 1;
			} elseif ($diff == 75) {
				$tujuhlima += 1;
			} 

			$row['nilai'] = $diff . "%";
			// $tmp[$row['owner_id']] = $row;
		}

		$hasil['tepat']['total'] = count($rows);
		$hasil['tepat']['110'] = $seratussepuluh;
		$hasil['tepat']['100'] = $seratus;
		$hasil['tepat']['90'] = $semilanpuluh;
		$hasil['tepat']['75'] = $tujuhlima;
		$hasil['tepat']['0'] = $nol;
		$hasil['tepat']['110%'] = (count($rows) > 0) ? number_format(($seratussepuluh / count($rows)) * 100, 2) : 0;
		$hasil['tepat']['100%'] = (count($rows) > 0) ? number_format(($seratus / count($rows)) * 100, 2) : 0;
		$hasil['tepat']['90%'] = (count($rows) > 0) ? number_format(($semilanpuluh / count($rows)) * 100, 2) : 0;
		$hasil['tepat']['75%'] = (count($rows) > 0) ? number_format(($tujuhlima / count($rows)) * 100, 2) : 0;
		$hasil['tepat']['0%'] = (count($rows) > 0) ? number_format(($nol / count($rows)) * 100, 2) : 0;

		return $hasil;
	}

	function kepatuhan($nilai)
	{
		if ($nilai < 0) {
			$hasil = "110";
		} elseif ($nilai <= 30) {
			$hasil = "100";
		} elseif ($nilai > 90) {
			$hasil = "0";
		} elseif ($nilai > 60) {
			$hasil = "75";
		} elseif ($nilai > 30) {
			$hasil = "90";
		}

		return $hasil;
	}

	function get_data_kpi_by_id($dtuser, $rcsa_id, $id)
	{
		$bulan = [1, 12];
		$this->db->where('id', $id);
		$rcsa = $this->db->select('rcsa_id, owner_id')
		->get(_TBL_VIEW_RCSA_DETAIL)->row_array();
		$owner = 0;
		$parent = [];
		$owner_name = ' All Departement ';
		if (intval($rcsa['owner_id']) > 0) {
			$owner = $rcsa['owner_id'];
			$parent = $this->db->where('id', $owner)->get(_TBL_OWNER)->row_array();
			$owner_name = $parent['owner_name'];
			$owner_kode = $parent['owner_code'];
		}
		
		$this->db->where('rcsa_id', $rcsa_id);

		$rows = $this->db
			// ->get_compiled_select(_TBL_VIEW_RCSA_KPI);
			->get(_TBL_VIEW_RCSA_KPI)->result_array();
		// dumps($rows);
		// die();
		$lap2 = [];
		foreach ($rows as $row) {
			$tmp = [];
			$d = $this->db->where('kpi_id', $row['id'])->get(_TBL_VIEW_RCSA_KPI_DETAIL)->result_array();
			$detail = [];
			foreach ($d as $dd) {
				$detail[] = $dd;
			}
			$tmp = $row;
			$tmp['detail'] = $detail;
			$lap2[] = $tmp;
		}
		// dumps($lap2);die();

		$detail = [];
		foreach ($rows as $row) {
			$d = $this->db->where('kpi_id', $row['id'])->get(_TBL_VIEW_RCSA_KPI_DETAIL)->result_array();
			foreach ($d as $dd) {
				$detail[$row['id']]['detail'][$dd['id']] = $dd;
			}
		}

		$x = [];
		// dumps($detail);die();
		foreach ($detail as $key => $row) {
			$owner_id = 0;
			$xx = [];
			foreach ($row['detail'] as $k => $d) {
				$idi = '-1';
				if ($owner_id !== $key) {
					// dumps($d);
					$xx[$k]['name'] = $d['owner_name'];
					$xx[$k]['satuan'] = $d['satuan'];
					$xx[$k]['title'] = trim($d['title']);
					$xx[$k]['indikator'] = $d['indikator'];
					$owner_id = $d['kpi_id'];
					$idi = $key;
				}
				// if (count($rcsa_id)>0) {
				// }else{
				// 	$this->db->where('rcsa_id', '-1');
				// }
				$this->db->where_in('kpi_id', $idi);
				$dd = $this->db->where('minggu_type', 1)
					// ->where('bulan_int>=',$bulan[0]) 
					// ->where('bulan_int<=',$bulan[1])
					// ->where('period_id',$period)
					->where('title like ', "%" . $d['title'])


					// ->get_compiled_select(_TBL_VIEW_RCSA_KPI_DETAIL);
					->get(_TBL_VIEW_RCSA_KPI_DETAIL)->result_array();
				// dumps($idi);
				// die();
				// dumps($dd);

				foreach ($dd as $ke => $va) {
					$xx[$k]['bulan'][$va['bulan_int']] = $va;
				}
				// if ($minggu==0) {
				// } else {
				// 	$xx[$k]['bulan'][$d['bulan_int']]=$d;
				// }


			}
			$x[$key] = $xx;
		}
		// dumps($x);
		// die();
		unset($row);
		$y = [];
		$owner_id = 0;
		foreach ($rows as $key => $row) {
			$idi = 0;

			if ($owner_id !== $row['id']) {
				$y[$row['id']]['name'] = $row['owner_name'];
				$y[$row['id']]['satuan'] = $row['satuan'];
				$y[$row['id']]['title'] = trim($row['title']);
				$y[$row['id']]['indikator'] = $row['indikator'];
				$owner_id = $row['id'];
				// $idi = $d['id'];
			}
			$this->db->where('rcsa_id', $rcsa_id);
			// if (count($rcsa_id) > 0) {
			// } else {
			// 	$this->db->where('rcsa_id', '-1');
			// }
			$dd = $this->db->where('minggu_type', 1)
				// ->where('bulan_int>=',$bulan[0])
				// ->where('bulan_int<=',$bulan[1])
				// ->where('period_id',$period)

				->where('title like ', "%" . $row['title'])
				->get(_TBL_VIEW_RCSA_KPI)->result_array();

			foreach ($dd as $key => $value) {
				$y[$row['id']]['bulan'][$value['bulan_int']] = $value;
			}
			// if ($minggu==0) {

			// }else{
			// 	$y[$row['id']]['bulan'][$row['bulan_int']]=$row;
			// }
		}

		unset($row);
		// dumps($x);
		foreach ($y as $key => &$row) {
			if (array_key_exists($key, $x)) {
				// dumps('xxx');
				$row['detail'] = $x[$key];
			} else {
				$row['detail'] = [];
			}
		}

		// dumps($x);
		// die();
		unset($row);
		$rows = $this->db->where('bk_tipe', 3)->where('rcsa_detail_id', intval($id))->or_group_start()->where('rcsa_detail_id', 0)->group_end()->get(_TBL_VIEW_RCSA_DET_LIKE_INDI)->result_array();
		$ttl = 0;
		foreach ($rows as $row) {
			$nilai = ($row['pencapaian'] / 100) * ($row['pembobotan'] * count($rows));
			$ttl += floatval($nilai);
		}
		// dumps($rows);
		// die();
		$hasil['ttl'] = $ttl;
		$hasil['bulan'] = $bulan;
		$hasil['data'] = $y;
		$hasil['lap2'] = $lap2;
		$hasil['target'] = $parent;
		$hasil['parent'] = $parent;
		$hasil['owner_name'] = $owner_name;
		
		return $hasil;
	}

	function get_data_kompilasi_by_id($id)
	{

		$rcsa = [];
		$rcsa_detail = [];
		$this->db->where('id', $id);

		$rows = $this->db->order_by('tgl_mulai_minggu')->get(_TBL_VIEW_RCSA_DETAIL)->result_array();
		foreach ($rows as $key => $value) {
			if (!in_array($value['id'], $rcsa_detail)) {
				$rcsa_detail[] = $value['id'];
			}

			if (!in_array($value['rcsa_id'], $rcsa)) {
				$rcsa[] = $value['rcsa_id'];
			}
		}


		$rows = $this->db->where_in('rcsa_detail_id', $rcsa_detail)
			// ->get_compiled_select(_TBL_VIEW_RCSA_MITIGASI_PROGRES);
			->get(_TBL_VIEW_RCSA_MITIGASI_PROGRES)->result_array();

		// dumps($rows);
		// die();
		$mit = [];
		$jml['aktif'] = [];
		foreach ($rows as $row) {
			$mit[$row['penyebab_id']][] = $row;
			$jml['aktif'][$row['rcsa_mitigasi_detail_id']][] = $row['id'];
		}
		$hasil = $jml;
		$rows = $this->db->where_in('id', $rcsa)->get(_TBL_VIEW_RCSA)->row_array();
		$parent = $rows;
		$mitigasi = $mit;
		$rows = $this->db->where_in('rcsa_detail_id', $rcsa_detail)->get(_TBL_VIEW_MONITORING)->result_array();
		$rows = $this->convert_owner->set_data($rows)->set_param(['penanggung_jawab' => 'penanggung_jawab_detail_id', 'koordinator' => 'koordinator_detail_id'])->draw();
		$rowsx = $rows;

		$jml['miti'] = [];
		$jml['identi'] = [];
		foreach ($rows as $row) {
			$jml['identi'][$row['rcsa_detail_id']][] = $row['id'];
			$jml['miti'][$row['mitigasi_id']][] = $row['id'];
		}
		$hasil = $jml;
		$hasil['rows'] = $rowsx;

		$hasil['parent'] = $parent;
		$hasil['mitigasi'] = $mit;
		$hasil['minggu'] = $this->crud->combo_select(['id', 'concat(param_string) as minggu'])->combo_where('kelompok', 'minggu')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		return $hasil;
	}

	function get_data_monitoring_profil($id, $rcsa)
	{
		$rows = $this->db->where('rcsa_detail_id', $id)->get(_TBL_VIEW_RCSA_MITIGASI_PROGRES)->result_array();

		$mit = [];
		$jml['aktif'] = [];
		foreach ($rows as $row) {
			$mit[$row['rcsa_mitigasi_detail_id']][] = $row;
			$jml['aktif'][$row['rcsa_mitigasi_detail_id']][] = $row['id'];
		}
		$hasil = $jml;
		$rows = $this->db->where('id', $rcsa)->get(_TBL_VIEW_RCSA)->row_array();
		$parent = $rows;
		$mitigasi = $mit;
		$rows = $this->db->where('rcsa_detail_id', $id)->get(_TBL_VIEW_MONITORING)->result_array();
		$rows = $this->convert_owner->set_data($rows)->set_param(['penanggung_jawab' => 'penanggung_jawab_detail_id', 'koordinator' => 'koordinator_detail_id'])->draw();
		$rowsx = $rows;

		$jml['miti'] = [];
		$jml['identi'] = [];
		foreach ($rows as $row) {
			$jml['identi'][$row['rcsa_detail_id']][] = $row['id'];
			$jml['miti'][$row['mitigasi_id']][] = $row['id'];
		}
		$hasil = $jml;
		$hasil['rows'] = $rowsx;
		$hasil['parent'] = $parent;
		$hasil['mitigasi'] = $mit;
		$hasil['minggu'] = $this->crud->combo_select(['id', 'concat(param_string) as minggu'])->combo_where('kelompok', 'minggu')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		return $hasil;
	}
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */