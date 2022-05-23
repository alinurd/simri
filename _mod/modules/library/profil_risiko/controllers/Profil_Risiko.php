<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Profil_Risiko extends MY_Controller {
	var $table="";
	var $post=array();
	var $sts_cetak=false;
	var $super_user=0;
	var $ownerx=0;
	var $ori=[];

	public function __construct()
	{
		parent::__construct();
		$this->load->library('map');
		$this->load->language('risk_context');
	}

	function init($action='list'){

		$this->cbo_owner = $this->get_combo_parent_dept();
		$this->period=$this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'period')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$this->term=$this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'term')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$this->minggu=$this->crud->combo_select(['id', 'concat(param_string) as minggu'])->combo_where('kelompok', 'minggu')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();

		
		$this->set_Tbl_Master(_TBL_VIEW_RCSA_DETAIL);

		$this->addField(array('field'=>'id', 'type'=>'int', 'show'=>false, 'size'=>4));
		$this->addField(array('field'=>'rcsa_id', 'type'=>'int', 'show'=>false, 'size'=>4));
		$this->addField(['field'=>'risiko_dept', 'show'=>false]);
		$this->addField(['field'=>'kode_risk', 'show'=>false]);
		$this->addField(['field'=>'kode_risiko_dept', 'show'=>false]);
		$this->addField(['field'=>'kode_aktifitas', 'show'=>false]);
		$this->addField(['field'=>'kode_dept', 'show'=>false]);
		$this->addField(['field'=>'klasifikasi_risiko', 'show'=>false]);
		$this->addField(['field'=>'tipe_risiko', 'show'=>false]);
		$this->addField(['field'=>'owner_name', 'show'=>false]);
		$this->addField(['field'=>'like_code', 'show'=>false]);
		$this->addField(['field'=>'color', 'show'=>false]);
		$this->addField(['field'=>'color_text', 'show'=>false]);
		$this->addField(['field'=>'level_color', 'show'=>false]);
		$this->addField(['field'=>'impact_code', 'show'=>false]);
		$this->addField(['field'=>'risiko_inherent_text', 'show'=>false]);
		$this->addField(['field'=>'like_code_residual', 'show'=>false]);
		$this->addField(['field'=>'color_residual', 'show'=>false]);
		$this->addField(['field'=>'color_text_residual', 'show'=>false]);
		$this->addField(['field'=>'level_color_residual', 'show'=>false]);
		$this->addField(['field'=>'impact_code_residual', 'show'=>false]);
		$this->addField(['field'=>'risiko_residual_text', 'show'=>false]);
		$this->addField(['field'=>'efek_kontrol_text', 'show'=>false]);
		$this->addField(['field'=>'treatment', 'show'=>false]);
		$this->addField(['field'=>'like_code_target', 'show'=>false]);
		$this->addField(['field'=>'color_target', 'show'=>false]);
		$this->addField(['field'=>'color_text_target', 'show'=>false]);
		$this->addField(['field'=>'level_color_target', 'show'=>false]);
		$this->addField(['field'=>'impact_code_target', 'show'=>false]);
		$this->addField(['field'=>'risiko_target_text', 'show'=>false]);
		$this->addField(['field'=>'jml', 'show'=>false]);
		$this->addField(['field'=>'owner_id', 'title'=>'Owner', 'type'=>'int', 'required'=>false,'input'=>'combo', 'search'=>true, 'values'=>$this->cbo_owner, 'show'=>false]);
		$this->addField(['field'=>'period_id', 'title'=>'Period', 'type'=>'int', 'required'=>false,'input'=>'combo', 'search'=>true, 'values'=>$this->period, 'show'=>false]);
		$this->addField(['field'=>'term_id', 'title'=>'Term', 'type'=>'int', 'required'=>false,'input'=>'combo', 'search'=>true, 'values'=>$this->term, 'show'=>false]);
		$this->addField(['field'=>'minggu_id', 'title'=>'Minggu', 'type'=>'int', 'required'=>false,'input'=>'combo', 'search'=>true, 'values'=>[], 'show'=>false]);

		$this->set_Field_Primary($this->tbl_master, 'id');

		$this->set_Sort_Table($this->tbl_master,'created_at', 'desc');
		$this->set_Group_Table($this->tbl_master,'kode_risk');
		$this->set_Group_Table($this->tbl_master,'period_id');


		$this->set_Table_List($this->tbl_master,'id', '<input type="checkbox" class="form-check-input pointer" name="chk_list_parent" id="chk_list_parent"  style="padding:0;margin:0;">','0%','left','no-sort');
		$this->set_Table_List($this->tbl_master,'kode_risk', 'Kode Risiko');
		$this->set_Table_List($this->tbl_master,'owner_name', 'Owner');
		$this->set_Table_List($this->tbl_master,'period_id', 'Periode');
		// $this->set_Table_List($this->tbl_master,'term_id', 'Triwulan');
		$this->set_Table_List($this->tbl_master,'risiko_dept', 'Risiko Dept.');
		$this->set_Table_List($this->tbl_master,'klasifikasi_risiko', 'Klasifikasi');
		$this->set_Table_List($this->tbl_master,'like_code', 'Risiko Inheren');
		$this->set_Table_List($this->tbl_master,'efek_kontrol_text', 'Efek Kontrol');
		$this->set_Table_List($this->tbl_master,'like_code_residual', 'Risiko Residual');
		$this->set_Table_List($this->tbl_master,'treatment', 'Respon');
		$this->set_Table_List($this->tbl_master,'like_code_target', 'Risiko Target');
		$this->set_Table_List($this->tbl_master,'jml', 'Mitigasi');

		$this->_set_Where_Owner();

		$this->set_Close_Setting();
		$this->super_user = intval($this->_data_user_['is_admin']);
		$this->ownerx = intval(($this->super_user==0)?$this->_data_user_['owner_id']:0);
		$this->ori = [];

		$configuration = [
			'show_title_header' => false,
			'show_list_header' => true,
			'content_title' => 'Profil Risiko List'.form_hidden(['is_admin'=>$this->super_user, 'owner'=>$this->ownerx])
		];
		return [
			'configuration'	=> $configuration
		];
	}

	public function MASTER_DATA_LISTX($arrId, $rows)
    {

		// $this->ori=[];
		
		foreach ($rows as $row){
			
			$this->super_user = intval($this->_data_user_['is_admin']);
			$this->ownerx = intval(($this->super_user==0)?$this->_data_user_['owner_id']:0);
			$check = $this->data->checklist($this->ownerx, $row['period_id']);

			$kode = $row['kode_dept'].'-'.$row['kode_aktifitas'].'-'.$row['kode_risiko_dept'].'-'.$row['period_id'];
			if (in_array($kode, $check)) {
				$this->ori[] = $kode;
			}
		}
		// Doi::dump($this->ori);

	}

	function setContentHeader($mode=''){
		$data['period'] = $this->period;
		$data['term'] = [];
		$content=$this->load->view('header', $data, true);
		return $content;
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
	
	function listBox_KODE_RISIKO_DEPTX($field, $rows, $value){
		$urut=str_pad($value,3,0,STR_PAD_LEFT );

		return $rows['kode_dept'].'-'.$rows['kode_aktifitas'].' - '.$urut;
	}
	
	function listBox_TERM_ID($field, $rows, $value){
		$cbominggu=$this->data->get_data_minggu($value);
		$minggu = ($rows['minggu_id'])?$cbominggu[$rows['minggu_id']]:'';
		$a = $this->term[$value].' - '.$minggu;
		return $a;
	}

	function listBox_like_code($field, $rows, $value){
		$a = '<div class="text-center" style="padding:20px;background-color:'.$rows['color'].';color:'.$rows['color_text'].';">'.$rows['level_color'].'<br/><small>'.$rows['like_code'].'x'.$rows['impact_code'].' : '.$rows['risiko_inherent_text'].'</small></div>';
		return $a;
	}

	function listBox_like_code_residual($field, $rows, $value){
		$a = '<div class="text-center" style="padding:20px;background-color:'.$rows['color_residual'].';color'.$rows['color_text_residual'].';">'.$rows['level_color_residual'].'<br/><small>'.$rows['like_code_residual'].'x'.$rows['impact_code_residual'].' : '.$rows['risiko_residual_text'].'</small></div>';
		return $a;
	}

	

	function listBox_like_code_target($field, $rows, $value){
		$a = '<div class="text-center" style="padding:20px;background-color:'.$rows['color_target'].';color'.$rows['color_text_target'].';">'.$rows['level_color_target'].'<br/><small>'.$rows['like_code_target'].'x'.$rows['impact_code_target'].' : '.$rows['risiko_target_text'].'</small></div>';
		return $a;
	}

	function listBox_jml($field, $rows, $value){
		$a = '<div class="text-center"><span class="badge bg-teal-400 badge-pill align-self-center ml-auto">'.$rows['jml'].'</span></div>';
		return $a;
	}

	function listBox_klasifikasi_risiko($field, $rows, $value){
		$a = $rows['klasifikasi_risiko'].' | '.$rows['tipe_risiko'];
		return $a;
	}

	function listBox_id($field, $rows, $value){
		// $period=intval($this->input->get('period'));
		// $term=intval($this->input->get('term'));
		$this->super_user = intval($this->_data_user_['is_admin']);
		$this->ownerx = intval(($this->super_user==0)?$this->_data_user_['owner_id']:0);
		// $check = $this->data->checklist();
		
		
		// if ($period>0 && $term>0) {
		// if ($this->super_user==0) {
			$check = $this->data->checklist($this->ownerx, $rows['period_id']);
			// dumps($check);
			// die();
		// }
		$urut=str_pad($rows['kode_risiko_dept'],3,0,STR_PAD_LEFT );
		$kode = $rows['kode_dept'].'-'.$rows['kode_aktifitas'].'-'.$rows['kode_risiko_dept'].'-'.$rows['period_id'];

		$select = (in_array($kode, $check))?'checked':'';
		$a='<div class="text-center"  style="padding:0px 20px 20px 0px;"><input type="checkbox" class="form-check-input pointer text-center" name="chk_list[]" style="padding:0;margin:0;" value="'.$kode.'" data-dept="'.$rows['kode_dept'].'" data-aktifitas="'.$rows['kode_aktifitas'].'" data-risiko="'.$rows['kode_risiko_dept'].'" data-period="'.$rows['period_id'].'" '.$select.'/></div>';
		return $a;
	}

	function optionalButton($button, $mode){
	
		if ($mode=='list'){
			unset($button['delete']);
			unset($button['print']);
			unset($button['insert']);
			// unset($button['search']);

			$button['save']=[
				'label'=>$this->lang->line('btn_save'),
				'color'=>'bg-success-300',
				'id'=>'btn_save_modul',
				'name'=>'Save',
				'value'=>'Simpan',
				'attr'=>'data-ori="ok"',
				'type'=>'submit',
				'round'=>($this->configuration['round_button'])?'rounded-round':'',
				'icon' =>'icon-floppy-disk',
				'url' => base_url(_MODULE_NAME_.'/save-modul/')
			];
		}

		return $button;
	}

	function optionalPersonalButton($button, $row){
		$button=[];
		$button['identifikasi']=[
			'label'=>'View',
			'id'=>'btn_schedule_one',
			'class'=>'text-success',
			'icon' =>'icon-file-spreadsheet ',
			'url' => base_url(_MODULE_NAME_.'/edit-identifikasi/'.$row['rcsa_id'].'/'),
			'attr' => ' target="_self" '
		];

		return $button;
	}

	
	function edit_identifikasi($id=0, $edit=0){
		$mode='save';
		
		if (empty($edit)){
			$edit=intval($this->input->post('edit'));
		}
		$this->db->delete(_TBL_RCSA_DET_LIKE_INDI, ['rcsa_detail_id'=>0,'created_by'=>$this->ion_auth->get_user_name()]);
		$this->db->delete(_TBL_RCSA_DET_DAMPAK_INDI, ['rcsa_detail_id'=>0,'created_by'=>$this->ion_auth->get_user_name()]);

		$data['parent']=$this->db->where('id', $id)->get(_TBL_VIEW_RCSA)->row_array();
		$rcsa_detail=$this->db->where('id', $edit)->get(_TBL_VIEW_RCSA_DETAIL)->row_array();
		$data['mode']=1;//'Mode : Update data';
		$data['mode_text']=_l('fld_mode_edit');//'Mode : Update data';
		$data['info_parent']=$this->load->view('info-parent',$data, true);
		$data['detail']=$this->identifikasi_content($rcsa_detail, $data['parent']);

		$data['identifikasi']=$this->load->view('risk_context/identifikasi-risiko',$data, true);
		
		$data['analisa']=$this->load->view('risk_context/analisa-risiko',$data, true);
		$data['rcsa_detail']=$rcsa_detail;

		$rows=$this->db->select('rcsa_mitigasi_id as id, count(rcsa_mitigasi_id) as jml')->group_by(['rcsa_mitigasi_id'])->where('rcsa_detail_id', $edit)->get(_TBL_VIEW_RCSA_MITIGASI_DETAIL)->result_array();
		$miti=[];
		foreach($rows as $row){
			$miti[$row['id']]=$row['jml'];
		}
		$rows=$this->db->where('rcsa_detail_id', $edit)->get(_TBL_VIEW_RCSA_MITIGASI)->result_array();
		foreach($rows as &$row){
			if (array_key_exists($row['id'], $miti)){
				$row['jml']=$miti[$row['id']];
			}
		}
		unset($row);
		$rows = $this->convert_owner->set_data($rows)->set_param(['penanggung_jawab'=>'penanggung_jawab_id', 'koordinator'=>'koordinator_id'])->draw();
		
		$data['picku'] = $this->get_data_dept();
		
		$data['mitigasi']=$rows;
		$data['d_evaluasi']=$this->evaluasi_content($rcsa_detail);
		$data['d_target']=$this->target_content($rcsa_detail);
		$data['list_mitigasi']=$this->load->view('list-mitigasi',$data, true);
		$data['evaluasi']=$this->load->view('evaluasi-risiko',$data, true);
		$data['target']=$this->load->view('target-risiko',$data, true);
		$data['hidden']=['rcsa_id'=>$id,'rcsa_detail_id'=>$edit];

		$hasil = $this->load->view('update-identifikasi',$data, true);

		$configuration = [
			'show_title_header' => false,
			'show_action_button' => false,
			'show_header_content' => false,
			'box_content' => false,
		];

		if ($this->input->is_ajax_request()){
			echo json_encode(['combo'=>$hasil]);
		}else{
			$this->default_display(['content'=>$hasil, 'configuration'=>$configuration]);
		}
		
	}

	function identifikasi_content($data=[], $parent=[]){
		$mode='add';
		$id_edit=0;
		if ($data){
			$mode='edit';
			$id_edit=$data['id'];
		}

		$jml_like_indi=$this->db->where('bk_tipe', 1)->where('rcsa_detail_id', intval($id_edit))->or_group_start()->where('rcsa_detail_id',0)->where('created_by', $this->ion_auth->get_user_name())->group_end()->get(_TBL_VIEW_RCSA_DET_LIKE_INDI)->num_rows();
		$jml_dampak_indi=$this->db->where('bk_tipe', 1)->where('rcsa_detail_id', intval($id_edit))->or_group_start()->where('rcsa_detail_id',0)->where('created_by', $this->ion_auth->get_user_name())->group_end()->get(_TBL_VIEW_RCSA_DET_DAMPAK_INDI)->num_rows();

		// dumps($jml_like_indi);
		// dumps($jml_dampak_indi);
		$kpi=$this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'kpi')->combo_where('param_text like ', '%'.$parent['owner_id'].'%')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
	
		$aktivitas=$this->crud->combo_select(['id', 'concat(kode,\' - \',data) as data'])->combo_where('pid', intval($parent['owner_id']))->combo_where('kelompok', 'aktivitas')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$sasaran=$this->crud->combo_select(['id', 'data'])->combo_where('pid', intval($parent['owner_id']))->combo_where('kelompok', 'sasaran-aktivitas')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		
		
		$tahapan=$this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'tahapan-proses')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$kel=$this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'lib-cat')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();

		$risk_type=[_l('cbo_select')];
		if (isset($data['klasifikasi_risiko_id'])){
			$risk_type=$this->crud->combo_select(['id', 'data'])->combo_where('pid',$data['klasifikasi_risiko_id'])->combo_where('kelompok', 'risk-type')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		}
		$penyebab_id=[_l('cbo_select')];
		$peristiwa_id=[_l('cbo_select')];
		$dampak_id=[_l('cbo_select')];
		if (isset($data['tipe_risiko_id'])){
			$penyebab_id=$this->crud->combo_select(['id', 'library'])->combo_where('type', 1)->combo_where('risk_type_no', $data['tipe_risiko_id'])->combo_where('active', 1)->combo_tbl(_TBL_LIBRARY)->get_combo()->result_combo();
			$peristiwa_id=$this->crud->combo_select(['id', 'library'])->combo_where('type', 2)->combo_where('library_no', $data['penyebab_id'])->combo_where('active', 1)->combo_tbl(_TBL_VIEW_LIBRARY_DETAIL)->get_combo()->result_combo();
			$dampak_id=$this->crud->combo_select(['id', 'library'])->combo_where('type', 3)->combo_where('library_no', $data['penyebab_id'])->combo_where('active', 1)->combo_tbl(_TBL_VIEW_LIBRARY_DETAIL)->get_combo()->result_combo();
		}

		$option = '';
		foreach($peristiwa_id as $key=>$row){
			$option .= '<option value="'.$key.'">'.$row.'</option>';
		}
		$param['peristiwa_cbo']=$option;
		$option = '';
		foreach($dampak_id as $key=>$row){
			$option .= '<option value="'.$key.'">'.$row.'</option>';
		}
		$param['dampak_cbo']=$option;

		$aspek =0;
		$aspek_det ="";
		if($data){
			if($data['tipe_analisa_no'] == 2 || $data['tipe_analisa_no'] == 3){
				$aspek = $data['aspek_risiko_id'];
			}else{

				$aspek =0;
			}

			$aspek_det = $data['aspek_det'];
		}
		// dumps($aspek);
		if ($aspek){
			$like=$this->crud->combo_select(['urut', 'concat(urut,\' - \',data) as x'])->combo_where('active', 1)->combo_where('pid',$aspek)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();

			$like_semi=$this->crud->combo_select(['urut', 'concat(urut,\' - \',data) as x'])->combo_where('active', 1)->combo_where('pid',$aspek)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();

		}else{
			$like=$this->crud->combo_select(['id', 'concat(code,\' - \',level) as x'])->combo_where('active', 1)->combo_where('category','likelihood')->combo_tbl(_TBL_LEVEL)->get_combo()->result_combo();
			
			$like_semi = [];
		}
		// dumps($like);
		
		$impact=$this->crud->combo_select(['id', 'concat(code,\' - \',level) as x'])->combo_where('active', 1)->combo_where('category','impact')->combo_tbl(_TBL_LEVEL)->get_combo()->result_combo();
		$cboControl=$this->crud->combo_select(['id', 'data'])->noSelect()->combo_where('active', 1)->combo_where('kelompok','existing-control')->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$aspek_risiko=$this->crud->combo_select(['id', 'data'])->combo_where('active', 1)->combo_where('kelompok','aspek-risiko')->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		// $aspek_risiko['-1'] = "dll";
		$arrControl=[];
		$jml = intval(count($cboControl) / 2);
        $kontrol = '';
		$i = 1;
		$control =[];
		if ($data){
        	$control = explode('###',$data['nama_kontrol']);
		}
		$kontrol .= '<div class="well p100">';
        foreach ($cboControl as $row) {
            if ($i == 1)
                $kontrol .= '<div class="col-md-6">';

            $sts = false;
            foreach ($control as $ctrl) {
                if ($row == $ctrl) {
                    $sts = true;
                    break;
                }
            }

            $kontrol .= '<label class="pointer">' . form_checkbox('check_item[]', $row, $sts);
            $kontrol .= '&nbsp;' . $row . '</label><br/>';
            if ($i == $jml)
                $kontrol .= '</div><div class="col-md-6">';

            ++$i;
        }
		$kontrol .= '</div>' . form_textarea("note_control", ($data) ? $data['nama_kontrol_note'] : '', ' class="summernote" style="width:100%;"') . '</div><br/>';

		$efek_control=[0=>_l('cbo_select'),1=>'L',2=>'D',3=>'L & D',4=>'Tidak ada kontrol'];

		$peristiwa = '<table class="table table-borderless" id="tblperistiwa"><tbody>';
		if ($data){
			$pi = explode(',',$data['peristiwa_id']);

			foreach($pi as $key=>$x){
				$icon='<i class="icon-plus-circle2 text-primary-400 add-peristiwa"></i>&nbsp;&nbsp;<i class="icon-file-empty text-success-400 add-text-peristiwa" data-id="0"></i>';
				if ($key>0){
					$icon='<i class="icon-database-remove text-danger-400 del-peristiwa"></i>';
				}
				$peristiwa .= '<tr><td style="padding-left:0px;">'.form_dropdown('peristiwa_id[]', $peristiwa_id, $x, 'id="peristiwa_id" class="form-control select" style="width:100%;"').form_input('peristiwa_id_text[]','','class="form-control d-none" id="peristiwa_id_text" placeholder="'._l('fld_peristiwa_risiko').'" ').'</td><td class="text-right pointer" width="10%" style="padding-right:0px;">'.$icon.'</td></tr>';
			}
		}else{
			$peristiwa .= '<tr><td style="padding-left:0px;">'.form_dropdown('peristiwa_id[]', $peristiwa_id, '', 'id="peristiwa_id" class="form-control select" style="width:100%;"').form_input('peristiwa_id_text[]','','class="form-control d-none" id="peristiwa_id_text" placeholder="'._l('fld_peristiwa_risiko').'"').'</td><td class="text-right pointer" width="10%" style="padding-right:0px;"><i class="icon-plus-circle2 text-primary-400 add-peristiwa"></i></td></tr>';
		}
		$peristiwa .= '</tbody></table>';

		$dampak = '<table class="table table-borderless" id="tbldampak"><tbody>';

		$csslevel='';
		if ($data){
			$pi = explode(',',$data['dampak_id']);

			foreach($pi as $key=>$x){
				$icon='<i class="icon-plus-circle2 text-primary-400 add-dampak"></i>&nbsp;&nbsp;<i class="icon-file-empty text-success-400 add-text-dampak" data-id="0"></i>';
				if ($key>0){
					$icon='<i class="icon-database-remove text-danger-400 del-dampak"></i>';
				}
				$dampak .= '<tr><td style="padding-left:0px;">'.form_dropdown('dampak_id[]', $dampak_id, $x, 'id="dampak_id" class="form-control select" style="width:100%;"').form_input('dampak_id_text[]','','class="form-control d-none" id="dampak_id_text" placeholder="'._l('fld_dampak_risiko').'"').'</td><td class="text-right pointer" width="10%" style="padding-right:0px;">'.$icon.'</td></tr>';
			}
			$csslevel='background-color:'.$data['color'].';color:'.$data['color_text'].';';
		}else{
			$dampak .= '<tr><td style="padding-left:0px;">'.form_dropdown('dampak_id[]', $dampak_id, '', 'id="dampak_id" class="form-control select" style="width:100%;"').form_input('dampak_id_text[]','','class="form-control d-none" id="dampak_id_text" placeholder="'._l('fld_dampak_risiko').'"').'</td><td class="text-right pointer" width="10%" style="padding-right:0px;"><i class="icon-plus-circle2 text-primary-400 add-dampak"></i></td></tr>';
		}

		$dampak .= '</tbody></table>';
		$tAdd= '<div class="form-control-feedback form-control-feedback-lg"><i class="icon-make-group"></i></div>';
		$param['identifikasi']['kpi'] = ['title'=>"KPI",'help'=>"", 'add'=>false, 'mandatori'=>false,'isi'=>form_dropdown('id_kpi', $kpi, ($data)?$data['id_kpi']:'', 'id="id_kpi" class="form-control select" style="width:100%;"')];

		$param['identifikasi']['aktifitas_id'] = ['title'=>_l('fld_aktifitas'),'help'=>_h('help_aktifitas'), 'add'=>false, 'mandatori'=>true,'isi'=>form_dropdown('aktifitas_id', $aktivitas, ($data)?$data['aktifitas_id']:'', 'id="aktifitas_id" class="form-control select" style="width:100%;"')];
		$param['identifikasi']['sasaran_id'] = ['title'=>_l('fld_sasaran_aktifitas'),'help'=>_h('help_sasaran_aktifitas'), 'mandatori'=>true,'add'=>false,'isi'=>form_dropdown('sasaran_id', $sasaran, ($data)?$data['sasaran_id']:'', 'id="sasaran_id" class="form-control select" style="width:100%;"')];
		// $param['identifikasi']['tahapan_id'] = ['title'=>_l('fld_tahapan_proses'),'help'=>_h('help_tahapan_proses'), 'add'=>true,'isi'=>form_dropdown('tahapan_id', $tahapan, ($data)?$data['tahapan_id']:'', 'id="tahapan_id" class="form-control select" style="width:100%;"')];
		// $param['identifikasi']['tahapan'] = ['title'=>_l('fld_tahapan_proses'),'help'=>_h('help_tahapan_proses'), 'add'=>true,'isi'=>form_dropdown('tahapan_id', $tahapan, ($data)?$data['tahapan_id']:'', 'id="tahapan_id" class="form-control select" style="width:100%;"')];
		$param['identifikasi']['tahapan'] = ['title'=>_l('fld_tahapan_proses'),'help'=>_h('help_tahapan_proses'), 'mandatori'=>true,'isi'=>form_textarea('tahapan', ($data)?$data['tahapan']:''," id='tahapan' maxlength='500' size='500' class='form-control' style='overflow: hidden; width: 100% !important; height: 200px;' onblur='_maxLength(this , \"id_sisa_2\")' onkeyup='_maxLength(this , \"id_sisa_2\")' data-role='tagsinput'", true, ['size'=>500, 'isi'=>0,'no'=>2])];
		$param['identifikasi']['klasifikasi_risiko_id'] = ['title'=>_l('fld_klasifikasi_risiko'),'help'=>_h('help_klasifikasi_risiko'), 'mandatori'=>true,'isi'=>form_dropdown('klasifikasi_risiko_id', $kel, ($data)?$data['klasifikasi_risiko_id']:'', 'id="klasifikasi_risiko_id" class="form-control select" style="width:100%;"')];
		$param['identifikasi']['tipe_risiko_id'] = ['title'=>_l('fld_tipe_risiko'),'help'=>_h('help_tipe_risiko'), 'mandatori'=>true,'isi'=>form_dropdown('tipe_risiko_id', $risk_type, ($data)?$data['tipe_risiko_id']:'', 'id="tipe_risiko_id" class="form-control select" style="width:100%;"')];
		$param['identifikasi']['penyebab_id'] = ['title'=>_l('fld_penyebab_risiko'),'help'=>_h('help_penyebab_risiko'), 'mandatori'=>true, 'add'=>false,'isi'=>form_dropdown('penyebab_id', $penyebab_id, ($data)?$data['penyebab_id']:'', 'id="penyebab_id" class="form-control select" style="width:100%;"')];
		$param['identifikasi']['peristiwa_id'] = ['title'=>_l('fld_peristiwa_risiko'),'help'=>_h('help_peristiwa_risiko'), 'mandatori'=>true,'isi'=>$peristiwa];
		$param['identifikasi']['dampak_id'] = ['title'=>_l('fld_dampak_risiko'),'help'=>_h('help_dampak_risiko'), 'mandatori'=>true,'isi'=>$dampak];
		$param['identifikasi']['risiko_dept'] = ['title'=>_l('fld_risiko_dept'),'help'=>_h('help_risiko_dept'), 'mandatori'=>true,'isi'=>form_textarea('risiko_dept', ($data)?$data['risiko_dept']:''," id='risiko_dept' maxlength='500' size='500' class='form-control' style='overflow: hidden; width: 100% !important; height: 200px;' onblur='_maxLength(this , \"id_sisa_1\")' onkeyup='_maxLength(this , \"id_sisa_1\")' data-role='tagsinput'", true, ['size'=>500, 'isi'=>0,'no'=>1])];

		$tipe_analisa="<br/>&nbsp;";
		$check1=true;
		$check2=false;
		$check3=false;
		$tipe_analisa_no=1;
		if ($data){
			if ($data['tipe_analisa_no']==2){
				$check1=false;
				$check2=true;
				$check3=false;
			}elseif ($data['tipe_analisa_no']==3){
				$check1=false;
				$check2=false;
				$check3=true;
			}
			$tipe_analisa_no=$data['tipe_analisa_no'];
		}
		$tipe_analisa .='<div class="form-check form-check-inline"><label class="form-check-label">';
		$tipe_analisa .= form_radio('tipe_analisa_no', 1, $check1, 'id="tipe_analisa_no_1"  class="form-check-primary" ');
		$tipe_analisa .= form_label('&nbsp;&nbsp;&nbsp; Kualitatif &nbsp;&nbsp;', 'tipe_analisa_no_1', ['class'=>'pointer']);
		$tipe_analisa .='</label></div>';
		$tipe_analisa .='<div class="form-check form-check-inline"><label class="form-check-label">';
		$tipe_analisa .= form_radio('tipe_analisa_no', 2, $check2, 'id="tipe_analisa_no_2"  class="form-check-primary" ');
		$tipe_analisa .= form_label('&nbsp;&nbsp;&nbsp; Kuantitatif &nbsp;&nbsp;', 'tipe_analisa_no_2', ['class'=>'pointer']);
		$tipe_analisa .='</label></div>';
		$tipe_analisa .='<div class="form-check form-check-inline"><label class="form-check-label">';
		$tipe_analisa .= form_radio('tipe_analisa_no', 3, $check3, 'id="tipe_analisa_no_3"  class="form-check-primary" ');
		$tipe_analisa .= form_label('&nbsp;&nbsp;&nbsp; Semi Kuantitatif &nbsp;&nbsp;', 'tipe_analisa_no_3', ['class'=>'pointer']);
		$tipe_analisa .='</label></div><br/>&nbsp<br/>&nbsp;';

		$param['tipe_analisa_no'] = $tipe_analisa_no;

		$param['tipe_analisa'] = ['title'=>'','help'=>'','isi'=>$tipe_analisa];
		$param['analisa_kualitatif'][] = ['title'=>_l('fld_indi_likelihood'),'help'=>_h('help_likelihood'),'mandatori'=>true,'isi'=>form_input('like_text', ($data)?$data['like_text']:'', 'id="like_text" class="form-control" style="width:100%;"')];

		$param['analisa_kualitatif'][] = ['title'=>_l('fld_likelihood'),'help'=>_h('help_likelihood'), 'mandatori'=>true,'isi'=>form_dropdown('like_id', $like, ($data)?$data['like_id']:'', 'id="like_id" class="form-control select" style="width:100%;"')];
		$param['analisa_kualitatif'][] = ['title'=>_l('fld_indi_dampak'),'help'=>_h('help_impact'),'mandatori'=>true,'isi'=>form_input('impact_text', ($data)?$data['impact_text']:'', 'id="impact_text" class="form-control" style="width:100%;"')];
		$param['analisa_kualitatif'][] = ['title'=>_l('fld_impact'),'help'=>_h('help_impact'), 'mandatori'=>true,'isi'=>form_dropdown('impact_id', $impact, ($data)?$data['impact_id']:'', 'id="impact_id" class="form-control select" style="width:100%;"')];
		

		// dumps($parent);
		$param['analisa_kuantitatif'][] = ['title'=>'','help'=>'','isi'=>'<span class="btn btn-primary legitRipple pointer" data-rcsa="'.$parent['id'].'" data-id="'.$id_edit.'" id="indikator_like" style="width:100%;"> Input Risk Indikator Likelihood [ '.$jml_like_indi.' ] </span>'];
		
		$param['analisa_kuantitatif'][] = ['title'=>'','help'=>'','isi'=>'<span class="btn btn-primary legitRipple pointer"  data-rcsa="'.$parent['id'].'" data-id="'.$id_edit.'" id="indikator_dampak" style="width:100%;"> Input Risk Indikator Dampak  [ '.$jml_dampak_indi.' ] </span>'];

		$param['analisa_kuantitatif'][] = ['title'=>_l('fld_likelihood'),'help'=>_h('help_likelihood'), 'mandatori'=>true,'isi'=>form_input('like_text_kuantitatif', ($data)?$data['like_inherent']:'', 'id="like_text_kuantitatif" class="form-control" style="width:100%;" readonly="readonly"').form_hidden(['like_id_2'=>($data)?$data['like_id']:''])];

		$param['analisa_kuantitatif'][] = ['title'=>_l('fld_impact'),'help'=>_h('help_impact'), 'mandatori'=>true,'isi'=>form_input('impact_text_kuantitatif', ($data)?$data['impact_inherent']:'', 'id="impact_text_kuantitatif" class="form-control" style="width:100%;" readonly="readonly"').form_hidden(['impact_id_2'=>($data)?$data['impact_id']:''])];


	
	


		$param['analisa_semi'][] = ['title'=>_l('fld_indi_likelihood'),'help'=>_h('help_likelihood'), 'mandatori'=>true,'isi'=>form_dropdown('aspek_risiko_id', $aspek_risiko, $aspek, 'id="aspek_risiko_id" class="form-control select" style="width:100%;"')];

		$param['analisa_semi'][] = ['title'=>"Detail",'help'=>_h("keterangan/Detail lainnya"), 'mandatori'=>false,'isi'=>form_input('aspek_det', ($data)?$data['aspek_det']:'', 'id="aspek_det" class="form-control" style="width:100%;" ')];

		$urutTemp = [1,7,8,9,10];
		
		$like_semi_form = '<select name="like_id_3" id="like_id_3" class="form-control select" style="width:100%;">';
		if (!empty($like_semi)) {
			
			foreach ($like_semi as $key => $value) {
				$sel = ($data)?$data['like_id']:'';
				$selected = ($sel==$key)?'selected':'';
				$k = intval($key)-1;
				$dataTemp = (isset($urutTemp[$k]))?$urutTemp[$k]:0;
				$like_semi_form .= '<option data-temp="'.$dataTemp.'" value="'.$key.'"'.$selected.'>'.$value.'</option>';
			}
		}
		$like_semi_form .= '</select>';

		// form_dropdown('like_id_3', $like_semi, ($data)?$data['like_id']:'', 'id="like_id_3" class="form-control select" style="width:100%;"')

		$param['analisa_semi'][] = ['title'=>_l('fld_likelihood'),'help'=>_h('help_likelihood'), 'mandatori'=>true,'isi'=>$like_semi_form];

		$param['analisa_semi'][] = ['title'=>'','help'=>'','isi'=>'<span class="btn btn-primary legitRipple pointer"  data-rcsa="'.$parent['id'].'" data-id="'.$id_edit.'" id="indikator_dampak" style="width:100%;"> Input Risk Indikator Dampak  [ '.$jml_dampak_indi.' ] </span>'];
		// $param['analisa_semi'][] = ['title'=>_l('fld_likelihood'),'help'=>_h('help_likelihood'), 'mandatori'=>true,'isi'=>form_input('like_text_kuantitatif_semi', ($data)?$data['like_inherent']:'', 'id="like_text_kuantitatif" class="form-control" style="width:100%;" readonly="readonly"').form_hidden(['like_id_3'=>($data)?$data['like_id']:''])];

		$param['analisa_semi'][] = ['title'=>_l('fld_impact'),'help'=>_h('help_impact'), 'mandatori'=>true,'isi'=>form_input('impact_text_kuantitatif', ($data)?$data['impact_inherent']:'', 'id="impact_text_kuantitatif_semi" class="form-control" style="width:100%;" readonly="readonly"').form_hidden(['impact_id_3'=>($data)?$data['impact_id']:''],'id="impact_id_3"')];



		$param['analisa_semi'][] = ['title'=>'','help'=>'','isi'=>form_input('like_text_3', ($data)?$data['like_text']:'', 'id="like_text_3" class="form-control" style="width:100%;display:none"')];

		// $param['analisa_semi'][] = ['title'=>_l('fld_impact'),'help'=>_h('help_impact'), 'mandatori'=>true,'isi'=>form_dropdown('impact_id_3', $impact, ($data)?$data['impact_id']:'', 'id="impact_id_3" class="form-control select" style="width:100%;"')];
	


		$param['analisa_semi'][] = ['title'=>'','help'=>'','isi'=>form_input('impact_text_3', ($data)?$data['impact_text']:'', 'id="impact_text_3" class="form-control" style="width:100%;display:none"')];


		$param['analisa2'][] = ['title'=>_l('fld_risiko_inherent'),'help'=>_h('help_risiko_inherent'), 'mandatori'=>true,'isi'=>form_input('risiko_inherent_text', ($data)?$data['risiko_inherent_text']:'', 'class="form-control text-center" id="risiko_inherent_text" readonly="readonly" style="width:15%;"').form_hidden(['risiko_inherent'=>($data)?$data['risiko_inherent']:0])];
		$param['analisa2'][] = ['title'=>_l('fld_level_risiko'),'help'=>_h('help_level_risiko'), 'mandatori'=>true,'isi'=>form_input('level_inherent_text', ($data)?$data['level_color']:'', 'class="form-control text-center" id="level_inherent_text" readonly="readonly" style="width:30%;'.$csslevel.'"').form_hidden(['level_inherent'=>($data)?$data['level_inherent']:0])];
		$param['analisa2'][] = ['title'=>_l('fld_nama_control'),'help'=>_h('help_nama_control'), 'mandatori'=>false,'isi'=>$kontrol];
		$param['analisa2'][] = ['title'=>_l('fld_efek_kontrol'),'help'=>_h('help_efek_kontrol'), 'mandatori'=>true,'isi'=>form_dropdown('efek_kontrol', $efek_control,  ($data)?$data['efek_kontrol']:'', 'id="efek_kontrol" class="form-control select" style="width:100%;"')];
		if ($data) {
			if ($data['lampiran']=='0') {
				$url = '#';
				$nmfile = 'Belum ada File';
			}else{
				$url = base_url().substr($data['lampiran'], 1);
				$nmfile = substr($data['lampiran'], 13);
			}
		}else{
			$url = '#';
			$nmfile = 'Belum ada File';
		}
		$param['analisa2'][] = ['title'=>_l('fld_upload'),'help'=>_h('help_upload'),'isi'=>'<a href="'.$url.'" target="_blank">'.$nmfile.'</a><br><br><b>Max size file 10MB, file yang dibolehkan xlsx | csv | docx | pdf | zip | rar </b><br>'];
		$param['analisa2'][] = ['title'=>_l('fld_lampiran'),'help'=>_h('help_lampiran'),'isi'=>form_upload('lampiran')];

		return $param;
	}
	
	function evaluasi_content($data=[]){
		$aspek =0;
		if($data){
			if($data['tipe_analisa_no'] == 2 || $data['tipe_analisa_no'] == 3){
				$aspek = $data['aspek_risiko_id'];
			}else{

				$aspek =0;
			}
		}
		if ($aspek){
			$like=$this->crud->combo_select(['urut', 'concat(urut,\' - \',data) as x'])->combo_where('active', 1)->combo_where('pid',$aspek)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		}else{
			$like=$this->crud->combo_select(['id', 'concat(code,\' - \',level) as x'])->combo_where('active', 1)->combo_where('category','likelihood')->combo_tbl(_TBL_LEVEL)->get_combo()->result_combo();
		}
		$impact=$this->crud->combo_select(['id', 'concat(code,\' - \',level) as x'])->combo_where('active', 1)->combo_where('category','impact')->combo_tbl(_TBL_LEVEL)->get_combo()->result_combo();
		$treatment=$this->crud->combo_select(['id', 'treatment'])->combo_where('active', 1)->combo_tbl(_TBL_TREATMENT)->get_combo()->result_combo();
		$efek_mitigasi=[0=>_l('cbo_select'),1=>'L',2=>'D',3=>'L & D',4=>'Tidak ada mitigasi'];

		$aspek_risiko=$this->crud->combo_select(['id', 'data'])->combo_where('active', 1)->combo_where('kelompok','aspek-risiko')->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$parent=$this->db->where('id', $data['rcsa_id'])->get(_TBL_VIEW_RCSA)->row_array();
		$csslevel='';
		$csslevel_inherent='';
		$control = '';
		if($data){
			$csslevel='background-color:'.$data['color_residual'].';color:'.$data['color_text_residual'].';';
			$csslevel_inherent='background-color:'.$data['color'].';color:'.$data['color_text'].';';

			if(!empty($data['nama_kontrol_note']) && !empty($data['nama_kontrol'])){
				$data['nama_kontrol'].='###'.$data['nama_kontrol_note'];
			}else{
				$data['nama_kontrol'].= $data['nama_kontrol_note'];
			}
			$y = explode('###',$data['nama_kontrol']);
			$control='<ul>';
			foreach($y as $x){
				$control.='<li>'.$x.'</li>';
			}
			// strip_tags($x, '<p><ol><ul><li>')
			$control.='</ul>';
		}
	
		$l_events='auto';
		$i_events='auto';
		if ($data['efek_kontrol']==1){
			$l=form_dropdown('like_residual_id', $like, ($data)?$data['like_residual_id']:'', 'id="like_residual_id" class="form-control select" style="width:100%;"');
			$i=form_input('impact_residual', ($data)?$data['impact_residual']:'', 'id="impact_residual" class="form-control" readonly="readonly" style="width:100%;"').form_input(['type'=>'hidden','name'=>'impact_residual_id','id'=>'impact_residual_id','value'=>($data)?$data['impact_residual_id']:0]);
			// $l3=form_dropdown('like_residual_id_3', $like, ($data)?$data['like_residual_id']:'', 'id="like_residual_id_3" class="form-control select" style="width:100%;"');
			// $i3=form_input('impact_residual_3', ($data)?$data['impact_residual']:'', 'id="impact_residual_3" class="form-control" readonly="readonly" style="width:100%;"').form_input(['type'=>'hidden','name'=>'impact_residual_id_3','id'=>'impact_residual_id_3','value'=>($data)?$data['impact_residual_id']:0]);
			$l_events='none';
		}elseif ($data['efek_kontrol']==2){
			$l=form_input('like_residual', ($data)?$data['like_residual']:'', 'id="like_residual" class="form-control" readonly="readonly" style="width:100%;"').form_input(['type'=>'hidden','name'=>'like_residual_id','id'=>'like_residual_id','value'=>($data)?$data['like_residual_id']:0]);
			$i=form_dropdown('impact_residual_id', $impact, ($data)?$data['impact_residual_id']:'', 'id="impact_residual_id" class="form-control select" style="width:100%;"');
			// $l3=form_input('like_residual_3', ($data)?$data['like_residual']:'', 'id="like_residual_3" class="form-control" readonly="readonly" style="width:100%;"').form_input(['type'=>'hidden','name'=>'like_residual_id_3','id'=>'like_residual_id_3','value'=>($data)?$data['like_residual_id']:0]);
			// $i3=form_dropdown('impact_residual_id_3', $impact, ($data)?$data['impact_residual_id']:'', 'id="impact_residual_id_3" class="form-control select" style="width:100%;"');
			$i_events='none';
		}elseif($data['efek_kontrol']==4){
			$l=form_input('like_residual', ($data)?$data['like_residual']:'', 'id="like_residual" class="form-control" readonly="readonly" style="width:100%;"').form_input(['type'=>'hidden','name'=>'like_residual_id','id'=>'like_residual_id','value'=>($data)?$data['like_residual_id']:0]);
			$i=form_input('impact_residual', ($data)?$data['impact_residual']:'', 'id="impact_residual" class="form-control" readonly="readonly" style="width:100%;"').form_input(['type'=>'hidden','name'=>'impact_residual_id','id'=>'impact_residual_id','value'=>($data)?$data['impact_residual_id']:0]);
			// $l3=form_dropdown('like_residual_id_3', $like, ($data)?$data['like_residual_id']:'', 'id="like_residual_id_3" class="form-control select" style="width:100%;"');
			// $i3=form_dropdown('impact_residual_id_3', $impact, ($data)?$data['impact_residual_id']:'', 'id="impact_residual_id_3" class="form-control select" style="width:100%;"');
			$l_events='none';

		}else{
			$l=form_dropdown('like_residual_id', $like, ($data)?$data['like_residual_id']:'', 'id="like_residual_id" class="form-control select" style="width:100%;"');
			$i=form_dropdown('impact_residual_id', $impact, ($data)?$data['impact_residual_id']:'', 'id="impact_residual_id" class="form-control select" style="width:100%;"');
			// $l3=form_dropdown('like_residual_id_3', $like, ($data)?$data['like_residual_id']:'', 'id="like_residual_id_3" class="form-control select" style="width:100%;"');
			// $i3=form_dropdown('impact_residual_id_3', $impact, ($data)?$data['impact_residual_id']:'', 'id="impact_residual_id_3" class="form-control select" style="width:100%;"');
		}

		if($data['tipe_analisa_no']==2){
			$param['evaluasi'][] = ['title'=>'','help'=>'','isi'=>'<span class="btn btn-primary legitRipple pointer" data-rcsa="'.$parent['id'].'" data-id="'.$data['id'].'" data-control="'.$data['efek_kontrol'].'" id="indikator_like_residual" style="width:100%;pointer-events:'.$i_events.'"> Input Risk Indikator Likelihood </span>'];

			$param['evaluasi'][] = ['title'=>'','help'=>'','isi'=>'<span class="btn btn-primary legitRipple pointer"  data-rcsa="'.$parent['id'].'" data-id="'.$data['id'].'" data-control="'.$data['efek_kontrol'].'" id="indikator_dampak_residual" style="width:100%;pointer-events:'.$l_events.'"> Input Risk Indikator Dampak </span>'];

			$param['evaluasi'][] = ['title'=>_l('fld_likelihood_residual'),'help'=>_h('help_likelihood'),'isi'=>form_input('like_text_kuantitatif_residual', ($data)?$data['like_residual']:'', 'id="like_text_kuantitatif_residual" class="form-control" style="width:100%;" readonly="readonly"').form_hidden(['like_residual_id'=>($data)?$data['like_residual_id']:''])];

			$param['evaluasi'][] = ['title'=>_l('fld_impact_residual'),'help'=>_h('help_impact_residual'),'isi'=>form_input('impact_text_kuantitatif_residual', ($data)?$data['impact_residual']:'', 'id="impact_text_kuantitatif_residual" class="form-control" style="width:100%;" readonly="readonly"').form_hidden(['impact_residual_id'=>($data)?$data['impact_residual_id']:''])];

			$param['evaluasi'][] = ['title'=>_l('fld_risiko_residual'),'help'=>_h('help_risiko_residual'),'isi'=>form_input('risiko_residual_text', ($data)?$data['risiko_residual_text']:'', 'class="form-control text-center" id="risiko_residual_text" readonly="readonly" style="width:15%;"').form_hidden(['risiko_residual'=>($data)?$data['risiko_residual']:0])];

			$param['evaluasi'][] = ['title'=>_l('fld_level_risiko_residual'),'help'=>_h('help_level_risiko'),'isi'=>form_input('level_residual_text', ($data)?$data['level_color_residual']:'', 'class="form-control text-center" id="level_residual_text" readonly="readonly" style="width:30%;'.$csslevel.'"').form_hidden(['level_residual'=>($data)?$data['level_residual']:0,'sts_save_evaluasi'=>($data)?$data['sts_save_evaluasi']:0])];
		}elseif($data['tipe_analisa_no']==1){
			$param['evaluasi'][] = ['title'=>_l('fld_likelihood_residual'),'help'=>_h('help_likelihood_residual'),'isi'=>$l];
			$param['evaluasi'][] = ['title'=>_l('fld_impact_residual'),'help'=>_h('help_impact_residual'),'isi'=>$i];
			$param['evaluasi'][] = ['title'=>_l('fld_risiko_residual'),'help'=>_h('help_risiko_residual'),'isi'=>form_input('risiko_residual_text', ($data)?$data['risiko_residual_text']:'', 'class="form-control text-center" id="risiko_residual_text" readonly="readonly" style="width:15%;"').form_hidden(['risiko_residual'=>($data)?$data['risiko_residual']:0])];
			$param['evaluasi'][] = ['title'=>_l('fld_level_risiko_residual'),'help'=>_h('help_level_risiko'),'isi'=>form_input('level_residual_text', ($data)?$data['level_color_residual']:'', 'class="form-control text-center" id="level_residual_text" readonly="readonly" style="width:30%;'.$csslevel.'"').form_hidden(['level_residual'=>($data)?$data['level_residual']:0,'sts_save_evaluasi'=>($data)?$data['sts_save_evaluasi']:0])];
		}elseif($data['tipe_analisa_no']==3){
			$param['evaluasi'][] = ['title'=>_l('fld_aspek_risiko'),'help'=>_h('help_aspek_risiko'),'isi'=>form_dropdown('aspek_risiko_id_3', $aspek_risiko, ($data)?$data['aspek_risiko_id']:'', 'id="aspek_risiko_id_3" class="form-control select" style="width:100%;" disabled="disabled"')];
			
			// $param['evaluasi'][] = ['title'=>_l('fld_likelihood_residual'),'help'=>_h('help_likelihood_residual'),'isi'=>$l];
			
			$urutTemp = [1,7,8,9,10];
			
			$like_semi_form = '<select name="like_residual_id" id="like_residual_id_3" class="form-control select" style="width:100%;">';
			if (!empty($like)) {
				
				foreach ($like as $key => $value) {
					$sel = ($data)?$data['like_residual_id']:'';
					$selected = ($sel==$key)?'selected':'';
					$k = intval($key)-1;
					$dataTemp = (isset($urutTemp[$k]))?$urutTemp[$k]:0;
					$like_semi_form .= '<option data-temp="'.$dataTemp.'" value="'.$key.'"'.$selected.'>'.$value.'</option>';
				}
			}
			$like_semi_form .= '</select>';

			$param['evaluasi'][] = ['title'=>_l('fld_likelihood_residual'),'help'=>_h('help_likelihood_residual'),'isi'=>$like_semi_form];

			// form_dropdown('like_id_3', $like_semi, ($data)?$data['like_id']:'', 'id="like_id_3" class="form-control select" style="width:100%;"')


			$param['evaluasi'][] = ['title'=>'','help'=>'','isi'=>'<span class="btn btn-primary legitRipple pointer"  data-rcsa="'.$parent['id'].'" data-id="'.$data['id'].'" data-control="'.$data['efek_kontrol'].'" id="indikator_dampak_residual" style="width:100%;pointer-events:'.$l_events.'"> Input Risk Indikator Dampak </span>'];

			// $param['evaluasi'][] = ['title'=>_l('fld_impact_residual'),'help'=>_h('help_impact_residual'),'isi'=>$i];

			$param['evaluasi'][] = ['title'=>_l('fld_impact_residual'),'help'=>_h('help_impact_residual'),'isi'=>form_input('impact_text_kuantitatif_residual', ($data)?$data['impact_residual']:'', 'id="impact_text_kuantitatif_residual" class="form-control" style="width:100%;" readonly="readonly"').form_hidden(['impact_residual_id'=>($data)?$data['impact_residual_id']:''])];
			

			$param['evaluasi'][] = ['title'=>_l('fld_risiko_residual'),'help'=>_h('help_risiko_residual'),'isi'=>form_input('risiko_residual_text', ($data)?$data['risiko_residual_text']:'', 'class="form-control text-center" id="risiko_residual_text" readonly="readonly" style="width:15%;"').form_hidden(['risiko_residual'=>($data)?$data['risiko_residual']:0])];
			$param['evaluasi'][] = ['title'=>_l('fld_level_risiko_residual'),'help'=>_h('help_level_risiko'),'isi'=>form_input('level_residual_text', ($data)?$data['level_color_residual']:'', 'class="form-control text-center" id="level_residual_text" readonly="readonly" style="width:30%;'.$csslevel.'"').form_hidden(['level_residual'=>($data)?$data['level_residual']:0,'sts_save_evaluasi'=>($data)?$data['sts_save_evaluasi']:0])];
		}
		$param['evaluasi'][] = ['title'=>_l('fld_treatment'),'help'=>_h('help_treatment'), 'mandatori'=>true,'isi'=>form_dropdown('treatment_id', $treatment, ($data)?$data['treatment_id']:'', 'class="form-control select" id="treatment_id" style="width:100%;"')];

		$param['evaluasi'][] = ['title'=>_l('fld_efek_mitigasi'),'help'=>_h('help_efek_mitigasi'), 'mandatori'=>true,'isi'=>form_dropdown('efek_mitigasi', $efek_mitigasi,  ($data)?$data['efek_mitigasi']:'', 'id="efek_mitigasi" class="form-control select" style="width:100%;"')];
		
		
		$param['info'][] = ['title'=>_l('fld_risiko_dept'),'isi'=>$data['risiko_dept']];
		$param['info'][] = ['title'=>_l('fld_level_risiko'),'isi'=>form_input('level_inherent_info', ($data)?$data['risiko_inherent_text']:'', 'class="form-control text-center" id="level_inherent_info" readonly="readonly"  style="width:40%;"').'<span class="input-group-append">
		<button class="btn btn-light legitRipple" type="button" style="'.$csslevel_inherent.'">'.$data['level_color'].'</button>
		</span>'];
		$param['info'][] = ['title'=>_l('fld_nama_control'),'isi'=>$control];
		$param['info'][] = ['title'=>_l('fld_efek_kontrol'),'isi'=>$data['efek_kontrol_text']];

		return $param;
	}

	function target_content($data=[]){
		$aspek =0;
		if($data){
			if($data['tipe_analisa_no'] == 2 || $data['tipe_analisa_no'] == 3){
				$aspek = $data['aspek_risiko_id'];
			}else{

				$aspek =0;
			}
		}
		if ($aspek){
			$like=$this->crud->combo_select(['urut', 'concat(urut,\' - \',data) as x'])->combo_where('active', 1)->combo_where('pid',$aspek)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		}else{
			$like=$this->crud->combo_select(['id', 'concat(code,\' - \',level) as x'])->combo_where('active', 1)->combo_where('category','likelihood')->combo_tbl(_TBL_LEVEL)->get_combo()->result_combo();
		}
		$aspek_risiko=$this->crud->combo_select(['id', 'data'])->combo_where('active', 1)->combo_where('kelompok','aspek-risiko')->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$impact=$this->crud->combo_select(['id', 'concat(code,\' - \',level) as x'])->combo_where('active', 1)->combo_where('category','impact')->combo_tbl(_TBL_LEVEL)->get_combo()->result_combo();
		$treatment=$this->crud->combo_select(['id', 'treatment'])->combo_where('active', 1)->combo_tbl(_TBL_TREATMENT)->get_combo()->result_combo();
		$parent=$this->db->where('id', $data['rcsa_id'])->get(_TBL_VIEW_RCSA)->row_array();
		
		$csslevel='';
		$csslevel_inherent='';
		if($data){
			$bg = ($data['color_target'])?$data['color_target']:$data['color_residual'];
			$clr = ($data['color_text_target'])?$data['color_text_target']:$data['color_text_residual'];
			$csslevel='background-color:'.$bg.';color:'.$clr.';';
			
			$csslevel_inherent='background-color:'.$data['color_residual'].';color:'.$data['color_text_residual'].';';
		}

		$y = explode('###',$data['nama_kontrol']);
		$control='';
		foreach($y as $x){
			$control.='- '.$x.'<br/>';
		}

		$l_events='auto';
		$i_events='auto';
		if ($data['efek_mitigasi']==1){
			$l=form_dropdown('like_target_id', $like, ($data['like_target_id'])?$data['like_target_id']:$data['like_residual_id'], 'id="like_target_id" class="form-control select" style="width:100%;"');
			$i=form_input('impact_target', ($data['impact_target'])?$data['impact_target']:$data['impact_residual'], 'id="impact_target" class="form-control" readonly="readonly" style="width:100%;"').form_input(['type'=>'hidden','name'=>'impact_target_id','id'=>'impact_target_id','value'=>($data['impact_target_id'])?$data['impact_target_id']:$data['impact_residual_id']]);
			$l_events='none';
		}elseif ($data['efek_mitigasi']==2){
			$l=form_input('like_target', ($data['like_target'])?$data['like_target']:$data['like_residual'], 'id="like_target" class="form-control" readonly="readonly" style="width:100%;"').form_input(['type'=>'hidden','name'=>'like_target_id','id'=>'like_target_id','value'=>($data['like_target_id'])?$data['like_target_id']:$data['like_residual_id']]);
			$i=form_dropdown('impact_target_id', $impact, ($data['impact_target_id'])?$data['impact_target_id']:$data['impact_residual_id'], 'id="impact_target_id" class="form-control select" style="width:100%;"');
			$i_events='none';
		}elseif ($data['efek_mitigasi']==4){
			$l=form_input('like_target', ($data['like_target'])?$data['like_target']:$data['like_residual'], 'id="like_target" class="form-control" readonly="readonly" style="width:100%;"').form_input(['type'=>'hidden','name'=>'like_target_id','id'=>'like_target_id','value'=>($data['like_target_id'])?$data['like_target_id']:$data['like_residual_id']]);
			$i=form_input('impact_target', ($data['impact_target'])?$data['impact_target']:$data['impact_residual'], 'id="impact_target" class="form-control" readonly="readonly" style="width:100%;"').form_input(['type'=>'hidden','name'=>'impact_target_id','id'=>'impact_target_id','value'=>($data['impact_target_id'])?$data['impact_target_id']:$data['impact_residual_id']]);
			$i_events='none';
		}else{
			$l=form_dropdown('like_target_id', $like, ($data['like_target_id'])?$data['like_target_id']:$data['like_residual_id'], 'id="like_target_id" class="form-control select" style="width:100%;"');
			$i=form_dropdown('impact_target_id', $impact, ($data['impact_target_id'])?$data['impact_target_id']:$data['impact_residual_id'], 'id="impact_target_id" class="form-control select" style="width:100%;"');
		}

		if($data['tipe_analisa_no']==2){
			$param['target'][] = ['title'=>'','help'=>'','isi'=>'<span class="btn btn-primary legitRipple pointer" data-rcsa="'.$parent['id'].'" data-id="'.$data['id'].'" data-control="'.$data['efek_kontrol'].'" id="indikator_like_target" style="width:100%;pointer-events:'.$i_events.'"> Input Risk Indikator Likelihood </span>'];

			$param['target'][] = ['title'=>'','help'=>'','isi'=>'<span class="btn btn-primary legitRipple pointer"  data-rcsa="'.$parent['id'].'" data-id="'.$data['id'].'" data-control="'.$data['efek_kontrol'].'" id="indikator_dampak_target" style="width:100%;pointer-events:'.$l_events.'"> Input Risk Indikator Dampak </span>'];
			$param['target'][] = ['title'=>_l('fld_likelihood_target'),'help'=>_h('help_likelihood'),'isi'=>form_input('like_text_kuantitatif_targetl', ($data)?$data['like_target']:'', 'id="like_text_kuantitatif_target" class="form-control" style="width:100%;" readonly="readonly"').form_hidden(['like_target_id'=>($data)?$data['like_target_id']:''])];

			$param['target'][] = ['title'=>_l('fld_impact_target'),'help'=>_h('help_impact_target'),'isi'=>form_input('impact_text_kuantitatif_target', ($data)?$data['impact_target']:'', 'id="impact_text_kuantitatif_target" class="form-control" style="width:100%;" readonly="readonly"').form_hidden(['impact_target_id'=>($data)?$data['impact_target_id']:''])];

			$param['target'][] = ['title'=>_l('fld_risiko_target'),'help'=>_h('help_risiko_target'),'isi'=>form_input('risiko_target_text', ($data['risiko_target_text'])?$data['risiko_target_text']:$data['risiko_residual_text'], 'class="form-control text-center" id="risiko_target_text" readonly="readonly" style="width:15%;"').form_hidden(['risiko_target'=>($data['risiko_target'])?$data['risiko_target']:$data['risiko_residual']])];
			$param['target'][] = ['title'=>_l('fld_level_risiko_target'),'help'=>_h('help_level_risiko'),'isi'=>form_input('level_target_text', ($data['level_color_target'])?$data['level_color_target']:$data['level_color_residual'], 'class="form-control text-center" id="level_target_text" readonly="readonly" style="width:30%;'.$csslevel.'"').form_hidden(['level_target'=>($data['level_target'])?$data['level_target']:$data['level_residual']])];
		}elseif($data['tipe_analisa_no']==1){
			$param['target'][] = ['title'=>_l('fld_likelihood_target'),'help'=>_h('help_likelihood_target'),'isi'=>$l];
			$param['target'][] = ['title'=>_l('fld_impact_target'),'help'=>_h('help_impact_target'),'isi'=>$i];
			$param['target'][] = ['title'=>_l('fld_risiko_target'),'help'=>_h('help_risiko_target'),'isi'=>form_input('risiko_target_text', ($data['risiko_target_text'])?$data['risiko_target_text']:$data['risiko_residual_text'], 'class="form-control text-center" id="risiko_target_text" readonly="readonly" style="width:15%;"').form_hidden(['risiko_target'=>($data['risiko_target'])?$data['risiko_target']:$data['risiko_residual']])];
			$param['target'][] = ['title'=>_l('fld_level_risiko_target'),'help'=>_h('help_level_risiko'),'isi'=>form_input('level_target_text', ($data['level_color_target'])?$data['level_color_target']:$data['level_color_residual'], 'class="form-control text-center" id="level_target_text" readonly="readonly" style="width:30%;'.$csslevel.'"').form_hidden(['level_target'=>($data['level_target'])?$data['level_target']:$data['level_residual']])];
		}elseif($data['tipe_analisa_no']==3){
			$param['target'][] = ['title'=>_l('fld_aspek_risiko'),'help'=>_h('help_aspek_risiko'),'isi'=>form_dropdown('aspek_risiko_id_3', $aspek_risiko, ($data)?$data['aspek_risiko_id']:'', 'id="aspek_risiko_id_3" class="form-control select" style="width:100%;" disabled="disabled"')];
			

			$urutTemp = [1,7,8,9,10];
			
			$like_semi_form = '<select name="like_target_id" id="like_target_id_3" class="form-control select" style="width:100%;">';
			if (!empty($like)) {
				
				foreach ($like as $key => $value) {
					$sel = ($data)?$data['like_target_id']:'';
					$selected = ($sel==$key)?'selected':'';
					$k = intval($key)-1;
					$dataTemp = (isset($urutTemp[$k]))?$urutTemp[$k]:0;
					$like_semi_form .= '<option data-temp="'.$dataTemp.'" value="'.$key.'"'.$selected.'>'.$value.'</option>';
				}
			}
			$like_semi_form .= '</select>';

			$param['target'][] = ['title'=>_l('fld_likelihood_target'),'help'=>_h('help_likelihood_target'),'isi'=>$like_semi_form];

			// $param['target'][] = ['title'=>_l('fld_likelihood_target'),'help'=>_h('help_likelihood_target'),'isi'=>$l];

			// $l_events='auto';

			$param['target'][] = ['title'=>'','help'=>'','isi'=>'<span class="btn btn-primary legitRipple pointer"  data-rcsa="'.$parent['id'].'" data-id="'.$data['id'].'" data-control="'.$data['efek_kontrol'].'" id="indikator_dampak_target" style="width:100%;pointer-events:'.$l_events.'"> Input Risk Indikator Dampak </span>'];
			
			// $param['target'][] = ['title'=>_l('fld_impact_target'),'help'=>_h('help_impact_target'),'isi'=>$i];

			$param['target'][] = ['title'=>_l('fld_impact_target'),'help'=>_h('help_impact_target'),'isi'=>form_input('impact_text_kuantitatif_target', ($data)?$data['impact_target']:'', 'id="impact_text_kuantitatif_target" class="form-control" style="width:100%;" readonly="readonly"').form_hidden(['impact_target_id'=>($data)?$data['impact_target_id']:''])];


			$param['target'][] = ['title'=>_l('fld_risiko_target'),'help'=>_h('help_risiko_target'),'isi'=>form_input('risiko_target_text', ($data['risiko_target_text'])?$data['risiko_target_text']:$data['risiko_residual_text'], 'class="form-control text-center" id="risiko_target_text" readonly="readonly" style="width:15%;"').form_hidden(['risiko_target'=>($data['risiko_target'])?$data['risiko_target']:$data['risiko_residual']])];
			$param['target'][] = ['title'=>_l('fld_level_risiko_target'),'help'=>_h('help_level_risiko'),'isi'=>form_input('level_target_text', ($data['level_color_target'])?$data['level_color_target']:$data['level_color_residual'], 'class="form-control text-center" id="level_target_text" readonly="readonly" style="width:30%;'.$csslevel.'"').form_hidden(['level_target'=>($data['level_target'])?$data['level_target']:$data['level_residual']])];
		}

		// $param['target'][] = ['title'=>_l('fld_treatment'),'help'=>_h('help_treatment'),'isi'=>form_dropdown('treatment_id', $treatment, ($data)?$data['treatment_id']:'', 'class="form-control select" id="treatment_id" style="width:100%;"')];

		$param['info'][] = ['title'=>_l('fld_risiko_dept'),'isi'=>$data['risiko_dept']];
		$param['info'][] = ['title'=>_l('fld_level_risiko_residual'),'isi'=>form_input('level_target_info', ($data)?$data['risiko_residual_text']:'', 'class="form-control text-center" id="level_target_info" readonly="readonly"  style="width:40%;"').'<span class="input-group-append">
		<button class="btn btn-light legitRipple" type="button" style="'.$csslevel_inherent.'">'.$data['level_color_residual'].'</button>
		</span>'];
		// $param['info'][] = ['title'=>_l('fld_mitigasi'),'isi'=>$control];
		$param['info'][] = ['title'=>_l('fld_efek_mitigasi'),'isi'=>$data['efek_mitigasi_text']];

		return $param;
	}

	function save_modul(){
		$post=explode(",",$this->input->post('id'));
		$ori=explode(",",$this->input->post('dtori'));
		// $period=$this->input->post('period');
		// $term=$this->input->post('term');
		$is_admin=$this->input->post('is_admin');
		$owner=$this->input->post('owner');
		$result = $this->data->simpan_data($post, $owner, $ori);
	
		echo json_encode($result);
	}

	function dashboard()
	{
		
		$x = $this->session->userdata('periode');
		$tgl1=date('Y-m-d');
		$tgl2=date('Y-m-d');
		if ($x){
			// $tgl1=$x['tgl_awal'];
			// $tgl2=$x['tgl_akhir'];
			$tgl1=$x['period'].'-01-01';
			$tgl2=$x['period'].'-12-31';
		}
		if ($this->input->post()) {
			$this->pos=$this->input->post();
		}else{
			$this->pos=[
				'term_mulai' => $tgl1,
				'term_akhir' => $tgl2,
			];
		}
		$data=$this->map();
	
		$this->super_user = intval($this->_data_user_['is_admin']);
		$this->ownerx = intval(($this->super_user==0)?$this->_data_user_['owner_id']:0);

		$data['post'] = $this->pos;
		$data['owner']=$this->get_combo_parent_dept();
		$data['dtowner']=$this->ownerx;
		$data['type_ass']=$this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'ass-type')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();

		$data['period']=$this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'period')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();

		$data['term']=$this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'term')->combo_where('pid', _TAHUN_ID_)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();

		$data['minggu']=$this->crud->combo_select(['id', 'concat(param_string, \' ( \', param_date, \' s.d \', param_date_after, \' ) \') as minggu'])->combo_where('kelompok', 'minggu')
		// ->combo_where('param_date>=', $tgl1)->combo_where('param_date<=', $tgl2)
		->combo_where('pid', _TAHUN_ID_)
		->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		// $data['minggu']=$this->crud->combo_select(['id', 'concat(param_string,\' minggu ke - \',data, \' ( \', param_date, \' s.d \', param_date_after, \' ) \') as minggu'])->combo_where('kelompok', 'minggu')->combo_where('param_date>=', $tgl1)->combo_where('param_date<=', $tgl2)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		// die($this->db->last_query());
	

		$this->data->pos['tgl1']=$tgl1;
		$this->data->pos['tgl2']=$tgl2;
		$this->data->pos['owner']=$this->ownerx;
		$this->data->pos['type_ass']=0;
		$this->data->pos['period']=_TAHUN_ID_;
		$this->data->pos['term']=_TERM_ID_;
		$this->data->pos['minggu']=_MINGGU_ID_;

		$hasil=$this->load->view('dashboard',$data, true);
	

		$configuration = [
			'show_title_header' => false,
			'show_action_button' => false,
			'show_header_content' => false,
			'box_content' => false,
		];

		if ($this->input->is_ajax_request()){
			echo json_encode(['combo'=>$hasil]);
		}else{
			$this->default_display(['content'=>$hasil, 'configuration'=>$configuration]);
		}
	}

	function map(){
		
		$this->data->filter_data($this->_data_user_);
		// -- COUNT(risiko_inherent) as nilai,
		$rows = $this->db->SELECT('risiko_inherent as id, level_color, level_color_residual, level_color_target, minggu_id')
		// ->group_by('risiko_inherent')
		// ->get_compiled_select(_TBL_VIEW_RCSA_DETAIL);
		->get(_TBL_VIEW_RCSA_DETAIL)->result_array();
	
		$data['map_inherent']=$this->map->set_data_profile($rows, $this->pos)->set_param(['tipe'=>'angka', 'level'=>1])->draw_profile();

		
		$jml=$this->map->get_total_nilai();
		$jmlstatus=$this->map->get_jumlah_status();
		$data['jml_inherent_status']=$jmlstatus;
		$data['jml_inherent']='';
		if ($jml>0){
			$data['jml_inherent']='<span class="badge bg-primary badge-pill"> '.$jml.' </span>';
		}
		$this->data->filter_data($this->_data_user_);
		// COUNT(risiko_residual) as nilai,
		$rows = $this->db->SELECT('risiko_residual as id,  level_color, level_color_residual, level_color_target, minggu_id')
		// ->group_by('risiko_residual')
		->get(_TBL_VIEW_RCSA_DETAIL)->result_array();
		$data['map_residual']=$this->map->set_data_profile($rows, $this->pos)->set_param(['tipe'=>'angka', 'level'=>2])->draw_profile();

		$jml=$this->map->get_total_nilai();
		$jmlstatus=$this->map->get_jumlah_status();
		$data['jml_residual_status']=$jmlstatus;
		$data['jml_residual']='';
		if ($jml>0){
			$data['jml_residual']='<span class="badge bg-success badge-pill"> '.$jml.' </span>';
		}
		$this->data->filter_data($this->_data_user_);
		// COUNT(risiko_target) as nilai
		$rows = $this->db->SELECT('risiko_target as id,  level_color, level_color_residual, level_color_target, minggu_id')
		// ->group_by('risiko_target')
		->get(_TBL_VIEW_RCSA_DETAIL)->result_array();
		$data['map_target']=$this->map->set_data_profile($rows, $this->pos)->set_param(['tipe'=>'angka', 'level'=>3])->draw_profile();

		$jml=$this->map->get_total_nilai();
		$jmlstatus=$this->map->get_jumlah_status();
		$data['jml_target_status']=$jmlstatus;
		$data['jml_target']='';
		if ($jml>0){
			$data['jml_target']='<span class="badge bg-warning badge-pill"> '.$jml.' </span>';
		}

		return $data;
	}

	function get_map(){
		$this->pos=$this->input->post();
		$this->data->pos=$this->pos;
		$this->data->owner_child=[];
		$this->data->owner_child[]=intval($this->pos['owner']);
		$this->data->get_owner_child(intval($this->pos['owner']));
		$this->owner_child=$this->data->owner_child;
		// dumps($this->data->get_data_map($this->_data_user_));
		// die();
		$data=$this->map();
		$hasil['combo']=$this->load->view('map',$data, true);
		$x=$this->data->get_data_map($this->_data_user_);
		$x['post']=$this->pos;
		$x['minggu']=$this->crud->combo_select(['id', 'concat(param_string, \' ( \', param_date, \' s.d \', param_date_after, \' ) \') as minggu'])->combo_where('kelompok', 'minggu')->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();

		// $aw = (isset($x['minggu'][$x['post']['term_mulai']]))?$x['minggu'][$x['post']['term_mulai']]:'';
		// $ak = (isset($x['minggu'][$x['post']['term_akhir']]))?$x['minggu'][$x['post']['term_akhir']]:'';
		
		$hasil['range']= 'Jan - Des';
	
		$hasil['detail_list']=$this->load->view('identifikasi', $x, true);

		$det = $this->data->get_detail_data($this->_data_user_);
		$det['mode']=0;

		$hasil['kpi'] = $this->load->view('detail', $det, true);

		$y=$this->data->get_data_kompilasi($this->pos['period'],$this->pos['owner'],$this->pos['type_ass'], $this->_data_user_);
		$y['pos'] = $this->pos;
	
		$hasil['progress']=$this->hasil=$this->load->view('monitoring',$y, true);


		echo json_encode($hasil);
	}

	function get_detail_map(){
		$post = $this->input->post();
		$this->data->pos=$post;
		$x=$this->data->get_data_map();
		$hasil['combo']=$this->load->view('ajax/identifikasi', $x, true);
		echo json_encode($hasil);
	}

	function get_monitoring(){
		$id = $this->input->post('id');
		$rcsa = $this->input->post('rcsa');
		$data=$this->data->get_data_monitoring_profil($id, $rcsa);
		$data['id']=$id;
		$x['combo']=$this->load->view('risk_context/monitoring', $data, true);
		echo json_encode($x);
	}
}