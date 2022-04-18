<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class SwichParam
{
	protected $_params=[];
	protected $_rows=[];
	protected $_mode=false;
	public function __construct()
	{
		
	}

	function _set_data($datas=[]){
		$this->_rows=$datas;
		return $this;
	}

	function _set_params($params){
		$this->_params=$params;
		return $this;
	}

	function _row_array($mode=true){
		$this->_mode=$mode;
		return $this;
	}

	function _build(){
		$hasil=$this->_rows;
		$this->_clear_data();
		return $hasil;
	}

	function _clear_data(){
		$this->_params=[];
		$this->_rows=[];
		$this->_mode=false;
	}

	function convert_data_param(){
		if (!$this->_mode){
			foreach($this->_rows as &$row){
				foreach($this->_params as $param){
					if (array_key_exists($param, $row)){
						$x = json_decode($row[$param], true);
						$row[$param]=$x;
					}
				}
			}
			unset($row);
		}else{
			foreach($this->_params as $param){
				$x=[];
				if (array_key_exists($param, $this->_rows)){
					if (!is_array($this->_rows[$param]))
						$x = json_decode($this->_rows[$param], true);
				}
				$this->_rows[$param]=$x;
			}
		}
        return $this;
	}
	function convert_data_lang($params=['title']){
		$lang=$this->session->userdata('site_lang');
		// dumps($this->_rows);die();
		if ($this->_params){
			if (!$this->_mode){
				foreach($this->_rows as &$row){
					if(array_key_exists('param_lang', $row)){
						if(!empty($row['param_lang'])){
								
							$datas=[];
							if (array_key_exists($lang, $row['param_lang'])){
								$datas=$row['param_lang'][$lang];
							}
							foreach($params as $param){
								if (array_key_exists($param, $datas)){
									if(!empty($datas[$param]))
										$row[$param]=$datas[$param];
								}
							}
						}
					}
				}
				unset($row);
			}else{
				foreach($params as $key=>$param){
					$datas=[];
					if (array_key_exists($lang, $this->_rows['param_lang'])){
						$datas=$this->_rows['param_lang'][$lang];
					}
					if (array_key_exists($param, $datas)){
						if(!empty($datas[$param]))
							$this->_rows[$param]=$datas[$param];
					
					}
				}
			}

		}

        return $this;
    }
}
/* End of file app_login_model.php */
