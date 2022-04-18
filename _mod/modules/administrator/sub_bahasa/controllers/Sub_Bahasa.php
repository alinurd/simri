<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sub_Bahasa extends MX_Controller {
	public $bahasa="english";
	public function __construct()
	{
        parent::__construct();
		$this->module=$this->uri->segment(2);
		$this->bahasa=$this->uri->segment(3);
		
		$js = link_tag(plugin_url("codemirror/lib/codemirror.css"));
		$js .= script_tag(plugin_url("codemirror/lib/codemirror.js"));
		$js .= script_tag(plugin_url("codemirror/mode/php/php.js"));
		$js .= script_tag(plugin_url("codemirror/mode/properties/properties.js"));
		$js .= link_tag(plugin_url('codemirror/theme/mbo.css'));
		
		$this->template->prepend_metadata($js);
		
		if (empty($this->bahasa))
			$this->bahasa=_BAHASA_;
		
		$this->second_sidebar=FALSE;
	}
	
	public function index()
	{	
		$path = $this->get_path_language();
		$string="";
		if (!empty($path))
			$string= file_get_contents($path);
		
		$data['cbo_modul']=$this->data->get_modul();
		$data['bahasa']=$this->bahasa;
		$data['cbo_bahasa']=$this->get_combo('bahasa');
		$data['nm_modul']=$this->module;
		$data['string'] = $string;
		$this->template->build('view', $data); 
	}
	
	public function save_data(){
		$data=$this->input->post();
		$this->bahasa=$data['cboBahasa'];
		$this->module=$data['cboModul'];
		$content = $data['code'];
		$path = $this->get_path_language();
		// Doi::dump($data);
		// Doi::dump($path);
		// die();
		if (!empty($path)){
			file_put_contents($path, $content);
		}
		header('location:' . base_url($this->modul_name . '/' . $this->module. '/' . $this->bahasa));
	}
	
	function get_path_language(){
		
		$ext = $this->config->item('controller_suffix').EXT;
		$string="";
		foreach (Modules::$locations as $location => $offset) {
			if (is_dir($source = $location.$this->module.'/language/' . $this->bahasa . '/')) {
				$directory = $this->module.'_lang'.$ext;
				if($directory AND is_file($source.$directory)) {
					$string= str_replace('/','\\',$source.$directory);
				}
			}
		}
		
		if (empty($string)){
			if (is_dir($source = APPPATH.'/language/' . $this->bahasa . '/')) {
				$directory = $this->module.'_lang'.$ext;
				if($directory AND is_file($source.$directory)) {
					$string= str_replace('/','\\',$source.$directory);
				}
			}
		}
		$string = str_replace('\\','/',$string);
		$string = str_replace('//','/',$string);
		return $string;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */