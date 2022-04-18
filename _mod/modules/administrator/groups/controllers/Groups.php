<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Groups extends MY_Controller {
	protected $no=0;
	protected $child=0;
	
	public function __construct()
	{
		parent::__construct();
		
	}

	function init($action='list'){
		$this->cbo_dashboard=$this->crud->combo_select(['id', 'dashboard'])->combo_where('active', 1)->combo_tbl(_TBL_DASHBOARD)->get_combo()->result_combo();
		$this->cboData=$this->crud->combo_value([1=>'All Data', 2=>'Risk Ownere', 3=>'Risk Officer'])->result_combo();
		
		$this->set_Tbl_Master(_TBL_GROUPS);

		$this->set_Open_Tab('Data Petugas');
			$this->addField(array('field'=>'id', 'show'=>false));
			$this->addField(array('field'=>'name', 'size'=>30, 'required'=>true, 'search'=>true));
			// $this->addField(array('field'=>'params', 'title'=>'Data Privilege'));
			$this->addField(['field'=>'privilege_owner', 'input'=>'combo', 'required'=>true, 'search'=>true, 'values'=>$this->cboData, 'size'=>50]);
			// $this->addField(['field'=>'super_user', 'input'=>'boolean', 'required'=>true]);
			$this->addField(array('field'=>'active', 'input'=>'boolean', 'size'=>40, 'required'=>true));
			$this->addField(array('field'=>'privilege', 'title'=>'Menu Privilege', 'type'=>'free', 'input'=>'free', 'mode'=>'a', 'size'=>100));
		$this->set_Close_Tab();
			
		$this->set_Field_Primary(_TBL_GROUPS, 'id');
		$this->set_Join_Table(array('pk'=>$this->tbl_master));
		
		$this->set_Sort_Table($this->tbl_master,'name');
		
		$this->set_Table_List($this->tbl_master,'name');
		$this->set_Table_List($this->tbl_master,'privilege_owner');
		// $this->set_Table_List($this->tbl_master,'super_user','',8,'center');
		$this->set_Table_List($this->tbl_master,'active','',8,'center');
		
		$this->set_Close_Setting();
		$configuration = [
			'show_title_header' => false,
		];
		return [
			'configuration'	=> $configuration
		];
	}

	function get_param(){
		$_GET['id']=intval($this->uri->segment(3));
		$data=$this->data->get_param($_GET['id']);
		$result=$this->load->view('groups/param',$data,true);
		return $result;
	}

	function inputBox_PARAMS($mode, $field, $rows, $value){
		$value=json_decode($value, true);
		$sts=false;
		$read_only=false;
		if ($value){
			if (array_key_exists('validator', $value)){
				$sts=$value['validator'];
			}
			if (array_key_exists('read_only', $value)){
				$read_only=$value['read_only'];
			}
		}
		$x='<label class="pointer">' . form_checkbox('validator', 1, $sts);
		$x .= '&nbsp; Validator </label><br/>';
		$x.='<label class="pointer">' . form_checkbox('read_only', 1, $read_only);
		$x .= '&nbsp; Read Only </label><br/>';

		return $x;
	}

	function inputBox_PRIVILEGE($mode, $field, $rows, $value){
		//$_GET['id']=$this->uri->segment(3);
		$this->data->group_id=$this->uri->segment(3);
		$data=$this->data->get_modul();

		$outpute = '<table class="table">';
		foreach($data['field'] as $row){
			$this->child=0;
			$this->level=0;
			$outpute .= $this->buildItem($row);
		}
		$outpute .= '</table>';
		$result=$this->load->view('previlege',['content'=>$outpute], true);
		return $result;
	}

	function buildItem($ad) {
		if ($this->child<=0){$this->child=0;}
		$loop = str_repeat('&nbsp;', (intval($ad['level'])-1)*8);
		$html = "<tr>";
		$html.= "<td width='5%'>".++$this->no.'</td>';
		$html.= "<td width='40%' class='pointer toggleCheckBoxes' data-sts='0'>".$loop.$ad['title'].form_hidden(['modul[]'=>$ad['id'], 'edit_id[]'=>$ad['edit_id'], 'source_'.$ad['id']=>$ad['source']])."</td>";
		$privilege='';
		foreach($ad['privilege'] as $key=>$pr){
			$checked='';
			if ($pr=='1') { $checked='checked';}
			$privilege .= '<div class="form-check form-check-switchery form-check-inline form-check-right">
			<label class="form-check-label">';
			$privilege.= form_hidden([$key.'_'.$ad['id']=>0]).'<input class="pointer form-switchery-primary" type="checkbox" name="'.$key.'_'.$ad['id'].'" value="1" '.$checked.'>';
			$privilege.= $key . '</label></div>';
		}
		$html.= "<td>".$privilege.'</td>';
		$html .= "</tr>";
		if (array_key_exists('children', $ad)) {
				++$this->child;
			foreach($ad['children'] as $row){
				$html .= $this->buildItem($row);
			}
		}
		return $html;
	}

	function afterDelete($id){
		$this->crud->crud_table(_TBL_USERS_GROUPS);
		$this->crud->crud_type('delete');
		$this->crud->crud_where(['op'=>'in', 'field'=>'group_id', 'value'=>$id]);
		$this->crud->process_crud();

		$this->crud->crud_table(_TBL_GROUP_PRIVILEGE);
		$this->crud->crud_type('delete');
		$this->crud->crud_where(['op'=>'in', 'field'=>'group_id', 'value'=>$id]);
		$this->crud->process_crud();

		return true;
	}

	function afterSave($id , $new_data, $old_data, $mode){
		$result = $this->data->save_privilege($id , $new_data);
		return $result;
	}
}