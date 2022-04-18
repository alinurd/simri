<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Operator extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('file');

	}

	function init($action='list'){
		$this->cboGroup=$this->crud->combo_select(['id', 'name'])->combo_where('active', 1)->combo_tbl(_TBL_GROUPS)->get_combo()->result_combo();
		
		
		$this->set_Tbl_Master(_TBL_USERS);

		$this->set_Open_Tab('Data Petugas');
			$this->addField(['field'=>'id', 'show'=>false]);
			if ($this->configuration['show_list_photo'])
			$this->addField(['field'=>'photo', 'input'=>'upload', 'path'=>'file/staft', 'file_thumb'=>true]);
			$this->addField(['field'=>'real_name', 'required'=>true, 'search'=>true]);
			$this->addField(['field'=>'email', 'required'=>true, 'search'=>true, 'size'=>30]);
			$this->addField(['field'=>'active', 'input'=>'bool:switch', 'search'=>true]);
			$this->addField(['field'=>'is_admin', 'show'=>false]);
			$this->addField(['field'=>'last_login', 'show'=>false]);
			$this->addField(['field'=>'ip_address', 'show'=>false]);
			$this->addField(['field'=>'session_id', 'title'=>'Session', 'show'=>false]);
			$this->addField(['field'=>'staft_id', 'show'=>false, 'save'=>false]);
			$this->addField(['field'=>'username', 'size'=>40, 'line'=>true, 'line-text'=>'Authentication', 'line-icon'=>'icon-users']);
			$this->addField(['field'=>'password', 'type'=>'free', 'input'=>'pass', 'size'=>40]);
			$this->addField(['field'=>'passwordc', 'type'=>'free', 'input'=>'pass', 'size'=>40]);
			$this->addField(['field'=>'group_no', 'title'=>'Group', 'type'=>'string', 'input'=>'combo', 'search'=>true, 'values'=>$this->cboGroup, 'multiselect'=>true]);
		$this->set_Close_Tab();

		$this->set_Field_Primary(_TBL_USERS, 'id');
		$this->set_Join_Table(['pk'=>$this->tbl_master]);

		$this->set_Sort_Table($this->tbl_master,'real_name');

		if ($this->configuration['show_list_photo']){
			$this->set_Table_List($this->tbl_master,'photo',0,'center');
		}
		$this->set_Table_List($this->tbl_master,'real_name');
		$this->set_Table_List($this->tbl_master,'email');
		$this->set_Table_List($this->tbl_master,'username');
		$this->set_Table_List($this->tbl_master,'group_no');
		$this->set_Table_List($this->tbl_master,'last_login');
		$this->set_Table_List($this->tbl_master,'session_id','', 'center');

		$this->set_Close_Setting();

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
			$o='<small>'.time_ago(date('d M Y H:i:s', $value)).'</small>';
		}
		return $o;
	}

	function listBox_SESSION_ID($field, $rows, $value){
		$o='';
		if (!empty($value)){
			$o='<a href="'.base_url(_MODULE_NAME_.'/reset-session/'.$rows['id']).'"> Reset </a>';
		}
		return $o;
	}

	function reset_session(){
		$id=intval($this->uri->segment(3));
		$this->db->update('users', ['session_id'=>null], ['id' => $id]);
		header('location:'.base_url(_MODULE_NAME_));
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
		if ($result){
			$result=$this->data->save_group($id , $new_data);
		}
	
		return $result;
	}
}