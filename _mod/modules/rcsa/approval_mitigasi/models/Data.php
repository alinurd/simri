<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MX_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	function simpan_progres($data)
	{
		$id = intval($data['id']);
		$this->crud->crud_table(_TBL_RCSA_MITIGASI_PROGRES);
		$this->crud->crud_field('rcsa_mitigasi_detail_id', $data['aktifitas_mitigasi_id']);
		$this->crud->crud_field('target', $data['target']);
		$this->crud->crud_field('aktual', $data['aktual']);
		$this->crud->crud_field('uraian', $data['uraian']);
		$this->crud->crud_field('kendala', $data['kendala']);
		$this->crud->crud_field('tindak_lanjut', $data['tindak_lanjut']);
		$this->crud->crud_field('batas_waktu_tindak_lanjut', $data['batas_waktu_tindak_lanjut_submit'], 'date');
		$this->crud->crud_field('keterangan', $data['keterangan']);
		// $this->crud->crud_field('lampiran', $data['lampiran']);

		if ($id > 0) {
			$this->crud->crud_type('edit');
			$this->crud->crud_where(['field' => 'id', 'value' => $id]);
			$this->crud->crud_field('updated_by', $this->ion_auth->get_user_name());
		} else {
			$this->crud->crud_type('add');
			$this->crud->crud_field('created_by', $this->ion_auth->get_user_name());
		}
		$this->crud->process_crud();
		if ($id == 0) {
			$id = $this->crud->last_id();
		}

		$rows = $this->db->where('rcsa_mitigasi_detail_id', $data['aktifitas_mitigasi_id'])->order_by('aktual', 'desc')->limit(1)->get(_TBL_VIEW_RCSA_MITIGASI_PROGRES)->row_array();

		if ($rows) {
			$this->crud->crud_table(_TBL_RCSA_MITIGASI_DETAIL);
			$this->crud->crud_field('aktual', $rows['aktual'], 'int');
			$this->crud->crud_field('target', $rows['target'], 'int');
			$this->crud->crud_type('edit');
			$this->crud->crud_where(['field' => 'id', 'value' => $data['aktifitas_mitigasi_id']]);
			$this->crud->process_crud();
		}

		return $id;
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
			$option[$row->id] = $row->param_string . ' (' . date('d-m-Y', strtotime($row->param_date)) . ' s.d ' . date('d-m-Y', strtotime($row->param_date_after)) . ')';
		}

		return $option;
	}

	function get_detail_data()
	{
		$bulan = [1, 12];
		// dumps($this->pos);die();

		if (intval($this->pos['term']) > 0) {
			$rows = $this->db->select('*')->where('id', intval($this->pos['term']))->get(_TBL_COMBO)->row_array();
			$bulan[0] = date('n', strtotime($rows['param_date']));
			$bulan[1] = date('n', strtotime($rows['param_date_after']));
		}
		$period = date('Y');
		if (intval($this->pos['period']) > 0) {
			$period = $this->pos['period'];
		}

		$owner = 0;
		$parent = [];
		$owner_name = ' All Departement ';
		if (intval($this->pos['owner']) > 0) {
			$owner = $this->pos['owner'];
			$parent = $this->db->where('id', $owner)->get(_TBL_OWNER)->row_array();
			$owner_name = $parent['owner_name'];
			$owner_kode = $parent['owner_code'];
		}

		if (isset($this->pos['minggu'])) {
			if (intval($this->pos['minggu']) > 0) {
				// $this->db->where('minggu_id_rcsa', $this->pos['minggu']);
			}
		}
		$this->db->where("`minggu_id`", "`minggu_id_rcsa`", false);

		$rows = $this->db->where('minggu_type', 1)
			->where('bulan_int>=', $bulan[0])
			->where('bulan_int<=', $bulan[1])
			->where('period_id', $period)

			// ->where('kode_dept',$owner_kode)
			->where('owner_id', $owner)
			// ->group_by('satuan')
			// ->get_compiled_select(_TBL_VIEW_RCSA_KPI);
			->get(_TBL_VIEW_RCSA_KPI)->result_array();



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
			$lap2[$row['id']] = $tmp;
		}


		$detail = [];
		foreach ($rows as $row) {
			$d = $this->db->where('kpi_id', $row['id'])->get(_TBL_VIEW_RCSA_KPI_DETAIL)->result_array();
			foreach ($d as $dd) {
				$detail[$row['id']]['detail'][$dd['id']] = $dd;
			}
		}

		$x = [];
		$idi = [];
		foreach ($detail as $key => $row) {
			$xx = [];
			$owner_id = 0;
			foreach ($row['detail'] as $k => $d) {

				if ($owner_id !== $key) {
					// dumps($d);
					$xx[$k]['name'] = $d['owner_name'];
					$xx[$k]['satuan'] = $d['satuan'];
					$xx[$k]['title'] = $d['title'];
					$xx[$k]['indikator'] = $d['indikator'];
					$xx[$k]['id'] = $d['id'];
					$owner_id = $d['kpi_id'];
					if (!in_array($key, $idi)) {
						$idi[] = $key;
					}
				}
			}
			$x[$key] = $xx;
		}
		$this->db->where_in("kpi_id", $idi);

		$dd = $this->db->where('minggu_type', 1)

			->get(_TBL_VIEW_RCSA_KPI_DETAIL)->result_array();

		foreach ($x as $key => $value) {
			foreach ($value as $k => $v) {
				$c = [];
				foreach ($dd as $ke => $va) {
					if (in_array($this->slugify($va['title']), $c)) {
						$x[$key][$k]['bulan'][$va['bulan_int']] = $va;
					} else {
						$x[$key][$k]['bulan'][$va['bulan_int']] = $va;
						$c[] = $this->slugify($va['title']);
					}
				}
			}
		}

		unset($row);
		$y = [];
		$owner_id = 0;
		foreach ($rows as $key => $row) {
			if ($owner_id !== $row['id']) {
				$y[$row['id']]['name'] = $row['owner_name'];
				$y[$row['id']]['satuan'] = $row['satuan'];
				$y[$row['id']]['title'] = $row['title'];
				$y[$row['id']]['indikator'] = $row['indikator'];
				// $y[$row['id']]['rcsa_id']=$row['rcsa_id'];
				// $y[$row['id']]['minggu_id_rcsa']=$row['minggu_id_rcsa'];
				$owner_id = $row['id'];
			}
			$dd = $this->db->where('minggu_type', 1)
				->where('bulan_int>=', $bulan[0])
				->where('bulan_int<=', $bulan[1])
				->where('period_id', $period)
				->where("`minggu_id`", 'minggu_id_rcsa', false)

				->where('title', $row['title'])
				->get(_TBL_VIEW_RCSA_KPI)->result_array();
			// 	->get_compiled_select(_TBL_VIEW_RCSA_KPI);
			// dumps($dd);
			foreach ($dd as $key => $value) {
				$y[$row['id']]['bulan'][$value['bulan_int']] = $value;
			}
		}
		// die();
		unset($row);
		foreach ($y as $key => &$row) {
			if (array_key_exists($key, $x)) {
				// dumps('xxx');
				$row['detail'] = $x[$key];
			} else {
				$row['detail'] = [];
			}
		}

		// dumps($y);die();

		unset($row);

		$hasil['bulan'] = $bulan;
		$hasil['data'] = $y;
		$hasil['lap2'] = $lap2;
		$hasil['parent'] = $parent;
		$hasil['owner_name'] = $owner_name;
		return $hasil;
	}

	public static function slugify($text, string $divider = '-')
	{
		// replace non letter or digits by divider
		$text = preg_replace('~[^\pL\d]+~u', $divider, $text);

		// transliterate
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

		// remove unwanted characters
		$text = preg_replace('~[^-\w]+~', '', $text);

		// trim
		$text = trim($text, $divider);

		// remove duplicate divider
		$text = preg_replace('~-+~', $divider, $text);

		// lowercase
		$text = strtolower($text);

		if (empty($text)) {
			return 'n-a';
		}

		return $text;
	}

	function get_detail_data_o()
	{
		$bulan = [1, 12];
		// dumps($this->pos);die();

		if (intval($this->pos['term']) > 0) {
			$rows = $this->db->select('*')->where('id', intval($this->pos['term']))->get(_TBL_COMBO)->row_array();
			$bulan[0] = date('n', strtotime($rows['param_date']));
			$bulan[1] = date('n', strtotime($rows['param_date_after']));
		}
		$period = date('Y');
		if (intval($this->pos['period']) > 0) {
			$period = $this->pos['period'];
		}

		$owner = 0;
		$parent = [];
		$owner_name = ' All Departement ';
		if (intval($this->pos['owner']) > 0) {
			$owner = $this->pos['owner'];
			$parent = $this->db->where('id', $owner)->get(_TBL_OWNER)->row_array();
			$owner_name = $parent['owner_name'];
			$owner_kode = $parent['owner_code'];
		}

		if (isset($this->pos['minggu'])) {
			if (intval($this->pos['minggu']) > 0) {
				$this->db->where('minggu_id_rcsa', $this->pos['minggu']);
			}
		}

		$rows = $this->db->where('minggu_type', 1)
			->where('bulan_int>=', $bulan[0])
			->where('bulan_int<=', $bulan[1])
			->where('period_id', $period)

			// ->where('kode_dept',$owner_kode)
			->where('owner_id', $owner)
			// ->group_by('satuan')
			// ->get_compiled_select(_TBL_VIEW_RCSA_KPI);
			->get(_TBL_VIEW_RCSA_KPI)->result_array();


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
			$lap2[$row['id']] = $tmp;
		}


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
				if ($owner_id !== $key) {
					// dumps($d);
					$xx[$k]['name'] = $d['owner_name'];
					$xx[$k]['satuan'] = $d['satuan'];
					$xx[$k]['title'] = $d['title'];
					$xx[$k]['indikator'] = $d['indikator'];
					$xx[$k]['id'] = $d['id'];
					$owner_id = $d['kpi_id'];
				}

				$dd = $this->db->where('minggu_type', 1)
					->where('bulan_int>=', $bulan[0])
					->where('bulan_int<=', $bulan[1])
					->where('period_id', $period)
					->where('title', $d['title'])
					->get(_TBL_VIEW_RCSA_KPI_DETAIL)->result_array();

				foreach ($dd as $ke => $va) {
					$xx[$k]['bulan'][$va['bulan_int']] = $va;
				}
			}
			$x[$key] = $xx;
		}
		unset($row);
		$y = [];
		$owner_id = 0;
		foreach ($rows as $key => $row) {
			if ($owner_id !== $row['id']) {
				$y[$row['id']]['name'] = $row['owner_name'];
				$y[$row['id']]['satuan'] = $row['satuan'];
				$y[$row['id']]['title'] = $row['title'];
				$y[$row['id']]['indikator'] = $row['indikator'];
				// $y[$row['id']]['rcsa_id']=$row['rcsa_id'];
				// $y[$row['id']]['minggu_id_rcsa']=$row['minggu_id_rcsa'];
				$owner_id = $row['id'];
			}
			$dd = $this->db->where('minggu_type', 1)
				->where('bulan_int>=', $bulan[0])
				->where('bulan_int<=', $bulan[1])
				->where('period_id', $period)
				->where("`minggu_id`", "`minggu_id_rcsa`", false)

				->where('title', $row['title'])
				->get(_TBL_VIEW_RCSA_KPI)->result_array();
			// 	->get_compiled_select(_TBL_VIEW_RCSA_KPI);
			// dumps($dd);
			foreach ($dd as $key => $value) {
				$y[$row['id']]['bulan'][$value['bulan_int']] = $value;
			}
		}
		// die();
		unset($row);
		foreach ($y as $key => &$row) {
			if (array_key_exists($key, $x)) {
				// dumps('xxx');
				$row['detail'] = $x[$key];
			} else {
				$row['detail'] = [];
			}
		}

		// dumps($y);die();

		unset($row);

		$hasil['bulan'] = $bulan;
		$hasil['data'] = $y;
		$hasil['lap2'] = $lap2;
		$hasil['parent'] = $parent;
		$hasil['owner_name'] = $owner_name;
		return $hasil;
	}

	function get_detail_datax()
	{
		$bulan = [1, 12];

		if (intval($this->pos['term']) > 0) {
			$rows = $this->db->select('*')->where('id', intval($this->pos['term']))->get(_TBL_COMBO)->row_array();
			$bulan[0] = date('m', strtotime($rows['param_date']));
			$bulan[1] = date('m', strtotime($rows['param_date_after']));
		}
		$period = date('Y');
		if (intval($this->pos['period']) > 0) {
			$period = $this->pos['period'];
		}

		$owner = 0;
		$parent = [];
		$owner_name = ' All Departement ';
		if (intval($this->pos['owner']) > 0) {
			$owner = $this->pos['owner'];
			$parent = $this->db->where('id', $owner)->get(_TBL_OWNER)->row_array();
			$owner_name = $parent['owner_name'];
			$owner_kode = $parent['owner_code'];
		}

		// $rows = $this->db->where('minggu_type',1)->where('bulan_int>=',$bulan[0])->where('bulan_int<=',$bulan[1])->where('period_id',$period)->where('owner_id',$owner)->get(_TBL_VIEW_RCSA_KPI)->result_array();
		$rows = $this->db->where('minggu_type', 1)
			// ->where('bulan_int>=',$bulan[0])
			// ->where('bulan_int<=',$bulan[1])
			// ->where('period_id',$period)

			->where('kode_dept', $owner_kode)
			->where('owner_id', $owner)
			->get(_TBL_VIEW_RCSA_KPI)->result_array();

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

		// dumps($rows);die();
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
				if ($owner_id !== $key) {
					// dumps($d);
					$xx[$k]['name'] = $d['owner_name'];
					$xx[$k]['satuan'] = $d['satuan'];
					$xx[$k]['title'] = $d['title'];
					$xx[$k]['indikator'] = $d['indikator'];
					$owner_id = $d['kpi_id'];
				}
				$xx[$k]['bulan'][$d['bulan_int']] = $d;
			}
			$x[$key] = $xx;
		}
		unset($row);
		// dumps($x);die();
		$y = [];
		$owner_id = 0;
		foreach ($rows as $key => $row) {
			if ($owner_id !== $row['id']) {
				$y[$row['id']]['name'] = $row['owner_name'];
				$y[$row['id']]['satuan'] = $row['satuan'];
				$y[$row['id']]['title'] = $row['title'];
				$y[$row['id']]['indikator'] = $row['indikator'];
				$owner_id = $row['id'];
			}
			$y[$row['id']]['bulan'][$row['bulan_int']] = $row;
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

		// dumps($y);
		unset($row);

		$hasil['bulan'] = $bulan;
		$hasil['data'] = $y;
		$hasil['lap2'] = $lap2;
		$hasil['parent'] = $parent;
		$hasil['owner_name'] = $owner_name;
		return $hasil;
	}

	function get_email_creator($id)
	{
		$risk = $this->db->select('created_by, updated_by')->from('il_rcsa')->where('id', $id)->get()->row();
		if ($risk->created_by) {
			$this->db->where('username', $risk->created_by);
		} else {
			$this->db->where('username', $risk->updated_by);
		}

		return  $this->db->select('email, real_name')->from('il_users')->get()->row();
	}
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */