<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Pages extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('file');
	}

	function init($action='list'){
		$this->kel_id=3;
		$this->cboParent=$this->datacombo->set_data('cat-page')->set_noblank()->build();
		$this->cboVisibility=$this->crud->combo_value([0=>'Draft', 1=>'Public', 2=>'Private'])->result_combo();
		$this->set_Tbl_Master(_TBL_NEWS);

		$this->set_Open_Tab('Data Pages');
			$this->addField(['field'=>'id', 'show'=>false]);
			$this->addField(['field'=>'kel_id', 'default'=>$this->kel_id, 'show'=>false, 'save'=>true]);
			$this->addField(['field'=>'cover_image', 'input'=>'upload', 'path'=>'file/pages', 'file_thumb'=>false]);
			$this->addField(['field'=>'category_id', 'type'=>'int','input'=>'combo', 'search'=>true, 'values'=>$this->cboParent]);
			$this->addField(['field'=>'title', 'required'=>true, 'search'=>true]);
			$this->addField(['field'=>'uri_title', 'save'=>true, 'show'=>false]);
			$this->addField(['field'=>'news', 'input'=>'html']);
			$this->addField(['field'=>'cover_pdf', 'input'=>'upload', 'path'=>'file/pages', 'file_thumb'=>false, 'file_type'=>'jpg|png']);
			$this->addField(['field'=>'file_pdf', 'input'=>'upload', 'path'=>'file/pages', 'file_thumb'=>false, 'file_type'=>'pdf|pdfx']);
			$this->addField(['field'=>'visibility', 'type'=>'int', 'input'=>'combo', 'values'=>$this->cboVisibility, 'default'=>1, 'size'=>100, 'search'=>true]);
			$this->addField(['field'=>'urut', 'input'=>'updown', 'default'=>1]);
			$this->addField(['field'=>'active', 'input'=>'bool:switch', 'default'=>1]);
		$this->set_Close_Tab();
		$this->_multi_language(['title', 'news']);
		$this->_meta_seo();
		
		$this->set_Field_Primary($this->tbl_master, 'id');
		$this->set_Where_Table(['field'=>'kel_id', 'value'=>$this->kel_id]);

		$this->set_Sort_Table($this->tbl_master,'title');

		$this->set_Table_List($this->tbl_master,'title');
		$this->set_Table_List($this->tbl_master,'category_id');
		$this->set_Table_List($this->tbl_master,'visibility');
		$this->set_Table_List($this->tbl_master,'active', '', 10,'center');

		$this->set_Close_Setting();

		$configuration = [
			'show_title_header' => false,
		];
		return [
			'configuration'	=> $configuration
		];
	}

	function  searchBox_VALUE($data=[]){

		if ($data){
            if (!empty($data['category_id'])){
                $this->data->pages_child=[];
                $this->data->pages_child[]=intval($data['category_id']);
                $this->data->get_pages_child($data['category_id']);
				$data['category_id'] = $this->data->pages_child;
            }
		}
		return $data;
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

	function listBox_PHOTO($field, $rows, $value){
		$pic='';
		$value = json_decode($value, true);
		foreach($value as $val){
			if ($val['default']==1){
				$pic=$val['name'];
				break;
			}
		}

		$result='';
		if (!empty($pic))
			$result = img($pic, 'file', ['class'=>'rounded-circle detail-img pointer', 'data-file'=>$pic, 'data-path'=>'file'], 'tiny');
		return $result;
	}

	function inputBox_GALLERY($mode, $field, $rows, $value){
		$data['rows']=[];
		if ($mode=='edit')
			$data['rows']=json_decode($rows['photo'], true);
		return $this->load->view('upload-image',$data, true);
	}

	function afterSavex($id , $new_data, $old_data, $mode){
		$result=$this->data->save_detail($id , $new_data, $old_data, $mode);
		return $result;
	}

}