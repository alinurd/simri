<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Visitor extends MY_Controller {

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
	
	public function __construct()
	{
		parent::__construct();
		// if ($this->configuration['user']['modul']){
		// 	$this->templateDepan();
		// }
	}

	function content($ty='detail'){
		$data=[];
		if ($this->configuration['user']['modul']){
			$this->set_template_('depan');
			$this->hasil=$this->visitor($data);
			return $this->hasil;
		}else{
			header('location:'.base_url());
		}
	}

	function init($aksi=''){
		$configuration = [
			'show_title_header' => false,
			'show_action_button' => false,
			'box_content' => false,
		];
		return [
			'configuration'	=> $configuration
		];
	}

	function visitor($data){
		$car = $this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'kendaraan')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$identitas = $this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'identitas')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		
		$updown= '<div class="input-group" style="width:100% !important;">
					<button type="button" onclick="this.parentNode.querySelector(\'[type=number]\').stepDown();jumlah_change();">-</button>';
		$updown .= form_input(array('type'=>'number','name'=>'jml'),1," class='form-control touchspin-postfixtext-center text-center' max='100' min='1' step='1' style='width:80% !important;' id='jml' ");

		$updown .= '<button type="button" onclick="this.parentNode.querySelector(\'[type=number]\').stepUp();jumlah_change();">+</button>
					</div>';

		$user = '<div class="input-group" id="div_updown">
					<span class="input-group-prepend">
						<span class="input-group-text"><i class="icon-user"></i></span>
					</span>';
		$user .=form_input('detail_visitor[]','','class="form-control" id="detail_visitor" readonly="readonly"');
		$user .='</div>';
		$required = '<span class="pull-right text-danger"><sub>*)</sub></span>';
		$data=[];
		$data['jml']=$updown;
		$data['required']=$required;
		$data['form1'][]=['label'=>lang('form_nama').$required,'content'=>form_input('nama','','class="form-control" required="required" style="width:100%;" id="nama" autofocus')];
		$data['form1'][]=['label'=>lang('form_identitas_id').$required,'content'=>form_dropdown('identitas_id', $identitas, 42,'class="form-control" required="required" id="identitas_id" style="width:100%;"')];
		$data['form1'][]=['label'=>lang('form_identitas').$required,'content'=>form_input('identitas','','class="form-control" id="identitas" required="required" style="width:100%;"')];
		$data['form1'][]=['label'=>lang('form_pekerjaan'),'content'=>form_input('pekerjaan','','class="form-control" id="pekerjaan" style="width:100%;"')];
		$data['form1'][]=['label'=>lang('form_alamat'),'content'=>form_input('alamat','','class="form-control" id="alamat" style="width:100%;"')];
		$data['form1'][]=['label'=>lang('form_perusahaan'),'content'=>form_input('perusahaan','','class="form-control" id="perusahaan" style="width:100%;"')];
		$data['form1'][]=['label'=>lang('form_telp').$required,'content'=>form_input('telp','','class="form-control" required="required" id="telp" style="width:100%;"')];
		$data['form1'][]=['label'=>lang('form_email'),'content'=>form_input('email','','class="form-control" id="email" style="width:100%;"')];
		$data['form1'][]=['label'=>lang('form_tujuan').$required,'content'=>form_input('tujuan','','class="form-control" required="required" id="tujuan" style="width:100%;"')];
		$data['form1'][]=['label'=>lang('form_janjian'),'content'=>form_input('sts_janji','','class="form-control" id="sts_janji" style="width:100%;"')];
		$data['form1'][]=['label'=>lang('form_kepentingan'),'content'=>form_input('kepentingan','','class="form-control" id="kepentingan" style="width:100%;"')];
		$data['form2'][]=['label'=>lang('form_jml'),'content'=>$updown];
		$data['form2'][]=['label'=>lang('form_detail_visitor'),'content'=>$user];
		$data['form2'][]=['label'=>lang('form_kendaraan'),'content'=>form_dropdown('kendaraan', $car,'','class="form-control" id="kendaraan" style="width:100%;"')];
		$data['form2'][]=['label'=>lang('form_nopol'),'content'=>form_input('nopol','','class="form-control" id="nopol" style="width:100%;"')];
		$data['form2'][]=['label'=>lang('form_tanggal'),'content'=>'<div class="row"><div class="col-md-5">'.form_input('tanggal',date('d F, Y'),'class="form-control pickadate" id="tanggal" style="width:100%;"').'</div><div class="col-md-7">'.form_input('jam_masuk',date('H:i'),'class="form-control pickatime" id="jam_masuk" style="width:100%;"').'</div></div>'];
		// $data['form2'][]=['label'=>lang('form_jam_masuk'),'content'=>form_input('jam_masuk',date('H:i'),'class="form-control pickatime" id="jam_masuk" style="width:100%;"')];
		// $data['form2'][]=['label'=>lang('form_jam_keluar'),'content'=>form_input('jam_keluar','','class="form-control pickatime" id="jam_keluar" style="width:100%;"')];
		$content = $this->load->view('visitor',$data, true);
		return $content;
	}

	function simpan_visitor(){
		$new_data=$this->input->post();
		$this->crud->crud_table(_TBL_VISITOR);
		$this->crud->crud_type('add');
		$this->crud->crud_field('pos_id', $this->configuration['user']['pos_id'], 'int');
		// $this->crud->crud_field('barcode', $this->category_user, 'int');
		$this->crud->crud_field('photo', '');
		$this->crud->crud_field('nama', $new_data['nama']);
		$this->crud->crud_field('identitas_id', $new_data['identitas_id'], 'int');
		$this->crud->crud_field('identitas', $new_data['identitas']);
		$this->crud->crud_field('pekerjaan', $new_data['pekerjaan']);
		$this->crud->crud_field('alamat', $new_data['alamat']);
		$this->crud->crud_field('perusahaan', $new_data['perusahaan']);
		$this->crud->crud_field('telp', $new_data['telp']);
		$this->crud->crud_field('email', $new_data['email']);
		$this->crud->crud_field('jml', $new_data['jml'], 'int');
		$this->crud->crud_field('bertemu_dengan', $new_data['tujuan']);
		$this->crud->crud_field('sts_janji', $new_data['sts_janji']);
		$this->crud->crud_field('keperluan', $new_data['kepentingan']);
		$this->crud->crud_field('tanggal', $new_data['tanggal_submit'], 'date');
		$this->crud->crud_field('jam_masuk', $new_data['jam_masuk']);
		$this->crud->crud_field('kendaraan_id', $new_data['kendaraan'], 'int');
		$this->crud->crud_field('keamanan_id', $this->configuration['user']['staft_id'], 'int');
		$this->crud->crud_field('no_pol', $new_data['nopol']);
		$this->crud->crud_field('sts_penerima', 0);
		$this->crud->crud_field('created_by', $this->ion_auth->get_user_name());
		$this->crud->process_crud();

		$visitor_id = $this->crud->last_id();
		$this->load->library('ciqrcode');
		$this->load->library('encryption');

		$folder = date('Y') . '/' . date('m') ;
		if (!is_dir(file_path_relative('barcode').$folder)) {
			mkdir(file_path_relative('barcode').'/'.$folder, 0777, TRUE);
		}
		$path = file_path_relative('barcode').'/'.$folder.'/';
		$detail=[];
		foreach($new_data['detail_visitor'] as $row){
			$nama = $visitor_id.'-'.strtolower(url_title($row));
			$barcode = token($this->preference['barcode_size'], $this->preference['barcode_type'], 'A', $visitor_id);
			$params=[];
			$params['data'] = $barcode;
			$params['level'] = 'H';
			$params['size'] = 5;
			$params['savename'] = $path.$nama.'.png';
			$this->ciqrcode->generate($params);

			$this->crud->crud_table(_TBL_VISITOR_DETAIL);
			$this->crud->crud_type('add');
			$this->crud->crud_field('visitor_id', $visitor_id, 'int');
			$this->crud->crud_field('nama_visitor', $row);
			$this->crud->crud_field('barcode', $barcode);
			$this->crud->crud_field('file_barcode', 'barcode'.'/'.$folder.'/'.$nama.'.png');
			$this->crud->process_crud();

			$visitor_detail_id = $this->crud->last_id();

			$this->crud->crud_table(_TBL_VISITOR_ROUTE);
			$this->crud->crud_type('add');
			$this->crud->crud_field('visitor_detail_id', $visitor_detail_id, 'int');
			$this->crud->crud_field('pos_id', $this->configuration['user']['pos_id']);
			$this->crud->crud_field('petugas_id', $this->configuration['user']['id']);
			$this->crud->crud_field('tanggal', date('Y-m-d H:i:s'));
			$this->crud->crud_field('status', '1', 'int');
			$this->crud->process_crud();

			$detail[]=['nama'=>$row, 'barcode'=>'barcode'.'/'.$folder.'/'.$nama.'.png'];
		}
		$new_data['detail']=$detail;
		$this->session->set_flashdata('register', $new_data);


		header('location:'.base_url('success-registration'));
	}

	function get_search(){
		$post = $this->input->post();
		$this->data->mode=$post['mode'];
		$dat['statistik']=$this->data->get_data_statistik();
		$hasil['combo']=$this->load->view('statistik',$dat, true);
		echo json_encode($hasil);
	}

	function success_registration(){
		$post = $this->session->flashdata('register');

		//$post['detail'] = [['nama'=>'Tri Untoro','barcode'=>'barcode/2020/02/1-tri-untoro.png'],['nama'=>'Muhammad Ihsan','barcode'=>'barcode/2020/02/1-muhammad-ihsan.png'],['nama'=>'Mutiara Untari','barcode'=>'barcode/2020/02/1-mutiara-untari.png']];
		$car = $this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'kendaraan')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$identitas = $this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'identitas')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();


		$data['post']=$post;
		// $data['form1'][]=['label'=>lang('form_nama'),'content'=>$post['nama']];
		// $data['form1'][]=['label'=>lang('form_identitas_id'),'content'=>$post['identitas_id']];
		// $data['form1'][]=['label'=>lang('form_identitas'),'content'=>$post['identitas']];
		// $data['form1'][]=['label'=>lang('form_pekerjaan'),'content'=>$post['pekerjaan']];
		// $data['form1'][]=['label'=>lang('form_alamat'),'content'=>$post['alamat']];
		// $data['form1'][]=['label'=>lang('form_perusahaan'),'content'=>$post['perusahaan']];
		// $data['form1'][]=['label'=>lang('form_telp'),'content'=>$post['telp']];
		// $data['form1'][]=['label'=>lang('form_email'),'content'=>$post['email']];
		// $data['form1'][]=['label'=>lang('form_tujuan'),'content'=>$post['tujuan']];
		// $data['form1'][]=['label'=>lang('form_janjian'),'content'=>$post['sts_janji']];
		// $data['form1'][]=['label'=>lang('form_kepentingan'),'content'=>$post['kepentingan']];
		// $data['form2'][]=['label'=>lang('form_jml'),'content'=>$post['jml']];
		// // $data['form2'][]=['label'=>lang('form_detail_visitor'),'content'=>$user];
		// $data['form2'][]=['label'=>lang('form_kendaraan'),'content'=>$post['kendaraan']];
		// $data['form2'][]=['label'=>lang('form_nopol'),'content'=>$post['nopol']];
		// $data['form2'][]=['label'=>lang('form_tanggal'),'content'=>$post['tanggal']];
		// $data['form2'][]=['label'=>lang('form_jam_masuk'),'content'=>$post['jam_masuk']];
		// $data['form2'][]=['label'=>lang('form_jam_keluar'),'content'=>$post['jam_keluar']];
		$content = $this->load->view('visitor-print',$data, true);
		$this->default_display(['content'=>$content]);
	}
}