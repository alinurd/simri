<?php if( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Kpimap extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	function init( $action = 'list' )
	{
		$this->kelompok_id = 'kpi';
		$this->set_Tbl_Master( _TBL_COMBO );

		$this->addField( [ 'field' => 'id', 'type' => 'int', 'show' => FALSE, 'size' => 4 ] );
		$this->addField( [ 'field' => 'data', 'title' => 'Nama KPI', 'required' => TRUE, 'search' => TRUE, 'size' => 100, 'save' => FALSE, 'disabled' => TRUE ] );
		$this->addField( [ 'field' => 'kelompok', 'show' => FALSE, 'save' => TRUE, 'default' => $this->kelompok_id ] );
		$this->addField( [ 'field' => 'param_text', 'show' => FALSE, 'save' => FALSE ] );
		$this->addField( [ 'field' => 'urut', 'input' => 'updown', 'size' => 20, 'min' => 1, 'default' => 1, 'save' => FALSE, 'disabled' => TRUE, "show" => FALSE ] );
		$this->addField( [ 'field' => 'active', 'input' => 'boolean', 'size' => 20, 'save' => FALSE, 'disabled' => TRUE ] );
		$this->addField( [ 'field' => 'risk_type', 'title' => 'List KRI', 'type' => 'free', 'mode' => 'a' ] );
		$this->addField( [ 'field' => 'uri_title', 'show' => FALSE, 'save' => TRUE ] );
		$this->addField( [ 'field' => 'created_at', 'save' => FALSE, "show" => FALSE ] );

		$this->set_Field_Primary( $this->tbl_master, 'id' );
		$this->set_Where_Table( [ 'field' => 'kelompok', 'value' => $this->kelompok_id ] );

		$this->set_Sort_Table( $this->tbl_master, 'created_at', "desc" );

		$this->set_Table_List( $this->tbl_master, 'data' );
		$this->set_Table_List( $this->tbl_master, 'param_text', 'Total Dept' );
		// $this->set_Table_List( $this->tbl_master, 'urut' );
		$this->set_Table_List( $this->tbl_master, 'active', '', 7, 'center' );
		$this->set_Table_List( $this->tbl_master, 'created_at', 'Tanggal Dibuat', 10, 'center' );

		$this->set_Close_Setting();

		if( _MODE_ == 'add' )
		{
			$content_title = 'Mapping KPI/KRI';
		}
		elseif( _MODE_ == 'edit' )
		{
			$content_title = 'Perubahan Mapping KPI/KRI';
		}
		else
		{
			$content_title = 'Daftar Mapping KPI/KRI';
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

	function inputBox_RISK_TYPE( $mode, $field, $row, $value )
	{

		if( $mode == 'add' )
		{
			$rows = [];
		}
		else
		{
			$this->db->select( 'id, data' );
			$this->db->where( 'active', 1 );
			$this->db->where( 'kelompok', 'kri' );
			$this->db->where( 'param_other_int', $row['id'] );
			$rowx = $this->db->get( _TBL_COMBO )->result_array();
			$rows = [];
			if( count( $rowx ) > 0 )
			{
				foreach( $rowx as $key => $value )
				{
					$rows[$value['id']] = $value['data'];
				}
			}
		}
		$owner = $this->crud->combo_select( [ 'id', 'data' ] )->combo_where( 'active', 1 )->combo_where( 'kelompok', 'kri' )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();

		$content = $this->load->view( 'term', [ 'data' => $rows, 'owner' => $owner ], TRUE );
		return $content;
	}

	function afterSave( $id, $new_data, $old_data, $mode )
	{
		$result = $this->data->save_detail( $id, $new_data, $old_data, $mode );
		return $result;
	}

	function listBox_PARAM_TEXT( $field, $rows, $value )
	{
		$val = ( ! empty( $value ) ) ? json_decode( $value, TRUE ) : "";
		$jml = ( is_array( $val ) ) ? count( $val ) : 0;

		return $jml;
	}
}
