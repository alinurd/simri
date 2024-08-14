<?php if( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class MX_Model extends CI_Model
{
	public $pages_child = array();
	public $owner_child = array();
	public function __construct()
	{
		parent::__construct();
	}

	function get_library( $type )
	{
		if( $type )
		{
			$libs = $this->db->where_in( 'type', $type )->where( "active", 1 )->get( _TBL_VIEW_LIBRARY )->result_array();
		}
		else
		{
			$libs = [];
		}
		return $libs;
	}


	function get_owner_child( $id )
	{
		$this->db->select( '*' );
		$this->db->from( _TBL_OWNER );
		$this->db->where( 'pid', $id );
		$this->db->where( 'active', 1 );

		$sql  = $this->db->get();
		$rows = $sql->result();
		foreach( $rows as $key => $row )
		{
			$this->get_owner_child( $row->id );
			$this->owner_child[] = $row->id;
		}
	}

	function get_pages_child( $id )
	{
		$this->db->select( '*' );
		$this->db->from( _TBL_COMBO );
		$this->db->where( 'pid', $id );
		$this->db->where( 'active', 1 );

		$sql  = $this->db->get();
		$rows = $sql->result();
		foreach( $rows as $key => $row )
		{
			$this->get_pages_child( $row->id );
			$this->pages_child[] = $row->id;
		}
	}

	function get_data_register_bytype( $type, $period, $term )
	{
		// $rows=$this->db->where('id', $id)->get(_TBL_VIEW_RCSA)->row_array();
		// $hasil['parent']=$rows;
		$rcsa_id = [ 0 ];
		$rows    = $this->db->select( 'rcsa_id' )->where( 'tipe_risiko_id', $type )->where( 'period_id', $period )->where( 'term_id', $term )->order_by( 'kode_dept' )->order_by( 'kode_aktifitas' )->get( _TBL_VIEW_REGISTER )->result_array();
		if( $rows )
		{
			foreach( $rows as $key => $value )
			{
				$rcsa_id[] = $value['rcsa_id'];
			}
		}

		$rows = $this->db->where_in( 'rcsa_id', $rcsa_id )->get( _TBL_VIEW_RCSA_MITIGASI )->result_array();
		$rows = $this->convert_owner->set_data( $rows )->set_param( [ 'penanggung_jawab' => 'penanggung_jawab_id', 'koordinator' => 'koordinator_id' ] )->draw();
		$mit  = [];
		foreach( $rows as $key => $row )
		{
			$this->db->select( 'aktual' );
			$progres     = $this->db->where( 'rcsa_mitigasi_id', $row['id'] )->get( _TBL_VIEW_RCSA_MITIGASI_PROGRES )->result_array();
			$jmlprogres  = count( $progres );
			$totalaktual = 0;
			foreach( $progres as $v )
			{
				$totalaktual += $v['aktual'];
			}
			$rata                          = ( $jmlprogres >= 1 ) ? $totalaktual / $jmlprogres : 0;
			$row['progres']                = $rata;
			$mit[$row['rcsa_detail_id']][] = $row;
		}

		$hasil['mitigasi'] = $mit;
		$rows              = $this->db->where_in( 'rcsa_id', $rcsa_id )->where( 'tipe_risiko_id', $type )->where( 'period_id', $period )->where( 'term_id', $term )->order_by( 'kode_dept' )->order_by( 'kode_aktifitas' )->get( _TBL_VIEW_REGISTER )->result_array();
		foreach( $rows as &$row )
		{
			$idx  = explode( ',', $row['peristiwa_id'] );
			$libs = $this->db->where_in( 'id', $idx )->get( _TBL_LIBRARY )->result_array();
			$x    = [];
			foreach( $libs as $lib )
			{
				$x[] = $lib['library'];
			}
			$row['peristiwa'] = implode( '###', $x );

			$idx  = explode( ',', $row['dampak_id'] );
			$libs = $this->db->where_in( 'id', $idx )->get( _TBL_LIBRARY )->result_array();
			$x    = [];
			foreach( $libs as $lib )
			{
				$x[] = $lib['library'];
			}
			$row['dampak'] = implode( '###', $x );
			if( ! empty( $row['nama_kontrol_note'] ) && ! empty( $row['nama_kontrol'] ) )
			{
				$row['nama_kontrol'] .= '###' . $row['nama_kontrol_note'];
			}
			else
			{
				$row['nama_kontrol'] .= $row['nama_kontrol_note'];
			}
		}
		unset( $row );
		$hasil['rows'] = $rows;
		$rows          = $this->db->where_in( 'rcsa_id', $rcsa_id )->get( _TBL_VIEW_RCSA_DET_LIKE_INDI )->result_array();
		$like          = [];
		foreach( $rows as $row )
		{
			$like[$row['rcsa_detail_id']][$row['bk_tipe']][] = $row;
		}
		$hasil['like_indi'] = $like;
		$rows               = $this->db->where_in( 'rcsa_id', $rcsa_id )->get( _TBL_VIEW_RCSA_DET_DAMPAK_INDI )->result_array();
		$dampak             = [];
		foreach( $rows as $row )
		{
			$dampak[$row['rcsa_detail_id']][$row['bk_tipe']][] = $row;
		}
		$hasil['dampak_indi'] = $dampak;
		return $hasil;
	}

	function get_data_register( $id )
	{
		$rows = $this->db->where( 'id', $id )->get( _TBL_VIEW_RCSA )->row_array();

		$hasil['parent'] = $rows;
		$rows            = $this->db->where( 'rcsa_id', $id )->get( _TBL_VIEW_RCSA_MITIGASI )->result_array();

		/** convert_json_to_implode integer */
		if( ! empty( $rows ) )
		{
			foreach( $rows as $krows => $vrows )
			{
				$rows[$krows]["penanggung_jawab_id"] = ( ! empty( $vrows["penanggung_jawab_id"] ) && json_decode( $vrows["penanggung_jawab_id"] ) ) ? implode( ",", json_decode( $vrows["penanggung_jawab_id"] ) ) : $vrows["penanggung_jawab_id"];
			}
		}
		/**
		 * end convert
		 */
		$rows = $this->convert_owner->set_data( $rows )->set_param( [ 'penanggung_jawab' => 'penanggung_jawab_id', 'koordinator' => 'koordinator_id' ] )->draw();
		$mit  = [];
		foreach( $rows as $key => $row )
		{
			$this->db->select( 'aktual' );
			$progres     = $this->db->where( 'rcsa_mitigasi_id', $row['id'] )->get( _TBL_VIEW_RCSA_MITIGASI_PROGRES )->result_array();
			$jmlprogres  = count( $progres );
			$totalaktual = 0;
			foreach( $progres as $v )
			{
				$totalaktual += $v['aktual'];
			}
			$rata                          = ( $jmlprogres >= 1 ) ? $totalaktual / $jmlprogres : 0;
			$row['progres']                = $rata;
			$mit[$row['rcsa_detail_id']][] = $row;
		}

		$hasil['mitigasi'] = $mit;
		$rows              = $this->db->select( [ _TBL_VIEW_REGISTER . ".*", "CONCAT(" . _TBL_OWNER . ".owner_name" . ",' - '," . _TBL_OWNER . ".owner_code) as seksi" ] )->order_by( 'kode_dept' )->order_by( 'kode_aktifitas' )->join( _TBL_OWNER, _TBL_OWNER . '.id = ' . _TBL_VIEW_REGISTER . '.seksi', 'left' )->get_where( _TBL_VIEW_REGISTER, [ 'rcsa_id' => $id ] )->result_array();
		foreach( $rows as &$row )
		{
			$idx  = explode( ',', $row['penyebab_id'] );
			$libs = $this->db->where_in( 'id', $idx )->get( _TBL_LIBRARY )->result_array();
			$x    = [];
			foreach( $libs as $lib )
			{
				$x[] = $lib['library'];
			}
			$row['penyebab'] = implode( '###', $x );


			$idx  = explode( ',', $row['peristiwa_id'] );
			$libs = $this->db->where_in( 'id', $idx )->get( _TBL_LIBRARY )->result_array();
			$x    = [];
			foreach( $libs as $lib )
			{
				$x[] = $lib['library'];
			}
			$row['peristiwa'] = implode( '###', $x );

			$idx  = explode( ',', $row['dampak_id'] );
			$libs = $this->db->where_in( 'id', $idx )->get( _TBL_LIBRARY )->result_array();
			$x    = [];
			foreach( $libs as $lib )
			{
				$x[] = $lib['library'];
			}
			$row['dampak'] = implode( '###', $x );
			if( ! empty( $row['nama_kontrol_note'] ) && ! empty( $row['nama_kontrol'] ) )
			{
				$row['nama_kontrol'] .= '###' . $row['nama_kontrol_note'];
			}
			else
			{
				$row['nama_kontrol'] .= $row['nama_kontrol_note'];
			}
		}
		unset( $row );
		$hasil['rows'] = $rows;
		$rows          = $this->db->where( 'rcsa_id', $id )->get( _TBL_VIEW_RCSA_DET_LIKE_INDI )->result_array();
		$like          = [];
		foreach( $rows as $row )
		{
			$like[$row['rcsa_detail_id']][$row['bk_tipe']][] = $row;
		}
		$hasil['like_indi'] = $like;
		$rows               = $this->db->where( 'rcsa_id', $id )->get( _TBL_VIEW_RCSA_DET_DAMPAK_INDI )->result_array();
		$dampak             = [];
		foreach( $rows as $row )
		{
			$dampak[$row['rcsa_detail_id']][$row['bk_tipe']][] = $row;
		}
		$hasil['dampak_indi'] = $dampak;
		return $hasil;
	}

	function get_data_monitoring( $id )
	{
		$rows         = $this->db->where( 'rcsa_id', $id )->get( _TBL_VIEW_RCSA_MITIGASI_PROGRES )->result_array();
		$mit          = [];
		$jml['aktif'] = [];
		foreach( $rows as $row )
		{
			$mit[$row['rcsa_mitigasi_detail_id']][]          = $row;
			$jml['aktif'][$row['rcsa_mitigasi_detail_id']][] = $row['id'];
		}
		$hasil    = $jml;
		$rows     = $this->db->where( 'id', $id )->get( _TBL_VIEW_RCSA )->row_array();
		$parent   = $rows;
		$mitigasi = $mit;
		$rows     = $this->db->where( 'rcsa_id', $id )->get( _TBL_VIEW_MONITORING )->result_array();
		$rows     = $this->convert_owner->set_data( $rows )->set_param( [ 'penanggung_jawab' => 'penanggung_jawab_detail_id', 'koordinator' => 'koordinator_detail_id' ] )->draw();
		$rowsx    = $rows;

		$jml['miti']   = [];
		$jml['identi'] = [];
		foreach( $rows as $row )
		{
			$jml['identi'][$row['rcsa_detail_id']][] = $row['id'];
			$jml['miti'][$row['mitigasi_id']][]      = $row['id'];
		}
		$hasil             = $jml;
		$hasil['rows']     = $rowsx;
		$hasil['parent']   = $parent;
		$hasil['mitigasi'] = $mit;
		$hasil['minggu']   = $this->crud->combo_select( [ 'id', 'concat(param_string) as minggu' ] )->combo_where( 'kelompok', 'minggu' )->combo_where( 'active', 1 )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();
		return $hasil;
	}
	public function getMonthlyMonitoring( $id, $month )
	{
		$thn         = date( 'Y' );
		$getProgress = $this->db->where( 'rcsa_detail_id', $id )->where( 'month', $month )->get( "il_update_residual" )->row_array();
		return $getProgress;
	}
	function cek_mitigasi_final( $id_detail, $month, $update )
	{
		$alur               = [];
		$cekResidual        = $this->db->where( 'rcsa_detail_id', $id_detail )->where( 'month', $month )->get( "il_update_residual" )->row_array();
		$countMitdetail     = 0;
		$countMitDetailProg = 0;  // Initialize to keep track of total progress

		$getMitigasi = $this->db->where( 'rcsa_detail_id', $id_detail )->get( "il_rcsa_mitigasi" )->result_array();
		$rcsa_detail = $this->db->where( 'id', $id_detail )->get( "il_view_rcsa_detail" )->row_array();

		foreach( $getMitigasi as $m )
		{
			$mitDetails = $this->db->where( 'rcsa_mitigasi_id', $m['id'] )->get( "il_rcsa_mitigasi_detail" )->result_array();

			foreach( $mitDetails as $md )
			{
				$getMinggu = $this->db->where( 'period_id', $rcsa_detail['period_id'] )->where( 'bulan_int', $month )->get( "il_view_minggu" )->row_array();

				if( $getMinggu )
				{
					$mitProgress = $this->db->where( 'rcsa_mitigasi_detail_id', $md['id'] )->where( 'minggu_id', $getMinggu['id'] )->get( "il_rcsa_mitigasi_progres" )->result_array();

					$countMitdetail++;
					$countMitDetailProg += count( $mitProgress );
				}
			}
		}
		$setFinal = FALSE;

		if( $cekResidual && $countMitDetailProg >= $countMitdetail )
		{
			$detail    = $this->db->where( 'id', $id_detail )->get( "il_view_rcsa_detail" )->row_array();
			$id        = $detail['rcsa_id'];
			$sts_final = 1;
			$urut      = count( $alur );
			$final     = "Final";
			$setFinal  = TRUE;
		}
		else
		{
			$sts_final = 0;
			$final     = "Proses Mitigasi";
		}
		if( $setFinal && $update )
		{
			$this->crud->crud_table( _TBL_RCSA );
			$this->crud->crud_type( 'edit' );
			$this->crud->crud_field( 'status_revisi_mitigasi', 0, 'int' );
			$this->crud->crud_field( 'status_id_mitigasi', $sts_final, 'int' );
			$this->crud->crud_field( 'note_propose_mitigasi', $final );
			// $this->crud->crud_field( 'param_approval_mitigasi', json_encode( $alur ) );
			$this->crud->crud_field( 'tgl_propose_mitigasi', date( 'Y-m-d H:i:s' ), 'datetime' );
			$this->crud->crud_field( 'status_final_mitigasi', $sts_final, 'int' );
			$this->crud->crud_where( [ 'field' => 'id', 'value' => $id ] );
			$this->crud->process_crud();
			$this->crud->crud_table( _TBL_LOG_APPROVAL );
			$this->crud->crud_type( 'add' );
			$this->crud->crud_field( 'tipe_log', 2, 'int' );
			$this->crud->crud_field( 'rcsa_id', $id, 'int' );
			$this->crud->crud_field( 'keterangan', $final );

			$this->crud->crud_field( 'note', $final );
			$this->crud->crud_field( 'tanggal', date( 'Y-m-d H:i:s' ), 'datetime' );
			$this->crud->crud_field( 'user_id', $this->ion_auth->get_user_id() );
			$this->crud->crud_field( 'penerima_id', '' );
			$this->crud->process_crud();

			$setFinal = TRUE;
		}


		return $setFinal;
	}
}
/* End of file app_login_model.php */
