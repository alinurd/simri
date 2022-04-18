<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MX_Model {

	var $pos=[];
	var $cek_tgl=true;
	var $miti_aktual=[];
	public function __construct()
    {
        parent::__construct();
	}

	function get_detail_char(){
		switch (intval($this->pos['data']['type_chat'])){
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

	function detail_lap_komitment(){
		$owner=[];
		$rows=$this->db->select('*, 0 as target, 0 as aktual , "" as tgl_propose, "" as file ')->where('owner_code<>','')->get(_TBL_OWNER)->result_array();
		foreach($rows as $row){
			$owner[$row['id']]=$row;
		}

		$this->filter_data();
		$rows=$this->db->select('owner_id, status_lengkap, 0 as status')->group_by(['owner_id', 'status_lengkap'])->get(_TBL_VIEW_RCSA_APPROVAL_MITIGASI)->result_array();
		$tmp=[];
		foreach($rows as $row){
			$tmp[$row['owner_id']]=$row;
		}
		$id=[];
		foreach($owner as $key=>&$row){
			if (array_key_exists($key, $tmp)){
				unset($owner[$key]);
				if (intval($this->pos['data']['param_id'])==1 && $tmp[$key]['status_lengkap']==1){
					$id[]=$key;
				}elseif (intval($this->pos['data']['param_id'])==2 && $tmp[$key]['status_lengkap']==2){
					$id[]=$key;
				}
			}else{
				if (intval($this->pos['data']['param_id'])==0){
					$id[]=$key;
				}
			}
		}
		if (!$id){
			$id[]=0;
		}
		if (intval($this->pos['data']['param_id'])>0){
			$this->filter_data();
			$this->db->where_in('owner_id',$id);
			$rows=$this->db->select('owner_id, kode_dept as owner_code, owner_name, tgl_propose, 0 as status, 0 as target, 0 as aktual, file_att as file  ')->get(_TBL_VIEW_RCSA_APPROVAL_MITIGASI)->result_array();
		}else{
			$rows=$owner;
		}
		$hasil['data']=$rows;

		return $hasil;
	}

	function filter_data(){
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

	function get_data_grap(){
		$owner=[];
		$rows=$this->db->where('owner_code<>','')->get(_TBL_OWNER)->result_array();
		foreach($rows as $row){
			$owner[$row['id']]=$row;
		}
		
		$this->filter_data();
		$rows=$this->db->select('owner_id, status_lengkap, 0 as status')->group_by(['owner_id', 'status_lengkap'])->get(_TBL_VIEW_RCSA_APPROVAL_MITIGASI)->result_array();
		$tmp=[];
		foreach($rows as $row){
			$tmp[$row['owner_id']]=$row;
		}
		foreach($owner as $key=>&$row){
			if (array_key_exists($key, $tmp)){
				$row['status']=$tmp[$key]['status_lengkap'];
			}else{
				$row['status']=0;
			}
		}
		unset($row);
		$stat[2]=['category'=>'Dibicarakan rutin setiap minggu dengan Evidence', 'nilai'=>0];
		$stat[1]=['category'=>'Dibicarakan rutin setiap minggu dengan Evidence tidak lengkap', 'nilai'=>0];
		$stat[0]=['category'=>'Tidak dibicarakan', 'nilai'=>0];
		// dumps($owner);die();
		foreach($owner as $key=>$row){
			++$stat[$row['status']]['nilai'];
		}

		$hasil['komitment']['owner']=$owner;
		$hasil['komitment']['total']=count($owner);
		$hasil['komitment']['data']=$stat;
		return $hasil;
	}
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */