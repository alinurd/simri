<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kepatuhan_Monitoring extends MY_Controller {

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
		$this->load->library('map');
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
		$x = $this->session->userdata('periode');
		$tgl1=date('Y-m-d');
		$tgl2=date('Y-m-d');
		if ($x){
			$tgl1=$x['tgl_awal'];
			$tgl2=$x['tgl_akhir'];
		}
		// dumps($x);
		$asse_type=$this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'ass-type')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$owner =$this->get_combo_parent_dept();
		$period =$this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'period')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$term=$this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'term')->combo_where('pid', _TAHUN_ID_)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();


		$minggu=$this->crud->combo_select(['id', 'param_string as minggu'])->combo_where('kelompok', 'minggu')->combo_where('param_date>=', $tgl1)->combo_where('param_date<=', $tgl2)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		unset($minggu[""]);
		
		$this->data->pos['tgl1']=$tgl1;
		$this->data->pos['tgl2']=$tgl2;
		$this->data->pos['owner']=0;
		$this->data->pos['type_ass']=0;
		$this->data->pos['period']=_TAHUN_ID_;
		$this->data->pos['term']=_TERM_ID_;
		$this->data->pos['minggu']=_MINGGU_ID_;

		$data = [];
		$divisi = $this->data->get_data_lap_basic();
		$data = $this->data->get_data_lap();
		
		$data['periode'] = $period;
		$data['term'] = $term;
		$data['minggu'] = $minggu;
		$data['asse_type'] = $asse_type;
		$data['divisi'] = $divisi;
		$data['term_t'] = '';
		
		// $data['asse_type'] = $this->get_combo('data-combo', 'asse-tipe');;

		$data['title'] = _TERM_ . ' - ' . _TAHUN_;
		$data['is_triwulan'] = (strpos(strtolower(_TERM_), 'triwulan')!== false)?1:0;

		$data['detail'] = $this->load->view('detail', $data, true);
		$data['detail2'] = $this->load->view('detail2', $data, true);

		$this->hasil=$this->load->view('lapunit',$data, true);
		return $this->hasil;
	}

	function get_map(){
		$this->pos=$this->input->post();
		$this->data->pos=$this->pos;
		$this->data->owner_child=[];
		$this->data->owner_child[]=intval($this->pos['owner']);
		$this->data->get_owner_child(intval($this->pos['owner']));
		$this->owner_child=$this->data->owner_child;
		
		$x=$this->data->get_data_grap();

		$dat['data']=$x['tepat'];
		$hasil['grap2'] = $this->hasil=$this->load->view('grap3',$dat, true);
		$hasil['data_grap2']= $this->hasil=$this->load->view('grap4',$dat, true);
		header('Content-type: application/json');
		echo json_encode($hasil);
	}

	function get_detail_char(){
		$pos=$this->input->post();
		$this->data->pos=$pos;
		$data = $this->data->get_detail_char();
		$data['mode']=0;
		$x=$this->load->view('detail-char-'.$pos['data']['type_chat'], $data, true);
		$hasil['combo']=$x;
		$this->session->set_userdata(['cetak_grap'=>$data]);
		$this->session->set_userdata(['type_chat'=>$pos['data']['type_chat']]);
		header('Content-type: application/json');
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
		$type = $this->session->userdata('type_chat');
		$data['mode']=1;
		$x=$this->load->view('detail-char-'.$type, $data, true);
		$file_name ="file_name.xls";
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$file_name");
		echo $x;
		
	}

	function get_detail()
	{
		$post = $this->input->post();
		$offset = strpos($post['term_t'],'[');
		$tgl = substr($post['term_t'], $offset+1, 10);

		// $x = $post['term'];
		$tgl1=date('Y-m-d');
		$tgl2=date('Y-m-d');
	
		$term= $this->db->select('param_date, param_date_after')->where('kelompok','term')->where('id', $post['term'])->where('active', 1)->get(_TBL_COMBO)->row_array();
		if ($term) {
			$tgl1 = $term['param_date'];
			$tgl2 = $term['param_date_after'];
		}
		
		$minggu=$this->crud->combo_select(['id', 'param_string as minggu'])->combo_where('kelompok', 'minggu')->combo_where('param_date>=', $tgl1)->combo_where('param_date<=', $tgl2)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		unset($minggu[""]);

		
		$data = $this->data->get_data_lap($post['tahun'], $post['term'], 0, $post['divisi'], $tgl);
	
		$data['divisi'] = $post['divisi'];
		$data['minggu'] = $minggu;
		$data['term_t'] = $tgl;
		$data['title'] = $post['term_t'] . ' - ' . $post['tahun_t'];
		// $data['title'] = "cek";
		$data['is_triwulan'] = (strpos(strtolower($post['term_t']), 'triwulan')!== false)?1:0;
		
		$hasil['detail'] = $this->load->view('detail', $data, true);
		header('Content-type: application/json');
		echo json_encode($hasil);
	}
}