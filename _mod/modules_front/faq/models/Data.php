<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MX_Model {
	var $kelompok='';
	public function __construct()
    {
        parent::__construct();
	}

	function get_data_faq($mdl){

		$result['menu'] = $this->db->where('param_text', $mdl)->get(_TBL_COMBO)->row_array();
		$rows = $this->db->get(_TBL_VIEW_FAQ)->result_array();
		$faq=[];
		foreach($rows as $row){
			$faq[$row['category_id']]['name']=$row['kelompok'];
			$faq[$row['category_id']]['detail'][]=$row;
		}

		$result['faq'] =$faq;
		return $result;
	}
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */