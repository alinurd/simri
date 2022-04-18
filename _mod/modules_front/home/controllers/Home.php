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

		// $rows = $this->db->get(_TBL_COMBO)->result();
		// foreach ($rows as $row){
		// 	$uri = create_unique_slug($row->data, _TBL_COMBO);
		// 	$this->db->where('id', $row->id);
		// 	$this->db->update(_TBL_COMBO, ['uri_title'=>$uri]);
		// }
		// die('selelsai');

	}

	function content($ty='detail'){
		$data=$this->data->get_data();
		$data['preference']=$this->preference;;
		$data['slide']=$this->load->view('slide',$data, true);
		$data['service']=$this->load->view('service',$data, true);
		$data['feature']=$this->load->view('feature',$data, true);
		$data['award']=$this->load->view('award',$data, true);
		$data['testimonial']=$this->load->view('testimonial',$data, true);
		$data['newsletter']=$this->load->view('newsletter',$data, true);
		$this->hasil=$this->load->view('home',$data, true);

		return $this->hasil;
	}

	function init($aksi=''){
		$configuration = [
			'title'=>'Anda berada di home',
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

	function save_newsletter(){
		$post=$this->input->post();
		$rows = $this->db->where('email', $post['email'])->get(_TBL_NEWSLETTER)->row_array();
		if ($rows){
			$this->session->set_flashdata('newsletter_failed', $rows);
			header('location:'.base_url('home/confirm'));
		}else{
			$ins['email']=$post['email'];
			$ins['token']=token();
			$ins['active']=1;
			$this->db->insert(_TBL_NEWSLETTER, $ins);
			$this->session->set_flashdata('newsletter', $post['email']);
			header('location:'.base_url('home/confirm'));
		}
	}

	function confirm(){
		$this->load->library('outbox');
		$email_failed = $this->session->flashdata('newsletter_failed');
		if ($email_failed){
			$data=$this->outbox->setTemplate('NOTIF02')->getTemplate();
			$data['email'] = $email_failed['email'];
			$content=$this->load->view('confirm', $data, true);
			$this->default_display(['content'=>$content]);
		}else{
			$email = $this->session->flashdata('newsletter');
			if(empty($email)){
				header('location:'.base_url('home'));
			}else{
				$data=$this->outbox->setTemplate('NOTIF01')->getTemplate();
				$data['email'] = $email;
				$content=$this->load->view('confirm',$data, true);
				$this->default_display(['content'=>$content]);
			}
		}
	}
}