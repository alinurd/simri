<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_Tipe_Risiko extends MY_Controller {

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
		$this->load->language('risk_context');

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
		$data['term']=$this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'term')->combo_where('pid', _TAHUN_ID_)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$data['minggu']=$this->crud->combo_select(['id', 'concat(param_string, \' ( \', param_date, \' s.d \', param_date_after, \' ) \') as minggu'])->combo_where('kelompok', 'minggu')->combo_where('param_date>=', $tgl1)->combo_where('param_date<=', $tgl2)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		// $data['minggu']=$this->crud->combo_select(['id', 'concat(param_string,\' minggu ke - \',data, \' ( \', param_date, \' s.d \', param_date_after, \' ) \') as minggu'])->combo_where('kelompok', 'minggu')->combo_where('param_date>=', $tgl1)->combo_where('param_date<=', $tgl2)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
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
	
	function proses_search(){
		$post=$this->input->post();
		$this->data->post=$post;
		$data = $this->data->get_data();
		$data['mode']=0;
		$data['post']=$post;
		$hasil['combo'] = $this->load->view('lap', $data, true);
		$this->session->set_userdata(['cetak_grap'=>$data]);
		header('Content-type: application/json');
		echo json_encode($hasil);
	}

	function cetak(){
		$data = $this->session->userdata('cetak_grap');
		$data['mode']=1;
		$data['post']=[];

		$x=$this->load->view('lap', $data, true);
		$file_name ="laporan-tipe-risiko.xls";
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$file_name");
		echo $x;
		
	}

	function get_detail_map(){
		$post = $this->input->post();
		$this->data->post=$post;
		$x=$this->data->get_data_map();
		$hasil['combo']=$this->load->view('identifikasi', $x, true);
		header('Content-type: application/json');
		echo json_encode($hasil);
	}

	function cetak_register($type, $period, $term)
    {
		$data=$this->data->get_data_register_bytype($type, $period, $term);
		
		$data['export']=false;
		$hasil = $this->load->view('register', $data, true);
		// $n = $data['parent']['kode_dept'].'-'.$data['parent']['term'].'-'.$data['parent']['period_name'];
		$n = '';

        $cetak = 'register_excel';
        $nm_file = 'Laporan-Risk-Register-'.$n;
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