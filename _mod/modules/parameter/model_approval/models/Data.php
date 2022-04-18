<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MX_Model {
	public function __construct()
    {
        parent::__construct();
	}
	
	function save_detail($newid=0,$data=array(), $img)
	{
		if (isset($data['edit_id'])){
			if(count($data['edit_id'])>0){
				$no=0;
				foreach($data['edit_id'] as $key=>$row)
				{
					if (!empty($data['alur'][$key])){
						$this->crud->crud_table(_TBL_COMBO);
						$this->crud->crud_field('pid', $newid, 'int');
						$this->crud->crud_field('param_int', $data['alur'][$key], 'int');
						$ac['tipe_approval']=$data['type_id'][$key];
						$ac['level_approval']=$data['level_id'][$key];
						$ac['monit']=$data['sts_monit'][$key];
						$ac['notif_email']=$data['sts_notif'][$key];
						$this->crud->crud_field('param_text', json_encode($ac));
						$this->crud->crud_field('kelompok', 'alur-approval');
						$this->crud->crud_field('urut', ++$no);
						$sts_last = 0;
						if(count($data['edit_id'])==$no){
							$sts_last = 1;
						}
						$this->crud->crud_field('param_string', $sts_last);
						
						if(intval($data['edit_id'][$key])>0)
						{
							$this->crud->crud_where(['field'=>'id', 'value'=>$row, 'op'=>'=']);
							$this->crud->crud_field('updated_by', $this->ion_auth->get_user_name());
							$this->crud->crud_type('edit');
						}
						else
						{
							$title=create_unique_slug($data['alur'][$key], _TBL_COMBO);
							$this->crud->crud_field('uri_title', $title);
							$this->crud->crud_field('created_by', $this->ion_auth->get_user_name());
							$this->crud->crud_type('add');
						}
						$this->crud->process_crud();
					}
				}
			}
		}
		return true;
	}
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */