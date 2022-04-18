<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Profile extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('file');
		$id = $this->uri->segment(3);
		if ($id!==$this->_data_user_['staft_id']){
			header('location:'.base_url('profile/edit/'.$this->_data_user_['staft_id']));
		}
	}

	function init($aksi=''){
		$this->cboGender=$this->crud->combo_value([1=>'Pria', 2=>'Wanita'])->result_combo();

		$this->set_Open_Tab('Data Employee');
			$this->set_Tbl_Master(_TBL_EMPLOYEE);
			$this->set_Table(_TBL_EMPLOYEE);
			$this->addField(['field'=>'id', 'show'=>false]);
			$this->addField(['field'=>'photo', 'input'=>'upload', 'path'=>'file/staft', 'file_thumb'=>true]);
			$this->addField(['field'=>'nip', 'required'=>false, 'search'=>true, 'size'=>20]);
			$this->addField(['field'=>'name', 'required'=>true, 'search'=>true, 'size'=>40]);
			$this->addField(['field'=>'gender', 'type'=>'int', 'input'=>'combo', 'values'=>$this->cboGender, 'size'=>100, 'search'=>true]);
			$this->addField(['field'=>'phone', 'size'=>30]);
			$this->addField(['field'=>'email', 'size'=>30]);
		$this->set_Close_Tab();

		$this->set_Field_Primary($this->tbl_master, 'id');
		$this->set_Join_Table(['pk'=>$this->tbl_master]);
		$this->tmp_data['sort'][]=['tbl'=>$this->tbl_master,'id'=>'name'];

		$this->set_Table_List($this->tbl_master,'nip');
		$this->set_Table_List($this->tbl_master,'name');
		$this->set_Close_Setting();

		$configuration = [
			'show_title_header' => false,
		];
		return [
			'configuration'	=> $configuration
		];
	}

	public function index()
	{
		header('location:'.base_url('profile/edit/'.$this->_data_user_['staft_id']));
		// $this->init('update');
		// $this->__update($this->_data_user_['id']);
	}

	function afterSave($id , $new_data, $old_data, $mode){
		$result=true;

		$this->crud->crud_table(_TBL_USERS);
		$this->crud->crud_type('edit');
		$this->crud->crud_where(['field'=>'staft_id', 'value'=>$id]);
		$this->crud->crud_field('real_name', $new_data['name']);
		if (!empty($new_data['photo'])){
			$this->crud->crud_field('photo', $new_data['photo']);
		}
		$this->crud->crud_field('email', $new_data['email']);
		$this->crud->crud_field('update_user', $this->ion_auth->get_user_name());
		$this->crud->process_crud();

		//$this->logdata->set_error("Gagal memproses data karena ");
		return $result;
	}

	function optionalButton($button, $mode){
		unset($button['back']);
		unset($button['save_quit']);
		return $button;
	}

}