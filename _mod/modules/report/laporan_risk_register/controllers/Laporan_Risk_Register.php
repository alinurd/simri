<?php
defined( 'BASEPATH' ) or exit( 'No direct script access allowed' );

class Laporan_Risk_Register extends MY_Controller
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

	public function __construct()
	{
		parent::__construct();

	}

	function content( $ty = 'detail' )
	{
		$x    = $this->session->userdata( 'periode' );
		$tgl1 = date( 'Y-m-d' );
		$tgl2 = date( 'Y-m-d' );
		if( $x )
		{
			$tgl1 = $x['tgl_awal'];
			$tgl2 = $x['tgl_akhir'];
		}
		$data['owner']  = $this->get_combo_parent_dept();
		$data['period'] = $this->crud->combo_select( [ 'id', 'data' ] )->combo_where( 'kelompok', 'period' )->combo_where( 'active', 1 )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();
		$data['term']   = $this->crud->combo_select( [ 'id', 'data' ] )->combo_where( 'kelompok', 'term' )->combo_where( 'pid', _TAHUN_ID_ )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();
		$data['minggu'] = $this->crud->combo_select( [ 'id', 'concat(param_string, \' ( \', param_date, \' s.d \', param_date_after, \' ) \') as minggu' ] )->combo_where( 'kelompok', 'minggu' )->combo_where( 'param_date>=', $tgl1 )->combo_where( 'param_date<=', $tgl2 )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();
		// $data['minggu']=$this->crud->combo_select(['id', 'concat(param_string,\' minggu ke - \',data, \' ( \', param_date, \' s.d \', param_date_after, \' ) \') as minggu'])->combo_where('kelompok', 'minggu')->combo_where('param_date>=', $tgl1)->combo_where('param_date<=', $tgl2)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$data['data'] = $this->data->get_data( [] );
		$data['picku'] = $this->get_data_dept();
		$data['tbl']  = $this->hasil = $this->load->view( 'lap', $data, TRUE );
		$this->hasil = $this->load->view( 'view', $data, TRUE );

		return $this->hasil;
	}

	function init( $aksi = '' )
	{
		$configuration = [
		 'show_title_header'  => FALSE,
		 'show_action_button' => FALSE,
		 'box_content'        => FALSE,
		 'left_sidebar_mini'  => FALSE,
		];
		return [
		 'configuration' => $configuration,
		];
	}

	function get_bulan()
	{
		$tahun = intval( $this->input->post( 'tahun' ) );
		$rows  = $this->db->select( 'bulan, cbulan' )->where( 'tahun', $tahun )->distinct()->order_by( 'bulan' )->get( _TBL_VIEW_ORDERS )->result_array();
		$arr   = '';
		foreach( $rows as $row )
		{
			$arr .= '<option value="' . $row['bulan'] . '">' . $row['cbulan'] . '</option>';
		}
		header( 'Content-type: application/json' );
		echo json_encode( [ 'combo' => $arr ] );
	}

	function get_product()
	{
		$categori = $this->input->post( 'category' );
		if( $categori )
		{
			if( $categori[0] > 0 )
			{
				$rows = $this->datacombo->upperGroup()->set_data()->where( [ 'category_id' => $categori ] )->isGroup()->set_noblank( FALSE )->build( 'product' );
			}
			else
			{
				$rows = $this->datacombo->upperGroup()->set_data()->isGroup()->set_noblank( FALSE )->build( 'product' );
			}
		}
		else
		{
			$rows = $this->datacombo->upperGroup()->set_data()->isGroup()->set_noblank( FALSE )->build( 'product' );
		}
		$combo = form_dropdown( 'product', $rows, '', 'class="form-control select" style="width:100% !important;" multiple="multiple" id="product"' );
		header( 'Content-type: application/json' );
		echo json_encode( [ 'combo' => $combo ] );
	}

	function proses_search()
	{
		$whereData = [];
		$post      = $this->input->post();
		if( ! empty( $post["owner"] ) )
		{ 
			$whereData["owner_id"] = $post["owner"];
		}
		if( ! empty( $post["period"] ) )
		{
			$whereData["period_id"] = $post["period"];
		}
		
		$this->data->post = $post;
		$data['data'] = $this->data->get_data( $whereData );
		$data['post']=$post;
		// doi::dump($data);
		$data['picku'] = $this->get_data_dept();

		$hasil['combo']   = $this->load->view( 'lap', $data, TRUE );
		header( 'Content-type: application/json' );
		echo json_encode( $hasil );
	}

	function cetak(){
		$whereData = []; 
 		if( ! empty( $_GET["owner"] ) )
		{ 
			$whereData["owner_id"] = $_GET["owner"];
		}
		if( ! empty( $_GET["period"] ) )
		{
			$whereData["period_id"] = $_GET["period"];
		}
 
		$data['cetak_grap'] = $this->session->userdata('cetak_grap');
		$data['data']     = $this->data->get_data( $whereData ); 
		$data['picku'] = $this->get_data_dept();

		// doi::dump($data);
 		$x = $this->load->view( 'lap-cetak', $data, TRUE ); 
		$file_name ="report-risk-register.xls";
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$file_name");
		echo $x;
		
	}

}
