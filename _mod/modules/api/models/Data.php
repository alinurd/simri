<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MX_Model {
	var $post=[];
	var $get=[];
	var $preference=[];
	var $param=[];
	var $user_id=0;
	var $apikey=0;
	var $max_photo=4;
	var $photo='';
	var $fields=[];
	var $limit=10;
	var $data_user=[];
	public function __construct()
    {
		parent::__construct();
		
		$this->fields=[
			'users'=>['id', 'real_name as name', 'username', 'photo', 'email', 'token', 'subscribe_sts', 'active', 'wilayah_id', 'registration_sts', 'mobile_activation', 'password', 'dept_id as institusi_id', 'institusi'],
			'pref'=>['nama_kantor', 'alamat_kantor', 'telp_kantor', 'email_kantor', 'fax_kantor', 'web_kantor', 'sts_app', 'hp_admin', 'logo_kantor', 'mobile_version', 'mapbox_api', 'otp_digit', 'otp_type'],
			'tutupan_lahan'=>['id', 'staft_id', 'petugas', 'tutupan_lahan_id', 'class_tutupan', 'status_id', 'status', 'lat', 'lng', 'approval_date', 'approval_by', 'photo', 'city_id', 'wilayah', 'city', 'province',  'tanggal', 'parent_id as province_id', 'note', 'konviden', 'note', 'description'],
		];
	}

	function get_login($email, $pass){
		$rows = $this->db->where('email', $email)->where('verifikasi_no > ', 0)->get(_TBL_VIEW_USER)->row_array();
		$hasil=[];
		if ($rows){
			if ($this->authentication->generate_hash($pass, $rows['password']) == $rows['password'])
			{
				$hasil=$rows;
			}
		}
		return $hasil;
	}

	function get_param(){
		if (!isset($this->get['kel'])){
			$kels =['institusi','wilayah','tutupan','preference', 'about'];
		}elseif (empty($this->get['kel'])){
			$kels =['institusi','wilayah','tutupan','preference', 'about'];
		}else{
			$kels = [$this->get['kel']];
		}

		foreach($kels as $kel){
			switch ($kel){
				case 'institusi':
					$level_id = intval((isset($this->get['level_id']))?intval($this->get['level_id']):'');
					$sts_all = intval((isset($this->get['sts_all']))?intval($this->get['sts_all']):0);
					if ($sts_all){
						if ($level_id){
							$this->datacombo->where(['level'=>($level_id-1)]);
						}else{
							$this->datacombo->where(['level'=>0]);
						}
					}
					$result[$kel]=$this->datacombo->isGroup(true)->build('dept');
					break;
				case 'wilayah':
					$propinsi_id = (isset($this->get['propinsi_id']))?intval($this->get['propinsi_id']):'';
					$kota_id = (isset($this->get['kota_id']))?intval($this->get['kota_id']):'';
					if ($propinsi_id){
						$this->db->where('id',$propinsi_id);
						$this->db->where('parent_id',0);
						$this->db->select('id, name, lat, lng');
					}elseif ($kota_id){
						$this->db->where('id',$kota_id);
						$this->db->select('parent_id as id, name, lat, lng');
					}else{
						$this->db->where('parent_id',0);
						$this->db->select('id, name, lat, lng');
					}
					$rows=$this->db->get(_TBL_WILAYAH)->result_array();
					$propinsi=[];
					foreach($rows as $row){
						$propinsi[]=$row;
					}
					
					if ($kota_id){
						$this->db->where('id',$kota_id);
					}
					$rows=$this->db->where('parent_id>',0)->get(_TBL_VIEW_WILAYAH)->result_array();
					$wilayah=[];
					foreach($rows as $row){
						$wilayah[$row['parent_id']][]=['id'=>$row['id'], 'name'=>$row['name'], 'lat'=>$row['lat'], 'lng'=>$row['lng']];
					}
					foreach($propinsi as $key=>&$row){
						$kab_kota=[];
						if (array_key_exists($row['id'], $wilayah)){
							$kab_kota=$wilayah[$row['id']];
						}
						$row['qty']=count($kab_kota);
						$row['kab_kota']=$kab_kota;

					}
					unset($row);
					$result[$kel]=$propinsi;
					break;
				case 'propinsi':
					$result[$kel]=$this->db->select('id, name, lat,lng')->where('level',1)->where('active',1)->get(_TBL_WILAYAH)->result_array();
					break;
				case 'kota':
					$result[$kel]=$this->db->select('id, name, lat,lng')->where('parent_id',intval($this->get['propinsi_id']))->where('active',1)->get(_TBL_WILAYAH)->result_array();
					break;
				case 'tutupan':
					$result[$kel]=$this->db->select('id, code, toponimi, note as name')->where('active',1)->get(_TBL_TUTUPAN_LAHAN)->result_array();
					break;
				case 'about':
					$result[$kel]=$this->db->where('id',1)->select('id, title, uri_title, cover_image, news as content, param_meta')->get(_TBL_VIEW_NEWS)->row_array();
					break;
				case 'preference':
					$x = $this->preference;
					$resultx=[];
					$resultx['id']=1;
					foreach($this->fields['pref'] as $row){
						if(is_numeric($x[$row]))
                            $resultx[$row]=intval($x[$row]);
                        else
						    $resultx[$row]=$x[$row];
					}
					$result[$kel]=$resultx;
					break;
				default:
					$result=['institusi','wilayah','propinsi','kota','tutupan','preference', 'about'];
					break;
			}
		}

		$data=$result;
		if (count($kels)==1){
			$data=$result[$this->get['kel']];
		}
		return $data;
	}

	function errorMessage($rows){
		$result['sts']=0;
		$result['pesan']='success';
		// $result['data']=['id'=>$rows['id'], 'name'=>$rows['real_name'], 'username'=>$rows['username'], 'institusi'=>$rows['wilayah_id'], 'token'=>$rows['token'], 'email'=>$rows['email'], 'socmed_type'=>$rows['socmed_type']];
		$result['data']=[];
		if(!$rows['registration_sts']){
			$result['pesan']='Silahkan validasi Email anda terlebih dahulu';
		}elseif(!$rows['mobile_activation']){
			$result['pesan']='Akun anda sedang dalam proses verifikasi, mohon coba beberapa saat lagi';
		}elseif(!$rows['active']){
			$result['pesan']='Account anda sedang tidak aktif';
		}else{
			$result['sts']=1;
			// foreach($rows as &$row){
			$wilayah=explode(",",$rows['wilayah_id']);
			if ($wilayah){
				foreach($wilayah as &$x){
					$x=floatval($x);
				}
				unset($x);
			}
			$rows['wilayah_id']=$wilayah;
			// }
			unset($row);
			$rows['privilege']=['akses_data'=>2,'validator'=>false,'read_only'=>false];
			$groups = $this->db->where(_TBL_USERS_GROUPS.'.user_id', $rows['id'])->select(_TBL_GROUPS.'.params')->from(_TBL_USERS_GROUPS)->join(_TBL_GROUPS, _TBL_USERS_GROUPS.'.group_id='._TBL_GROUPS.'.id')->get()->result_array();
			$akses_data=1;
			$validator=0;
			$read_only=0;
			$first=true;
			foreach($groups as $g){
				$param = json_decode($g['params'], true);
				if (isset($param['validator'])){
					if ($first){
						$validator=$param['validator'];
					}elseif ($param['validator']){
						$validator=$param['validator'];
					}
				}
				if (isset($param['read_only'])){
					if ($first){
						$read_only=$param['read_only'];
					}elseif ($param['read_only']){
						$read_only=$param['read_only'];
					}
				}
				if (isset($param['akses_data'])){
					if ($first){
						$akses_data=$param['akses_data'];
					}elseif ($param['akses_data']>1){
						$akses_data=intval($param['akses_data']);
					}
				}
				$first=false;
			}
			$result['data']=$rows;
			$result['data']['privilege']=['akses_data'=>$akses_data,'validator'=>$validator,'read_only'=>$read_only];
		}
		return $result;
	}


	function cek_valid_user_login($is_sosmed=false){
		$email = $this->post['email'];
		$cekemail=isValidEmail($email);
		$result['pesan']='';
		$result['data']=[];
		$result['sts']=0;
		if($cekemail && !empty($email)){
			$rows = $this->db->select(implode(',',$this->fields['users']))->where('email', $email)->get(_TBL_VIEW_USERS)->row_array();
			if ($rows){
				// $rows['privilege']=['akses_data'=>2,'validator'=>false,'read_only'=>false];
				$result=$this->errorMessage($rows);
				// if ($result['sts']==1){
				// 	$groups = $this->db->where(_TBL_USERS_GROUPS.'.user_id', $rows['id'])->select(_TBL_GROUPS.'.params')->from(_TBL_USERS_GROUPS)->join(_TBL_GROUPS, _TBL_USERS_GROUPS.'.group_id='._TBL_GROUPS.'.id')->get()->result_array();
				// 	$akses_data=1;
				// 	$validator=0;
				// 	$read_only=0;
				// 	$first=true;
				// 	foreach($groups as $g){
				// 		$param = json_decode($g['params'], true);
				// 		if (isset($param['validator'])){
				// 			if ($first){
				// 				$validator=$param['validator'];
				// 			}elseif ($param['validator']){
				// 				$validator=$param['validator'];
				// 			}
				// 		}
				// 		if (isset($param['read_only'])){
				// 			if ($first){
				// 				$read_only=$param['read_only'];
				// 			}elseif ($param['read_only']){
				// 				$read_only=$param['read_only'];
				// 			}
				// 		}
				// 		if (isset($param['akses_data'])){
				// 			if ($first){
				// 				$akses_data=$param['akses_data'];
				// 			}elseif ($param['akses_data']>1){
				// 				$akses_data=intval($param['akses_data']);
				// 			}
				// 		}
				// 		$first=false;
				// 	}
				// 	$result['data']['privilege']=['akses_data'=>$akses_data,'validator'=>$validator,'read_only'=>$read_only];
				// }
				$this->user_id=$rows['id'];
			}else{
				$result['pesan']='Email atau Password anda tidak ditemukan';
			}
		}else{
			$result['pesan']='data email tidak valid';
		}
		return $result;
	}
	
	function cek_valid_user($email=""){
		$token = $this->apikey;
		// $email = (isset($this->post['email']))?$this->post['email']:'';
		// if (empty($email)){
		// 	$email = $this->get['email'];
		// }
		$user_id = (isset($this->post['user_id']))?intval($this->post['user_id']):'';
		if (empty($user_id)){
			$user_id = (isset($this->get['user_id']))?intval($this->get['user_id']):'';
		}
		$cekemail=false;
		if (!empty($email)){
			$cekemail=isValidEmail($email);
		}

		$result['pesan']='';
		$result['data']=[];
		$result['sts']=0;
		$kettoken = '';
		if (!empty($this->apikey)){
			$kettoken=' atau token ';
		}
		$stslanjut=true;
		if(!empty($email)){
			if($cekemail){
				$this->db->where('email', $email);
			}else{
				$stslanjut=false;
				$result['sts']=0;
				$result['pesan']='Email tidak Valid';
			}
		}else{
			if (!empty($this->apikey)){
				$this->db->where('token', $token);
			}
			if ($user_id){
				$this->db->where('id', $user_id);
			}
		}
		if ($stslanjut){
			$rows = $this->db->select(implode(',',$this->fields['users']))->get(_TBL_VIEW_USERS)->row_array();
			if ($rows){
				$result=$this->errorMessage($rows);
				$this->data_user = $result['data'];
				$this->user_id=$rows['id'];
			}else{
				$result['pesan']='anda tidak memiliki hak untuk mengakses informasi ini';
			}
		}
		// }else{
		// 	$result['pesan']='data email'.$kettoken.'tidak valid';
		// }
		return $result;
	}

	function hasRegister(){
		$result['pesan']='error';
		$result['sts']=1;
		$result['data']=[];
		$email = $this->post['email'];
		$cekemail=isValidEmail($email);
		if($cekemail && !empty($email)){
			$rows = $this->db->select(implode(',',$this->fields['users']))->where('email', $email)->get(_TBL_VIEW_USERS)->row_array();
			if ($rows){
				$result=$this->errorMessage($rows);
				if ($result['sts']){
					$result['pesan']='Email sudah digunakan';
				}
				$result['sts']=0;
			}
		}else{
			$result['sts']=0;
			$result['pesan']='Email tidak valid';
		}
		return $result;
	}

	function cek_otp(){
		$rows =  $this->db->select('id, username')->where('forgotten_password_code', $this->post['otp'])->get(_TBL_VIEW_USERS)->row_array();
		$data=[];
		// dumps($this->db->last_query());
		if (!$rows){
			$data['pesan'] ='data anda tidak ditemukan atau token sudah kadaluarsa';
			$data['sts']=0;
			$data['data']=[];
		}else{
			$data['pesan'] = "Silahkan masukkan password baru anda:";
			$data['sts']=1;
			$data['data']=$rows;
		}

		return $data;
	}
	
	function proses_forgot_password(){
		$rows =  $this->db->select(implode(',',$this->fields['users']))->where('id', $this->post['id'])->get(_TBL_VIEW_USERS)->row_array();
		
		if($rows){
			$pass = $this->ion_auth->reset_password($rows['email'], $this->post['new_pass']);
			$this->crud->crud_table(_TBL_USERS);
			$this->crud->crud_type('edit');
			$this->crud->crud_field('forgotten_password_code', null);
			$this->crud->crud_field('forgotten_password_time', null);
			$this->crud->crud_where(['field'=>'id', 'value'=>$this->post['id']]);
			$this->crud->process_crud();

			$link = '<a href="'.base_front_url().'">disini</a>';
			$content_replace = ['[[nama]]'=>$rows['real_name'],'[[disini]]'=>$link, '[[footer]]'=>$this->preference['footer_email']];

			$datasOutbox=[
				'recipient' => [$rows['email']],
				'kel_id' => 4,
			];

			$this->load->library('outbox');
			$this->outbox->setTemplate('EML-FP-02');
			$this->outbox->setParams($content_replace);
			$this->outbox->setDatas($datasOutbox);
			$this->outbox->send();
			$result['sts']=1;
			$result['data']=$rows;
			$result['pesan']='Password berhasil di ubah';
		}else{
			$result['sts']=0;
			$result['data']=[];
			$result['pesan']='Data user tidak ditemukan';
		}

		return $result;
	}

	function change_password(){
		// dumps($this->data_user['email']);
		// dumps()
		if ($this->ion_auth->login($this->data_user['email'], $this->post['old_pass'])){
			$pass = $this->ion_auth->reset_password($this->data_user['email'], $this->post['new_pass']);

			$result['sts']=1;
			$result['pesan']='Password berhasil diubah';
		}else{
			$result['sts']=0;
			$result['pesan']='Password lama salah';
		}

		return $result;
	}

	function save_users(){
		if (is_array($this->post['wilayah_id'])){
			$wilayah = implode(",",$this->post['wilayah_id']);
		}else{
			$wilayah = $this->post['wilayah_id'];
		}
		$institusi=0;
		if (isset($this->post['institusi_id'])){
			$institusi = $this->post['institusi_id'];
		}

		$jam = date("Y-m-d H:i");
		$valid_daftar_date = date("Y-m-d H:i", strtotime($jam)+(60*60*$this->preference['batas_verifikasi_pendaftaran']));
		$token=token();

		$this->crud->crud_table(_TBL_USERS);
		$this->crud->crud_type('add');
		$this->crud->crud_field('username', $this->post['email']);
		$this->crud->crud_field('real_name', $this->post['name']);
		$this->crud->crud_field('email', $this->post['email']);
		$this->crud->crud_field('dept_id', $institusi);
		if (array_key_exists('sosmed_origin',$this->post)){
			if (!empty($this->post['sosmed_origin'])){
				$this->crud->crud_field('socmed_type', $this->post['sosmed_origin']);
				$this->crud->crud_field('active', 1);
			}else{
				$this->crud->crud_field('active', 0);
			}
		}else{
			$this->crud->crud_field('active', 0);
		}

		if (!empty($this->photo)){
			$this->crud->crud_field('photo', $this->photo);
		}
		
		//sementara
		$this->crud->crud_field('active', 1);
		$this->crud->crud_field('group_no', 2);
		$this->crud->crud_field('registration_type', 1);
		$this->crud->crud_field('registration_sts', 0);
		$this->crud->crud_field('mobile_activation', 0);
		
		$this->crud->crud_field('wilayah_id', $wilayah);
		$this->crud->crud_field('token', $token);
		$this->crud->crud_field('link_verif', token());
		$this->crud->crud_field('valid_daftar_date', $valid_daftar_date);
		$this->crud->crud_field('created_by', 'system');
		$this->crud->process_crud();
		$id=$this->crud->last_id();

		$pass=true;
		if (array_key_exists('sosmed_origin',$this->post)){
			if (!empty($this->post['sosmed_origin'])){
				$pass=false;
			}
		}
		if ($pass){
			$pass = $this->ion_auth->reset_password($this->post['email'], $this->post['password']);
		}

		$this->crud->crud_table(_TBL_API_KEYS);
		$this->crud->crud_type('add');
		$this->crud->crud_field('token', $token);
		$this->crud->crud_field('kelompok_no', 1);
		$this->crud->crud_field('user_id', $id);
		$this->crud->process_crud();

		$rows = $this->db->select('id, real_name as name, username, wilayah_id as institusi, token, email, socmed_type, photo')->where('id', $id)->get(_TBL_VIEW_USERS)->row_array();
		return $rows;
	}

	function change_profile(){
		$rows=[];
		if ($this->user_id>0){
			if (is_array($this->post['wilayah_id'])){
				$wilayah = implode(",",$this->post['wilayah_id']);
			}else{
				$wilayah = $this->post['wilayah_id'];
			}

			$this->crud->crud_table(_TBL_USERS);
			$this->crud->crud_type('edit');
			if (!empty($this->post['email'])){
				$this->crud->crud_field('username', $this->post['email']);
			}
			if (!empty($this->photo)){
				$this->crud->crud_field('photo', $this->photo);
			}
			if (!empty($this->post['name'])){
				$this->crud->crud_field('real_name', $this->post['name']);
			}
			if (!empty($this->post['email'])){
				$this->crud->crud_field('email', $this->post['email']);
			}
			if (!empty($this->post['wilayah_id'])){
				$this->crud->crud_field('wilayah_id', $wilayah);
			}
			if (!empty($this->post['institusi_id'])){
				$this->crud->crud_field('dept_id', $this->post['institusi_id']);
			}
			if (isset($this->post['subscribe_sts'])){
				$this->crud->crud_field('subscribe_sts', $this->post['subscribe_sts']);
			}
			$this->crud->crud_field('updated_by', 'system');
			$this->crud->crud_where(['field'=>'id', 'value'=>$this->user_id]);
			$this->crud->process_crud();

			if (!empty($this->post['email']) && !empty($this->post['password'])){
				$pass = $this->ion_auth->reset_password($this->post['email'], $this->post['password']);
			}

			$rows = $this->db->select(implode(',',$this->fields['users']))->where('id', $this->user_id)->get(_TBL_VIEW_USERS)->row_array();
			$hasil['pesan']='data berhasil diupdate';
			$hasil['sts']=1;
			$wilayah=explode(',',$rows['wilayah_id']);
			if ($wilayah){
				foreach($wilayah as &$x){
					$x=floatval($x);
				}
				unset($x);
			}
			$rows['wilayah_id']=$wilayah;
			$hasil['data']=$rows;
		}else{
			$hasil['pesan']='data id tidak ditemukan';
			$hasil['sts']=0;
			$hasil['data']=[];
		}
		return $hasil;
	}

	function getLastNo($tipe){
		$thn=date('Y');
		$bln=date('n');
		$rows=$this->db->where('tahun', $thn)->where('bulan', $bln)->where('tipe', $tipe)->get(_TBL_NOMOR)->row_array();
		if ($rows){
			$nomor=$rows['nomor']+1;
			$this->crud->crud_table(_TBL_NOMOR);
			$this->crud->crud_type('edit');
			$this->crud->crud_field('nomor', $nomor);
			$this->crud->crud_where(['field'=>'id', 'value'=>$rows['id']]);
			$this->crud->process_crud();
		}else{
			$nomor=1;
			$this->crud->crud_table(_TBL_NOMOR);
			$this->crud->crud_type('add');
			$this->crud->crud_field('tahun', $thn);
			$this->crud->crud_field('bulan', $bln);
			$this->crud->crud_field('tipe', $tipe);
			$this->crud->crud_field('nomor', $nomor);
			$this->crud->process_crud();
		}

		$result = $nomor.'/CL'.'/'.$bln.'/'.$thn;

		return $result;
	}

	function save_cek_lapangan($mode='add'){
		$rows=[];
		if ($this->user_id>0){
			$arr_save=[1=>_TBL_CEK_LAPANGAN, 2=>_TBL_CEK_LAPANGAN_LOG];

			$nomor=$this->getLastNo(2);
			foreach($arr_save as $key=>$tbl){
				$this->crud->crud_table($tbl);
				if ($key==1){
					$this->crud->crud_type($mode);
					if ($mode=='edit'){
						$this->crud->crud_where(['field'=>'id', 'value'=>$this->post['id']]);
					}
				}else{
					$this->crud->crud_type('add');
				}
				if ($key==2){
					$this->crud->crud_field('parent_id', $id);
					$this->crud->crud_field('type_edit', 1);
				}
				$this->crud->crud_field('lap_no', $nomor);
				if (!empty($this->post['kelas_tutupan_lahan'])){
					$this->crud->crud_field('tutupan_lahan_id', $this->post['kelas_tutupan_lahan']);
				}
				$this->crud->crud_field('status_id', 1);
				if (!empty($this->photo)){
					$this->crud->crud_field('photo', $this->photo);
				}
				$this->crud->crud_field('staft_id', $this->user_id);
				if (!empty($this->post['lat'])){
					$this->crud->crud_field('lat', $this->post['lat']);
				}
				if (!empty($this->post['kota_id'])){
					$this->crud->crud_field('city_id', $this->post['kota_id']);
				}
				if (!empty($this->post['konviden'])){
					$this->crud->crud_field('konviden', intval($this->post['konviden']));
				}
				if (!empty($this->post['lng'])){
					$this->crud->crud_field('lng', $this->post['lng']);
				}
				if (!empty($this->post['description'])){
					$this->crud->crud_field('description', $this->post['description']);
				}
				if (!empty($this->post['description'])){
					$this->crud->crud_field('description', $this->post['description']);
				}

				if ($this->data_user['privilege']['validator']){
					$this->crud->crud_field('status_id', 2);
				}
				
				$this->crud->crud_field('created_by', 'system');
				$this->crud->process_crud();
				if ($key==1){
					$id=$this->crud->last_id();
				}
			}

			$rows = $this->db->where('id', $id)->get(_TBL_CEK_LAPANGAN)->row_array();
			$hasil['pesan']='data berhasil diupdate';
			$hasil['sts']=1;
			$hasil['data']=$rows;
		}else{
			$hasil['pesan']='data id tidak ditemukan';
			$hasil['sts']=0;
			$hasil['data']=[];
		}
		return $hasil;
	}
	
	function delete_data_lapangan(){
		$result['sts']=0;
		$result['pesan']='Data cek lapangan tidak dapat dihapus';
		if ($this->data_user['privilege']['validator']){
			if(isset($this->post['id'])){
				if(intval($this->post['id'])>0){
					$this->crud->crud_table(_TBL_CEK_LAPANGAN);
					$this->crud->crud_type('edit');
					$this->crud->crud_field('note', $this->post['note']);
					$this->crud->crud_field('deleted_by', intval($this->user_id));
					$this->crud->crud_field('deleted_at', date('d-m-Y H:i:s'));
					$this->crud->crud_where(['field'=>'id', 'value'=>$this->post['id']]);
					$this->crud->process_crud();

					$sql= $this->db->query("INSERT INTO lhk_cek_lapangan_log(parent_id, staft_id, tutupan_lahan_id, lat, lng, photo, description, status_id, approval_date, approval_by, created_at, created_by, updated_at, updated_by, deleted_at, deleted_by, lap_no, konviden, city_id, note, type_edit)
					select {$this->post['id']}, staft_id, tutupan_lahan_id, lat, lng, photo, description, status_id, approval_date, approval_by, created_at, created_by, updated_at, updated_by, deleted_at, deleted_by, lap_no, konviden, city_id, '{$this->post['note']}', 5 from public.lhk_cek_lapangan where id={$this->post['id']}");
	
					$result['sts']=1;
					$result['pesan']='Data cek lapangan berhasil dihapus';
				}
			}else{
				$result['sts']=0;
				$result['pesan']='Data cek lapangan tidak ada';
			}
		}else{
			$result['sts']=0;
			$result['pesan']='Anda tidak memiliki autoritas untuk menghapus data cek lapangan';
		}

		return $result;
	}

	function validasi_data_lapangan(){
		$this->cboSts=$this->crud->combo_value(['1'=>'Proses', '2'=>'Valid', '3'=>'Tidak Valid'])->result_combo();
		$result['sts']=0;
		$result['pesan']='Data cek lapangan tidak dapat divalidasi';
		if ($this->data_user['privilege']['validator']){
			if(isset($this->post['id'])){
				if(intval($this->post['id'])>0){
					$this->crud->crud_table(_TBL_CEK_LAPANGAN);
					$this->crud->crud_type('edit');
					$this->crud->crud_field('note', $this->post['note']);
					$this->crud->crud_field('status_id', $this->post['status_id']);
					$this->crud->crud_field('approval_by', intval($this->user_id));
					$this->crud->crud_field('updated_by', intval($this->user_id));
					$this->crud->crud_field('updated_at', date('d-m-Y H:i:s'));
					$this->crud->crud_field('approval_date', date('d-m-Y H:i:s'));
					$this->crud->crud_where(['field'=>'id', 'value'=>$this->post['id']]);
					$this->crud->process_crud();

					$sql= $this->db->query("INSERT INTO lhk_cek_lapangan_log(parent_id, staft_id, tutupan_lahan_id, lat, lng, photo, description, status_id, approval_date, approval_by, created_at, created_by, updated_at, updated_by, deleted_at, deleted_by, lap_no, konviden, city_id, note, type_edit)
					select {$this->post['id']}, staft_id, tutupan_lahan_id, lat, lng, photo, description, status_id, approval_date, approval_by, created_at, created_by, updated_at, updated_by, deleted_at, deleted_by, lap_no, konviden, city_id, '{$this->post['note']}', 3 from public.lhk_cek_lapangan where id={$this->post['id']}");

					$data = $this->db->where('id',$this->post['id'])->get(_TBL_VIEW_CEK_LAPANGAN)->row_array();

					$rows = $this->db->where('id', intval($this->user_id))->get(_TBL_VIEW_USERS)->row_array();
					$link = '<a href="'.base_front_url('cek-laporan/'.$this->post['id']).'">disini</a>';
					$content_replace = ['[[nama]]'=>$rows['real_name'],'[[nolap]]'=>$data['lap_no'],'[[status]]'=>$this->cboSts[$data['status_id']],'[[disini]]'=>$link, '[[footer]]'=>$this->preference['footer_email']];

					$datasOutbox=[
						'recipient' => [$rows['email']],
						'kel_id' => 4,
					];

					$this->load->library('outbox');
					$this->outbox->setTemplate('EML-LAP-02');
					$this->outbox->setParams($content_replace);
					$this->outbox->setDatas($datasOutbox);
					$this->outbox->send();

					$result['sts']=1;
					$result['pesan']='Data cek lapangan berhasil divalidasi';
				}
			}else{
				$result['sts']=0;
				$result['pesan']='Data cek lapangan tidak ada';
			}
		}else{
			$result['sts']=0;
			$result['pesan']='Anda tidak memiliki autoritas untuk memvalidasi data cek lapangan';
		}

		return $result;
	}

	function get_cek_lapangan_detail(){

        if (!$this->data_user['privilege']['validator']){
            $this->db->where('staft_id', $this->user_id);
        }

        $rows = $this->db->where('id', intval($this->get['id']))->select(implode(',',$this->fields['tutupan_lahan']))->get(_TBL_VIEW_CEK_LAPANGAN)->result_array();
        if ($rows){
            $photo = json_decode($rows['photo'], true);
            foreach($photo as &$x){
                foreach($x['photos'] as &$y){
                    if ($y['photo_path']!==null){
                        $y['photo_path']=file_url($y['photo_path']);
                    }
                }
                unset($y);
            }
            unset($x);
            $rows['photo']=$photo;
            $result['rows'] = $rows;
            $result['sts'] = 1;
            $result['pesan'] = 'Success';
        }else{
            $result['sts'] = 0;
            $result['pesan'] = 'Data tidak ditemukan';
        }
        return $result;
	}
	
	function get_data_lapangan_filter(){
		if(isset($this->get['status_id'])){
			if(intval($this->get['status_id'])>0){
				$this->db->where('status_id', $this->get['status_id']);
			}
		}

		if ($this->data_user['privilege']['validator']){
			if(isset($this->get['tipe_user'])){
				if(intval($this->get['tipe_user'])==1){
					$this->db->where('staft_id', $this->user_id);
				}elseif(intval($this->get['tipe_user'])==2){
					$this->db->where('staft_id <>', $this->user_id);
				}
			}
		}else{
			$this->db->where('staft_id', $this->user_id);
		}

		$sts_wilayah=$this->data_user['privilege']['akses_data'];
		if(isset($this->get['kota_id'])){
			if(intval($this->get['kota_id'])>0){
				$this->db->where('city_id', $this->get['kota_id']);
				// $sts_wilayah=true;
			}else{
				if(isset($this->get['propinsi_id'])){
					if(intval($this->get['propinsi_id'])>0){
						$this->db->where('parent_id', $this->get['propinsi_id']);
						// $sts_wilayah=true;
					}elseif($sts_wilayah==2){
						$this->db->where_in('city_id', $this->data_user['wilayah_id']);
					}
				}elseif($sts_wilayah==2){
					$this->db->where_in('city_id', $this->data_user['wilayah_id']);
				}
			}
		}elseif(isset($this->get['propinsi_id'])){
			if(intval($this->get['propinsi_id'])>0){
				$this->db->where('parent_id', $this->get['propinsi_id']);
				// $sts_wilayah=true;
			}elseif($sts_wilayah==2){
				$this->db->where_in('city_id', $this->data_user['wilayah_id']);
			}
		}elseif($sts_wilayah==2){
			$this->db->where_in('city_id', $this->data_user['wilayah_id']);
		}

		if(isset($this->get['kelas_tutupan_lahan'])){
			if (is_numeric($this->get['kelas_tutupan_lahan'])){
				if(intval($this->get['kelas_tutupan_lahan'])>0){
					$this->db->where('tutupan_lahan_id', $this->get['kelas_tutupan_lahan']);
				}
			}elseif (!empty($this->get['kelas_tutupan_lahan'])){
				$this->db->like('LOWER(class_tutupan)', strtolower($this->get['kelas_tutupan_lahan']));
			}
		}

		if(isset($this->get['search'])){
			if (!empty($this->get['search'])){
				$this->db->group_start();
				$this->db->like('LOWER(class_tutupan)', strtolower($this->get['search']));
				$this->db->or_like('LOWER(city)', strtolower($this->get['search']));
				$this->db->or_like('LOWER(province)', strtolower($this->get['search']));
				$this->db->group_end();
			}
		}


		if(isset($this->get['konviden'])){
			$sts_konviden=false;
			if(is_array($this->get['konviden'])){
				if (count($this->get['konviden']==2)){
					$min=$this->get['konviden'][0];
					$max=$this->get['konviden'][1];
					$sts_konviden=true;
				}
			}else{
				$x=explode(',', $this->get['konviden']);
				if (count($x)==2){
					$min=$x[0];
					$max=$x[1];
					$sts_konviden=true;
				}
			}
			if ($sts_konviden){
				$this->db->where('konviden>=', intval($min));
				$this->db->where('konviden<=', intval($max));
			}
		}

		if(isset($this->get['tgl_awal']) && isset($this->get['tgl_akhir'])){
			if(!empty($this->get['tgl_awal']) && !empty($this->get['tgl_akhir'])){
				$this->db->where('tanggal>=', date('Y-m-d',strtotime($this->get['tgl_awal'])));
				$this->db->where('tanggal<=', date('Y-m-d',strtotime($this->get['tgl_akhir'])));
			}
		}

		$this->db->where('deleted_by', null);
	}
	function get_data_lapangan(){
		$rows=[];

		if (isset($this->get['limit'])){
			$this->limit=$this->get['limit'];
		}

		if ($this->user_id){
			$this->get_data_lapangan_filter();
			$page = (isset($this->get['page']))?intval($this->get['page']):1;
			if($page<1){$page=1;}

			$rows = $this->db->get(_TBL_VIEW_CEK_LAPANGAN)->num_rows();
			$result['paging']['total']=$rows;
			$result['paging']['page']=$page;
			$result['paging']['limit']=$this->limit;
			$x=$rows%$this->limit;
			$tpage=0;
			if ($x>0){
				$tpage=1;
			}
			--$page;
			$result['paging']['total_page']=intval($rows/$this->limit)+$tpage;
			$this->get_data_lapangan_filter();
			if(isset($this->get['urut_id'])){
				if(intval($this->get['urut_id'])==1){
					$this->db->order_by('created_at', 'desc');
				}
			}
			$this->db->limit($this->limit, ($page*$this->limit));
			$rows = $this->db->select(implode(',',$this->fields['tutupan_lahan']))->get(_TBL_VIEW_CEK_LAPANGAN)->result_array();

			// dumps($this->db->last_query());
			$result['paging']['rows']=count($rows);
			$result['paging']['rows']=count($rows);
			$result['rows'] = $this->convert_data($rows,'tutupan');

			$this->get_data_lapangan_filter();
			$rows = $this->db->select('status_id, status, count(status_id) as jumlah')->group_by(['status', 'status_id'])->order_by('status_id')->get(_TBL_VIEW_CEK_LAPANGAN)->result_array();
			$arr_statis=[1=>'Proses', 3=>'Tidak Valid', 2=>'Valid'];
			$statis=[];
			foreach($arr_statis as $key=>$st){
				$jumlah=0;
				foreach($rows as $row){
					if ($row['status_id'] == $key){
						$jumlah=$row['jumlah'];
						break;
					}
				}
				$statis[]=['id'=>$key,'status'=>$st, 'jumlah'=>$jumlah];
			}
			$result['statistik']=$statis;
		}

		return $result;
	}

	function convert_data($rows, $kel){
		$photo_id=10;
		$id=100;
		if ($kel=='tutupan'){
			foreach($rows as &$row){
				// $photo = json_decode($row['photo']);
				$row['photo'] = json_decode($row['photo'], true);
				// dumps($row['photo']);
				foreach($row['photo'] as &$x){
					// dumps($x['photos']);
					foreach($x['photos'] as &$y){
						if ($y['photo_path']!==null){
							$y['photo_path']=file_url($y['photo_path']);
						}
					}
					unset($y);
				}
				unset($x);

				// if (isset($row['photo']['selatan'])){
				// 	if ($row['photo']['selatan']){
				// 		foreach($row['photo']['selatan'] as $x=>$y){
				// 			if ($y!==null)
				// 			$row['photo']['selatan'][$x]=file_url($y);
				// 		}
				// 	}
				// }

				// if (isset($row['photo']['timur'])){
				// 	if ($row['photo']['timur']){
				// 		foreach($row['photo']['timur'] as $x=>$y){
				// 			if ($y!==null)
				// 				$row['photo']['timur'][$x]=file_url($y);
				// 		}
				// 	}
				// }

				// if (isset($row['photo']['barat'])){
				// 	if ($row['photo']['barat']){
				// 		foreach($row['photo']['barat'] as $x=>$y){
				// 			if ($y!==null)
				// 				$row['photo']['barat'][$x]=file_url($y);
				// 		}
				// 	}
				// }

				if ($this->data_user['privilege']['validator']){
					if ($this->user_id==$row['staft_id']){
						$row['tipe_user']='Validator';
					}else{
						$row['tipe_user']='Member';
					}
				}else{
					$row['tipe_user']='Validator';
				}
			}
			unset($row);
		}
		return $rows;
	}

	function save_data_lapan(){
		$this->crud->crud_table(_TBL_DATA_LAPAN);
		$this->crud->crud_type('add');
		$d=[];
		$t=0;
		if($this->post){
			$d=$this->post;
			$t=1;
		}elseif($this->get){
			$d=$this->get;
			$t=2;
		}
		$this->crud->crud_field('params', json_encode($d));
		$this->crud->crud_field('tipe', $t);
		$this->crud->process_crud();
		$id=$this->crud->last_id();
		return $id;
	}
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */