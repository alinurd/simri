<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Profil_Risiko extends MY_Controller {
	var $table="";
	var $post=array();
	var $sts_cetak=false;
	public function __construct()
	{
		parent::__construct();
		$this->load->library('map');
		$this->load->language('risk_context');

	}

	function init($action='list'){
		$this->cbo_owner = $this->get_combo_parent_dept();

		
		$this->set_Tbl_Master(_TBL_VIEW_RCSA_DETAIL);

		$this->addField(array('field'=>'id', 'type'=>'int', 'show'=>false, 'size'=>4));
		$this->addField(['field'=>'risiko_dept', 'show'=>false]);
		$this->addField(['field'=>'klasifikasi_risiko', 'show'=>false]);
		$this->addField(['field'=>'tipe_risiko', 'show'=>false]);
		$this->addField(['field'=>'owner_name', 'show'=>false]);
		$this->addField(['field'=>'like_code', 'show'=>false]);
		$this->addField(['field'=>'color', 'show'=>false]);
		$this->addField(['field'=>'color_text', 'show'=>false]);
		$this->addField(['field'=>'level_color', 'show'=>false]);
		$this->addField(['field'=>'impact_code', 'show'=>false]);
		$this->addField(['field'=>'risiko_inherent_text', 'show'=>false]);
		$this->addField(['field'=>'like_code_residual', 'show'=>false]);
		$this->addField(['field'=>'color_residual', 'show'=>false]);
		$this->addField(['field'=>'color_text_residual', 'show'=>false]);
		$this->addField(['field'=>'level_color_residual', 'show'=>false]);
		$this->addField(['field'=>'impact_code_residual', 'show'=>false]);
		$this->addField(['field'=>'risiko_residual_text', 'show'=>false]);
		$this->addField(['field'=>'efek_kontrol_text', 'show'=>false]);
		$this->addField(['field'=>'treatment', 'show'=>false]);
		$this->addField(['field'=>'like_code_target', 'show'=>false]);
		$this->addField(['field'=>'color_target', 'show'=>false]);
		$this->addField(['field'=>'color_text_target', 'show'=>false]);
		$this->addField(['field'=>'level_color_target', 'show'=>false]);
		$this->addField(['field'=>'impact_code_target', 'show'=>false]);
		$this->addField(['field'=>'risiko_target_text', 'show'=>false]);
		$this->addField(['field'=>'jml', 'show'=>false]);

		$this->set_Field_Primary($this->tbl_master, 'id');

		$this->set_Sort_Table($this->tbl_master,'id');

		$this->set_Table_List($this->tbl_master,'id', '<input type="checkbox" class="form-check-input pointer" name="chk_list_parent" id="chk_list_parent"  style="padding:0;margin:0;">');
		$this->set_Table_List($this->tbl_master,'owner_name', 'Owner');
		$this->set_Table_List($this->tbl_master,'risiko_dept', 'Risiko Dept.');
		$this->set_Table_List($this->tbl_master,'klasifikasi_risiko', 'Klasifikasi');
		$this->set_Table_List($this->tbl_master,'like_code', 'Risiko Inheren');
		$this->set_Table_List($this->tbl_master,'efek_kontrol_text', 'Efek Kontrol');
		$this->set_Table_List($this->tbl_master,'like_code_residual', 'Risiko Residual');
		$this->set_Table_List($this->tbl_master,'treatment', 'Respon');
		$this->set_Table_List($this->tbl_master,'like_code_target', 'Risiko Target');
		$this->set_Table_List($this->tbl_master,'jml', 'Mitigasi');


		$this->set_Close_Setting();

		$configuration = [
			'show_title_header' => false,
		];
		return [
			'configuration'	=> $configuration
		];
	}

	
	
	function listBox_like_code($field, $rows, $value){
		$a = '<div class="text-center" style="padding:20px;background-color:'.$rows['color'].';color:'.$rows['color_text'].';">'.$rows['level_color'].'<br/><small>'.$rows['like_code'].'x'.$rows['impact_code'].' : '.$rows['risiko_inherent_text'].'</small></div>';
		return $a;
	}

	function listBox_like_code_residual($field, $rows, $value){
		$a = '<div class="text-center" style="padding:20px;background-color:'.$rows['color_residual'].';color'.$rows['color_text_residual'].';">'.$rows['level_color_residual'].'<br/><small>'.$rows['like_code_residual'].'x'.$rows['impact_code_residual'].' : '.$rows['risiko_residual_text'].'</small></div>';
		return $a;
	}

	

	function listBox_like_code_target($field, $rows, $value){
		$a = '<div class="text-center" style="padding:20px;background-color:'.$rows['color_target'].';color'.$rows['color_text_target'].';">'.$rows['level_color_target'].'<br/><small>'.$rows['like_code_target'].'x'.$rows['impact_code_target'].' : '.$rows['risiko_target_text'].'</small></div>';
		return $a;
	}

	function listBox_jml($field, $rows, $value){
		$a = '<div class="text-center"><span class="badge bg-teal-400 badge-pill align-self-center ml-auto">'.$rows['jml'].'</span></div>';
		return $a;
	}

	function listBox_klasifikasi_risiko($field, $rows, $value){
		$a = $rows['klasifikasi_risiko'].' | '.$rows['tipe_risiko'];
		return $a;
	}

	function listBox_id($field, $rows, $value){
		$check = $this->data->checklist();
		$select = (in_array($rows['id'], $check))?'checked':'';
		$a='<div class="text-center"  style="padding:0px 20px 20px 0px;"><input type="checkbox" class="form-check-input pointer text-center" name="chk_list[]" style="padding:0;margin:0;" value="'.$rows['id'].'" '.$select.'/></div>';
		return $a;
	}

	function optionalButton($button, $mode){
		if ($mode=='list'){
			// unset($button['delete']);
			unset($button['print']);
			unset($button['search']);

			$button['save']=[
				'label'=>$this->lang->line('btn_save'),
				'color'=>'bg-success-300',
				'id'=>'btn_save_modul',
				'name'=>'Save',
				'value'=>'Simpan',
				'type'=>'submit',
				'round'=>($this->configuration['round_button'])?'rounded-round':'',
				'icon' =>'icon-floppy-disk',
				'url' => base_url(_MODULE_NAME_.'/save-modul/')
			];
		}

		return $button;
	}

	function optionalPersonalButtonx($button, $row){
		$button=[];
		$button['delete']=[
			'label'=>"",
			'id'=>'btn_delete_on',
			'icon' =>'',
			'class'=>'delet text-danger',
			'url' => '#'
		];

		return $data;
	}

	function save_modul(){
		$post=explode(",",$this->input->post('id'));
		$result = $this->data->simpan_data($post);
	
		echo json_encode($result);
	}

	function dashboard()
	{
		$x = $this->session->userdata('periode');
		$tgl1=date('Y-m-d');
		$tgl2=date('Y-m-d');
		if ($x){
			$tgl1=$x['tgl_awal'];
			$tgl2=$x['tgl_akhir'];
		}
		// dumps($x);
		$data=$this->map();
		$data['owner']=$this->get_combo_parent_dept();
		$data['type_ass']=$this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'ass-type')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();

		$data['period']=$this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'period')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$data['term']=$this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'term')->combo_where('pid', _TAHUN_ID_)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$data['minggu']=$this->crud->combo_select(['id', 'concat(param_string, \' ( \', param_date, \' s.d \', param_date_after, \' ) \') as minggu'])->combo_where('kelompok', 'minggu')->combo_where('param_date>=', $tgl1)->combo_where('param_date<=', $tgl2)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		// $data['minggu']=$this->crud->combo_select(['id', 'concat(param_string,\' minggu ke - \',data, \' ( \', param_date, \' s.d \', param_date_after, \' ) \') as minggu'])->combo_where('kelompok', 'minggu')->combo_where('param_date>=', $tgl1)->combo_where('param_date<=', $tgl2)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		// die($this->db->last_query());

		$this->data->pos['tgl1']=$tgl1;
		$this->data->pos['tgl2']=$tgl2;
		$this->data->pos['owner']=0;
		$this->data->pos['type_ass']=0;
		$this->data->pos['period']=_TAHUN_ID_;
		$this->data->pos['term']=_TERM_ID_;
		$this->data->pos['minggu']=_MINGGU_ID_;

		$hasil=$this->load->view('dashboard',$data, true);
	

		$configuration = [
			'show_title_header' => false,
			'show_action_button' => false,
			'show_header_content' => false,
			'box_content' => false,
		];

		if ($this->input->is_ajax_request()){
			echo json_encode(['combo'=>$hasil]);
		}else{
			$this->default_display(['content'=>$hasil, 'configuration'=>$configuration]);
		}
	}

	function map(){
		$this->data->filter_data();
		
		$rows = $this->db->SELECT('risiko_inherent as id, COUNT(risiko_inherent) as nilai, level_color, level_color_residual, level_color_target')->group_by('risiko_inherent')->get(_TBL_VIEW_RCSA_DETAIL)->result_array();
		
		
		$data['map_inherent']=$this->map->set_data($rows)->set_param(['tipe'=>'angka', 'level'=>1])->draw();
		$jml=$this->map->get_total_nilai();
		$jmlstatus=$this->map->get_jumlah_status();
		$data['jml_inherent_status']=$jmlstatus;
		$data['jml_inherent']='';
		if ($jml>0){
			$data['jml_inherent']='<span class="badge bg-primary badge-pill"> '.$jml.' </span>';
		}
		$this->data->filter_data();
		$rows = $this->db->SELECT('risiko_residual as id, COUNT(risiko_residual) as nilai, level_color, level_color_residual, level_color_target')->group_by('risiko_residual')->get(_TBL_VIEW_RCSA_DETAIL)->result_array();
		$data['map_residual']=$this->map->set_data($rows)->set_param(['tipe'=>'angka', 'level'=>2])->draw();
		$jml=$this->map->get_total_nilai();
		$jmlstatus=$this->map->get_jumlah_status();
		$data['jml_residual_status']=$jmlstatus;
		$data['jml_residual']='';
		if ($jml>0){
			$data['jml_residual']='<span class="badge bg-success badge-pill"> '.$jml.' </span>';
		}
		$this->data->filter_data();
		$rows = $this->db->SELECT('risiko_target as id, COUNT(risiko_target) as nilai, level_color, level_color_residual, level_color_target')->group_by('risiko_target')->get(_TBL_VIEW_RCSA_DETAIL)->result_array();
		$data['map_target']=$this->map->set_data($rows)->set_param(['tipe'=>'angka', 'level'=>3])->draw();
		$jml=$this->map->get_total_nilai();
		$jmlstatus=$this->map->get_jumlah_status();
		$data['jml_target_status']=$jmlstatus;
		$data['jml_target']='';
		if ($jml>0){
			$data['jml_target']='<span class="badge bg-warning badge-pill"> '.$jml.' </span>';
		}
		return $data;
	}

	function get_map(){
		$this->pos=$this->input->post();
		$this->data->pos=$this->pos;
		$this->data->owner_child=[];
		$this->data->owner_child[]=intval($this->pos['owner']);
		$this->data->get_owner_child(intval($this->pos['owner']));
		$this->owner_child=$this->data->owner_child;

		$data=$this->map();
		$hasil['combo']=$this->load->view('map',$data, true);

		echo json_encode($hasil);
	}

	function get_detail_map(){
		$post = $this->input->post();
		$this->data->pos=$post;
		$x=$this->data->get_data_map();
		$hasil['combo']=$this->load->view('ajax/identifikasi', $x, true);
		echo json_encode($hasil);
	}
}