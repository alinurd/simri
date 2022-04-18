<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Control_Component extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	function init($action='list'){
		$this->kelompok_id='existing-control';
		$this->set_Tbl_Master(_TBL_COMBO);
		
		$this->addField(['field'=>'id', 'type'=>'int', 'show'=>false, 'size'=>4]);
		$this->addField(['field'=>'data', 'title'=>'Existing Control', 'required'=>true, 'search'=>true, 'size'=>100]);
		$this->addField(['field'=>'kelompok', 'show'=>false, 'save'=>true, 'default'=>$this->kelompok_id]);
		$this->addField(['field'=>'urut', 'input'=>'updown', 'size'=>20, 'min'=>1, 'default'=>1]);
		$this->addField(['field'=>'active', 'input'=>'boolean', 'size'=>20]);
		$this->addField(['field'=>'uri_title', 'show'=>false, 'save'=>true]);

		$this->set_Field_Primary($this->tbl_master, 'id');
		$this->set_Where_Table(['field'=>'kelompok', 'value'=>$this->kelompok_id]);

		$this->set_Sort_Table($this->tbl_master,'data');

		$this->set_Table_List($this->tbl_master,'data');
		$this->set_Table_List($this->tbl_master,'id', 'Total Terpakai');
		$this->set_Table_List($this->tbl_master,'urut');
		$this->set_Table_List($this->tbl_master,'active','',7, 'center');

		$this->set_Close_Setting();

		
		if (_MODE_=='add') {
			$content_title = 'Penambahan exisiting kontrol';
		}elseif(_MODE_=='edit'){
			$content_title = 'Perubahan exisiting kontrol';
		}else{
			$content_title = 'Daftar exisiting kontrol';
		}

		$configuration = [
			'show_title_header' => false,
			'content_title' => $content_title
		];
		return [
			'configuration'	=> $configuration
		];
	}

	function listBox_ID($field, $rows, $value){
        $nama=$rows['data'];
		$this->db->select("count('nama_kontrol') as total", false);
		$this->db->where('nama_kontrol like', '%'.$nama.'%');
		$data = $this->db->get(_TBL_RCSA_DETAIL);
		$d = $data->row();

		return $d->total;
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
}