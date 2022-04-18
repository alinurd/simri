<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MX_Model {
	public $group_id =0;
	public function __construct()
    {
        parent::__construct();
	}
	
	function get_modul()
	{
		$this->db->select('*');
		$this->db->from(_TBL_MODUL);
		// $this->db->where('active',1);
		// $this->db->where('pid',0);
		$this->db->order_by('urut');
		
		$query=$this->db->get();
		$x=$query->result();
		$menu=array();
		foreach($x as $row)
		{	
			$modul_no=$row->id;
			$privilege=json_decode($row->privilege, true);
			$privilege_tmp=[];
			foreach($privilege as $pr){
				$privilege_tmp[$pr]=0;
			}
			$pr = $this->cari_privilege($modul_no);
			$privilege = array_merge($privilege_tmp, $pr['isi']);
			$menu[]=array("slug" => $row->pid, 'title'=>$row->title, 'id'=>$row->id, 'link'=>$row->nm_modul, 'posisi'=>$row->posisi,'privilege'=>$privilege,'source'=>$row->privilege,'edit_id'=>$pr['id'],'level'=>$row->level);
		}
		$result['field']=_tree($menu);
		// dumps($result['field']);die();
		return $result;
	}
	
	function cari_privilege( $id)
	{
		
		$this->db->select('*');
		$this->db->from(_TBL_GROUP_PRIVILEGE);
		$this->db->where('group_id',$this->group_id);
		$this->db->where('menu_id',$id);
		
		$query=$this->db->get();
		$rows=$query->row_array();
		$hasil=[];
		$id=0;
		if ($rows){
			$hasil=json_decode($rows['privilege'], true);
			$id=$rows['id'];
		}
		$result['id']=$id;
		$result['isi']=$hasil;

		return $result;
	}

	function save_privilege($newid=0,$data=array(), $old_data=array())
	{
		foreach($data['modul'] as $key=>$row)
		{
			if(array_key_exists('source_'.$row, $data)){
				$this->crud->crud_table(_TBL_GROUP_PRIVILEGE);
				$source_tmp = json_decode($data['source_'.$row],true);
				$source=[];
				$source_edit=[];
				foreach($source_tmp as $pr){
					$source[$pr]=0;
					$source_edit[$pr]=$data[$pr.'_'.$row];
				}
				$privilege = array_merge($source, $source_edit);

				$upd['privilege'] = json_encode($privilege);
				$this->crud->crud_field('privilege', json_encode($privilege));
				$this->crud->crud_field('group_id', $newid, 'int');
				$this->crud->crud_field('menu_id', $row, 'int');

				if(intval($data['edit_id'][$key])>0)
				{
					$this->crud->crud_type('edit');
					$this->crud->crud_field('updated_by', $this->ion_auth->get_user_name());
					$this->crud->crud_where(['field'=>'id', 'value'=>$data['edit_id'][$key]]);
					$this->crud->process_crud();
				}
				else
				{
					$this->crud->crud_type('add');
					$this->crud->crud_field('created_by', $this->ion_auth->get_user_name());
					$this->crud->process_crud();
				}
			}
		}

		
		// $valid=0;
		// if (isset($data['validator'])){
		// 	$valid=1;
		// }
		// $read_only=0;
		// if (isset($data['read_only'])){
		// 	$read_only=1;
		// }
		// $tipe=0;
		// if (isset($data['tipe'])){
		// 	$tipe=intval($data['tipe']);
		// }
		// $tmp=json_encode(['akses_data'=>$tipe,'validator'=>$valid,'read_only'=>$read_only]);

		// $this->crud->crud_table(_TBL_GROUPS);
		// $this->crud->crud_type('edit');
		// $this->crud->crud_field('params', $tmp);
		// $this->crud->crud_where(['field'=>'id', 'value'=>$newid, 'int']);
		// $this->crud->process_crud();

		return true;
	}
	
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */