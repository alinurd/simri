<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Image {
	private $ci;
	private $param=[];
	private $result=[];
	private $_error='';

	function __construct()
	{
        $this->ci =& get_instance();
        $this->param=[
            'path'=>file_path_relative(),
            'id'=>'0',
            'nm_random'=>true,
            'type'=>'gif|jpg|jpeg|png|pdf|xlsx|docx|ppt',
            'thumb'=>false,
            'split_folder'=>false,
            'multi'=>false,
            'image_no'=>0,
            'no'=>0,
            'size'=>10000000,
            'sub_path'=>'',
            'file_name'=>'file_'.date('dmY')
        ];
    }

    function set_Param($key,$value){
        $this->param[$key]=$value;
        return  $this;
    }

    function upload(){
        if ($this->param['split_folder']){
            $folder = date('Y') . '/' . date('m') ;
			if (!is_dir($this->param['path'].'/'.$folder)) {
				mkdir($this->param['path'].'/'.$folder, 0777, TRUE);
			}
            $this->param['path'] .= '/'.$folder;
        }
        if(!is_dir($this->param['path'])){
			return false;
        }

        $this->param['file_name'] = preg_replace('/(.*)\\.[^\\.]*/', '$1', $this->param['file_name']);
		if (!empty($this->param['id'])){
			$this->param['file_name'] = $this->param['id'].'-'.$this->param['file_name']; 
        }
        if ($this->param['nm_random'])
			$file_name=md5($this->param['file_name'].time());
		else
            $file_name=url_title(strtolower(basename($this->param['file_name'])));

        $config['file_name']=$file_name;
        $config['upload_path']=$this->param['path'];
		$config['allowed_types']=$this->param['type'];
		$config['max_size']=$this->param['size'];
		$config['overwrite']=false;
		$config['encryp_name']=false;
        $config['remove_space']=true;
        if (($this->param['multi'] && $this->param['image_no']==0) || !$this->param['multi']){
            $this->ci->load->library('upload',$config);
		}else{
			$this->ci->upload->initialize($config, true);
        }

        if(! $this->ci->upload->do_upload($this->param['nm_file'])){
			$error = $this->ci->upload->display_errors();
            $this->ci->logdata->set_error('upload image gagal  message : '.$error );
            $this->_error='upload image gagal  message : '. $this->param['nm_file'] . $error ;

			return false;
		}else{
			$result= $this->ci->upload->data();
        }
        
        if ($this->param['thumb']){
			$this->create_thumb($result['file_name'], $this->param['path']);
		}
		if ($this->param['split_folder']){
			$result['file_name'] = $folder . "/". $result['file_name'];
        }
      
        $this->result=$result;
    }

    function create_thumb($file_name, $path) {
        $this->ci->load->config("configuration", true);
        $sizes = $this->ci->config->item("image_sizes", 'configuration');

        $this->ci->load->library('image_lib');
        
        // $pathinfo = pathinfo($file_name);
		$arrpic=explode('.',$file_name);
		$nmpic=$arrpic[count($arrpic)-2];
		$ext=$arrpic[count($arrpic)-1];
		
        // die($path. $nmpic.'_'.$width.'_'.$width.'.'.$ext);
        foreach($sizes as $row){
            $folder = '/thumb-' . $row[0].'x'.$row[1] ;
			if (!is_dir($this->param['path'].$folder)) {
                mkdir($this->param['path'].$folder, 0777, TRUE);
            }
           
            $img_cfg['image_library'] = 'gd2';
            $img_cfg['source_image'] = $path . '/'. $file_name;
            $img_cfg['maintain_ratio'] = TRUE;
            $img_cfg['create_thumb'] = false;
            $img_cfg['quality'] = 100;
            $img_cfg['new_image'] = $this->param['path'] . '/thumb-' . $row[0].'x'.$row[1] . '/' . $nmpic.'-'.$row[0].'x'.$row[1].'.'.$ext;
            $img_cfg['width'] = $row[0];
            $img_cfg['height'] = $row[1];
            $this->ci->image_lib->initialize($img_cfg);
            $this->ci->image_lib->resize();

            $this->ci->image_lib->clear();
        }
    }
    
    function error(){
        return $this->_error;
    }
    function result($info=''){
        if (empty($info)){
            dumps($this->result);
        }else{
            if (array_key_exists($info, $this->result))
                return $this->result[$info];
            else
                return 'error';
        }
    }
}

/* End of file Authentication.php */
/* Location: ./application/libraries/Authentication.php */