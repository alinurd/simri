<?php

defined('BASEPATH') or exit('No direct script access allowed');

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

class Api extends REST_Controller
{
	protected $api_kel = 0;
	protected $max_photo = 4;
	function __construct()
	{
		// Construct the parent class
		parent::__construct();
		$this->config->set_item('csrf_protection', FALSE);
		ini_set('max_execution_time', 150);
		// Configure limits on our controller methods
		// Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
		$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
		$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
		$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key

		$this->load->model('auth/ion_auth_crud', 'crud');
		$this->g = $this->get();
		$this->p = $this->post();
		$this->data->get = $this->g;
		$this->data->post = $this->p;

		$preference = $this->db->select('*')->get(_TBL_PREFERENCE)->result_array();
		$p = [];
		foreach ($preference as $key => $pref) {
			$p[$pref['uri_title']] = $pref['value'];
		}
		$this->data->preference = $p;
		$this->preference = $p;
	}

	public function index_get()
	{
		$message = 'Selamat Datang di API Simontana, berikut API yang tersedia di Mode GET';
		$data['param'] = 'Mengambil data parameter seperti: institusi, wilayah, propinsi, kota, tutupan, preference';

		if (ENVIRONMENT !== 'production') {
			$this->response(['status' => true, 'message' => $message, 'data' => $data], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		} else {
			$this->response([
				'status' => FALSE,
				'message' => 'No data were found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
	}

	public function index_post()
	{
		$message = 'Selamat Datang di API Simontana, berikut API yang tersedia di Mode POST';
		$data['login'] = 'prosedur login ke aplikasi mobile';
		$data['profile'] = 'Merubah data profile pengguna, termasuk password dan photo profile';
		$data['register'] = 'Mendaftarkan data user baru';
		$data['cek-lapangan'] = 'Mengirimkan data cek lapangan';
		$data['forgot-password'] = 'Mereset password penggunana';

		if (ENVIRONMENT !== 'production') {
			$this->response(['status' => true, 'message' => $message, 'data' => $data], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		} else {
			$this->response([
				'status' => FALSE,
				'message' => 'No data were found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
	}

	public function register_post()
	{
		// $this->data->param=$this->rest->params_api;
		$this->data->apikey = $this->rest->key;
		$pelanggan = $this->data->hasRegister();
		if ($pelanggan['sts']) {
			$errors = [];
			checkPassword($this->p['password'], $errors);
			if (!$errors) {
				if (!empty($_FILES['photo']['name'])) {
					$this->load->library('image');
					$this->image->set_Param('nm_file', 'photo');
					$this->image->set_Param('file_name', $_FILES['photo']['name']);
					$this->image->set_Param('path', file_path_relative('staft'));
					$this->image->set_Param('thumb', false);
					$this->image->set_Param('type', 'gif|jpg|jpeg|png');
					$this->image->set_Param('size', 1000000);
					$this->image->set_Param('nm_random', false);
					$this->image->set_Param('multi', false);
					// $this->image->set_Param('split_folder', true);
					$this->image->upload();

					$this->data->photo = 'staft/' . $this->image->result('file_name');
				}

				$info = $this->data->save_users();

				$token = token();
				$link = '<a href="' . base_url('proses-registration?token=' . $token) . '">disini</a>';
				$content_replace = ['[[nama]]' => $info['name'], '[[disini]]' => $link, '[[link]]' => base_url('proses-registration?token=' . $token), '[[footer]]' => $this->preference['footer_email']];

				$datasOutbox = [
					'recipient' => [$this->p['email']],
					'kel_id' => 4,
				];
				if ($this->preference['send_notif'] == 1) {
					$this->load->library('outbox');
					$this->outbox->setTemplate('EML-REG-01');
					$this->outbox->setParams($content_replace);
					$this->outbox->setDatas($datasOutbox);
					$this->outbox->send();
				}

				$jam = date("Y-m-d H:i");
				$valid_daftar_date = date("Y-m-d H:i", strtotime($jam) + (60 * 60 * $this->preference['batas_verifikasi_pendaftaran']));

				$this->crud->crud_table(_TBL_USERS);
				$this->crud->crud_type('edit');
				$this->crud->crud_field('forgotten_password_code', $token);
				$this->crud->crud_field('forgotten_password_time', $valid_daftar_date);
				$this->crud->crud_where(['field' => 'id', 'value' => $info['id']]);
				$this->crud->process_crud();

				$this->set_response(['status' => true, 'data' => $info], REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
			} else {
				$message = implode(",", $errors);
				$this->set_response([
					'status' => FALSE,
					'message' => $message
				], REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
			}
		} else {
			$this->set_response([
				'status' => FALSE,
				'message' => $pelanggan['pesan']
			], REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
		}
	}

	public function param_get()
	{
		$data = $this->data->get_param();
		if ($data) {
			$this->response(['status' => true, 'data' => $data, 'message' => 'Success'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		} else {
			$this->response([
				'status' => FALSE,
				'message' => 'No data were found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
	}

	public function profile_get()
	{
		// $this->data->param=$this->rest->params_api;
		$this->data->apikey = $this->rest->key;
		$check = $this->data->cek_valid_user();
		if ($check['sts']) {
			$this->response(['status' => true, 'data' => $check['data'], 'message' => 'success'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		} else {
			$this->response([
				'status' => FALSE,
				'message' => $check['pesan']
			], REST_Controller::HTTP_UNAUTHORIZED); // NOT_FOUND (404) being the HTTP response code
		}
	}

	public function get_otp_get()
	{
		$check = $this->data->cek_valid_user($this->g['email']);
		if ($check['sts']) {
			$token = token($this->preference['otp_digit'], $this->preference['otp_type'], '', '', _TBL_USERS);
			$link = '<a href="' . base_url('proses-forgot-password?key=' . $token) . '">disini</a>';
			$content_replace = ['[[nama]]' => $check['data']['name'], '[[kode]]' => $token, '[[footer]]' => $this->preference['footer_email']];

			$datasOutbox = [
				'recipient' => [$this->g['email']],
				'kel_id' => 4,
			];
			if ($this->preference['send_notif'] == 1) {
				$this->load->library('outbox');
				$this->outbox->setTemplate('EML-FP-01');
				$this->outbox->setParams($content_replace);
				$this->outbox->setDatas($datasOutbox);
				$this->outbox->send();
			}

			$jam = date("Y-m-d H:i");
			$valid_daftar_date = date("Y-m-d H:i", strtotime($jam) + (60 * 60 * $this->preference['batas_verifikasi_pendaftaran']));

			$this->crud->crud_table(_TBL_USERS);
			$this->crud->crud_type('edit');
			$this->crud->crud_field('forgotten_password_code', $token);
			$this->crud->crud_field('forgotten_password_time', $valid_daftar_date);
			$this->crud->crud_where(['field' => 'id', 'value' => $check['data']['id']]);
			$this->crud->process_crud();

			$this->response(['status' => true, 'message' => 'Kami telah mengirim link untuk melakukan reset password ke email anda!'], REST_Controller::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message' => $check['pesan']
			], REST_Controller::HTTP_UNAUTHORIZED); // NOT_FOUND (404) being the HTTP response code
		}
	}


	public function cek_otp_post()
	{

		$data = $this->data->cek_otp();
		if ($data['sts']) {
			$this->response(['status' => true, 'data' => $data['data'], 'message' => $data['pesan']], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		} else {
			$this->response([
				'status' => FALSE,
				'message' => $data['pesan']
			], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
		}
	}

	public function proses_forgot_password_post()
	{

		$data = $this->data->proses_forgot_password();
		if ($data['sts']) {
			$this->response(['status' => true, 'message' => $data['pesan']], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		} else {
			$this->response([
				'status' => FALSE,
				'message' => $data['pesan']
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
	}

	public function change_password_post()
	{
		// $this->data->param=$this->rest->params_api;
		$this->data->apikey = $this->rest->key;
		$check = $this->data->cek_valid_user();
		if ($check['sts']) {

			$data = $this->data->change_password();
			if ($data['sts']) {
				$this->response(['status' => true, 'data' => $data['data'], 'message' => $data['pesan']], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			} else {
				$this->response([
					'status' => FALSE,
					'message' => $data['pesan']
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		} else {
			$this->response([
				'status' => FALSE,
				'message' => $check['pesan']
			], REST_Controller::HTTP_UNAUTHORIZED); // NOT_FOUND (404) being the HTTP response code
		}
	}

	public function profile_post()
	{
		// $this->data->param=$this->rest->params_api;
		$this->data->apikey = $this->rest->key;
		$check = $this->data->cek_valid_user();
		if ($check['sts']) {

			if (!empty($_FILES['photo']['name'])) {
				$this->load->library('image');
				$this->image->set_Param('nm_file', 'photo');
				$this->image->set_Param('file_name', $_FILES['photo']['name']);
				$this->image->set_Param('path', file_path_relative('staft'));
				$this->image->set_Param('thumb', true);
				$this->image->set_Param('type', 'gif|jpg|jpeg|png');
				$this->image->set_Param('size', 1000000);
				$this->image->set_Param('nm_random', false);
				$this->image->set_Param('multi', false);
				$this->image->set_Param('split_folder', true);
				$this->image->upload();

				$this->data->photo = 'staft/' . $this->image->result('file_name');
			}

			$data = $this->data->change_profile();
			if ($data['sts']) {
				$this->response(['status' => true, 'data' => $data['data'], 'message' => $data['pesan']], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			} else {
				$this->response([
					'status' => FALSE,
					'message' => $data['pesan']
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		} else {
			$this->response([
				'status' => FALSE,
				'message' => $check['pesan']
			], REST_Controller::HTTP_UNAUTHORIZED); // NOT_FOUND (404) being the HTTP response code
		}
	}

	public function cek_lapangan_get()
	{
		$this->data->apikey = $this->rest->key;
		$check = $this->data->cek_valid_user();
		if ($check['sts']) {
			$data = $this->data->get_data_lapangan();
			$this->response(['status' => true, 'data' => $data, 'message' => 'success'], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		} else {
			$this->response([
				'status' => FALSE,
				'message' => $check['pesan']
			], REST_Controller::HTTP_UNAUTHORIZED); // NOT_FOUND (404) being the HTTP response code
		}
	}

	public function delete_cek_lapangan_post()
	{
		$this->data->apikey = $this->rest->key;
		$check = $this->data->cek_valid_user();
		if ($check['sts']) {
			$data = $this->data->delete_data_lapangan();
			if ($data['sts']) {
				$this->response(['status' => true, 'message' => $data['pesan']], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			} else {
				$this->response([
					'status' => FALSE,
					'message' => $data['pesan']
				], REST_Controller::HTTP_UNAUTHORIZED); // NOT_FOUND (404) being the HTTP response code
			}
		} else {
			$this->response([
				'status' => FALSE,
				'message' => $check['pesan']
			], REST_Controller::HTTP_UNAUTHORIZED); // NOT_FOUND (404) being the HTTP response code
		}
	}

	public function validasi_cek_lapangan_post()
	{
		$this->data->apikey = $this->rest->key;
		$check = $this->data->cek_valid_user();
		if ($check['sts']) {
			$data = $this->data->validasi_data_lapangan();
			if ($data['sts']) {
				$this->response(['status' => true, 'data' => $data, 'message' => $data['pesan']], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			} else {
				$this->response([
					'status' => FALSE,
					'message' => $data['pesan']
				], REST_Controller::HTTP_UNAUTHORIZED); // NOT_FOUND (404) being the HTTP response code
			}
		} else {
			$this->response([
				'status' => FALSE,
				'message' => $check['pesan']
			], REST_Controller::HTTP_UNAUTHORIZED); // NOT_FOUND (404) being the HTTP response code
		}
	}


	public function edit_cek_lapangan_post()
	{

		$this->data->apikey = $this->rest->key;
		$check = $this->data->cek_valid_user();
		if ($check['sts']) {
			$this->process_upload_image('edit');
			$data = $this->data->save_cek_lapangan('edit');
			if ($data['sts']) {
				$this->response(['status' => true, 'data' => $data['data'], 'message' => $data['pesan']], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			} else {
				$this->response([
					'status' => FALSE,
					'message' => $data['pesan']
				], REST_Controller::HTTP_UNAUTHORIZED); // NOT_FOUND (404) being the HTTP response code
			}
		} else {
			$this->response([
				'status' => FALSE,
				'message' => $check['pesan']
			], REST_Controller::HTTP_UNAUTHORIZED); // NOT_FOUND (404) being the HTTP response code
		}
	}

	function process_upload_image($mode = 'add')
	{
		$upload_image = [];
		$photo_parent = 100;
		$photo_id_1 = 200;
		$photo_id_2 = 300;
		$this->load->library('image');
		$this->db->insert(_TBL_ERROR_LOGS, ['errstr' => json_encode($_FILES)]);
		$upload_image_tmp = [];
		if (array_key_exists('utara', $_FILES)) {
			$path = 'file_path_relative';
			$jml = count($_FILES['utara']['name']);
			$files = $_FILES;
			for ($x = 0; $x < $jml; ++$x) {
				if (!empty($files['utara']['name'][$x])) {
					$_FILES['upload_imagex']['name'] = $files['utara']['name'][$x];
					$_FILES['upload_imagex']['type'] = $files['utara']['type'][$x];
					$_FILES['upload_imagex']['tmp_name'] = $files['utara']['tmp_name'][$x];
					$_FILES['upload_imagex']['error'] = $files['utara']['error'][$x];
					$_FILES['upload_imagex']['size'] = $files['utara']['size'][$x];

					$this->image->set_Param('nm_file', 'upload_imagex');
					$this->image->set_Param('file_name', $_FILES['utara']['name'][$x]);
					$this->image->set_Param('path', file_path_relative('monitoring'));
					$this->image->set_Param('thumb', true);
					$this->image->set_Param('type', '*');
					// $this->image->set_Param('type','gif|jpg|jpeg|png|bmp');
					$this->image->set_Param('size', 1000000);
					$this->image->set_Param('nm_random', false);
					$this->image->set_Param('multi', true);
					$this->image->set_Param('image_no', $x);
					$this->image->set_Param('split_folder', true);

					$this->image->upload();
					// $this->image->result();
					if ($this->image->result('file_name') !== 'error')
						$upload_image_tmp[] = ['id' => ++$photo_id_1, 'photo_id' => ++$photo_id_2, 'photo_path' => 'monitoring/' . $this->image->result('file_name')];
				}
			}

			$yy = $this->max_photo - ($this->max_photo - count($upload_image_tmp));
			for ($xx = $yy; $xx < $this->max_photo; ++$xx) {
				$upload_image_tmp[] = ['id' => ++$photo_id_1, 'photo_id' => ++$photo_id_2, 'photo_path' => null];
			}
		} else {
			for ($x = 0; $x < $this->max_photo; ++$x) {
				$upload_image_tmp[] = ['id' => ++$photo_id_1, 'photo_id' => ++$photo_id_2, 'photo_path' => null];
			}
		}
		// die('debug');
		$upload_image[] = ['id' => ++$photo_parent, 'name' => 'Utara', 'photos' => $upload_image_tmp, 'isExpanded' => true];

		$upload_image_tmp = [];
		if (array_key_exists('selatan', $_FILES)) {
			$path = 'file_path_relative';
			$jml = count($_FILES['selatan']['name']);
			$files = $_FILES;
			for ($x = 0; $x < $jml; ++$x) {
				if (!empty($files['selatan']['name'][$x])) {
					$_FILES['upload_imagex']['name'] = $files['selatan']['name'][$x];
					$_FILES['upload_imagex']['type'] = $files['selatan']['type'][$x];
					$_FILES['upload_imagex']['tmp_name'] = $files['selatan']['tmp_name'][$x];
					$_FILES['upload_imagex']['error'] = $files['selatan']['error'][$x];
					$_FILES['upload_imagex']['size'] = $files['selatan']['size'][$x];

					$this->image->set_Param('nm_file', 'upload_imagex');
					$this->image->set_Param('file_name', $_FILES['selatan']['name'][$x]);
					$this->image->set_Param('path', file_path_relative('monitoring'));
					$this->image->set_Param('thumb', true);
					$this->image->set_Param('type', '*');
					$this->image->set_Param('size', 1000000);
					$this->image->set_Param('nm_random', false);
					$this->image->set_Param('multi', true);
					$this->image->set_Param('image_no', $x);
					$this->image->set_Param('split_folder', true);

					$this->image->upload();

					if ($this->image->result('file_name') !== 'error')
						$upload_image_tmp[] = ['id' => ++$photo_id_1, 'photo_id' => ++$photo_id_2, 'photo_path' => 'monitoring/' . $this->image->result('file_name')];
				}
			}
			$yy = $this->max_photo - ($this->max_photo - count($upload_image_tmp));
			for ($xx = $yy; $xx < $this->max_photo; ++$xx) {
				$upload_image_tmp[] = ['id' => ++$photo_id_1, 'photo_id' => ++$photo_id_2, 'photo_path' => null];
			}
		} else {
			for ($x = 0; $x < $this->max_photo; ++$x) {
				$upload_image_tmp[] = ['id' => ++$photo_id_1, 'photo_id' => ++$photo_id_2, 'photo_path' => null];
			}
		}
		$upload_image[] = ['id' => ++$photo_parent, 'name' => 'Selatan', 'photos' => $upload_image_tmp];

		$upload_image_tmp = [];
		if (array_key_exists('timur', $_FILES)) {
			$path = 'file_path_relative';
			$jml = count($_FILES['timur']['name']);
			$files = $_FILES;
			for ($x = 0; $x < $jml; ++$x) {
				if (!empty($files['timur']['name'][$x])) {
					$_FILES['upload_imagex']['name'] = $files['timur']['name'][$x];
					$_FILES['upload_imagex']['type'] = $files['timur']['type'][$x];
					$_FILES['upload_imagex']['tmp_name'] = $files['timur']['tmp_name'][$x];
					$_FILES['upload_imagex']['error'] = $files['timur']['error'][$x];
					$_FILES['upload_imagex']['size'] = $files['timur']['size'][$x];

					$this->image->set_Param('nm_file', 'upload_imagex');
					$this->image->set_Param('file_name', $_FILES['timur']['name'][$x]);
					$this->image->set_Param('path', file_path_relative('monitoring'));
					$this->image->set_Param('thumb', true);
					$this->image->set_Param('type', '*');
					$this->image->set_Param('size', 1000000);
					$this->image->set_Param('nm_random', false);
					$this->image->set_Param('multi', true);
					$this->image->set_Param('image_no', $x);
					$this->image->set_Param('split_folder', true);

					$this->image->upload();

					if ($this->image->result('file_name') !== 'error')
						$upload_image_tmp[] = ['id' => ++$photo_id_1, 'photo_id' => ++$photo_id_2, 'photo_path' => 'monitoring/' . $this->image->result('file_name')];
				}
			}
			$yy = $this->max_photo - ($this->max_photo - count($upload_image_tmp));
			for ($xx = $yy; $xx < $this->max_photo; ++$xx) {
				$upload_image_tmp[] = ['id' => ++$photo_id_1, 'photo_id' => ++$photo_id_2, 'photo_path' => null];
			}
		} else {
			for ($x = 0; $x < $this->max_photo; ++$x) {
				$upload_image_tmp[] = ['id' => ++$photo_id_1, 'photo_id' => ++$photo_id_2, 'photo_path' => null];
			}
		}
		$upload_image[] = ['id' => ++$photo_parent, 'name' => 'Timur', 'photos' => $upload_image_tmp];

		$upload_image_tmp = [];
		if (array_key_exists('barat', $_FILES)) {
			$path = 'file_path_relative';
			$jml = count($_FILES['barat']['name']);
			$files = $_FILES;
			for ($x = 0; $x < $jml; ++$x) {
				if (!empty($files['barat']['name'][$x])) {
					$_FILES['upload_imagex']['name'] = $files['barat']['name'][$x];
					$_FILES['upload_imagex']['type'] = $files['barat']['type'][$x];
					$_FILES['upload_imagex']['tmp_name'] = $files['barat']['tmp_name'][$x];
					$_FILES['upload_imagex']['error'] = $files['barat']['error'][$x];
					$_FILES['upload_imagex']['size'] = $files['barat']['size'][$x];

					$this->image->set_Param('nm_file', 'upload_imagex');
					$this->image->set_Param('file_name', $_FILES['barat']['name'][$x]);
					$this->image->set_Param('path', file_path_relative('monitoring'));
					$this->image->set_Param('thumb', true);
					$this->image->set_Param('type', '*');
					$this->image->set_Param('size', 1000000);
					$this->image->set_Param('nm_random', false);
					$this->image->set_Param('multi', true);
					$this->image->set_Param('image_no', $x);
					$this->image->set_Param('split_folder', true);

					$this->image->upload();

					if ($this->image->result('file_name') !== 'error')
						$upload_image_tmp[] = ['id' => ++$photo_id_1, 'photo_id' => ++$photo_id_2, 'photo_path' => 'monitoring/' . $this->image->result('file_name')];
				}
			}
			$yy = $this->max_photo - ($this->max_photo - count($upload_image_tmp));
			for ($xx = $yy; $xx < $this->max_photo; ++$xx) {
				$upload_image_tmp[] = ['id' => ++$photo_id_1, 'photo_id' => ++$photo_id_2, 'photo_path' => null];
			}
		} else {
			for ($x = 0; $x < $this->max_photo; ++$x) {
				$upload_image_tmp[] = ['id' => ++$photo_id_1, 'photo_id' => ++$photo_id_2, 'photo_path' => null];
			}
		}
		$upload_image[] = ['id' => ++$photo_parent, 'name' => 'Barat', 'photos' => $upload_image_tmp];

		$this->data->photo = json_encode($upload_image);
		if ($mode == 'edit') {
			$this->data->photo = '';
		}
	}

	public function cek_lapangan_post()
	{
		$this->data->apikey = $this->rest->key;
		$check = $this->data->cek_valid_user();
		// dumps($_FILES);
		if ($check['sts']) {
			$this->process_upload_image();
			$data = $this->data->save_cek_lapangan();

			$content_replace = ['[[nama]]' => $check['data']['name'], '[[nolap]]' => $data['data']['lap_no'], '[[footer]]' => $this->preference['footer_email']];

			$datasOutbox = [
				'recipient' => [$check['data']['email']],
				'kel_id' => 5,
			];

			if ($this->preference['send_notif'] == 1) {
				$this->load->library('outbox');
				$this->outbox->setTemplate('EML-LAP-01');
				$this->outbox->setParams($content_replace);
				$this->outbox->setDatas($datasOutbox);
				$this->outbox->send();
			}

			if ($data['sts']) {
				$this->response(['status' => true, 'data' => $data['data'], 'message' => $data['pesan']], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			} else {
				$this->response([
					'status' => FALSE,
					'message' => $data['pesan']
				], REST_Controller::HTTP_UNAUTHORIZED); // NOT_FOUND (404) being the HTTP response code
			}
		} else {
			$this->response([
				'status' => FALSE,
				'message' => $check['pesan']
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
	}

	public function login_post()
	{
		// $this->data->param=$this->rest->params_api;
		$this->data->apikey = $this->rest->key;
		$check = $this->data->cek_valid_user_login();
		if ($check['sts']) {
			// if ($this->p['is_socmed']){
			// 	if (!empty($check['data']['socmed_type'])){
			// 		$this->response(['status'=>true, 'data'=>$check['data'],'message' => $check['pesan']], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			// 	}else{
			// 		$this->response(['status' => FALSE,'message' => $check['pesan']], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			// 	}
			// }else{
			if ($this->ion_auth->login($this->p['email'], $this->p['password'])) {
				$this->response(['status' => true, 'data' => $check['data'], 'message' => $check['pesan']], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			} else {
				$this->response(['status' => FALSE, 'message' => 'Email atau Password anda tidak ditemukan'], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
			// }
		} else {
			$this->response([
				'status' => FALSE,
				'message' => $check['pesan']
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
	}

	public function lapan_get()
	{
		if ($this->g) {
			$id = $this->data->save_data_lapan();
			$tbl = '<table width="100%">';
			foreach ($this->get as $key => $x) {
				$tbl .= '<tr><td>' . $key . '</td><td>' . $x . '</td></tr>';
			}
			$tbl .= '</table>';
			$dat['email'] = ['simontana.app@gmail.com'];
			$dat['subject'] = 'Lapan kirim data Get ' . $id;
			$dat['content'] = 'Lapan telah mengirimkan data dengan mode GET pada tanggal ' . date('d-M-Y H:i:s') . '<br/>' . $tbl;

			$x = Doi::kirim_email($dat);

			$this->response(['status' => true, 'message' => 'Success', 'data' => ['transaction_id' => $id]], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		} else {
			$this->response([
				'status' => FALSE,
				'message' => 'No data param found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
	}

	public function lapan_post()
	{
		if ($this->p) {
			$id = $this->data->save_data_lapan();
			$tbl = '<table width="100%">';
			foreach ($this->post as $key => $x) {
				$tbl .= '<tr><td>' . $key . '</td><td>' . $x . '</td></tr>';
			}
			$dat['email'] = ['abutiara@gmail.com'];
			$dat['subject'] = 'Lapan kirim data Post ' . $id;
			$dat['content'] = 'Lapan telah mengirimkan data dengan mode POST pada tanggal ' . date('d-M-Y H:i:s') . '<br/>' . $tbl;

			$x = Doi::kirim_email($dat);
			$this->response(['status' => true, 'message' => 'Success', 'data' => ['transaction_id' => $id]], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		} else {
			$this->response([
				'status' => FALSE,
				'message' => 'No data param found'
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
	}

	public function detail_cek_lapangan_get()
	{
		$this->data->apikey = $this->rest->key;
		$check = $this->data->cek_valid_user();
		if ($check['sts']) {
			$data = $this->data->get_cek_lapangan_detail();
			if ($data['sts']) {
				$this->response(['status' => true, 'data' => $data['rows'], 'message' => $data['pesan']], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			} else {
				$this->response([
					'status' => FALSE,
					'message' => $data['pesan']
				], REST_Controller::HTTP_UNAUTHORIZED); // NOT_FOUND (404) being the HTTP response code
			}
		} else {
			$this->response([
				'status' => FALSE,
				'message' => $check['pesan']
			], REST_Controller::HTTP_UNAUTHORIZED); // NOT_FOUND (404) being the HTTP response code
		}
	}

	public function error_get()
	{
		$this->response([
			'status' => FALSE,
			'message' => 'No data were foundx'
		], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
	}
}
