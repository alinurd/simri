<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MX_Model {
	var $category='';
	var $bulan='';
	public function __construct()
    {
        parent::__construct();
	}

	function get_data($param='')
	{
        $param=str_replace('_','-',$param);
		if (!empty($this->category)){
			$this->db->where('kelompok',$this->category);
		}
		if (!empty($this->category)){
			$this->db->where('bulan',$this->bulan);
		}
        $rows=$this->db->where('kel_id',2)->order_by('created_at')->limit(5)->get(_TBL_VIEW_NEWS)->result_array();
		$result['news']=$rows;
		$rows=$this->db->select('title, id, uri_title, created_at, updated_at')->where('kel_id',2)->order_by('created_at desc')->limit(5)->get(_TBL_VIEW_NEWS)->result_array();
		$result['recent']=$rows;
		$rows=$this->db->select('kelompok, category_id')->where('kel_id',2)->group_by(['category_id'])->order_by('category_id')->get(_TBL_VIEW_NEWS)->result_array();
		$result['category']=$rows;
		$rows=$this->db->select('bulan, count(id) as jml')->where('kel_id',2)->group_by(['bulan'])->order_by('bulan')->get(_TBL_VIEW_NEWS)->result_array();
		$result['archives']=$rows;
		
		return $result;
	}
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */