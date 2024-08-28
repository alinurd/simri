<?php if( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Map_Level extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	function init( $action = 'list' )
	{
		$this->cboKel = $this->crud->combo_value( [ 'likelihood' => 'likelihood/Kemungkinan', 'impact' => 'Impact/Dampak' ] )->result_combo();
		$this->set_Tbl_Master( _TBL_LEVEL );

		$this->addField( [ 'field' => 'id', 'type' => 'int', 'show' => FALSE, 'size' => 4 ] );
		$this->addField( [ 'field' => 'category', 'type' => 'string', 'input' => 'combo', 'values' => $this->cboKel, 'size' => 100, 'search' => TRUE ] );
		$this->addField( [ 'field' => 'code', 'title' => 'Code', 'search' => TRUE, 'size' => 10, 'align' => 'center' ] );
		$this->addField( [ 'field' => 'level', 'title' => 'Level', 'required' => TRUE, 'search' => TRUE, 'size' => 100 ] );
		$this->addField( [ 'field' => 'warna', 'input' => 'color', 'size' => 30 ] );
		// $this->addField(['field'=>'score', 'input'=>'updown', 'size'=>30]);
		// $this->addField(['field'=>'bottom_value', 'input'=>'updown', 'size'=>30]);
		// $this->addField(['field'=>'upper_value', 'input'=>'updown', 'size'=>30]);
		// $this->addField(['field'=>'urut', 'input'=>'updown', 'size'=>20, 'min'=>1, 'default'=>1]);
		$this->addField( [ 'field' => 'active', 'input' => 'boolean', 'size' => 20 ] );
		$this->addField( [ 'field' => 'created_at', 'save' => FALSE, "show" => FALSE ] );

		$this->set_Field_Primary( $this->tbl_master, 'id' );

		// $this->set_Sort_Table($this->tbl_master,'category');
		// $this->set_Sort_Table($this->tbl_master,'code');

		$this->set_Sort_Table( $this->tbl_master, 'created_at', "desc" );

		$this->set_Table_List( $this->tbl_master, 'category' );
		$this->set_Table_List( $this->tbl_master, 'code' );
		$this->set_Table_List( $this->tbl_master, 'level' );
		$this->set_Table_List( $this->tbl_master, 'warna' );
		// $this->set_Table_List($this->tbl_master,'score');
		// $this->set_Table_List($this->tbl_master,'bottom_value');
		// $this->set_Table_List($this->tbl_master,'upper_value');
		// $this->set_Table_List($this->tbl_master,'urut');
		$this->set_Table_List( $this->tbl_master, 'active', '', 7, 'center' );
		$this->set_Table_List( $this->tbl_master, 'created_at', 'Tanggal Dibuat', 10, 'center' );

		$this->set_Close_Setting();

		if( _MODE_ == 'add' )
		{
			$content_title = 'Penambahan Level Likelihood/Impact';
		}
		elseif( _MODE_ == 'edit' )
		{
			$content_title = 'Perubahan Level Likelihood/Impact';
		}
		else
		{
			$content_title = 'Daftar Level Likelihood/Impact';
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
	function listBox_WARNA( $fields, $rows, $value )
	{
		$o = '<span style="background-color:' . $value . ';color:#ffffff;padding:4px 10px;">' . $value . '</span>';

		return $o;
	}
}
