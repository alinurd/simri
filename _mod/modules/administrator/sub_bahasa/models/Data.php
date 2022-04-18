<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MX_Model {

	var $nm_tbl='';
	var $nm_tbl_user='';
	var $_prefix='';
	var $_modules='';
	var $tbl_modul;
	public function __construct()
    {
        parent::__construct();
		$this->tbl_modul='modul';
		$this->tbl_owner='owner';
		$this->tbl_groups='groups';
		$this->tbl_group_privilege='group_privilege';
		
		$this->nm_tbl="group_user";
		$this->nm_tbl_user="users";
		$this->_modules= $this->router->fetch_module();
	}
	
	function get_modul()
	{
		$files = array_diff(scandir(APPPATH.'/language/'._BAHASA_.'/',2), array('.', '..', 'index.html'));
		$result = array();
		foreach($files as $row){
			$result[str_replace('_lang.php','',$row)] = (ucwords(str_replace('_',' ',(str_replace('_lang.php','',$row)))));
		}
		ksort($result);
		array_unshift($result, ' - Select - ');
		return $result;
	}
	
	public function get_data_submenu1($idgroup=0)
	{
		$this->db->select('*');
		$this->db->from($this->tbl_modul);
		$this->db->where('active',1);
		$this->db->where('pid',$idgroup);
		$this->db->order_by('urut');
		
		$query=$this->db->get();
		$menu1=array();
		foreach($query->result() as $row)
		{
			$idnya=$row->id;
			$sub_menu=$this->get_data_submenu2($idnya);
			$modul = explode('/',$row->nm_modul);
			$menu1[]=array('label'=>$row->title,'id'=>$row->id,'link'=>$modul[0],'submenu'=>$sub_menu);
		}
		return $menu1;
	}
	
	public function get_data_submenu2($idgroup=0)
	{
		$this->db->select('*');
		$this->db->from($this->tbl_modul);
		$this->db->where('active',1);
		$this->db->where('pid',$idgroup);
		$this->db->order_by('urut');
		
		$query=$this->db->get();
		$menu1=array();
		foreach($query->result() as $row)
		{
			$idnya=$row->id;
			$modul = explode('/',$row->nm_modul);
			$menu1[]=array('label'=>$row->title,'id'=>$row->id,'link'=>$modul[0],'submenu'=>'');
		}
		return $menu1;
	}
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */