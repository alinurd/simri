<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MX_Model {
	var $used=[];

	public function __construct()
    {
        parent::__construct();
	}

	function cari_total_dipakai($id=[]){
		$this->used=[];
		foreach($id as $i){
			$this->db->where('dampak_id', $i);
			$this->db->or_like('dampak_id', ','.$i, 'before');
			$this->db->or_like('dampak_id', $i.',', 'after');
			$this->db->or_like('dampak_id', ','.$i.',', 'both');
			$rows = $this->db->select('dampak_id as id, ifnull(COUNT(dampak_id),0) AS jml')->group_by(['dampak_id'])->get(_TBL_VIEW_RCSA_DETAIL)->row_array();
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