<?php if( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Map_Mapping extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	function init( $action = 'list' )
	{
		$this->cboKel    = $this->crud->combo_value( [ 'likelihood' => 'likelihood/Kemungkinan', 'impact' => 'Impact/Dampak' ] )->result_combo();
		$this->cboType   = $this->crud->combo_select( [ 'id', 'level_color' ] )->combo_where( 'active', 1 )->combo_tbl( _TBL_LEVEL_COLOR )->get_combo()->result_combo();
		$this->cboLike   = $this->crud->combo_select( [ 'id', 'concat(code,\' - \',level) as x' ] )->combo_where( 'active', 1 )->combo_where( 'category', 'likelihood' )->combo_tbl( _TBL_LEVEL )->get_combo()->result_combo();
		$this->cboImpact = $this->crud->combo_select( [ 'id', 'concat(code,\' - \',level) as x' ] )->combo_where( 'active', 1 )->combo_where( 'category', 'impact' )->combo_tbl( _TBL_LEVEL )->get_combo()->result_combo();
		$this->cboTreat  = $this->crud->combo_select( [ 'id', 'treatment' ] )->combo_where( 'active', 1 )->combo_tbl( _TBL_TREATMENT )->get_combo()->result_combo();

		$this->set_Tbl_Master( _TBL_VIEW_LEVEL_MAPPING );

		$this->addField( [ 'field' => 'id', 'type' => 'int', 'show' => FALSE, 'size' => 4 ] );
		$this->addField( [ 'field' => 'level_risk_no', 'input' => 'combo', 'values' => $this->cboType, 'size' => 100, 'search' => TRUE ] );
		$this->addField( [ 'field' => 'impact', 'input' => 'combo', 'values' => $this->cboImpact, 'size' => 100, 'search' => TRUE ] );
		$this->addField( [ 'field' => 'likelihood', 'input' => 'combo', 'values' => $this->cboLike, 'size' => 100, 'search' => TRUE ] );
		$this->addField( [ 'field' => 'score', 'input' => 'updown', 'size' => 30 ] );
		$this->addField( [ 'field' => 'pgn', 'input' => 'updown', 'size' => 30 ] );
		$this->addField( [ 'field' => 'treatment_no', 'input' => 'combo', 'values' => $this->cboTreat, 'size' => 100, 'search' => TRUE ] );
		$this->addField( [ 'field' => 'urut', 'input' => 'updown', 'size' => 20, 'min' => 1, 'default' => 1 ] );
		$this->addField( [ 'field' => 'color', 'show' => FALSE, 'save' => FALSE ] );
		$this->addField( [ 'field' => 'color_text', 'show' => FALSE, 'save' => FALSE ] );
		$this->addField( [ 'field' => 'level_color', 'show' => FALSE, 'save' => FALSE ] );
		$this->addField( [ 'field' => 'like_code', 'show' => FALSE, 'save' => FALSE ] );
		$this->addField( [ 'field' => 'impact_code', 'show' => FALSE, 'save' => FALSE ] );

		$this->set_Field_Primary( $this->tbl_master, 'id' );

		$this->set_Sort_Table( $this->tbl_master, 'like_code', 'desc' );
		$this->set_Sort_Table( $this->tbl_master, 'impact_code' );
		// $this->set_Sort_Table($this->tbl_master,'likelihood');

		// $this->set_Table_List($this->tbl_master,'level_risk_no');
		$this->set_Table_List( $this->tbl_master, 'likelihood' );
		$this->set_Table_List( $this->tbl_master, 'impact' );
		$this->set_Table_List( $this->tbl_master, 'color' );
		$this->set_Table_List( $this->tbl_master, 'score' );
		$this->set_Table_List( $this->tbl_master, 'urut' );
		$this->set_Table_List( $this->tbl_master, 'pgn' );
		$this->set_Save_Table( _TBL_LEVEL_MAPPING );
		$this->set_Close_Setting();

		if( _MODE_ == 'add' )
		{
			$content_title = 'Penambahan Map Mapping';
		}
		elseif( _MODE_ == 'edit' )
		{
			$content_title = 'Perubahan Map Mapping';
		}
		else
		{
			$content_title = 'Daftar Map Mapping';
		}

		$configuration = [
		 'show_title_header' => FALSE,
		 'content_title'     => $content_title,
		];
		return [
		 'configuration' => $configuration,
		];
	}

	function listBox_COLOR( $fields, $rows, $value )
	{
		$o = '<span style="background-color:' . $value . ';color:' . $rows['color_text'] . ';padding:4px 10px;">' . $rows['level_color'] . '</span>';

		return $o;
	}
}
