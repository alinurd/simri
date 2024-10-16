<?php if( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Data extends MX_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	function checklist()
	{
		$checklist = [];
		$check     = $this->db->select( "id" )->get( _TBL_VIEW_RCSA )->result();

		foreach( $check as $key => $value )
		{
			$checklist[] = $value->id;
		}

		return $checklist;
	}

	function simpan_identifikasi( $data )
	{

		$mode = 'add';

		if( isset( $data['txt_aktifitas_id'] ) )
		{
			if( ! empty( trim( $data['txt_aktifitas_id'] ) ) )
			{
				$this->crud->crud_type( 'add' );
				$this->crud->crud_table( _TBL_COMBO );
				$this->crud->crud_field( 'data', $data['txt_aktifitas_id'] );
				$this->crud->crud_field( 'kelompok', 'aktifitas' );
				$this->crud->crud_field( 'active', 1 );
				$this->crud->process_crud();
				$data['aktifitas_id'] = $this->crud->last_id();
			}
		}

		if( isset( $data['txt_sasaran_id'] ) )
		{
			if( ! empty( trim( $data['txt_sasaran_id'] ) ) )
			{
				$this->crud->crud_type( 'add' );
				$this->crud->crud_table( _TBL_COMBO );
				$this->crud->crud_field( 'data', $data['txt_sasaran_id'] );
				$this->crud->crud_field( 'kelompok', 'sasaran-aktivitas' );
				$this->crud->crud_field( 'active', 1 );
				$this->crud->process_crud();
				$data['sasaran_id'] = $this->crud->last_id();
			}
		}

		// if (!empty(trim($data['txt_tahapan_id']))){
		// 	$this->crud->crud_type('add');
		// 	$this->crud->crud_table(_TBL_COMBO);
		// 	$this->crud->crud_field('data', $data['txt_tahapan_id']);
		// 	$this->crud->crud_field('kelompok', 'tahapan-proses');
		// 	$this->crud->crud_field('active', 1);
		// 	$this->crud->process_crud();
		// 	$data['tahapan_id'] = $this->crud->last_id();
		// }
		$peristiwa_id_tmp = [];
		$dampak_id_tmp    = [];
		$penyebab_id_tmp  = [];
		if( isset( $data['txt_penyebab_id'] ) )
		{

			if( ! empty( trim( $data['txt_penyebab_id'] ) ) )
			{
				$this->crud->crud_type( 'add' );
				$this->crud->crud_table( _TBL_LIBRARY );
				$this->crud->crud_field( 'type', 1 );
				$this->crud->crud_field( 'risk_type_no', $data['tipe_risiko_id'] );
				$this->crud->crud_field( 'library', $data['txt_penyebab_id'] );
				$this->crud->crud_field( 'active', 1 );
				$this->crud->process_crud();
				$data['penyebab_id'] = $this->crud->last_id();

				foreach( $data['peristiwa_id_text'] as $pt )
				{
					if( ! empty( trim( $pt ) ) )
					{
						$this->crud->crud_type( 'add' );
						$this->crud->crud_table( _TBL_LIBRARY );
						$this->crud->crud_field( 'type', 2 );
						$this->crud->crud_field( 'risk_type_no', $data['tipe_risiko_id'] );
						$this->crud->crud_field( 'library', $pt );
						$this->crud->crud_field( 'active', 1 );
						$this->crud->process_crud();
						$idsx               = $this->crud->last_id();
						$peristiwa_id_tmp[] = $idsx;

						$this->crud->crud_type( 'add' );
						$this->crud->crud_table( _TBL_LIBRARY_DETAIL );
						$this->crud->crud_field( 'library_no', $data['penyebab_id'] );
						$this->crud->crud_field( 'child_no', $idsx );
						$this->crud->crud_field( 'active', 1 );
						$this->crud->process_crud();
					}
				}

				foreach( $data['dampak_id_text'] as $pt )
				{
					if( ! empty( trim( $pt ) ) )
					{
						$this->crud->crud_type( 'add' );
						$this->crud->crud_table( _TBL_LIBRARY );
						$this->crud->crud_field( 'type', 3 );
						$this->crud->crud_field( 'risk_type_no', $data['tipe_risiko_id'] );
						$this->crud->crud_field( 'library', $pt );
						$this->crud->crud_field( 'active', 1 );
						$this->crud->process_crud();
						$idsx            = $this->crud->last_id();
						$dampak_id_tmp[] = $idsx;

						$this->crud->crud_type( 'add' );
						$this->crud->crud_table( _TBL_LIBRARY_DETAIL );
						$this->crud->crud_field( 'library_no', $data['penyebab_id'] );
						$this->crud->crud_field( 'child_no', $idsx );
						$this->crud->crud_field( 'active', 1 );
						$this->crud->process_crud();
					}
				}
			}
			else
			{
				foreach( $data['peristiwa_id_text'] as $pt )
				{
					if( ! empty( trim( $pt ) ) )
					{
						$this->crud->crud_type( 'add' );
						$this->crud->crud_table( _TBL_LIBRARY );
						$this->crud->crud_field( 'type', 2 );
						$this->crud->crud_field( 'risk_type_no', $data['tipe_risiko_id'] );
						$this->crud->crud_field( 'library', $pt );
						$this->crud->crud_field( 'active', 1 );
						$this->crud->process_crud();
						$idsx                   = $this->crud->last_id();
						$peristiwa_id_tmp[]     = $idsx;
						$data['peristiwa_id'][] = $idsx;

						$this->crud->crud_type( 'add' );
						$this->crud->crud_table( _TBL_LIBRARY_DETAIL );
						$this->crud->crud_field( 'library_no', $data['penyebab_id'] );
						$this->crud->crud_field( 'child_no', $idsx );
						$this->crud->crud_field( 'active', 1 );
						$this->crud->process_crud();
					}
				}

				foreach( $data['dampak_id_text'] as $pt )
				{
					if( ! empty( trim( $pt ) ) )
					{
						$this->crud->crud_type( 'add' );
						$this->crud->crud_table( _TBL_LIBRARY );
						$this->crud->crud_field( 'type', 3 );
						$this->crud->crud_field( 'risk_type_no', $data['tipe_risiko_id'] );
						$this->crud->crud_field( 'library', $pt );
						$this->crud->crud_field( 'active', 1 );
						$this->crud->process_crud();
						$idsx                = $this->crud->last_id();
						$data['dampak_id'][] = $idsx;

						$this->crud->crud_type( 'add' );
						$this->crud->crud_table( _TBL_LIBRARY_DETAIL );
						$this->crud->crud_field( 'library_no', $data['penyebab_id'] );
						$this->crud->crud_field( 'child_no', $idsx );
						$this->crud->crud_field( 'active', 1 );
						$this->crud->process_crud();
					}
				}
			}
		}
		// var_dump( $data["rcsa_id"] );
		// var_dump( _TBL_RCSA_DETAIL );
		// exit;
		$this->crud->crud_table( _TBL_RCSA_DETAIL );
		$this->crud->crud_field( 'rcsa_id', $data['rcsa_id'] );
		$this->crud->crud_field( 'id_kpi', $data['id_kpi'] );
		$this->crud->crud_field( 'aktifitas_id', $data['aktifitas_id'] );

		$this->crud->crud_field( 'fraud_risk', $data['fraud_risk'] );
		$this->crud->crud_field( 'esg_risk', $data['esg_risk'] );
		$this->crud->crud_field( 'smap', $data['smap'] );

		$this->crud->crud_field( 'sasaran_id', $data['sasaran_id'] );
		$this->crud->crud_field( 'tahapan', $data['tahapan'] );
		$this->crud->crud_field( 'klasifikasi_risiko_id', $data['klasifikasi_risiko_id'] );
		$this->crud->crud_field( 'tipe_risiko_id', $data['tipe_risiko_id'] );
		$this->crud->crud_field( 'peristiwa_id', $data['peristiwa_id'] );


		// if( $peristiwa_id_tmp )
		// {
		// 	$peristiwa_id =$peristiwa_id_tmp ;
		// }
		// else
		// {
		// 	$peristiwa_id =$data['peristiwa_id'];
		// }
		// $this->crud->crud_field( 'peristiwa_id', $peristiwa_id );

		if( $penyebab_id_tmp )
		{
			$penyebab_id = implode( ',', $penyebab_id_tmp );
		}
		else
		{
			$penyebab_id = implode( ',', $data['penyebab_id'] );
		}
		if( $dampak_id_tmp )
		{
			$dampak_id = implode( ',', $dampak_id_tmp );
		}
		else
		{
			$dampak_id = implode( ',', $data['dampak_id'] );
		}
		$this->crud->crud_field( 'penyebab_id', $penyebab_id );
		$this->crud->crud_field( 'dampak_id', $dampak_id );

		$this->crud->crud_field( 'risiko_dept', $data['risiko_dept'] );
		$this->crud->crud_field( 'tipe_analisa_no', $data['tipe_analisa_no'] );

		if( intval( $data['tipe_analisa_no'] ) == 1 )
		{
			$this->crud->crud_field( 'like_id', $data['like_id'] );
			$this->crud->crud_field( 'impact_id', $data['impact_id'] );
			$this->crud->crud_field( 'impact_text', $data['impact_text'] );
			$this->crud->crud_field( 'like_text', $data['like_text'] );
		}
		elseif( intval( $data['tipe_analisa_no'] ) == 2 )
		{
			$this->crud->crud_field( 'like_id', $data['like_id_2'] );
			$this->crud->crud_field( 'impact_id', $data['impact_id_2'] );
		}
		elseif( intval( $data['tipe_analisa_no'] ) == 3 )
		{
			$this->crud->crud_field( 'aspek_risiko_id', $data['aspek_risiko_id'] );
			$this->crud->crud_field( 'aspek_det', $data['aspek_det'] );
			$this->crud->crud_field( 'like_id', $data['like_id_3'] );
			$this->crud->crud_field( 'impact_id', $data['impact_id_3'] );
			$this->crud->crud_field( 'impact_text', $data['impact_text_3'] );
			$this->crud->crud_field( 'like_text', $data['like_text_3'] );
		}

		$this->crud->crud_field( 'risiko_inherent', $data['risiko_inherent'] );
		$this->crud->crud_field( 'level_inherent', $data['level_inherent'] );

		if( isset( $data['check_item'] ) )
		{
			$nama_kontrol = implode( '###', $data['check_item'] );
			$this->crud->crud_field( 'nama_kontrol', $nama_kontrol );
		}
		else
		{
			$this->crud->crud_field( 'nama_kontrol', "" );
		}

		$this->crud->crud_field( 'nama_kontrol_note', $data['note_control'] );
		$this->crud->crud_field( 'efek_kontrol', $data['efek_kontrol'] );
		ini_set( 'MAX_EXECUTION_TIME', -1 );


		$upload = upload_image_new( array( 'type' => 'pdf|xlsx|docx|doc|docx|xls', 'nm_file' => 'lampiran', 'path' => 'rcsa', 'thumb' => FALSE ) );

		if( $upload )
		{
			$inputFileName = file_path_relative( 'rcsa/' . $upload['file_name'] );

			$this->crud->crud_field( 'lampiran', $inputFileName );
		}

		$id = intval( $data['rcsa_detail_id'] );
		if( $id > 0 )
		{
			$this->crud->crud_type( 'edit' );
			if( $data['like_residual_id'] == '' || $data['impact_residual_id'] == '' )
			{
				if( intval( $data['tipe_analisa_no'] ) == 3 )
				{
					$this->crud->crud_field( 'like_residual_id', $data['like_id_3'] );
					$this->crud->crud_field( 'impact_residual_id', $data['impact_id_3'] );
					$this->crud->crud_field( 'risiko_residual', $data['risiko_inherent'] );
				}
				elseif( intval( $data['tipe_analisa_no'] ) == 2 )
				{
					$this->crud->crud_field( 'like_residual_id', $data['like_id_2'] );
					$this->crud->crud_field( 'impact_residual_id', $data['impact_id_2'] );
					$this->crud->crud_field( 'risiko_residual', $data['risiko_inherent'] );
				}
			}

			$this->crud->crud_where( [ 'field' => 'id', 'value' => $id ] );
			$this->crud->crud_field( 'updated_by', $this->ion_auth->get_user_name() );

			$mode = 'edit';
		}
		else
		{
			$this->crud->crud_type( 'add' );
			if( intval( $data['tipe_analisa_no'] ) == 3 )
			{
				$this->crud->crud_field( 'like_residual_id', $data['like_id_3'] );
				$this->crud->crud_field( 'impact_residual_id', $data['impact_id_3'] );
			}
			elseif( intval( $data['tipe_analisa_no'] ) == 2 )
			{
				$this->crud->crud_field( 'like_residual_id', $data['like_id_2'] );
				$this->crud->crud_field( 'impact_residual_id', $data['impact_id_2'] );
			}
			else
			{
				$this->crud->crud_field( 'like_residual_id', $data['like_id'] );
				$this->crud->crud_field( 'impact_residual_id', $data['impact_id'] );
			}
			$this->crud->crud_field( 'risiko_residual', $data['risiko_inherent'] );

			// $this->crud->crud_field('like_target_id', $data['like_id']);
			// $this->crud->crud_field('impact_target_id', $data['impact_id']);
			// $this->crud->crud_field('risiko_target', $data['risiko_inherent']);
			$this->crud->crud_field( 'created_by', $this->ion_auth->get_user_name() );
		}

		$this->crud->process_crud();
		if( $id == 0 )
		{
			$id = $this->crud->last_id();
		}

		if( $mode == 'add' )
		{
			$this->crud->crud_table( _TBL_RCSA_DET_LIKE_INDI );
			$this->crud->crud_type( 'edit' );
			$this->crud->crud_where( [ 'field' => 'rcsa_detail_id', 'value' => 0 ] );
			$this->crud->crud_where( [ 'field' => 'created_by', 'value' => $this->ion_auth->get_user_name() ] );
			$this->crud->crud_field( 'rcsa_detail_id', $id );
			$this->crud->process_crud();

			$this->crud->crud_table( _TBL_RCSA_DET_DAMPAK_INDI );
			$this->crud->crud_type( 'edit' );
			$this->crud->crud_where( [ 'field' => 'rcsa_detail_id', 'value' => 0 ] );
			$this->crud->crud_where( [ 'field' => 'created_by', 'value' => $this->ion_auth->get_user_name() ] );
			$this->crud->crud_field( 'rcsa_detail_id', $id );
			$this->crud->process_crud();

			$this->db->query( 'INSERT INTO il_rcsa_det_like_indi(rcsa_detail_id, bk_tipe, kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_4, s_4_min, s_4_max, p_2, s_2_min, s_2_max, p_5, s_5_min, s_5_max, p_3, s_3_min, s_3_max, score, param, created_by) (SELECT rcsa_detail_id, ?, kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_4, s_4_min, s_4_max, p_2, s_2_min, s_2_max, p_5, s_5_min, s_5_max , p_3, s_3_min, s_3_max, score, param, created_by from il_rcsa_det_like_indi WHERE rcsa_detail_id=? AND bk_tipe=1)', [ 2, $id ] );
			// $this->db->query('INSERT INTO il_rcsa_det_like_indi(rcsa_detail_id, bk_tipe, kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_2, s_2_min, s_2_max, p_3, s_3_min, s_3_max, score, param, created_by) (SELECT rcsa_detail_id, ?, kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_2, s_2_min, s_2_max, p_3, s_3_min, s_3_max, score, param, created_by from il_rcsa_det_like_indi WHERE rcsa_detail_id=? AND bk_tipe=1)', [3, $id]);
			$this->db->query( 'INSERT INTO il_rcsa_det_dampak_indi(bk_tipe,rcsa_detail_id,kri_id,created_by, detail) (SELECT ?,rcsa_detail_id,kri_id, created_by, detail from il_rcsa_det_dampak_indi WHERE rcsa_detail_id=? AND bk_tipe=1)', [ 2, $id ] );
			// $this->db->query('INSERT INTO il_rcsa_det_dampak_indi(bk_tipe,rcsa_detail_id,kri_id,created_by) (SELECT ?,rcsa_detail_id,kri_id, created_by from il_rcsa_det_dampak_indi WHERE rcsa_detail_id=? AND bk_tipe=1)', [3, $id]);
		}
		else
		{
			$cek  = $this->db->where( 'bk_tipe', 2 )->where( 'rcsa_detail_id', $id )->get( _TBL_VIEW_RCSA_DET_LIKE_INDI )->result_array();
			$cek2 = $this->db->where( 'bk_tipe', 2 )->where( 'rcsa_detail_id', $id )->get( _TBL_VIEW_RCSA_DET_DAMPAK_INDI )->result_array();
			// dumps($cek2);
			// die();
			if( ! $cek )
			{

				$w = $this->db->query( 'SELECT rcsa_detail_id,  kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_4, s_4_min, s_4_max, p_2, s_2_min, s_2_max, p_5, s_5_min, s_5_max, p_3, s_3_min, s_3_max, score, param, created_by from il_rcsa_det_like_indi WHERE rcsa_detail_id=? AND bk_tipe=1', [ $id ] )->result_array();

				foreach( $w as $key => $value )
				{
					$this->db->query( 'INSERT INTO il_rcsa_det_like_indi(rcsa_detail_id, bk_tipe, kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_4, s_4_min, s_4_max, p_2, s_2_min, s_2_max, p_5, s_5_min, s_5_max, p_3, s_3_min, s_3_max ,score, param, created_by) values(' . $value['rcsa_detail_id'] . ', 2, ' . $value['kri_id'] . ', ' . $value['satuan_id'] . ', ' . $value['pembobotan'] . ', "' . $value['p_1'] . '", ' . $value['s_1_min'] . ', ' . $value['s_1_max'] . ', "' . $value['p_4'] . '", ' . $value['s_4_min'] . ', ' . $value['s_4_max'] . ', "' . $value['p_2'] . '", ' . $value['s_2_min'] . ', ' . $value['s_2_max'] . ', "' . $value['p_5'] . '", ' . $value['s_5_min'] . ', ' . $value['s_5_max'] . ', "' . $value['p_3'] . '", ' . $value['s_3_min'] . ', ' . $value['s_3_max'] . ', ' . $value['score'] . ', "' . $value['param'] . '", "' . $value['created_by'] . '")' );
				}
			}
			else
			{
				$prev   = $this->db->query( 'SELECT kri_id,rcsa_detail_id from il_rcsa_det_like_indi WHERE rcsa_detail_id=' . $id . ' AND bk_tipe=1' )->result_array();
				$jenis1 = [];
				$jenis2 = [];
				// dumps($prev);
				foreach( $prev as $u => $v )
				{
					$jenis1[] = $v['kri_id'];
				}

				foreach( $cek as $u => $v )
				{
					$jenis2[] = $v['kri_id'];
				}

				$diff = array_diff( $jenis2, $jenis1 );

				foreach( $diff as $key => $value )
				{
					$cekagain = $this->db->query( 'SELECT id from il_rcsa_det_like_indi WHERE rcsa_detail_id=' . $id . ' AND bk_tipe=2 AND kri_id=' . $value )->row_array();
					if( $cekagain )
					{
						$this->db->query( 'DELETE FROM il_rcsa_det_like_indi
						WHERE id = ' . $cekagain['id'] );
					}
				}

				foreach( $prev as $u => $v )
				{
					$cekagain = $this->db->query( 'SELECT kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_4, s_4_min, s_4_max, p_2, s_2_min, s_2_max, p_5, s_5_min, s_5_max, p_3, s_3_min, s_3_max, score, param,created_by from il_rcsa_det_like_indi WHERE rcsa_detail_id=' . $v['rcsa_detail_id'] . ' AND bk_tipe=2 AND kri_id=' . $v['kri_id'] )->row_array();

					// dumps($v['kri_id']);
					if( $cekagain )
					{

						foreach( $cek as $key => $value )
						{
							$dd = $this->db->query( 'SELECT kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_4, s_4_min, s_4_max, p_2, s_2_min, s_2_max, p_5, s_5_min, s_5_max, p_3, s_3_min, s_3_max, score, param,created_by from il_rcsa_det_like_indi WHERE rcsa_detail_id=? AND bk_tipe=1', [ $value['rcsa_detail_id'] ] )->result_array();

							foreach( $dd as $o => $i )
							{
								if( isset( $cek[$o] ) )
								{
									$this->db->query( 'UPDATE il_rcsa_det_like_indi SET 
									kri_id=' . $i['kri_id'] . ' ,
									satuan_id=' . $i['satuan_id'] . ' ,
									pembobotan=' . $i['pembobotan'] . ' ,
									p_1="' . $i['p_1'] . '" ,
									s_1_min=' . $i['s_1_min'] . ' ,
									s_1_max=' . $i['s_1_max'] . ' ,
									p_4="' . $i['p_4'] . '",
									s_4_min=' . $i['s_4_min'] . ' ,
									s_4_max=' . $i['s_4_max'] . ' ,
									p_2="' . $i['p_2'] . '",
									s_2_min=' . $i['s_2_min'] . ' ,
									s_2_max=' . $i['s_2_max'] . ' ,
									p_5="' . $i['p_5'] . '",
									s_5_min=' . $i['s_5_min'] . ' ,
									s_5_max=' . $i['s_5_max'] . ' ,
									p_3="' . $i['p_3'] . '",
									s_3_min=' . $i['s_3_min'] . ' ,
									s_3_max=' . $i['s_3_max'] . ' ,
									score=' . $i['score'] . ' ,
									param="' . $i['param'] . '" 
									
									WHERE id=?', [ $cek[$o]['id'] ] );
								}
							}

							$this->crud->crud_table( _TBL_RCSA_DETAIL );
							$this->crud->crud_type( 'edit' );
							if( intval( $data['tipe_analisa_no'] ) == 3 )
							{
								$this->crud->crud_field( 'like_residual_id', $data['like_id_3'] );
								$this->crud->crud_field( 'impact_residual_id', $data['impact_id_3'] );
								$this->crud->crud_field( 'risiko_residual', $data['risiko_inherent'] );
							}
							elseif( intval( $data['tipe_analisa_no'] ) == 2 )
							{
								$this->crud->crud_field( 'like_residual_id', $data['like_id_2'] );
								$this->crud->crud_field( 'impact_residual_id', $data['impact_id_2'] );
								$this->crud->crud_field( 'risiko_residual', $data['risiko_inherent'] );
							}


							$this->crud->crud_where( [ 'field' => 'id', 'value' => $value['rcsa_detail_id'] ] );
							$this->crud->crud_field( 'updated_by', $this->ion_auth->get_user_name() );
							$this->crud->process_crud();
						}
					}
					else
					{

						$vo = $this->db->query( 'SELECT rcsa_detail_id,  kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_4, s_4_min, s_4_max, p_2, s_2_min, s_2_max, p_5, s_5_min, s_5_max, p_3, s_3_min, s_3_max, score, param, created_by from il_rcsa_det_like_indi WHERE rcsa_detail_id=? AND bk_tipe=1 AND kri_id=?', [ $id, $v['kri_id'] ] )->result_array();

						foreach( $vo as $key => $va )
						{
							$this->db->query( 'INSERT INTO il_rcsa_det_like_indi(rcsa_detail_id, bk_tipe, kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_4, s_4_min, s_4_max, p_2, s_2_min, s_2_max, p_5, s_5_min, s_5_max, p_3, s_3_min, s_3_max, score, param, created_by) values(' . $va['rcsa_detail_id'] . ', 2, ' . $va['kri_id'] . ', ' . $va['satuan_id'] . ', ' . $va['pembobotan'] . ', "' . $va['p_1'] . '", ' . $va['s_1_min'] . ', ' . $va['s_1_max'] . ', "' . $va['p_4'] . '", ' . $va['s_4_min'] . ', ' . $va['s_4_max'] . ', "' . $va['p_2'] . '", ' . $va['s_2_min'] . ', ' . $va['s_2_max'] . ', "' . $va['p_5'] . '", ' . $va['s_5_min'] . ', ' . $va['s_5_max'] . ', "' . $va['p_3'] . '", ' . $va['s_3_min'] . ', ' . $va['s_3_max'] . ', ' . $va['score'] . ', "' . $va['param'] . '", "' . $va['created_by'] . '")' );
						}
					}
				}
			}

			if( ! $cek2 )
			{

				$v = $this->db->query( 'SELECT ?,rcsa_detail_id,kri_id, created_by, detail from il_rcsa_det_dampak_indi WHERE rcsa_detail_id=? AND bk_tipe=1', [ 2, $id ] )->result_array();

				foreach( $v as $key => $value )
				{
					$this->db->query( 'INSERT INTO il_rcsa_det_dampak_indi(bk_tipe,rcsa_detail_id,kri_id,created_by, detail) values (2,' . $value['rcsa_detail_id'] . ',' . $value['kri_id'] . ', "' . $value['created_by'] . '", "' . $value['detail'] . '")' );
				}
			}
			else
			{

				$prev   = $this->db->query( 'SELECT jenis_kri_id,rcsa_detail_id from il_view_rcsa_det_dampak_indi WHERE rcsa_detail_id=' . $id . ' AND bk_tipe=1' )->result_array();
				$jenis1 = [];
				$jenis2 = [];

				foreach( $prev as $u => $v )
				{
					$jenis1[] = $v['jenis_kri_id'];
				}

				foreach( $cek2 as $u => $v )
				{
					$jenis2[] = $v['jenis_kri_id'];
				}

				$diff = array_diff( $jenis2, $jenis1 );
				foreach( $diff as $key => $value )
				{
					$cekagain = $this->db->query( 'SELECT id from il_view_rcsa_det_dampak_indi WHERE rcsa_detail_id=' . $id . ' AND bk_tipe=2 AND jenis_kri_id=' . $value )->row_array();
					if( $cekagain )
					{
						$this->db->query( 'DELETE FROM il_rcsa_det_dampak_indi
						WHERE id = ' . $cekagain['id'] );
					}
				}

				foreach( $prev as $u => $v )
				{
					$cekagain = $this->db->query( 'SELECT kri_id, detail from il_view_rcsa_det_dampak_indi WHERE rcsa_detail_id=' . $v['rcsa_detail_id'] . ' AND bk_tipe=2 AND jenis_kri_id=' . $v['jenis_kri_id'] )->row_array();
					if( $cekagain )
					{
						foreach( $cek2 as $key => $value )
						{

							$kriid = $this->db->query( 'SELECT kri_id, detail from il_view_rcsa_det_dampak_indi WHERE rcsa_detail_id=' . $value['rcsa_detail_id'] . ' AND bk_tipe=1 AND jenis_kri_id=' . $value['jenis_kri_id'] )->row_array();
							if( $kriid )
							{
								$this->db->query( 'UPDATE il_rcsa_det_dampak_indi SET 
									kri_id=' . $kriid['kri_id'] . ',
									detail="' . $kriid['detail'] . '" WHERE id=?', [ $value['id'] ] );
							}

							$this->crud->crud_table( _TBL_RCSA_DETAIL );
							$this->crud->crud_type( 'edit' );
							if( intval( $data['tipe_analisa_no'] ) == 3 )
							{
								$this->crud->crud_field( 'like_residual_id', $data['like_id_3'] );
								$this->crud->crud_field( 'impact_residual_id', $data['impact_id_3'] );
								$this->crud->crud_field( 'risiko_residual', $data['risiko_inherent'] );
							}
							elseif( intval( $data['tipe_analisa_no'] ) == 2 )
							{
								$this->crud->crud_field( 'like_residual_id', $data['like_id_2'] );
								$this->crud->crud_field( 'impact_residual_id', $data['impact_id_2'] );
								$this->crud->crud_field( 'risiko_residual', $data['risiko_inherent'] );
							}


							$this->crud->crud_where( [ 'field' => 'id', 'value' => $value['rcsa_detail_id'] ] );
							$this->crud->crud_field( 'updated_by', $this->ion_auth->get_user_name() );
							$this->crud->process_crud();
						}
					}
					else
					{
						$vo = $this->db->query( 'SELECT ?,rcsa_detail_id,kri_id, created_by, detail from il_view_rcsa_det_dampak_indi WHERE rcsa_detail_id=? AND bk_tipe=1 AND jenis_kri_id=' . $v['jenis_kri_id'], [ 2, $id ] )->result_array();

						foreach( $vo as $key => $va )
						{
							$this->db->query( 'INSERT INTO il_rcsa_det_dampak_indi(bk_tipe,rcsa_detail_id,kri_id,created_by, detail) values(2,' . $va['rcsa_detail_id'] . ',' . $va['kri_id'] . ', "' . $va['created_by'] . '", "' . $va['detail'] . '")' );
						}
					}
				}
			}
		}

		$rows = $this->db->where( 'aktifitas_id', $data['aktifitas_id'] )->where( 'rcsa_id', $data['rcsa_id'] )->order_by( 'created_at' )->get( _TBL_RCSA_DETAIL )->result_array();
		$no   = 0;
		foreach( $rows as $row )
		{
			$this->crud->crud_table( _TBL_RCSA_DETAIL );
			$this->crud->crud_type( 'edit' );
			$this->crud->crud_where( [ 'field' => 'id', 'value' => $row['id'] ] );
			$this->crud->crud_field( 'kode_risiko_dept', ++$no );
			$this->crud->process_crud();
		}

		return $id;
	}

	function simpan_identifikasi_awal( $data )
	{
		$mode = 'add';

		if( isset( $data['txt_aktifitas_id'] ) )
		{
			if( ! empty( trim( $data['txt_aktifitas_id'] ) ) )
			{
				$this->crud->crud_type( 'add' );
				$this->crud->crud_table( _TBL_COMBO );
				$this->crud->crud_field( 'data', $data['txt_aktifitas_id'] );
				$this->crud->crud_field( 'kelompok', 'aktifitas' );
				$this->crud->crud_field( 'active', 1 );
				$this->crud->process_crud();
				$data['aktifitas_id'] = $this->crud->last_id();
			}
		}

		if( isset( $data['txt_sasaran_id'] ) )
		{
			if( ! empty( trim( $data['txt_sasaran_id'] ) ) )
			{
				$this->crud->crud_type( 'add' );
				$this->crud->crud_table( _TBL_COMBO );
				$this->crud->crud_field( 'data', $data['txt_sasaran_id'] );
				$this->crud->crud_field( 'kelompok', 'sasaran-aktivitas' );
				$this->crud->crud_field( 'active', 1 );
				$this->crud->process_crud();
				$data['sasaran_id'] = $this->crud->last_id();
			}
		}

		// if (!empty(trim($data['txt_tahapan_id']))){
		// 	$this->crud->crud_type('add');
		// 	$this->crud->crud_table(_TBL_COMBO);
		// 	$this->crud->crud_field('data', $data['txt_tahapan_id']);
		// 	$this->crud->crud_field('kelompok', 'tahapan-proses');
		// 	$this->crud->crud_field('active', 1);
		// 	$this->crud->process_crud();
		// 	$data['tahapan_id'] = $this->crud->last_id();
		// }
		// $peristiwa_id_tmp = [];
		$dampak_id_tmp   = [];
		$penyebab_id_tmp = [];

		// if( isset( $data['txt_penyebab_id'] ) )
		// {
		// 	if( ! empty( trim( $data['txt_penyebab_id'] ) ) )
		// 	{
		// 		$this->crud->crud_type( 'add' );
		// 		$this->crud->crud_table( _TBL_LIBRARY );
		// 		$this->crud->crud_field( 'type', 1 );
		// 		$this->crud->crud_field( 'risk_type_no', $data['tipe_risiko_id'] );
		// 		$this->crud->crud_field( 'library', $data['txt_penyebab_id'] );
		// 		$this->crud->crud_field( 'active', 1 );
		// 		$this->crud->process_crud();
		// 		$data['penyebab_id'] = $this->crud->last_id();

		// 		foreach( $data['peristiwa_id_text'] as $pt )
		// 		{
		// 			if( ! empty( trim( $pt ) ) )
		// 			{
		// 				$this->crud->crud_type( 'add' );
		// 				$this->crud->crud_table( _TBL_LIBRARY );
		// 				$this->crud->crud_field( 'type', 2 );
		// 				$this->crud->crud_field( 'risk_type_no', $data['tipe_risiko_id'] );
		// 				$this->crud->crud_field( 'library', $pt );
		// 				$this->crud->crud_field( 'active', 1 );
		// 				$this->crud->process_crud();
		// 				$idsx               = $this->crud->last_id();
		// 				$peristiwa_id_tmp[] = $idsx;

		// 				$this->crud->crud_type( 'add' );
		// 				$this->crud->crud_table( _TBL_LIBRARY_DETAIL );
		// 				$this->crud->crud_field( 'library_no', $data['penyebab_id'] );
		// 				$this->crud->crud_field( 'child_no', $idsx );
		// 				$this->crud->crud_field( 'active', 1 );
		// 				$this->crud->process_crud();
		// 			}
		// 		}

		// 		foreach( $data['dampak_id_text'] as $pt )
		// 		{
		// 			if( ! empty( trim( $pt ) ) )
		// 			{
		// 				$this->crud->crud_type( 'add' );
		// 				$this->crud->crud_table( _TBL_LIBRARY );
		// 				$this->crud->crud_field( 'type', 3 );
		// 				$this->crud->crud_field( 'risk_type_no', $data['tipe_risiko_id'] );
		// 				$this->crud->crud_field( 'library', $pt );
		// 				$this->crud->crud_field( 'active', 1 );
		// 				$this->crud->process_crud();
		// 				$idsx            = $this->crud->last_id();
		// 				$dampak_id_tmp[] = $idsx;

		// 				$this->crud->crud_type( 'add' );
		// 				$this->crud->crud_table( _TBL_LIBRARY_DETAIL );
		// 				$this->crud->crud_field( 'library_no', $data['penyebab_id'] );
		// 				$this->crud->crud_field( 'child_no', $idsx );
		// 				$this->crud->crud_field( 'active', 1 );
		// 				$this->crud->process_crud();
		// 			}
		// 		}
		// 	}
		// 	else
		// 	{
		// 		foreach( $data['peristiwa_id_text'] as $pt )
		// 		{
		// 			if( ! empty( trim( $pt ) ) )
		// 			{
		// 				$this->crud->crud_type( 'add' );
		// 				$this->crud->crud_table( _TBL_LIBRARY );
		// 				$this->crud->crud_field( 'type', 2 );
		// 				$this->crud->crud_field( 'risk_type_no', $data['tipe_risiko_id'] );
		// 				$this->crud->crud_field( 'library', $pt );
		// 				$this->crud->crud_field( 'active', 1 );
		// 				$this->crud->process_crud();
		// 				$idsx                   = $this->crud->last_id();
		// 				$peristiwa_id_tmp[]     = $idsx;
		// 				$data['peristiwa_id'][] = $idsx;

		// 				$this->crud->crud_type( 'add' );
		// 				$this->crud->crud_table( _TBL_LIBRARY_DETAIL );
		// 				$this->crud->crud_field( 'library_no', $data['penyebab_id'] );
		// 				$this->crud->crud_field( 'child_no', $idsx );
		// 				$this->crud->crud_field( 'active', 1 );
		// 				$this->crud->process_crud();
		// 			}
		// 		}

		// 		foreach( $data['dampak_id_text'] as $pt )
		// 		{
		// 			if( ! empty( trim( $pt ) ) )
		// 			{
		// 				$this->crud->crud_type( 'add' );
		// 				$this->crud->crud_table( _TBL_LIBRARY );
		// 				$this->crud->crud_field( 'type', 3 );
		// 				$this->crud->crud_field( 'risk_type_no', $data['tipe_risiko_id'] );
		// 				$this->crud->crud_field( 'library', $pt );
		// 				$this->crud->crud_field( 'active', 1 );
		// 				$this->crud->process_crud();
		// 				$idsx                = $this->crud->last_id();
		// 				$data['dampak_id'][] = $idsx;

		// 				$this->crud->crud_type( 'add' );
		// 				$this->crud->crud_table( _TBL_LIBRARY_DETAIL );
		// 				$this->crud->crud_field( 'library_no', $data['penyebab_id'] );
		// 				$this->crud->crud_field( 'child_no', $idsx );
		// 				$this->crud->crud_field( 'active', 1 );
		// 				$this->crud->process_crud();
		// 			}
		// 		}
		// 	}
		// }
		$this->crud->crud_table( _TBL_RCSA_DETAIL );
		$this->crud->crud_field( 'rcsa_id', $data['rcsa_id'] );
		$this->crud->crud_field( 'id_kpi', $data['id_kpi'] );
		$this->crud->crud_field( 'aktifitas_id', $data['aktifitas_id'] );

		$this->crud->crud_field( 'fraud_risk', $data['fraud_risk'] );
		$this->crud->crud_field( 'esg_risk', $data['esg_risk'] );
		$this->crud->crud_field( 'smap', $data['smap'] );

		$this->crud->crud_field( 'sasaran_id', $data['sasaran_id'] );
		$this->crud->crud_field( 'tahapan', $data['tahapan'] );
		$this->crud->crud_field( 'klasifikasi_risiko_id', $data['klasifikasi_risiko_id'] );
		$this->crud->crud_field( 'peristiwa_id', $data['peristiwa_id'] );
		$this->crud->crud_field( 'tipe_risiko_id', $data['tipe_risiko_id'] );
		// $this->crud->crud_field( 'penyebab_id', $data['penyebab_id'] );
// doi::dump($data['penyebab_id']);
		// if( $peristiwa_id_tmp )
		// {
		// 	$peristiwa_id = $peristiwa_id_tmp;
		// 	// $peristiwa_id = implode( ',', $peristiwa_id_tmp );//peristiwa lebih dari 1
		// }
		// else
		// {
		// 	$peristiwa_id = $data['peristiwa_id'];
		// 	// $peristiwa_id = implode( ',', $data['peristiwa_id'] );//peristiwa lebih dari 1
		// }
		// $this->crud->crud_field( 'peristiwa_id', $peristiwa_id );

		if( $penyebab_id_tmp )
		{
			$penyebab_id = implode( ',', $penyebab_id_tmp );
		}
		else
		{
			$penyebab_id = implode( ',', $data['penyebab_id'] );
		}
		$this->crud->crud_field( 'penyebab_id', $penyebab_id );

		if( $dampak_id_tmp )
		{
			$dampak_id = implode( ',', $dampak_id_tmp );
		}
		else
		{
			$dampak_id = implode( ',', $data['dampak_id'] );
		}
		$this->crud->crud_field( 'dampak_id', $dampak_id );

		$this->crud->crud_field( 'risiko_dept', $data['risiko_dept'] );

		$id = intval( $data['rcsa_detail_id'] );
		if( $id > 0 )
		{
			$this->crud->crud_type( 'edit' );
			$this->crud->crud_where( [ 'field' => 'id', 'value' => $id ] );
			$this->crud->crud_field( 'updated_by', $this->ion_auth->get_user_name() );
			$mode = 'edit';
		}
		else
		{
			$this->crud->crud_type( 'add' );
			if( intval( $data['tipe_analisa_no'] ) == 3 )
			{
				$this->crud->crud_field( 'like_residual_id', $data['like_id_3'] );
				$this->crud->crud_field( 'impact_residual_id', $data['impact_id_3'] );
			}
			else
			{
				$this->crud->crud_field( 'like_residual_id', $data['like_id'] );
				$this->crud->crud_field( 'impact_residual_id', $data['impact_id'] );
			}
			$this->crud->crud_field( 'risiko_residual', $data['risiko_inherent'] );
			// $this->crud->crud_field('like_target_id', $data['like_id']);
			// $this->crud->crud_field('impact_target_id', $data['impact_id']);
			// $this->crud->crud_field('risiko_target', $data['risiko_inherent']);
			$this->crud->crud_field( 'created_by', $this->ion_auth->get_user_name() );
		}
		$this->crud->process_crud();
		if( $id == 0 )
		{
			$id = $this->crud->last_id();
		}

		if( $mode == 'add' )
		{

			$this->crud->crud_table( _TBL_RCSA_DET_LIKE_INDI );
			$this->crud->crud_type( 'edit' );
			$this->crud->crud_where( [ 'field' => 'rcsa_detail_id', 'value' => 0 ] );
			$this->crud->crud_where( [ 'field' => 'created_by', 'value' => $this->ion_auth->get_user_name() ] );
			$this->crud->crud_field( 'rcsa_detail_id', $id );
			$this->crud->process_crud();

			$this->crud->crud_table( _TBL_RCSA_DET_DAMPAK_INDI );
			$this->crud->crud_type( 'edit' );
			$this->crud->crud_where( [ 'field' => 'rcsa_detail_id', 'value' => 0 ] );
			$this->crud->crud_where( [ 'field' => 'created_by', 'value' => $this->ion_auth->get_user_name() ] );
			$this->crud->crud_field( 'rcsa_detail_id', $id );
			$this->crud->process_crud();

			$this->db->query( 'INSERT INTO il_rcsa_det_like_indi(rcsa_detail_id, bk_tipe, kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_4, s_4_min, s_4_max, p_2, s_2_min, s_2_max, p_5, s_5_min, s_5_max, p_3, s_3_min, s_3_max, score, param, created_by) (SELECT rcsa_detail_id, ?, kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_4, s_4_min, s_4_max, p_2, s_2_min, s_2_max, p_5, s_5_min, s_5_max, p_3, s_3_min, s_3_max, score, param, created_by from il_rcsa_det_like_indi WHERE rcsa_detail_id=? AND bk_tipe=1)', [ 2, $id ] );
			// $this->db->query('INSERT INTO il_rcsa_det_like_indi(rcsa_detail_id, bk_tipe, kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_2, s_2_min, s_2_max, p_3, s_3_min, s_3_max, score, param, created_by) (SELECT rcsa_detail_id, ?, kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_2, s_2_min, s_2_max, p_3, s_3_min, s_3_max, score, param, created_by from il_rcsa_det_like_indi WHERE rcsa_detail_id=? AND bk_tipe=1)', [3, $id]);
			$this->db->query( 'INSERT INTO il_rcsa_det_dampak_indi(bk_tipe,rcsa_detail_id,kri_id,created_by, detail) (SELECT ?,rcsa_detail_id,kri_id, created_by, detail from il_rcsa_det_dampak_indi WHERE rcsa_detail_id=? AND bk_tipe=1)', [ 2, $id ] );
			// $this->db->query('INSERT INTO il_rcsa_det_dampak_indi(bk_tipe,rcsa_detail_id,kri_id,created_by) (SELECT ?,rcsa_detail_id,kri_id, created_by from il_rcsa_det_dampak_indi WHERE rcsa_detail_id=? AND bk_tipe=1)', [3, $id]);
		}
		else
		{
			$cek  = $this->db->where( 'bk_tipe', 2 )->where( 'rcsa_detail_id', $id )->get( _TBL_RCSA_DET_LIKE_INDI )->result_array();
			$cek2 = $this->db->where( 'bk_tipe', 2 )->where( 'rcsa_detail_id', $id )->get( _TBL_RCSA_DET_DAMPAK_INDI )->result_array();

			if( ! $cek && ! $cek2 )
			{
				$qLike = 'SELECT rcsa_detail_id, ?, kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_4, s_4_min, s_4_max, p_2, s_2_min, s_2_max, p_5, s_5_min, s_5_max, p_3, s_3_min, s_3_max, score, param, created_by from il_rcsa_det_like_indi WHERE rcsa_detail_id=? AND bk_tipe=1';
				$rLike = $this->db->query( $qLike, [ 2, $id ] )->row_array();

				if( $rLike !== NULL )
				{
					$this->db->query( 'INSERT INTO il_rcsa_det_like_indi(rcsa_detail_id, bk_tipe, kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_4, s_4_min, s_4_max, p_2, s_2_min, s_2_max, p_5, s_5_min, s_5_max.p_3, s_3_min, s_3_max, score, param, created_by) (' . $qLike . ')', [ 2, $id ] );
				}

				$qDampak = 'SELECT ?,rcsa_detail_id,kri_id, created_by, detail from il_rcsa_det_dampak_indi WHERE rcsa_detail_id=? AND bk_tipe=1';
				$rDampak = $this->db->query( $qDampak, [ 2, $id ] )->row_array();
				if( $rDampak !== NULL )
				{
					$this->db->query( 'INSERT INTO il_rcsa_det_dampak_indi(bk_tipe,rcsa_detail_id,kri_id,created_by, detail) (' . $qDampak . ')', [ 2, $id ] );
				}
			}
		}

		$rows = $this->db->where( 'aktifitas_id', $data['aktifitas_id'] )->where( 'rcsa_id', $data['rcsa_id'] )->order_by( 'created_at' )->get( _TBL_RCSA_DETAIL )->result_array();
		$no   = 0;
		foreach( $rows as $row )
		{
			$this->crud->crud_table( _TBL_RCSA_DETAIL );
			$this->crud->crud_type( 'edit' );
			$this->crud->crud_where( [ 'field' => 'id', 'value' => $row['id'] ] );
			$this->crud->crud_field( 'kode_risiko_dept', ++$no );
			$this->crud->process_crud();
		}

		return $id;
	}

	function simpan_evaluasi( $data )
	{
		// dumps($data['sts_save_evaluasi']);
		// die();
		$this->crud->crud_table( _TBL_RCSA_DETAIL );
		$this->crud->crud_field( 'like_residual_id', $data['like_residual_id'] );
		$this->crud->crud_field( 'impact_residual_id', $data['impact_residual_id'] );

		$this->crud->crud_field( 'risiko_residual', $data['risiko_residual'] );
		$this->crud->crud_field( 'level_residual', $data['level_residual'] );
		$this->crud->crud_field( 'treatment_id', $data['treatment_id'] );
		$this->crud->crud_field( 'efek_mitigasi', $data['efek_mitigasi'] );
		$this->crud->crud_field( 'sts_save_evaluasi', 1 );

		$id = intval( $data['rcsa_detail_id'] );

		if( $id > 0 )
		{
			$this->crud->crud_type( 'edit' );
			$this->crud->crud_where( [ 'field' => 'id', 'value' => $id ] );
			$this->crud->crud_field( 'updated_by', $this->ion_auth->get_user_name() );
		}

		$this->crud->process_crud();

		if( $id == 0 )
		{
			$id = $this->crud->last_id();
		}
		if( $data['sts_save_evaluasi'] == 0 )
		{

			$this->db->query( 'INSERT INTO il_rcsa_det_like_indi(rcsa_detail_id, bk_tipe, kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_4, s_4_min, s_4_max, p_2, s_2_min, s_2_max, p_5, s_5_min, s_5_max, p_3, s_3_min, s_3_max, score, param, created_by) (SELECT rcsa_detail_id, ?, kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_4, s_4_min, s_4_max, p_2, s_2_min, s_2_max, p_5, s_5_min, s_5_max, p_3, s_3_min, s_3_max, score, param, created_by from il_rcsa_det_like_indi WHERE rcsa_detail_id=? AND bk_tipe=2)', [ 3, $id ] );

			$this->db->query( 'INSERT INTO il_rcsa_det_dampak_indi(bk_tipe,rcsa_detail_id,kri_id,created_by, detail) (SELECT ?,rcsa_detail_id,kri_id, created_by, detail from il_rcsa_det_dampak_indi WHERE rcsa_detail_id=? AND bk_tipe=2)', [ 3, $id ] );

			// $this->crud->crud_field('like_target_id', $data['like_residual_id']);
			// $this->crud->crud_field('impact_target_id', $data['impact_residual_id']);
			// $this->crud->crud_field('risiko_target', $data['risiko_residual']);

			$this->crud->crud_table( _TBL_RCSA_DETAIL );
			$this->crud->crud_type( 'edit' );
			$this->crud->crud_field( 'like_target_id', $data['like_residual_id'] );
			$this->crud->crud_field( 'impact_target_id', $data['impact_residual_id'] );
			$this->crud->crud_field( 'risiko_target', $data['risiko_residual'] );

			$this->crud->crud_where( [ 'field' => 'id', 'value' => $id ] );
			$this->crud->crud_field( 'updated_by', $this->ion_auth->get_user_name() );
			$this->crud->process_crud();
		}
		else
		{

			$cek  = $this->db->where( 'bk_tipe', 3 )->where( 'rcsa_detail_id', $id )->get( _TBL_VIEW_RCSA_DET_LIKE_INDI )->result_array();
			$cek2 = $this->db->where( 'bk_tipe', 3 )->where( 'rcsa_detail_id', $id )->get( _TBL_VIEW_RCSA_DET_DAMPAK_INDI )->result_array();



			if( ! $cek )
			{

				$w = $this->db->query( 'SELECT rcsa_detail_id,  kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_4, s_4_min, s_4_max, p_2, s_2_min, s_2_max, p_5, s_5_min, s_5_max, p_3, s_3_min, s_3_max, score, param, created_by from il_rcsa_det_like_indi WHERE rcsa_detail_id=? AND bk_tipe=2', [ $id ] )->result_array();


				foreach( $w as $key => $value )
				{
					$this->db->query( 'INSERT INTO il_rcsa_det_like_indi(rcsa_detail_id, bk_tipe, kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_4, s_4_min, s_4_max, p_2, s_2_min, s_2_max, p_5, s_5_min, s_5_max, p_3, s_3_min, s_3_max, score, param, created_by) values(' . $value['rcsa_detail_id'] . ', 3, ' . $value['kri_id'] . ', ' . $value['satuan_id'] . ', ' . $value['pembobotan'] . ', "' . $value['p_1'] . '", ' . $value['s_1_min'] . ', ' . $value['s_1_max'] . ', "' . $value['p_4'] . '", ' . $value['s_4_min'] . ', ' . $value['s_4_max'] . ', "' . $value['p_2'] . '", ' . $value['s_2_min'] . ', ' . $value['s_2_max'] . ', "' . $value['p_5'] . '", ' . $value['s_5_min'] . ', ' . $value['s_5_max'] . ', "' . $value['p_3'] . '", ' . $value['s_3_min'] . ', ' . $value['s_3_max'] . ', ' . $value['score'] . ', "' . $value['param'] . '", "' . $value['created_by'] . '")' );
				}
			}
			else
			{
				$prev   = $this->db->query( 'SELECT kri_id,rcsa_detail_id from il_rcsa_det_like_indi WHERE rcsa_detail_id=' . $id . ' AND bk_tipe=2' )->result_array();
				$jenis1 = [];
				$jenis2 = [];
				// dumps($prev);
				foreach( $prev as $u => $v )
				{
					$jenis1[] = $v['kri_id'];
				}

				foreach( $cek as $u => $v )
				{
					$jenis2[] = $v['kri_id'];
				}

				$diff = array_diff( $jenis2, $jenis1 );

				foreach( $diff as $key => $value )
				{
					$cekagain = $this->db->query( 'SELECT id from il_rcsa_det_like_indi WHERE rcsa_detail_id=' . $id . ' AND bk_tipe=3 AND kri_id=' . $value )->row_array();
					if( $cekagain )
					{
						$this->db->query( 'DELETE FROM il_rcsa_det_like_indi
						WHERE id = ' . $cekagain['id'] );
					}
				}

				foreach( $prev as $u => $v )
				{
					$cekagain = $this->db->query( 'SELECT kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_4, s_4_min, s_4_max, p_2, s_2_min, s_2_max, p_5, s_5_min, s_5_max, p_3, s_3_min, s_3_max, score, param,created_by from il_rcsa_det_like_indi WHERE rcsa_detail_id=' . $v['rcsa_detail_id'] . ' AND bk_tipe=3 AND kri_id=' . $v['kri_id'] )->row_array();

					// dumps($v['kri_id']);
					if( $cekagain )
					{
						foreach( $cek as $key => $value )
						{
							$dd = $this->db->query( 'SELECT kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_4, s_4_min, s_4_max, p_2, s_2_min, s_2_max, p_5, s_5_min, s_5_max, p_3, s_3_min, s_3_max, score, param, created_by from il_rcsa_det_like_indi WHERE rcsa_detail_id=? AND bk_tipe=2', [ $value['rcsa_detail_id'] ] )->result_array();

							foreach( $dd as $o => $i )
							{
								if( isset( $cek[$o] ) )
								{
									$this->db->query( 'UPDATE il_rcsa_det_like_indi SET 
									kri_id=' . $i['kri_id'] . ' ,
									satuan_id=' . $i['satuan_id'] . ' ,
									pembobotan=' . $i['pembobotan'] . ' ,
									p_1="' . $i['p_1'] . '" ,
									s_1_min=' . $i['s_1_min'] . ' ,
									s_1_max=' . $i['s_1_max'] . ' ,
									p_4="' . $i['p_4'] . '",
									s_4_min=' . $i['s_4_min'] . ' ,
									s_4_max=' . $i['s_4_max'] . ' ,
									p_2="' . $i['p_2'] . '",
									s_2_min=' . $i['s_2_min'] . ' ,
									s_2_max=' . $i['s_2_max'] . ' ,
									p_5="' . $i['p_5'] . '",
									s_5_min=' . $i['s_5_min'] . ' ,
									s_5_max=' . $i['s_5_max'] . ' ,
									p_3="' . $i['p_3'] . '",
									s_3_min=' . $i['s_3_min'] . ' ,
									s_3_max=' . $i['s_3_max'] . ' ,
									score=' . $i['score'] . ' ,
									param="' . $i['param'] . '" 
									
									WHERE id=?', [ $cek[$o]['id'] ] );
								}
							}
							$this->crud->crud_table( _TBL_RCSA_DETAIL );
							$this->crud->crud_type( 'edit' );
							$this->crud->crud_field( 'like_target_id', $data['like_residual_id'] );
							$this->crud->crud_field( 'impact_target_id', $data['impact_residual_id'] );
							$this->crud->crud_field( 'risiko_target', $data['risiko_residual'] );

							$this->crud->crud_where( [ 'field' => 'id', 'value' => $value['rcsa_detail_id'] ] );
							$this->crud->crud_field( 'updated_by', $this->ion_auth->get_user_name() );
							$this->crud->process_crud();
						}
					}
					else
					{

						$vo = $this->db->query( 'SELECT rcsa_detail_id,  kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_4, s_4_min, s_4_max, p_2, s_2_min, s_2_max, p_5, s_5_min, s_5_max, p_3, s_3_min, s_3_max, score, param, created_by from il_rcsa_det_like_indi WHERE rcsa_detail_id=? AND bk_tipe=2 AND kri_id=?', [ $id, $v['kri_id'] ] )->result_array();

						foreach( $vo as $key => $va )
						{
							$this->db->query( 'INSERT INTO il_rcsa_det_like_indi(rcsa_detail_id, bk_tipe, kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_4, s_4_min, s_4_max, p_2, s_2_min, s_2_max, p_5, s_5_min, s_5_max, p_3, s_3_min, s_3_max, score, param, created_by) values(' . $va['rcsa_detail_id'] . ', 3, ' . $va['kri_id'] . ', ' . $va['satuan_id'] . ', ' . $va['pembobotan'] . ', "' . $va['p_1'] . '", ' . $va['s_1_min'] . ', ' . $va['s_1_max'] . ', "' . $va['p_4'] . '", ' . $va['s_4_min'] . ', ' . $va['s_4_max'] . ', "' . $va['p_2'] . '", ' . $va['s_2_min'] . ', ' . $va['s_2_max'] . ', "' . $va['p_5'] . '", ' . $va['s_5_min'] . ', ' . $va['s_5_max'] . ', "' . $va['p_3'] . '", ' . $va['s_3_min'] . ', ' . $va['s_3_max'] . ', ' . $va['score'] . ', "' . $va['param'] . '", "' . $va['created_by'] . '")' );
						}
					}
				}
			}

			if( ! $cek2 )
			{
				$v = $this->db->query( 'SELECT ?,rcsa_detail_id,kri_id, created_by, detail from il_rcsa_det_dampak_indi WHERE rcsa_detail_id=? AND bk_tipe=2', [ 3, $id ] )->result_array();

				foreach( $v as $key => $value )
				{
					$this->db->query( 'INSERT INTO il_rcsa_det_dampak_indi(bk_tipe,rcsa_detail_id,kri_id,created_by, detail) values(3,' . $value['rcsa_detail_id'] . ',' . $value['kri_id'] . ', "' . $value['created_by'] . '", "' . $value['detail'] . '")' );
				}
			}
			else
			{

				$prev   = $this->db->query( 'SELECT jenis_kri_id,rcsa_detail_id from il_view_rcsa_det_dampak_indi WHERE rcsa_detail_id=' . $id . ' AND bk_tipe=2' )->result_array();
				$jenis1 = [];
				$jenis2 = [];

				foreach( $prev as $u => $v )
				{
					$jenis1[] = $v['jenis_kri_id'];
				}

				foreach( $cek2 as $u => $v )
				{
					$jenis2[] = $v['jenis_kri_id'];
				}

				$diff = array_diff( $jenis2, $jenis1 );
				foreach( $diff as $key => $value )
				{
					$cekagain = $this->db->query( 'SELECT id from il_view_rcsa_det_dampak_indi WHERE rcsa_detail_id=' . $id . ' AND bk_tipe=3 AND jenis_kri_id=' . $value )->row_array();
					if( $cekagain )
					{
						$this->db->query( 'DELETE FROM il_rcsa_det_dampak_indi
						WHERE id = ' . $cekagain['id'] );
					}
				}

				foreach( $prev as $u => $v )
				{
					$cekagain = $this->db->query( 'SELECT kri_id, detail from il_view_rcsa_det_dampak_indi WHERE rcsa_detail_id=' . $v['rcsa_detail_id'] . ' AND bk_tipe=3 AND jenis_kri_id=' . $v['jenis_kri_id'] )->row_array();
					if( $cekagain )
					{
						foreach( $cek2 as $key => $value )
						{


							$kriid = $this->db->query( 'SELECT kri_id, detail from il_view_rcsa_det_dampak_indi WHERE rcsa_detail_id=' . $value['rcsa_detail_id'] . ' AND bk_tipe=2 AND jenis_kri_id=' . $value['jenis_kri_id'] )->row_array();
							if( $kriid )
							{

								$this->db->query( 'UPDATE il_rcsa_det_dampak_indi SET 
										kri_id=' . $kriid['kri_id'] . ',
										detail="' . $kriid['detail'] . '" WHERE id=?', [ $value['id'] ] );
							}
							$this->crud->crud_table( _TBL_RCSA_DETAIL );
							$this->crud->crud_type( 'edit' );
							$this->crud->crud_field( 'like_target_id', $data['like_residual_id'] );
							$this->crud->crud_field( 'impact_target_id', $data['impact_residual_id'] );
							$this->crud->crud_field( 'risiko_target', $data['risiko_residual'] );

							$this->crud->crud_where( [ 'field' => 'id', 'value' => $value['rcsa_detail_id'] ] );
							$this->crud->crud_field( 'updated_by', $this->ion_auth->get_user_name() );
							$this->crud->process_crud();
						}
					}
					else
					{
						$vo = $this->db->query( 'SELECT ?,rcsa_detail_id,kri_id, created_by, detail from il_view_rcsa_det_dampak_indi WHERE rcsa_detail_id=? AND bk_tipe=2 AND jenis_kri_id=' . $v['jenis_kri_id'], [ 3, $id ] )->result_array();

						foreach( $vo as $key => $va )
						{
							$this->db->query( 'INSERT INTO il_rcsa_det_dampak_indi(bk_tipe,rcsa_detail_id,kri_id,created_by, detail) values(3,' . $va['rcsa_detail_id'] . ',' . $va['kri_id'] . ', "' . $va['created_by'] . '", "' . $va['detail'] . '")' );
						}
					}
				}
			}
		}



		return $id;
	}


	function get_data_minggu( $id )
	{
		$rows = $this->db->select( '*' )->where( 'id', $id )->get( _TBL_COMBO )->row();
		$tgl1 = date( 'Y-m-d' );
		$tgl2 = date( 'Y-m-d' );
		if( $rows )
		{
			$tgl1 = $rows->param_date;
			$tgl2 = $rows->param_date_after;
		}
		$rows       = $this->db->select( '*' )->where( 'kelompok', 'minggu' )->where( 'param_date>=', $tgl1 )->where( 'param_date_after<=', $tgl2 )->get( _TBL_COMBO )->result();
		$option[""] = _l( 'cbo_select' );
		foreach( $rows as $row )
		{
			$option[$row->id] = $row->param_string . ' (' . date( 'd-m-Y', strtotime( $row->param_date ) ) . ' s.d ' . date( 'd-m-Y', strtotime( $row->param_date_after ) ) . ')';
		}

		return $option;
	}

	function simpan_target( $data )
	{
		$this->crud->crud_table( _TBL_RCSA_DETAIL );
		$this->crud->crud_field( 'like_target_id', $data['like_target_id'] );
		$this->crud->crud_field( 'impact_target_id', $data['impact_target_id'] );

		$this->crud->crud_field( 'risiko_target', $data['risiko_target'] );
		$this->crud->crud_field( 'level_target', $data['level_target'] );

		$id = intval( $data['rcsa_detail_id'] );
		if( $id > 0 )
		{
			$this->crud->crud_type( 'edit' );
			$this->crud->crud_where( [ 'field' => 'id', 'value' => $id ] );
			$this->crud->crud_field( 'updated_by', $this->ion_auth->get_user_name() );
		}
		else
		{
			$this->crud->crud_type( 'add' );
			$this->crud->crud_field( 'created_by', $this->ion_auth->get_user_name() );
		}
		$this->crud->process_crud();
		if( $id == 0 )
		{
			$id = $this->crud->last_id();
		}

		return $id;
	}

	function simpan_mitigasi( $data )
	{
		$id = intval( $data['id'] );
		$this->crud->crud_table( _TBL_RCSA_MITIGASI );
		$this->crud->crud_field( 'rcsa_detail_id', $data['rcsa_detail_id'] );
		$this->crud->crud_field( 'mitigasi', $data['mitigasi'] );
		$this->crud->crud_field( 'batas_waktu', $data['batas_waktu_submit'], 'date' );
		$this->crud->crud_field( 'biaya', $data['biaya'], 'currency' );
		$this->crud->crud_field( 'penanggung_jawab_id', $data['penanggung_jawab_id'] );
		$this->crud->crud_field( 'koordinator_id', $data['koordinator_id'] );
		$this->crud->crud_field( 'status_jangka', $data['status_jangka'] );
		$this->crud->crud_field( 'reminder_email', $data['email_reminder'] );

		if( $id > 0 )
		{
			$this->crud->crud_type( 'edit' );
			$this->crud->crud_where( [ 'field' => 'id', 'value' => $id ] );
			$this->crud->crud_field( 'updated_by', $this->ion_auth->get_user_name() );
		}
		else
		{
			$this->crud->crud_type( 'add' );
			$this->crud->crud_field( 'created_by', $this->ion_auth->get_user_name() );
		}
		$this->crud->process_crud();
		if( $id == 0 )
		{
			$id = $this->crud->last_id();
		}

		return $id;
	}

	function simpan_aktifitas_mitigasi( $data )
	{
		$id = intval( $data['id'] );
		$this->crud->crud_table( _TBL_RCSA_MITIGASI_DETAIL );
		$this->crud->crud_field( 'rcsa_mitigasi_id', $data['rcsa_mitigasi_id'] );
		$this->crud->crud_field( 'aktifitas_mitigasi', $data['aktifitas_mitigasi'] );
		$this->crud->crud_field( 'batas_waktu_detail', $data['batas_waktu_detail_submit'], 'date' );
		$this->crud->crud_field( 'penanggung_jawab_detail_id', $data['penanggung_jawab_detail_id'] );
		$this->crud->crud_field( 'koordinator_detail_id', $data['koordinator_detail_id'] );

		if( $id > 0 )
		{
			$this->crud->crud_type( 'edit' );
			$this->crud->crud_where( [ 'field' => 'id', 'value' => $id ] );
			$this->crud->crud_field( 'updated_by', $this->ion_auth->get_user_name() );
		}
		else
		{
			$this->crud->crud_type( 'add' );
			$this->crud->crud_field( 'created_by', $this->ion_auth->get_user_name() );
		}
		$this->crud->process_crud();
		if( $id == 0 )
		{
			$id = $this->crud->last_id();
		}

		return $id;
	}
	//  simpan like indi 
	function simpan_like_indi( $data )
	{
		$id = 0;
		if( isset( $data['id'] ) )
		{
			$id = intval( $data['id'] );
		}
		if( $data['bk_tipe'] == 1 )
		{
			if( isset( $data['txt_like'] ) )
			{
				if( ! empty( trim( $data['txt_like'] ) ) )
				{
					$this->crud->crud_type( 'add' );
					$this->crud->crud_table( _TBL_COMBO );
					$this->crud->crud_field( 'data', $data['txt_like'] );
					$this->crud->crud_field( 'kelompok', 'kri' );
					$this->crud->crud_field( 'active', 1 );
					$this->crud->process_crud();
					$data['kri_id'] = $this->crud->last_id();
				}
			}

			$this->crud->crud_table( _TBL_RCSA_DET_LIKE_INDI );
			$this->crud->crud_field( 'rcsa_detail_id', $data['rcsa_detail_no'] );
			$this->crud->crud_field( 'bk_tipe', $data['bk_tipe'] );
			$this->crud->crud_field( 'kri_id', $data['kri_id'] );
			$this->crud->crud_field( 'satuan_id', $data['satuan_id'] );
			$this->crud->crud_field( 'pembobotan', $data['pembobotan'] );
			$this->crud->crud_field( 'p_1', $data['p_1'] );
			$this->crud->crud_field( 's_1_min', $data['s_1_min'] );
			$this->crud->crud_field( 's_1_max', $data['s_1_max'] );
			$this->crud->crud_field( 'p_4', $data['p_4'] );
			$this->crud->crud_field( 's_4_min', $data['s_4_min'] );
			$this->crud->crud_field( 's_4_max', $data['s_4_max'] );
			$this->crud->crud_field( 'p_2', $data['p_2'] );
			$this->crud->crud_field( 's_2_min', $data['s_2_min'] );
			$this->crud->crud_field( 's_2_max', $data['s_2_max'] );
			$this->crud->crud_field( 'p_5', $data['p_5'] );
			$this->crud->crud_field( 's_5_min', $data['s_5_min'] );
			$this->crud->crud_field( 's_5_max', $data['s_5_max'] );
			$this->crud->crud_field( 'p_3', $data['p_3'] );
			$this->crud->crud_field( 's_3_min', $data['s_3_min'] );
			$this->crud->crud_field( 's_3_max', $data['s_3_max'] );
			$this->crud->crud_field( 'score', $data['score'] );
		}
		else
		{
			$this->crud->crud_table( _TBL_RCSA_DET_LIKE_INDI );
			$this->crud->crud_field( 'score', $data['score'] );
		}

		if( $id > 0 )
		{
			$this->crud->crud_type( 'edit' );
			$this->crud->crud_where( [ 'field' => 'id', 'value' => $id ] );
			$this->crud->crud_field( 'updated_by', $this->ion_auth->get_user_name() );
		}
		else
		{
			$this->crud->crud_type( 'add' );
			$this->crud->crud_field( 'created_by', $this->ion_auth->get_user_name() );
		}
		$this->crud->process_crud();
		if( $id == 0 )
		{
			$id = $this->crud->last_id();
		}

		// $rows=$this->db->where('rcsa_detail_id', intval($data['rcsa_detail_no']))->group_start()->where('rcsa_detail_id',0)->or_where('created_by', $this->ion_auth->get_user_name())->group_end()->get(_TBL_VIEW_RCSA_DET_LIKE_INDI)->result_array();
		$hasil       = $this->update_list_indi_like( $data );
		$hasil['id'] = $id;
		return $hasil;
	}

	// update like indi 
	function update_list_indi_like( $data = [] )
	{

		$rows = $this->db->where( 'category', 'likelihood' )->order_by( 'code' )->get( _TBL_LEVEL )->result_array();

		$x = [];
		foreach( $rows as $row )
		{
			$x[$row['code']] = $row;
		}
		$mLike = $x;

		$rows = $this->db->where( 'bk_tipe', $data['bk_tipe'] )->where( 'rcsa_detail_id', intval( $data['rcsa_detail_no'] ) )->or_group_start()->where( 'rcsa_detail_id', 0 )->where( 'created_by', $this->ion_auth->get_user_name() )->group_end()->get( _TBL_VIEW_RCSA_DET_LIKE_INDI )->result_array();


		$ttl = 0;
		foreach( $rows as $row )
		{
			$nilai = ( $row['pencapaian'] / 100 ) * ( $row['pembobotan'] * count( $rows ) );
			$ttl += floatval( $nilai );
		}

		$jml = round( ( ( count( $rows ) * 5 ) - count( $rows ) ) / 5, 1 );

		$last = count( $rows ) + $jml;


		$param[1] = [ 'min' => count( $rows ), 'mak' => $last ];
		$param[2] = [ 'min' => $last, 'mak' => $last += $jml ];
		$param[3] = [ 'min' => $last, 'mak' => $last += $jml ];
		$param[4] = [ 'min' => $last, 'mak' => $last += $jml ];
		$param[5] = [ 'min' => $last, 'mak' => $last += $jml ];


		foreach( $param as $key => $row )
		{
			if( $key == 1 )
			{
				if( $ttl <= $row['min'] )
				{
					$like = $key;
					break;
				}
				elseif( $ttl >= $row['min'] && $ttl < $row['mak'] )
				{
					$like = $key;
					break;
				}
			}
			elseif( $key == 5 )
			{
				if( $ttl >= $row['mak'] )
				{
					$like = $key;
					break;
				}
				elseif( $ttl >= $row['min'] && $ttl < $row['mak'] )
				{
					$like = $key;
					break;
				}
			}
			elseif( $ttl >= $row['min'] && $ttl < $row['mak'] )
			{
				$like = $key;
				break;
			}
		}

		if( array_key_exists( $like, $mLike ) )
		{

			$like_no = $mLike[$like]['id'];
			$likes   = $mLike[$like]['code'] . ' - ' . $mLike[$like]['level'];
		}
		$color  = '#ffffff';
		$tcolor = '#000000';
		if( array_key_exists( intval( $like ), $mLike ) )
		{
			$color  = $mLike[intval( $like )]['warna'];
			$tcolor = '#ffffff';
			$like .= ' - ' . $mLike[intval( $like )]['level'];
		}

		$hasil['like_no'] = $like_no;
		$hasil['likes']   = $likes;
		$hasil['color']   = $color;
		$hasil['tcolor']  = $tcolor;
		$hasil['ttl']     = $ttl;
		$hasil['param']   = $param;
		$hasil['mLike']   = $mLike;

		$x = [ 'id' => 0, 'level_color' => '-', 'level_risk_no' => 0, 'code' => 0, 'like_code' => 0, 'impact_code' => 0, 'color' => '#FAFAFA', 'color_text' => '#000000', 'text' => '-', 'nil' => 0 ];

		$this->db->where( 'likelihood', intval( $like_no ) );
		$this->db->where( 'impact', intval( $data['dampak_id'] ) );
		$rows = $this->db->get( _TBL_VIEW_LEVEL_MAPPING )->row_array();
		if( $rows )
		{
			$x = $rows;
		}
		$hasil['warna']   = $x;
		$hasil['bk_tipe'] = $data['bk_tipe'];
		return $hasil;
	}

	function simpan_dampak_indi( $data )
	{
		// $id=intval($data['id']);

		if( isset( $data['edit_id'] ) )
		{
			if( count( $data['edit_id'] ) > 0 )
			{
				$no = 0;
				foreach( $data['edit_id'] as $key => $row )
				{
					$this->crud->crud_table( _TBL_RCSA_DET_DAMPAK_INDI );
					$this->crud->crud_field( 'rcsa_detail_id', $data['rcsa_detail_no'] );
					$this->crud->crud_field( 'kri_id', $data['kri'][$key] );
					$this->crud->crud_field( 'detail', $data['detail'][$key] );

					if( $row > 0 )
					{
						$this->crud->crud_type( 'edit' );
						$this->crud->crud_where( [ 'field' => 'id', 'value' => $row ] );
						$this->crud->crud_field( 'updated_by', $this->ion_auth->get_user_name() );
					}
					else
					{
						$this->crud->crud_field( 'bk_tipe', $data['bk_tipe'] );
						$this->crud->crud_type( 'add' );
						$this->crud->crud_field( 'created_by', $this->ion_auth->get_user_name() );
					}
					$this->crud->process_crud();
				}
			}
		}

		$this->db->where( 'category', 'impact' );
		$this->db->where( 'code', intval( $data['mak'] ) );
		$rows  = $this->db->get( _TBL_LEVEL )->row_array();
		$hasil = [ 'id' => 0, 'level_color' => '-', 'level_risk_id' => 0, 'code' => 0, 'like_code' => 0, 'impact_code' => 0, 'color' => '#FAFAFA', 'color_text' => '#000000', 'text' => '-', 'nil' => 0 ];
		if( $rows )
		{
			$x['text'] = $rows['code'] . ' - ' . $rows['level'];
			$x['nil']  = $rows['id'];

			$this->db->where( 'likelihood', intval( $data['like_id'] ) );
			$this->db->where( 'impact', intval( $rows['id'] ) );
			$rows = $this->db->get( _TBL_VIEW_LEVEL_MAPPING )->row_array();
			if( $rows )
			{
				$hasil         = $rows;
				$hasil['text'] = $x['text'];
				$hasil['nil']  = $x['nil'];
			}
		}
		$hasil['bk_tipe'] = $data['bk_tipe'];

		return $hasil;
	}

	function getDataDropdownDivision( $id, $seksi = "", $isAjax = FALSE, $validate = "" )
	{
		$id                       = ( intval( $id ) ) ? (int) $id : "";
		$queryGet["formAtSelect"] = [ "id", "owner_code", "CONCAT(owner_name,' - ',owner_code) as text", "level" ];
		$queryGet["orderBy"]      = "urut ASC";
		$data                     = [];
		if( empty( $id ) )
		{
			return [ "" => "- select seksi -" ];
		}

		if( $isAjax )
		{
			$getChild1 = $this->db->select( $queryGet["formAtSelect"] )->order_by( $queryGet["orderBy"] )->get_where( _TBL_OWNER, [ "pid" => $id, "active" => 1 ] )->result_array();
			$data      = $this->getDataSeksiByParent( $getChild1, $queryGet, $seksi, $validate );
		}
		else
		{
			$result              = $this->db->query( "select io.id, CONCAT(io.owner_name,' - ',io.owner_code) as text from il_rcsa ir join il_owner io on ir.owner_id = io.pid where ir.id ={$id}" )->result_array();
			$getFormatedDataDept = $this->getDataSeksiByParent( $result, $queryGet, "", "" );
			if( ! empty( $getFormatedDataDept ) )
			{
				foreach( $getFormatedDataDept as $key => $value )
				{
					$data[$value["id"]] = $value["text"];
				}
			}

		}

		return ( ! empty( $data ) ? $data : [ "" => [ "- select seksi -" ] ] );
	}
	private function getDataSeksiByParent( $getChild1, $formAtSelect, $seksi, $mode )
	{

		$resultData = [];
		$checkDept  = FALSE;
		if( ! empty( $getChild1 ) )
		{
			foreach( $getChild1 as $keyChild1 => $valueChild1 )
			{

				if( ! empty( $mode ) && $valueChild1["id"] == (int) $seksi )
				{
					$checkDept = TRUE;
				}
				$resultData[] = [ "id" => $valueChild1["id"], "text" => "&nbsp;&nbsp;" . $valueChild1["text"] ];
				$getChild2    = $this->db->select( $formAtSelect["formAtSelect"] )->order_by( $formAtSelect["orderBy"] )->get_where( _TBL_OWNER, [ "pid" => $valueChild1["id"], "active" => 1 ] )->result_array();
				if( ! empty( $getChild2 ) )
				{
					foreach( $getChild2 as $keyChild2 => $valueChild2 )
					{
						if( ! empty( $mode ) && $valueChild2["id"] == (int) $seksi )
						{
							$checkDept = TRUE;
						}
						$resultData[] = [ "id" => $valueChild2["id"], "text" => "&nbsp;&nbsp;&nbsp;&nbsp;" . $valueChild2["text"] ];
						$getChild3    = $this->db->select( $formAtSelect["formAtSelect"] )->order_by( $formAtSelect["orderBy"] )->get_where( _TBL_OWNER, [ "pid" => $valueChild2["id"], "active" => 1 ] )->result_array();
						if( ! empty( $getChild3 ) )
						{
							foreach( $getChild3 as $keyChild3 => $valueChild3 )
							{
								if( ! empty( $mode ) && $valueChild3["id"] == (int) $seksi )
								{
									$checkDept = TRUE;
								}
								$resultData[] = [ "id" => $valueChild3["id"], "text" => "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $valueChild3["text"] ];
								$getChild4    = $this->db->select( $formAtSelect["formAtSelect"] )->order_by( $formAtSelect["orderBy"] )->get_where( _TBL_OWNER, [ "pid" => $valueChild3["id"], "active" => 1 ] )->result_array();
								if( ! empty( $getChild4 ) )
								{
									foreach( $getChild4 as $keyChild4 => $valueChild4 )
									{
										if( ! empty( $mode ) && $valueChild4["id"] == (int) $seksi )
										{
											$checkDept = TRUE;
										}
										$resultData[] = [ "id" => $valueChild4["id"], "text" => "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $valueChild4["text"] ];
										$getChild5    = $this->db->select( $formAtSelect["formAtSelect"] )->order_by( $formAtSelect["orderBy"] )->get_where( _TBL_OWNER, [ "pid" => $valueChild4["id"], "active" => 1 ] )->result_array();
										if( ! empty( $getChild5 ) )
										{
											foreach( $getChild5 as $keyChild5 => $valueChild5 )
											{
												if( ! empty( $mode ) && $valueChild5["id"] == (int) $seksi )
												{
													$checkDept = TRUE;
												}
												$resultData[] = [ "id" => $valueChild5["id"], "text" => "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $valueChild5["text"] ];
											}
										}
									}
								}
							}
						}

					}
				}
			}
		}
		return ( ! empty( $seksi ) ) ? $checkDept : $resultData;
	}

	function refreshInputRisk( $type, $id_edit )
	{
		$result = 0;
		$tbl    = '';
		switch( $type )
		{
			case 'likehood':
				$tbl = _TBL_VIEW_RCSA_DET_LIKE_INDI;
				break;

			case 'dampak':
				$tbl = _TBL_VIEW_RCSA_DET_DAMPAK_INDI;
				break;
			default:
				$tbl = "";
				break;
		}
		if( ! empty( $tbl ) )
			$result = $this->db->where( 'bk_tipe', 1 )->where( 'rcsa_detail_id', intval( $id_edit ) )->or_group_start()->where( 'rcsa_detail_id', 0 )->where( 'created_by', $this->ion_auth->get_user_name() )->group_end()->get( $tbl )->num_rows();

		return $result;
	}
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */
