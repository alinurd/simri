<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Registration_Notcomplete extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('file');

	}

	function init($action='list'){
		$this->cboGroup=$this->crud->combo_select(['id', 'name'])->combo_where('active', 1)->combo_tbl(_TBL_GROUPS)->get_combo()->result_combo();
		$this->cboDept=$this->get_combo_parent_dept();
		$this->cboWilayah=$this->datacombo->isGroup(true)->build('wilayah');
		$this->cboType=$this->crud->combo_value([0=>'Sistem', 1=>'Mobile', 2=>'Website'])->result_combo();
		
		
		$this->set_Tbl_Master(_TBL_USERS);

		$this->set_Open_Tab('Data Petugas');
			$this->addField(['field'=>'id', 'show'=>false]);
			if ($this->configuration['show_list_photo'])
			$this->addField(['field'=>'photo', 'input'=>'upload', 'path'=>'file/staft', 'file_thumb'=>true]);
			$this->addField(['field'=>'dept_id', 'title'=>'Department', 'type'=>'int','input'=>'combo', 'search'=>true, 'values'=>$this->cboDept]);
			$this->addField(['field'=>'real_name', 'required'=>true, 'search'=>true]);
			$this->addField(['field'=>'email', 'required'=>true, 'search'=>true, 'size'=>30]);
			$this->addField(['field'=>'wilayah_id', 'title'=>'Department', 'type'=>'string','input'=>'combo', 'multiselect'=>true, 'search'=>true, 'values'=>$this->cboWilayah]);
			$this->addField(['field'=>'mobile_activation', 'input'=>'bool:switch', 'search'=>true]);
			$this->addField(['field'=>'registration_sts', 'show'=>false]);
			$this->addField(['field'=>'active', 'input'=>'bool:switch', 'search'=>true]);
			$this->addField(['field'=>'is_admin', 'show'=>false]);
			$this->addField(['field'=>'last_login', 'show'=>false]);
			$this->addField(['field'=>'ip_address', 'show'=>false]);
			$this->addField(['field'=>'registration_type', 'title'=>'Registration Type', 'type'=>'int','input'=>'combo', 'search'=>true, 'values'=>$this->cboType]);
			$this->addField(['field'=>'token', 'append'=>' reset token ', 'readonly'=>true]);
			$this->addField(['field'=>'staft_id', 'show'=>false, 'save'=>false]);
			$this->addField(['field'=>'username', 'size'=>40, 'line'=>true, 'line-text'=>'Authentication', 'line-icon'=>'icon-users']);
			$this->addField(['field'=>'password', 'type'=>'free', 'input'=>'pass', 'size'=>40]);
			$this->addField(['field'=>'passwordc', 'type'=>'free', 'input'=>'pass', 'size'=>40]);
			$this->addField(['field'=>'group_no', 'title'=>'Group', 'input'=>'combo', 'search'=>true, 'values'=>$this->cboGroup, 'multiselect'=>true]);
		$this->set_Close_Tab();
		$this->set_Open_Tab('Privilege');
			$this->addField(['field'=>'privilege', 'type'=>'free', 'input'=>'pass']);
		$this->set_Close_Tab();

		$this->set_Field_Primary(_TBL_USERS, 'id');
		$this->set_Join_Table(['pk'=>$this->tbl_master]);
		$this->set_Where_Table(['field'=>'registration_sts', 'op'=>'=', 'value'=>0]);

		$this->set_Sort_Table($this->tbl_master,'real_name');

		if ($this->configuration['show_list_photo']){
			$this->set_Table_List($this->tbl_master,'photo',0,'center');
		}
		$this->set_Table_List($this->tbl_master,'real_name');
		$this->set_Table_List($this->tbl_master,'email');
		$this->set_Table_List($this->tbl_master,'dept_id');
		$this->set_Table_List($this->tbl_master,'username');
		$this->set_Table_List($this->tbl_master,'group_no');
		$this->set_Table_List($this->tbl_master,'mobile_activation');
		$this->set_Table_List($this->tbl_master,'registration_type');
		$this->set_Table_List($this->tbl_master,'last_login');

		$this->set_Close_Setting();
		$this->setPrivilege('update', false);
		$configuration = [
			'show_title_header' => false,
		];
		return [
			'configuration'	=> $configuration
		];
	}

	function listBox_LAST_LOGIN($field, $rows, $value){
		$o='';
		if (!empty($value)){
			$o=time_ago(date('d M Y H:i:s', $value));
		}
		return $o;
	}

	function checkBeforeSave($data, $old_data, $mode='add'){
		$pesan="";
		$no=0;
		$result=false;
		if ($mode=='edit' && $data['username'] !== $old_data['username']){
			$result = $this->ion_auth->username_check($data['username'], $old_data['username']);
		}elseif ($mode=='add'){
			$result = $this->ion_auth->username_check($data['username'], '');
		}

		if ($result){
			$this->logdata->set_error("username - ".$data['username'].' - sudah digunakan');
			++$no;
		}
		
		$result=false;
		if ($mode=='edit' && $data['email'] !== $old_data['email']){
			$result = $this->ion_auth->email_check($data['email'], $old_data['email']);
		}elseif ($mode=='add'){
			$result = $this->ion_auth->email_check($data['email'], '');
		}
		if ($result){
			$this->logdata->set_error("Email - ".$data['email'].' - sudah digunakan');
			++$no;
		}

		if($data['password'] !== $data['passwordc']){
			$this->logdata->set_error("Password tidak sama");
			++$no;
		}
		
		if ($mode=='add'){
			if(empty($data['password']) || empty($data['username'])){
				$this->logdata->set_error("User name dan Password tidak boleh kosong");
				++$no;
			}
		}
		
		if (!isset($data['group_no'])){
			$this->logdata->set_error("User minimal harus memiliki 1 group yang aktif ");
			++$no;
		}
		
		if ($no>0){
			return FALSE;
		}else{
			return TRUE;
		}
	}

	function afterSave($id , $new_data, $old_data, $mode){
		$result=true;
		if (!empty($new_data['password'])){
			$result = $this->ion_auth->reset_password($new_data['username'], $new_data['password']);
		}
		$now = new DateTime();
		$tgl= $now->format('Y-m-d H:i:s');

		if ($result)
			$result=$this->data->save_group($id , $new_data);
		return $result;
	}

	function get_token(){
		$id=$this->input->get('id');
		$token = token();
		$data['combo']=$token;
		echo json_encode($data);
	}
}