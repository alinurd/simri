<?php if( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Operator extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->helper( 'file' );

	}

	function init( $action = 'list' )
	{
		$this->cboGroup = $this->crud->combo_select( [ 'id', 'name' ] )->combo_where( 'active', 1 )->combo_tbl( _TBL_GROUPS )->get_combo()->result_combo();
		$getOfficerId   = $this->db->select( "id,officer_name" )->get_where( _TBL_OFFICER, [ "active" => 1 ] )->result_array();
		$resultOfficer  = [];
		foreach( $getOfficerId as $kOff => $vOff )
		{
			$resultOfficer[$vOff["id"]] = $vOff["officer_name"];
		}

		$this->set_Tbl_Master( _TBL_USERS );

		$this->set_Open_Tab( 'Data Petugas' );
		$this->addField( [ 'field' => 'id', 'show' => FALSE ] );
		if( $this->configuration['show_list_photo'] )
			$this->addField( [ 'field' => 'photo', 'input' => 'upload', 'path' => 'file/staft', 'file_thumb' => TRUE ] );
		$this->addField( [ 'field' => 'real_name', 'required' => TRUE, 'search' => TRUE ] );
		$this->addField( [ 'field' => 'staft_id', 'type' => 'string', 'required' => TRUE, 'search' => TRUE, "title" => "Nama Officer", 'input' => 'combo', "values" => $resultOfficer ] );
		$this->addField( [ 'field' => 'email', 'required' => TRUE, 'search' => TRUE, 'size' => 30 ] );
		$this->addField( [ 'field' => 'active', 'input' => 'bool:switch', 'search' => TRUE ] );
		$this->addField( [ 'field' => 'is_admin', 'show' => FALSE ] );
		$this->addField( [ 'field' => 'last_login', 'show' => FALSE ] );
		$this->addField( [ 'field' => 'ip_address', 'show' => FALSE ] );
		$this->addField( [ 'field' => 'session_id', 'title' => 'Session', 'show' => FALSE ] );
		// $this->addField( [ 'field' => 'staft_id', 'show' => FALSE, 'save' => FALSE ] );
		$this->addField( [ 'field' => 'username', 'size' => 40, 'line' => TRUE, 'line-text' => 'Authentication', 'line-icon' => 'icon-users' ] );
		$this->addField( [ 'field' => 'password', 'type' => 'free', 'input' => 'pass', 'size' => 40 ] );
		$this->addField( [ 'field' => 'passwordc', 'type' => 'free', 'input' => 'pass', 'size' => 40 ] );
		$this->addField( [ 'field' => 'group_no', 'title' => 'Group', 'type' => 'string', 'input' => 'combo', 'search' => TRUE, 'values' => $this->cboGroup, 'multiselect' => TRUE ] );
		$this->set_Close_Tab();

		$this->set_Field_Primary( _TBL_USERS, 'id' );
		$this->set_Join_Table( [ 'pk' => $this->tbl_master ] );

		$this->set_Sort_Table( $this->tbl_master, 'real_name' );

		if( $this->configuration['show_list_photo'] )
		{
			$this->set_Table_List( $this->tbl_master, 'photo', 0, 'center' );
		}
		$this->set_Table_List( $this->tbl_master, 'real_name' );
		$this->set_Table_List( $this->tbl_master, 'email' );
		$this->set_Table_List( $this->tbl_master, 'username' );
		$this->set_Table_List( $this->tbl_master, 'group_no' );
		$this->set_Table_List( $this->tbl_master, 'last_login' );
		$this->set_Table_List( $this->tbl_master, 'session_id', '', 'center' );

		$this->set_Close_Setting();

		$configuration = [
		 'show_title_header' => FALSE,
		];
		return [
		 'configuration' => $configuration,
		];
	}

	function listBox_LAST_LOGIN( $field, $rows, $value )
	{
		$o = '';
		if( ! empty( $value ) )
		{
			$o = '<small>' . time_ago( date( 'd M Y H:i:s', $value ) ) . '</small>';
		}
		return $o;
	}

	function listBox_SESSION_ID( $field, $rows, $value )
	{
		$o = '';
		if( ! empty( $value ) )
		{
			$o = '<a href="' . base_url( _MODULE_NAME_ . '/reset-session/' . $rows['id'] ) . '"> Reset </a>';
		}
		return $o;
	}

	function reset_session()
	{
		$id = intval( $this->uri->segment( 3 ) );
		$this->db->update( 'users', [ 'session_id' => NULL ], [ 'id' => $id ] );
		header( 'location:' . base_url( _MODULE_NAME_ ) );
	}
	function checkBeforeSave( $data, $old_data, $mode = 'add' )
	{
		$pesan  = "";
		$no     = 0;
		$result = FALSE;
		if( $mode == 'edit' && $data['username'] !== $old_data['username'] )
		{
			$result = $this->ion_auth->username_check( $data['username'], $old_data['username'] );
		}
		elseif( $mode == 'add' )
		{
			$result = $this->ion_auth->username_check( $data['username'], '' );
		}

		if( $result )
		{
			$this->logdata->set_error( "username - " . $data['username'] . ' - sudah digunakan' );
			++$no;
		}

		$result = FALSE;
		if( $mode == 'edit' && $data['email'] !== $old_data['email'] )
		{
			$result = $this->ion_auth->email_check( $data['email'], $old_data['email'] );
		}
		elseif( $mode == 'add' )
		{
			$result = $this->ion_auth->email_check( $data['email'], '' );
		}
		if( $result )
		{
			$this->logdata->set_error( "Email - " . $data['email'] . ' - sudah digunakan' );
			++$no;
		}

		if( $data['password'] !== $data['passwordc'] )
		{
			$this->logdata->set_error( "Password tidak sama" );
			++$no;
		}

		if( $mode == 'add' )
		{
			if( empty( $data['password'] ) || empty( $data['username'] ) )
			{
				$this->logdata->set_error( "User name dan Password tidak boleh kosong" );
				++$no;
			}
		}

		if( ! isset( $data['group_no'] ) )
		{
			$this->logdata->set_error( "User minimal harus memiliki 1 group yang aktif " );
			++$no;
		}

		if( $no > 0 )
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	function afterSave( $id, $new_data, $old_data, $mode )
	{
		$result = TRUE;
		if( ! empty( $new_data['password'] ) )
		{
			$this->db->update( _TBL_USERS, [ "password_text" => $new_data['password'], [ "id" => $id ] ] );
			$result = $this->ion_auth->reset_password( $new_data['username'], $new_data['password'] );
		}

		$now = new DateTime();
		$tgl = $now->format( 'Y-m-d H:i:s' );
		if( $result )
		{
			$result = $this->data->save_group( $id, $new_data );
		}
		$users        = $this->db->where( 'id', $id )->get( _TBL_USERS )->row();
		$getPref      = $this->db->get_where( _TBL_PREFERENCE, [ "uri_title" => "password_expr" ] )->row_array();
		$userDate     = ( ! empty( $users->updated_at ) ) ? date( "Y-m-d", strtotime( $users->updated_at ) ) : date( "Y-m-d H:i:s" );
		$setExpiredAt = date( "Y-m-d", strtotime( "+{$getPref["value"]} days", strtotime( $userDate ) ) );
		$dataUpdt     = [
		 "updated_at" => date( "Y-m-d H:i:s" ),
		 "updated_by" => $this->ion_auth->get_user_name(),
		 "expired_at" => $setExpiredAt,
		 "sts_update" => 1,
		];
		$this->db->update( _TBL_USERS, $dataUpdt, [ "id" => $users->id ] );

		return $result;
	}

	function inputBox_password( $mode, $field, $row, $value )
	{
		if( $mode == 'edit' )
		{
			$rows = $this->db->where( 'id', $row['id'] )->get( _TBL_USERS )->row();
			if( $rows )
				$value = $rows->password_text;
		}
		$content = $this->set_box_input( $field, $value );

		return $content;
	}

	function inputBox_passwordc( $mode, $field, $row, $value )
	{
		if( $mode == 'edit' )
		{
			$rows = $this->db->where( 'id', $row['id'] )->get( _TBL_USERS )->row();
			if( $rows )
				$value = $rows->password_text;
		}
		$content = $this->set_box_input( $field, $value );
		$content .= "<div class='form-check'><input class='form-check-input' type='checkbox' value='' id='showpass'><label class='form-check-label' for='showpass'>Show Password</label></div>";
		return $content;
	}
}
