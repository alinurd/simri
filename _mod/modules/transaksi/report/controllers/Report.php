<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends MY_Controller {

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

	}

	function content($ty='detail'){
		$data['cboStatus']=form_dropdown('status', [-1=>'Semua data',0=>'Pesan',1=>'Proses', 2=>'Selesai', 3=>'Cancel'], -1, 'class="form-control" id="status"');
		$data['cboWaktu']=form_dropdown('waktu', [1=>'Tanggal', 2=>'Bulan'], 1, 'class="form-control" id="waktu"');
		$data['cboTanggal1']= form_input('tanggal1', date('d F, Y'),' id="tanggal1" class="form-control pickadate" ');
		$data['cboTanggal2']= form_input('tanggal2', date('d F, Y'),' id="tanggal2" class="form-control pickadate" ');
		$rows = $this->db->select('tahun')->distinct()->order_by('tahun')->get(_TBL_VIEW_ORDERS)->result_array();
		$arr=[];
		foreach($rows as $row){
			$arr[$row['tahun']]=$row['tahun'];
		}
		$data['cboTahun']=form_dropdown('tahun', $arr, date('Y'), 'class="form-control select" id="tahun"');
		$rows = $this->db->select('bulan, cbulan')->where('tahun', date('Y'))->distinct()->order_by('bulan')->get(_TBL_VIEW_ORDERS)->result_array();
		$arr=[];
		foreach($rows as $row){
			$arr[$row['bulan']]=$row['cbulan'];
		}
		$data['cboBulan']=form_dropdown('bulan', $arr, date('m'), 'class="form-control select" id="bulan"  multiple="multiple"');
		$rows = $this->db->select('id, data')->where('kelompok','cat-product')->order_by('data')->get(_TBL_COMBO)->result_array();
		$arr=[0=>'semua categori'];
		foreach($rows as $row){
			$arr[$row['id']]=$row['data'];
		}
		$data['cboCatProduct']=form_dropdown('category', $arr, 0, 'class="form-control select" id="category"  multiple="multiple"');
		$arr=$this->datacombo->upperGroup()->set_data()->isGroup()->set_noblank(false)->build('product');
		$data['cboProduct'] = form_dropdown('product', $arr, '', 'class="form-control select" style="width:100% !important;" multiple="multiple" id="product"');

		$this->hasil=$this->load->view('view',$data, true);

		return $this->hasil;
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

	function get_bulan(){
		$tahun = intval($this->input->post('tahun'));
		$rows = $this->db->select('bulan, cbulan')->where('tahun', $tahun)->distinct()->order_by('bulan')->get(_TBL_VIEW_ORDERS)->result_array();
		$arr='';
		foreach($rows as $row){
			$arr .='<option value="'.$row['bulan'].'">'.$row['cbulan'].'</option>';
		}

		echo json_encode(['combo'=>$arr]);
	}

	function get_product(){
		$categori = $this->input->post('category');
		if ($categori){
			if ($categori[0]>0){
				$rows=$this->datacombo->upperGroup()->set_data()->where(['category_id'=>$categori])->isGroup()->set_noblank(false)->build('product');
			}else{
				$rows=$this->datacombo->upperGroup()->set_data()->isGroup()->set_noblank(false)->build('product');
			}
		}else{
			$rows=$this->datacombo->upperGroup()->set_data()->isGroup()->set_noblank(false)->build('product');
		}
		$combo = form_dropdown('product', $rows, '', 'class="form-control select" style="width:100% !important;" multiple="multiple" id="product"');

		echo json_encode(['combo'=>$combo]);
	}
	
	function proses_search(){
		$post=$this->input->post();
		$this->data->post=$post;
		$data['rows'] = $this->data->get_data();
		$hasil['combo'] = $this->load->view('lap', $data, true);
		echo json_encode($hasil);
	}
}