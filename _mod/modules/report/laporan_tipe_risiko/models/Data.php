<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MX_Model {
	var $post=[];
	public function __construct()
    {
        parent::__construct();
	}

	function get_data()
	{
		$owner=[];
		$jml_dept=[];
		$rows=$this->db->where('owner_code<>','')->where('active',1)->group_by('owner_code')->order_by('owner_code')->get(_TBL_OWNER)->result_array();
		foreach($rows as $row){
			$owner[$row['id']]=$row;
			$jml_dept[$row['id']]=0;
		}

		$risk_tipe=[];
		$jml_risk=[];
		$rows=$this->db->where('active',1)->get(_TBL_VIEW_RISK_TYPE)->result_array();
		foreach($rows as $row){
			$risk_tipe[$row['pid']]['name']=$row['type_name'];
			$risk_tipe[$row['pid']]['child'][$row['id']]=$row;
			$jml_risk[]=0;
		}

		$rows = $this->db->select('*, 0 as jml')->order_by('id desc')->get(_TBL_LEVEL_COLOR)->result_array();
		$level=[];
		foreach($rows as $row){
			$level[$row['id']]=$row;
		}

		$this->filter_db();
		
        $this->filter_db();
		$rows = $this->db->select('tipe_risiko_id, owner_id, max(level_risk_no_residual) AS mak')->group_by(['tipe_risiko_id', 'owner_id'])->order_by('tipe_risiko_id, owner_id')->get(_TBL_VIEW_RCSA_DETAIL)->result_array();
		$warna=[];
		foreach($rows as $row){
			$warna[$row['tipe_risiko_id'].'-'.$row['owner_id']]=$row['mak'];
		}

        $this->filter_db();
		$rows = $this->db->select('klasifikasi_risiko_id, tipe_risiko_id, owner_id, COUNT(tipe_risiko_id) AS jml')->group_by(['klasifikasi_risiko_id', 'tipe_risiko_id', 'owner_id', ])->order_by('klasifikasi_risiko, tipe_risiko')->get(_TBL_VIEW_RCSA_DETAIL)->result_array();
		$nilai=[];
		foreach($rows as $row){
			$key=$row['tipe_risiko_id'].'-'.$row['owner_id'];
			$clr=[];
			if (array_key_exists($key, $warna)){
				if (intval($warna[$key])>0){
					$clr=$level[$warna[$key]];
				}
			}
			$nilai[$key]=['nil'=>$row['jml'], 'warna'=>$clr];
		}

		// dumps($this->db->last_query());
        $result['rows']=$rows;
        $result['nilai']=$nilai;
        $result['dept']=$owner;
        $result['totalV']=$jml_dept;
        $result['totalV2']=$jml_dept;
		$result['risk']=$risk_tipe ;
		// dumps($risk_tipe);die();
		return $result;
	}

	function filter_db(){
		if (isset($this->post['period'])){
			$this->db->where('period_id', $this->post['period']);
		}else{
			$this->db->where('period_id', _TAHUN_ID_);
		}

		if (isset($this->post['term'])){
			$this->db->where('term_id', $this->post['term']);
		}else{
			$this->db->where('term_id', _TERM_ID_);
		}

		if (isset($this->post['owner'])){
			if ($this->post['owner'] != 0 && $this->post['owner']!=1) {
				$this->db->where('owner_id', $this->post['owner']);
			}
		}

		$this->db->where('type_ass_id', 128);
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

		$this->db->where('tipe_risiko_id',$this->post['rowc']);
		$this->db->where('owner_id',$this->post['row']);
	
		$this->filter_db();
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

	
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */