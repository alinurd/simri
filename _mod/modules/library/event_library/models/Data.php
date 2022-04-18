<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MX_Model {
	var $used=[];

	public function __construct()
    {
        parent::__construct();
	}
	
	// function save_library($newid=0,$data=array(), $tipe=1, $mode='new', $old_data=array())
	// {
	// 	$updf['id'] = $newid;
	// 	$upd['type'] = $tipe;
	// 	if ($mode=='new'){
	// 		$upd['code'] = $this->cari_code_library($data, $tipe);
	// 	}elseif($mode=='edit'){
	// 		if ($data['l_risk_type_no'] !== $old_data['l_risk_type_no']){
	// 			$upd['code'] = $this->cari_code_library($data, $tipe);
	// 		}
	// 	}
	// 	$this->db->update("library",$upd,$updf);
	// 	return true;
	// }
	
	function cari_total_dipakai($id=[]){
		$this->used=[];
		foreach($id as $i){
			$this->db->where('peristiwa_id', $i);
			$this->db->or_like('peristiwa_id', ','.$i, 'before');
			$this->db->or_like('peristiwa_id', $i.',', 'after');
			$this->db->or_like('peristiwa_id', ','.$i.',', 'both');
			$rows = $this->db->select('peristiwa_id as id, ifnull(COUNT(peristiwa_id),0) AS jml')->group_by(['peristiwa_id'])->get(_TBL_VIEW_RCSA_DETAIL)->row_array();
			if ($rows){
				$this->used[$rows['id']]=$rows['jml'];
			}
		}
	}

	function get_used($id=0){
		$value=0;
		if (array_key_exists($id, $this->used)){
			$value=$this->used[$id];
		}
		return $value;
	}
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */