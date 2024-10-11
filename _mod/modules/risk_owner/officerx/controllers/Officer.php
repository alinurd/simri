<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Officer extends MY_Controller
{
	var $table = "";
	var $post = array();
	var $sts_cetak = false;
	public function __construct()
	{
		parent::__construct();
	}
	
	function init($action='list'){
		$this->load->helper('file');
		$this->data_group=$this->crud->combo_select(['id', 'name'])->combo_where('active', 1)->combo_tbl(_TBL_GROUPS)->get_combo()->result_combo();
		$this->cbo_posisi=$this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'posisi')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$this->cbo_owner=$this->get_combo_parent_dept();
		$this->data_user = ['id' => 0, 'username' => ''];

		$this->set_Tbl_Master(_TBL_VIEW_OFFICER);

		$this->set_Open_Tab('Data Owner');
		$this->addField(['field' => 'id', 'type' => 'int', 'show' => false, 'size' => 4]);
		if ($this->configuration['show_list_photo'])
			$this->addField(['field' => 'photo', 'input' => 'upload', 'path' => 'file/staft', 'file_thumb' => true]);

		$this->addField(['field' => 'owner_no', 'type' => 'int', 'input' => 'combo', 'values' => $this->cbo_owner, 'search' => true, 'size' => 100]);
		$this->addField(['field' => 'nip', 'search' => true, 'size' => 50, 'required' => true]);
		$this->addField(['field' => 'officer_name', 'search' => true, 'required' => false, 'size' => 50]);
		$this->addField(['field' => 'position_no', 'type' => 'int', 'input' => 'combo', 'values' => $this->cbo_posisi, 'search' => true, 'size' => 50]);
		$this->addField(['field' => 'address', 'input' => 'multitext', 'search' => true, 'size' => 50]);
		$this->addField(['field' => 'phone', 'search' => true, 'required' => false,'size' => 50]);
		$this->addField(['field' => 'mobile', 'search' => true, 'size' => 20]);
		$this->addField(['field' => 'email', 'search' => true, 'size' => 100]);
		$this->addField(['field' => 'username', 'line'=>true, 'line-text'=>'Authentication', 'line-icon'=>'icon-users']);
		$this->addField(['field' => 'password', 'type'=>'free', 'input'=>'pass']);
		$this->addField(['field' => 'passwordc', 'type'=>'free', 'input'=>'pass']);
		$this->addField(['field' => 'user',  'type' => 'free', 'search' => true, 'size' => 50]);
		$this->addField(['field' => 'active', 'type' => 'int', 'input' => 'boolean', 'size' => 50]);
		$this->addField(['field' => 'user_no', 'type' => 'int', 'show' => false]);
		$this->addField(['field' => 'sts_owner', 'type' => 'int', 'input' => 'boolean', 'size' => 50]);
		$this->addField(['field' => 'sts_admin', 'type' => 'int', 'title' => 'Status Admin', 'input' => 'boolean', 'size' => 50]);
		$this->addField(['field' => 'owner_name', 'size' => 20, 'show' => false]);
		$this->addField(['field' => 'posisi', 'size' => 20, 'show' => false]);
		$this->set_Close_Tab();

		$this->set_Field_Primary($this->tbl_master, 'id');
		$this->set_Save_Table(_TBL_OFFICER);

		$this->set_Join_Table(['pk' => $this->tbl_master]);

		$this->set_Sort_Table($this->tbl_master, 'nip');

		if ($this->configuration['show_list_photo'])
			$this->set_Table_List($this->tbl_master, 'photo', 'Photo', 0, 'center', true, true);

		$this->set_Table_List($this->tbl_master, 'owner_name');
		$this->set_Table_List($this->tbl_master, 'nip');
		$this->set_Table_List($this->tbl_master, 'officer_name');
		$this->set_Table_List($this->tbl_master, 'username');
		$this->set_Table_List($this->tbl_master, 'posisi');
		// $this->set_Table_List($this->tbl_master, 'user');
		$this->set_Table_List($this->tbl_master, 'active', '', '', 'center');

		$this->set_Close_Setting();

		$configuration = [
			'show_title_header' => false,
		];
		return [
			'configuration'	=> $configuration
		];
	}

	
	function subDelete_PROCESSOR($id)
	{
		$result = $this->data->delete_data($id['iddel']);
		return $result;
	}

	function listBox_USER($field, $row, $value)
	{
		$group = '';
		$no = 0;
		foreach ($this->data_group as $dg) {
			if ($dg['user_no'] == $row['user_no']) {
				if ($no == 0)
					$group .= $dg['name'];
				else
					$group .= "/" . $dg['name'];
				++$no;
			}
		}
		return $group;
	}

	function insertBox_USER_NAME($field)
	{
		$content = form_input($field['label'], ' ', " size='{$field['size']}' maxlength='{$field['size']}' class='form-control'  id='{$field['label']}'");
		return $content;
	}

	function inputBox_USER($mode, $row, $value, $isi)
	{
		return $this->user_group($row);
	}

	function user_group($param = [])
	{
		$id = 0;
		if ($param)
			$id = $param['user_no'];
		
		$data = $this->data->get_group($id);

		$data['angka'] = "10";
		$data['cbogroup'] = $this->get_combo('groups');
		$result = $this->load->view('groups', $data, true);
		return $result;
	}

	function updateBox_USER_NAME($field, $row, $value)
	{
		$this->data_user = $this->data->cari_data_users($row['id']);
		$users = '';
		$id = 0;
		if ($this->data_user) {
			$users = $this->data_user['username'];
			$id = $this->data_user['id'];
		}
		$content = form_input($field['label'], $users, " size='{$field['size']}' class='form-control'  id='{$field['label']}'");
		$content .= form_hidden(array($field['label'] . '_old' => $users));
		$content .= form_hidden(['id_users' => $id]);
		
		return $content;
	}

	function checkBeforeSave($data, $old_data, $mode='add'){
		$pesan="";
		$no=0;
		$result=false;


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
		
		// if (!isset($data['group'])){
		// 	$this->logdata->set_error("User minimal harus memiliki 1 group yang aktif ");
		// 	++$no;
		// }
		
		if ($no>0){
			return FALSE;
		}else{
			return TRUE;
		}
	}

	function afterSave($id , $new_data, $old_data, $mode){
		$result=true;
		$new_user=false;
		if ($mode=='add' || empty($new_data['user_id'])){
			$this->crud->crud_table(_TBL_USERS);
			$this->crud->crud_type('add');
			$this->crud->crud_field('officer_id', $id, 'int');
			$this->crud->crud_field('username', $new_data['username']);
			$this->crud->crud_field('real_name', $new_data['owner']);
			$this->crud->crud_field('photo', $new_data['photo']);
			$this->crud->crud_field('email', $new_data['email']);
			$this->crud->crud_field('group_no', $new_data['group']);
			$this->crud->crud_field('created_by', $this->ion_auth->get_user_name());
			$this->crud->process_crud();
			$new_user=true;
		}else{
			$this->crud->crud_table(_TBL_USERS);
			$this->crud->crud_type('edit');
			$this->crud->crud_where(['field'=>'officer_id', 'value'=>$id]);
			$this->crud->crud_field('username', $new_data['username']);
			$this->crud->crud_field('real_name', $new_data['owner']);
			$this->crud->crud_field('photo', $new_data['photo']);
			$this->crud->crud_field('group_no', $new_data['group']);
			$this->crud->crud_field('email', $new_data['email']);
			$this->crud->crud_field('updated_by', $this->ion_auth->get_user_name());
			$this->crud->process_crud();
		}
		$users = $this->db->where('officer_id', $id)->get(_TBL_USERS)->row();
		if (!empty($new_data['password']))
			$result = $this->ion_auth->reset_password($new_data['username'], $new_data['password']);
			
		if ($new_user)
			$result=$this->data->save_group($id , $new_data, $users);

		//$this->logdata->set_error("Gagal memproses data karena ");
		return $result;
	}
}
