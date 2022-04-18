<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Treatment extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	function init($action='list'){
		$this->set_Tbl_Master(_TBL_TREATMENT);

		$this->addField(['field'=>'id', 'type'=>'int', 'show'=>false, 'size'=>4]);
		$this->addField(['field'=>'code', 'title'=>'Code', 'search'=>true, 'size'=>10, 'align'=>'center']);
		$this->addField(['field'=>'treatment', 'title'=>'Level', 'required'=>true, 'search'=>true, 'size'=>100]);
		$this->addField(['field'=>'color', 'input'=>'color', 'size'=>30]);
		$this->addField(['field'=>'sts_lanjut', 'input'=>'boolean', 'size'=>20]);
		$this->addField(['field'=>'urut', 'input'=>'updown', 'size'=>20, 'min'=>1, 'default'=>1]);
		$this->addField(['field'=>'active', 'input'=>'boolean', 'size'=>20]);

		$this->set_Field_Primary($this->tbl_master, 'id');

		$this->set_Sort_Table($this->tbl_master,'urut');

		$this->set_Table_List($this->tbl_master,'code');
		$this->set_Table_List($this->tbl_master,'treatment');
		$this->set_Table_List($this->tbl_master,'color');
		$this->set_Table_List($this->tbl_master,'sts_lanjut');
		$this->set_Table_List($this->tbl_master,'urut');
		$this->set_Table_List($this->tbl_master,'active','',7, 'center');

		$this->set_Close_Setting();

		if (_MODE_=='add') {
			$content_title = 'Penambahan Respon Risiko';
		}elseif(_MODE_=='edit'){
			$content_title = 'Perubahan Respon Risiko';
		}else{
			$content_title = 'Daftar Respon Risiko';
		}

		$configuration = [
			'show_title_header' => false,
			'content_title' => $content_title
		];
		return [
			'configuration'	=> $configuration
		];
	}
}