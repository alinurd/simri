<?php if( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Email_Template extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	function init( $action = 'list' )
	{

		$this->set_Tbl_Master( _TBL_TEMPLATE_EMAIL );

		$this->addField( [ 'field' => 'id', 'show' => FALSE ] );
		$this->addField( [ 'field' => 'code', 'required' => TRUE, 'readonly' => ( $this->_mode_ == 'edit' ) ? FALSE : FALSE, 'search' => TRUE ] );
		$this->addField( [ 'field' => 'title', 'search' => TRUE ] );
		$this->addField( [ 'field' => 'subject', 'search' => TRUE ] );
		// $this->addField(['field'=>'content_text', 'input'=>'multitext', 'size'=>1000, 'search'=>true]);
		$this->addField( [ 'field' => 'content_html', 'input' => 'html' ] );
		$this->addField( [ 'field' => 'active', 'input' => 'boolean', 'search' => TRUE ] );
		$this->addField( [ 'field' => 'created_at', 'save' => FALSE, "show" => FALSE ] );


		$this->set_Field_Primary( $this->tbl_master, 'id' );

		$this->set_Sort_Table( $this->tbl_master, 'created_at', "desc" );

		$this->set_Table_List( $this->tbl_master, 'code' );
		$this->set_Table_List( $this->tbl_master, 'title' );
		$this->set_Table_List( $this->tbl_master, 'subject' );
		$this->set_Table_List( $this->tbl_master, 'active', '', 10, 'center' );
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
