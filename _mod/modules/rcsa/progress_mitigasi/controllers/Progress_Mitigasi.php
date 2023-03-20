<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Progress_Mitigasi extends MY_Controller {
	protected $approval=[];
	protected $approval_minggu=[];
	var $super_user=0;

	public function __construct()
	{
		parent::__construct();
		$this->super_user = $this->_data_user_['is_admin'];

		$this->load->language('risk_context');
		$this->load->language('monitoring_mitigasi');
	}

	function init($action='list'){
		$this->type_ass_no=$this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'ass-type')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$this->period=$this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'period')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$this->alat=$this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'metode-alat')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$this->stakeholder=$this->crud->combo_select(['id', 'officer_name'])->combo_where('active', 1)->combo_tbl(_TBL_VIEW_OFFICER)->get_combo()->result_combo();
		$this->term=$this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'term')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$this->cboDept=$this->get_combo_parent_dept();
		$this->cboStack=$this->get_combo_parent_dept(false);


		$this->set_Tbl_Master(_TBL_VIEW_RCSA);

		$this->addField(['field'=>'id', 'type'=>'int', 'show'=>false, 'size'=>4]);
		$this->addField(['field'=>'type_ass_id', 'input'=>'combo', 'required'=>true, 'search'=>true, 'values'=>$this->type_ass_no, 'size'=>50]);
		$this->addField(['field'=>'owner_id', 'title'=>'Department', 'type'=>'int', 'required'=>true,'input'=>'combo', 'search'=>true, 'values'=>$this->cboDept]);
		$this->addField(['field'=>'sasaran_dept', 'input'=>'multitext', 'search'=>true, 'size'=>500]);
		$this->addField(['field'=>'ruang_lingkup', 'input'=>'multitext', 'search'=>true, 'size'=>500]);
		$this->addField(['field'=>'stakeholder_id', 'title'=>'Stakeholder', 'type'=>'string','input'=>'combo', 'search'=>true, 'values'=>$this->cboStack, 'multiselect'=>true]);

		$this->addField(['field'=>'alat_metode_id', 'title'=>'Alat & Metode', 'type'=>'int','input'=>'combo', 'search'=>true, 'values'=>$this->alat]);
		$this->addField(['field'=>'period_id', 'title'=>'Periode', 'type'=>'int', 'required'=>true,'input'=>'combo', 'search'=>true, 'values'=>$this->period]);
		$this->addField(['field'=>'term_id', 'title'=>'Term', 'type'=>'int', 'required'=>true,'input'=>'combo', 'search'=>true, 'values'=>[]]);
		$this->addField(['field'=>'minggu_id', 'title'=>'Bulan', 'type'=>'int', 'required'=>true,'input'=>'combo', 'search'=>true, 'values'=>[]]);

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
		$this->addField(['field'=>'tgl_selesai_term', 'show'=>false]);
		$this->addField(['field'=>'tgl_akhir_mitigasi', 'show'=>false]);

		$this->addField(['field'=>'term', 'show'=>false]);
		$this->addField(['field'=>'kode_dept', 'show'=>false]);
		$this->addField(['field'=>'owner_name', 'show'=>false]);

		$this->set_Field_Primary($this->tbl_master, 'id', true);

		$this->set_Sort_Table($this->tbl_master,'created_at', 'desc');
		// $this->set_Where_Table(['field'=>'status_id_mitigasi', 'value'=>1, 'op'=>'>=']);
		// $this->set_Where_Table(['field'=>'status_final_mitigasi', 'value'=>1, 'op'=>'>=']);
		$this->set_Where_Table(['field'=>'status_final', 'value'=>1, 'op'=>'>=']);

		$this->set_Table_List($this->tbl_master,'id', '<input type="checkbox" class="form-check-input pointer" name="chk_list_parent" id="chk_list_parent"  style="padding:0;margin:0;">','0%','left','no-sort');

		$this->set_Table_List($this->tbl_master,'owner_name');
		$this->set_Table_List($this->tbl_master,'kode_dept','');
		$this->set_Table_List($this->tbl_master,'stakeholder_id', '', 15);
		$this->set_Table_List($this->tbl_master,'type_ass_id', 'Tipe Ass');
		$this->set_Table_List($this->tbl_master,'period_id', 'Tahun');
		$this->set_Table_List($this->tbl_master,'term_id', 'Periode');
		$this->set_Table_List($this->tbl_master,'status_id_mitigasi','Status Mitigasi');
		$this->set_Table_List($this->tbl_master,'tgl_propose_mitigasi');
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
			$content_title = 'Daftar Mitigasi';
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
	
        $arr_approval = $this->db->where('pid', 249)->order_by('urut')->get(_TBL_VIEW_APPROVAL)->result_array();
		$this->approval=[];
		foreach($arr_approval as $row){
			$this->approval[$row['urut']] = $row;
		}
		if (count($arrId)>0) {
			$arr_approval = $this->db->where_in('rcsa_id', $arrId)->order_by('minggu_id')->get(_TBL_RCSA_APPROVAL_MITIGASI)->result_array();
		} else {
			$arr_approval = $this->db->where('rcsa_id', 0)->order_by('minggu_id')->get(_TBL_RCSA_APPROVAL_MITIGASI)->result_array();
		}
		

		$this->approval_minggu=[];
		foreach($arr_approval as $row){
			$this->approval_minggu[$row['rcsa_id']][$row['minggu_id']] = $row;
		}

		if (count($arrId)>0) {
			$rows = $this->db->select('rcsa_id, count(rcsa_id) as jml')->where_in('rcsa_id', $arrId)->order_by('rcsa_id')->group_by('rcsa_id')->get(_TBL_VIEW_RCSA_KPI)->result_array();
		}else{
			$rows = $this->db->select('rcsa_id, count(rcsa_id) as jml')->where('rcsa_id', 0)->order_by('rcsa_id')->group_by('rcsa_id')->get(_TBL_VIEW_RCSA_KPI)->result_array();
		}

		$this->kpi=[];
		foreach($rows as $row){
			$this->kpi[$row['rcsa_id']] = $row['jml'];
		}
	}

	function listBox_TERM_ID($field, $rows, $value){
		$cbominggu=$this->data->get_data_minggu($value);
		$minggu = ($rows['minggu_id'])?$cbominggu[$rows['minggu_id']]:'';
		$a = $this->term[$value].' - '.$minggu;
		return $a;
	}

	function listBox_id($field, $rows, $value){
		$check = $this->data->checklist();
		// $select = (in_array($rows['id'], $check))?'checked':'';
		$a='<div class="text-center"  style="padding:0px 20px 20px 0px;"><input type="checkbox" class="form-check-input pointer text-center" name="chk_list[]" style="padding:0;margin:0;" value="'.$rows['id'].'"/></div>';
		return $a;
	}

	function listBox_STATUS_ID_MITIGASI($field, $rows, $value){
        $revisi=intval($rows['status_revisi_mitigasi']);
		$urut=intval($rows['status_id_mitigasi']);
		$final=intval($rows['status_final_mitigasi']);
		// $hasil = '<a href="'.base_url(_MODULE_NAME_.'/propose/'.$rows['id']).'"a class="propose btn  pointer" style="width:100% !important;padding:5px;background-color:'.$this->_preference_['warna_propose'].';color:#ffffff;" data-id="' . $rows['id'].'"> '._l('msg_notif_propose').' </a>';
		$hasil = '<a href="#"a class="propose btn disabled" style="width:100% !important;padding:5px;background-color:'.$this->_preference_['warna_propose'].';color:#ffffff;" data-id="' . $rows['id'].'"> '._l('msg_notif_propose').' </a>';

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
			$hasil = '<div  class="label text-center" style="background-color:'.$this->approval[$urut]['warna'].';color:#ffffff;width:100%;padding:10px 5px; display:block;">'._l('msg_notif_need_approved').' <br/>'.$this->approval[$urut]['model'].'</div><br/>';
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

	function inputBox_MINGGU_ID($mode, $field, $rows, $value){
		if ($mode=='edit'){
			$id=0;
			if (isset($rows['term_id']))
				$id=$rows['term_id'];

			$field['values']=$this->data->get_data_minggu($id);
			
			// $field['values'] = $this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'minggu')->combo_where('pid', $id)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		}
		// dumps($field['values']);
		// die();
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
		$cbominggu = $this->data->get_data_minggu($data['parent']['term_id']);
		$minggu = ($data['parent']['minggu_id']) ? $cbominggu[$data['parent']['minggu_id']] : '';

		$data['parent']['bulan'] = $this->term[$data['parent']['term_id']] . ' - ' . $minggu;
		$data['info_parent']=$this->load->view('info-parent',$data, true);
		
		$rows=$this->db->where('rcsa_id', $id)->get(_TBL_VIEW_RCSA_MITIGASI_DETAIL)->result_array();
		$data['detail']=$rows;
		$hasil=$this->load->view('mitigasi',$data, true);
		$configuration = [
			'show_title_header' => false,
			'show_action_button' => false,
			'content_title' => 'Progres Aktivitas Mitigasi'
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
			$trg=1;
			$disabled='';
			if ($am['batas_waktu_detail']<date('Y-m-d')){
				$trg=100;
				// $disabled=' disabled="disabled" ';
			}
			$data['minggu']=$this->crud->combo_select(['id', 'concat(param_string, " ", YEAR(param_date)) as minggu'])->combo_where('kelompok', 'minggu')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
			
			
			// $curMing = $this->_data_user_['term']['period_id'];
			
			if ($dp) {
				$cekbul = $this->db->where('id', $dp['minggu_id'])->get(_TBL_COMBO)->row_array();

				// $minggupil=$this->crud->combo_select(['id', 'concat(param_string, \' ( \', param_date, \' s.d \', param_date_after, \' ) \') as minggu'])->combo_where('kelompok', 'minggu')->combo_where('active', 1)->combo_where('pid',$cekbul['pid'])->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();

			} else {
				$cekbul['pid'] = _TAHUN_ID_;
				// $minggupil=$this->crud->combo_select(['id', 'concat(param_string, \' ( \', param_date, \' s.d \', param_date_after, \' ) \') as minggu'])->combo_where('kelompok', 'minggu')->combo_where('active', 1)->combo_where('pid',_TAHUN_ID_)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
			}
			
			$minggupil[""] = '--select Bulan--';
			$minggupil = $this->data->get_data_minggu($data['parent']['term_id']);
			// Dumps($minggupil);
			// die();
			$periodpil = $this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'period')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
			$periodpil[""] = '--select Periode--';
	
			
			$minggu = form_dropdown('tahun_pil', $periodpil, $cekbul['pid'], 'class="form-control select" style="width:100%;"  id="tahun" disabled');
			$minggu .= form_hidden('tahun', $cekbul['pid']);
			$minggu .= form_dropdown('minggu', $minggupil, ($dp)?$dp['minggu_id']:_MINGGU_ID_, 'class="form-control select" style="width:100%;"  id="minggu"');
			$minggu .= '<script>
				$(".select").select2({
					allowClear: false
				});
				$("#tahun").change(function () {
					var parent = $(this).parent();
					var nilai = $(this).val();
					var data = {
						"id": nilai
					};
					var target_combo = $("#minggu");
					var url = "ajax/get-minggu-by-tahun";
					_ajax_("post", parent, data, target_combo, url);
				})
			</script>';


			$aktual= '<div class="input-group" style="width:19% !important;"> <button type="button" onclick="this.parentNode.querySelector(\'[type=number]\').stepDown();"> - </button>';
			$aktual .= form_input(['type'=>'number','name'=>'aktual'],($dp)?$dp['aktual']:'1'," class='form-control touchspin-postfix text-center' max='100' min='1' step='1' id='aktual' ");
			$aktual .= '<button type="button" onclick="this.parentNode.querySelector(\'[type=number]\').stepUp();"> + </button> <div class="form-control-feedback text-primary form-control-feedback-lg pointer" style="right:-25%;"> % </div></div>';

			$target= '<div class="input-group" style="width:19% !important;"> <button type="button" onclick="this.parentNode.querySelector(\'[type=number]\').stepDown();"> - </button>';
			$target .= form_input(['type'=>'number','name'=>'target'],($dp)?$dp['target']:$trg," '.$disabled.' class='form-control touchspin-postfix text-center' max='100' min='1' step='1' id='target' ");
			$target .= '<button type="button" onclick="this.parentNode.querySelector(\'[type=number]\').stepUp();"> + </button> <div class="form-control-feedback text-primary form-control-feedback-lg pointer" style="right:-25%;"> % </div></div>';

			$data['progres'][] = ['title'=>"Bulan Progress", 'mandatori'=>false,'isi'=>$minggu];
			$data['progres'][] = ['title'=>_l('fld_target'),'help'=>_h('help_target'), 'mandatori'=>true,'isi'=>$target];
			$data['progres'][] = ['title'=>_l('fld_aktual'),'help'=>_h('help_aktual'), 'mandatori'=>true,'isi'=>$aktual];
			$data['progres'][] = ['title'=>_l('fld_uraian'),'help_popup'=>false,'help'=>_h('help_uraian','',true, false), 'mandatori'=>true,'isi'=>form_textarea('uraian', ($dp)?$dp['uraian']:'',"required id='uraian' maxlength='500' size='500' class='form-control' style='overflow: hidden; width: 100% !important; height: 100px;' onblur='_maxLength(this , \"id_sisa_1\")' onkeyup='_maxLength(this , \"id_sisa_1\")' data-role='tagsinput'", true, ['size'=>1000, 'isi'=>0,'no'=>1])];
			$data['progres'][] = ['title'=>_l('fld_kendala'),'help'=>_h('help_kendala'),'isi'=>form_textarea('kendala', ($dp)?$dp['kendala']:'',"required id='kendala' maxlength='500' size='500' class='form-control' style='overflow: hidden; width: 100% !important; height: 100px;' onblur='_maxLength(this , \"id_sisa_2\")' onkeyup='_maxLength(this , \"id_sisa_2\")' data-role='tagsinput'", true, ['size'=>1000, 'isi'=>0,'no'=>2])];
			$data['progres'][] = ['title'=>_l('fld_tindak_lanjut'),'help'=>_h('help_tindak_lanjut'),'isi'=>form_textarea('tindak_lanjut', ($dp)?$dp['tindak_lanjut']:''," id='tindak_lanjut' maxlength='500' size='500' class='form-control' style='overflow: hidden; width: 100% !important; height: 100px;' onblur='_maxLength(this , \"id_sisa_3\")' onkeyup='_maxLength(this , \"id_sisa_3\")' data-role='tagsinput'", true, ['size'=>1000, 'isi'=>0,'no'=>3])];
			$data['progres'][] = ['title'=>_l('fld_due_date'),'help'=>_h('help_due_date'),'isi'=>form_input('batas_waktu_tindak_lanjut', ($dp)?$dp['batas_waktu_tindak_lanjut']:'', 'class="form-control pickadate" id="batas_waktu_tindak_lanjut" style="width:100%;"')];

			$data['progres'][] = ['title'=>_l('fld_keterangan'),'help'=>_h('help_keterangan'),'isi'=>form_textarea('keterangan', ($dp)?$dp['keterangan']:''," id='keterangan' maxlength='500' size='500' class='form-control' style='overflow: hidden; width: 100% !important; height: 100px;' onblur='_maxLength(this , \"id_sisa_4\")' onkeyup='_maxLength(this , \"id_sisa_4\")' data-role='tagsinput'", true, ['size'=>1000, 'isi'=>0,'no'=>4])];
			$data['progres'][] = ['title'=>_l('fld_lampiran'),'help'=>_h('help_lampiran'),'isi'=>form_upload('lampiran')];
			$data['progres'][] = ['title'=>'','help'=>'','isi'=>form_hidden(['aktifitas_mitigasi_id'=>$id, 'id'=>$id_edit])];

			$data['info_1'][] = ['title'=>_l('fld_risiko_dept'),'isi'=>$rcsa_detail['risiko_dept']];
			$data['info_1'][] = ['title'=>_l('fld_risiko_inherent'),'isi'=>$rcsa_detail['level_color']];
			$data['info_1'][] = ['title'=>_l('fld_efek_kontrol'),'isi'=>$rcsa_detail['efek_kontrol_text']];
			$data['info_1'][] = ['title'=>_l('fld_nama_control'),'isi'=>$rcsa_detail['nama_kontrol']];
			$data['info_1'][] = ['title'=>_l('fld_level_risiko_residual'),'isi'=>$rcsa_detail['level_color_residual']];
			$data['info_1'][] = ['title'=>_l('fld_treatment'),'isi'=>$rcsa_detail['treatment']];

			$data['info_2'][] = ['title'=>_l('fld_mitigasi'),'isi'=>$mit['mitigasi']];
			$data['info_2'][] = ['title'=>_l('fld_biaya'),'isi'=>number_format($mit['biaya'])];
			$data['info_2'][] = ['title'=>_l('fld_pic'),'isi'=>$mit['penanggung_jawab']];
			$data['info_2'][] = ['title'=>_l('fld_koordinator'),'isi'=>$mit['koordinator']];
			$data['info_2'][] = ['title'=>_l('fld_due_date'),'isi'=>date('d-M-Y',strtotime($mit['batas_waktu']))];

			$data['informasi'] = $this->load->view('informasi', $data, true);
			$data['list_progres'] = $this->load->view('list-progres', $data, true);
			$data['update'] = $this->load->view('progres', $data, true);
			$data['id']=$am['rcsa_id'];
			$hasil=$this->load->view('monitoring',$data, true);
			
			$configuration = [
				'show_title_header' => false,
				'show_action_button' => false,
				'content_title' => 'Update Progres Aktivitas Mitigasi'
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
		header('Content-type: application/json');
		echo json_encode(['combo'=>$hasil['update']]);
	}

	function simpan_progres(){
		$post = $this->input->post();
		$id_edit = $this->data->simpan_progres($post);

		$id=intval($post['aktifitas_mitigasi_id']);
		$hasil=$this->update_progres(0, $id);
		$result['update'] = $hasil['update'];
		$result['list_progres'] = $hasil['list_progres'];
		header('Content-type: application/json');
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
		header('Content-type: application/json');
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

	function propose(){
		$id=intval($this->uri->segment(3));
		$data['note_propose']='Catatan :<br/>'.form_textarea('note_propose', ''," id='note_propose' placeholder = 'silahkan masukkan catatan anda disini' maxlength='500' size='500' class='form-control' style='overflow: hidden; width: 100% !important; height: 200px;' onblur='_maxLength(this , \"id_sisa_1\")' onkeyup='_maxLength(this , \"id_sisa_1\")' data-role='tagsinput'", true, ['size'=>500, 'isi'=>0,'no'=>1]);

		$data['parent']=$this->db->where('id', $id)->get(_TBL_VIEW_RCSA)->row_array();

		$cbominggu = $this->data->get_data_minggu($data['parent']['term_id']);
		$minggu = ($data['parent']['minggu_id']) ? $cbominggu[$data['parent']['minggu_id']] : '';

		$data['parent']['bulan'] = $this->term[$data['parent']['term_id']] . ' - ' . $minggu;
		$data['info_parent']=$this->load->view('info-parent',$data, true);

		$alur=$this->data_alur(['owner_no'=>$data['parent']['owner_id']]);
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

		$x = $this->session->userdata('periode');
		
		$tgl1=date('Y-m-d');
		$tgl2=date('Y-m-d');
		// if ($x){
		// 	$tgl1=$x['tgl_awal'];
		// 	$tgl2=$x['tgl_akhir'];
		// }

		$data['lanjut']=$data_notif;
		$data['poin_start']=$data_notif_asli;
		$data['id']=$id;
		$x['notif'] = json_encode($data_notif);
        $x['ket'] = $ket;
        $x['id'] = $id;
        $x['alur'] = json_encode($alur);
		$data['hidden']=$x;
		$data['period']=$this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'period')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		// Doi::dump($data['parent']['period_id']);
		// Doi::dump($data['period']);
		// die();
		$data['term']=$this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'term')->combo_where('pid', $data['parent']['period_id'])->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		// $data['minggu']=$this->crud->combo_select(['id', 'concat(param_string, \' ( \', param_date, \' s.d \', param_date_after, \' ) \') as minggu'])->combo_where('kelompok', 'minggu')->combo_where('param_date',null)
		// ->combo_where('param_date<=', $tgl2)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$data['minggu'] = $this->data->get_data_minggu($data['parent']['term_id']);
		// $data['minggu']=$this->crud->combo_select(['id', 'concat(param_string,\' minggu ke - \',data, \' ( \', param_date, \' s.d \', param_date_after, \' ) \') as minggu'])->combo_where('kelompok', 'minggu')->combo_where('param_date>=', $tgl1);
		
		
		$hasil=$this->load->view('propose', $data, true);

		$configuration = [
			'show_title_header' => false,
			'show_action_button' => false,
			'content_title' => 'Propose Progres Mitigasi'
		];

		// $this->crud->crud_table(_TBL_RCSA_KPI);
		// $this->crud->crud_type('delete');
		// $this->crud->crud_where(['field' => 'rcsa_id', 'value' => $id]);
		// $this->crud->crud_where(['field' => 'sts_add', 'value' => 0]);
		// $this->crud->process_crud();

		$this->default_display(['content'=>$hasil, 'configuration'=>$configuration]);

		// echo json_encode($propose);
	}

	function proses_propose_mitigasi(){
		$post=$this->input->post();
		$alur=json_decode($post['alur'], true);
        $notif=json_decode($post['notif'], true);
	
        $sts_final=0;
        if (count($alur)==$notif['urut']){
            $sts_final=1;
        }
		$alur[$notif['urut']-1]['tanggal']=date('Y-m-d H:i:s');

		$this->crud->crud_table(_TBL_RCSA);
		$this->crud->crud_type('edit');
		$this->crud->crud_field('status_revisi_mitigasi', 0, 'int');
		$this->crud->crud_field('status_id_mitigasi', $notif['urut'], 'int');
		$this->crud->crud_field('minggu_id_mitigasi', $post['minggu'], 'int');
		$this->crud->crud_field('status_final_mitigasi', 0, 'int');
		$this->crud->crud_field('note_propose_mitigasi', $post['note_propose']);
		$this->crud->crud_field('param_approval_mitigasi', json_encode($alur));
		$this->crud->crud_field('tgl_propose_mitigasi', date('Y-m-d H:i:s'), 'datetime');
		$this->crud->crud_where(['field' => 'id', 'value' => $post['id']]);
		$this->crud->process_crud();

		$this->crud->crud_table(_TBL_LOG_APPROVAL);
		$this->crud->crud_type('add');
		$this->crud->crud_field('tipe_log', 2, 'int');
		$this->crud->crud_field('rcsa_id', $post['id'], 'int');
		$this->crud->crud_field('keterangan', 'Propose ke '.$notif['level']);
		$this->crud->crud_field('note', $post['note_propose']);
		$this->crud->crud_field('tanggal', date('Y-m-d H:i:s'), 'datetime');
		$this->crud->crud_field('user_id', $this->ion_auth->get_user_id());
		$this->crud->crud_field('penerima_id', $notif['staft_no']);
		$this->crud->process_crud();

		$file_name='';
		$this->load->library('image');
		if (array_key_exists('attr', $_FILES)){
			if (!empty($_FILES['attr']['name'])) {
				$this->image->set_Param('nm_file', 'attr');
				$this->image->set_Param('file_name', $_FILES['attr']['name']);
				$this->image->set_Param('path',file_path_relative('rcsa'));
				$this->image->set_Param('thumb',false);
				$this->image->set_Param('type','*');
				$this->image->set_Param('size', 10000000);
				$this->image->set_Param('nm_random', false);
				$this->image->set_Param('multi', false);
				$this->image->upload();

				$file_name='rcsa/'.$this->image->result('file_name');

			}
		}

		$rows=$this->db->where('rcsa_id', $post['id'])->where('minggu_id', $post['minggu'])->where('term_id', $post['term'])->get(_TBL_RCSA_APPROVAL_MITIGASI)->row_array();
		$status_lengkap=0;
		if ($post['minggu']==_MINGGU_ID_ && empty($file_name)){
			$status_lengkap=1;
		}elseif ($post['minggu']==_MINGGU_ID_ && !empty($file_name)){
			$status_lengkap=2;
		}

		if (!$rows){
			$this->crud->crud_table(_TBL_RCSA_APPROVAL_MITIGASI);
			$this->crud->crud_type('add');
			$this->crud->crud_field('rcsa_id', $post['id'], 'int');
			$this->crud->crud_field('note', $post['note_propose']);
			$this->crud->crud_field('minggu_id', $post['minggu'], 'int');
			$this->crud->crud_field('minggu_aktif_id', _MINGGU_ID_, 'int');
			$this->crud->crud_field('term_id', $post['term'], 'int');
			$this->crud->crud_field('tgl_propose', date('Y-m-d H:i:s'), 'datetime');
			$this->crud->crud_field('param_approval', json_encode($alur));
			$this->crud->crud_field('file_att', $file_name);
			$this->crud->crud_field('status_lengkap', $status_lengkap);
			$this->crud->crud_field('officer_id', $this->ion_auth->get_user_id());
			$this->crud->crud_field('created_by', $this->ion_auth->get_user_name());
			$this->crud->process_crud();
		}else{
			$this->crud->crud_table(_TBL_RCSA_APPROVAL_MITIGASI);
			$this->crud->crud_type('edit');
			$this->crud->crud_field('minggu_id', $post['minggu'], 'int');
			$this->crud->crud_field('minggu_aktif_id', _MINGGU_ID_, 'int');
			$this->crud->crud_field('note', $post['note_propose']);
			$this->crud->crud_field('term_id', $post['term'], 'int');
			$this->crud->crud_field('tgl_propose', date('Y-m-d H:i:s'), 'datetime');
			$this->crud->crud_field('param_approval', json_encode($alur));
			$this->crud->crud_field('file_att', $file_name);
			$this->crud->crud_field('status_lengkap', $status_lengkap);
			$this->crud->crud_field('officer_id', $this->ion_auth->get_user_id());
			$this->crud->crud_field('updated_by', $this->ion_auth->get_user_name());
			$this->crud->crud_where(['field' => 'id', 'value' => $rows['id']]);
			$this->crud->process_crud();
		}

		$this->crud->crud_table(_TBL_RCSA_KPI);
		$this->crud->crud_type('edit');
		$this->crud->crud_field('sts_add', 1, 'int');
		$this->crud->crud_field('minggu_id', $post['minggu'], 'int');
		$this->crud->crud_where(['field' => 'rcsa_id', 'value' => $post['id']]);
		$this->crud->crud_where(['field' => 'sts_add', 'value' => 0]);
		$this->crud->process_crud();

		if ($notif['email']) {
			$datasOutbox = [
				'recipient' => [$notif['email']],
			];
			$content_replace = [
				'[[konteks]]' => 'Progress Mitigasi',
				'[[redir]]' => 2,
				'[[id]]' => $post['id'],
				'[[notif]]' => $notif['staft'],
				'[[sender]]' => $this->session->userdata('data_user')['real_name'],
				'[[link]]' => base_url() . "approval-mitigasi",
				'[[footer]]' => $this->session->userdata('preference-0')['nama_kantor']

			];

			$this->load->library('outbox');
			$this->outbox->setTemplate('NOTIF01');
			$this->outbox->setParams($content_replace);
			$this->outbox->setDatas($datasOutbox);
			$this->outbox->send();
		}
		// echo json_encode(['data'=>true]);
		header('location:'.base_url(_MODULE_NAME_));
	}

	function kri_add($post=[]){
		if (!$post){
			$post=$this->input->post();
			$post['id']=0;
		}

		$data['list']=$this->db->where('kpi_id', $post['kpi_id'])->get(_TBL_RCSA_KPI)->result_array();

		$data['parent']=$post['kpi_id'];
		$data['entri'] = $this->entri_kri(['edit_id'=>$post['id'], 'kpi_id'=>$post['kpi_id'], 'rcsa_id'=>$post['rcsa_id'], 'minggu'=>$post['minggu']], false);
		$result['combo'] = $this->load->view('kri', $data, true);
		header('Content-type: application/json');
		echo json_encode($result);
	}

	function kri_edit($post=[]){
		$id=$this->input->post('edit_id');
		$post=$this->input->post();
		$data['parent']=$post['kpi_id'];
		$data['entri'] = $this->entri_kri(['edit_id'=>$id, 'kpi_id'=>$post['kpi_id'], 'rcsa_id'=>$post['rcsa_id'], 'minggu'=>$post['minggu']]);
		$result['combo'] = $this->load->view('input-kri', $data, true);
		header('Content-type: application/json');
		echo json_encode($result);
	}

	function simpan_kri(){
		$post=$this->input->post();
		$this->data->post=$post;
		$id=$this->data->simpan_kri();
		// $data['kpi_id']=$post['kpi_id'];
		// $data['rcsa_id']=$post['rcsa_id'];
		// $data['minggu']=$post['minggu'];
		// $post['id']=0;

		$data['minggu']=$post['minggu'];
		$data['id']=$post['rcsa_id'];
		$this->list_kpi($data);
		// $this->kri_add($post);
	}

	function entri_kri($post=[], $input=true){
		$data['param']=$post;
		$this->cboSatuan=$this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'satuan')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$mit=[];
		if (intval($post['edit_id'])>0){
			$mit=$this->db->where('id', $post['edit_id'])->get(_TBL_RCSA_KPI)->row_array();
		}
	
		$disabled = '';

		if ($input) {
			$data['like'][] = ['title'=>_l('fld_kri'),'help'=>_h('help_kri'),'isi'=>form_input('title', ($mit)?$mit['title']:'', 'class="form-control" '.$disabled.' id="title"')];
			$data['like'][] = ['title'=>_l('fld_satuan'),'help'=>_h('help_satuan'),'isi'=>form_dropdown('satuan_id', $this->cboSatuan, ($mit)?$mit['satuan_id']:'', 'class="form-control select" '.$disabled.' id="satuan_id"')];

			$data['like'][] = ['title'=>'<a style="height:1.4rem;width:1.4rem;background-color:#2c5b29" class="btn bg-successx-400 rounded-round btn-icon btn-sm"><span class="letter-icon"></span></a>','help'=>_h('help_pencapaian'),'isi'=>form_input('p_1', ($mit)?$mit['p_1']:'', 'class="form-control" '.$disabled.' id="p_1" placeholder="'._l('fld_pencapaian').'" style="width:50%"').'&nbsp;&nbsp;&nbsp;'.form_input('s_1_min', ($mit)?$mit['s_1_min']:'', 'class="form-control text-center" '.$disabled.' id="s_1_min" placeholder="'._l('fld_min_satuan').'" style="width:10%"').'&nbsp;&nbsp;&nbsp;<span class="input-group-text"> - </span>&nbsp;&nbsp;&nbsp;'.form_input('s_1_max', ($mit)?$mit['s_1_max']:'', 'class="form-control text-center" '.$disabled.' id="s_1_max" placeholder="'._l('fld_mak_satuan').'" style="width:10%"').' <span class="input-group-text"> Satuan </span> '];

			$data['like'][] = ['title'=>'<a style="height:1.4rem;width:1.4rem;background-color:#50ca4e;" class="btn bg-orangex-400 rounded-round btn-icon btn-sm"><span class="letter-icon"></span></a>','help'=>_h('help_pencapaian_4'),'isi'=>form_input('p_4', ($mit)?$mit['p_4']:'', 'class="form-control" '.$disabled.' id="p_4" placeholder="'._l('fld_pencapaian').'" style="width:50%"').'&nbsp;&nbsp;&nbsp;'.form_input('s_4_min', ($mit)?$mit['s_4_min']:'', 'class="form-control text-center" '.$disabled.' id="s_4_min" placeholder="'._l('fld_min_satuan').'" style="width:10%"').'&nbsp;&nbsp;&nbsp;<span class="input-group-text"> - </span>&nbsp;&nbsp;&nbsp;'.form_input('s_4_max', ($mit)?$mit['s_4_max']:'', 'class="form-control text-center" '.$disabled.' id="s_4_max" placeholder="'._l('fld_mak_satuan').'" style="width:10%"').' <span class="input-group-text"> Satuan </span> '];

			$data['like'][] = ['title'=>'<a style="height:1.4rem;width:1.4rem;background-color:#edfd17;" class="btn bg-dangerx-400 rounded-round btn-icon btn-sm"><span class="letter-icon"></span></a>','help'=>_h('help_pencapaian_2'),'isi'=>form_input('p_2', ($mit)?$mit['p_2']:'', 'class="form-control" '.$disabled.' id="p_2" placeholder="'._l('fld_pencapaian').'" style="width:50%"').'&nbsp;&nbsp;&nbsp;'.form_input('s_2_min', ($mit)?$mit['s_2_min']:'', 'class="form-control text-center" '.$disabled.' id="s_2_min" placeholder="'._l('fld_min_satuan').'" style="width:10%"').'&nbsp;&nbsp;&nbsp;<span class="input-group-text"> - </span>&nbsp;&nbsp;&nbsp;'.form_input('s_2_max', ($mit)?$mit['s_2_max']:'', 'class="form-control text-center" '.$disabled.' id="s_2_max" placeholder="'._l('fld_mak_satuan').'" style="width:10%"').' <span class="input-group-text"> Satuan </span> '];

			$data['like'][] = ['title'=>'<a style="height:1.4rem;width:1.4rem;background-color:#f0ca0f;" class="btn bg-dangerx-400 rounded-round btn-icon btn-sm"><span class="letter-icon"></span></a>','help'=>_h('help_pencapaian_5'),'isi'=>form_input('p_5', ($mit)?$mit['p_5']:'', 'class="form-control" '.$disabled.' id="p_5" placeholder="'._l('fld_pencapaian').'" style="width:50%"').'&nbsp;&nbsp;&nbsp;'.form_input('s_5_min', ($mit)?$mit['s_5_min']:'', 'class="form-control text-center" '.$disabled.' id="s_5_min" placeholder="'._l('fld_min_satuan').'" style="width:10%"').'&nbsp;&nbsp;&nbsp;<span class="input-group-text"> - </span>&nbsp;&nbsp;&nbsp;'.form_input('s_5_max', ($mit)?$mit['s_5_max']:'', 'class="form-control text-center" '.$disabled.' id="s_5_max" placeholder="'._l('fld_mak_satuan').'" style="width:10%"').' <span class="input-group-text"> Satuan </span> '];

			$data['like'][] = ['title'=>'<a style="height:1.4rem;width:1.4rem;background-color:#e70808" class="btn bg-dangerx-400 rounded-round btn-icon btn-sm"><span class="letter-icon"></span></a>','help'=>_h('help_pencapaian_3'),'isi'=>form_input('p_3', ($mit)?$mit['p_3']:'', 'class="form-control" '.$disabled.' id="p_3" placeholder="'._l('fld_pencapaian').'" style="width:50%"').'&nbsp;&nbsp;&nbsp;'.form_input('s_3_min', ($mit)?$mit['s_3_min']:'', 'class="form-control text-center" '.$disabled.' id="s_3_min" placeholder="'._l('fld_min_satuan').'" style="width:10%"').'&nbsp;&nbsp;&nbsp;<span class="input-group-text"> - </span>&nbsp;&nbsp;&nbsp;'.form_input('s_3_max', ($mit)?$mit['s_3_max']:'', 'class="form-control text-center" '.$disabled.' id="s_3_max" placeholder="'._l('fld_mak_satuan').'" style="width:10%"').' <span class="input-group-text"> Satuan </span> '];

			$data['like'][] = ['title'=>_l('fld_score'),'help'=>_h('help_score'),'isi'=>'<div class="input-group" style="width:15%;text-align:center;">'.form_input('score', ($mit)?$mit['score']:'', 'class="form-control" id="score" placeholder="'._l('fld_score').'"').'</div>'];
		}else{
			$data['like']= [];
		}
		$hasil = $this->load->view('input-kri', $data, true);

		return $hasil;
	}

	function list_kpi($post=[]){
		if (!$post){
			$post=$this->input->post();
		}
		
		$this->db->select('a.*, COUNT(b.kpi_id) AS kri_count', false);
		$this->db->join(_TBL_RCSA_KPI.' as b', 'a.id = b.kpi_id', 'left', false);
		$this->db->group_by("a.id");
		$data['list']=$this->db->where('a.minggu_id', $post['minggu'])->where('a.rcsa_id', intval($post['id']))->or_group_start()->where('a.sts_add',0)->where('a.rcsa_id', intval($post['id']))->group_end()->where('a.kpi_id',0)->get(_TBL_RCSA_KPI." as a")->result_array();

		$kpi=$this->db
			->select('id_kpi, kpi_detail, id, kode_risk')
			// ->select('id_kpi, kpi_detail, GROUP_CONCAT(id) as id')
			->where('rcsa_id', intval($post['id']))->where('kpi_detail !=',null)->order_by('kode_dept')
			// ->group_by('id_kpi')
			->order_by('kode_aktifitas')
		// ->get_compiled_select(_TBL_VIEW_RCSA_DETAIL);
			->get(_TBL_VIEW_RCSA_DETAIL)->result_array();
		
		if(count($data['list'])==0){
			$this->db->where('rcsa_id', $post['id']);
			$this->db->where('minggu_id', '(select min(minggu_id) from '._TBL_RCSA_KPI.' where rcsa_id ='.$post['id'].')', false);
			// $cekKpi = $this->db->get_compiled_select(_TBL_RCSA_KPI);
			$cekKpi = $this->db->get(_TBL_RCSA_KPI);
			
			if(count($cekKpi->result_array())>0){
				foreach ($cekKpi->result_array() as $key => $value) {
					$dataKpi = [
						'minggu' => $post['minggu'],
						'rcsa_id' => $post['id'],
						'edit_id' => 0,
						'title' => $value['title'],
						'satuan_id' => $value['satuan_id'],
						'p_1' => $value['p_1'],
						's_1_min' => $value['s_1_min'],
						's_1_max' => $value['s_1_max'],
						'p_4' => $value['p_4'],
						's_4_min' => $value['s_4_min'],
						's_4_max' => $value['s_4_max'],
						'p_2' => $value['p_2'],
						's_2_min' => $value['s_2_min'],
						's_2_max' => $value['s_2_max'],
						'p_5' => $value['p_5'],
						's_5_min' => $value['s_5_min'],
						's_5_max' => $value['s_5_max'],
						'p_3' => $value['p_3'],
						's_3_min' => $value['s_3_min'],
						's_3_max' => $value['s_3_max'],
						'score' => $value['score'],
					];
					$this->data->post=$dataKpi;
					$this->data->simpan_kpi();
					$idKpi = $this->crud->last_id();

					$this->db->where('kpi_id', $value['id']);
					$cekKri = $this->db->get(_TBL_RCSA_KPI);
					foreach ($cekKri->result_array() as $k => $v) {
						$dataKri = [
							'minggu' => 0,
							'rcsa_id' => 0,
							'edit_id' => 0,
							'kpi_id' => $idKpi,
							'title' => $v['title'],
							'satuan_id' => $v['satuan_id'],
							'p_1' => $v['p_1'],
							's_1_min' => $v['s_1_min'],
							's_1_max' => $v['s_1_max'],
							'p_4' => $v['p_4'],
							's_4_min' => $v['s_4_min'],
							's_4_max' => $v['s_4_max'],
							'p_2' => $v['p_2'],
							's_2_min' => $v['s_2_min'],
							's_2_max' => $v['s_2_max'],
							'p_5' => $v['p_5'],
							's_5_min' => $v['s_5_min'],
							's_5_max' => $v['s_5_max'],
							'p_3' => $v['p_3'],
							's_3_min' => $v['s_3_min'],
							's_3_max' => $v['s_3_max'],
							'score' => $v['score'],
						];
						$this->data->post=$dataKri;
						$id=$this->data->simpan_kri();
					}

				}
			}else{
			
				if (!isset($post['del'])) {
				
					if (count($kpi)>0) {
						foreach ($kpi as $key => $value) {
							if ($value['id_kpi']!='') {
								// $idkri = explode(',', $value['id']);
								$idkri = $value['id'];
								
								$dataKpi = [
									'minggu' => $post['minggu'],
									'rcsa_id' => $post['id'],
									'edit_id' => 0,
									'title' => $value['kpi_detail'].' ('. $value['kode_risk'].')',
									'satuan_id' => 0,
									'p_1' => 0,
									's_1_min' => 0,
									's_1_max' => 0,
									'p_4' => 0,
									's_4_min' => 0,
									's_4_max' => 0,
									'p_2' => 0,
									's_2_min' => 0,
									's_2_max' => 0,
									'p_5' => 0,
									's_5_min' => 0,
									's_5_max' => 0,
									'p_3' => 0,
									's_3_min' => 0,
									's_3_max' => 0,
									'score' => 0,
								];
								$this->data->post=$dataKpi;
								$this->data->simpan_kpi();
								$idKpi = $this->crud->last_id();
								// // $value['kpi_detail']
								$kri=$this->db->where('bk_tipe', 1)->where('rcsa_id', $post['id'])->where('rcsa_detail_id', $idkri)
								->group_by('kri_id')
								// ->get_compiled_select(_TBL_VIEW_RCSA_DET_LIKE_INDI);

								->get(_TBL_VIEW_RCSA_DET_LIKE_INDI)->result_array();
				
								
								if (count($kri)>0) {
									foreach ($kri as $k => $v) {
										$dataKri = [
											'minggu' => 0,
											'rcsa_id' => 0,
											'edit_id' => 0,
											'kpi_id' => $idKpi,
											'title' => $v['kri'],
											'satuan_id' => $v['satuan_id'],
											'p_1' => $v['p_1'],
											's_1_min' => $v['s_1_min'],
											's_1_max' => $v['s_1_max'],
											'p_4' => $v['p_4'],
											's_4_min' => $v['s_4_min'],
											's_4_max' => $v['s_4_max'],
											'p_2' => $v['p_2'],
											's_2_min' => $v['s_2_min'],
											's_2_max' => $v['s_2_max'],
											'p_5' => $v['p_5'],
											's_5_min' => $v['s_5_min'],
											's_5_max' => $v['s_5_max'],
											'p_3' => $v['p_3'],
											's_3_min' => $v['s_3_min'],
											's_3_max' => $v['s_3_max'],
											'score' => $v['score'],
										];
										$this->data->post=$dataKri;
										$this->data->simpan_kri();
									}
								}
							}
		
						}
					}
				}
			}

			$this->db->select('a.*, COUNT(b.kpi_id) AS kri_count', false);
			$this->db->join(_TBL_RCSA_KPI.' as b', 'a.id = b.kpi_id', 'left', false);
			$this->db->group_by("a.id");
			$data['list']=$this->db->where('a.minggu_id', $post['minggu'])->where('a.rcsa_id', intval($post['id']))->or_group_start()->where('a.sts_add',0)->where('a.rcsa_id', intval($post['id']))->group_end()->where('a.kpi_id',0)->get(_TBL_RCSA_KPI." as a")->result_array();
		}
	
		$id_kpi = [];
		foreach ($data['list'] as $key => $value) {
			$id_kpi[] = $value['id'];
		}
		$data['list_kpi'] = [];
		if (count($id_kpi)>0) {
			$data['list_kpi']=$this->db->where_in('kpi_id', $id_kpi)->get(_TBL_RCSA_KPI)->result_array();
		}
	
		// $data['combo'] = $this->load->view('kri', $data, true);

		$data['parent']=$post['id'];
		$data['minggu']=$post['minggu'];
		$result['combo'] = $this->load->view('kpi', $data, true);
		header('Content-type: application/json');
		echo json_encode($result);
	}

	function review_kpi(){
		$pos=$this->input->post();
		$rows = $this->db->where('id', $pos['rcsa_id'])->get(_TBL_RCSA)->row_array();
		$pos['owner']=$rows['owner_id'];
		$pos['period']=$rows['period_id'];
		$pos['term']=$rows['term_id'];
		$pos['minggu']=$rows['minggu_id'];

		$this->data->pos=$pos;
		$data = $this->data->get_detail_data();
		// dumps($data);
		// die($data);
		$data['mode']=0;
		$data['id']=$pos['rcsa_id'];

		$x=$this->load->view('detail-kpi', $data, true);
		$y=$this->load->view('detail-kpi2', $data, true);
		// $this->session->set_userdata(['cetak_grap'=>$data]);
		$hasil['combo']=$x.$y;
		header('Content-type: application/json');
		echo json_encode($hasil);
	}

	function view_kpi(){
		$data['minggu']=$post['minggu'];
		$data['id']=$post['rcsa_id'];
		$this->list_kpi($data);
	}

	function kpi_add(){
		$post=$this->input->post();
		$data['param']=$post;
		$this->cboSatuan=$this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'satuan')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$mit=[];
		if (intval($post['edit_id'])>0){
			$mit=$this->db->where('id', $post['edit_id'])->get(_TBL_RCSA_KPI)->row_array();
		}
		$disabled = '';

		$data['like'][] = ['title'=>_l('fld_kpi'),'help'=>_h('help_kpi'),'isi'=>form_input('title', ($mit)?$mit['title']:'', 'class="form-control" '.$disabled.' id="title"')];
		$data['like'][] = ['title'=>_l('fld_satuan'),'help'=>_h('help_satuan'),'isi'=>form_dropdown('satuan_id', $this->cboSatuan, ($mit)?$mit['satuan_id']:'', 'class="form-control select" '.$disabled.' id="satuan_id"')];
		$data['like'][] = ['title'=>'<a style="height:1.4rem;width:1.4rem;background-color:#2c5b29" class="btn bg-successx-400 rounded-round btn-icon btn-sm"><span class="letter-icon"></span></a>','help'=>_h('help_pencapaian'),'isi'=>form_input('p_1', ($mit)?$mit['p_1']:'', 'class="form-control" '.$disabled.' id="p_1" placeholder="'._l('fld_pencapaian').'" style="width:50%"').'&nbsp;&nbsp;&nbsp;'.form_input('s_1_min', ($mit)?$mit['s_1_min']:'', 'class="form-control text-center" '.$disabled.' id="s_1_min" placeholder="'._l('fld_min_satuan').'" style="width:10%"').'&nbsp;&nbsp;&nbsp;<span class="input-group-text"> - </span>&nbsp;&nbsp;&nbsp;'.form_input('s_1_max', ($mit)?$mit['s_1_max']:'', 'class="form-control text-center" '.$disabled.' id="s_1_max" placeholder="'._l('fld_mak_satuan').'" style="width:10%"').' <span class="input-group-text"> Satuan </span> '];

		$data['like'][] = ['title'=>'<a style="height:1.4rem;width:1.4rem;background-color:#50ca4e;" class="btn bg-orangex-400 rounded-round btn-icon btn-sm"><span class="letter-icon"></span></a>','help'=>_h('help_pencapaian_4'),'isi'=>form_input('p_4', ($mit)?$mit['p_4']:'', 'class="form-control" '.$disabled.' id="p_4" placeholder="'._l('fld_pencapaian').'" style="width:50%"').'&nbsp;&nbsp;&nbsp;'.form_input('s_4_min', ($mit)?$mit['s_4_min']:'', 'class="form-control text-center" '.$disabled.' id="s_4_min" placeholder="'._l('fld_min_satuan').'" style="width:10%"').'&nbsp;&nbsp;&nbsp;<span class="input-group-text"> - </span>&nbsp;&nbsp;&nbsp;'.form_input('s_4_max', ($mit)?$mit['s_4_max']:'', 'class="form-control text-center" '.$disabled.' id="s_4_max" placeholder="'._l('fld_mak_satuan').'" style="width:10%"').' <span class="input-group-text"> Satuan </span> '];

		$data['like'][] = ['title'=>'<a style="height:1.4rem;width:1.4rem;background-color:#edfd17;" class="btn bg-dangers-400 rounded-round btn-icon btn-sm"><span class="letter-icon"></span></a>','help'=>_h('help_pencapaian_2'),'isi'=>form_input('p_2', ($mit)?$mit['p_2']:'', 'class="form-control" '.$disabled.' id="p_2" placeholder="'._l('fld_pencapaian').'" style="width:50%"').'&nbsp;&nbsp;&nbsp;'.form_input('s_2_min', ($mit)?$mit['s_2_min']:'', 'class="form-control text-center" '.$disabled.' id="s_2_min" placeholder="'._l('fld_min_satuan').'" style="width:10%"').'&nbsp;&nbsp;&nbsp;<span class="input-group-text"> - </span>&nbsp;&nbsp;&nbsp;'.form_input('s_2_max', ($mit)?$mit['s_2_max']:'', 'class="form-control text-center" '.$disabled.' id="s_2_max" placeholder="'._l('fld_mak_satuan').'" style="width:10%"').' <span class="input-group-text"> Satuan </span> '];

		$data['like'][] = ['title'=>'<a style="height:1.4rem;width:1.4rem;background-color:#f0ca0f;" class="btn bg-dangers-400 rounded-round btn-icon btn-sm"><span class="letter-icon"></span></a>','help'=>_h('help_pencapaian_5'),'isi'=>form_input('p_5', ($mit)?$mit['p_5']:'', 'class="form-control" '.$disabled.' id="p_5" placeholder="'._l('fld_pencapaian').'" style="width:50%"').'&nbsp;&nbsp;&nbsp;'.form_input('s_5_min', ($mit)?$mit['s_5_min']:'', 'class="form-control text-center" '.$disabled.' id="s_5_min" placeholder="'._l('fld_min_satuan').'" style="width:10%"').'&nbsp;&nbsp;&nbsp;<span class="input-group-text"> - </span>&nbsp;&nbsp;&nbsp;'.form_input('s_5_max', ($mit)?$mit['s_5_max']:'', 'class="form-control text-center" '.$disabled.' id="s_5_max" placeholder="'._l('fld_mak_satuan').'" style="width:10%"').' <span class="input-group-text"> Satuan </span> '];

		$data['like'][] = ['title'=>'<a style="height:1.4rem;width:1.4rem;background-color:#e70808" class="btn bg-dangers-400 rounded-round btn-icon btn-sm"><span class="letter-icon"></span></a>','help'=>_h('help_pencapaian_3'),'isi'=>form_input('p_3', ($mit)?$mit['p_3']:'', 'class="form-control" '.$disabled.' id="p_3" placeholder="'._l('fld_pencapaian').'" style="width:50%"').'&nbsp;&nbsp;&nbsp;'.form_input('s_3_min', ($mit)?$mit['s_3_min']:'', 'class="form-control text-center" '.$disabled.' id="s_3_min" placeholder="'._l('fld_min_satuan').'" style="width:10%"').'&nbsp;&nbsp;&nbsp;<span class="input-group-text"> - </span>&nbsp;&nbsp;&nbsp;'.form_input('s_3_max', ($mit)?$mit['s_3_max']:'', 'class="form-control text-center" '.$disabled.' id="s_3_max" placeholder="'._l('fld_mak_satuan').'" style="width:10%"').' <span class="input-group-text"> Satuan </span> '];

		$data['like'][] = ['title'=>_l('fld_score'),'help'=>_h('help_score'),'isi'=>'<div class="input-group" style="width:15%;text-align:center;">'.form_input('score', ($mit)?$mit['score']:'', 'class="form-control" id="score" placeholder="'._l('fld_score').'"').'</div>'];

		$result['combo'] = $this->load->view('input-kpi', $data, true);
		header('Content-type: application/json');
		echo json_encode($result);
	}

	function simpan_kpi(){
		$post=$this->input->post();
		// dumps($post);
		// die();
		$this->data->post=$post;
		$this->data->simpan_kpi();
		$data['minggu']=$post['minggu'];
		$data['id']=$post['rcsa_id'];
		$this->list_kpi($data);
	}

	function kpi_delete(){
		$post=$this->input->post();
		$this->crud->crud_table(_TBL_RCSA_KPI);
		$this->crud->crud_type('delete');
		$this->crud->crud_where(['field' => 'id', 'value' => $post['edit_id']]);
		$this->crud->process_crud();
		$data['minggu']=$post['minggu'];
		$data['id']=$post['rcsa_id'];
		$data['del']=true;
		$this->list_kpi($data);
	}

	function kri_delete(){
		$post=$this->input->post();
		$this->crud->crud_table(_TBL_RCSA_KPI);
		$this->crud->crud_type('delete');
		$this->crud->crud_where(['field' => 'id', 'value' => $post['edit_id']]);
		$this->crud->process_crud();
		$data = $post;
		// $data['id']=$post['edit_id'];
		// $this->kri_add($data);
		$data['del']=true;
		$data['minggu']=$post['minggu'];
		$data['id']=$post['rcsa_id'];
		$this->list_kpi($data);
	}

	function optionalPersonalButton($button, $row){
		$button=[];

		if ($row['tgl_selesai_term'] != $row['tgl_akhir_mitigasi']) {
			# code...
		
			$button['propose']=[
				'label'=>'Update Progress',
				'id'=>'btn_propose_one',
				'class'=>'text-success',
				'icon' =>'icon-file-spreadsheet',
				'url' => base_url(_MODULE_NAME_.'/progress/'),
				'attr' => ' target="_self" '
			];

			if ($row['status_id_mitigasi']==0 || $row['status_final_mitigasi']==1){
				$button['progress']=[
					'label'=>'Propose Risiko',
					'id'=>'btn_propose_one',
					'class'=>'text-info propose-mitigasi',
					'icon' =>'icon-file-spreadsheet',
					'url' => base_url(_MODULE_NAME_.'/propose/'),
					'attr' => ['target'=>'_self'],
				];
			}

		}

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

		if($row['status_id_mitigasi']>= 1){
			if ($this->super_user){
				$button['reset']=[
					'label'=>'Reset Approval',
					'id'=>'btn_reset_one',
					'class'=>'text-warning reset-approval',
					'icon' =>'icon-paste ',
					'url' => base_url(_MODULE_NAME_.'/reset-approval/'),
					'attr' => ['data-id'=>$row['id'], 'data-url'=>'reset-approval'],
					'type'=>'span',
				];
			}
		}
		
		return $button;
	}

	function optionalButton($button, $mode){
		if ($mode=='list'){
			// unset($button['delete']);
			// unset($button['print']);
			// unset($button['search']);

		

			$button['print']['detail']['lap'] = [
				'label' => 'Excel Laporan',
				'color' => 'bg-green',
				'id' => 'btn_lap',
				'tag' => 'a',
				'round' => ($this->configuration['round_button']) ? 'rounded-round' : '',
				'icon' => 'icon-import',
				'url' => '#!',

				'attr' => ' target="" data-url="'.base_url(_MODULE_NAME_.'/export-lap/').'"',
				'align' => 'left'
			];

			$button['print']['detail']['kri'] = [
				'label' => 'Excel KPI & KRI',
				'color' => 'bg-green',
				'id' => 'btn_kri',
				'tag' => 'a',
				'round' => ($this->configuration['round_button']) ? 'rounded-round' : '',
				'icon' => 'icon-import',
				'url' => '#!',

				'attr' => ' target="" data-url="'.base_url(_MODULE_NAME_.'/export-kri/').'"',
				'align' => 'left'
			];
		}

		return $button;
	}

	function export_lap(){
		$post=explode(",",$this->input->post('id'));
		$result = [];
		foreach ($post as $key => $value) {
			$result[] = base_url('/progress-mitigasi/cetak-lap/' . $value);
		}
		header('Content-type: application/json');
		echo json_encode($result);
	}

	function export_kri(){
		$post=explode(",",$this->input->post('id'));
		$result = [];
		foreach ($post as $key => $value) {
			$result[] = base_url('/progress-mitigasi/cetak-kri/' . $value);
		}
		header('Content-type: application/json');
		echo json_encode($result);
	}

	function cetak_lap($id)
    {
		$data=$this->data->get_data_monitoring($id);
		$data['id']=$id;
		$data['export']=false;
		$hasil = $this->load->view('risk_context/monitoring', $data, true);
		$n = $data['parent']['kode_dept'].'-'.$data['parent']['term'].'-'.$data['parent']['period_name'];
		
        $cetak = 'register_excel';
        $nm_file = 'Laporan-Progress-Mitigasi-'.$n;
        $this->$cetak($hasil, $nm_file);
    }

	function cetak_kri($id)
    {
		$data['export']=false;

		$pos=$this->input->post();
		$rows = $this->db->where('id', $id)->get(_TBL_RCSA)->row_array();
		$pos['owner']=$rows['owner_id'];
		$pos['period']=$rows['period_id'];
		$pos['term']=$rows['term_id'];
		$this->data->pos=$pos;
		$data = $this->data->get_detail_data();
		$firstKey = reset($data['data']);
		$bulKey = reset($firstKey['bulan']);
		$n = $data['parent']['owner_code'].'-'.$bulKey['period'];
	
		$data['mode']=0;
		$data['id']=$id;

		$x=$this->load->view('detail-kpi', $data, true);
		$y=$this->load->view('detail-kpi2', $data, true);
		$hasil=$x.$y;

		
        $cetak = 'register_excel';
        $nm_file = 'Review-KRI-'.$n;
        $this->$cetak($hasil, $nm_file);
    }

	function register_excel($data, $nm_file)
    {
        header("Content-type:appalication/vnd.ms-excel");
        header("content-disposition:attachment;filename=" . $nm_file . ".xls");

        $html = $data;
        echo $html;
        exit;
    }

	function reset_approval(){
		$post=$this->input->post();
		$hasil=[];

		$this->crud->crud_table(_TBL_RCSA);
		$this->crud->crud_type('edit');
		$this->crud->crud_field('status_revisi_mitigasi', 0, 'int');
		$this->crud->crud_field('status_id_mitigasi', 0, 'int');
		$this->crud->crud_field('status_final_mitigasi', 0, 'int');
		$this->crud->crud_field('minggu_id_mitigasi', 0, 'int');
		
		$this->crud->crud_field('note_propose_mitigasi', null);
		$this->crud->crud_field('param_approval_mitigasi', null);
		$this->crud->crud_field('tgl_propose_mitigasi', null);
		$this->crud->crud_where(['field' => 'id', 'value' => $post['id']]);
		$this->crud->process_crud();

		$this->crud->crud_table(_TBL_LOG_APPROVAL);
		$this->crud->crud_type('add');
		$this->crud->crud_field('rcsa_id', $post['id'], 'int');
		$this->crud->crud_field('keterangan', 'Approval dibatalkan oleh Admin');
		$this->crud->crud_field('note', 'Approval dibatalkan oleh Admin');
		$this->crud->crud_field('tanggal', date('Y-m-d H:i:s'), 'datetime');
		$this->crud->crud_field('user_id', $this->ion_auth->get_user_id());
		$this->crud->crud_field('penerima_id', 0);
		$this->crud->process_crud();
		header('Content-type: application/json');
		echo json_encode($hasil);
	}

}