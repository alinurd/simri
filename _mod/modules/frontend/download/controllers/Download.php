<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Download extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('file');
	}

	function init($action='list'){
		$this->kel_id=5;
		$this->set_Tbl_Master(_TBL_NEWS);
		
		$this->addField(['field'=>'id', 'show'=>false]);
		$this->addField(['field'=>'kel_id', 'default'=>$this->kel_id, 'show'=>false, 'save'=>true]);
		$this->addField(['field'=>'cover_image', 'title'=>'File', 'input'=>'upload', 'path'=>'file/download', 'file_thumb'=>false, 'file_type' =>str_replace(',','|',$this->_preference_['upload_type']), 'file_size'=>$this->_preference_['upload_size']]);
		$this->addField(['field'=>'title', 'required'=>true, 'search'=>true]);
		$this->addField(['field'=>'news', 'title'=>'Note', 'input'=>'html']);
		$this->addField(['field'=>'uri_title', 'save'=>true, 'show'=>false]);
		$this->addField(['field'=>'hit', 'show'=>false, 'search'=>false]);
		$this->addField(['field'=>'sticky', 'input'=>'bool:switch', 'search'=>true]);
		$this->addField(['field'=>'active', 'input'=>'bool:switch', 'default'=>1, 'search'=>true]);

		$this->set_Field_Primary($this->tbl_master, 'id');
		$this->set_Where_Table(['field'=>'kel_id', 'value'=>$this->kel_id]);

		$this->set_Sort_Table($this->tbl_master,'title');

		$this->set_Table_List($this->tbl_master,'title');
		$this->set_Table_List($this->tbl_master,'uri_title');
		$this->set_Table_List($this->tbl_master,'cover_image');
		$this->set_Table_List($this->tbl_master,'hit');
		$this->set_Table_List($this->tbl_master,'sticky');
		$this->set_Table_List($this->tbl_master,'active', '', 10,'center');

		$this->set_Close_Setting();

		$configuration = [
			'show_title_header' => false,
		];
		return [
			'configuration'	=> $configuration
		];
	}

	function insertValue_URI_TITLE($value, $rows, $old){
		$title=create_unique_slug($rows['title'], $this->tbl_master);
		return $title;
	}

	function updateValue_URI_TITLE($value, $rows, $old){
		$title=$value;
		if ($rows['title']!==$old['title'])
			$title=create_unique_slug($rows['title'], $this->tbl_master);
		return $title;
	}
}