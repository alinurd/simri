<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MX_Model {
    var $uri_title='';
	public function __construct()
    {
        parent::__construct();
	}

	function get_data($param='')
	{
        $rows = $this->db->where('active', 1)->order_by('cat_id, param_int, order')->get(_TBL_VIEW_PORTOFOLIO)->result_array();
        $arr=[];
        foreach($rows as &$row){
            $row['image'] = json_decode($row['photo'], true);
            $row = $this->_set_data($row)->_set_params(['photo', 'param_meta', 'param_lang', 'img_cover'])->_row_array()->convert_data_param()->convert_data_lang(['title', 'news_short', 'news'])->_build();
            $arr[$row['cat_id']][]=$row;
        }
        unset($row);
        $result['info']=$arr;

        if(empty($this->uri_title)){
            $result['satu']=false;
        }else{
            $rows = $this->db->where('uri_title', $this->uri_title)->get(_TBL_VIEW_PORTOFOLIO)->row_array();
            $arr=[];
            if ($rows){
                $rows['image'] = json_decode($rows['photo'], true);
                $rows = $this->_set_data($rows)->_set_params(['photo', 'param_meta', 'param_lang', 'img_cover'])->_row_array()->convert_data_param()->convert_data_lang(['title', 'news_short', 'news'])->_build();
            }
            $result['satu']=true;
            $result['detail']=$rows;
        }

        $rows=$this->db->from(_TBL_VIEW_PRODUCT.' a')->select('a.category_id, a.kelompok, a.uri_title_category, count(a.id) as jml')->join(_TBL_VIEW_NEWS.' b', 'b.param_int=a.id')->where('b.kel_id',3)->where('b.category_id',104)->group_by(['a.category_id', 'a.kelompok', 'a.uri_title_category'])->get()->result_array();
        $arr=[];
        foreach($rows as $row){
            $arr[$row['category_id']]=$row;
        }
        $result['cProduct']=$arr;
		return $result;
	}
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */