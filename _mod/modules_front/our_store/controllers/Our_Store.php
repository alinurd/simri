<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Our_Store extends MY_Frontend {

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
	private $dataTmp=[];
	public function __construct()
	{
		parent::__construct();
		
	}

	function content($ty='detail'){
		$data=$this->data->get_data();
		$this->hasil=$this->load->view('view',$data, true);

		return $this->hasil;
	}

	function init($aksi=''){
		$configuration = [

		];
		return [
			'configuration'	=> $configuration
		];
	}

	function save_message(){
		$post=$this->input->post();
		$ins['type_id']=1;
		$ins['name']=$post['name'];
		$ins['email']=$post['email'];
		$ins['phone']=$post['phone'];
		$ins['website']=$post['website'];
		$ins['message']=$post['pesan'];
		$this->db->insert(_TBL_INBOX, $ins);
		$hasil = $this->content();
		$this->session->set_flashdata('contact', 'data anda Sudah berhasil tersimpan dalam database.');
		header('location:'.base_url('career'));
	}
}