<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Debug extends MX_Controller {
	var $table="";
	var $post=array();
	var $sts_cetak=false;
	public function __construct()
	{
        parent::__construct();
		
		$this->tbl_master=$this->db->dbprefix('debug');
		
		$this->tmp_data['fields'][]=array('nmtbl'=>$this->tbl_master, 'field'=>'id', 'title'=>'id', 'input'=>array('type'=>'int','input'=>'text'), 'show'=>false, 'required'=>false, 'search'=>false, 'help'=>false, 'size'=>4, 'label'=>'l_id');
		$this->tmp_data['fields'][]=array('nmtbl'=>$this->tbl_master, 'field'=>'priority_name', 'title'=>'Priority Name', 'input'=>array('type'=>'string','input'=>'text'), 'show'=>true, 'required'=>true, 'search'=>true, 'help'=>false, 'size'=>40, 'label'=>'l_priority_name');
		$this->tmp_data['fields'][]=array('nmtbl'=>$this->tbl_master, 'field'=>'type', 'title'=>'Type', 'input'=>array('type'=>'string','input'=>'text'), 'show'=>true, 'required'=>false, 'search'=>true, 'help'=>false, 'size'=>40, 'label'=>'l_type');
		$this->tmp_data['fields'][]=array('nmtbl'=>$this->tbl_master, 'field'=>'http_user_agent', 'title'=>'Http User Agent', 'input'=>array('type'=>'string','input'=>'multitext'), 'show'=>true, 'required'=>false, 'search'=>true,'help'=>false, 'size'=>40, 'label'=>'l_http_user_agent');
		$this->tmp_data['fields'][]=array('nmtbl'=>$this->tbl_master, 'field'=>'remote_addr', 'title'=>'Remote Addr', 'input'=>array('type'=>'string','input'=>'text'), 'show'=>true, 'required'=>false, 'search'=>true, 'help'=>false, 'size'=>40, 'label'=>'l_remote_addr');
		$this->tmp_data['fields'][]=array('nmtbl'=>$this->tbl_master, 'field'=>'request_uri', 'title'=>'Request Uri', 'input'=>array('type'=>'string','input'=>'text'), 'show'=>true, 'required'=>false, 'search'=>true,'help'=> false, 'size'=>40, 'label'=>'l_request_uri');
		$this->tmp_data['fields'][]=array('nmtbl'=>$this->tbl_master, 'field'=>'user', 'title'=>'User Name', 'input'=>array('type'=>'string','input'=>'text'), 'show'=>true, 'required'=>false, 'search'=>true, 'help'=>false, 'size'=>40, 'label'=>'l_user');
		$this->tmp_data['fields'][]=array('nmtbl'=>$this->tbl_master, 'field'=>'created_at', 'title'=>'Date', 'input'=>array('type'=>'string','input'=>'text'), 'show'=>true, 'required'=>false, 'search'=>true, 'help'=>false, 'size'=>40, 'label'=>'l_tgl_proses');
		$this->tmp_data['fields'][]=array('nmtbl'=>$this->tbl_master, 'field'=>'message', 'title'=>'SQl Syntac', 'input'=>array('type'=>'string','input'=>'multitext'), 'show'=>true, 'required'=>false, 'search'=>true, 'help'=>false, 'size'=>1000, 'label'=>'l_db_dump');
		
		$this->tmp_data['primary']=array('tbl'=>$this->tbl_master,'id'=>'id');
		
		$this->tmp_data['m_tbl'][]=array('master'=>1,'pk'=>$this->tbl_master);
		
		$this->tmp_data['sort'][]=array('tbl'=>$this->tbl_master,'id'=>'created_at','type'=>'desc');
		
		// $this->tmp_data['title'][]=array($this->tbl_master,'priority_name','Themes','0','left');
		$this->tmp_data['title'][]=array($this->tbl_master,'created_at');
		$this->tmp_data['title'][]=array($this->tbl_master,'type');
		$this->tmp_data['title'][]=array($this->tbl_master,'request_uri');
		$this->tmp_data['title'][]=array($this->tbl_master,'user');
		$this->tmp_data['title'][]=array($this->tbl_master,'message');
		
		$this->data_fields['master']=$this->tmp_data;
	}
	
	public function index()
	{	
		$this->data_fields['dat_edit']['fields']=$this->post;
		$this->data_fields['search']=$this->load->view('statis/tmp_search',$this->data_fields,true);	
		$this->_param_list_['content']=$this->load->view('statis/tmp_table',$this->data_fields,true);
		$this->template->build('statis/table',$this->_param_list_);
	}
}