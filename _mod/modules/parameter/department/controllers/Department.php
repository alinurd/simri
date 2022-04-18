<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Department extends MY_Controller {
	var $post=array();
	public function __construct()
	{
		parent::__construct();

	}

	function init($action='list'){
		$this->set_Tbl_Master(_TBL_DEPARTMENT);

		$this->set_Open_Tab('Data Department');
			$this->addField(['field'=>'id', 'type'=>'int', 'show'=>false, 'size'=>4]);
			$this->addField(['field'=>'pid', 'input'=>'combo', 'values'=>$this->get_combo_parent_dept(), 'search'=>true, 'size'=>20]);
			$this->addField(['field'=>'department', 'required'=>true, 'search'=>true, 'size'=>50]);
			$this->addField(['field'=>'note', 'input'=>'multitext', 'size'=>500]);
			$this->addField(['field'=>'urut', 'input'=>'updown', 'default'=>1, 'size'=>50]);
			$this->addField(['field'=>'active', 'input'=>'boolean', 'default'=>1, 'size'=>20]);
		$this->set_Close_Tab();

		$this->set_Field_Primary($this->tbl_master, 'id');
		$this->set_Join_Table(['pk'=>$this->tbl_master]);

		$this->set_Sort_Table($this->tbl_master,'id');

		$this->set_Table_List($this->tbl_master,'pid');
		$this->set_Table_List($this->tbl_master,'department');
		$this->set_Table_List($this->tbl_master,'urut');
		$this->set_Table_List($this->tbl_master,'active','',7, 'center');

		$this->set_Close_Setting();

		$configuration = [
			'show_title_header' => false,
		];
		return [
			'configuration'	=> $configuration
		];
	}

	function content($ty='detail'){
		$this->_css_[] = 'jquery.nestable.css';
		$this->_js_[] = 'plugins/nestable/jquery.nestable.js';
		$content = $this->menu_posisi();
		return $content;
	}

	function optionalButton($button, $mode){
		if ($mode=='list'){
			unset($button['delete']);
			unset($button['print']);
			unset($button['search']);

			$button['save']=[
				'label'=>$this->lang->line('btn_save'),
				'color'=>'bg-success-300',
				'id'=>'btn_save_modul',
				'name'=>'Save',
				'value'=>'Simpan',
				'type'=>'submit',
				'round'=>($this->configuration['round_button'])?'rounded-round':'',
				'icon' =>'icon-floppy-disk',
				'url' => base_url(_MODULE_NAME_.'/save-modul/')
			];
		}
		return $button;
	}

	function menu_posisi(){
		
		$data['field']=$this->data->get_data_posisi_menu();
		$outpute = '';
		foreach($data['field'] as $row){
			$outpute .= $this->buildItem($row);
		}
		
		$data['tree'] = $outpute;
		$data['source_tree'] = json_encode($data['field']);
		$tombol = [];//$this->_get_list_action_button();
		$data['action']=$tombol;
		return $this->load->view('dept',$data, true); 
	}
	
	function buildItem($ad) {
		$aktif = '';
		$delete = '';
		$info = '';
		$distribusi = '';
		if($ad['active']==0){
			$aktif=" <i class='icon-x text-danger aktif'></i>";
		}

		if (!array_key_exists('children', $ad)) {
			if (intval($ad['jml'])<=0){
				$delete=" | <a href='".base_url($this->modul_name.'/delete/'.$ad['id'])."' class='edit_modul text-danger delete'>
				<i class='icon-database-remove'></i></a>";
			}
		}

		// if (intval($ad['sts_distribusi'])>0){
		// 	$distribusi=" | <i class='icon-stack  text-orange-300 pointer'></i>";
		// }

		if (intval($ad['jml'])>0){
			$info=" | <i class='icon-info22 text-success-300 pointer' data-popup='tooltip' data-placement='top' data-trigger='hover'  title='data ini sudah digunakan ".$ad['jml']." x'></i>";
		}

		$jml='';
		if (array_key_exists('children', $ad)) {
			$jml = '<span class="badge bg-indigo-400 badge-pill ml-md-3 mr-md-auto"> '.count($ad['children']).' items </span>';
		}
		$html = "<li class='dd-item dd3-item' data-id='" . $ad['id'] . "'>";
		$html .= "<div class='dd-handle dd3-handle'></div><div class='dd3-content'><span class='judul text-primary'>" . $ad['title']. ' '.$distribusi . ' <em> '.$jml.' </em> ' . $aktif . "</span> 
		<span class='float-right' style='margin-top:0px;'> 
			<a href='".base_url($this->modul_name.'/edit/'.$ad['id'])."' class='edit_modul'>
				<i class='icon-database-edit2'></i>
			</a>".$delete.$info."
		</span>
		</div>";
		if (array_key_exists('children', $ad)) {	
			$html .= "<ol class='dd-list'>";
			foreach($ad['children'] as $row){
				$html .= $this->buildItem($row);
			}
			$html .= "</ol>";
		}	
		$html .= "</li>";
		return $html;
	}
	
	function save_modul(){
		$post=$this->input->post();
		$result = $this->data->simpan_data($post);
		echo json_encode([]);
	}

	function afterSave($id , $data, $old, $mode){
		$rows = $this->db->where('id', $data['pid'])->get(_TBL_DEPARTMENT)->row();
		$level=0;
		$urut=1;
		if ($rows){
			$level = $rows->level+1;
			$rows = $this->db->select('max(urut) as jml')->where('pid', $rows->id)->get(_TBL_DEPARTMENT)->row();
			if ($rows)
				$urut=$rows->jml+1;
		}
		$this->crud->crud_table(_TBL_DEPARTMENT);
		$this->crud->crud_type('edit');
		$this->crud->crud_field('level', $level, 'int');
		$this->crud->crud_field('urut', $urut, 'int');
		$this->crud->crud_where(['field'=>'id', 'value'=>$id]);
		$this->crud->process_crud();
		return true;
	}

}