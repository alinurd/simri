<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Log_User_Login extends MX_Controller {
	var $table="";
	var $post=array();
	var $sts_cetak=false;
	public function __construct()
	{
        parent::__construct();
		
		$this->set_Tbl_Master(_TBL_USER_LOG);
		
		$this->set_Open_Tab("Info User Login");
			$this->addField(array('field'=>'id', 'type'=>'int', 'show'=>false, 'size'=>4));
			$this->addField(array('field'=>'ip', 'size'=>30));
			$this->addField(array('field'=>'dns', 'size'=>30));
			$this->addField(array('field'=>'agen', 'size'=>30));
			$this->addField(array('field'=>'server_add', 'size'=>30));
			$this->addField(array('field'=>'forwader', 'size'=>30));
			$this->addField(array('field'=>'create_date', 'size'=>30));
			$this->addField(array('field'=>'pesan', 'size'=>30));
			$this->addField(array('field'=>'status', 'size'=>30));
			$this->addField(array('field'=>'user_name', 'size'=>30));
			$this->addField(array('field'=>'password', 'size'=>30));
			$this->addField(array('field'=>'country', 'size'=>30));
			$this->addField(array('field'=>'region', 'size'=>30));
			$this->addField(array('field'=>'city', 'size'=>30));
			$this->addField(array('field'=>'isp', 'size'=>30));
			$this->addField(array('field'=>'organization', 'size'=>30));
		$this->set_Close_Tab();
		
		$this->set_Field_Primary('id');
		$this->set_Join_Table(array('pk'=>$this->tbl_master));
		
		$this->set_Sort_Table($this->tbl_master, 'tanggal', 'desc');
		
		$this->set_Where_Table(['field'=>'kelompok', 'value'=>0]);
		
		$this->set_Table_List($this->tbl_master,'create_date');
		$this->set_Table_List($this->tbl_master,'ip');
		$this->set_Table_List($this->tbl_master,'server_add');
		$this->set_Table_List($this->tbl_master,'country');
		$this->set_Table_List($this->tbl_master,'region');
		$this->set_Table_List($this->tbl_master,'city');
		$this->set_Table_List($this->tbl_master,'pesan');
		$this->set_Table_List($this->tbl_master,'user_name');
		$this->set_Table_List($this->tbl_master,'password');
		$this->set_Table_List($this->tbl_master,'agen');
		$this->set_Close_Setting();
			
		// $this->tmp_data['setSearchprivilege']=false;
		// $this->tmp_data['setActionprivilege']=false;
		
		$this->_SET_PRIVILEGE('add', false);
		$this->_SET_PRIVILEGE('delete', false);
		
	}
	
	
	function list_MANIPULATE_PERSONAL_ACTION($tombol, $rows){
		$tombol['edit'] = array();
		$tombol['delete']=array();
		$tombol['view']['default']=true;
		$tombol['print']=array();
		return $tombol;
	}
	
	function listBox_STATUS($row, $value){
		if ($value=='Berhasil')
			$result='<span class="label label-success"> '.lang('msg_input_success').'</span>';
		else
			$result='<span class="label label-warning"> '.lang('msg_input_failed').'</span>';
		
		return $result;
	}
}