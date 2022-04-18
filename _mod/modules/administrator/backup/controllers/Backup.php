<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Backup extends MX_Controller {
	var $tmp_data=array();
	var $data_fields=array();
	
	public function __construct()
	{
        parent::__construct();
		ini_set('max_execution_time', 0); 
		ini_set('memory_limit', '-1');
		$this->nm_file = 'Backup-'.date('dmYhis');
		
		$this->set_Tbl_Master(_TBL_BACKUP);
		
		$this->addField(array('field'=>'id', 'type'=>'int', 'show'=>false, 'size'=>4));
		$this->addField(array('field'=>'nama_file', 'size'=>100));
		$this->addField(array('field'=>'ip', 'size'=>100));
		$this->addField(array('field'=>'create_date', 'input'=>'date', 'type'=>'date'));
		$this->addField(array('field'=>'created_by', 'show'=>false));
		$this->set_Field_Primary('id');
		$this->set_Join_Table(array('pk'=>$this->tbl_master));
		
		$this->set_Table_List($this->tbl_master,'nama_file');
		$this->set_Table_List($this->tbl_master,'ip');
		$this->set_Table_List($this->tbl_master,'create_date');
		$this->set_Table_List($this->tbl_master,'created_by');
		
		$this->_SET_PRIVILEGE('add', false);
		$this->_SET_PRIVILEGE('cetak', false);
		$this->_SET_PRIVILEGE('delete', false);
		$this->set_Close_Setting();
		
	}
	
	function restoredb()
	{
		$rows = $this->db->order_by('create_date', 'desc')->limit(1)->get(_TBL_BACKUP)->row();
		$nmfile="";
		if ($rows){
			$nmfile=$rows->nama_file;
		}
		if (!empty($nmfile))
		{
			// die($nmfile);
			$isi_file = file_get_contents('./_themes/file/backup/'.$nmfile);
			$string_query = rtrim( $isi_file, "\n;" );
			$array_query = explode(";", $string_query);
			foreach($array_query as $query)
			{
				$this->db->query($query);
			}
		}
		header('location :'.base_url(_MODULE_NAME));
	}

	public function indexx()
	{	
		$data['title']=lang('msg_title');
		$this->template->build('backup', $data); 
	}
	
	function list_MANIPULATE_ACTION(){
		$tombol=array();
		$tombol['right'][]='&nbsp;&nbsp;<a class="btn btn-danger" href="'.base_url($this->modul_name.'/get_backup').'" data-toggle="popover" data-content="Backup Database" ><i class="fa fa-list"></i> Backup Database </a>&nbsp;&nbsp;';
		// $tombol['right'][]='&nbsp;&nbsp;<a class="btn btn-info pull-right" href="'.base_url($this->modul_name.'/restoredb').'" data-toggle="popover" data-content="Backup Database" ><i class="fa fa-list"></i> Restore Database </a>&nbsp;&nbsp;';
			
		return $tombol;
	}
	
	function get_backup(){
		$nm_file = 'Backup-'.date('dmYhis');
		$this->load->dbutil();
		
		$prefs = array(
				'ignore'        => array('qc_self_session', 'qc_debug', 'qc_log'), 		// List of tables to omit from the backup
				'format'        => 'zip',          // gzip, zip, txt
				'filename'      => $nm_file.'.sql', // File name - NEEDED ONLY WITH ZIP FILES
				'add_drop'      => TRUE,            // Whether to add DROP TABLE statements to backup file
				'add_insert'    => TRUE,            // Whether to add INSERT data to backup file
				'newline'       => "\n"             // Newline character used in backup file
		);

		$backup = $this->dbutil->backup($prefs);

		$this->load->helper('file');
		write_file('./_themes/file/backup/'.$nm_file.'.zip', $backup);

		
		$upd['nama_file'] = $nm_file.'.zip';
		$upd['ip'] = $_SERVER['SERVER_ADDR'];
		$upd['created_by'] = $this->authentication->get_Info_User('username');
		$result=$this->crud->crud_data(array('table'=>'backup', 'field'=>$upd,'type'=>'add'));
		
		$this->load->helper('download');
		force_download($nm_file.'.zip', $backup);
	}
	
	function list_MANIPULATE_PERSONAL_ACTION($tombol, $rows){
		$tombol['edit']=array();
		$tombol['delete']=array();
		$tombol['view']=array();
		$tombol['download']['url']=base_url('ajax/download/backup/'.$rows['l_nama_file']);
		$tombol['download']['default']=true;
		$tombol['download']['label']='Download';
		// $tombol['print']=array();
		return $tombol;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */