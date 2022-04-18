<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Frontend {

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
		$data['slide']=$this->load->view('slide',$data, true);
		$data['service']=$this->load->view('service',$data, true);
		$data['feature']=$this->load->view('feature',$data, true);
		$data['award']=$this->load->view('award',$data, true);
		$data['testimonial']=$this->load->view('testimonial',$data, true);
		$this->hasil=$this->load->view('home',$data, true);

		return $this->hasil;
	}

	function init($aksi=''){
		$configuration = [
			
		];
		return [
			'configuration'	=> $configuration
		];
	}

	function addressbyrowcol($row,$col) {
		return $this->getColFromNumber($col).$row;
	}
	
	
	function getColFromNumber($num) {
		$numeric = ($num - 1) % 26;
		$letter = chr(65 + $numeric);
		$num2 = intval(($num - 1) / 26);
		if ($num2 > 0) {
			return $this->getColFromNumber($num2) . $letter;
		} else {
			return $letter;
		}
	}
}