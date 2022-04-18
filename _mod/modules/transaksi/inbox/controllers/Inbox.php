<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Inbox extends MY_Controller {
	protected $type_id=0;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('file');
		$this->kd_sequence='TK';

	}

	function init($action='list'){
		$this->type_id=1;
		$this->set_Tbl_Master(_TBL_INBOX);

		$this->set_Open_Tab(lang(_MODULE_NAME_REAL_.'_title'));

			$this->addField(['field'=>'id', 'show'=>false]);
			$this->addField(['field'=>'name', 'search'=>true, 'size'=>100]);
			$this->addField(['field'=>'website', 'search'=>true, 'size'=>100]);
			$this->addField(['field'=>'email', 'search'=>true, 'size'=>100]);
			$this->addField(['field'=>'phone', 'search'=>true, 'size'=>100]);
			$this->addField(['field'=>'message', 'input'=>'multitext', 'size'=>500]);
			$this->addField(['field'=>'file', 'input'=>'upload', 'path'=>'file/support', 'file_thumb'=>false]);
			$this->addField(['field'=>'is_read', 'input'=>'boolean']);
			$this->addField(['field'=>'created_at', 'show'=>false]);

		$this->set_Close_Tab();
		$this->set_Field_Primary(_TBL_INBOX, 'id');
		$this->set_Where_Table(['field'=>'type_id', 'value'=>$this->type_id]);

		$this->set_Sort_Table(_TBL_INBOX,'created_at');

		$this->set_Table_List($this->tbl_master,'created_at');
		$this->set_Table_List($this->tbl_master,'name');
		$this->set_Table_List($this->tbl_master,'email');
		$this->set_Table_List($this->tbl_master,'phone');
		$this->set_Table_List($this->tbl_master,'is_read',10);

		$this->set_Close_Setting();
		$this->setPrivilege('insert', false);
		$this->setPrivilege('update', false);

		$configuration = [
			'content_title'	=> '<i class="icon-comment-discussion"></i> Support Center',
			'tab_title'	=> 'List Ticket',
		];
		return [
			'configuration'	=> $configuration
		];
	}
}