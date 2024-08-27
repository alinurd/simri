<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MX_Model {

	var $pos=[];
	var $cek_tgl=true;
	var $miti_aktual=[];
	public function __construct()
    {
        parent::__construct();
	}

	function filter_datax(){
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
	}

	function filter_data($custom = false)
	{

		$minggu = [];
		if ($this->cek_tgl) {
			if (isset($this->pos['minggu'])) {
				if (intval($this->pos['minggu']) && $custom == false) {

					$rows = $this->db->select('*')->where('id', intval($this->pos['minggu']))->get(_TBL_COMBO)->row_array();
					$this->pos['tgl1'] = $rows['param_date'];
					$this->pos['tgl2'] = $rows['param_date_after'];
				} else {

					if ($custom == true && intval($this->pos['minggu']) == 0) {
						$rows = $this->db->select('*')->where('id', $this->pos['term'])->get(_TBL_COMBO)->row();
						$tgl1 = date('Y-m-d');
						$tgl2 = date('Y-m-d');
						if ($rows) {
							$tgl1 = $rows->param_date;
							$tgl2 = $rows->param_date_after;
						}
						$bulan = $this->db->select('id')->where('kelompok', 'minggu')->where('param_date>=', $tgl1)->where('param_date_after<=', $tgl2)->get(_TBL_COMBO)->result_array();
						$minggu = array_column($bulan, 'id');
					}
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
		if ($this->pos) {
			if ($this->pos['owner']) {
				if ($this->owner_child) {
					$this->db->where_in('owner_id', $this->owner_child);
				}
			}
			if ($this->pos['type_ass']) {
				$this->db->where('type_ass_id', $this->pos['type_ass']);
			}
			if ($this->pos['period']) {
				$this->db->where('period_id', $this->pos['period']);
			}

			// if ($this->pos['term']) {
			// 	$this->db->where('term_id', $this->pos['term']);
			// }

			if (isset($this->pos['tgl1']) && $custom == false) {
				$this->db->where('tgl_mulai_minggu>=', $this->pos['tgl1']);
				$this->db->where('tgl_akhir_minggu<=', $this->pos['tgl2']);
			} elseif (isset($this->pos['minggu'])) {
				// $this->db->where('minggu_id', $this->pos['minggu']);
				// dumps($minggu);

				if (count($minggu) > 0) {
					$this->db->where_in('minggu_id', $minggu);
				} elseif (intval($this->pos['minggu']) > 0) {
					$this->db->where('minggu_id', $this->pos['minggu']);
				}
			}
		} else {
			$this->db->where('period_id', _TAHUN_ID_);
			// $this->db->where('term_id', _TERM_ID_);
		}
	}
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */