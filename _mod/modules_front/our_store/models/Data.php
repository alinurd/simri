<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MX_Model {
	public function __construct()
    {
        parent::__construct();
	}

	function get_data($param='')
	{
        $param=str_replace('_','-',$param);

		$rows=$this->db->select('type')->group_by(['type'])->order_by('type')->get(_TBL_STORE)->result_array();
		$arr=[];
		foreach($rows as $row){
			$arr[$row['type']]=$row['type'];
		}
		$result['group']=$arr;

		$rows=$this->db->order_by('urut')->get(_TBL_STORE)->result_array();
		$arr=[];
		foreach($rows as $row){
			$arr[$row['type']][]=$row;
		}
        $result['info']=$arr;
		return $result;
	}
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */