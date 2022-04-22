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

	function checklist($period=0, $term=0)
	{
		$checklist = [];
		if ($period!=0) {
			$this->db->where('period_id', $period);
		}
		if ($term!=0) {
			$this->db->where('term_id', $term);
		}
		$check = $this->db->select("rcsa_detail_id")->get($this->nm_tbl)->result();
	
		foreach ($check as $key => $value) {
			$checklist[] = $value->rcsa_detail_id;
		}
		
		return $checklist;
		
	}

	

	function filter_data(){

		$check = $this->checklist();

		if ($this->cek_tgl){
			if (isset($this->pos['minggu'])){
				if (intval($this->pos['minggu'])){
					$rows= $this->db->select('*')->where('id', intval($this->pos['minggu']))->get(_TBL_COMBO)->row_array();
					$this->pos['tgl1']=$rows['param_date'];
					$this->pos['tgl2']=$rows['param_date_after'];
				}
			}

			if (!isset($this->pos['tgl1'])){
				if (isset($this->pos['term'])){
					if (intval($this->pos['term'])){
						$rows= $this->db->select('*')->where('id', intval($this->pos['term']))->get(_TBL_COMBO)->row_array();
						$this->pos['tgl1']=$rows['param_date'];
						$this->pos['tgl2']=$rows['param_date_after'];
					}
				}
			}
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
				$this->db->where('created_at>=', $this->pos['tgl1']);
				$this->db->where('created_at<=', $this->pos['tgl2']);
			}elseif ($this->pos['minggu']){
				$this->db->where('minggu_id', $this->pos['minggu']);
			}
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

	function get_data_map(){
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
		$this->filter_data();

		if ($this->pos['level']==1){
			$this->db->where('risiko_inherent',$this->pos['id']);
		}elseif ($this->pos['level']==2){
			$this->db->where('risiko_residual',$this->pos['id']);
		}elseif ($this->pos['level']==3){
			$this->db->where('risiko_target',$this->pos['id']);
		}elseif ($this->pos['level']==9){
			$this->db->where('owner_id',$this->pos['id']);
		}

		$rows=$this->db->get(_TBL_VIEW_RCSA_DETAIL)->result_array();
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
	
	function delete_data($id){
		$this->db->where_in('id', $id);
		$this->db->where('period_id', $period);
		$this->db->where('term_id', $term);
		$this->db->delete($this->nm_tbl);
		$jml=$this->db->affected_rows();
		return $jml;
	}

	function simpan_data($data, $period, $term){
		// $this->db->empty_table('profil_risiko');
		$old = $this->checklist($period, $term);
		
		$new = [];
		$del = [];
		$newdata = [];
		foreach ($old as $key => $value) {
			if (!in_array($value, $data)) {
				$del[] = $value;
			}
		}

		foreach ($data as $key => $value) {
			if (!in_array($value, $old)) {
				$new[] = $value;
			}
		}
		if(count($del)>0){
			$this->db->where_in('rcsa_detail_id', $del);
			$this->db->where('period_id', $period);
			$this->db->where('term_id', $term);
			$this->db->delete($this->nm_tbl);
		}

		if(count($new)>0){
			foreach ($new as $key => $value) {
				$newdata[] = [
					'rcsa_detail_id' => $value,
					'period_id' => $period,
					'term_id' => $term,
				];
			}
		
			$this->db->insert_batch($this->nm_tbl,$newdata);
		}

		return true;
		
	}
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */