<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MX_Model {
	var $post=[];
	public function __construct()
    {
        parent::__construct();
	}

	function get_data()
	{

		$rows=$this->db->get(_TBL_VIEW_RCSA)->result_array();
		$result['parent']=$rows;



 	return $result;
	}
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */