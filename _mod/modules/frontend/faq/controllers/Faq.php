<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Faq extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	
	function init($action='list'){
		$this->cboCategory=$this->crud->combo_select(['id', 'data'])->combo_where(['active'=>1, 'kelompok'=>'cat-faq'])->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();

		$this->set_Tbl_Master(_TBL_FAQ);
		$this->addField(['field'=>'id', 'show'=>false]);
		$this->addField(['field'=>'category_id', 'type'=>'int','input'=>'combo', 'search'=>true, 'values'=>$this->cboCategory]);
		$this->addField(['field'=>'faq', 'required'=>true, 'search'=>true]);
		$this->addField(['field'=>'answer', 'input'=>'html']);
		$this->addField(['field'=>'order', 'input'=>'updown', 'default'=>1]);
		$this->addField(['field'=>'active', 'input'=>'bool:switch', 'default'=>1, 'search'=>true]);

		$this->set_Field_Primary($this->tbl_master, 'id');

		$this->set_Sort_Table($this->tbl_master,'faq');

		$this->set_Table_List($this->tbl_master,'category_id');
		$this->set_Table_List($this->tbl_master,'faq');
		$this->set_Table_List($this->tbl_master,'order', '', 10,'center');
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