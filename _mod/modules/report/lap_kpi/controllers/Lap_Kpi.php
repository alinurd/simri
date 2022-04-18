<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lap_Kpi extends MY_Controller {

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
	protected $owner_child=[];
	protected $pos=[];
	public function __construct()
	{
		parent::__construct();
		$this->load->language('risk_context');
		$this->load->language('monitoring_mitigasi');

	// 	$dat=$this->db->select('param_text')->where('id', 1)->get(_TBL_COMBO)->row_array();

	// 		$dats=unserialize($dat['param_text']);
	// // dumps($dats);
	// unset($dats[115910]);
	// unset($dats[120592]);
	// dumps($dats);
	// die();

	// dumps($this->_preference_);die();

	}

	function content($ty='detail'){
		$data['owner']=$this->get_combo_parent_dept();
		$data['period']=$this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'period')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$data['term']=$this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'term')->combo_where('pid', _TAHUN_ID_)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();

		$x = $this->session->userdata('periode');
		$tgl1=date('Y-m-d');
		$tgl2=date('Y-m-d');
		if ($x){
			$tgl1=$x['tgl_awal'];
			$tgl2=$x['tgl_akhir'];
		}
		$data['minggu']=$this->crud->combo_select(['id', 'concat(param_string, \' ( \', param_date, \' s.d \', param_date_after, \' ) \') as minggu'])->combo_where('kelompok', 'minggu')->combo_where('param_date>=', $tgl1)->combo_where('param_date<=', $tgl2)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		// $data['minggu']=$this->crud->combo_select(['id', 'concat(param_string,\' minggu ke - \',data, \' ( \', param_date, \' s.d \', param_date_after, \' ) \') as minggu'])->combo_where('kelompok', 'minggu')->combo_where('param_date>=', $tgl1)->combo_where('param_date<=', $tgl2)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		// die($this->db->last_query());

		$this->data->pos['owner']=0;
		$this->data->pos['period']=_TAHUN_ID_;
		$this->data->pos['term']=_TERM_ID_;
		$this->data->pos['minggu']=_MINGGU_ID_;
		
		$this->hasil=$this->load->view('view',$data, true);
		return $this->hasil;
	}

	function get_lap(){
		$pos=$this->input->get();
		$this->data->pos=$pos;
		$data = $this->data->get_detail_data();
		$data['mode']=0;
		$x=$this->load->view('detail', $data, true);
		$y=$this->load->view('detail2', $data, true);
		$this->session->set_userdata(['cetak_grap'=>$data]);
		$hasil['combo']=$x.$y;
		echo json_encode($hasil);
	}

	function init($aksi=''){
		$configuration = [
			'show_title_header' => false,
			'show_action_button' => false,
			'show_header_content' => false,
			'box_content' => false,
		];
		return [
			'configuration'	=> $configuration
		];
	}

	function cetak(){
		$data = $this->session->userdata('cetak_grap');
		$data['mode']=1;
		$x=$this->load->view('detail', $data, true);
		$y=$this->load->view('detail2', $data, true);
		$file_name ="file_name.xls";
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$file_name");
		echo $x.$y;
		
	}
}