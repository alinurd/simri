<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Export_Import extends MX_Controller {
	var $tmp_data=array();
	var $data_fields=array();
	
	public function __construct()
	{
        parent::__construct();
	}
	
	public function index()
	{	
		$post = $this->input->post();
		$file = $_FILES;
		if (!$post){
			$this->load->view('export', array()); 
		}else{
			$this->upload_data($post, $file);
		}
	}
	
	function upload_data($data, $file){
		ini_set('MAX_EXECUTION_TIME', -1);
		$this->load->library('PHPExcel');
		
		$fileName = time().$file['export']['name'];
        $upload=upload_image_new(array('type'=>'xls|xlsx|csv','nm_file'=>'export','path'=>'upload','thumb'=>false));
        
		if($upload){
			$inputFileName = upload_path_relative($upload['file_name']);
			 
			//$objReader =PHPExcel_IOFactory::createReader('Excel5');     //For excel 2003 
			$objReader= PHPExcel_IOFactory::createReader('Excel2007');	// For excel 2007 	  
			$objReader->setReadDataOnly(true); 		  
			$objPHPExcel=$objReader->load($inputFileName);		 
			$totalrows=$objPHPExcel->setActiveSheetIndex(0)->getHighestRow();   //Count Numbe of rows avalable in excel      	 
			$objWorksheet=$objPHPExcel->setActiveSheetIndex(0);                
			//loop from first data untill last data
			$user= $this->authentication->get_Info_User('username');	
			$now = new DateTime();			
			$tgl_skr= $now->format('Y-m-d H:i:s');
			$data=array();
			for($i=2;$i<=$totalrows;$i++)
			{
				$upd=array();
				if ($i==2){
					$cabang_no=$objWorksheet->getCellByColumnAndRow(1,$i)->getValue();
					$rows = $this->db->where('id', $cabang_no)->get(_TBL_CABANG)->row();
					$kode = $rows->kode;
					$no_peserta= intval($rows->no_last);
				}
				// $upd['no']= $objWorksheet->getCellByColumnAndRow(0,$i)->getValue();			
				$upd['cabang_no']= $cabang_no;
				$upd['angkatan_no']= $objWorksheet->getCellByColumnAndRow(2,$i)->getValue();
				$upd['no_peserta']=++$no_peserta;
				$upd['nis']=$kode.'/'.romanic_number($objWorksheet->getCellByColumnAndRow(2,$i)->getValue()).'/'.$no_peserta;
				$upd['nama_peserta']=$objWorksheet->getCellByColumnAndRow(3,$i)->getValue();
				$upd['nama_orang_tua']="";
				$upd['tempat_lahir']=$objWorksheet->getCellByColumnAndRow(4,$i)->getValue();
				$tgl =  $objWorksheet->getCellByColumnAndRow(5,$i)->getValue();
				if (!empty($tgl)){
					$tgl =  PHPExcel_Shared_Date::ExcelToPHP($tgl);
					$tgl =  date('Y-m-d', $tgl);
				}
				$upd['tanggal_lahir']=$tgl;
				$upd['kelamin']=$objWorksheet->getCellByColumnAndRow(6,$i)->getValue();
				$upd['alamat']=$objWorksheet->getCellByColumnAndRow(7,$i)->getValue();
				$upd['telp']=$objWorksheet->getCellByColumnAndRow(8,$i)->getValue();
				$upd['hp']=$objWorksheet->getCellByColumnAndRow(9,$i)->getValue();
				$upd['created_by']=$user;
				$upd['update_user']=$user;
				$upd['updated_at']=$tgl_skr; 
				$upd['aktif']=1;
				$upd['kelurahan_no']=0;
				
				$data[]=$upd;
			}
			$this->db->insert_batch(_TBL_PESERTA, $data);
			$this->db->update(_TBL_CABANG, array('no_last'=>$no_peserta),array('id'=>$cabang_no));
			unlink($inputFileName); //File Deleted After uploading in database .			 
		}
		header('location:'.base_url('export-import'));
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */