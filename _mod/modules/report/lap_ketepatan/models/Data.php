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

	function detail_lap_ketepatan(){
		$owner=[];
		$rows=$this->db->select('*, 0 as target, 0 as aktual , "" as tgl_propose, "" as file  ')->where('owner_code<>','')->get(_TBL_OWNER)->result_array();
		foreach($rows as $row){
			$owner[$row['id']]=$row;
		}

		unset($this->pos['tgl1']);
		$this->filter_data();
		$this->db->where('kode_dept<>','');
		$rows=$this->db->select('owner_id as id, kode_dept as owner_code, owner_name, 0 as status')->group_by(['owner_id', 'kode_dept','owner_name'])->get(_TBL_VIEW_RCSA_APPROVAL_MITIGASI)->result_array();
		$tmp=[];
		foreach($rows as $row){
			$tmp[$row['id']]=$row;
		}
		$id=[];
		foreach($owner as $key=>$row){
			if (array_key_exists($key, $tmp)){
				unset($owner[$key]);
				if (intval($this->pos['data']['param_id'])==1){
					$id[]=$key;
				}
			}else{
				if (intval($this->pos['data']['param_id'])==0){
					$id[]=$row;
				}
			}
		}

		unset($row);
		// dumps($id);
		if (intval($this->pos['data']['param_id'])==1){
			$this->filter_data();
			if (!$id){
				$id[]=0;
			}
			$this->db->where_in('owner_id',$id);
			$rows=$this->db->select('owner_id, kode_dept as owner_code, owner_name, tgl_propose, 0 as status, 0 as target, 0 as aktual,  file_att as file  ')->get(_TBL_VIEW_RCSA_APPROVAL_MITIGASI)->result_array();
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

		unset($this->pos['tgl1']);
		$this->filter_data();
		$this->db->where('kode_dept<>','');
		$rows=$this->db->select('owner_id, kode_dept, owner_name, 0 as status')->group_by(['owner_id', 'kode_dept','owner_name'])->get(_TBL_VIEW_RCSA_APPROVAL_MITIGASI)->result_array();
		$tmp=[];
		foreach($rows as $row){
			$tmp[$row['owner_id']]=$row;
		}
		$ownerx=$owner;
		foreach($ownerx as $key=>&$row){
			if (array_key_exists($key, $tmp)){
				$row['status']=1;
			}else{
				$row['status']=0;
			}
		}
		unset($row);

		$hasil['tepat']['owner']=$ownerx;
		$hasil['tepat']['total']=count($owner);
		$hasil['tepat']['sudah']=count($rows);
		$hasil['tepat']['belum']=count($owner)-count($rows);
		$hasil['tepat']['sudah_persen']=number_format((count($rows)/count($owner))*100,2);
		$hasil['tepat']['belum_persen']=number_format(((count($owner)-count($rows))/count($owner))*100,2);

		return $hasil;
	}
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */