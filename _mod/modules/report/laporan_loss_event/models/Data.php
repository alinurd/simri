<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MX_Model {
	var $post=[];
	public function __construct()
    {
        parent::__construct();
	}

	function get_data($post=[])
	{
		if (count($post)>0) {
			if ($post['owner_no']>1) {
				$this->db->where('owner_no', $post['owner_no']);
			}
			$this->db->where('period_id', $post['period_id']);
		}
		$this->db->join(_TBL_RCSA_DETAIL, _TBL_LOSS_EVENT.'.peristiwa = '._TBL_RCSA_DETAIL.'.id', 'left');
        $rows = $this->db->get(_TBL_LOSS_EVENT)->result_array();
		foreach($rows as &$row){
			$arrCouse = explode(',',$row['penanggung_jawab_no']);
			$rows_couse=array();
			if ($arrCouse)
				$rows_couse = $this->db->where_in('id', $arrCouse)->get(_TBL_OWNER)->result_array();
			$arrCouse=array();
			foreach($rows_couse as $rc){
				$arrCouse[] = $rc['owner_name'];
			}
			$row['pic']= implode(',',$arrCouse);

			$arrCouse = explode(',',$row['koordinator_id']);
			$rows_couse=array();
			if ($arrCouse)
				$rows_couse = $this->db->where_in('id', $arrCouse)->get(_TBL_OWNER)->result_array();
			$arrCouse=array();
			foreach($rows_couse as $rc){
				$arrCouse[] = $rc['owner_name'];
			}
			$row['kid']= implode(',',$arrCouse);
		}
	unset($row);
	
		// dumps($this->db->last_query());
        $result['rows']=$rows;
	return $result;
	}
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */