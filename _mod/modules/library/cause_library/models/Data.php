<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MX_Model {
	var $used=[];
	var $child=[];
	public function __construct()
    {
        parent::__construct();
	}
	
	function get_library($id=0, $key=2){
		$this->db->select(_TBL_LIBRARY.'.*, '._TBL_LIBRARY_DETAIL.'.child_no, '._TBL_LIBRARY_DETAIL.'.id as edit_no');
		$this->db->from(_TBL_LIBRARY_DETAIL);
		$this->db->join(_TBL_LIBRARY, _TBL_LIBRARY_DETAIL . '.child_no='. _TBL_LIBRARY . '.id');
		$this->db->where(_TBL_LIBRARY_DETAIL . '.library_no', $id);
		$this->db->where(_TBL_LIBRARY . '.type',$key);
		
		$query=$this->db->get();
		$result['field']=$query->result_array();
		// Doi::dump($this->db->last_query());die();
		return $result;
	}
	
	function save_library($newid=0,$data=array())
	{

		// dumps($data);die();
		if (isset($data['id_edit'])){
			if(count($data['id_edit'])>0){
				foreach($data['id_edit'] as $key=>$row)
				{
					$this->crud->crud_table(_TBL_LIBRARY_DETAIL);
					$this->crud->crud_field('library_no', $newid);
					$this->crud->crud_field('child_no', $data['library_no'][$key]);
					
					if(intval($data['id_edit'][$key])>0)
					{
						$this->crud->crud_type('edit');
						$this->crud->crud_where(['field' => 'id', 'value' => $row]);
						$this->crud->crud_field('updated_by', $this->ion_auth->get_user_name());
					}
					else
					{
						$this->crud->crud_type('add');
						$this->crud->crud_field('created_by', $this->ion_auth->get_user_name());
					}
					$this->crud->process_crud();
				}
			}
		}
		return true;
	}

	function cari_total_dipakai($id){
		$rows = $this->db->select('type, library_no, COUNT(id) AS jml')->where_in('library_no', $id)->where_in('type', [2,3])->group_by(['type', 'library_no'])->get(_TBL_VIEW_LIBRARY_DETAIL)->result_array();
		$this->child=[];
		foreach($rows as $row){
			$this->child[$row['library_no']][$row['type']]=$row['jml'];
		}

		$rows = $this->db->select('penyebab_id, COUNT(penyebab_id) AS jml')->where('penyebab_id>', 0)->group_by(['penyebab_id'])->get(_TBL_VIEW_RCSA_DETAIL)->result_array();
		$this->used=[];
		foreach($rows as $row){
			$this->used[$row['penyebab_id']]=$row['jml'];
		}
	}

	function get_child($id=0, $type=2){
		$value=0;
		if (array_key_exists($id, $this->child)){
			if (array_key_exists($type, $this->child[$id])){
				$value=$this->child[$id][$type];
			}
		}
		return $value;
	}
	function get_used($id=0){
		$value=0;
		if (array_key_exists($id, $this->used)){
			$value=$this->used[$id];
		}
		return $value;
	}
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */