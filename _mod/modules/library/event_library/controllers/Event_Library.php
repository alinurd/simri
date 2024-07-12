<?php if( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Event_Library extends MY_Controller
{
	var $type_risk = 0;
	var $risk_type = [];
	public function __construct()
	{
		parent::__construct();
	}

	function init( $action = 'list' )
	{
		$this->type_risk     = 2;
		$this->kel           = $this->crud->combo_select( [ 'id', 'data' ] )->combo_where( 'kelompok', 'lib-cat' )->combo_where( 'active', 1 )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();
		$this->cbo_risk_type = $this->crud->combo_select( [ 'id', 'data' ] )->combo_where( 'kelompok', 'risk-type' )->combo_where( 'active', 1 )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();
		$this->cbo_status    = $this->crud->combo_value( [ 1 => 'aktif', 2 => 'tidak aktif' ] )->result_combo();

		$this->set_Tbl_Master( _TBL_VIEW_LIBRARY );

		$this->set_Open_Tab( 'Data Risk Event Library' );
		$this->addField( [ 'field' => 'id', 'type' => 'int', 'show' => FALSE, 'size' => 4 ] );
		$this->addField( [ 'field' => 'kel', 'save' => FALSE, 'input' => 'combo', 'search' => TRUE, 'values' => $this->kel, 'size' => 50, "title" => "Taksonomi BUMN" ] );
		$this->addField( [ 'field' => 'risk_type_no', 'type' => 'int', 'input' => 'combo', 'search' => TRUE, 'values' => [ ' - Pilih - ' ], 'size' => 50, "title" => "Nomor Tipe Risiko" ] );
		// $this->addField(['field'=>'code',  'search'=>true, 'size'=>25]);
		$this->addField( [ 'field' => 'library', 'title' => 'Risk Event', 'input' => 'multitext', 'search' => TRUE, 'size' => 500 ] );
		$this->addField( [ 'field' => 'jml_couse', 'title' => 'Jml Cause', 'type' => 'free', 'show' => FALSE, 'search' => FALSE ] );
		$this->addField( [ 'field' => 'jml_impact', 'type' => 'free', 'show' => FALSE, 'search' => FALSE ] );
		$this->addField( [ 'field' => 'nama_kelompok', 'show' => FALSE ] );
		$this->addField( [ 'field' => 'used', 'type' => 'free', 'show' => FALSE, 'search' => FALSE ] );
		$this->addField( [ 'field' => 'risk_type', 'show' => FALSE ] );
		$this->addField( [ 'field' => 'created_by', 'show' => FALSE ] );
		$this->addField( [ 'field' => 'type', 'type' => 'int', 'default' => $this->type_risk, 'show' => FALSE, 'save' => TRUE ] );
		$this->addField( [ 'field' => 'active', 'type' => 'int', 'input' => 'combo', 'values' => $this->cbo_status, 'default' => 1, 'size' => 40 ] );

		$this->addField( [ 'field' => 'cause', 'title' => 'Penyebab', 'type' => 'free', 'search' => FALSE, 'mode' => 'o' ] );
		$this->addField( [ 'field' => 'impact', 'title' => 'Dampak', 'type' => 'free', 'search' => FALSE, 'mode' => 'o' ] );
		$this->set_Close_Tab();

		$this->set_Field_Primary( $this->tbl_master, 'id' );
		$this->set_Join_Table( [ 'pk' => $this->tbl_master ] );

		$this->set_Sort_Table( $this->tbl_master, 'id' );
		$this->set_Where_Table( [ 'tbl' => $this->tbl_master, 'field' => 'type', 'op' => '=', 'value' => $this->type_risk ] );

		// $this->set_Table_List($this->tbl_master,'nama_kelompok');
		// $this->set_Table_List($this->tbl_master,'risk_type');
		// $this->set_Table_List($this->tbl_master,'code', '', 10, 'center');

		$this->set_Table_List( $this->tbl_master, 'nama_kelompok', 'Taksonomi BUMN' );
		$this->set_Table_List( $this->tbl_master, 'risk_type', 'Tipe Risiko' );

		$this->set_Table_List( $this->tbl_master, 'library' );
		$this->set_Table_List( $this->tbl_master, 'jml_couse', '', 10, 'center' );
		$this->set_Table_List( $this->tbl_master, 'jml_impact', '', 10, 'center' );
		$this->set_Table_List( $this->tbl_master, 'used', '', 10, 'center' );
		$this->set_Table_List( $this->tbl_master, 'created_by' );
		$this->set_Table_List( $this->tbl_master, 'active' );

		$this->set_Close_Setting();
		$this->set_Save_Table( _TBL_LIBRARY );
		$configuration = [
		 'show_title_header' => FALSE,
		];
		return [
		 'configuration' => $configuration,
		];
	}
	function inputBox_CAUSE( $mode, $field, $rows, $value )
	{
		$content = $this->get_cause();
		return $content;
	}

	function get_cause()
	{
		$id               = intval( $this->uri->segment( 3 ) );
		$data             = $this->data->get_library( $id, 1 );
		$data['angka']    = "10";
		$data['cbogroup'] = $this->crud->combo_select( [ 'id', 'library' ] )->combo_where( 'type', 1 )->combo_where( 'active', 1 )->combo_tbl( _TBL_LIBRARY )->get_combo()->result_combo();

		$result = $this->load->view( 'cause', $data, TRUE );
		return $result;
	}

	function inputBox_IMPACT( $mode, $field, $rows, $value )
	{
		$content = $this->get_impact();
		return $content;
	}

	function get_impact()
	{
		$id               = intval( $this->uri->segment( 3 ) );
		$data             = $this->data->get_library( $id, 3 );
		$data['angka']    = "10";
		$data['cbogroup'] = $this->crud->combo_select( [ 'id', 'library' ] )->combo_where( 'type', 3 )->combo_where( 'active', 1 )->combo_tbl( _TBL_LIBRARY )->get_combo()->result_combo();
		$result           = $this->load->view( 'impact', $data, TRUE );
		return $result;
	}
	function get_library()
	{
		$nilKel = $this->input->post( 'kel' );
		$nmTbl  = _TBL_VIEW_LIBRARY;
		$this->db->where( 'type', $nilKel );

		$data['field'] = $this->db->get( $nmTbl )->result_array();
		$kl            = '-';
		if( $nilKel == 1 )
		{
			$kl = 'Cause';
		}
		elseif( $nilKel == 3 )
		{
			$kl = 'Impact';
		}
		$data['kel']      = $kl;
		$data['event_no'] = 0;
		$rok              = $this->db->where( 'active', 1 )->order_by( 'kelompok, type_name' )->get( _TBL_VIEW_RISK_TYPE )->result_array();
		$arrayX           = [ '- Pilih-' ];
		foreach( $rok as $x )
		{
			$kel = "EXTERNAL";
			if( $x['kelompok'] == 77 )
			{
				$kel = "INTERNAL";
			}
			$arrayX[$kel][$x['id']] = $x['type_name'];
		}
		$data['nilKel']         = $nilKel;
		$data['cboTypeLibrary'] = $arrayX;
		$hasil['library']       = $this->load->view( 'list-library', $data, TRUE );
		$hasil['title']         = "List " . $data['kel'];
		header( 'Content-type: application/json' );
		echo json_encode( $hasil );
	}
	function simpan_library()
	{
		$post                = $this->input->post();
		$upd['library']      = $post['library'];
		$upd['risk_type_no'] = $post['jenis_resiko'];
		$upd['type']         = $post['kel'];
		$upd['active']       = 1;
		$upd['created_by']   = $this->ion_auth->get_user_name();

		$this->db->insert( _TBL_LIBRARY, $upd );
		// $this->crud->crud_data(['table' => _TBL_LIBRARY, 'field' => $upd, 'type' => 'add']);
		$id = $this->crud->last_id();

		$data['id']    = $id;
		$data['kel']   = $post['kel'];
		$data['event'] = $post['library'];
		header( 'Content-type: application/json' );
		echo json_encode( $data );
	}
	function MASTER_DATA_LIST( $id, $field )
	{
		if( $id )
			$this->data->cari_total_dipakai( $id );
	}

	function inputBox_RISK_TYPE_NO( $mode, $field, $rows, $value )
	{
		if( $mode == 'edit' )
		{
			$id = 0;
			if( isset( $rows['risk_type_no'] ) )
				$id = $rows['risk_type_no'];
			$rows = $this->db->where( 'id', $id )->get( _TBL_COMBO )->row_array();
			$x    = 0;
			if( $rows )
			{
				$x = $rows['pid'];
			}
			$field['values'] = $this->crud->combo_select( [ 'id', 'data' ] )->combo_where( 'kelompok', 'risk-type' )->combo_where( 'pid', $x )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();

		}
		$content = $this->set_box_input( $field, $value );
		return $content;
	}

	function inputBox_KEL( $mode, $field, $rows, $value )
	{
		if( $mode == 'edit' )
		{
			$id = 0;
			if( isset( $rows['risk_type_no'] ) )
				$id = $rows['risk_type_no'];
			$rows = $this->db->where( 'id', $id )->get( _TBL_VIEW_RISK_TYPE )->row_array();

			if( $rows )
			{
				$value = $rows['pid'];
			}
		}
		$content = $this->set_box_input( $field, $value );
		return $content;
	}

	function listBox_JML_COUSE( $field, $row, $value )
	{
		$rows = $this->db->where( 'library_no', $row['id'] )->where( 'type', 1 )->get( "il_view_library_detail" )->result_array();
		return count( $rows );
	}
	function listBox_JML_IMPACT( $field, $row, $value )
	{
		$rows = $this->db->where( 'library_no', $row['id'] )->where( 'type', 3 )->get( "il_view_library_detail" )->result_array();
		return count( $rows );
	}

	function listBox_USED( $field, $row, $value )
	{
		$result = '';
		$value  = $this->data->get_used( $row['id'] );
		if( $value > 0 )
			$result = '<span class="badge bg-success detail-used pointer" data-id="' . $row['id'] . '" title="klik untuk melihat detail">' . $value . '</span>';
		return $result;
	}

	function optionalPersonalButton( $button, $row )
	{

		$v1 = $this->data->get_used( $row['id'] );

		if( $v1 > 0 )
		{
			unset( $button['delete'] );
		}
		return $button;
	}

	function afterSave( $id, $new_data, $old_data, $mode )
	{
		$result = $this->data->save_library( $id, $new_data );
		return $result;
	}

	// function inputBox_CODEx($mode, $field, $rows, $value){
	// 	$content = form_input($field['label'],$value," size='{$field['size']}' class='form-control'  id='{$field['label']}' readonly='readonly' ");
	// 	return $content;
	// }
}
