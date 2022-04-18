<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Wilayah extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	function init($action='list'){
		$this->cboLevel=$this->crud->combo_value([1=>'Propinsi', 2=>'Kab/Kota'])->result_combo();
		$this->cboParent=$this->crud->combo_select(['id', 'name'])->combo_where('parent_id', 0)->combo_where('active', 1)->combo_tbl(_TBL_WILAYAH)->get_combo()->result_combo();
		
		$this->set_Tbl_Master(_TBL_WILAYAH);
		
		$this->addField(['field'=>'id', 'type'=>'int', 'show'=>false, 'size'=>4]);
		$this->addField(['field'=>'level', 'type'=>'int', 'input'=>'combo', 'values'=>$this->cboLevel, 'default'=>1,'search'=>true, 'size'=>100]);
		$this->addField(['field'=>'parent_id', 'type'=>'int', 'input'=>'combo', 'search'=>true, 'values'=>$this->cboParent, 'size'=>100]);
		$this->addField(['field'=>'code', 'required'=>true, 'search'=>true, 'size'=>100]);
		$this->addField(['field'=>'name', 'required'=>true, 'search'=>true, 'size'=>100]);
		$this->addField(['field'=>'lat', 'search'=>true, 'size'=>100]);
		$this->addField(['field'=>'lng', 'search'=>true, 'size'=>100]);
		$this->addField(['field'=>'active', 'type'=>'int', 'input'=>'boolean', 'size'=>20]);

		$this->set_Field_Primary($this->tbl_master, 'id');

		$this->set_Sort_Table($this->tbl_master,'level');
		$this->set_Sort_Table($this->tbl_master,'code');

		$this->set_Table_List($this->tbl_master,'parent_id');
		$this->set_Table_List($this->tbl_master,'code');
		$this->set_Table_List($this->tbl_master,'name');
		$this->set_Table_List($this->tbl_master,'lat');
		$this->set_Table_List($this->tbl_master,'lng');
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