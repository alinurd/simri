<?php defined('BASEPATH') OR exit('No direct script access allowed');

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
class Doi
{
	private $_ci;
	private $preference=array();

	function __construct()
	{
		$this->_ci =& get_instance();
		require APPPATH . 'libraries/phpmailer/src/Exception.php';
		require APPPATH . 'libraries/phpmailer/src/PHPMailer.php';
		require APPPATH . 'libraries/phpmailer/src/SMTP.php';
		if ($x=$this->_ci->session->userdata('preference')){
			$this->preference=$this->_ci->session->userdata('preference');
		}

	}

	function initialize($config = array())
	{

	}

	public static function now($type='full', $return=true, $data=''){
		$result="";

		if ($data=='')
			$data=time();
		else
			$data=strtotime($data);

		switch($type){
			case 'full':
				$result= date('Y-m-d H:i:s',$data);
				break;
		}
		if ($return)
			return $result;
		else
			echo $result;
	}

	public static function dump($expression , $return = false, $die=false){
        ob_start();
        var_dump($expression);
        $content = ob_get_contents();
        ob_end_clean();

        if($return){
            return $content;
        }else{
            if(isset($_SERVER['argc']) && isset($_SERVER['argv'])){//from cli
                echo '<pre class="doi_dump">';
                echo htmlentities($content);
                echo '</pre>';
			}else{
                echo '<pre class="doi_dump">';
                echo htmlentities($content);
                echo '</pre>';
            }
			if($die)
				die();
        }
    }

	public static function kirim_emailx($data){
		$_ci =& get_instance();
		$preference = $_ci->db->select('*');
		$preference = $_ci->db->get('preference');
			
		$prefs=$preference->result_array();
		foreach($prefs as $key=>$pref){
			$p[$pref['uri_title']]=$pref['value'];
		}
		
		ini_set('MAX_EXECUTION_TIME', -1);
		$subject=$data['subject'];
		//$email_user=$this->_ci->session->userdata('email_user');
		$config = array(
					  'protocol' => $p['email_protocol'],
					  'smtp_host' => $p['email_smtp_host'],
					  'smtp_port' => $p['email_smtp_port'],
					  'smtp_user' => $p['email_smtp_user'],
					  'smtp_pass' => $p['email_smtp_pass'],
					  'mailtype' => $p['email_mailtype'],
					  'charset' => $p['email_charset'],
					  'newline' => "\r\n",
					  'wordwrap' => $p['email_wordwrap'],
					);
		
		$message = $data['content'];
		// $path = $data['file'];
		// Doi::dump($data);
		// Doi::dump($config);
		if (array_key_exists('file', $data)){
			if (is_array($data['file'])){
				foreach($data['file'] as $row){
					$_ci->email->attach($row);
				}
			}else{
				$_ci->email->attach($data['file']);
			}
		}
		
		$_ci->load->library('email', $config);
		$_ci->email->initialize($config);
		$_ci->email->set_newline("\r\n");
		$_ci->email->set_mailtype("html");
		$_ci->email->from($p['email_smtp_user'], $p['email_title']); // change it to yours
		$_ci->email->to($data['email']);// change it to yours
		if (array_key_exists('cc', $data)){
			$_ci->email->cc($data['cc']);// change it to yours
		}
		if (array_key_exists('bcc', $data)){
			$_ci->email->bcc($data['bcc']);// change it to yours
		}
		$_ci->email->subject($subject);
		$_ci->email->message($message);
		if($_ci->email->send())
		{
			$hasil= 'success';
		}
		else
		{
			 $hasil= $_ci->email->print_debugger();
		}
		// die($hasil);
		return $hasil;

	}

	public static function kirim_email($data)
	{
		$_ci = &get_instance();
		$preference = $_ci->db->select('*');
		$preference = $_ci->db->get('preference');

		$prefs = $preference->result_array();
		foreach ($prefs as $key => $pref) {
			$p[$pref['uri_title']] = $pref['value'];
		}

		ini_set('MAX_EXECUTION_TIME', -1);
		$mail = new PHPMailer();
		
		$subject = $data['subject'];
		// SMTP configuration
		$mail->isSMTP();
		$mail->Host     = $p['email_smtp_host']; //sesuaikan sesuai nama domain hosting/server yang digunakan
		
		$mail->SMTPAuth = true;
		$mail->Username = $p['email_smtp_user']; // user email
		$mail->Password = $p['email_smtp_pass']; // password email
		$mail->SMTPSecure = 'tls';
		$mail->Port     = $p['email_smtp_port'];
		
		$mail->Timeout = 60; // timeout pengiriman (dalam detik)
		$mail->SMTPKeepAlive = true;

		$mail->setFrom($p['email_smtp_user'], $p['email_title']); // user email
		
		$mail->addReplyTo('noreply@inalum.id', ''); //user email
		
		// Add a recipient
		foreach ($data['email'] as $key => $value) {
			$mail->addAddress($value); //email tujuan pengiriman email
		}
		if (array_key_exists('cc', $data)) {
			foreach ($data['cc'] as $key => $value) {
				$mail->addCC($value); // change it to yours
			}
		}
		if (array_key_exists('bcc', $data)) {
			foreach ($data['bcc'] as $key => $value) {
				$mail->addBCC($value); // change it to yours
			} // change it to yours
		}
	
		// Email subject
		$mail->Subject = $subject; //subject email

		// Set email format to HTML
		$mail->isHTML(true);
		$message = $data['content'];
		// Email body content
		$mailContent = $message; // isi email
		$mail->Body = $mailContent;

		// Send email
		if (!$mail->send()) {
			$hasil = $mail->ErrorInfo;
		} else {
			$hasil = 'success';
		}

		return $hasil;
	}
}

// END Template class