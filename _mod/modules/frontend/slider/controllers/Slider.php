<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Slider extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('file');
	}

	function init($action='list'){
		$this->kel_id=4;
		$this->cboUrl=$this->crud->combo_value([-1=>$this->lang->line('cbo_select_parent'), 0=>'External', 1=>'News', 2=>'Blog', 3=>'Page', 10=>'Modul'])->noSelect()->result_combo();
		$this->set_Tbl_Master(_TBL_NEWS);
		
		$this->addField(['field'=>'id', 'show'=>false]);
		$this->addField(['field'=>'kel_id', 'default'=>$this->kel_id, 'show'=>false, 'save'=>true]);
		$this->addField(['field'=>'cover_image', 'input'=>'upload', 'path'=>'file/slider', 'file_thumb'=>true]);
		$this->addField(['field'=>'title', 'required'=>true, 'search'=>true]);
		$this->addField(['field'=>'news', 'title'=>'Description', 'input'=>'multitext', 'size'=>200]);
		$this->addField(['field'=>'uri_title', 'save'=>true, 'show'=>false]);
		$this->addField(['field'=>'url_type', 'type'=>'int', 'input'=>'combo', 'values'=>$this->cboUrl, 'default'=>0, 'size'=>100, 'search'=>true]);
		$this->addField(['field'=>'url']);
		$this->addField(['field'=>'urut', 'input'=>'updown', 'default'=>1]);
		$this->addField(['field'=>'active', 'input'=>'bool:switch', 'default'=>1, 'search'=>true]);

		$this->set_Field_Primary($this->tbl_master, 'id');
		$this->set_Where_Table(['field'=>'kel_id', 'value'=>$this->kel_id]);
		$this->set_Sort_Table($this->tbl_master,'title');

		$this->set_Table_List($this->tbl_master,'cover_image');
		$this->set_Table_List($this->tbl_master,'title');
		$this->set_Table_List($this->tbl_master,'urut', '', 10,'center');
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

	function inputBox_URL($mode, $field, $row, $value){
		$kel=0;
		if ($mode=='edit'){
		$kel = $row['url_type'];
		}
		if ($kel>=1 && $kel <=3){
			$cboProduct=$this->datacombo->upperGroup()->set_data()->isGroup()->set_noblank(false)->where(['kel_id'=>$kel])->build('news');
			$field['input']='combo';
			$field['values']=$cboProduct;
		}
		$content = $this->set_box_input($field, $value);
		return $content;
	}

	function list_url(){
		$id=$this->input->post('id');
		if ($id==0){
			$url = form_input('url', '', 'class="form-control  text-left" style="width:100% !important;" id="url"');
		}elseif ($id>0 && $id<=3 && $id<>50){
			$cboProduct=$this->datacombo->upperGroup()->set_data()->isGroup()->set_noblank(false)->where(['kel_id'=>$id])->build('news');
			$url = form_dropdown('url', $cboProduct, '', 'class="form-control select" style="width:100% !important;" id="url"');
		}

		$url .='<span class="text-muted" id="info_url">&nbsp;</span>';

		$hasil['combo'] = $url;
		echo json_encode($hasil);
	}
}