<?php if( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Level_Approval extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	function init( $action = 'list' )
	{
		$this->kelompok_id = 'level-approval';
		$this->set_Tbl_Master( _TBL_COMBO );

		$this->addField( [ 'field' => 'id', 'type' => 'int', 'show' => FALSE, 'size' => 4 ] );
		$this->addField( [ 'field' => 'kode', 'title' => 'Code', 'required' => TRUE, 'search' => TRUE, 'size' => 15, 'align' => 'center' ] );
		$this->addField( [ 'field' => 'data', 'title' => 'Level Approval', 'required' => TRUE, 'search' => TRUE, 'size' => 100 ] );
		$this->addField( [ 'field' => 'param_string', 'title' => 'Color', 'input' => 'color', 'search' => TRUE, 'size' => 100 ] );
		$this->addField( [ 'field' => 'param_other', 'title' => 'Revision Color', 'input' => 'color', 'search' => TRUE, 'size' => 100 ] );
		$this->addField( [ 'field' => 'kelompok', 'show' => FALSE, 'save' => TRUE, 'default' => $this->kelompok_id ] );
		$this->addField( [ 'field' => 'active', 'input' => 'boolean', 'size' => 20 ] );
		$this->addField( [ 'field' => 'uri_title', 'show' => FALSE, 'save' => TRUE ] );
		$this->addField( [ 'field' => 'created_at', 'save' => FALSE, "show" => FALSE ] );

		$this->set_Field_Primary( $this->tbl_master, 'id' );
		$this->set_Where_Table( [ 'field' => 'kelompok', 'value' => $this->kelompok_id ] );

		$this->set_Sort_Table( $this->tbl_master, 'created_at', "desc" );

		$this->set_Table_List( $this->tbl_master, 'kode' );
		$this->set_Table_List( $this->tbl_master, 'data' );
		// $this->set_Table_List($this->tbl_master,'param_string','',7, 'center');
		$this->set_Table_List( $this->tbl_master, 'param_other', '', 7, 'center' );
		$this->set_Table_List( $this->tbl_master, 'active', '', 7, 'center' );
		$this->set_Table_List( $this->tbl_master, 'created_at', 'Tanggal Dibuat', 10, 'center' );

		$this->set_Close_Setting();

		if( _MODE_ == 'add' )
		{
			$content_title = 'Penambahan Level Approval';
		}
		elseif( _MODE_ == 'edit' )
		{
			$content_title = 'Perubahan Level Approval';
		}
		else
		{
			$content_title = 'Daftar Level Approval';
		}

		$configuration = [
		 'show_title_header' => FALSE,
		 'content_title'     => $content_title,
		];
		return [
		 'configuration' => $configuration,
		];
	}
	function listBox_created_at( $field, $rows, $value )
	{
		return ( ! empty( $value ) && $value != "0000-00-00" ) ? date( "d-m-Y", strtotime( $value ) ) : "";
	}
	function insertValue_URI_TITLE( $value, $rows, $old )
	{
		$title = create_unique_slug( $rows['data'], $this->tbl_master );
		return $title;
	}

	function updateValue_URI_TITLE( $value, $rows, $old )
	{
		$title = $value;
		if( $rows['data'] !== $old['data'] )
			$title = create_unique_slug( $rows['data'], $this->tbl_master );
		return $title;
	}

	function listBox_KODE( $field, $rows, $value )
	{
		$warna = $rows['param_string'];
		if( ! empty( $warna ) )
		{
			$value = '<span class="label" style="background-color:' . $warna . ';color:#ffffff; padding:5px 15px;">' . $value . '</span>';
		}
		return $value;
	}

	function listBox_PARAM_OTHER( $field, $rows, $value )
	{
		$warna = $rows['param_other'];
		if( ! empty( $warna ) )
		{
			$value = '<span class="label" style="background-color:' . $warna . ';color:#ffffff; padding:5px 15px;">' . $value . '</span>';
		}
		return $value;
	}
}
