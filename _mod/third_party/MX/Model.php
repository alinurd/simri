<?php if( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class MX_Model extends CI_Model
{
	public $pages_child = array();
	public $owner_child = array();
	public function __construct()
	{
		parent::__construct();
	}

	function get_library( $type )
	{
		if($type){
			$libs = $this->db->where_in('type', $type)->where("active", 1)->get( _TBL_VIEW_LIBRARY )->result_array();
		}else{
			$libs=[];
		}
		return $libs;
	}

	
	function get_owner_child( $id )
	{
		$this->db->select( '*' );
		$this->db->from( _TBL_OWNER );
		$this->db->where( 'pid', $id );
		$this->db->where( 'active', 1 );

		$sql  = $this->db->get();
		$rows = $sql->result();
		foreach( $rows as $key => $row )
		{
			$this->get_owner_child( $row->id );
			$this->owner_child[] = $row->id;
		}
	}

	function get_pages_child( $id )
	{
		$this->db->select( '*' );
		$this->db->from( _TBL_COMBO );
		$this->db->where( 'pid', $id );
		$this->db->where( 'active', 1 );

		$sql  = $this->db->get();
		$rows = $sql->result();
		foreach( $rows as $key => $row )
		{
			$this->get_pages_child( $row->id );
			$this->pages_child[] = $row->id;
		}
	}

	function get_data_register_bytype( $type, $period, $term )
	{
		// $rows=$this->db->where('id', $id)->get(_TBL_VIEW_RCSA)->row_array();
		// $hasil['parent']=$rows;
		$rcsa_id = [ 0 ];
		$rows    = $this->db->select( 'rcsa_id' )->where( 'tipe_risiko_id', $type )->where( 'period_id', $period )->where( 'term_id', $term )->order_by( 'kode_dept' )->order_by( 'kode_aktifitas' )->get( _TBL_VIEW_REGISTER )->result_array();
		if( $rows )
		{
			foreach( $rows as $key => $value )
			{
				$rcsa_id[] = $value['rcsa_id'];
			}
		}

		$rows = $this->db->where_in( 'rcsa_id', $rcsa_id )->get( _TBL_VIEW_RCSA_MITIGASI )->result_array();
		$rows = $this->convert_owner->set_data( $rows )->set_param( [ 'penanggung_jawab' => 'penanggung_jawab_id', 'koordinator' => 'koordinator_id' ] )->draw();
		$mit  = [];
		foreach( $rows as $key => $row )
		{
			$this->db->select( 'aktual' );
			$progres     = $this->db->where( 'rcsa_mitigasi_id', $row['id'] )->get( _TBL_VIEW_RCSA_MITIGASI_PROGRES )->result_array();
			$jmlprogres  = count( $progres );
			$totalaktual = 0;
			foreach( $progres as $v )
			{
				$totalaktual += $v['aktual'];
			}
			$rata                          = ( $jmlprogres >= 1 ) ? $totalaktual / $jmlprogres : 0;
			$row['progres']                = $rata;
			$mit[$row['rcsa_detail_id']][] = $row;
		}

		$hasil['mitigasi'] = $mit;
		$rows              = $this->db->where_in( 'rcsa_id', $rcsa_id )->where( 'tipe_risiko_id', $type )->where( 'period_id', $period )->where( 'term_id', $term )->order_by( 'kode_dept' )->order_by( 'kode_aktifitas' )->get( _TBL_VIEW_REGISTER )->result_array();
		foreach( $rows as &$row )
		{
			$idx  = explode( ',', $row['peristiwa_id'] );
			$libs = $this->db->where_in( 'id', $idx )->get( _TBL_LIBRARY )->result_array();
			$x    = [];
			foreach( $libs as $lib )
			{
				$x[] = $lib['library'];
			}
			$row['peristiwa'] = implode( '###', $x );

			$idx  = explode( ',', $row['dampak_id'] );
			$libs = $this->db->where_in( 'id', $idx )->get( _TBL_LIBRARY )->result_array();
			$x    = [];
			foreach( $libs as $lib )
			{
				$x[] = $lib['library'];
			}
			$row['dampak'] = implode( '###', $x );
			if( ! empty( $row['nama_kontrol_note'] ) && ! empty( $row['nama_kontrol'] ) )
			{
				$row['nama_kontrol'] .= '###' . $row['nama_kontrol_note'];
			}
			else
			{
				$row['nama_kontrol'] .= $row['nama_kontrol_note'];
			}
		}
		unset( $row );
		$hasil['rows'] = $rows;
		$rows          = $this->db->where_in( 'rcsa_id', $rcsa_id )->get( _TBL_VIEW_RCSA_DET_LIKE_INDI )->result_array();
		$like          = [];
		foreach( $rows as $row )
		{
			$like[$row['rcsa_detail_id']][$row['bk_tipe']][] = $row;
		}
		$hasil['like_indi'] = $like;
		$rows               = $this->db->where_in( 'rcsa_id', $rcsa_id )->get( _TBL_VIEW_RCSA_DET_DAMPAK_INDI )->result_array();
		$dampak             = [];
		foreach( $rows as $row )
		{
			$dampak[$row['rcsa_detail_id']][$row['bk_tipe']][] = $row;
		}
		$hasil['dampak_indi'] = $dampak;
		return $hasil;
	}

	function get_data_register( $id )
	{
		$rows            = $this->db->where( 'id', $id )->get( _TBL_VIEW_RCSA )->row_array();
		$hasil['parent'] = $rows;
		$rows            = $this->db->where( 'rcsa_id', $id )->get( _TBL_VIEW_RCSA_MITIGASI )->result_array();
		$rows            = $this->convert_owner->set_data( $rows )->set_param( [ 'penanggung_jawab' => 'penanggung_jawab_id', 'koordinator' => 'koordinator_id' ] )->draw();
		$mit             = [];
		foreach( $rows as $key => $row )
		{
			$this->db->select( 'aktual' );
			$progres     = $this->db->where( 'rcsa_mitigasi_id', $row['id'] )->get( _TBL_VIEW_RCSA_MITIGASI_PROGRES )->result_array();
			$jmlprogres  = count( $progres );
			$totalaktual = 0;
			foreach( $progres as $v )
			{
				$totalaktual += $v['aktual'];
			}
			$rata                          = ( $jmlprogres >= 1 ) ? $totalaktual / $jmlprogres : 0;
			$row['progres']                = $rata;
			$mit[$row['rcsa_detail_id']][] = $row;
		}

		$hasil['mitigasi'] = $mit;
		$rows              = $this->db->select( [ _TBL_VIEW_REGISTER . ".*", "CONCAT(" . _TBL_OWNER . ".owner_name" . ",' - '," . _TBL_OWNER . ".owner_code) as seksi" ] )->order_by( 'kode_dept' )->order_by( 'kode_aktifitas' )->join( _TBL_OWNER, _TBL_OWNER . '.id = ' . _TBL_VIEW_REGISTER . '.seksi', 'left' )->get_where( _TBL_VIEW_REGISTER, [ 'rcsa_id' => $id ] )->result_array();
		foreach( $rows as &$row )
		{
			$idx  = explode( ',', $row['peristiwa_id'] );
			$libs = $this->db->where_in( 'id', $idx )->get( _TBL_LIBRARY )->result_array();
			$x    = [];
			foreach( $libs as $lib )
			{
				$x[] = $lib['library'];
			}
			$row['peristiwa'] = implode( '###', $x );

			$idx  = explode( ',', $row['dampak_id'] );
			$libs = $this->db->where_in( 'id', $idx )->get( _TBL_LIBRARY )->result_array();
			$x    = [];
			foreach( $libs as $lib )
			{
				$x[] = $lib['library'];
			}
			$row['dampak'] = implode( '###', $x );
			if( ! empty( $row['nama_kontrol_note'] ) && ! empty( $row['nama_kontrol'] ) )
			{
				$row['nama_kontrol'] .= '###' . $row['nama_kontrol_note'];
			}
			else
			{
				$row['nama_kontrol'] .= $row['nama_kontrol_note'];
			}
		}
		unset( $row );
		$hasil['rows'] = $rows;
		$rows          = $this->db->where( 'rcsa_id', $id )->get( _TBL_VIEW_RCSA_DET_LIKE_INDI )->result_array();
		$like          = [];
		foreach( $rows as $row )
		{
			$like[$row['rcsa_detail_id']][$row['bk_tipe']][] = $row;
		}
		$hasil['like_indi'] = $like;
		$rows               = $this->db->where( 'rcsa_id', $id )->get( _TBL_VIEW_RCSA_DET_DAMPAK_INDI )->result_array();
		$dampak             = [];
		foreach( $rows as $row )
		{
			$dampak[$row['rcsa_detail_id']][$row['bk_tipe']][] = $row;
		}
		$hasil['dampak_indi'] = $dampak;
		return $hasil;
	}

	function get_data_monitoring( $id )
	{
		$rows         = $this->db->where( 'rcsa_id', $id )->get( _TBL_VIEW_RCSA_MITIGASI_PROGRES )->result_array();
		$mit          = [];
		$jml['aktif'] = [];
		foreach( $rows as $row )
		{
			$mit[$row['rcsa_mitigasi_detail_id']][]          = $row;
			$jml['aktif'][$row['rcsa_mitigasi_detail_id']][] = $row['id'];
		}
		$hasil    = $jml;
		$rows     = $this->db->where( 'id', $id )->get( _TBL_VIEW_RCSA )->row_array();
		$parent   = $rows;
		$mitigasi = $mit;
		$rows     = $this->db->where( 'rcsa_id', $id )->get( _TBL_VIEW_MONITORING )->result_array();
		$rows     = $this->convert_owner->set_data( $rows )->set_param( [ 'penanggung_jawab' => 'penanggung_jawab_detail_id', 'koordinator' => 'koordinator_detail_id' ] )->draw();
		$rowsx    = $rows;

		$jml['miti']   = [];
		$jml['identi'] = [];
		foreach( $rows as $row )
		{
			$jml['identi'][$row['rcsa_detail_id']][] = $row['id'];
			$jml['miti'][$row['mitigasi_id']][]      = $row['id'];
		}
		$hasil             = $jml;
		$hasil['rows']     = $rowsx;
		$hasil['parent']   = $parent;
		$hasil['mitigasi'] = $mit;
		$hasil['minggu']   = $this->crud->combo_select( [ 'id', 'concat(param_string) as minggu' ] )->combo_where( 'kelompok', 'minggu' )->combo_where( 'active', 1 )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();
		return $hasil;
	}
	public function getMonthlyMonitoring($id, $month)
    {
        $thn = date('Y');
		$getProgress = $this->db->where('rcsa_detail_id', $id)->where('month', $month)->get("il_update_residual")->row_array();
         return $getProgress;
    }
}
/* End of file app_login_model.php */
