<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Error_Log extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
	}

	function init($action='list'){

		$this->cboType=$this->crud->combo_value([0=>'error', 1=>'sql', 2=>'like', 3=>'unlike', 4=>'download', 5=>'preview', 6=>'delete', 7=>'login'])->result_combo();

		$this->set_Tbl_Master(_TBL_ERROR_LOGS);
		$this->set_Open_Tab('Data Log');
			$this->addField(['field'=>'id', 'show'=>false]);
			$this->addField(['field'=>'errno', 'title'=>'Error No.', 'search'=>true]);
			$this->addField(['field'=>'user_id', 'title'=>'User Name', 'search'=>true]);
			$this->addField(['field'=>'errtype', 'title'=>'Error Type']);
			$this->addField(['field'=>'ip_address', 'title'=>'IP', 'size'=>20]);
			$this->addField(['field'=>'errstr', 'size'=>20, 'search'=>true]);
			$this->addField(['field'=>'errfile', 'input'=>"multitext", 'size'=>20]);
			$this->addField(['field'=>'errline', 'size'=>20]);
			$this->addField(['field'=>'user_agent', 'size'=>20]);
			$this->addField(['field'=>'time', 'size'=>20]);
		$this->set_Close_Tab();
		$this->set_Field_Primary(_TBL_ERROR_LOGS, 'id', false);
		$this->set_Join_Table(['pk'=>$this->tbl_master]);
		$this->set_Sort_Table($this->tbl_master,'time', 'desc');

		$this->set_Table_List($this->tbl_master,'time');
		$this->set_Table_List($this->tbl_master,'user_id');
		$this->set_Table_List($this->tbl_master,'errtype');
		$this->set_Table_List($this->tbl_master,'errstr');
		$this->set_Table_List($this->tbl_master,'errfile');
		$this->set_Table_List($this->tbl_master,'errline');

		$this->set_Close_Setting();

		$configuration = [
			'show_title_header' => false,
		];
		return [
			'configuration'	=> $configuration
		];
	}

	function listBox_ERRFILE($fields, $rows, $value){
		$val=explode("\\", $value);
		$value = $val[count($val)-2].'/'.$val[count($val)-1];
		return $value;
	}
}