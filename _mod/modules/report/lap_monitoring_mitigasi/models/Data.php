<?php if( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Data extends MX_Model
{

	var $pos = [];
	var $cek_tgl = TRUE;
	var $miti_aktual = [];
	public function __construct()
	{
		parent::__construct();
	}

	function get_detail_char()
	{
		switch( intval( $this->pos['data']['type_chat'] ) )
		{
			case 1:
				$rows = $this->detail_lap_mitigasi();
				break;
			case 2:
				$rows = $this->detail_lap_ketepatan();
				break;
			case 3:
				$rows = $this->detail_lap_komitment();
				break;
			default:
				break;
		}

		return $rows;
	}

	function get_data_kompilasi( $period, $owner, $type )
	{
		if( $type == '' || $type == 0 )
		{
			$type = 128;
		}
		$rows         = $this->db->where( 'period_id', $period )->where( 'owner_id', $owner )->where( 'type_ass_id', $type )->get( _TBL_VIEW_RCSA_MITIGASI_PROGRES )->result_array();
		$mit          = [];
		$jml['aktif'] = [];
		// foreach($rows as $row){
		// 	$mit[$row['penyebab_id']][]=$row;
		// 	$jml['aktif'][$row['rcsa_mitigasi_detail_id']][]=$row['id'];
		// }
		foreach( $rows as $row )
		{
			$mit[$row['rcsa_mitigasi_detail_id']][]          = $row;
			$jml['aktif'][$row['rcsa_mitigasi_detail_id']][] = $row['penyebab_id'];
		}
		$hasil    = $jml;
		$rows     = $this->db->where( 'period_id', $period )->where( 'owner_id', $owner )->where( 'type_ass_id', $type )->get( _TBL_VIEW_RCSA )->row_array();
		$parent   = $rows;
		$mitigasi = $mit;
		$rows     = $this->db->where( 'period_id', $period )->where( 'owner_id', $owner )->where( 'type_ass_id', $type )->get( _TBL_VIEW_MONITORING )->result_array();
		$rows     = $this->convert_owner->set_data( $rows )->set_param( [ 'penanggung_jawab' => 'penanggung_jawab_detail_id', 'koordinator' => 'koordinator_detail_id' ] )->draw();
		$rowsx    = $rows;

		$jml['miti']   = [];
		$jml['identi'] = [];
		foreach( $rows as $row )
		{
			$jml['identi'][$row['rcsa_detail_id']][] = $row['id'];
			$jml['miti'][$row['mitigasi_id']][]      = $row['id'];
		}
		$hasil         = $jml;
		$hasil['rows'] = $rowsx;

		$hasil['parent']   = $parent;
		$hasil['mitigasi'] = $mit;
		$hasil['minggu']   = $this->crud->combo_select( [ 'id', 'concat(param_string) as minggu' ] )->combo_where( 'kelompok', 'minggu' )->combo_where( 'active', 1 )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();
		return $hasil;
	}

	function detail_lap_mitigasi( $mode = [] )
	{
		$this->filter_data();

		$rows_progres = $this->db->select( 'rcsa_mitigasi_id, rcsa_mitigasi_detail_id, created_at, target, aktual' )->order_by( 'rcsa_mitigasi_detail_id, created_at desc' )
		// ->get_compiled_select(_TBL_VIEW_RCSA_MITIGASI_PROGRES);
		->get( _TBL_VIEW_RCSA_MITIGASI_PROGRES )->result_array();
		// dumps($rows_progres);
		// die();
		$progres = [];
		$id      = 0;
		$tgl     = '2000/01/01';
		foreach( $rows_progres as $row )
		{
			if( $id !== $row['rcsa_mitigasi_detail_id'] )
			{
				$progres[$row['rcsa_mitigasi_detail_id']] = $row;
			}
			elseif( $row['created_at'] > $tgl )
			{
				$progres[$row['rcsa_mitigasi_detail_id']] = $row;
				$tgl                                      = $row['created_at'];
			}
			$id = $row['rcsa_mitigasi_detail_id'];
		}

		$rows = [];
		// dumps($progres);
		foreach( $progres as $key => $row )
		{
			if( array_key_exists( $row['rcsa_mitigasi_id'], $rows ) )
			{
				$rows[$row['rcsa_mitigasi_id']]['target'] += floatval( $row['target'] );
				$rows[$row['rcsa_mitigasi_id']]['aktual'] += floatval( $row['aktual'] );
				++$rows[$row['rcsa_mitigasi_id']]['jml'];
			}
			else
			{
				$rows[$row['rcsa_mitigasi_id']]['target'] = floatval( $row['target'] );
				$rows[$row['rcsa_mitigasi_id']]['aktual'] = floatval( $row['aktual'] );
				$rows[$row['rcsa_mitigasi_id']]['jml']    = 1;
			}
		}
		foreach( $rows as $key => &$row )
		{
			$row['target'] = floatval( $row['target'] ) / $row['jml'];
			$row['aktual'] = floatval( $row['aktual'] ) / $row['jml'];
		}
		unset( $row );

		$mitigasi    = [];
		$mitigasi[1] = [ 'category' => 'Selesai', 'nilai' => 0 ];
		$mitigasi[2] = [ 'category' => 'Belum Selesai, On Schdule', 'nilai' => 0 ];
		$mitigasi[3] = [ 'category' => 'Belum Selesai, Terlambat', 'nilai' => 0 ];
		$mitigasi[4] = [ 'category' => 'Belum Dilaksanakan', 'nilai' => 0 ];

		$id   = [];
		$id[] = 0;
		foreach( $rows as $key => $row )
		{
			if( $row['target'] >= 100 && $row['aktual'] >= 100 )
			{
				if( intval( $this->pos['data']['param_id'] ) == 1 )
				{
					$id[] = $key;
				}
			}
			elseif( $row['target'] == $row['aktual'] && floatval( $row['target'] ) != 100 )
			{
				if( intval( $this->pos['data']['param_id'] ) == 2 )
				{
					$id[] = $key;
				}
			}
			elseif( $row['target'] > 0 && floatval( $row['aktual'] ) == 0 )
			{
				if( intval( $this->pos['data']['param_id'] ) == 3 )
				{
					$id[] = $key;
				}
			}
			elseif( intval( $this->pos['data']['param_id'] ) == 4 )
			{
				$id[] = $key;
			}
		}
		// dumps($id);

		$mit = $this->db->where_in( 'id', $id )->get( _TBL_VIEW_RCSA_MITIGASI )->result_array();
		foreach( $mit as &$row )
		{
			$row['target'] = $rows[$row['id']]['target'];
			$row['aktual'] = $rows[$row['id']]['aktual'];
		}
		unset( $row );
		$hasil['data'] = $mit;

		return $hasil;
	}


	function filter_data()
	{
		$minggu = [];
		if( $this->cek_tgl )
		{
			if( isset( $this->pos['minggu'] ) )
			{
				if( intval( $this->pos['minggu'] ) > 0 )
				{
					// $rows= $this->db->select('*')->where('id', intval($this->pos['minggu']))->get(_TBL_COMBO)->row_array();
					// $this->pos['tgl1']=$rows['param_date'];
					// $this->pos['tgl2']=$rows['param_date_after'];
				}
				else
				{
					$rows = $this->db->select( '*' )->where( 'id', $this->pos['term'] )->get( _TBL_COMBO )->row();
					$tgl1 = date( 'Y-m-d' );
					$tgl2 = date( 'Y-m-d' );
					if( $rows )
					{
						$tgl1 = $rows->param_date;
						$tgl2 = $rows->param_date_after;
					}
					$bulan  = $this->db->select( 'id' )->where( 'kelompok', 'minggu' )->where( 'param_date>=', $tgl1 )->where( 'param_date_after<=', $tgl2 )->get( _TBL_COMBO )->result_array();
					$minggu = array_column( $bulan, 'id' );
					// $this->db->where_in('minggu_id', array_column($bulan, 'id'));
				}
			}

			// if (!isset($this->pos['tgl1'])){
			// 	if (isset($this->pos['term'])){
			// 		if (intval($this->pos['term'])){
			// 			$rows= $this->db->select('*')->where('id', intval($this->pos['term']))->get(_TBL_COMBO)->row_array();
			// 			$this->pos['tgl1']=$rows['param_date'];
			// 			$this->pos['tgl2']=$rows['param_date_after'];
			// 		}
			// 	}
			// }
		}

		if( $this->pos )
		{
			if( $this->pos['owner'] )
			{
				if( $this->owner_child )
				{
					$this->db->where_in( 'owner_id', $this->owner_child );
				}
			}
			if( $this->pos['type_ass'] )
			{
				$this->db->where( 'type_ass_id', $this->pos['type_ass'] );
			}
			if( $this->pos['period'] )
			{
				$this->db->where( 'period_id', $this->pos['period'] );
			}

			if( $this->pos['term'] )
			{
				$this->db->where( 'term_id', $this->pos['term'] );
			}

			if( isset( $this->pos['tgl1'] ) )
			{
				$this->db->where( 'created_at>=', $this->pos['tgl1'] );
				$this->db->where( 'created_at<=', $this->pos['tgl2'] );
			}
			elseif( isset( $this->pos['minggu'] ) )
			{
				if( count( $minggu ) > 0 )
				{
					$this->db->where_in( 'minggu_id', $minggu );
				}
				elseif( intval( $this->pos['minggu'] ) > 0 )
				{
					$this->db->where( 'minggu_id', $this->pos['minggu'] );
				}
			}
		}
		else
		{
			$this->db->where( 'period_id', _TAHUN_ID_ );
			$this->db->where( 'term_id', _TERM_ID_ );
		}
	}

	function grap_mitigasi()
	{
		if( $this->pos['owner'] == 0 )
		{
			$this->filter_data();
		}
		else
		{
			if( $this->pos['type'] == '' || $this->pos['type'] == 0 )
			{
				$this->pos['type'] = 128;
			}
			$this->db->where( 'period_id', $this->pos['period'] )->where( 'owner_id', $this->pos['owner'] )->where( 'type_ass_id', $this->pos['type'] );
		}
		$rows_progres = $this->db->select( 'rcsa_detail_id,rcsa_id' )->group_by( 'rcsa_detail_id' )
		->get( _TBL_VIEW_RCSA_MITIGASI_PROGRES )->result_array();

		$jmlmiti        = 0;
		$seratussepuluh = 0;
		$seratus        = 0;
		$semilanpuluh   = 0;
		$tujuhlima      = 0;
		$nol            = 0;

		foreach( $rows_progres as $key => $value )
		{
			$histori = $this->db->where( 'rcsa_id', $value['rcsa_id'] )->where( 'tipe_log', 2 )->where( 'keterangan like', '%final%' )->order_by( 'tanggal', 'desc' )->get( _TBL_VIEW_LOG_APPROVAL )->row_array();

			if( isset( $histori['tanggal'] ) )
			{
				$tgl_final = $histori['tanggal'];

				$this->db->where( 'rcsa_detail_id', $value['rcsa_detail_id'] );
				$rows = $this->db->select( 'rcsa_detail_id as id, aktual, created_at, batas_waktu' )
				->get( _TBL_VIEW_RCSA_MITIGASI_DETAIL )->result_array();

				$jmlmiti += count( $rows );
				foreach( $rows as $row )
				{
					$deadline   = date(
					 'Y-m-d',
					 strtotime( $row['batas_waktu'] ),
					);
					$tgl_finalx = date( 'Y-m-d', strtotime( $tgl_final ) );

					$date1      = date_create( $tgl_finalx );
					$date2      = date_create( $deadline );
					$diffo      = date_diff( $date2, $date1 );
					$nilai_diff = intval( $diffo->format( "%R%a" ) );

					$diff = $this->kepatuhan_mitigasi( $nilai_diff );

					if( intval( $row['aktual'] ) == 0 )
					{
						$nol += 1;
					}
					elseif( $diff == 110 )
					{
						$seratussepuluh += 1;
					}
					elseif( $diff == 100 )
					{
						$seratus += 1;
					}
					elseif( $diff == 90 )
					{
						$semilanpuluh += 1;
					}
					elseif( $diff == 75 )
					{
						$tujuhlima += 1;
					}
				}
			}
		}
		$hasil['total'] = $jmlmiti;
		$hasil['110']   = $seratussepuluh;
		$hasil['100']   = $seratus;
		$hasil['90']    = $semilanpuluh;
		$hasil['75']    = $tujuhlima;
		$hasil['0']     = $nol;
		$hasil['110%']  = ( $jmlmiti > 0 ) ? number_format( ( $seratussepuluh / $jmlmiti ) * 100, 2 ) : 0;
		$hasil['100%']  = ( $jmlmiti > 0 ) ? number_format( ( $seratus / $jmlmiti ) * 100, 2 ) : 0;
		$hasil['90%']   = ( $jmlmiti > 0 ) ? number_format( ( $semilanpuluh / $jmlmiti ) * 100, 2 ) : 0;
		$hasil['75%']   = ( $jmlmiti > 0 ) ? number_format( ( $tujuhlima / $jmlmiti ) * 100, 2 ) : 0;
		$hasil['0%']    = ( $jmlmiti > 0 ) ? number_format( ( $nol / $jmlmiti ) * 100, 2 ) : 0;

		return $hasil;
	}

	function kepatuhan_mitigasi( $nilai )
	{
		if( $nilai < 0 )
		{
			$hasil = "110";
		}
		elseif( $nilai == 0 )
		{
			$hasil = "100";
		}
		elseif( $nilai <= 30 )
		{
			$hasil = "90";
		}
		elseif( $nilai > 30 )
		{
			$hasil = "75";
		}

		return $hasil;
	}

	function grap_mitigasi_old()
	{
		$this->filter_data();
		$rows_progres = $this->db->select( 'rcsa_mitigasi_id, rcsa_mitigasi_detail_id, created_at, target, aktual' )->order_by( 'rcsa_mitigasi_detail_id, created_at desc' )
		// ->get_compiled_select(_TBL_VIEW_RCSA_MITIGASI_PROGRES);
		->get( _TBL_VIEW_RCSA_MITIGASI_PROGRES )->result_array();
		// dumps($rows_progres);
		// die();
		$progres = [];
		$id      = 0;
		$tgl     = '2000/01/01';
		foreach( $rows_progres as $row )
		{
			if( $id !== $row['rcsa_mitigasi_detail_id'] )
			{
				$progres[$row['rcsa_mitigasi_detail_id']] = $row;
			}
			elseif( $row['created_at'] > $tgl )
			{
				$progres[$row['rcsa_mitigasi_detail_id']] = $row;
				$tgl                                      = $row['created_at'];
			}
			$id = $row['rcsa_mitigasi_detail_id'];
		}

		$rows = [];
		foreach( $progres as $key => $row )
		{
			if( array_key_exists( $row['rcsa_mitigasi_id'], $rows ) )
			{
				$rows[$row['rcsa_mitigasi_id']]['target'] += floatval( $row['target'] );
				$rows[$row['rcsa_mitigasi_id']]['aktual'] += floatval( $row['aktual'] );
				++$rows[$row['rcsa_mitigasi_id']]['jml'];
			}
			else
			{
				$rows[$row['rcsa_mitigasi_id']]['target'] = floatval( $row['target'] );
				$rows[$row['rcsa_mitigasi_id']]['aktual'] = floatval( $row['aktual'] );
				$rows[$row['rcsa_mitigasi_id']]['jml']    = 1;
			}
		}
		foreach( $rows as $key => &$row )
		{
			$row['target'] = floatval( $row['target'] ) / $row['jml'];
			$row['aktual'] = floatval( $row['aktual'] ) / $row['jml'];
		}
		unset( $row );

		$mitigasi    = [];
		$mitigasi[1] = [ 'category' => 'Selesai', 'nilai' => 0 ];
		$mitigasi[2] = [ 'category' => 'Belum Selesai, On Schdule', 'nilai' => 0 ];
		$mitigasi[3] = [ 'category' => 'Belum Selesai, Terlambat', 'nilai' => 0 ];
		$mitigasi[4] = [ 'category' => 'Belum Dilaksanakan', 'nilai' => 0 ];
		foreach( $rows as $key => $row )
		{
			if( $row['target'] >= 100 && $row['aktual'] >= 100 )
			{
				++$mitigasi[1]['nilai'];
			}
			elseif( $row['target'] == $row['aktual'] && floatval( $row['target'] ) != 100 )
			{
				++$mitigasi[2]['nilai'];
			}
			elseif( $row['target'] > 0 && floatval( $row['aktual'] ) == 0 )
			{
				++$mitigasi[3]['nilai'];
			}
			else
			{
				++$mitigasi[4]['nilai'];
			}
		}

		return $mitigasi;
	}

	function get_data_grap()
	{

		$mitigasi = $this->grap_mitigasi();
		// $mitigasi = $this->grap_mitigasi_old();

		$hasil['mitigasi'] = $mitigasi;
		return $hasil;
	}
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */
