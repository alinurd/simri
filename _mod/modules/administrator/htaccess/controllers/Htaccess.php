<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Htaccess extends MX_Controller {
	public function __construct()
	{
        parent::__construct();
		
		$js = link_tag(plugin_url("codemirror/lib/codemirror.css"));
		$js .= script_tag(plugin_url("codemirror/lib/codemirror.js"));
		$js .= script_tag(plugin_url("codemirror/mode/php/php.js"));
		$js .= script_tag(plugin_url("codemirror/mode/properties/properties.js"));
		$js .= link_tag(plugin_url('codemirror/theme/mbo.css'));
		
		$this->template->prepend_metadata($js);
	}
	
	public function index()
	{		
		$this->template->build('view'); 
	}
	
	public function save_data(){
		$content = $this->input->post('code');
        file_put_contents(FCPATH . '.htaccess', $content);
		header('location:' . base_url('htaccess'));
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */