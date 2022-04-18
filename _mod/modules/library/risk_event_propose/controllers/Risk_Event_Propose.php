<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Risk_Event_Propose extends BackendController {
	var $type_risk=0;
	var $risk_type=[];
	public function __construct() {
        parent::__construct();
		ini_set('max_execution_time', 300); //300 seconds = 5 minutes
		ini_set('memory_limit', '-1');
		$this->type_risk=1;
		$this->type=array(0=>' - ', 1=>'Event', 2=>'Cause', 3=>'Impact');
		$this->kel=$this->get_combo('data-combo','kel-library');
		$this->cbo_risk_type=$this->get_combo('risk_type');
		$this->cbo_status = [1=>'aktif', 0=>'tidak aktif'];
		
		$this->set_Tbl_Master(_TBL_LIBRARY);
		$this->set_Table(_TBL_RISK_TYPE);
		
		$this->set_Open_Tab('Data Risk Event Library');
			$this->addField(array('field'=>'id', 'type'=>'int', 'show'=>false, 'size'=>4));
			$this->addField(array('field'=>'kel', 'type'=>'free', 'input'=>'combo', 'combo'=>$this->kel, 'size'=>50));
			$this->addField(array('field'=>'risk_type_no', 'type'=>'int', 'input'=>'combo', 'combo'=>[], 'size'=>50));
			// $this->addField(array('field'=>'code', 'search'=>true, 'size'=>50));
			$this->addField(array('field'=>'description', 'input'=>'multitext', 'search'=>true, 'size'=>500));
			$this->addField(array('field'=>'notes', 'input'=>'multitext', 'search'=>true, 'size'=>500));
			$this->addField(array('field'=>'jml_couse', 'title'=>'Jml Cause', 'type'=>'free', 'show'=>false, 'search'=>true));
			$this->addField(array('field'=>'jml_impact', 'type'=>'free', 'show'=>false, 'search'=>true));
			// $this->addField(array('field'=>'cause', 'type'=>'free', 'search'=>true, 'mode'=>'o'));
			// $this->addField(array('field'=>'impact', 'type'=>'free', 'search'=>true, 'mode'=>'o'));
			$this->addField(array('field'=>'create_user', 'show'=>false));
			$this->addField(array('field'=>'create_date', 'show'=>false));
			$this->addField(array('field'=>'type', 'show'=>false, 'show'=>false, 'save'=>false));
			$this->addField(array('field'=>'status', 'type'=>'int', 'input'=>'combo', 'combo'=>$this->cbo_status, 'default'=>1, 'size'=>40));
		$this->set_Close_Tab();
			
		$this->set_Field_Primary('id');
		$this->set_Join_Table(array('pk'=>$this->tbl_master));
	
		$this->set_Sort_Table($this->tbl_master,'create_user');
		$this->set_Sort_Table($this->tbl_master,'create_date');
		$this->set_Where_Table($this->tbl_master, 'status', '=', 0);
		
		$this->set_Table_List($this->tbl_master,'type');
		$this->set_Table_List($this->tbl_master,'kel');
		$this->set_Table_List($this->tbl_master,'risk_type_no');
		// $this->set_Table_List($this->tbl_master,'code');
		$this->set_Table_List($this->tbl_master,'description');
		// $this->set_Table_List($this->tbl_master,'notes');
		$this->set_Table_List($this->tbl_master,'jml_couse', '', 10, 'center');
		$this->set_Table_List($this->tbl_master,'jml_impact', '', 10, 'center');
		$this->set_Table_List($this->tbl_master,'create_user');
		$this->set_Table_List($this->tbl_master,'create_date');
		// $this->set_Table_List($this->tbl_master,'status');
		// $this->_CHECK_PRIVILEGE_OWNER($this->tbl_master, 'owner_no');

		$this->_SET_PRIVILEGE('add', false);
       
		$this->set_Close_Setting();
	}
	
	function MASTER_DATA_LIST($id, $field){
		$rows = $this->db->get(_TBL_RISK_TYPE)->result_array();
		$this->risk_type=[];
		foreach($rows as $row){
			$this->risk_type[$row['id']]=$row['kelompok'];
		}
	}
	
	function listBox_RISK_TYPE_NO($rows, $value){
		$value = $rows['l_risk_type_no'];
		$hasil=$value;
		if (array_key_exists($value, $this->cbo_risk_type))
			$hasil=$this->cbo_risk_type[$value];
		return $hasil;
	}
	
	function updateBox_RISK_TYPE_NO($field, $rows, $value){
		$id=$rows['l_risk_type_no'];
		$rows = $this->db->where('id', $id)->get(_TBL_RISK_TYPE)->row_array();
		$x=0;
		if ($rows){$x = $rows['kelompok'];}
		$field['input']['combo'] = $this->get_combo('risk_type', $x);
		$content = $this->add_Box_Input('combo', $field, $value);
		return $content;
	}

	function listBox_TYPE($rows, $value){
		$hasil='';
		if (array_key_exists($value, $this->type))
			$hasil=$this->type[$value];
		return $hasil;
	}


	function listBox_KEL($rows, $value){
		$value = $rows['l_risk_type_no'];
		$hasil='';
		if (array_key_exists($value, $this->risk_type))
			$hasil=$this->kel[$this->risk_type[$value]];
		return $hasil;
	}
	
	function updateBox_KEL($field, $rows, $value){
		$id=$rows['l_risk_type_no'];
		$rows = $this->db->where('id', $id)->get(_TBL_RISK_TYPE)->row_array();
		
		if ($rows){$value = $rows['kelompok'];}
				
		$content = $this->add_Box_Input('combo', $field, $value);
		return $content;
	}
	
	function insertBox_CODE($field){
		$content = form_input($field['label'],' '," size='{$field['size']}' class='form-control'  id='{$field['label']}' readonly='readonly' ");
		return $content;
	}
	
	function updateBox_CODE($field, $row, $value){
		$content = form_input($field['label'],$value," size='{$field['size']}' class='form-control'  id='{$field['label']}' readonly='readonly' ");
		return $content;
	}
	
	public function index() {	
		$this->data_fields['dat_edit']['fields']=$this->post;
		$this->data_fields['search']=$this->load->view('statis/tmp_search',$this->data_fields,true);	
		$this->_param_list_['content']=$this->load->view('statis/tmp_table',$this->data_fields,true);
		$this->template->build('statis/table',$this->_param_list_); 
	}
	
	function insertBox_CAUSE($field){
		$content = $this->get_cause();
		return $content;
	}
	
	function updateBox_CAUSE($field, $row, $value){
		$content = $this->get_cause();
		return $content;
	}
	
	function get_cause()
	{
		$id=intval($this->uri->segment(3));
		$data=$this->data->get_library($id, 2);
		$data['angka']="10";
		$data['cbogroup']=$this->get_combo('library', 2);
		$result=$this->load->view('cause',$data,true);
		return $result;
	}
	
	function insertBox_IMPACT($field){
		$content = $this->get_impact();
		return $content;
	}
	
	function updateBox_IMPACT($field, $row, $value){
		$content = $this->get_impact();
		return $content;
	}
	
	function get_impact()
	{
		$id=intval($this->uri->segment(3));
		$data=$this->data->get_library($id, 3);
		$data['angka']="10";
		$data['cbogroup']=$this->get_combo('library', 3);
		$result=$this->load->view('impact',$data,true);
		return $result;
	}
	
	function listBox_STATUS($row, $value){
		if ($value=='1')
			$result='<span class="label label-success"> Aktif</span>';
		else
			$result='<span class="label label-warning"> Off</span>';
		
		return $result;
	}
	
	function POST_INSERT_PROCESSOR($id , $new_data){
		$result = $this->data->save_library($id , $new_data);
		return $result;
	}
	
	function POST_UPDATE_PROCESSOR($id , $new_data, $old_data){
		$result = $this->data->save_library($id , $new_data);
		return $result;
	}
	
	function POST_CHECK_BEFORE_UPDATEx($new_data, $old_data){
		$result=true;
		if ($old_data['l_code'] !== $new_data['l_code'])
		{
			$result = $this->crud->cek_double_data_library($new_data['l_code'], 1);
		}
		return $result;
	}
	
	function POST_CHECK_BEFORE_DELETE($ids=array()){
		$ada=false;
		// Doi::dump($ids);die();
		foreach($ids as $row){
			$value=$this->data->cari_total_dipakai($row);
			if ($value['jml']>0){
				$this->_set_pesan('Event : ' . $value['nama_lib']);
				$ada=true;
			}
		}
		if ($ada)
			$this->_set_pesan('Tidak bisa dihapus');
		
		return !$ada;
	}
	
	function subDelete_PROCESSOR($param){
		$this->crud->crud_data(array('table'=>_TBL_LIBRARY_DETAIL, 'where'=>array('id'=>$param['iddel']),'type'=>'delete'));
		$hasil['ket'] ="Data berhasil di hapus!";
		$hasil['sts'] =true;
		return $hasil;
	}
	
	function listBox_JML_COUSE($row, $value){
		$result='';
		$value=$this->data->cari_total_dipakai($row['l_id']);
		if ($value['jmlCouse']>0)
			$result =  '<span class="badge bg-info">' . $value['jmlCouse'] . '</span>';
		return $result;
	}
	
	function listBox_JML_IMPACT($row, $value){
		$result='';
		$value=$this->data->cari_total_dipakai($row['l_id']);
		if ($value['jmlImpact']>0)
			$result =  '<span class="badge bg-info">' . $value['jmlImpact'] . '</span>';
		return $result;
	}
}