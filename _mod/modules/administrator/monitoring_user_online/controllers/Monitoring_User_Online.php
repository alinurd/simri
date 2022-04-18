<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Monitoring_User_Online extends MX_Controller {
	 //put your code here
    public function __construct() {
        parent::__construct();
       
    }
    
    public function index() {
		$this->template->build('monitoring'); 
    }
    
    public function get_data_monitoring() {
        $result = $this->data->get_data_monitoring();
        return $result;
    }
    
    public function logout_user() {
        $result = $this->data->logout_user();
        return $result;
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */