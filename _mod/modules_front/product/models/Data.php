<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MX_Model {
	public function __construct()
    {
        parent::__construct();
	}

	function get_data($param='')
	{
        $param=str_replace('_','-',$param);
        if ($param)
            $rows = $this->db->where('uri_title', $param)->where('active', 1)->get(_TBL_VIEW_PRODUCT)->row_array();
        else
            $rows = $this->db->where('uri_title', $param)->where('active', 1)->get(_TBL_VIEW_PRODUCT)->result_array();

        if ($rows){
            if ($param){
                $rows = $this->_set_data($rows)->_set_params(['photo', 'param_meta', 'param_lang', 'img_cover'])->_row_array()->convert_data_param()->convert_data_lang(['note', 'instruction'])->_build();
            }else{
                $rows = $this->_set_data($rows)->_set_params(['photo', 'param_meta', 'param_lang', 'img_cover'])->convert_data_param()->convert_data_lang(['note', 'instruction'])->_build();
            }
            }
        // dump($this->db->last_query());
        // dumps($rows);die();
        $result['info']=$rows;
		return $result;
	}
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */