<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Cause_Library extends MY_Controller {
	var $type_risk=0;
	var $risk_type=[];
	public function __construct() {
		parent::__construct();
	}

	function init($action='list'){
		$this->type_risk=1;
		$this->kel=$this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'lib-cat')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$this->cbo_risk_type=$this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'risk-type')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$this->cbo_status=$this->crud->combo_value([1=>'aktif', 2=>'tidak aktif'])->result_combo();

		$this->set_Tbl_Master(_TBL_VIEW_LIBRARY);
		// tbl_master dr set_Tbl_Master tabel view _TBL_VIEW_LIBRARY
		// menu tambah
		$this->set_Open_Tab('Data Risk Event Library');
			$this->addField(['field'=>'id', 'type'=>'int', 'show'=>false, 'size'=>4]);
			// $this->addField(['field'=>'kel', 'save'=>false, 'input'=>'combo', 'search'=>true, 'values'=>$this->kel, 'size'=>50]);
			// $this->addField(['field'=>'risk_type_no','title'=>'Tipe Risiko', 'type'=>'int', 'input'=>'combo', 'search'=>true, 'values'=>[' - Pilih - '], 'size'=>50]);
			$this->addField(['field'=>'library', 'title'=>'Penyebab Risiko', 'input'=>'multitext', 'search'=>true, 'size'=>500]);
			$this->addField(['field'=>'jml_couse', 'title'=>'Jml Event', 'type'=>'free', 'show'=>false, 'search'=>false]);
			// custom tampilan
			$this->addField(['field'=>'jml_impact', 'type'=>'free', 'show'=>false, 'search'=>false]);
			$this->addField(['field'=>'used', 'type'=>'free', 'show'=>false, 'search'=>false]);
			// $this->addField(['field'=>'cause', 'title'=>'Peristiwa', 'type'=>'free', 'search'=>false, 'mode'=>'o']);
			// $this->addField(['field'=>'impact',  'title'=>'Dampak','type'=>'free', 'search'=>false, 'mode'=>'o']);
			$this->addField(['field'=>'nama_kelompok', 'show'=>false]);
			$this->addField(['field'=>'risk_type', 'show'=>false]);
			$this->addField(['field'=>'created_by', 'show'=>false]);
			$this->addField(['field'=>'type', 'type'=>'int', 'default'=>$this->type_risk, 'show'=>false, 'save'=>true]);
			$this->addField(['field'=>'active', 'title'=>'Status', 'type'=>'int', 'input'=>'combo', 'values'=>$this->cbo_status, 'default'=>1, 'size'=>40]);

		$this->set_Close_Tab();

		$this->set_Field_Primary($this->tbl_master, 'id');
		$this->set_Join_Table(['pk'=>$this->tbl_master]);

		$this->set_Sort_Table($this->tbl_master,'id');
		$this->set_Where_Table(['tbl'=>$this->tbl_master, 'field'=>'type', 'op'=>'=', 'value'=>$this->type_risk]);
		// list tabel 
		// $this->set_Table_List($this->tbl_master,'nama_kelompok', 'Klasifikasi Risiko');
		// $this->set_Table_List($this->tbl_master,'risk_type', 'Tipe Risiko');
		$this->set_Table_List($this->tbl_master,'library');
		// $this->set_Table_List($this->tbl_master,'jml_couse', 'Jml Peristiwa', 10, 'center');
		// $this->set_Table_List($this->tbl_master,'jml_impact', 'Jml Dampak', 10, 'center');
		$this->set_Table_List($this->tbl_master,'used', ' Digunakan', 10, 'center');
		$this->set_Table_List($this->tbl_master,'created_by', 'Disusun oleh');
		$this->set_Table_List($this->tbl_master,'active', 'Status');
		$this->set_Close_Setting();

		$this->set_Save_Table(_TBL_LIBRARY);
		$configuration = [
			'show_title_header' => false,
			'content_title' =>'Risk Cause Library List'
		];
		return [
			'configuration'	=> $configuration
		];
	}

	function MASTER_DATA_LIST($id, $field){
		// dumps(implode(',',$id));
		// $rows = $this->db->get(_TBL_VIEW_RISK_TYPE)->result_array();
		// $this->risk_type=[];
		// foreach($rows as $row){
		// 	$this->risk_type[$row['id']]=$row['kelompok'];
		// }
		if ($id)
			$this->data->cari_total_dipakai($id);
	}
	// mengubah inputan
	function inputBox_RISK_TYPE_NO($mode, $field, $rows, $value){
		if ($mode=='edit'){
			$id=0;
			if (isset($rows['risk_type_no']))
			$id=$rows['risk_type_no'];
			$rows = $this->db->where('id', $id)->get(_TBL_COMBO)->row_array();
			$x=0;
			if ($rows){$x = $rows['pid'];}
			$field['values'] = $this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'risk-type')->combo_where('pid', $x)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		
		}
		$content = $this->set_box_input($field, $value);
		return $content;
	}

	function inputBox_KEL($mode, $field, $rows, $value){
		if ($mode=='edit'){
			$id=0;
			if (isset($rows['risk_type_no']))
				$id=$rows['risk_type_no'];
			$rows = $this->db->where('id', $id)->get(_TBL_VIEW_RISK_TYPE)->row_array();

			if ($rows){$value = $rows['pid'];}
		}
		$content = $this->set_box_input($field, $value);
		return $content;
	}

	function inputBox_CODE($mode, $field, $rows, $value){
		$content = form_input($field['label'],$value," size='{$field['size']}' class='form-control'  id='{$field['label']}' readonly='readonly' ");
		return $content;
	}

	function inputBox_CAUSE($mode, $field, $rows, $value){
		$content = $this->get_cause();
		return $content;
	}

	function get_cause()
	{
		$id=intval($this->uri->segment(3));
		$data=$this->data->get_library($id, 2);
		$data['angka']="10";
		$data['cbogroup']=$this->crud->combo_select(['id', 'library'])->combo_where('type', 2)->combo_where('active', 1)->combo_tbl(_TBL_LIBRARY)->get_combo()->result_combo();

		$result=$this->load->view('cause',$data,true);
		return $result;
	}

	function inputBox_IMPACT($mode, $field, $rows, $value){
		$content = $this->get_impact();
		return $content;
	}

	function get_impact()
	{
		$id=intval($this->uri->segment(3));
		$data=$this->data->get_library($id, 3);
		$data['angka']="10";
		$data['cbogroup']=$this->crud->combo_select(['id', 'library'])->combo_where('type', 3)->combo_where('active', 1)->combo_tbl(_TBL_LIBRARY)->get_combo()->result_combo();
		$result=$this->load->view('impact',$data,true);
		return $result;
	}

	function subDelete_PROCESSOR($param){
		$this->crud->crud_data(['table'=>_TBL_LIBRARY_DETAIL, 'where'=>['id'=>$param['iddel']],'type'=>'delete']);
		$hasil['ket'] ="Data berhasil di hapus!";
		$hasil['sts'] =true;
		return $hasil;
	}

	function listBox_JML_COUSE($field, $row, $value){
		$result='';
		$value=$this->data->get_child($row['id'], 2);
		if ($value>0)
			$result =  '<span class="badge bg-info">' . $value . '</span>';
		return $result;
	}

	function listBox_JML_IMPACT($field, $row, $value){
		$result='';
		$value=$this->data->get_child($row['id'], 3);
		if ($value>0)
			$result =  '<span class="badge bg-info">' . $value . '</span>';
		return $result;
	}

	function listBox_USED($field, $row, $value){
		$result='';
		$value=$this->data->get_used($row['id']);
		if ($value>0)
			$result =  '<span class="badge bg-success detail-used pointer" data-id="'.$row['id'].'" title="klik untuk melihat detail">' . $value . '</span>';
		return $result;
	}

	function get_library()
    {
        $nilKel = $this->input->post('kel');
       	$nmTbl = _TBL_VIEW_LIBRARY;
        $this->db->where('type', $nilKel);

        $data['field'] = $this->db->get($nmTbl)->result_array();
        $kl = '-';
        if ($nilKel == 2) {
            $kl = 'Cause';
        } elseif ($nilKel == 3) {
            $kl = 'Impact';
        }
        $data['kel'] = $kl;
        $data['event_no'] = 0;
        $rok = $this->db->where('active', 1)->order_by('kelompok, type_name')->get(_TBL_VIEW_RISK_TYPE)->result_array();
        $arrayX = ['- Pilih-'];
        foreach ($rok as $x) {
            $kel = "EXTERNAL";
            if ($x['kelompok'] == 77) {
                $kel = "INTERNAL";
            }
            $arrayX[$kel][$x['id']] = $x['type_name'];
        }
        $data['nilKel'] = $nilKel;
        $data['cboTypeLibrary'] = $arrayX;
        $hasil['library'] = $this->load->view('list-library', $data, true);
        $hasil['title'] = "List " . $data['kel'];
		header('Content-type: application/json');
        echo json_encode($hasil);
	}

	function simpan_library()
    {
        $post = $this->input->post();
        $upd['library'] = $post['library'];
        $upd['risk_type_no'] = $post['jenis_resiko'];
        $upd['type'] = $post['kel'];
        $upd['active'] = 1;
		$upd['created_by'] = $this->ion_auth->get_user_name();
		
		$this->db->insert(_TBL_LIBRARY,$upd);
        // $this->crud->crud_data(['table' => _TBL_LIBRARY, 'field' => $upd, 'type' => 'add']);
        $id = $this->crud->last_id();

        $data['id'] = $id;
        $data['kel'] = $post['kel'];
        $data['event'] = $post['library'];
		header('Content-type: application/json');
        echo json_encode($data);
	}

	// function afterSave($id , $new_data, $old_data, $mode){
	// 	$result = $this->data->save_library($id , $new_data);
	// 	return $result;
	// }
	// buten per list / action 
	function optionalPersonalButton($button, $row){
		
		$v1=$this->data->get_child($row['id'], 2);
		$v2=$this->data->get_child($row['id'], 3);
		$v3=$this->data->get_used($row['id']);

		if ($v1>0 || $v2>0 || $v3>0){
			unset($button['delete']);
		}
		return $button;
	}

}