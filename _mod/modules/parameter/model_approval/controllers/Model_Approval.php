<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Model_Approval extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	function init($action='list'){
		$this->kelompok_id='model-approval';
		$this->set_Tbl_Master(_TBL_COMBO);

		$this->addField(['field'=>'id', 'type'=>'int', 'show'=>false, 'size'=>4]);
		$this->addField(['field'=>'data', 'title'=>'Model Approval', 'required'=>true, 'search'=>true, 'size'=>100]);
		$this->addField(['field'=>'kelompok', 'show'=>false, 'save'=>true, 'default'=>$this->kelompok_id]);
		$this->addField(['field'=>'active', 'input'=>'boolean', 'size'=>20]);
		$this->addField(['field'=>'alur_approval', 'type'=>'free', 'mode'=>'a']);
		$this->addField(['field'=>'uri_title', 'show'=>false, 'save'=>true]);

		$this->set_Field_Primary($this->tbl_master, 'id');
		$this->set_Where_Table(['field'=>'kelompok', 'value'=>$this->kelompok_id]);

		$this->set_Sort_Table($this->tbl_master,'data');

		$this->set_Table_List($this->tbl_master,'data');
		$this->set_Table_List($this->tbl_master,'active','',7, 'center');

		$this->set_Close_Setting();

		if (_MODE_=='add') {
			$content_title = 'Penambahan Model Approval';
		}elseif(_MODE_=='edit'){
			$content_title = 'Perubahan Model Approval';
		}else{
			$content_title = 'Daftar Model Approval';
		}

		$configuration = [
			'show_title_header' => false,
			'content_title' => $content_title
		];
		return [
			'configuration'	=> $configuration
		];
	}

	function insertValue_URI_TITLE($value, $rows, $old){
		$title=create_unique_slug($rows['data'], $this->tbl_master);
		return $title;
	}

	function updateValue_URI_TITLE($value, $rows, $old){
		$title=$value;
		if ($rows['data']!==$old['data'])
			$title=create_unique_slug($rows['data'], $this->tbl_master);
		return $title;
	}

	function inputBox_ALUR_APPROVAL($mode, $field, $row, $value){
		if ($mode=='add'){
			$rows=[];
		}else{
			$rows=$this->db->where('pid', $row['id'])->where('kelompok','alur-approval')->get(_TBL_COMBO)->result_array();
		}
		$combo=$this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'level-approval')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$content = $this->load->view('alur', ['data'=>$rows, 'combo'=>$combo], true);
		return $content;
	}

	function afterSave($id , $new_data, $old_data, $mode){
		$result=$this->data->save_detail($id , $new_data, $old_data, $mode);
		return $result;
	}

	function optionalPersonalButton($button, $row){
		
		if ($row['id']==249){
			unset($button['delete']);
		}
		return $button;
	}
}