<?php if( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Data extends MX_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	function getDataHistoryKajian( $idkajian )
	{
		$queryHistoryKajian = "select ikrah.*,ikr.name,ikr.tiket_terbit from il_kajian_risiko_approval_history ikrah join il_kajian_risiko ikr on ikrah.id_kajian_risiko =ikr.id where ikr.id={$idkajian} ";
		return $this->db->query( $queryHistoryKajian )->result_array();
	}
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */
