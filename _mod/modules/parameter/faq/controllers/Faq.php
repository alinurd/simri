<?php if( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Faq extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	function init( $action = 'list' )
	{

		$this->set_Tbl_Master( _TBL_FAQ );

		$this->addField( [ 'field' => 'id', 'show' => FALSE ] );
		$this->addField( [ 'field' => 'faq', 'required' => TRUE, 'search' => TRUE, "title" => "Question" ] );
		$this->addField( [ 'field' => 'answer', 'required' => TRUE, 'input' => 'multitext', 'search' => TRUE, 'size' => 500, "title" => "Answer" ] );
		$this->addField( [ 'field' => 'order', 'input' => 'updown', 'size' => 20, 'min' => 1, 'default' => 1 ] );
		$this->addField( [ 'field' => 'active', 'required' => TRUE, 'input' => 'boolean', "title" => "Active" ] );
		$this->addField( [ 'field' => 'created_at', 'save' => FALSE, "show" => FALSE ] );

		$this->set_Field_Primary( $this->tbl_master, 'id' );
		$this->set_Sort_Table( $this->tbl_master, 'created_at', "desc" );
		$this->set_Table_List( $this->tbl_master, 'faq' );
		$this->set_Table_List( $this->tbl_master, 'answer' );
		// $this->set_Table_List( $this->tbl_master, 'order' );
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

	function insertValue_URI_TITLE( $value, $rows, $old )
	{
		$title = create_unique_slug( $rows['title'], $this->tbl_master );
		return $title;
	}

	function updateValue_URI_TITLE( $value, $rows, $old )
	{
		$title = $value;
		if( $rows['title'] !== $old['title'] )
			$title = create_unique_slug( $rows['title'], $this->tbl_master );
		return $title;
	}
}
