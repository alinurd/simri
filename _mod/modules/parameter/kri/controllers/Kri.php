<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Kri extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	function init($action='list'){
		$this->tipe=$this->crud->combo_select(['id', 'data'])->combo_where('active', 1)->combo_where('kelompok','tipe-kri')->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$this->kpi=$this->crud->combo_select(['id', 'data'])->combo_where('active', 1)->combo_where('kelompok','kpi')->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$this->kel=$this->crud->combo_value([1=>'Likelihood', 2=>'Dampak'])->result_combo();
		$this->kelompok_id='kri';
		$this->set_Tbl_Master(_TBL_COMBO);
		$this->tbly = _TBL_COMBO .' as x';
	
		$this->cboDept=$this->get_combo_parent_dept();

		
		$this->addField(['field'=>'id', 'type'=>'int', 'show'=>false, 'size'=>4]);
		$this->addField(['tbl'=>'x','field'=>'param_text', 'title'=>'Department', 'type'=>'text', 'required'=>true,'input'=>'combo', 'search'=>true, 'values'=>$this->cboDept, 'save'=>false, 'show'=>false]);

		$this->addField(['field'=>'param_int', 'title'=>'Kelompok', 'input'=>'combo', 'values'=>$this->kel, 'size'=>100, 'search'=>true]);
		$this->addField(['field'=>'pid', 'title'=>'Tipe', 'input'=>'combo', 'values'=>$this->tipe, 'size'=>100, 'search'=>true]);
		$this->addField(['field'=>'param_other_int', 'title'=>'KPI', 'input'=>'combo', 'values'=>$this->kpi, 'size'=>100, 'search'=>true]);
		
		$this->addField(['field'=>'data', 'title'=>'KRI', 'required'=>true,'input'=>'multitext', 'search'=>true, 'size'=>500]);
		$this->addField(['field'=>'kelompok', 'show'=>false, 'save'=>true, 'default'=>$this->kelompok_id]);
		$this->addField(['field'=>'urut', 'input'=>'updown', 'size'=>20, 'min'=>1, 'default'=>1]);
		$this->addField(['field'=>'active', 'input'=>'boolean', 'size'=>20]);
		$this->addField(['field'=>'uri_title', 'show'=>false, 'save'=>true]);

		$this->set_Field_Primary($this->tbl_master, 'id');
		$this->set_Join_Table(array(
			'pk'=>$this->tbl_master,
			'id_pk' => 'param_other_int',
			'sp' => $this->tbly ,
			'id_sp' => 'id',
			'type'=>'left'
		));

		$this->set_Where_Table(['field'=>'kelompok', 'value'=>$this->kelompok_id]);

		$this->set_Sort_Table($this->tbl_master,'param_int');
		$this->set_Sort_Table($this->tbl_master,'pid');
		$this->set_Sort_Table($this->tbl_master,'urut');

		$this->set_Table_List($this->tbl_master,'param_int');
		$this->set_Table_List($this->tbl_master,'pid');
		$this->set_Table_List($this->tbl_master,'param_other_int');
		$this->set_Table_List($this->tbl_master,'data');
		$this->set_Table_List($this->tbl_master,'id', 'Total Terpakai');
		$this->set_Table_List($this->tbly,'param_text', 'Total Dept');
		$this->set_Table_List($this->tbl_master,'urut');
		$this->set_Table_List($this->tbl_master,'active','',7, 'center');

		$this->set_Close_Setting();

		if (_MODE_=='add') {
			$content_title = 'Penambahan KRI';
		}elseif(_MODE_=='edit'){
			$content_title = 'Perubahan KRI';
		}else{
			$content_title = 'Daftar KRI';
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

	function searchBox_PARAM_TEXTx($data=[], $isi){
		$x = [];
		if ($data) {
			$x[$isi] = $data;
		}
		return $x;
	}

	function listBox_ID($field, $rows, $value){
        
		$this->db->select("count('kri_id') as total", false);
		$this->db->where('kri_id', $value);
		$data = $this->db->get(_TBL_RCSA_DET_LIKE_INDI);
		$d = $data->row();
		$like = $d->total;

		$this->db->select("count('kri_id') as total", false);
		$this->db->where('kri_id', $value);
		$data = $this->db->get(_TBL_RCSA_DET_DAMPAK_INDI);
		$e = $data->row();
		$dampak = $e->total;

		return $like+$dampak;
	}

	function listBox_PARAM_TEXT($field, $rows, $value){
        $val = json_decode($value, true);
		$jml = (is_array($val))?count($val):0;
		
		return $jml;
	}
	
}