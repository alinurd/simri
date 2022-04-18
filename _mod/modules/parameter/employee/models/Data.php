<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MX_Model {
	public function __construct()
    {
        parent::__construct();
	}
	
	
	function get_group($iduser=0){
		$this->db->select('*');
		$this->db->from(_TBL_USERS_GROUPS);
		$this->db->where('user_id',$iduser);
		
		$query=$this->db->get();
		$rows=$query->result_array();
		$user=[];
		foreach($rows as $row){
			$user[]=$row['group_id'];
		}
		return implode(',',$user);
	}
	
	function get_img_file_name($id){
		$query = $this->db->select('*')
				->where('id',$id)
				->get(_TBL_USERS);
		$rows = $query->result();
		$nm='';
		foreach($rows as $row){
			$nm=$row->photo;
		}
		return $nm;
	}
	
	function save_group($newid=0, $data=array(), $user=[])
	{
		// doi::dump($data,false,true);
		$now = new DateTime();
		$tgl= $now->format('Y-m-d H:i:s');
		$result=1;
		
		$this->crud->crud_table(_TBL_USERS_GROUPS);
		$this->crud->crud_type('delete');
		$this->crud->crud_where(['field'=>'user_id', 'value'=>$user->id, 'op'=>'=']);
		$this->crud->process_crud();

		if (isset($data['group'])){
			if(count($data['group'])>0){
				foreach($data['group'] as $key=>$row)
				{
					$this->crud->crud_table(_TBL_USERS_GROUPS);
					$this->crud->crud_type('add');
					$this->crud->crud_field('group_id', $row, 'int');
					$this->crud->crud_field('user_id', $user->id, 'int');
					$this->crud->crud_field('created_by', $this->ion_auth->get_user_name());
					$this->crud->process_crud();
					//$this->crud->last_query();
				}
			}
		}
		return $result;
	}
			
	function delete_data($id){
		$this->db->where('id', $id);
		$this->db->delete(_TBL_GROUP_USER);
		$jml=$this->db->affected_rows();
		// die($this->db->last_query());
		$hasil['sts']=0;
		$hasil['ket']='Gagal Mengahapus';
			
		if ($jml>0){
			$hasil['sts']=$jml;
			$hasil['ket']='data berhasil dihapus';
		}
		return $hasil;
	}
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */