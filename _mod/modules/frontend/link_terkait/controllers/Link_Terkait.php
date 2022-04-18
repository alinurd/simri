<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Link_Terkait extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	function init($action='list'){
		$this->kel_id=6;
		$this->set_Tbl_Master(_TBL_NEWS);

		$this->set_Open_Tab('Data Pages');
			$this->addField(['field'=>'id', 'show'=>false]);
			$this->addField(['field'=>'kel_id', 'default'=>$this->kel_id, 'show'=>false, 'save'=>true]);
			$this->addField(['field'=>'title', 'required'=>true, 'search'=>true]);
			$this->addField(['field'=>'uri_title', 'save'=>true, 'show'=>false]);
			$this->addField(['field'=>'url']);
			$this->addField(['field'=>'urut', 'input'=>'updown', 'default'=>1]);
			$this->addField(['field'=>'active', 'input'=>'bool:switch', 'default'=>1]);
		$this->set_Close_Tab();
		$this->_multi_language(['title']);
		// $this->_meta_seo();

		$this->set_Field_Primary($this->tbl_master, 'id');
		$this->set_Where_Table(['field'=>'kel_id', 'value'=>$this->kel_id]);

		$this->set_Sort_Table($this->tbl_master,'title');

		$this->set_Table_List($this->tbl_master,'title');
		// $this->set_Table_List($this->tbl_master,'category_id');
		$this->set_Table_List($this->tbl_master,'url');
		$this->set_Table_List($this->tbl_master,'active', '', 10,'center');

		$this->set_Close_Setting();

		$configuration = [
			'show_title_header' => false,
		];
		return [
			'configuration'	=> $configuration
		];
	}

	function insertValue_URI_TITLE($value, $rows, $old){
		$title=create_unique_slug($rows['title'], $this->tbl_master);
		return $title;
	}

	function updateValue_URI_TITLE($value, $rows, $old){
		$title=$value;
		if ($rows['title']!==$old['title'])
			$title=create_unique_slug($rows['title'], $this->tbl_master);
		return $title;
	}
}