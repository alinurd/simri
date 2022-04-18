<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/** load the CI class for Modular Extensions **/
require dirname(__FILE__).'/Base.php';

/**
 * Modular Extensions - HMVC
 *
 * Adapted from the CodeIgniter Core Classes
 * @link	http://codeigniter.com
 *
 * Description:
 * This library replaces the CodeIgniter Controller class
 * and adds features allowing use of modules and the HMVC design pattern.
 *
 * Install this file as application/third_party/MX/Controller.php
 *
 * @copyright	Copyright (c) 2015 Wiredesignz
 * @version 	5.5
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 **/
class MX_Controller
{
	public $_Snippets_=array();
	public $_param_list_=array();
	public $modul_name='';
	public $_mode_='list';
	public $_preference_=[];
	public $_is_data_exist=false;
	protected $mode_action="";
	public $autoload = array();
	public function __construct()
	{
		$class = str_replace(CI::$APP->config->item('controller_suffix'), '', get_class($this));
		log_message('debug', $class." MX_Controller Initialized");
		Modules::$registry[strtolower($class)] = $this;

		/* copy a loader instance and initialize */
		$this->load = clone load_class('Loader');
		$this->load->initialize($this);

		/* autoload module items */
		$this->load->_autoloader($this->autoload);

		$this->_param_list_['breadcrumb']=explode("/",$this->uri->uri_string);
		$this->modul_name = str_replace('_','-',$this->router->fetch_module());
		$this->mode_action = $this->router->fetch_method();
		define('_MODULE_NAME_', $this->modul_name);
		define('_MODULE_NAME_REAL_', $this->router->fetch_module());
		$this->_mode_=strtolower($this->uri->segment(2,'list'));
		define('_MODE_', $this->_mode_);
		
		$x = $this->session->userdata('periode');
		if($x){
			define('_TAHUN_', $x['period']);
			define('_TAHUN_ID_', $x['period_id']);
			define('_TERM_', $x['term']);
			define('_TERM_ID_', $x['term_id']);
		}
		$x = $this->session->userdata('minggu');
		if($x){
			define('_MINGGU_', $x['minggu']);
			define('_MINGGU_ID_', $x['minggu_id']);
		}

		$this->set_Define_Table();
		if ($this->session->userdata('site_lang')=='')
			$bahasa=$this->config->item('language');
		else
			$bahasa=$this->session->userdata('site_lang');
		
		foreach (Modules::$locations as $location => $offset) {
			if (file_exists($location.$this->router->fetch_module().'/config/var.php'))
			{
				$this->config->load('var');
				break;
			}
		}

		define('_BAHASA_', $bahasa);
		$arr_bahasa=array('datatable', 'share', $this->router->fetch_module());
		foreach($arr_bahasa as $bhs){
			if (file_exists(APPPATH.'/language/'.$bahasa.'/'. $bhs .'_lang.php'))
			{
				$this->lang->load($bhs,$bahasa);
			}
		}

		if (is_model_exist('data')){
			$this->_is_data_exist=true;
			$this->load->model('data');
		}

		$prefs = $this->db->get('preference')->result_array();
		foreach($prefs as $key=>$pref){
			$this->_preference_[$pref['uri_title']]=$pref['value'];
		}
	}

	public function __get($class)
	{
		return CI::$APP->$class;
	}

	public function set_Define_Table(){
		$prefix=$this->db->dbprefix;
		$table = $this->db->list_tables();
		foreach ($table as $tbl){
			$nama=strtoupper(str_replace($prefix, '_tbl_', $tbl));
			define($nama, $tbl);
		}
	}
}