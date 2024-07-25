<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MX_Model {
	public function __construct()
    {
        parent::__construct();
	}

	function simpan_progres($data){
		$id=intval($data['id']);
		$this->crud->crud_table(_TBL_RCSA_MITIGASI_PROGRES);
		$this->crud->crud_field('rcsa_mitigasi_detail_id', $data['aktifitas_mitigasi_id']);
		$this->crud->crud_field('minggu_id', $data['minggu']);
		$this->crud->crud_field('target', $data['target']);
		$this->crud->crud_field('aktual', $data['aktual']);
		$this->crud->crud_field('uraian', $data['uraian']);
		$this->crud->crud_field('kendala', $data['kendala']);
		$this->crud->crud_field('tindak_lanjut', $data['tindak_lanjut']);
		$this->crud->crud_field('batas_waktu_tindak_lanjut', $data['batas_waktu_tindak_lanjut_submit'], 'date');
		$this->crud->crud_field('keterangan', $data['keterangan']);
		// $this->crud->crud_field('lampiran', $data['lampiran']);

		if ($id>0){
			$this->crud->crud_type('edit');
			$this->crud->crud_where(['field' => 'id', 'value' => $id]);
			$this->crud->crud_field('updated_by', $this->ion_auth->get_user_name());
		}else{
			$this->crud->crud_type('add');
			$this->crud->crud_field('created_by', $this->ion_auth->get_user_name());
		}
		$this->crud->process_crud();
		if($id==0){
			$id = $this->crud->last_id();
        }
        
        $rows = $this->db->where('rcsa_mitigasi_detail_id', $data['aktifitas_mitigasi_id'])->order_by('aktual','desc')->limit(1)->get(_TBL_VIEW_RCSA_MITIGASI_PROGRES)->row_array();
        
        if ($rows){
            $this->crud->crud_table(_TBL_RCSA_MITIGASI_DETAIL);
            $this->crud->crud_field('aktual', $rows['aktual'], 'int');
            $this->crud->crud_field('target', $rows['target'], 'int');
            $this->crud->crud_type('edit');
            $this->crud->crud_where(['field' => 'id', 'value' => $data['aktifitas_mitigasi_id']]);
            $this->crud->process_crud();
        }

		return $id;
	}
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */