<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Email_Template extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	function init($action='list'){

		$this->set_Tbl_Master(_TBL_TEMPLATE_EMAIL);

		$this->addField(['field'=>'id', 'show'=>false]);
		$this->addField(['field'=>'code', 'required'=>true, 'readonly'=>($this->_mode_=='edit')?false: false, 'search'=>true]);
		$this->addField(['field'=>'title', 'search'=>true]);
		$this->addField(['field'=>'subject', 'search'=>true]);
		// $this->addField(['field'=>'content_text', 'input'=>'multitext', 'size'=>1000, 'search'=>true]);
		$this->addField(['field'=>'content_html', 'input'=>'html']);
		$this->addField(['field'=>'active', 'input'=>'boolean', 'search'=>true]);

		$this->set_Field_Primary($this->tbl_master, 'id');

		$this->set_Sort_Table($this->tbl_master,'code');

		$this->set_Table_List($this->tbl_master,'code');
		$this->set_Table_List($this->tbl_master,'title');
		$this->set_Table_List($this->tbl_master,'subject');
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