<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MX_Model {
	public function __construct()
    {
        parent::__construct();
	}

	function save_detail($id , $new_data, $old_data, $mode)
	{
		// upload image product
		$datas=[];
		// dump($new_data);die();
		if (isset($new_data['upload_name_tmp'])){
			if(count($new_data['upload_name_tmp'])>0){
				foreach($new_data['upload_name_tmp'] as $key=>$row)
				{
					$data=[];
					$nama = $row;
					if ($new_data['type_gallery_tmp'][$key]==1){
						$nama = parse_yturl($new_data['text_video_tmp'][$key]);
						$nama = $this->_params['url_youtube'].$nama;
					}

					$data['name']=$nama;
					$data['real_name']=$nama;
					$data['type']=$new_data['type_gallery_tmp'][$key];
					$data['title']=$new_data['upload_title_tmp'][$key];
					$data['note']=$new_data['upload_note_tmp'][$key];
					$data['sticky']=$new_data['upload_sticky_tmp'][$key];
					$data['default']=$new_data['upload_default_tmp'][$key];
					$data['active']=$new_data['upload_active_tmp'][$key];
					$datas[]=$data;
				}
			}
		}

		$this->load->library('image');
		$path='file_path_relative';
		if (array_key_exists('upload_image', $_FILES)){
			$jml=count($_FILES['upload_image']['name']);

			$files = $_FILES;
			for($x=0;$x<$jml;++$x){
				if (intval($new_data['type_gallery'][$x])==0){
					if (!empty($files['upload_image']['name'][$x])) {
						$data=[];
						$_FILES['upload_imagex']['name'] = $files['upload_image']['name'][$x];
						$_FILES['upload_imagex']['type'] = $files['upload_image']['type'][$x];
						$_FILES['upload_imagex']['tmp_name'] = $files['upload_image']['tmp_name'][$x];
						$_FILES['upload_imagex']['error'] = $files['upload_image']['error'][$x];
						$_FILES['upload_imagex']['size'] = $files['upload_image']['size'][$x];

						$this->image->set_Param('nm_file', 'upload_imagex');
						$this->image->set_Param('file_name', $_FILES['upload_image']['name'][$x]);
						$this->image->set_Param('path',$path('pages'));
						$this->image->set_Param('thumb',true);
						$this->image->set_Param('type','gif|jpg|jpeg|png');
						$this->image->set_Param('size', 1000000);
						$this->image->set_Param('nm_random', true);
						$this->image->set_Param('multi', true);
						$this->image->set_Param('image_no', $x);

						$this->image->upload();

						$real=str_replace(' ','-',$files['upload_image']['name'][$x]);
						$real=str_replace('---','-',$real);
						$real=str_replace('--','-',$real);
						$real=strtolower($real);

						$data['name']='pages/'.$this->image->result('file_name');
						$data['real_name']=$real;
						$data['type']=$new_data['type_gallery'][$x];
						$data['title']=$new_data['upload_title'][$x];
						$data['note']=$new_data['upload_note'][$x];
						$data['sticky']=$new_data['upload_sticky'][$x];
						$data['default']=$new_data['upload_default'][$x];
						$data['active']=$new_data['upload_active'][$x];
						$datas[]=$data;
					}
				}else{
					$nama = parse_yturl($new_data['text_video'][$x]);
					$nama = $this->_params['url_youtube'].$nama;
					$data['name']=$nama;
					$data['real_name']=$nama;
					$data['type']=$new_data['type_gallery'][$x];
					$data['title']=$new_data['upload_title'][$x];
					$data['note']=$new_data['upload_note'][$x];
					$data['sticky']=$new_data['upload_sticky'][$x];
					$data['default']=$new_data['upload_default'][$x];
					$data['active']=$new_data['upload_active'][$x];
					$datas[]=$data;
				}
			}
		}
		$this->crud->crud_table(_TBL_NEWS);
		$this->crud->crud_field('photo', json_encode($datas));
		$this->crud->crud_where(['field'=>'id', 'value'=>$id, 'op'=>'=']);
		$this->crud->crud_type('edit');
		$this->crud->process_crud();
		return true;
	}
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */