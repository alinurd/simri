<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MX_Model {
	var $post=[];
	public function __construct()
    {
        parent::__construct();
	}

	function get_data()
	{
        if ($this->post['waktu']==1){
			$this->db->where('tgl >=', date('Y-m-d',strtotime($this->post['tanggal1_submit'])))->where('tgl <=', date('Y-m-d',strtotime($this->post['tanggal2_submit'])));
		}else{
			$this->db->where('tahun', $this->post['tahun']);
			if (array_key_exists('bulan',$this->post)){
				$this->db->where_in('bulan', $this->post['bulan']);
				$this->db->where_in('bulan', $this->post['bulan']);
			}
		}

		if (array_key_exists('category',$this->post)){
			if ($this->post['category'][0]>0){
				$this->db->where_in('category_id', $this->post['category']);
			}
		}

		if (array_key_exists('product',$this->post)){
			$this->db->where_in('id', $this->post['product']);
		}

		$rows = $this->db->where('status_id', 0)->order_by('created_at desc')->get(_TBL_VIEW_ORDERS)->result_array();
		
		// dumps($this->db->last_query());
        $result['orders']=$rows;
		return $rows;
	}
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */