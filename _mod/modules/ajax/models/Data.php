<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MX_Model {
	public $pos=[];
	public $cek_tgl=true;
	public function __construct()
    {
        parent::__construct();
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
		// $this->filter_data();
		$this->filter_data_all(_TBL_VIEW_RCSA_DETAIL);

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
				if ($this->pos['owner'] != 0 && $this->pos['owner']!=1) {
					$this->db->where('owner_id', $this->pos['owner']);
				}
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


		
		// if (isset($this->post['period'])){
		// 	$this->db->where('period_id', $this->post['period']);
		// }else{
		// 	$this->db->where('period_id', _TAHUN_ID_);
		// }

		// if (isset($this->post['term'])){
		// 	$this->db->where('term_id', $this->post['term']);
		// }else{
		// 	$this->db->where('term_id', _TERM_ID_);
		// }

		// if (isset($this->post['owner'])){
		// 	if ($this->post['owner'] != 0 && $this->post['owner']!=1) {
		// 		$this->db->where('owner_id', $this->post['owner']);
		// 	}
		// }

		$this->db->where('type_ass_id', 128);
	}

	function filter_data_all($customfield=''){
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
				if ($this->pos['owner'] != 0 && $this->pos['owner']!=1) {
					$this->owner_child[]=intval($this->pos['owner']);
					$this->get_owner_child(intval($this->pos['owner']));

					$this->db->where_in($field.'owner_id', $this->owner_child);
				}
			}
			
		
		if ($this->pos['type_ass']){
			$this->db->where($field.'type_ass_id', $this->pos['type_ass']);
		}
		if ($this->pos['period']){
			$this->db->where($field.'period_id', $this->pos['period']);
		}

		if ($this->pos['term']){
			$this->db->where($field.'term_id', $this->pos['term']);
		}

			// if (isset($this->pos['tgl1'])){
			// 	$this->db->where('DATE('._TBL_VIEW_RCSA_DETAIL.'.created_at)>=', $this->pos['tgl1']);
			// 	$this->db->where('DATE('._TBL_VIEW_RCSA_DETAIL.'.created_at)<=', $this->pos['tgl2']);
			// }elseif ($this->pos['minggu']){
			// 	$this->db->where('minggu_id', $this->pos['minggu']);
			// }
		}else{
			$this->db->where($field.'period_id', _TAHUN_ID_);
			// $this->db->where($field.'term_id', _TERM_ID_);
			$c =$this->session->userdata('data_user');
			if ($c['group']['param']['privilege_owner']>=2){
				if ($c['owner']){
					$this->db->where_in($field.'owner_id', $c['owner']);
				
				}
			}
		}


		
		// if (isset($this->post['period'])){
		// 	$this->db->where('period_id', $this->post['period']);
		// }else{
		// 	$this->db->where('period_id', _TAHUN_ID_);
		// }

		// if (isset($this->post['term'])){
		// 	$this->db->where('term_id', $this->post['term']);
		// }else{
		// 	$this->db->where('term_id', _TERM_ID_);
		// }

		// if (isset($this->post['owner'])){
		// 	if ($this->post['owner'] != 0 && $this->post['owner']!=1) {
		// 		$this->db->where('owner_id', $this->post['owner']);
		// 	}
		// }

		if (isset($this->pos['type_ass'])){
			if (intval($this->pos['type_ass'])) {
				$this->db->where('type_ass_id', $this->pos['type_ass']);
			}
		}

		// $this->db->where('type_ass_id', 128);
	}

	function get_data_detail_rcsa(){
		$rows = $this->db->where('id', $this->pos['id'])->get(_TBL_VIEW_RCSA_DETAIL)->row_array();
		$idx=explode(',', $rows['peristiwa_id']);
		$libs=$this->db->where_in('id', $idx)->get(_TBL_LIBRARY)->result_array();
		$x=[];
		foreach($libs as $lib){
			$x[]=$lib['library'];
		}
		$rows['peristiwa']=implode('###',$x);

		$idx=explode(',', $rows['dampak_id']);
		$libs=$this->db->where_in('id', $idx)->get(_TBL_LIBRARY)->result_array();
		$x=[];
		foreach($libs as $lib){
			$x[]=$lib['library'];
		}
		$rows['dampak']=implode('###',$x);
		
		$data['parent']=$rows;

		if($data['parent']['tipe_analisa_no'] == 2||$data['parent']['tipe_analisa_no'] == 3){
			
			$dampak=$this->db->where('bk_tipe', 1)->where('rcsa_detail_id', intval($this->pos['id']))->or_group_start()->where('rcsa_detail_id',0)->where('created_by', $this->ion_auth->get_user_name())->group_end()->get(_TBL_VIEW_RCSA_DET_DAMPAK_INDI)->result_array();

			$data['dampak']=$dampak;
		}


		$rows = $this->db->where('rcsa_detail_id', $this->pos['id'])->get(_TBL_VIEW_RCSA_MITIGASI)->result_array();
		$data['mitigasi'] = $this->convert_owner->set_data($rows)->set_param(['penanggung_jawab'=>'penanggung_jawab_id', 'koordinator'=>'koordinator_id'])->draw();
		
		return $data;
	}
	
	function get_data_detail_mitigasi(){
		$rows = $this->db->where('id', $this->pos['id'])->get(_TBL_VIEW_RCSA_MITIGASI)->row_array();
		$data['parent'] = $this->convert_owner->set_data($rows, false)->set_param(['penanggung_jawab'=>'penanggung_jawab_id', 'koordinator'=>'koordinator_id'])->draw();
		$data['aktifitas'] = $this->db->where('rcsa_mitigasi_id', $this->pos['id'])->get(_TBL_VIEW_RCSA_MITIGASI_DETAIL)->result_array();
		return $data;
	}
	function get_detail_progres_mitigasi(){
		$data['parent'] = $this->db->where('id', $this->pos['id'])->get(_TBL_VIEW_RCSA_MITIGASI_DETAIL)->row_array();
		$data['progres'] = $this->db->where('rcsa_mitigasi_detail_id', $this->pos['id'])->get(_TBL_VIEW_RCSA_MITIGASI_PROGRES)->result_array();
		return $data;
	}

	function update_list_indi_like($data=[]){

		$rows=$this->db->where('category', 'likelihood')->order_by('code')->get(_TBL_LEVEL)->result_array();
		$x=[];
		foreach($rows as $row){
			$x[$row['code']]=$row;
		}
		$mLike=$x;

		$rows=$this->db->where('bk_tipe', $data['bk_tipe'])->where('rcsa_detail_id', intval($data['rcsa_detail_no']))->or_group_start()->where('rcsa_detail_id',0)->where('created_by', $this->ion_auth->get_user_name())->group_end()->get(_TBL_VIEW_RCSA_DET_LIKE_INDI)->result_array();
		$ttl=0;
		foreach($rows as $row){
			$nilai = ($row['pencapaian']/100)*($row['pembobotan']*count($rows));
			$ttl+=floatval($nilai);
		}

		$jml=round(((count($rows)*3)-count($rows))/5,1);
		$last=count($rows)+$jml;
		$param[1]=['min'=>count($rows), 'mak'=>$last];
		$param[2]=['min'=>$last, 'mak'=>$last+=$jml];
		$param[3]=['min'=>$last, 'mak'=>$last+=$jml];
		$param[4]=['min'=>$last, 'mak'=>$last+=$jml];
		$param[5]=['min'=>$last, 'mak'=>$last+=$jml];

		foreach($param as $key=>$row){
            if ($key==1){
                if($ttl<=$row['min']){
                    $like = $key;break;
                }elseif ($ttl>=$row['min'] && $ttl<$row['mak']){
                    $like = $key;break;
                }
            }elseif ($key==5){
                if($ttl>=$row['mak']){
                    $like = $key;break;
                }elseif ($ttl>=$row['min'] && $ttl<$row['mak']){
                    $like = $key;break;
                }
            }elseif ($ttl>=$row['min'] && $ttl<$row['mak']){
                $like = $key;break;
            }
        }

        if (array_key_exists($like, $mLike)){
            $like_no = $mLike[$like]['id'];
            $likes = $mLike[$like]['code'].' - '.$mLike[$like]['level'];
		}
		$color='#ffffff';
		$tcolor='#000000';
		if (array_key_exists(intval($like), $mLike)){
			$color=$mLike[intval($like)]['warna'];
			$tcolor='#ffffff';
			$like .=' - '.$mLike[intval($like)]['level'];
		}

		$hasil['like_no']=$like_no;
		$hasil['likes']=$likes;
		$hasil['color']=$color;
		$hasil['tcolor']=$tcolor;
		$hasil['ttl']=$ttl;
		$hasil['param']=$param;
		$hasil['mLike']=$mLike;

		$x=['id'=>0, 'level_color'=>'-', 'level_risk_no'=>0, 'code'=>0, 'like_code'=>0, 'impact_code'=>0, 'color'=>'#FAFAFA', 'color_text'=>'#000000', 'text'=>'-', 'nil'=>0];

		$this->db->where('likelihood', intval($like_no));
		$this->db->where('impact', intval($data['dampak_id']));
		$rows= $this->db->get(_TBL_VIEW_LEVEL_MAPPING)->row_array();
		if($rows){
			$x=$rows;
		}
		$hasil['warna']=$x;
		$hasil['bk_tipe']=$data['bk_tipe'];

		return $hasil;
	}
	
    function get_data_type_risk($param){
		if ($param>0){
			$sql= $this->db
				  ->from(_TBL_COMBO)
				  ->where('kelompok', 'risk-type')
				  ->where('pid', $param)
				  ->order_by('urut')
				  ->get();
		}else{
			$sql= $this->db
				  ->from(_TBL_COMBO)
				  ->where('kelompok', 'risk-type')
				  ->order_by('urut')
				  ->get();
		}
		$rows=$sql->result();
		$option = '<option value="">'._l('cbo_select').'</option>';
		foreach($rows as $row){
			$option .= '<option value="'.$row->id.'">'.$row->kode.' - '.$row->data.'</option>';
		}
		$result['combo']=$option;
		return $result;
    }

    function get_data_term($id){
		$rows= $this->db->select('*')->where('kelompok','term')->where('pid', $id)->get(_TBL_COMBO)->result();
		$option = '<option value="0">'._l('cbo_select').'</option>';
		foreach($rows as $row){
			$option .= '<option value="'.$row->id.'">'.$row->data. ' - ('.date('d-m-Y',strtotime($row->param_date)).' s.d '.date('d-m-Y',strtotime($row->param_date_after)).')</option>';
		}
		$result['combo']=$option;
		return $result;
	}

	function get_data_minggu_per_bulan($id)
	{
		$rows = $this->db->select('*')->where('id', $id)->get(_TBL_COMBO)->row();
		$tgl1 = date('Y-m-d');
		$tgl2 = date('Y-m-d');
		if ($rows) {
			$tgl1 = $rows->param_date;
			$tgl2 = $rows->param_date_after;
		}
		$rows = $this->db->select('*')->where('kelompok', 'minggu')->where('param_date>=', $tgl1)->where('param_date_after<=', $tgl2)->get(_TBL_COMBO)->result();
		$option[""] = _l('cbo_select');
		foreach ($rows as $row) {
			$option[$row->id] = $row->param_string . ' (' . date('d-m-Y', strtotime($row->param_date)) . ' s.d ' . date('d-m-Y', strtotime($row->param_date_after)) . ')';
		}

		return $option;
	}

	function get_data_minggu_by_tahun($id){
		$rows= $this->db->select('*')->where('kelompok','minggu')->where('pid', $id)->get(_TBL_COMBO)->result();
		$option = '<option value="0">'._l('cbo_select').'</option>';
		foreach($rows as $row){
			
			$option .= '<option value="'.$row->id.'">'.$row->param_string.' ('.date('d-m-Y',strtotime($row->param_date)).' s.d '.date('d-m-Y',strtotime($row->param_date_after)).')</option>';
		}
		$result['combo']=$option;
		return $result;
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
		$option = '<option value="0">'._l('cbo_select').'</option>';
		foreach($rows as $row){
			// $option .= '<option value="'.$row->id.'">'.$row->param_string.' Minggu ke - '.$row->data. '  ('.date('d-m-Y',strtotime($row->param_date)).' s.d '.date('d-m-Y',strtotime($row->param_date_after)).')</option>';
			$option .= '<option value="'.$row->id.'">'.$row->param_string.' ('.date('d-m-Y',strtotime($row->param_date)).' s.d '.date('d-m-Y',strtotime($row->param_date_after)).')</option>';
		}
		$result['combo']=$option;
		return $result;
	}

	function get_data_kri($id){
		$rows= $this->db->select('*')->where('pid', $id)->where('param_int',2)->order_by('urut')->get(_TBL_COMBO)->result();
		$option = '<option value="0">'._l('cbo_select').'</option>';
		foreach($rows as $row){
			$option .= '<option value="'.$row->id.'">'.$row->urut.' - '.$row->data. '</option>';
		}
		$result['combo']=$option;
		return $result;
	}

	function get_data_like_aspek_risiko(){
		$rows= $this->db->select('*')->where('pid', intval($this->pos['id']))->where('kelompok','kriteria-like')->order_by('urut')->get(_TBL_COMBO)->result();
		$urutTemp = [1,7,8,9,10];
		foreach ($rows as $k=>$v) {
			
			$v->urut_temp = (isset($urutTemp[$k]))?$urutTemp[$k]:0;
		}
	
		$option = '<option data-temp="0" value="0">'._l('cbo_select').'</option>';
		foreach($rows as $row){
			$option .= '<option data-temp="'.$row->urut_temp.'" value="'.$row->urut.'" >'.$row->urut.' - '.$row->data. '</option>';
		}
		$result['combo']=$option;
		return $result;
	}

	function get_data_library($data){
		if (intval($data['kel'])==23){
			$rows= $this->db->where('type', 2)->where('library_no', $data['id'])->get(_TBL_VIEW_LIBRARY_DETAIL)->result();
			$option = '<option value="0">'._l('cbo_select').'</option>';
			foreach($rows as $row){
				$option .= '<option value="'.$row->id.'">'.$row->library.'</option>';
			}
			$result['peristiwa']=$option;

			$rows= $this->db->where('type', 3)->where('library_no', $data['id'])->get(_TBL_VIEW_LIBRARY_DETAIL)->result();
			$option = '<option value="0">'._l('cbo_select').'</option>';
			foreach($rows as $row){
				$option .= '<option value="'.$row->id.'">'.$row->library.'</option>';
			}
			$result['dampak']=$option;
		}else{
			$this->db->where('type', $data['kel']);
			$rows= $this->db->where('risk_type_no', $data['id'])->get(_TBL_LIBRARY)->result();
			$option = '<option value="0">'._l('cbo_select').'</option>';
			foreach($rows as $row){
				$option .= '<option value="'.$row->id.'">'.$row->library.'</option>';
			}
			$result['combo']=$option;
		}
		return $result;
	}

	function get_data_inherent($data=[]){
		$this->db->where('likelihood', intval($data['like']));
		$this->db->where('impact', intval($data['impact']));
		$rows= $this->db->get(_TBL_VIEW_LEVEL_MAPPING)->row_array();
		$hasil=['id'=>0, 'level_color'=>'-', 'level_risk_id'=>0, 'code'=>0, 'like_code'=>0, 'impact_code'=>0, 'color'=>'#FAFAFA', 'color_text'=>'#000000'];
		if ($rows){
			$hasil=$rows;
		}
		return $hasil;
	}

	function get_data_inherent_semi($data=[]){
		$this->db->where('like_code', intval($data['like']));
		$this->db->where('impact', intval($data['impact']));
		$rows= $this->db->get(_TBL_VIEW_LEVEL_MAPPING)->row_array();
		$hasil=['id'=>0, 'level_color'=>'-', 'level_risk_id'=>0, 'code'=>0, 'like_code'=>0, 'impact_code'=>0, 'color'=>'#FAFAFA', 'color_text'=>'#000000'];
		if ($rows){
			$hasil=$rows;
		}
		return $hasil;
	}

	function get_data_dampak($data=[]){
		$this->db->where('category', 'impact');
		$this->db->where('code', intval($data['id']));
		$rows= $this->db->get(_TBL_LEVEL)->row_array();
		$x['text']=$rows['code'].' - '.$rows['level'];
		$x['nil']=$rows['id'];

		$this->db->where('likelihood', intval($data['like_id']));
		$this->db->where('impact', intval($rows['id']));
		$rows= $this->db->get(_TBL_VIEW_LEVEL_MAPPING)->row_array();
		$hasil=['id'=>0, 'level_color'=>'-', 'level_risk_id'=>0, 'code'=>0, 'like_code'=>0, 'impact_code'=>0, 'color'=>'#FAFAFA', 'color_text'=>'#000000', 'text'=>'-', 'nil'=>0];
		if ($rows){
			$hasil=$rows;
			$hasil['text']=$x['text'];
			$hasil['nil']=$x['nil'];
		}

		return $hasil;
	}

	function get_data_used_library($param=[]){
		$hasil['title']="Detail Penggunaan ";
		$rows=[];
		$t='';
		if ($param['tipe']==1){
			$rows = $this->db->where('penyebab_id', $param['id'])->join(_TBL_VIEW_RCSA, _TBL_VIEW_RCSA_DETAIL.'.rcsa_id='._TBL_VIEW_RCSA.'.id', 'left')->get(_TBL_VIEW_RCSA_DETAIL)->result_array();
			$t='Cause Library';
		}elseif ($param['tipe']==2){
			$this->db->where('peristiwa_id', $param['id']);
			$this->db->or_like('peristiwa_id', ','.$param['id'], 'before');
			$this->db->or_like('peristiwa_id', $param['id'].',', 'after');
			$this->db->or_like('peristiwa_id', ','.$param['id'].',', 'both');
			$this->db->join(_TBL_VIEW_RCSA, _TBL_VIEW_RCSA_DETAIL.'.rcsa_id='._TBL_VIEW_RCSA.'.id', 'left');
			$rows = $this->db->get(_TBL_VIEW_RCSA_DETAIL)->result_array();
			$t='Event Library';
		}elseif ($param['tipe']==3){
			$this->db->where('dampak_id', $param['id']);
			$this->db->or_like('dampak_id', ','.$param['id'], 'before');
			$this->db->or_like('dampak_id', $param['id'].',', 'after');
			$this->db->or_like('dampak_id', ','.$param['id'].',', 'both');
			$this->db->join(_TBL_VIEW_RCSA, _TBL_VIEW_RCSA_DETAIL.'.rcsa_id='._TBL_VIEW_RCSA.'.id', 'left');
			$rows = $this->db->get(_TBL_VIEW_RCSA_DETAIL)->result_array();
			$t='Impact Library';
		}

		$hasil['title'].=$t;
		$hasil['data']=$rows;
		return $hasil;
	}
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */