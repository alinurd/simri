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
		$rows=$this->db->select('owner_id as id, kode_dept as owner_code, owner_name, 0 as status, tgl_propose, minggu_id')->group_by(['owner_id', 'kode_dept','owner_name'])->get(_TBL_VIEW_RCSA_APPROVAL_MITIGASI)->result_array();
		$tmp=[];
		$seratussepuluh = [];
		$seratus = [];
		$semilanpuluh = [];
		$tujuhlima = [];
		$nol = [];
		foreach($rows as $row){
			$tgl = $this->get_minggu($row['minggu_id']);
			$time = strtotime($tgl);

			$newformat = date('Y-m',$time);
			$deadline = $newformat.'-05';

			$date1=date_create($row['tgl_propose']);
			$date2=date_create($deadline);
			$diffo=date_diff($date2,$date1);
			$nilai_diff=intval($diffo->format("%R%a"));
			$diff = $this->kepatuhan($nilai_diff);

			
			if ($diff==110) {
				$seratussepuluh[]= $row['id'];
				$tmp[$row['id']]=$row;

			}elseif($diff==100){
				$seratus[] = $row['id'];
				$tmp[$row['id']]=$row;

			}elseif($diff==90){
				$semilanpuluh[] = $row['id'];
				$tmp[$row['id']]=$row;

			}elseif($diff==75){
				$tujuhlima[] = $row['id'];
				$tmp[$row['id']]=$row;

			}
		}
		$id=[];
		foreach($owner as $key=>$row){
			if (array_key_exists($key, $tmp)){
				unset($owner[$key]);
				$nol[] = $key;
			}
		}
	
		if (intval($this->pos['data']['param_id'])==1){
			$id=$tujuhlima;
		}elseif (intval($this->pos['data']['param_id'])==2){
			$id=$semilanpuluh;
		}elseif (intval($this->pos['data']['param_id'])==3){
			$id=$seratus;
		}elseif (intval($this->pos['data']['param_id'])==4){
			$id=$seratussepuluh;
		}
		
		unset($row);
		if (intval($this->pos['data']['param_id'])==0){
			$rows = $owner;
		}else{
			$this->filter_data();
	
			$this->db->where_in('owner_id',$id);
			$rows=$this->db->select('owner_id, kode_dept as owner_code, owner_name, tgl_propose, 0 as status, 0 as target, 0 as aktual,  file_att as file  ')
			->group_by(['owner_id', 'kode_dept','owner_name'])
			// ->get_compiled_select(_TBL_VIEW_RCSA_APPROVAL_MITIGASI);
			->get(_TBL_VIEW_RCSA_APPROVAL_MITIGASI)->result_array();

		}
		// dumps($rows);
		// die();
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
			// dumps($rows);
			// die();
			// if (isset($this->pos['tgl1'])){
			// 	$this->db->where('created_at>=', $this->pos['tgl1']);
			// 	$this->db->where('created_at<=', $this->pos['tgl2']);
			// }else
			if ($this->pos['minggu']){
				$this->db->where('minggu_id', $this->pos['minggu']);
			}else{
				$this->db->where('minggu_id', '-1');

			}
		}else{
			$this->db->where('period_id', _TAHUN_ID_);
			$this->db->where('term_id', _TERM_ID_);
		}
	}

	function get_minggu($id)
    {
        $minggu=$this->crud->combo_select(['id', 'param_date'])->combo_where('kelompok', 'minggu')->combo_where('id', $id)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		unset($minggu[""]);

        return (isset($minggu[$id]))?$minggu[$id]:0;
    }

	function kepatuhan($nilai)
	{
		if ($nilai<0) {
			$hasil = "110";
		}elseif($nilai==0){
			$hasil = "100";
		}elseif($nilai==1){
			$hasil = "90";
		}elseif($nilai==2){
			$hasil = "90";
		}elseif($nilai>=3){
			$hasil = "75";
		}

		return $hasil;
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
		$rows=$this->db->select('owner_id, kode_dept, owner_name, 0 as status, tgl_propose, minggu_id')->group_by(['owner_id', 'kode_dept','owner_name'])
		// ->get_compiled_select(_TBL_VIEW_RCSA_APPROVAL_MITIGASI);
		->get(_TBL_VIEW_RCSA_APPROVAL_MITIGASI)->result_array();
		
		$tmp=[];
		$seratussepuluh = 0;
		$seratus = 0;
		$semilanpuluh = 0;
		$tujuhlima = 0;
		$nol = 0;
		foreach($rows as $row){
			$tgl = $this->get_minggu($row['minggu_id']);
			$time = strtotime($tgl);

			$newformat = date('Y-m',$time);
			$deadline = $newformat.'-05';

			$date1=date_create($row['tgl_propose']);
			$date2=date_create($deadline);
			$diffo=date_diff($date2,$date1);
			$nilai_diff=intval($diffo->format("%R%a"));
			$diff = $this->kepatuhan($nilai_diff);

			
			if ($diff==110) {
				$seratussepuluh += 1;
			}elseif($diff==100){
				$seratus += 1;
			}elseif($diff==90){
				$semilanpuluh += 1;
			}elseif($diff==75){
				$tujuhlima += 1;
			}

			$row['nilai'] = $diff."%";
			$tmp[$row['owner_id']]=$row;
		}

	
		$ownerx=$owner;
		foreach($ownerx as $key=>&$row){
			if (!array_key_exists($key, $tmp)){
				$nol += 1;
				$row['nilai'] = "0%";
			}else{
				$row['nilai'] = $tmp[$key]['nilai'];
			}

		}
		unset($row);
		// dumps($ownerx);
		// die();
		$hasil['tepat']['owner']=$ownerx;
		$hasil['tepat']['total']=count($owner);
		$hasil['tepat']['110']=$seratussepuluh;
		$hasil['tepat']['100']=$seratus;
		$hasil['tepat']['90']=$semilanpuluh;
		$hasil['tepat']['75']=$tujuhlima;
		$hasil['tepat']['0']=$nol;
		$hasil['tepat']['110%']=number_format(($seratussepuluh/count($owner))*100,2);
		$hasil['tepat']['100%']=number_format(($seratus/count($owner))*100,2);
		$hasil['tepat']['90%']=number_format(($semilanpuluh/count($owner))*100,2);
		$hasil['tepat']['75%']=number_format(($tujuhlima/count($owner))*100,2);
		$hasil['tepat']['0%']=number_format(($nol/count($owner))*100,2);
		// $hasil['tepat']['sudah_persen']=number_format((count($rows)/count($owner))*100,2);
		// $hasil['tepat']['belum_persen']=number_format(((count($owner)-count($rows))/count($owner))*100,2);

		return $hasil;
	}
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */