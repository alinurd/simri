<?php
defined( 'BASEPATH' ) or exit( 'No direct script access allowed' );

class Dashboard extends MY_Controller
{

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 *
	 */
	protected $owner_child = [];
	protected $pos = [];
	public function __construct()
	{
		parent::__construct();
		$this->load->library( 'map' );
		$this->load->language( 'risk_context' );
		$this->load->language( 'monitoring_mitigasi' );

		// 	$dat=$this->db->select('param_text')->where('id', 1)->get(_TBL_COMBO)->row_array();

		// 		$dats=unserialize($dat['param_text']);
		// // dumps($dats);
		// unset($dats[115910]);
		// unset($dats[120592]);
		// dumps($dats);
		// die();

		// dumps($this->_preference_);die();

	}

	function content( $ty = 'detail' )
	{

		$getPrevPath = ( ! empty( $_SERVER["HTTP_REFERER"] ) ) ? $_SERVER["HTTP_REFERER"] : "";
		$x           = $this->session->userdata( 'periode' );
		$tgl1        = date( 'Y-m-d' );
		$tgl2        = date( 'Y-m-d' );
		if( $x )
		{
			$tgl1 = $x['tgl_awal'];
			$tgl2 = $x['tgl_akhir'];
		}

		$data             = $this->map();
		$data['type_ass'] = $this->crud->combo_select( [ 'id', 'data' ] )->combo_where( 'kelompok', 'ass-type' )->combo_where( 'active', 1 )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();
		$data['owner']    = $this->get_combo_parent_dept();
		$data['period']   = $this->crud->combo_select( [ 'id', 'data' ] )->combo_where( 'kelompok', 'period' )->combo_where( 'active', 1 )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();
		$data['term']     = $this->crud->combo_select( [ 'id', 'data' ] )->combo_where( 'kelompok', 'term' )->combo_where( 'pid', _TAHUN_ID_ )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();
		$data['minggu']   = $this->crud->combo_select( [ 'id', 'concat(param_string, \' ( \', param_date, \' s.d \', param_date_after, \' ) \') as minggu' ] )->combo_where( 'kelompok', 'minggu' )->combo_where( 'param_date>=', $tgl1 )->combo_where( 'param_date<=', $tgl2 )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();
		// $data['minggu']=$this->crud->combo_select(['id', 'concat(param_string,\' minggu ke - \',data, \' ( \', param_date, \' s.d \', param_date_after, \' ) \') as minggu'])->combo_where('kelompok', 'minggu')->combo_where('param_date>=', $tgl1)->combo_where('param_date<=', $tgl2)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		// die($this->db->last_query());

		$this->data->pos['tgl1']     = $tgl1;
		$this->data->pos['tgl2']     = $tgl2;
		$this->data->pos['owner']    = 0;
		$this->data->pos['type_ass'] = 0;
		$this->data->pos['period']   = _TAHUN_ID_;
		$this->data->pos['term']     = _TERM_ID_;
		$this->data->pos['minggu']   = _MINGGU_ID_;

		$x           = $this->data->get_data_grap();
		$dat['data'] = $x['mitigasi'];

		$data['grap1']      = $this->hasil = $this->load->view( 'grap', $dat, TRUE );
		$data['data_grap1'] = '';
		// $data['data_grap1']= $this->hasil=$this->load->view('grap2',$dat, true);

		$dat['data']        = $x['tepat'];
		$data['grap2']      = $this->hasil = $this->load->view( 'grap3', $dat, TRUE );
		$data['data_grap2'] = $this->hasil = $this->load->view( 'grap4', $dat, TRUE );

		$dat['data'] = $x['komitment'];

		$data['grap3']      = $this->hasil = $this->load->view( 'grap5', $dat, TRUE );
		$data['data_grap3'] = $this->hasil = $this->load->view( 'grap6', $dat, TRUE );


		$data["legendLikelihoodMatrix"] = [ 5 => "Hampir Pasti Terjadi", 4 => "Sangat Mungkin Terjadi", 3 => "Mungkin Terjadi", 2 => "Jarang Terjadi", 1 => "Hampir Tidak Terjadi" ];
		$data["legendImpactMatrix"]     = $data["legendImpactMatrix"] = [ 5 => "High", 4 => "Moderate to High", 3 => "Moderate", 2 => "Low to Moderate", 1 => "Low" ];
		$data["matrix_peta_risiko"] = $this->load->view( "matrik-peta-risiko", $data, TRUE );




		$data["notif_startup"] = $this->startupNotif( $getPrevPath );
		$this->hasil           = $this->load->view( 'dashboard', $data, TRUE );
		return $this->hasil;
	}

	function map()
	{
		$this->data->filter_data();

		$this->db->where( 'status_final', 1 );

		$rows = $this->db->SELECT( 'risiko_inherent as id, COUNT(risiko_inherent) as nilai, level_color, level_color_residual, level_color_target' )->group_by( 'risiko_inherent' )->get( _TBL_VIEW_RCSA_DETAIL )->result_array();

		// ->get_compiled_select(_TBL_VIEW_RCSA_DETAIL);
		// dumps($rows);
		// die();

		$data['map_inherent']        = $this->map->set_data( $rows )->set_param( [ 'tipe' => 'angka', 'level' => 1 ] )->draw_dashboard();
		$jml                         = $this->map->get_total_nilai();
		$jmlstatus                   = $this->map->get_jumlah_status();
		$data['jml_inherent_status'] = $jmlstatus;
		$data['jml_inherent']        = '';
		if( $jml > 0 )
		{
			$data['jml_inherent'] = '<span class="badge bg-primary badge-pill"> ' . $jml . ' </span>';
		}

		// $this->data->filter_data();

		// $this->db->where( 'status_final', 1 );

		$rows                      = $this->db->SELECT( 'risiko_target_mon as id, COUNT(risiko_target_mon) as nilai, level_color_mon as level_color, level_color_residual, level_color_target, bulan_mon, mon_id' )->group_by( 'risiko_target_mon' )->get( "il_view_rcsa_detail_monitoring" )->result_array();
 
		
		$data['map_residual']        = $this->map->_setDataMonitoring( $rows )->_setParam( [ 'tipe' => 'angka', 'level' => 2, 'rows'=>$rows] )->draw_dashboard_monitoring();
		$jml                         = $this->map->get_total_nilai();
		$jmlstatus                   = $this->map->get_jumlah_status();
		$data['jml_residual_status'] = $jmlstatus;
		$data['jml_residual']        = '';
		if( $jml > 0 )
		{
			$data['jml_residual'] = '<span class="badge bg-success badge-pill"> ' . $jml . ' </span>';
		}

		$this->data->filter_data();

		$this->db->where( 'status_final', 1 );

		$rows                      = $this->db->SELECT( 'risiko_target as id, COUNT(risiko_target) as nilai, level_color, level_color_residual, level_color_target' )->group_by( 'risiko_target' )->get( _TBL_VIEW_RCSA_DETAIL )->result_array();
		$data['map_target']        = $this->map->set_data( $rows )->set_param( [ 'tipe' => 'angka', 'level' => 3 ] )->draw_dashboard();
		$jml                       = $this->map->get_total_nilai();
		$jmlstatus                 = $this->map->get_jumlah_status();
		$data['jml_target_status'] = $jmlstatus;
		$data['jml_target']        = '';
		if( $jml > 0 )
		{
			$data['jml_target'] = '<span class="badge bg-warning badge-pill"> ' . $jml . ' </span>';
		}
		return $data;
	}

	function get_map()
	{
		$this->pos                 = $this->input->post();
		$this->data->pos           = $this->pos;
		$this->data->owner_child   = [];
		$this->data->owner_child[] = intval( $this->pos['owner'] );
		$this->data->get_owner_child( intval( $this->pos['owner'] ) );
		$this->owner_child = $this->data->owner_child;

		$data                           = $this->map();
		$data["legendLikelihoodMatrix"] = [ 5 => "Hampir Pasti Terjadi", 4 => "Sangat Mungkin Terjadi", 3 => "Mungkin Terjadi", 2 => "Jarang Terjadi", 1 => "Hampir Tidak Terjadi" ];
		$data["legendImpactMatrix"]     = [ 5 => "High", 4 => "Moderate to High", 3 => "Moderate", 2 => "Low to Moderate", 1 => "Low" ];
		$hasil['combo']                 = $this->load->view( 'map', $data, TRUE );

		$x                   = $this->data->get_data_grap();
		$dat['data']         = $x['mitigasi'];
		$hasil['grap1']      = $this->hasil = $this->load->view( 'grap', $dat, TRUE );
		$hasil['data_grap1'] = $this->hasil = $this->load->view( 'grap2', $dat, TRUE );

		$dat['data']         = $x['tepat'];
		$hasil['grap2']      = $this->hasil = $this->load->view( 'grap3', $dat, TRUE );
		$hasil['data_grap2'] = $this->hasil = $this->load->view( 'grap4', $dat, TRUE );

		$dat['data']         = $x['komitment'];
		$hasil['grap3']      = $this->hasil = $this->load->view( 'grap4', $dat, TRUE );
		$hasil['data_grap3'] = $this->hasil = $this->load->view( 'grap5', $dat, TRUE );
		header( 'Content-type: application/json' );
		echo json_encode( $hasil );
	}

	function get_detail_char()
	{
		$pos             = $this->input->post();
		$this->data->pos = $pos;
		$data            = $this->data->get_detail_char();
		$data['mode']    = 0;
		$x               = $this->load->view( 'detail-char-' . $pos['data']['type_chat'], $data, TRUE );
		$hasil['combo']  = $x;
		$this->session->set_userdata( [ 'cetak_grap' => $data ] );
		$this->session->set_userdata( [ 'type_chat' => $pos['data']['type_chat'] ] );
		header( 'Content-type: application/json' );
		echo json_encode( $hasil );
	}

	function init( $aksi = '' )
	{
		$configuration = [
		 'show_title_header'   => FALSE,
		 'show_action_button'  => FALSE,
		 'show_header_content' => FALSE,
		 'box_content'         => FALSE,
		];
		return [
		 'configuration' => $configuration,
		];
	}

	function cetak()
	{
		$data         = $this->session->userdata( 'cetak_grap' );
		$type         = $this->session->userdata( 'type_chat' );
		$data['mode'] = 1;
		$x            = $this->load->view( 'detail-char-' . $type, $data, TRUE );
		$file_name    = "file_name.xls";
		header( "Content-type: application/vnd.ms-excel" );
		header( "Content-Disposition: attachment; filename=$file_name" );
		echo $x;

	}

	function startupNotif( $urlPath )
	{
		$getMessageContent = str_replace( "[[expired_date]]", $this->configuration['preference']['password_expr'], $this->configuration['preference']['startup_message'] );
		$data              = [
		  "title"   => $this->configuration["preference"]['startup_title'],
		  "message" => $getMessageContent,
		  "status"  => ( in_array( "login", explode( "/", $urlPath ) ) || isset( explode( "/", $urlPath )[3] ) && explode( "/", $urlPath )[3] == "" ) ? TRUE : FALSE,
		  ];

		return $data;
	}
}
