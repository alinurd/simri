<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Curl extends MX_Controller {

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

	public function index(){
		$data['data'] = json_decode($this->guzzle_get('http://pusatkrisis.kemkes.go.id/api_provinsi/'), true);
		$this->load->view('welcome_message',$data);
	}

	public function guzzle_get($url){
		$params = [
			'uid' => 'irBqbsb5',
			'domain' => 'surveilans.org',
			'reguid' => 'l',
		];
		$client = new GuzzleHttp\Client();
		$response = $client->request('GET', $url, ['query'=>$params]);
		return $response->getBody()->getContents();
	}

}