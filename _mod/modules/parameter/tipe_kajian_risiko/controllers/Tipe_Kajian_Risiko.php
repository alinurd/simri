<?php if( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Tipe_Kajian_Risiko extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	function init( $action = 'list' )
	{

		$this->set_Tbl_Master( _TBL_KAJIAN_RISIKO_TIPE );

		$this->addField( [ 'field' => 'id', 'type' => 'int', 'show' => FALSE, 'size' => 4 ] );
		$this->addField( [ 'field' => 'tipe', 'title' => 'Tipe Kajian Risiko', 'type' => 'string', 'required' => TRUE, 'input' => 'text', 'search' => TRUE ] );
		$this->addField( [ 'field' => 'active', 'required' => TRUE, 'input' => 'boolean', "title" => "Active" ] );
		$this->addField( [ 'field' => 'created_at', "show" => FALSE ] );

		$this->set_Field_Primary( $this->tbl_master, 'id' );
		$this->set_Where_Table( [ 'field' => 'active', 'value' => 1 ] );

		$this->set_Sort_Table( $this->tbl_master, 'created_at', "desc" );

		$this->set_Table_List( $this->tbl_master, 'tipe', '', "", '' );
		$this->set_Table_List( $this->tbl_master, 'active', '', 7, 'center' );
		$this->set_Table_List( $this->tbl_master, 'created_at', 'Tanggal Dibuat', 10, 'center' );

		$this->set_Close_Setting();

		$configuration = [
		 'show_title_header' => FALSE,
		];
		return [
		 'configuration' => $configuration,
		];
	}
	function listBox_created_at( $field, $rows, $value )
	{
		return ( ! empty( $value ) && $value != "0000-00-00" ) ? date( "d-m-Y", strtotime( $value ) ) : "";
	}
}
