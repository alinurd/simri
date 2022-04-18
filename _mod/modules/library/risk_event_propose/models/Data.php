<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MX_Model {

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
		$tgl=Doi::now();
		if (isset($data['id_edit'])){
			if(count($data['id_edit'])>0){
				foreach($data['id_edit'] as $key=>$row)
				{
					$upd=array();
					$upd['library_no'] = $newid;;
					$upd['child_no'] = $data['library_no'][$key];;
					
					if(intval($data['id_edit'][$key])>0)
					{
						$upd['update_date'] = $tgl;
						$upd['update_user'] = $this->authentication->get_info_user('username');
						$result=$this->crud->crud_data(array('table'=>_TBL_LIBRARY_DETAIL, 'field'=>$upd,'where'=>array('id'=>$data['id_edit'][$key]),'type'=>'update'));
					}
					else
					{
						$upd['create_user'] = $this->authentication->get_info_user('username');
						$result=$this->crud->crud_data(array('table'=>_TBL_LIBRARY_DETAIL, 'field'=>$upd,'type'=>'add'));
					}
				}
			}
		}		
		return true;
	}
	
	function cari_total_dipakai($id){
		$this->db->where('library_no', $id);
		$this->db->where('type', 2);
		$num_rows = $this->db->count_all_results(_TBL_VIEW_LIBRARY_DETAIL);
		$hasil['jmlCouse']=$num_rows;
		
		$this->db->where('library_no', $id);
		$this->db->where('type', 3);
		$num_rows = $this->db->count_all_results(_TBL_VIEW_LIBRARY_DETAIL);
		$hasil['jmlImpact']=$num_rows;
		
		$sql=$this->db
				->select('*')
				->from(_TBL_LIBRARY)
				->where('id', $id)
				->get();
		
		$rows=$sql->row();
		$hasil['nama_lib'] = $rows->description;
		return $hasil;
	}
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */