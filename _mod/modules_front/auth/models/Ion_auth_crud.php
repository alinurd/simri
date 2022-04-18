<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ion_auth_crud extends MX_Model
{
	public $cParamsData=[];
	public $cParamsModul=[];
	public $cWhere=[];
	public $cField=[];
	public $cId=0;
	public $cTable='';
	public $iLastId=0;
	public $cTypeCrud='add';
	public $cOldField=[];
	public $cPost=[];
	public $cType ='';
	public $iStsCount =false;
	public $iStsLimit =true;
	protected $cResultRows;
	protected $sError;
	protected $last_query='';
	protected $cSelect='';
	protected $cSort=[];
	protected $cValue=[];
	protected $cTbl='';
	protected $cNoSelect=false;

	public function __construct()
	{
		parent::__construct();
		ini_set('max_execution_time', 2500);

	}

	function getQuery(){
		$start = 0;
		$limit = 0;
		if ( isset( $this->cParamsData['start'] ) && $this->cParamsData['length'] != '-1' )
		{
			$start = intval( $this->cParamsData['start']);
			$limit = intval( $this->cParamsData['length']);
		}
		if (!$this->iStsCount && $this->iStsLimit && $limit>0){
			$this->db->limit($limit, $start);
		}
		if (array_key_exists("sort",$this->cParamsModul)){
			foreach($this->cParamsModul['sort'] as $sort)
			{
				$type='asc';
				if (!empty($sort['type']))
					$type=$sort['type'];
				
				$this->db->order_by($sort['tbl'].'.'.$sort['id'],$type);
			}
		}

		if (isset($this->cParamsData['order'][0]['column']))
		{
			foreach($this->cParamsModul['title'] as $key=>$title){
				if (intval($this->cParamsData['order'][0]['column']) == ($key+2))
				{
					$this->db->order_by($title[0].'.'.$title[1], ($this->cParamsData['order'][0]['dir']==='asc' ? 'asc' : 'desc'));
				}
			}
		}
		$fields=array();
		if (array_key_exists("fields",$this->cParamsModul)){
			foreach($this->cParamsModul['fields'] as $key=>$row)
			{
				$label=$row['field'];
				if (array_key_exists('alias', $row)){
					$label= $row['alias'];
				}


				if ($row['type']!=='free'){
					$fields[]= $row['nmtbl'].'.'.$row['field'].' as '.$label;
				}

				if (!$this->iStsCount){
					if (count($this->cPost)>0)
					{
						if (array_key_exists('search',$row))
						{
							if ($row['search'])
							{
								if (!empty($this->cPost[$row['field']])){
									switch ($row['type']){
										case 'string':
										case 'text':
											$this->db->like($row['nmtbl'].'.'.$row['field'], $this->cPost[$row['field']]);
											break;
										case 'int':
										case 'integer':
										case 'boolean':
										case 'float':
											$this->db->where($row['nmtbl'].'.'.$row['field'], $this->cPost[$row['field']]);
											break;
										case 'date':
											$tgl=date('Y-m-d',strtotime($this->cPost[$row['field']]));
											$this->db->where($row['nmtbl'].'.'.$row['field'], $tgl);
											break;
									}
								}
							}
						}
					}
				}
			}
		}

		if ($this->cParamsModul['primary']['info']){
			$fields[] = 'create_date';
			$fields[] = 'create_user';
			$fields[] = 'update_date';
			$fields[] = 'update_user';
		}
		$this->db->select(implode(',',$fields));
		if ($this->cId>0){
			$this->db->where($this->cParamsModul['primary']['tbl'].'.'.$this->cParamsModul['primary']['id'], $this->cId);
		}
		if (array_key_exists("where",$this->cParamsModul)){
			foreach($this->cParamsModul['where'] as $whr)
			{
				$op='=';
				if (!empty($whr['op'])){
					if ($whr['op']=='null'){
						$this->db->where($whr['tbl'].'.'.$whr['id'] .' is NULL', NULL, FALSE);
					}elseif ($whr['op']=='not null'){
						$this->db->where($whr['tbl'].'.'.$whr['id'] .' is not NULL', NULL, FALSE);
					}elseif ($whr['op']=='in'){
						$this->db->where_in($whr['tbl'].'.'.$whr['id'], $whr['value']);
					}elseif ($whr['op']=='not in'){
						$this->db->where_not_in($whr['tbl'].'.'.$whr['id'], $whr['value']);
					}else{
						$this->db->where($whr['tbl'].'.'.$whr['id'], "'".$whr['op'].$whr['value']."'");
						// die($whr['tbl'].'.'.$whr['id'] . $whr['op']);
					}
				}else{
					$this->db->where($whr['tbl'].'.'.$whr['id'], $whr['value']);
				}
			}
		}

		if (!$this->iStsCount){
			if ( isset($this->cParamsData['search']['value']) && $this->cParamsData['search']['value'] != "" )
			{
				$this->db->or_group_start();
				foreach($this->cParamsModul['title'] as $key=>$title){
					foreach($this->cParamsModul['fields'] as $field)
					{
						if ($title[1]==$field['field']){
							switch ($field['type']){
								case 'string':
								case 'text':
									$this->db->or_like($title[0].'.'.$title[1], $this->cParamsData['search']['value']);
								// 	break;
								// case 'int':
								// case 'integer':
								// case 'boolean':
								// case 'float':
								// 	$this->db->or_where($title[0].'.'.$title[1], $this->cParamsData['search']['value']);
									break;
								case 'date':
									$tgl=date('Y-m-d',strtotime($this->cParamsData['search']['value']));
									$this->db->or_where($title[0].'.'.$title[1], $tgl);
									break;
							}
						}
					}
				}
				$this->db->group_end();
			}
		}
		$this->cResultRows =$this->db->get($this->cParamsModul['table']);
	}

	function getOneData(){
		$rows = $this->cResultRows->row_array();
		$this->last_query=$this->db->last_query();
		return  $rows;
	}

	function getAllData(){
		$rows = $this->cResultRows->result_array();
		$this->last_query=$this->db->last_query();
		return  $rows;
	}

	function getCountData(){
		$jml = $this->cResultRows->num_rows();
		$this->last_query=$this->db->last_query();
		return  $jml;
	}

	function crud_table($table){
		$this->cTable=$table;
		return $this;
	}

	function crud_type($aksi){
		$this->cTypeCrud=$aksi;
		return $this;
	}

	function crud_field($field, $value, $type='text'){
		if (is_array($field)){
			$this->cField = $field;
			$this->cTypeCrud='add_batch';
		}else{
			switch ($type){
				case 'int':
				case 'integer':
				case 'boolean':
					$value = intval($value);
					break;
				case 'float':
					$value = floatval($value);
					break;
				case 'date':
					$value=date('Y-m-d',strtotime($value));
					break;
				case 'datetime':
					$value=date('Y-m-d H:i:s',strtotime($value));
					break;
			}
			$this->cField[$field] = $value;
		}

		return $this;
	}

	function crud_where($data){
		$op='=';
		if (array_key_exists('op', $data)){
			$op=$data['op'];
		}
		switch ($op){
			case 'null':
				$this->db->where($data['field'] .' is NULL', NULL, FALSE);
				break;
			case 'not null':
				$this->db->where($data['field'] .' is not NULL', NULL, FALSE);
				break;
			case 'in':
				if (!is_array($data['value']))
					$value[]=$data['value'];
				else
					$value = $data['value'];
				$this->db->where_in($data['field'], $value);
				break;
			case 'not in':
				if (!is_array($data['value']))
					$value[]=$data['value'];
				$this->db->where_not_in($data['field'], $value);
				break;
			case '=':
				$this->db->where($data['field'], $data['value']);
				break;
			default:
				$this->db->where($data['field']. ' '.$op, $data['value']);
				break;

		}
		return $this;
	}

	function process_crud(){
		$pesan='';
		switch ($this->cTypeCrud){
			case 'add':
				$this->db->insert($this->cTable, $this->cField);
				$this->iLastId=$this->db->insert_id();
				$this->iAffectedRows=1;
				$pesan =' ditambahkan';
				$this->logdata->type=5;
				$this->logdata->set_log('new id :', $this->iLastId);
				break;
			case 'add_batch':
				$this->db->insert_batch($this->cTable, $this->cField);
				$this->iLastId=$this->db->insert_id();
				$this->iAffectedRows=count($this->cField);
				$pesan =' ditambahkan';
				$this->logdata->type=5;
				break;
			case 'edit':
				$this->db->update($this->cTable, $this->cField);
				$this->iAffectedRows=$this->db->affected_rows();
				$pesan =' diedit';
				$this->iLastId=1;
				$this->logdata->type=6;
				break;
			case 'delete':
				$this->db->delete($this->cTable);
				$this->iAffectedRows=$this->db->affected_rows();
				$id=$this->iAffectedRows;
				$this->iLastId=1;
				$pesan =' dihapus';
				$this->logdata->type=7;
				break;
		}
		if ($this->db->error()['code']){
			$this->sError=$this->db->error();
			$this->logdata->set_error('Code:'.$this->sError['code'].' - '.$this->sError['message']). ' - '.$this->db->last_query();
			$this->iLastId=0;
		}else{
			$this->sError='';
		}
		$pesan =$this->iAffectedRows . ' data <span class="text-danger">'.strtoupper(str_replace('_',' ',substr($this->cTable,2))).'</span> berhasil '.$pesan;
		$this->logdata->set_log('sql', $this->db->last_query());
		$this->last_query=$this->db->last_query();
		if ($this->cTable!=='t_log')
		$this->logdata->set_message($pesan);
		
		$this->clear_crud();
	}

	function last_id(){
		return (int) $this->iLastId;
	}

	function last_query(){
		return $this->last_query;
	}
	function err_query(){
		return $this->sError['message'];
	}

	function clear_crud(){
		$this->iAffectedRows=0;
		$this->cTable='';
		$this->cField=[];
	}

	function combo_select($isi=[]){
		$this->cSelect=implode(',',$isi);
		return $this;
	}
	function combo_where($keys, $val=''){

		if (is_array($keys)){
			foreach($keys as $key=>$row){
				$this->cWhere[$key]=$row;
			}
		}else{
			$this->cWhere[$keys]=$val;
		}
		return $this;
	}
	function combo_sort($keys, $val='asc'){
		if (is_array($keys)){
			foreach($keys as $key=>$row){
				$this->cSort[$key]=$row;
			}
		}else{
			$this->cSort[$keys]=$val;
		}
		return $this;
	}
	function combo_tbl($tbl){
		$this->cTbl=$tbl;
		return $this;
	}

	function noSelect(){
		$this->cNoSelect=true;
		return $this;
	}


	function get_combo(){
		$this->db->select($this->cSelect);
		foreach($this->cWhere as $key=>$row){
			$this->db->where($key, $row);
		}
		foreach($this->cSort as $key=>$row){
			$this->db->order_by($key, $row);
		}
		$sql=$this->db->get($this->cTbl);
		$this->cValue  = $sql->result_array();
		$this->last_query=$this->db->last_query();
		return $this;
	}

	function combo_value($data){
		foreach($data as $key=>$row){
			$this->cValue[]=[0=>$key,1=>$row];
		}
		return $this;
	}
	
	function result_combo(){
		$combo=[];
		if (!$this->cNoSelect){
			$combo[''] = lang('cbo_select');
		}
		// dump($combo);
		// die('kesini');
		$rows=$this->cValue;

		foreach ($rows as $dt) {
			$id=0;
			$name='';
			$no=0;
			
			foreach($dt as $key => $d){
				if ($no==0)
					$id=$d;
				elseif ($no==1)
					$name=$d;
				++$no;
			}
			
			$combo[$id] = $name;
		}
		$this->clear_combo();
		return $combo;
	}
	function clear_combo(){
		$this->cSelect='';
		$this->cSort=[];
		$this->cValue=[];
		$this->cTbl='';
		$this->cNoSelect=false;
	}
}