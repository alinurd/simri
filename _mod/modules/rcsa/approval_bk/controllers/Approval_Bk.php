<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Approval_Bk extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->language('risk_context');
	}

	function init($action='list'){
		$this->type_ass_no=$this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'ass-type')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$this->period=$this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'period')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$this->alat=$this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'metode-alat')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$this->stakeholder=$this->crud->combo_select(['id', 'officer_name'])->combo_where('active', 1)->combo_tbl(_TBL_VIEW_OFFICER)->get_combo()->result_combo();
		$this->cboDept=$this->get_combo_parent_dept();

		$this->set_Tbl_Master(_TBL_VIEW_RCSA);

		$this->addField(['field'=>'id', 'type'=>'int', 'show'=>false, 'size'=>4]);
		$this->addField(['field'=>'type_ass_id', 'input'=>'combo', 'required'=>true, 'search'=>true, 'values'=>$this->type_ass_no, 'size'=>50]);
		$this->addField(['field'=>'owner_id', 'title'=>'Department', 'type'=>'int', 'required'=>true,'input'=>'combo', 'search'=>true, 'values'=>$this->cboDept]);
		$this->addField(['field'=>'sasaran_dept', 'input'=>'multitext', 'search'=>true, 'size'=>500]);
		$this->addField(['field'=>'ruang_lingkup', 'input'=>'multitext', 'search'=>true, 'size'=>500]);
		$this->addField(['field'=>'stakeholder_id', 'title'=>'Stakeholder', 'type'=>'string','input'=>'combo', 'search'=>true, 'values'=>$this->cboDept, 'multiselect'=>true]);
		$this->addField(['field'=>'alat_metode_id', 'title'=>'Alat & Metode', 'type'=>'int','input'=>'combo', 'search'=>true, 'values'=>$this->alat]);
		$this->addField(['field'=>'period_id', 'title'=>'Period', 'type'=>'int', 'required'=>true,'input'=>'combo', 'search'=>true, 'values'=>$this->period]);
		$this->addField(['field'=>'term_id', 'title'=>'Term', 'type'=>'int', 'required'=>true,'input'=>'combo', 'search'=>true, 'values'=>[]]);
		$this->addField(['field'=>'term', 'show'=>false]);
		$this->addField(['field'=>'kode_dept', 'show'=>false]);
		$this->addField(['field'=>'owner_name', 'show'=>false]);

		$this->addField(['field'=>'active', 'input'=>'boolean', 'size'=>20]);
		$this->addField(['field'=>'status_id', 'show'=>false]);
		$this->addField(['field'=>'status_final', 'show'=>false]);
		$this->addField(['field'=>'status_revisi', 'show'=>false]);
		$this->addField(['field'=>'tgl_propose', 'type'=>'date', 'input'=>'date', 'show'=>false]);
		$this->addField(['field'=>'register', 'title'=>'Laporan', 'type'=>'free', 'show'=>false]);
		$this->addField(['field'=>'created_at', 'show'=>false]);

		$this->set_Field_Primary($this->tbl_master, 'id', true);

		$this->set_Sort_Table($this->tbl_master,'created_at', 'desc');
		$this->set_Where_Table(['field'=>'status_id', 'value'=>1, 'op'=>'>=']);
		$this->set_Where_Table(['field'=>'status_final', 'value'=>1, 'op'=>'<>']);

		$this->set_Table_List($this->tbl_master,'owner_name', "Dir/Dept/Proyek");
		$this->set_Table_List($this->tbl_master,'kode_dept','');

		$this->set_Table_List($this->tbl_master,'stakeholder_id');
		$this->set_Table_List($this->tbl_master,'type_ass_id', 'Tipe asesmen');
		$this->set_Table_List($this->tbl_master,'period_id', 'Tahun');
		$this->set_Table_List($this->tbl_master,'term', 'Periode');
		$this->set_Table_List($this->tbl_master,'status_id');
		$this->set_Table_List($this->tbl_master,'tgl_propose');
		$this->set_Table_List($this->tbl_master,'register','',7, 'center');
		$this->_set_Where_Owner();

		$this->set_Save_Table(_TBL_RCSA);
		$this->setPrivilege('delete', false);
		$this->setPrivilege('update', false);
		$this->setPrivilege('insert', false);
		$this->set_Close_Setting();

		// if (_MODE_=='add') {
		// 	$content_title = 'Penambahan Konteks Risiko';
		// }elseif(_MODE_=='edit'){
		// 	$content_title = 'Perubahan Konteks Risiko';
		// }elseif(_MODE_=='identifikasi-risiko'){
		// 	$content_title = 'Asesmen Risiko';
		// }else{
			$content_title = 'Daftar Approval Risk Register';
		// }

		$configuration = [
			'show_title_header' => false,
			'content_title' => $content_title
		];
		return [
			'configuration'	=> $configuration
		];
	}

	public function MASTER_DATA_LIST($arrId, $rows)
    {
		$this->asse_tipe=[];
		$this->assesment=[];
        $arr_approval = $this->db->order_by('pid')->get(_TBL_VIEW_APPROVAL)->result_array();
		$approval=[];
		foreach($arr_approval as $row){
			$approval[$row['pid']][$row['urut']] = $row;
		}

		$arr_assesment = $this->db->select('id, data, pid')->where('kelompok', 'ass-type')->get(_TBL_COMBO)->result_array();
		$this->assesment = [];
		foreach ($arr_assesment as $row){
            $this->assesment[$row['id']]=$row;
			if (array_key_exists(intval($row['pid']), $approval)){
                $this->assesment[$row['id']]['detail']=$approval[intval($row['pid'])];
            }
            $this->asse_tipe[$row['id']]=$row;
        }
	}

	function listBox_STATUS_ID($field, $rows, $value){
        $ass=intval($rows['type_ass_id']);
        $revisi=intval($rows['status_revisi']);
		$urut=intval($rows['status_id']);
		$final=intval($rows['status_final']);
		// dumps($this->asse_tipe);
		// dumps($this->assesment);
        $revisi_text='';
        if (array_key_exists($ass, $this->assesment)){
			if (array_key_exists('detail', $this->assesment[$ass])){
				if (array_key_exists($revisi, $this->assesment[$ass]['detail'])){
					$revisi_text = '<div class="label text-center" style="background-color:'.$this->assesment[$ass]['detail'][$revisi]['warna_revisi'].';color:#ffffff;width:100%;padding:10px 5px; display:block;">'._l('msg_notif_revisi').'<br/>'.$this->assesment[$ass]['detail'][$revisi]['model'].'</div><br/>';
				}
			}
        }

        $value=intval($value);
        $hasil='unknow-'.$value;
        if ($final){
            $hasil = '<div class="label text-center" style="background-color:'.$this->_preference_['warna_approved'].';color:#ffffff;width:100%;padding:10px 5px; display:block;"> '._l('msg_notif_approved').'</div>';
        }
		elseif (array_key_exists($ass, $this->assesment)){
			if (array_key_exists('detail', $this->assesment[$ass])){
				if (array_key_exists($urut, $this->assesment[$ass]['detail'])){
                    $ket = ' - ';
                    if(!empty($this->assesment[$ass]['detail'][$urut]['model'])){
                        $ket = $this->assesment[$ass]['detail'][$urut]['model'];
                    }
					$hasil = '<div class="label text-center" style="background-color:'.$this->assesment[$ass]['detail'][$urut]['warna'].';color:#ffffff;width:100%;padding:10px 5px; display:block;">'._l('msg_notif_need_approved').'<br/>'.$ket.'</div>';
				}
			}
        }
        
        if($value==0){
        	$hasil = '<a href="' . base_url($this->modul_name . '/propose-risiko/' . $rows['id']) . '" class="propose btn  pointer" style="width:100% !important;padding:5px;background-color:'.$this->_preference_['warna_propose'].';color:#ffffff;" data-id="' . $rows['id'].'"> '._l('msg_notif_propose').' </a>';
        }
        return $revisi_text.$hasil;
    }

	function listBox_REGISTER($field, $rows, $value){
		$o='<i class="icon-menu6 pointer text-primary risk-register" title=" View Risk Register " data-id="'.$rows['id'].'"></i>';

		return $o;
	}

	function inputBox_TERM_ID($mode, $field, $rows, $value){
		if ($mode=='edit'){
			$id=0;
			if (isset($rows['period_id']))
				$id=$rows['period_id'];
			$field['values'] = $this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'term')->combo_where('pid', $id)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		}
		$content = $this->set_box_input($field, $value);
		return $content;
	}

	function propose_risiko(){
		$this->load->library('map');
		$id=intval($this->uri->segment(3));
		$data['parent']=$this->db->where('id', $id)->get(_TBL_VIEW_RCSA)->row_array();
		$data['detail']=$this->db->where('rcsa_id', $id)->get(_TBL_VIEW_RCSA_DETAIL)->result_array();
		$data['info_parent']=$this->load->view('info-parent',$data, true);
		$rows = $this->db->where('rcsa_id', $id)->SELECT('risiko_inherent as id, COUNT(risiko_inherent) as nilai')->group_by('risiko_inherent')->get(_TBL_VIEW_RCSA_DETAIL)->result_array();
		$data['map_inherent']=$this->map->set_data($rows)->set_param(['tipe'=>'angka', 'level'=>1])->draw();
		$rows = $this->db->where('rcsa_id', $id)->SELECT('risiko_residual as id, COUNT(risiko_residual) as nilai')->group_by('risiko_residual')->get(_TBL_VIEW_RCSA_DETAIL)->result_array();
		$data['map_residual']=$this->map->set_data($rows)->set_param(['tipe'=>'angka', 'level'=>2])->draw();
		$rows = $this->db->where('rcsa_id', $id)->SELECT('risiko_target as id, COUNT(risiko_target) as nilai')->group_by('risiko_target')->get(_TBL_VIEW_RCSA_DETAIL)->result_array();
		$data['map_target']=$this->map->set_data($rows)->set_param(['tipe'=>'angka', 'level'=>3])->draw();
		$data['note_propose']=form_textarea('note_propose', ''," id='note_propose' placeholder = 'silahkan masukkan catatan anda disini' maxlength='500' size='500' class='form-control' style='overflow: hidden; width: 100% !important; height: 200px;' onblur='_maxLength(this , \"id_sisa_1\")' onkeyup='_maxLength(this , \"id_sisa_1\")' data-role='tagsinput'", true, ['size'=>500, 'isi'=>0,'no'=>1]);

		// $alur=$this->data_alur(['owner_no'=>$data['parent']['owner_id'], 'ass_type_no'=>$data['parent']['type_ass_id']]);
		$alur=json_decode($data['parent']['param_approval'],true);
		$data['alur']=$alur;
		$data['histori']=$this->db->where('rcsa_id', $id)->where('tipe_log', 1)->order_by('tanggal desc')->get(_TBL_VIEW_LOG_APPROVAL)->result_array();
		$data['info_alur']=$this->load->view('info-alur',$data, true);
		
        $data_notif = [];
        $data_notif_asli = [];

		$ket='Risk Context ini belum memiliki Owner yang akan melakukan approval';
        if ($alur) {
            if (array_key_exists($data['parent']['status_id']+1, $alur)){
				$data_notif = $alur[$data['parent']['status_id']+1];
				$data_notif_asli = $alur[$data['parent']['status_id']];
				$ket = 'Risk Context akan dikirim ke <strong>'.$data_notif['staft'].'</strong> bagian <strong>'.$data_notif['owner'].'</strong>';
            }
		}
		$sts_final=0;
		if (count($alur)==($data['parent']['status_id']+1)){
			$ket='1 langkah lagi Risk Context ini akan Final';
			$sts_final=1;
			$data_notif_asli = $alur[$data['parent']['status_id']];
			$data_notif = $alur[$data['parent']['status_id']];
		}

		$data['lanjut']=$data_notif;
		$data['poin_start']=$data_notif_asli;
		$data['id']=$id;
		$data['sts_final']=$sts_final;
		$x['notif'] = json_encode($data_notif);
        $x['sts_final'] = $sts_final;
        $x['ket'] = $ket;
        $x['id'] = $id;
        $x['alur'] = json_encode($alur);
		$data['hidden']=$x;

		$regis=$this->data->get_data_register($id);
		$regis['id']=$id;
		$regis['export']=false;

		$regis['notes']=true;
		$regis['poin_start']=$data_notif_asli;
		$regis['hidden']=$x;

		$data['regis']=$this->load->view('risk_context/register', $regis, true);
		
		$hasil=$this->load->view('propose',$data, true);
		$configuration = [
			'show_title_header' => false,
			'show_action_button' => false,
			'box_content' => false,
		];

		$this->default_display(['content'=>$hasil, 'configuration'=>$configuration]);
	}

	function revisi_propose(){
		$post = $this->input->post();

        $alur=json_decode($post['alur'], true);
		$notif=json_decode($post['notif'], true);

		if ($post['sts_final']){
			$urut=count($alur);
        }else{
			$urut=$notif['urut'];
		}

		$urut_back=$urut-2;
		$notif=$alur[$urut_back];

		$this->crud->crud_table(_TBL_RCSA);
		$this->crud->crud_type('edit');
		$this->crud->crud_field('status_revisi', $urut - 1, 'int');
		$this->crud->crud_field('status_id', 0, 'int');
		
		$this->crud->crud_field('note_propose', $post['note_propose']);
		$this->crud->crud_field('param_approval', json_encode($alur));
		$this->crud->crud_field('tgl_propose', date('Y-m-d H:i:s'), 'datetime');
		$this->crud->crud_where(['field' => 'id', 'value' => $post['id']]);
		$this->crud->process_crud();

		$this->crud->crud_table(_TBL_LOG_APPROVAL);
		$this->crud->crud_type('add');
		$this->crud->crud_field('rcsa_id', $post['id'], 'int');
		$this->crud->crud_field('keterangan', 'Revisi ke '.$notif['level']);
		$this->crud->crud_field('note', $post['note_propose']);
		$this->crud->crud_field('tanggal', date('Y-m-d H:i:s'), 'datetime');
		$this->crud->crud_field('user_id', $this->ion_auth->get_user_id());
		$this->crud->crud_field('penerima_id', $notif['staft_no']);
		$this->crud->process_crud();

		$creatorEmail = $this->data->get_email_creator($post['id']);

		$content_replace = ['[[owner]]'=>$this->_data_user_['real_name']];

		$datasOutbox=[
			'recipient' => $creatorEmail->email,
		];

		$this->load->library('outbox');
		$this->outbox->setTemplate('NOTIF03');
		$this->outbox->setParams($content_replace);
		$this->outbox->setDatas($datasOutbox);
		$this->outbox->send();

		echo json_encode(['combo'=>true]);
	}
	
	function simpan_propose(){
		$post = $this->input->post();

        $alur=json_decode($post['alur'], true);
		$notif=json_decode($post['notif'], true);
		// dumps($alur);die();
		$sts_final=$post['sts_final'];
        if ($post['sts_final']){
			$sts_final=1;
			$urut=count($alur);
			$alur[$notif['urut']]['tanggal']=date('Y-m-d H:i:s');
        }else{
			$urut=$notif['urut'];
			$alur[$notif['urut']-1]['tanggal']=date('Y-m-d H:i:s');
		}
		
		$sts_monit=false;
		if (array_key_exists('sts_monit', $notif)){
			if($notif['sts_monit']){
				$sts_monit=true;
				$nil_monit=intval($notif['sts_monit']);
			}
		}
		
		$this->crud->crud_table(_TBL_RCSA);
		$this->crud->crud_type('edit');
		$this->crud->crud_field('status_revisi', 0, 'int');
		$this->crud->crud_field('status_id', $urut, 'int');
		$this->crud->crud_field('status_final', $sts_final, 'int');
		if($sts_monit){
			$this->crud->crud_field('status_monitoring', $nil_monit, 'int');
		}
		$this->crud->crud_field('note_propose', $post['note_propose']);
		$this->crud->crud_field('param_approval', json_encode($alur));
		$this->crud->crud_field('tgl_propose', date('Y-m-d H:i:s'), 'datetime');
		$this->crud->crud_where(['field' => 'id', 'value' => $post['id']]);
		$this->crud->process_crud();

		$this->crud->crud_table(_TBL_LOG_APPROVAL);
		$this->crud->crud_type('add');
		$this->crud->crud_field('rcsa_id', $post['id'], 'int');
		if($sts_final){
			$this->crud->crud_field('keterangan', 'Final');
		}else{
			$this->crud->crud_field('keterangan', 'Propose ke '.$notif['level']);
		}
		$this->crud->crud_field('note', $post['note_propose']);
		$this->crud->crud_field('tanggal', date('Y-m-d H:i:s'), 'datetime');
		$this->crud->crud_field('user_id', $this->ion_auth->get_user_id());
		$this->crud->crud_field('penerima_id', $notif['staft_no']);
		$this->crud->process_crud();
		
		foreach($post['rcsa_detail_id'] as $row){
			$note=[];
			foreach($alur as $key=>$al){
				if (isset($post[$row.'_'.$al['level_approval_id']])){
					$note[$al['level_approval_id']]['name']=$al['level'];
					$note[$al['level_approval_id']]['note']=$post[$row.'_'.$al['level_approval_id']];
				}
			}

			if ($note){
				$this->crud->crud_table(_TBL_RCSA_DETAIL);
				$this->crud->crud_type('edit');
				$this->crud->crud_field('note_approval', json_encode($note));
				$this->crud->crud_where(['field' => 'id', 'value' => $row]);
				$this->crud->process_crud();
			}
		}
		$urut_back=$urut-1;
		$notif=$alur[$urut_back];
		if (array_key_exists('sts_notif', $notif)){
			if($notif['sts_notif']){
				$parent = $this->db->where('id', $post['id'])->get(_TBL_VIEW_RCSA)->row_array();
				$rows = $this->db->where('rcsa_id', $post['id'])->get(_TBL_VIEW_RCSA_MITIGASI)->result_array();
				$pic=[];
				$arr_pic=[];
				foreach($rows as $row){
					$pic[$row['penanggung_jawab_id']][]=$row;
					$arr_pic[$row['penanggung_jawab_id']]=$row['penanggung_jawab_id'];
				}
				$rows = $this->db->where_in('owner_no', $arr_pic)->where('sts_owner',1)->get(_TBL_VIEW_OFFICER)->result_array();
				$officer=[];
				foreach($rows as $row){
					$officer[$row['owner_no']][$row['id']]=$row;
				}
				foreach($officer as $key_of=>$of){
					$email=[];
					$officer_name=[];
					$mitigasi='';
					if (array_key_exists($key_of, $pic)){
						$mitigasi='<table width="100%" border="100%">';
						$mitigasi.='<thead><tr><th width="5%">No.</tdh><th>Mitigasi</th><th width="15%">Biaya</th></tr></thead><tbody>';
						foreach($officer[$key_of] as $key=>$row){
							if(!empty($row['email'])){
								$email[]=$row['email'];
								$officer_name[]=$row['officer_name'];
							}
						}
						
						$no_miti=0;
						foreach($pic[$key_of] as $key_pic=>$pi){
							$mitigasi .= '<tr><td>'.++$no_miti.'</td><td>'.$pi['mitigasi'].'</td><td>'.number_format($pi['biaya']).'</td></tr>';
						}
						$mitigasi.='</tbody></table>';
					}

					$content_replace = ['[[dept]]'=>$parent['owner_name'], '[[owner]]'=>$this->ion_auth->get_user_name(), '[[mitigasi]]'=>$mitigasi, '[[officer]]'=>implode(', ',$officer_name)];
					
					if ($email){
						$datasOutbox=[
							'recipient' => $email,
							'cc' => ['debug.aplikasi@gmail.com'],
						];
						$this->load->library('outbox');
						$this->outbox->setTemplate('NOTIF02');
						$this->outbox->setParams($content_replace);
						$this->outbox->setDatas($datasOutbox);
						$this->outbox->send();
					}
				}
			}
		}

		$staft = json_decode($post['notif'], true);

		$staft_name = [];
		$staft_email = [];

		if ($staft['urut'] == 3 && $post['sts_final'] != 1) {
			$all_admin = $this->data->get_email_role_admin_mr();
			
			foreach ($all_admin as $adm) {
				if ($adm->email) {
					$staft_name[] = $adm->officer_name;
					$staft_email[] = $adm->email;					
				} 				
			}

			$content_replace = ['[[owner]]'=> implode(", ",$staft_name)];

			$datasOutbox=[
				'recipient' => $staft_email
			];
		} else {
			$content_replace = ['[[owner]]'=>$staft['staft']];

			$datasOutbox=[
				'recipient' => $staft['email'],
			];

		}

		$this->__send_email($content_replace, $datasOutbox);

		header('location:'.base_url(_MODULE_NAME_));
	}

	private function __send_email($content_replace, $datasOutbox)
	{
		$this->load->library('outbox');
		$this->outbox->setTemplate('NOTIF01');
		$this->outbox->setParams($content_replace);
		$this->outbox->setDatas($datasOutbox);
		$this->outbox->send();
	}

	function data_alur($param=[]){
		$rows = $this->db->where('id', $param['owner_no'])->get(_TBL_VIEW_OWNER_PARENT)->row_array();
        $owner=[];
        $officer=[];
        if ($rows){
            if (!empty($rows['level_approval'])){
                $level = explode(',',$rows['level_approval']);
                foreach($level as $x){
                    $owner[$x]=['id'=>$rows['id'],'name'=>$rows['parent_name']];$officer[$x]=$rows['id'];
                }
            }
            if (!empty($rows['level_approval_1'])){ 
                $level = explode(',',$rows['level_approval_1']);
                foreach($level as $x){
                    $owner[$x]=['id'=>$rows['lv_1_id'],'name'=>$rows['lv_1_name']];$officer[$x]=$rows['lv_1_id'];
                }
            }
            if (!empty($rows['level_approval_2'])){ 
                $level = explode(',',$rows['level_approval_2']);
                foreach($level as $x){
                    $owner[$x]=['id'=>$rows['lv_2_id'],'name'=>$rows['lv_2_name']];$officer[$x]=$rows['lv_2_id'];
                }
            }
            if (!empty($rows['level_approval_3'])){ 
                $level = explode(',',$rows['level_approval_3']);
                foreach($level as $x){
                    $owner[$x]=['id'=>$rows['lv_3_id'],'name'=>$rows['lv_3_name']];$officer[$x]=$rows['lv_3_id'];
                }
            }
		}

        $staft=[];
        if ($officer){
            $rows = $this->db->where_in('owner_no', $officer)->group_start()->where('sts_owner',1)->or_where('sts_approval',1)->group_end()->get(_TBL_VIEW_OFFICER)->result_array();
            foreach($rows as $row){
                $staft[$row['owner_no']]['name'][] = $row['officer_name'];
                $staft[$row['owner_no']]['id'][] = $row['id'];
                $staft[$row['owner_no']]['email'][] = $row['email'];
            }
		}
		
        $rows = $this->db->where('id',$param['ass_type_no'])->get(_TBL_COMBO)->row_array();
        $type_approval=0;
        if ($rows){
            $type_approval=intval($rows['pid']);
		}
		
        $rows = $this->db->select("'' as staft, '' as bagian, "._TBL_VIEW_APPROVAL.".*")->where('pid', $type_approval)->order_by('urut')->get(_TBL_VIEW_APPROVAL)->result_array();
        $alur[0] = ['level'=>'Risk Officer', 'owner'=>'', 'staft'=>'', 'level_approval_id'=>0, 'owner_no'=>0, 'staft_no'=>0, 'urut'=>0, 'sts_last'=>0, 'email'=>'', 'tanggal'=>''];
        foreach($rows as $row){
            $ow='';
            $ow_id='';
            $of='';
            $of_id='';
			$email='';
			if (intval($row['param_other'])==0){
				if (array_key_exists($row['param_int'], $owner)){
					$ow=$owner[$row['param_int']]['name'];
					$ow_id=$owner[$row['param_int']]['id'];
					if (array_key_exists($owner[$row['param_int']]['id'], $staft)){
						$of=implode(', ',$staft[$owner[$row['param_int']]['id']]['name']);
						$of_id=implode(', ',$staft[$owner[$row['param_int']]['id']]['id']);
						$email=implode(', ',$staft[$owner[$row['param_int']]['id']]['email']);
					}
				}
			}elseif (intval($row['param_other'])==1){
                $arr_free = $this->db->where_in('level_approval', $row['param_int'])->get(_TBL_OWNER)->row_array();
                if ($arr_free){
                    $ow=$arr_free['owner_name'];
                    $ow_id=$arr_free['id'];
                    $of_arr=[];
                    $of_id_arr=[];
                    $email_arr=[];
                    $arr_free = $this->db->where('owner_no', $ow_id)->group_start()->where('sts_owner',1)->or_where('sts_approval',1)->group_end()->get(_TBL_VIEW_OFFICER)->result_array();
                    if($arr_free){
                        foreach($arr_free as $fr){
                            $of_arr[] = $fr['officer_name'];
                            $of_id_arr[] = $fr['id'];
                            $email_arr[] = $fr['email'];
                        }
                        $of=implode(', ',$of_arr);
                        $of_id=implode(', ',$of_id_arr);
                        $email=implode(', ',$email_arr);
                    }
                }
            }
            $alur[$row['urut']] = ['level'=>$row['model'], 'owner'=>$ow, 'staft'=>$of, 'level_approval_id'=>$row['id'], 'owner_no'=>$ow_id, 'staft_no'=>$of_id, 'urut'=>$row['urut'], 'sts_last'=>$row['sts_last'], 'email'=>$email, 'tanggal'=>''];
        }
        return $alur;
	}

	function optionalPersonalButton($button, $row){
		$button=[];
		if (!$row['status_final']){
			$button['propose']=[
				'label'=>'Propose Risiko',
				'id'=>'btn_propose_one',
				'class'=>'text-warning',
				'icon' =>'icon-file-spreadsheet ',
				'url' => base_url(_MODULE_NAME_.'/propose-risiko/'),
				'attr' => ' target="_self" '
			];
		}
		
		return $button;
	}

}