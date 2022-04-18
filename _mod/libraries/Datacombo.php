<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Datacombo
{
	private $_ci;
	private $preference=array();
	private $output_parent=array();
	private $data=array();
	private $no_blank=false;
	private $isGroup=false;
	private $upperGroup=false;
	private $_kelData='';
	private $_where=[];

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

	function get_data(){

		$this->_ci->db->select('*');
		$this->_ci->db->from(_TBL_MODUL);
		$this->_ci->db->order_by('urut');
		$query=$this->_ci->db->get();
		$rows=$query->result_array();
		$input=array();
		foreach($rows as $row){
			$input[] = array("id" => $row['id'], "title" => '['.$row['nm_modul'].'] - '.$row['title'], "slug" => $row['pid'], "pid" => $row['pid'],  "urut" => $row['urut'], "aktif" => $row['aktif']);
		}

		$result = _tree($input);
		return $result;
	}

    function get_data_combo(){
		$this->_ci->db->select('*');
		$this->_ci->db->from(_TBL_COMBO);
		if (is_array($this->_kelData)){
			$this->_ci->db->where_in('kelompok', $this->_kelData);
		}else{
			$this->_ci->db->where('kelompok', $this->_kelData);
		}
		$this->_ci->db->order_by('urut');
		$query=$this->_ci->db->get();
        $rows=$query->result_array();
        $input=[];
		foreach($rows as $row){
			$input[] = array("id" => $row['id'], "title" => $row['data'], "slug" => $row['pid'], "urut" => $row['urut'], "active" => $row['active']);
		}

		$result = _tree($input);
		$this->data = $result;
	}

	function get_data_dept(){
		$this->_ci->db->select('*');
		$this->_ci->db->from(_TBL_DEPARTMENT);
		$this->_ci->db->order_by('urut');
		$query=$this->_ci->db->get();
		$rows=$query->result_array();
		$input=[];
		foreach($rows as $row){
			$input[] = array("id" => $row['id'], "title" => $row['department'], "slug" => $row['pid'], "urut" => $row['urut'], "active" => $row['active']);
		}
		
		$result = _tree($input);
		$this->data = $result;
	}

	function get_data_wilayah(){
		$this->_ci->db->where('a.level',2)->where('a.active',1);
		$query = $this->_ci->db->select(' b.name as kelompok, a.*')->from(_TBL_WILAYAH.' as a ')->join(_TBL_WILAYAH.' as b','a.parent_id=b.id')->order_by('a.name')->get();
		// $query = $this->_ci->db->query('SELECT b.name as kelompok, a.* FROM public.lhk_wilayah as a join lhk_wilayah as b on a.parent_id=b.id where a.level=2 and a.active =1 order by a.name');
        $rows=$query->result_array();
        $input=[];
		foreach($rows as $row){
			if ($this->upperGroup)
				$input[strtoupper($row['kelompok'])][$row['id']] = $row['name'];
			else
				$input[$row['kelompok']][$row['id']] = $row['name'];

		}
		$this->data = $input;
	}

	function get_data_news(){
		$this->_ci->db->select('*');
		$this->_ci->db->from(_TBL_VIEW_NEWS);
		$this->_ci->db->where('active', 1);
		// $this->_ci->db->order_by('product');
		$query=$this->_ci->db->get();
        $rows=$query->result_array();
        $input=[];
		foreach($rows as $row){
			if ($this->upperGroup)
				$input[strtoupper($row['kelompok'])][$row['id']] = $row['title'];
			else
				$input[$row['kelompok']][$row['id']] = $row['title'];

		}
		//dump($this->_ci->db->last_query());
		$this->data = $input;
	}


    function set_data($kel=''){
		$this->_kelData=$kel;
        return $this;
	}
	
	function where($where){
		$this->_where=$where;
        return $this;
    }

    function set_noblank($sts=true){
		$this->no_blank=$sts;
        return $this;
	}

	function isGroup($sts=true){
		$this->isGroup=$sts;
		return $this;
	}

	function upperGroup($sts=true){
		$this->upperGroup=$sts;
		return $this;
	}


	function build($kel=''){

		if ($this->_where){
			foreach($this->_where as $key=>$wr){
				if (is_array($wr)){
					$this->_ci->db->where_in($key, $wr);
				}else{
					$this->_ci->db->where($key, $wr);
				}
			}
		}
		if (!empty($this->_kelData)){
            $this->get_data_combo();
        }elseif ($kel=='wilayah'){
			$this->get_data_wilayah();
		}elseif ($kel=='dept'){
				$this->get_data_dept();
        }elseif ($kel=='news'){
            $this->get_data_news();
        }
        $this->output_parent=[];
        if (!$this->no_blank)
            $this->output_parent = array(0=>' - Parent - ');

		if ($this->isGroup){
			$this->output_parent=$this->data;
			if (!$this->output_parent){
				$this->output_parent=array(0=>lang('cbo_select'));
			}
		}else{
			foreach($this->data as $row){
				$this->buildItem($row);
			}
		}
		return $this->output_parent;
	}

	function buildItem($ad, $level=0) {
		$space = str_repeat('&nbsp;',$level*6);
		$this->output_parent[$ad['id']]=$space . $ad['title'];
		if (array_key_exists('children', $ad)) {
			++$level;
			foreach($ad['children'] as $row){
				$this->buildItem($row, $level);
			}
		}
		$level=0;
	}
}
// END Template class