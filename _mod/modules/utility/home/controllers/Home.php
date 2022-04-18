<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

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
		$this->dataTmp['notif']=[];
		$this->dataTmp['rows']=[];
	}

	function init($aksi=''){
		$configuration = [
			'show_second_sidebar' => false,
			'show_action_button' => FALSE,
			'show_list_header' => false,
			'box_list_header' => false,
			'show_title_header' => false,
			'content_title' => 'Home'
		];
		return [
			'configuration'	=> $configuration
		];
	}

	function content($ty='detail'){
		$data=[];
		$content=$this->load->view('home',$this->dataTmp, true);
		
		return $content;
	}

	function setContentHeader($mode=''){
		$content=$this->load->view('header',$this->dataTmp, true);
		return $content;
	}

	function secondSidebarData(){
		$content['data'] =$this->dataTmp['notif'];
		return $content;
	}
}