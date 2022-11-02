<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_Loss_Event extends MY_Controller {

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
		$x = $this->session->userdata('periode');
		$tgl1=date('Y-m-d');
		$tgl2=date('Y-m-d');
		if ($x){
			$tgl1=$x['tgl_awal'];
			$tgl2=$x['tgl_akhir'];
		}
		$data['owner']=$this->get_combo_parent_dept();
		$data['period']=$this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'period')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$data['data']=$this->data->get_data();
		$data['period_id']= _TAHUN_ID_;
		$data['owner_no'] = 0;
		
		$data['tbl']=$this->hasil=$this->load->view('lap',$data, true);
		$this->hasil=$this->load->view('view',$data, true);

		return $this->hasil;
	}

	function init($aksi=''){
		$configuration = [
			'show_title_header' => false, 
			'show_action_button' => false, 
			'box_content' => false, 
			'left_sidebar_mini' => false,
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
		$data['period_id'] = $post['period_id'];
		$data['owner_no'] = $post['owner_no'];
		$data['data'] = $this->data->get_data($post);
		$hasil['combo'] = $this->load->view('lap', $data, true);
		echo json_encode($hasil);
	}

	function cetak_register($period, $owner)
	{
		// $data['id']=$id;
		$post = [
			'owner_no' => $owner,
			'period_id' => $period,
		];
		$data['period_id'] = $period;
		$data['owner_no'] = $owner;
		$data['export'] = false;
		$data['data'] = ($owner==0)? $this->data->get_data():$this->data->get_data($post);

		$hasil = $this->load->view('lap', $data, true);
		$cetak = 'register_excel';
		$nm_file = 'Laporan-Loss-Event';
		$this->$cetak($hasil, $nm_file);
	}

	function register_excel($data, $nm_file)
	{
		header("Content-type:appalication/vnd.ms-excel");
		header("content-disposition:attachment;filename=" . $nm_file . ".xls");

		$html = $data;
		echo $html;
		exit;
	}
}