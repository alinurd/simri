<?php
defined( 'BASEPATH' ) or exit( 'No direct script access allowed' );

class Task extends MY_Controller
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
	private $dataTmp = [];
	public function __construct()
	{
		$this->today      = date( 'Y-m-d' );
		$this->limit_date = date( 'Y-m-d', strtotime( '+7 days' ) );
		parent::__construct();
		$this->dataTmp['notif'] = [];
		$this->dataTmp['rows']  = [];
	}

	function init( $aksi = '' )
	{
		$configuration = [
		 'show_second_sidebar' => FALSE,
		 'show_action_button'  => FALSE,
		 'show_list_header'    => FALSE,
		 'box_list_header'     => TRUE,
		 'show_title_header'   => FALSE,
		 'content_title'       => 'Taks & FAQ',
		];
		return [
		 'configuration' => $configuration,
		];
	}

	function content( $ty = 'detail' )
	{

		$data['upcoming'] = $this->db
		 ->where( 'batas_waktu >=', $this->today )
		 ->where( 'batas_waktu <=', $this->limit_date )
		 ->order_by( 'batas_waktu', 'ASC' )
		 ->get( _TBL_VIEW_RCSA_MITIGASI_DETAIL )
		 ->result_array();
		$data['overdue']  = $this->db
		->where( 'batas_waktu <', $this->today )
		->order_by( 'batas_waktu', 'ASC' )
		->get( _TBL_VIEW_RCSA_MITIGASI_DETAIL )
		->result_array();

		$data["faq"] = $this->db->get_where( _TBL_FAQ, [ "active" => 1 ] )->result_array();
		$content     = $this->load->view( 'task', $data, TRUE );

		return $content;
	}

	function setContentHeader( $mode = '' )
	{
		$content = [];
		return $content;
	}

	function secondSidebarData()
	{
		$content['data'] = $this->dataTmp['notif'];
		return $content;
	}

	function get_ceklog()
	{
		$post        = $this->input->post();
		$data['log'] = $this->db->where( 'ref_id', $post['id'] )->get( "il_log_send_email" )->result_array();

		$result = $this->load->view( 'cekLog', $data, TRUE );
		header( 'Content-Type: application/json' );
		echo json_encode( [ 'combo' => $result ] );
	}

	function sen_email()
	{

		$post = $this->input->post();

	}

}
