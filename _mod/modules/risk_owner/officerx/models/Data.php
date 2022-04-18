<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MX_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->sts_save = '';
	}

	function get_group($iduser = -1)
	{
		$this->db->select(_TBL_USERS_GROUPS . '.*,' . _TBL_GROUPS . '.name');
		$this->db->from(_TBL_USERS_GROUPS);
		$this->db->join(_TBL_GROUPS, _TBL_USERS_GROUPS . '.group_id=' . _TBL_GROUPS . '.id');
		// if ($iduser > -1)
			$this->db->where(_TBL_USERS_GROUPS . '.user_id', $iduser);

		$query = $this->db->get();
		$result['field'] = $query->result_array();
		return $result;
	}

	function cari_data_users($data)
	{
		$query = $this->db->select('*')
			->where('officer_no', $data)
			->get(_TBL_USERS);
		$rows = $query->row();
		return (array)$rows;
	}

	function set_sts_save($sts)
	{
		$this->sts_save = $sts;
	}

	function get_img_file_name($id)
	{
		$query = $this->db->select('*')
			->where('id', $id)
			->get(_TBL_OFFICER);
		$rows = $query->result();
		$nm = '';
		foreach ($rows as $row) {
			$nm = $row->photo;
		}
		return $nm;
	}

	function save_group($newid=0,$data=array(), $user=[])
	{
		// doi::dump($data,false,true);
		$now = new DateTime();
		$tgl= $now->format('Y-m-d H:i:s');
		$result=1;
		
		$this->crud->crud_table(_TBL_USERS_GROUPS);
		$this->crud->crud_type('delete');
		$this->crud->crud_where(['field'=>'user_id', 'value'=>$user->id, 'op'=>'=']);
		$this->crud->process_crud();

		
		$this->crud->crud_table(_TBL_USERS_GROUPS);
		$this->crud->crud_type('add');
		$this->crud->crud_field('group_id', $data['group'], 'int');
		$this->crud->crud_field('user_id', $user->id, 'int');
		$this->crud->crud_field('created_by', $this->ion_auth->get_user_name());
		$this->crud->process_crud();

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


