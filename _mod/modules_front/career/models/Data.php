<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MX_Model {
	public function __construct()
    {
        parent::__construct();
	}

	function get_data($param='')
	{
        $param=str_replace('_','-',$param);

        $rows=$this->db->order_by('order')->get(_TBL_VIEW_CAREER)->result_array();
        $result['info']=$rows;
		return $result;
	}
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */