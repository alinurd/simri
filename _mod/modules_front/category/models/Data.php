<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MX_Model {
    var $uri_title='';
	public function __construct()
    {
        parent::__construct();
	}

	function get_data($param='')
	{
        $rows=$this->db->where('active',1)->order_by('category_id, product')->get(_TBL_VIEW_PRODUCT)->result_array();
        $arr=[];
        foreach($rows as &$row){
            $row['image'] = json_decode($row['photo'], true);
            $row = $this->_set_data($row)->_set_params(['photo', 'param_meta', 'param_lang', 'img_cover'])->_row_array()->convert_data_param()->convert_data_lang(['product', 'note'])->_build();
            
            $arr[$row['category_id']][]=$row;
        }
        unset($row);
        $result['info']=$arr;
        
        $rows = $this->db->where('active', 1)->where('kelompok','cat-product')->order_by('urut')->get(_TBL_COMBO)->result_array();
        $arr=[];
        foreach($rows as $row){
            $arr[$row['id']]=$row;
        }
        $result['cProduct']=$arr;
		return $result;
	}
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */