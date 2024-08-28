<?php if( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class News extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->helper( 'file' );
	}

	function init( $action = 'list' )
	{
		$this->cboParent = $this->crud->combo_select( [ 'id', 'data' ] )->combo_where( 'kelompok', 'category-news' )->combo_where( 'active', 1 )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();
		$this->set_Tbl_Master( _TBL_NEWS );

		$this->addField( [ 'field' => 'id', 'show' => FALSE ] );
		$this->addField( [ 'field' => 'cover_image', 'input' => 'upload', 'path' => 'file/news', 'file_thumb' => FALSE ] );
		$this->addField( [ 'field' => 'category_id', 'type' => 'int', 'input' => 'combo', 'search' => TRUE, 'values' => $this->cboParent ] );
		$this->addField( [ 'field' => 'title', 'required' => TRUE, 'search' => TRUE ] );
		$this->addField( [ 'field' => 'content', 'input' => 'html' ] );
		$this->addField( [ 'field' => 'uri_title', 'save' => TRUE, 'show' => FALSE ] );
		$this->addField( [ 'field' => 'sticky', 'input' => 'bool:switch', 'default' => 0, 'search' => TRUE ] );
		$this->addField( [ 'field' => 'files', 'title' => 'File', 'input' => 'upload', 'path' => 'file/download', 'file_thumb' => FALSE, 'file_type' => 'pdf|pdfx|doc|docx', 'file_size' => $this->_preference_['upload_size'] ] );
		$this->addField( [ 'field' => 'active', 'input' => 'bool:switch', 'search' => TRUE ] );
		$this->addField( [ 'field' => 'created_at', 'save' => FALSE, "show" => FALSE ] );

		$this->set_Field_Primary( $this->tbl_master, 'id' );

		$this->set_Sort_Table( $this->tbl_master, 'created_at', "desc" );

		$this->set_Table_List( $this->tbl_master, 'category_id' );
		$this->set_Table_List( $this->tbl_master, 'title' );
		$this->set_Table_List( $this->tbl_master, 'sticky' );
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
