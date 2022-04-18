<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Data extends MX_Model
{
    public $post          = [];
    public $get           = [];
    public $preference    = [];
    public $param         = [];
    public $user_id       = 0;
    public $apikey        = 0;
    public $max_photo     = 4;
    public $photo         = '';
    public $fields        = [];
    public $limit         = 10;
    public $limit_newest  = 5;
    public $limit_search  = 15;
    public $limit_word  = 50;
    public $limit_word_actifity  = 50;
    public $data_user     = [];
    protected $content_id = [];
    public function __construct()
    {
        parent::__construct();
        $this->content_id = ['tentang' => 8, 'beranda_fitur' => 9];
        $this->fields     = [
            'users'         => ['id', 'real_name as name', 'username', 'photo', 'email', 'token', 'subscribe_sts', 'active', 'wilayah_id', 'registration_sts', 'mobile_activation'],
            'pref'          => ['nama_kantor', 'alamat_kantor', 'telp_kantor', 'email_kantor', 'fax_kantor', 'web_kantor', 'sts_app', 'hp_admin', 'logo_kantor', 'web_limit_validasi', 'web_list_aktifitas', 'web_list_aktifitas_terbaru'],
            'tutupan_lahan' => ['id', 'staft_id', 'petugas', 'tutupan_lahan_id', 'class_tutupan', 'status_id', 'status', 'lat', 'lng', 'approval_date', 'approval_by', 'photo', 'city_id', 'wilayah', 'city', 'province', 'tanggal', 'parent_id as province_id', 'description', 'konviden'],
        ];

        $this->load->library('SwichParam', 'swich');
    }

    public function get_data_beranda()
    {
        $rows              = $this->db->select('id, category_id, kelompok as category, title, uri_title, cover_image, news as content')->where('id', $this->content_id['tentang'])->get(_TBL_VIEW_NEWS)->row_array();
        $result['tentang'] = null;
        if ($rows) {$result['tentang'] = $rows;}

        $rows            = $this->db->select('id, category_id, kelompok as category, title, uri_title, cover_image, news as content')->where('category_id', $this->content_id['beranda_fitur'])->get(_TBL_VIEW_NEWS)->result_array();
        $result['fitur'] = null;
        if ($rows) {$result['fitur'] = $rows;}
        $result['desc_fitur'] = $this->preference['desc_fitur'];
        
        $result['mobile'] = ['play_store' => $this->preference['sos_play_store'], 'app_store' => $this->preference['sos_app_store'], 'mobile_web' => $this->preference['sos_mobile_web'], 'cover' => $this->preference['image_download_app']];
        $result['desc_mobile'] = $this->preference['desc_download'];
        
        $rows                = $this->db->select('id, title, cover_image, news as content, uri_title, param_meta')->where('sticky', 1)->order_by('created_at', 'desc')->get(_TBL_VIEW_NEWS)->result_array();
        $result['aktifitas'] = null;
        if ($rows) {$result['aktifitas'] = $rows;}
        $result['desc_aktifitas'] = $this->preference['desc_aktifitas'];

        return $result;
    }

    public function get_blog_detail()
    {
        // menampilkan data blog
        $this->db->select('id, title, cover_image, news as content, uri_title, tanggal, param_meta');
        $this->db->where('uri_title', $this->get['uri_title']);
        $rows = $this->db->get(_TBL_VIEW_NEWS)->row_array();
        if ($rows){
            $rows['cover_image'] = file_url($rows['cover_image']);
        }
        $result['rows'] = $rows;

        return $result;
    }

    public function get_download_detail()
    {
        // menampilkan data blog
        $this->db->select('id, title, cover_image as file, news as content, uri_title, hit, param_meta');
        $this->db->where('uri_title', $this->get['uri_title']);
        $rows = $this->db->get(_TBL_VIEW_NEWS)->row_array();
        if ($rows){
            $rows['file'] = file_url($rows['file']);
        }
        $result['rows'] = $rows;

        return $result;
    }

    public function get_blog_search()
    {
        // cari pagging 
        $page        = (isset($this->get['page'])) ? intval($this->get['page']) : 1;
        $this->limit_search = (isset($this->get['limit'])) ? intval($this->get['limit']) : $this->limit_search;
        if ($page < 1) {$page = 1;}

        $this->db->where('active', 1);
        if ($this->kel_id > 0) {
            $this->db->where('kel_id', $this->kel_id);
        }
        if (isset($this->get['category_id'])) {
            $this->db->where('category_id', $this->get['category_id']);
        }
        if (!empty($this->get['search'])) {
            $this->db->group_start();
            $this->db->like('LOWER(title)', strtolower($this->get['search']));
            $this->db->or_like('LOWER(news)', strtolower($this->get['search']));
            $this->db->group_end();

        }
        $rows = $this->db->get(_TBL_VIEW_NEWS)->num_rows();

        $result['paging']['total'] = $rows;
        $result['paging']['page']  = $page;
        $result['paging']['limit'] = $this->limit_search;
        $x                         = $rows % $this->limit_search;
        $tpage                     = 0;
        if ($x > 0) {
            $tpage = 1;
        }
        --$page;
        $result['paging']['total_page'] = intval($rows / $this->limit_search) + $tpage;

        // menampilkan data blog
        $this->db->select('id, title, cover_image, news as content, uri_title, tanggal, param_meta');
        $this->db->where('active', 1);
        $this->db->where('kel_id', $this->kel_id);
        if (isset($this->get['category_id'])) {
            $this->db->where('category_id', $this->get['category_id']);
        }
        if (!empty($this->get['search'])) {
            $this->db->group_start();
            $this->db->like('LOWER(title)', strtolower($this->get['search']));
            $this->db->or_like('LOWER(news)', strtolower($this->get['search']));
            $this->db->group_end();

        }
        $this->db->limit($this->limit_search, ($page*$this->limit_search));
        $this->db->order_by('created_at', 'desc');
        $rows = $this->db->get(_TBL_VIEW_NEWS)->result_array();
        $result['rows'] = $rows;

        return $result;
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
    
    public function get_data()
    {

        $this->limit_word=intval($this->preference['download_desc_word_limit']);
        if($this->limit_word<5){
            $this->limit_word=50;
        }

        $this->limit_word_actifity=intval($this->preference['actifity_desc_word_limit']);
        if($this->limit_word_actifity<5){
            $this->limit_word_actifity=50;
        }


        $page        = (isset($this->get['page'])) ? intval($this->get['page']) : 1;
        if (isset($this->get['limit'])){
            if (intval($this->get['limit']>0)){
			    $this->limit=$this->get['limit'];
		    }
		}
        if ($page < 1) {$page = 1;}

        $this->db->where('active', 1);
        if ($this->kel_id > 0) {
            $this->db->where('kel_id', $this->kel_id);
        }
        if (isset($this->get['category_id'])) {
            $this->db->where('category_id', $this->get['category_id']);
        }
        $rows = $this->db->get(_TBL_VIEW_NEWS)->num_rows();

        $result['paging']['total'] = $rows;
        $result['paging']['page']  = $page;
        $result['paging']['limit'] = intval($this->limit);
        $x                         = $rows % $this->limit;
        $tpage                     = 0;
        if ($x > 0) {
            $tpage = 1;
        }
        --$page;
        $result['paging']['total_page'] = intval($rows / $this->limit) + $tpage;

        if ($this->kel_id !== 3) {
            $this->db->where('active', 1);
            if ($this->kel_id > 0) {
                $this->db->where('kel_id', $this->kel_id);
            }
            if (isset($this->get['category_id'])) {
                $this->db->where('category_id', $this->get['category_id']);
            }

            $this->db->limit($this->limit, ($page*$this->limit));

            $sts_newest = false;
            if (isset($this->get['newest'])) {
                $this->db->limit($this->limit_newest);
                $this->db->order_by('created_at', 'desc');
                $sts_newest = true;
            }
        }
        switch ($this->kel_id) {
            case 3: //pages
                $file = "cover_image";
                // $this->db->select('id, category_id, kelompok as category, title, uri_title, cover_image, news as content, param_meta');
                $rows = $this->get_pages_data();
                break;
            case 2: //blog
                $file = "cover_image";
                $this->db->select('id, title, cover_image, news as content, uri_title, tanggal, file_pdf, param_meta');
                break;
            case 4: //slide
                $file = "slide";
                $this->db->select('id, title, news as description, uri_title, cover_image as slide, url');
                break;
            case 5: //download
                $file = "file";
                $this->db->select('id, title,cover_image as file, news as content, uri_title, hit, param_meta');
                break;
            case 6: //download
                $file = "cover_image";
                $this->db->select('id, title, url, uri_title, cover_image, hit');
                break;
            default:
        }
        if ($this->kel_id !== 3) {
            $rows = $this->db->get(_TBL_VIEW_NEWS)->result_array();
        }
        // dumps($this->db->last_query());
        foreach ($rows as &$row) {
            if (!empty($row[$file])) {
                $row[$file] = file_url($row[$file]);
            }

            if ($this->kel_id == 5) {
                $row['content'] = split_words($row['content'], $this->limit_word);
            }

            if ($this->kel_id == 2) {
                $row['content'] = split_words($row['content'], $this->limit_word_actifity);
            }
        }
        unset($row);
        $result['rows'] = $rows;

        if ($this->kel_id == 2 && $sts_newest) {
            $this->db->where('active', 1);
            $this->db->where('kel_id', $this->kel_id);
            $this->db->limit($this->limit_newest);
            $this->db->order_by('created_at', 'desc');
            $file = "cover_image";
            $this->db->select('id, title, uri_title, tanggal');
            $rows             = $this->db->get(_TBL_VIEW_NEWS)->result_array();
            $result['newest'] = $rows;
        } elseif ($this->kel_id == 2) {
            $result['newest'] = [];
        }
        return $result;
    }

    public function get_pages_data()
    {
        if (isset($this->get['category_id'])) {
            $this->db->where('id', $this->get['category_id']);
        }

        $this->db->select('*');
        $this->db->from(_TBL_VIEW_COMBO_TREE);
        $query = $this->db->get();
        $rows  = $query->result_array();
        $input = [];
        $id    = [];
        foreach ($rows as $key => $row) {
            if (!in_array($row['id'], $id)) {
                $id[] = $row['id'];
            }
            if (!empty($row['lv_1_id'])) {
                if (!in_array($row['lv_1_id'], $id)) {
                    $id[] = $row['lv_1_id'];
                }
            }
            if (!empty($row['lv_2_id'])) {
                if (!in_array($row['lv_2_id'], $id)) {
                    $id[] = $row['lv_2_id'];
                }
            }
            if (!empty($row['lv_3_id'])) {
                if (!in_array($row['lv_3_id'], $id)) {
                    $id[] = $row['lv_3_id'];
                }
            }
        }
        $result=[];
        if ($id){
            $this->db->select('*');
            $this->db->from(_TBL_COMBO);
            $this->db->where_in('id', $id);
            $this->db->where('active', 1);
            $this->db->order_by('urut');
            $query = $this->db->get();
            $rows  = $query->result_array();
            $input = [];
            foreach ($rows as $row) {
                $input[] = array("id" => $row['id'], "title" => $row['data'], "parent_id" => $row['pid']);
            }

            $rows            = $this->db->where_in('category_id', $id)->where('active', 1)->get(_TBL_VIEW_NEWS)->result_array();
            $this->page_news = [];
            foreach ($rows as $row) {
                $this->page_news[$row['category_id']] = $row;
            }
            $result = $this->_tree($input);
        }
        return $result;
    }

    public function _tree(array $elements, $parentId = 0)
    {
        $branch = array();
        foreach ($elements as &$element) {
            if ($element['parent_id'] == $parentId) {
                $cover_image=null;
                if (!empty($this->page_news[$element['id']]['cover_image'])) {
                    $cover_image = $this->page_news[$element['id']]['cover_image'];
                }
                $element['cover_image'] = $cover_image;
                $element['file_pdf'] = $this->page_news[$element['id']]['file_pdf'];
                $element['cover_pdf'] = $this->page_news[$element['id']]['cover_pdf'];
                if (array_key_exists($element['id'], $this->page_news)) {
                    $element['content'] = $this->page_news[$element['id']]['news'];
                } else {
                    $element['content'] = "-";
                }
                $children = $this->_tree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }

unset($element);
        return $branch;
    }

    public function errorMessage($rows)
    {
        $result['sts']   = 0;
        $result['pesan'] = 'success';
        $result['data']  = [];
        if (!$rows['registration_sts']) {
            $result['pesan'] = 'Silahkan validasi Email anda terlebih dahulu';
        } elseif (!$rows['active']) {
            $result['pesan'] = 'Account anda sedang tidak aktif';
        } else {
            $result['sts'] = 1;
            $wilayah       = explode(",", $rows['wilayah_id']);
            if ($wilayah) {
                foreach ($wilayah as &$x) {
                    $x = floatval($x);
                }
                unset($x);
            }
            $rows['wilayah_id'] = $wilayah;
            unset($row);
            $rows['privilege'] = ['akses_data' => 2, 'validator' => false, 'read_only' => false];
            $groups            = $this->db->where(_TBL_USERS_GROUPS . '.user_id', $rows['id'])->select(_TBL_GROUPS . '.params')->from(_TBL_USERS_GROUPS)->join(_TBL_GROUPS, _TBL_USERS_GROUPS . '.group_id=' . _TBL_GROUPS . '.id')->get()->result_array();
            $akses_data        = 1;
            $validator         = 0;
            $read_only         = 0;
            $first             = true;
            foreach ($groups as $g) {
                $param = json_decode($g['params'], true);
                if (isset($param['validator'])) {
                    if ($first) {
                        $validator = $param['validator'];
                    } elseif ($param['validator']) {
                        $validator = $param['validator'];
                    }
                }
                if (isset($param['read_only'])) {
                    if ($first) {
                        $read_only = $param['read_only'];
                    } elseif ($param['read_only']) {
                        $read_only = $param['read_only'];
                    }
                }
                if (isset($param['akses_data'])) {
                    if ($first) {
                        $akses_data = $param['akses_data'];
                    } elseif ($param['akses_data'] > 1) {
                        $akses_data = intval($param['akses_data']);
                    }
                }
                $first = false;
            }
            $result['data']              = $rows;
            $result['data']['privilege'] = ['akses_data' => $akses_data, 'validator' => $validator, 'read_only' => $read_only];
        }
        return $result;
    }

    public function hasRegister()
    {
        $result['pesan'] = 'error';
        $result['sts']   = 1;
        $result['data']  = [];
        $email           = $this->post['email'];
        $cekemail        = isValidEmail($email);
        if ($cekemail && !empty($email)) {
            $rows = $this->db->select(implode(',', $this->fields['users']))->where('email', $email)->get(_TBL_VIEW_USERS)->row_array();
            if ($rows) {
                $result = $this->errorMessage($rows);
                if ($result['sts']) {
                    $result['pesan'] = 'Email sudah digunakan';
                }
                $result['sts'] = 0;
            }
        } else {
            $result['sts']   = 0;
            $result['pesan'] = 'Email tidak valid';
        }
        return $result;
    }

    public function save_users()
    {
        if (is_array($this->post['wilayah_id'])) {
            $wilayah = implode(",", $this->post['wilayah_id']);
        } else {
            $wilayah = $this->post['wilayah_id'];
        }
        $institusi = 0;
        if (isset($this->post['institusi_id'])) {
            $institusi = $this->post['institusi_id'];
        }

        $jam               = date("Y-m-d H:i");
        $valid_daftar_date = date("Y-m-d H:i", strtotime($jam) + (60 * 60 * $this->preference['batas_verifikasi_pendaftaran']));
        $token             = token();

        $this->crud->crud_table(_TBL_USERS);
        $this->crud->crud_type('add');
        $this->crud->crud_field('username', $this->post['email']);
        $this->crud->crud_field('real_name', $this->post['name']);
        $this->crud->crud_field('email', $this->post['email']);
        $this->crud->crud_field('dept_id', $institusi);
        if (array_key_exists('sosmed_origin', $this->post)) {
            if (!empty($this->post['sosmed_origin'])) {
                $this->crud->crud_field('socmed_type', $this->post['sosmed_origin']);
                $this->crud->crud_field('active', 1);
            }
        }
        if (!empty($this->photo)) {
            $this->crud->crud_field('photo', $this->photo);
        }

        //sementara
        $this->crud->crud_field('active', 1);
        $this->crud->crud_field('group_no', 2);
        $this->crud->crud_field('registration_type', 2);
        $this->crud->crud_field('registration_sts', 0);
        $this->crud->crud_field('mobile_activation', 0);

        $this->crud->crud_field('token', $token);
        $this->crud->crud_field('link_verif', token());
        $this->crud->crud_field('valid_daftar_date', $valid_daftar_date);
        $this->crud->crud_field('created_by', 'system');
        $this->crud->process_crud();
        $id = $this->crud->last_id();

        $pass = true;
        if (array_key_exists('sosmed_origin', $this->post)) {
            if (!empty($this->post['sosmed_origin'])) {
                $pass = false;
            }
        }
        if ($pass) {
            $pass = $this->ion_auth->reset_password($this->post['email'], $this->post['password']);
        }

        $this->crud->crud_table(_TBL_API_KEYS);
        $this->crud->crud_type('add');
        $this->crud->crud_field('token', $token);
        $this->crud->crud_field('kelompok_no', 1);
        $this->crud->crud_field('user_id', $id);
        $this->crud->process_crud();

        $rows = $this->db->select('id, real_name as name, username, wilayah_id as institusi, token, email, socmed_type, photo')->where('id', $id)->get(_TBL_USERS)->row_array();
        return $rows;
    }

    public function cek_valid_user_login($is_sosmed = false)
    {
        $email           = $this->post['email'];
        $cekemail        = isValidEmail($email);
        $result['pesan'] = '';
        $result['data']  = [];
        $result['sts']   = 0;
        if ($cekemail && !empty($email)) {
            $rows = $this->db->select(implode(',', $this->fields['users']))->where('email', $email)->get(_TBL_VIEW_USERS)->row_array();
            if ($rows) {
                $result        = $this->errorMessage($rows);
                $this->user_id = $rows['id'];
            } else {
                $result['pesan'] = 'Email atau Password anda tidak ditemukan';
            }
        } else {
            $result['pesan'] = 'data email tidak valid';
        }
        return $result;
    }

    public function cek_valid_user()
    {
        $token   = $this->apikey;
        $user_id = (isset($this->post['user_id'])) ? intval($this->post['user_id']) : '';
        if (empty($user_id)) {
            $user_id = (isset($this->get['user_id'])) ? intval($this->get['user_id']) : '';
        }
        $result['pesan'] = '';
        $result['data']  = [];
        $result['sts']   = 0;
        $kettoken        = '';
        if (!empty($this->apikey)) {
            $kettoken = ' atau token ';
        }
        if (!empty($this->apikey)) {
            $this->db->where('token', $token);
        }
        if ($user_id) {
            $this->db->where('id', $user_id);
        }
        $rows = $this->db->select(implode(',', $this->fields['users']))->get(_TBL_VIEW_USERS)->row_array();
        if ($rows) {
            $result          = $this->errorMessage($rows);
            $this->data_user = $result['data'];
            $this->user_id   = $rows['id'];
        } else {
            $result['pesan'] = 'anda tidak memiliki hak untuk mengakses informasi ini';
        }
        return $result;
    }

    public function change_profile()
    {
        $rows = [];
        if ($this->user_id > 0) {
            if (is_array($this->post['wilayah_id'])) {
                $wilayah = implode(",", $this->post['wilayah_id']);
            } else {
                $wilayah = $this->post['wilayah_id'];
            }

            $this->crud->crud_table(_TBL_USERS);
            $this->crud->crud_type('edit');
            if (!empty($this->post['email'])) {
                $this->crud->crud_field('username', $this->post['email']);
            }
            if (!empty($this->photo)) {
                $this->crud->crud_field('photo', $this->photo);
            }
            if (!empty($this->post['name'])) {
                $this->crud->crud_field('real_name', $this->post['name']);
            }
            if (!empty($this->post['email'])) {
                $this->crud->crud_field('email', $this->post['email']);
            }
            if (!empty($this->post['wilayah_id'])) {
                $this->crud->crud_field('wilayah_id', $wilayah);
            }
            if (!empty($this->post['institusi_id'])) {
                $this->crud->crud_field('dept_id', $this->post['institusi_id']);
            }
            if (!empty($this->post['subscribe_sts'])) {
                $this->crud->crud_field('subscribe_sts', $this->post['subscribe_sts']);
            }
            $this->crud->crud_field('updated_by', 'system');
            $this->crud->crud_where(['field' => 'id', 'value' => $this->user_id]);
            $this->crud->process_crud();

            if (!empty($this->post['email']) && !empty($this->post['password'])) {
                $pass = $this->ion_auth->reset_password($this->post['email'], $this->post['password']);
            }

            $rows           = $this->db->select(implode(',', $this->fields['users']))->where('id', $this->user_id)->get(_TBL_USERS)->row_array();
            $hasil['pesan'] = 'data berhasil diupdate';
            $hasil['sts']   = 1;
            $hasil['data']  = $rows;
        } else {
            $hasil['pesan'] = 'data id tidak ditemukan';
            $hasil['sts']   = 0;
            $hasil['data']  = [];
        }
        return $hasil;
    }

    function save_hit_download(){

        $id = (isset($this->post['id'])) ? intval($this->post['id']) : 0;
        if ($id>0){
            $rows           = $this->db->where('id', $id)->get(_TBL_VIEW_NEWS)->row_array();
            if ($rows>0){
                $jml=intval($rows['hit'])+1;
                $this->crud->crud_table(_TBL_NEWS);
                $this->crud->crud_type('edit');
                $this->crud->crud_field('hit', $jml);
                $this->crud->crud_where(['field' => 'id', 'value' => $id]);
                $this->crud->process_crud();
            }else{
                $id=0;
            }
        }
        return $id;
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
        $Sts_wilayah=false;
        if(isset($this->get['kota_id'])){
            if(intval($this->get['kota_id'])>0){
                $this->db->where('city_id', $this->get['kota_id']);
                $Sts_wilayah=true;
            }else{
                if(isset($this->get['propinsi_id'])){
                    if(intval($this->get['propinsi_id'])>0){
                        $this->db->where('parent_id', $this->get['propinsi_id']);
                        $Sts_wilayah=true;
                    }
                }
            }
        }elseif(isset($this->get['propinsi_id'])){
            if(intval($this->get['propinsi_id'])>0){
                $this->db->where('parent_id', $this->get['propinsi_id']);
                $Sts_wilayah=true;
            }
        }
        
        if(!$Sts_wilayah && !$this->data_user['privilege']['validator']){
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

    function get_data_validation_detail(){

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


    function get_data_validation(){
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
                $row['photo'] = json_decode($row['photo'], true);
                foreach($row['photo'] as &$x){
                    foreach($x['photos'] as &$y){
                        if ($y['photo_path']!==null){
                            $y['photo_path']=file_url($y['photo_path']);
                        }
                    }
                    unset($y);
                }
                unset($x);
                $row['photo']=[];
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

    function validasi_data(){
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

					$sql= $this->db->query("INSERT INTO lhk_cek_lapangan_log(parent_id, staft_id, tutupan_lahan_id, lat, lng, photo, description, status_id, approval_date, approval_by, created_at, created_by, updated_at, updated_by, deleted_at, deleted_by, lap_no, konviden, city_id, note, type_edit) select {$this->post['id']}, staft_id, tutupan_lahan_id, lat, lng, photo, description, status_id, approval_date, approval_by, created_at, created_by, updated_at, updated_by, deleted_at, deleted_by, lap_no, konviden, city_id, '{$this->post['note']}', 3 from public.lhk_cek_lapangan where id={$this->post['id']}");
                    
                    $data = $this->db->where('id',$this->post['id'])->get(_TBL_VIEW_CEK_LAPANGAN)->row_array();

                    $rows = $this->db->where('id', intval($this->user_id))->get(_TBL_USERS)->row_array();
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
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */
