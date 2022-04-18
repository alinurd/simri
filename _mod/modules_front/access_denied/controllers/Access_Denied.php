<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Access_Denied extends MY_Controller {

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
	protected $data=[];
	public function __construct()
	{
		parent::__construct();
	}


	function modul(){
		
		$this->default_display(['content'=>$this->load->view('info-class',[], true)]);
	}
	function content($ty='detail'){
		// die('sippp');
		$this->hasil=$this->load->view('info',[], true);

		return $this->hasil;
	}

	function init($aksi=''){
		$configuration = [
			'show_title_header' => false, 
			'show_action_button' => false, 
			'show_action_button' => false, 
			'content_title' => 'Info'
		];
		return [
			'configuration'	=> $configuration
		];
	}
}