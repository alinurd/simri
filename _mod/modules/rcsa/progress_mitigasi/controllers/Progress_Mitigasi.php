<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Progress_Mitigasi extends MY_Controller
{
	protected $approval = [];
	protected $approval_minggu = [];
	var $super_user = 0;

	public function __construct()
	{
		parent::__construct();
		$this->super_user = $this->_data_user_['is_admin'];

		$this->load->language('risk_context');
		$this->load->language('monitoring_mitigasi');
	}

	function init($action = 'list')
	{
		$this->type_ass_no = $this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'ass-type')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$this->period = $this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'period')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$this->alat = $this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'metode-alat')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$this->stakeholder = $this->crud->combo_select(['id', 'officer_name'])->combo_where('active', 1)->combo_tbl(_TBL_VIEW_OFFICER)->get_combo()->result_combo();
		$this->term = $this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'term')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$this->cboDept = $this->get_combo_parent_dept();
		$this->cboStack = $this->get_combo_parent_dept(false);


		$this->set_Tbl_Master(_TBL_VIEW_RCSA_DETAIL);

		$this->addField(['field' => 'id', 'type' => 'int', 'show' => false, 'size' => 4]);
		$this->addField(['field' => 'type_ass_id', 'input' => 'combo', 'required' => true, 'search' => true, 'values' => $this->type_ass_no, 'size' => 50]);
		$this->addField(['field' => 'owner_id', 'title' => 'Department', 'type' => 'int', 'required' => true, 'input' => 'combo', 'search' => true, 'values' => $this->cboDept]);
		$this->addField(['field' => 'risiko_dept', 'input' => 'multitext', 'search' => true, 'size' => 500]);
		$this->addField(['field' => 'period_id', 'title' => 'Periode', 'type' => 'int', 'required' => true, 'input' => 'combo', 'search' => true, 'values' => $this->period]);

		$this->addField(['field' => 'level_color', 'show' => false]);
		$this->addField(['field' => 'like_code', 'show' => false]);
		$this->addField(['field' => 'impact_code', 'show' => false]);
		$this->addField(['field' => 'color', 'show' => false]);
		$this->addField(['field' => 'color_text', 'show' => false]);


		$this->addField(['field' => 'level_color_target', 'show' => false]);
		$this->addField(['field' => 'like_code_target', 'show' => false]);
		$this->addField(['field' => 'impact_code_target', 'show' => false]);
		$this->addField(['field' => 'color_target', 'show' => false]);
		$this->addField(['field' => 'color_text_target ', 'show' => false]);


		$this->addField(['field' => 'kode_dept', 'show' => false]);
		$this->addField(['field' => 'owner_name', 'show' => false]);
		$this->addField(['field' => 'rcsa_id', 'show' => false]);
		$this->addField(['field' => 'inherent', 'type' => 'free', 'show' => false]);
		$this->addField(['field' => 'target', 'type' => 'free', 'show' => false]);
		foreach (range(1, 12) as $key => $value) {
			$this->addField(['field' => 'monitoring' . $value, 'type' => 'free', 'show' => false]);
		}
		$this->set_Field_Primary($this->tbl_master, 'id', true);

		$this->set_Sort_Table($this->tbl_master, 'created_at', 'desc');
		$this->set_Where_Table(['field' => 'status_final', 'value' => 1, 'op' => '>=']);

		// $this->set_Table_List($this->tbl_master, 'id', '<input type="checkbox" class="form-check-input pointer" name="chk_list_parent" id="chk_list_parent"  style="padding:0;margin:0;">', '0%', 'left', 'no-sort');

		$this->set_Table_List($this->tbl_master, 'owner_name', 'Owner Name', 10);
		$this->set_Table_List($this->tbl_master, 'period_id','thn', 10);
		$this->set_Table_List($this->tbl_master, 'risiko_dept', 'Risiko Departemen', 20);
		$this->set_Table_List($this->tbl_master, 'inherent', '', 3, '', 'no-sort');
		$this->set_Table_List($this->tbl_master, 'target', '', 3, '', 'no-sort');
		$bulan = [
			1 => 'Jan',
			2 => 'Feb',
			3 => 'Mar',
			4 => 'Apr',
			5 => 'Mei',
			6 => 'Jun',
			7 => 'Jul',
			8 => 'Agu',
			9 => 'Sep',
			10 => 'Okt',
			11 => 'Nov',
			12 => 'Des',
		];

		foreach (range(1, 12) as $key => $value) {
			$datetime = DateTime::createFromFormat('m', $value);
			$nama =  $bulan[$value];
			$this->set_Table_List($this->tbl_master, 'monitoring' . $value, $nama, 5, 'center', 'no-sort');
		}

		$this->_set_Where_Owner();

		$this->set_Save_Table(_TBL_RCSA);
		$this->setPrivilege('delete', false);
		$this->setPrivilege('update', false);
		$this->setPrivilege('insert', false);
		$this->set_Close_Setting();
		// if (_MODE_=='add') {
		// 	$content_title = 'Penambahan Konteks Risiko';
		// }elseif(_MODE_=='edit'){
		// 	$content_title = 'Perubahan Konteks Risiko';
		// }elseif(_MODE_=='identifikasi-risiko'){
		// 	$content_title = 'Asesmen Risiko';
		// }else{
		$content_title = 'Daftar Mitigasi';
		// }
		$configuration = [
			'monitoring'	=> true,
			'type_action_button' => FALSE,
			'show_title_header' => FALSE,
		   ];
		   return [
			'configuration' => $configuration,
		   ];
	// 	$configuration = [
	// 		'monitoring'	=> true,
	// 		'show_title_header' => false,
	// 		'show_action_button' => FALSE,
	// 		'show_column_action' => FALSE,
	// 		'type_action_button' => FALSE,
	// 		'content_title' => $content_title
	// 	];
	// 	return [
	// 		'configuration'	=> $configuration
	// 	];
	}

	public function listBox_INHERENT($field, $rows, $value)
	{
		$result = '<span class="btn" style="padding:4px 8px;width:100%;background-color:' . $rows['color'] . ';color:' . $rows['color_text'] . ';">[' . $rows['like_code'] . ' x ' . $rows['impact_code'] . '] <br>' . $rows['level_color'] . '</span>';
		return $result;
	}

	public function listBox_TARGET($field, $rows, $value)
	{
		$result = '<span class="btn" style="padding:4px 8px;width:100%;background-color:' . $rows['color_target'] . ';color:' . $rows['color_text_target'] . ';">[' . $rows['like_code_target'] . ' x ' . $rows['impact_code_target'] . '] <br>' . $rows['level_color_target'] . '</span>';
		return $result;
	}

	public function listBox_MONITORING1($field, $rows, $value)
	{
		return $this->getListMonitoring("01", $rows, $value);
	}
	public function listBox_MONITORING2($field, $rows, $value)
	{
		return $this->getListMonitoring("02", $rows, $value);
	}
	public function listBox_MONITORING3($field, $rows, $value)
	{
		return $this->getListMonitoring("03", $rows, $value);
	}
	public function listBox_MONITORING4($field, $rows, $value)
	{
		return $this->getListMonitoring("04", $rows, $value);
	}
	public function listBox_MONITORING5($field, $rows, $value)
	{
		return $this->getListMonitoring("05", $rows, $value);
	}
	public function listBox_MONITORING6($field, $rows, $value)
	{
		return $this->getListMonitoring("06", $rows, $value);
	}
	public function listBox_MONITORING7($field, $rows, $value)
	{
		return $this->getListMonitoring("07", $rows, $value);
	}
	public function listBox_MONITORING8($field, $rows, $value)
	{
		return $this->getListMonitoring("08", $rows, $value);
	}
	public function listBox_MONITORING9($field, $rows, $value)
	{
		return $this->getListMonitoring("09", $rows, $value);
	}
	public function listBox_MONITORING10($field, $rows, $value)
	{
		return $this->getListMonitoring("10", $rows, $value);
	}
	public function listBox_MONITORING11($field, $rows, $value)
	{
		return $this->getListMonitoring("11", $rows, $value);
	}
	public function listBox_MONITORING12($field, $rows, $value)
	{
		return $this->getListMonitoring("12", $rows, $value);
	}






	// function content($ty = 'detail')
	// {
	// 	$data['x']=[];
	// 	$data['parent'] = $this->db
	// 		->get(_TBL_VIEW_RCSA)
	// 		->result_array();
	// $content = $this->load->view('sebaran', $data, true);

	// 	return $content;
	// }
	public function MASTER_DATA_LIST($arrId, $rows)
	{

		$arr_approval = $this->db->where('pid', 249)->order_by('urut')->get(_TBL_VIEW_APPROVAL)->result_array();
		$this->approval = [];
		foreach ($arr_approval as $row) {
			$this->approval[$row['urut']] = $row;
		}
		if (count($arrId) > 0) {
			$arr_approval = $this->db->where_in('rcsa_id', $arrId)->order_by('minggu_id')->get(_TBL_RCSA_APPROVAL_MITIGASI)->result_array();
		} else {
			$arr_approval = $this->db->where('rcsa_id', 0)->order_by('minggu_id')->get(_TBL_RCSA_APPROVAL_MITIGASI)->result_array();
		}


		$this->approval_minggu = [];
		foreach ($arr_approval as $row) {
			$this->approval_minggu[$row['rcsa_id']][$row['minggu_id']] = $row;
		}

		if (count($arrId) > 0) {
			$rows = $this->db->select('rcsa_id, count(rcsa_id) as jml')->where_in('rcsa_id', $arrId)->order_by('rcsa_id')->group_by('rcsa_id')->get(_TBL_VIEW_RCSA_KPI)->result_array();
		} else {
			$rows = $this->db->select('rcsa_id, count(rcsa_id) as jml')->where('rcsa_id', 0)->order_by('rcsa_id')->group_by('rcsa_id')->get(_TBL_VIEW_RCSA_KPI)->result_array();
		}

		$this->kpi = [];
		foreach ($rows as $row) {
			$this->kpi[$row['rcsa_id']] = $row['jml'];
		}
	}

	function listBox_TERM_ID($field, $rows, $value)
	{
		$cbominggu = $this->data->get_data_minggu($value);
		$minggu = ($rows['minggu_id']) ? $cbominggu[$rows['minggu_id']] : '';
		$a = $this->term[$value] . ' - ' . $minggu;
		return $a;
	}

	function listBox_id($field, $rows, $value)
	{
		$check = $this->data->checklist();
		// $select = (in_array($rows['id'], $check))?'checked':'';
		$a = '<div class="text-center"  style="padding:0px 20px 20px 0px;"><input type="checkbox" class="form-check-input pointer text-center" name="chk_list[]" style="padding:0;margin:0;" value="' . $rows['id'] . '"/></div>';
		return $a;
	}

	function listBox_STATUS_ID_MITIGASI($field, $rows, $value)
	{
		$revisi = intval($rows['status_revisi_mitigasi']);
		$urut = intval($rows['status_id_mitigasi']);
		$final = intval($rows['status_final_mitigasi']);
		// $hasil = '<a href="'.base_url(_MODULE_NAME_.'/propose/'.$rows['id']).'"a class="propose btn  pointer" style="width:100% !important;padding:5px;background-color:'.$this->_preference_['warna_propose'].';color:#ffffff;" data-id="' . $rows['id'].'"> '._l('msg_notif_propose').' </a>';
		$hasil = '<a href="#"a class="propose btn disabled" style="width:100% !important;padding:5px;background-color:' . $this->_preference_['warna_propose'] . ';color:#ffffff;" data-id="' . $rows['id'] . '"> ' . _l('msg_notif_propose') . ' </a>';

		if ($final) {
			// if ($final && $rows['tgl_selesai_term'] == $rows['tgl_akhir_mitigasi']){
			$hasil = '<div class="label text-center" style="background-color:' . $this->_preference_['warna_approved'] . ';color:#ffffff;width:100%;padding:10px 5px; display:block;"> ' . _l('msg_notif_approved') . '</div>';
			$hasil = $hasil;
		} elseif (array_key_exists($urut, $this->approval)) {
			$ket = ' - ';
			if (!empty($this->approval[$urut]['model'])) {
				$ket = $this->approval[$urut]['model'];
			}
			$hasil = '<div  class="label text-center" style="background-color:' . $this->approval[$urut]['warna'] . ';color:#ffffff;width:100%;padding:10px 5px; display:block;">' . _l('msg_notif_need_approved') . ' <br/>' . $this->approval[$urut]['model'] . '</div><br/>';
		}

		return $hasil;
	}

	function listBox_REGISTER($field, $rows, $value)
	{
		$o = '<i class="icon-menu6 pointer text-primary risk-monitoring" title=" View Risk Register " data-id="' . $rows['id'] . '"></i>';

		return $o;
	}

	function listBox_TGL_PROPOSE_MITIGASI($field, $rows, $value)
	{

		if ($rows['status_id_mitigasi'] == 0) {
			$value = '';
		}
		return $value;
	}

	function inputBox_TERM_ID($mode, $field, $rows, $value)
	{
		if ($mode == 'edit') {
			$id = 0;
			if (isset($rows['period_id']))
				$id = $rows['period_id'];
			$field['values'] = $this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'term')->combo_where('pid', $id)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		}
		$content = $this->set_box_input($field, $value);
		return $content;
	}

	function inputBox_MINGGU_ID($mode, $field, $rows, $value)
	{
		if ($mode == 'edit') {
			$id = 0;
			if (isset($rows['term_id']))
				$id = $rows['term_id'];

			$field['values'] = $this->data->get_data_minggu($id);

			// $field['values'] = $this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'minggu')->combo_where('pid', $id)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		}
		// dumps($field['values']);
		// die();
		$content = $this->set_box_input($field, $value);
		return $content;
	}

	function progress()
	{
		if ($this->input->is_ajax_request()) {
			$id = intval($this->input->post('id'));
		} else {
			$id = intval($this->uri->segment(3));
		}
		$data['parent'] = $this->db->where('id', $id)->get(_TBL_VIEW_RCSA)->row_array();
		$cbominggu = $this->data->get_data_minggu($data['parent']['term_id']);
		$minggu = ($data['parent']['minggu_id']) ? $cbominggu[$data['parent']['minggu_id']] : '';

		$data['parent']['bulan'] = $this->term[$data['parent']['term_id']] . ' - ' . $minggu;
		$data['info_parent'] = $this->load->view('info-parent', $data, true);

		$rows = $this->db->where('rcsa_id', $id)->get(_TBL_VIEW_RCSA_MITIGASI_DETAIL)->result_array();
		$data['detail'] = $rows;
		$hasil = $this->load->view('mitigasi', $data, true);
		$configuration = [
			'show_title_header' => false,
			'show_action_button' => false,
			'content_title' => 'Progres Aktivitas Mitigasi'
		];

		$this->default_display(['content' => $hasil, 'configuration' => $configuration]);
	}

	function update()
	{
		$id = intval($this->uri->segment(3));
		$month = intval($this->uri->segment(4));
		$info['id'] = $id;
		$info['monthNow'] = date('m');
		$data = false;

		$rcsa_detail = $this->db->where('id', $id)->get(_TBL_VIEW_RCSA_DETAIL)->row_array();
		
		$info['detail'] = $rcsa_detail;
		$info['parent'] = $this->db->where('id', $rcsa_detail['rcsa_id'])->get(_TBL_VIEW_RCSA)->row_array();
		$mit = $this->db->where('rcsa_detail_id', $id)->get("il_view_rcsa_mitigasi_detail")->result_array();
		$penyebab_grouped = [];
		foreach ($mit as $m) {
			$penyebab_grouped[$m['penyebab_id']][] = $m;
		}

		$info['penyebab_grouped'] = $penyebab_grouped;

		$info['month'] = $this->db->where('period_id', $info['parent']['period_id'])->where('bulan_int', $month)->get("il_view_minggu")->row_array();
		$info['monthParam'] = $month;

		$impact       = $this->crud->combo_select(['id', 'concat(code,\' - \',level) as x'])->combo_where('active', 1)->combo_where('category', 'impact')->combo_tbl(_TBL_LEVEL)->get_combo()->result_combo();
		// $aspek     = 0;
		$like = $this->crud->combo_select(['id', 'concat(code,\' - \',level) as x'])->combo_where('active', 1)->combo_where('category', 'likelihood')->combo_tbl(_TBL_LEVEL)->get_combo()->result_combo();

		$csslevel          = '';
		$csslevel_inherent = '';
		$residual = $this->db->where('rcsa_detail_id', $id)->where('month', $month)->get("il_update_residual")->row_array();
		if ($residual) {
			$csslevel  = 'background-color:' . $residual['color'] . ';color:' . $residual['color_text'] . ';';
		}

		$info['level'] = form_input('mit_level_residual_text', ($residual) ?  $residual['level_color'] : '', 'class="form-control text-center" id="mit_level_residual_text" readonly="readonly" style="width:30%;' . $csslevel . '"')
			. form_hidden(['level_color' => ($residual) ? $residual['level_color'] : ''])
			. form_hidden(['color' => ($residual) ? $residual['color'] : ''])
			. form_hidden(['color_text' => ($residual) ? $residual['color_text'] : ''])
			. form_hidden(['score' => ($residual) ? $residual['score'] : 0])
			. form_hidden(['month' => ($month) ? $month : 0])
			. form_hidden(['id_detail' => ($id) ? $id : 0])
			. form_hidden(['id_edit' => ($residual) ? $residual['id'] : 0]);

		$info['dampak'] =  form_dropdown('mit_like_id', $like, ($residual) ? $residual['like'] : $rcsa_detail['like_code'], 'id="mit_like_id" class="form-control select" ');

		$info['impact'] =  form_dropdown('mit_impact_id', $impact, ($residual) ? $residual['impact'] : $rcsa_detail['impact_code'], 'id="mit_impact_id" class="form-control select" ');

		$info['update'] = $this->load->view('progres', $info, true);
		$info['list_progres'] = $this->load->view('list-progres', $info, true);
		$info['informasi'] = $this->load->view('informasi', $info, true);
		$info['mode']            = 0; //'Mode : Insert data';
		$info['mode_text']       = _l('fld_mode_add'); //'Mode : Insert data';
		$info['rcsa_detail']     = ['sts_save_evaluasi' => 0];
		$info['content'] = $this->edit_identifikasi($rcsa_detail['rcsa_id'], $rcsa_detail['id']);

		$info['mit'] = $this->data->getMonthlyMonitoring($id, $month);
		$info['idenContent'] = $this->identifikasi_content($rcsa_detail, $info['parent']);

		$content = $this->load->view('update-monitoring', $info, true);
		$configuration = [
			'show_title_header' => false,
			'show_action_button' => false,
			'content_title' => 'Update Progres Aktivitas Mitigasi'
		];
		$this->default_display(['content' => $content, 'configuration' => $configuration]);

		return $content;
	}
	function update_progres($id_edit = 0, $id = 0)
	{

		$awal = false;
		if (!$id) {
			$awal = true;
			$id = intval($this->uri->segment(3));
			$id_edit = 0;
		}

		$dp = $this->db->where('id', $id_edit)->get(_TBL_VIEW_RCSA_MITIGASI_PROGRES)->row_array();
		$am = $this->db->where('id', $id)->get(_TBL_VIEW_RCSA_MITIGASI_DETAIL)->row_array();
		if ($am) {
			$data['detail_progres'] = $this->db->where('rcsa_mitigasi_detail_id', $id)->get(_TBL_VIEW_RCSA_MITIGASI_PROGRES)->result_array();
			$data['aktifitas_mitigas'] = $am;
			$mit = $this->db->where('id', $data['aktifitas_mitigas']['rcsa_mitigasi_id'])->get(_TBL_VIEW_RCSA_MITIGASI)->row_array();
			$mit = $this->convert_owner->set_data($mit, false)->set_param(['penanggung_jawab' => 'penanggung_jawab_id', 'koordinator' => 'koordinator_id'])->draw();
			$rcsa_detail = $this->db->where('id', $mit['rcsa_detail_id'])->get(_TBL_VIEW_RCSA_DETAIL)->row_array();
			$data['parent'] = $this->db->where('id', $rcsa_detail['rcsa_id'])->get(_TBL_VIEW_RCSA)->row_array();
			$trg = 1;
			$disabled = '';
			if ($am['batas_waktu_detail'] < date('Y-m-d')) {
				$trg = 100;
				// $disabled=' disabled="disabled" ';
			}
			$data['minggu'] = $this->crud->combo_select(['id', 'concat(param_string, " ", YEAR(param_date)) as minggu'])->combo_where('kelompok', 'minggu')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();


			// $curMing = $this->_data_user_['term']['period_id'];

			if ($dp) {
				$cekbul = $this->db->where('id', $dp['minggu_id'])->get(_TBL_COMBO)->row_array();

				// $minggupil=$this->crud->combo_select(['id', 'concat(param_string, \' ( \', param_date, \' s.d \', param_date_after, \' ) \') as minggu'])->combo_where('kelompok', 'minggu')->combo_where('active', 1)->combo_where('pid',$cekbul['pid'])->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();

			} else {
				$cekbul['pid'] = _TAHUN_ID_;
				// $minggupil=$this->crud->combo_select(['id', 'concat(param_string, \' ( \', param_date, \' s.d \', param_date_after, \' ) \') as minggu'])->combo_where('kelompok', 'minggu')->combo_where('active', 1)->combo_where('pid',_TAHUN_ID_)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
			}

			$minggupil[""] = '--select Bulan--';
			$minggupil = $this->data->get_data_minggu($data['parent']['term_id']);
			// Dumps($minggupil);
			// die();
			$periodpil = $this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'period')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
			$periodpil[""] = '--select Periode--';


			$minggu = form_dropdown('tahun_pil', $periodpil, $cekbul['pid'], 'class="form-control select" style="width:100%;"  id="tahun" disabled');
			$minggu .= form_hidden('tahun', $cekbul['pid']);
			$minggu .= form_dropdown('minggu', $minggupil, ($dp) ? $dp['minggu_id'] : _MINGGU_ID_, 'class="form-control select" style="width:100%;"  id="minggu"');
			$minggu .= '<script>
				$(".select").select2({
					allowClear: false
				});
				$("#tahun").change(function () {
					var parent = $(this).parent();
					var nilai = $(this).val();
					var data = {
						"id": nilai
					};
					var target_combo = $("#minggu");
					var url = "ajax/get-minggu-by-tahun";
					_ajax_("post", parent, data, target_combo, url);
				})
			</script>';


			$aktual = '<div class="input-group" style="width:19% !important;"> <button type="button" onclick="this.parentNode.querySelector(\'[type=number]\').stepDown();"> - </button>';
			$aktual .= form_input(['type' => 'number', 'name' => 'aktual'], ($dp) ? $dp['aktual'] : '1', " class='form-control touchspin-postfix text-center' max='100' min='1' step='1' id='aktual' ");
			$aktual .= '<button type="button" onclick="this.parentNode.querySelector(\'[type=number]\').stepUp();"> + </button> <div class="form-control-feedback text-primary form-control-feedback-lg pointer" style="right:-25%;"> % </div></div>';

			$target = '<div class="input-group" style="width:19% !important;"> <button type="button" onclick="this.parentNode.querySelector(\'[type=number]\').stepDown();"> - </button>';
			$target .= form_input(['type' => 'number', 'name' => 'target'], ($dp) ? $dp['target'] : $trg, " '.$disabled.' class='form-control touchspin-postfix text-center' max='100' min='1' step='1' id='target' ");
			$target .= '<button type="button" onclick="this.parentNode.querySelector(\'[type=number]\').stepUp();"> + </button> <div class="form-control-feedback text-primary form-control-feedback-lg pointer" style="right:-25%;"> % </div></div>';

			$data['progres'][] = ['title' => "Bulan Progress", 'mandatori' => false, 'isi' => $minggu];
			$data['progres'][] = ['title' => _l('fld_target'), 'help' => _h('help_target'), 'mandatori' => true, 'isi' => $target];
			$data['progres'][] = ['title' => _l('fld_aktual'), 'help' => _h('help_aktual'), 'mandatori' => true, 'isi' => $aktual];
			$data['progres'][] = ['title' => _l('fld_uraian'), 'help_popup' => false, 'help' => _h('help_uraian', '', true, false), 'mandatori' => true, 'isi' => form_textarea('uraian', ($dp) ? $dp['uraian'] : '', "required id='uraian' maxlength='500' size='500' class='form-control' style='overflow: hidden; width: 100% !important; height: 100px;' onblur='_maxLength(this , \"id_sisa_1\")' onkeyup='_maxLength(this , \"id_sisa_1\")' data-role='tagsinput'", true, ['size' => 1000, 'isi' => 0, 'no' => 1])];
			$data['progres'][] = ['title' => _l('fld_kendala'), 'help' => _h('help_kendala'), 'isi' => form_textarea('kendala', ($dp) ? $dp['kendala'] : '', "required id='kendala' maxlength='500' size='500' class='form-control' style='overflow: hidden; width: 100% !important; height: 100px;' onblur='_maxLength(this , \"id_sisa_2\")' onkeyup='_maxLength(this , \"id_sisa_2\")' data-role='tagsinput'", true, ['size' => 1000, 'isi' => 0, 'no' => 2])];
			$data['progres'][] = ['title' => _l('fld_tindak_lanjut'), 'help' => _h('help_tindak_lanjut'), 'isi' => form_textarea('tindak_lanjut', ($dp) ? $dp['tindak_lanjut'] : '', " id='tindak_lanjut' maxlength='500' size='500' class='form-control' style='overflow: hidden; width: 100% !important; height: 100px;' onblur='_maxLength(this , \"id_sisa_3\")' onkeyup='_maxLength(this , \"id_sisa_3\")' data-role='tagsinput'", true, ['size' => 1000, 'isi' => 0, 'no' => 3])];
			$data['progres'][] = ['title' => _l('fld_due_date'), 'help' => _h('help_due_date'), 'isi' => form_input('batas_waktu_tindak_lanjut', ($dp) ? $dp['batas_waktu_tindak_lanjut'] : '', 'class="form-control pickadate" id="batas_waktu_tindak_lanjut" style="width:100%;"')];

			$data['progres'][] = ['title' => _l('fld_keterangan'), 'help' => _h('help_keterangan'), 'isi' => form_textarea('keterangan', ($dp) ? $dp['keterangan'] : '', " id='keterangan' maxlength='500' size='500' class='form-control' style='overflow: hidden; width: 100% !important; height: 100px;' onblur='_maxLength(this , \"id_sisa_4\")' onkeyup='_maxLength(this , \"id_sisa_4\")' data-role='tagsinput'", true, ['size' => 1000, 'isi' => 0, 'no' => 4])];
			$data['progres'][] = ['title' => _l('fld_lampiran'), 'help' => _h('help_lampiran'), 'isi' => form_upload('lampiran')];
			$data['progres'][] = ['title' => '', 'help' => '', 'isi' => form_hidden(['aktifitas_mitigasi_id' => $id, 'id' => $id_edit])];

			$data['info_1'][] = ['title' => _l('fld_risiko_dept'), 'isi' => $rcsa_detail['risiko_dept']];
			$data['info_1'][] = ['title' => _l('fld_risiko_inherent'), 'isi' => $rcsa_detail['level_color']];
			$data['info_1'][] = ['title' => _l('fld_efek_kontrol'), 'isi' => $rcsa_detail['efek_kontrol_text']];
			$data['info_1'][] = ['title' => _l('fld_nama_control'), 'isi' => $rcsa_detail['nama_kontrol']];
			$data['info_1'][] = ['title' => _l('fld_level_risiko_residual'), 'isi' => $rcsa_detail['level_color_residual']];
			$data['info_1'][] = ['title' => _l('fld_treatment'), 'isi' => $rcsa_detail['treatment']];

			$data['info_2'][] = ['title' => _l('fld_mitigasi'), 'isi' => $mit['mitigasi']];
			$data['info_2'][] = ['title' => _l('fld_biaya'), 'isi' => number_format($mit['biaya'])];
			$data['info_2'][] = ['title' => _l('fld_pic'), 'isi' => $mit['penanggung_jawab']];
			$data['info_2'][] = ['title' => _l('fld_koordinator'), 'isi' => $mit['koordinator']];
			$data['info_2'][] = ['title' => _l('fld_due_date'), 'isi' => date('d-M-Y', strtotime($mit['batas_waktu']))];

			$data['informasi'] = $this->load->view('informasi', $data, true);
			$data['list_progres'] = $this->load->view('list-progres', $data, true);
			$data['update'] = $this->load->view('progres', $data, true);
			$data['id'] = $am['rcsa_id'];
			$hasil = $this->load->view('monitoring', $data, true);

			$configuration = [
				'show_title_header' => false,
				'show_action_button' => false,
				'content_title' => 'Update Progres Aktivitas Mitigasi'
			];

			if ($awal) {
				$this->default_display(['content' => $hasil, 'configuration' => $configuration]);
			} else {
				return $data;
			}
		} else {
			header('location:' . base_url(_MODULE_NAME_));
		}
	}

	function add_progres()
	{
		$id = intval($this->input->post('id'));
		$mitigasi_id = intval($this->input->post('mitigasi_id'));
		$hasil = $this->update_progres($id, $mitigasi_id);
		header('Content-type: application/json');
		echo json_encode(['combo' => $hasil['update']]);
	}

	function simpan_progres()
	{
		$post = $this->input->post();
		$id_edit = $this->data->simpan_progres($post);
		$result['sts'] = $id_edit;

		echo json_encode($result);
	}

	function hapus_progres()
	{
		$id = intval($this->input->post('id'));
		$mitigasi_id = intval($this->input->post('mitigasi_id'));
		$this->crud->crud_table(_TBL_RCSA_MITIGASI_PROGRES);
		$this->crud->crud_type('delete');
		$this->crud->crud_where(['field' => 'id', 'value' => $id]);
		$this->crud->process_crud();
		$hasil = $this->update_progres(0, $mitigasi_id);
		$result['list_progres'] = $hasil['list_progres'];
		$result['combo'] = 'Sukses';
		header('Content-type: application/json');
		echo json_encode($result);
	}

	function data_alur($param = [])
	{
		$rows = $this->db->where('id', $param['owner_no'])->get(_TBL_VIEW_OWNER_PARENT)->row_array();
		$owner = [];
		$officer = [];
		if ($rows) {
			if (!empty($rows['level_approval'])) {
				$level = explode(',', $rows['level_approval']);
				foreach ($level as $x) {
					$owner[$x] = ['id' => $rows['id'], 'name' => $rows['parent_name']];
					$officer[$x] = $rows['id'];
				}
			}
			if (!empty($rows['level_approval_1'])) {
				$level = explode(',', $rows['level_approval_1']);
				foreach ($level as $x) {
					$owner[$x] = ['id' => $rows['lv_1_id'], 'name' => $rows['lv_1_name']];
					$officer[$x] = $rows['lv_1_id'];
				}
			}
			if (!empty($rows['level_approval_2'])) {
				$level = explode(',', $rows['level_approval_2']);
				foreach ($level as $x) {
					$owner[$x] = ['id' => $rows['lv_2_id'], 'name' => $rows['lv_2_name']];
					$officer[$x] = $rows['lv_2_id'];
				}
			}
			if (!empty($rows['level_approval_3'])) {
				$level = explode(',', $rows['level_approval_3']);
				foreach ($level as $x) {
					$owner[$x] = ['id' => $rows['lv_3_id'], 'name' => $rows['lv_3_name']];
					$officer[$x] = $rows['lv_3_id'];
				}
			}
		}
		$staft_tahu = [];
		$staft_setuju = [];
		$staft_valid = [];
		if ($officer) {
			$rows = $this->db->where_in('owner_no', $officer)->group_start()->where('sts_mengetahui', 1)->or_where('sts_menyetujui', 1)->or_where('sts_menvalidasi', 1)->group_end()->get(_TBL_VIEW_OFFICER)->result_array();
			foreach ($rows as $row) {
				if ($row['sts_mengetahui'] == 1) {
					$staft_tahu[$row['owner_no']]['name'][] = $row['officer_name'];
					$staft_tahu[$row['owner_no']]['id'][] = $row['id'];
					$staft_tahu[$row['owner_no']]['email'][] = $row['email'];
				} elseif ($row['sts_menyetujui'] == 1) {
					$staft_setuju[$row['owner_no']]['name'][] = $row['officer_name'];
					$staft_setuju[$row['owner_no']]['id'][] = $row['id'];
					$staft_setuju[$row['owner_no']]['email'][] = $row['email'];
				} elseif ($row['sts_menvalidasi'] == 1) {
					$staft_valid[$row['owner_no']]['name'][] = $row['officer_name'];
					$staft_valid[$row['owner_no']]['id'][] = $row['id'];
					$staft_valid[$row['owner_no']]['email'][] = $row['email'];
				}
			}
		}

		$rows = $this->db->select("'' as staft, '' as bagian, " . _TBL_VIEW_APPROVAL . ".*")->where('pid', 249)->order_by('urut')->get(_TBL_VIEW_APPROVAL)->result_array();
		$alur[0] = ['level' => 'Risk Officer', 'owner' => '', 'staft' => '', 'level_approval_id' => 0, 'owner_no' => 0, 'staft_no' => 0, 'urut' => 0, 'sts_last' => 0, 'email' => '', 'tanggal' => '', 'sts_monit' => 0];
		foreach ($rows as $row) {
			$prm = json_decode($row['param_text'], true);
			$ow = '';
			$ow_id = '';
			$of = '';
			$of_id = '';
			$email = '';
			if (intval($prm['tipe_approval']) == 0) {
				if (array_key_exists($row['param_int'], $owner)) {
					$ow = $owner[$row['param_int']]['name'];
					$ow_id = $owner[$row['param_int']]['id'];
					if ($prm['level_approval'] == 1) {
						if (array_key_exists($owner[$row['param_int']]['id'], $staft_tahu)) {
							$of = implode(', ', $staft_tahu[$owner[$row['param_int']]['id']]['name']);
							$of_id = implode(', ', $staft_tahu[$owner[$row['param_int']]['id']]['id']);
							$email = implode(', ', $staft_tahu[$owner[$row['param_int']]['id']]['email']);
						}
					} elseif ($prm['level_approval'] == 2) {
						if (array_key_exists($owner[$row['param_int']]['id'], $staft_setuju)) {
							$of = implode(', ', $staft_setuju[$owner[$row['param_int']]['id']]['name']);
							$of_id = implode(', ', $staft_setuju[$owner[$row['param_int']]['id']]['id']);
							$email = implode(', ', $staft_setuju[$owner[$row['param_int']]['id']]['email']);
						}
					} elseif ($prm['level_approval'] == 3) {
						if (array_key_exists($owner[$row['param_int']]['id'], $staft_valid)) {
							$of = implode(', ', $staft_valid[$owner[$row['param_int']]['id']]['name']);
							$of_id = implode(', ', $staft_valid[$owner[$row['param_int']]['id']]['id']);
							$email = implode(', ', $staft_valid[$owner[$row['param_int']]['id']]['email']);
						}
					}
				}
			} elseif (intval($prm['tipe_approval']) == 1) {
				$arr_free = $this->db->where_in('level_approval', $row['param_int'])->get(_TBL_OWNER)->row_array();
				if ($arr_free) {
					$ow = $arr_free['owner_name'];
					$ow_id = $arr_free['id'];
					$of_arr = [];
					$of_id_arr = [];
					$email_arr = [];
					$arr_free = $this->db->where('owner_no', $ow_id)->group_start()->where('sts_mengetahui', 1)->or_where('sts_menyetujui', 1)->or_where('sts_menvalidasi', 1)->group_end()->get(_TBL_VIEW_OFFICER)->result_array();
					if ($arr_free) {
						foreach ($arr_free as $fr) {
							if ($prm['level_approval'] == 1 && $fr['sts_mengetahui'] == 1) {
								$of_arr[] = $fr['officer_name'];
								$of_id_arr[] = $fr['id'];
								$email_arr[] = $fr['email'];
							} elseif ($prm['level_approval'] == 2 && $fr['sts_menyetujui'] == 1) {
								$of_arr[] = $fr['officer_name'];
								$of_id_arr[] = $fr['id'];
								$email_arr[] = $fr['email'];
							} elseif ($prm['level_approval'] == 3 && $fr['sts_menvalidasi'] == 1) {
								$of_arr[] = $fr['officer_name'];
								$of_id_arr[] = $fr['id'];
								$email_arr[] = $fr['email'];
							}
						}
						$of = implode(', ', $of_arr);
						$of_id = implode(', ', $of_id_arr);
						$email = implode(', ', $email_arr);
					}
				}
			}
			$alur[$row['urut']] = ['level' => $row['model'], 'owner' => $ow, 'staft' => $of, 'level_approval_id' => $row['id'], 'owner_no' => $ow_id, 'staft_no' => $of_id, 'urut' => $row['urut'], 'sts_last' => $row['sts_last'], 'email' => $email, 'tanggal' => '', 'sts_monit' => $prm['monit'], 'sts_notif' => $prm['notif_email']];
		}
		return $alur;
	}

	function propose()
	{
		$id = intval($this->uri->segment(3));
		$data['note_propose'] = 'Catatan :<br/>' . form_textarea('note_propose', '', " id='note_propose' placeholder = 'silahkan masukkan catatan anda disini' maxlength='500' size='500' class='form-control' style='overflow: hidden; width: 100% !important; height: 200px;' onblur='_maxLength(this , \"id_sisa_1\")' onkeyup='_maxLength(this , \"id_sisa_1\")' data-role='tagsinput'", true, ['size' => 500, 'isi' => 0, 'no' => 1]);

		$data['parent'] = $this->db->where('id', $id)->get(_TBL_VIEW_RCSA)->row_array();

		$cbominggu = $this->data->get_data_minggu($data['parent']['term_id']);
		$minggu = ($data['parent']['minggu_id']) ? $cbominggu[$data['parent']['minggu_id']] : '';

		$data['parent']['bulan'] = $this->term[$data['parent']['term_id']] . ' - ' . $minggu;
		$data['info_parent'] = $this->load->view('info-parent', $data, true);

		$alur = $this->data_alur(['owner_no' => $data['parent']['owner_id']]);
		$data_notif = [];
		$data_notif_asli = ['level_approval_id' => 0];

		$data['alur'] = $alur;
		$data['histori'] = $this->db->where('rcsa_id', $id)->where('tipe_log', 2)->order_by('tanggal desc')->get(_TBL_VIEW_LOG_APPROVAL)->result_array();
		$data['info_alur'] = $this->load->view('info-alur', $data, true);

		$ket = 'Progress Mitigasi Risk Context ini belum memiliki Owner atau officer yang akan melakukan approval mitigasi';
		if ($alur) {
			if (array_key_exists(1, $alur)) {
				$data_notif = $alur[1];
				$data_notif_asli = $alur[0];
				$ket = 'Progress Mitigasi Risk Context akan dikirim ke <strong>' . $data_notif['staft'] . '</strong> bagian <strong>' . $data_notif['owner'] . '</strong>';
				if (!$data_notif['staft'] || !$data_notif['owner']) {
					$data_notif = [];
					$ket = 'Progress Mitigasi Risk Context ini belum memiliki Owner atau officer yang akan melakukan approval mitigasi';
				}
			}
		}

		$x = $this->session->userdata('periode');

		$tgl1 = date('Y-m-d');
		$tgl2 = date('Y-m-d');
		// if ($x){
		// 	$tgl1=$x['tgl_awal'];
		// 	$tgl2=$x['tgl_akhir'];
		// }

		$data['lanjut'] = $data_notif;
		$data['poin_start'] = $data_notif_asli;
		$data['id'] = $id;
		$x['notif'] = json_encode($data_notif);
		$x['ket'] = $ket;
		$x['id'] = $id;
		$x['alur'] = json_encode($alur);
		$data['hidden'] = $x;
		$data['period'] = $this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'period')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		// Doi::dump($data['parent']['period_id']);
		// Doi::dump($data['period']);
		// die();
		$data['term'] = $this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'term')->combo_where('pid', $data['parent']['period_id'])->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		// $data['minggu']=$this->crud->combo_select(['id', 'concat(param_string, \' ( \', param_date, \' s.d \', param_date_after, \' ) \') as minggu'])->combo_where('kelompok', 'minggu')->combo_where('param_date',null)
		// ->combo_where('param_date<=', $tgl2)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$data['minggu'] = $this->data->get_data_minggu($data['parent']['term_id']);
		// $data['minggu']=$this->crud->combo_select(['id', 'concat(param_string,\' minggu ke - \',data, \' ( \', param_date, \' s.d \', param_date_after, \' ) \') as minggu'])->combo_where('kelompok', 'minggu')->combo_where('param_date>=', $tgl1);


		$hasil = $this->load->view('propose', $data, true);

		$configuration = [
			'show_title_header' => false,
			'show_action_button' => false,
			'content_title' => 'Propose Progres Mitigasi'
		];

		// $this->crud->crud_table(_TBL_RCSA_KPI);
		// $this->crud->crud_type('delete');
		// $this->crud->crud_where(['field' => 'rcsa_id', 'value' => $id]);
		// $this->crud->crud_where(['field' => 'sts_add', 'value' => 0]);
		// $this->crud->process_crud();

		$this->default_display(['content' => $hasil, 'configuration' => $configuration]);

		// echo json_encode($propose);
	}

	function proses_propose_mitigasi()
	{
		$post = $this->input->post();
		$alur = json_decode($post['alur'], true);
		$notif = json_decode($post['notif'], true);

		$sts_final = 0;
		if (count($alur) == $notif['urut']) {
			$sts_final = 1;
		}
		$alur[$notif['urut'] - 1]['tanggal'] = date('Y-m-d H:i:s');

		$this->crud->crud_table(_TBL_RCSA);
		$this->crud->crud_type('edit');
		$this->crud->crud_field('status_revisi_mitigasi', 0, 'int');
		$this->crud->crud_field('status_id_mitigasi', $notif['urut'], 'int');
		$this->crud->crud_field('minggu_id_mitigasi', $post['minggu'], 'int');
		$this->crud->crud_field('status_final_mitigasi', 0, 'int');
		$this->crud->crud_field('note_propose_mitigasi', $post['note_propose']);
		$this->crud->crud_field('param_approval_mitigasi', json_encode($alur));
		$this->crud->crud_field('tgl_propose_mitigasi', date('Y-m-d H:i:s'), 'datetime');
		$this->crud->crud_where(['field' => 'id', 'value' => $post['id']]);
		$this->crud->process_crud();

		$this->crud->crud_table(_TBL_LOG_APPROVAL);
		$this->crud->crud_type('add');
		$this->crud->crud_field('tipe_log', 2, 'int');
		$this->crud->crud_field('rcsa_id', $post['id'], 'int');
		$this->crud->crud_field('keterangan', 'Propose ke ' . $notif['level']);
		$this->crud->crud_field('note', $post['note_propose']);
		$this->crud->crud_field('tanggal', date('Y-m-d H:i:s'), 'datetime');
		$this->crud->crud_field('user_id', $this->ion_auth->get_user_id());
		$this->crud->crud_field('penerima_id', $notif['staft_no']);
		$this->crud->process_crud();

		$file_name = '';
		$this->load->library('image');
		if (array_key_exists('attr', $_FILES)) {
			if (!empty($_FILES['attr']['name'])) {
				$this->image->set_Param('nm_file', 'attr');
				$this->image->set_Param('file_name', $_FILES['attr']['name']);
				$this->image->set_Param('path', file_path_relative('rcsa'));
				$this->image->set_Param('thumb', false);
				$this->image->set_Param('type', '*');
				$this->image->set_Param('size', 10000000);
				$this->image->set_Param('nm_random', false);
				$this->image->set_Param('multi', false);
				$this->image->upload();

				$file_name = 'rcsa/' . $this->image->result('file_name');
			}
		}

		$rows = $this->db->where('rcsa_id', $post['id'])->where('minggu_id', $post['minggu'])->where('term_id', $post['term'])->get(_TBL_RCSA_APPROVAL_MITIGASI)->row_array();
		$status_lengkap = 0;
		if ($post['minggu'] == _MINGGU_ID_ && empty($file_name)) {
			$status_lengkap = 1;
		} elseif ($post['minggu'] == _MINGGU_ID_ && !empty($file_name)) {
			$status_lengkap = 2;
		}

		if (!$rows) {
			$this->crud->crud_table(_TBL_RCSA_APPROVAL_MITIGASI);
			$this->crud->crud_type('add');
			$this->crud->crud_field('rcsa_id', $post['id'], 'int');
			$this->crud->crud_field('note', $post['note_propose']);
			$this->crud->crud_field('minggu_id', $post['minggu'], 'int');
			$this->crud->crud_field('minggu_aktif_id', _MINGGU_ID_, 'int');
			$this->crud->crud_field('term_id', $post['term'], 'int');
			$this->crud->crud_field('tgl_propose', date('Y-m-d H:i:s'), 'datetime');
			$this->crud->crud_field('param_approval', json_encode($alur));
			$this->crud->crud_field('file_att', $file_name);
			$this->crud->crud_field('status_lengkap', $status_lengkap);
			$this->crud->crud_field('officer_id', $this->ion_auth->get_user_id());
			$this->crud->crud_field('created_by', $this->ion_auth->get_user_name());
			$this->crud->process_crud();
		} else {
			$this->crud->crud_table(_TBL_RCSA_APPROVAL_MITIGASI);
			$this->crud->crud_type('edit');
			$this->crud->crud_field('minggu_id', $post['minggu'], 'int');
			$this->crud->crud_field('minggu_aktif_id', _MINGGU_ID_, 'int');
			$this->crud->crud_field('note', $post['note_propose']);
			$this->crud->crud_field('term_id', $post['term'], 'int');
			$this->crud->crud_field('tgl_propose', date('Y-m-d H:i:s'), 'datetime');
			$this->crud->crud_field('param_approval', json_encode($alur));
			$this->crud->crud_field('file_att', $file_name);
			$this->crud->crud_field('status_lengkap', $status_lengkap);
			$this->crud->crud_field('officer_id', $this->ion_auth->get_user_id());
			$this->crud->crud_field('updated_by', $this->ion_auth->get_user_name());
			$this->crud->crud_where(['field' => 'id', 'value' => $rows['id']]);
			$this->crud->process_crud();
		}

		$this->crud->crud_table(_TBL_RCSA_KPI);
		$this->crud->crud_type('edit');
		$this->crud->crud_field('sts_add', 1, 'int');
		$this->crud->crud_field('minggu_id', $post['minggu'], 'int');
		$this->crud->crud_where(['field' => 'rcsa_id', 'value' => $post['id']]);
		$this->crud->crud_where(['field' => 'sts_add', 'value' => 0]);
		$this->crud->process_crud();

		$email = explode(',', preg_replace('/\s+/', '', $notif['email']));
		$emailFilter = array_values(array_filter($email, function ($value) {
			return !is_null($value) && $value !== '';
		}));

		if (count($emailFilter) > 0) {
			$datasOutbox = [
				'recipient' => $emailFilter,
			];
			$content_replace = [
				'[[konteks]]' => 'Progress Mitigasi',
				'[[redir]]' => 2,
				'[[id]]' => $post['id'],
				'[[notif]]' => $notif['staft'],
				'[[sender]]' => $this->session->userdata('data_user')['real_name'],
				'[[link]]' => base_url() . "approval-mitigasi",
				'[[footer]]' => $this->session->userdata('preference-0')['nama_kantor']

			];
			if ($this->session->userdata('preference-0')['send_notif'] == 1) {
				$this->load->library('outbox');
				$this->outbox->setTemplate('NOTIF01');
				$this->outbox->setParams($content_replace);
				$this->outbox->setDatas($datasOutbox);
				$this->outbox->send();
			}
		}
		// echo json_encode(['data'=>true]);
		header('location:' . base_url(_MODULE_NAME_));
	}

	function kri_add($post = [])
	{
		if (!$post) {
			$post = $this->input->post();
			$post['id'] = 0;
		}

		$data['list'] = $this->db->where('kpi_id', $post['kpi_id'])->get(_TBL_RCSA_KPI)->result_array();

		$data['parent'] = $post['kpi_id'];
		$data['entri'] = $this->entri_kri(['edit_id' => $post['id'], 'kpi_id' => $post['kpi_id'], 'rcsa_id' => $post['rcsa_id'], 'minggu' => $post['minggu']], false);
		$result['combo'] = $this->load->view('kri', $data, true);
		header('Content-type: application/json');
		echo json_encode($result);
	}

	function kri_edit($post = [])
	{
		$id = $this->input->post('edit_id');
		$post = $this->input->post();
		$data['parent'] = $post['kpi_id'];
		$data['entri'] = $this->entri_kri(['edit_id' => $id, 'kpi_id' => $post['kpi_id'], 'rcsa_id' => $post['rcsa_id'], 'minggu' => $post['minggu']]);
		$result['combo'] = $this->load->view('input-kri', $data, true);
		header('Content-type: application/json');
		echo json_encode($result);
	}

	function simpan_kri()
	{
		$post = $this->input->post();
		$this->data->post = $post;
		$id = $this->data->simpan_kri();
		// $data['kpi_id']=$post['kpi_id'];
		// $data['rcsa_id']=$post['rcsa_id'];
		// $data['minggu']=$post['minggu'];
		// $post['id']=0;

		$data['minggu'] = $post['minggu'];
		$data['id'] = $post['rcsa_id'];
		$this->list_kpi($data);
		// $this->kri_add($post);
	}

	function entri_kri($post = [], $input = true)
	{
		$data['param'] = $post;
		$this->cboSatuan = $this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'satuan')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$mit = [];
		if (intval($post['edit_id']) > 0) {
			$mit = $this->db->where('id', $post['edit_id'])->get(_TBL_RCSA_KPI)->row_array();
		}

		$disabled = '';

		if ($input) {
			$data['like'][] = ['title' => _l('fld_kri'), 'help' => _h('help_kri'), 'isi' => form_input('title', ($mit) ? $mit['title'] : '', 'class="form-control" ' . $disabled . ' id="title"')];
			$data['like'][] = ['title' => _l('fld_satuan'), 'help' => _h('help_satuan'), 'isi' => form_dropdown('satuan_id', $this->cboSatuan, ($mit) ? $mit['satuan_id'] : '', 'class="form-control select" ' . $disabled . ' id="satuan_id"')];

			$data['like'][] = ['title' => '<a style="height:1.4rem;width:1.4rem;background-color:#2c5b29" class="btn bg-successx-400 rounded-round btn-icon btn-sm"><span class="letter-icon"></span></a>', 'help' => _h('help_pencapaian'), 'isi' => form_input('p_1', ($mit) ? $mit['p_1'] : '', 'class="form-control" ' . $disabled . ' id="p_1" placeholder="' . _l('fld_pencapaian') . '" style="width:50%"') . '&nbsp;&nbsp;&nbsp;' . form_input('s_1_min', ($mit) ? $mit['s_1_min'] : '', 'class="form-control text-center" ' . $disabled . ' id="s_1_min" placeholder="' . _l('fld_min_satuan') . '" style="width:10%"') . '&nbsp;&nbsp;&nbsp;<span class="input-group-text"> - </span>&nbsp;&nbsp;&nbsp;' . form_input('s_1_max', ($mit) ? $mit['s_1_max'] : '', 'class="form-control text-center" ' . $disabled . ' id="s_1_max" placeholder="' . _l('fld_mak_satuan') . '" style="width:10%"') . ' <span class="input-group-text"> Satuan </span> '];

			$data['like'][] = ['title' => '<a style="height:1.4rem;width:1.4rem;background-color:#50ca4e;" class="btn bg-orangex-400 rounded-round btn-icon btn-sm"><span class="letter-icon"></span></a>', 'help' => _h('help_pencapaian_4'), 'isi' => form_input('p_4', ($mit) ? $mit['p_4'] : '', 'class="form-control" ' . $disabled . ' id="p_4" placeholder="' . _l('fld_pencapaian') . '" style="width:50%"') . '&nbsp;&nbsp;&nbsp;' . form_input('s_4_min', ($mit) ? $mit['s_4_min'] : '', 'class="form-control text-center" ' . $disabled . ' id="s_4_min" placeholder="' . _l('fld_min_satuan') . '" style="width:10%"') . '&nbsp;&nbsp;&nbsp;<span class="input-group-text"> - </span>&nbsp;&nbsp;&nbsp;' . form_input('s_4_max', ($mit) ? $mit['s_4_max'] : '', 'class="form-control text-center" ' . $disabled . ' id="s_4_max" placeholder="' . _l('fld_mak_satuan') . '" style="width:10%"') . ' <span class="input-group-text"> Satuan </span> '];

			$data['like'][] = ['title' => '<a style="height:1.4rem;width:1.4rem;background-color:#edfd17;" class="btn bg-dangerx-400 rounded-round btn-icon btn-sm"><span class="letter-icon"></span></a>', 'help' => _h('help_pencapaian_2'), 'isi' => form_input('p_2', ($mit) ? $mit['p_2'] : '', 'class="form-control" ' . $disabled . ' id="p_2" placeholder="' . _l('fld_pencapaian') . '" style="width:50%"') . '&nbsp;&nbsp;&nbsp;' . form_input('s_2_min', ($mit) ? $mit['s_2_min'] : '', 'class="form-control text-center" ' . $disabled . ' id="s_2_min" placeholder="' . _l('fld_min_satuan') . '" style="width:10%"') . '&nbsp;&nbsp;&nbsp;<span class="input-group-text"> - </span>&nbsp;&nbsp;&nbsp;' . form_input('s_2_max', ($mit) ? $mit['s_2_max'] : '', 'class="form-control text-center" ' . $disabled . ' id="s_2_max" placeholder="' . _l('fld_mak_satuan') . '" style="width:10%"') . ' <span class="input-group-text"> Satuan </span> '];

			$data['like'][] = ['title' => '<a style="height:1.4rem;width:1.4rem;background-color:#f0ca0f;" class="btn bg-dangerx-400 rounded-round btn-icon btn-sm"><span class="letter-icon"></span></a>', 'help' => _h('help_pencapaian_5'), 'isi' => form_input('p_5', ($mit) ? $mit['p_5'] : '', 'class="form-control" ' . $disabled . ' id="p_5" placeholder="' . _l('fld_pencapaian') . '" style="width:50%"') . '&nbsp;&nbsp;&nbsp;' . form_input('s_5_min', ($mit) ? $mit['s_5_min'] : '', 'class="form-control text-center" ' . $disabled . ' id="s_5_min" placeholder="' . _l('fld_min_satuan') . '" style="width:10%"') . '&nbsp;&nbsp;&nbsp;<span class="input-group-text"> - </span>&nbsp;&nbsp;&nbsp;' . form_input('s_5_max', ($mit) ? $mit['s_5_max'] : '', 'class="form-control text-center" ' . $disabled . ' id="s_5_max" placeholder="' . _l('fld_mak_satuan') . '" style="width:10%"') . ' <span class="input-group-text"> Satuan </span> '];

			$data['like'][] = ['title' => '<a style="height:1.4rem;width:1.4rem;background-color:#e70808" class="btn bg-dangerx-400 rounded-round btn-icon btn-sm"><span class="letter-icon"></span></a>', 'help' => _h('help_pencapaian_3'), 'isi' => form_input('p_3', ($mit) ? $mit['p_3'] : '', 'class="form-control" ' . $disabled . ' id="p_3" placeholder="' . _l('fld_pencapaian') . '" style="width:50%"') . '&nbsp;&nbsp;&nbsp;' . form_input('s_3_min', ($mit) ? $mit['s_3_min'] : '', 'class="form-control text-center" ' . $disabled . ' id="s_3_min" placeholder="' . _l('fld_min_satuan') . '" style="width:10%"') . '&nbsp;&nbsp;&nbsp;<span class="input-group-text"> - </span>&nbsp;&nbsp;&nbsp;' . form_input('s_3_max', ($mit) ? $mit['s_3_max'] : '', 'class="form-control text-center" ' . $disabled . ' id="s_3_max" placeholder="' . _l('fld_mak_satuan') . '" style="width:10%"') . ' <span class="input-group-text"> Satuan </span> '];

			$data['like'][] = ['title' => _l('fld_score'), 'help' => _h('help_score'), 'isi' => '<div class="input-group" style="width:15%;text-align:center;">' . form_input('score', ($mit) ? $mit['score'] : '', 'class="form-control" id="score" placeholder="' . _l('fld_score') . '"') . '</div>'];
		} else {
			$data['like'] = [];
		}
		$hasil = $this->load->view('input-kri', $data, true);

		return $hasil;
	}

	function list_kpi($post = [])
	{
		if (!$post) {
			$post = $this->input->post();
		}

		$this->db->select('a.*, COUNT(b.kpi_id) AS kri_count', false);
		$this->db->join(_TBL_RCSA_KPI . ' as b', 'a.id = b.kpi_id', 'left', false);
		$this->db->group_by("a.id");
		$data['list'] = $this->db->where('a.minggu_id', $post['minggu'])->where('a.rcsa_id', intval($post['id']))->or_group_start()->where('a.sts_add', 0)->where('a.rcsa_id', intval($post['id']))->group_end()->where('a.kpi_id', 0)->get(_TBL_RCSA_KPI . " as a")->result_array();

		$kpi = $this->db
			->select('id_kpi, kpi_detail, id, kode_risk, parent_id')
			// ->select('id_kpi, kpi_detail, GROUP_CONCAT(id) as id')
			->where('rcsa_id', intval($post['id']))->where('kpi_detail !=', null)->order_by('kode_dept')
			// ->group_by('id_kpi')
			->order_by('kode_aktifitas')
			// ->get_compiled_select(_TBL_VIEW_RCSA_DETAIL);
			->get(_TBL_VIEW_RCSA_DETAIL)->result_array();

		if (count($data['list']) == 0) {
			$this->db->where('rcsa_id', $post['id']);
			$this->db->where('minggu_id', '(select min(minggu_id) from ' . _TBL_RCSA_KPI . ' where rcsa_id =' . $post['id'] . ')', false);
			// $cekKpi = $this->db->get_compiled_select(_TBL_RCSA_KPI);
			$cekKpi = $this->db->get(_TBL_RCSA_KPI);

			if (count($cekKpi->result_array()) > 0) {
				foreach ($cekKpi->result_array() as $key => $value) {
					$dataKpi = [
						'minggu' => $post['minggu'],
						'rcsa_id' => $post['id'],
						'edit_id' => 0,
						'title' => $value['title'],
						'satuan_id' => $value['satuan_id'],
						'p_1' => $value['p_1'],
						's_1_min' => $value['s_1_min'],
						's_1_max' => $value['s_1_max'],
						'p_4' => $value['p_4'],
						's_4_min' => $value['s_4_min'],
						's_4_max' => $value['s_4_max'],
						'p_2' => $value['p_2'],
						's_2_min' => $value['s_2_min'],
						's_2_max' => $value['s_2_max'],
						'p_5' => $value['p_5'],
						's_5_min' => $value['s_5_min'],
						's_5_max' => $value['s_5_max'],
						'p_3' => $value['p_3'],
						's_3_min' => $value['s_3_min'],
						's_3_max' => $value['s_3_max'],
						'score' => $value['score'],
					];
					$this->data->post = $dataKpi;
					$this->data->simpan_kpi();
					$idKpi = $this->crud->last_id();

					$this->db->where('kpi_id', $value['id']);
					$cekKri = $this->db->get(_TBL_RCSA_KPI);
					foreach ($cekKri->result_array() as $k => $v) {
						$dataKri = [
							'minggu' => 0,
							'rcsa_id' => 0,
							'edit_id' => 0,
							'kpi_id' => $idKpi,
							'title' => $v['title'],
							'satuan_id' => $v['satuan_id'],
							'p_1' => $v['p_1'],
							's_1_min' => $v['s_1_min'],
							's_1_max' => $v['s_1_max'],
							'p_4' => $v['p_4'],
							's_4_min' => $v['s_4_min'],
							's_4_max' => $v['s_4_max'],
							'p_2' => $v['p_2'],
							's_2_min' => $v['s_2_min'],
							's_2_max' => $v['s_2_max'],
							'p_5' => $v['p_5'],
							's_5_min' => $v['s_5_min'],
							's_5_max' => $v['s_5_max'],
							'p_3' => $v['p_3'],
							's_3_min' => $v['s_3_min'],
							's_3_max' => $v['s_3_max'],
							'score' => $v['score'],
						];
						$this->data->post = $dataKri;
						$id = $this->data->simpan_kri();
					}
				}
			} else {

				if (!isset($post['del'])) {

					if (count($kpi) > 0) {
						if ($kpi[0]['parent_id'] > 0) {
							foreach ($kpi as $keyx => $kpix) {
								$this->db->where('rcsa_id', $kpix['parent_id']);
								$this->db->where('minggu_id', '(select min(minggu_id) from ' . _TBL_RCSA_KPI . ' where rcsa_id =' . $kpix['parent_id'] . ')', false);
								// $cekKpi = $this->db->get_compiled_select(_TBL_RCSA_KPI);
								$cekKpix = $this->db->get(_TBL_RCSA_KPI);

								foreach ($cekKpix->result_array() as $key => $value) {
									$idkri = $kpix['id'];
									$dataKpi = [
										'minggu' => $post['minggu'],
										'rcsa_id' => $post['id'],
										'edit_id' => 0,
										'title' => $value['title'],
										'satuan_id' => $value['satuan_id'],
										'p_1' => $value['p_1'],
										's_1_min' => $value['s_1_min'],
										's_1_max' => $value['s_1_max'],
										'p_4' => $value['p_4'],
										's_4_min' => $value['s_4_min'],
										's_4_max' => $value['s_4_max'],
										'p_2' => $value['p_2'],
										's_2_min' => $value['s_2_min'],
										's_2_max' => $value['s_2_max'],
										'p_5' => $value['p_5'],
										's_5_min' => $value['s_5_min'],
										's_5_max' => $value['s_5_max'],
										'p_3' => $value['p_3'],
										's_3_min' => $value['s_3_min'],
										's_3_max' => $value['s_3_max'],
										'score' => $value['score'],
									];
									$this->data->post = $dataKpi;
									$this->data->simpan_kpi();
									$idKpi = $this->crud->last_id();

									// $this->db->where('kpi_id', $value['id']);
									// $cekKri = $this->db->get(_TBL_RCSA_KPI);
									$cekKri = $this->db->where('bk_tipe', 2)->where('rcsa_id', $post['id'])->where('rcsa_detail_id', $idkri)
										->group_by('kri_id')
										// ->get_compiled_select(_TBL_VIEW_RCSA_DET_LIKE_INDI);

										->get(_TBL_VIEW_RCSA_DET_LIKE_INDI)->result_array();


									if (count($cekKri) > 0) {
										foreach ($cekKri as $k => $v) {
											$dataKri = [
												'minggu' => 0,
												'rcsa_id' => 0,
												'edit_id' => 0,
												'kpi_id' => $idKpi,
												'title' => $v['kri'],
												'satuan_id' => $v['satuan_id'],
												'p_1' => $v['p_1'],
												's_1_min' => $v['s_1_min'],
												's_1_max' => $v['s_1_max'],
												'p_4' => $v['p_4'],
												's_4_min' => $v['s_4_min'],
												's_4_max' => $v['s_4_max'],
												'p_2' => $v['p_2'],
												's_2_min' => $v['s_2_min'],
												's_2_max' => $v['s_2_max'],
												'p_5' => $v['p_5'],
												's_5_min' => $v['s_5_min'],
												's_5_max' => $v['s_5_max'],
												'p_3' => $v['p_3'],
												's_3_min' => $v['s_3_min'],
												's_3_max' => $v['s_3_max'],
												'score' => $v['score'],
											];
											$this->data->post = $dataKri;
											$id = $this->data->simpan_kri();
										}
									}
								}
							}
						} else {
							foreach ($kpi as $key => $value) {
								if ($value['id_kpi'] != '') {
									// $idkri = explode(',', $value['id']);
									$idkri = $value['id'];

									$dataKpi = [
										'minggu' => $post['minggu'],
										'rcsa_id' => $post['id'],
										'edit_id' => 0,
										'title' => $value['kpi_detail'] . ' (' . $value['kode_risk'] . ')',
										'satuan_id' => 0,
										'p_1' => 0,
										's_1_min' => 0,
										's_1_max' => 0,
										'p_4' => 0,
										's_4_min' => 0,
										's_4_max' => 0,
										'p_2' => 0,
										's_2_min' => 0,
										's_2_max' => 0,
										'p_5' => 0,
										's_5_min' => 0,
										's_5_max' => 0,
										'p_3' => 0,
										's_3_min' => 0,
										's_3_max' => 0,
										'score' => 0,
									];
									$this->data->post = $dataKpi;
									$this->data->simpan_kpi();
									$idKpi = $this->crud->last_id();
									// // $value['kpi_detail']
									$kri = $this->db->where('bk_tipe', 2)->where('rcsa_id', $post['id'])->where('rcsa_detail_id', $idkri)
										->group_by('kri_id')
										// ->get_compiled_select(_TBL_VIEW_RCSA_DET_LIKE_INDI);

										->get(_TBL_VIEW_RCSA_DET_LIKE_INDI)->result_array();


									if (count($kri) > 0) {
										foreach ($kri as $k => $v) {
											$dataKri = [
												'minggu' => 0,
												'rcsa_id' => 0,
												'edit_id' => 0,
												'kpi_id' => $idKpi,
												'title' => $v['kri'],
												'satuan_id' => $v['satuan_id'],
												'p_1' => $v['p_1'],
												's_1_min' => $v['s_1_min'],
												's_1_max' => $v['s_1_max'],
												'p_4' => $v['p_4'],
												's_4_min' => $v['s_4_min'],
												's_4_max' => $v['s_4_max'],
												'p_2' => $v['p_2'],
												's_2_min' => $v['s_2_min'],
												's_2_max' => $v['s_2_max'],
												'p_5' => $v['p_5'],
												's_5_min' => $v['s_5_min'],
												's_5_max' => $v['s_5_max'],
												'p_3' => $v['p_3'],
												's_3_min' => $v['s_3_min'],
												's_3_max' => $v['s_3_max'],
												'score' => $v['score'],
											];
											$this->data->post = $dataKri;
											$this->data->simpan_kri();
										}
									}
								}
							}
						}
					}
				}
			}

			$this->db->select('a.*, COUNT(b.kpi_id) AS kri_count', false);
			$this->db->join(_TBL_RCSA_KPI . ' as b', 'a.id = b.kpi_id', 'left', false);
			$this->db->group_by("a.id");
			$data['list'] = $this->db->where('a.minggu_id', $post['minggu'])->where('a.rcsa_id', intval($post['id']))->or_group_start()->where('a.sts_add', 0)->where('a.rcsa_id', intval($post['id']))->group_end()->where('a.kpi_id', 0)->get(_TBL_RCSA_KPI . " as a")->result_array();
		}

		$id_kpi = [];
		foreach ($data['list'] as $key => $value) {
			$id_kpi[] = $value['id'];
		}
		$data['list_kpi'] = [];
		if (count($id_kpi) > 0) {
			$data['list_kpi'] = $this->db->where_in('kpi_id', $id_kpi)->get(_TBL_RCSA_KPI)->result_array();
		}

		// $data['combo'] = $this->load->view('kri', $data, true);

		$data['parent'] = $post['id'];
		$data['minggu'] = $post['minggu'];
		$result['combo'] = $this->load->view('kpi', $data, true);
		header('Content-type: application/json');
		echo json_encode($result);
	}

	function review_kpi()
	{
		$pos = $this->input->post();
		$rows = $this->db->where('id', $pos['rcsa_id'])->get(_TBL_RCSA)->row_array();
		$pos['owner'] = $rows['owner_id'];
		$pos['period'] = $rows['period_id'];
		$pos['term'] = $rows['term_id'];
		$pos['minggu'] = $rows['minggu_id'];

		$this->data->pos = $pos;
		$data = $this->data->get_detail_data();
		// dumps($data);
		// die($data);
		$data['mode'] = 0;
		$data['id'] = $pos['rcsa_id'];

		$x = $this->load->view('detail-kpi', $data, true);
		$y = $this->load->view('detail-kpi2', $data, true);
		// $this->session->set_userdata(['cetak_grap'=>$data]);
		$hasil['combo'] = $x . $y;
		header('Content-type: application/json');
		echo json_encode($hasil);
	}

	function view_kpi()
	{
		$data['minggu'] = $post['minggu'];
		$data['id'] = $post['rcsa_id'];
		$this->list_kpi($data);
	}

	function kpi_add()
	{
		$post = $this->input->post();
		$data['param'] = $post;
		$this->cboSatuan = $this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'satuan')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$mit = [];
		if (intval($post['edit_id']) > 0) {
			$mit = $this->db->where('id', $post['edit_id'])->get(_TBL_RCSA_KPI)->row_array();
		}
		$disabled = '';

		$data['like'][] = ['title' => _l('fld_kpi'), 'help' => _h('help_kpi'), 'isi' => form_input('title', ($mit) ? $mit['title'] : '', 'class="form-control" ' . $disabled . ' id="title"')];
		$data['like'][] = ['title' => _l('fld_satuan'), 'help' => _h('help_satuan'), 'isi' => form_dropdown('satuan_id', $this->cboSatuan, ($mit) ? $mit['satuan_id'] : '', 'class="form-control select" ' . $disabled . ' id="satuan_id"')];
		$data['like'][] = ['title' => '<a style="height:1.4rem;width:1.4rem;background-color:#2c5b29" class="btn bg-successx-400 rounded-round btn-icon btn-sm"><span class="letter-icon"></span></a>', 'help' => _h('help_pencapaian'), 'isi' => form_input('p_1', ($mit) ? $mit['p_1'] : '', 'class="form-control" ' . $disabled . ' id="p_1" placeholder="' . _l('fld_pencapaian') . '" style="width:50%"') . '&nbsp;&nbsp;&nbsp;' . form_input('s_1_min', ($mit) ? $mit['s_1_min'] : '', 'class="form-control text-center" ' . $disabled . ' id="s_1_min" placeholder="' . _l('fld_min_satuan') . '" style="width:10%"') . '&nbsp;&nbsp;&nbsp;<span class="input-group-text"> - </span>&nbsp;&nbsp;&nbsp;' . form_input('s_1_max', ($mit) ? $mit['s_1_max'] : '', 'class="form-control text-center" ' . $disabled . ' id="s_1_max" placeholder="' . _l('fld_mak_satuan') . '" style="width:10%"') . ' <span class="input-group-text"> Satuan </span> '];

		$data['like'][] = ['title' => '<a style="height:1.4rem;width:1.4rem;background-color:#50ca4e;" class="btn bg-orangex-400 rounded-round btn-icon btn-sm"><span class="letter-icon"></span></a>', 'help' => _h('help_pencapaian_4'), 'isi' => form_input('p_4', ($mit) ? $mit['p_4'] : '', 'class="form-control" ' . $disabled . ' id="p_4" placeholder="' . _l('fld_pencapaian') . '" style="width:50%"') . '&nbsp;&nbsp;&nbsp;' . form_input('s_4_min', ($mit) ? $mit['s_4_min'] : '', 'class="form-control text-center" ' . $disabled . ' id="s_4_min" placeholder="' . _l('fld_min_satuan') . '" style="width:10%"') . '&nbsp;&nbsp;&nbsp;<span class="input-group-text"> - </span>&nbsp;&nbsp;&nbsp;' . form_input('s_4_max', ($mit) ? $mit['s_4_max'] : '', 'class="form-control text-center" ' . $disabled . ' id="s_4_max" placeholder="' . _l('fld_mak_satuan') . '" style="width:10%"') . ' <span class="input-group-text"> Satuan </span> '];

		$data['like'][] = ['title' => '<a style="height:1.4rem;width:1.4rem;background-color:#edfd17;" class="btn bg-dangers-400 rounded-round btn-icon btn-sm"><span class="letter-icon"></span></a>', 'help' => _h('help_pencapaian_2'), 'isi' => form_input('p_2', ($mit) ? $mit['p_2'] : '', 'class="form-control" ' . $disabled . ' id="p_2" placeholder="' . _l('fld_pencapaian') . '" style="width:50%"') . '&nbsp;&nbsp;&nbsp;' . form_input('s_2_min', ($mit) ? $mit['s_2_min'] : '', 'class="form-control text-center" ' . $disabled . ' id="s_2_min" placeholder="' . _l('fld_min_satuan') . '" style="width:10%"') . '&nbsp;&nbsp;&nbsp;<span class="input-group-text"> - </span>&nbsp;&nbsp;&nbsp;' . form_input('s_2_max', ($mit) ? $mit['s_2_max'] : '', 'class="form-control text-center" ' . $disabled . ' id="s_2_max" placeholder="' . _l('fld_mak_satuan') . '" style="width:10%"') . ' <span class="input-group-text"> Satuan </span> '];

		$data['like'][] = ['title' => '<a style="height:1.4rem;width:1.4rem;background-color:#f0ca0f;" class="btn bg-dangers-400 rounded-round btn-icon btn-sm"><span class="letter-icon"></span></a>', 'help' => _h('help_pencapaian_5'), 'isi' => form_input('p_5', ($mit) ? $mit['p_5'] : '', 'class="form-control" ' . $disabled . ' id="p_5" placeholder="' . _l('fld_pencapaian') . '" style="width:50%"') . '&nbsp;&nbsp;&nbsp;' . form_input('s_5_min', ($mit) ? $mit['s_5_min'] : '', 'class="form-control text-center" ' . $disabled . ' id="s_5_min" placeholder="' . _l('fld_min_satuan') . '" style="width:10%"') . '&nbsp;&nbsp;&nbsp;<span class="input-group-text"> - </span>&nbsp;&nbsp;&nbsp;' . form_input('s_5_max', ($mit) ? $mit['s_5_max'] : '', 'class="form-control text-center" ' . $disabled . ' id="s_5_max" placeholder="' . _l('fld_mak_satuan') . '" style="width:10%"') . ' <span class="input-group-text"> Satuan </span> '];

		$data['like'][] = ['title' => '<a style="height:1.4rem;width:1.4rem;background-color:#e70808" class="btn bg-dangers-400 rounded-round btn-icon btn-sm"><span class="letter-icon"></span></a>', 'help' => _h('help_pencapaian_3'), 'isi' => form_input('p_3', ($mit) ? $mit['p_3'] : '', 'class="form-control" ' . $disabled . ' id="p_3" placeholder="' . _l('fld_pencapaian') . '" style="width:50%"') . '&nbsp;&nbsp;&nbsp;' . form_input('s_3_min', ($mit) ? $mit['s_3_min'] : '', 'class="form-control text-center" ' . $disabled . ' id="s_3_min" placeholder="' . _l('fld_min_satuan') . '" style="width:10%"') . '&nbsp;&nbsp;&nbsp;<span class="input-group-text"> - </span>&nbsp;&nbsp;&nbsp;' . form_input('s_3_max', ($mit) ? $mit['s_3_max'] : '', 'class="form-control text-center" ' . $disabled . ' id="s_3_max" placeholder="' . _l('fld_mak_satuan') . '" style="width:10%"') . ' <span class="input-group-text"> Satuan </span> '];

		$data['like'][] = ['title' => _l('fld_score'), 'help' => _h('help_score'), 'isi' => '<div class="input-group" style="width:15%;text-align:center;">' . form_input('score', ($mit) ? $mit['score'] : '', 'class="form-control" id="score" placeholder="' . _l('fld_score') . '"') . '</div>'];

		$result['combo'] = $this->load->view('input-kpi', $data, true);
		header('Content-type: application/json');
		echo json_encode($result);
	}

	function simpan_kpi()
	{
		$post = $this->input->post();
		// dumps($post);
		// die();
		$this->data->post = $post;
		$this->data->simpan_kpi();
		$data['minggu'] = $post['minggu'];
		$data['id'] = $post['rcsa_id'];
		$this->list_kpi($data);
	}

	function kpi_delete()
	{
		$post = $this->input->post();
		$this->crud->crud_table(_TBL_RCSA_KPI);
		$this->crud->crud_type('delete');
		$this->crud->crud_where(['field' => 'id', 'value' => $post['edit_id']]);
		$this->crud->process_crud();
		$data['minggu'] = $post['minggu'];
		$data['id'] = $post['rcsa_id'];
		$data['del'] = true;
		$this->list_kpi($data);
	}

	function kri_delete()
	{
		$post = $this->input->post();
		$this->crud->crud_table(_TBL_RCSA_KPI);
		$this->crud->crud_type('delete');
		$this->crud->crud_where(['field' => 'id', 'value' => $post['edit_id']]);
		$this->crud->process_crud();
		$data = $post;
		// $data['id']=$post['edit_id'];
		// $this->kri_add($data);
		$data['del'] = true;
		$data['minggu'] = $post['minggu'];
		$data['id'] = $post['rcsa_id'];
		$this->list_kpi($data);
	}

	function optionalPersonalButton($button, $row)
	{
		$button = [];

		// if ($row['tgl_selesai_term'] != $row['tgl_akhir_mitigasi']) {
		// 	# code...

		// 	$button['propose'] = [
		// 		'label' => 'Update Progress',
		// 		'id' => 'btn_propose_one',
		// 		'class' => 'text-success',
		// 		'icon' => 'icon-file-spreadsheet',
		// 		'url' => base_url(_MODULE_NAME_ . '/progress/'),
		// 		'attr' => ' target="_self" '
		// 	];

		// 	if ($row['status_id_mitigasi'] == 0 || $row['status_final_mitigasi'] == 1) {
		// 		$button['progress'] = [
		// 			'label' => 'Propose Risiko',
		// 			'id' => 'btn_propose_one',
		// 			'class' => 'text-info propose-mitigasi',
		// 			'icon' => 'icon-file-spreadsheet',
		// 			'url' => base_url(_MODULE_NAME_ . '/propose/'),
		// 			'attr' => ['target' => '_self'],
		// 		];
		// 	}
		// }

		// if (array_key_exists($row['id'], $this->kpi)) {
		// 	$button['review'] = [
		// 		'label' => 'Review KPI & KRI',
		// 		'id' => 'btn_review_one',
		// 		'class' => 'text-danger review-kpi',
		// 		'icon' => 'icon-list',
		// 		'url' => base_url(_MODULE_NAME_ . '/review-kpi/'),
		// 		'type' => 'span',
		// 		'attr' => ['data-id' => $row['id']],
		// 	];
		// }

		// if ($row['status_id_mitigasi'] >= 1) {
		// 	if ($this->super_user) {
		// 		$button['reset'] = [
		// 			'label' => 'Reset Approval',
		// 			'id' => 'btn_reset_one',
		// 			'class' => 'text-warning reset-approval',
		// 			'icon' => 'icon-paste ',
		// 			'url' => base_url(_MODULE_NAME_ . '/reset-approval/'),
		// 			'attr' => ['data-id' => $row['id'], 'data-url' => 'reset-approval'],
		// 			'type' => 'span',
		// 		];
		// 	}
		// }

		return $button;
	}

	function optionalButton($button, $mode)
	{
		if ($mode == 'list') {
			// unset($button['delete']);
			// unset($button['print']);
			// unset($button['search']);



			$button['print']['detail']['lap'] = [
				'label' => 'Excel Laporan',
				'color' => 'bg-green',
				'id' => 'btn_lap',
				'tag' => 'a',
				'round' => ($this->configuration['round_button']) ? 'rounded-round' : '',
				'icon' => 'icon-import',
				'url' => '#!',

				'attr' => ' target="" data-url="' . base_url(_MODULE_NAME_ . '/export-lap/') . '"',
				'align' => 'left'
			];

			$button['print']['detail']['kri'] = [
				'label' => 'Excel KPI & KRI',
				'color' => 'bg-green',
				'id' => 'btn_kri',
				'tag' => 'a',
				'round' => ($this->configuration['round_button']) ? 'rounded-round' : '',
				'icon' => 'icon-import',
				'url' => '#!',

				'attr' => ' target="" data-url="' . base_url(_MODULE_NAME_ . '/export-kri/') . '"',
				'align' => 'left'
			];
		}

		return $button;
	}

	function export_lap()
	{
		$post = explode(",", $this->input->post('id'));
		$result = [];
		foreach ($post as $key => $value) {
			$result[] = base_url('/progress-mitigasi/cetak-lap/' . $value);
		}
		header('Content-type: application/json');
		echo json_encode($result);
	}

	function export_kri()
	{
		$post = explode(",", $this->input->post('id'));
		$result = [];
		foreach ($post as $key => $value) {
			$result[] = base_url('/progress-mitigasi/cetak-kri/' . $value);
		}
		header('Content-type: application/json');
		echo json_encode($result);
	}

	function cetak_lap($id)
	{
		$data = $this->data->get_data_monitoring($id);
		$data['id'] = $id;
		$data['export'] = false;
		$hasil = $this->load->view('risk_context/monitoring', $data, true);
		$n = $data['parent']['kode_dept'] . '-' . $data['parent']['term'] . '-' . $data['parent']['period_name'];

		$cetak = 'register_excel';
		$nm_file = 'Laporan-Progress-Mitigasi-' . $n;
		$this->$cetak($hasil, $nm_file);
	}

	function cetak_kri($id)
	{
		$data['export'] = false;

		$pos = $this->input->post();
		$rows = $this->db->where('id', $id)->get(_TBL_RCSA)->row_array();
		$pos['owner'] = $rows['owner_id'];
		$pos['period'] = $rows['period_id'];
		$pos['term'] = $rows['term_id'];
		$this->data->pos = $pos;

		$data = $this->data->get_detail_data();
		$firstKey = reset($data['data']);
		$bulKey = reset($firstKey['bulan']);
		$n = $data['parent']['owner_code'] . '-' . $bulKey['period'];

		$data['mode'] = 0;
		$data['id'] = $id;

		$x = $this->load->view('detail-kpi', $data, true);
		$y = $this->load->view('detail-kpi2', $data, true);
		$hasil = $x . $y;


		$cetak = 'register_excel';
		$nm_file = 'Review-KRI-' . $n;
		$this->$cetak($hasil, $nm_file);
	}

	function register_excel($data, $nm_file)
	{
		header("Content-type:appalication/vnd.ms-excel");
		header("content-disposition:attachment;filename=" . $nm_file . ".xls");

		$html = $data;
		echo $html;
		exit;
	}

	function reset_approval()
	{
		$post = $this->input->post();
		$hasil = [];

		$this->crud->crud_table(_TBL_RCSA);
		$this->crud->crud_type('edit');
		$this->crud->crud_field('status_revisi_mitigasi', 0, 'int');
		$this->crud->crud_field('status_id_mitigasi', 0, 'int');
		$this->crud->crud_field('status_final_mitigasi', 0, 'int');
		$this->crud->crud_field('minggu_id_mitigasi', 0, 'int');

		$this->crud->crud_field('note_propose_mitigasi', null);
		$this->crud->crud_field('param_approval_mitigasi', null);
		$this->crud->crud_field('tgl_propose_mitigasi', null);
		$this->crud->crud_where(['field' => 'id', 'value' => $post['id']]);
		$this->crud->process_crud();

		$this->crud->crud_table(_TBL_LOG_APPROVAL);
		$this->crud->crud_type('add');
		$this->crud->crud_field('rcsa_id', $post['id'], 'int');
		$this->crud->crud_field('keterangan', 'Approval dibatalkan oleh Admin');
		$this->crud->crud_field('note', 'Approval dibatalkan oleh Admin');
		$this->crud->crud_field('tanggal', date('Y-m-d H:i:s'), 'datetime');
		$this->crud->crud_field('user_id', $this->ion_auth->get_user_id());
		$this->crud->crud_field('penerima_id', 0);
		$this->crud->process_crud();
		header('Content-type: application/json');
		echo json_encode($hasil);
	}


	function form_update_aktifitas($post = [])
	{
		if (!$post) {
			$post = $this->input->post();
			$post['id'] = 0;
		}
		$id = $post['id'];
		$imitigasiId = $post['mit'];
		$mitdetail = $post['mitdetail'];
		$bln = intval($post['bln']);
		$periodeId = intval($post['periode']);
		$getMinggu = $this->db->where('period_id', $periodeId)->where('bulan_int', $bln)->get("il_view_minggu")->row_array();
		$x = $this->db->where('rcsa_mitigasi_detail_id', $mitdetail)->where('minggu_id', $getMinggu['id'])->get("il_rcsa_mitigasi_progres")->row_array();
		if (!$x) {
			$id = intval($this->uri->segment(3));
			$id_edit = 0;
		} else {

			$id_edit = $x['id'];
		}
		// doi::dump($post);
		$dp = $this->db->where('id', $id_edit)->get(_TBL_VIEW_RCSA_MITIGASI_PROGRES)->row_array();
		$am = $this->db->where('id', $mitdetail)->get(_TBL_VIEW_RCSA_MITIGASI_DETAIL)->row_array();

		$data['detail_progres'] = $this->db->where('rcsa_mitigasi_detail_id', $id)->get(_TBL_VIEW_RCSA_MITIGASI_PROGRES)->result_array();
		$data['aktifitas_mitigas'] = $am;
		$mit = $this->db->where('id', (isset($data['aktifitas_mitigas']['rcsa_mitigasi_id']) ? $data['aktifitas_mitigas']['rcsa_mitigasi_id'] : $imitigasiId))->get(_TBL_VIEW_RCSA_MITIGASI)->row_array();
		$mit = $this->convert_owner->set_data($mit, false)->set_param(['penanggung_jawab' => 'penanggung_jawab_id', 'koordinator' => 'koordinator_id'])->draw();
		$rcsa_detail = $this->db->where('id', $mit['rcsa_detail_id'])->get(_TBL_VIEW_RCSA_DETAIL)->row_array();
		$data['parent'] = $this->db->where('id', $rcsa_detail['rcsa_id'])->get(_TBL_VIEW_RCSA)->row_array();
		$trg = 1;
		$disabled = '';
		if (isset($am) && $am['batas_waktu_detail'] < date('Y-m-d')) {
			$trg = 100;
		}
		$data['minggu'] = $this->crud->combo_select(['id', 'concat(param_string, " ", YEAR(param_date)) as minggu'])->combo_where('kelompok', 'minggu')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();

		$aktual = '<div class="input-group" style="width:19% !important;"> <button type="button" onclick="this.parentNode.querySelector(\'[type=number]\').stepDown();"> - </button>';
		$aktual .= form_input(['type' => 'number', 'name' => 'aktual'], ($dp) ? $dp['aktual'] : '1', " class='form-control touchspin-postfix text-center' max='100' min='1' step='1' id='aktual' ");
		$aktual .= '<button type="button" onclick="this.parentNode.querySelector(\'[type=number]\').stepUp();"> + </button> <div class="form-control-feedback text-primary form-control-feedback-lg pointer" style="right:-25%;"> % </div></div>';

		$target = '<div class="input-group" style="width:19% !important;"> <button type="button" onclick="this.parentNode.querySelector(\'[type=number]\').stepDown();"> - </button>';
		$target .= form_input(['type' => 'number', 'name' => 'target'], ($dp) ? $dp['target'] : $trg, " '.$disabled.' class='form-control touchspin-postfix text-center' max='100' min='1' step='1' id='target' ");
		$target .= '<button type="button" onclick="this.parentNode.querySelector(\'[type=number]\').stepUp();"> + </button> <div class="form-control-feedback text-primary form-control-feedback-lg pointer" style="right:-25%;"> % </div></div>';

		$data['progres'][] = ['title' => "Tahun ", 'mandatori' => false, 'isi' => form_input('bln', ($getMinggu) ? $getMinggu['period'] : $bln, 'class="form-control " id="bln" disabled style="width:100%;"')];
		$data['progres'][] = [
			'title' => "Bulan ",
			'mandatori' => false,
			'isi' => form_input('bln', ($getMinggu) ? $getMinggu['param_string'] : $bln, 'class="form-control " id="bln" disabled style="width:100%;"')
				. form_hidden(['month' => $bln, 'id' => $bln])
				. form_hidden(['minggu' => $getMinggu['id']])
				. form_hidden(['periode' => $periodeId])
		];
		$data['progres'][] = ['title' => _l('fld_target'), 'help' => _h('help_target'), 'mandatori' => true, 'isi' => $target];
		$data['progres'][] = ['title' => _l('fld_aktual'), 'help' => _h('help_aktual'), 'mandatori' => true, 'isi' => $aktual];
		$data['progres'][] = ['title' => _l('fld_uraian'), 'help_popup' => false, 'help' => _h('help_uraian', '', true, false), 'mandatori' => true, 'isi' => form_textarea('uraian', ($dp) ? $dp['uraian'] : '', "required id='uraian' maxlength='500' size='500' class='form-control' style='overflow: hidden; width: 100% !important; height: 100px;' onblur='_maxLength(this , \"id_sisa_1\")' onkeyup='_maxLength(this , \"id_sisa_1\")' data-role='tagsinput'", true, ['size' => 1000, 'isi' => 0, 'no' => 1])];
		$data['progres'][] = ['title' => _l('fld_kendala'), 'help' => _h('help_kendala'), 'isi' => form_textarea('kendala', ($dp) ? $dp['kendala'] : '', "required id='kendala' maxlength='500' size='500' class='form-control' style='overflow: hidden; width: 100% !important; height: 100px;' onblur='_maxLength(this , \"id_sisa_2\")' onkeyup='_maxLength(this , \"id_sisa_2\")' data-role='tagsinput'", true, ['size' => 1000, 'isi' => 0, 'no' => 2])];
		$data['progres'][] = ['title' => _l('fld_tindak_lanjut'), 'help' => _h('help_tindak_lanjut'), 'isi' => form_textarea('tindak_lanjut', ($dp) ? $dp['tindak_lanjut'] : '', " id='tindak_lanjut' maxlength='500' size='500' class='form-control' style='overflow: hidden; width: 100% !important; height: 100px;' onblur='_maxLength(this , \"id_sisa_3\")' onkeyup='_maxLength(this , \"id_sisa_3\")' data-role='tagsinput'", true, ['size' => 1000, 'isi' => 0, 'no' => 3])];
		$data['progres'][] = ['title' => _l('fld_due_date'), 'help' => _h('help_due_date'), 'isi' => form_input('batas_waktu_tindak_lanjut', ($dp) ? $dp['batas_waktu_tindak_lanjut'] : '', 'class="form-control pickadate" id="batas_waktu_tindak_lanjut" style="width:100%;"')];

		$data['progres'][] = ['title' => _l('fld_keterangan'), 'help' => _h('help_keterangan'), 'isi' => form_textarea('keterangan', ($dp) ? $dp['keterangan'] : '', " id='keterangan' maxlength='500' size='500' class='form-control' style='overflow: hidden; width: 100% !important; height: 100px;' onblur='_maxLength(this , \"id_sisa_4\")' onkeyup='_maxLength(this , \"id_sisa_4\")' data-role='tagsinput'", true, ['size' => 1000, 'isi' => 0, 'no' => 4])];
		$data['progres'][] = ['title' => _l('fld_lampiran'), 'help' => _h('help_lampiran'), 'isi' => form_upload('lampiran')];
		$data['progres'][] = ['title' => '', 'help' => '', 'isi' => form_hidden(['aktifitas_mitigasi_id' => $mitdetail, 'id' => $id_edit])];

		$data['info_1'][] = ['title' => _l('fld_risiko_dept'), 'isi' => $rcsa_detail['risiko_dept']];
		$data['info_1'][] = ['title' => _l('fld_risiko_inherent'), 'isi' => $rcsa_detail['level_color']];
		$data['info_1'][] = ['title' => _l('fld_efek_kontrol'), 'isi' => $rcsa_detail['efek_kontrol_text']];
		$data['info_1'][] = ['title' => _l('fld_nama_control'), 'isi' => $rcsa_detail['nama_kontrol']];
		$data['info_1'][] = ['title' => _l('fld_level_risiko_residual'), 'isi' => $rcsa_detail['level_color_residual']];
		$data['info_1'][] = ['title' => _l('fld_treatment'), 'isi' => $rcsa_detail['treatment']];

		$data['info_2'][] = ['title' => _l('fld_mitigasi'), 'isi' => $mit['mitigasi']];
		$data['info_2'][] = ['title' => _l('fld_biaya'), 'isi' => number_format($mit['biaya'])];
		$data['info_2'][] = ['title' => _l('fld_pic'), 'isi' => $mit['penanggung_jawab']];
		$data['info_2'][] = ['title' => _l('fld_koordinator'), 'isi' => $mit['koordinator']];
		$data['info_2'][] = ['title' => _l('fld_due_date'), 'isi' => date('d-M-Y', strtotime($mit['batas_waktu']))];

		$data['update'] = $this->load->view('progres', $data, true);
		$data['id'] = $id;

		$result['title'] = "Update Aktifitas ";
		if (isset($mit['mitigasi']) && $mit['mitigasi'] !== '') {
			$result['title'] .= ': ' . $am['aktifitas_mitigasi'];
		}
		$result['combo'] = $this->load->view('progres', $data, true);
		header('Content-type: application/json');
		echo json_encode($result);
	}

	function edit_identifikasi($id = 0, $edit = 0)
	{
		$mode = 'save';
		if (empty($id)) {
			$mode = 'edit';
			$id   = intval($this->input->post('id'));
		}
		if (empty($edit)) {
			$edit = intval($this->input->post('edit'));
		}
		$this->db->delete(_TBL_RCSA_DET_LIKE_INDI, ['rcsa_detail_id' => 0, 'created_by' => $this->ion_auth->get_user_name()]);
		$this->db->delete(_TBL_RCSA_DET_DAMPAK_INDI, ['rcsa_detail_id' => 0, 'created_by' => $this->ion_auth->get_user_name()]);

		$data['parent']    = $this->db->where('id', $id)->get(_TBL_VIEW_RCSA)->row_array();
		$rcsa_detail       = $this->db->where('id', $edit)->get(_TBL_VIEW_RCSA_DETAIL)->row_array();
		$data['mode']      = 1; //'Mode : Update data';
		$data['mode_text'] = _l('fld_mode_edit'); //'Mode : Update data';
		$cbominggu         = $this->data->get_data_minggu($data['parent']['term_id']);
		$minggu            = ($data['parent']['minggu_id']) ? $cbominggu[$data['parent']['minggu_id']] : '';
		// 
		$data['parent']['bulan'] = (isset($this->term[$data['parent']['term_id']])) ? $this->term[$data['parent']['term_id']] . ' - ' . $minggu : '-';
		$data['info_parent']     = $this->load->view('info-parent', $data, TRUE);
		$data['detail']          = $this->identifikasi_content($rcsa_detail, $data['parent']);
		$data['identifikasi']    = $this->load->view('identifikasi-risiko', $data, TRUE);
		$data['analisa']         = $this->load->view('analisa-risiko', $data, TRUE);

		$data['rcsa_detail'] = $rcsa_detail;

		$rows = $this->db->select('rcsa_mitigasi_id as id, count(rcsa_mitigasi_id) as jml')->group_by(['rcsa_mitigasi_id'])->where('rcsa_detail_id', $edit)->get(_TBL_VIEW_RCSA_MITIGASI_DETAIL)->result_array();
		$miti = [];
		foreach ($rows as $row) {
			$miti[$row['id']] = $row['jml'];
		}
		$rows = $this->db->where('rcsa_detail_id', $edit)->get(_TBL_VIEW_RCSA_MITIGASI)->result_array();
		foreach ($rows as &$row) {
			if (array_key_exists($row['id'], $miti)) {
				$row['jml'] = $miti[$row['id']];
			}
		}
		unset($row);
		$rows = $this->convert_owner->set_data($rows)->set_param(['penanggung_jawab' => 'penanggung_jawab_id', 'koordinator' => 'koordinator_id'])->draw();

		$data['picku'] = $this->get_data_dept();

		$data['mitigasi'] = $rows;

		$data['d_evaluasi']    = $this->evaluasi_content($rcsa_detail);
		$data['d_target']      = $this->target_content($rcsa_detail);
		$data['list_mitigasi'] = $this->load->view('list-mitigasi', $data, TRUE);
		$data['evaluasi']      = $this->load->view('evaluasi-risiko', $data, TRUE);
		$data['target']        = $this->load->view('target-risiko', $data, TRUE);
		$data['hidden']        = ['rcsa_id' => $id, 'rcsa_detail_id' => $edit];
		// $data['detvail']          = $this->identifikasi_content($rcsa_detail, $data['parent']);
		$hasil['combo']        = $this->load->view('update-monitoring', $data, TRUE);
		return $hasil;
	}
	function evaluasi_content($data = [])
	{
		$disabled = ' disabled="disabled" ';
		$aspek = 0;
		if ($data) {
			if ($data['tipe_analisa_no'] == 2 || $data['tipe_analisa_no'] == 3) {
				$aspek = $data['aspek_risiko_id'];
			} else {

				$aspek = 0;
			}
		}
		if ($aspek) {
			$like = $this->crud->combo_select(['urut', 'concat(urut,\' - \',data) as x'])->combo_where('active', 1)->combo_where('pid', $aspek)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		} else {
			$like = $this->crud->combo_select(['id', 'concat(code,\' - \',level) as x'])->combo_where('active', 1)->combo_where('category', 'likelihood')->combo_tbl(_TBL_LEVEL)->get_combo()->result_combo();
		}
		$impact        = $this->crud->combo_select(['id', 'concat(code,\' - \',level) as x'])->combo_where('active', 1)->combo_where('category', 'impact')->combo_tbl(_TBL_LEVEL)->get_combo()->result_combo();
		$treatment     = $this->crud->combo_select(['id', 'treatment'])->combo_where('active', 1)->combo_tbl(_TBL_TREATMENT)->get_combo()->result_combo();
		$efek_mitigasi = [0 => _l('cbo_select'), 1 => 'L', 2 => 'D', 3 => 'L & D', 4 => 'Tidak ada mitigasi'];

		$aspek_risiko      = $this->crud->combo_select(['id', 'data'])->combo_where('active', 1)->combo_where('kelompok', 'aspek-risiko')->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$parent            = $this->db->where('id', $data['rcsa_id'])->get(_TBL_VIEW_RCSA)->row_array();
		$csslevel          = '';
		$csslevel_inherent = '';
		if ($data) {
			$csslevel          = 'background-color:' . $data['color_residual'] . ';color:' . $data['color_text_residual'] . ';';
			$csslevel_inherent = 'background-color:' . $data['color'] . ';color:' . $data['color_text'] . ';';
		}
		if (!empty($data['nama_kontrol_note']) && !empty($data['nama_kontrol'])) {
			$data['nama_kontrol'] .= '###' . $data['nama_kontrol_note'];
		} else {
			$data['nama_kontrol'] .= $data['nama_kontrol_note'];
		}
		$y       = explode('###', $data['nama_kontrol']);
		$control = '<ul>';
		foreach ($y as $x) {
			$control .= '<li>' . $x . '</li>';
		}
		// strip_tags($x, '<p><ol><ul><li>')
		$control .= '</ul>';

		$l_events = 'auto';
		$i_events = 'auto';
		// doi::dump("efek_kontrol: ".$data['efek_kontrol']);
		// doi::dump("impact_residual: ". $data['impact_residual']);
		// doi::dump("like_id: ". $data['like_id']);
		// doi::dump("efek_kontrol: ". $data['efek_kontrol']);
		if ($data['efek_kontrol'] == 1) {
			$l = form_dropdown('like_residual_id', $like, ($data['like_residual_id'] > 0) ? $data['like_residual_id'] : $data['like_id'], 'id="like_residual_id" class="form-control select" style="width:100%;"' . $disabled);
			$i = form_dropdown('impact_residual_id', $impact, ($data['impact_residual_id'] > 0) ? $data['impact_residual_id'] : $data['impact_id'], 'id="impact_residual_id" disabled readonly="readonly" class="form-control select"  style="width:100%;"' . $disabled) .

				// $i = form_input('impact_residual', ($data['impact_residual']>0) ? $data['impact_residual'] : $data['impact_id'], 'id="impact_residual" class="form-control" readonly="readonly" style="width:100%;"') .
				form_input(['type' => 'hidden', 'name' => 'impact_residual_id', 'id' => 'impact_residual_id', 'value' => ($data['impact_residual_id'] != 0) ? $data['impact_residual_id'] : $data['impact_id']]);

			$l_events = 'none';
		} elseif ($data['efek_kontrol'] == 2) {
			$l = form_dropdown('like_residual_id', $like, ($data['like_residual_id'] > 0) ? $data['like_residual_id'] : $data['like_id'], 'id="like_residual_id" readonly="readonly" disabled class="form-control select" style="width:100%;"' . $disabled) .

				// $l = form_input('like_residual', ($data['like_residual']>0) ? $data['like_residual'] : $data['like_id'], 'id="like_residual" class="form-control" readonly="readonly" style="width:100%;"') . 
				form_input(['type' => 'hidden', 'name' => 'like_residual_id', 'id' => 'like_residual_id', 'value' => ($data) ? $data['like_residual_id'] : $data['like_id']]);
			$i = form_dropdown('impact_residual_id', $impact, ($data['impact_residual_id'] > 0) ? $data['impact_residual_id'] : $data['impact_id'], 'id="impact_residual_id" class="form-control select" style="width:100%;"' . $disabled);
			// $l3=form_input('like_residual_3', ($data)?$data['like_residual']:'', 'id="like_residual_3" class="form-control" readonly="readonly" style="width:100%;"').form_input(['type'=>'hidden','name'=>'like_residual_id_3','id'=>'like_residual_id_3','value'=>($data)?$data['like_residual_id']:0]);
			// $i3=form_dropdown('impact_residual_id_3', $impact, ($data)?$data['impact_residual_id']:'', 'id="impact_residual_id_3" class="form-control select" style="width:100%;"');
			$i_events = 'none';
		} elseif ($data['efek_kontrol'] == 4) {
			$l = form_input('like_residual', ($data['like_residual'] != 0) ? $data['like_residual'] : $data['like_id'], 'id="like_residual" class="form-control" readonly="readonly" style="width:100%;"' . $disabled) .
				form_input(['type' => 'hidden', 'name' => 'like_residual_id', 'id' => 'like_residual_id', 'value' => ($data) ? $data['like_residual_id'] : $data['like_id']]);
			$i = form_input('impact_residual', ($data['impact_residual'] != 0) ? $data['impact_residual'] : $data['impact_id'], 'id="impact_residual" class="form-control" readonly="readonly" style="width:100%;"') .
				form_input(['type' => 'hidden', 'name' => 'impact_residual_id', 'id' => 'impact_residual_id', 'value' => ($data) ? $data['impact_residual_id'] : $data['impact_id']]);
			// $l3=form_dropdown('like_residual_id_3', $like, ($data)?$data['like_residual_id']:'', 'id="like_residual_id_3" class="form-control select" style="width:100%;"');
			// $i3=form_dropdown('impact_residual_id_3', $impact, ($data)?$data['impact_residual_id']:'', 'id="impact_residual_id_3" class="form-control select" style="width:100%;"');
			$l_events = 'none';
		} else {
			$l = form_dropdown('like_residual_id', $like, ($data['like_residual_id'] > 0) ? $data['like_residual_id'] : $data['like_id'], 'id="like_residual_id" class="form-control select" style="width:100%;"' . $disabled);

			$i = form_input('impact_residual', ($data['impact_residual'] > 0) ? $data['impact_residual'] : $data['impact_id'], 'id="impact_residual" class="form-control" readonly="readonly" style="width:100%;"' . $disabled);
			// $l3=form_dropdown('like_residual_id_3', $like, ($data)?$data['like_residual_id']:'', 'id="like_residual_id_3" class="form-control select" style="width:100%;"');
			// $i3=form_dropdown('impact_residual_id_3', $impact, ($data)?$data['impact_residual_id']:'', 'id="impact_residual_id_3" class="form-control select" style="width:100%;"');
		}

		// doi::dump($data['tipe_analisa_no']);
		if ($data['tipe_analisa_no'] == 2) {
			$param['evaluasi'][] = ['title' => '', 'help' => '', 'isi' => '<span class="btn btn-primary legitRipple pointer" data-rcsa="' . $parent['id'] . '" data-id="' . $data['id'] . '" data-control="' . $data['efek_kontrol'] . '" id="indikator_like_residual" style="width:100%;pointer-events:' . $i_events . '"> Input Risk Indikator Likelihood </span>'];

			$param['evaluasi'][] = ['title' => '', 'help' => '', 'isi' => '<span class="btn btn-primary legitRipple pointer"  data-rcsa="' . $parent['id'] . '" data-id="' . $data['id'] . '" data-control="' . $data['efek_kontrol'] . '" id="indikator_dampak_residual" style="width:100%;pointer-events:' . $l_events . '"> Input Risk Indikator Dampak </span>'];

			$param['evaluasi'][] = ['title' => _l('fld_likelihood_residual'), 'help' => _h('help_likelihood'), 'isi' => form_input('like_text_kuantitatif_residual', ($data) ? $data['like_residual'] : '', 'id="like_text_kuantitatif_residual" class="form-control" style="width:100%;" readonly="readonly"') . form_hidden(['like_residual_id' => ($data) ? $data['like_residual_id'] : ''])];

			$param['evaluasi'][] = ['title' => _l('fld_impact_residual'), 'help' => _h('help_impact_residual'), 'isi' => form_input('impact_text_kuantitatif_residual', ($data) ? $data['impact_residual'] : '', 'id="impact_text_kuantitatif_residual" class="form-control" style="width:100%;" readonly="readonly"') . form_hidden(['impact_residual_id' => ($data) ? $data['impact_residual_id'] : ''])];

			$param['evaluasi'][] = ['title' => _l('fld_risiko_residual'), 'help' => _h('help_risiko_residual'), 'isi' => form_input('risiko_residual_text', ($data) ? $data['risiko_residual_text'] : '', 'class="form-control text-center" id="risiko_residual_text" readonly="readonly" style="width:15%;"') . form_hidden(['risiko_residual' => ($data) ? $data['risiko_residual'] : 0])];

			$param['evaluasi'][] = ['title' => _l('fld_level_risiko_residual'), 'help' => _h('help_level_risiko'), 'isi' => form_input('level_residual_text', ($data) ? $data['level_color_residual'] : '', 'class="form-control text-center" id="level_residual_text" readonly="readonly" style="width:30%;' . $csslevel . '"') . form_hidden(['level_residual' => ($data) ? $data['level_residual'] : 0, 'sts_save_evaluasi' => ($data) ? $data['sts_save_evaluasi'] : 0])];
		} elseif ($data['tipe_analisa_no'] == 1) {
			$param['evaluasi'][] = ['title' => _l('fld_likelihood_residual'), 'help' => _h('help_likelihood_residual'), 'isi' => $l];
			$param['evaluasi'][] = ['title' => _l('fld_impact_residual'), 'help' => _h('help_impact_residual'), 'isi' => $i];
			$param['evaluasi'][] = ['title' => _l('fld_risiko_residual'), 'help' => _h('help_risiko_residual'), 'isi' => form_input('risiko_residual_text', ($data) ? $data['risiko_residual_text'] : '', 'class="form-control text-center" id="risiko_residual_text" readonly="readonly" style="width:15%;"') . form_hidden(['risiko_residual' => ($data) ? $data['risiko_residual'] : 0])];
			$param['evaluasi'][] = ['title' => _l('fld_level_risiko_residual'), 'help' => _h('help_level_risiko'), 'isi' => form_input('level_residual_text', ($data) ? $data['level_color_residual'] : '', 'class="form-control text-center" id="level_residual_text" readonly="readonly" style="width:30%;' . $csslevel . '"') . form_hidden(['level_residual' => ($data) ? $data['level_residual'] : 0, 'sts_save_evaluasi' => ($data) ? $data['sts_save_evaluasi'] : 0])];
		} elseif ($data['tipe_analisa_no'] == 3) {
			$param['evaluasi'][] = ['title' => _l('fld_aspek_risiko'), 'help' => _h('help_aspek_risiko'), 'isi' => form_dropdown('aspek_risiko_id_3', $aspek_risiko, ($data) ? $data['aspek_risiko_id'] : '', 'id="aspek_risiko_id_3" class="form-control select" style="width:100%;" disabled="disabled"' . $disabled)];

			// $param['evaluasi'][] = ['title'=>_l('fld_likelihood_residual'),'help'=>_h('help_likelihood_residual'),'isi'=>$l];

			$urutTemp = [1, 7, 8, 9, 10];

			$like_semi_form = '<select name="like_residual_id" id="like_residual_id_3" class="form-control select" style="width:100%;">';
			if (! empty($like)) {

				foreach ($like as $key => $value) {
					$sel            = ($data) ? $data['like_residual_id'] : '';
					$selected       = ($sel == $key) ? 'selected' : '';
					$k              = intval($key) - 1;
					$dataTemp       = (isset($urutTemp[$k])) ? $urutTemp[$k] : 0;
					$like_semi_form .= '<option data-temp="' . $dataTemp . '" value="' . $key . '"' . $selected . '>' . $value . '</option>';
				}
			}
			$like_semi_form .= '</select>';

			$param['evaluasi'][] = ['title' => _l('fld_likelihood_residual'), 'help' => _h('help_likelihood_residual'), 'isi' => $like_semi_form];

			// form_dropdown('like_id_3', $like_semi, ($data)?$data['like_id']:'', 'id="like_id_3" class="form-control select" style="width:100%;"')


			$param['evaluasi'][] = ['title' => '', 'help' => '', 'isi' => '<span class="btn btn-primary legitRipple pointer"  data-rcsa="' . $parent['id'] . '" data-id="' . $data['id'] . '" data-control="' . $data['efek_kontrol'] . '" id="indikator_dampak_residual" style="width:100%;pointer-events:' . $l_events . '"> Input Risk Indikator Dampak </span>'];

			// $param['evaluasi'][] = ['title'=>_l('fld_impact_residual'),'help'=>_h('help_impact_residual'),'isi'=>$i];

			$param['evaluasi'][] = ['title' => _l('fld_impact_residual'), 'help' => _h('help_impact_residual'), 'isi' => form_input('impact_text_kuantitatif_residual', ($data) ? $data['impact_residual'] : '', 'id="impact_text_kuantitatif_residual" class="form-control" style="width:100%;" readonly="readonly"') . form_hidden(['impact_residual_id' => ($data) ? $data['impact_residual_id'] : ''])];


			$param['evaluasi'][] = ['title' => _l('fld_risiko_residual'), 'help' => _h('help_risiko_residual'), 'isi' => form_input('risiko_residual_text', ($data) ? $data['risiko_residual_text'] : '', 'class="form-control text-center" id="risiko_residual_text" readonly="readonly" style="width:15%;"') . form_hidden(['risiko_residual' => ($data) ? $data['risiko_residual'] : 0])];
			$param['evaluasi'][] = ['title' => _l('fld_level_risiko_residual'), 'help' => _h('help_level_risiko'), 'isi' => form_input('level_residual_text', ($data) ? $data['level_color_residual'] : '', 'class="form-control text-center" id="level_residual_text" readonly="readonly" style="width:30%;' . $csslevel . '"') . form_hidden(['level_residual' => ($data) ? $data['level_residual'] : 0, 'sts_save_evaluasi' => ($data) ? $data['sts_save_evaluasi'] : 0])];
		}
		$param['evaluasi'][] = ['title' => _l('fld_treatment'), 'help' => _h('help_treatment'), 'mandatori' => TRUE, 'isi' => form_dropdown('treatment_id', $treatment, ($data) ? $data['treatment_id'] : '', 'class="form-control select" id="treatment_id" style="width:100%;"' . $disabled)];

		$param['evaluasi'][] = ['title' => _l('fld_efek_mitigasi'), 'help' => _h('help_efek_mitigasi'), 'mandatori' => TRUE, 'isi' => form_dropdown('efek_mitigasi', $efek_mitigasi, ($data) ? $data['efek_mitigasi'] : '', 'id="efek_mitigasi" class="form-control select" style="width:100%;"' . $disabled)];


		$param['info'][] = ['title' => _l('fld_risiko_dept'), 'isi' => $data['risiko_dept']];
		$param['info'][] = ['title' => _l('fld_level_risiko'), 'isi' => form_input('level_inherent_info', ($data) ? $data['risiko_inherent_text'] : '', 'class="form-control text-center" id="level_inherent_info" readonly="readonly"  style="width:40%;"') . '<span class="input-group-append">
		<button class="btn btn-light legitRipple" type="button" style="' . $csslevel_inherent . '">' . $data['level_color'] . '</button>
		</span>'];
		$param['info'][] = ['title' => _l('fld_nama_control'), 'isi' => $control];
		$param['info'][] = ['title' => _l('fld_efek_kontrol'), 'isi' => $data['efek_kontrol_text']];

		return $param;
	}

	function target_content($data = [])
	{

		$aspek = 0;
		if ($data) {
			if ($data['tipe_analisa_no'] == 2 || $data['tipe_analisa_no'] == 3) {
				$aspek = $data['aspek_risiko_id'];
			} else {

				$aspek = 0;
			}
		}
		if ($aspek) {
			$like = $this->crud->combo_select(['urut', 'concat(urut,\' - \',data) as x'])->combo_where('active', 1)->combo_where('pid', $aspek)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		} else {
			$like = $this->crud->combo_select(['id', 'concat(code,\' - \',level) as x'])->combo_where('active', 1)->combo_where('category', 'likelihood')->combo_tbl(_TBL_LEVEL)->get_combo()->result_combo();
		}
		$aspek_risiko = $this->crud->combo_select(['id', 'data'])->combo_where('active', 1)->combo_where('kelompok', 'aspek-risiko')->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$impact       = $this->crud->combo_select(['id', 'concat(code,\' - \',level) as x'])->combo_where('active', 1)->combo_where('category', 'impact')->combo_tbl(_TBL_LEVEL)->get_combo()->result_combo();
		$treatment    = $this->crud->combo_select(['id', 'treatment'])->combo_where('active', 1)->combo_tbl(_TBL_TREATMENT)->get_combo()->result_combo();
		$parent       = $this->db->where('id', $data['rcsa_id'])->get(_TBL_VIEW_RCSA)->row_array();

		$csslevel          = '';
		$csslevel_inherent = '';
		if ($data) {
			$bg       = ($data['color_target']) ? $data['color_target'] : $data['color_residual'];
			$clr      = ($data['color_text_target']) ? $data['color_text_target'] : $data['color_text_residual'];
			$csslevel = 'background-color:' . $bg . ';color:' . $clr . ';';

			$csslevel_inherent = 'background-color:' . $data['color_residual'] . ';color:' . $data['color_text_residual'] . ';';
		}

		$y       = explode('###', $data['nama_kontrol']);
		$control = '';
		foreach ($y as $x) {
			$control .= '- ' . $x . '<br/>';
		}

		$l_events = 'auto';
		$i_events = 'auto';
		if ($data['efek_mitigasi'] == 1) {
			$l        = form_dropdown('like_target_id', $like, ($data['like_target_id']) ? $data['like_target_id'] : $data['like_residual_id'], 'id="like_target_id" class="form-control select" style="width:100%;"');
			$i        = form_input('impact_target', ($data['impact_target']) ? $data['impact_target'] : $data['impact_residual'], 'id="impact_target" class="form-control" readonly="readonly" style="width:100%;"') . form_input(['type' => 'hidden', 'name' => 'impact_target_id', 'id' => 'impact_target_id', 'value' => ($data['impact_target_id']) ? $data['impact_target_id'] : $data['impact_residual_id']]);
			$l_events = 'none';
		} elseif ($data['efek_mitigasi'] == 2) {
			$l        = form_input('like_target', ($data['like_target']) ? $data['like_target'] : $data['like_residual'], 'id="like_target" class="form-control" readonly="readonly" style="width:100%;"') . form_input(['type' => 'hidden', 'name' => 'like_target_id', 'id' => 'like_target_id', 'value' => ($data['like_target_id']) ? $data['like_target_id'] : $data['like_residual_id']]);
			$i        = form_dropdown('impact_target_id', $impact, ($data['impact_target_id']) ? $data['impact_target_id'] : $data['impact_residual_id'], 'id="impact_target_id" class="form-control select" style="width:100%;"');
			$i_events = 'none';
		} elseif ($data['efek_mitigasi'] == 4) {
			$l        = form_input('like_target', ($data['like_target']) ? $data['like_target'] : $data['like_residual'], 'id="like_target" class="form-control" readonly="readonly" style="width:100%;"') . form_input(['type' => 'hidden', 'name' => 'like_target_id', 'id' => 'like_target_id', 'value' => ($data['like_target_id']) ? $data['like_target_id'] : $data['like_residual_id']]);
			$i        = form_input('impact_target', ($data['impact_target']) ? $data['impact_target'] : $data['impact_residual'], 'id="impact_target" class="form-control" readonly="readonly" style="width:100%;"') . form_input(['type' => 'hidden', 'name' => 'impact_target_id', 'id' => 'impact_target_id', 'value' => ($data['impact_target_id']) ? $data['impact_target_id'] : $data['impact_residual_id']]);
			$i_events = 'none';
		} else {
			$l = form_dropdown('like_target_id', $like, ($data['like_target_id']) ? $data['like_target_id'] : $data['like_residual_id'], 'id="like_target_id" class="form-control select" style="width:100%;"');
			$i = form_dropdown('impact_target_id', $impact, ($data['impact_target_id']) ? $data['impact_target_id'] : $data['impact_residual_id'], 'id="impact_target_id" class="form-control select" style="width:100%;"');
		}

		if ($data['tipe_analisa_no'] == 2) {
			$param['target'][] = ['title' => '', 'help' => '', 'isi' => '<span class="btn btn-primary legitRipple pointer" data-rcsa="' . $parent['id'] . '" data-id="' . $data['id'] . '" data-control="' . $data['efek_kontrol'] . '" id="indikator_like_target" style="width:100%;pointer-events:' . $i_events . '"> Input Risk Indikator Likelihood </span>'];

			$param['target'][] = ['title' => '', 'help' => '', 'isi' => '<span class="btn btn-primary legitRipple pointer"  data-rcsa="' . $parent['id'] . '" data-id="' . $data['id'] . '" data-control="' . $data['efek_kontrol'] . '" id="indikator_dampak_target" style="width:100%;pointer-events:' . $l_events . '"> Input Risk Indikator Dampak </span>'];
			$param['target'][] = ['title' => _l('fld_likelihood_target'), 'help' => _h('help_likelihood'), 'isi' => form_input('like_text_kuantitatif_targetl', ($data) ? $data['like_target'] : '', 'id="like_text_kuantitatif_target" class="form-control" style="width:100%;" readonly="readonly"') . form_hidden(['like_target_id' => ($data) ? $data['like_target_id'] : ''])];

			$param['target'][] = ['title' => _l('fld_impact_target'), 'help' => _h('help_impact_target'), 'isi' => form_input('impact_text_kuantitatif_target', ($data) ? $data['impact_target'] : '', 'id="impact_text_kuantitatif_target" class="form-control" style="width:100%;" readonly="readonly"') . form_hidden(['impact_target_id' => ($data) ? $data['impact_target_id'] : ''])];

			$param['target'][] = ['title' => _l('fld_risiko_target'), 'help' => _h('help_risiko_target'), 'isi' => form_input('risiko_target_text', ($data['risiko_target_text']) ? $data['risiko_target_text'] : $data['risiko_residual_text'], 'class="form-control text-center" id="risiko_target_text" readonly="readonly" style="width:15%;"') . form_hidden(['risiko_target' => ($data['risiko_target']) ? $data['risiko_target'] : $data['risiko_residual']])];
			$param['target'][] = ['title' => _l('fld_level_risiko_target'), 'help' => _h('help_level_risiko'), 'isi' => form_input('level_target_text', ($data['level_color_target']) ? $data['level_color_target'] : $data['level_color_residual'], 'class="form-control text-center" id="level_target_text" readonly="readonly" style="width:30%;' . $csslevel . '"') . form_hidden(['level_target' => ($data['level_target']) ? $data['level_target'] : $data['level_residual']])];
		} elseif ($data['tipe_analisa_no'] == 1) {
			$param['target'][] = ['title' => _l('fld_likelihood_target'), 'help' => _h('help_likelihood_target'), 'isi' => $l];
			$param['target'][] = ['title' => _l('fld_impact_target'), 'help' => _h('help_impact_target'), 'isi' => $i];
			$param['target'][] = ['title' => _l('fld_risiko_target'), 'help' => _h('help_risiko_target'), 'isi' => form_input('risiko_target_text', ($data['risiko_target_text']) ? $data['risiko_target_text'] : $data['risiko_residual_text'], 'class="form-control text-center" id="risiko_target_text" readonly="readonly" style="width:15%;"') . form_hidden(['risiko_target' => ($data['risiko_target']) ? $data['risiko_target'] : $data['risiko_residual']])];
			$param['target'][] = ['title' => _l('fld_level_risiko_target'), 'help' => _h('help_level_risiko'), 'isi' => form_input('level_target_text', ($data['level_color_target']) ? $data['level_color_target'] : $data['level_color_residual'], 'class="form-control text-center" id="level_target_text" readonly="readonly" style="width:30%;' . $csslevel . '"') . form_hidden(['level_target' => ($data['level_target']) ? $data['level_target'] : $data['level_residual']])];
		} elseif ($data['tipe_analisa_no'] == 3) {
			$param['target'][] = ['title' => _l('fld_aspek_risiko'), 'help' => _h('help_aspek_risiko'), 'isi' => form_dropdown('aspek_risiko_id_3', $aspek_risiko, ($data) ? $data['aspek_risiko_id'] : '', 'id="aspek_risiko_id_3" class="form-control select" style="width:100%;" disabled="disabled"')];


			$urutTemp = [1, 7, 8, 9, 10];

			$like_semi_form = '<select name="like_target_id" id="like_target_id_3" class="form-control select" style="width:100%;">';
			if (! empty($like)) {

				foreach ($like as $key => $value) {
					$sel            = ($data) ? $data['like_target_id'] : '';
					$selected       = ($sel == $key) ? 'selected' : '';
					$k              = intval($key) - 1;
					$dataTemp       = (isset($urutTemp[$k])) ? $urutTemp[$k] : 0;
					$like_semi_form .= '<option data-temp="' . $dataTemp . '" value="' . $key . '"' . $selected . '>' . $value . '</option>';
				}
			}
			$like_semi_form .= '</select>';

			$param['target'][] = ['title' => _l('fld_likelihood_target'), 'help' => _h('help_likelihood_target'), 'isi' => $like_semi_form];

			// $param['target'][] = ['title'=>_l('fld_likelihood_target'),'help'=>_h('help_likelihood_target'),'isi'=>$l];

			// $l_events='auto';

			$param['target'][] = ['title' => '', 'help' => '', 'isi' => '<span class="btn btn-primary legitRipple pointer"  data-rcsa="' . $parent['id'] . '" data-id="' . $data['id'] . '" data-control="' . $data['efek_kontrol'] . '" id="indikator_dampak_target" style="width:100%;pointer-events:' . $l_events . '"> Input Risk Indikator Dampak </span>'];

			// $param['target'][] = ['title'=>_l('fld_impact_target'),'help'=>_h('help_impact_target'),'isi'=>$i];

			$param['target'][] = ['title' => _l('fld_impact_target'), 'help' => _h('help_impact_target'), 'isi' => form_input('impact_text_kuantitatif_target', ($data) ? $data['impact_target'] : '', 'id="impact_text_kuantitatif_target" class="form-control" style="width:100%;" readonly="readonly"') . form_hidden(['impact_target_id' => ($data) ? $data['impact_target_id'] : ''])];


			$param['target'][] = ['title' => _l('fld_risiko_target'), 'help' => _h('help_risiko_target'), 'isi' => form_input('risiko_target_text', ($data['risiko_target_text']) ? $data['risiko_target_text'] : $data['risiko_residual_text'], 'class="form-control text-center" id="risiko_target_text" readonly="readonly" style="width:15%;"') . form_hidden(['risiko_target' => ($data['risiko_target']) ? $data['risiko_target'] : $data['risiko_residual']])];
			$param['target'][] = ['title' => _l('fld_level_risiko_target'), 'help' => _h('help_level_risiko'), 'isi' => form_input('level_target_text', ($data['level_color_target']) ? $data['level_color_target'] : $data['level_color_residual'], 'class="form-control text-center" id="level_target_text" readonly="readonly" style="width:30%;' . $csslevel . '"') . form_hidden(['level_target' => ($data['level_target']) ? $data['level_target'] : $data['level_residual']])];
		}

		// $param['target'][] = ['title'=>_l('fld_treatment'),'help'=>_h('help_treatment'),'isi'=>form_dropdown('treatment_id', $treatment, ($data)?$data['treatment_id']:'', 'class="form-control select" id="treatment_id" style="width:100%;"')];

		$param['info'][] = ['title' => _l('fld_risiko_dept'), 'isi' => $data['risiko_dept']];
		$param['info'][] = ['title' => _l('fld_level_risiko_residual'), 'isi' => form_input('level_target_info', ($data) ? $data['risiko_residual_text'] : '', 'class="form-control text-center" id="level_target_info" readonly="readonly"  style="width:40%;"') . '<span class="input-group-append">
		<button class="btn btn-light legitRipple" type="button" style="' . $csslevel_inherent . '">' . $data['level_color_residual'] . '</button>
		</span>'];
		// $param['info'][] = ['title'=>_l('fld_mitigasi'),'isi'=>$control];
		$param['info'][] = ['title' => _l('fld_efek_mitigasi'), 'isi' => $data['efek_mitigasi_text']];

		return $param;
	}


	function identifikasi_content($data = [], $parent = [])
	{
		$uriId = intval($this->uri->segment(3));
		$uriMonth = intval($this->uri->segment(4));
		$residual = $this->db->where('rcsa_detail_id', $uriId)->where('month', $uriMonth)->get("il_update_residual")->row_array();

		// doi::dump($residual);
		$mode    = 'add';
		$id_edit = 0;
		if ($data) {
			$mode    = 'edit';
			$id_edit = $data['id'];
		}
		$disabled = 'disabled';

		$jml_like_indi   = $this->db->where('bk_tipe', 1)->where('rcsa_detail_id', intval($id_edit))->or_group_start()->where('rcsa_detail_id', 0)->where('created_by', $this->ion_auth->get_user_name())->group_end()->get(_TBL_VIEW_RCSA_DET_LIKE_INDI)->num_rows();
		$jml_dampak_indi = $this->db->where('bk_tipe', 1)->where('rcsa_detail_id', intval($id_edit))->or_group_start()->where('rcsa_detail_id', 0)->where('created_by', $this->ion_auth->get_user_name())->group_end()->get(_TBL_VIEW_RCSA_DET_DAMPAK_INDI)->num_rows();


		$kpi = $this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'kpi')->combo_where('param_text like ', '%' . $parent['owner_id'] . '%')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();


		$aktivitas = $this->crud->combo_select(['id', 'concat(kode,\' - \',data) as data'])->combo_where('pid', intval($parent['owner_id']))->combo_where('kelompok', 'aktivitas')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$sasaran   = $this->crud->combo_select(['id', 'data'])->combo_where('pid', intval($parent['owner_id']))->combo_where('kelompok', 'sasaran-aktivitas')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();


		$tahapan = $this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'tahapan-proses')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$kel     = $this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'lib-cat')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();

		$risk_type = [_l('cbo_select')];
		if (isset($data['klasifikasi_risiko_id'])) {
			$risk_type = $this->crud->combo_select(['id', 'data'])->combo_where('pid', $data['klasifikasi_risiko_id'])->combo_where('kelompok', 'risk-type')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		}
		$penyebab_id  = $this->crud->combo_select(['id', 'library'])->combo_where('type', 1)->combo_where('active', 1)->combo_tbl(_TBL_LIBRARY)->get_combo()->result_combo();
		$peristiwa_id  = $this->crud->combo_select(['id', 'library'])->combo_where('type', 2)->combo_where('active', 1)->combo_tbl(_TBL_VIEW_LIBRARY_DETAIL)->get_combo()->result_combo();
		$dampak_id    = $this->crud->combo_select(['id', 'library'])->combo_where('type', 3)->combo_where('active', 1)->combo_tbl(_TBL_LIBRARY)->get_combo()->result_combo();
		// }

		$option = '';
		foreach ($penyebab_id as $key => $row) {
			$option .= '<option value="' . $key . '">' . $row . '</option>';
		}
		$param['peristiwa_cbo'] = $option;
		$option                 = '';
		foreach ($dampak_id as $key => $row) {
			$option .= '<option value="' . $key . '">' . $row . '</option>';
		}
		$param['dampak_cbo'] = $option;

		$aspek     = 0;
		$aspek_det = "";
		if ($residual) {
			$aspek = $residual['aspek'];
			$aspek_det = $residual['aspek_det'];
		} elseif ($data) {
			if ($data['tipe_analisa_no'] == 2 || $data['tipe_analisa_no'] == 3) {
				$aspek = $data['aspek_risiko_id'];
			} else {
				$aspek = 0;
			}
			$aspek_det = $data['aspek_det'];
		} else {
			$aspek = 0;
		}
		// dumps($aspek);
		if ($aspek) {
			$like = $this->crud->combo_select(['urut', 'concat(urut,\' - \',data) as x'])->combo_where('active', 1)->combo_where('pid', $aspek)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();

			$like_semi = $this->crud->combo_select(['urut', 'concat(urut,\' - \',data) as x'])->combo_where('active', 1)->combo_where('pid', $aspek)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		} else {
			$like = $this->crud->combo_select(['id', 'concat(code,\' - \',level) as x'])->combo_where('active', 1)->combo_where('category', 'likelihood')->combo_tbl(_TBL_LEVEL)->get_combo()->result_combo();

			$like_semi = [];
		}
		// dumps($like);

		$impact       = $this->crud->combo_select(['id', 'concat(code,\' - \',level) as x'])->combo_where('active', 1)->combo_where('category', 'impact')->combo_tbl(_TBL_LEVEL)->get_combo()->result_combo();
		$cboControl   = $this->crud->combo_select(['id', 'data'])->noSelect()->combo_where('active', 1)->combo_where('kelompok', 'existing-control')->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$aspek_risiko = $this->crud->combo_select(['id', 'data'])->combo_where('active', 1)->combo_where('kelompok', 'aspek-risiko')->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		// $aspek_risiko['-1'] = "dll";
		$arrControl      = [];
		$jml             = intval(count($cboControl) / 2);
		$kontrol         = '';
		$kontrolCheckbox = '';
		$i               = 1;
		$control         = [];
		if ($data) {
			$control = explode('###', $data['nama_kontrol']);
		}
		$kontrol .= '<div class="well p100">';

		$kontrol .= '</div>' . form_textarea("note_control", ($data) ? $data['nama_kontrol_note'] : '', ' class="summernote-risk-evaluate" readonly="readonly" style="width:100%;" maxlength="999"') . '<br/>';


		foreach ($cboControl as $row) {
			if ($i == 1)
				// $kontrolCheckbox .= '<div class="col-md-6">';

				$sts = FALSE;
			foreach ($control as $ctrl) {
				if ($row == $ctrl) {
					$sts = TRUE;
					break;
				}
			}

			$kontrolCheckbox .= '<label class="pointer" disabled>' . form_checkbox('check_item[]', $row, $sts);
			$kontrolCheckbox .= '&nbsp;' . $row . '</label><br/>';
			if ($i == $jml)
				// $kontrolCheckbox .= '</div><div class="col-md-6">';
				// $kontrolCheckbox .= '</div>';

				++$i;
		}

		$efek_control = [0 => _l('cbo_select'), 1 => 'L', 2 => 'D', 3 => 'L & D', 4 => 'Tidak ada kontrol'];
		$fraud        = [0 => _l('cbo_select'), 1 => 'Ya', 2 => 'Tidak'];

		$penyebab = '<table class="table table-borderless" id="tblpenyebab"><tbody>';
		if ($data) {
			$pi = explode(',', $data['penyebab_id']);

			// doi::dump($penyebab_id);
			foreach ($pi as $key => $x) {
				$icon = '';
				if ($key > 0) {
					$icon = '';
				}
				$penyebab .= '<tr><td style="padding-left:0px;">' . form_dropdown('penyebab_id[]', $penyebab_id, $x, 'id="penyebab_id_"  class="form-control select" style="width:100%;"' . $disabled) . form_input('penyebab_id_text[]', '', 'class="form-control d-none" id="penyebab_id" placeholder="' . _l('fld_penyebab_risiko') . '" ' . $disabled) . '</td><td class="text-right pointer" width="10%" style="padding-right:0px;">' . $icon . '</td></tr>';
			}
		} else {
			$penyebab .= '<tr><td style="padding-left:0px;">' . form_dropdown('penyebab_id[]', $penyebab_id, '', 'id="penyebab_id_"  class="form-control select" style="width:100%;"' . $disabled) . form_input('penyebab_id_text[]', '', 'class="form-control d-none" id="penyebab_id_text" placeholder="' . _l('fld_penyebab_risiko') . '"' . $disabled) . '</td><td class="text-right pointer" width="10%" style="padding-right:0px;"></td></tr>';
		}
		$penyebab .= '</tbody></table>';

		$dampak = '<table class="table table-borderless" id="tbldampak"><tbody>';

		$csslevel = '';
		if ($data) {
			$pi = explode(',', $data['dampak_id']);

			foreach ($pi as $key => $x) {
				$icon = '';
				if ($key > 0) {
					$icon = '';
				}
				$dampak .= '<tr><td style="padding-left:0px;">' . form_dropdown('dampak_id[]', $dampak_id, $x, 'id="dampak_id_" class="form-control select" style="width:100%;"' . $disabled) . form_input('dampak_id_text[]', '', 'class="form-control d-none" id="dampak_id_text" placeholder="' . _l('fld_dampak_risiko') . '"' . $disabled) . '</td><td class="text-right pointer" width="10%" style="padding-right:0px;">' . $icon . '</td></tr>';
			}
			$csslevel = 'background-color:' . $data['color'] . ';color:' . $data['color_text'] . ';';
		} else {
			$dampak .= '<tr><td style="padding-left:0px;">' . form_dropdown('dampak_id[]', $dampak_id, '', 'id="dampak_id_" class="form-control select" style="width:100%;"' . $disabled) . form_input('dampak_id_text[]', '', 'class="form-control d-none" id="dampak_id_text" placeholder="' . _l('fld_dampak_risiko') . '"' . $disabled) . '</td><td class="text-right pointer" width="10%" style="padding-right:0px;"></td></tr>';
		}
		$dampak .= '</tbody></table>';
		$lib = false;
		if ($data) {
			$lib = $this->db->where('id', $data['peristiwa_id'])->get(_TBL_VIEW_LIBRARY)->row_array();
		}
		// doi::dump($data);
		$peristiwa = '<table class="table table-borderless" id="tblperistiwa"><tbody>
   	 <tr id="getPeristiwa">
        <td style="padding-left:0px;">'
			. form_input('peristiwa_id_text', ($lib) ? $lib['library'] : '', 'class="form-control getPeristiwa" id="peristiwa_id_text" readonly="readonly" placeholder="' . _l('fld_peristiwa_risiko') . '"')
			. form_hidden('peristiwa_id', ($lib) ? $data['peristiwa_id'] : '', 'id="peristiwa_id"') .
			'</td>
  	  </tr>
		</tbody></table>';

		$tasktonomi = '<table class="table table-borderless" id="tblperistiwa"><tbody>
   	 <tr>
        <td style="padding-left:0px;">'
			. form_input('tasktonomiName', ($lib) ? $lib['nama_kelompok'] : '', 'class="form-control getPeristiwa" id="tasktonomiName" readonly="readonly" placeholder="' . _l('help_ket_peristiwa') . '"')
			. form_hidden('klasifikasi_risiko_id', ($lib) ? $data['klasifikasi_risiko_id'] : '', 'id="klasifikasi_risiko_id"') .
			'</td>
  	  </tr>
		</tbody></table>';

		$tipeRisiko = '<table class="table table-borderless" id="tblperistiwa"><tbody>
   	 <tr>
        <td style="padding-left:0px;">'
			. form_input('tipeName', ($lib) ? $lib['risk_type'] : '', 'class="form-control getPeristiwa" id="tipeName" readonly="readonly" placeholder="' . _l('help_ket_peristiwa') . '"')
			. form_hidden('tipe_risiko_id', ($lib) ? $data['tipe_risiko_id'] : '', 'id="tipe_risiko_id"') .
			'</td>
   	 </tr>
		</tbody></table>';



		$tAdd                         = '<div class="form-control-feedback form-control-feedback-lg"><i class="icon-make-group"></i></div>';
		$param['identifikasi']['kpi'] = ['title' => "KPI", 'help' => "", 'add' => FALSE, 'mandatori' => FALSE, 'isi' => form_dropdown('id_kpi', $kpi, ($data) ? $data['id_kpi'] : '', 'id="id_kpi" class="form-control select" style="width:100%;"' . $disabled)];

		$param['identifikasi']['aktifitas_id'] = ['title' => _l('fld_aktifitas'), 'help' => _h('help_aktifitas'), 'add' => FALSE, 'mandatori' => TRUE, 'isi' => form_dropdown('aktifitas_id', $aktivitas, ($data) ? $data['aktifitas_id'] : '', 'id="aktifitas_id" class="form-control select" style="width:100%;"' . $disabled)];
		$param['identifikasi']['sasaran_id']   = ['title' => _l('fld_sasaran_aktifitas'), 'help' => _h('help_sasaran_aktifitas'), 'mandatori' => TRUE, 'add' => FALSE, 'isi' => form_dropdown('sasaran_id', $sasaran, ($data) ? $data['sasaran_id'] : '', 'id="sasaran_id" class="form-control select" style="width:100%;"' . $disabled)];
		// $param['identifikasi']['tahapan_id'] = ['title'=>_l('fld_tahapan_proses'),'help'=>_h('help_tahapan_proses'), 'add'=>true,'isi'=>form_dropdown('tahapan_id', $tahapan, ($data)?$data['tahapan_id']:'', 'id="tahapan_id" class="form-control select" style="width:100%;"'.$disabled)];
		// $param['identifikasi']['tahapan'] = ['title'=>_l('fld_tahapan_proses'),'help'=>_h('help_tahapan_proses'), 'add'=>true,'isi'=>form_dropdown('tahapan_id', $tahapan, ($data)?$data['tahapan_id']:'', 'id="tahapan_id" class="form-control select" style="width:100%;"'.$disabled)];
		$param['identifikasi']['tahapan'] = ['title' => _l('fld_tahapan_proses'), 'help' => _h('help_tahapan_proses'), 'mandatori' => TRUE, 'isi' => form_textarea('tahapan', ($data) ? $data['tahapan'] : '', " id='tahapan' maxlength='500' size='500' class='form-control' style='overflow: hidden; width: 100% !important; height: 200px;' onblur='_maxLength(this , \"id_sisa_2\")' readonly='readonly' onkeyup='_maxLength(this , \"id_sisa_2\")' data-role='tagsinput'", TRUE, ['size' => 500, 'isi' => 0, 'no' => 2])];

		// $param['identifikasi']['peristiwa_id']          = [ 'title' => _l( 'fld_peristiwa_risiko' ), 'help' => _h( 'help_peristiwa_risiko' ), 'mandatori' => TRUE, 'add' => FALSE, 'isi' => form_dropdown( 'peristiwa_id', $peristiwa_id, ( $data ) ? $data['peristiwa_id'] : '', 'id="peristiwa_id" class="form-control select" style="width:100%;"' ) ];
		$param['identifikasi']['peristiwa_id'] = ['title' => _l('fld_peristiwa_risiko'), 'help' => _h('help_peristiwa_risiko'), 'mandatori' => TRUE, 'isi' => $peristiwa];
		$param['identifikasi']['klasifikasi_risiko_id'] = ['title' => _l('fld_klasifikasi_risiko'), 'help' => _h('help_klasifikasi_risiko'), 'mandatori' => FALSE, 'isi' => $tasktonomi];
		$param['identifikasi']['tipe_risiko_id'] = ['title' => _l('fld_tipe_risiko'), 'help' => _h('help_tipe_risiko'), 'mandatori' => FALSE, 'isi' => $tipeRisiko];

		// $param['identifikasi']['klasifikasi_risiko_id'] = ['title' => _l('fld_klasifikasi_risiko'), 'help' => _h('help_klasifikasi_risiko'), 'mandatori' => TRUE, 'isi' => form_dropdown('klasifikasi_risiko_id', $kel, ($data) ? $data['klasifikasi_risiko_id'] : '', 'id="klasifikasi_risiko_id" class="form-control select" style="width:100%;"'.$disabled)];
		// $param['identifikasi']['tipe_risiko_id']        = ['title' => _l('fld_tipe_risiko'), 'help' => _h('help_tipe_risiko'), 'mandatori' => TRUE, 'isi' => form_dropdown('tipe_risiko_id', $risk_type, ($data) ? $data['tipe_risiko_id'] : '', 'id="tipe_risiko_id" class="form-control select" style="width:100%;"'.$disabled)];

		//new
		$param['identifikasi']['fraud_risk'] = ['title' => _l('Fraud Risk'), 'help' => _h('help_fraud_risk'), 'mandatori' => TRUE, 'isi' => form_dropdown('fraud_risk', $fraud, ($data) ? $data['fraud_risk'] : '', 'id="fraud_risk" class="form-control select" style="width:100%;"' . $disabled)];
		$param['identifikasi']['smap']       = ['title' => _l('SMAP'), 'help' => _h('help_smap'), 'mandatori' => TRUE, 'isi' => form_dropdown('smap', $fraud, ($data) ? $data['smap'] : '', 'id="smap" class="form-control select" style="width:100%;"' . $disabled)];
		$param['identifikasi']['esg_risk']   = ['title' => _l('ESG Risk'), 'help' => _h('help_esg_risk'), 'mandatori' => TRUE, 'isi' => form_dropdown('esg_risk', $fraud, ($data) ? $data['esg_risk'] : '', 'id="esg_risk" class="form-control select" style="width:100%;"' . $disabled)];

		$param['identifikasi']['penyebab_id'] = ['title' => _l('fld_penyebab_risiko'), 'help' => _h('help_penyebab_risiko'), 'mandatori' => TRUE, 'isi' => $penyebab];
		$param['identifikasi']['dampak_id']   = ['title' => _l('fld_dampak_risiko'), 'help' => _h('help_dampak_risiko'), 'mandatori' => TRUE, 'isi' => $dampak];

		$param['identifikasi']['risiko_dept'] = ['title' => _l('fld_risiko_dept'), 'help' => _h('help_risiko_dept'), 'mandatori' => TRUE, 'isi' => form_textarea('risiko_dept', ($data) ? $data['risiko_dept'] : '', " id='risiko_dept' maxlength='500' size='500' class='form-control' style='overflow: hidden; width: 100% !important; height: 200px;' onblur='_maxLength(this , \"id_sisa_1\")' readonly='readonly onkeyup='_maxLength(this , \"id_sisa_1\")' data-role='tagsinput'", TRUE, ['size' => 500, 'isi' => 0, 'no' => 1])];

		$tipe_analisa    = "<br/>&nbsp;";
		$check1          = FALSE;
		$check2          = TRUE;
		$check3          = FALSE;
		// $tipe_analisa_no = 2;
		if ($data) {
			$data['tipe_analisa_no'] = ($data['tipe_analisa_no'] == 1) ? 2 : $data['tipe_analisa_no'];
			if ($data['tipe_analisa_no'] == 2) {
				$check1 = FALSE;
				$check2 = TRUE;
				$check3 = FALSE;
			} elseif ($data['tipe_analisa_no'] == 3) {
				$check1 = FALSE;
				$check2 = FALSE;
				$check3 = TRUE;
			}
			$tipe_analisa_no = $data['tipe_analisa_no'];
		}
		$tipe_analisa .= '<div class="form-check form-check-inline d-none"><label class="form-check-label">';
		$tipe_analisa .= form_radio('tipe_analisa_no', 1, $check1, 'id="tipe_analisa_no_1"  class="form-check-primary" ' . $disabled);
		$tipe_analisa .= form_label('&nbsp;&nbsp;&nbsp; Kualitatif &nbsp;&nbsp;', 'tipe_analisa_no_1', ['class' => 'pointer ']);
		$tipe_analisa .= '</label></div>';
		$tipe_analisa .= '<div class="form-check form-check-inline"><label class="form-check-label">';
		$tipe_analisa .= form_radio('tipe_analisa_no', 2, $check2, 'id="tipe_analisa_no_2"  class="form-check-primary" ' . $disabled);
		$tipe_analisa .= form_label('&nbsp;&nbsp;&nbsp; Kuantitatif &nbsp;&nbsp;', 'tipe_analisa_no_2', ['class' => 'pointer']);
		$tipe_analisa .= '</label></div>';
		$tipe_analisa .= '<div class="form-check form-check-inline"><label class="form-check-label">';
		$tipe_analisa .= form_radio('tipe_analisa_no', 3, $check3, 'id="tipe_analisa_no_3"  class="form-check-primary" ' . $disabled);
		$tipe_analisa .= form_label('&nbsp;&nbsp;&nbsp; Kualitatif &nbsp;&nbsp;', 'tipe_analisa_no_3', ['class' => 'pointer']);
		$tipe_analisa .= '</label></div><br/>&nbsp<br/>&nbsp;';

		$param['tipe_analisa_no'] = $tipe_analisa_no;
		$param['tipe_analisa']    = ['title' => '', 'help' => '', 'isi' => $tipe_analisa];

		//analisa kuantitatif **********/

		$param['analisa_kuantitatif'][] = ['title' => '', 'help' => '', 'isi' => '<div style="display: flex; justify-content: space-between;">
		<span class="btn btn-primary legitRipple pointer" data-rcsa="' . $parent['id'] . '" data-id="' . $id_edit . '" id="indikator_like" data-jml_like_indi="' . $jml_like_indi . '" style="width:100%;"> Input Risk Indikator Likelihood [ ' . $jml_like_indi . ' ] </span>
		</div> ' .
			form_hidden(['indikator_like_cek' => ($jml_like_indi) ? $jml_like_indi : ''], 'id="indikator_like_cek"')];

		$param['analisa_kuantitatif'][] = ['title' => '', 'help' => '', 'isi' => '<div style="display: flex; justify-content: space-between;">
		<span class="btn btn-primary legitRipple pointer"  data-rcsa="' . $parent['id'] . '" data-id="' . $id_edit . '" id="indikator_dampak" data-jml_dampak_indi="' . $jml_dampak_indi . '" style="width:100%;"> Input Risk Indikator Dampak  [ ' . $jml_dampak_indi . ' ] </span>		 
		</div> '];



		/**inherent impact */
		
		$LV =null;
		if($residual ){
			$this->db->where( 'like_code', intval( $residual['like'] ) );
			$this->db->where( 'impact_code', intval( $residual['impact'] ) );
			$LV = $this->db->get( _TBL_VIEW_LEVEL_MAPPING )->row_array();
 		}

		 $param['analisa_kuantitatif'][] = ['title' => _l('fld_likelihood'), 'help' => _h('help_likelihood'), 'mandatori' => TRUE, 'isi' => form_input('like_text_kuantitatif', ($LV) ? $LV['like_code'] .'-'. $LV['like_text']: $data['like_inherent'], 'id="like_text_kuantitatif" class="form-control" readonly="readonly" style="width:100%;"')
		 . form_hidden(['like_text' => ($LV) ? $LV['like_text'] :  $data['like_text']])
		 . form_hidden(['like_id' => ($LV) ? $LV['like_code'] :$data['like_code']])
		 . form_hidden(['like_id_2' => ($LV) ? $LV['like_code'] :$data['like_code']])
		 . form_hidden(['like_id_3' => ($LV) ? $LV['like_code'] :$data['like_code']])];

		 $param['analisa_kuantitatif'][] = ['title' => _l('fld_impact'), 'help' => _h('help_impact'), 'mandatori' => TRUE, 'isi' => form_input('impact_text_kuantitatif', ($LV) ? $LV['impact_code'] .'-'.$LV['impact_text']:'', 'id="impact_text_kuantitatif" class="form-control" readonly="readonly" style="width:100%;"') 
		 . form_hidden(['impact_text' => ($LV) ? $LV['impact_text'] : $data['impact_text']]) 
		 . form_hidden(['impact_id' => ($LV) ? $LV['impact_code'] : $data['impact_code']]) 
		 . form_hidden(['impact_id_2' => ($LV) ? $LV['impact_code'] : $data['impact_code']])
		 . form_hidden(['impact_id_3' => ($LV) ? $LV['impact_code'] : $data['impact_code']])];
		// doi::dump($residual);
		
 	

		/**========== */
		$param['analisa_semi'][] = ['title' => _l('fld_indi_likelihood'), 'help' => _h('help_likelihood'), 'mandatori' => TRUE, 'isi' => form_dropdown('aspek_risiko_id', $aspek_risiko, $aspek, 'id="aspek_risiko_id" class="form-control select" style="width:100%;"') 
		. form_hidden('aspek', ($aspek) ? $aspek : 0, 'id="aspek"')];

		// $param['analisa_semi'][] = ['title' => "Detail", 'help' => _h("keterangan/Detail lainnya"), 'mandatori' => FALSE, 'isi' => form_input('aspek_det', ($aspek_det) ? $aspek_det : '', 'id="aspek_det" class="form-control" style="width:100%;" ')];

		$urutTemp = [1, 7, 8, 9, 10];

		$like_semi_form = '<select name="like_id_3" id="like_id_3" class="form-control like_id_3 select" style="width:100%;">';
		if (! empty($like_semi)) {

			foreach ($like_semi as $key => $value) {
				$sel            = ($residual) ? $residual['like'] : '';
				$selected       = ($sel == $key) ? 'selected' : '';
				$k              = intval($key) - 1;
				$dataTemp       = (isset($urutTemp[$k])) ? $urutTemp[$k] : 0;
				$like_semi_form .= '<option data-temp="' . $dataTemp . '" value="' . $key . '"' . $selected . '>' . $value . '</option>';
			}
		}
		$like_semi_form .= '</select>';
		$like_semi_form .= form_hidden(['like_text' => ($LV) ? $LV['like_text'] :  $data['like_text']])
		 . form_hidden(['like_id' => ($LV) ? $LV['like_code'] :$data['like_code']])
		 . form_hidden(['like_id_2' => ($LV) ? $LV['like_code'] :$data['like_code']])
		 . form_hidden(['like_id_3' => ($LV) ? $LV['like_code'] :$data['like_code']]);

		// form_dropdown('like_id_3', $like_semi, ($data)?$data['like_id']:'', 'id="like_id_3" class="form-control select" style="width:100%;"')

		$param['analisa_semi'][] = ['title' => _l('fld_likelihood'), 'help' => _h('help_likelihood'), 'mandatori' => TRUE, 'isi' => $like_semi_form];

		$param['analisa_semi'][] = ['title' => '', 'help' => '', 'isi' => '<span class="btn btn-primary legitRipple pointer"  data-rcsa="' . $parent['id'] . '" data-id="' . $id_edit . '" id="indikator_dampak" style="width:100%;"> Input Risk Indikator Dampak  [ ' . $jml_dampak_indi . ' ] </span>' .
			form_hidden(['indikator_dampak_cek' => ($jml_dampak_indi) ? $jml_dampak_indi : ''], 'id="indikator_dampak_cek"')];
		// $param['analisa_semi'][] = [ 'title' => _l( 'fld_likelihood' ), 'help' => _h( 'help_likelihood' ), 'mandatori' => TRUE, 'isi' => form_input( 'like_text_kuantitatif_semi', ( $data ) ? $data['like_inherent'] : '', 'id="like_text_kuantitatif" class="form-control" style="width:100%;" readonly="readonly"' ) . form_hidden( [ 'like_id_3' => ( $data ) ? $data['like_id'] : '' ] ) ];

		$param['analisa_semi'][] = ['title' => _l('fld_impact'), 'help' => _h('help_impact'), 'mandatori' => TRUE, 'isi' => form_input('impact_text_kuantitatif', ($LV) ? $LV['impact_code'] .'-'.$LV['impact_text'] : '', 'id="impact_text_kuantitatif_semi" class="form-control" style="width:100%;" readonly="readonly"') 
		. form_hidden(['impact_text' => ($LV) ? $LV['impact_text'] : $data['impact_text']]) 
		 . form_hidden(['impact_id' => ($LV) ? $LV['impact_code'] : $data['impact_code']]) 
		 . form_hidden(['impact_id_2' => ($LV) ? $LV['impact_code'] : $data['impact_code']])
		 . form_hidden(['impact_id_3' => ($LV) ? $LV['impact_code'] :  $data['impact_code']], 'id="impact_id_3"')];


		$param['analisa_semi'][] = ['title' => '', 'help' => '', 'isi' => form_input('like_text_3', ($data) ? $data['like_text'] : '', 'id="like_text_3" class="form-control" style="width:100%;display:none"')];

		// $param['analisa_semi'][] = ['title'=>_l('fld_impact'),'help'=>_h('help_impact'), 'mandatori'=>true,'isi'=>form_dropdown('impact_id_3', $impact, ($data)?$data['impact_id']:'', 'id="impact_id_3" class="form-control select" style="width:100%;"')];

		$param['analisa_semi'][] = ['title' => '', 'help' => '', 'isi' => form_input('impact_text_3', ($data) ? $data['impact_text'] : '', 'id="impact_text_3" class="form-control" style="width:100%;display:none"')];


		$param['analisa2'][] = ['title' => _l('fld_risiko_inherent'), 'help' => _h('help_risiko_inherent'), 'mandatori' => TRUE, 'isi' => form_input('risiko_inherent_text', ($data) ? $data['risiko_inherent_text'] : '', 'class="form-control text-center" id="risiko_inherent_text" readonly="readonly" style="width:15%;"') . form_hidden(['risiko_inherent' => ($data) ? $data['risiko_inherent'] : 0])];
		$param['analisa2'][] = ['title' => _l('fld_level_risiko'), 'help' => _h('help_level_risiko'), 'mandatori' => TRUE, 'isi' => form_input('level_inherent_text', ($data) ? $data['level_color'] : '', 'class="form-control text-center" id="level_inherent_text" readonly="readonly" style="width:30%;' . $csslevel . '"') . form_hidden(['level_inherent' => ($data) ? $data['level_inherent'] : 0]) . form_hidden(['mit_like_id' => ($residual) ? $residual['like'] : ''], 'id="mit_like_id"') . form_hidden(['mit_like_id_cek' => 0], 'id="mit_like_id_cek"')];
		$param['analisa2'][] = ['title' => _l('fld_nama_control'), 'help' => _h('help_nama_control'), 'mandatori' => FALSE, 'isi' => $kontrol];
		$param['analisa2'][] = ['title' => _l('fld_nama_control_checkbox'), 'help' => _h('help_nama_control_checkbox'), 'mandatori' => FALSE, 'isi' => $kontrolCheckbox];
		$param['analisa2'][] = ['title' => _l('fld_efek_kontrol'), 'help' => _h('help_efek_kontrol'), 'mandatori' => TRUE, 'isi' => form_dropdown('efek_kontrol', $efek_control, ($data) ? $data['efek_kontrol'] : '', 'id="efek_kontrol" class="form-control select" style="width:100%;"' . $disabled)];
		if ($data) {
			if ($data['lampiran'] == '0') {
				$url    = '#';
				$nmfile = 'Belum ada File';
			} else {
				$url    = base_url() . substr($data['lampiran'], 1);
				$nmfile = substr($data['lampiran'], 13);
			}
		} else {
			$url    = '#';
			$nmfile = 'Belum ada File';
		}
		$param['analisa2'][] = ['title' => _l('fld_upload'), 'help' => _h('help_upload'), 'isi' => '<a href="' . $url . '" target="_blank">' . $nmfile . '</a><br><br><b>Max size file 10MB, file yang dibolehkan xlsx | csv | docx | pdf | zip | rar </b><br>'];
		$param['analisa2'][] = ['title' => _l('fld_lampiran'), 'help' => _h('help_lampiran'), 'isi' => ''];
		return $param;
	}


	function simpan_update_residual()
	{
		$post = $this->input->post();
		$like = $post['like'];
		$aspek = $post['aspek'];
		$impact = $post['impact'];
		$id_detail = $post['id_detail'];
		$month = $post['month'];
		$id_edit = intval($post['id_edit']);

		$color_text = $post['color_text'];
		$level_color = $post['level_color'];
		$color = $post['color'];
		$score = $post['score'];

		$this->crud->crud_table("il_update_residual");
		$this->crud->crud_field('like', $like);
		if ($aspek) {
			$this->crud->crud_field('aspek', $aspek);
		}
		$this->crud->crud_field('impact', $impact);

		$this->crud->crud_field('level_color', $level_color);
		$this->crud->crud_field('color', $color);
		$this->crud->crud_field('color_text', $color_text);
		$this->crud->crud_field('score', $score);
		$cekResidual = $this->db->where('rcsa_detail_id', $id_detail)->where('month', $month)->get("il_update_residual")->result_array();

		if (count($cekResidual) > 0) {
			$this->crud->crud_type('edit');
			$this->crud->crud_where(['field' => 'id', 'value' => $cekResidual[0]['id']]);
			$this->crud->crud_field('updated_by', $this->ion_auth->get_user_name());
			$id = $cekResidual['0']['id'];
			$info['info'] = "update";
			// $info['data'] = $this->data->getMonthlyMonitoring($id_detail, $month);
		} else {

			$this->crud->crud_field('rcsa_detail_id', $id_detail);
			$this->crud->crud_field('month', $month);
			$this->crud->crud_field('created_by', $this->ion_auth->get_user_name());
			$id = $this->crud->last_id();
			$info['info'] = "create";
			//    $info['data'] = $this->data->getMonthlyMonitoring($id_detail, $month);

		}
		$this->crud->process_crud();

		if ($id) {
			$info['status'] = "berhasil";
			$this->data->cek_mitigasi_final($id_detail, $month, true);
		} else {
			$info['status'] = "gagal";
		}

		header('Content-type: application/json');
		echo json_encode($info);
	}

	function indikator_dampak()
	{
		$post                = $this->input->post();
		$data['parent']      = $post;
		$data['dampak_indi'] = [];
		$tipe_kri            = $this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'tipe-kri')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$data['tipe_kri']    = form_dropdown('tipe_kri[]', $tipe_kri, '', 'class="form-control tipe_kri select" id="tipe_kri"');
		$data['kri']         = form_dropdown('kri[]', [], '', 'class="form-control kri select" id="kri"');
		$data['detail']      = form_input('detail[]', '', 'class="form-control detail_input" id="detail_input"');
 
		$rows = $this->db->where('bk_tipe', $post['bk_tipe'])->where('rcsa_detail_id', intval($post['rcsa_detail_no']))->or_group_start()->where('rcsa_detail_id', 0)->where('created_by', $this->ion_auth->get_user_name())->group_end()->get(_TBL_VIEW_RCSA_DET_DAMPAK_INDI)->result_array();

		if ($post['bk_tipe'] == 1) {
			$disabeld          = '';
			$data['sub_title'] = 'Inheren';
		} else {
			$data['sub_title'] = 'Residual';
			$disabeld          = '';
			// $disabeld=' readonly="readonly" ';
		}
				$this->db->where( 'rcsa_detail_id', intval( $post['rcsa_detail_no'] ) );
				$this->db->where( 'month', intval( $post['month'] ) );
				$residual = $this->db->get( "il_update_residual" )->row_array();

		foreach ($rows as &$row) {
			$tipe_kri            = $this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'tipe-kri')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
			$kri                 = $this->crud->combo_select(['id', 'concat(urut,\' - \',data) as data'])->combo_where('pid',isset($residual['tipe_kri'])?$residual['tipe_kri']:$row['jenis_kri_id'])->combo_where('param_int', 2)->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
			$detail_input        = '';
			$row['cbo_kri'] = form_dropdown(
				'kri[]',               
				$kri,               
				isset($residual['kri_id']) ? $residual['kri_id'] : $row['kri_id'],
				'class="form-control kri select" id="kri"'
			);
			$row['cbo_tipe_kri'] = form_dropdown(
				'tipe_kri[]',               
				$tipe_kri,               
				isset($residual['tipe_kri']) ? $residual['tipe_kri'] : $row['jenis_kri_id'],
				'class="form-control tipe_kri select" id="tipe_kri"'
			);
 			$row['detail_input'] = form_input('detail[]', isset($residual['detail_dampak_indi']) ? $residual['detail_dampak_indi'] : $row['detail'], 'class="form-control detail_input ' . $row['detail'] . '" ' . $disabeld . ' id="detail_input"');
		}
		unset($row);

		$data['list_dampak_indi'] = $rows;

		$result['combo'] = $this->load->view('input-indikator-dampak', $data, TRUE);
		header('Content-type: application/json');
		echo json_encode($result);
	}

	function simpan_dampak_indi()
	{
		$post  = $this->input->post();
		$hasil = $this->data->simpan_dampak_indi($post);
		// $hasil = "";

		header('Content-type: application/json');
		echo json_encode($hasil);
	}

	function simpan_like_indi()
	{
		$post = $this->input->post();

		// model simpan 
		$hasil = $this->data->simpan_like_indi($post);

		$id_detail = intval($post['rcsa_detail_no']);
		$this->indikator_like(['id' => $hasil['id'], 'rcsa_detail_no' => $id_detail, 'hasil' => $hasil, 'bk_tipe' => $post['bk_tipe']]);
	}
	function indikator_like($post = [])
	{
		if (! $post) {
			$post = $this->input->post();

			$rcsa_detail_no = $post['rcsa_detail_no'];
			$bk_tipe        = $post['bk_tipe'];
			if (! empty($post['id_kpi'])) {

				$this->db->select('id');
				$this->db->where('kelompok', 'kri');
				// $this->db->where('param_int', 1);
				$this->db->where('param_other_int', $post['id_kpi']);
				$this->db->where('active', 1);
				$kri = $this->db->get(_TBL_COMBO)->result_array();
				$cek = $this->db->where('bk_tipe', $post['bk_tipe'])->where('rcsa_detail_id', $post['rcsa_detail_no'])->get(_TBL_VIEW_RCSA_DET_LIKE_INDI)->result_array();


				if (count($cek) == 0) {
					foreach ($kri as $key => $value) {
						$data['id']             = 0;
						$data['rcsa_detail_no'] = $post['rcsa_detail_no'];
						$data['bk_tipe']        = $post['bk_tipe'];
						$data['kri_id']         = $value['id'];
						$data['satuan_id']      = 0;
						$data['pembobotan']     = 0;
						$data['p_1']            = 0;
						$data['s_1_min']        = 0;
						$data['s_1_max']        = 0;
						$data['p_4']            = 0;
						$data['s_4_min']        = 0;
						$data['s_4_max']        = 0;
						$data['p_2']            = 0;
						$data['s_2_min']        = 0;
						$data['s_2_max']        = 0;
						$data['p_5']            = 0;
						$data['s_5_min']        = 0;
						$data['s_5_max']        = 0;
						$data['p_3']            = 0;
						$data['s_3_min']        = 0;
						$data['s_3_max']        = 0;
						$data['score']          = 0;
						$data['dampak_id']      = $post['dampak_id'];
						$this->data->simpan_like_indi($data);
					}
				} else {
					$value = $cek[0];
					if ($value) {
						$this->db->select('param_other_int');
						$this->db->where('kelompok', 'kri');
						// $this->db->where('param_int', 1);
						$this->db->where('id', $value['kri_id']);
						$this->db->where('active', 1);
						$kriCek = $this->db->get(_TBL_COMBO)->row_array();

						if ($kriCek) {
							if ($kriCek['param_other_int'] != $post['id_kpi']) {
								$this->db->delete(_TBL_RCSA_DET_LIKE_INDI, array('rcsa_detail_id' => $post['rcsa_detail_no'], 'bk_tipe' => $post['bk_tipe']));
								foreach ($kri as $key => $valuex) {
									$data['id']             = 0;
									$data['rcsa_detail_no'] = $rcsa_detail_no;
									$data['bk_tipe']        = $bk_tipe;
									$data['kri_id']         = $valuex['id'];
									$data['satuan_id']      = 0;
									$data['pembobotan']     = 0;
									$data['p_1']            = 0;
									$data['s_1_min']        = 0;
									$data['s_1_max']        = 0;
									$data['p_4']            = 0;
									$data['s_4_min']        = 0;
									$data['s_4_max']        = 0;
									$data['p_2']            = 0;
									$data['s_2_min']        = 0;
									$data['s_2_max']        = 0;
									$data['p_5']            = 0;
									$data['s_5_min']        = 0;
									$data['s_5_max']        = 0;
									$data['p_3']            = 0;
									$data['s_3_min']        = 0;
									$data['s_3_max']        = 0;
									$data['score']          = 0;
									$data['dampak_id']      = $post['dampak_id'];
									$data['month']      = $post['month'];
									$this->data->simpan_like_indi($data);
								}
							}
						}
					}
				}
			}

			if(!isset($post['month'])){
				$post['month']=1;
			}
			$post['hasil'] = $this->data->update_list_indi_like(['rcsa_detail_no' => $post['rcsa_detail_no'], 'bk_tipe' => $post['bk_tipe'], 'dampak_id' => $post['dampak_id'], 'month'=>$post['month']], false);
		}


		$data['param'] = $post['hasil'];

		/**list table Indikator Likelihood */

		$data['list_like_indi'] = $this->db->where('bk_tipe', $post['bk_tipe'])->where('rcsa_detail_id', intval($post['rcsa_detail_no']))->or_group_start()->where('rcsa_detail_id', 0)->where('created_by', $this->ion_auth->get_user_name())->group_end()->get(_TBL_VIEW_RCSA_DET_LIKE_INDI)->result_array();

		/**------- ------- ------ */

		$data['parent'] = $post['rcsa_detail_no'];

		if ($post['bk_tipe'] == 1) {
			$data['sub_title'] = ' Inheren';
			$data['title']     = ' Inheren';
			$result['combo']   = $this->load->view('indikator-like', $data, TRUE);
		} elseif ($post['bk_tipe'] == 2) {
			$data['sub_title'] = ' Current';
			$result['combo']   = $this->load->view('indikator-like-residual', $data, TRUE);
		} elseif ($post['bk_tipe'] == 3) {
			$data['sub_title'] = ' Residual';
			$result['combo']   = $this->load->view('indikator-like-target', $data, TRUE);
		}
		$result['hasil']=$post['hasil'];
		header('Content-type: application/json');
		echo json_encode($result);
	}
		// function add likelihod 
		function indikator_like_add()
		{
			$post          = $this->input->post();
			$data['param'] = $post;
			// $kpi = ($post['id_kpi'])?$post['id_kpi']:'1=1';
			$this->cboKri = $this->crud->combo_select( [ 'id', 'data' ] )->combo_where( 'kelompok', 'kri' )->combo_where( 'param_other_int', $post['id_kpi'] )->combo_where( 'active', 1 )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();
			// ->combo_where( 'param_int', 1 )
	
			$this->cboSatuan = $this->crud->combo_select( [ 'id', 'data' ] )->combo_where( 'kelompok', 'satuan' )->combo_where( 'active', 1 )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();
	
			$rows = $this->db->where( 'rcsa_detail_id', intval( $post['rcsa_detail_no'] ) )->get( _TBL_VIEW_RCSA_DET_LIKE_INDI )->result_array();
			$mak  = 100;
			foreach( $rows as $row )
			{
				$mak += floatval( $row['pembobotan'] );
			}
			$mit = $this->db->where( 'id', intval( $post['id'] ) )->get( _TBL_VIEW_RCSA_DET_LIKE_INDI )->row_array();
			if( $mit )
			{
				$mak += floatval( $mit['pembobotan'] );
			}
			$disabled = '';
			if( intval( $post['bk_tipe'] ) > 1 )
			{
				$disabled = ' disabled="disabled" ';
			}
			$disabled = ' disabled="disabled" ';
			$pembobotan = '<div class="input-group" style="width:15% !important;">
				<button type="button" onclick="this.parentNode.querySelector(\'[type=number]\').stepDown();">
					-
				</button>';
	
			$pembobotan .= form_input( array( 'type' => 'number', 'name' => 'pembobotan' ), ( $mit ) ? $mit['pembobotan'] : '', " class='form-control touchspin-postfix text-center'  " . $disabled . " max='" . $mak . "' min='" . ( $mak * -1 ) . "' step='1' id='pembobotan' " );
	
			$pembobotan .= '<button type="button" onclick="this.parentNode.querySelector(\'[type=number]\').stepUp();">
					+
				</button>
				</div>';
			// inputan likelihod view
			$data['like'][] = [ 'title' => _l( 'fld_kri' ), 'help' => _h( 'help_kri' ), 'add' => FALSE, 'isi' => form_dropdown( 'kri_id', $this->cboKri, ( $mit ) ? $mit['kri_id'] : '', 'class="form-control select" ' . $disabled . ' id="kri_id"' ) ];
			$data['like'][] = [ 'title' => _l( 'fld_pembobotan' ), 'help' => _h( 'help_pembobotan' ), 'isi' => $pembobotan ];
			$data['like'][] = [ 'title' => _l( 'fld_satuan' ), 'help' => _h( 'help_satuan' ), 'isi' => form_dropdown( 'satuan_id', $this->cboSatuan, ( $mit ) ? $mit['satuan_id'] : '', 'class="form-control select" ' . $disabled . ' id="satuan_id"' ) ];
	
			$data['like'][] = [ 'title' => '<a style="height:1.4rem;width:1.4rem;background-color:#2c5b29" class="btn bg-successx-400 rounded-round btn-icon btn-sm" ><span class="letter-icon"></span></a>', 'help' => _h( 'help_pencapaian' ), 'isi' => form_input( 'p_1', ( $mit ) ? $mit['p_1'] : '', 'class="form-control" ' . $disabled . ' id="p_1" placeholder="' . _l( 'fld_pencapaian' ) . '" style="width:50%"' ) . '&nbsp;&nbsp;&nbsp;' . form_input( 's_1_min', ( $mit ) ? $mit['s_1_min'] : '', 'class="form-control text-center" ' . $disabled . ' id="s_1_min" placeholder="' . _l( 'fld_min_satuan' ) . '" style="width:10%"' ) . '&nbsp;&nbsp;&nbsp;<span class="input-group-text"> - </span>&nbsp;&nbsp;&nbsp;' . form_input( 's_1_max', ( $mit ) ? $mit['s_1_max'] : '', 'class="form-control text-center" ' . $disabled . ' id="s_1_max" placeholder="' . _l( 'fld_mak_satuan' ) . '" style="width:10%"' ) . ' <span class="input-group-text"> Satuan </span> ' ];
	
			$data['like'][] = [ 'title' => '<a style="height:1.4rem;width:1.4rem;background-color:#50ca4e;" class="btn bg-orangex-400 rounded-round btn-icon btn-sm"><span class="letter-icon"></span></a>', 'help' => _h( 'help_pencapaian_4' ), 'isi' => form_input( 'p_4', ( $mit ) ? $mit['p_4'] : '', 'class="form-control" ' . $disabled . ' id="p_4" placeholder="' . _l( 'fld_pencapaian' ) . '" style="width:50%"' ) . '&nbsp;&nbsp;&nbsp;' . form_input( 's_4_min', ( $mit ) ? $mit['s_4_min'] : '', 'class="form-control text-center" ' . $disabled . ' id="s_4_min" placeholder="' . _l( 'fld_min_satuan' ) . '" style="width:10%"' ) . '&nbsp;&nbsp;&nbsp;<span class="input-group-text"> - </span>&nbsp;&nbsp;&nbsp;' . form_input( 's_4_max', ( $mit ) ? $mit['s_4_max'] : '', 'class="form-control text-center" ' . $disabled . ' id="s_4_max" placeholder="' . _l( 'fld_mak_satuan' ) . '" style="width:10%"' ) . ' <span class="input-group-text"> Satuan </span> ' ];
	
			$data['like'][] = [ 'title' => '<a style="height:1.4rem;width:1.4rem;background-color:#edfd17;" class="btn bg-dangerx-400 rounded-round btn-icon btn-sm"><span class="letter-icon"></span></a>', 'help' => _h( 'help_pencapaian_2' ), 'isi' => form_input( 'p_2', ( $mit ) ? $mit['p_2'] : '', 'class="form-control" ' . $disabled . ' id="p_2" placeholder="' . _l( 'fld_pencapaian' ) . '" style="width:50%"' ) . '&nbsp;&nbsp;&nbsp;' . form_input( 's_2_min', ( $mit ) ? $mit['s_2_min'] : '', 'class="form-control text-center" ' . $disabled . ' id="s_2_min" placeholder="' . _l( 'fld_min_satuan' ) . '" style="width:10%"' ) . '&nbsp;&nbsp;&nbsp;<span class="input-group-text"> - </span>&nbsp;&nbsp;&nbsp;' . form_input( 's_2_max', ( $mit ) ? $mit['s_2_max'] : '', 'class="form-control text-center" ' . $disabled . ' id="s_2_max" placeholder="' . _l( 'fld_mak_satuan' ) . '" style="width:10%"' ) . ' <span class="input-group-text"> Satuan </span> ' ];
	
			$data['like'][] = [ 'title' => '<a style="height:1.4rem;width:1.4rem;background-color:#f0ca0f;" class="btn bg-dangers-400 rounded-round btn-icon btn-sm"><span class="letter-icon"></span></a>', 'help' => _h( 'help_pencapaian_5' ), 'isi' => form_input( 'p_5', ( $mit ) ? $mit['p_5'] : '', 'class="form-control" ' . $disabled . ' id="p_5" placeholder="' . _l( 'fld_pencapaian' ) . '" style="width:50%"' ) . '&nbsp;&nbsp;&nbsp;' . form_input( 's_5_min', ( $mit ) ? $mit['s_5_min'] : '', 'class="form-control text-center" ' . $disabled . ' id="s_5_min" placeholder="' . _l( 'fld_min_satuan' ) . '" style="width:10%"' ) . '&nbsp;&nbsp;&nbsp;<span class="input-group-text"> - </span>&nbsp;&nbsp;&nbsp;' . form_input( 's_5_max', ( $mit ) ? $mit['s_5_max'] : '', 'class="form-control text-center" ' . $disabled . ' id="s_5_max" placeholder="' . _l( 'fld_mak_satuan' ) . '" style="width:10%"' ) . ' <span class="input-group-text"> Satuan </span> ' ];
	
			$data['like'][] = [ 'title' => '<a style="height:1.4rem;width:1.4rem;background-color:#e70808" class="btn bg-dangers-400 rounded-round btn-icon btn-sm"><span class="letter-icon"></span></a>', 'help' => _h( 'help_pencapaian_3' ), 'isi' => form_input( 'p_3', ( $mit ) ? $mit['p_3'] : '', 'class="form-control" ' . $disabled . ' id="p_3" placeholder="' . _l( 'fld_pencapaian' ) . '" style="width:50%"' ) . '&nbsp;&nbsp;&nbsp;' . form_input( 's_3_min', ( $mit ) ? $mit['s_3_min'] : '', 'class="form-control text-center" ' . $disabled . ' id="s_3_min" placeholder="' . _l( 'fld_min_satuan' ) . '" style="width:10%"' ) . '&nbsp;&nbsp;&nbsp;<span class="input-group-text"> - </span>&nbsp;&nbsp;&nbsp;' . form_input( 's_3_max', ( $mit ) ? $mit['s_3_max'] : '', 'class="form-control text-center" ' . $disabled . ' id="s_3_max" placeholder="' . _l( 'fld_mak_satuan' ) . '" style="width:10%"' ) . ' <span class="input-group-text"> Satuan </span> ' ];
	
			$data['like'][] = [ 'title' => _l( 'fld_score' ), 'help' => _h( 'help_score' ), 'isi' => '<div class="input-group" style="width:15%;text-align:center;">' . form_input( 'score', ( $mit ) ? $mit['score'] : '', 'class="form-control" id="score" placeholder="' . _l( 'fld_score' ) . '"' ) . '</div>' ];
	
			$result['combo'] = $this->load->view( 'input-indikator-like', $data, TRUE );
			header( 'Content-type: application/json' );
			echo json_encode( $result );
		}
	
	 
}
