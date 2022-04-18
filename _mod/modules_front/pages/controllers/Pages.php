<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends MY_Frontend {

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

	}

	function pagesx(){
		$data=[];
		$hasil=$this->load->view('view',$data, true);

		$this->default_display(['content'=>$hasil]);
	}

	function info(){
		$param = $this->uri->rsegment_array();
		$target = str_replace('_','-',strtolower($param[3]));
		$data['info']=$this->db->where('uri_title', $target)->get(_TBL_VIEW_NEWS)->row_array();
		if ($data['info']){
			$data['info'] = $this->data->_set_data($data['info'])->_set_params(['photo', 'param_meta', 'param_lang', 'img_cover'])->_row_array(true)->convert_data_param()->convert_data_lang(['title', 'news_short', 'news'])->_build();
			$content=$this->load->view('view',$data, true);

		}elseif(!empty($target)){
			$view = 'view-'.$target;
			$get_data ='data_'.$target;
			$data = $this->data->$get_data($target);
			if($this->load->is_view($view.'.php'))
			{
				$content = $this->load->view($view, $data, true);
			}else{
				$content = $this->load->view('view', $data, true);
			}
		}

		$this->default_display(['content'=>$content]);
	}

	function init($aksi=''){
		$configuration = [
			
		];
		return [
			'configuration'	=> $configuration
		];
	}
}