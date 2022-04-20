<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MX_Model {
	
	public function __construct()
    {
        parent::__construct();
	}
    function get_library($id=0, $key=2){
		$this->db->select(_TBL_CEK.'.*');
		
		$query=$this->db->get();
		$result['field']=$query->result_array();
		// Doi::dump($this->db->last_query());die();
		return $result;
	}
}