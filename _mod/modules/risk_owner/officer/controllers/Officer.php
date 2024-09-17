<?php if( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Officer extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->helper( 'file' );
		$this->groups_id = 4;

	}

	function init( $action = 'list' )
	{

		$this->cboTitle  = $this->crud->combo_select( [ 'id', 'data' ] )->combo_where( 'kelompok', 'jabatan' )->combo_where( 'active', 1 )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();
		$this->cboDept   = $this->get_combo_parent_dept();
		$this->cboGroup  = $this->crud->combo_select( [ 'id', 'name' ] )->combo_where( 'active', 1 )->combo_tbl( _TBL_GROUPS )->get_combo()->result_combo();
		$this->cboGender = $this->crud->combo_value( [ 1 => 'Pria', 2 => 'Wanita' ] )->result_combo();

		$this->set_Tbl_Master( _TBL_VIEW_OFFICER );

		$this->set_Open_Coloums();
		$this->addField( [ 'field' => 'id', 'show' => FALSE ] );
		$this->addField( [ 'field' => 'photo', 'input' => 'upload', 'path' => 'file/staft', 'file_thumb' => TRUE, 'file_size' => '5120', 'size_pic' => 132, "required" => TRUE ] );
		$this->addField( [ 'field' => 'nip', 'type' => 'int', 'required' => TRUE, 'search' => TRUE, 'title' => 'NIP [Username]' ] );
		$this->addField( [ 'field' => 'officer_name', 'required' => TRUE, 'search' => TRUE ] );
		$this->addField( [ 'field' => 'owner_no', 'title' => 'Department', 'required' => TRUE, 'type' => 'int', 'input' => 'combo', 'search' => TRUE, 'values' => $this->cboDept ] );
		$this->addField( [ 'field' => 'position_no', 'required' => TRUE, 'title' => 'Title', 'type' => 'int', 'input' => 'combo', 'search' => TRUE, 'values' => $this->cboTitle ] );
		$this->addField( [ 'field' => 'gender_no', 'title' => "Gender", 'type' => 'int', 'input' => 'combo', 'values' => $this->cboGender, 'size' => 100, 'search' => TRUE, 'required' => TRUE ] );
		$this->addField( [ 'field' => 'phone', 'search' => TRUE, 'required' => TRUE ] );
		$this->addField( [ 'field' => 'email', 'type' => 'email', 'required' => TRUE, 'search' => TRUE ] );
		$this->addField( [ 'field' => 'sts_owner', 'input' => 'boolean', 'search' => TRUE ] );
		// $this->addField(['field'=>'sts_approval', 'input'=>'boolean', 'search'=>true]);
		$this->addField( [ 'field' => 'sts_mengetahui', 'title' => 'Status VP', 'input' => 'boolean', 'search' => TRUE, 'default' => 0 ] );
		$this->addField( [ 'field' => 'sts_menyetujui', 'title' => 'Status SVP', 'input' => 'boolean', 'search' => TRUE, 'default' => 0 ] );
		$this->addField( [ 'field' => 'sts_menvalidasi', 'title' => 'Status Admin RM', 'input' => 'boolean', 'search' => TRUE, 'default' => 0 ] );
		$this->addField( [ 'field' => 'active', 'input' => 'boolean', 'search' => TRUE ] );
		$this->addField( [ 'field' => 'user_no', 'show' => FALSE, 'save' => FALSE ] );
		$this->set_Close_Coloums();
		$this->set_Open_Coloums();
		$this->addField( [ 'field' => 'sts_login', 'input' => 'boolean', 'search' => TRUE, 'line' => TRUE, 'line-text' => 'Authentication', 'line-icon' => 'icon-users' ] );
		$this->addField( [ 'field' => 'username', 'save' => FALSE, "title" => "NIP [Username]", "readonly" => TRUE ] );
		$this->addField( [ 'field' => 'password', 'type' => 'free', 'input' => 'pass' ] );
		$this->addField( [ 'field' => 'passwordc', 'type' => 'free', 'required' => FALSE, 'input' => 'pass' ] );
		$this->addField( [ 'field' => 'group', 'title' => 'Group', 'type' => 'free', 'input' => 'combo', 'values' => $this->cboGroup, 'multiselect' => TRUE ] );
		$this->set_Close_Coloums();
		$this->set_Sort_Table( $this->tbl_master, 'created_at', 'desc' );
		$this->set_Field_Primary( $this->tbl_master, 'id' );
		$this->set_Join_Table( [ 'pk' => $this->tbl_master ] );
		$this->set_Save_Table( _TBL_OFFICER );

		$this->set_Sort_Table( $this->tbl_master, 'officer_name' );
		$this->set_Table_List( $this->tbl_master, 'photo', 'Photo', 0, 'center' );
		$this->set_Table_List( $this->tbl_master, 'owner_no' );
		$this->set_Table_List( $this->tbl_master, 'position_no' );
		$this->set_Table_List( $this->tbl_master, 'nip' );
		$this->set_Table_List( $this->tbl_master, 'officer_name' );
		$this->set_Table_List( $this->tbl_master, 'email' );
		// $this->set_Table_List( $this->tbl_master, 'username' );
		$this->set_Table_List( $this->tbl_master, 'sts_owner' );
		// $this->set_Table_List($this->tbl_master,'sts_approval');
		$this->set_Table_List( $this->tbl_master, 'active' );

		$this->set_Close_Setting();

		$configuration = [
		 'show_title_header' => FALSE,
		 'modal_box_search'  => FALSE,
		 'box_list_header'   => FALSE,
		];

		return [
		 'configuration' => $configuration,
		];
	}

	function listBox_photo( $field, $rows, $value )
	{

		if( empty( $value ) || ! file_exists( file_path_relative( $value ) ) )
		{
			$value = img_url( "profile.jpg" );
		}
		else
		{
			$value = file_path_relative( $value );
		}

		$content = '<img width="50" height="50" src="' . $value . '" class="rounded-circle detail-img pointer" data-file="' . $value . '" data-path="file">';
		return $content;

	}
	function inputBox_GROUP( $mode, $field, $row, $value )
	{
		if( $mode == 'edit' )
			$value = $this->data->get_group( $row['id'] );

		$content = $this->set_box_input( $field, $value );
		return $content;
	}

	function inputBox_USER_ID( $mode, $field, $row, $value )
	{
		if( $mode == 'edit' )
		{
			$rows = $this->db->where( 'staft_id', $row['id'] )->get( _TBL_USERS )->row();
			if( $rows )
				$value = $rows->id;
		}
		$content = $this->set_box_input( $field, $value );
		return $content;
	}

	function inputBox_USERNAME( $mode, $field, $row, $value )
	{
		if( $mode == 'edit' )
		{
			$rows = $this->db->where( 'id', $row['id'] )->get( _TBL_OFFICER )->row();
			if( $rows )
				$value = $rows->nip;
		}
		$content = $this->set_box_input( $field, $value );
		return $content;
	}

	function inputBox_password( $mode, $field, $row, $value )
	{
		if( $mode == 'edit' )
		{
			$rows = $this->db->where( 'staft_id', $row['id'] )->get( _TBL_USERS )->row();
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
			$rows = $this->db->where( 'staft_id', $row['id'] )->get( _TBL_USERS )->row();
			if( $rows )
				$value = $rows->password_text;
		}
		$content = $this->set_box_input( $field, $value );
		// var_dump( $content );
		// exit;
		$content .= "<div class='form-check'><input class='form-check-input' type='checkbox' value='' id='showpass'><label class='form-check-label' for='showpass'>Show Password</label></div>";
		return $content;
	}

	function checkBeforeSave( $data, $old_data, $mode = 'add' )
	{

		$pesan       = "";
		$no          = 0;
		$result      = FALSE;
		$result      = FALSE;
		$email_valid = filter_var( $data['email'], FILTER_VALIDATE_EMAIL );

		if( ! $email_valid )
		{
			$this->logdata->set_error( "Email - " . $data['email'] . " - tidak valid" );
			++$no;
		}
		else
		{
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
				$this->logdata->set_error( "Email - " . $data['email'] . " - sudah digunakan" );
				++$no;
			}
		}

		if( $data['sts_login'] == 1 )
		{
			if( $mode == 'edit' && $data['nip'] !== $old_data['nip'] )
			{
				$result = $this->ion_auth->identity_check( $data['nip'], $old_data['nip'] );
			}
			elseif( $mode == 'add' )
			{
				$result = $this->ion_auth->identity_check( $data['nip'], '' );
			}

			if( $result )
			{
				$this->logdata->set_error( "nip - " . $data['nip'] . ' - sudah digunakan' );
				++$no;
			}
			$errors = [];
			if( $data['password'] !== $data['passwordc'] )
			{
				$this->logdata->set_error( "Password tidak sama" );
				++$no;
			}
			else
			{
				checkPassword( $data['password'], $errors );
			}
			if( count( $errors ) > 0 )
			{
				foreach( $errors as $err )
				{
					$this->logdata->set_error( $err );
					++$no;
				}
			}

			if( $mode == 'add' )
			{
				if( empty( $data['password'] ) || empty( $data['nip'] ) )
				{
					$this->logdata->set_error( "User name dan Password tidak boleh kosong" );
					++$no;
				}
			}
			$data['group'] = array_filter( $data['group'], function ($value)
			{
				return $value !== "";
			} );

			if( ! isset( $data['group'] ) || empty( $data['group'] ) )
			{
				$this->logdata->set_error( "User minimal harus memiliki 1 group yang aktif " );
				++$no;
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
		if( $new_data['sts_login'] == 1 )
		{
			if( $mode == 'add' || empty( $new_data['user_no'] ) )
			{
				$this->crud->crud_table( _TBL_USERS );
				$this->crud->crud_type( 'add' );
				$this->crud->crud_field( 'staft_id', $id, 'int' );
				$this->crud->crud_field( 'username', $new_data['nip'] );
				$this->crud->crud_field( 'real_name', $new_data['officer_name'] );
				$this->crud->crud_field( 'password_text', $new_data['password'] );
				$this->crud->crud_field( 'photo', $new_data['photo'] );
				$this->crud->crud_field( 'email', $new_data['email'] );
				$this->crud->crud_field( 'group_no', implode( ',', $new_data['group'] ) );
				$this->crud->crud_field( 'created_by', $this->ion_auth->get_user_name() );
				$this->crud->crud_field( 'updated_at', date( "Y-m-d H:i:s" ) );
				$this->crud->process_crud();
			}
			else
			{
				$this->crud->crud_table( _TBL_USERS );
				$this->crud->crud_type( 'edit' );
				$this->crud->crud_where( [ 'field' => 'staft_id', 'value' => $id ] );
				$this->crud->crud_field( 'username', $new_data['nip'] );
				$this->crud->crud_field( 'real_name', $new_data['officer_name'] );
				$this->crud->crud_field( 'password_text', $new_data['password'] );
				if( empty( $new_data['photo'] ) )
				{
					$this->crud->crud_field( 'photo', ( ! empty( $new_data["photo_tmp"] ) ) ? $new_data['photo_tmp'] : "" );
				}
				else
				{
					$this->crud->crud_field( 'photo', $new_data['photo'] );
				}
				$this->crud->crud_field( 'group_no', implode( ',', $new_data['group'] ) );
				$this->crud->crud_field( 'email', $new_data['email'] );
				$this->crud->crud_field( 'updated_by', $this->ion_auth->get_user_name() );

				$this->crud->process_crud();
			}
			$users = $this->db->where( 'staft_id', $id )->get( _TBL_USERS )->row();

			if( ! empty( $new_data['password'] ) )
				$result = $this->ion_auth->reset_password( $new_data['nip'], $new_data['password'] );
			if( $result )
				$result = $this->data->save_group( $id, $new_data, $users );


			if( ! empty( $users->updated_at ) )
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
		}
		if( $result )
		{
			$this->session->set_flashdata( "message", "" );
		}
		return $result;
	}

	function afterDelete( $id )
	{
		if( empty( $id[0] ) || ! is_numeric( (int) $id[0] ) )
		{
			return FALSE;
		}
		$sql_query = "delete a,ig from il_users a join il_users_groups ig on a.id = ig.user_id where a.staft_id ={$id[0]}";
		return ( ! empty( $id ) ) ? $this->db->query( $sql_query ) : FALSE;
	}
}
