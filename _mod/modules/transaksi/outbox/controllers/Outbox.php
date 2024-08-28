<?php if( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Outbox extends MY_Controller
{
	protected $type_id = 0;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper( 'file' );
	}

	function init( $action = 'list' )
	{
		$this->cbokel = $this->crud->combo_select( [ 'id', 'data' ] )->combo_where( [ 'kelompok' => 'kel-outbox', 'active' => 1 ] )->combo_tbl( _TBL_COMBO )->combo_sort( 'urut' )->get_combo()->result_combo();

		$this->set_Tbl_Master( _TBL_OUTBOX );

		$this->set_Open_Tab( lang( _MODULE_NAME_REAL_ . '_title' ) );
		$this->addField( [ 'field' => 'id', 'show' => FALSE ] );
		$this->addField( [ 'field' => 'kel_id', 'title' => 'Category', 'type' => 'int', 'input' => 'combo', 'search' => TRUE, 'values' => $this->cbokel ] );
		$this->addField( [ 'field' => 'subject', 'search' => TRUE, 'size' => 100 ] );
		$this->addField( [ 'field' => 'recipient', 'search' => TRUE, 'size' => 100 ] );
		$this->addField( [ 'field' => 'scheduled_at', 'search' => TRUE, 'size' => 100 ] );
		$this->addField( [ 'field' => 'is_sent', 'search' => TRUE, 'size' => 100 ] );
		$this->addField( [ 'field' => 'sent_at', 'size' => 500 ] );
		$this->addField( [ 'field' => 'created_at', 'show' => FALSE ] );
		$this->addField( [ 'field' => 'cc', 'show' => FALSE ] );
		$this->addField( [ 'field' => 'bcc', 'show' => FALSE ] );
		$this->addField( [ 'field' => 'message_text', 'show' => FALSE ] );
		$this->addField( [ 'field' => 'attachment', 'show' => FALSE ] );
		$this->addField( [ 'field' => 'message', 'input' => 'html', 'show' => TRUE ] );
		$this->addField( [ 'field' => 'tried', 'show' => FALSE ] );

		$this->set_Close_Tab();
		$this->set_Field_Primary( $this->tbl_master, 'id' );

		$this->set_Sort_Table( $this->tbl_master, 'id', 'desc' );

		$this->set_Table_List( $this->tbl_master, 'kel_id' );
		$this->set_Table_List( $this->tbl_master, 'subject' );
		$this->set_Table_List( $this->tbl_master, 'recipient' );
		$this->set_Table_List( $this->tbl_master, 'scheduled_at' );
		$this->set_Table_List( $this->tbl_master, 'is_sent', '', 10 );
		$this->set_Table_List( $this->tbl_master, 'sent_at' );

		$this->set_Close_Setting();
		$this->setPrivilege( 'insert', FALSE );
		$this->setPrivilege( 'update', FALSE );

		$configuration = [
		 'content_title' => '<i class="icon-comment-discussion"></i> Support Center',
		 'tab_title'     => 'List Ticket',
		];
		return [
		 'configuration' => $configuration,
		];
	}
}
