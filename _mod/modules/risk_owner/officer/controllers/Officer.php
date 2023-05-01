<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Officer extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('file');
		$this->groups_id=4;
	}

	function init($action='list'){
		$this->cboTitle=$this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'jabatan')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$this->cboDept=$this->get_combo_parent_dept();
		$this->cboGroup=$this->crud->combo_select(['id', 'name'])->combo_where('active', 1)->combo_tbl(_TBL_GROUPS)->get_combo()->result_combo();
		$this->cboGender=$this->crud->combo_value([1=>'Pria', 2=>'Wanita'])->result_combo();
		
		$this->set_Tbl_Master(_TBL_VIEW_OFFICER);

		$this->set_Open_Coloums();
			$this->addField(['field'=>'id', 'show'=>false]);
			$this->addField(['field'=>'photo', 'input'=>'upload', 'path'=>'file/staft', 'file_thumb'=>true, 'file_size'=>'5120', 'size_pic'=>132]);
			$this->addField(['field'=>'nip', 'required'=>true, 'search'=>true, 'title'=>'NIP [Username]']);
			$this->addField(['field'=>'officer_name', 'required'=>true, 'search'=>true]);
			$this->addField(['field'=>'owner_no', 'title'=>'Department', 'type'=>'int','input'=>'combo', 'search'=>true, 'values'=>$this->cboDept]);
			$this->addField(['field'=>'position_no', 'title'=>'Title', 'type'=>'int','input'=>'combo', 'search'=>true, 'values'=>$this->cboTitle]);
			$this->addField(['field'=>'gender_no', 'type'=>'int', 'input'=>'combo', 'values'=>$this->cboGender, 'size'=>100, 'search'=>true]);
			$this->addField(['field'=>'phone',  'search'=>true]);
			$this->addField(['field'=>'email',  'search'=>true]);
			$this->addField(['field'=>'sts_owner', 'input'=>'boolean', 'search'=>true]);
			// $this->addField(['field'=>'sts_approval', 'input'=>'boolean', 'search'=>true]);
			$this->addField(['field'=>'sts_mengetahui', 'title'=>'Status VP' , 'input'=>'boolean', 'search'=>true, 'default'=>0]);
			$this->addField(['field'=>'sts_menyetujui', 'title' => 'Status SVP', 'input'=>'boolean', 'search'=>true, 'default'=>0]);
			$this->addField(['field'=>'sts_menvalidasi','title' => 'Status Admin RM', 'input'=>'boolean', 'search'=>true, 'default'=>0]);
			$this->addField(['field'=>'active', 'input'=>'boolean', 'search'=>true]);
			$this->addField(['field'=>'user_no', 'show'=>false, 'save'=>false]);
			$this->set_Close_Coloums();
			$this->set_Open_Coloums();
			$this->addField(['field'=>'sts_login', 'input'=>'boolean', 'search'=>true, 'line'=>true, 'line-text'=>'Authentication', 'line-icon'=>'icon-users']);
			$this->addField(['field'=>'username', 'save'=>false]);
			$this->addField(['field'=>'password', 'type'=>'free', 'input'=>'pass']);
			$this->addField(['field'=>'passwordc', 'type'=>'free', 'input'=>'pass']);
			$this->addField(['field'=>'group', 'title'=>'Group', 'type'=>'free','input'=>'combo', 'values'=>$this->cboGroup, 'multiselect'=>true]);
		$this->set_Close_Coloums();

		$this->set_Field_Primary($this->tbl_master, 'id');
		$this->set_Join_Table(['pk'=>$this->tbl_master]);
		$this->set_Save_Table(_TBL_OFFICER);

		$this->set_Sort_Table($this->tbl_master,'officer_name');

		$this->set_Table_List($this->tbl_master,'photo','Photo',0,'center');
		$this->set_Table_List($this->tbl_master,'owner_no');
		$this->set_Table_List($this->tbl_master,'position_no');
		$this->set_Table_List($this->tbl_master,'nip');
		$this->set_Table_List($this->tbl_master,'officer_name');
		$this->set_Table_List($this->tbl_master,'email');
		$this->set_Table_List($this->tbl_master,'username');
		$this->set_Table_List($this->tbl_master,'sts_owner');
		// $this->set_Table_List($this->tbl_master,'sts_approval');
		$this->set_Table_List($this->tbl_master,'active');

		$this->set_Close_Setting();

		$configuration = [
			'show_title_header' => false,
			'modal_box_search' => false,
		];
		return [
			'configuration'	=> $configuration
		];
	}
	
	function inputBox_GROUP($mode, $field, $row, $value){
		if ($mode=='edit')
			$value=$this->data->get_group($row['id']);

		$content = $this->set_box_input($field, $value);
		return $content;
	}

	function inputBox_USER_ID($mode, $field, $row, $value){
		if ($mode=='edit'){
			$rows = $this->db->where('staft_id', $row['id'])->get(_TBL_USERS)->row();
			if ($rows)
				$value=$rows->id;
		}
		$content = $this->set_box_input($field, $value);
		return $content;
	}
	
	function inputBox_USERNAME($mode, $field, $row, $value){
		
		if ($mode=='edit'){
			$rows = $this->db->where('id', $row['id'])->get(_TBL_OFFICER)->row();
			if ($rows)
				$value=$rows->nip;
		}
		
		$content = $this->set_box_input($field, $value);
		return $content;
	}

	function checkBeforeSavex($data, $old_data, $mode='add'){
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
		
		if (!isset($data['group'])){
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
		if ($new_data['sts_login']==1){
			if ($mode=='add' || empty($new_data['user_no'])){
				$this->crud->crud_table(_TBL_USERS);
				$this->crud->crud_type('add');
				$this->crud->crud_field('staft_id', $id, 'int');
				$this->crud->crud_field('username', $new_data['nip']);
				$this->crud->crud_field('real_name', $new_data['officer_name']);
				$this->crud->crud_field('photo', $new_data['photo']);
				$this->crud->crud_field('email', $new_data['email']);
				$this->crud->crud_field('group_no', implode(',',$new_data['group']));
				$this->crud->crud_field('created_by', $this->ion_auth->get_user_name());
				$this->crud->process_crud();
			}else{
				$this->crud->crud_table(_TBL_USERS);
				$this->crud->crud_type('edit');
				$this->crud->crud_where(['field'=>'staft_id', 'value'=>$id]);
				$this->crud->crud_field('username', $new_data['nip']);
				$this->crud->crud_field('real_name', $new_data['officer_name']);
				if (empty($new_data['photo'])){
					$this->crud->crud_field('photo', $new_data['photo_tmp']);
				}else{
					$this->crud->crud_field('photo', $new_data['photo']);
				}
				$this->crud->crud_field('group_no', implode(',',$new_data['group']));
				$this->crud->crud_field('email', $new_data['email']);
				$this->crud->crud_field('updated_by', $this->ion_auth->get_user_name());
				$this->crud->process_crud();
			}
			$users = $this->db->where('staft_id', $id)->get(_TBL_USERS)->row();
			if (!empty($new_data['password']))
				$result = $this->ion_auth->reset_password($new_data['nip'], $new_data['password']);
			if ($result)
				$result=$this->data->save_group($id , $new_data, $users);
		}

		return $result;
	}
}