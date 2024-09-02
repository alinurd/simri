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
	function data_alur($own_no)
	{
		$rows = $this->db->where('id', $own_no)->get(_TBL_VIEW_OWNER_PARENT)->row_array();
		$owner = [];
		$officer = [];
		if ($rows) {
			if (!empty($rows['level_approval'])) {
				$level = explode(',', $rows['level_approval']);
				foreach ($level as $x) {
					$owner[$x] = ['id' => $rows['id'], 'name' => $rows['parent_name']];
					$officer[$x] = $rows['id'];
				}
			}
			if (!empty($rows['level_approval_1'])) {
				$level = explode(',', $rows['level_approval_1']);
				foreach ($level as $x) {
					$owner[$x] = ['id' => $rows['lv_1_id'], 'name' => $rows['lv_1_name']];
					$officer[$x] = $rows['lv_1_id'];
				}
			}
			if (!empty($rows['level_approval_2'])) {
				$level = explode(',', $rows['level_approval_2']);
				foreach ($level as $x) {
					$owner[$x] = ['id' => $rows['lv_2_id'], 'name' => $rows['lv_2_name']];
					$officer[$x] = $rows['lv_2_id'];
				}
			}
			if (!empty($rows['level_approval_3'])) {
				$level = explode(',', $rows['level_approval_3']);
				foreach ($level as $x) {
					$owner[$x] = ['id' => $rows['lv_3_id'], 'name' => $rows['lv_3_name']];
					$officer[$x] = $rows['lv_3_id'];
				}
			}
		}
		$staft_tahu = [];
		$staft_setuju = [];
		$staft_valid = [];
		if ($officer) {
			$rows = $this->db->where_in('owner_no', $officer)->group_start()->where('sts_mengetahui', 1)->or_where('sts_menyetujui', 1)->or_where('sts_menvalidasi', 1)->group_end()->get(_TBL_VIEW_OFFICER)->result_array();
			foreach ($rows as $row) {
				if ($row['sts_mengetahui'] == 1) {
					$staft_tahu[$row['owner_no']]['name'][] = $row['officer_name'];
					$staft_tahu[$row['owner_no']]['id'][] = $row['id'];
					$staft_tahu[$row['owner_no']]['email'][] = $row['email'];
				} elseif ($row['sts_menyetujui'] == 1) {
					$staft_setuju[$row['owner_no']]['name'][] = $row['officer_name'];
					$staft_setuju[$row['owner_no']]['id'][] = $row['id'];
					$staft_setuju[$row['owner_no']]['email'][] = $row['email'];
				} elseif ($row['sts_menvalidasi'] == 1) {
					$staft_valid[$row['owner_no']]['name'][] = $row['officer_name'];
					$staft_valid[$row['owner_no']]['id'][] = $row['id'];
					$staft_valid[$row['owner_no']]['email'][] = $row['email'];
				}
			}
		}

		$rows = $this->db->select("'' as staft, '' as bagian, " . _TBL_VIEW_APPROVAL . ".*")->where('pid', 249)->order_by('urut')->get(_TBL_VIEW_APPROVAL)->result_array();
		$alur[0] = ['level' => 'Risk Officer', 'owner' => '', 'staft' => '', 'level_approval_id' => 0, 'owner_no' => 0, 'staft_no' => 0, 'urut' => 0, 'sts_last' => 0, 'email' => '', 'tanggal' => '', 'sts_monit' => 0];
		foreach ($rows as $row) {
			$prm = json_decode($row['param_text'], true);
			$ow = '';
			$ow_id = '';
			$of = '';
			$of_id = '';
			$email = '';
			if (intval($prm['tipe_approval']) == 0) {
				if (array_key_exists($row['param_int'], $owner)) {
					$ow = $owner[$row['param_int']]['name'];
					$ow_id = $owner[$row['param_int']]['id'];
					if ($prm['level_approval'] == 1) {
						if (array_key_exists($owner[$row['param_int']]['id'], $staft_tahu)) {
							$of = implode(', ', $staft_tahu[$owner[$row['param_int']]['id']]['name']);
							$of_id = implode(', ', $staft_tahu[$owner[$row['param_int']]['id']]['id']);
							$email = implode(', ', $staft_tahu[$owner[$row['param_int']]['id']]['email']);
						}
					} elseif ($prm['level_approval'] == 2) {
						if (array_key_exists($owner[$row['param_int']]['id'], $staft_setuju)) {
							$of = implode(', ', $staft_setuju[$owner[$row['param_int']]['id']]['name']);
							$of_id = implode(', ', $staft_setuju[$owner[$row['param_int']]['id']]['id']);
							$email = implode(', ', $staft_setuju[$owner[$row['param_int']]['id']]['email']);
						}
					} elseif ($prm['level_approval'] == 3) {
						if (array_key_exists($owner[$row['param_int']]['id'], $staft_valid)) {
							$of = implode(', ', $staft_valid[$owner[$row['param_int']]['id']]['name']);
							$of_id = implode(', ', $staft_valid[$owner[$row['param_int']]['id']]['id']);
							$email = implode(', ', $staft_valid[$owner[$row['param_int']]['id']]['email']);
						}
					}
				}
			} elseif (intval($prm['tipe_approval']) == 1) {
				$arr_free = $this->db->where_in('level_approval', $row['param_int'])->get(_TBL_OWNER)->row_array();
				if ($arr_free) {
					$ow = $arr_free['owner_name'];
					$ow_id = $arr_free['id'];
					$of_arr = [];
					$of_id_arr = [];
					$email_arr = [];
					$arr_free = $this->db->where('owner_no', $ow_id)->group_start()->where('sts_mengetahui', 1)->or_where('sts_menyetujui', 1)->or_where('sts_menvalidasi', 1)->group_end()->get(_TBL_VIEW_OFFICER)->result_array();
					if ($arr_free) {
						foreach ($arr_free as $fr) {
							if ($prm['level_approval'] == 1 && $fr['sts_mengetahui'] == 1) {
								$of_arr[] = $fr['officer_name'];
								$of_id_arr[] = $fr['id'];
								$email_arr[] = $fr['email'];
							} elseif ($prm['level_approval'] == 2 && $fr['sts_menyetujui'] == 1) {
								$of_arr[] = $fr['officer_name'];
								$of_id_arr[] = $fr['id'];
								$email_arr[] = $fr['email'];
							} elseif ($prm['level_approval'] == 3 && $fr['sts_menvalidasi'] == 1) {
								$of_arr[] = $fr['officer_name'];
								$of_id_arr[] = $fr['id'];
								$email_arr[] = $fr['email'];
							}
						}
						$of = implode(', ', $of_arr);
						$of_id = implode(', ', $of_id_arr);
						$email = implode(', ', $email_arr);
					}
				}
			}
			$alur[$row['urut']] = ['level' => $row['model'], 'owner' => $ow, 'staft' => $of, 'level_approval_id' => $row['id'], 'owner_no' => $ow_id, 'staft_no' => $of_id, 'urut' => $row['urut'], 'sts_last' => $row['sts_last'], 'email' => $email, 'tanggal' => '', 'sts_monit' => $prm['monit'], 'sts_notif' => $prm['notif_email']];
		}
		return $alur;
	}
}
/* End of file app_login_model.php */
