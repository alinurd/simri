<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MX_Model {

	var $tbl_items='';
	var $_prefix='';
	var $_modules='';
	var $cabang =" Semua Cabang ";
	public function __construct()
    {
        parent::__construct();
		$this->arr_bulan=array(0=>'undefine', 1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'Nopember', 12=>'Desember');
	}
	
	function get_param_combo(){
		$query=$this->db->query("SELECT distinct YEAR(tanggal) thn from "._TBL_SO_BUKU." order by YEAR(tanggal)");
		$rows=$query->result_array();
		$arr=array();
		foreach ($rows as $row){
			$arr[$row['thn']]=$row['thn'];
		}
		$result['tahun']=$arr;
		
		$query=$this->db->query("SELECT distinct YEAR(tanggal) thn, MONTH(tanggal) bulan from "._TBL_SO_BUKU." order by YEAR(tanggal), MONTH(tanggal)");
		$rows=$query->result_array();
		$arr=array();
		foreach ($rows as $row){
			$arr[$row['thn'].'-'.$row['bulan']]=$row['thn'] . ' - ' . $this->arr_bulan[intval($row['bulan'])];
		}
		$result['bulan']=$arr;
		return $result;
	}
	
	function get_tgl_kuarter($id){
		$query=$this->db->query("SELECT * from {$this->tbl_kuarter} where id='{$id}'");
		$rows=$query->row();
		return $rows;
	}
	
	function get_data_laporan($data){
		$where = '';
		if ($data['type']==1){
			$tgl1=date('Y-m-d',strtotime($data['tgl1']));
			$tgl2=date('Y-m-d',strtotime($data['tgl2']));	
			$where = "a.tanggal between '{$tgl1}' and '{$tgl2}' ";
			$result['keterangan']="Rekap Transaksi Tanggal ".$tgl1." sampai ".$tgl2;
		}elseif($data['type']==3){
			$tgl=explode('-',$data['bulan']);
			$where = "YEAR(a.tanggal)= '{$tgl[0]}' and MONTH(a.tanggal) = '{$tgl[1]}' ";
			$result['keterangan']="Rekap Transaksi Bulan ".$this->arr_bulan[$tgl[1]]." ".$tgl[0];
		}elseif($data['type']==4){
			$where = "YEAR(a.tanggal)= '{$data['tahun']}' ";
			$result['keterangan']="Rekap Transaksi Tahun ".$data['tahun'];
		}
		
		if (!empty($data['cabang_no'])){
			if (!empty($where))
				$where .= ' and a.kelompok=2 and a.cabang_no='.$data['cabang_no'];
		}
		$order = ' a.po_no ';
		if ($data['order']=='1'){
			$order = ' a.tanggal, a.kode ';
		}elseif ($data['order']=='2'){
			$order = ' a.cabang, a.kode ';
		}elseif ($data['order']=='3'){
			$order = ' a.buku_buku, a.tanggal ';
		}
		
		$sql = "SELECT  a.* FROM "._TBL_VIEW_LAP_INVENTORI_PUSAT." a where {$where}  ORDER BY {$order}";
		
		$query=$this->db->query($sql);
		$result['hasil']=$query->result();
		
		return $result;
	}
	
	function get_data_laporan_rekap($data){
		$where = '';
		if ($data['type']==1){
			$tgl1=date('Y-m-d',strtotime($data['tgl1']));
			$tgl2=date('Y-m-d',strtotime($data['tgl2']));	
			$where = "a.tanggal between '{$tgl1}' and '{$tgl2}' ";
			$result['keterangan']="Rekap Transaksi Tanggal ".$tgl1." sampai ".$tgl2;
		}elseif($data['type']==3){
			$tgl=explode('-',$data['bulan']);
			$where = "YEAR(a.tanggal)= '{$tgl[0]}' and MONTH(a.tanggal) = '{$tgl[1]}' ";
			$result['keterangan']="Rekap Transaksi Bulan ".$this->arr_bulan[$tgl[1]]." ".$tgl[0];
		}elseif($data['type']==4){
			$where = "YEAR(a.tanggal)= '{$data['tahun']}' ";
			$result['keterangan']="Rekap Transaksi Tahun ".$data['tahun'];
		}
		
		if (!empty($data['cabang_no'])){
			$this->cabang = $this->cari_cabang($data['cabang_no']);
			if (!empty($where))
				$where .= ' and a.kelompok=2 and a.cabang_no='.$data['cabang_no'];
		}
		
		if (!empty($where))
			$where =' where  ' . $where;
		
		$sql = "select day(a.tanggal) as tgl, month(a.tanggal) as bln, year(a.tanggal) as thn, a.cabang, a.nama_buku, a.harga_jual, sum(a.jumlah) as jml, sum(a.harga) as hrg, sum(a.diskon) as disk, sum(a.jumlah * a.harga - a.diskon) as total  from "._TBL_VIEW_LAP_INVENTORI_PUSAT. " a " . $where . " group by a.tanggal, a.cabang, a.nama_buku order by day(a.tanggal);";
		
		$query=$this->db->query($sql);
		$result['hasil']=$query->result();
		$tanggal=array();
		foreach($result['hasil'] as $row){
			$tanggal[$row->tgl]=$row->tgl;
		}
		$result['tgl']=$tanggal;
		
		return $result;
	}
	
	function cari_cabang($id){
		$query = $this->db->where('id',$id)
				->get(_TBL_CABANG);
		$rows = $query->row();
		return (array) $rows;
	}
	
	function get_cabang($info){
		return $this->cabang[$info];
	}
	
	function get_data_laporan_detail($data){
		$query=$this->db->query($sql);
		$rows['rekap']=$query->row();
		return $rows;
	}
}
/* End of file app_login_model.php */
/* Location: ./application/models/app_login_model.php */