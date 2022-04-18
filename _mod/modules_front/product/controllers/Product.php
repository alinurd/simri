<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends MY_Frontend {

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
	}

	
	function detail(){
		$param = $this->uri->rsegment_array();
		$target = strtolower($param[3]);
		$data=$this->data->get_data($target);
		$data['price'] = $this->db->where('product_id', $data['info']['id'])->get(_TBL_PRODUCTPRICE)->num_rows();
		$data['tblprice'] = $this->list_price($data['info']['id']);
		$content = $this->load->view('view',$data, true);
		$this->_set_title($data['info']['product']);
		$this->_set_meta($data['info']['param_meta']);
		$this->default_display(['content'=>$content]);
	}

	function order(){
		$param = $this->uri->rsegment_array();
		$target = strtolower($param[3]);
		$pilih=0;
		if(count($param)==4){
			$pilih = intval($param[4]);
		}
		$data=$this->data->get_data($target);
		$data['price'] = $this->db->where('product_id', $data['info']['id'])->get(_TBL_PRODUCTPRICE)->num_rows();
		$data['order'] = $this->list_order($data['info']['id'], $pilih);
		$content = $this->load->view('order',$data, true);
		$this->_set_title($data['info']['product']);
		$this->default_display(['content'=>$content]);
	}

	function init($aksi=''){
		$configuration = [

		];
		return [
			'configuration'	=> $configuration
		];
	}

	function list_order($id, $pil=0){
		$rows = $this->db->where('id', $id)->get(_TBL_VIEW_PRODUCT)->row_array();

        if ($rows){
				// dumps($this->_rows[$param]);
				$rows = $this->data->_set_data($rows)->_set_params(['photo', 'param_meta', 'param_lang', 'img_cover'])->_row_array()->convert_data_param()->convert_data_lang(['note', 'instruction'])->_build();
		}
		$rows['m_input'] = explode(',',$rows['m_input']);
		$m_input = array_map(function($val){
			$dont=['laminated_price','spanram_price','price_sheet'];
			$val=str_replace('-','_', $val);
			if (!in_array($val,$dont))
				return $val.'_id';
			else
				return $val;
		},$rows['m_input']);
		$m_input = implode(',',$m_input);
		$pilih=[];
		if ($pil){
			$pilih = $this->db->where('id', $pil)->get(_TBL_VIEW_PRODUCTPRICE)->row_array();
		}
		$field = $this->db->where('product_id', $id)->order_by('urut')->get(_TBL_VIEW_PRODUCTPRICE)->result_array();
		$cbo=[];
		foreach($rows['m_input'] as $fld){
			$x=str_replace('-','_',$fld);
			$cbo[$fld][0]=$this->lang->line('cbo_select');
			foreach($field as $ros){
				$cbo[$fld][$ros[$x]]=$ros[$x];
			}
		}
		$data['pilih']=$pilih;
		$data['order']=$cbo;
		$data['info'] = $rows;
		$rows = $this->db->where('active',1)->where('kelompok','pickup')->get(_TBL_COMBO)->result_array();
		$pickup=[' - select -'];
		foreach($rows as $row){
			$pickup[$row['id']]=$row['data'];
		}
		$data['pickup'] = $pickup;
		$content = $this->load->view('list-order',$data, true);
		return $content;
	}

	function list_price($id=0){
		if ($id==0){
			$id=$this->input->get('id');
			$kel=$this->input->get('kel');
		}
		$rows = $this->db->where('id', $id)->get(_TBL_VIEW_PRODUCT)->row_array();

        if ($rows){
			$rows['param'] = $this->data->_set_data($rows)->_set_params(['photo', 'param_meta', 'param_lang'])->_row_array()->convert_data_param()->convert_data_lang(['note', 'instruction'])->_build();
		}

		$rows['m_input'] = explode(',',$rows['m_input']);
		$m_input = array_map(function($val){
			$dont=['laminated_price','spanram_price','price_sheet'];
			$val=str_replace('-','_', $val);
			if (!in_array($val,$dont))
			return $val.'_id';
			else
			return $val;
		},$rows['m_input']);
		$m_input = implode(',',$m_input);
		$data['info'] = $rows;
		// $data['field'] = $this->db->where('product_id', $id)->order_by($m_input)->get(_TBL_VIEW_PRODUCTPRICE)->result_array();
		$data['field'] = $this->db->where('product_id', $id)->order_by('urut')->get(_TBL_VIEW_PRODUCTPRICE)->result_array();
		$hasil['combo']=$this->load->view('price', $data, true);
		if ($id){
			return $hasil['combo'];
		}else{
			echo json_encode($hasil);
		}
	}

	function save_order(){
		$post=$this->input->post();
		// dumps($post);die();
		$uri_title=str_replace('-','_',$post['uri_title']);
		$nama_uri_title=ucwords(str_replace('-',' ',$post['uri_title']));
		$ins['product_id']=$post['product_id'];
		$ins['product_price_id']=$post['order_id'];
		$ins['product_price']=floatval(str_replace(',','',$post['price']));
		$ins['cover']=$post['cover_name'];
		$ins['name']=$post['name'];
		$ins['phone']=$post['telp'];
		$ins['email']=$post['email'];
		$ins['pickup']=$post['pickup'];
		$ins['alamat']=$post['alamat'];
		$ins['note']=$post['pesan'];
		$ins['qty']=$post['jml'];
		//$ins['attacment']=json_encode($datas);
		$this->db->insert(_TBL_ORDERS, $ins);
		$id=$this->db->insert_id();
		$this->load->library('image');
		$path='order_path_relative';
		$datas=[];
		$file_path=[];
		if (array_key_exists('upload', $_FILES)){
			$jml=count($_FILES['upload']['name']);

			$files = $_FILES;
			for($x=0;$x<$jml;++$x){
				if (!empty($files['upload']['name'][$x])) {
					$data=[];
					$_FILES['upload_imagex']['name'] = $files['upload']['name'][$x];
					$_FILES['upload_imagex']['type'] = $files['upload']['type'][$x];
					$_FILES['upload_imagex']['tmp_name'] = $files['upload']['tmp_name'][$x];
					$_FILES['upload_imagex']['error'] = $files['upload']['error'][$x];
					$_FILES['upload_imagex']['size'] = $files['upload']['size'][$x];

					$this->image->set_Param('nm_file', 'upload_imagex');
					$this->image->set_Param('file_name', 'order#'.$id.'_'.$uri_title.'_'.$_FILES['upload']['name'][$x]);
					$this->image->set_Param('path',$path('orders'));
					$this->image->set_Param('thumb',false);
					$this->image->set_Param('type','gif|jpg|jpeg|png');
					$this->image->set_Param('size', 1000000);
					$this->image->set_Param('nm_random', false);
					$this->image->set_Param('multi', true);
					$this->image->set_Param('image_no', $x);

					$this->image->upload();
					$file_path[] = file_path_relative('orders/'.$this->image->result('file_name'));
					$data['name']='orders/'.$this->image->result('file_name');
					$datas[]=$data;
				}
			}
			$this->db->where('id',$id);
			$this->db->update(_TBL_ORDERS, ['attacment'=>json_encode($datas)]);
		}

		$this->load->library('outbox');
		$this->outbox->setTemplate('TMP01');
		$this->outbox->setParams([
			'<<nama>>'=>$post['name'],
			'<<product>>'=>$nama_uri_title,
			'<<kel>>'=>$nama_uri_title,
			'<<phone>>'=>$nama_uri_title,
			'<<email>>'=>$post['email'],
			'<<note>>'=>$post['pesan'],
			'<<qty>>'=>$post['jml'],
			'<<nopesan>>'=>'#'.$id,
			'<<tanggal>>'=>date('d M Y'),
		]);
		$this->outbox->setDatas([
			'recipient'=>[$post['email']],
			'bcc'=>['debug.aplikasi@gmail.com'],
			'attachment'=>json_encode($file_path),
		]);

		$this->outbox->send();
		$this->session->set_flashdata('order', $post);
		header('location:'.base_url('product/confirm/'));
	}

	function confirm(){
		$this->load->library('outbox');
		$email_failed = $this->session->flashdata('order_failed');
		if ($email_failed){
			$data=$this->outbox->setTemplate('NOTIF04')->getTemplate();
			$data['email'] = $email_failed['email'];
			$content=$this->load->view('confirm', $data, true);
			$this->default_display(['content'=>$content]);
		}else{
			$email = $this->session->flashdata('order');
			if(empty($email)){
				header('location:'.base_url('category'));
			}else{
				$data=$this->outbox->setTemplate('NOTIF03')->getTemplate();
				$data['email'] = $email['email'];
				$content=$this->load->view('confirm',$data, true);
				$this->default_display(['content'=>$content]);
			}
		}
	}
}