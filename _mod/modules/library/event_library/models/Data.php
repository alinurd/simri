<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MX_Model {
	var $used=[];

	public function __construct()
    {
        parent::__construct();
	}
	
	// function save_library($newid=0,$data=array(), $tipe=1, $mode='new', $old_data=array())
	// {
	// 	$updf['id'] = $newid;
	// 	$upd['type'] = $tipe;
	// 	if ($mode=='new'){
	// 		$upd['code'] = $this->cari_code_library($data, $tipe);
	// 	}elseif($mode=='edit'){
	// 		if ($data['l_risk_type_no'] !== $old_data['l_risk_type_no']){
	// 			$upd['code'] = $this->cari_code_library($data, $tipe);
	// 		}
	// 	}
	// 	$this->db->update("library",$upd,$updf);
	// 	return true;
	// }
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
	function cari_total_dipakai($id=[]){
		$this->used=[];
		foreach($id as $i){
			$this->db->where('peristiwa_id', $i);
			$this->db->or_like('peristiwa_id', ','.$i, 'before');
			$this->db->or_like('peristiwa_id', $i.',', 'after');
			$this->db->or_like('peristiwa_id', ','.$i.',', 'both');
			$rows = $this->db->select('peristiwa_id as id, ifnull(COUNT(peristiwa_id),0) AS jml')->group_by(['peristiwa_id'])->get(_TBL_VIEW_RCSA_DETAIL)->row_array();
			if ($rows){
				$this->used[$rows['id']]=$rows['jml'];
			}
		}
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