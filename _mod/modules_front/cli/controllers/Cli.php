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
		
		$this->url="http://referensi.data.kemdikbud.go.id/index11.php";
		$this->url_s="https://qshe.wika.co.id/app/main_page.php?method=auditormgmt&page=";
	}

	function content($ty='detail'){
		$this->data['hasil']=[];
		$param = 'get_'.$ty;
		// die($param);
		$this->data=[];
		$this->$param();
		die('selesai '.$ty.' '.count($this->data['hasil']). ' record');
		return $this->data;
	}

	function get_propinsi(){
		include('simple_html_dom.php');
		$html = file_get_html($this->url);
		$sql=[];
		foreach($html->find('table[id=box-table-a]') as $tb) {
			foreach($tb->find('tr') as $keyr=>$tr){
				if ($keyr>2){
					foreach($tr->find('td') as $keyd=>$td){
						if ($keyd==1){
							foreach($td->find('a') as $element){
								$hrefs= $element->href;
								$hrefs_text= $element->plaintext;
								$href_arr=explode('?', $hrefs);
								parse_str($href_arr[1],$href);
								$href['nama']=$hrefs_text;
								$sql[]=$href;
								$this->data['hasil'][]= $hrefs . $hrefs_text;
							}
						}
					}
				}
			}
		}
		if ($sql){
			try{
				$this->db->insert_batch('tmp', $sql);

				$db_error = $this->db->error();
				if ($db_error['code']) {
					$this->data['hasil'][]= 'Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message'];
				}
			}
			catch(Exception $e){
				$this->data['hasil'][]=$e->getMessage();
			}
		}
		return $this->data;
	}

	function get_kota(){
		include('simple_html_dom.php');

		$rows = $this->db->where('parent_no',0)->where('level',1)->where('id',28)->order_by('kode')->get('tmp')->result_array();
		foreach($rows as $row){
			$sql=[];
			$url = $this->url;
			$url .='?kode='.$row['kode'].'&level='.$row['level'];
			$html = file_get_html($url);
			$id=$row['id'];
			foreach($html->find('table[id=box-table-a]') as $tb) {
				foreach($tb->find('tr') as $keyr=>$tr){
					if ($keyr>2){
						foreach($tr->find('td') as $keyd=>$td){
							if ($keyd==1){
								foreach($td->find('a') as $element){
									$href='';
									$hrefs= $element->href;
									$hrefs_text= $element->plaintext;
									$href_arr=explode('?', $hrefs);
									parse_str($href_arr[1],$href);
									$href['nama']=$hrefs_text;
									$href['parent_no']=$id;
									$sql[]=$href;
									$this->data['hasil'][]= $hrefs . '<br/>';
								}
							}
						}
					}
				}
			}

			if ($sql){
				try{
					$this->db->insert_batch('tmp', $sql);

					$db_error = $this->db->error();
					if ($db_error['code']) {
						$this->data['hasil'][]= 'Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message'];
					}
				}
				catch(Exception $e){
					$this->data['hasil'][]=$e->getMessage();
				}
			}
			sleep(10);
		}
		//return $this->data;
	}

	function get_kecamatan(){
		include('simple_html_dom.php');

		$rows = $this->db->where('level',2)->order_by('kode')->get('tmp')->result_array();
		foreach($rows as $row){
			$sql=[];
			$url = $this->url;
			$url .='?kode='.$row['kode'].'&level='.$row['level'];
			$html = file_get_html($url);
			$id=$row['id'];
			foreach($html->find('table[id=box-table-a]') as $tb) {
				foreach($tb->find('tr') as $keyr=>$tr){
					if ($keyr>2){
						foreach($tr->find('td') as $keyd=>$td){
							if ($keyd==1){
								foreach($td->find('a') as $element){
									$href='';
									$hrefs= $element->href;
									$hrefs_text= $element->plaintext;
									$href_arr=explode('?', $hrefs);
									parse_str($href_arr[1],$href);
									$href['nama']=$hrefs_text;
									$href['parent_no']=$id;
									$sql[]=$href;
									$this->data['hasil'][]= $hrefs;
								}
							}
						}
					}
				}
			}

			if ($sql){
				try{
					$this->db->insert_batch('tmp', $sql);

					$db_error = $this->db->error();
					if ($db_error['code']) {
						$this->data['hasil'][]= 'Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message'];
					}
				}
				catch(Exception $e){
					$this->data['hasil'][]=$e->getMessage();
				}
			}
		}
		//return $this->data;
	}

	function get_sekolah(){
		include('simple_html_dom.php');

		$rows = $this->db->where('level',3)->order_by('kode')->get('tmp')->result_array();
		foreach($rows as $row){
			$sql=[];
			$url = $this->url;
			$url .='?kode='.$row['kode'].'&level='.$row['level'];
			$html = file_get_html($url);
			$id=$row['id'];
			foreach($html->find('table[id=example]') as $tb) {
				foreach($tb->find('tr') as $keyr=>$tr){
					if ($keyr>1){
						foreach($tr->find('td') as $keyd=>$td){
							if ($keyd==1){
								foreach($td->find('a') as $element){
									$href='';
									$hrefs= $element->href;
									$hrefs_text= $element->plaintext;
									$href_arr=explode('?', $hrefs);
									parse_str($href_arr[1],$href);
									$href['parent_no']=$id;
								}
							}
							elseif ($keyd==2){
								$href['sekolah']=$td->plaintext;
							}
							elseif ($keyd==3){
								$href['alamat']=$td->plaintext;
							}
							elseif ($keyd==4){
								$href['kelurahan']=$td->plaintext;
							}
							elseif ($keyd==5){
								$href['status']=$td->plaintext;
								$sql[]=$href;
								$this->data['hasil'][]= $hrefs;
							}
						}
						//var_dump($sql)
					}
				}
			}

			if ($sql){
				try{
					$this->db->insert_batch('sekolah_tmp', $sql);

					$db_error = $this->db->error();
					if ($db_error['code']) {
						$this->data['hasil'][]= 'Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message'];
					}
				}
				catch(Exception $e){
					$this->data['hasil'][]=$e->getMessage();
				}
			}
		}
		//return $this->data;
	}

	function get_detail(){
		ini_set('memory_limit', '-1');
		include('simple_html_dom.php');
		$rows = $this->db->where('sts_detail',0)->limit(10000)->order_by('npsn')->get('school')->result_array();
		foreach($rows as $row){
			$sql=[];
			$url = $this->url_s;
			$url .=$row['npsn'];
			$html = file_get_html($url);
			$id=$row['id'];
			foreach($html->find('div[id=tabs-6]') as $dv) {
				foreach($dv->find('table') as $tb) {
					foreach($tb->find('tr') as $keyr=>$tr){
						if ($keyr==0){
							foreach($tr->find('td') as $keyd=>$td){
								if ($keyd==3){
									$href['phone']=$td->plaintext;
								}
							}
						}elseif ($keyr==2){
							foreach($tr->find('td') as $keyd=>$td){
								if ($keyd==3){
									$href['email']=$td->plaintext;
								}
							}
						}elseif ($keyr==3){
							foreach($tr->find('td') as $keyd=>$td){
								if ($keyd==3){
									$href['website']=$td->plaintext;
									$href['sts_detail']=1;
									$this->db->where('id', $id);
									$this->db->update('school', $href);
									$this->data['hasil']= $href;
								}
							}
						}
					}
				}
			}

			// if ($sql){
			// 	try{
			// 		$this->db->insert_batch('tmp', $sql);

			// 		$db_error = $this->db->error();
			// 		if ($db_error['code']) {
			// 			$this->data['hasil'][]= 'Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message'];
			// 		}
			// 	}
			// 	catch(Exception $e){
			// 		$this->data['hasil'][]=$e->getMessage();
			// 	}
			// }
		}
		//return $this->data;
	}

	public function message($to = 'World')
	{
			echo "Hello {$to}!".PHP_EOL;
	}
	
	function clearsession()
	{
		$this->db->empty_table(_TBL_SELF_SESSION);
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

	function test_cli(){
		$this->crud->crud_table(_TBL_BAHASA);
		$this->crud->crud_type('add');
		
		$this->crud->crud_field('key', 'Key_'.date('His'));
		$this->crud->crud_field('title', 'data ke '. date('Ymd His'));
		$this->crud->process_crud();
		$nolast = $this->crud->last_id();
		echo "1 data berhasil ditambah dengan id : ".$nolast;
	}
}