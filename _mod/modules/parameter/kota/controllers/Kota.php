<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Kota extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	function init($action='list'){
		$this->cboProp=$this->crud->combo_select(['id', 'province'])->combo_where('active', 1)->combo_tbl(_TBL_PROVINSI)->get_combo()->result_combo();
		
		$this->set_Tbl_Master(_TBL_KOTA);
		
		$this->addField(['field'=>'id', 'type'=>'int', 'show'=>false, 'size'=>4]);
		$this->addField(['field'=>'province_id', 'title'=>'Department', 'type'=>'int','input'=>'combo', 'search'=>true, 'values'=>$this->cboProp]);
		$this->addField(['field'=>'code', 'required'=>true, 'search'=>true, 'size'=>100]);
		$this->addField(['field'=>'city', 'required'=>true, 'search'=>true, 'size'=>100]);
		$this->addField(['field'=>'lat', 'search'=>true, 'size'=>100]);
		$this->addField(['field'=>'lng', 'search'=>true, 'size'=>100]);
		$this->addField(['field'=>'active', 'input'=>'boolean', 'size'=>20]);

		$this->set_Field_Primary($this->tbl_master, 'id');

		$this->set_Sort_Table($this->tbl_master,'code');

		$this->set_Table_List($this->tbl_master,'province_id');
		$this->set_Table_List($this->tbl_master,'code');
		$this->set_Table_List($this->tbl_master,'city');
		$this->set_Table_List($this->tbl_master,'lat');
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