<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Blog_Category extends MY_Controller {
	var $kelompok_id='';

	public function __construct()
	{
		parent::__construct();

	}

	function init($action='list'){
		$this->kelompok_id='cat-blog';
		$this->kelompok=$this->crud->combo_value(['cat-blog'=>'Activity', 'cat-page'=>'Page'])->result_combo();
		$this->load->library('datacombo');
		$this->kel_id=3;
		$this->cboParent=$this->datacombo->set_data(['cat-blog','cat-page'])->build();
		$this->set_Tbl_Master(_TBL_COMBO);

		$this->addField(['field'=>'id', 'type'=>'int', 'show'=>false, 'size'=>4]);
		$this->addField(['field'=>'kelompok','title'=>'Category', 'type'=>'string', 'show'=>true, 'input'=>'combo', 'values'=>$this->kelompok, 'default'=>$this->kelompok_id]);
		$this->addField(['field'=>'pid', 'input'=>'combo', 'values'=>$this->cboParent, 'search'=>true, 'size'=>20]);
		$this->addField(['field'=>'data', 'required'=>true, 'search'=>true, 'size'=>100]);
		$this->addField(['field'=>'urut', 'input'=>'updown', 'size'=>20, 'min'=>1, 'default'=>1]);
		$this->addField(['field'=>'active', 'input'=>'boolean', 'size'=>20]);
		$this->addField(['field'=>'uri_title', 'save'=>true, 'show'=>false]);

		$this->set_Field_Primary($this->tbl_master, 'id');

		$this->set_Sort_Table($this->tbl_master,'kelompok');
		$this->set_Sort_Table($this->tbl_master,'urut');

		$this->set_Table_List($this->tbl_master,'kelompok');
		$this->set_Table_List($this->tbl_master,'pid');
		$this->set_Table_List($this->tbl_master,'data');
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


	function insertValue_URI_TITLE($value, $rows, $old){
		$title=create_unique_slug($rows['data'], $this->tbl_master);
		return $title;
	}

	function updateValue_URI_TITLE($value, $rows, $old){
		$title=$value;
		if (trim(strtolower($rows['data']))!==trim(strtolower($old['data'])))
			$title=create_unique_slug($rows['data'], $this->tbl_master);
		return $title;
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

	function list_MANIPULATE_ACTION(){
		$tombol['urut']='<a class="add btn btn-primary" href="'.base_url($this->modul_name.'/menu-posisi').'" data-toggle="popover" data-content="Atur Menu Posisi"><i class="icon-list"></i> Edit All </a>&nbsp;&nbsp;';
		return $tombol;
	}

	function menu_posisi(){
		$this->data->kelompok = $this->kelompok_id;
		$data['field']=$this->data->get_data_posisi_menu();
		$outpute = '';
		foreach($data['field'] as $row){
			$outpute .= $this->buildItem($row);
		}

		$data['tree'] = $outpute;
		$data['source_tree'] = json_encode($data['field']);
		$tombol = [];//$this->_get_list_action_button();
		$data['action']=$tombol;
		return $this->load->view('modul',$data, true);
	}
	
	function buildItem($ad) {
		$aktif = '';
		$delete = '';
		if($ad['active']==0){
			$aktif=" <i class='icon-x text-danger aktif'></i>";
		}

		$kel=" <span class='judul text-warning'> [Blog]</span> ";
		if($ad['kelompok']=='cat-page'){
			$kel=" <span class='judul text-success'> [Page]</span> ";
		}

		if (!array_key_exists('children', $ad)) {
			$delete=" | <a href='".base_url($this->modul_name.'/delete/'.$ad['id'])."' class='edit_modul text-danger delete'>
			<i class='icon-database-remove'></i></a>";
		}
		
		$html = "<li class='dd-item dd3-item' data-id='" . $ad['id'] . "'>";
		$html .= "<div class='dd-handle dd3-handle'></div><div class='dd3-content'><span class='judul text-primary'>" . $ad['title'] . "</span> &nbsp;&nbsp; - &nbsp;&nbsp;<small>" . $kel . "</small>
		<span class='float-right' style='margin-top:0px;'>
			<a href='".base_url($this->modul_name.'/edit/'.$ad['id'])."' class='edit_modul'>
				<i class='icon-database-edit2'></i>
			</a>".$delete."
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

	function buildItem_parent($ad, $level=0) {
		$space = str_repeat('&nbsp;',$level*6);
		$this->output_parent[$ad['id']]=$space . $ad['title'];
		if (array_key_exists('children', $ad)) {
			++$level;
			foreach($ad['children'] as $row){
				$this->buildItem_parent($row, $level);
			}
		}
		$level=0;
	}

	function save_modul(){
		$post=$this->input->post();
		$result = $this->data->simpan_data($post);
		echo json_encode([]);
	}

	function afterSave($id , $data, $old, $mode){
		$rows = $this->db->where('id', $data['pid'])->get(_TBL_COMBO)->row();
		$level=0;
		$urut=1;
		if ($rows){
			$level = $rows->level+1;
			$rows = $this->db->select('max(urut) as jml')->where('pid', $rows->id)->get(_TBL_COMBO)->row();
			if ($rows)
				$urut=$rows->jml+1;
		}
		$this->crud->crud_table(_TBL_COMBO);
		$this->crud->crud_type('edit');
		$this->crud->crud_field('level', $level, 'int');
		// $this->crud->crud_field('urut', $urut, 'int');
		$this->crud->crud_where(['field'=>'id', 'value'=>$id]);
		$this->crud->process_crud();
		return true;
	}
}