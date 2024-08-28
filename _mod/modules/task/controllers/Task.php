<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Task extends MY_Controller
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
		$this->today      = date('Y-m-d');
		$this->limit_date = date('Y-m-d', strtotime('+7 days'));
		parent::__construct();
		$this->dataTmp['notif'] = [];
		$this->dataTmp['rows']  = [];
	}

	function init($aksi = '')
	{
		$configuration = [
			'show_second_sidebar' => FALSE,
			'show_action_button'  => FALSE,
			'show_list_header'    => FALSE,
			'box_list_header'     => TRUE,
			'show_title_header'   => FALSE,
			'content_title'       => 'Taks & FAQ',
		];
		return [
			'configuration' => $configuration,
		];
	}

	function content($ty = 'detail')
	{

		$data['upcoming'] = $this->db
			->where('batas_waktu >=', $this->today)
			->where('batas_waktu <=', $this->limit_date)
			->order_by('batas_waktu', 'ASC')
			->get(_TBL_VIEW_RCSA_MITIGASI_DETAIL)
			->result_array();
		$data['overdue']  = $this->db
			->where('batas_waktu <', $this->today)
			->order_by('batas_waktu', 'ASC')
			->get(_TBL_VIEW_RCSA_MITIGASI_DETAIL)
			->result_array();

		$data["faq"] = $this->db->get_where(_TBL_FAQ, ["active" => 1])->result_array();
		$content     = $this->load->view('task', $data, TRUE);

		return $content;
	}

	function setContentHeader($mode = '')
	{
		$content = [];
		return $content;
	}

	function secondSidebarData()
	{
		$content['data'] = $this->dataTmp['notif'];
		return $content;
	}

	function get_ceklog()
	{
		$post        = $this->input->post();
		$data['log'] = $this->db->where('ref_id', $post['id'])->get("il_log_send_email")->result_array();

		$result = $this->load->view('cekLog', $data, TRUE);
		header('Content-Type: application/json');
		echo json_encode(['combo' => $result]);
	}

	function notif()
	{

		$getTemplate = $this->db->get_where("il_template_email", ["code" => "NOTIF07"])->row_array();

		$post = $this->input->post();
		$vOff = $this->db->get_where(_TBL_OFFICER, ["owner_no" => $post['owner_id'], "active" => 1])->row_array();
		$detail = $this->db->get_where('il_view_rcsa_detail', ["id" => $post['detailId']])->row_array();
 
		if (! empty($vOff["email"])) {
			$getTemplate["content_html"] = str_replace("[[MITIGASI]]", $detail["peristiwa_risiko"], $getTemplate["content_html"]);
			$getTemplate["content_html"] = str_replace("[[day]]", $post['day'].' hari', $getTemplate["content_html"]);
			$content                     = $this->load->view("email-notification", $getTemplate, TRUE);
			$emailData['email']          = [$vOff["email"]];
			$emailData['subject']        = "Reminder Due Date Mitigasi {$post["day"]} hari";
			$emailData['content']        = $content ?? "";
			$status                      = Doi::kirim_email($emailData);
 			if ($status == "success") {
				$insertOutbox["sender"]       = json_encode([$this->preference['email_smtp_user'], $this->preference['email_title']]);
				$insertOutbox["recipient"]    = $vOff["email"];
				$insertOutbox["subject"]      = $emailData['subject'];
				$insertOutbox["message"]      = $getTemplate["content_html"];
				$insertOutbox["message_text"] = "Reminder Due Date Mitigasi H- {$post["day"]}";
				$insertOutbox["subject"]      = "Reminder Due Date Mitigasi H- {$post["day"]}";
				$insertOutbox["sent_at"]      = date("Y-m-d H:i:s");
				$insertOutbox["is_sent"]      = 1;
				$insertOutbox["kel_id"]      = $post["detailId"];
				$insertOutbox["scheduled_at"] = date("Y-m-d H:i:s");
				$insertOutbox["created_at"]   = date("Y-m-d H:i:s");
				$insertOutbox["updated_at"]   = date("Y-m-d H:i:s");
				$this->db->insert(_TBL_OUTBOX, $insertOutbox);

				$this->crud->crud_table(_TBL_LOG_SEND_EMAIL);
				$this->crud->crud_type('add');
				$this->crud->crud_field('type', 1, 'int');
				$this->crud->crud_field('ref_id', $post["detailId"], 'int');
				$this->crud->crud_field('subject', "Reminder Due Date H- {$post["day"]}", 'string');
				$this->crud->crud_field('message', $emailData['subject'], 'string');
				$this->crud->crud_field('ket', 'Dengan email ini  diberitahukan bahwa mitigasi terkait akan sampai pada due date ' . $post['day'] . ' lagi.', 'string');
				$this->crud->crud_field('to',  $vOff["email"], 'string');
				$this->crud->process_crud();
			}
			$hasil['sts']  = $status;
			// $hasil['pesan']  ="Reminder Due Date"'. $vOff["email"];
			header( 'Content-type: application/json' );
			echo json_encode( $hasil );

 		}
	}
}
