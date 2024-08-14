<?php if( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Risk_Context extends MY_Controller
{
	var $super_user = 0;
	public function __construct()
	{
		parent::__construct();

		// if (array_key_exists('group',$this->_data_user_)){
		// 	if (array_key_exists('param',$this->_data_user_['group'])){
		// 		if (array_key_exists('super_user',$this->_data_user_['group']['param'])){
		// 			$this->super_user = $this->_data_user_['group']['param']['super_user'];
		// 		}
		// 	}
		// }
		$this->super_user = $this->_data_user_['is_admin'];
	}

	function init( $action = 'list' )
	{

		$this->type_ass_no = $this->crud->combo_select( [ 'id', 'data' ] )->combo_where( 'kelompok', 'ass-type' )->combo_where( 'active', 1 )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();
		$this->period      = $this->crud->combo_select( [ 'id', 'data' ] )->combo_where( 'kelompok', 'period' )->combo_where( 'active', 1 )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();
		$this->alat        = $this->crud->combo_select( [ 'id', 'data' ] )->combo_where( 'kelompok', 'metode-alat' )->combo_where( 'active', 1 )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();
		$this->term        = $this->crud->combo_select( [ 'id', 'data' ] )->combo_where( 'kelompok', 'term' )->combo_where( 'active', 1 )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();


		$this->stakeholder      = $this->crud->combo_select( [ 'id', 'officer_name' ] )->combo_where( 'active', 1 )->combo_tbl( _TBL_VIEW_OFFICER )->get_combo()->result_combo();
		$this->cboDept          = $this->get_combo_parent_dept();
		$this->cboStack         = $this->get_combo_parent_dept( FALSE );
		$resultDivisionDropdown = $this->data->getDataDropdownDivision( $this->uri->segment( 3 ) );


		$this->set_Tbl_Master( _TBL_VIEW_RCSA );
		$this->set_Open_Tab( 'Data RCSA' );
		$this->addField( [ 'field' => 'id', 'type' => 'int', 'show' => FALSE, 'size' => 4 ] );
		$this->addField( [ 'field' => 'type_ass_id', 'input' => 'combo', 'required' => TRUE, 'search' => TRUE, 'values' => $this->type_ass_no, 'size' => 50 ] );
		$this->addField( [ 'field' => 'owner_id', 'title' => 'Department', 'type' => 'int', 'required' => TRUE, 'input' => 'combo', 'search' => TRUE, 'values' => $this->cboDept ] );
		// $this->addField( [ 'field' => 'division', 'input' => 'text', 'title' => 'Division' ] );

		$this->addField( [ 'field' => 'seksi', 'type' => 'int', 'input' => 'combo', 'title' => 'Seksi', 'search' => FALSE, 'values' => $resultDivisionDropdown ] );
		$this->addField( [ 'field' => 'judul_ass', 'title' => 'Judul Assement', 'input' => 'text' ] );
		$this->addField( [ 'field' => 'sasaran_dept', 'input' => 'multitext', 'search' => TRUE, 'size' => 500 ] );
		$this->addField( [ 'field' => 'ruang_lingkup', 'input' => 'multitext', 'search' => TRUE, 'size' => 500 ] );
		$this->addField( [ 'field' => 'stakeholder_id', 'title' => 'Stakeholder', 'type' => 'string', 'input' => 'combo', 'search' => FALSE, 'values' => $this->cboStack, 'multiselect' => TRUE ] );
		$this->addField( [ 'field' => 'alat_metode_id', 'title' => 'Alat & Metode', 'type' => 'string', 'input' => 'combo', 'multiselect' => TRUE, 'search' => FALSE, 'values' => $this->alat ] );
		$this->addField( [ 'field' => 'period_id', 'title' => 'Period', 'type' => 'int', 'required' => TRUE, 'input' => 'combo', 'search' => TRUE, 'values' => $this->period ] );
		$this->addField( [ 'field' => 'term_id', 'title' => 'Term', 'type' => 'int', 'required' => TRUE, 'input' => 'combo', 'search' => TRUE, 'values' => [] ] );
		$this->addField( [ 'field' => 'minggu_id', 'title' => 'Bulan', 'type' => 'int', 'required' => TRUE, 'input' => 'combo', 'search' => TRUE, 'values' => [] ] );

		// $this->addField(['field'=>'active', 'input'=>'boolean', 'size'=>20]);
		$this->addField( [ 'field' => 'term', 'show' => FALSE ] );
		$this->addField( [ 'field' => 'kode_dept', 'show' => FALSE ] );
		$this->addField( [ 'field' => 'owner_name', 'show' => FALSE ] );
		$this->addField( [ 'field' => 'status_id', 'show' => FALSE ] );
		$this->addField( [ 'field' => 'status_final', 'show' => FALSE ] );
		$this->addField( [ 'field' => 'status_revisi', 'show' => FALSE ] );
		$this->addField( [ 'field' => 'tgl_propose', 'type' => 'date', 'input' => 'date', 'show' => FALSE ] );
		$this->addField( [ 'field' => 'register', 'type' => 'free', 'show' => FALSE ] );
		$this->addField( [ 'field' => 'created_at', 'show' => FALSE ] );
		$this->set_Close_Tab();
		$this->set_Open_Tab( 'Log Approval' );
		$this->addField( [ 'field' => 'log_approval', 'type' => 'free', 'mode' => 'a' ] );
		$this->addField( [ 'field' => 'log_approval_mitigasi', 'type' => 'free', 'mode' => 'a' ] );
		$this->addField( [ 'field' => 'log_approval_mitigasi_per_minggu', 'type' => 'free', 'mode' => 'a' ] );
		$this->set_Close_Tab();

		$this->set_Field_Primary( $this->tbl_master, 'id', TRUE );

		$this->set_Sort_Table( $this->tbl_master, 'created_at', 'desc' );
		$this->set_Table_List( $this->tbl_master, 'id', '<input type="checkbox" class="form-check-input pointer" name="chk_list_parent" id="chk_list_parent"  style="padding:0;margin:0;">', '0%', 'left', 'no-sort' );
		$this->set_Table_List( $this->tbl_master, 'owner_name', _l( 'fld_owner_id' ) );
		$this->set_Table_List( $this->tbl_master, 'kode_dept', '' );
		$this->set_Table_List( $this->tbl_master, 'stakeholder_id', '', 15 );
		$this->set_Table_List( $this->tbl_master, 'type_ass_id' );
		$this->set_Table_List( $this->tbl_master, 'period_id' );
		$this->set_Table_List( $this->tbl_master, 'term_id', 'Periode' );
		$this->set_Table_List( $this->tbl_master, 'status_id' );
		$this->set_Table_List( $this->tbl_master, 'tgl_propose' );
		$this->set_Table_List( $this->tbl_master, 'register', '', 7, 'center' );
		$this->set_Table_List( $this->tbl_master, 'created_at', 'Disusun' );
		// $this->set_Table_List($this->tbl_master,'active','',7, 'center');

		$this->_set_Where_Owner();
		$this->set_Save_Table( _TBL_RCSA );

		$this->set_Close_Setting();
		if( _MODE_ == 'add' )
		{
			$content_title = 'Penambahan Konteks Risiko';
		}
		elseif( _MODE_ == 'edit' )
		{
			$content_title = 'Perubahan Konteks Risiko';
		}
		elseif( _MODE_ == 'identifikasi-risiko' )
		{
			$content_title = 'Asesmen Risiko';
		}
		else
		{
			$content_title = 'Daftar Konteks Risiko';
		}

		$configuration = [
		 'show_title_header' => FALSE,
		 'content_title'     => $content_title,
		];
		return [
		 'configuration' => $configuration,
		];
	}

	// public function __insert()
	// {

	// return 	$this->configuration['content_title']='Daftar Konteks cek';
	// 	// return $this->__insert();

	// }



	public function MASTER_DATA_LIST( $arrId, $rows )
	{

		$this->asse_tipe = [];
		$this->assesment = [];
		$arr_approval    = $this->db->order_by( 'pid' )->get( _TBL_VIEW_APPROVAL )->result_array();
		$approval        = [];
		foreach( $arr_approval as $row )
		{
			$approval[$row['pid']][$row['urut']] = $row;
		}

		$arr_assesment   = $this->db->select( 'id, data, pid' )->where( 'kelompok', 'ass-type' )->get( _TBL_COMBO )->result_array();
		$this->assesment = [];
		foreach( $arr_assesment as $row )
		{
			$this->assesment[$row['id']] = $row;
			if( array_key_exists( intval( $row['pid'] ), $approval ) )
			{
				$this->assesment[$row['id']]['detail'] = $approval[intval( $row['pid'] )];
			}
			$this->asse_tipe[$row['id']] = $row;
		}

		$rows             = $this->db->select( 'rcsa_id, count(id) as jml' )->group_by( 'rcsa_id' )->get( _TBL_VIEW_RCSA_DETAIL )->result_array();
		$this->has_detail = [];
		foreach( $rows as $row )
		{
			$this->has_detail[$row['rcsa_id']] = $row['jml'];
		}
	}
	function listBox_TERM_ID( $field, $rows, $value )
	{
		$cbominggu = $this->data->get_data_minggu( $value );
		$minggu    = ( $rows['minggu_id'] ) ? $cbominggu[$rows['minggu_id']] : '';
		$a         = ( isset( $this->term[$value] ) ) ? $this->term[$value] . ' - ' . $minggu : '-';
		return $a;
	}
	function listBox_id( $field, $rows, $value )
	{
		$check = $this->data->checklist();
		// $select = (in_array($rows['id'], $check))?'checked':'';
		$a = '<div class="text-center"  style="padding:0px 20px 20px 0px;"><input type="checkbox" class="form-check-input pointer text-center" name="chk_list[]" style="padding:0;margin:0;" value="' . $rows['id'] . '"/></div>';
		return $a;
	}

	function listBox_STATUS_ID( $field, $rows, $value )
	{
		$ass    = intval( $rows['type_ass_id'] );
		$revisi = intval( $rows['status_revisi'] );
		$urut   = intval( $rows['status_id'] );
		$final  = intval( $rows['status_final'] );
		// dumps($this->asse_tipe);
		// dumps($this->assesment);
		$revisi_text = '';
		if( array_key_exists( $ass, $this->assesment ) )
		{
			if( array_key_exists( 'detail', $this->assesment[$ass] ) )
			{
				if( array_key_exists( $revisi, $this->assesment[$ass]['detail'] ) )
				{
					$revisi_text = '<div class="label text-center" style="background-color:' . $this->assesment[$ass]['detail'][$revisi]['warna_revisi'] . ';color:#ffffff;width:100%;padding:10px 5px; display:block;">' . _l( 'msg_notif_revisi' ) . '<br/>' . $this->assesment[$ass]['detail'][$revisi]['model'] . '</div><br/>';
				}
			}
		}

		$value = intval( $value );
		$hasil = 'unknow-' . $value;
		if( $final )
		{
			$hasil = '<div class="label text-center" style="background-color:' . $this->_preference_['warna_approved'] . ';color:#ffffff;width:100%;padding:10px 5px; display:block;"> ' . _l( 'msg_notif_approved' ) . '</div>';
		}
		elseif( array_key_exists( $ass, $this->assesment ) )
		{
			if( array_key_exists( 'detail', $this->assesment[$ass] ) )
			{
				if( array_key_exists( $urut, $this->assesment[$ass]['detail'] ) )
				{
					$ket = ' - ';
					if( ! empty( $this->assesment[$ass]['detail'][$urut]['model'] ) )
					{
						$ket = $this->assesment[$ass]['detail'][$urut]['model'];
					}
					$hasil = '<div class="label text-center" style="background-color:' . $this->assesment[$ass]['detail'][$urut]['warna'] . ';color:#ffffff;width:100%;padding:10px 5px; display:block;">' . _l( 'msg_notif_need_approved' ) . '<br/>' . $ket . '</div>';
				}
			}
		}

		if( $value == 0 )
		{
			$hasil = '<a href="' . base_url( $this->modul_name . '/propose-risiko/' . $rows['id'] ) . '" class="propose btn  pointer" style="width:100% !important;padding:5px;background-color:' . $this->_preference_['warna_propose'] . ';color:#ffffff;" data-id="' . $rows['id'] . '"> ' . _l( 'msg_notif_propose' ) . ' </a>';
		}
		return $revisi_text . $hasil;
	}

	function listBox_REGISTER( $field, $rows, $value )
	{
		$o = '<i class="icon-menu6 pointer text-primary risk-register" title=" View Risk Register " data-id="' . $rows['id'] . '"></i>';

		return $o;
	}

	function inputBox_LOG_APPROVAL( $mode, $field, $rows, $value )
	{
		$o = '';
		if( $mode == 'edit' )
		{
			$o = '<table class="table table-bordered table-striped">';
			$o .= '<thead><tr class=" bg-info-300">';
			$o .= '<th>Tanggal</th><th>Petugas</th><th>Penerima</th><th>Judul</th><th>Catatan</th></thead>';
			$o .= '<tbody>';

			$histori = $this->db->where( 'rcsa_id', $rows['id'] )->where( 'tipe_log', 1 )->order_by( 'tanggal' )->get( _TBL_VIEW_LOG_APPROVAL )->result_array();
			foreach( $histori as $key => $row )
			{
				$o .= '<tr><td>' . $row['tanggal'] . '</td><td>' . $row['pengirim'] . '</td><td>' . $row['penerima'] . '</td><td>' . $row['keterangan'] . '</td><td>' . $row['note'] . '</td></tr>';
			}
			$o .= '</tbody>';
			$o .= '</table>';
		}
		return $o;
	}

	function inputBox_LOG_APPROVAL_MITIGASI( $mode, $field, $rows, $value )
	{
		$o = '';
		if( $mode == 'edit' )
		{
			$o = '<table class="table table-bordered table-striped">';
			$o .= '<thead><tr class=" bg-primary-300">';
			$o .= '<th>Tanggal</th><th>Petugas</th><th>Penerima</th><th>Judul</th><th>Catatan</th></thead>';
			$o .= '<tbody>';

			$histori = $this->db->where( 'rcsa_id', $rows['id'] )->where( 'tipe_log', 2 )->order_by( 'tanggal' )->get( _TBL_VIEW_LOG_APPROVAL )->result_array();
			foreach( $histori as $key => $row )
			{
				$o .= '<tr><td>' . $row['tanggal'] . '</td><td>' . $row['pengirim'] . '</td><td>' . $row['penerima'] . '</td><td>' . $row['keterangan'] . '</td><td>' . $row['note'] . '</td></tr>';
			}
			$o .= '</tbody>';
			$o .= '</table>';
		}
		return $o;
	}

	function inputBox_LOG_APPROVAL_MITIGASI_PER_MINGGU( $mode, $field, $rows, $value )
	{
		$o = '';
		if( $mode == 'edit' )
		{
			$o = '<table class="table table-bordered table-striped">';
			$o .= '<thead><tr class=" bg-success-300">';
			$o .= '<th>Tanggal</th><th>Periode</th><th>Minggu</th><th>Petugas</th></thead>';
			$o .= '<tbody>';

			$histori = $this->db->where( 'rcsa_id', $rows['id'] )->order_by( 'tgl_propose' )->get( _TBL_VIEW_RCSA_APPROVAL_MITIGASI )->result_array();
			foreach( $histori as $key => $row )
			{
				$o .= '<tr><td>' . $row['tgl_propose'] . '</td><td>Periode ' . $row['period'] . ' - ' . $row['term'] . '</td><td>Minggu ke - ' . $row['minggu'] . '</td><td>' . $row['real_name'] . '</td></tr>';
			}
			$o .= '</tbody>';
			$o .= '</table>';
		}
		return $o;
	}

	function inputBox_TERM_ID( $mode, $field, $rows, $value )
	{
		$id = 0;
		if( isset( $rows['period_id'] ) )
		{
			$id              = $rows['period_id'];
			$field['values'] = $this->crud->combo_select( [ 'id', 'data' ] )->combo_where( 'kelompok', 'term' )->combo_where( 'pid', $id )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();
		}

		$content = $this->set_box_input( $field, $value );
		return $content;
	}

	function inputBox_MINGGU_ID( $mode, $field, $rows, $value )
	{
		$id = 0;
		if( isset( $rows['term_id'] ) )
			$id = $rows['term_id'];

		$field['values'] = $this->data->get_data_minggu( $id );
		// if ($mode == 'edit') {

		// $field['values'] = $this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'minggu')->combo_where('pid', $id)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		// }
		// dumps($field['values']);
		// die();
		$content = $this->set_box_input( $field, $value );
		return $content;
	}

	function identifikasi_risiko()
	{
		if( $this->input->is_ajax_request() )
		{
			$id = intval( $this->input->post( 'id' ) );
		}
		else
		{
			$id = intval( $this->uri->segment( 3 ) );
		}
		$data['parent'] = $this->db->where( 'id', $id )->get( _TBL_VIEW_RCSA )->row_array();

		$cbominggu = $this->data->get_data_minggu( $data['parent']['term_id'] );
		$minggu    = ( $data['parent']['minggu_id'] ) ? $cbominggu[$data['parent']['minggu_id']] : '';

		$data['parent']['bulan'] = ( isset( $this->term[$data['parent']['term_id']] ) ) ? $this->term[$data['parent']['term_id']] . ' - ' . $minggu : '-';
		$data['info_parent']     = $this->load->view( 'info-parent', $data, TRUE );
		$rows                    = $this->db->select( 'rcsa_detail_id as id, count(rcsa_detail_id) as jml' )->group_by( [ 'rcsa_detail_id' ] )->where( 'rcsa_id', $id )->get( _TBL_VIEW_RCSA_MITIGASI )->result_array();
		$miti                    = [];
		foreach( $rows as $row )
		{
			$miti[$row['id']] = $row['jml'];
		}

		$rows = $this->db->where( 'rcsa_id', $id )->order_by( 'kode_dept' )->order_by( 'kode_aktifitas' )->get( _TBL_VIEW_RCSA_DETAIL )->result_array();
		foreach( $rows as &$row )
		{
			if( array_key_exists( $row['id'], $miti ) )
			{
				$row['jml'] = $miti[$row['id']];
			}
		}
		unset( $row );
		$data['detail'] = $rows;

		$hasil         = $this->load->view( 'identifikasi', $data, TRUE );
		$configuration = [
		 'show_title_header'  => FALSE,
		 'show_action_button' => FALSE,
		];

		if( $this->input->is_ajax_request() )
		{
			header( 'Content-type: application/json' );
			echo json_encode( [ 'combo' => $hasil ] );
		}
		else
		{
			$this->default_display( [ 'content' => $hasil, 'configuration' => $configuration ] );
		}
	}

	function add_identifikasi()
	{

		$id = intval( $this->input->post( 'id' ) );
		$this->db->delete( _TBL_RCSA_DET_LIKE_INDI, [ 'rcsa_detail_id' => 0, 'created_by' => $this->ion_auth->get_user_name() ] );
		$this->db->delete( _TBL_RCSA_DET_DAMPAK_INDI, [ 'rcsa_detail_id' => 0, 'created_by' => $this->ion_auth->get_user_name() ] );

		$data['parent']          = $this->db->where( 'id', $id )->get( _TBL_VIEW_RCSA )->row_array();
		$data['rcsa_detail']     = [ 'sts_save_evaluasi' => 0 ];
		$data['mode']            = 0; //'Mode : Insert data';
		$data['mode_text']       = _l( 'fld_mode_add' ); //'Mode : Insert data';
		$cbominggu               = $this->data->get_data_minggu( $data['parent']['term_id'] );
		$minggu                  = ( ! empty( $cbominggu[$data['parent']['minggu_id']] ) ) ? $cbominggu[$data['parent']['minggu_id']] : '';
		$data['parent']['bulan'] = ! empty( $this->term[$data['parent']['term_id']] ) ? $this->term[$data['parent']['term_id']] . ' - ' . $minggu : "";
		$data['info_parent']     = $this->load->view( 'info-parent', $data, TRUE );
		$data['detail']          = $this->identifikasi_content( [], $data['parent'] );


		$data['identifikasi'] = $this->load->view( 'identifikasi-risiko', $data, TRUE );
		$data['analisa']      = $this->load->view( 'analisa-risiko', $data, TRUE );
		$data['target']       = '';
		$data['hidden']       = [ 'rcsa_id' => $id, 'rcsa_detail_id' => 0 ];
		$hasil['combo']       = $this->load->view( 'update-identifikasi', $data, TRUE );
		header( 'Content-type: application/json' );
		echo json_encode( $hasil );
	}

	function edit_identifikasi( $id = 0, $edit = 0 )
	{

		$mode = 'save';
		if( empty( $id ) )
		{
			$mode = 'edit';
			$id   = intval( $this->input->post( 'id' ) );
		}
		if( empty( $edit ) )
		{
			$edit = intval( $this->input->post( 'edit' ) );
		}
		$this->db->delete( _TBL_RCSA_DET_LIKE_INDI, [ 'rcsa_detail_id' => 0, 'created_by' => $this->ion_auth->get_user_name() ] );
		$this->db->delete( _TBL_RCSA_DET_DAMPAK_INDI, [ 'rcsa_detail_id' => 0, 'created_by' => $this->ion_auth->get_user_name() ] );

		$data['parent'] = $this->db->where( 'id', $id )->get( _TBL_VIEW_RCSA )->row_array();

		//get like ineheren
		$rcsa_detail = $this->db->where( 'id', $edit )->get( _TBL_VIEW_RCSA_DETAIL )->row_array();


		$data['mode']      = 1; //'Mode : Update data';
		$data['mode_text'] = _l( 'fld_mode_edit' ); //'Mode : Update data';
		$cbominggu         = $this->data->get_data_minggu( $data['parent']['term_id'] );
		$minggu            = ( $data['parent']['minggu_id'] ) ? $cbominggu[$data['parent']['minggu_id']] : '';

		$data['parent']['bulan'] = ( isset( $this->term[$data['parent']['term_id']] ) ) ? $this->term[$data['parent']['term_id']] . ' - ' . $minggu : '-';
		$data['info_parent']     = $this->load->view( 'info-parent', $data, TRUE );
		$data['detail']          = $this->identifikasi_content( $rcsa_detail, $data['parent'] );

		$data['identifikasi'] = $this->load->view( 'identifikasi-risiko', $data, TRUE );
		$data['analisa']      = $this->load->view( 'analisa-risiko', $data, TRUE );

		$data['rcsa_detail'] = $rcsa_detail;

		$rows = $this->db->select( 'rcsa_mitigasi_id as id, count(rcsa_mitigasi_id) as jml' )->group_by( [ 'rcsa_mitigasi_id' ] )->where( 'rcsa_detail_id', $edit )->get( _TBL_VIEW_RCSA_MITIGASI_DETAIL )->result_array();
		$miti = [];
		foreach( $rows as $row )
		{
			$miti[$row['id']] = $row['jml'];
		}
		$rows = $this->db->where( 'rcsa_detail_id', $edit )->get( _TBL_VIEW_RCSA_MITIGASI )->result_array();
		foreach( $rows as &$row )
		{
			if( array_key_exists( $row['id'], $miti ) )
			{
				$row['jml'] = $miti[$row['id']];
			}
		}
		unset( $row );
		$rows = $this->convert_owner->set_data( $rows )->set_param( [ 'penanggung_jawab' => 'penanggung_jawab_id', 'koordinator' => 'koordinator_id' ] )->draw();

		$data['picku'] = $this->get_data_dept();

		$data['mitigasi'] = $rows;

		$data['d_evaluasi']    = $this->evaluasi_content( $rcsa_detail );
		$data['d_target']      = $this->target_content( $rcsa_detail );
		$data['list_mitigasi'] = $this->load->view( 'list-mitigasi', $data, TRUE );
		$data['evaluasi']      = $this->load->view( 'evaluasi-risiko', $data, TRUE );
		$data['target']        = $this->load->view( 'target-risiko', $data, TRUE );
		$data['hidden']        = [ 'rcsa_id' => $id, 'rcsa_detail_id' => $edit ];
		$hasil['combo']        = $this->load->view( 'update-identifikasi', $data, TRUE );
		if( $mode == 'save' )
		{
			return $hasil;
		}
		else
		{
			header( 'Content-type: application/json' );
			echo json_encode( $hasil );
		}
	}

	function identifikasi_content( $data = [], $parent = [] )
	{
		$mode    = 'add';
		$id_edit = 0;
		if( $data )
		{
			$mode    = 'edit';
			$id_edit = $data['id'];
		}
		$jml_like_indi = $this->db->where( 'bk_tipe', 1 )->where( 'rcsa_detail_id', intval( $id_edit ) )->or_group_start()->where( 'rcsa_detail_id', 0 )->where( 'created_by', $this->ion_auth->get_user_name() )->group_end()->get( _TBL_VIEW_RCSA_DET_LIKE_INDI )->num_rows();

		$jml_dampak_indi = $this->db->where( 'bk_tipe', 1 )->where( 'rcsa_detail_id', intval( $id_edit ) )->or_group_start()->where( 'rcsa_detail_id', 0 )->where( 'created_by', $this->ion_auth->get_user_name() )->group_end()->get( _TBL_VIEW_RCSA_DET_DAMPAK_INDI )->num_rows();

		$kpi = $this->crud->combo_select( [ 'id', 'data' ] )->combo_where( 'kelompok', 'kpi' )->combo_where( 'param_text like ', '%' . $parent['owner_id'] . '%' )->combo_where( 'active', 1 )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();


		$aktivitas = $this->crud->combo_select( [ 'id', 'concat(kode,\' - \',data) as data' ] )->combo_where( 'pid', intval( $parent['owner_id'] ) )->combo_where( 'kelompok', 'aktivitas' )->combo_where( 'active', 1 )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();

		$sasaran = $this->crud->combo_select( [ 'id', 'data' ] )->combo_where( 'pid', intval( $parent['owner_id'] ) )->combo_where( 'kelompok', 'sasaran-aktivitas' )->combo_where( 'active', 1 )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();

		$tahapan = $this->crud->combo_select( [ 'id', 'data' ] )->combo_where( 'kelompok', 'tahapan-proses' )->combo_where( 'active', 1 )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();

		$kel = $this->crud->combo_select( [ 'id', 'data' ] )->combo_where( 'kelompok', 'lib-cat' )->combo_where( 'active', 1 )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();

		$risk_type = [ _l( 'cbo_select' ) ];

		if( isset( $data['klasifikasi_risiko_id'] ) )
		{
			$risk_type = $this->crud->combo_select( [ 'id', 'data' ] )->combo_where( 'pid', $data['klasifikasi_risiko_id'] )->combo_where( 'kelompok', 'risk-type' )->combo_where( 'active', 1 )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();

		}

		$penyebab_id = $this->crud->combo_select( [ 'id', 'library' ] )->combo_where( 'type', 1 )->combo_where( 'active', 1 )->combo_tbl( _TBL_LIBRARY )->get_combo()->result_combo();


		$peristiwa_id = $this->crud->combo_select( [ 'id', 'library' ] )->combo_where( 'type', 2 )->combo_where( 'active', 1 )->combo_tbl( _TBL_VIEW_LIBRARY_DETAIL )->get_combo()->result_combo();

		$dampak_id = $this->crud->combo_select( [ 'id', 'library' ] )->combo_where( 'type', 3 )->combo_where( 'active', 1 )->combo_tbl( _TBL_LIBRARY )->get_combo()->result_combo();


		$option = '';
		foreach( $penyebab_id as $key => $row )
		{
			$option .= '<option value="' . $key . '">' . $row . '</option>';
		}
		$param['peristiwa_cbo'] = $option;

		$option = '';
		foreach( $dampak_id as $key => $row )
		{
			$option .= '<option value="' . $key . '">' . $row . '</option>';
		}
		$param['dampak_cbo'] = $option;

		$aspek     = 0;
		$aspek_det = "";
		if( $data )
		{
			if( $data['tipe_analisa_no'] == 2 || $data['tipe_analisa_no'] == 3 )
			{
				$aspek = $data['aspek_risiko_id'];
			}
			else
			{
				$aspek = 0;
			}
			$aspek_det = $data['aspek_det'];
		}

		if( $aspek )
		{
			$like = $this->crud->combo_select( [ 'urut', 'concat(urut,\' - \',data) as x' ] )->combo_where( 'active', 1 )->combo_where( 'pid', $aspek )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();

			$like_semi = $this->crud->combo_select( [ 'urut', 'concat(urut,\' - \',data) as x' ] )->combo_where( 'active', 1 )->combo_where( 'pid', $aspek )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();
		}
		else
		{
			$like = $this->crud->combo_select( [ 'id', 'concat(code,\' - \',level) as x' ] )->combo_where( 'active', 1 )->combo_where( 'category', 'likelihood' )->combo_tbl( _TBL_LEVEL )->get_combo()->result_combo();

			$like_semi = [];
		}


		$impact = $this->crud->combo_select( [ 'id', 'concat(code,\' - \',level) as x' ] )->combo_where( 'active', 1 )->combo_where( 'category', 'impact' )->combo_tbl( _TBL_LEVEL )->get_combo()->result_combo();

		$cboControl = $this->crud->combo_select( [ 'id', 'data' ] )->noSelect()->combo_where( 'active', 1 )->combo_where( 'kelompok', 'existing-control' )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();

		$aspek_risiko = $this->crud->combo_select( [ 'id', 'data' ] )->combo_where( 'active', 1 )->combo_where( 'kelompok', 'aspek-risiko' )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();

		$arrControl      = [];
		$jml             = intval( count( $cboControl ) / 2 );
		$kontrol         = '';
		$kontrolCheckbox = '';
		$i               = 1;
		$control         = [];
		if( $data )
		{
			$control = explode( '###', $data['nama_kontrol'] );
		}
		$kontrol .= '<div class="well p100">';

		$kontrol .= '</div>' . form_textarea( "note_control", ( $data ) ? $data['nama_kontrol_note'] : '', ' class="summernote-risk-evaluate" style="width:100%;" maxlength="999"' ) . '<br/>';


		foreach( $cboControl as $row )
		{
			if( $i == 1 )


				$sts = FALSE;
			foreach( $control as $ctrl )
			{
				if( $row == $ctrl )
				{
					$sts = TRUE;
					break;
				}
			}

			$kontrolCheckbox .= '<label class="pointer">' . form_checkbox( 'check_item[]', $row, $sts );
			$kontrolCheckbox .= '&nbsp;' . $row . '</label><br/>';


			++$i;
		}

		$efek_control = [ 0 => _l( 'cbo_select' ), 1 => 'L', 2 => 'D', 3 => 'L & D', 4 => 'Tidak ada kontrol' ];
		$fraud        = [ 0 => _l( 'cbo_select' ), 1 => 'Ya', 2 => 'Tidak' ];


		$penyebab = '<table class="table table-borderless" id="tblpenyebab"><tbody>';
		if( $data )
		{

			$pi = explode( ',', $data['penyebab_id'] );

			foreach( $pi as $key => $x )
			{
				$icon = '<i class="icon-plus-circle2 text-primary-400 add-penyebab"></i>&nbsp;&nbsp;<i class="icon-file-empty text-success-400 add-text-penyebab d-none" data-id="0"></i>';
				if( $key > 0 )
				{
					$icon = '<i class="icon-database-remove text-danger-400 del-penyebab"></i>';
				}

				$getPenyebabVal = $this->db->select( "id,library" )->get_where( 'il_library', [ "id" => $x ] )->row_array();
				$penyebab .= '<tr><td style="padding-left:0px;"><input type="hidden" name="penyebab_id[]" value="' . $x . '" class="penyebab_id"/>' . form_input( 'penyebab_id_text[]', ( ! empty( $getPenyebabVal["library"] ) ) ? $getPenyebabVal["library"] : "", 'class="form-control getLibrary" readonly id="penyebab_id_text" identity="' . $key . '" placeholder="' . _l( 'fld_penyebab_risiko' ) . '" ' ) . '</td><td class="text-right pointer" width="10%" style="padding-right:0px;">' . $icon . '</td></tr>';
			}
		}
		else
		{
			$penyebab .= '<tr><td style="padding-left:0px;"><input type="hidden" name="penyebab_id[]" value="" class="penyebab_id"/>' . form_input( 'penyebab_id_text[]', '', 'class="form-control getLibrary" readonly identity="' . $key . '" id="penyebab_id_text" placeholder="' . _l( 'fld_penyebab_risiko' ) . '"' ) . '</td><td class="text-right pointer" width="10%" style="padding-right:0px;"><i class="icon-plus-circle2 text-primary-400 add-penyebab"></i></td></tr>';
		}
		$penyebab .= '</tbody></table>';

		$dampak   = '<table class="table table-borderless" id="tbldampak"><tbody>';
		$csslevel = '';
		if( $data )
		{
			$pi = explode( ',', $data['dampak_id'] );
			foreach( $pi as $key => $x )
			{
				$icon = '<i class="icon-plus-circle2 text-primary-400 add-dampak"></i>&nbsp;&nbsp;<i class="icon-file-empty text-success-400 add-text-dampak d-none" data-id="0"></i>';
				if( $key > 0 )
				{
					$icon = '<i class="icon-database-remove text-danger-400 del-dampak"></i>';
				}
				$getDampakVal = $this->db->select( "id,library" )->get_where( 'il_library', [ "id" => $x ] )->row_array();
				$dampak .= '<tr><td style="padding-left:0px;"><input type="hidden" name="dampak_id[]" value=' . $x . ' class="dampak_id"/>' . form_input( 'dampak_id_text[]', ( ! empty( $getDampakVal["library"] ) ) ? $getDampakVal["library"] : "" ?? "", 'class="form-control getLibrary" readonly id="dampak_id_text" identity="' . $key . '" placeholder="' . _l( 'fld_dampak_risiko' ) . '"' ) . '</td><td class="text-right pointer" width="10%" style="padding-right:0px;">' . $icon . '</td></tr>';
			}
			$csslevel = 'background-color:' . $data['color'] . ';color:' . $data['color_text'] . ';';
		}
		else
		{
			$dampak .= '<tr><td style="padding-left:0px;"><input type="hidden" name="dampak_id[]" value="" class="dampak_id"/>' . form_input( 'dampak_id_text[]', '', 'class="form-control getLibrary" identity="0" id="dampak_id_text" readonly placeholder="' . _l( 'fld_dampak_risiko' ) . '"' ) . '</td><td class="text-right pointer" width="10%" style="padding-right:0px;"><i class="icon-plus-circle2 text-primary-400 add-dampak"></i></td></tr>';
		}
		$dampak .= '</tbody></table>';
		$lib    = FALSE;
		if( $data )
		{
			$lib = $this->db->where( 'id', $data['peristiwa_id'] )->get( _TBL_VIEW_LIBRARY )->row_array();
		}

		$peristiwa = '<table class="table table-borderless" id="tblperistiwa"><tbody><tr id="getPeristiwa"><td style="padding-left:0px;">' . form_input( 'peristiwa_id_text', ( $lib ) ? $lib['library'] : '', 'class="form-control getPeristiwa" id="peristiwa_id_text" readonly="readonly" placeholder="' . _l( 'fld_peristiwa_risiko' ) . '"' ) . form_hidden( 'peristiwa_id', ( $lib ) ? $data['peristiwa_id'] : '', 'id="peristiwa_id"' ) .
		 '</td>
    </tr>
</tbody></table>';

		$tasktonomi = '<table class="table table-borderless" id="tblperistiwa"><tbody>
    <tr>
        <td style="padding-left:0px;">'
		 . form_input( 'tasktonomiName', ( $lib ) ? $lib['nama_kelompok'] : '', 'class="form-control getPeristiwa" id="tasktonomiName" readonly="readonly" placeholder="' . _l( 'help_ket_peristiwa' ) . '"' )
		 . form_hidden( 'klasifikasi_risiko_id', ( $lib ) ? $data['klasifikasi_risiko_id'] : '', 'id="klasifikasi_risiko_id"' ) .
		 '</td>
    </tr>
</tbody></table>';

		$tipeRisiko = '<table class="table table-borderless" id="tblperistiwa"><tbody>
    <tr>
        <td style="padding-left:0px;">'
		 . form_input( 'tipeName', ( $lib ) ? $lib['risk_type'] : '', 'class="form-control getPeristiwa" id="tipeName" readonly="readonly" placeholder="' . _l( 'help_ket_peristiwa' ) . '"' )
		 . form_hidden( 'tipe_risiko_id', ( $lib ) ? $data['tipe_risiko_id'] : '', 'id="tipe_risiko_id"' ) .
		 '</td>
    </tr>
</tbody></table>';

		$tAdd                         = '<div class="form-control-feedback form-control-feedback-lg"><i class="icon-make-group"></i></div>';
		$param['identifikasi']['kpi'] = [ 'title' => "KPI", 'help' => "", 'add' => FALSE, 'mandatori' => FALSE, 'isi' => form_dropdown( 'id_kpi', $kpi, ( $data ) ? $data['id_kpi'] : '', 'id="id_kpi" class="form-control select" style="width:100%;"' ) ];

		$param['identifikasi']['aktifitas_id'] = [ 'title' => _l( 'fld_aktifitas' ), 'help' => _h( 'help_aktifitas' ), 'add' => FALSE, 'mandatori' => TRUE, 'isi' => form_dropdown( 'aktifitas_id', $aktivitas, ( $data ) ? $data['aktifitas_id'] : '', 'id="aktifitas_id" class="form-control select" style="width:100%;"' ) ];
		$param['identifikasi']['sasaran_id']   = [ 'title' => _l( 'fld_sasaran_aktifitas' ), 'help' => _h( 'help_sasaran_aktifitas' ), 'mandatori' => TRUE, 'add' => FALSE, 'isi' => form_dropdown( 'sasaran_id', $sasaran, ( $data ) ? $data['sasaran_id'] : '', 'id="sasaran_id" class="form-control select" style="width:100%;"' ) ];

		$param['identifikasi']['tahapan'] = [ 'title' => _l( 'fld_tahapan_proses' ), 'help' => _h( 'help_tahapan_proses' ), 'mandatori' => TRUE, 'isi' => form_textarea( 'tahapan', ( $data ) ? $data['tahapan'] : '', " id='tahapan' maxlength='500' size='500' class='form-control' style='overflow: hidden; width: 100% !important; height: 200px;' onblur='_maxLength(this , \"id_sisa_2\")' onkeyup='_maxLength(this , \"id_sisa_2\")' data-role='tagsinput'", TRUE, [ 'size' => 500, 'isi' => 0, 'no' => 2 ] ) ];


		$param['identifikasi']['peristiwa_id']          = [ 'title' => _l( 'fld_peristiwa_risiko' ), 'help' => _h( 'help_peristiwa_risiko' ), 'mandatori' => TRUE, 'isi' => $peristiwa ];
		$param['identifikasi']['klasifikasi_risiko_id'] = [ 'title' => _l( 'fld_klasifikasi_risiko' ), 'help' => _h( 'help_klasifikasi_risiko' ), 'mandatori' => FALSE, 'isi' => $tasktonomi ];
		$param['identifikasi']['tipe_risiko_id']        = [ 'title' => _l( 'fld_tipe_risiko' ), 'help' => _h( 'help_tipe_risiko' ), 'mandatori' => FALSE, 'isi' => $tipeRisiko ];


		//new
		$param['identifikasi']['fraud_risk'] = [ 'title' => _l( 'Fraud Risk' ), 'help' => _h( 'help_fraud_risk' ), 'mandatori' => TRUE, 'isi' => form_dropdown( 'fraud_risk', $fraud, ( $data ) ? $data['fraud_risk'] : '', 'id="fraud_risk" class="form-control select" style="width:100%;"' ) ];
		$param['identifikasi']['smap']       = [ 'title' => _l( 'SMAP' ), 'help' => _h( 'help_smap' ), 'mandatori' => TRUE, 'isi' => form_dropdown( 'smap', $fraud, ( $data ) ? $data['smap'] : '', 'id="smap" class="form-control select" style="width:100%;"' ) ];
		$param['identifikasi']['esg_risk']   = [ 'title' => _l( 'ESG Risk' ), 'help' => _h( 'help_esg_risk' ), 'mandatori' => TRUE, 'isi' => form_dropdown( 'esg_risk', $fraud, ( $data ) ? $data['esg_risk'] : '', 'id="esg_risk" class="form-control select" style="width:100%;"' ) ];

		$param['identifikasi']['penyebab_id'] = [ 'title' => _l( 'fld_penyebab_risiko' ), 'help' => _h( 'help_penyebab_risiko' ), 'mandatori' => TRUE, 'isi' => $penyebab ];
		$param['identifikasi']['dampak_id']   = [ 'title' => _l( 'fld_dampak_risiko' ), 'help' => _h( 'help_dampak_risiko' ), 'mandatori' => TRUE, 'isi' => $dampak ];

		$param['identifikasi']['risiko_dept'] = [ 'title' => _l( 'fld_risiko_dept' ), 'help' => _h( 'help_risiko_dept' ), 'mandatori' => TRUE, 'isi' => form_textarea( 'risiko_dept', ( $data ) ? $data['risiko_dept'] : '', " id='risiko_dept' maxlength='500' size='500' class='form-control' style='overflow: hidden; width: 100% !important; height: 200px;' onblur='_maxLength(this , \"id_sisa_1\")' onkeyup='_maxLength(this , \"id_sisa_1\")' data-role='tagsinput'", TRUE, [ 'size' => 500, 'isi' => 0, 'no' => 1 ] ) ];

		$tipe_analisa    = "<br/>&nbsp;";
		$check1          = FALSE;
		$check2          = TRUE;
		$check3          = FALSE;
		$tipe_analisa_no = 2;
		if( $data )
		{
			$data['tipe_analisa_no'] = ( $data['tipe_analisa_no'] == 1 ) ? 2 : $data['tipe_analisa_no'];
			if( $data['tipe_analisa_no'] == 2 )
			{
				$check1 = FALSE;
				$check2 = TRUE;
				$check3 = FALSE;
			}
			elseif( $data['tipe_analisa_no'] == 3 )
			{
				$check1 = FALSE;
				$check2 = FALSE;
				$check3 = TRUE;
			}
			$tipe_analisa_no = $data['tipe_analisa_no'];
		}
		$tipe_analisa .= '<div class="form-check form-check-inline d-none"><label class="form-check-label">';
		$tipe_analisa .= form_radio( 'tipe_analisa_no', 1, $check1, 'id="tipe_analisa_no_1"  class="form-check-primary" ' );
		$tipe_analisa .= form_label( '&nbsp;&nbsp;&nbsp; Kualitatif &nbsp;&nbsp;', 'tipe_analisa_no_1', [ 'class' => 'pointer ' ] );
		$tipe_analisa .= '</label></div>';
		$tipe_analisa .= '<div class="form-check form-check-inline"><label class="form-check-label">';
		$tipe_analisa .= form_radio( 'tipe_analisa_no', 2, $check2, 'id="tipe_analisa_no_2"  class="form-check-primary" ' );
		$tipe_analisa .= form_label( '&nbsp;&nbsp;&nbsp; Kuantitatif &nbsp;&nbsp;', 'tipe_analisa_no_2', [ 'class' => 'pointer' ] );
		$tipe_analisa .= '</label></div>';
		$tipe_analisa .= '<div class="form-check form-check-inline"><label class="form-check-label">';
		$tipe_analisa .= form_radio( 'tipe_analisa_no', 3, $check3, 'id="tipe_analisa_no_3"  class="form-check-primary" ' );
		$tipe_analisa .= form_label( '&nbsp;&nbsp;&nbsp; Kualitatif &nbsp;&nbsp;', 'tipe_analisa_no_3', [ 'class' => 'pointer' ] );
		$tipe_analisa .= '</label></div><br/>&nbsp<br/>&nbsp;';

		$param['tipe_analisa_no'] = $tipe_analisa_no;
		$param['tipe_analisa']    = [ 'title' => '', 'help' => '', 'isi' => $tipe_analisa ];



		//analisa kuantitatif **********/

		$param['analisa_kuantitatif'][] = [ 'title' => '', 'help' => '', 'isi' => '<div style="display: flex; justify-content: space-between;">
		<span class="btn btn-primary legitRipple pointer" data-rcsa="' . $parent['id'] . '" data-id="' . $id_edit . '" id="indikator_like" data-jml_like_indi="' . $jml_like_indi . '" style="width:100%;"> Input Risk Indikator Likelihood [ ' . $jml_like_indi . ' ] </span>
		 <span class="icon-database-refresh pointer" id="refreshRiskLikeHood" title="Click untuk refresh data" style="width: 5%;"></span></div> ' .
		 form_hidden( [ 'indikator_like_cek' => ( $jml_like_indi ) ? $jml_like_indi : '' ], 'id="indikator_like_cek"' ) ];

		$param['analisa_kuantitatif'][] = [ 'title' => '', 'help' => '', 'isi' => '<div style="display: flex; justify-content: space-between;">
		<span class="btn btn-primary legitRipple pointer"  data-rcsa="' . $parent['id'] . '" data-id="' . $id_edit . '" id="indikator_dampak" data-jml_dampak_indi="' . $jml_dampak_indi . '" style="width:100%;"> Input Risk Indikator Dampak  [ ' . $jml_dampak_indi . ' ] </span>		 
		<span class="icon-database-refresh pointer" id="refreshRiskDampak" title="Click untuk refresh data" style="width: 5%;">
		</span></div> ' ];


		/**inherent impact */
		$param['analisa_kuantitatif'][] = [ 'title' => _l( 'fld_likelihood' ), 'help' => _h( 'help_likelihood' ), 'mandatori' => TRUE, 'isi' => form_input( 'like_text_kuantitatif', ( $data ) ? $data['like_inherent'] : '', 'id="like_text_kuantitatif" class="form-control" readonly="readonly" style="width:100%;"' ) . form_hidden( [ 'like_text' => ( $data ) ? $data['like_text'] : '' ] ) . form_hidden( [ 'like_id' => ( $data ) ? $data['like_id'] : '' ] ) . form_hidden( [ 'like_id_2' => ( $data ) ? $data['like_id'] : '' ] ) ];

		$param['analisa_kuantitatif'][] = [ 'title' => _l( 'fld_impact' ), 'help' => _h( 'help_impact' ), 'mandatori' => TRUE, 'isi' => form_input( 'impact_text_kuantitatif', ( $data ) ? $data['impact_inherent'] : '', 'id="impact_text_kuantitatif" class="form-control" readonly="readonly" style="width:100%;"' ) . form_hidden( [ 'impact_text' => ( $data ) ? $data['impact_text'] : '' ] ) . form_hidden( [ 'impact_id' => ( $data ) ? $data['impact_id'] : '' ] ) . form_hidden( [ 'impact_id_2' => ( $data ) ? $data['impact_id'] : '' ] ) ];
		/**========== */


		$param['analisa_semi'][] = [ 'title' => _l( 'fld_indi_likelihood' ), 'help' => _h( 'help_likelihood' ), 'mandatori' => TRUE, 'isi' => form_dropdown( 'aspek_risiko_id', $aspek_risiko, $aspek, 'id="aspek_risiko_id" class="form-control select" style="width:100%;"' ) ];

		$param['analisa_semi'][] = [ 'title' => "Detail", 'help' => _h( "keterangan/Detail lainnya" ), 'mandatori' => FALSE, 'isi' => form_input( 'aspek_det', ( $data ) ? $data['aspek_det'] : '', 'id="aspek_det" class="form-control" style="width:100%;" ' ) ];

		$urutTemp = [ 1, 7, 8, 9, 10 ];

		$like_semi_form = '<select name="like_id_3" id="like_id_3" class="form-control select" style="width:100%;">';
		if( ! empty( $like_semi ) )
		{

			foreach( $like_semi as $key => $value )
			{
				$sel            = ( $data ) ? $data['like_id'] : '';
				$selected       = ( $sel == $key ) ? 'selected' : '';
				$k              = intval( $key ) - 1;
				$dataTemp       = ( isset( $urutTemp[$k] ) ) ? $urutTemp[$k] : 0;
				$like_semi_form .= '<option data-temp="' . $dataTemp . '" value="' . $key . '"' . $selected . '>' . $value . '</option>';
			}
		}
		$like_semi_form .= '</select>';



		$param['analisa_semi'][] = [ 'title' => _l( 'fld_likelihood' ), 'help' => _h( 'help_likelihood' ), 'mandatori' => TRUE, 'isi' => $like_semi_form ];

		$param['analisa_semi'][] = [ 'title' => '', 'help' => '', 'isi' => '<span class="btn btn-primary legitRipple pointer"  data-rcsa="' . $parent['id'] . '" data-id="' . $id_edit . '" id="indikator_dampak" style="width:100%;"> Input Risk Indikator Dampak  [ ' . $jml_dampak_indi . ' ] </span>' .
		 form_hidden( [ 'indikator_dampak_cek' => ( $jml_dampak_indi ) ? $jml_dampak_indi : '' ], 'id="indikator_dampak_cek"' ) ];


		$param['analisa_semi'][] = [ 'title' => _l( 'fld_impact' ), 'help' => _h( 'help_impact' ), 'mandatori' => TRUE, 'isi' => form_input( 'impact_text_kuantitatif', ( $data ) ? $data['impact_inherent'] : '', 'id="impact_text_kuantitatif_semi" class="form-control" style="width:100%;" readonly="readonly"' ) . form_hidden( [ 'impact_id_3' => ( $data ) ? $data['impact_id'] : '' ], 'id="impact_id_3"' ) ];

		$param['analisa_semi'][] = [ 'title' => '', 'help' => '', 'isi' => form_input( 'like_text_3', ( $data ) ? $data['like_text'] : '', 'id="like_text_3" class="form-control" style="width:100%;display:none"' ) ];


		$param['analisa_semi'][] = [ 'title' => '', 'help' => '', 'isi' => form_input( 'impact_text_3', ( $data ) ? $data['impact_text'] : '', 'id="impact_text_3" class="form-control" style="width:100%;display:none"' ) ];


		$param['analisa2'][] = [ 'title' => _l( 'fld_risiko_inherent' ), 'help' => _h( 'help_risiko_inherent' ), 'mandatori' => TRUE, 'isi' => form_input( 'risiko_inherent_text', ( $data ) ? $data['risiko_inherent_text'] : '', 'class="form-control text-center" id="risiko_inherent_text" readonly="readonly" style="width:15%;"' ) . form_hidden( [ 'risiko_inherent' => ( $data ) ? $data['risiko_inherent'] : 0 ] ) ];
		$param['analisa2'][] = [ 'title' => _l( 'fld_level_risiko' ), 'help' => _h( 'help_level_risiko' ), 'mandatori' => TRUE, 'isi' => form_input( 'level_inherent_text', ( $data ) ? $data['level_color'] : '', 'class="form-control text-center" id="level_inherent_text" readonly="readonly" style="width:30%;' . $csslevel . '"' ) . form_hidden( [ 'level_inherent' => ( $data ) ? $data['level_inherent'] : 0 ] ) ];
		$param['analisa2'][] = [ 'title' => _l( 'fld_nama_control' ), 'help' => _h( 'help_nama_control' ), 'mandatori' => FALSE, 'isi' => $kontrol ];
		$param['analisa2'][] = [ 'title' => _l( 'fld_nama_control_checkbox' ), 'help' => _h( 'help_nama_control_checkbox' ), 'mandatori' => FALSE, 'isi' => $kontrolCheckbox ];
		$param['analisa2'][] = [ 'title' => _l( 'fld_efek_kontrol' ), 'help' => _h( 'help_efek_kontrol' ), 'mandatori' => TRUE, 'isi' => form_dropdown( 'efek_kontrol', $efek_control, ( $data ) ? $data['efek_kontrol'] : '', 'id="efek_kontrol" class="form-control select" style="width:100%;"' ) ];
		if( $data )
		{
			if( $data['lampiran'] == '0' )
			{
				$url    = '#';
				$nmfile = 'Belum ada File';
			}
			else
			{
				$url    = base_url() . substr( $data['lampiran'], 1 );
				$nmfile = substr( $data['lampiran'], 13 );
			}
		}
		else
		{
			$url    = '#';
			$nmfile = 'Belum ada File';
		}
		$param['analisa2'][] = [ 'title' => _l( 'fld_upload' ), 'help' => _h( 'help_upload' ), 'isi' => '<a href="' . $url . '" target="_blank">' . $nmfile . '</a><br><br><b>Max size file 10MB, file yang dibolehkan xlsx | csv | docx | pdf | zip | rar </b><br>' ];
		$param['analisa2'][] = [ 'title' => _l( 'fld_lampiran' ), 'help' => _h( 'help_lampiran' ), 'isi' => form_upload( 'lampiran' ) ];
		return $param;
	}

	function evaluasi_content( $data = [] )
	{
		$aspek = 0;
		if( $data )
		{
			if( $data['tipe_analisa_no'] == 2 || $data['tipe_analisa_no'] == 3 )
			{
				$aspek = $data['aspek_risiko_id'];
			}
			else
			{

				$aspek = 0;
			}
		}
		if( $aspek )
		{
			$like = $this->crud->combo_select( [ 'urut', 'concat(urut,\' - \',data) as x' ] )->combo_where( 'active', 1 )->combo_where( 'pid', $aspek )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();
		}
		else
		{
			$like = $this->crud->combo_select( [ 'id', 'concat(code,\' - \',level) as x' ] )->combo_where( 'active', 1 )->combo_where( 'category', 'likelihood' )->combo_tbl( _TBL_LEVEL )->get_combo()->result_combo();
		}
		$impact        = $this->crud->combo_select( [ 'id', 'concat(code,\' - \',level) as x' ] )->combo_where( 'active', 1 )->combo_where( 'category', 'impact' )->combo_tbl( _TBL_LEVEL )->get_combo()->result_combo();
		$treatment     = $this->crud->combo_select( [ 'id', 'treatment' ] )->combo_where( 'active', 1 )->combo_tbl( _TBL_TREATMENT )->get_combo()->result_combo();
		$efek_mitigasi = [ 0 => _l( 'cbo_select' ), 1 => 'L', 2 => 'D', 3 => 'L & D', 4 => 'Tidak ada mitigasi' ];

		$aspek_risiko      = $this->crud->combo_select( [ 'id', 'data' ] )->combo_where( 'active', 1 )->combo_where( 'kelompok', 'aspek-risiko' )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();
		$parent            = $this->db->where( 'id', $data['rcsa_id'] )->get( _TBL_VIEW_RCSA )->row_array();
		$csslevel          = '';
		$csslevel_inherent = '';
		if( $data )
		{
			$csslevel          = 'background-color:' . $data['color_residual'] . ';color:' . $data['color_text_residual'] . ';';
			$csslevel_inherent = 'background-color:' . $data['color'] . ';color:' . $data['color_text'] . ';';
		}
		if( ! empty( $data['nama_kontrol_note'] ) && ! empty( $data['nama_kontrol'] ) )
		{
			$data['nama_kontrol'] .= '###' . $data['nama_kontrol_note'];
		}
		else
		{
			$data['nama_kontrol'] .= $data['nama_kontrol_note'];
		}
		$y       = explode( '###', $data['nama_kontrol'] );
		$control = '<ul>';
		foreach( $y as $x )
		{
			$control .= '<li>' . $x . '</li>';
		}

		$control .= '</ul>';

		$l_events = 'auto';
		$i_events = 'auto';

		if( $data['efek_kontrol'] == 1 )
		{
			$l = form_dropdown( 'like_residual_id', $like, ( $data['like_residual_id'] > 0 ) ? $data['like_residual_id'] : $data['like_id'], 'id="like_residual_id" class="form-control select" style="width:100%;"' );
			$i = form_dropdown( 'impact_residual_id', $impact, ( $data['impact_residual_id'] > 0 ) ? $data['impact_residual_id'] : $data['impact_id'], 'id="impact_residual_id" disabled readonly="readonly" class="form-control select"  style="width:100%;"' ) .


			 form_input( [ 'type' => 'hidden', 'name' => 'impact_residual_id', 'id' => 'impact_residual_id', 'value' => ( $data['impact_residual_id'] != 0 ) ? $data['impact_residual_id'] : $data['impact_id'] ] );

			$l_events = 'none';
		}
		elseif( $data['efek_kontrol'] == 2 )
		{
			$l = form_dropdown( 'like_residual_id', $like, ( $data['like_residual_id'] > 0 ) ? $data['like_residual_id'] : $data['like_id'], 'id="like_residual_id" readonly="readonly" disabled class="form-control select" style="width:100%;"' ) .


			 form_input( [ 'type' => 'hidden', 'name' => 'like_residual_id', 'id' => 'like_residual_id', 'value' => ( $data ) ? $data['like_residual_id'] : $data['like_id'] ] );
			$i = form_dropdown( 'impact_residual_id', $impact, ( $data['impact_residual_id'] > 0 ) ? $data['impact_residual_id'] : $data['impact_id'], 'id="impact_residual_id" class="form-control select" style="width:100%;"' );

			$i_events = 'none';
		}
		elseif( $data['efek_kontrol'] == 4 )
		{
			$l = form_input( 'like_residual', ( $data['like_residual'] != 0 ) ? $data['like_residual'] : $data['like_id'], 'id="like_residual" class="form-control" readonly="readonly" style="width:100%;"' ) .
			 form_input( [ 'type' => 'hidden', 'name' => 'like_residual_id', 'id' => 'like_residual_id', 'value' => ( $data ) ? $data['like_residual_id'] : $data['like_id'] ] );
			$i = form_input( 'impact_residual', ( $data['impact_residual'] != 0 ) ? $data['impact_residual'] : $data['impact_id'], 'id="impact_residual" class="form-control" readonly="readonly" style="width:100%;"' ) .
			 form_input( [ 'type' => 'hidden', 'name' => 'impact_residual_id', 'id' => 'impact_residual_id', 'value' => ( $data ) ? $data['impact_residual_id'] : $data['impact_id'] ] );

			$l_events = 'none';
		}
		else
		{
			$l = form_dropdown( 'like_residual_id', $like, ( $data['like_residual_id'] > 0 ) ? $data['like_residual_id'] : $data['like_id'], 'id="like_residual_id" class="form-control select" style="width:100%;"' );

			$i = form_input( 'impact_residual', ( $data['impact_residual'] > 0 ) ? $data['impact_residual'] : $data['impact_id'], 'id="impact_residual" class="form-control" readonly="readonly" style="width:100%;"' );

		}

		if( $data['tipe_analisa_no'] == 2 )
		{
			$param['evaluasi'][] = [ 'title' => '', 'help' => '', 'isi' => '<span class="btn btn-primary legitRipple pointer" data-rcsa="' . $parent['id'] . '" data-id="' . $data['id'] . '" data-control="' . $data['efek_kontrol'] . '" id="indikator_like_residual" style="width:100%;pointer-events:' . $i_events . '"> Input Risk Indikator Likelihood </span>' ];

			$param['evaluasi'][] = [ 'title' => '', 'help' => '', 'isi' => '<span class="btn btn-primary legitRipple pointer"  data-rcsa="' . $parent['id'] . '" data-id="' . $data['id'] . '" data-control="' . $data['efek_kontrol'] . '" id="indikator_dampak_residual" style="width:100%;pointer-events:' . $l_events . '"> Input Risk Indikator Dampak </span>' ];

			$param['evaluasi'][] = [ 'title' => _l( 'fld_likelihood_residual' ), 'help' => _h( 'help_likelihood' ), 'isi' => form_input( 'like_text_kuantitatif_residual', ( $data ) ? $data['like_residual'] : '', 'id="like_text_kuantitatif_residual" class="form-control" style="width:100%;" readonly="readonly"' ) . form_hidden( [ 'like_residual_id' => ( $data ) ? $data['like_residual_id'] : '' ] ) ];

			$param['evaluasi'][] = [ 'title' => _l( 'fld_impact_residual' ), 'help' => _h( 'help_impact_residual' ), 'isi' => form_input( 'impact_text_kuantitatif_residual', ( $data ) ? $data['impact_residual'] : '', 'id="impact_text_kuantitatif_residual" class="form-control" style="width:100%;" readonly="readonly"' ) . form_hidden( [ 'impact_residual_id' => ( $data ) ? $data['impact_residual_id'] : '' ] ) ];

			$param['evaluasi'][] = [ 'title' => _l( 'fld_risiko_residual' ), 'help' => _h( 'help_risiko_residual' ), 'isi' => form_input( 'risiko_residual_text', ( $data ) ? $data['risiko_residual_text'] : '', 'class="form-control text-center" id="risiko_residual_text" readonly="readonly" style="width:15%;"' ) . form_hidden( [ 'risiko_residual' => ( $data ) ? $data['risiko_residual'] : 0 ] ) ];

			$param['evaluasi'][] = [ 'title' => _l( 'fld_level_risiko_residual' ), 'help' => _h( 'help_level_risiko' ), 'isi' => form_input( 'level_residual_text', ( $data ) ? $data['level_color_residual'] : '', 'class="form-control text-center" id="level_residual_text" readonly="readonly" style="width:30%;' . $csslevel . '"' ) . form_hidden( [ 'level_residual' => ( $data ) ? $data['level_residual'] : 0, 'sts_save_evaluasi' => ( $data ) ? $data['sts_save_evaluasi'] : 0 ] ) ];
		}
		elseif( $data['tipe_analisa_no'] == 1 )
		{
			$param['evaluasi'][] = [ 'title' => _l( 'fld_likelihood_residual' ), 'help' => _h( 'help_likelihood_residual' ), 'isi' => $l ];
			$param['evaluasi'][] = [ 'title' => _l( 'fld_impact_residual' ), 'help' => _h( 'help_impact_residual' ), 'isi' => $i ];
			$param['evaluasi'][] = [ 'title' => _l( 'fld_risiko_residual' ), 'help' => _h( 'help_risiko_residual' ), 'isi' => form_input( 'risiko_residual_text', ( $data ) ? $data['risiko_residual_text'] : '', 'class="form-control text-center" id="risiko_residual_text" readonly="readonly" style="width:15%;"' ) . form_hidden( [ 'risiko_residual' => ( $data ) ? $data['risiko_residual'] : 0 ] ) ];
			$param['evaluasi'][] = [ 'title' => _l( 'fld_level_risiko_residual' ), 'help' => _h( 'help_level_risiko' ), 'isi' => form_input( 'level_residual_text', ( $data ) ? $data['level_color_residual'] : '', 'class="form-control text-center" id="level_residual_text" readonly="readonly" style="width:30%;' . $csslevel . '"' ) . form_hidden( [ 'level_residual' => ( $data ) ? $data['level_residual'] : 0, 'sts_save_evaluasi' => ( $data ) ? $data['sts_save_evaluasi'] : 0 ] ) ];
		}
		elseif( $data['tipe_analisa_no'] == 3 )
		{
			$param['evaluasi'][] = [ 'title' => _l( 'fld_aspek_risiko' ), 'help' => _h( 'help_aspek_risiko' ), 'isi' => form_dropdown( 'aspek_risiko_id_3', $aspek_risiko, ( $data ) ? $data['aspek_risiko_id'] : '', 'id="aspek_risiko_id_3" class="form-control select" style="width:100%;" disabled="disabled"' ) ];

			$urutTemp = [ 1, 7, 8, 9, 10 ];

			$like_semi_form = '<select name="like_residual_id" id="like_residual_id_3" class="form-control select" style="width:100%;">';
			if( ! empty( $like ) )
			{

				foreach( $like as $key => $value )
				{
					$sel            = ( $data ) ? $data['like_residual_id'] : '';
					$selected       = ( $sel == $key ) ? 'selected' : '';
					$k              = intval( $key ) - 1;
					$dataTemp       = ( isset( $urutTemp[$k] ) ) ? $urutTemp[$k] : 0;
					$like_semi_form .= '<option data-temp="' . $dataTemp . '" value="' . $key . '"' . $selected . '>' . $value . '</option>';
				}
			}
			$like_semi_form .= '</select>';

			$param['evaluasi'][] = [ 'title' => _l( 'fld_likelihood_residual' ), 'help' => _h( 'help_likelihood_residual' ), 'isi' => $like_semi_form ];


			$param['evaluasi'][] = [ 'title' => '', 'help' => '', 'isi' => '<span class="btn btn-primary legitRipple pointer"  data-rcsa="' . $parent['id'] . '" data-id="' . $data['id'] . '" data-control="' . $data['efek_kontrol'] . '" id="indikator_dampak_residual" style="width:100%;pointer-events:' . $l_events . '"> Input Risk Indikator Dampak </span>' ];

			$param['evaluasi'][] = [ 'title' => _l( 'fld_impact_residual' ), 'help' => _h( 'help_impact_residual' ), 'isi' => form_input( 'impact_text_kuantitatif_residual', ( $data ) ? $data['impact_residual'] : '', 'id="impact_text_kuantitatif_residual" class="form-control" style="width:100%;" readonly="readonly"' ) . form_hidden( [ 'impact_residual_id' => ( $data ) ? $data['impact_residual_id'] : '' ] ) ];


			$param['evaluasi'][] = [ 'title' => _l( 'fld_risiko_residual' ), 'help' => _h( 'help_risiko_residual' ), 'isi' => form_input( 'risiko_residual_text', ( $data ) ? $data['risiko_residual_text'] : '', 'class="form-control text-center" id="risiko_residual_text" readonly="readonly" style="width:15%;"' ) . form_hidden( [ 'risiko_residual' => ( $data ) ? $data['risiko_residual'] : 0 ] ) ];
			$param['evaluasi'][] = [ 'title' => _l( 'fld_level_risiko_residual' ), 'help' => _h( 'help_level_risiko' ), 'isi' => form_input( 'level_residual_text', ( $data ) ? $data['level_color_residual'] : '', 'class="form-control text-center" id="level_residual_text" readonly="readonly" style="width:30%;' . $csslevel . '"' ) . form_hidden( [ 'level_residual' => ( $data ) ? $data['level_residual'] : 0, 'sts_save_evaluasi' => ( $data ) ? $data['sts_save_evaluasi'] : 0 ] ) ];
		}
		$param['evaluasi'][] = [ 'title' => _l( 'fld_treatment' ), 'help' => _h( 'help_treatment' ), 'mandatori' => TRUE, 'isi' => form_dropdown( 'treatment_id', $treatment, ( $data ) ? $data['treatment_id'] : '', 'class="form-control select" id="treatment_id" style="width:100%;"' ) ];

		$param['evaluasi'][] = [ 'title' => _l( 'fld_efek_mitigasi' ), 'help' => _h( 'help_efek_mitigasi' ), 'mandatori' => TRUE, 'isi' => form_dropdown( 'efek_mitigasi', $efek_mitigasi, ( $data ) ? $data['efek_mitigasi'] : '', 'id="efek_mitigasi" class="form-control select" style="width:100%;"' ) ];


		$param['info'][] = [ 'title' => _l( 'fld_risiko_dept' ), 'isi' => $data['risiko_dept'] ];
		$param['info'][] = [ 'title' => _l( 'fld_level_risiko' ), 'isi' => form_input( 'level_inherent_info', ( $data ) ? $data['risiko_inherent_text'] : '', 'class="form-control text-center" id="level_inherent_info" readonly="readonly"  style="width:40%;"' ) . '<span class="input-group-append">
		<button class="btn btn-light legitRipple" type="button" style="' . $csslevel_inherent . '">' . $data['level_color'] . '</button>
		</span>' ];
		$param['info'][] = [ 'title' => _l( 'fld_nama_control' ), 'isi' => $control ];
		$param['info'][] = [ 'title' => _l( 'fld_efek_kontrol' ), 'isi' => $data['efek_kontrol_text'] ];

		return $param;
	}

	function target_content( $data = [] )
	{
		$aspek = 0;
		if( $data )
		{
			if( $data['tipe_analisa_no'] == 2 || $data['tipe_analisa_no'] == 3 )
			{
				$aspek = $data['aspek_risiko_id'];
			}
			else
			{

				$aspek = 0;
			}
		}
		if( $aspek )
		{
			$like = $this->crud->combo_select( [ 'urut', 'concat(urut,\' - \',data) as x' ] )->combo_where( 'active', 1 )->combo_where( 'pid', $aspek )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();
		}
		else
		{
			$like = $this->crud->combo_select( [ 'id', 'concat(code,\' - \',level) as x' ] )->combo_where( 'active', 1 )->combo_where( 'category', 'likelihood' )->combo_tbl( _TBL_LEVEL )->get_combo()->result_combo();
		}
		$aspek_risiko = $this->crud->combo_select( [ 'id', 'data' ] )->combo_where( 'active', 1 )->combo_where( 'kelompok', 'aspek-risiko' )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();
		$impact       = $this->crud->combo_select( [ 'id', 'concat(code,\' - \',level) as x' ] )->combo_where( 'active', 1 )->combo_where( 'category', 'impact' )->combo_tbl( _TBL_LEVEL )->get_combo()->result_combo();
		$treatment    = $this->crud->combo_select( [ 'id', 'treatment' ] )->combo_where( 'active', 1 )->combo_tbl( _TBL_TREATMENT )->get_combo()->result_combo();
		$parent       = $this->db->where( 'id', $data['rcsa_id'] )->get( _TBL_VIEW_RCSA )->row_array();

		$csslevel          = '';
		$csslevel_inherent = '';
		if( $data )
		{
			$bg       = ( $data['color_target'] ) ? $data['color_target'] : $data['color_residual'];
			$clr      = ( $data['color_text_target'] ) ? $data['color_text_target'] : $data['color_text_residual'];
			$csslevel = 'background-color:' . $bg . ';color:' . $clr . ';';

			$csslevel_inherent = 'background-color:' . $data['color_residual'] . ';color:' . $data['color_text_residual'] . ';';
		}

		$y       = explode( '###', $data['nama_kontrol'] );
		$control = '';
		foreach( $y as $x )
		{
			$control .= '- ' . $x . '<br/>';
		}

		$l_events = 'auto';
		$i_events = 'auto';
		if( $data['efek_mitigasi'] == 1 )
		{
			$l        = form_dropdown( 'like_target_id', $like, ( $data['like_target_id'] ) ? $data['like_target_id'] : $data['like_residual_id'], 'id="like_target_id" class="form-control select" style="width:100%;"' );
			$i        = form_input( 'impact_target', ( $data['impact_target'] ) ? $data['impact_target'] : $data['impact_residual'], 'id="impact_target" class="form-control" readonly="readonly" style="width:100%;"' ) . form_input( [ 'type' => 'hidden', 'name' => 'impact_target_id', 'id' => 'impact_target_id', 'value' => ( $data['impact_target_id'] ) ? $data['impact_target_id'] : $data['impact_residual_id'] ] );
			$l_events = 'none';
		}
		elseif( $data['efek_mitigasi'] == 2 )
		{
			$l        = form_input( 'like_target', ( $data['like_target'] ) ? $data['like_target'] : $data['like_residual'], 'id="like_target" class="form-control" readonly="readonly" style="width:100%;"' ) . form_input( [ 'type' => 'hidden', 'name' => 'like_target_id', 'id' => 'like_target_id', 'value' => ( $data['like_target_id'] ) ? $data['like_target_id'] : $data['like_residual_id'] ] );
			$i        = form_dropdown( 'impact_target_id', $impact, ( $data['impact_target_id'] ) ? $data['impact_target_id'] : $data['impact_residual_id'], 'id="impact_target_id" class="form-control select" style="width:100%;"' );
			$i_events = 'none';
		}
		elseif( $data['efek_mitigasi'] == 4 )
		{
			$l        = form_input( 'like_target', ( $data['like_target'] ) ? $data['like_target'] : $data['like_residual'], 'id="like_target" class="form-control" readonly="readonly" style="width:100%;"' ) . form_input( [ 'type' => 'hidden', 'name' => 'like_target_id', 'id' => 'like_target_id', 'value' => ( $data['like_target_id'] ) ? $data['like_target_id'] : $data['like_residual_id'] ] );
			$i        = form_input( 'impact_target', ( $data['impact_target'] ) ? $data['impact_target'] : $data['impact_residual'], 'id="impact_target" class="form-control" readonly="readonly" style="width:100%;"' ) . form_input( [ 'type' => 'hidden', 'name' => 'impact_target_id', 'id' => 'impact_target_id', 'value' => ( $data['impact_target_id'] ) ? $data['impact_target_id'] : $data['impact_residual_id'] ] );
			$i_events = 'none';
		}
		else
		{
			$l = form_dropdown( 'like_target_id', $like, ( $data['like_target_id'] ) ? $data['like_target_id'] : $data['like_residual_id'], 'id="like_target_id" class="form-control select" style="width:100%;"' );
			$i = form_dropdown( 'impact_target_id', $impact, ( $data['impact_target_id'] ) ? $data['impact_target_id'] : $data['impact_residual_id'], 'id="impact_target_id" class="form-control select" style="width:100%;"' );
		}

		if( $data['tipe_analisa_no'] == 2 )
		{
			$param['target'][] = [ 'title' => '', 'help' => '', 'isi' => '<span class="btn btn-primary legitRipple pointer" data-rcsa="' . $parent['id'] . '" data-id="' . $data['id'] . '" data-control="' . $data['efek_kontrol'] . '" id="indikator_like_target" style="width:100%;pointer-events:' . $i_events . '"> Input Risk Indikator Likelihood </span>' ];

			$param['target'][] = [ 'title' => '', 'help' => '', 'isi' => '<span class="btn btn-primary legitRipple pointer"  data-rcsa="' . $parent['id'] . '" data-id="' . $data['id'] . '" data-control="' . $data['efek_kontrol'] . '" id="indikator_dampak_target" style="width:100%;pointer-events:' . $l_events . '"> Input Risk Indikator Dampak </span>' ];
			$param['target'][] = [ 'title' => _l( 'fld_likelihood_target' ), 'help' => _h( 'help_likelihood' ), 'isi' => form_input( 'like_text_kuantitatif_targetl', ( $data ) ? $data['like_target'] : '', 'id="like_text_kuantitatif_target" class="form-control" style="width:100%;" readonly="readonly"' ) . form_hidden( [ 'like_target_id' => ( $data ) ? $data['like_target_id'] : '' ] ) ];

			$param['target'][] = [ 'title' => _l( 'fld_impact_target' ), 'help' => _h( 'help_impact_target' ), 'isi' => form_input( 'impact_text_kuantitatif_target', ( $data ) ? $data['impact_target'] : '', 'id="impact_text_kuantitatif_target" class="form-control" style="width:100%;" readonly="readonly"' ) . form_hidden( [ 'impact_target_id' => ( $data ) ? $data['impact_target_id'] : '' ] ) ];

			$param['target'][] = [ 'title' => _l( 'fld_risiko_target' ), 'help' => _h( 'help_risiko_target' ), 'isi' => form_input( 'risiko_target_text', ( $data['risiko_target_text'] ) ? $data['risiko_target_text'] : $data['risiko_residual_text'], 'class="form-control text-center" id="risiko_target_text" readonly="readonly" style="width:15%;"' ) . form_hidden( [ 'risiko_target' => ( $data['risiko_target'] ) ? $data['risiko_target'] : $data['risiko_residual'] ] ) ];
			$param['target'][] = [ 'title' => _l( 'fld_level_risiko_target' ), 'help' => _h( 'help_level_risiko' ), 'isi' => form_input( 'level_target_text', ( $data['level_color_target'] ) ? $data['level_color_target'] : $data['level_color_residual'], 'class="form-control text-center" id="level_target_text" readonly="readonly" style="width:30%;' . $csslevel . '"' ) . form_hidden( [ 'level_target' => ( $data['level_target'] ) ? $data['level_target'] : $data['level_residual'] ] ) ];
		}
		elseif( $data['tipe_analisa_no'] == 1 )
		{
			$param['target'][] = [ 'title' => _l( 'fld_likelihood_target' ), 'help' => _h( 'help_likelihood_target' ), 'isi' => $l ];
			$param['target'][] = [ 'title' => _l( 'fld_impact_target' ), 'help' => _h( 'help_impact_target' ), 'isi' => $i ];
			$param['target'][] = [ 'title' => _l( 'fld_risiko_target' ), 'help' => _h( 'help_risiko_target' ), 'isi' => form_input( 'risiko_target_text', ( $data['risiko_target_text'] ) ? $data['risiko_target_text'] : $data['risiko_residual_text'], 'class="form-control text-center" id="risiko_target_text" readonly="readonly" style="width:15%;"' ) . form_hidden( [ 'risiko_target' => ( $data['risiko_target'] ) ? $data['risiko_target'] : $data['risiko_residual'] ] ) ];
			$param['target'][] = [ 'title' => _l( 'fld_level_risiko_target' ), 'help' => _h( 'help_level_risiko' ), 'isi' => form_input( 'level_target_text', ( $data['level_color_target'] ) ? $data['level_color_target'] : $data['level_color_residual'], 'class="form-control text-center" id="level_target_text" readonly="readonly" style="width:30%;' . $csslevel . '"' ) . form_hidden( [ 'level_target' => ( $data['level_target'] ) ? $data['level_target'] : $data['level_residual'] ] ) ];
		}
		elseif( $data['tipe_analisa_no'] == 3 )
		{
			$param['target'][] = [ 'title' => _l( 'fld_aspek_risiko' ), 'help' => _h( 'help_aspek_risiko' ), 'isi' => form_dropdown( 'aspek_risiko_id_3', $aspek_risiko, ( $data ) ? $data['aspek_risiko_id'] : '', 'id="aspek_risiko_id_3" class="form-control select" style="width:100%;" disabled="disabled"' ) ];


			$urutTemp = [ 1, 7, 8, 9, 10 ];

			$like_semi_form = '<select name="like_target_id" id="like_target_id_3" class="form-control select" style="width:100%;">';
			if( ! empty( $like ) )
			{

				foreach( $like as $key => $value )
				{
					$sel            = ( $data ) ? $data['like_target_id'] : '';
					$selected       = ( $sel == $key ) ? 'selected' : '';
					$k              = intval( $key ) - 1;
					$dataTemp       = ( isset( $urutTemp[$k] ) ) ? $urutTemp[$k] : 0;
					$like_semi_form .= '<option data-temp="' . $dataTemp . '" value="' . $key . '"' . $selected . '>' . $value . '</option>';
				}
			}
			$like_semi_form .= '</select>';

			$param['target'][] = [ 'title' => _l( 'fld_likelihood_target' ), 'help' => _h( 'help_likelihood_target' ), 'isi' => $like_semi_form ];


			$param['target'][] = [ 'title' => '', 'help' => '', 'isi' => '<span class="btn btn-primary legitRipple pointer"  data-rcsa="' . $parent['id'] . '" data-id="' . $data['id'] . '" data-control="' . $data['efek_kontrol'] . '" id="indikator_dampak_target" style="width:100%;pointer-events:' . $l_events . '"> Input Risk Indikator Dampak </span>' ];


			$param['target'][] = [ 'title' => _l( 'fld_impact_target' ), 'help' => _h( 'help_impact_target' ), 'isi' => form_input( 'impact_text_kuantitatif_target', ( $data ) ? $data['impact_target'] : '', 'id="impact_text_kuantitatif_target" class="form-control" style="width:100%;" readonly="readonly"' ) . form_hidden( [ 'impact_target_id' => ( $data ) ? $data['impact_target_id'] : '' ] ) ];


			$param['target'][] = [ 'title' => _l( 'fld_risiko_target' ), 'help' => _h( 'help_risiko_target' ), 'isi' => form_input( 'risiko_target_text', ( $data['risiko_target_text'] ) ? $data['risiko_target_text'] : $data['risiko_residual_text'], 'class="form-control text-center" id="risiko_target_text" readonly="readonly" style="width:15%;"' ) . form_hidden( [ 'risiko_target' => ( $data['risiko_target'] ) ? $data['risiko_target'] : $data['risiko_residual'] ] ) ];
			$param['target'][] = [ 'title' => _l( 'fld_level_risiko_target' ), 'help' => _h( 'help_level_risiko' ), 'isi' => form_input( 'level_target_text', ( $data['level_color_target'] ) ? $data['level_color_target'] : $data['level_color_residual'], 'class="form-control text-center" id="level_target_text" readonly="readonly" style="width:30%;' . $csslevel . '"' ) . form_hidden( [ 'level_target' => ( $data['level_target'] ) ? $data['level_target'] : $data['level_residual'] ] ) ];
		}



		$param['info'][] = [ 'title' => _l( 'fld_risiko_dept' ), 'isi' => $data['risiko_dept'] ];
		$param['info'][] = [ 'title' => _l( 'fld_level_risiko_residual' ), 'isi' => form_input( 'level_target_info', ( $data ) ? $data['risiko_residual_text'] : '', 'class="form-control text-center" id="level_target_info" readonly="readonly"  style="width:40%;"' ) . '<span class="input-group-append">
		<button class="btn btn-light legitRipple" type="button" style="' . $csslevel_inherent . '">' . $data['level_color_residual'] . '</button>
		</span>' ];

		$param['info'][] = [ 'title' => _l( 'fld_efek_mitigasi' ), 'isi' => $data['efek_mitigasi_text'] ];

		return $param;
	}

	function add_mitigasi( $edit_id = 0, $rcsa_detail = 0 )
	{
		$mode = 'add';
		if( ! $rcsa_detail && ! $edit_id )
		{
			$mode        = 'edit';
			$edit_id     = intval( $this->input->post( 'id' ) );
			$rcsa_detail = $this->input->post( 'rcsa_detail' );
		}

		$data['parent']     = $this->db->where( 'id', $rcsa_detail )->get( _TBL_VIEW_RCSA_DETAIL )->row_array();
		$mit                = $this->db->where( 'id', $edit_id )->get( _TBL_VIEW_RCSA_MITIGASI )->row_array();
		$owner              = [];
		$data['mitigasi'][] = [ 'title' => _l( 'fld_mitigasi' ), 'help' => _h( 'help_mitigasi' ), 'mandatori' => TRUE, 'isi' => form_input( 'mitigasi', ( $mit ) ? $mit['mitigasi'] : '', 'id="mitigasi" class="form-control" style="width:100%;"' ) ];
		$data['mitigasi'][] = [ 'title' => _l( 'fld_biaya' ), 'help' => _h( 'help_biaya' ), 'mandatori' => TRUE, 'isi' => '<span class="input-group-prepend"><span class="input-group-text">Rp. </span></span>' . form_input( 'biaya', ( $mit ) ? $mit['biaya'] : '', 'id="biaya" class="form-control rupiah text-right" style="width:30%;"' ) ];
		$data['mitigasi'][] = [ 'title' => _l( 'fld_pic' ), 'help' => _h( 'help_pic' ), 'mandatori' => TRUE, 'isi' => form_dropdown( 'penanggung_jawab_id[]', $this->cboStack, ( $mit ) ? json_decode( $mit['penanggung_jawab_id'] ) : '', 'class="form-control select" id="penanggung_jawab_id" multiple="multiple" ' ) ];
		$data['mitigasi'][] = [ 'title' => _l( 'fld_koordinator' ), 'help' => _h( 'help_koordinator' ), 'mandatori' => TRUE, 'isi' => form_dropdown( 'koordinator_id', $this->cboStack, ( $mit ) ? $mit['koordinator_id'] : '', 'class="form-control select" id="koordinator_id"  ' ) ];
		$data['mitigasi'][] = [ 'title' => _l( 'fld_due_date' ), 'help' => _h( 'help_due_date' ), 'mandatori' => TRUE, 'isi' => form_input( 'batas_waktu', ( $mit ) ? $mit['batas_waktu'] : '', 'class="form-control pickadateaddmitigasi" id="batas_waktu" style="width:100%;"' ) ];
		$data['mitigasi'][] = [ 'title' => _l( 'fld_status_jangka' ), 'help' => _h( 'help_status_jangka' ), 'mandatori' => TRUE, 'isi' => form_dropdown( 'status_jangka', [ 1 => 'Jangka Pendek', 2 => 'Jangka Panjang' ], ( $mit ) ? $mit['status_jangka'] : '', 'class="form-control select" id="status_jangka"' ) ];
		$data['mitigasi'][] = [ 'title' => '', 'help' => '', 'isi' => form_hidden( [ 'id' => ( $mit ) ? $mit['id'] : 0, 'rcsa_detail_id' => intval( $rcsa_detail ) ] ) ];
		$data['mitigasi'][] = [ 'title' => "Reminder Email Notification ", 'help' => "", 'mandatori' => TRUE, 'isi' => "<span class='input-group-prepend'><span class='input-group-text'><</span></span><div class='input-group' style='width:15% !important;'><button type='button' onclick='this.parentNode.querySelector(\'[type=number]\').stepDown();'>
				-
			</button>'" . form_input( array( 'type' => 'number', 'name' => 'email_reminder' ), ( ! empty( $mit['reminder_email'] ) ) ? $mit['reminder_email'] : '', " class='form-control touchspin-postfix text-center' placeholder='hari' max='' min='1' step='1' id='email_reminder' " ) . '<button type="button"  onclick="this.parentNode.querySelector(\'[type=number]\').stepUp();">
				+
			</button>
			</div>' ];



		$result = $this->load->view( 'mitigasi', $data, TRUE );
		if( $mode == 'add' )
		{
			return $result;
		}
		else
		{
			header( 'Content-type: application/json' );
			echo json_encode( [ 'combo' => $result ] );
		}
	}

	function delete_mitigasi()
	{
		$id = $this->input->post( 'id' );
		$this->crud->crud_table( _TBL_RCSA_MITIGASI );
		$this->crud->crud_type( 'delete' );
		$this->crud->crud_where( [ 'field' => 'id', 'value' => $id ] );
		$this->crud->process_crud();
		header( 'Content-type: application/json' );
		echo json_encode( [ 'combo' => 'berhasil' ] );
	}

	function delete_identifikasi()
	{
		$id = $this->input->post( 'id' );
		$this->crud->crud_table( _TBL_RCSA_DETAIL );
		$this->crud->crud_type( 'delete' );
		$this->crud->crud_where( [ 'field' => 'id', 'value' => $id ] );
		$this->crud->process_crud();

		$this->crud->crud_table( _TBL_RCSA_DET_DAMPAK_INDI );
		$this->crud->crud_type( 'delete' );
		$this->crud->crud_where( [ 'field' => 'rcsa_detail_id', 'value' => $id ] );
		$this->crud->process_crud();

		$this->crud->crud_table( _TBL_RCSA_DET_LIKE_INDI );
		$this->crud->crud_type( 'delete' );
		$this->crud->crud_where( [ 'field' => 'rcsa_detail_id', 'value' => $id ] );
		$this->crud->process_crud();
		header( 'Content-type: application/json' );
		echo json_encode( [ 'combo' => 'berhasil' ] );
	}

	function add_aktifitas_mitigasi( $edit_id = 0, $mitigasi_id = 0 )
	{
		$mode  = 'add';
		$entry = FALSE;
		if( ! $mitigasi_id && ! $edit_id )
		{
			$mode        = 'edit';
			$edit_id     = intval( $this->input->post( 'id' ) );
			$mitigasi_id = intval( $this->input->post( 'mitigasi_id' ) );
			$part        = intval( $this->input->post( 'part' ) );
			if( $edit_id > 0 || $part )
			{
				$entry = TRUE;
			}
		}

		$data['parent']         = $this->db->where( 'id', $mitigasi_id )->get( _TBL_VIEW_RCSA_MITIGASI )->row_array();
		$mit                    = $this->db->where( 'id', $edit_id )->get( _TBL_VIEW_RCSA_MITIGASI_DETAIL )->row_array();
		$data['list_aktifitas'] = $this->db->where( 'rcsa_mitigasi_id', $mitigasi_id )->get( _TBL_VIEW_RCSA_MITIGASI_DETAIL )->result_array();
		$data['picku']          = $this->get_data_dept();
		$data['list_aktifitas'] = $this->load->view( 'list-aktifitas-mitigasi', $data, TRUE );

		$data['mitigasi'][] = [ 'title' => _l( 'fld_aktifitas_mitigasi' ), 'help' => _h( 'help_aktifitas_mitigasi' ), 'mandatori' => TRUE, 'isi' => form_input( 'aktifitas_mitigasi', ( $mit ) ? $mit['aktifitas_mitigasi'] : '', 'id="aktifitas_mitigasi" class="form-control" style="width:100%;"' ) ];
		$data['mitigasi'][] = [ 'title' => _l( 'fld_pic' ), 'help' => _h( 'help_pic' ), 'isi' => form_dropdown( 'penanggung_jawab_detail_id[]', $this->cboStack, ( $mit ) ? json_decode( $mit['penanggung_jawab_detail_id'] ) : '', 'class="form-control select" id="penanggung_jawab_detail_id" multiple="multiple"' ) ];
		$data['mitigasi'][] = [ 'title' => _l( 'fld_koordinator' ), 'help' => _h( 'help_koordinator' ), 'isi' => form_dropdown( 'koordinator_detail_id', $this->cboStack, ( $mit ) ? $mit['koordinator_detail_id'] : '', 'class="form-control select" id="koordinator_detail_id"' ) ];
		$data['mitigasi'][] = [ 'title' => _l( 'fld_due_date' ), 'help' => _h( 'help_due_date' ), 'mandatori' => TRUE, 'isi' => form_input( 'batas_waktu_detail', ( $mit ) ? $mit['batas_waktu_detail'] : '', 'class="form-control pickadate2" id="batas_waktu_detail" style="width:100%;"' ) ];
		$data['mitigasi'][] = [ 'title' => '', 'help' => '', 'isi' => form_hidden( [ 'id' => ( $mit ) ? $mit['id'] : 0, 'rcsa_mitigasi_id' => intval( $mitigasi_id ) ] ) ];

		$data['aktifitas'] = $this->load->view( 'input-aktifitas-mitigasi', $data, TRUE );
		$result            = $this->load->view( 'aktifitas-mitigasi', $data, TRUE );
		if( $mode == 'add' )
		{
			return $result;
		}
		else
		{
			if( $entry )
			{
				header( 'Content-type: application/json' );
				echo json_encode( [ 'combo' => $data['aktifitas'] ] );
			}
			else
			{
				header( 'Content-type: application/json' );
				echo json_encode( [ 'combo' => $result ] );
			}
		}
	}

	function simpan_mitigasi()
	{
		$post                        = $this->input->post();
		$post['penanggung_jawab_id'] = json_encode( $post['penanggung_jawab_id'] );
		$id                          = $this->data->simpan_mitigasi( $post );

		$id_detail          = intval( $post['rcsa_detail_id'] );
		$result['mitigasi'] = $this->add_mitigasi( $id, $id_detail );

		$rows = $this->db->select( 'rcsa_mitigasi_id as id, count(rcsa_mitigasi_id) as jml' )->group_by( [ 'rcsa_mitigasi_id' ] )->where( 'rcsa_detail_id', $id_detail )->get( _TBL_VIEW_RCSA_MITIGASI_DETAIL )->result_array();
		$miti = [];
		foreach( $rows as $row )
		{
			$miti[$row['id']] = $row['jml'];
		}
		// $rows=$this->db->where('rcsa_detail_id', $edit)->get(_TBL_VIEW_RCSA_MITIGASI)->result_array();
		$rows = $this->db->where( 'rcsa_detail_id', $id_detail )->get( _TBL_VIEW_RCSA_MITIGASI )->result_array();
		foreach( $rows as &$row )
		{
			if( array_key_exists( $row['id'], $miti ) )
			{
				$row['jml'] = $miti[$row['id']];
			}
		}
		unset( $row );
		$rows             = $this->convert_owner->set_data( $rows )->set_param( [ 'penanggung_jawab' => 'penanggung_jawab_id', 'koordinator' => 'koordinator_id' ] )->draw();
		$data['mitigasi'] = $rows;
		$data['picku']    = $this->get_data_dept();


		$data['rcsa_detail'] = $this->db->where( 'id', $id_detail )->get( _TBL_VIEW_RCSA_DETAIL )->row_array();

		$result['list_mitigasi'] = $this->load->view( 'list-mitigasi', $data, TRUE );

		header( 'Content-type: application/json' );
		echo json_encode( $result );
	}

	function delete_aktifitas_mitigasi()
	{
		$id = $this->input->post( 'id' );
		$this->crud->crud_table( _TBL_RCSA_MITIGASI_DETAIL );
		$this->crud->crud_type( 'delete' );
		$this->crud->crud_where( [ 'field' => 'id', 'value' => $id ] );
		$this->crud->process_crud();
		header( 'Content-type: application/json' );
		echo json_encode( [ 'combo' => 'berhasil' ] );
	}

	function simpan_aktifitas_mitigasi()
	{
		$post                               = $this->input->post();
		$post['penanggung_jawab_detail_id'] = json_encode( $post['penanggung_jawab_detail_id'] );

		$id = $this->data->simpan_aktifitas_mitigasi( $post );

		$id_detail       = intval( $post['rcsa_mitigasi_id'] );
		$result['combo'] = $this->add_aktifitas_mitigasi( $id, $id_detail );

		$x                   = $this->db->where( 'id', $post['rcsa_mitigasi_id'] )->get( _TBL_VIEW_RCSA_MITIGASI )->row_array();
		$data['parent']      = $this->db->where( 'id', $x['rcsa_id'] )->get( _TBL_VIEW_RCSA )->row_array();
		$data['rcsa_detail'] = $this->db->where( 'id', $x['rcsa_detail_id'] )->get( _TBL_VIEW_RCSA_DETAIL )->row_array();

		$rows = $this->db->select( 'rcsa_mitigasi_id as id, count(rcsa_mitigasi_id) as jml' )->group_by( [ 'rcsa_mitigasi_id' ] )->where( 'rcsa_detail_id', $x['rcsa_detail_id'] )->get( _TBL_VIEW_RCSA_MITIGASI_DETAIL )->result_array();
		$miti = [];
		foreach( $rows as $row )
		{
			$miti[$row['id']] = $row['jml'];
		}
		// $rows=$this->db->where('rcsa_detail_id', $edit)->get(_TBL_VIEW_RCSA_MITIGASI)->result_array();
		$rows = $this->db->where( 'rcsa_detail_id', $x['rcsa_detail_id'] )->get( _TBL_VIEW_RCSA_MITIGASI )->result_array();
		foreach( $rows as &$row )
		{
			if( array_key_exists( $row['id'], $miti ) )
			{
				$row['jml'] = $miti[$row['id']];
			}
		}
		unset( $row );
		$rows                    = $this->convert_owner->set_data( $rows )->set_param( [ 'penanggung_jawab' => 'penanggung_jawab_id', 'koordinator' => 'koordinator_id' ] )->draw();
		$data['mitigasi']        = $rows;
		$data['picku']           = $this->get_data_dept();
		$result['list_mitigasi'] = $this->load->view( 'list-mitigasi-part', $data, TRUE );
		header( 'Content-type: application/json' );
		echo json_encode( $result );
	}

	function simpan_identifikasi()
	{
		$post      = $this->input->post();
		$id_detail = $this->data->simpan_identifikasi( $post );

		$id    = intval( $post['rcsa_id'] );
		$hasil = $this->edit_identifikasi( $id, $id_detail );
		header( 'Content-type: application/json' );
		echo json_encode( $hasil );
	}

	function simpan_identifikasi_awal()
	{
		$post      = $this->input->post();
		$id_detail = $this->data->simpan_identifikasi_awal( $post );

		$id    = intval( $post['rcsa_id'] );
		$hasil = $this->edit_identifikasi( $id, $id_detail );
		header( 'Content-type: application/json' );
		echo json_encode( $hasil );
	}

	function simpan_evaluasi()
	{
		$post      = $this->input->post();
		$id_detail = $this->data->simpan_evaluasi( $post );

		$id    = intval( $post['rcsa_id'] );
		$hasil = $this->edit_identifikasi( $id, $id_detail );
		header( 'Content-type: application/json' );
		echo json_encode( $hasil );
	}

	function simpan_target()
	{

		$post      = $this->input->post();
		$id_detail = $this->data->simpan_target( $post );

		$id    = intval( $post['rcsa_id'] );
		$hasil = $this->edit_identifikasi( $id, $id_detail );
		header( 'Content-type: application/json' );
		echo json_encode( $hasil );
	}

	// function proses_propose(){
	// 	$id=$this->uri->segment(3);
	// 	$this->crud->crud_table(_TBL_RCSA);
	// 	$this->crud->crud_type('edit');
	// 	$this->crud->crud_field('status_id', 1, 'int');
	// 	$this->crud->crud_field('tgl_propose', date('Y-m-d H:i:s'), 'data');
	// 	$this->crud->crud_where(['field' => 'id', 'value' => $id]);
	// 	$this->crud->process_crud();
	// 	header('location:'.base_url(_MODULE_NAME_));
	// }

	function propose_risiko()
	{
		$this->load->library( 'map' );
		$id             = intval( $this->uri->segment( 3 ) );
		$data['parent'] = $this->db->where( 'id', $id )->get( _TBL_VIEW_RCSA )->row_array();
		$data['detail'] = $this->db->where( 'rcsa_id', $id )->get( _TBL_VIEW_RCSA_DETAIL )->result_array();

		$cbominggu = $this->data->get_data_minggu( $data['parent']['term_id'] );
		$minggu    = ( $data['parent']['minggu_id'] ) ? $cbominggu[$data['parent']['minggu_id']] : '';

		$data['parent']['bulan'] = ( isset( $this->term[$data['parent']['term_id']] ) ) ? $this->term[$data['parent']['term_id']] . ' - ' . $minggu : '-';

		$data['info_parent']  = $this->load->view( 'info-parent', $data, TRUE );
		$rows                 = $this->db->where( 'rcsa_id', $id )->SELECT( 'risiko_inherent as id, COUNT(risiko_inherent) as nilai' )->group_by( 'risiko_inherent' )->get( _TBL_VIEW_RCSA_DETAIL )->result_array();
		$data['map_inherent'] = $this->map->set_data( $rows )->set_param( [ 'tipe' => 'angka', 'level' => 1 ] )->draw();
		$rows                 = $this->db->where( 'rcsa_id', $id )->SELECT( 'risiko_residual as id, COUNT(risiko_residual) as nilai' )->group_by( 'risiko_residual' )->get( _TBL_VIEW_RCSA_DETAIL )->result_array();
		$data['map_residual'] = $this->map->set_data( $rows )->set_param( [ 'tipe' => 'angka', 'level' => 2 ] )->draw();
		$rows                 = $this->db->where( 'rcsa_id', $id )->SELECT( 'risiko_target as id, COUNT(risiko_target) as nilai' )->group_by( 'risiko_target' )->get( _TBL_VIEW_RCSA_DETAIL )->result_array();
		$data['map_target']   = $this->map->set_data( $rows )->set_param( [ 'tipe' => 'angka', 'level' => 3 ] )->draw();
		$data['note_propose'] = form_textarea( 'note_propose', '', " id='note_propose' placeholder = 'silahkan masukkan catatan anda disini' maxlength='500' size='500' class='form-control' style='overflow: hidden; width: 100% !important; height: 200px;' onblur='_maxLength(this , \"id_sisa_1\")' onkeyup='_maxLength(this , \"id_sisa_1\")' data-role='tagsinput'", TRUE, [ 'size' => 500, 'isi' => 0, 'no' => 1 ] );

		$alur            = $this->data_alur( [ 'owner_no' => $data['parent']['owner_id'], 'ass_type_no' => $data['parent']['type_ass_id'] ] );
		$data_notif      = [];
		$data_notif_asli = [ 'level_approval_id' => 0 ];

		$data['alur']      = $alur;
		$data['histori']   = $this->db->where( 'rcsa_id', $id )->where( 'tipe_log', 1 )->order_by( 'tanggal desc' )->get( _TBL_VIEW_LOG_APPROVAL )->result_array();
		$data['info_alur'] = $this->load->view( 'info-alur', $data, TRUE );

		$ket = 'Risk Context ini belum memiliki Owner atau officer yang akan melakukan approval';
		if( $alur )
		{
			if( array_key_exists( 1, $alur ) )
			{
				$data_notif      = $alur[1];
				$data_notif_asli = $alur[0];
				$ket             = 'Risk Context akan dikirim ke <strong>' . $data_notif['staft'] . '</strong> bagian <strong>' . $data_notif['owner'] . '</strong>';
				if( ! $data_notif['staft'] || ! $data_notif['owner'] )
				{
					$data_notif = [];
					$ket        = 'Risk Context ini belum memiliki Owner atau officer yang akan melakukan approval';
				}
			}
		}

		$data['lanjut']     = $data_notif;
		$data['poin_start'] = $data_notif_asli;
		$data['id']         = $id;
		$x['notif']         = json_encode( $data_notif );
		$x['ket']           = $ket;
		$x['id']            = $id;
		$x['alur']          = json_encode( $alur );
		$data['hidden']     = $x;

		$regis           = $this->data->get_data_register( $id );
		$regis['id']     = $id;
		$regis['export'] = FALSE;

		$regis['notes']      = TRUE;
		$regis['poin_start'] = $data_notif_asli;
		$regis['hidden']     = $x;
		$cbominggu           = $this->data->get_data_minggu( $regis['parent']['term_id'] );
		$minggu              = ( $regis['parent']['minggu_id'] ) ? $cbominggu[$regis['parent']['minggu_id']] : '';

		$regis['parent']['bulan'] = ( isset( $this->term[$regis['parent']['term_id']] ) ) ? $this->term[$regis['parent']['term_id']] . ' - ' . $minggu : '-';
		$data['regis']            = $this->load->view( 'risk_context/register', $regis, TRUE );

		$hasil         = $this->load->view( 'propose', $data, TRUE );
		$configuration = [
		 'show_title_header'  => FALSE,
		 'show_action_button' => FALSE,
		 'box_content'        => FALSE,
		];

		$this->default_display( [ 'content' => $hasil, 'configuration' => $configuration ] );
	}

	function simpan_propose()
	{
		$post = $this->input->post();

		$alur      = json_decode( $post['alur'], TRUE );
		$notif     = json_decode( $post['notif'], TRUE );
		$sts_final = 0;
		if( count( $alur ) == $notif['urut'] )
		{
			$sts_final = 1;
		}
		$alur[$notif['urut'] - 1]['tanggal'] = date( 'Y-m-d H:i:s' );

		$this->crud->crud_table( _TBL_RCSA );
		$this->crud->crud_type( 'edit' );
		$this->crud->crud_field( 'status_revisi', 0, 'int' );
		$this->crud->crud_field( 'status_id', $notif['urut'], 'int' );
		$this->crud->crud_field( 'note_propose', $post['note_propose'] );
		$this->crud->crud_field( 'param_approval', json_encode( $alur ) );
		$this->crud->crud_field( 'tgl_propose', date( 'Y-m-d H:i:s' ), 'datetime' );
		$this->crud->crud_where( [ 'field' => 'id', 'value' => $post['id'] ] );
		$this->crud->process_crud();

		$this->crud->crud_table( _TBL_LOG_APPROVAL );
		$this->crud->crud_type( 'add' );
		$this->crud->crud_field( 'rcsa_id', $post['id'], 'int' );
		$this->crud->crud_field( 'keterangan', 'Propose ke ' . $notif['level'] );
		$this->crud->crud_field( 'note', $post['note_propose'] );
		$this->crud->crud_field( 'tanggal', date( 'Y-m-d H:i:s' ), 'datetime' );
		$this->crud->crud_field( 'user_id', $this->ion_auth->get_user_id() );
		$this->crud->crud_field( 'penerima_id', $notif['staft_no'] );
		$this->crud->process_crud();

		foreach( $post['rcsa_detail_id'] as $row )
		{
			$note = [];
			foreach( $alur as $key => $al )
			{
				if( isset( $post[$row . '_' . $al['level_approval_id']] ) )
				{
					$note[$al['level_approval_id']]['name'] = $al['level'];
					$note[$al['level_approval_id']]['note'] = $post[$row . '_' . $al['level_approval_id']];
				}
			}
			if( $note )
			{
				$this->crud->crud_table( _TBL_RCSA_DETAIL );
				$this->crud->crud_type( 'edit' );
				$this->crud->crud_field( 'note_approval', json_encode( $note ) );
				$this->crud->crud_where( [ 'field' => 'id', 'value' => $row ] );
				$this->crud->process_crud();
			}
		}
		$email       = explode( ',', preg_replace( '/\s+/', '', $notif['email'] ) );
		$emailFilter = array_values( array_filter( $email, function ($value)
		{
			return ! is_null( $value ) && $value !== '';
		} ) );

		$email       = explode( ',', preg_replace( '/\s+/', '', $notif['email'] ) );
		$emailFilter = array_values( array_filter( $email, function ($value)
		{
			return ! is_null( $value ) && $value !== '';
		} ) );

		if( count( $emailFilter ) > 0 )
		{
			$datasOutbox = [
			 'recipient' => $emailFilter,
			];

			$content_replace = [
			 '[[konteks]]' => 'Konteks Risiko',
			 '[[redir]]'   => 1,
			 '[[id]]'      => $post['id'],
			 '[[notif]]'   => $notif['staft'],
			 '[[sender]]'  => $this->session->userdata( 'data_user' )['real_name'],
			 '[[link]]'    => base_url() . "approval-bk",
			 '[[footer]]'  => $this->session->userdata( 'preference-0' )['nama_kantor'],

			];
			if( $this->session->userdata( 'preference-0' )['send_notif'] == 1 )
			{
				$this->load->library( 'outbox' );
				$this->outbox->setTemplate( 'NOTIF01' );
				$this->outbox->setParams( $content_replace );
				$this->outbox->setDatas( $datasOutbox );
				$this->outbox->send();
			}
		}

		header( 'location:' . base_url( _MODULE_NAME_ ) );
	}

	function data_alur( $param = [] )
	{
		$rows    = $this->db->where( 'id', $param['owner_no'] )->get( _TBL_VIEW_OWNER_PARENT )->row_array();
		$owner   = [];
		$officer = [];
		if( $rows )
		{
			if( ! empty( $rows['level_approval'] ) )
			{
				$level = explode( ',', $rows['level_approval'] );
				foreach( $level as $x )
				{
					$owner[$x]   = [ 'id' => $rows['id'], 'name' => $rows['parent_name'] ];
					$officer[$x] = $rows['id'];
				}
			}
			if( ! empty( $rows['level_approval_1'] ) )
			{
				$level = explode( ',', $rows['level_approval_1'] );
				foreach( $level as $x )
				{
					$owner[$x]   = [ 'id' => $rows['lv_1_id'], 'name' => $rows['lv_1_name'] ];
					$officer[$x] = $rows['lv_1_id'];
				}
			}
			if( ! empty( $rows['level_approval_2'] ) )
			{
				$level = explode( ',', $rows['level_approval_2'] );
				foreach( $level as $x )
				{
					$owner[$x]   = [ 'id' => $rows['lv_2_id'], 'name' => $rows['lv_2_name'] ];
					$officer[$x] = $rows['lv_2_id'];
				}
			}
			if( ! empty( $rows['level_approval_3'] ) )
			{
				$level = explode( ',', $rows['level_approval_3'] );
				foreach( $level as $x )
				{
					$owner[$x]   = [ 'id' => $rows['lv_3_id'], 'name' => $rows['lv_3_name'] ];
					$officer[$x] = $rows['lv_3_id'];
				}
			}
		}
		// dumps($owner);
		// dumps($officer);
		$staft_tahu   = [];
		$staft_setuju = [];
		$staft_valid  = [];
		if( $officer )
		{
			$rows = $this->db->where_in( 'owner_no', $officer )->group_start()->where( 'sts_mengetahui', 1 )->or_where( 'sts_menyetujui', 1 )->or_where( 'sts_menvalidasi', 1 )->group_end()->get( _TBL_VIEW_OFFICER )->result_array();
			foreach( $rows as $row )
			{
				if( $row['sts_mengetahui'] == 1 )
				{
					$staft_tahu[$row['owner_no']]['name'][]  = $row['officer_name'];
					$staft_tahu[$row['owner_no']]['id'][]    = $row['id'];
					$staft_tahu[$row['owner_no']]['email'][] = $row['email'];
				}
				elseif( $row['sts_menyetujui'] == 1 )
				{
					$staft_setuju[$row['owner_no']]['name'][]  = $row['officer_name'];
					$staft_setuju[$row['owner_no']]['id'][]    = $row['id'];
					$staft_setuju[$row['owner_no']]['email'][] = $row['email'];
				}
				elseif( $row['sts_menvalidasi'] == 1 )
				{
					$staft_valid[$row['owner_no']]['name'][]  = $row['officer_name'];
					$staft_valid[$row['owner_no']]['id'][]    = $row['id'];
					$staft_valid[$row['owner_no']]['email'][] = $row['email'];
				}
			}
		}
		// dumps($staft);
		$rows          = $this->db->where( 'id', $param['ass_type_no'] )->get( _TBL_COMBO )->row_array();
		$type_approval = 0;
		if( $rows )
		{
			$type_approval = intval( $rows['pid'] );
		}
		// dumps($owner);
		// dumps($staft);
		$rows    = $this->db->select( "'' as staft, '' as bagian, " . _TBL_VIEW_APPROVAL . ".*" )->where( 'pid', $type_approval )->order_by( 'urut' )->get( _TBL_VIEW_APPROVAL )->result_array();
		$alur[0] = [ 'level' => 'Risk Officer', 'owner' => '', 'staft' => '', 'level_approval_id' => 0, 'owner_no' => 0, 'staft_no' => 0, 'urut' => 0, 'sts_last' => 0, 'email' => '', 'tanggal' => '', 'sts_monit' => 0 ];
		foreach( $rows as $row )
		{
			// dumps($row);
			$prm   = json_decode( $row['param_text'], TRUE );
			$ow    = '';
			$ow_id = '';
			$of    = '';
			$of_id = '';
			$email = '';
			if( intval( $prm['tipe_approval'] ) == 0 )
			{
				if( array_key_exists( $row['param_int'], $owner ) )
				{
					$ow    = $owner[$row['param_int']]['name'];
					$ow_id = $owner[$row['param_int']]['id'];
					if( $prm['level_approval'] == 1 )
					{
						if( array_key_exists( $owner[$row['param_int']]['id'], $staft_tahu ) )
						{
							$of    = implode( ', ', $staft_tahu[$owner[$row['param_int']]['id']]['name'] );
							$of_id = implode( ', ', $staft_tahu[$owner[$row['param_int']]['id']]['id'] );
							$email = implode( ', ', $staft_tahu[$owner[$row['param_int']]['id']]['email'] );
						}
					}
					elseif( $prm['level_approval'] == 2 )
					{
						if( array_key_exists( $owner[$row['param_int']]['id'], $staft_setuju ) )
						{
							$of    = implode( ', ', $staft_setuju[$owner[$row['param_int']]['id']]['name'] );
							$of_id = implode( ', ', $staft_setuju[$owner[$row['param_int']]['id']]['id'] );
							$email = implode( ', ', $staft_setuju[$owner[$row['param_int']]['id']]['email'] );
						}
					}
					elseif( $prm['level_approval'] == 3 )
					{
						if( array_key_exists( $owner[$row['param_int']]['id'], $staft_valid ) )
						{
							$of    = implode( ', ', $staft_valid[$owner[$row['param_int']]['id']]['name'] );
							$of_id = implode( ', ', $staft_valid[$owner[$row['param_int']]['id']]['id'] );
							$email = implode( ', ', $staft_valid[$owner[$row['param_int']]['id']]['email'] );
						}
					}
				}
			}
			elseif( intval( $prm['tipe_approval'] ) == 1 )
			{
				$arr_free = $this->db->where_in( 'level_approval', $row['param_int'] )->get( _TBL_OWNER )->row_array();
				if( $arr_free )
				{
					$ow        = $arr_free['owner_name'];
					$ow_id     = $arr_free['id'];
					$of_arr    = [];
					$of_id_arr = [];
					$email_arr = [];
					$arr_free  = $this->db->where( 'owner_no', $ow_id )->group_start()->where( 'sts_mengetahui', 1 )->or_where( 'sts_menyetujui', 1 )->or_where( 'sts_menvalidasi', 1 )->group_end()->get( _TBL_VIEW_OFFICER )->result_array();
					if( $arr_free )
					{
						foreach( $arr_free as $fr )
						{
							if( $prm['level_approval'] == 1 && $fr['sts_mengetahui'] == 1 )
							{
								$of_arr[]    = $fr['officer_name'];
								$of_id_arr[] = $fr['id'];
								$email_arr[] = $fr['email'];
							}
							elseif( $prm['level_approval'] == 2 && $fr['sts_menyetujui'] == 1 )
							{
								$of_arr[]    = $fr['officer_name'];
								$of_id_arr[] = $fr['id'];
								$email_arr[] = $fr['email'];
							}
							elseif( $prm['level_approval'] == 3 && $fr['sts_menvalidasi'] == 1 )
							{
								$of_arr[]    = $fr['officer_name'];
								$of_id_arr[] = $fr['id'];
								$email_arr[] = $fr['email'];
							}
						}
						$of    = implode( ', ', $of_arr );
						$of_id = implode( ', ', $of_id_arr );
						$email = implode( ', ', $email_arr );
					}
				}
			}
			$alur[$row['urut']] = [ 'level' => $row['model'], 'owner' => $ow, 'staft' => $of, 'level_approval_id' => $row['id'], 'owner_no' => $ow_id, 'staft_no' => $of_id, 'urut' => $row['urut'], 'sts_last' => $row['sts_last'], 'email' => $email, 'tanggal' => '', 'sts_monit' => $prm['monit'], 'sts_notif' => $prm['notif_email'] ];
		}
		return $alur;
	}

	function indikator_like( $post = [] )
	{
		if( ! $post )
		{
			$post = $this->input->post();

			$rcsa_detail_no = $post['rcsa_detail_no'];
			$bk_tipe        = $post['bk_tipe'];
			if( ! empty( $post['id_kpi'] ) )
			{

				$this->db->select( 'id' );
				$this->db->where( 'kelompok', 'kri' );
				// $this->db->where('param_int', 1);
				$this->db->where( 'param_other_int', $post['id_kpi'] );
				$this->db->where( 'active', 1 );
				$kri = $this->db->get( _TBL_COMBO )->result_array();
				$cek = $this->db->where( 'bk_tipe', $post['bk_tipe'] )->where( 'rcsa_detail_id', $post['rcsa_detail_no'] )->get( _TBL_VIEW_RCSA_DET_LIKE_INDI )->result_array();


				if( count( $cek ) == 0 )
				{
					foreach( $kri as $key => $value )
					{
						$data['id']             = 0;
						$data['rcsa_detail_no'] = $post['rcsa_detail_no'];
						$data['bk_tipe']        = $post['bk_tipe'];
						$data['kri_id']         = $value['id'];
						$data['satuan_id']      = 0;
						$data['pembobotan']     = 0;
						$data['p_1']            = 0;
						$data['s_1_min']        = 0;
						$data['s_1_max']        = 0;
						$data['p_4']            = 0;
						$data['s_4_min']        = 0;
						$data['s_4_max']        = 0;
						$data['p_2']            = 0;
						$data['s_2_min']        = 0;
						$data['s_2_max']        = 0;
						$data['p_5']            = 0;
						$data['s_5_min']        = 0;
						$data['s_5_max']        = 0;
						$data['p_3']            = 0;
						$data['s_3_min']        = 0;
						$data['s_3_max']        = 0;
						$data['score']          = 0;
						$data['dampak_id']      = $post['dampak_id'];
						$this->data->simpan_like_indi( $data );
					}
				}
				else
				{
					$value = $cek[0];
					if( $value )
					{
						$this->db->select( 'param_other_int' );
						$this->db->where( 'kelompok', 'kri' );
						// $this->db->where('param_int', 1);
						$this->db->where( 'id', $value['kri_id'] );
						$this->db->where( 'active', 1 );
						$kriCek = $this->db->get( _TBL_COMBO )->row_array();

						if( $kriCek )
						{
							if( $kriCek['param_other_int'] != $post['id_kpi'] )
							{
								$this->db->delete( _TBL_RCSA_DET_LIKE_INDI, array( 'rcsa_detail_id' => $post['rcsa_detail_no'], 'bk_tipe' => $post['bk_tipe'] ) );
								foreach( $kri as $key => $valuex )
								{
									$data['id']             = 0;
									$data['rcsa_detail_no'] = $rcsa_detail_no;
									$data['bk_tipe']        = $bk_tipe;
									$data['kri_id']         = $valuex['id'];
									$data['satuan_id']      = 0;
									$data['pembobotan']     = 0;
									$data['p_1']            = 0;
									$data['s_1_min']        = 0;
									$data['s_1_max']        = 0;
									$data['p_4']            = 0;
									$data['s_4_min']        = 0;
									$data['s_4_max']        = 0;
									$data['p_2']            = 0;
									$data['s_2_min']        = 0;
									$data['s_2_max']        = 0;
									$data['p_5']            = 0;
									$data['s_5_min']        = 0;
									$data['s_5_max']        = 0;
									$data['p_3']            = 0;
									$data['s_3_min']        = 0;
									$data['s_3_max']        = 0;
									$data['score']          = 0;
									$data['dampak_id']      = $post['dampak_id'];
									$this->data->simpan_like_indi( $data );
								}
							}
						}
					}
				}
			}

			$post['hasil'] = $this->data->update_list_indi_like( [ 'rcsa_detail_no' => $post['rcsa_detail_no'], 'bk_tipe' => $post['bk_tipe'], 'dampak_id' => $post['dampak_id'] ] );
		}


		$data['param'] = $post['hasil'];

		/**list table Indikator Likelihood */

		$data['list_like_indi'] = $this->db->where( 'bk_tipe', $post['bk_tipe'] )->where( 'rcsa_detail_id', intval( $post['rcsa_detail_no'] ) )->or_group_start()->where( 'rcsa_detail_id', 0 )->where( 'created_by', $this->ion_auth->get_user_name() )->group_end()->get( _TBL_VIEW_RCSA_DET_LIKE_INDI )->result_array();

		/**------- ------- ------ */

		$data['parent'] = $post['rcsa_detail_no'];

		if( $post['bk_tipe'] == 1 )
		{
			$data['sub_title'] = ' Inheren';
			$data['title']     = ' Inheren';
			$result['combo']   = $this->load->view( 'indikator-like', $data, TRUE );
		}
		elseif( $post['bk_tipe'] == 2 )
		{
			$data['sub_title'] = ' Current';
			$result['combo']   = $this->load->view( 'indikator-like-residual', $data, TRUE );
		}
		elseif( $post['bk_tipe'] == 3 )
		{
			$data['sub_title'] = ' Residual';
			$result['combo']   = $this->load->view( 'indikator-like-target', $data, TRUE );
		}

		header( 'Content-type: application/json' );
		echo json_encode( $result );
	}

	// function add likelihod 
	function indikator_like_add()
	{
		$post          = $this->input->post();
		$data['param'] = $post;
		// $kpi = ($post['id_kpi'])?$post['id_kpi']:'1=1';
		$this->cboKri = $this->crud->combo_select( [ 'id', 'data' ] )->combo_where( 'kelompok', 'kri' )->combo_where( 'param_other_int', $post['id_kpi'] )->combo_where( 'active', 1 )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();
		// ->combo_where( 'param_int', 1 )

		$this->cboSatuan = $this->crud->combo_select( [ 'id', 'data' ] )->combo_where( 'kelompok', 'satuan' )->combo_where( 'active', 1 )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();

		$rows = $this->db->where( 'rcsa_detail_id', intval( $post['rcsa_detail_no'] ) )->get( _TBL_VIEW_RCSA_DET_LIKE_INDI )->result_array();
		$mak  = 100;
		foreach( $rows as $row )
		{
			$mak += floatval( $row['pembobotan'] );
		}
		$mit = $this->db->where( 'id', intval( $post['id'] ) )->get( _TBL_VIEW_RCSA_DET_LIKE_INDI )->row_array();
		if( $mit )
		{
			$mak += floatval( $mit['pembobotan'] );
		}
		$disabled = '';
		if( intval( $post['bk_tipe'] ) > 1 )
		{
			$disabled = ' disabled="disabled" ';
		}
		$pembobotan = '<div class="input-group" style="width:15% !important;">
			<button type="button" onclick="this.parentNode.querySelector(\'[type=number]\').stepDown();">
				-
			</button>';

		$pembobotan .= form_input( array( 'type' => 'number', 'name' => 'pembobotan' ), ( $mit ) ? $mit['pembobotan'] : '', " class='form-control touchspin-postfix text-center'  " . $disabled . " max='" . $mak . "' min='" . ( $mak * -1 ) . "' step='1' id='pembobotan' " );

		$pembobotan .= '<button type="button" onclick="this.parentNode.querySelector(\'[type=number]\').stepUp();">
				+
			</button>
			</div>';
		// inputan likelihod view
		$data['like'][] = [ 'title' => _l( 'fld_kri' ), 'help' => _h( 'help_kri' ), 'add' => FALSE, 'isi' => form_dropdown( 'kri_id', $this->cboKri, ( $mit ) ? $mit['kri_id'] : '', 'class="form-control select" ' . $disabled . ' id="kri_id"' ) ];
		$data['like'][] = [ 'title' => _l( 'fld_pembobotan' ), 'help' => _h( 'help_pembobotan' ), 'isi' => $pembobotan ];
		$data['like'][] = [ 'title' => _l( 'fld_satuan' ), 'help' => _h( 'help_satuan' ), 'isi' => form_dropdown( 'satuan_id', $this->cboSatuan, ( $mit ) ? $mit['satuan_id'] : '', 'class="form-control select" ' . $disabled . ' id="satuan_id"' ) ];

		$data['like'][] = [ 'title' => '<a style="height:1.4rem;width:1.4rem;background-color:#2c5b29" class="btn bg-successx-400 rounded-round btn-icon btn-sm" ><span class="letter-icon"></span></a>', 'help' => _h( 'help_pencapaian' ), 'isi' => form_input( 'p_1', ( $mit ) ? $mit['p_1'] : '', 'class="form-control" ' . $disabled . ' id="p_1" placeholder="' . _l( 'fld_pencapaian' ) . '" style="width:50%"' ) . '&nbsp;&nbsp;&nbsp;' . form_input( 's_1_min', ( $mit ) ? $mit['s_1_min'] : '', 'class="form-control text-center" ' . $disabled . ' id="s_1_min" placeholder="' . _l( 'fld_min_satuan' ) . '" style="width:10%"' ) . '&nbsp;&nbsp;&nbsp;<span class="input-group-text"> - </span>&nbsp;&nbsp;&nbsp;' . form_input( 's_1_max', ( $mit ) ? $mit['s_1_max'] : '', 'class="form-control text-center" ' . $disabled . ' id="s_1_max" placeholder="' . _l( 'fld_mak_satuan' ) . '" style="width:10%"' ) . ' <span class="input-group-text"> Satuan </span> ' ];

		$data['like'][] = [ 'title' => '<a style="height:1.4rem;width:1.4rem;background-color:#50ca4e;" class="btn bg-orangex-400 rounded-round btn-icon btn-sm"><span class="letter-icon"></span></a>', 'help' => _h( 'help_pencapaian_4' ), 'isi' => form_input( 'p_4', ( $mit ) ? $mit['p_4'] : '', 'class="form-control" ' . $disabled . ' id="p_4" placeholder="' . _l( 'fld_pencapaian' ) . '" style="width:50%"' ) . '&nbsp;&nbsp;&nbsp;' . form_input( 's_4_min', ( $mit ) ? $mit['s_4_min'] : '', 'class="form-control text-center" ' . $disabled . ' id="s_4_min" placeholder="' . _l( 'fld_min_satuan' ) . '" style="width:10%"' ) . '&nbsp;&nbsp;&nbsp;<span class="input-group-text"> - </span>&nbsp;&nbsp;&nbsp;' . form_input( 's_4_max', ( $mit ) ? $mit['s_4_max'] : '', 'class="form-control text-center" ' . $disabled . ' id="s_4_max" placeholder="' . _l( 'fld_mak_satuan' ) . '" style="width:10%"' ) . ' <span class="input-group-text"> Satuan </span> ' ];

		$data['like'][] = [ 'title' => '<a style="height:1.4rem;width:1.4rem;background-color:#edfd17;" class="btn bg-dangerx-400 rounded-round btn-icon btn-sm"><span class="letter-icon"></span></a>', 'help' => _h( 'help_pencapaian_2' ), 'isi' => form_input( 'p_2', ( $mit ) ? $mit['p_2'] : '', 'class="form-control" ' . $disabled . ' id="p_2" placeholder="' . _l( 'fld_pencapaian' ) . '" style="width:50%"' ) . '&nbsp;&nbsp;&nbsp;' . form_input( 's_2_min', ( $mit ) ? $mit['s_2_min'] : '', 'class="form-control text-center" ' . $disabled . ' id="s_2_min" placeholder="' . _l( 'fld_min_satuan' ) . '" style="width:10%"' ) . '&nbsp;&nbsp;&nbsp;<span class="input-group-text"> - </span>&nbsp;&nbsp;&nbsp;' . form_input( 's_2_max', ( $mit ) ? $mit['s_2_max'] : '', 'class="form-control text-center" ' . $disabled . ' id="s_2_max" placeholder="' . _l( 'fld_mak_satuan' ) . '" style="width:10%"' ) . ' <span class="input-group-text"> Satuan </span> ' ];

		$data['like'][] = [ 'title' => '<a style="height:1.4rem;width:1.4rem;background-color:#f0ca0f;" class="btn bg-dangers-400 rounded-round btn-icon btn-sm"><span class="letter-icon"></span></a>', 'help' => _h( 'help_pencapaian_5' ), 'isi' => form_input( 'p_5', ( $mit ) ? $mit['p_5'] : '', 'class="form-control" ' . $disabled . ' id="p_5" placeholder="' . _l( 'fld_pencapaian' ) . '" style="width:50%"' ) . '&nbsp;&nbsp;&nbsp;' . form_input( 's_5_min', ( $mit ) ? $mit['s_5_min'] : '', 'class="form-control text-center" ' . $disabled . ' id="s_5_min" placeholder="' . _l( 'fld_min_satuan' ) . '" style="width:10%"' ) . '&nbsp;&nbsp;&nbsp;<span class="input-group-text"> - </span>&nbsp;&nbsp;&nbsp;' . form_input( 's_5_max', ( $mit ) ? $mit['s_5_max'] : '', 'class="form-control text-center" ' . $disabled . ' id="s_5_max" placeholder="' . _l( 'fld_mak_satuan' ) . '" style="width:10%"' ) . ' <span class="input-group-text"> Satuan </span> ' ];

		$data['like'][] = [ 'title' => '<a style="height:1.4rem;width:1.4rem;background-color:#e70808" class="btn bg-dangers-400 rounded-round btn-icon btn-sm"><span class="letter-icon"></span></a>', 'help' => _h( 'help_pencapaian_3' ), 'isi' => form_input( 'p_3', ( $mit ) ? $mit['p_3'] : '', 'class="form-control" ' . $disabled . ' id="p_3" placeholder="' . _l( 'fld_pencapaian' ) . '" style="width:50%"' ) . '&nbsp;&nbsp;&nbsp;' . form_input( 's_3_min', ( $mit ) ? $mit['s_3_min'] : '', 'class="form-control text-center" ' . $disabled . ' id="s_3_min" placeholder="' . _l( 'fld_min_satuan' ) . '" style="width:10%"' ) . '&nbsp;&nbsp;&nbsp;<span class="input-group-text"> - </span>&nbsp;&nbsp;&nbsp;' . form_input( 's_3_max', ( $mit ) ? $mit['s_3_max'] : '', 'class="form-control text-center" ' . $disabled . ' id="s_3_max" placeholder="' . _l( 'fld_mak_satuan' ) . '" style="width:10%"' ) . ' <span class="input-group-text"> Satuan </span> ' ];

		$data['like'][] = [ 'title' => _l( 'fld_score' ), 'help' => _h( 'help_score' ), 'isi' => '<div class="input-group" style="width:15%;text-align:center;">' . form_input( 'score', ( $mit ) ? $mit['score'] : '', 'class="form-control" id="score" placeholder="' . _l( 'fld_score' ) . '"' ) . '</div>' ];

		$result['combo'] = $this->load->view( 'input-indikator-like', $data, TRUE );
		header( 'Content-type: application/json' );
		echo json_encode( $result );
	}

	// proses simpan 
	function simpan_like_indi()
	{
		$post = $this->input->post();

		// model simpan 
		$hasil = $this->data->simpan_like_indi( $post );

		$id_detail = intval( $post['rcsa_detail_no'] );
		$this->indikator_like( [ 'id' => $hasil['id'], 'rcsa_detail_no' => $id_detail, 'hasil' => $hasil, 'bk_tipe' => $post['bk_tipe'] ] );
	}

	function delete_indikator_like()
	{
		$post = $this->input->post();
		$this->db->delete( _TBL_RCSA_DET_LIKE_INDI, [ 'id' => intval( $post['id'] ) ] );
		$id_detail = intval( $post['rcsa_detail_no'] );
		$dampak_id = intval( $post['dampak_id'] );
		$bk_tipe   = intval( $post['bk_tipe'] );
		$hasil     = $this->data->update_list_indi_like( [ 'rcsa_detail_no' => $id_detail, 'bk_tipe' => $bk_tipe, 'dampak_id' => $dampak_id ] );
		$this->indikator_like( [ 'id' => intval( $post['id'] ), 'rcsa_detail_no' => $id_detail, 'hasil' => $hasil, 'bk_tipe' => $bk_tipe ] );
	}

	function indikator_dampak()
	{
		$post                = $this->input->post();
		$data['parent']      = $post;
		$data['dampak_indi'] = [];
		$tipe_kri            = $this->crud->combo_select( [ 'id', 'data' ] )->combo_where( 'kelompok', 'tipe-kri' )->combo_where( 'active', 1 )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();
		$data['tipe_kri']    = form_dropdown( 'tipe_kri[]', $tipe_kri, '', 'class="form-control tipe_kri select" id="tipe_kri"' );
		$data['kri']         = form_dropdown( 'kri[]', [], '', 'class="form-control kri select" id="kri"' );
		$data['detail']      = form_input( 'detail[]', '', 'class="form-control detail_input" id="detail_input"' );

		$rows = $this->db->where( 'bk_tipe', $post['bk_tipe'] )->where( 'rcsa_detail_id', intval( $post['rcsa_detail_no'] ) )->or_group_start()->where( 'rcsa_detail_id', 0 )->where( 'created_by', $this->ion_auth->get_user_name() )->group_end()->get( _TBL_VIEW_RCSA_DET_DAMPAK_INDI )->result_array();

		if( $post['bk_tipe'] == 1 )
		{
			$disabeld          = '';
			$data['sub_title'] = 'Inheren';
		}
		else
		{
			$data['sub_title'] = 'Residual';
			$disabeld          = '';
			// $disabeld=' readonly="readonly" ';
		}

		foreach( $rows as &$row )
		{
			$tipe_kri            = $this->crud->combo_select( [ 'id', 'data' ] )->combo_where( 'kelompok', 'tipe-kri' )->combo_where( 'active', 1 )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();
			$kri                 = $this->crud->combo_select( [ 'id', 'concat(urut,\' - \',data) as data' ] )->combo_where( 'pid', $row['jenis_kri_id'] )->combo_where( 'param_int', 2 )->combo_where( 'active', 1 )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();
			$detail_input        = '';
			$row['cbo_kri']      = form_dropdown( 'kri[]', $kri, $row['kri_id'], 'class="form-control kri select" id="kri"' );
			$row['cbo_tipe_kri'] = form_dropdown( 'tipe_kri[]', $tipe_kri, $row['jenis_kri_id'], 'class="form-control tipe_kri select" ' . $disabeld . ' id="tipe_kri"' );
			$row['detail_input'] = form_input( 'detail[]', $row['detail'], 'class="form-control detail_input ' . $row['detail'] . '" ' . $disabeld . ' id="detail_input"' );
		}
		unset( $row );

		$data['list_dampak_indi'] = $rows;

		$result['combo'] = $this->load->view( 'input-indikator-dampak', $data, TRUE );
		header( 'Content-type: application/json' );
		echo json_encode( $result );
	}

	function simpan_dampak_indi()
	{
		$post  = $this->input->post();
		$hasil = $this->data->simpan_dampak_indi( $post );

		header( 'Content-type: application/json' );
		echo json_encode( $hasil );
	}

	function delete_indikator_dampak()
	{
		$post = $this->input->post();
		$this->db->delete( _TBL_RCSA_DET_DAMPAK_INDI, [ 'id' => intval( $post['id'] ) ] );
		header( 'Content-type: application/json' );
		echo json_encode( 1 );
	}

	function copy_data()
	{
		$id           = $this->input->post( 'id' );
		$data['rcsa'] = $this->db->where( 'id', $id )->get( _TBL_VIEW_RCSA )->row_array();

		$query    = "select id, concat(data, ' - [', param_date, ' s.d ', param_date_after ,']') as name from " . _TBL_COMBO . " where kelompok=? and param_date>? order by param_date";
		$rows     = $this->db->query( $query, [ 'term', $data['rcsa']['tgl_selesai_term'] ] )->result_array();
		$cbo_term = [];
		foreach( $rows as $row )
		{
			$cbo_term[$row['id']] = $row['name'];
		}
		$mingguId  = ( $data['rcsa']['minggu_id'] ) ? $data['rcsa']['minggu_id'] : 0;
		$cbominggu = $this->data->get_data_minggu( $mingguId );

		$data['cbo_term']    = $cbo_term;
		$data['cbo_minggu']  = $cbominggu;
		$data['periode']     = form_dropdown( 'periode_copy', $this->period, $data['rcsa']['period_id'], 'class="form-control" id="periode_copy"' );
		$data['term']        = form_dropdown( 'term_copy', $cbo_term, '', 'class="form-control" id="term_copy"' );
		$data['minggu']      = form_dropdown( 'minggu_copy', $cbominggu, '', 'class="form-control" id="minggu_copy"' );
		$data['id']          = form_hidden( [ 'id' => $id ] );
		$data['rcsa_detail'] = $this->db->where( 'rcsa_id', $id )->get( _TBL_VIEW_RCSA_DETAIL )->result_array();
		$field               = $this->load->view( 'copi', $data, TRUE );
		$hasil['combo']      = $field;

		header( 'Content-type: application/json' );
		echo json_encode( $hasil );
	}

	function proses_copy()
	{
		$data['pesan'] = 'Tidak ada Data yang dicopi';
		$data['sts']   = FALSE;
		$post          = $this->input->post();

		$fields['rcsa']            = [ 'type_ass_id', 'owner_id', 'sasaran_dept', 'ruang_lingkup', 'stakeholder_id', 'alat_metode_id' ];
		$fields['detail']          = [ 'id_kpi', 'aktifitas_id', 'sasaran_id', 'tahapan', 'klasifikasi_risiko_id', 'tipe_risiko_id', 'penyebab_id', 'peristiwa_id', 'dampak_id', 'risiko_dept', 'kode_risiko_dept', 'tipe_analisa_no', 'like_id', 'like_text', 'impact_id', 'impact_text', 'risiko_inherent', 'level_inherent', 'nama_kontrol', 'nama_kontrol_note', 'efek_kontrol', 'lampiran', 'aspek_risiko_id', 'like_residual_id', 'impact_residual_id', 'risiko_residual', 'level_residual', 'like_target_id', 'impact_target_id', 'risiko_target', 'level_target', 'treatment_id', 'note_approval', 'sts_save_evaluasi' ];
		$fields['mitigasi']        = [ 'mitigasi', 'batas_waktu', 'biaya', 'penanggung_jawab_id', 'koordinator_id', 'status_jangka' ];
		$fields['mitigasi_detail'] = [ 'aktifitas_mitigasi', 'batas_waktu_detail', 'penanggung_jawab_detail_id', 'koordinator_detail_id', 'target', 'aktual' ];
		$fields['dampak_indi']     = [ 'bk_tipe', 'kri_id' ];
		$fields['like_indi']       = [ 'bk_tipe', 'kri_id', 'satuan_id', 'pembobotan', 'p_1', 's_1_min', 's_1_max', 'p_4', 's_4_min', 's_4_max', 'p_2', 's_2_min', 's_2_max', 'p_5', 's_5_min', 's_5_max', 'p_3', 's_3_min', 's_3_max', 'score', 'param' ];

		$rows = $this->db->where( 'parent_id', $post['id'] )->where( 'period_id', $post['periode'] )->where( 'term_id', $post['term'] )->where( 'minggu_id', $post['minggu'] )->get( _TBL_RCSA )->row_array();

		if( $rows )
		{
			$data['pesan'] = "Data Periode : " . $rows['period_name'] . ' kwartal : ' . $rows['term'] . ' sudah ada dalam database';
			$data['sts']   = FALSE;
		}
		else
		{
			$rows = $this->db->where( 'id', $post['id'] )->get( _TBL_RCSA )->row_array();

			if( $rows )
			{
				$this->db->trans_begin();
				$this->crud->crud_table( _TBL_RCSA );
				$this->crud->crud_type( 'add' );
				foreach( $fields['rcsa'] as $nil )
				{
					$this->crud->crud_field( $nil, $rows[$nil] );
				}
				$this->crud->crud_field( 'parent_id', $post['id'] );
				$this->crud->crud_field( 'period_id', $post['periode'] );
				$this->crud->crud_field( 'term_id', $post['term'] );
				$this->crud->crud_field( 'minggu_id', $post['minggu'] );
				$this->crud->process_crud();
				$rcsa_id = $this->crud->last_id();

				$rcsa_detail_id     = [];
				$rcsa_detail_id_new = [];
				$rows               = $this->db->where( 'rcsa_id', $post['id'] )->get( _TBL_RCSA_DETAIL )->result_array();
				if( $rows )
				{
					foreach( $rows as $row )
					{
						$this->crud->crud_table( _TBL_RCSA_DETAIL );
						$this->crud->crud_type( 'add' );
						foreach( $fields['detail'] as $nil )
						{
							$this->crud->crud_field( $nil, $row[$nil] );
						}
						$this->crud->crud_field( 'rcsa_id', $rcsa_id );
						$this->crud->process_crud();
						$id                             = $this->crud->last_id();
						$rcsa_detail_id[$row['id']]     = $row['id'];
						$rcsa_detail_id_new[$row['id']] = $id;
					}

					$rows = $this->db->where_in( 'rcsa_detail_id', $rcsa_detail_id )->get( _TBL_RCSA_DET_DAMPAK_INDI )->result_array();
					if( $rows )
					{
						foreach( $rows as $row )
						{
							$this->crud->crud_table( _TBL_RCSA_DET_DAMPAK_INDI );
							$this->crud->crud_type( 'add' );
							foreach( $fields['dampak_indi'] as $nil )
							{
								$this->crud->crud_field( $nil, $row[$nil] );
							}
							$this->crud->crud_field( 'rcsa_detail_id', $rcsa_detail_id_new[$row['rcsa_detail_id']] );
							$this->crud->process_crud();
						}
					}

					$rows = $this->db->where_in( 'rcsa_detail_id', $rcsa_detail_id )->get( _TBL_RCSA_DET_LIKE_INDI )->result_array();
					if( $rows )
					{
						foreach( $rows as $row )
						{
							$this->crud->crud_table( _TBL_RCSA_DET_LIKE_INDI );
							$this->crud->crud_type( 'add' );
							foreach( $fields['like_indi'] as $nil )
							{
								$this->crud->crud_field( $nil, $row[$nil] );
							}
							$this->crud->crud_field( 'rcsa_detail_id', $rcsa_detail_id_new[$row['rcsa_detail_id']] );
							$this->crud->process_crud();
						}
					}

					$rcsa_mitigasi_id     = [];
					$rcsa_mitigasi_id_new = [];
					$rows                 = $this->db->where_in( 'rcsa_detail_id', $rcsa_detail_id )->get( _TBL_RCSA_MITIGASI )->result_array();
					if( $rows )
					{
						foreach( $rows as $row )
						{
							$this->crud->crud_table( _TBL_RCSA_MITIGASI );
							$this->crud->crud_type( 'add' );
							foreach( $fields['mitigasi'] as $nil )
							{
								$this->crud->crud_field( $nil, $row[$nil] );
							}
							$this->crud->crud_field( 'rcsa_detail_id', $rcsa_detail_id_new[$row['rcsa_detail_id']] );
							$this->crud->process_crud();
							$id                               = $this->crud->last_id();
							$rcsa_mitigasi_id[$row['id']]     = $row['id'];
							$rcsa_mitigasi_id_new[$row['id']] = $id;
						}

						$rows = $this->db->where_in( 'rcsa_mitigasi_id', $rcsa_mitigasi_id )->get( _TBL_RCSA_MITIGASI_DETAIL )->result_array();
						if( $rows )
						{
							foreach( $rows as $row )
							{
								$this->crud->crud_table( _TBL_RCSA_MITIGASI_DETAIL );
								$this->crud->crud_type( 'add' );
								foreach( $fields['mitigasi_detail'] as $nil )
								{
									$this->crud->crud_field( $nil, $row[$nil] );
								}
								$this->crud->crud_field( 'rcsa_mitigasi_id', $rcsa_mitigasi_id_new[$row['rcsa_mitigasi_id']] );
								$this->crud->process_crud();
								$id = $this->crud->last_id();
							}
						}
					}
				}
				$this->db->trans_commit();
				$data['pesan'] = 'Data berhasil dicopi';
				$data['sts']   = TRUE;
			}
		}

		header( 'Content-type: application/json' );
		echo json_encode( $data );
	}

	function reset_approval()
	{
		$post  = $this->input->post();
		$hasil = [];

		$this->crud->crud_table( _TBL_RCSA );
		$this->crud->crud_type( 'edit' );
		$this->crud->crud_field( 'status_revisi', 0, 'int' );
		$this->crud->crud_field( 'status_id', 0, 'int' );
		$this->crud->crud_field( 'status_final', 0, 'int' );

		$this->crud->crud_field( 'note_propose', NULL );
		$this->crud->crud_field( 'param_approval', NULL );
		$this->crud->crud_field( 'tgl_propose', NULL );
		$this->crud->crud_where( [ 'field' => 'id', 'value' => $post['id'] ] );
		$this->crud->process_crud();

		$this->crud->crud_table( _TBL_LOG_APPROVAL );
		$this->crud->crud_type( 'add' );
		$this->crud->crud_field( 'rcsa_id', $post['id'], 'int' );
		$this->crud->crud_field( 'keterangan', 'Approval dibatalkan oleh Admin' );
		$this->crud->crud_field( 'note', 'Approval dibatalkan oleh Admin' );
		$this->crud->crud_field( 'tanggal', date( 'Y-m-d H:i:s' ), 'datetime' );
		$this->crud->crud_field( 'user_id', $this->ion_auth->get_user_id() );
		$this->crud->crud_field( 'penerima_id', 0 );
		$this->crud->process_crud();

		header( 'Content-type: application/json' );
		echo json_encode( $hasil );
	}

	function optionalButton( $button, $mode )
	{
		if( $mode == 'list' )
		{
			// unset($button['delete']);
			// unset($button['print']);
			// unset($button['search']);



			$button['print']['detail']['lap'] = [
			 'label' => 'Excel Register',
			 'color' => 'bg-green',
			 'id'    => 'btn_lap',
			 'tag'   => 'a',
			 'round' => ( $this->configuration['round_button'] ) ? 'rounded-round' : '',
			 'icon'  => 'icon-import',
			 'url'   => '#!',

			 'attr'  => ' target="" data-url="' . base_url( _MODULE_NAME_ . '/export-lap/' ) . '"',
			 'align' => 'left',
			];

			$button['print']['detail']['lap-sum'] = [
			 'label' => 'Excel Register (Summary)',
			 'color' => 'bg-green',
			 'id'    => 'btn_lap_sum',
			 'tag'   => 'a',
			 'round' => ( $this->configuration['round_button'] ) ? 'rounded-round' : '',
			 'icon'  => 'icon-import',
			 'url'   => '#!',

			 'attr'  => ' target="" data-url="' . base_url( _MODULE_NAME_ . '/export-lap-summary/' ) . '"',
			 'align' => 'left',
			];
		}

		return $button;
	}


	function optionalPersonalButton( $button, $row )
	{
		$button['identifikasi'] = [
		 'label' => 'Identifikasi Risiko',
		 'id'    => 'btn_schedule_one',
		 'class' => 'text-success',
		 'icon'  => 'icon-file-spreadsheet ',
		 'url'   => base_url( _MODULE_NAME_ . '/identifikasi-risiko/' ),
		 'attr'  => ' target="_self" ',
		];
		if( $row['status_id'] == 0 )
		{
			$button['propose'] = [
			 'label' => 'Propose Risiko',
			 'id'    => 'btn_propose_one',
			 'class' => 'text-warning',
			 'icon'  => 'icon-file-spreadsheet ',
			 'url'   => base_url( _MODULE_NAME_ . '/propose-risiko/' ),
			 'attr'  => ' target="_self" ',
			];
		}
		if( $row['status_final'] == 1 )
		{
			$button['copy'] = [
			 'label' => 'Salin Data',
			 'id'    => 'btn_copy_one',
			 'class' => 'text-info copy-data',
			 'icon'  => 'icon-paste ',
			 'url'   => base_url( _MODULE_NAME_ . '/copy-data/' ),
			 'attr'  => [ 'data-id' => $row['id'], 'data-url' => 'copy-data' ],
			 'type'  => 'span',
			];
		}
		if( $row['status_id'] >= 1 )
		{
			if( $this->super_user )
			{
				$button['reset'] = [
				 'label' => 'Reset Approval',
				 'id'    => 'btn_reset_one',
				 'class' => 'text-warning reset-approval',
				 'icon'  => 'icon-paste ',
				 'url'   => base_url( _MODULE_NAME_ . '/reset-approval/' ),
				 'attr'  => [ 'data-id' => $row['id'], 'data-url' => 'reset-approval' ],
				 'type'  => 'span',
				];
			}
		}

		$v1 = intval( $row['status_final'] );
		$v2 = 0;
		if( array_key_exists( $row['id'], $this->has_detail ) )
		{
			$v1 = intval( $this->has_detail[$row['id']] );
		}
		if( $v1 > 0 || $v2 > 0 )
		{
			unset( $button['delete'] );
		}
		return $button;
	}


	function cetak_register( $id )
	{
		$data           = $this->data->get_data_register( $id );
		$data['id']     = $id;
		$data['export'] = FALSE;
		$cbominggu      = $this->data->get_data_minggu( $data['parent']['term_id'] );
		$minggu         = ( $data['parent']['minggu_id'] ) ? $cbominggu[$data['parent']['minggu_id']] : '';

		$data['parent']['bulan'] = $this->term[$data['parent']['term_id']] . ' - ' . $minggu;
		$hasil                   = $this->load->view( 'risk_context/register', $data, TRUE );
		$n                       = $data['parent']['kode_dept'] . '-' . $data['parent']['term'] . '-' . $data['parent']['period_name'];


		$cetak   = 'register_excel';
		$nm_file = 'Laporan-Risk-Register-' . $n;
		$this->$cetak( $hasil, $nm_file );
	}

	function register_excel( $data, $nm_file )
	{
		header( "Content-type:appalication/vnd.ms-excel" );
		header( "content-disposition:attachment;filename=" . $nm_file . ".xls" );

		$html = $data;
		echo $html;
		exit;
	}

	function export_lap()
	{
		$post   = explode( ",", $this->input->post( 'id' ) );
		$result = [];
		foreach( $post as $key => $value )
		{
			$result[] = base_url( '/risk-context/cetak-register/' . $value );
		}

		header( 'Content-type: application/json' );
		echo json_encode( $result );
	}

	function export_lap_summary()
	{
		$post   = explode( ",", $this->input->post( 'id' ) );
		$result = "";
		foreach( $post as $key => $value )
		{
			$result .= $this->cetak_register_sum( $value );
		}
		// dumps($result);
		// die();
		$nm_file = 'Laporan-Risk-Register-Summary';
		$cetak   = 'register_excel';
		header( "Content-type:appalication/vnd.ms-excel" );
		header( "content-disposition:attachment;filename=" . $nm_file . ".xls" );

		$html = $result;
		echo $html;
		exit;
		header( 'Content-type: application/json' ); // 
		echo json_encode( $result );
	}

	function cetak_register_sum( $id )
	{
		$data           = $this->data->get_data_register( $id );
		$data['id']     = $id;
		$data['export'] = FALSE;
		$cbominggu      = $this->data->get_data_minggu( $data['parent']['term_id'] );
		$minggu         = ( $data['parent']['minggu_id'] ) ? $cbominggu[$data['parent']['minggu_id']] : '';

		$data['parent']['bulan'] = $this->term[$data['parent']['term_id']] . ' - ' . $minggu;
		$hasil                   = $this->load->view( 'risk_context/register', $data, TRUE );

		return $hasil;
	}
	public function getDataDivisionDropdown() : void
	{
		$mode  = (bool) $this->input->post( "validate" ) ?? "";
		$dept  = $this->input->post( "dept" );
		$seksi = $this->input->post( "seksi" );
		if( empty( $dept ) )
		{
			echo json_encode( [ "items" => "- No Options Available -" ] );
		}
		echo json_encode( [ "items" => $this->data->getDataDropdownDivision( $dept, $seksi, TRUE, $mode ) ] );
	}


	function get_peristiwa()
	{
		$data['libs'] = $this->data->get_library( "2" );
		$result       = $this->load->view( 'get-peristiwa', $data, TRUE );
		header( 'Content-Type: application/json' );
		echo json_encode( [ 'combo' => $result ] );
	}

	function add_peristiwa()
	{
		$data['libs'] = $this->data->get_library( "2" );

		$cboKel  = $this->crud->combo_select( [ 'id', 'data' ] )->combo_where( 'kelompok', 'lib-cat' )->combo_where( 'active', 1 )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();
		$cboTipe = $this->crud->combo_select( [ 'id', 'data' ] )->combo_where( 'kelompok', 'risk-type' )->combo_where( 'active', 1 )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();

		$data['form'][] = [ 'title' => _l( 'fld_peristiwa_risiko' ), 'required' => TRUE, 'help' => _h( 'help_add_peristiwa' ), 'isi' => form_input( 'peristiwaBaru', '', 'class="form-control" id="peristiwaBaru"  placeholder="Tambah ' . _l( 'fld_peristiwa_risiko' ) . '"' ) ];
		$data['form'][] = [ 'title' => _l( 'fld_klasifikasi_risiko' ), 'required' => TRUE, 'help' => _h( 'fld_klasifikasi_risiko' ), 'isi' => form_dropdown( 'kelBaru', $cboKel, '', 'class="form-control select" id="kelBaru"' ) ];
		$data['form'][] = [ 'title' => _l( 'fld_tipe_risiko' ), 'required' => TRUE, 'help' => _h( 'fld_tipe_risiko' ), 'isi' => form_dropdown( 'tipeBaru', $cboTipe, '', 'class="form-control select" id="tipeBaru"' ) ];


		$result = $this->load->view( 'add-peristiwa', $data, TRUE );
		header( 'Content-Type: application/json' );
		echo json_encode( [ 'combo' => $result ] );
	}


	function simpan_peristiwa()
	{
		$post = $this->input->post();

		$post                = $this->input->post();
		$upd['library']      = $post['peristiwaBaru'];
		$upd['risk_type_no'] = $post['tipeBaru'];
		$upd['type']         = 2;
		$upd['active']       = 0;
		$upd['created_by']   = $this->ion_auth->get_user_name();

		$this->db->insert( _TBL_LIBRARY, $upd );
		$id                     = $this->db->insert_id();
		$lib                    = $this->db->where( 'id', $id )->get( _TBL_VIEW_LIBRARY )->row_array();
		$data['idPeristiwa']    = $lib['id'];
		$data['peristiwaName']  = $lib['library'];
		$data['tipeId']         = $lib['risk_type_no'];
		$data['tipeName']       = $lib['risk_type'];
		$data['tasktonomiId']   = $lib['kel'];
		$data['tasktonomiName'] = $lib['nama_kelompok'];
		header( 'Content-type: application/json' );
		echo json_encode( $data );

	}
	public function refreshAnalisaRisk()
	{
		$type    = $this->input->post( "type" );
		$id_edit = $this->input->post( "id_edit" );
		$result  = $this->data->refreshInputRisk( $type, $id_edit );

		header( 'Content-type: application/json' );
		echo json_encode( $result );

	}

	function getLibraryModal()
	{
		$viewData = [];
		$lib      = 0;
		$title    = "";
		switch( strtolower( url_title( $this->input->post( "lib" ) ) ) )
		{
			case 'penyebab_id_text':
			case 'penyebab-risiko':
				$lib = 1;
				$title = "Penyebab Risiko";
				$viewData["thead1"] = _l( 'fld_klasifikasi_risiko' );
				$viewData["thead2"] = _l( 'fld_tipe_risiko' );
				$viewData["thead3"] = "Penyebab Risiko";
				break;

			case 'dampak_id_text':
			case 'dampak-risiko':
				$lib = 3;
				$title = "Dampak Risiko";
				$viewData["thead1"] = _l( 'fld_klasifikasi_risiko' );
				$viewData["thead2"] = _l( 'fld_tipe_risiko' );
				$viewData["thead3"] = "Dampak Risiko";
				break;

			default:
				break;
		}
		$viewData["data"]     = $this->data->get_library( $lib );
		$viewData["libtype"]  = $lib;
		$viewData["identity"] = $this->input->post( "identity" );
		$result["content"]    = $this->load->view( "library-modal", $viewData, TRUE );
		$result["title"]      = $title;
		header( 'Content-type: application/json' );
		echo json_encode( $result );

	}

	function addLibrary()
	{
		$lib     = 0;
		$libtype = $this->input->post( "lib" );
		switch( strtolower( url_title( $libtype ) ) )
		{
			case 'penyebab-risiko':
				$lib = 1;
				break;
			case 'dampak-risiko':
				$lib = 3;
				break;
			default:

				break;
		}
		$data['libs'] = $this->data->get_library( $lib );

		$data['form'][] = [ 'title' => $libtype, 'required' => TRUE, 'help' => "", 'isi' => form_input( 'libraryBaru', '', 'class="form-control" id="libraryBaru"  placeholder="Tambah ' . $libtype . '"' ) ];

		$data['form'][] = [ 'title' => "", "show" => FALSE, 'help' => "", 'isi' => form_hidden( 'libtipe', $lib ) ];

		$data["libtype"]   = $libtype;
		$data["identity"]  = $this->input->post( "identity" );
		$result["content"] = $this->load->view( 'add-library', $data, TRUE );
		$result["lib"]     = $libtype;
		header( 'Content-Type: application/json' );
		echo json_encode( $result );
	}

	function simpanLibrary()
	{

		$post                = $this->input->post();
		$upd['library']      = $post['libraryBaru'];
		$upd['risk_type_no'] = $post['risktype'];
		$upd['type']         = $post["libtipe"];
		$upd['active']       = 1;
		$upd['created_by']   = $this->ion_auth->get_user_name();

		$this->db->insert( _TBL_LIBRARY, $upd );
		$id  = $this->db->insert_id();
		$lib = $this->db->where( 'id', $id )->get( _TBL_VIEW_LIBRARY )->row_array();

		$data['idLibrary']   = $lib['id'];
		$data['libraryName'] = $lib['library'];
		$data['tipeLib']     = $lib['type'];
		$data['identity']    = $post['identity'];
		header( 'Content-type: application/json' );
		echo json_encode( $data );
	}
}
