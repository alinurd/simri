<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */

class Api_Frontend extends REST_Controller {
	protected $api_kel=0;
    function __construct()
    {
        // Construct the parent class
		parent::__construct();
		ini_set('max_execution_time', 150);
        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
		$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key

		$this->load->model('auth/ion_auth_crud', 'crud');
		$this->g = $this->get();
		$this->p = $this->post();
		$this->data->get=$this->g;
		$this->data->post=$this->p;
		$preference = $this->db->select('*')->get(_TBL_PREFERENCE)->result_array();
		$p=[];
		foreach($preference as $key=>$pref){
			$p[$pref['uri_title']]=$pref['value'];
		}
		$this->data->preference=$p;
		$this->preference=$p;
    }

	public function index_get()
    {
        $get = $this->get();
        $data = 'welcome';

		if ($data)
		{
			$this->response(['status'=>true, 'data'=>$data, 'message' =>'Success'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->response([
				'status' => FALSE,
				'message' => 'No data were found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
	}

    public function blog_get()
    {
		$this->data->kel_id=2;

		if (isset($this->g['search'])) {
        	$data = $this->data->get_blog_search();
		}elseif (isset($this->g['uri_title'])) {
        	$data = $this->data->get_blog_detail();
		}else{
        	$data = $this->data->get_data();
		}

		if ($data)
		{
			$this->response(['status'=>true, 'data'=>$data, 'message' =>'Success'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->response([
				'status' => FALSE,
				'message' => 'No data were found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
	}

	public function pages_get()
    {
		$this->data->kel_id=3;
        $data = $this->data->get_data();

		if ($data)
		{
			$this->response(['status'=>true, 'data'=>$data, 'message' =>'Success'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->response([
				'status' => FALSE,
				'message' => 'No data were found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
	}

	public function slides_get()
    {
		$this->data->kel_id=4;
        $data = $this->data->get_data();

		if ($data)
		{
			$this->response(['status'=>true, 'data'=>$data, 'message' =>'Success'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->response([
				'status' => FALSE,
				'message' => 'No data were found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
	}

	public function link_get()
    {
		$this->data->kel_id=6;
        $data = $this->data->get_data();

		if ($data)
		{
			$this->response(['status'=>true, 'data'=>$data, 'message' =>'Success'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->response([
				'status' => FALSE,
				'message' => 'No data were found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
	}

	public function download_get()
    {
		$this->data->kel_id=5;

		if (isset($this->g['uri_title'])) {
        	$data = $this->data->get_download_detail();
		}else{
        	$data = $this->data->get_data();
		}

		if ($data)
		{
			$this->response(['status'=>true, 'data'=>$data, 'message' =>'Success'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->response([
				'status' => FALSE,
				'message' => 'No data were found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
	}

	public function hit_download_post()
    {
		$this->data->apikey=$this->rest->key;
		$data=$this->data->save_hit_download();
		if ($data)
		{
			$this->response(['status'=>true, 'data'=>$data, 'message' =>'Success'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->response([
				'status' => FALSE,
				'message' => 'No data were found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
	}

	public function beranda_get()
    {
        $data = $this->data->get_data_beranda();

		if ($data)
		{
			$this->response(['status'=>true, 'data'=>$data, 'message' =>'Success'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->response([
				'status' => FALSE,
				'message' => 'No data were found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
	}

	public function register_post()
    {
		$this->data->apikey=$this->rest->key;
		$pelanggan = $this->data->hasRegister();
		if ($pelanggan['sts']){
			$errors=[];
			checkPassword($this->p['password'], $errors);
			if (!$errors){
				if (!empty($_FILES['photo']['name'])){
					$this->load->library('image');
					$this->image->set_Param('nm_file', 'photo');
					$this->image->set_Param('file_name', $_FILES['photo']['name']);
					$this->image->set_Param('path',file_path_relative('staft'));
					$this->image->set_Param('thumb',false);
					$this->image->set_Param('type','gif|jpg|jpeg|png');
					$this->image->set_Param('size', 1000000);
					$this->image->set_Param('nm_random', false);
					$this->image->set_Param('multi', false);
					// $this->image->set_Param('split_folder', true);
					$this->image->upload();

					$this->data->photo='staft/'.$this->image->result('file_name');
				}

				$info=$this->data->save_users();

				$token=token();
				$link = '<a href="'.base_url('proses-registration-web?token='.$token).'">disini</a>';
				$content_replace = ['[[nama]]'=>$info['name'],'[[disini]]'=>$link, '[[link]]'=>base_url('proses-registration-web?token='.$token), '[[footer]]'=>$this->preference['footer_email']];

				$datasOutbox=[
					'recipient' => [$this->p['email']],
					'kel_id' => 4,
				];

				$this->load->library('outbox');
				$this->outbox->setTemplate('EML-REG-01');
				$this->outbox->setParams($content_replace);
				$this->outbox->setDatas($datasOutbox);
				$this->outbox->send();

				$jam = date("Y-m-d H:i");
				$valid_daftar_date = date("Y-m-d H:i", strtotime($jam)+(60*60*$this->preference['batas_verifikasi_pendaftaran']));

				$this->crud->crud_table(_TBL_USERS);
				$this->crud->crud_type('edit');
				$this->crud->crud_field('forgotten_password_code', $token);
				$this->crud->crud_field('forgotten_password_time', $valid_daftar_date);
				$this->crud->crud_where(['field'=>'id', 'value'=>$info['id']]);
				$this->crud->process_crud();

				$this->set_response(['status'=>true, 'data'=>$info], REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
			}else{
				$message=implode(",",$errors);
				$this->set_response([
					'status' => FALSE,
					'message' => $message
				], REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
			}
		}else{
			$this->set_response([
				'status' => FALSE,
				'message' => $pelanggan['pesan']
			], REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
		}
	}

	public function login_post()
    {
		$this->data->apikey=$this->rest->key;
		$check = $this->data->cek_valid_user_login();
		if ($check['sts']){
			if ($this->p['is_socmed']){
				if (!empty($check['data']['socmed_type'])){
					$this->response(['status'=>true, 'data'=>$check['data'],'message' => $check['pesan']], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
				}else{
					$this->response(['status' => FALSE,'message' => $check['pesan']], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
				}
			}else{
				if ($this->ion_auth->login($this->p['email'], $this->p['password']))
				{
					$this->response(['status'=>true, 'data'=>$check['data'],'message' => $check['pesan']], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
				}
				else
				{
					$this->response(['status' => FALSE,'message' => 'Email atau Password anda tidak ditemukan'], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
				}
			}
		}else{
			$this->response([
				'status' => FALSE,
				'message' => $check['pesan']
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
	}

	public function profile_get()
    {
		// $this->data->param=$this->rest->params_api;
		$this->data->apikey=$this->rest->key;
		$check = $this->data->cek_valid_user();
		if ($check['sts']){
			$this->response(['status'=>true, 'data'=>$check['data'],'message' => 'success'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}else{
			$this->response([
				'status' => FALSE,
				'message' => $check['pesan']
			], REST_Controller::HTTP_UNAUTHORIZED); // NOT_FOUND (404) being the HTTP response code
		}
	}

	public function profile_post()
    {
		// $this->data->param=$this->rest->params_api;
		$this->data->apikey=$this->rest->key;
		$check = $this->data->cek_valid_user();
		if ($check['sts']){

			if (!empty($_FILES['photo']['name'])){
				$this->load->library('image');
				$this->image->set_Param('nm_file', 'photo');
				$this->image->set_Param('file_name', $_FILES['photo']['name']);
				$this->image->set_Param('path',file_path_relative('staft'));
				$this->image->set_Param('thumb',true);
				$this->image->set_Param('type','gif|jpg|jpeg|png');
				$this->image->set_Param('size', 1000000);
				$this->image->set_Param('nm_random', false);
				$this->image->set_Param('multi', false);
				$this->image->set_Param('split_folder', true);
				$this->image->upload();

				$this->data->photo='staft/'.$this->image->result('file_name');
			}

			$data = $this->data->change_profile();
			if ($data['sts'])
			{
				$this->response(['status'=>true, 'data'=>$data['data'], 'message' => $data['pesan']], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}
			else
			{
				$this->response([
					'status' => FALSE,
					'message' => $data['pesan']
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}else{
			$this->response([
				'status' => FALSE,
				'message' => $check['pesan']
			], REST_Controller::HTTP_UNAUTHORIZED); // NOT_FOUND (404) being the HTTP response code
		}
	}

	public function forgot_password_post()
    {
		$check = $this->data->cek_valid_user();
		if ($check['sts']){
			$token=token();
			$link = '<a href="'.base_url('proses-forgot-password?key='.$token).'">disini</a>';
			$content_replace = ['[[nama]]'=>$check['data']['name'],'[[disini]]'=>$link, '[[link]]'=>base_url('proses-forgot-password?key='.$token), '[[footer]]'=>$this->preference['footer_email']];

			$datasOutbox=[
				'recipient' => [$this->p['email']],
				'kel_id' => 4,
			];

			$this->load->library('outbox');
			$this->outbox->setTemplate('EML-FP-01');
			$this->outbox->setParams($content_replace);
			$this->outbox->setDatas($datasOutbox);
			$this->outbox->send();

			$jam = date("Y-m-d H:i");
			$valid_daftar_date = date("Y-m-d H:i", strtotime($jam)+(60*60*$this->preference['batas_verifikasi_pendaftaran']));

			$this->crud->crud_table(_TBL_USERS);
			$this->crud->crud_type('edit');
			$this->crud->crud_field('forgotten_password_code', $token);
			$this->crud->crud_field('forgotten_password_time', $valid_daftar_date);
			$this->crud->crud_where(['field'=>'id', 'value'=>$check['data']['id']]);
			$this->crud->process_crud();

			$this->response(['status'=>true, 'message' => 'Kami telah mengirim link untuk melakukan reset password ke email anda!'], REST_Controller::HTTP_OK); 
		}else{
			$this->response([
				'status' => FALSE,
				'message' => $check['pesan']
			], REST_Controller::HTTP_UNAUTHORIZED); // NOT_FOUND (404) being the HTTP response code
		}
	}

	public function param_get()
    {
		$data = $this->data->get_param();
		if ($data)
		{
			$this->response(['status'=>true, 'data'=>$data, 'message' => 'Success'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}
		else
		{
			$this->response([
				'status' => FALSE,
				'message' => 'No data were found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
	}

	public function get_validation_data_get()
    {
		$this->data->apikey=$this->rest->key;
		$check = $this->data->cek_valid_user();
		if ($check['sts']){
			if(isset($this->g['tipe_data'])){
				if(intval($this->g['tipe_data'])==2){
					$data = $this->data->get_data_devegetasi();
				}else{
					$data = $this->data->get_data_validation();
				}
			}else{
				$data = $this->data->get_data_validation();
			}

			$this->response(['status'=>true, 'data'=>$data,'message' => 'success'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		}else{
			$this->response([
				'status' => FALSE,
				'message' => $check['pesan']
			], REST_Controller::HTTP_UNAUTHORIZED); // NOT_FOUND (404) being the HTTP response code
		}
	}

	public function validasi_post()
    {
		$this->data->apikey=$this->rest->key;
		$check = $this->data->cek_valid_user();
		if ($check['sts']){
			$data = $this->data->validasi_data();
			if ($data['sts']){
				$this->response(['status'=>true, 'data'=>$data,'message'=>$data['pesan']], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}else{
				$this->response([
					'status' => FALSE,
					'message' => $data['pesan']
				], REST_Controller::HTTP_UNAUTHORIZED); // NOT_FOUND (404) being the HTTP response code
			}
		}else{
			$this->response([
				'status' => FALSE,
				'message' => $check['pesan']
			], REST_Controller::HTTP_UNAUTHORIZED); // NOT_FOUND (404) being the HTTP response code
		}
	}

	public function detail_validasi_get()
    {
		$this->data->apikey=$this->rest->key;
		$check = $this->data->cek_valid_user();
		if ($check['sts']){
			$data = $this->data->get_data_validation_detail();
			if ($data['sts']){
				$this->response(['status'=>true, 'data'=>$data['rows'],'message'=>$data['pesan']], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			}else{
				$this->response([
					'status' => FALSE,
					'message' => $data['pesan']
				], REST_Controller::HTTP_UNAUTHORIZED); // NOT_FOUND (404) being the HTTP response code
			}
		}else{
			$this->response([
				'status' => FALSE,
				'message' => $check['pesan']
			], REST_Controller::HTTP_UNAUTHORIZED); // NOT_FOUND (404) being the HTTP response code
		}
	}

}