<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Outbox
{
	protected $_ci;
	protected $preference=array();
	protected $_params=array();
	protected $_datas=array();

	function __construct()
	{
		$this->_ci =& get_instance();
		$this->preference=$this->_ci->data_config->get_Preference('',0);
		// dump($this->preference);die();
		$this->clear();
	}

	function setTemplate($kode)
	{
		$this->_ci->db->where('code', $kode);
        $rows = $this->_ci->db->where('code', $kode)->get(_TBL_TEMPLATE_EMAIL)->row_array();

		if ($this->_ci->db->error()['code']){
			$err=$this->_ci->db->error();
			throw new Exception('Error: '.'Code:'.$err['code'].' - '.$err['message']. ' - '.$this->_ci->db->last_query(), E_USER_ERROR);
		}else{

			if ($rows){
				$this->_datas['subject']=$rows['subject'];
				$this->_datas['message']=$rows['content_html'];
				$this->_datas['message_text']=$rows['content_text'];
			}
		}
		return $this;
	}

	function getTemplate()
	{
		return $this->_datas;
	}

	function setParams($keys, $value='')
	{
		if (is_array($keys)){
			foreach($keys as $key=>$row){
                $this->_params[$key]=$row;
            }
		}else{
			$this->_params[$keys]=$value;
		}
		return $this;
	}

	function setDatas($keys, $value='')
	{
		if (is_array($keys)){
			foreach($keys as $key=>$row){
                $this->_datas[$key]=$row;
            }
		}else{
			$this->_datas[$keys]=$value;
		}

		return $this;
	}

	function send(){
		try{
			if ($this->_params){
				foreach($this->_params as $key=>$row){
					$this->_datas['message'] = str_replace($key, $row, $this->_datas['message']);
					$this->_datas['message_text'] = str_replace($key, $row, $this->_datas['message_text']);
					$this->_datas['subject'] = str_replace($key, $row, $this->_datas['subject']);
				}
			}
			$dat['email']=$this->_datas['recipient'];
			if (!empty($this->_datas['recipient']))
				$this->_datas['recipient'] = json_encode($this->_datas['recipient']);
			if (!empty($this->_datas['sender']))
				$this->_datas['sender'] = json_encode($this->_datas['sender']);
			if (!empty($this->_datas['cc']))
				$this->_datas['cc'] = json_encode($this->_datas['cc']);
			if (!empty($this->_datas['bcc']))
				$this->_datas['bcc'] = json_encode($this->_datas['bcc']);

			$this->_ci->db->insert(_TBL_OUTBOX, $this->_datas);
			$id=$this->_ci->db->insert_id();

			$this->_ci->load->config("configuration", true);
			$sts_cli = $this->_ci->config->item("status_cli", 'configuration');
			if (!$sts_cli){
				// $dat['subject']=$this->_datas['subject'];
				// $dat['content']=$this->_datas['message'];
				// // $dat['content_text']=$this->_datas['message_text'];
				// $dat['bcc']=['debug.aplikasi@gmail.com'];

				// $x=Doi::kirim_email($dat);
				$this->proses_outbox();
			}
		
		}catch(Exception $e){
			throw new Exception('Error: '.$e, E_USER_ERROR);
			
		}

		$this->clear();
		return true;
	}

	function clear(){
		$this->_params=[];
		$this->_datas=[];
		$this->_datas=[
			'sender' => [$this->preference['email_smtp_user'], $this->preference['email_title']],
			'recipient' => '',
			'cc' => [$this->preference['email_admin']],
			'bcc' => '',
			'subject'=>'Notif RMS HK',
			'message'=>'',
			'message_text'=>'',
			'scheduled_at'=>date('Y-m-d H:i:s')
		];
	}

	function proses_outbox(){
		try {
			$rows = $this->_ci->db->where('is_sent', 0)->where('tried<=5')->where('scheduled_at<=',date('Y-m-d H:i:s'))->order_by('id', 'desc')->limit(2)->get(_TBL_OUTBOX)->result_array();
			
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

				$this->_ci->crud->crud_table(_TBL_OUTBOX);
				$this->_ci->crud->crud_type('edit');

				if ($x=='success'){
					$this->_ci->crud->crud_field('sent_at', date('Y-m-d H:i:s'));
					$this->_ci->crud->crud_field('is_sent', 1);
				}else{
					$this->_ci->crud->crud_field('last_error', $x);
					$this->_ci->crud->crud_field('tried', $row['tried']+1);
				}
				$this->_ci->crud->crud_where(['field'=>'id', 'value'=>$row['id'], 'op'=>'=']);
				$this->_ci->crud->process_crud();
			}
		} catch (\Exception $e) {
            throw new Exception('Error: '.$e, E_USER_ERROR);
        }
	}
}

// END Template class