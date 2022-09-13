<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Change_password extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('ion_auth_model');
	}


	function init($aksi = '')
	{

		$username = $this->_data_user_['username'];
		// $profileWest = $this->crud->getByuser($username);

		$this->set_Open_Tab('Change Password');
		$this->set_Tbl_Master(_TBL_USERS);
		$this->addField(['field' => 'id', 'show' => false]);
		$this->addField(array('field' => 'username', 'required' => true, 'size' => 40));
		// if ($profileWest == "Data not found") {			
		$this->addField(array('field' => 'password', 'input' => 'pass', 'size' => 50));
		$this->addField(array('field' => 'passwordc', 'type' => 'free', 'input' => 'pass', 'label' => 'l_passwordc'));
		// } else {
		// $this->addField(['field' => 'ket_password',  'title' => '', 'type' => 'free', 'search' => false, 'mode' => 'o']);
		// }
		$this->set_Close_Tab();

		$this->set_Field_Primary($this->tbl_master, 'id');
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
		// echo "cek";
		header('location:' . base_url('change-password/edit/' . $this->_data_user_['id']));
	}

	function inputBox_KET_PASSWORD($mode, $field, $rows, $value)
	{
		$content = "<span class='badge'>User telah terdaftar di aplikasi west, harap untuk mengubah password di aplikasi tersebut!</span>";
		return $content;
	}

	function checkBeforeSave($data, $old_data, $mode = 'add')
	{
		if ($data['password'] == '') {
			$this->logdata->set_error("Password tidak boleh kosong!");
			return false;
		}

		if ($data['password'] !== $data['passwordc']) {
			$this->logdata->set_error("Password tidak sama!");
			return false;
		} else {
			return true;
		}
	}

	function afterSave($id, $new_data, $old_data, $mode)
	{
		$result = true;

		$this->crud->crud_table(_TBL_USERS);
		$this->crud->crud_type('edit');
		$this->crud->crud_where(['field' => 'id', 'value' => $id]);
		$this->crud->crud_field('username', $new_data['username']);
		$this->crud->crud_field('password', $this->ion_auth_model->hash_password($new_data['password']));
		$this->crud->process_crud();
		return $result;
	}

	function optionalButton($button, $mode)
	{
		$username = $this->_data_user_['username'];
		// $profileWest = $this->crud->getByuser($username);

		// if ($profileWest != "Data not found") {
		// 	unset($button['save']);
		// }

		unset($button['back']);
		unset($button['save_quit']);
		return $button;
	}
}
