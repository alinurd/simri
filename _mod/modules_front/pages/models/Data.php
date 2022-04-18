<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MX_Model {
	public function __construct()
    {
        parent::__construct();
	}

	function data_aboutus($param='')
	{
        $rows = $this->db->where('param_text', $param)->where('kelompok', 'menu')->get(_TBL_COMBO)->row_array();
        $result['menu']=$rows;
        
        $rows = $this->db->where('id', 6)->where('active', 1)->get(_TBL_VIEW_NEWS)->row_array();
        if ($rows){
			$rows = $this->_set_data($rows)->_set_params(['photo', 'param_meta', 'param_lang', 'img_cover'])->_row_array()->convert_data_param()->convert_data_lang(['title', 'news_short', 'news'])->_build();

        }
        $result['info']=$rows;
        $result['breadcrumb']=['title'=>'About Us', 'detail'=>['Home',' Detail', 'siap', 'About Us']];
		return $result;
    }

}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */