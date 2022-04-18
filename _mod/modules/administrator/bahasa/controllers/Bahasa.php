<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Bahasa extends MX_Controller {
	var $table="";
	var $post=array();
	var $sts_cetak=false;
	public function __construct()
	{
        parent::__construct();
		$this->tbl_master=$this->db->dbprefix('bahasa');
		$this->tmp_data['fields'][]=array('nmtbl'=>$this->tbl_master, 'field'=>'id', 'title'=>'id', 'input'=>array('type'=>'int','input'=>'text'), 'show'=>false, 'required'=>false, 'search'=>false, 'help'=>false, 'size'=>4, 'label'=>'l_id');
		$this->tmp_data['fields'][]=array('nmtbl'=>$this->tbl_master, 'field'=>'key', 'title'=>'Key', 'input'=>array('type'=>'string','input'=>'text'), 'show'=>true, 'required'=>true, 'search'=>true, 'help'=>true, 'size'=>50, 'label'=>'l_key');
		$this->tmp_data['fields'][]=array('nmtbl'=>$this->tbl_master, 'field'=>'title', 'title'=>'Title', 'input'=>array('type'=>'string','input'=>'string'), 'show'=>true, 'required'=>false, 'search'=>true, 'help'=>true, 'size'=>50, 'label'=>'l_title');
		$this->tmp_data['fields'][]=array('nmtbl'=>$this->tbl_master, 'field'=>'icon', 'title'=>'Icon', 'input'=>array('type'=>'string','input'=>'string'), 'show'=>true, 'required'=>false, 'search'=>true, 'help'=>true, 'size'=>20, 'label'=>'l_icon');
		$this->tmp_data['fields'][]=array('nmtbl'=>$this->tbl_master, 'field'=>'is_default', 'title'=>'Default', 'input'=>array('type'=>'boolean','input'=>'boolean'), 'show'=>true, 'required'=>false, 'search'=>true, 'help'=>true, 'size'=>60, 'label'=>'l_is_default');
		$this->tmp_data['fields'][]=array('nmtbl'=>$this->tbl_master, 'field'=>'status', 'title'=>'Status', 'input'=>array('type'=>'boolean','input'=>'boolean'), 'show'=>true, 'required'=>false, 'search'=>true, 'help'=>true, 'size'=>60, 'label'=>'l_aktif');
		
		$this->tmp_data['primary']=array('tbl'=>$this->tbl_master,'id'=>'id');
		$this->tmp_data['m_tbl'][]=array('master'=>1,'pk'=>$this->tbl_master);
		$this->tmp_data['sort'][]=array('tbl'=>$this->tbl_master,'id'=>'id');
		
		$this->tmp_data['title'][]=array($this->tbl_master,'key');
		$this->tmp_data['title'][]=array($this->tbl_master,'title');
		$this->tmp_data['title'][]=array($this->tbl_master,'icon');
		$this->tmp_data['title'][]=array($this->tbl_master,'is_default','Default','10','center');
		$this->tmp_data['title'][]=array($this->tbl_master,'status','Status','10','center');
		
		$this->data_fields['master']=$this->tmp_data;
	}
	
	public function index()
	{	
		$this->data_fields['dat_edit']['fields']=$this->post;
		$this->data_fields['search']=$this->load->view('statis/tmp_search',$this->data_fields,true);
		$this->_param_list_['content']=$this->load->view('statis/tmp_table',$this->data_fields,true);
		$this->template->build('statis/table',$this->_param_list_); 
	}
	
	function listBox_STATUS($row, $value){
		if ($value=='1')
			$result='<span class="label label-success"> Aktif</span>';
		else
			$result='<span class="label label-warning"> Off</span>';
		
		return $result;
	}
	
	function listBox_IS_DEFAULT($row, $value){
		if ($value=='1')
			$result='<span class="label label-info"> Default</span>';
		else
			$result='';
		
		return $result;
	}
	
	function listBox_ICON($row, $value){
		$result='<img src="'.img_url($value).'">';
		return $result;
	}
}