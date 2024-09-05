<?php if( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Data extends MX_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	function getRowMapData( $idkajian )
	{
		$levelQuery  = "select id,code,level from il_level ivlm where category='likelihood' order by code desc";
		$getRowLevel = $this->db->query( $levelQuery )->result_array();

		foreach( $getRowLevel as $kLvl => $vLvl )
		{
			$result["current"][$kLvl]["id"]     = $vLvl["id"];
			$result["current"][$kLvl]["code"]   = $vLvl["code"];
			$result["current"][$kLvl]["level"]  = $vLvl["level"];
			$result["current"][$kLvl]["column"] = $this->db->query( "select color,color_text,level_color,like_code,like_text,impact_code,impact_text,id from il_view_level_mapping ivlm where like_code = {$vLvl['code']} order by impact_code asc " )->result_array();
			if( ! empty( $result["current"][$kLvl]["column"] ) )
			{
				foreach( $result["current"][$kLvl]["column"] as $kColumn => $vCol )
				{
					$result["current"][$kLvl]["column"][$kColumn]["color"]         = $vCol["color"];
					$result["current"][$kLvl]["column"][$kColumn]["color_text"]    = $vCol["color_text"];
					$result["current"][$kLvl]["column"][$kColumn]["level_color"]   = $vCol["level_color"];
					$result["current"][$kLvl]["column"][$kColumn]["like_code"]     = $vCol["like_code"];
					$result["current"][$kLvl]["column"][$kColumn]["like_text"]     = $vCol["like_text"];
					$result["current"][$kLvl]["column"][$kColumn]["impact_code"]   = $vCol["impact_code"];
					$result["current"][$kLvl]["column"][$kColumn]["impact_text"]   = $vCol["impact_text"];
					$result["current"][$kLvl]["column"][$kColumn]["id"]            = $vCol["id"];
					$result["current"][$kLvl]["column"][$kColumn]["countregister"] = $this->db->get_where( _TBL_KAJIAN_RISIKO_REGISTER, [ "impact_current_level" => $vCol["impact_code"], "likelihood_current_level" => $vCol["like_code"], "id_kajian_risiko" => $idkajian ] )->num_rows();
				}
			}
		}

		foreach( $getRowLevel as $kLvl2 => $vLvl2 )
		{
			$result["residual"][$kLvl2]["id"]     = $vLvl2["id"];
			$result["residual"][$kLvl2]["code"]   = $vLvl2["code"];
			$result["residual"][$kLvl2]["level"]  = $vLvl2["level"];
			$result["residual"][$kLvl2]["column"] = $this->db->query( "select color,color_text,level_color,like_code,like_text,impact_code,impact_text,id from il_view_level_mapping ivlm  where like_code = {$vLvl2['code']} order by impact_code asc " )->result_array();
			if( ! empty( $result["residual"][$kLvl2]["column"] ) )
			{
				foreach( $result["residual"][$kLvl2]["column"] as $kColumnRes => $vColRes )
				{
					$result["residual"][$kLvl2]["column"][$kColumnRes]["color"]         = $vColRes["color"];
					$result["residual"][$kLvl2]["column"][$kColumnRes]["color_text"]    = $vColRes["color_text"];
					$result["residual"][$kLvl2]["column"][$kColumnRes]["level_color"]   = $vColRes["level_color"];
					$result["residual"][$kLvl2]["column"][$kColumnRes]["like_code"]     = $vColRes["like_code"];
					$result["residual"][$kLvl2]["column"][$kColumnRes]["like_text"]     = $vColRes["like_text"];
					$result["residual"][$kLvl2]["column"][$kColumnRes]["impact_code"]   = $vColRes["impact_code"];
					$result["residual"][$kLvl2]["column"][$kColumnRes]["impact_text"]   = $vColRes["impact_text"];
					$result["residual"][$kLvl2]["column"][$kColumnRes]["id"]            = $vColRes["id"];
					$result["residual"][$kLvl2]["column"][$kColumnRes]["countregister"] = $this->db->get_where( _TBL_KAJIAN_RISIKO_REGISTER, [ "impact_residual_level" => $vColRes["impact_code"], "likelihood_residual_level" => $vColRes["like_code"], "id_kajian_risiko" => $idkajian ] )->num_rows();
					;
				}
			}
		}
		return $result;
	}

	function getIdIncrementDb()
	{
		$query = "SELECT AUTO_INCREMENT FROM information_schema.tables WHERE table_name = 'il_kajian_risiko' and table_schema = '" . $this->db->database . "';";
		return $this->db->query( $query )->row_array()["AUTO_INCREMENT"];

	}
	function getDataHistoryKajian( $idkajian )
	{
		$queryHistoryKajian = "select ikrah.*,ikr.name,ikr.tiket_terbit,ikr.tipe_kajian from il_kajian_risiko_approval_history ikrah join il_kajian_risiko ikr on ikrah.id_kajian_risiko =ikr.id where ikr.id={$idkajian} ";
		return $this->db->query( $queryHistoryKajian )->result_array();
	}
	function getDataTipekajian()
	{
		$result = [];
		$data   = $this->db->get_where( _TBL_KAJIAN_RISIKO_TIPE, [ "active" => 1 ] )->result_array();
		if( ! empty( $data ) )
		{
			foreach( $data as $key => $value )
			{
				$result[$value["id"]] = $value["tipe"];
			}
		}
		return $result;
	}

}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */
