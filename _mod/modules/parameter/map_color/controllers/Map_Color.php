<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Map_Color extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	function init($action='list'){
		$this->set_Tbl_Master(_TBL_LEVEL_COLOR);

		$this->addField(['field'=>'id', 'type'=>'int', 'show'=>false, 'size'=>4]);
		$this->addField(['field'=>'level_color', 'title'=>'Level', 'required'=>true, 'search'=>true, 'size'=>100]);
		$this->addField(['field'=>'score_min', 'size'=>30, 'align'=>'center']);
		$this->addField(['field'=>'score_max', 'size'=>30, 'align'=>'center']);
		$this->addField(['field'=>'color', 'input'=>'color', 'size'=>15]);
		$this->addField(['field'=>'color_text', 'input'=>'color', 'size'=>15]);
		$this->addField(['field'=>'urut', 'input'=>'updown', 'size'=>20, 'min'=>1, 'default'=>1]);
		$this->addField(['field'=>'active', 'input'=>'boolean', 'size'=>20]);

		$this->set_Field_Primary($this->tbl_master, 'id');

		$this->set_Sort_Table($this->tbl_master,'urut');

		$this->set_Table_List($this->tbl_master,'level_color');
		$this->set_Table_List($this->tbl_master,'color','',10, 'center');
		$this->set_Table_List($this->tbl_master,'score_min','',10, 'center');
		$this->set_Table_List($this->tbl_master,'score_max','',10, 'center');
		$this->set_Table_List($this->tbl_master,'urut');
		$this->set_Table_List($this->tbl_master,'active','',7, 'center');

		$this->set_Close_Setting();

		if (_MODE_=='add') {
			$content_title = 'Penambahan Map Color';
		}elseif(_MODE_=='edit'){
			$content_title = 'Perubahan Map Color';
		}else{
			$content_title = 'Daftar Map Color';
		}

		$configuration = [
			'show_title_header' => false,
			'content_title' => $content_title
		];
		return [
			'configuration'	=> $configuration
		];
	}

	function listBox_COLOR($fields, $rows, $value){
		$o='<span style="background-color:'.$value.';color:'.$rows['color_text'].';padding:4px 10px;">'.$rows['level_color'].'</span>';
		
		return $o;
	}
}