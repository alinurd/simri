<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MX_Model {
	var $post=[];
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
    
    function simpan_progres($data){
		$id=intval($data['id']);
		$this->crud->crud_table(_TBL_RCSA_MITIGASI_PROGRES);
		$this->crud->crud_field('rcsa_mitigasi_detail_id', $data['aktifitas_mitigasi_id']);
		$this->crud->crud_field('minggu_id', $data['minggu']);
		$this->crud->crud_field('target', $data['target']);
		$this->crud->crud_field('aktual', $data['aktual']);
		$this->crud->crud_field('uraian', $data['uraian']);
		$this->crud->crud_field('kendala', $data['kendala']);
		$this->crud->crud_field('tindak_lanjut', $data['tindak_lanjut']);
		$this->crud->crud_field('batas_waktu_tindak_lanjut', $data['batas_waktu_tindak_lanjut_submit'], 'date');
		$this->crud->crud_field('keterangan', $data['keterangan']);
		// $this->crud->crud_field('lampiran', $data['lampiran']);

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
        
        $rows = $this->db->where('rcsa_mitigasi_detail_id', $data['aktifitas_mitigasi_id'])->order_by('aktual','desc')->limit(1)->get(_TBL_VIEW_RCSA_MITIGASI_PROGRES)->row_array();
        
        if ($rows){
            $this->crud->crud_table(_TBL_RCSA_MITIGASI_DETAIL);
            $this->crud->crud_field('aktual', $rows['aktual'], 'int');
            $this->crud->crud_field('target', $rows['target'], 'int');
			
			$this->crud->crud_type('edit');
			$this->crud->crud_field('created_by', $this->ion_auth->get_user_name());
            $this->crud->crud_where(['field' => 'id', 'value' => $data['aktifitas_mitigasi_id']]);
            $this->crud->process_crud();
        }

		return $id;
	}

	function simpan_kpi(){

		if (floatval($this->post['score'])>=floatval($this->post['s_3_min'])){
			$indi=5;
		}elseif (floatval($this->post['score'])>=floatval($this->post['s_5_min'])){
			$indi=4;
		}elseif (floatval($this->post['score'])>=floatval($this->post['s_2_min'])){
			$indi=3;
		}elseif (floatval($this->post['score'])>=floatval($this->post['s_4_min'])){
			$indi=2;
		}elseif (floatval($this->post['score'])>=floatval($this->post['s_1_min'])){
			$indi=1;
		}else{
			$indi=0;
		}
	
		$this->crud->crud_table(_TBL_RCSA_KPI);
		$this->crud->crud_field('rcsa_id', $this->post['rcsa_id'], 'int');
		$this->crud->crud_field('minggu_id', $this->post['minggu'], 'int');
		$this->crud->crud_field('kpi_id', 0, 'int');
		$this->crud->crud_field('title', $this->post['title']);
		$this->crud->crud_field('satuan_id', $this->post['satuan_id']);
		$this->crud->crud_field('p_1', $this->post['p_1']);
		$this->crud->crud_field('s_1_min', $this->post['s_1_min']);
		$this->crud->crud_field('s_1_max', $this->post['s_1_max']);
		$this->crud->crud_field('p_4', $this->post['p_4']);
		$this->crud->crud_field('s_4_min', $this->post['s_4_min']);
		$this->crud->crud_field('s_4_max', $this->post['s_4_max']);
		$this->crud->crud_field('p_2', $this->post['p_2']);
		$this->crud->crud_field('s_2_min', $this->post['s_2_min']);
		$this->crud->crud_field('s_2_max', $this->post['s_2_max']);
		$this->crud->crud_field('p_5', $this->post['p_5']);
		$this->crud->crud_field('s_5_min', $this->post['s_5_min']);
		$this->crud->crud_field('s_5_max', $this->post['s_5_max']);
		$this->crud->crud_field('p_3', $this->post['p_3']);
		$this->crud->crud_field('s_3_min', $this->post['s_3_min']);
		$this->crud->crud_field('s_3_max', $this->post['s_3_max']);
		$this->crud->crud_field('score', $this->post['score']);
		$this->crud->crud_field('indikator', $indi);
		if($this->post['edit_id']==0){
			$this->crud->crud_type('add');
			$this->crud->crud_field('created_by', $this->ion_auth->get_user_name());
		}else{
			$this->crud->crud_type('edit');
			$this->crud->crud_where(['field' => 'id', 'value' => $this->post['edit_id']]);
			$this->crud->crud_field('updated_by', $this->ion_auth->get_user_name());
		}
		$this->crud->process_crud();
		return true;
	} 
	

	function simpan_kri(){
		if (floatval($this->post['score'])>=floatval($this->post['s_3_min'])){
			$indi=5;
		}elseif (floatval($this->post['score'])>=floatval($this->post['s_5_min'])){
			$indi=4;
		}elseif (floatval($this->post['score'])>=floatval($this->post['s_2_min'])){
			$indi=3;
		}elseif (floatval($this->post['score'])>=floatval($this->post['s_4_min'])){
			$indi=2;
		}elseif (floatval($this->post['score'])>=floatval($this->post['s_1_min'])){
			$indi=1;
		}else{
			$indi=0;
		}

		$this->crud->crud_table(_TBL_RCSA_KPI);
		$this->crud->crud_field('rcsa_id', 0, 'int');
		$this->crud->crud_field('minggu_id', 0, 'int');
		$this->crud->crud_field('kpi_id', $this->post['kpi_id'], 'int');
		$this->crud->crud_field('title', $this->post['title']);
		$this->crud->crud_field('satuan_id', $this->post['satuan_id']);
		$this->crud->crud_field('p_1', $this->post['p_1']);
		$this->crud->crud_field('s_1_min', $this->post['s_1_min']);
		$this->crud->crud_field('s_1_max', $this->post['s_1_max']);
		$this->crud->crud_field('p_4', $this->post['p_4']);
		$this->crud->crud_field('s_4_min', $this->post['s_4_min']);
		$this->crud->crud_field('s_4_max', $this->post['s_4_max']);
		$this->crud->crud_field('p_2', $this->post['p_2']);
		$this->crud->crud_field('s_2_min', $this->post['s_2_min']);
		$this->crud->crud_field('s_2_max', $this->post['s_2_max']);
		$this->crud->crud_field('p_5', $this->post['p_5']);
		$this->crud->crud_field('s_5_min', $this->post['s_5_min']);
		$this->crud->crud_field('s_5_max', $this->post['s_5_max']);
		$this->crud->crud_field('p_3', $this->post['p_3']);
		$this->crud->crud_field('s_3_min', $this->post['s_3_min']);
		$this->crud->crud_field('s_3_max', $this->post['s_3_max']);
		$this->crud->crud_field('score', $this->post['score']);
		$this->crud->crud_field('indikator', $indi);
		if($this->post['edit_id']==0){
			$this->crud->crud_type('add');
			$this->crud->crud_field('created_by', $this->ion_auth->get_user_name());
		}else{
			$this->crud->crud_type('edit');
			$this->crud->crud_where(['field' => 'id', 'value' => $this->post['edit_id']]);
			$this->crud->crud_field('updated_by', $this->ion_auth->get_user_name());
		}
		$this->crud->process_crud();
		return true;
	} 

	function get_detail_data(){
		$bulan=[1,12];
		
		if (intval($this->pos['term'])>0){
			$rows= $this->db->select('*')->where('id', intval($this->pos['term']))->get(_TBL_COMBO)->row_array();
			$bulan[0]=date('n',strtotime($rows['param_date']));
			$bulan[1]=date('n',strtotime($rows['param_date_after']));
		}
		$period=date('Y');
		if (intval($this->pos['period'])>0){
			$period = $this->pos['period'];
		}

		$owner=0;
		$parent=[];
		$owner_name=' All Departement ';
		if (intval($this->pos['owner'])>0){
			$owner = $this->pos['owner'];
			$parent = $this->db->where('id', $owner)->get(_TBL_OWNER)->row_array();
			$owner_name = $parent['owner_name'];
			$owner_kode = $parent['owner_code'];
		}
	

		$rows = $this->db->where('minggu_type',1)
			->where('bulan_int>=',$bulan[0])
			->where('bulan_int<=',$bulan[1])
			->where('period_id',$period)
			
			// ->where('kode_dept',$owner_kode)
			->where('owner_id',$owner)
			// ->group_by('satuan')
			->get(_TBL_VIEW_RCSA_KPI)->result_array();

		$lap2=[];
		foreach ($rows as $row){
			$tmp=[];
			$d = $this->db->where('kpi_id',$row['id'])->get(_TBL_VIEW_RCSA_KPI_DETAIL)->result_array();
			$detail=[];
			foreach ($d as $dd){
				$detail[]=$dd;
			}
			$tmp=$row;
			$tmp['detail']=$detail;
			$lap2[$row['id']]=$tmp;
		}

		$detail=[];
		foreach ($rows as $row){
			$d = $this->db->where('kpi_id',$row['id'])->get(_TBL_VIEW_RCSA_KPI_DETAIL)->result_array();
			foreach ($d as $dd){
				$detail[$row['id']]['detail'][$dd['id']]=$dd;
			}
		}

		$x=[];
		// dumps($detail);die();
		foreach ($detail as $key=>$row){
			$owner_id=0;
			$xx=[];
			foreach ($row['detail'] as $k=>$d){
				if($owner_id!==$key){
					// dumps($d);
					$xx[$k]['name']=$d['owner_name'];
					$xx[$k]['satuan']=$d['satuan'];
					$xx[$k]['title']=$d['title'];
					$xx[$k]['indikator']=$d['indikator'];
					$xx[$k]['id']=$d['id'];
					$owner_id=$d['kpi_id'];
				}

				$dd = $this->db->where('minggu_type',1)
				->where('bulan_int>=',$bulan[0]) 
				->where('bulan_int<=',$bulan[1])
				->where('period_id',$period)
				->where('title',$d['title'])

				->get(_TBL_VIEW_RCSA_KPI_DETAIL)->result_array();

				foreach ($dd as $ke => $va) {
					$xx[$k]['bulan'][$va['bulan_int']]=$va;
				}
			}
			$x[$key]=$xx;
		}
		unset($row);
		// dumps($x);die();
		$y=[];
		$owner_id=0;
		foreach ($rows as $key=>$row){
			if($owner_id!==$row['id']){
				$y[$row['id']]['name']=$row['owner_name'];
				$y[$row['id']]['satuan']=$row['satuan'];
				$y[$row['id']]['title']=$row['title'];
				$y[$row['id']]['indikator']=$row['indikator'];
				$owner_id=$row['id'];
			}
			$dd = $this->db->where('minggu_type',1)
				->where('bulan_int>=',$bulan[0])
				->where('bulan_int<=',$bulan[1])
				->where('period_id',$period)
				
				->where('title',$row['title'])
				->get(_TBL_VIEW_RCSA_KPI)->result_array();
			foreach ($dd as $key => $value) {
				$y[$row['id']]['bulan'][$value['bulan_int']]=$value;
			}
		}
		unset($row);
		// dumps($x);
		foreach($y as $key=>&$row){
			if (array_key_exists($key, $x)){
				// dumps('xxx');
				$row['detail']=$x[$key];
			}else{
				$row['detail']=[];
			}
		}

		// dumps($y);
		unset($row);

		$hasil['bulan']=$bulan;
		$hasil['data']=$y;
		$hasil['lap2']=$lap2;
		$hasil['parent']=$parent;
		$hasil['owner_name']=$owner_name;
		return $hasil;
	}
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */