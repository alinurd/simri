<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lap_Monitoring_Mitigasi extends MY_Controller {

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
		$data['type_ass']=$this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'ass-type')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$data['owner']=$this->get_combo_parent_dept();
		$data['period']=$this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'period')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$data['term']=$this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'term')->combo_where('pid', _TAHUN_ID_)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();

		$data['minggu']=$this->crud->combo_select(['id', 'concat(param_string, \' ( \', param_date, \' s.d \', param_date_after, \' ) \') as minggu'])->combo_where('kelompok', 'minggu')->combo_where('param_date>=', $tgl1)->combo_where('param_date<=', $tgl2)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		// $data['minggu']=$this->crud->combo_select(['id', 'concat(param_string,\' minggu ke - \',data, \' ( \', param_date, \' s.d \', param_date_after, \' ) \') as minggu'])->combo_where('kelompok', 'minggu')->combo_where('param_date>=', $tgl1)->combo_where('param_date<=', $tgl2)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		// die($this->db->last_query());

		$this->data->pos['tgl1']=$tgl1;
		$this->data->pos['tgl2']=$tgl2;
		$this->data->pos['owner']=0;
		$this->data->pos['type_ass']=0;
		$this->data->pos['period']=_TAHUN_ID_;
		$this->data->pos['term']=_TERM_ID_;
		$this->data->pos['minggu']=0;

		$x=$this->data->get_data_grap();
		$dat['data']=$x['mitigasi'];
		// dumps($x['mitigasi']);
		// die();
		$data['grap1'] = $this->hasil=$this->load->view('grap',$dat, true);
		$data['data_grap1']= $this->hasil=$this->load->view('grap2',$dat, true);

		$this->hasil=$this->load->view('dashboard',$data, true);
		return $this->hasil;
	}

	function map(){
		$this->data->filter_data();
		$rows = $this->db->SELECT('risiko_inherent as id, COUNT(risiko_inherent) as nilai')->group_by('risiko_inherent')->get(_TBL_VIEW_RCSA_DETAIL)->result_array();
		$data['map_inherent']=$this->map->set_data($rows)->set_param(['tipe'=>'angka', 'level'=>1])->draw();
		$jml=$this->map->get_total_nilai();
		$data['jml_inherent']='';
		if ($jml>0){
			$data['jml_inherent']='<span class="badge bg-primary badge-pill"> '.$jml.' </span>';
		}
		$this->data->filter_data();
		$rows = $this->db->SELECT('risiko_residual as id, COUNT(risiko_residual) as nilai')->group_by('risiko_residual')->get(_TBL_VIEW_RCSA_DETAIL)->result_array();
		$data['map_residual']=$this->map->set_data($rows)->set_param(['tipe'=>'angka', 'level'=>2])->draw();
		$jml=$this->map->get_total_nilai();
		$data['jml_residual']='';
		if ($jml>0){
			$data['jml_residual']='<span class="badge bg-success badge-pill"> '.$jml.' </span>';
		}
		$this->data->filter_data();
		$rows = $this->db->SELECT('risiko_target as id, COUNT(risiko_target) as nilai')->group_by('risiko_target')->get(_TBL_VIEW_RCSA_DETAIL)->result_array();
		$data['map_target']=$this->map->set_data($rows)->set_param(['tipe'=>'angka', 'level'=>3])->draw();
		$jml=$this->map->get_total_nilai();
		$data['jml_target']='';
		if ($jml>0){
			$data['jml_target']='<span class="badge bg-warning badge-pill"> '.$jml.' </span>';
		}
		return $data;
	}

	function get_map(){
		$this->pos=$this->input->post();
		$this->data->pos=$this->pos;
		$this->data->owner_child=[];
		$this->data->owner_child[]=intval($this->pos['owner']);
		$this->data->get_owner_child(intval($this->pos['owner']));
		$this->owner_child=$this->data->owner_child;

		// $data=$this->map();
		// $hasil['combo']=$this->load->view('map',$data, true);

		if ($this->pos['owner']!=0 && $this->pos['period']!=0) {
			$y=$this->data->get_data_kompilasi($this->pos['period'],$this->pos['owner'],$this->pos['type_ass']);
			$y['pos'] = $this->pos;
			$hasil['kompi']= $this->hasil=$this->load->view('monitoring_detail',$y, true);
		}else{
			$hasil['kompi']= '';
		}

		$x=$this->data->get_data_grap();

		$dat['data']=$x['mitigasi'];
		// $hasil['grap1'] = $this->hasil=$this->load->view('grap',$dat, true);
		// $hasil['data_grap1']= $this->hasil=$this->load->view('grap2',$dat, true);

		// $dat['data']=$x['tepat'];
		// $hasil['grap2'] = $this->hasil=$this->load->view('grap3',$dat, true);
		// $hasil['data_grap2']= $this->hasil=$this->load->view('grap4',$dat, true);

		// $hasil['grap3'] = $this->hasil=$this->load->view('grap4',$dat, true);
		// $hasil['data_grap3']= $this->hasil=$this->load->view('grap5',$dat, true);

		echo json_encode($hasil);
	}

	function cetak_register($period,$owner,$type_ass=128)
    {
		$data=$this->data->get_data_kompilasi($period,$owner,$type_ass);

		// $data['id']=$id;
		$data['pos'] = [
			'owner' => $owner,
			'period' => $period,
			'type_ass' =>$type_ass
		];
		$data['export']=false;

		
		$hasil= $this->load->view('monitoring_detail',$data, true);


        $cetak = 'register_excel';
		$nm_file = 'Laporan-Monitoring-Mitigasi';
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

	function get_detail_char(){
		$pos=$this->input->post();
		$this->data->pos=$pos;
		$this->pos=$pos;
		$this->data->owner_child=[];
		$this->data->owner_child[]=intval($this->pos['owner']);
		$this->data->get_owner_child(intval($this->pos['owner']));
		$this->owner_child=$this->data->owner_child;

		$data = $this->data->get_detail_char();
		// dumps($data);
		// die();
		$data['mode']=0;
		$x=$this->load->view('detail-char-'.$pos['data']['type_chat'], $data, true);
		$hasil['combo']=$x;
		$this->session->set_userdata(['cetak_grap'=>$data]);
		$this->session->set_userdata(['type_chat'=>$pos['data']['type_chat']]);

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
}