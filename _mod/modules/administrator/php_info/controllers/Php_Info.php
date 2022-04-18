<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Php_Info extends MX_Controller {
	public function __construct()
	{
        parent::__construct();
		
	}
	
	public function index()
	{		
		$this->template->build('php_info/view'); 
	}
	
	public function phpinfo(){
		phpinfo();
		exit;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */