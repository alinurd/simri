<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Log_Activity extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
	}

	function init($action='list'){

		$this->cboType=$this->crud->combo_value([1=>'Login', 2=>'Logout', 5=>'add', 7=>'delete', 6=>'edit'])->result_combo();

		$this->set_Tbl_Master(_TBL_LOG);
		$this->set_Open_Tab('Data Log');
			$this->addField(['field'=>'id', 'show'=>false]);
			$this->addField(['field'=>'type', 'type'=>'int', 'title'=>'Type', 'input'=>'combo', 'search'=>true, 'values'=>$this->cboType]);
			$this->addField(['field'=>'ip', 'size'=>20]);
			$this->addField(['field'=>'module', 'search'=>true, 'size'=>100]);
			$this->addField(['field'=>'agent', 'input'=>"multitext", 'size'=>1000]);
			$this->addField(['field'=>'user_no', 'type'=>'int', 'size'=>20]);
			$this->addField(['field'=>'user_name', 'search'=>true, 'size'=>100]);
			$this->addField(['field'=>'created_at', 'type'=>'date', 'size'=>20]);
			$this->addField(['field'=>'message', 'input'=>"multitext", 'size'=>1000]);
		$this->set_Close_Tab();
		$this->set_Field_Primary(_TBL_LOG, 'id', false);
		$this->set_Join_Table(['pk'=>$this->tbl_master]);
		$this->set_Sort_Table($this->tbl_master,'created_at', 'desc');

		$this->set_Table_List($this->tbl_master,'type', '', 'center');
		$this->set_Table_List($this->tbl_master,'created_at');
		$this->set_Table_List($this->tbl_master,'ip');
		$this->set_Table_List($this->tbl_master,'module');
		$this->set_Table_List($this->tbl_master,'user_name');
		$this->set_Table_List($this->tbl_master,'message', '', 'center');

		$this->set_Close_Setting();
		$this->setPrivilege('update', false);
		$this->setPrivilege('delete', false);
		$this->setPrivilege('new', false);
		$configuration = [
			'show_title_header' => false,
		];
		return [
			'configuration'	=> $configuration
		];
	}

	function listBox_IP($fields, $rows, $value){
		if ($value=='::1')
			$value='localhost';

		return $value;
	}

	
	function listBox_MESSAGE($fields, $rows, $value){
		$value=str_replace('"', '',$value);
		$value=str_replace('"', '',$value);
		$content = "<i class='icon-newspaper pointer detail-notif'></i>";
		$content = "<span data-container='body' data-toggle='popover' data-placement='top' data-content='".$value."'> ".$content."</span>";

		return $content;
	}
}