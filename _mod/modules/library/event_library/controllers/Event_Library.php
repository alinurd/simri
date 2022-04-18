<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Event_Library extends MY_Controller {
	var $type_risk=0;
	var $risk_type=[];
	public function __construct() {
		parent::__construct();
	}
	
	function init($action='list'){
		$this->type_risk=2;
		$this->kel=$this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'lib-cat')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$this->cbo_risk_type=$this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'risk-type')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$this->cbo_status=$this->crud->combo_value([1=>'aktif', 2=>'tidak aktif'])->result_combo();
		
		$this->set_Tbl_Master(_TBL_VIEW_LIBRARY);

		$this->set_Open_Tab('Data Risk Event Library');
			$this->addField(['field'=>'id', 'type'=>'int', 'show'=>false, 'size'=>4]);
			// $this->addField(['field'=>'kel', 'save'=>false, 'input'=>'combo', 'search'=>true, 'values'=>$this->kel, 'size'=>50]);
			// $this->addField(['field'=>'risk_type_no', 'type'=>'int', 'input'=>'combo', 'search'=>true, 'values'=>[' - Pilih - '], 'size'=>50]);
			// $this->addField(['field'=>'code',  'search'=>true, 'size'=>25]);
			$this->addField(['field'=>'library', 'title'=>'Risk Event', 'input'=>'multitext', 'search'=>true, 'size'=>500]);
			$this->addField(['field'=>'jml_couse', 'title'=>'Jml Cause', 'type'=>'free', 'show'=>false, 'search'=>false]);
			$this->addField(['field'=>'jml_impact', 'type'=>'free', 'show'=>false, 'search'=>false]);
			$this->addField(['field'=>'nama_kelompok', 'show'=>false]);
			$this->addField(['field'=>'used', 'type'=>'free', 'show'=>false, 'search'=>false]);
			$this->addField(['field'=>'risk_type', 'show'=>false]);
			$this->addField(['field'=>'created_by', 'show'=>false]);
			$this->addField(['field'=>'type', 'type'=>'int', 'default'=>$this->type_risk, 'show'=>false, 'save'=>true]);
			$this->addField(['field'=>'active', 'type'=>'int', 'input'=>'combo', 'values'=>$this->cbo_status, 'default'=>1, 'size'=>40]);
		$this->set_Close_Tab();
			
		$this->set_Field_Primary($this->tbl_master, 'id');
		$this->set_Join_Table(['pk'=>$this->tbl_master]);
		
		$this->set_Sort_Table($this->tbl_master,'id');
		$this->set_Where_Table(['tbl'=>$this->tbl_master, 'field'=>'type', 'op'=>'=', 'value'=>$this->type_risk]);
		
		// $this->set_Table_List($this->tbl_master,'nama_kelompok');
		// $this->set_Table_List($this->tbl_master,'risk_type');
		// $this->set_Table_List($this->tbl_master,'code', '', 10, 'center');
		$this->set_Table_List($this->tbl_master,'library');
		// $this->set_Table_List($this->tbl_master,'jml_couse', '', 10, 'center');
		$this->set_Table_List($this->tbl_master,'used', '', 10, 'center');
		$this->set_Table_List($this->tbl_master,'created_by');
		$this->set_Table_List($this->tbl_master,'active');
		
		$this->set_Close_Setting();
		$this->set_Save_Table(_TBL_LIBRARY);
		$configuration = [
			'show_title_header' => false,
		];
		return [
			'configuration'	=> $configuration
		];
	}
	
	function MASTER_DATA_LIST($id, $field){
		if ($id)
			$this->data->cari_total_dipakai($id);
	}
	
	function inputBox_RISK_TYPE_NO($mode, $field, $rows, $value){
		if ($mode=='edit'){
			$id=0;
			if (isset($rows['risk_type_no']))
			$id=$rows['risk_type_no'];
			$rows = $this->db->where('id', $id)->get(_TBL_COMBO)->row_array();
			$x=0;
			if ($rows){$x = $rows['pid'];}
			$field['values'] = $this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'risk-type')->combo_where('pid', $x)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		
		}
		$content = $this->set_box_input($field, $value);
		return $content;
	}

	function inputBox_KEL($mode, $field, $rows, $value){
		if ($mode=='edit'){
			$id=0;
			if (isset($rows['risk_type_no']))
				$id=$rows['risk_type_no'];
			$rows = $this->db->where('id', $id)->get(_TBL_VIEW_RISK_TYPE)->row_array();

			if ($rows){$value = $rows['pid'];}
		}
		$content = $this->set_box_input($field, $value);
		return $content;
	}
	
	function listBox_USED($field, $row, $value){
		$result='';
		$value=$this->data->get_used($row['id']);
		if ($value>0)
			$result =  '<span class="badge bg-success detail-used pointer" data-id="'.$row['id'].'" title="klik untuk melihat detail">' . $value . '</span>';
		return $result;
	}

	function optionalPersonalButton($button, $row){
		
		$v1=$this->data->get_used($row['id']);

		if ($v1>0){
			unset($button['delete']);
		}
		return $button;
	}
	// function inputBox_CODEx($mode, $field, $rows, $value){
	// 	$content = form_input($field['label'],$value," size='{$field['size']}' class='form-control'  id='{$field['label']}' readonly='readonly' ");
	// 	return $content;
	// }
}