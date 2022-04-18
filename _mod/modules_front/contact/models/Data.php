<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MX_Model {
	public function __construct()
    {
        parent::__construct();
	}

	function get_data($param='')
	{
        $param=str_replace('_','-',$param);

        $rows=$this->db->where('is_center',1)->order_by('urut')->get(_TBL_STORE)->result_array();
        $result['info']=$rows;
		return $result;
	}
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */