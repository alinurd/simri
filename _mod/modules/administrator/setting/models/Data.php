<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MX_Model {


	public function __construct()
    {
        parent::__construct();

	}

	function get_data(){
		$this->db->select('*');
		$this->db->from(_TBL_PREFERENCE);
		$query=$this->db->get();
		$data=array();
		if($query){
			$rows=$query->result();
			$data=array('id'=>0);
			foreach($rows as $key=>$row){
				$data[$row->uri_title]=$row->value;
			}
			$result['fields']=$data;
		}
		return $data;
	}

	function save_data($id, $data, $old_data, $mode){
		$this->load->library('image');
		$this->db->trans_begin();
		foreach($data['fields'] as $key=>$row)
		{
			if ($row['show']){
				if (array_key_exists($row['field'], $old_data)){
					// dump('key ke :'.$key);
					// dump($data['data'][$row['field']] .'!=='. $old_data[$row['field']]);
					if ($row['input']!=='upload'){
						if ($data['data'][$row['field']] !== $old_data[$row['field']]){
							if ($row['multiselect']){
								$value = implode(',', $data['data'][$row['field']]);
							}else{
								$value=$data['data'][$row['field']];
							}
							$this->crud->crud_table(_TBL_PREFERENCE);
							$this->crud->crud_type('edit');
							$this->crud->crud_where(['field'=>'uri_title', 'value'=>$row['field'], 'op'=>'=']);
							$this->crud->crud_field('value', $value);

							$this->crud->process_crud();
						}
					}
				}
			}
		}

		if (array_key_exists('image_disc', $_FILES)){
			if (!empty($_FILES['image_disc']['name'])) {
				$this->image->set_Param('nm_file', 'image_disc');
				$this->image->set_Param('file_name', $_FILES['image_disc']['name']);
				$this->image->set_Param('path',img_path_relative());
				$this->image->set_Param('thumb',false);
				$this->image->set_Param('type','gif|jpg|jpeg|png');
				$this->image->set_Param('size', 1000000);
				$this->image->set_Param('nm_random', false);
				$this->image->set_Param('multi', false);
				$this->image->upload();

				$value=$this->image->result('file_name');

				$this->crud->crud_table(_TBL_PREFERENCE);
				$this->crud->crud_type('edit');
				$this->crud->crud_where(['field'=>'uri_title', 'value'=>'image_disc', 'op'=>'=']);
				$this->crud->crud_field('value', $value);

				$this->crud->process_crud();
			}
		}

		if (array_key_exists('image_login', $_FILES)){
			if (!empty($_FILES['image_login']['name'])) {
				$this->image->set_Param('nm_file', 'image_login');
				$this->image->set_Param('file_name', $_FILES['image_login']['name']);
				$this->image->set_Param('path',img_path_relative());
				$this->image->set_Param('thumb',false);
				$this->image->set_Param('type','gif|jpg|jpeg|png');
				$this->image->set_Param('size', 1000000);
				$this->image->set_Param('nm_random', false);
				$this->image->set_Param('multi', false);
				$this->image->upload();

				$value=$this->image->result('file_name');

				$this->crud->crud_table(_TBL_PREFERENCE);
				$this->crud->crud_type('edit');
				$this->crud->crud_where(['field'=>'uri_title', 'value'=>'image_login', 'op'=>'=']);
				$this->crud->crud_field('value', $value);

				$this->crud->process_crud();
			}
		}

		if (array_key_exists('logo_kantor', $_FILES)){
			if (!empty($_FILES['logo_kantor']['name'])) {
				$this->image->set_Param('nm_file', 'logo_kantor');
				$this->image->set_Param('file_name', 'newsletter');
				$this->image->set_Param('path',img_path_relative());
				$this->image->set_Param('thumb',false);
				$this->image->set_Param('type','*');
				$this->image->set_Param('size', 1000000);
				$this->image->set_Param('nm_random', false);
				$this->image->set_Param('multi', false);
				$this->image->upload();

				$value=$this->image->result('file_name');

				$this->crud->crud_table(_TBL_PREFERENCE);
				$this->crud->crud_type('edit');
				$this->crud->crud_where(['field'=>'uri_title', 'value'=>'logo_kantor', 'op'=>'=']);
				$this->crud->crud_field('value', $value);

				$this->crud->process_crud();
			}
		}

		$this->db->trans_commit();
		// die();
		$this->data_config->tipe=0;
		$this->data_config->set_Preference();
		$this->data_config->tipe=1;
		$this->data_config->set_Preference();
		//$this->session->set_userdata(array('result_proses'=>lang('msg_success_save_edit')));
		return true;
	}
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */