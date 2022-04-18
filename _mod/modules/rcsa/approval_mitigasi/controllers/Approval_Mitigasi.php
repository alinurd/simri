<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Approval_Mitigasi extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->language('risk_context');
		$this->load->language('monitoring_mitigasi');
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
		$this->addField(['field'=>'period_id', 'title'=>'Periode', 'type'=>'int', 'required'=>true,'input'=>'combo', 'search'=>true, 'values'=>$this->period]);
		$this->addField(['field'=>'term_id', 'title'=>'Term', 'type'=>'int', 'required'=>true,'input'=>'combo', 'search'=>true, 'values'=>[]]);
		$this->addField(['field'=>'active', 'input'=>'boolean', 'size'=>20]);
		$this->addField(['field'=>'status_id', 'show'=>false]);
		$this->addField(['field'=>'status_final', 'show'=>false]);
		$this->addField(['field'=>'status_revisi', 'show'=>false]);
		$this->addField(['field'=>'tgl_propose_mitigasi', 'type'=>'date', 'input'=>'date', 'show'=>false]);
		$this->addField(['field'=>'register', 'title'=>'Laporan', 'type'=>'free', 'show'=>false]);
		$this->addField(['field'=>'created_at', 'show'=>false]);
		$this->addField(['field'=>'status_revisi_mitigasi', 'show'=>false]);
		$this->addField(['field'=>'status_id_mitigasi', 'show'=>false]);
		$this->addField(['field'=>'status_final_mitigasi', 'show'=>false]);
		$this->addField(['field'=>'term', 'show'=>false]);
		$this->addField(['field'=>'kode_dept', 'show'=>false]);



		$this->set_Field_Primary($this->tbl_master, 'id', true);

		$this->set_Sort_Table($this->tbl_master,'created_at', 'desc');
		$this->set_Where_Table(['field'=>'status_id_mitigasi', 'value'=>1, 'op'=>'>=']);
		$this->set_Where_Table(['field'=>'status_final', 'value'=>1, 'op'=>'>=']);
		// $this->set_Where_Table(['field'=>'status_final_mitigasi', 'value'=>0, 'op'=>'=']);

		$this->set_Table_List($this->tbl_master,'owner_id');
		$this->set_Table_List($this->tbl_master,'kode_dept','');

		$this->set_Table_List($this->tbl_master,'stakeholder_id');
		$this->set_Table_List($this->tbl_master,'type_ass_id', 'Tipe Ass');
		$this->set_Table_List($this->tbl_master,'period_id', 'Tahun');
		$this->set_Table_List($this->tbl_master,'term', 'Periode');

		$this->set_Table_List($this->tbl_master,'status_id_mitigasi');
		$this->set_Table_List($this->tbl_master,'tgl_propose_mitigasi');
		$this->set_Table_List($this->tbl_master,'register','',7, 'center');

		$this->set_Save_Table(_TBL_RCSA);
		$this->_set_Where_Owner();
		
		$this->setPrivilege('delete', false);
		$this->setPrivilege('update', false);
		$this->setPrivilege('insert', false);

		$this->set_Close_Setting();
		
		$configuration = [
			'show_title_header' => false,
			'content_title' =>'Daftar Approval Mitigasi'
		];
		return [
			'configuration'	=> $configuration
		];
	}

	public function MASTER_DATA_LIST($arrId, $rows)
    {
        $arr_approval = $this->db->where('pid', 249)->order_by('urut')->get(_TBL_VIEW_APPROVAL)->result_array();
		$this->approval=[];
		foreach($arr_approval as $row){
			$this->approval[$row['urut']] = $row;
		}
		if (count($arrId)>0) {
			$rows = $this->db->select('rcsa_id, count(rcsa_id) as jml')
			->where_in('rcsa_id', $arrId)
			->order_by('rcsa_id')->group_by('rcsa_id')->get(_TBL_VIEW_RCSA_KPI)->result_array();
		} else {
			$rows = $this->db->select('rcsa_id, count(rcsa_id) as jml')
			->where('rcsa_id', 0)
			->order_by('rcsa_id')->group_by('rcsa_id')->get(_TBL_VIEW_RCSA_KPI)->result_array();
		}
		
		$this->kpi=[];
		foreach($rows as $row){
			$this->kpi[$row['rcsa_id']] = $row['jml'];
		}
	}

	// public function MASTER_DATA_LIST($arrId, $rows)
    // {
    //     $arr_approval = $this->db->where('pid', 249)->order_by('urut')->get(_TBL_VIEW_APPROVAL)->result_array();
	// 	$this->approval=[];
	// 	foreach($arr_approval as $row){
	// 		$this->approval[$row['urut']] = $row;
	// 	}
	// }

	function listBox_STATUS_ID_MITIGASI($field, $rows, $value){
        $revisi=intval($rows['status_revisi_mitigasi']);
		$urut=intval($rows['status_id_mitigasi']);
		$final=intval($rows['status_final_mitigasi']);
		$hasil = '<span class="propose btn  pointer" style="width:100% !important;padding:5px;background-color:'.$this->_preference_['warna_propose'].';color:#ffffff;" data-id="' . $rows['id'].'"> '._l('msg_notif_propose').' </span>';

        if ($final ){
		// if ($final && $rows['tgl_selesai_term'] == $rows['tgl_akhir_mitigasi']){
            $hasil = '<div class="label text-center" style="background-color:'.$this->_preference_['warna_approved'].';color:#ffffff;width:100%;padding:10px 5px; display:block;"> '._l('msg_notif_approved').'</div>';
			$hasil = $hasil;
        }
        elseif (array_key_exists($urut, $this->approval)){
			$ket = ' - ';
			if(!empty($this->approval[$urut]['model'])){
				$ket = $this->approval[$urut]['model'];
			}
			$hasil = '<div class="label text-center" style="background-color:'.$this->approval[$urut]['warna'].';color:#ffffff;width:100%;padding:10px 5px; display:block;">'._l('msg_notif_need_approved').'<br/>'.$this->approval[$urut]['model'].'</div><br/>';
        }

        return $hasil;
    }

	function listBox_REGISTER($field, $rows, $value){
		$o='<i class="icon-menu6 pointer text-primary risk-monitoring" title=" View Risk Register " data-id="'.$rows['id'].'"></i>';

		return $o;
	}

	function listBox_TGL_PROPOSE_MITIGASI($field, $rows, $value){
		
		if($rows['status_id_mitigasi']==0){
			$value='';
		}
		return $value;
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

	function progress(){
		if ($this->input->is_ajax_request()){
			$id=intval($this->input->post('id'));
		}else{
			$id=intval($this->uri->segment(3));
		}
		$data['parent']=$this->db->where('id', $id)->get(_TBL_VIEW_RCSA)->row_array();
		$data['info_parent']=$this->load->view('info-parent',$data, true);
		
		$rows=$this->db->where('rcsa_id', $id)->get(_TBL_VIEW_RCSA_MITIGASI_DETAIL)->result_array();
		$data['detail']=$rows;
		$hasil=$this->load->view('mitigasi',$data, true);
		$configuration = [
			'show_title_header' => false,
			'show_action_button' => false,
		];

		$this->default_display(['content'=>$hasil, 'configuration'=>$configuration]);
	}

	function update_progres($id_edit=0, $id=0){
		$awal=false;
		if (!$id){
			$awal=true;
			$id=intval($this->uri->segment(3));
			$id_edit=0;
		}

		$dp = $this->db->where('id', $id_edit)->get(_TBL_VIEW_RCSA_MITIGASI_PROGRES)->row_array();
		$am = $this->db->where('id', $id)->get(_TBL_VIEW_RCSA_MITIGASI_DETAIL)->row_array();
		if ($am){
			$data['detail_progres'] = $this->db->where('rcsa_mitigasi_detail_id', $id)->get(_TBL_VIEW_RCSA_MITIGASI_PROGRES)->result_array();
			$data['aktifitas_mitigas'] = $am;
			$mit = $this->db->where('id', $data['aktifitas_mitigas']['rcsa_mitigasi_id'])->get(_TBL_VIEW_RCSA_MITIGASI)->row_array();
			$mit = $this->convert_owner->set_data($mit, false)->set_param(['penanggung_jawab'=>'penanggung_jawab_id', 'koordinator'=>'koordinator_id'])->draw();
			$rcsa_detail = $this->db->where('id', $mit['rcsa_detail_id'])->get(_TBL_VIEW_RCSA_DETAIL)->row_array();
			$data['parent'] = $this->db->where('id', $rcsa_detail['rcsa_id'])->get(_TBL_VIEW_RCSA)->row_array();

			$data['minggu']=$this->crud->combo_select(['id', 'concat(param_string) as minggu'])->combo_where('kelompok', 'minggu')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();

			// $curMing = $this->_data_user_['term']['period_id'];
			$minggupil=$this->crud->combo_select(['id', 'concat(param_string, \' ( \', param_date, \' s.d \', param_date_after, \' ) \') as minggu'])->combo_where('kelompok', 'minggu')->combo_where('active', 1)->combo_where('pid',_TAHUN_ID_)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();

			$minggu = form_dropdown('minggu', $minggupil, ($dp)?$dp['minggu_id']:_MINGGU_ID_, 'class="form-control select" style="width:100%;"  id="minggu"');
			$minggu .= '<script>$(".select").select2({
				allowClear: false
			});</script>';

			$aktual= '<div class="input-group" style="width:19% !important;"> <button type="button" onclick="this.parentNode.querySelector(\'[type=number]\').stepDown();"> - </button>';
			$aktual .= form_input(['type'=>'number','name'=>'aktual'],($dp)?$dp['aktual']:'1'," class='form-control touchspin-postfix text-center' max='100' min='1' step='1' id='aktual' ");
			$aktual .= '<button type="button" onclick="this.parentNode.querySelector(\'[type=number]\').stepUp();"> + </button> <div class="form-control-feedback text-primary form-control-feedback-lg pointer" style="right:-25%;"> % </div></div>';

			$target= '<div class="input-group" style="width:19% !important;"> <button type="button" onclick="this.parentNode.querySelector(\'[type=number]\').stepDown();"> - </button>';
			$target .= form_input(['type'=>'number','name'=>'target'],($dp)?$dp['target']:'1'," class='form-control touchspin-postfix text-center' max='100' min='1' step='1' id='target' ");
			$target .= '<button type="button" onclick="this.parentNode.querySelector(\'[type=number]\').stepUp();"> + </button> <div class="form-control-feedback text-primary form-control-feedback-lg pointer" style="right:-25%;"> % </div></div>';

			$data['progres'][] = ['title'=>"Bulan Progress", 'mandatori'=>false,'isi'=>$minggu];
			
			$data['progres'][] = ['title'=>_l('fld_target'),'help'=>_h('help_target'),'isi'=>$target];
			$data['progres'][] = ['title'=>_l('fld_aktual'),'help'=>_h('help_aktual'),'isi'=>$aktual];
			$data['progres'][] = ['title'=>_l('fld_uraian'),'help'=>_h('help_uraian'),'isi'=>form_textarea('uraian', ($dp)?$dp['uraian']:''," id='uraian' maxlength='500' size='500' class='form-control' style='overflow: hidden; width: 100% !important; height: 100px;' onblur='_maxLength(this , \"id_sisa_1\")' onkeyup='_maxLength(this , \"id_sisa_1\")' data-role='tagsinput'", true, ['size'=>1000, 'isi'=>0,'no'=>1])];
			$data['progres'][] = ['title'=>_l('fld_kendala'),'help'=>_h('help_kendala'),'isi'=>form_textarea('kendala', ($dp)?$dp['kendala']:''," id='kendala' maxlength='500' size='500' class='form-control' style='overflow: hidden; width: 100% !important; height: 100px;' onblur='_maxLength(this , \"id_sisa_2\")' onkeyup='_maxLength(this , \"id_sisa_2\")' data-role='tagsinput'", true, ['size'=>1000, 'isi'=>0,'no'=>2])];
			$data['progres'][] = ['title'=>_l('fld_tindak_lanjut'),'help'=>_h('help_tindak_lanjut'),'isi'=>form_textarea('tindak_lanjut', ($dp)?$dp['tindak_lanjut']:''," id='tindak_lanjut' maxlength='500' size='500' class='form-control' style='overflow: hidden; width: 100% !important; height: 100px;' onblur='_maxLength(this , \"id_sisa_3\")' onkeyup='_maxLength(this , \"id_sisa_3\")' data-role='tagsinput'", true, ['size'=>1000, 'isi'=>0,'no'=>3])];
			$data['progres'][] = ['title'=>_l('fld_due_date'),'help'=>_h('help_due_date'),'isi'=>form_input('batas_waktu_tindak_lanjut', ($dp)?$dp['batas_waktu_tindak_lanjut']:'', 'class="form-control pickadate" id="batas_waktu_tindak_lanjut" style="width:100%;"')];

			$data['progres'][] = ['title'=>_l('fld_keterangan'),'help'=>_h('help_keterangan'),'isi'=>form_textarea('keterangan', ($dp)?$dp['keterangan']:''," id='keterangan' maxlength='500' size='500' class='form-control' style='overflow: hidden; width: 100% !important; height: 100px;' onblur='_maxLength(this , \"id_sisa_4\")' onkeyup='_maxLength(this , \"id_sisa_4\")' data-role='tagsinput'", true, ['size'=>1000, 'isi'=>0,'no'=>4])];
			$data['progres'][] = ['title'=>_l('fld_lampiran'),'help'=>_h('help_lampiran'),'isi'=>form_upload('lampiran')];
			$data['progres'][] = ['title'=>'','help'=>'','isi'=>form_hidden(['aktifitas_mitigasi_id'=>$id, 'id'=>$id_edit])];

			$data['info_1'][] = ['title'=>_l('fld_risiko_dept'),'isi'=>$rcsa_detail['risiko_dept']];
			$data['info_1'][] = ['title'=>_l('fld_risiko_inherent'),'isi'=>$rcsa_detail['level_color']];
			$data['info_1'][] = ['title'=>_l('fld_efek_kontrol'),'isi'=>$rcsa_detail['efek_kontrol_text']];
			$data['info_1'][] = ['title'=>_l('fld_nama_control'),'isi'=>$rcsa_detail['nama_kontrol']];
			$data['info_1'][] = ['title'=>_l('fld_level_risiko'),'isi'=>$rcsa_detail['level_color_residual']];
			$data['info_1'][] = ['title'=>_l('fld_treatment'),'isi'=>$rcsa_detail['treatment']];

			$data['info_2'][] = ['title'=>_l('fld_mitigasi'),'isi'=>$mit['mitigasi']];
			$data['info_2'][] = ['title'=>_l('fld_biaya'),'isi'=>number_format($mit['biaya'])];
			$data['info_2'][] = ['title'=>_l('fld_pic'),'isi'=>$mit['penanggung_jawab']];
			$data['info_2'][] = ['title'=>_l('fld_koordinator'),'isi'=>$mit['koordinator']];
			$data['info_2'][] = ['title'=>_l('fld_due_date'),'isi'=>date('d-M-Y',strtotime($mit['batas_waktu']))];

			$data['informasi'] = $this->load->view('informasi', $data, true);
			$data['list_progres'] = $this->load->view('list-progres', $data, true);
			$data['update'] = $this->load->view('progres', $data, true);

			$hasil=$this->load->view('monitoring',$data, true);
			$configuration = [
				'show_title_header' => false,
				'show_action_button' => false,
			];

			if ($awal){
				$this->default_display(['content'=>$hasil, 'configuration'=>$configuration]);
			}else{
				return $data;
			}
		}else{
			header('location:'.base_url(_MODULE_NAME_));
		}
	}

	function add_progres(){
		$id=intval($this->input->post('id'));
		$mitigasi_id=intval($this->input->post('mitigasi_id'));
		$hasil = $this->update_progres($id, $mitigasi_id);
		echo json_encode(['combo'=>$hasil['update']]);
	}

	function simpan_progres(){
		$post = $this->input->post();
		$id_edit = $this->data->simpan_progres($post);

		$id=intval($post['aktifitas_mitigasi_id']);
		$hasil=$this->update_progres(0, $id);
		$result['update'] = $hasil['update'];
		$result['list_progres'] = $hasil['list_progres'];
		echo json_encode($result);
	}

	function hapus_progres(){
		$id=intval($this->input->post('id'));
		$mitigasi_id=intval($this->input->post('mitigasi_id'));
		$this->crud->crud_table(_TBL_RCSA_MITIGASI_PROGRES);
		$this->crud->crud_type('delete');
		$this->crud->crud_where(['field' => 'id', 'value' => $id]);
		$this->crud->process_crud();
		$hasil=$this->update_progres(0, $mitigasi_id);
		$result['list_progres'] = $hasil['list_progres'];
		$result['combo'] = 'Sukses';
		echo json_encode($result);
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
        $staft_tahu=[];
        $staft_setuju=[];
        $staft_valid=[];
        if ($officer){
            $rows = $this->db->where_in('owner_no', $officer)->group_start()->where('sts_mengetahui',1)->or_where('sts_menyetujui',1)->or_where('sts_menvalidasi',1)->group_end()->get(_TBL_VIEW_OFFICER)->result_array();
            foreach($rows as $row){
				if ($row['sts_mengetahui']==1){
					$staft_tahu[$row['owner_no']]['name'][] = $row['officer_name'];
					$staft_tahu[$row['owner_no']]['id'][] = $row['id'];
					$staft_tahu[$row['owner_no']]['email'][] = $row['email'];
				}elseif ($row['sts_menyetujui']==1){
					$staft_setuju[$row['owner_no']]['name'][] = $row['officer_name'];
					$staft_setuju[$row['owner_no']]['id'][] = $row['id'];
					$staft_setuju[$row['owner_no']]['email'][] = $row['email'];
				}elseif ($row['sts_menvalidasi']==1){
					$staft_valid[$row['owner_no']]['name'][] = $row['officer_name'];
					$staft_valid[$row['owner_no']]['id'][] = $row['id'];
					$staft_valid[$row['owner_no']]['email'][] = $row['email'];
				}
            }
        }
       
        $rows = $this->db->select("'' as staft, '' as bagian, "._TBL_VIEW_APPROVAL.".*")->where('pid', 249)->order_by('urut')->get(_TBL_VIEW_APPROVAL)->result_array();
        $alur[0] = ['level'=>'Risk Officer', 'owner'=>'', 'staft'=>'', 'level_approval_id'=>0, 'owner_no'=>0, 'staft_no'=>0, 'urut'=>0, 'sts_last'=>0, 'email'=>'', 'tanggal'=>'', 'sts_monit'=>0];
        foreach($rows as $row){
			$prm=json_decode($row['param_text'],true);
            $ow='';
            $ow_id='';
            $of='';
            $of_id='';
			$email='';
			if (intval($prm['tipe_approval'])==0){
				if (array_key_exists($row['param_int'], $owner)){
					$ow=$owner[$row['param_int']]['name'];
					$ow_id=$owner[$row['param_int']]['id'];
					if($prm['level_approval']==1){
						if (array_key_exists($owner[$row['param_int']]['id'], $staft_tahu)){
							$of=implode(', ',$staft_tahu[$owner[$row['param_int']]['id']]['name']);
							$of_id=implode(', ',$staft_tahu[$owner[$row['param_int']]['id']]['id']);
							$email=implode(', ',$staft_tahu[$owner[$row['param_int']]['id']]['email']);
						}
					}elseif($prm['level_approval']==2){
						if (array_key_exists($owner[$row['param_int']]['id'], $staft_setuju)){
							$of=implode(', ',$staft_setuju[$owner[$row['param_int']]['id']]['name']);
							$of_id=implode(', ',$staft_setuju[$owner[$row['param_int']]['id']]['id']);
							$email=implode(', ',$staft_setuju[$owner[$row['param_int']]['id']]['email']);
						}
					}elseif($prm['level_approval']==3){
						if (array_key_exists($owner[$row['param_int']]['id'], $staft_valid)){
							$of=implode(', ',$staft_valid[$owner[$row['param_int']]['id']]['name']);
							$of_id=implode(', ',$staft_valid[$owner[$row['param_int']]['id']]['id']);
							$email=implode(', ',$staft_valid[$owner[$row['param_int']]['id']]['email']);
						}
					}
				}
			}elseif (intval($prm['tipe_approval'])==1){
                $arr_free = $this->db->where_in('level_approval', $row['param_int'])->get(_TBL_OWNER)->row_array();
                if ($arr_free){
                    $ow=$arr_free['owner_name'];
                    $ow_id=$arr_free['id'];
                    $of_arr=[];
                    $of_id_arr=[];
                    $email_arr=[];
                    $arr_free = $this->db->where('owner_no', $ow_id)->group_start()->where('sts_mengetahui',1)->or_where('sts_menyetujui',1)->or_where('sts_menvalidasi',1)->group_end()->get(_TBL_VIEW_OFFICER)->result_array();
                    if($arr_free){
                        foreach($arr_free as $fr){
							if ($prm['level_approval']==1 && $fr['sts_mengetahui']==1){
								$of_arr[] = $fr['officer_name'];
								$of_id_arr[] = $fr['id'];
								$email_arr[] = $fr['email'];
							}elseif ($prm['level_approval']==2 && $fr['sts_menyetujui']==1){
								$of_arr[] = $fr['officer_name'];
								$of_id_arr[] = $fr['id'];
								$email_arr[] = $fr['email'];
							}elseif ($prm['level_approval']==3 && $fr['sts_menvalidasi']==1){
								$of_arr[] = $fr['officer_name'];
								$of_id_arr[] = $fr['id'];
								$email_arr[] = $fr['email'];
							}
                        }
                        $of=implode(', ',$of_arr);
                        $of_id=implode(', ',$of_id_arr);
                        $email=implode(', ',$email_arr);
                    }
                }
            }
            $alur[$row['urut']] = ['level'=>$row['model'], 'owner'=>$ow, 'staft'=>$of, 'level_approval_id'=>$row['id'], 'owner_no'=>$ow_id, 'staft_no'=>$of_id, 'urut'=>$row['urut'], 'sts_last'=>$row['sts_last'], 'email'=>$email, 'tanggal'=>'', 'sts_monit'=>$prm['monit'], 'sts_notif'=>$prm['notif_email']];
        }
        return $alur;
	}

	function propose_mitigasi(){
		$pos=$this->input->post();
		$id=$pos['id'];
		$data['note_propose']=form_textarea('note_propose', ''," id='note_propose' placeholder = 'silahkan masukkan catatan anda disini' maxlength='500' size='500' class='form-control' style='overflow: hidden; width: 100% !important; height: 200px;' onblur='_maxLength(this , \"id_sisa_1\")' onkeyup='_maxLength(this , \"id_sisa_1\")' data-role='tagsinput'", true, ['size'=>500, 'isi'=>0,'no'=>1]);

		$data['parent']=$this->db->where('id', $pos['id'])->get(_TBL_VIEW_RCSA)->row_array();
		$data['info_parent']=$this->load->view('info-parent',$data, true);

		// $alur=$this->data_alur(['owner_no'=>$data['parent']['owner_id']]);
		$alur=json_decode($data['parent']['param_approval_mitigasi'],true);
        $data_notif = [];
		$data_notif_asli = ['level_approval_id'=>0];
		
		$data['alur']=$alur;
		$data['histori']=$this->db->where('rcsa_id', $id)->where('tipe_log', 2)->order_by('tanggal desc')->get(_TBL_VIEW_LOG_APPROVAL)->result_array();
		$data['info_alur']=$this->load->view('info-alur',$data, true);
		
		$ket='Progress Mitigasi Risk Context ini belum memiliki Owner atau officer yang akan melakukan approval mitigasi';
        if ($alur) {
            if (array_key_exists(1, $alur)){
				$data_notif = $alur[1];
				$data_notif_asli = $alur[0];
				$ket = 'Progress Mitigasi Risk Context akan dikirim ke <strong>'.$data_notif['staft'].'</strong> bagian <strong>'.$data_notif['owner'].'</strong>';
				if(!$data_notif['staft'] || !$data_notif['owner']){
					$data_notif = [];
					$ket='Progress Mitigasi Risk Context ini belum memiliki Owner atau officer yang akan melakukan approval mitigasi';
				}
            }
		}

		$sts_final=0;
		if (count($alur)==($data['parent']['status_id_mitigasi']+1)){
			$ket='1 langkah lagi Risk Context ini akan Final';
			$sts_final=1;
			$data_notif_asli = $alur[$data['parent']['status_id_mitigasi']];
			$data_notif = $alur[$data['parent']['status_id_mitigasi']];
		}

		$data['lanjut']=$data_notif;
		$data['poin_start']=$data_notif_asli;
		$data['id']=$id;
		$data['sts_final']=$sts_final;
        $x['sts_final'] = $sts_final;
		$x['notif'] = json_encode($data_notif);
        $x['ket'] = $ket;
        $x['id'] = $id;
        $x['alur'] = json_encode($alur);
		$data['hidden']=$x;
		$propose['combo']=$this->load->view('propose', $data, true);

		echo json_encode($propose);
	}

	function proses_propose_mitigasi(){
		$post=$this->input->post();
		$alur=json_decode($post['alur'], true);
        $notif=json_decode($post['notif'], true);
        $sts_final=0;
        if (count($alur)==$notif['urut']){
            $sts_final=1;
        }
		if ($post['sts_final']){
			$sts_final=1;
			$urut=count($alur);
			$alur[$notif['urut']]['tanggal']=date('Y-m-d H:i:s');
        }else{
			$urut=$notif['urut'];
			$alur[$notif['urut']-1]['tanggal']=date('Y-m-d H:i:s');
		}

		$this->crud->crud_table(_TBL_RCSA);
		$this->crud->crud_type('edit');
		$this->crud->crud_field('status_revisi_mitigasi', 0, 'int');
		$this->crud->crud_field('status_id_mitigasi', $notif['urut'], 'int');
		$this->crud->crud_field('note_propose_mitigasi', $post['note_propose']);
		$this->crud->crud_field('param_approval_mitigasi', json_encode($alur));
		$this->crud->crud_field('tgl_propose_mitigasi', date('Y-m-d H:i:s'), 'datetime');
		$this->crud->crud_field('status_final_mitigasi', $sts_final, 'int');
		$this->crud->crud_where(['field' => 'id', 'value' => $post['id']]);
		$this->crud->process_crud();

		$this->crud->crud_table(_TBL_LOG_APPROVAL);
		$this->crud->crud_type('add');
		$this->crud->crud_field('tipe_log', 2, 'int');
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

		$creatorEmail = $this->data->get_email_creator($post['id']);
		if ($creatorEmail) {
			
			$content_replace = ['[[owner]]'=>$this->_data_user_['real_name']];
	
			$datasOutbox=[
				'recipient' => $creatorEmail->email,
			];
	
			$this->load->library('outbox');
			$this->outbox->setTemplate('NOTIF02');
			$this->outbox->setParams($content_replace);
			$this->outbox->setDatas($datasOutbox);
			$this->outbox->send();
		}

		echo json_encode(['data'=>true]);
		// header('location:'.base_url(_MODULE_NAME_));
	}

	function review_kpi(){
		$pos=$this->input->post();
		$rows = $this->db->where('id', $pos['rcsa_id'])->get(_TBL_RCSA)->row_array();
		$pos['owner']=$rows['owner_id'];
		$pos['period']=$rows['period_id'];
		$pos['term']=$rows['term_id'];
		$this->data->pos=$pos;
		$data = $this->data->get_detail_data();
		$data['mode']=0;
		$x=$this->load->view('detail-kpi', $data, true);
		$y=$this->load->view('detail-kpi2', $data, true);
		// $this->session->set_userdata(['cetak_grap'=>$data]);
		$hasil['combo']=$x.$y;
		echo json_encode($hasil);
	}

	function optionalPersonalButton($button, $row){
		$button=[];
		$button['progress']=[
			'label'=>'Approve Mitigasi',
			'id'=>'btn_propose_one',
			'class'=>'text-info propose-mitigasi',
			'icon' =>'icon-file-spreadsheet',
			'attr' => ['target'=>'_self','data-id'=>$row['id']],
			'type' => 'span',
		];
		
		if (array_key_exists($row['id'],$this->kpi)){
			$button['review']=[
				'label'=>'Review KPI & KRI',
				'id'=>'btn_review_one',
				'class'=>'text-danger review-kpi',
				'icon' =>'icon-list',
				'url' => base_url(_MODULE_NAME_.'/review-kpi/'),
				'type' => 'span',
				'attr' => ['data-id'=>$row['id']],
			];
		}

		$button['propose']=[
			'label'=>'View Progress',
			'id'=>'btn_propose_one',
			'class'=>'text-success',
			'icon' =>'icon-file-spreadsheet',
			'url' => base_url(_MODULE_NAME_.'/progress/'),
			'attr' => ' target="_self" '
		];
		
		return $button;
	}

}