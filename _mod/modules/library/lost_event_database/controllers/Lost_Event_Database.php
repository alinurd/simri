<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Html;
use PhpOffice\PhpSpreadsheet\Shared\Date as Date;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Lost_Event_Database extends MY_Controller {
	var $table="";
	var $post=array();
	var $sts_cetak=false;
	public function __construct()
	{
		parent::__construct();
	}

	function init($action='list'){
		$this->cbo_owner = $this->get_combo_parent_dept();

		$this->period=$this->crud->combo_select(['id', 'data'])->combo_where('kelompok', 'period')->combo_where('active', 1)->combo_tbl(_TBL_COMBO)->get_combo()->result_combo();
		
		$this->risiko_dept=$this->crud->combo_select(['id', 'CONCAT(kode_dept,"-",kode_aktifitas,"-",LPAD(kode_risiko_dept,3,0),"  ",risiko_dept) as kode'])->combo_sort('kode')->combo_tbl(_TBL_VIEW_RCSA_DETAIL)->get_combo()->result_combo();

	 	$this->set_Tbl_Master(_TBL_LOSS_EVENT);
		$this->set_Table(_TBL_OWNER);

		$this->set_Open_Tab('Data Loss Risk Event Library');
			$this->addField(array('field'=>'id', 'type'=>'int', 'show'=>false, 'size'=>4));
			$this->addField(['field'=>'owner_code', 'title'=>'Kode Departemen','readonly'=>'readonly', 'input'=>'text']);

			$this->addField(['field'=>'owner_no', 'title'=>'Departemen', 'type'=>'int', 'required'=>true,'input'=>'combo', 'search'=>true, 'values'=>$this->cbo_owner]);
			$this->addField(array('field'=>'peristiwa', 'title'=>'Risiko Departemen','required'=>true,'input'=>'combo', 'values'=>$this->risiko_dept));
			$this->addField(array('field'=>'tempat_kejadian', 'title'=>'Sumber / Tempat Kejadian','input'=>'multitext', 'size'=>500));
            $this->addField(array('field'=>'tanggal', 'title'=>'Waktu Kejadian','input'=>'date', 'type'=>'date', 'size'=>100));
			

			$this->addField(array('field'=>'penyebab','title'=>'Penyebab Kejadian', 'input'=>'multitext','size'=>500));

			// $this->addField(array('field'=>'dampak', 'title'=>'Dampak Kejadian','input'=>'multitext', 'size'=>500));
			$this->addField(array('field'=>'durasi', 'title'=>'Durasi Kejadian','input'=>'text'));

			$this->addField(array('field'=>'dampak_kerugian', 'title'=>'Dampak Financial','input'=>'multitext', 'size'=>500));
			$this->addField(array('field'=>'dampak_non_uang', 'title'=>'Dampak Non Financial','input'=>'multitext', 'size'=>500));
			$this->addField(array('field'=>'tindakan',  'title'=>'Tindakan Perbaikan','input'=>'multitext', 'size'=>500));
			$this->addField(array('field'=>'keterangan','title'=>'Jenis Tindakan Perbaikan', 'input'=>'multitext', 'size'=>500));

			$this->addField(['field'=>'penanggung_jawab_no', 'title'=>'Pelaksana PIC', 'type'=>'string','input'=>'combo', 'search'=>true, 'values'=>$this->cbo_owner,'multiselect'=>TRUE]);

			$this->addField(['field'=>'koordinator_id', 'title'=>'Koordinator', 'type'=>'string','input'=>'combo', 'search'=>true, 'values'=>$this->cbo_owner,'multiselect'=>TRUE]);
			$this->addField(array('field'=>'due_date', 'title'=>'Due Date','input'=>'date', 'type'=>'date', 'size'=>100));
			$this->addField(array('field' => 'anggaran', 'type'=>'float', 'input'=>'float', 'required' => true,'prepend'=>'Rp.','size'=>50));

			$this->addField(['field'=>'period_id', 'title'=>'Periode', 'type'=>'int', 'required'=>true,'input'=>'combo', 'search'=>true, 'values'=>$this->period]);

			$this->addField(['field' => 'buktiup', 'title' => 'Lampiran', 'type' => 'free', 'search' => false, 'mode' => 'o']);
			
			$this->addField(['field'=>'active', 'input'=>'boolean', 'search'=>true]);
		$this->addField(['field' => 'bukti', 'show' => false, 'save' => false]);

			// $this->addField(['field'=>'term_id', 'title'=>'Term', 'type'=>'int', 'input'=>'combo', 'search'=>true, 'values'=>[]]);
			// $this->addField(['field'=>'minggu_id', 'title'=>'Minggu', 'type'=>'int', 'input'=>'combo', 'search'=>true, 'values'=>[]]);

		$this->set_Close_Tab();


		$this->set_Field_Primary($this->tbl_master, 'id');
		$this->set_Join_Table(array('pk'=>$this->tbl_master));

		$this->set_Sort_Table($this->tbl_master,'id');

		$this->set_Table_List($this->tbl_master,'owner_no');
		$this->set_Table_List($this->tbl_master,'peristiwa');
		$this->set_Table_List($this->tbl_master,'penyebab');
		$this->set_Table_List($this->tbl_master,'tanggal');
		$this->set_Table_List($this->tbl_master,'dampak_kerugian');
		$this->_set_Where_Owner(array('field'=>'owner_no'));

		$this->set_Close_Setting();

		$configuration = [
			'show_title_header' => false,
		];
		return [
			'configuration'	=> $configuration
		];
	}

	function inputBox_BUKTIUP($mode, $field, $rows, $value)
	{
		$content = '<br>'.form_upload('importer[]', '');
		$content .= "<br><b>Max size file 10MB, file yang dibolehkan jpeg | png | gif | xlsx | csv | docx | pdf | zip | rar </b><br>
		";
		$url = '#';
		$nmfile = 'Belum ada File';

		if (isset($rows['bukti'])) {
			if ($rows['bukti'] == '') {
				$url = '#';
				$nmfile = 'Belum ada File';
			} else {
				$url = base_url() . substr($rows['bukti'], 1);
				$nmfile = substr($rows['bukti'], 13);
			}
		}
		
		if($nmfile != "Belum ada File"){
			$content .= '<a href="'.$url.'" class="btn bg-success btn-labeled btn-labeled-left button-action" target="_blank">Download</a><br><br>';
		}else{
			$content .= 'Belum ada File';
		}
		
		return $content;
	}

	function listBox_tanggal($field, $rows, $value){
		$id=$rows['id'];
		$a = $rows['tanggal'];
		$b = date('d-m-Y',strtotime($a));
		return $b;
	}

	function upload_form()
	{
		$data['combo'] = $this->load->view('upload', [], true);
		header('Content-type: application/json');
		echo json_encode($data);
	}

	function import(){
		ini_set('MAX_EXECUTION_TIME', -1);
		$post = $this->input->post();
		$file = $_FILES;
		
		$fileName = time().$file['import']['name'];
        $upload=upload_image_new(array('type'=>'xls|xlsx|csv','nm_file'=>'import','path'=>'upload','thumb'=>false));

		if($upload){
			$inputFileName = file_path_relative($upload['file_name']);
			$spreadsheet = new Spreadsheet();
			//$objReader =PHPExcel_IOFactory::createReader('Excel5');     //For excel 2003 
			$objReader= IOFactory::createReader('Xlsx');	// For excel 2007 	  
			$objReader->setReadDataOnly(true); 		  
			$objPHPExcel=$objReader->load($inputFileName);		 
			$totalrows=$objPHPExcel->setActiveSheetIndex(0)->getHighestRow();   //Count Numbe of rows avalable in excel      	 
			$objWorksheet=$objPHPExcel->setActiveSheetIndex(0);                
			//loop from first data untill last data
			
			$data=array();
			for($i=4;$i<=$totalrows;$i++)
			{
				$upd=array();
				if ($objWorksheet->getCellByColumnAndRow(2,$i)->getValue()!= null) {
					$owner = $objWorksheet->getCellByColumnAndRow(2,$i)->getValue();
					$dtowner = $this->get_owner_by_code($owner);
					$upd['owner_no'] = $dtowner['owner_no'];
					$upd['owner_code'] = $dtowner['owner_code'];
					$upd['tempat_kejadian'] = $objWorksheet->getCellByColumnAndRow(3,$i)->getValue();
					$date = $objWorksheet->getCellByColumnAndRow(4,$i)->getValue();
					$tgl = Date::excelToDateTimeObject($date)->format('Y-m-d H:i:s');
					$upd['tanggal'] = $tgl;

					$upd['penyebab'] = $objWorksheet->getCellByColumnAndRow(5,$i)->getValue();
					$upd['dampak'] = $objWorksheet->getCellByColumnAndRow(6,$i)->getValue();
					$upd['dampak_kerugian'] = $objWorksheet->getCellByColumnAndRow(7,$i)->getValue();
					$upd['dampak_non_uang'] = $objWorksheet->getCellByColumnAndRow(8,$i)->getValue();
					$upd['tindakan'] = $objWorksheet->getCellByColumnAndRow(9,$i)->getValue();
					$upd['keterangan'] = $objWorksheet->getCellByColumnAndRow(10,$i)->getValue();

					$duedate = $objWorksheet->getCellByColumnAndRow(11,$i)->getValue();
					$duetgl = Date::excelToDateTimeObject($duedate);
					$upd['due_date'] = $duetgl->format('Y-m-d H:i:s');					;
					$upd['anggaran'] = $objWorksheet->getCellByColumnAndRow(12,$i)->getValue();
					$period = $objWorksheet->getCellByColumnAndRow(13,$i)->getValue();
					$dtperiod = $this->get_periode($period);
					$upd['period_id'] = $dtperiod['period_id'];

					$data[]=$upd;
				}
			}

			
			$this->db->insert_batch(_TBL_LOSS_EVENT, $data);
			unlink($inputFileName); //File Deleted After uploading in database .		
			$this->session->set_flashdata('message_crud', 'Data berhasil diimport');
			header('location:'.base_url(_MODULE_NAME_));	 
		}else{
			$this->session->set_flashdata('message_crud', 'Data gagal diimport, periksa format atau data yang diimport');
			header('location:'.base_url(_MODULE_NAME_));
		}
		// die();
		
	}

	function optionalButton($button, $mode){
		if ($mode=='list'){

			$button['print']=[
				'label'=> 'Export & Import',
				'color'=>'bg-green',
				'round'=>($this->configuration['round_button'])?'rounded-round':'',
				'icon' =>'icon-file-excel ',
				'align'=>'left'
			];

			$button['print']['detail']['excel']=[
				'label'=>$this->lang->line('btn_export_excel'),
				'color'=>'bg-green',
				'id'=>'btn_export_excel',
				'tag'=>'a',
				'round'=>($this->configuration['round_button'])?'rounded-round':'',
				'icon' =>'icon-file-excel ',
				'url' => base_url(_MODULE_NAME_.'/export/excel'),
				'align'=>'left'
			];

			$button['print']['detail']['pdf']=[
				'label'=>$this->lang->line('btn_export_pdf'),
				'color'=>'bg-green',
				'id'=>'btn_export_pdf',
				'tag'=>'a',
				'round'=>($this->configuration['round_button'])?'rounded-round':'',
				'icon' =>'icon-file-pdf',
				'url' => base_url(_MODULE_NAME_.'/export/pdf'),
				'align'=>'left'
			];
			$button['print']['detail']['import']=[
				'label'=>'Import',
				'color'=>'bg-green',
				'id'=>'btn_import',
				'tag'=>'a',
				'round'=>($this->configuration['round_button'])?'rounded-round':'',
				'icon' =>'icon-import',
				'url' => '#',
				'attr'=> ' target="" ',
				'align'=>'left'
			];

			$button['print']['detail']['import_template']=[
				'label'=>'Download Template Import',
				'color'=>'bg-green',
				'id'=>'btn_import_template',
				'tag'=>'a',
				'round'=>($this->configuration['round_button'])?'rounded-round':'',
				'icon' =>'icon-download',
				'url' => base_url('files/Template_import.xlsx'),
				'align'=>'left'
			];
		}

		return $button;
	}

	function get_owner_by_code($kode)
	{
		$this->db->select('id, owner_code');
		$this->db->where('active', 1);
		$this->db->where('owner_code like', '%'.$kode.'%');
		$this->db->limit(1);
		$data = $this->db->get(_TBL_OWNER);
		$bykode = $data->row();
		
		if ($bykode==null) {
			$this->db->select('id, owner_code');
			$data = $this->db->get_where(_TBL_OWNER, array('owner_name like'=> '%'.$kode.'%'), 1);
			$bykode = $data->row();
		}

		$result['owner_no'] = ($bykode==null)?0:$bykode->id;
		$result['owner_code'] = ($bykode==null)?0:$bykode->owner_code;

		return $result;
	}

	function get_periode($period)
	{
		$this->db->select('id');
		$data = $this->db->get_where(_TBL_COMBO, array('data like'=>'%'.$period.'%', 'kelompok'=>'period'), 1);
		$bykode = $data->row();

		$result['period_id'] = ($bykode==null)?0:$bykode->id;

		return $result;
	}

	function afterSave($id, $new_data, $old_data, $mode)
	{
		
		$result = true;

		$tanggal = date('Y-m-d', strtotime($new_data['tanggal_submit']));
		$duedate = date('Y-m-d', strtotime($new_data['due_date_submit']));
		

		ini_set('MAX_EXECUTION_TIME', -1);
		
		$this->db->where('id', $id);
		$datax = $this->db->get(_TBL_LOSS_EVENT)->row_array();

		
		$count = count($_FILES['importer']['name']);
		if ($datax) {
			$data = $datax['bukti'] ;
		}

		
		for ($i = 0; $i < $count; $i++) {

			if (!empty($_FILES['importer']['name'][$i])) {
				$_FILES['file']['name'] = $_FILES['importer']['name'][$i];
				$_FILES['file']['type'] = $_FILES['importer']['type'][$i];
				$_FILES['file']['tmp_name'] = $_FILES['importer']['tmp_name'][$i];
				$_FILES['file']['error'] = $_FILES['importer']['error'][$i];
				$_FILES['file']['size'] = $_FILES['importer']['size'][$i];

				$upload = upload_image_new(array('type' => 'jpeg|jpg|png|gif|xls|xlsx|csv|doc|docx|pdf|zip|rar', 'nm_file' => 'file', 'path' => 'loss', 'thumb' => false));
				
				if ($upload) {
					if (isset($upload['file_name'])) {
						$inputFileName = file_path_relative('loss/' . $upload['file_name']);
						$data = $inputFileName;
					}else{
						$data = '';
					}
					
				} else{
					$data = '';
				}
			} else{
				$data = '';
			}
		}
		
		$this->crud->crud_table(_TBL_LOSS_EVENT);
		$this->crud->crud_type('edit');
		$this->crud->crud_where(['field' => 'id', 'value' => $id]);

		$this->crud->crud_field('tanggal', $tanggal);
		$this->crud->crud_field('bukti', $data);
		$this->crud->crud_field('due_date', $duedate);

		$this->crud->process_crud();

		//$this->logdata->set_error("Gagal memproses data karena ");
		return $result;
	}
}