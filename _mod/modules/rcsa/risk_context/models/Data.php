<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MX_Model {
	public function __construct()
    {
        parent::__construct();
	}

	function checklist()
	{
		$checklist = [];
		$check = $this->db->select("id")->get(_TBL_VIEW_RCSA)->result();
	
		foreach ($check as $key => $value) {
			$checklist[] = $value->id;
		}
		
		return $checklist;
		
	}

	function simpan_identifikasi($data){
		$mode='add';

		if (isset($data['txt_aktifitas_id'])){
			if (!empty(trim($data['txt_aktifitas_id']))){
				$this->crud->crud_type('add');
				$this->crud->crud_table(_TBL_COMBO);
				$this->crud->crud_field('data', $data['txt_aktifitas_id']);
				$this->crud->crud_field('kelompok', 'aktifitas');
				$this->crud->crud_field('active', 1);
				$this->crud->process_crud();
				$data['aktifitas_id'] = $this->crud->last_id();
			}
		}
		
		if (isset($data['txt_sasaran_id'])){
			if (!empty(trim($data['txt_sasaran_id']))){
				$this->crud->crud_type('add');
				$this->crud->crud_table(_TBL_COMBO);
				$this->crud->crud_field('data', $data['txt_sasaran_id']);
				$this->crud->crud_field('kelompok', 'sasaran-aktivitas');
				$this->crud->crud_field('active', 1);
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
		$peristiwa_id_tmp=[];
		$dampak_id_tmp=[];

		if (isset($data['txt_penyebab_id'])){
			if (!empty(trim($data['txt_penyebab_id']))){
				$this->crud->crud_type('add');
				$this->crud->crud_table(_TBL_LIBRARY);
				$this->crud->crud_field('type', 1);
				$this->crud->crud_field('risk_type_no', $data['tipe_risiko_id']);
				$this->crud->crud_field('library', $data['txt_penyebab_id']);
				$this->crud->crud_field('active', 1);
				$this->crud->process_crud();
				$data['penyebab_id'] = $this->crud->last_id();

				foreach($data['peristiwa_id_text'] as $pt){
					if (!empty(trim($pt))){
						$this->crud->crud_type('add');
						$this->crud->crud_table(_TBL_LIBRARY);
						$this->crud->crud_field('type', 2);
						$this->crud->crud_field('risk_type_no', $data['tipe_risiko_id']);
						$this->crud->crud_field('library', $pt);
						$this->crud->crud_field('active', 1);
						$this->crud->process_crud();
						$idsx = $this->crud->last_id();
						$peristiwa_id_tmp[]=$idsx;

						$this->crud->crud_type('add');
						$this->crud->crud_table(_TBL_LIBRARY_DETAIL);
						$this->crud->crud_field('library_no', $data['penyebab_id']);
						$this->crud->crud_field('child_no', $idsx);
						$this->crud->crud_field('active', 1);
						$this->crud->process_crud();
					}
				}

				foreach($data['dampak_id_text'] as $pt){
					if (!empty(trim($pt))){
						$this->crud->crud_type('add');
						$this->crud->crud_table(_TBL_LIBRARY);
						$this->crud->crud_field('type', 3);
						$this->crud->crud_field('risk_type_no', $data['tipe_risiko_id']);
						$this->crud->crud_field('library', $pt);
						$this->crud->crud_field('active', 1);
						$this->crud->process_crud();
						$idsx = $this->crud->last_id();
						$dampak_id_tmp[]=$idsx;

						$this->crud->crud_type('add');
						$this->crud->crud_table(_TBL_LIBRARY_DETAIL);
						$this->crud->crud_field('library_no', $data['penyebab_id']);
						$this->crud->crud_field('child_no', $idsx);
						$this->crud->crud_field('active', 1);
						$this->crud->process_crud();
					}
				}
			}else{
				foreach($data['peristiwa_id_text'] as $pt){
					if (!empty(trim($pt))){
						$this->crud->crud_type('add');
						$this->crud->crud_table(_TBL_LIBRARY);
						$this->crud->crud_field('type', 2);
						$this->crud->crud_field('risk_type_no', $data['tipe_risiko_id']);
						$this->crud->crud_field('library', $pt);
						$this->crud->crud_field('active', 1);
						$this->crud->process_crud();
						$idsx = $this->crud->last_id();
						$peristiwa_id_tmp[]=$idsx;
						$data['peristiwa_id'][]=$idsx;

						$this->crud->crud_type('add');
						$this->crud->crud_table(_TBL_LIBRARY_DETAIL);
						$this->crud->crud_field('library_no', $data['penyebab_id']);
						$this->crud->crud_field('child_no', $idsx);
						$this->crud->crud_field('active', 1);
						$this->crud->process_crud();
					}
				}

				foreach($data['dampak_id_text'] as $pt){
					if (!empty(trim($pt))){
						$this->crud->crud_type('add');
						$this->crud->crud_table(_TBL_LIBRARY);
						$this->crud->crud_field('type', 3);
						$this->crud->crud_field('risk_type_no', $data['tipe_risiko_id']);
						$this->crud->crud_field('library', $pt);
						$this->crud->crud_field('active', 1);
						$this->crud->process_crud();
						$idsx = $this->crud->last_id();
						$data['dampak_id'][]=$idsx;

						$this->crud->crud_type('add');
						$this->crud->crud_table(_TBL_LIBRARY_DETAIL);
						$this->crud->crud_field('library_no', $data['penyebab_id']);
						$this->crud->crud_field('child_no', $idsx);
						$this->crud->crud_field('active', 1);
						$this->crud->process_crud();
					}
				}
			}
		}

		$this->crud->crud_table(_TBL_RCSA_DETAIL);
		$this->crud->crud_field('rcsa_id', $data['rcsa_id']);
		$this->crud->crud_field('id_kpi', $data['id_kpi']);
		$this->crud->crud_field('aktifitas_id', $data['aktifitas_id']);
		$this->crud->crud_field('sasaran_id',$data['sasaran_id']);
		$this->crud->crud_field('tahapan', $data['tahapan']);
		$this->crud->crud_field('klasifikasi_risiko_id', $data['klasifikasi_risiko_id']);
		$this->crud->crud_field('tipe_risiko_id', $data['tipe_risiko_id']);
		$this->crud->crud_field('penyebab_id', $data['penyebab_id']);

		if ($peristiwa_id_tmp){
			$peristiwa_id=implode(',',$peristiwa_id_tmp);
		}else{
			$peristiwa_id=implode(',',$data['peristiwa_id']);
		}
		$this->crud->crud_field('peristiwa_id', $peristiwa_id);

		if ($dampak_id_tmp){
			$dampak_id=implode(',',$dampak_id_tmp);
		}else{
			$dampak_id=implode(',',$data['dampak_id']);
		}
		$this->crud->crud_field('dampak_id', $dampak_id);

		$this->crud->crud_field('risiko_dept', $data['risiko_dept']);
		$this->crud->crud_field('tipe_analisa_no', $data['tipe_analisa_no']);
		
		if(intval($data['tipe_analisa_no'])==1){
			$this->crud->crud_field('like_id', $data['like_id']);
			$this->crud->crud_field('impact_id', $data['impact_id']);
			$this->crud->crud_field('impact_text', $data['impact_text']);
			$this->crud->crud_field('like_text', $data['like_text']);
		}elseif(intval($data['tipe_analisa_no'])==2){
			$this->crud->crud_field('like_id', $data['like_id_2']);
			$this->crud->crud_field('impact_id', $data['impact_id_2']);
		}elseif(intval($data['tipe_analisa_no'])==3){
			$this->crud->crud_field('aspek_risiko_id', $data['aspek_risiko_id']);
			$this->crud->crud_field('aspek_det', $data['aspek_det']);
			$this->crud->crud_field('like_id', $data['like_id_3']);
			$this->crud->crud_field('impact_id', $data['impact_id_3']);
			$this->crud->crud_field('impact_text', $data['impact_text_3']);
			$this->crud->crud_field('like_text', $data['like_text_3']);
		}

		$this->crud->crud_field('risiko_inherent', $data['risiko_inherent']);
		$this->crud->crud_field('level_inherent', $data['level_inherent']);

		if (isset($data['check_item'])){
			$nama_kontrol=implode('###',$data['check_item']);
			$this->crud->crud_field('nama_kontrol', $nama_kontrol);
		}else{
			$this->crud->crud_field('nama_kontrol', "");
		}

		$this->crud->crud_field('nama_kontrol_note', $data['note_control']);
		$this->crud->crud_field('efek_kontrol', $data['efek_kontrol']);
		ini_set('MAX_EXECUTION_TIME', -1);
	
		
        $upload=upload_image_new(array('type'=>'xls|xlsx|csv|doc|docx|pdf|zip|rar','nm_file'=>'lampiran','path'=>'rcsa','thumb'=>false));
		
		if($upload){
			$inputFileName = file_path_relative('rcsa/'.$upload['file_name']);
	
			$this->crud->crud_field('lampiran', $inputFileName);
		}

		$id=intval($data['rcsa_detail_id']);
		if ($id>0){
			$this->crud->crud_type('edit');
			if ($data['like_residual_id']=='' || $data['impact_residual_id']=='') {
				if(intval($data['tipe_analisa_no'])==3){
					$this->crud->crud_field('like_residual_id', $data['like_id_3']);
					$this->crud->crud_field('impact_residual_id', $data['impact_id_3']);
					$this->crud->crud_field('risiko_residual', $data['risiko_inherent']);
				}elseif(intval($data['tipe_analisa_no'])==2){
					$this->crud->crud_field('like_residual_id', $data['like_id_2']);
					$this->crud->crud_field('impact_residual_id', $data['impact_id_2']);
					$this->crud->crud_field('risiko_residual', $data['risiko_inherent']);
				}
			}

			$this->crud->crud_where(['field' => 'id', 'value' => $id]);
			$this->crud->crud_field('updated_by', $this->ion_auth->get_user_name());
			$mode='edit';
		}else{
			$this->crud->crud_type('add');
			if(intval($data['tipe_analisa_no'])==3){
				$this->crud->crud_field('like_residual_id', $data['like_id_3']);
				$this->crud->crud_field('impact_residual_id', $data['impact_id_3']);
			}elseif(intval($data['tipe_analisa_no'])==2){
				$this->crud->crud_field('like_residual_id', $data['like_id_2']);
				$this->crud->crud_field('impact_residual_id', $data['impact_id_2']);
			}else{
				$this->crud->crud_field('like_residual_id', $data['like_id']);
				$this->crud->crud_field('impact_residual_id', $data['impact_id']);
			}
			$this->crud->crud_field('risiko_residual', $data['risiko_inherent']);
			
			// $this->crud->crud_field('like_target_id', $data['like_id']);
			// $this->crud->crud_field('impact_target_id', $data['impact_id']);
			// $this->crud->crud_field('risiko_target', $data['risiko_inherent']);
			$this->crud->crud_field('created_by', $this->ion_auth->get_user_name());
		}
		$this->crud->process_crud();
		if($id==0){
			$id = $this->crud->last_id();
		}

		if($mode=='add'){
			$this->crud->crud_table(_TBL_RCSA_DET_LIKE_INDI);
			$this->crud->crud_type('edit');
			$this->crud->crud_where(['field' => 'rcsa_detail_id', 'value' => 0]);
			$this->crud->crud_where(['field' => 'created_by', 'value' => $this->ion_auth->get_user_name()]);
			$this->crud->crud_field('rcsa_detail_id', $id);
			$this->crud->process_crud();

			$this->crud->crud_table(_TBL_RCSA_DET_DAMPAK_INDI);
			$this->crud->crud_type('edit');
			$this->crud->crud_where(['field' => 'rcsa_detail_id', 'value' => 0]);
			$this->crud->crud_where(['field' => 'created_by', 'value' => $this->ion_auth->get_user_name()]);
			$this->crud->crud_field('rcsa_detail_id', $id);
			$this->crud->process_crud();

			$this->db->query('INSERT INTO il_rcsa_det_like_indi(rcsa_detail_id, bk_tipe, kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_2, s_2_min, s_2_max, p_3, s_3_min, s_3_max, score, param, created_by) (SELECT rcsa_detail_id, ?, kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_2, s_2_min, s_2_max, p_3, s_3_min, s_3_max, score, param, created_by from il_rcsa_det_like_indi WHERE rcsa_detail_id=? AND bk_tipe=1)', [2, $id]);
			// $this->db->query('INSERT INTO il_rcsa_det_like_indi(rcsa_detail_id, bk_tipe, kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_2, s_2_min, s_2_max, p_3, s_3_min, s_3_max, score, param, created_by) (SELECT rcsa_detail_id, ?, kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_2, s_2_min, s_2_max, p_3, s_3_min, s_3_max, score, param, created_by from il_rcsa_det_like_indi WHERE rcsa_detail_id=? AND bk_tipe=1)', [3, $id]);
			$this->db->query('INSERT INTO il_rcsa_det_dampak_indi(bk_tipe,rcsa_detail_id,kri_id,created_by, detail) (SELECT ?,rcsa_detail_id,kri_id, created_by, detail from il_rcsa_det_dampak_indi WHERE rcsa_detail_id=? AND bk_tipe=1)', [2, $id]);
			// $this->db->query('INSERT INTO il_rcsa_det_dampak_indi(bk_tipe,rcsa_detail_id,kri_id,created_by) (SELECT ?,rcsa_detail_id,kri_id, created_by from il_rcsa_det_dampak_indi WHERE rcsa_detail_id=? AND bk_tipe=1)', [3, $id]);
		}else{
			$cek=$this->db->where('bk_tipe', 2)->where('rcsa_detail_id', $id)->get(_TBL_VIEW_RCSA_DET_LIKE_INDI)->result_array();
			$cek2=$this->db->where('bk_tipe', 2)->where('rcsa_detail_id', $id)->get(_TBL_VIEW_RCSA_DET_DAMPAK_INDI)->result_array();
			// dumps($cek2);
			// die();
			if (!$cek){

				$w = $this->db->query('SELECT rcsa_detail_id,  kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_2, s_2_min, s_2_max, p_3, s_3_min, s_3_max, score, param, created_by from il_rcsa_det_like_indi WHERE rcsa_detail_id=? AND bk_tipe=1', [$id])->result_array();
				
				foreach ($w as $key => $value) {
					$this->db->query('INSERT INTO il_rcsa_det_like_indi(rcsa_detail_id, bk_tipe, kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_2, s_2_min, s_2_max, p_3, s_3_min, s_3_max, score, param, created_by) values('.$value['rcsa_detail_id'].', 2, '.$value['kri_id'].', '.$value['satuan_id'].', '.$value['pembobotan'].', "'.$value['p_1'].'", '.$value['s_1_min'].', '.$value['s_1_max'].', "'.$value['p_2'].'", '.$value['s_2_min'].', '.$value['s_2_max'].', "'.$value['p_3'].'", '.$value['s_3_min'].', '.$value['s_3_max'].', '.$value['score'].', "'.$value['param'].'", "'.$value['created_by'].'")');
				
				}
			}else{
				$prev = $this->db->query('SELECT kri_id,rcsa_detail_id from il_rcsa_det_like_indi WHERE rcsa_detail_id='.$id.' AND bk_tipe=1')->result_array();
				$jenis1 = [];
				$jenis2 = [];
				// dumps($prev);
				foreach ($prev as $u => $v) {
					$jenis1[] = $v['kri_id'];
				}

				foreach ($cek as $u => $v) {
					$jenis2[] = $v['kri_id'];
				}
			
				$diff = array_diff($jenis2,$jenis1);
				
				foreach ($diff as $key => $value) {
					$cekagain = $this->db->query('SELECT id from il_rcsa_det_like_indi WHERE rcsa_detail_id='.$id.' AND bk_tipe=2 AND kri_id='.$value)->row_array();
					if ($cekagain) {
						$this->db->query('DELETE FROM il_rcsa_det_like_indi
						WHERE id = '.$cekagain['id']);
					}
					
				}

				foreach ($prev as $u => $v) {
					$cekagain = $this->db->query('SELECT kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_2, s_2_min, s_2_max, p_3, s_3_min, s_3_max, score, param,created_by from il_rcsa_det_like_indi WHERE rcsa_detail_id='.$v['rcsa_detail_id'].' AND bk_tipe=2 AND kri_id='.$v['kri_id'])->row_array();

					// dumps($v['kri_id']);
					if ($cekagain) {

						foreach ($cek as $key => $value) {
							$dd = $this->db->query('SELECT kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_2, s_2_min, s_2_max, p_3, s_3_min, s_3_max, score, param,created_by from il_rcsa_det_like_indi WHERE rcsa_detail_id=? AND bk_tipe=1', [$value['rcsa_detail_id']])->result_array();
		
							foreach($dd as $o => $i){
								if (isset($cek[$o])) {
									$this->db->query('UPDATE il_rcsa_det_like_indi SET 
									kri_id='.$i['kri_id'].' ,
									satuan_id='.$i['satuan_id'].' ,
									pembobotan='.$i['pembobotan'].' ,
									p_1="'.$i['p_1'].'" ,
									s_1_min='.$i['s_1_min'].' ,
									s_1_max='.$i['s_1_max'].' ,
									p_2="'.$i['p_2'].'",
									s_2_min='.$i['s_2_min'].' ,
									s_2_max='.$i['s_2_max'].' ,
									p_3="'.$i['p_3'].'",
									s_3_min='.$i['s_3_min'].' ,
									s_3_max='.$i['s_3_max'].' ,
									score='.$i['score'].' ,
									param="'.$i['param'].'" 
									
									WHERE id=?', [$cek[$o]['id']]);
								}
							}	
		
								$this->crud->crud_table(_TBL_RCSA_DETAIL);
								$this->crud->crud_type('edit');
								if(intval($data['tipe_analisa_no'])==3){
									$this->crud->crud_field('like_residual_id', $data['like_id_3']);
									$this->crud->crud_field('impact_residual_id', $data['impact_id_3']);
									$this->crud->crud_field('risiko_residual', $data['risiko_inherent']);
								}elseif(intval($data['tipe_analisa_no'])==2){
									$this->crud->crud_field('like_residual_id', $data['like_id_2']);
									$this->crud->crud_field('impact_residual_id', $data['impact_id_2']);
									$this->crud->crud_field('risiko_residual', $data['risiko_inherent']);
								}
								
		
								$this->crud->crud_where(['field' => 'id', 'value' => $value['rcsa_detail_id']]);
								$this->crud->crud_field('updated_by', $this->ion_auth->get_user_name());
								$this->crud->process_crud();
							
						}

					}else{

						$vo = $this->db->query('SELECT rcsa_detail_id,  kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_2, s_2_min, s_2_max, p_3, s_3_min, s_3_max, score, param, created_by from il_rcsa_det_like_indi WHERE rcsa_detail_id=? AND bk_tipe=1 AND kri_id=?', [$id, $v['kri_id']])->result_array();
						
						foreach ($vo as $key => $va) {
							$this->db->query('INSERT INTO il_rcsa_det_like_indi(rcsa_detail_id, bk_tipe, kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_2, s_2_min, s_2_max, p_3, s_3_min, s_3_max, score, param, created_by) values('.$va['rcsa_detail_id'].', 2, '.$va['kri_id'].', '.$va['satuan_id'].', '.$va['pembobotan'].', "'.$va['p_1'].'", '.$va['s_1_min'].', '.$va['s_1_max'].', "'.$va['p_2'].'", '.$va['s_2_min'].', '.$va['s_2_max'].', "'.$va['p_3'].'", '.$va['s_3_min'].', '.$va['s_3_max'].', '.$va['score'].', "'.$va['param'].'", "'.$va['created_by'].'")');
						
						}


						
					}

				
				}

				
			}

			if(!$cek2){

				$v = $this->db->query('SELECT ?,rcsa_detail_id,kri_id, created_by, detail from il_rcsa_det_dampak_indi WHERE rcsa_detail_id=? AND bk_tipe=1', [2, $id])->result_array();

				foreach ($v as $key => $value) {
					$this->db->query('INSERT INTO il_rcsa_det_dampak_indi(bk_tipe,rcsa_detail_id,kri_id,created_by, detail) values (2,'.$value['rcsa_detail_id'].','.$value['kri_id'].', "'.$value['created_by'].'", "'.$value['detail'].'")');
				}
			}else{
				
				$prev = $this->db->query('SELECT jenis_kri_id,rcsa_detail_id from il_view_rcsa_det_dampak_indi WHERE rcsa_detail_id='.$id.' AND bk_tipe=1')->result_array();
				$jenis1 = [];
				$jenis2 = [];
				
				foreach ($prev as $u => $v) {
					$jenis1[] = $v['jenis_kri_id'];
				}

				foreach ($cek2 as $u => $v) {
					$jenis2[] = $v['jenis_kri_id'];
				}
			
				$diff = array_diff($jenis2,$jenis1);
				foreach ($diff as $key => $value) {
					$cekagain = $this->db->query('SELECT id from il_view_rcsa_det_dampak_indi WHERE rcsa_detail_id='.$id.' AND bk_tipe=2 AND jenis_kri_id='.$value)->row_array();
					if ($cekagain) {
						$this->db->query('DELETE FROM il_rcsa_det_dampak_indi
						WHERE id = '.$cekagain['id']);
					}
					
				}
		
				foreach ($prev as $u => $v) {
					$cekagain = $this->db->query('SELECT kri_id, detail from il_view_rcsa_det_dampak_indi WHERE rcsa_detail_id='.$v['rcsa_detail_id'].' AND bk_tipe=2 AND jenis_kri_id='.$v['jenis_kri_id'])->row_array();
					if ($cekagain) {
						foreach ($cek2 as $key => $value) {
					
							$kriid = $this->db->query('SELECT kri_id, detail from il_view_rcsa_det_dampak_indi WHERE rcsa_detail_id='.$value['rcsa_detail_id'].' AND bk_tipe=1 AND jenis_kri_id='.$value['jenis_kri_id'])->row_array();
							if($kriid){
								$this->db->query('UPDATE il_rcsa_det_dampak_indi SET 
									kri_id='.$kriid['kri_id'].',
									detail="'.$kriid['detail'].'" WHERE id=?', [$value['id']]);

							}
								
								$this->crud->crud_table(_TBL_RCSA_DETAIL);
								$this->crud->crud_type('edit');
								if(intval($data['tipe_analisa_no'])==3){
									$this->crud->crud_field('like_residual_id', $data['like_id_3']);
									$this->crud->crud_field('impact_residual_id', $data['impact_id_3']);
									$this->crud->crud_field('risiko_residual', $data['risiko_inherent']);
								}elseif(intval($data['tipe_analisa_no'])==2){
									$this->crud->crud_field('like_residual_id', $data['like_id_2']);
									$this->crud->crud_field('impact_residual_id', $data['impact_id_2']);
									$this->crud->crud_field('risiko_residual', $data['risiko_inherent']);
								}
								
		
								$this->crud->crud_where(['field' => 'id', 'value' => $value['rcsa_detail_id']]);
								$this->crud->crud_field('updated_by', $this->ion_auth->get_user_name());
								$this->crud->process_crud();
								
						}
					}else{
						$vo = $this->db->query('SELECT ?,rcsa_detail_id,kri_id, created_by, detail from il_view_rcsa_det_dampak_indi WHERE rcsa_detail_id=? AND bk_tipe=1 AND jenis_kri_id='.$v['jenis_kri_id'], [2, $id])->result_array();

						foreach ($vo as $key => $va) {
							$this->db->query('INSERT INTO il_rcsa_det_dampak_indi(bk_tipe,rcsa_detail_id,kri_id,created_by, detail) values(2,'.$va['rcsa_detail_id'].','.$va['kri_id'].', "'.$va['created_by'].'", "'.$va['detail'].'")');
						}
						
					}

				
				}
			}
		}

		$rows = $this->db->where('aktifitas_id', $data['aktifitas_id'])->where('rcsa_id', $data['rcsa_id'])->order_by('created_at')->get(_TBL_RCSA_DETAIL)->result_array();
		$no=0;
		foreach($rows as $row){
			$this->crud->crud_table(_TBL_RCSA_DETAIL);
			$this->crud->crud_type('edit');
			$this->crud->crud_where(['field' => 'id', 'value' => $row['id']]);
			$this->crud->crud_field('kode_risiko_dept', ++$no);
			$this->crud->process_crud();
		}

		return $id;
	}

	function simpan_identifikasi_awal($data){
		$mode='add';

		if (isset($data['txt_aktifitas_id'])){
			if (!empty(trim($data['txt_aktifitas_id']))){
				$this->crud->crud_type('add');
				$this->crud->crud_table(_TBL_COMBO);
				$this->crud->crud_field('data', $data['txt_aktifitas_id']);
				$this->crud->crud_field('kelompok', 'aktifitas');
				$this->crud->crud_field('active', 1);
				$this->crud->process_crud();
				$data['aktifitas_id'] = $this->crud->last_id();
			}
		}
		
		if (isset($data['txt_sasaran_id'])){
			if (!empty(trim($data['txt_sasaran_id']))){
				$this->crud->crud_type('add');
				$this->crud->crud_table(_TBL_COMBO);
				$this->crud->crud_field('data', $data['txt_sasaran_id']);
				$this->crud->crud_field('kelompok', 'sasaran-aktivitas');
				$this->crud->crud_field('active', 1);
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
		$peristiwa_id_tmp=[];
		$dampak_id_tmp=[];

		if (isset($data['txt_penyebab_id'])){
			if (!empty(trim($data['txt_penyebab_id']))){
				$this->crud->crud_type('add');
				$this->crud->crud_table(_TBL_LIBRARY);
				$this->crud->crud_field('type', 1);
				$this->crud->crud_field('risk_type_no', $data['tipe_risiko_id']);
				$this->crud->crud_field('library', $data['txt_penyebab_id']);
				$this->crud->crud_field('active', 1);
				$this->crud->process_crud();
				$data['penyebab_id'] = $this->crud->last_id();

				foreach($data['peristiwa_id_text'] as $pt){
					if (!empty(trim($pt))){
						$this->crud->crud_type('add');
						$this->crud->crud_table(_TBL_LIBRARY);
						$this->crud->crud_field('type', 2);
						$this->crud->crud_field('risk_type_no', $data['tipe_risiko_id']);
						$this->crud->crud_field('library', $pt);
						$this->crud->crud_field('active', 1);
						$this->crud->process_crud();
						$idsx = $this->crud->last_id();
						$peristiwa_id_tmp[]=$idsx;

						$this->crud->crud_type('add');
						$this->crud->crud_table(_TBL_LIBRARY_DETAIL);
						$this->crud->crud_field('library_no', $data['penyebab_id']);
						$this->crud->crud_field('child_no', $idsx);
						$this->crud->crud_field('active', 1);
						$this->crud->process_crud();
					}
				}

				foreach($data['dampak_id_text'] as $pt){
					if (!empty(trim($pt))){
						$this->crud->crud_type('add');
						$this->crud->crud_table(_TBL_LIBRARY);
						$this->crud->crud_field('type', 3);
						$this->crud->crud_field('risk_type_no', $data['tipe_risiko_id']);
						$this->crud->crud_field('library', $pt);
						$this->crud->crud_field('active', 1);
						$this->crud->process_crud();
						$idsx = $this->crud->last_id();
						$dampak_id_tmp[]=$idsx;

						$this->crud->crud_type('add');
						$this->crud->crud_table(_TBL_LIBRARY_DETAIL);
						$this->crud->crud_field('library_no', $data['penyebab_id']);
						$this->crud->crud_field('child_no', $idsx);
						$this->crud->crud_field('active', 1);
						$this->crud->process_crud();
					}
				}
			}else{
				foreach($data['peristiwa_id_text'] as $pt){
					if (!empty(trim($pt))){
						$this->crud->crud_type('add');
						$this->crud->crud_table(_TBL_LIBRARY);
						$this->crud->crud_field('type', 2);
						$this->crud->crud_field('risk_type_no', $data['tipe_risiko_id']);
						$this->crud->crud_field('library', $pt);
						$this->crud->crud_field('active', 1);
						$this->crud->process_crud();
						$idsx = $this->crud->last_id();
						$peristiwa_id_tmp[]=$idsx;
						$data['peristiwa_id'][]=$idsx;

						$this->crud->crud_type('add');
						$this->crud->crud_table(_TBL_LIBRARY_DETAIL);
						$this->crud->crud_field('library_no', $data['penyebab_id']);
						$this->crud->crud_field('child_no', $idsx);
						$this->crud->crud_field('active', 1);
						$this->crud->process_crud();
					}
				}

				foreach($data['dampak_id_text'] as $pt){
					if (!empty(trim($pt))){
						$this->crud->crud_type('add');
						$this->crud->crud_table(_TBL_LIBRARY);
						$this->crud->crud_field('type', 3);
						$this->crud->crud_field('risk_type_no', $data['tipe_risiko_id']);
						$this->crud->crud_field('library', $pt);
						$this->crud->crud_field('active', 1);
						$this->crud->process_crud();
						$idsx = $this->crud->last_id();
						$data['dampak_id'][]=$idsx;

						$this->crud->crud_type('add');
						$this->crud->crud_table(_TBL_LIBRARY_DETAIL);
						$this->crud->crud_field('library_no', $data['penyebab_id']);
						$this->crud->crud_field('child_no', $idsx);
						$this->crud->crud_field('active', 1);
						$this->crud->process_crud();
					}
				}
			}
		}

		$this->crud->crud_table(_TBL_RCSA_DETAIL);
		$this->crud->crud_field('rcsa_id', $data['rcsa_id']);
		$this->crud->crud_field('id_kpi', $data['id_kpi']);
		$this->crud->crud_field('aktifitas_id', $data['aktifitas_id']);
		$this->crud->crud_field('sasaran_id',$data['sasaran_id']);
		$this->crud->crud_field('tahapan', $data['tahapan']);
		$this->crud->crud_field('klasifikasi_risiko_id', $data['klasifikasi_risiko_id']);
		$this->crud->crud_field('tipe_risiko_id', $data['tipe_risiko_id']);
		$this->crud->crud_field('penyebab_id', $data['penyebab_id']);

		if ($peristiwa_id_tmp){
			$peristiwa_id=implode(',',$peristiwa_id_tmp);
		}else{
			$peristiwa_id=implode(',',$data['peristiwa_id']);
		}
		$this->crud->crud_field('peristiwa_id', $peristiwa_id);

		if ($dampak_id_tmp){
			$dampak_id=implode(',',$dampak_id_tmp);
		}else{
			$dampak_id=implode(',',$data['dampak_id']);
		}
		$this->crud->crud_field('dampak_id', $dampak_id);

		$this->crud->crud_field('risiko_dept', $data['risiko_dept']);
		
		$id=intval($data['rcsa_detail_id']);
		if ($id>0){
			$this->crud->crud_type('edit');
			$this->crud->crud_where(['field' => 'id', 'value' => $id]);
			$this->crud->crud_field('updated_by', $this->ion_auth->get_user_name());
			$mode='edit';
		}else{
			$this->crud->crud_type('add');
			if(intval($data['tipe_analisa_no'])==3){
				$this->crud->crud_field('like_residual_id', $data['like_id_3']);
				$this->crud->crud_field('impact_residual_id', $data['impact_id_3']);
			}else{
				$this->crud->crud_field('like_residual_id', $data['like_id']);
				$this->crud->crud_field('impact_residual_id', $data['impact_id']);
			}
			$this->crud->crud_field('risiko_residual', $data['risiko_inherent']);
			// $this->crud->crud_field('like_target_id', $data['like_id']);
			// $this->crud->crud_field('impact_target_id', $data['impact_id']);
			// $this->crud->crud_field('risiko_target', $data['risiko_inherent']);
			$this->crud->crud_field('created_by', $this->ion_auth->get_user_name());
		}
		$this->crud->process_crud();
		if($id==0){
			$id = $this->crud->last_id();
		}

		if($mode=='add'){
			$this->crud->crud_table(_TBL_RCSA_DET_LIKE_INDI);
			$this->crud->crud_type('edit');
			$this->crud->crud_where(['field' => 'rcsa_detail_id', 'value' => 0]);
			$this->crud->crud_where(['field' => 'created_by', 'value' => $this->ion_auth->get_user_name()]);
			$this->crud->crud_field('rcsa_detail_id', $id);
			$this->crud->process_crud();

			$this->crud->crud_table(_TBL_RCSA_DET_DAMPAK_INDI);
			$this->crud->crud_type('edit');
			$this->crud->crud_where(['field' => 'rcsa_detail_id', 'value' => 0]);
			$this->crud->crud_where(['field' => 'created_by', 'value' => $this->ion_auth->get_user_name()]);
			$this->crud->crud_field('rcsa_detail_id', $id);
			$this->crud->process_crud();

			$this->db->query('INSERT INTO il_rcsa_det_like_indi(rcsa_detail_id, bk_tipe, kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_2, s_2_min, s_2_max, p_3, s_3_min, s_3_max, score, param, created_by) (SELECT rcsa_detail_id, ?, kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_2, s_2_min, s_2_max, p_3, s_3_min, s_3_max, score, param, created_by from il_rcsa_det_like_indi WHERE rcsa_detail_id=? AND bk_tipe=1)', [2, $id]);
			// $this->db->query('INSERT INTO il_rcsa_det_like_indi(rcsa_detail_id, bk_tipe, kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_2, s_2_min, s_2_max, p_3, s_3_min, s_3_max, score, param, created_by) (SELECT rcsa_detail_id, ?, kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_2, s_2_min, s_2_max, p_3, s_3_min, s_3_max, score, param, created_by from il_rcsa_det_like_indi WHERE rcsa_detail_id=? AND bk_tipe=1)', [3, $id]);
			$this->db->query('INSERT INTO il_rcsa_det_dampak_indi(bk_tipe,rcsa_detail_id,kri_id,created_by, detail) (SELECT ?,rcsa_detail_id,kri_id, created_by, detail from il_rcsa_det_dampak_indi WHERE rcsa_detail_id=? AND bk_tipe=1)', [2, $id]);
			// $this->db->query('INSERT INTO il_rcsa_det_dampak_indi(bk_tipe,rcsa_detail_id,kri_id,created_by) (SELECT ?,rcsa_detail_id,kri_id, created_by from il_rcsa_det_dampak_indi WHERE rcsa_detail_id=? AND bk_tipe=1)', [3, $id]);
		}else{
			$cek=$this->db->where('bk_tipe', 2)->where('rcsa_detail_id', $id)->get(_TBL_RCSA_DET_LIKE_INDI)->result_array();
			$cek2=$this->db->where('bk_tipe', 2)->where('rcsa_detail_id', $id)->get(_TBL_RCSA_DET_DAMPAK_INDI)->result_array();
			if (!$cek && !$cek2){
				$this->db->query('INSERT INTO il_rcsa_det_like_indi(rcsa_detail_id, bk_tipe, kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_2, s_2_min, s_2_max, p_3, s_3_min, s_3_max, score, param, created_by) (SELECT rcsa_detail_id, ?, kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_2, s_2_min, s_2_max, p_3, s_3_min, s_3_max, score, param, created_by from il_rcsa_det_like_indi WHERE rcsa_detail_id=? AND bk_tipe=1)', [2, $id]);
				$this->db->query('INSERT INTO il_rcsa_det_dampak_indi(bk_tipe,rcsa_detail_id,kri_id,created_by, detail) (SELECT ?,rcsa_detail_id,kri_id, created_by, detail from il_rcsa_det_dampak_indi WHERE rcsa_detail_id=? AND bk_tipe=1)', [2, $id]);
			}
		}

		$rows = $this->db->where('aktifitas_id', $data['aktifitas_id'])->where('rcsa_id', $data['rcsa_id'])->order_by('created_at')->get(_TBL_RCSA_DETAIL)->result_array();
		$no=0;
		foreach($rows as $row){
			$this->crud->crud_table(_TBL_RCSA_DETAIL);
			$this->crud->crud_type('edit');
			$this->crud->crud_where(['field' => 'id', 'value' => $row['id']]);
			$this->crud->crud_field('kode_risiko_dept', ++$no);
			$this->crud->process_crud();
		}

		return $id;
	}

	function simpan_evaluasi($data){
		// dumps($data['sts_save_evaluasi']);
		// die();
		$this->crud->crud_table(_TBL_RCSA_DETAIL);
		$this->crud->crud_field('like_residual_id', $data['like_residual_id']);
		$this->crud->crud_field('impact_residual_id', $data['impact_residual_id']);

		$this->crud->crud_field('risiko_residual', $data['risiko_residual']);
		$this->crud->crud_field('level_residual', $data['level_residual']);
		$this->crud->crud_field('treatment_id', $data['treatment_id']);
		$this->crud->crud_field('efek_mitigasi', $data['efek_mitigasi']);
		$this->crud->crud_field('sts_save_evaluasi', 1);

		$id=intval($data['rcsa_detail_id']);
		
		if ($id>0){
			$this->crud->crud_type('edit');
			$this->crud->crud_where(['field' => 'id', 'value' => $id]);
			$this->crud->crud_field('updated_by', $this->ion_auth->get_user_name());
		}

		$this->crud->process_crud();

		if($id==0){
			$id = $this->crud->last_id();
		}
		if($data['sts_save_evaluasi']==0){
		
			$this->db->query('INSERT INTO il_rcsa_det_like_indi(rcsa_detail_id, bk_tipe, kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_2, s_2_min, s_2_max, p_3, s_3_min, s_3_max, score, param, created_by) (SELECT rcsa_detail_id, ?, kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_2, s_2_min, s_2_max, p_3, s_3_min, s_3_max, score, param, created_by from il_rcsa_det_like_indi WHERE rcsa_detail_id=? AND bk_tipe=2)', [3, $id]);

			$this->db->query('INSERT INTO il_rcsa_det_dampak_indi(bk_tipe,rcsa_detail_id,kri_id,created_by, detail) (SELECT ?,rcsa_detail_id,kri_id, created_by, detail from il_rcsa_det_dampak_indi WHERE rcsa_detail_id=? AND bk_tipe=2)', [3, $id]);

			// $this->crud->crud_field('like_target_id', $data['like_residual_id']);
			// $this->crud->crud_field('impact_target_id', $data['impact_residual_id']);
			// $this->crud->crud_field('risiko_target', $data['risiko_residual']);

			$this->crud->crud_table(_TBL_RCSA_DETAIL);
			$this->crud->crud_type('edit');
			$this->crud->crud_field('like_target_id', $data['like_residual_id']);
			$this->crud->crud_field('impact_target_id', $data['impact_residual_id']);
			$this->crud->crud_field('risiko_target', $data['risiko_residual']);
		
			$this->crud->crud_where(['field' => 'id', 'value' => $id]);
			$this->crud->crud_field('updated_by', $this->ion_auth->get_user_name());
			$this->crud->process_crud();
		}else{
			
			$cek=$this->db->where('bk_tipe', 3)->where('rcsa_detail_id', $id)->get(_TBL_VIEW_RCSA_DET_LIKE_INDI)->result_array();
			$cek2=$this->db->where('bk_tipe', 3)->where('rcsa_detail_id', $id)->get(_TBL_VIEW_RCSA_DET_DAMPAK_INDI)->result_array();

			

			if (!$cek){
				
				$w = $this->db->query('SELECT rcsa_detail_id,  kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_2, s_2_min, s_2_max, p_3, s_3_min, s_3_max, score, param, created_by from il_rcsa_det_like_indi WHERE rcsa_detail_id=? AND bk_tipe=2', [$id])->result_array();
				
				
				foreach ($w as $key => $value) {
					$this->db->query('INSERT INTO il_rcsa_det_like_indi(rcsa_detail_id, bk_tipe, kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_2, s_2_min, s_2_max, p_3, s_3_min, s_3_max, score, param, created_by) values('.$value['rcsa_detail_id'].', 3, '.$value['kri_id'].', '.$value['satuan_id'].', '.$value['pembobotan'].', "'.$value['p_1'].'", '.$value['s_1_min'].', '.$value['s_1_max'].', "'.$value['p_2'].'", '.$value['s_2_min'].', '.$value['s_2_max'].', "'.$value['p_3'].'", '.$value['s_3_min'].', '.$value['s_3_max'].', '.$value['score'].', "'.$value['param'].'", "'.$value['created_by'].'")');
				
				}
			}else{
				$prev = $this->db->query('SELECT kri_id,rcsa_detail_id from il_rcsa_det_like_indi WHERE rcsa_detail_id='.$id.' AND bk_tipe=2')->result_array();
				$jenis1 = [];
				$jenis2 = [];
				// dumps($prev);
				foreach ($prev as $u => $v) {
					$jenis1[] = $v['kri_id'];
				}

				foreach ($cek as $u => $v) {
					$jenis2[] = $v['kri_id'];
				}
			
				$diff = array_diff($jenis2,$jenis1);
				
				foreach ($diff as $key => $value) {
					$cekagain = $this->db->query('SELECT id from il_rcsa_det_like_indi WHERE rcsa_detail_id='.$id.' AND bk_tipe=3 AND kri_id='.$value)->row_array();
					if ($cekagain) {
						$this->db->query('DELETE FROM il_rcsa_det_like_indi
						WHERE id = '.$cekagain['id']);
					}
					
				}

				foreach ($prev as $u => $v) {
					$cekagain = $this->db->query('SELECT kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_2, s_2_min, s_2_max, p_3, s_3_min, s_3_max, score, param,created_by from il_rcsa_det_like_indi WHERE rcsa_detail_id='.$v['rcsa_detail_id'].' AND bk_tipe=3 AND kri_id='.$v['kri_id'])->row_array();

					// dumps($v['kri_id']);
					if ($cekagain) {
						foreach ($cek as $key => $value) {
							$dd = $this->db->query('SELECT kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_2, s_2_min, s_2_max, p_3, s_3_min, s_3_max, score, param, created_by from il_rcsa_det_like_indi WHERE rcsa_detail_id=? AND bk_tipe=2', [$value['rcsa_detail_id']])->result_array();
		
							foreach($dd as $o => $i){
								if(isset($cek[$o])){
									$this->db->query('UPDATE il_rcsa_det_like_indi SET 
									kri_id='.$i['kri_id'].' ,
									satuan_id='.$i['satuan_id'].' ,
									pembobotan='.$i['pembobotan'].' ,
									p_1="'.$i['p_1'].'" ,
									s_1_min='.$i['s_1_min'].' ,
									s_1_max='.$i['s_1_max'].' ,
									p_2="'.$i['p_2'].'",
									s_2_min='.$i['s_2_min'].' ,
									s_2_max='.$i['s_2_max'].' ,
									p_3="'.$i['p_3'].'",
									s_3_min='.$i['s_3_min'].' ,
									s_3_max='.$i['s_3_max'].' ,
									score='.$i['score'].' ,
									param="'.$i['param'].'" 
									
									WHERE id=?', [$cek[$o]['id']]);
								}
							}	
								$this->crud->crud_table(_TBL_RCSA_DETAIL);
								$this->crud->crud_type('edit');
								$this->crud->crud_field('like_target_id', $data['like_residual_id']);
								$this->crud->crud_field('impact_target_id', $data['impact_residual_id']);
								$this->crud->crud_field('risiko_target', $data['risiko_residual']);
							
								$this->crud->crud_where(['field' => 'id', 'value' => $value['rcsa_detail_id']]);
								$this->crud->crud_field('updated_by', $this->ion_auth->get_user_name());
								$this->crud->process_crud();
							
						}
					}else{

						$vo = $this->db->query('SELECT rcsa_detail_id,  kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_2, s_2_min, s_2_max, p_3, s_3_min, s_3_max, score, param, created_by from il_rcsa_det_like_indi WHERE rcsa_detail_id=? AND bk_tipe=2 AND kri_id=?', [$id, $v['kri_id']])->result_array();
						
						foreach ($vo as $key => $va) {
							$this->db->query('INSERT INTO il_rcsa_det_like_indi(rcsa_detail_id, bk_tipe, kri_id, satuan_id, pembobotan, p_1, s_1_min, s_1_max, p_2, s_2_min, s_2_max, p_3, s_3_min, s_3_max, score, param, created_by) values('.$va['rcsa_detail_id'].', 3, '.$va['kri_id'].', '.$va['satuan_id'].', '.$va['pembobotan'].', "'.$va['p_1'].'", '.$va['s_1_min'].', '.$va['s_1_max'].', "'.$va['p_2'].'", '.$va['s_2_min'].', '.$va['s_2_max'].', "'.$va['p_3'].'", '.$va['s_3_min'].', '.$va['s_3_max'].', '.$va['score'].', "'.$va['param'].'", "'.$va['created_by'].'")');
						
						}
					}
				}
				
			}

			if(!$cek2){
				$v = $this->db->query('SELECT ?,rcsa_detail_id,kri_id, created_by, detail from il_rcsa_det_dampak_indi WHERE rcsa_detail_id=? AND bk_tipe=2', [3, $id])->result_array();

				foreach ($v as $key => $value) {
					$this->db->query('INSERT INTO il_rcsa_det_dampak_indi(bk_tipe,rcsa_detail_id,kri_id,created_by, detail) values(3,'.$value['rcsa_detail_id'].','.$value['kri_id'].', "'.$value['created_by'].'", "'.$value['detail'].'")');
				}
			}else{
				
				$prev = $this->db->query('SELECT jenis_kri_id,rcsa_detail_id from il_view_rcsa_det_dampak_indi WHERE rcsa_detail_id='.$id.' AND bk_tipe=2')->result_array();
				$jenis1 = [];
				$jenis2 = [];
				
				foreach ($prev as $u => $v) {
					$jenis1[] = $v['jenis_kri_id'];
				}

				foreach ($cek2 as $u => $v) {
					$jenis2[] = $v['jenis_kri_id'];
				}
			
				$diff = array_diff($jenis2,$jenis1);
				foreach ($diff as $key => $value) {
					$cekagain = $this->db->query('SELECT id from il_view_rcsa_det_dampak_indi WHERE rcsa_detail_id='.$id.' AND bk_tipe=3 AND jenis_kri_id='.$value)->row_array();
					if ($cekagain) {
						$this->db->query('DELETE FROM il_rcsa_det_dampak_indi
						WHERE id = '.$cekagain['id']);
					}
					
				}

				foreach ($prev as $u => $v) {
					$cekagain = $this->db->query('SELECT kri_id, detail from il_view_rcsa_det_dampak_indi WHERE rcsa_detail_id='.$v['rcsa_detail_id'].' AND bk_tipe=3 AND jenis_kri_id='.$v['jenis_kri_id'])->row_array();
					if ($cekagain) {
						foreach ($cek2 as $key => $value) {
				

								$kriid = $this->db->query('SELECT kri_id, detail from il_view_rcsa_det_dampak_indi WHERE rcsa_detail_id='.$value['rcsa_detail_id'].' AND bk_tipe=2 AND jenis_kri_id='.$value['jenis_kri_id'])->row_array();
								if($kriid){
		
									$this->db->query('UPDATE il_rcsa_det_dampak_indi SET 
										kri_id='.$kriid['kri_id'].',
										detail="'.$kriid['detail'].'" WHERE id=?', [$value['id']]);
								}
								$this->crud->crud_table(_TBL_RCSA_DETAIL);
								$this->crud->crud_type('edit');
								$this->crud->crud_field('like_target_id', $data['like_residual_id']);
								$this->crud->crud_field('impact_target_id', $data['impact_residual_id']);
								$this->crud->crud_field('risiko_target', $data['risiko_residual']);
							
								$this->crud->crud_where(['field' => 'id', 'value' => $value['rcsa_detail_id']]);
								$this->crud->crud_field('updated_by', $this->ion_auth->get_user_name());
								$this->crud->process_crud();
							
						}
					}else{
						$vo = $this->db->query('SELECT ?,rcsa_detail_id,kri_id, created_by, detail from il_view_rcsa_det_dampak_indi WHERE rcsa_detail_id=? AND bk_tipe=2 AND jenis_kri_id='.$v['jenis_kri_id'], [3, $id])->result_array();

						foreach ($vo as $key => $va) {
							$this->db->query('INSERT INTO il_rcsa_det_dampak_indi(bk_tipe,rcsa_detail_id,kri_id,created_by, detail) values(3,'.$va['rcsa_detail_id'].','.$va['kri_id'].', "'.$va['created_by'].'", "'.$va['detail'].'")');
						}
						
					}
				}

				
			}
		}

		

		return $id;
	}

	function simpan_target($data){
		$this->crud->crud_table(_TBL_RCSA_DETAIL);
		$this->crud->crud_field('like_target_id', $data['like_target_id']);
		$this->crud->crud_field('impact_target_id', $data['impact_target_id']);

		$this->crud->crud_field('risiko_target', $data['risiko_target']);
		$this->crud->crud_field('level_target', $data['level_target']);

		$id=intval($data['rcsa_detail_id']);
		if ($id>0){
			$this->crud->crud_type('edit');
			$this->crud->crud_where(['field' => 'id', 'value' => $id]);
			$this->crud->crud_field('updated_by', $this->ion_auth->get_user_name());
		}else{
			$this->crud->crud_type('add');
			$this->crud->crud_field('created_by', $this->ion_auth->get_user_name());
		}
		$this->crud->process_crud();
		if($id==0){
			$id = $this->crud->last_id();
		}

		return $id;
	}

	function simpan_mitigasi($data){
		$id=intval($data['id']);
		$this->crud->crud_table(_TBL_RCSA_MITIGASI);
		$this->crud->crud_field('rcsa_detail_id', $data['rcsa_detail_id']);
		$this->crud->crud_field('mitigasi', $data['mitigasi']);
		$this->crud->crud_field('batas_waktu', $data['batas_waktu_submit'], 'date');
		$this->crud->crud_field('biaya', $data['biaya'], 'currency');
		$this->crud->crud_field('penanggung_jawab_id', $data['penanggung_jawab_id']);
		$this->crud->crud_field('koordinator_id', $data['koordinator_id']);
		$this->crud->crud_field('status_jangka', $data['status_jangka']);

		if ($id>0){
			$this->crud->crud_type('edit');
			$this->crud->crud_where(['field' => 'id', 'value' => $id]);
			$this->crud->crud_field('updated_by', $this->ion_auth->get_user_name());
		}else{
			$this->crud->crud_type('add');
			$this->crud->crud_field('created_by', $this->ion_auth->get_user_name());
		}
		$this->crud->process_crud();
		if($id==0){
			$id = $this->crud->last_id();
		}

		return $id;
	}

	function simpan_aktifitas_mitigasi($data){
		$id=intval($data['id']);
		$this->crud->crud_table(_TBL_RCSA_MITIGASI_DETAIL);
		$this->crud->crud_field('rcsa_mitigasi_id', $data['rcsa_mitigasi_id']);
		$this->crud->crud_field('aktifitas_mitigasi', $data['aktifitas_mitigasi']);
		$this->crud->crud_field('batas_waktu_detail', $data['batas_waktu_detail_submit'], 'date');
		$this->crud->crud_field('penanggung_jawab_detail_id', $data['penanggung_jawab_detail_id']);
		$this->crud->crud_field('koordinator_detail_id', $data['koordinator_detail_id']);

		if ($id>0){
			$this->crud->crud_type('edit');
			$this->crud->crud_where(['field' => 'id', 'value' => $id]);
			$this->crud->crud_field('updated_by', $this->ion_auth->get_user_name());
		}else{
			$this->crud->crud_type('add');
			$this->crud->crud_field('created_by', $this->ion_auth->get_user_name());
		}
		$this->crud->process_crud();
		if($id==0){
			$id = $this->crud->last_id();
		}

		return $id;
	}

	function simpan_like_indi($data){
		$id=0;
		if (isset($data['id'])){
			$id=intval($data['id']);
		}
		if ($data['bk_tipe']==1){
			if (isset($data['txt_like'])){
				if (!empty(trim($data['txt_like']))){
					$this->crud->crud_type('add');
					$this->crud->crud_table(_TBL_COMBO);
					$this->crud->crud_field('data', $data['txt_like']);
					$this->crud->crud_field('kelompok', 'kri');
					$this->crud->crud_field('active', 1);
					$this->crud->process_crud();
					$data['kri_id'] = $this->crud->last_id();
				}
			}
			
			$this->crud->crud_table(_TBL_RCSA_DET_LIKE_INDI);
			$this->crud->crud_field('rcsa_detail_id', $data['rcsa_detail_no']);
			$this->crud->crud_field('bk_tipe', $data['bk_tipe']);
			$this->crud->crud_field('kri_id', $data['kri_id']);
			$this->crud->crud_field('satuan_id', $data['satuan_id']);
			$this->crud->crud_field('pembobotan', $data['pembobotan']);
			$this->crud->crud_field('p_1', $data['p_1']);
			$this->crud->crud_field('s_1_min', $data['s_1_min']);
			$this->crud->crud_field('s_1_max', $data['s_1_max']);
			$this->crud->crud_field('p_2', $data['p_2']);
			$this->crud->crud_field('s_2_min', $data['s_2_min']);
			$this->crud->crud_field('s_2_max', $data['s_2_max']);
			$this->crud->crud_field('p_3', $data['p_3']);
			$this->crud->crud_field('s_3_min', $data['s_3_min']);
			$this->crud->crud_field('s_3_max', $data['s_3_max']);
			$this->crud->crud_field('score', $data['score']);
		}else{
			$this->crud->crud_table(_TBL_RCSA_DET_LIKE_INDI);
			$this->crud->crud_field('score', $data['score']);
		}

		if ($id>0){
			$this->crud->crud_type('edit');
			$this->crud->crud_where(['field' => 'id', 'value' => $id]);
			$this->crud->crud_field('updated_by', $this->ion_auth->get_user_name());
		}else{
			$this->crud->crud_type('add');
			$this->crud->crud_field('created_by', $this->ion_auth->get_user_name());
		}
		$this->crud->process_crud();
		if($id==0){
			$id = $this->crud->last_id();
		}

		// $rows=$this->db->where('rcsa_detail_id', intval($data['rcsa_detail_no']))->group_start()->where('rcsa_detail_id',0)->or_where('created_by', $this->ion_auth->get_user_name())->group_end()->get(_TBL_VIEW_RCSA_DET_LIKE_INDI)->result_array();
		$hasil=$this->update_list_indi_like($data);
		$hasil['id']=$id;
		return $hasil;
	}

	function update_list_indi_like($data=[]){

		$rows=$this->db->where('category', 'likelihood')->order_by('code')->get(_TBL_LEVEL)->result_array();
		$x=[];
		foreach($rows as $row){
			$x[$row['code']]=$row;
		}
		$mLike=$x;

		$rows=$this->db->where('bk_tipe', $data['bk_tipe'])->where('rcsa_detail_id', intval($data['rcsa_detail_no']))->or_group_start()->where('rcsa_detail_id',0)->where('created_by', $this->ion_auth->get_user_name())->group_end()->get(_TBL_VIEW_RCSA_DET_LIKE_INDI)->result_array();
		$ttl=0;
		foreach($rows as $row){
			$nilai = ($row['pencapaian']/100)*($row['pembobotan']*count($rows));
			$ttl+=floatval($nilai);
		}

		$jml=round(((count($rows)*3)-count($rows))/5,1);
		$last=count($rows)+$jml;
		$param[1]=['min'=>count($rows), 'mak'=>$last];
		$param[2]=['min'=>$last, 'mak'=>$last+=$jml];
		$param[3]=['min'=>$last, 'mak'=>$last+=$jml];
		$param[4]=['min'=>$last, 'mak'=>$last+=$jml];
		$param[5]=['min'=>$last, 'mak'=>$last+=$jml];

		foreach($param as $key=>$row){
            if ($key==1){
                if($ttl<=$row['min']){
                    $like = $key;break;
                }elseif ($ttl>=$row['min'] && $ttl<$row['mak']){
                    $like = $key;break;
                }
            }elseif ($key==5){
                if($ttl>=$row['mak']){
                    $like = $key;break;
                }elseif ($ttl>=$row['min'] && $ttl<$row['mak']){
                    $like = $key;break;
                }
            }elseif ($ttl>=$row['min'] && $ttl<$row['mak']){
                $like = $key;break;
            }
        }

        if (array_key_exists($like, $mLike)){
            $like_no = $mLike[$like]['id'];
            $likes = $mLike[$like]['code'].' - '.$mLike[$like]['level'];
		}
		$color='#ffffff';
		$tcolor='#000000';
		if (array_key_exists(intval($like), $mLike)){
			$color=$mLike[intval($like)]['warna'];
			$tcolor='#ffffff';
			$like .=' - '.$mLike[intval($like)]['level'];
		}

		$hasil['like_no']=$like_no;
		$hasil['likes']=$likes;
		$hasil['color']=$color;
		$hasil['tcolor']=$tcolor;
		$hasil['ttl']=$ttl;
		$hasil['param']=$param;
		$hasil['mLike']=$mLike;

		$x=['id'=>0, 'level_color'=>'-', 'level_risk_no'=>0, 'code'=>0, 'like_code'=>0, 'impact_code'=>0, 'color'=>'#FAFAFA', 'color_text'=>'#000000', 'text'=>'-', 'nil'=>0];

		$this->db->where('likelihood', intval($like_no));
		$this->db->where('impact', intval($data['dampak_id']));
		$rows= $this->db->get(_TBL_VIEW_LEVEL_MAPPING)->row_array();
		if($rows){
			$x=$rows;
		}
		$hasil['warna']=$x;
		$hasil['bk_tipe']=$data['bk_tipe'];

		return $hasil;
	}

	function simpan_dampak_indi($data){
		// $id=intval($data['id']);
		
		if (isset($data['edit_id'])){
			if(count($data['edit_id'])>0){
				$no=0;
				foreach($data['edit_id'] as $key=>$row)
				{	
					$this->crud->crud_table(_TBL_RCSA_DET_DAMPAK_INDI);
					$this->crud->crud_field('rcsa_detail_id', $data['rcsa_detail_no']);
					$this->crud->crud_field('kri_id', $data['kri'][$key]);
					$this->crud->crud_field('detail', $data['detail'][$key]);

					if ($row>0){
						$this->crud->crud_type('edit');
						$this->crud->crud_where(['field' => 'id', 'value' => $row]);
						$this->crud->crud_field('updated_by', $this->ion_auth->get_user_name());
					}else{
						$this->crud->crud_field('bk_tipe', $data['bk_tipe']);
						$this->crud->crud_type('add');
						$this->crud->crud_field('created_by', $this->ion_auth->get_user_name());
					}
					$this->crud->process_crud();
				}
			}
		}

		$this->db->where('category', 'impact');
		$this->db->where('code', intval($data['mak']));
		$rows= $this->db->get(_TBL_LEVEL)->row_array();
		$hasil=['id'=>0, 'level_color'=>'-', 'level_risk_id'=>0, 'code'=>0, 'like_code'=>0, 'impact_code'=>0, 'color'=>'#FAFAFA', 'color_text'=>'#000000', 'text'=>'-', 'nil'=>0];
		if($rows){
			$x['text']=$rows['code'].' - '.$rows['level'];
			$x['nil']=$rows['id'];

			$this->db->where('likelihood', intval($data['like_id']));
			$this->db->where('impact', intval($rows['id']));
			$rows= $this->db->get(_TBL_VIEW_LEVEL_MAPPING)->row_array();
			if ($rows){
				$hasil=$rows;
				$hasil['text']=$x['text'];
				$hasil['nil']=$x['nil'];
			}
		}
		$hasil['bk_tipe']=$data['bk_tipe'];

		return $hasil;
	}
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */