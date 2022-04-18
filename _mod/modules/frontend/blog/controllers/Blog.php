<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Blog extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('file');
	}

	function init($action='list'){
		$this->kel_id=2;
		$this->cboParent=$this->datacombo->set_data('cat-blog')->set_noblank()->build();
		$this->cboVisibility=$this->crud->combo_value([1=>'Public', 2=>'Private', 3=>'Password Protected'])->result_combo();

		$this->set_Tbl_Master(_TBL_NEWS);
		$this->set_Open_Tab('data : '.$this->lang->line('blog_title'));
			$this->addField(['field'=>'id', 'show'=>false]);
			$this->addField(['field'=>'kel_id', 'default'=>$this->kel_id, 'show'=>false, 'save'=>true]);
			$this->addField(['field'=>'cover_image', 'input'=>'upload', 'path'=>'file/blog', 'file_thumb'=>true]);
			$this->addField(['field'=>'category_id', 'type'=>'int','input'=>'combo', 'search'=>true, 'values'=>$this->cboParent]);
			$this->addField(['field'=>'title', 'required'=>true, 'search'=>true]);
			$this->addField(['field'=>'news', 'input'=>'html']);
			$this->addField(['field'=>'uri_title', 'save'=>true, 'show'=>false]);
			$this->addField(['field'=>'cover_pdf', 'input'=>'upload', 'path'=>'file/blog', 'file_thumb'=>false, 'file_type'=>'jpg|png']);
			$this->addField(['field'=>'file_pdf', 'input'=>'upload', 'path'=>'file/blog', 'file_thumb'=>false, 'file_type'=>'pdf|pdfx']);
			$this->addField(['field'=>'visibility', 'type'=>'int', 'input'=>'combo', 'default'=>1, 'values'=>$this->cboVisibility, 'size'=>100, 'search'=>true]);
			$this->addField(['field'=>'tags', 'input'=>'tag', 'search'=>true]);
			$this->addField(['field'=>'allow_comment', 'input'=>'bool:switch', 'default'=>1, 'search'=>true]);
			$this->addField(['field'=>'active', 'input'=>'bool:switch', 'default'=>1, 'search'=>true]);
		$this->set_Close_Tab();
		$this->_multi_language(['title', 'news']);
		$this->_meta_seo();

		$this->set_Field_Primary($this->tbl_master, 'id');
		$this->set_Where_Table(['field'=>'kel_id', 'value'=>$this->kel_id]);

		$this->set_Sort_Table($this->tbl_master,'title');

		$this->set_Table_List($this->tbl_master,'title');
		$this->set_Table_List($this->tbl_master,'tags');
		$this->set_Table_List($this->tbl_master,'visibility', '', 10,'center');
		$this->set_Table_List($this->tbl_master,'allow_comment', '', 10,'center');
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