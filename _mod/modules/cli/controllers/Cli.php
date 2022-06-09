<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cli extends MX_Controller {

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
	var $html_src='';
	var $url;
	var $url_s;
	var $data=[];
	public function __construct()
	{
		parent::__construct();
		$this->load->model('auth/ion_auth_crud', 'crud');
	}

	function cron()
	{
		if (date('m') == 25) {
			$this->update_minggu_aktif();
		}
	}

	function update_minggu_aktif(){

		$rows = $this->db->select('period, pid as period_id, id as term_id, data as term, param_date as tgl_awal, param_date_after as tgl_akhir')->where('param_date<=', date('Y-m-d'))->where('param_date_after>=', date('Y-m-d'))->get(_TBL_VIEW_TERM)->row_array();
	
		$thn = 0;
		$term = 0;
		$minggu = 0;
		if($rows){
			$thn=$rows['period_id'];
			$term=$rows['term_id'];
		}
		
		// $rows = $this->db->select('id as minggu_id, data as minggu, param_date as tgl_awal, param_date_after as tgl_akhir')->where('kelompok', 'minggu')->where('param_date<=', date('Y-m-d'))->where('param_date_after>=', date('Y-m-d'))->get(_TBL_COMBO)->row_array();
		// if($rows){
		// 	$minggu=$rows['minggu_id'];
		// }
	
		$userId = [];
		$id = [];
		$rows = $this->db->select('id, created_by, updated_by')
			->where('period_id', $thn)
			// ->where('term_id', $term)
			->where('status_final_mitigasi', 1)
			// ->where('minggu_id_mitigasi<>', $minggu)
			// ->where('minggu_id_mitigasi<>', 0)
			// ->get_compiled_select(_TBL_RCSA);
			->get(_TBL_RCSA)->result_array();

		foreach ($rows as $key => $value) {
			if (!empty($value['created_by']) || !empty($value['updated_by'])) {
				if (!in_array($value['created_by'], $userId) || !in_array($value['updated_by'], $userId)) {
					$userId[] = (!empty($value['created_by']))?$value['created_by']:$value['updated_by'];
				}
			}
			$id[] = $value['id'];
		}
	
		if(!empty($id)){
			foreach ($id as $key => $value) {
				$this->crud->crud_table(_TBL_RCSA);
				$this->crud->crud_type('edit');
				$this->crud->crud_field('status_id_mitigasi', 0);
				$this->crud->crud_field('status_final_mitigasi', 0);
				$this->crud->crud_field('tgl_propose_mitigasi', null);
				$this->crud->crud_field('note_propose_mitigasi', '');
				$this->crud->crud_field('param_approval_mitigasi', '');
				$this->crud->crud_where(['field'=>'id', 'value'=>$value]);
				// $this->crud->crud_where(['field'=>'period_id', 'value'=>$thn]);
				// $this->crud->crud_where(['field'=>'term_id', 'value'=>$term]);
				// $this->crud->crud_where(['field'=>'status_final_mitigasi', 'value'=>1]);
				// $this->crud->crud_where(['field'=>'minggu_id_mitigasi', 'op'=>'<>', 'value'=>$minggu]);
				// $this->crud->crud_where(['field'=>'minggu_id_mitigasi', 'op'=>'<>', 'value'=>0]);
				$this->crud->process_crud();
			}
		}

		
		$this->db->where_in('username', $userId);
		$users = $this->db->select('email, real_name')->from('il_users')->get()->result_array();
		// dumps($users);
		// die();
		foreach ($users as $key => $value) {
			if ($value['email']) {
				$datasOutbox = [
					'recipient' => [$value['email']],
				];
				$content_replace = [
					'[[konteks]]' => 'Konteks Risiko',
					'[[redir]]' => 2,
					'[[id]]' => 0,
					'[[notif]]' => $value['real_name'],
					'[[sender]]' => 'Administrator',
					'[[link]]' => base_url() . "progress-mitigasi",
					'[[footer]]' => 'PT Indonesia Asahan Aluminium (INALUM)'
	
				];
				$this->load->library('outbox');
				$this->outbox->setTemplate('NOTIF05');
				$this->outbox->setParams($content_replace);
				$this->outbox->setDatas($datasOutbox);
				$this->outbox->send();
			}
		}

	}

	function create_folder(){
		
		try {
			// echo $_SERVER['DOCUMENT_ROOT'];
			$folder_period = date('Y-m');
			$folder = realpath($_SERVER['DOCUMENT_ROOT'].'/home/backup_db').'/'.$folder_period;
			if(!is_dir($folder)){
				$old_mask = umask(0);
				mkdir($folder, 0777, true);
				umask($old_mask);
			}else{
				echo "sudah ada";
			}
        } catch (\Exception $e) { 
			echo 'errrr';
			var_dump($e);
		}
	}

	function delete_folder(){
		$this->load->helper('file');
		$folder_period = date('Y-m', strtotime("-1 month"));
		$folder = realpath($_SERVER['DOCUMENT_ROOT'].'/home/backup_db').'/'.$folder_period;
		delete_files($folder, true);
	}

	function outbox(){
		try {
			$rows = $this->db->where('is_sent', 0)->where('scheduled_at<=',date('Y-m-d H:i:s'))->get(_TBL_OUTBOX)->result_array();
			
			foreach($rows as $row){
				$dat['email']=json_decode($row['recipient']);
				$dat['cc']=json_decode($row['cc']);
				$dat['bcc']=json_decode($row['bcc']);
				$dat['subject']=$row['subject'];
				$dat['content']=$row['message'];
				$dat['content_text']=$row['message_text'];
				if (!empty($row['sender']))
					$dat['from']=json_decode($row['sender']);
				if (!empty($row['reply_to']))
					$dat['reply']=json_decode($row['reply_to']);

				$x=Doi::kirim_email($dat);

				$this->crud->crud_table(_TBL_OUTBOX);
				$this->crud->crud_type('edit');

				if ($x=='success'){
					$this->crud->crud_field('sent_at', date('Y-m-d H:i:s'));
					$this->crud->crud_field('is_sent', 1);
				}else{
					$this->crud->crud_field('last_error', $x);
					$this->crud->crud_field('is_sent', 2);
				}
				$this->crud->crud_where(['field'=>'id', 'value'=>$row['id'], 'op'=>'=']);
				$this->crud->process_crud();
			}
		} catch (\Exception $e) {
            throw new Exception('Error: '.$e, E_USER_ERROR);
        }
	}

	function test_cli($to = 'World'){
		echo "Hello {$to}!".PHP_EOL;
	}

	function email_dua_lima()
	{
		$tgl = date('d');
		if ($tgl == 25) {
			echo $tgl;
		}else{
			echo "bukan";
		}
	}
}