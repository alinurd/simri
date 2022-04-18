<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Log_Api extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
	}

	function init($action='list'){
		
		$this->cboType=$this->crud->combo_value([0=>'error', 1=>'sql', 2=>'like', 3=>'unlike', 4=>'download', 5=>'add', 7=>'delete', 6=>'edit'])->result_combo();
		$this->cboCode=$this->crud->combo_select(['code', 'concat(code,\'-\',message) as pesan'])->combo_tbl(_TBL_API_ERR_CODE)->get_combo()->result_combo();
		
		$this->set_Tbl_Master(_TBL_VIEW_API_LOGS);
		$this->set_Open_Tab('Data Log');
			$this->addField(['field'=>'id', 'show'=>false]);
			$this->addField(['field'=>'real_name', 'size'=>100]);
			$this->addField(['field'=>'username', 'size'=>100]);
			$this->addField(['field'=>'uri', 'size'=>100]);
			$this->addField(['field'=>'method', 'size'=>100]);
			$this->addField(['field'=>'params', 'input'=>"multitext", 'size'=>1000]);
			$this->addField(['field'=>'api_key', 'type'=>'int', 'size'=>100]);
			$this->addField(['field'=>'ip_address', 'size'=>100]);
			$this->addField(['field'=>'time', 'type'=>'string', 'size'=>100]);
			$this->addField(['field'=>'rtime', 'type'=>'string', 'size'=>100]);
			$this->addField(['field'=>'authorized', 'size'=>100]);
			$this->addField(['field'=>'response_code', 'title'=>'response_code', 'type'=>'int','input'=>'combo', 'search'=>true, 'values'=>$this->cboCode]);
		$this->set_Close_Tab();
		$this->set_Field_Primary($this->tbl_master, 'id', false);
		$this->set_Join_Table(['pk'=>$this->tbl_master]);
		$this->set_Sort_Table($this->tbl_master,'id', 'desc');

		$this->set_Table_List($this->tbl_master,'uri', '', 0, 'center');
		$this->set_Table_List($this->tbl_master,'real_name');
		$this->set_Table_List($this->tbl_master,'method');
		// $this->set_Table_List($this->tbl_master,'api_key');
		$this->set_Table_List($this->tbl_master,'ip_address');
		$this->set_Table_List($this->tbl_master,'time');
		$this->set_Table_List($this->tbl_master,'authorized', '', 0, 'center');
		$this->set_Table_List($this->tbl_master,'response_code', '', 0, 'center');

		$this->set_Close_Setting();

		$configuration = [
			'show_title_header' => false,
		];
		return [
			'configuration'	=> $configuration
		];

		$this->setPrivilege('update', false);
	}

	function listBox_TIME($field, $rows, $value){
		$o='';
		if (!empty($value)){
			$o=time_ago(date('d M Y H:i:s', $value));
		}
		return $o;
	}

	function listBox_IP($fields, $rows, $value){
		if ($value=='::1')
			$value='localhost';

		return $value;
	}

	function inputBox_TIME($mode, $field, $row, $value){
		$o='';
		if (!empty($value)){
			$o=time_ago(date('d M Y H:i:s', $value));
		}
		$o.=' ['.date('d M Y H:i:s', $value).']';
		$o = $this->set_box_input($field, $o);
		return $o;
	}
		function inputBox_PARAMS($mode, $field, $row, $value){
		$value=unserialize($value);
		$key=array_keys($value);
		$o='<table class="table">';
		foreach($key as $x){
			$o.='<tr><td width="20%">'.$x.'</td><td>'.$value[$x].'</td></tr>';
		}
		$o.='</table>';
		return $o;
	}

	function listBox_MESSAGE($fields, $rows, $value){
		$value=str_replace('"', '',$value);
		$value=str_replace('"', '',$value);
		$content = "<i class='icon-newspaper pointer detail-notif'></i>";
		$content = "<span data-container='body' data-toggle='popover' data-placement='top' data-content='".$value."'> ".$content."</span>";

		return $content;
	}
}