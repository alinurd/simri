<?php if( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Profile extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper( 'file' );
		$id = $this->uri->segment( 3 );
		if( $id !== $this->_data_user_['id'] )
		{
			header( 'location:' . base_url( 'profile/edit/' . $this->_data_user_['id'] ) );
		}
	}

	function init( $aksi = '' )
	{
		$this->cboGender = $this->crud->combo_value( [ 1 => 'Pria', 2 => 'Wanita' ] )->result_combo();

		$this->set_Open_Coloums( 'Data Profile' );
		$this->set_Tbl_Master( _TBL_VIEW_USERS );
		$this->addField( [ 'field' => 'id', 'show' => FALSE ] );
		$this->addField( [ 'field' => 'username', 'show' => FALSE ] );
		$this->addField( [ 'field' => 'photo', 'input' => 'upload', 'path' => 'file/staft', 'file_thumb' => TRUE ] );
		$this->addField( [ 'field' => 'nip', "readonly" => "readonly", 'search' => TRUE, 'size' => 50 ] );
		$this->addField( [ 'field' => 'real_name', "readonly" => "readonly", 'required' => TRUE, 'search' => TRUE, 'size' => 50, "title" => "Name", "save" => FALSE ] );
		$this->addField( [ 'field' => 'email', 'size' => 50, 'required' => TRUE ] );

		$this->addField( [ 'field' => 'phone', 'size' => 50 ] );
		$this->addField( [ 'field' => 'gender_no', 'type' => 'int', 'input' => 'combo', 'values' => $this->cboGender, 'size' => 50, 'search' => TRUE, "title" => "Gender" ] );

		$this->addField( [ 'field' => 'password', 'type' => 'free', "help" => FALSE, 'input' => 'pass', "save" => FALSE ] );
		$this->addField( [ 'field' => 'passwordc', 'type' => 'free', "help" => FALSE, 'required' => FALSE, 'input' => 'pass', "save" => FALSE ] );
		$this->set_Close_Coloums();

		$this->set_Field_Primary( $this->tbl_master, 'id' );
		$this->set_Join_Table( [ 'pk' => $this->tbl_master ] );

		$this->set_Close_Setting();
		$this->set_Save_Table( _TBL_OFFICER );

		$configuration = [
		 'show_title_header'   => FALSE,
		 'show_header_content' => FALSE,
		//  'box_list_header'     => FALSE,
		 'content_title'       => "Profile Setting",

		];

		return [
		 'configuration' => $configuration,
		];
	}

	function checkBeforeSave( $data, $old_data, $mode )
	{
		$no          = 0;
		$result      = FALSE;
		$result      = FALSE;
		$email_valid = filter_var( $data['email'], FILTER_VALIDATE_EMAIL );

		if( $mode == "edit" )
		{
			if( ! $email_valid )
			{
				$this->logdata->set_error( "Email - " . $data['email'] . " - tidak valid" );
				++$no;
			}
			else
			{
				if( $data['email'] !== $old_data['email'] )
				{
					$result = $this->ion_auth->email_check( $data['email'], $old_data['email'] );
				}

				if( $result )
				{
					$this->logdata->set_error( "Email - " . $data['email'] . " - sudah digunakan" );
					++$no;
				}
			}
			$errors = [];
			if( ! empty( $data['password'] ) )
			{
				if( $data['password'] !== $data['passwordc'] )
				{
					$this->logdata->set_error( "Password tidak sama" );
					++$no;
				}
				else
				{
					checkPassword( $data['password'], $errors );
				}
			}
			if( count( $errors ) > 0 )
			{
				foreach( $errors as $err )
				{
					$this->logdata->set_error( $err );
					++$no;
				}
			}
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
		$this->crud->crud_table( _TBL_USERS );
		$this->crud->crud_type( 'edit' );
		$this->crud->crud_where( [ 'field' => 'id', 'value' => $id ] );
		$this->crud->crud_field( 'real_name', $new_data['real_name'] );
		if( ! empty( $new_data['photo'] ) )
		{
			$this->crud->crud_field( 'photo', $new_data['photo'] );
		}
		$this->crud->crud_field( 'email', $new_data['email'] );
		$this->crud->crud_field( 'updated_by', $this->ion_auth->get_user_name() );
		$this->crud->process_crud();

		$users = $this->db->where( 'id', $id )->get( _TBL_USERS )->row();
		if( ! empty( $new_data['password'] ) )
			$result = $this->ion_auth->reset_password( $new_data['username'], $new_data['password'] );

		if( ! empty( $users->updated_at ) && ! empty( $users->password ) )
		{
			$getPref      = $this->db->get_where( _TBL_PREFERENCE, [ "uri_title" => "password_expr" ] )->row_array();
			$userDate     = date( "Y-m-d", strtotime( $users->updated_at ) );
			$setExpiredAt = date( "Y-m-d", strtotime( "+{$getPref["value"]} days", strtotime( $userDate ) ) );
			$dataUpdt     = [
			 "updated_at" => date( "Y-m-d H:i:s" ),
			 "expired_at" => $setExpiredAt,
			 "sts_update" => 1,
			];
			$this->db->update( _TBL_USERS, $dataUpdt, [ "id" => $users->id ] );
		}
		return $result;
	}

	function optionalButton( $button, $mode )
	{
		unset( $button['back'] );
		unset( $button['save_quit'] );
		return $button;
	}

}
