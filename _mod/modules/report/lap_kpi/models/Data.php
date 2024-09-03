<?php if( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Data extends MX_Model
{

	var $pos = [];
	public function __construct()
	{
		parent::__construct();
	}

	function get_detail_data()
	{
		$bulan = [ 1, 12 ];

		if( intval( $this->pos['term'] ) > 0 )
		{
			$rows     = $this->db->select( '*' )->where( 'id', intval( $this->pos['term'] ) )->get( _TBL_COMBO )->row_array();
			$bulan[0] = date( 'n', strtotime( $rows['param_date'] ) );
			$bulan[1] = date( 'n', strtotime( $rows['param_date_after'] ) );
		}
		$period = date( 'Y' );
		if( intval( $this->pos['period'] ) > 0 )
		{
			$period = $this->pos['period'];
		}
		$owner      = 0;
		$parent     = [];
		$owner_name = ' All Departement ';
		if( intval( $this->pos['owner'] ) > 0 )
		{
			$owner      = $this->pos['owner'];
			$parent     = $this->db->where( 'id', $owner )->get( _TBL_OWNER )->row_array();
			$owner_name = $parent['owner_name'];
		}
		$minggu = $this->pos['minggu'];
		if( $minggu > 0 )
		{
			$this->db->where( 'minggu_id', $minggu );
		}

		// if (intval($this->pos['term'])==0){
		// 	$rows = $this->db->select('min(bulan_int) as min, max(bulan_int) as max')->where('period_id',$period)->where('owner_id',$owner)->get(_TBL_VIEW_RCSA_KPI)->row_array();
		// 	if ($rows) {
		// 		$bulan[0]=$rows['min'];
		// 		$bulan[1]=$rows['max'];
		// 	}
		// }

		if( $owner > 0 )
		{
			$this->db->where( 'owner_id', $owner );
		}
		$rows = $this->db->where( 'bulan_int>=', $bulan[0] )->where( 'bulan_int<=', $bulan[1] )->where( 'period_id', $period )->get( _TBL_VIEW_RCSA_KPI )->result_array();

		$lap2 = [];
		foreach( $rows as $row )
		{
			$tmp    = [];
			$d      = $this->db->where( 'kpi_id', $row['id'] )->get( _TBL_VIEW_RCSA_KPI_DETAIL )->result_array();
			$detail = [];
			foreach( $d as $dd )
			{
				$detail[] = $dd;
			}
			$tmp           = $row;
			$tmp['detail'] = $detail;
			$lap2[]        = $tmp;
		}
		// dumps($lap2);die();

		$detail = [];
		foreach( $rows as $row )
		{
			$d = $this->db->where( 'kpi_id', $row['id'] )->get( _TBL_VIEW_RCSA_KPI_DETAIL )->result_array();
			foreach( $d as $dd )
			{
				$detail[$row['id']]['detail'][$dd['id']] = $dd;
			}
		}

		$x = [];
		// dumps($detail);die();
		foreach( $detail as $key => $row )
		{
			$owner_id = 0;
			$xx       = [];
			foreach( $row['detail'] as $k => $d )
			{
				if( $owner_id !== $key )
				{
					// dumps($d);
					$xx[$k]['name']      = $d['owner_name'];
					$xx[$k]['satuan']    = $d['satuan'];
					$xx[$k]['title']     = trim( $d['title'] );
					$xx[$k]['indikator'] = $d['indikator'];
					$owner_id            = $d['kpi_id'];
				}
				if( $minggu == 0 )
				{
					$dd = $this->db->where( 'minggu_type', 1 )
					 ->where( 'bulan_int>=', $bulan[0] )
					 ->where( 'bulan_int<=', $bulan[1] )
					 ->where( 'period_id', $period )
					 ->where( 'title like ', "%" . $d['title'] )


					 ->get( _TBL_VIEW_RCSA_KPI_DETAIL )->result_array();

					foreach( $dd as $ke => $va )
					{
						$xx[$k]['bulan'][$va['bulan_int']] = $va;
					}
				}
				else
				{
					$xx[$k]['bulan'][$d['bulan_int']] = $d;
				}


			}
			$x[$key] = $xx;
		}
		unset( $row );
		// dumps($minggu);die();
		$y        = [];
		$owner_id = 0;
		foreach( $rows as $key => $row )
		{
			if( $owner_id !== $row['id'] )
			{
				$y[$row['id']]['name']      = $row['owner_name'];
				$y[$row['id']]['satuan']    = $row['satuan'];
				$y[$row['id']]['title']     = trim( $row['title'] );
				$y[$row['id']]['indikator'] = $row['indikator'];
				$owner_id                   = $row['id'];
			}
			if( $minggu == 0 )
			{
				$dd = $this->db->where( 'minggu_type', 1 )
				 ->where( 'bulan_int>=', $bulan[0] )
				 ->where( 'bulan_int<=', $bulan[1] )
				 ->where( 'period_id', $period )

				 ->where( 'title like ', "%" . $row['title'] )
				 ->get( _TBL_VIEW_RCSA_KPI )->result_array();

				foreach( $dd as $key => $value )
				{
					$y[$row['id']]['bulan'][$value['bulan_int']] = $value;
				}

			}
			else
			{
				$y[$row['id']]['bulan'][$row['bulan_int']] = $row;
			}
		}
		unset( $row );
		// dumps($x);
		foreach( $y as $key => &$row )
		{
			if( array_key_exists( $key, $x ) )
			{
				// dumps('xxx');
				$row['detail'] = $x[$key];
			}
			else
			{
				$row['detail'] = [];
			}
		}

		// dumps($y);
		unset( $row );

		$hasil['bulan']      = $bulan;
		$hasil['data']       = $y;
		$hasil['lap2']       = $lap2;
		$hasil['parent']     = $parent;
		$hasil['owner_name'] = $owner_name;
		return $hasil;
	}
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */
