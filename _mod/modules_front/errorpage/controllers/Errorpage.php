<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Errorpage extends MY_Frontend {

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


	function modul(){
		$hasil=$this->load->view('info-class',[], true);
		$this->default_display(['content'=>$hasil]);
	}

	function content($ty='detail'){
		$this->hasil=$this->load->view('info-class',[], true);

		return $this->hasil;
	}

	function init($aksi=''){
		$configuration = [
			'show_title_header' => false,
			'show_action_button' => false,
			'content_title' => 'Not allowed'
		];
		return [
			'configuration'	=> $configuration
		];
	}
}