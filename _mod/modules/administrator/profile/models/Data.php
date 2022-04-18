<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MX_Model {

	
	public function __construct()
    {
        parent::__construct();
		
	}
	
	function get_data($id=1, $param=array()){
		$fields=array();
		foreach($param['fields'] as $key=>$row)
		{
			if (array_key_exists('label',$row))
				$label= $row['label'];
			else
				$label='l_'.$row['field'];
			
			if (array_key_exists('input',$row)){
				if ($row['input']['type']!=='free'){
					$fields[]= $row['nmtbl'].'.'.$row['field'].' as '.$label;
				}
			}else{
				$fields[]= $row['nmtbl'].'.'.$row['field'].' as '.$label;
			}
		}
		$field=implode(', ',$fields);
		
		$this->db->select($field);
		$this->db->from('users');
		$this->db->where('id',$id);
		$query=$this->db->get();
		$rows=$query->result_array();
		$result['fields']=$rows[0];
		// doi::dump($result['fields'],false,true);
		return $result;
	}
	
	function save_data($data, $old_data){
		foreach($data['fields'] as $key=>$row)
		{
			if ($row['show']){
				$upd['value'] = $data['data']['l_'.$row['field']];
				$this->db->where('uri_title',$row['field']);
				$this->db->update('preference',$upd);
				$upd=array();
			}
		}
		$this->session->set_userdata(array('result_proses'=>lang('msg_success_save_edit')));
		return true;
	}
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */