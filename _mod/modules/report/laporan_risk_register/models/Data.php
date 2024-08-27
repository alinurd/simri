<?php if( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Data extends MX_Model
{
	var $post = [];
	public function __construct()
	{
		parent::__construct();
	}

	function get_data( $where )
	{
		if( ! empty( $where ) )
		{
			$rows = $this->db->get_where( _TBL_VIEW_RCSA, $where )->result_array();
		}
		else
		{
			$rows = $this->db->get( _TBL_VIEW_RCSA )->result_array();
		}

		$result['parent'] = $rows;
		return $result; 
	}
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */
