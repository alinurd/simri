<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MX_Model {

	var $pos=[];
	var $cek_tgl=true;
	var $miti_aktual=[];
	public function __construct()
    {
        parent::__construct();
		$this->nm_tbl="profil_risiko";
	}

	function checklist($owner=0, $period=0)
	{
		$checklist = [];
		// if ($period!=0) {
		// 	$this->db->where('period_id', $period);
		// }
		// if ($term!=0) {
		// 	$this->db->where('term_id', $term);
		// }

		// if ($owner!=0) {
			$this->db->where('owner_id', $owner);
			if ($period!=0) {
				$this->db->where('period_id', $period);
			}
		// }

		$check = $this->db->select("rcsa_detail_id, kode_risiko_dept, kode_aktifitas, kode_dept, period_id")
		->get($this->nm_tbl)->result_array();
		
		foreach ($check as $key => $value) {
			// $urut=str_pad($value['kode_risiko_dept'],3,0,STR_PAD_LEFT );
			$kode = $value['kode_dept'].'-'.$value['kode_aktifitas'].'-'.$value['kode_risiko_dept'].'-'.$value['period_id'];
			$checklist[] = $kode;
		}
		
		return $checklist;
		
	}

	function filter_data($dtuser){

		if(isset($this->pos['owner'])){
			$check = $this->checklist($this->pos['owner']);
		}else{
			$this->super_user = intval($dtuser['is_admin']);
			$this->ownerx = intval(($this->super_user==0)?$dtuser['owner_id']:0);
			$check = $this->checklist($this->ownerx);

		}
	
		if ($this->cek_tgl){
			if (isset($this->pos['minggu'])){
				if (intval($this->pos['minggu'])){
					$rows= $this->db->select('*')->where('id', intval($this->pos['minggu']))->get(_TBL_COMBO)->row_array();
					$this->pos['tgl1']=$rows['param_date'];
					$this->pos['tgl2']=$rows['param_date_after'];
				}
			}

			if (!isset($this->pos['tgl1'])){
				if (isset($this->pos['term_mulai'])){
					if (intval($this->pos['term_mulai'])){
						$rows= $this->db->select('*')->where('id', intval($this->pos['term_mulai']))->get(_TBL_COMBO)->row_array();
						$this->pos['tgl1']=$rows['param_date'];
						// $this->pos['tgl2']=$rows['param_date_after'];
					}
				}

				if (isset($this->pos['term_akhir'])){
					if (intval($this->pos['term_akhir'])){
						$rows= $this->db->select('*')->where('id', intval($this->pos['term_akhir']))->get(_TBL_COMBO)->row_array();
						// $this->pos['tgl1']=$rows['param_date'];
						$this->pos['tgl2']=$rows['param_date_after'];
					}
				}
			}
		}

		if(count($check)>0){
			$this->db->where_in('id', $check);
		}else{
			$this->db->where('id', '-1');
		}

		if ($this->pos){
			if ($this->pos['owner']){
				if($this->owner_child){
					$this->db->where_in('owner_id', $this->owner_child);
				}
			}
			
			if ($this->pos['type_ass']){
				$this->db->where('type_ass_id', $this->pos['type_ass']);
			}
			
			if ($this->pos['period']){
				$this->db->where('period_id', $this->pos['period']);
			}

			if (isset($this->pos['tgl1'])){
				$this->db->where('tgl_mulai_minggu', $this->pos['tgl1']);
			}

			if (isset($this->pos['tgl2'])){
				$this->db->or_where('tgl_akhir_minggu', $this->pos['tgl2']);
			}
			if ($this->pos['owner']){
				if($this->owner_child){
					$this->db->where_in('owner_id', $this->owner_child);
				}
			}
			
			if ($this->pos['type_ass']){
				$this->db->where('type_ass_id', $this->pos['type_ass']);
			}
			
			if ($this->pos['period']){
				$this->db->where('period_id', $this->pos['period']);
			}

			
			// elseif ($this->pos['minggu']){
			// 	// $this->db->where('minggu_id', $this->pos['minggu']);
			// }
		}else{
			$this->db->where('period_id', _TAHUN_ID_);
			$this->db->where('term_id', _TERM_ID_);
		}

		if(count($check)>0){
			$this->db->where_in('id', $check);
		}else{
			$this->db->where('id', '-1');
		}
		
	}

	function get_data_map($dtuser){
		// $data['rcsa'] = $this->db->where('id', $this->pos['id'])->get(_TBL_VIEW_RCSA)->row_array();
		$rows=$this->db->select('rcsa_detail_id as id, count(rcsa_detail_id) as jml')->group_by(['rcsa_detail_id'])->get(_TBL_VIEW_RCSA_MITIGASI)->result_array();
		
		$miti=[];
		foreach($rows as $row){
			$miti[$row['id']]=$row['jml'];
		}
		$rows=$this->db->select('rcsa_detail_id as id, count(rcsa_detail_id) as jml')->group_by(['rcsa_detail_id'])->get(_TBL_VIEW_RCSA_MITIGASI_DETAIL)->result_array();
		$aktifitas=[];
		
		foreach($rows as $row){
			$aktifitas[$row['id']]=$row['jml'];
		}
		$rows=$this->db->select('rcsa_detail_id as id, count(rcsa_detail_id) as jml')->group_by(['rcsa_detail_id'])->get(_TBL_VIEW_RCSA_MITIGASI_PROGRES)->result_array();
		$progres=[];
		
		foreach($rows as $row){
			$progres[$row['id']]=$row['jml'];
		}
		// $this->filter_data();
		$this->filter_data_all(_TBL_VIEW_RCSA_DETAIL, $dtuser);
		
		// if ($this->pos['level']==1){
		// 	$this->db->where('risiko_inherent',$this->pos['id']);
		// }elseif ($this->pos['level']==2){
		// 	$this->db->where('risiko_residual',$this->pos['id']);
		// }elseif ($this->pos['level']==3){
		// 	$this->db->where('risiko_target',$this->pos['id']);
		// }elseif ($this->pos['level']==9){
		// 	$this->db->where('owner_id',$this->pos['id']);
		// }

		
		// dumps("cek");
		// die();
		$rows=$this->db->get(_TBL_VIEW_RCSA_DETAIL)->result_array();
		// $rows=$this->db->get_compiled_select(_TBL_VIEW_RCSA_DETAIL);
		foreach($rows as &$row){
			if (array_key_exists($row['id'], $miti)){
				$row['jml']=$miti[$row['id']];
			}
			$row['jml2']=0;
			if (array_key_exists($row['id'], $aktifitas)){
				$row['jml2']=$aktifitas[$row['id']];
			}
			$row['jml3']=0;
			if (array_key_exists($row['id'], $progres)){
				$row['jml3']=$progres[$row['id']];
			}
		}
		unset($row);
		$data['detail']=$rows;
		return $data;
	}

	function filter_data_all($customfield='', $dtuser){
		if(isset($this->pos['owner'])){
			$check = $this->checklist($this->pos['owner']);
		}else{
			$this->super_user = intval($dtuser['is_admin']);
			$this->ownerx = intval(($this->super_user==0)?$dtuser['owner_id']:0);
			$check = $this->checklist($this->ownerx);

		}
		
		$field = ($customfield!='')?$customfield.".":'';
		if ($this->cek_tgl){
			if (isset($this->pos['minggu'])){
				if (intval($this->pos['minggu'])){
					$rows= $this->db->select('*')->where('id', intval($this->pos['minggu']))->get(_TBL_COMBO)->row_array();
					$this->pos['tgl1']=$rows['param_date'];
					$this->pos['tgl2']=$rows['param_date_after'];
				}
			}

			if (!isset($this->pos['tgl1'])){
				if (isset($this->pos['term_mulai'])){
					if (intval($this->pos['term_mulai'])){
						$rows= $this->db->select('*')->where('id', intval($this->pos['term_mulai']))->get(_TBL_COMBO)->row_array();
						$this->pos['tgl1']=$rows['param_date'];
						// $this->pos['tgl2']=$rows['param_date_after'];
					}
				}

				if (isset($this->pos['term_akhir'])){
					if (intval($this->pos['term_akhir'])){
						$rows= $this->db->select('*')->where('id', intval($this->pos['term_akhir']))->get(_TBL_COMBO)->row_array();
						// $this->pos['tgl1']=$rows['param_date'];
						$this->pos['tgl2']=$rows['param_date_after'];
					}
				}
			}
		}
		
		if ($this->pos){
			if ($this->pos['owner']){
				if ($this->pos['owner'] != 0 && $this->pos['owner']!=1) {
					$this->owner_child[]=intval($this->pos['owner']);
					$this->get_owner_child(intval($this->pos['owner']));

					$this->db->where_in($field.'owner_id', $this->owner_child);
				}
			}

			if(count($check)>0){
				$this->db->where_in('id', $check);
			}else{
				$this->db->where('id', '-1');
			}
			
		
			if ($this->pos['type_ass']){
				$this->db->where($field.'type_ass_id', $this->pos['type_ass']);
			}
			if ($this->pos['period']){
				$this->db->where($field.'period_id', $this->pos['period']);
			}

			if (isset($this->pos['tgl1'])){
				$this->db->where('tgl_mulai_minggu', $this->pos['tgl1']);
			}

			
			if (isset($this->pos['tgl2'])){
				$this->db->or_where('tgl_akhir_minggu', $this->pos['tgl2']);
			}

			
			if ($this->pos['owner']){
				if ($this->pos['owner'] != 0 && $this->pos['owner']!=1) {

					$this->db->where_in($field.'owner_id', $this->owner_child);
				}
			}
			
			if ($this->pos['type_ass']){
				$this->db->where($field.'type_ass_id', $this->pos['type_ass']);
			}
			if ($this->pos['period']){
				$this->db->where($field.'period_id', $this->pos['period']);
			}

			
		}else{
			$this->db->where($field.'period_id', _TAHUN_ID_);
			$this->db->where($field.'term_id', _TERM_ID_);
			// $c =$this->session->userdata('data_user');
			// if ($c['group']['param']['privilege_owner']>=2){
			// 	if ($c['owner']){
			// 		$this->db->where_in($field.'owner_id', $c['owner']);
				
			// 	}
			// }
		}
		if (isset($this->pos['type_ass'])){
			if (intval($this->pos['type_ass'])) {
				$this->db->where('type_ass_id', $this->pos['type_ass']);
			}
		}

		if(count($check)>0){
			$this->db->where_in('id', $check);
		}else{
			$this->db->where('id', '-1');
		}

		
		// $this->db->where('type_ass_id', 128);
	}

	
	function delete_data($id, $owner){
		$this->db->where_in('id', $id);
		$this->db->where('owner_id', $owner);
		$this->db->delete($this->nm_tbl);
		$jml=$this->db->affected_rows();
		return $jml;
	}
	
	function simpan_data($data, $owner, $ori){
	
		$del = array_diff($ori, $data);
	
		if(count($del)>0){
			foreach ($del as $key => $value) {
				if ($value!="") {
					$kodeX = explode('-',$value);
	
					$this->db->where('kode_dept', $kodeX[0]);
					$this->db->where('kode_aktifitas', $kodeX[1]);
					$this->db->where('kode_risiko_dept', $kodeX[2]);
					$this->db->where('period_id', $kodeX[3]);
					$this->db->where('owner_id', $owner);
					$this->db->delete($this->nm_tbl);
				}
					
			}
		}
		
		$newdata = [];
		foreach ($data as $key => $value) {
			$kodeX = explode('-',$value);
			// $old = $this->checklist($owner, $kodeX[3]);

			$this->db->where('kode_dept', $kodeX[0]);
			$this->db->where('kode_aktifitas', $kodeX[1]);
			$this->db->where('kode_risiko_dept', $kodeX[2]);
			$this->db->where('period_id', $kodeX[3]);
			$this->db->where('owner_id', $owner);
			$cek = $this->db->get($this->nm_tbl)->row_array();
		
			if ($cek == null) {
				$newdata[] = [
					'kode_dept' => $kodeX[0],
					'kode_aktifitas' => $kodeX[1],
					'kode_risiko_dept' => $kodeX[2],
					'period_id' => $kodeX[3],
					'owner_id' => $owner,
				];
			}
		}

		if (count($newdata)) {
			$this->db->insert_batch($this->nm_tbl,$newdata);
		}

	}

	function simpan_datax($data, $owner){
		// $this->db->empty_table('profil_risiko');
		$old = $this->checklist($owner);
		
		$new = [];
		$del = [];
		$newdata = [];
		foreach ($old as $key => $value) {
			if (!in_array($value, $data)) {
				$del[] = $value;
			}
		}
		// dumps($old);
		// dumps($del);
		// die();
		foreach ($data as $key => $value) {
			if (!in_array($value, $old)) {
				$new[] = $value;
			}
		}
		
		if(count($del)>0){
			foreach ($del as $key => $value) {
				$kodeX = explode('-',$value);

				$this->db->where('period_id', $kodeX[3]);
				$this->db->where('owner_id', $owner);
				$cek = $this->db->get($this->nm_tbl)->result_array();

				if (count($cek)>0) {
					foreach ($cek as $key => $value) {
						$this->db->where('kode_dept', $value['kode_dept']);
						$this->db->where('kode_aktifitas', $value['kode_aktifitas']);
						$this->db->where('kode_risiko_dept', $value['kode_risiko_dept']);
						$this->db->where('period_id', $value['period_id']);
						$this->db->where('owner_id', $owner);
						$this->db->delete($this->nm_tbl);
					}
				}
			}
		}

		if(count($new)>0){
			foreach ($new as $key => $value) {
				$kodeX = explode('-',$value);

				$this->db->where('kode_dept', $kodeX[0]);
				$this->db->where('kode_aktifitas', $kodeX[1]);
				$this->db->where('kode_risiko_dept', $kodeX[2]);
				$this->db->where('period_id', $kodeX[3]);
				$this->db->where('owner_id', $owner);
				$cek = $this->db->get($this->nm_tbl)->row_array();
			
				if ($cek == null) {
					$newdata[] = [
						'kode_dept' => $kodeX[0],
						'kode_aktifitas' => $kodeX[1],
						'kode_risiko_dept' => $kodeX[2],
						'period_id' => $kodeX[3],
						'owner_id' => $owner,
					];
				}
			}
			
			$this->db->insert_batch($this->nm_tbl,$newdata);
		}

		return true;
		
	}

	function get_data_minggu($id){
		$rows= $this->db->select('*')->where('id', $id)->get(_TBL_COMBO)->row();
		$tgl1=date('Y-m-d');
		$tgl2=date('Y-m-d');
		if($rows){
			$tgl1=$rows->param_date;
			$tgl2=$rows->param_date_after;
		}
		$rows= $this->db->select('*')->where('kelompok', 'minggu')->where('param_date>=', $tgl1)->where('param_date_after<=', $tgl2)->get(_TBL_COMBO)->result();
		$option[""] = _l('cbo_select');
		foreach($rows as $row){
			$option[$row->id] = $row->param_string;
			// .' ('.date('d-m-Y',strtotime($row->param_date)).' s.d '.date('d-m-Y',strtotime($row->param_date_after)).')';
		}

		return $option;
	}
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */