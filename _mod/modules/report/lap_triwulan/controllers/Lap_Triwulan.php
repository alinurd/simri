<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lap_Triwulan extends MY_Controller {

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
		$data=$this->map();
		$data['type_ass']=$this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'ass-type')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$data['owner']=$this->get_combo_parent_dept();
		$data['period']=$this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'period')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$data['term']=$this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'term')->combo_where('pid', _TAHUN_ID_)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		$data['minggu'] = $this->crud->combo_select(['id', 'concat(param_string, \' ( \', param_date, \' s.d \', param_date_after, \' ) \') as minggu'])->combo_where('kelompok', 'minggu')->combo_where('param_date>=', $tgl1)->combo_where('param_date<=', $tgl2)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		// die($this->db->last_query());

		$this->data->pos['owner']=0;
		$this->data->pos['type_ass']=0;
		$this->data->pos['period']=_TAHUN_ID_;
		$this->data->pos['term']=_TERM_ID_;
		$this->data->pos['minggu'] = _MINGGU_ID_;

		$data['map']=$this->load->view('map',$data, true);
		$this->hasil=$this->load->view('dashboard',$data, true);
		return $this->hasil;
	}

	function map(){
		//cari totalrows=$thsoi>
		$rows = $this->db->select('*, 0 as jml')->order_by('urut')->get(_TBL_LEVEL_COLOR)->result_array();
		$level=[];
		foreach($rows as $row){
			$level[$row['level_color']]=$row;
		}

		$this->data->filter_data();
		$rows = $this->db->SELECT('risiko_inherent as id, COUNT(risiko_inherent) as nilai')->group_by('risiko_inherent')->get(_TBL_VIEW_RCSA_DETAIL)->result_array();
		$data['map_inherent']=$this->map->set_data($rows)->set_param(['tipe'=>'angka', 'level'=>1])->draw();
		$jml=$this->map->get_total_nilai();
		$data['jml_inherent']='';
		if ($jml>0){
			$data['jml_inherent']='<span class="badge bg-primary badge-pill"> '.$jml.' </span>';
		}
		$this->data->filter_data();
		$rowsCurrent                      = $this->db->SELECT( 'risiko_target_mon as id, COUNT(risiko_target_mon) as nilai, level_color_mon , level_color_residual, level_color_target, bulan_mon, mon_id, id as detail_id' )->group_by( 'risiko_target_mon' )->get( "il_view_rcsa_detail_monitoring" )->result_array();
		$data['map_residual']        = $this->map->_setDataMonitoring( $rowsCurrent )->_setParam( [ 'tipe' => 'angka', 'level' => 2, 'rows'=>$rows] )->draw_current();
		$x=$level;
		// doi::dump($rowsCurrent );
		foreach($rowsCurrent as $row){
			if (array_key_exists($row['level_color_mon'], $x)){
				$x[$row['level_color_mon']]['jml']+=intval($row['nilai']);
			}
		}
		$data['t_residual']=$x;
		// doi::dump($x );

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

		

		$this->data->filter_data();
		$rows = $this->db->SELECT('level_risk_no_target as id, level_color_target, COUNT(level_color_target) as nilai')->group_by('level_color_target')->get(_TBL_VIEW_RCSA_DETAIL)->result_array();
		$x=$level;
		foreach($rows as $row){
			if (array_key_exists($row['level_color_target'], $x)){
				$x[$row['level_color_target']]['jml']+=intval($row['nilai']);
			}
		}
		$data['t_target']=$x;
		
		$this->data->filter_data();
		$rows = $this->db->SELECT('id, penyebab_id, penyebab_risiko')->order_by('level_risk_no_residual desc, risiko_residual_text DESC')->limit(20)->get(_TBL_VIEW_RCSA_DETAIL)->result_array();
		$x=[];
		foreach($rows as $row){
			$x[]=$row['id'];
		}

		$this->db->select('rcsa_detail_id, mitigasi, penanggung_jawab_id, penanggung_jawab_detail, koordinator_id, batas_waktu, target, aktual');
		if (!empty($x)) {
			$this->db->where_in('rcsa_detail_id', $x);
		}else{
			$this->db->where_in('rcsa_detail_id', [0]);
		}
		$detail=$this->db->order_by('rcsa_detail_id')->get(_TBL_VIEW_RCSA_MITIGASI_DETAIL)->result_array();
		$x=[];
		if (!empty($detail)) {
			foreach($detail as $row){
				$x[$row['rcsa_detail_id']][]=$row;
			}
		}
		foreach($rows as &$row){
			if (array_key_exists($row['id'], $x)){
				$row['detail']=$x[$row['id']];
			}else{
				$row['detail']=[];
			}
		}
		unset($row);
		// dumps($rows);die();
		$data['top']=$rows;
		$data['level_risiko']=$level;

		return $data;
	}

	function get_map(){
		$this->pos=$this->input->post();
		$this->data->pos=$this->pos;
		$this->data->owner_child=[];
		$this->data->owner_child[]=intval($this->pos['owner']);
		$this->data->get_owner_child(intval($this->pos['owner']));
		$this->owner_child=$this->data->owner_child;

		$data=$this->map();
		$hasil['combo']=$this->load->view('map',$data, true);
		header('Content-type: application/json');
		echo json_encode($hasil);
	}

	function get_map_cetak(){
		$this->pos=$this->input->post();
		$this->data->pos=$this->pos;
		
		$this->data->owner_child=[];
		$this->data->owner_child[]=intval($this->pos['owner']);
		$this->data->get_owner_child(intval($this->pos['owner']));
		$this->owner_child=$this->data->owner_child;

		$data=$this->map();
		$hasil=$this->load->view('map',$data, true);

		echo $this->cetak_excel($hasil, 'Laporan Triwulan');
	}

	function cetak_excel($data, $nm_file)
    {
        header("Content-type:appalication/vnd.ms-excel");
        header("content-disposition:attachment;filename=" . $nm_file . ".xls");

        $html = $data;
        echo $html;
        exit;
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