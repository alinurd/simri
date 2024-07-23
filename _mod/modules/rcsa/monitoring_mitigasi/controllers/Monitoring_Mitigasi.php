<?php if( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Monitoring_Mitigasi extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->language( 'risk_context' );
	}

	function init( $action = 'list' )
	{
		$this->cboDept = $this->get_combo_parent_dept();
		$this->set_Tbl_Master( _TBL_VIEW_RCSA_MITIGASI_DETAIL );

		$this->addField( [ 'field' => 'id', 'type' => 'int', 'show' => FALSE, 'size' => 4 ] );
		$this->addField( [ 'field' => 'owner_id', 'title' => 'Owner Name', 'type' => 'int', 'input' => 'combo', 'search' => TRUE, 'values' => $this->cboDept ] );
		$this->addField( [ 'field' => 'owner_name', 'search' => FALSE, 'show' => FALSE ] );
		$this->addField( [ 'field' => 'penyebab_risiko', 'search' => TRUE, 'show' => FALSE ] );
		$this->addField( [ 'field' => 'mitigasi', 'search' => TRUE, 'show' => FALSE ] );
		$this->addField( [ 'field' => 'aktifitas_mitigasi', 'search' => TRUE, 'show' => FALSE ] );
		$this->addField( [ 'field' => 'batas_waktu_detail', 'search' => TRUE, 'show' => FALSE ] );
		$this->addField( [ 'field' => 'tgl_propose', 'show' => FALSE ] );
		$this->addField( [ 'field' => 'penanggung_jawab_detail_id', 'title' => 'Penanggung Jawab', 'type' => 'int', 'input' => 'combo', 'search' => TRUE, 'values' => $this->cboDept ] );
		$this->addField( [ 'field' => 'koordinator_detail_id', 'title' => 'Koordinator', 'type' => 'int', 'input' => 'combo', 'search' => TRUE, 'values' => $this->cboDept ] );
		$this->addField( [ 'field' => 'target', 'show' => FALSE ] );
		$this->addField( [ 'field' => 'aktual', 'show' => FALSE ] );
		$this->addField( [ 'field' => 'status_final', 'show' => FALSE ] );
		$this->addField( [ 'field' => 'status_monitoring', 'show' => FALSE ] );
		$this->addField( [ 'field' => 'risiko_dept', 'show' => FALSE ] );
		$this->addField( [ 'field' => 'kode_dept', 'show' => FALSE ] );
		$this->addField( [ 'field' => 'status_id', 'show' => FALSE ] );
		$this->addField( [ 'field' => 'created_at', 'show' => FALSE ] );
		$this->addField( [ 'field' => 'updated_at', 'show' => FALSE ] );

		$this->set_Field_Primary( $this->tbl_master, 'id', TRUE );

		$this->set_Sort_Table( $this->tbl_master, 'updated_at', 'desc' );
		$this->set_Where_Table( [ 'field' => 'status_monitoring', 'value' => 1, 'op' => '=' ] );
		$this->_set_Where_Owner();


		$this->set_Table_List( $this->tbl_master, 'owner_name' );
		$this->set_Table_List( $this->tbl_master, 'kode_dept' );
		$this->set_Table_List( $this->tbl_master, 'risiko_dept' );
		$this->set_Table_List( $this->tbl_master, 'mitigasi' );
		$this->set_Table_List( $this->tbl_master, 'aktifitas_mitigasi' );
		$this->set_Table_List( $this->tbl_master, 'tgl_propose' );
		$this->set_Table_List( $this->tbl_master, 'batas_waktu_detail' );
		$this->set_Table_List( $this->tbl_master, 'target' );
		$this->set_Table_List( $this->tbl_master, 'aktual' );
		$this->set_Table_List( $this->tbl_master, 'updated_at' );

		$this->set_Save_Table( _TBL_RCSA_MITIGASI_DETAIL );
		$this->setPrivilege( 'delete', FALSE );
		$this->setPrivilege( 'update', FALSE );
		$this->setPrivilege( 'insert', FALSE );
		$this->set_Close_Setting();

		$configuration = [
		 'show_title_header' => FALSE,
		];
		return [
		 'configuration' => $configuration,
		];
	}

	function listBox_TARGET( $field, $rows, $value )
	{
		$value .= '%';
		return $value;
	}
	function listBox_AKTUAL( $field, $rows, $value )
	{
		$value .= '%';

		return $value;
	}

	function progress_mitigasi( $id_edit = 0, $id = 0 )
	{
		$awal = FALSE;
		if( ! $id )
		{
			$awal    = TRUE;
			$id      = intval( $this->uri->segment( 3 ) );
			$id_edit = 0;
		}

		$dp = $this->db->where( 'id', $id_edit )->get( _TBL_VIEW_RCSA_MITIGASI_PROGRES )->row_array();
		$am = $this->db->where( 'id', $id )->get( _TBL_VIEW_RCSA_MITIGASI_DETAIL )->row_array();
		if( $am )
		{
			$data['detail_progres']    = $this->db->where( 'rcsa_mitigasi_detail_id', $id )->get( _TBL_VIEW_RCSA_MITIGASI_PROGRES )->result_array();
			$data['aktifitas_mitigas'] = $am;
			$mit                       = $this->db->where( 'id', $data['aktifitas_mitigas']['rcsa_mitigasi_id'] )->get( _TBL_VIEW_RCSA_MITIGASI )->row_array();
			$mit                       = $this->convert_owner->set_data( $mit, FALSE )->set_param( [ 'penanggung_jawab' => 'penanggung_jawab_id', 'koordinator' => 'koordinator_id' ] )->draw();
			$rcsa_detail               = $this->db->where( 'id', $mit['rcsa_detail_id'] )->get( _TBL_VIEW_RCSA_DETAIL )->row_array();
			$data['parent']            = $this->db->where( 'id', $rcsa_detail['rcsa_id'] )->get( _TBL_VIEW_RCSA )->row_array();

			$data['minggu'] = $this->crud->combo_select( [ 'id', 'concat(param_string) as minggu' ] )->combo_where( 'kelompok', 'minggu' )->combo_where( 'active', 1 )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();

			// $curMing = $this->_data_user_['term']['period_id'];
			$minggupil = $this->crud->combo_select( [ 'id', 'concat(param_string, \' ( \', param_date, \' s.d \', param_date_after, \' ) \') as minggu' ] )->combo_where( 'kelompok', 'minggu' )->combo_where( 'active', 1 )->combo_where( 'pid', _TAHUN_ID_ )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();

			$minggu = form_dropdown( 'minggu', $minggupil, ( $dp ) ? $dp['minggu_id'] : _MINGGU_ID_, 'class="form-control select" style="width:100%;"  id="minggu"' );
			$minggu .= '<script>$(".select").select2({
				allowClear: false
			});</script>';

			$aktual = '<div class="input-group" style="width:19% !important;"> <button type="button" onclick="this.parentNode.querySelector(\'[type=number]\').stepDown();"> - </button>';
			$aktual .= form_input( [ 'type' => 'number', 'name' => 'aktual' ], ( $dp ) ? $dp['aktual'] : '1', " class='form-control touchspin-postfix text-center' max='100' min='1' step='1' id='aktual' " );
			$aktual .= '<button type="button" onclick="this.parentNode.querySelector(\'[type=number]\').stepUp();"> + </button> <div class="form-control-feedback text-primary form-control-feedback-lg pointer" style="right:-25%;"> % </div></div>';

			$target = '<div class="input-group" style="width:19% !important;"> <button type="button" onclick="this.parentNode.querySelector(\'[type=number]\').stepDown();"> - </button>';
			$target .= form_input( [ 'type' => 'number', 'name' => 'target' ], ( $dp ) ? $dp['target'] : '1', " class='form-control touchspin-postfix text-center' max='100' min='1' step='1' id='target' " );
			$target .= '<button type="button" onclick="this.parentNode.querySelector(\'[type=number]\').stepUp();"> + </button> <div class="form-control-feedback text-primary form-control-feedback-lg pointer" style="right:-25%;"> % </div></div>';

			$data['progres'][] = [ 'title' => "Bulan Progress", 'mandatori' => FALSE, 'isi' => $minggu ];
			$data['progres'][] = [ 'title' => _l( 'fld_target' ), 'help' => _h( 'help_target' ), 'isi' => $target ];
			$data['progres'][] = [ 'title' => _l( 'fld_aktual' ), 'help' => _h( 'help_aktual' ), 'isi' => $aktual ];
			$data['progres'][] = [ 'title' => _l( 'fld_uraian' ), 'help' => _h( 'help_uraian' ), 'isi' => form_textarea( 'uraian', ( $dp ) ? $dp['uraian'] : '', " id='uraian' maxlength='500' size='500' class='form-control' style='overflow: hidden; width: 100% !important; height: 100px;' onblur='_maxLength(this , \"id_sisa_1\")' onkeyup='_maxLength(this , \"id_sisa_1\")' data-role='tagsinput'", TRUE, [ 'size' => 1000, 'isi' => 0, 'no' => 1 ] ) ];
			$data['progres'][] = [ 'title' => _l( 'fld_kendala' ), 'help' => _h( 'help_kendala' ), 'isi' => form_textarea( 'kendala', ( $dp ) ? $dp['kendala'] : '', " id='kendala' maxlength='500' size='500' class='form-control' style='overflow: hidden; width: 100% !important; height: 100px;' onblur='_maxLength(this , \"id_sisa_2\")' onkeyup='_maxLength(this , \"id_sisa_2\")' data-role='tagsinput'", TRUE, [ 'size' => 1000, 'isi' => 0, 'no' => 2 ] ) ];
			$data['progres'][] = [ 'title' => _l( 'fld_tindak_lanjut' ), 'help' => _h( 'help_tindak_lanjut' ), 'isi' => form_textarea( 'tindak_lanjut', ( $dp ) ? $dp['tindak_lanjut'] : '', " id='tindak_lanjut' maxlength='500' size='500' class='form-control' style='overflow: hidden; width: 100% !important; height: 100px;' onblur='_maxLength(this , \"id_sisa_3\")' onkeyup='_maxLength(this , \"id_sisa_3\")' data-role='tagsinput'", TRUE, [ 'size' => 1000, 'isi' => 0, 'no' => 3 ] ) ];
			$data['progres'][] = [ 'title' => _l( 'fld_due_date' ), 'help' => _h( 'help_due_date' ), 'isi' => form_input( 'batas_waktu_tindak_lanjut', ( $dp ) ? $dp['batas_waktu_tindak_lanjut'] : '', 'class="form-control pickadate" id="batas_waktu_tindak_lanjut" style="width:100%;"' ) ];

			$data['progres'][] = [ 'title' => _l( 'fld_keterangan' ), 'help' => _h( 'help_keterangan' ), 'isi' => form_textarea( 'keterangan', ( $dp ) ? $dp['keterangan'] : '', " id='keterangan' maxlength='500' size='500' class='form-control' style='overflow: hidden; width: 100% !important; height: 100px;' onblur='_maxLength(this , \"id_sisa_4\")' onkeyup='_maxLength(this , \"id_sisa_4\")' data-role='tagsinput'", TRUE, [ 'size' => 1000, 'isi' => 0, 'no' => 4 ] ) ];
			$data['progres'][] = [ 'title' => _l( 'fld_lampiran' ), 'help' => _h( 'help_lampiran' ), 'isi' => form_upload( 'lampiran' ) ];
			$data['progres'][] = [ 'title' => '', 'help' => '', 'isi' => form_hidden( [ 'aktifitas_mitigasi_id' => $id, 'id' => $id_edit ] ) ];

			$data['info_1'][] = [ 'title' => _l( 'fld_risiko_dept' ), 'isi' => $rcsa_detail['risiko_dept'] ];
			$data['info_1'][] = [ 'title' => _l( 'fld_risiko_inherent' ), 'isi' => $rcsa_detail['level_color'] ];
			$data['info_1'][] = [ 'title' => _l( 'fld_efek_kontrol' ), 'isi' => $rcsa_detail['efek_kontrol_text'] ];
			$data['info_1'][] = [ 'title' => _l( 'fld_nama_control' ), 'isi' => $rcsa_detail['nama_kontrol'] ];
			$data['info_1'][] = [ 'title' => _l( 'fld_level_risiko' ), 'isi' => $rcsa_detail['level_color_residual'] ];
			$data['info_1'][] = [ 'title' => _l( 'fld_treatment' ), 'isi' => $rcsa_detail['treatment'] ];

			$data['info_2'][] = [ 'title' => _l( 'fld_mitigasi' ), 'isi' => $mit['mitigasi'] ];
			$data['info_2'][] = [ 'title' => _l( 'fld_biaya' ), 'isi' => number_format( $mit['biaya'] ) ];
			$data['info_2'][] = [ 'title' => _l( 'fld_pic' ), 'isi' => $mit['penanggung_jawab'] ];
			$data['info_2'][] = [ 'title' => _l( 'fld_koordinator' ), 'isi' => $mit['koordinator'] ];
			$data['info_2'][] = [ 'title' => _l( 'fld_due_date' ), 'isi' => date( 'd-M-Y', strtotime( $mit['batas_waktu'] ) ) ];

			$data['informasi']    = $this->load->view( 'informasi', $data, TRUE );
			$data['list_progres'] = $this->load->view( 'list-progres', $data, TRUE );
			$data['update']       = $this->load->view( 'progres', $data, TRUE );

			$hasil         = $this->load->view( 'monitoring', $data, TRUE );
			$configuration = [
			 'show_title_header'  => FALSE,
			 'show_action_button' => FALSE,
			];

			if( $awal )
			{
				$this->default_display( [ 'content' => $hasil, 'configuration' => $configuration ] );
			}
			else
			{
				return $data;
			}
		}
		else
		{
			header( 'location:' . base_url( _MODULE_NAME_ ) );
		}
	}

	function add_progres()
	{
		$id          = intval( $this->input->post( 'id' ) );
		$mitigasi_id = intval( $this->input->post( 'mitigasi_id' ) );
		$hasil       = $this->progress_mitigasi( $id, $mitigasi_id );
		header( 'Content-type: application/json' );
		echo json_encode( [ 'combo' => $hasil['update'] ] );
	}

	function simpan_progres()
	{
		$post    = $this->input->post();
		$id_edit = $this->data->simpan_progres( $post );

		$id                     = intval( $post['aktifitas_mitigasi_id'] );
		$hasil                  = $this->progress_mitigasi( 0, $id );
		$result['update']       = $hasil['update'];
		$result['list_progres'] = $hasil['list_progres'];
		header( 'Content-type: application/json' );
		echo json_encode( $result );
	}

	function hapus_progres()
	{
		$id          = intval( $this->input->post( 'id' ) );
		$mitigasi_id = intval( $this->input->post( 'mitigasi_id' ) );
		$this->crud->crud_table( _TBL_RCSA_MITIGASI_PROGRES );
		$this->crud->crud_type( 'delete' );
		$this->crud->crud_where( [ 'field' => 'id', 'value' => $id ] );
		$this->crud->process_crud();
		$hasil                  = $this->progress_mitigasi( 0, $mitigasi_id );
		$result['list_progres'] = $hasil['list_progres'];
		$result['combo']        = 'Sukses';
		header( 'Content-type: application/json' );
		echo json_encode( $result );
	}

	function optionalPersonalButton( $button, $row )
	{
		$button             = [];
		$button['progress'] = [
		 'label' => 'Update Progress',
		 'id'    => 'btn_schedule_one',
		 'class' => 'text-success',
		 'icon'  => 'icon-file-spreadsheet ',
		 'url'   => base_url( _MODULE_NAME_ . '/progress-mitigasi/' ),
		 'attr'  => ' target="_self" ',
		];

		return $button;
	}
}
