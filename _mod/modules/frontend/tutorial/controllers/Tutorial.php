<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Tutorial extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('file');
	}

	function init($action='list'){
		$this->kel_id=2;
		$this->set_Tbl_Master(_TBL_NEWS);

		$this->addField(['field'=>'id', 'show'=>false]);
		$this->addField(['field'=>'kel_id', 'default'=>$this->kel_id, 'show'=>false, 'save'=>true]);
		$this->addField(['field'=>'cover_image', 'input'=>'upload', 'path'=>'file/news', 'file_thumb'=>false]);
		$this->addField(['field'=>'title', 'required'=>true, 'search'=>true]);
		$this->addField(['field'=>'uri_title', 'show'=>false, 'save'=>true]);
		$this->addField(['field'=>'news', 'input'=>'html']);
		$this->addField(['field'=>'sticky', 'input'=>'bool:switch', 'search'=>true]);
		$this->addField(['field'=>'active', 'input'=>'bool:switch', 'search'=>true]);

		$this->set_Field_Primary($this->tbl_master, 'id');
		$this->set_Where_Table(['field'=>'kel_id', 'value'=>$this->kel_id]);

		$this->set_Sort_Table($this->tbl_master,'title');

		$this->set_Table_List($this->tbl_master,'title');
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
}