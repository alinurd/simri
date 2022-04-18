<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Tutupan_Lahan extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	function init($action='list'){
		$this->set_Tbl_Master(_TBL_TUTUPAN_LAHAN);
		
		$this->addField(['field'=>'id', 'type'=>'int', 'show'=>false, 'size'=>4]);
		$this->addField(['field'=>'code', 'title'=>'Kode Unsur', 'required'=>true, 'search'=>true, 'size'=>100]);
		$this->addField(['field'=>'toponimi', 'required'=>true, 'search'=>true, 'size'=>100]);
		$this->addField(['field'=>'warna', 'required'=>true, 'size'=>100]);
		$this->addField(['field'=>'note', 'required'=>true, 'search'=>true, 'size'=>100]);
		$this->addField(['field'=>'active', 'input'=>'boolean', 'size'=>20]);

		$this->set_Field_Primary($this->tbl_master, 'id');

		$this->set_Sort_Table($this->tbl_master,'code');

		$this->set_Table_List($this->tbl_master,'code','',10, 'center');
		$this->set_Table_List($this->tbl_master,'toponimi','',10, 'center');
		$this->set_Table_List($this->tbl_master,'note');
		$this->set_Table_List($this->tbl_master,'active','',7, 'center');

		$this->set_Close_Setting();

		$configuration = [
			'show_title_header' => false,
		];
		return [
			'configuration'	=> $configuration
		];
	}
}