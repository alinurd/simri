<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Sms
{	
	private $_ci;
	private $preference=array();
	private $arrHp=array();
	
	function __construct()
	{
		$this->_ci =& get_instance();
		if ($x=$this->_ci->session->userdata('preference')){
			$this->preference=$this->_ci->session->userdata('preference');
		}
		
	}

	function initialize($config = array())
	{
		
	}
	
	function sendSms()
	{	
		$results=array();
		$ci =& get_instance();
		$hasil=array();
		$userkey = $ci->authentication->get_Preference('sms_user_key');
		$passkey = $ci->authentication->get_Preference('sms_api_key');
		$url = $ci->authentication->get_Preference('sms_api_url').'sendsms/';
		foreach($this->arrHp as $row){
			$config = array(
				'userkey' => $userkey,
				'passkey' => $passkey,
				'nohp' => $row['noHp'],
				'pesan' => $row['pesan']
			);
			$curlHandle = curl_init();
			curl_setopt($curlHandle, CURLOPT_URL, $url);
			curl_setopt($curlHandle, CURLOPT_HEADER, 0);
			curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);
			curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curlHandle, CURLOPT_TIMEOUT,30);
			curl_setopt($curlHandle, CURLOPT_POST, 1);
			curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $config);
			$results = json_decode(curl_exec($curlHandle), true);
			curl_close($curlHandle);
			$hasil[] =$result;
		}
		
		return $hasil;
	}

	function balance()
	{	
		$results=array();
		$ci =& get_instance();
		
		if ($ci->authentication->is_loggedin()){
			$userkey = $ci->authentication->get_Preference('sms_user_key');
			$passkey = $ci->authentication->get_Preference('sms_api_key');
			$url = $ci->authentication->get_Preference('sms_api_url').'balance/?userkey='.$userkey.'&passkey='.$passkey;
			$curlHandle = curl_init();
			curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curlHandle, CURLOPT_URL,$url);
			$result=curl_exec($curlHandle);
			curl_close($curlHandle);
			$results=json_decode($result, true);
		}
		return $results;
	}

	function readSms($tgl='')
	{	
		$results=array();
		$ci =& get_instance();
		
		$userkey = $ci->authentication->get_Preference('sms_user_key');
		$passkey = $ci->authentication->get_Preference('sms_api_key');
		if (empty($tgl)){
			$tgl=date('Y-m-d');
		}
		$url = $ci->authentication->get_Preference('sms_api_url').'readsms/?date='.$tgl;
		$curlHandle = curl_init();
		curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curlHandle, CURLOPT_URL,$url);
		$result=curl_exec($curlHandle);
		curl_close($curlHandle);
		$results=json_decode($result, true);
		return $results;
	}

	function getInbox($data=array())
	{	
		$results=array();
		$ci =& get_instance();
		
		if ($ci->authentication->is_loggedin()){
			$userkey = $ci->authentication->get_Preference('sms_user_key');
			$passkey = $ci->authentication->get_Preference('sms_api_key');
			$url = $ci->authentication->get_Preference('sms_api_url').'getinbox/?userkey='.$userkey.'&passkey='.$passkey.'&start_date='.$data['mulai'].'&end_date='.$data['selesai'];
			$curlHandle = curl_init();
			curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curlHandle, CURLOPT_URL,$url);
			$result=curl_exec($curlHandle);
			curl_close($curlHandle);
			$results=json_decode($result, true);
		}
		return $results;
	}

	function noHp($data){
		$this->arrHp[]=['noHp'=>$data['hp'], 'pesan'=>$data['pesan']];
	}
	
	function prosesSmsMasuk(){
		$rows = $this->_ci->db->where('aktif', 1)->get(_TBL_CALON)->result_array();
		$tahun=array();
		foreach($rows as $row){
			$kode = trim(strtolower($row['kode_sms']));
			$tahun[$kode] = $row;
		}
		$sts=array();
		$rows = $this->_ci->db->where('Processed', 'false')->get(_TBL_INBOX)->result_array();
		foreach($rows as $row){
			$pesan = $row['TextDecoded'];
			$split = explode('#',$pesan);
			// Doi::dump($split);
			$upd = array();
			$kode= trim(strtolower($split[0]));
			$jmlKirim = count($split)-1;
			if (array_key_exists($kode, $tahun)){
				$calon = $this->_ci->db->where('pemilu_no', $tahun[$kode]['id'])->order_by('no_urut')->get(_TBL_CALON_DETAIL)->result_array();
				$jmlHarus  = count($calon)+2;
				if ($jmlKirim==$jmlHarus){
					$sts = $this->cekTps($row['SenderNumber'], $tahun[$kode], $split);
					if($sts['sts_tps']){
						$noreff=array();
						$ttlCalon=0;
						foreach($calon as $key=>$cal){
							$no=$key+1;
							if (array_key_exists($cal['id'], $sts['detail'])){
								$id = $this->_ci->crud->crud_data(array('table'=>_TBL_RQCCALON, 'field'=>array('jumlah'=>$split[$no], 'send_no'=>1), 'where'=>array('id'=>$sts['detail'][$cal['id']]['id']),'type'=>'update'));
							}else{
								$id = $this->_ci->crud->crud_data(array('table'=>_TBL_RQCCALON, 'field'=>array('rqc_no'=>$sts['rqc_no'], 'rqcdetail_no'=>$sts['rqc_detail_no'], 'calon_no'=>$cal['id'], 'jumlah'=>$split[$no], 'send_no'=>1),'type'=>'add'));
							}
							$noreff[]=$id; 
							$ttlCalon+=floatval($split[$no]);
						}
						$pesan  = 'Data TPS '.$sts['tps']['no_tps'].' di '.$sts['tps']['kecamatan'].' '.$sts['tps']['kelurahan'].' telah di proses dan tersimpan di database dengan Noreff: '.implode('-',$noreff);
					}else{
						$pesan  = 'anda belum terdaftar pada TPS manapun';
					}
				}else{
					$pesan = "Format yang anda kirim salah!!";
				}
			}else{
				$pesan = "Format yang anda kirim salah";
			}
			
			$hp = $row['SenderNumber'];
			$pesan = $pesan;
			$this->noHp(['hp'=>$hp, 'pesan'=>$pesan]);
			$this->sendSms();
			
			$upd=array();
			$upd['DestinationNumber'] = $hp; 
			$upd['InsertIntoDB'] = date('Y-m-d h:i:s',strtotime(Doi::now())); 
			$upd['TextDecoded'] = $pesan; 
			$upd['SenderID'] = 1; 
			$id = $this->_ci->crud->crud_data(array('table'=>_TBL_SENTITEMS, 'field'=>$upd, 'type'=>'add'));
			
			$this->_ci->crud->crud_data(array('table'=>_TBL_INBOX, 'field'=>array('Processed'=>'true'), 'where'=>array('ID'=>$row['ID']),'type'=>'update'));
			
			if ($sts){
				$ttlCalon+=floatval($split[count($split)-2]);
				if (intval($sts['kelurahan_no'])>0){
					$calon = $this->_ci->db->select('kelurahan_no, calon_no, sum(jumlah) as jml')->where('kelurahan_no',$sts['kelurahan_no'])->group_by(['kelurahan_no', 'calon_no'])->order_by('no_urut')->get(_TBL_VIEW_RQCCALON)->result_array();
					
					$arrC=array();
					foreach($calon as $row){
						$arrC['calon_'.$row['calon_no']] =$row['jml'];
					}
					
					$calon = $this->_ci->db->select('kelurahan_no, sum(jml_tdk_sah) as tdk, sum(jml_saksi) as saksi, sum(jml_total) as ttl')->where('kelurahan_no',$sts['kelurahan_no'])->group_by(['kelurahan_no'])->order_by('kelurahan_no')->get(_TBL_VIEW_RQC)->row_array();
					
					if ($calon){
						$arrC['tdk'] = $calon['tdk'];
						$arrC['saksi'] = $calon['saksi'];
						$arrC['total'] = floatval($calon['ttl'])+floatval($ttlCalon);
					}else{
						$arrC['tdk'] = 0;
						$arrC['saksi'] = 0;
						$arrC['total'] = 0;
					}
					$upd=json_encode($arrC);
					$this->_ci->crud->crud_data(array('table'=>_TBL_RQC, 'field'=>array('rekap'=>$upd),'where'=>array('id'=>$sts['rqc_no']),'type'=>'update'));
				}
				
				$nilSaksi =$split[count($split)-1];
				if ($ttlCalon==$nilSaksi){
					$status_no=72;
				}elseif ($ttlCalon!=$nilSaksi){
					$status_no=74;
				}else{
					$status_no=73;
				}
				$this->_ci->crud->crud_data(array('table'=>_TBL_RQCDETAIL, 'field'=>array('status_no'=>$status_no, 'jml_total'=>$ttlCalon),'where'=>array('id'=>$sts['rqc_detail_no']),'type'=>'update'));
			}
		}
	}
	
	function cekTps($hp, $tahun, $split){
		$hasil['kelurahan_no']=0;
		$hasil['rqc_no']=0;
		$hasil['sts_tps']=false;
		$hasil['rqc_detail_no']=0;
		$hp = str_replace('+62', '', $hp);
		$hp = '0'.$hp;
		$tps =  $this->_ci->db->where('hp1', $hp)->or_where('hp2', $hp)->get(_TBL_VIEW_SAKSI)->row_array();
		$tps_no=0;
		$hasil['tps'] = $tps;
		if ($tps){
			$hasil['sts_tps']=true;
			$tps_no = $tps['tps_no'];
			$rqc = $this->_ci->db->where('tahun_no', $tahun['id'])->where('tps_no', $tps_no)->get(_TBL_VIEW_RQC)->row();
			if ($rqc){
				$hasil['rqc_no'] = $rqc->rqc_no;
				$hasil['kelurahan_no'] = $rqc->kelurahan_no;
				$hasil['rqc_detail_no'] = $rqc->id;
				$id=$this->_ci->crud->crud_data(array('table'=>_TBL_RQCDETAIL, 'field'=>array('jml_tdk_sah'=>$split[count($split)-2], 'jml_saksi'=>$split[count($split)-1]), 'where'=>array('id'=>$rqc->id),'type'=>'update'));
				$rqcCalon = $this->_ci->db->where('rqcdetail_no', $rqc->id)->order_by('no_urut')->get(_TBL_VIEW_RQCCALON)->result_array();
				foreach($rqcCalon as $row){
					$hasil['detail'][$row['calon_no']] = $row;
				}
			}else{
				$rqc_no=$this->_ci->crud->crud_data(array('table'=>_TBL_RQC, 'field'=>array('tahun_no'=>$tahun['id'], 'kelurahan_no'=> $tps['kelurahan_no']),'type'=>'add'));
				$id=$this->_ci->crud->crud_data(array('table'=>_TBL_RQCDETAIL, 'field'=>array('rqc_no'=>$rqc_no, 'tps_no'=>$tps['tps_no'], 'jml_tdk_sah'=>$split[count($split)-2], 'jml_saksi'=>$split[count($split)-1]),'type'=>'add'));
				$hasil['rqc_no'] = $rqc_no;
				$hasil['kelurahan_no'] = $tps['kelurahan_no'];
				$hasil['rqc_detail_no'] = $id;
				$hasil['detail']=array();
			}
		}
		return $hasil;
	}
}
// END Template class