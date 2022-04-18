<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Support extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('file');
		$this->kd_sequence='TK';

	}

	function init($action='list'){

		$this->cboTipe=$this->crud->combo_value(['1'=>'Fungsional', '2'=>'Stuktural'])->result_combo();
		$this->cboPosisi=$this->crud->combo_select(['id', 'data'])->combo_where(['kelompok'=>'posisi','active'=>1])->combo_tbl(_TBL_COMBO)->combo_sort('urut')->get_combo()->result_combo();
		$this->cboPriority=$this->crud->combo_select(['id', 'data'])->combo_where(['kelompok'=>'prioriti','active'=>1])->combo_tbl(_TBL_COMBO)->combo_sort('urut')->get_combo()->result_combo();
		$this->cboSts=$this->crud->combo_select(['id', 'data'])->combo_where(['kelompok'=>'sts-tiket','active'=>1])->combo_tbl(_TBL_COMBO)->combo_sort('urut')->get_combo()->result_combo();
		$this->set_Tbl_Master(_TBL_SUPPORT);

		$this->set_Open_Tab(lang(_MODULE_NAME_REAL_.'_title'));

			$this->addField(['field'=>'id', 'show'=>false]);
			$this->addField(['field'=>'no_ticket', 'search'=>true, 'size'=>15, 'align'=>'center', 'readonly'=>true]);
			$this->addField(['field'=>'posisi_id', 'title'=>'Department', 'type'=>'int', 'input'=>'combo', 'search'=>true, 'values'=>$this->cboPosisi]);
			$this->addField(['field'=>'priority_id', 'title'=>'Priority', 'type'=>'int', 'input'=>'combo', 'search'=>true, 'values'=>$this->cboPriority]);
			$this->addField(['field'=>'title', 'required'=>true, 'search'=>true, 'size'=>100]);
			$this->addField(['field'=>'question', 'required'=>true, 'input'=>'multitext', 'size'=>500]);
			$this->addField(['field'=>'file', 'input'=>'upload', 'path'=>'file/support', 'file_thumb'=>false]);
			$this->addField(['field'=>'status_id', 'title'=>'Status', 'type'=>'int', 'value'=>'combo', 'search'=>true, 'values'=>$this->cboSts, 'default'=>9, 'show'=>false, 'save'=>true]);

		$this->set_Close_Tab();
		$this->set_Field_Primary(_TBL_SUPPORT, 'id');
		
		$this->set_Sort_Table(_TBL_SUPPORT,'create_date');

		$this->set_Table_List($this->tbl_master,'no_ticket');
		$this->set_Table_List($this->tbl_master,'create_date');
		$this->set_Table_List($this->tbl_master,'title');
		$this->set_Table_List($this->tbl_master,'posisi_id');
		$this->set_Table_List($this->tbl_master,'status_id',10);

		$this->set_Close_Setting();

		$configuration = [
			'tab_list'	=> true,
			'content_title'	=> '<i class="icon-comment-discussion"></i> Support Center',
			'tab_title'	=> 'List Ticket',
			'show_right_sidebar'	=> true,
		];
		return [
			'configuration'	=> $configuration
		];
	}

	function contentTab(){
		$content[]=['title'=>'FAQ - Frequently Asked Questions', 'content'=>'Isi FAQ'];
		$content[]=['title'=>'Knowledge Base', 'content'=>'Isi Knowledge Base'];

		return $content;
	}

	function insertValue_NO_TICKET($value, $data){
		$rows = $this->db->where('category_id',$this->kd_sequence)->where('tahun',date('Y'))->get(_TBL_SEQUENCE)->row();
		$last=1;
		$id=1;
		if ($rows){
			$last=intval($rows->last)+1;
			$id=$rows->id;
			$this->db->update(_TBL_SEQUENCE, ['last'=>$last], ['id'=>$id]);
		}else{
			$this->db->insert(_TBL_SEQUENCE, ['category_id'=>$this->kd_sequence, 'tahun'=>date('Y'), 'last'=>1]);
		}
		$last = str_pad($last, 4, '0', STR_PAD_LEFT);
		$kode = $this->kd_sequence.$last;

		return $kode;
	}

	function afterSave($id, $data, $data_old, $mode){
		
		if ($mode=='add'){
			$content_replace = [];
			$email='tri.untoro@gmail.com';
			$datasOutbox=[
				'recipient' => $email,
			];
			
			$this->load->library('outbox');
			
			$this->outbox->setTemplate('TMP01');
			
			$this->outbox->setParams($content_replace);
			
			$this->outbox->setDatas($datasOutbox);
			
			$this->outbox->send();
		}

		return true;
	}
}