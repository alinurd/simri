<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MX_Model {
	public function __construct()
    {
        parent::__construct();
	}

	function get_data()
	{
        $rows = $this->db->where('kel_id', 4)->where('active', 1)->get(_TBL_NEWS)->result_array();
		$result['dSlide']=$rows;
		$rows = $this->db->where('kelompok', 'cat-product')->where('param_int', 1)->where('active', 1)->get(_TBL_COMBO)->result_array();
		$result['dService']=$rows;
		$rows = $this->db->where('kel_id', 3)->where('category_id', 101)->where('active', 1)->limit(4)->get(_TBL_NEWS)->result_array();
		$result['dAward']=$rows;
		$rows = $this->db->where('kel_id', 3)->where('category_id', 103)->where('active', 1)->limit(4)->get(_TBL_NEWS)->result_array();
		$result['dTesti']=$rows;
		$rows = $this->db->select('kelompok, category_id')->group_start()->where('new_sts', 1)->or_where('sticky_sts', 1)->group_end()->where('active', 1)->group_by(['kelompok', 'category_id'])->get(_TBL_VIEW_PRODUCT)->result_array();
		$result['dProductCategory']=$rows;
		$rows = $this->db->group_start()->where('new_sts', 1)->or_where('sticky_sts', 1)->group_end()->where('active', 1)->get(_TBL_VIEW_PRODUCT)->result_array();
		$hasil=[];
		foreach($rows as $row){
			// dumps($row);
			$row = $this->_set_data($row)->_set_params(['photo', 'param_meta', 'param_lang', 'img_cover'])->_row_array(true)->convert_data_param()->convert_data_lang(['note', 'instruction'])->_build();
			$hasil[$row['category_id']][]=$row;
		}
		$result['dProduct']=$hasil;

		// dumps($rows);die($this->db->last_query());
		$result['dSlide'] = $this->_set_data($result['dSlide'])->_set_params(['photo', 'param_meta', 'param_lang', 'img_cover'])->convert_data_param()->convert_data_lang(['title', 'news_short', 'news'])->_build();
		//$result['dService'] = $this->_set_data($result['dService'])->_set_params(['photo', 'param_meta', 'param_lang', 'img_cover'])->convert_data_param()->convert_data_lang(['title', 'news_short', 'news'])->_build();
		$result['dAward'] = $this->_set_data($result['dAward'])->_set_params(['photo', 'param_meta', 'param_lang', 'img_cover'])->convert_data_param()->convert_data_lang(['title', 'news_short', 'news'])->_build();
		$result['dTesti'] = $this->_set_data($result['dTesti'])->_set_params(['photo', 'param_meta', 'param_lang', 'img_cover'])->convert_data_param()->convert_data_lang(['title', 'news_short', 'news'])->_build();
		// dumps($result['dProduct']);die();
		return $result;
	}
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */