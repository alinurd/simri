<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Portfolio extends MY_Frontend {

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

	function detail(){
		$param = $this->uri->rsegment_array();
		$target = strtolower($param[3]);
		$target=str_replace('_','-',$target);
		$this->data->uri_title=$target;
		$data=$this->data->get_data($target);
		$content = $this->load->view('view',$data, true);
		$this->default_display(['content'=>$content]);
	}

	function init($aksi=''){
		$configuration = [

		];
		return [
			'configuration'	=> $configuration
		];
	}
}