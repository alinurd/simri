<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Knowledgebase extends MY_Controller
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
		parent::__construct();
		require APPPATH . 'libraries/phpmailer/src/Exception.php';
		require APPPATH . 'libraries/phpmailer/src/PHPMailer.php';
		require APPPATH . 'libraries/phpmailer/src/SMTP.php';

		$this->dataTmp['notif'] = [];
		$this->dataTmp['rows'] = [];
	}

	function init($aksi = '')
	{
		$configuration = [
			'show_action_button' => FALSE,
			'show_list_header' => false,
			'box_list_header' => false,
			'box_content' => false,
			'show_title_header' => false,
			'content_title' => 'Home'
		];
		return [
			'configuration'	=> $configuration
		];
	}

	function content($ty = 'detail')
	{
		$data = [];
		$content = $this->load->view('home', $this->dataTmp, true);

		return $content;
	}

	function setContentHeader($mode = '')
	{
		$content = $this->load->view('header', $this->dataTmp, true);
		return $content;
	}

	function secondSidebarData()
	{
		$content['data'] = $this->dataTmp['notif'];
		return $content;
	}

	function tes()
	{

		// PHPMailer object
		$response = false;
		$mail = new PHPMailer();

		// SMTP configuration
		$mail->isSMTP();
		$mail->Host     = 'smtp.office365.com'; //sesuaikan sesuai nama domain hosting/server yang digunakan
		$mail->SMTPAuth = true;
		$mail->Username = 'simri@inalum.id'; // user email
		$mail->Password = 'S1mr12022#'; // password email
		$mail->SMTPSecure = 'tls';
		$mail->Port     = 587;

		$mail->Timeout = 60; // timeout pengiriman (dalam detik)
		$mail->SMTPKeepAlive = true;

		$mail->setFrom('simri@inalum.id', ''); // user email
		$mail->addReplyTo('simri@inalum.id', ''); //user email

		// Add a recipient
		$mail->addAddress('jabbar@inalum.id'); //email tujuan pengiriman email

		// Email subject

		$mail->Subject = 'SMTP Codeigniter'; //subject email


		// Set email format to HTML
		$mail->isHTML(true);

		// Email body content
		$mailContent = "<h1>SMTP Codeigniterr</h1>
                        <p>Laporan email SMTP Codeigniter.</p>"; // isi email
		$mail->Body = $mailContent;

		// Send email
		if (!$mail->send()) {
			echo 'Message could not be sent.';
			echo 'Mailer Error: ' . $mail->ErrorInfo;
		} else {
			echo 'Message has been sent';
		}
	}
}
