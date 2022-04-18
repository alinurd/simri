<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Menu_Frontend extends MY_Controller {
	var $kelompok_id='';

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('file');
		//$this->load->library('datacombo');
	}

	function init($action='list'){
		$this->kelompok_id='menu';
		$this->kel_id=3;
		$this->cboParent=$this->datacombo->set_data($this->kelompok_id)->build();
		$this->position=$this->crud->combo_value([0=>'header', 1=>'Top', 2=>'Bottom'])->noSelect()->result_combo();
		$this->cboUrl=$this->crud->combo_value([-1=>$this->lang->line('cbo_select_parent'), 0=>'External', 1=>'News', 2=>'Blog', 3=>'Page', 4=>'Product', 10=>'Modul', 50=>'Script'])->noSelect()->result_combo();
		$this->set_Tbl_Master(_TBL_COMBO);

		// $aksi=array_keys($this->arr_privilege);
		$this->set_Open_Tab('Data Menu');
			$this->addField(['field'=>'id', 'type'=>'int', 'show'=>false, 'size'=>4]);
			$this->addField(['field'=>'kelompok', 'show'=>false, 'save'=>true, 'default'=>$this->kelompok_id]);
			$this->addField(['field'=>'pid', 'input'=>'combo', 'values'=>$this->cboParent, 'search'=>true, 'size'=>20]);
			$this->addField(['field'=>'data', 'title'=>'Menu', 'required'=>true, 'search'=>true, 'size'=>100]);
			$this->addField(['field'=>'param_string', 'title'=>'Icon', 'size'=>25, 'prepend'=>'  ', 'append'=>' ... ']);
			$this->addField(['field'=>'param_int', 'title'=>'Position', 'input'=>'combo', 'values'=>$this->position]);
			$this->addField(['field'=>'kode', 'title'=>'Type Url', 'input'=>'combo', 'values'=>$this->cboUrl]);
			$this->addField(['field'=>'param_text', 'title'=>'Url']);
			$this->addField(['field'=>'uri_title', 'save'=>true, 'show'=>false]);
			$this->addField(['field'=>'param_other', 'title'=>'Header Image', 'input'=>'upload', 'path'=>'file/menus', 'file_thumb'=>false]);
			$this->addField(['field'=>'urut', 'input'=>'updown', 'size'=>20, 'min'=>1, 'default'=>1]);
			$this->addField(['field'=>'active', 'input'=>'boolean', 'size'=>20]);
		$this->set_Close_Tab();
		$this->_multi_language(['data']);

		$this->set_Field_Primary($this->tbl_master, 'id');
		$this->set_Where_Table(['field'=>'kelompok', 'value'=>$this->kelompok_id]);

		$this->set_Sort_Table($this->tbl_master,'data');

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

	function content($ty='detail'){
		$this->_css_[] = 'jquery.nestable.css';
		$this->_js_[] = 'plugins/nestable/jquery.nestable.js';
		$content = $this->menu_posisi();
		return $content;
	}

	function inputBox_PARAM_TEXT($mode, $field, $row, $value){
		if ($mode=='edit'){
			$kel = $row['kode'];
			if ($kel>=1 && $kel <=3){
				$cboProduct=$this->datacombo->upperGroup()->set_data()->isGroup()->set_noblank(false)->where(['kel_id'=>$kel])->build('news');
				$field['input']='combo';
				$field['values']=$cboProduct;
			}
		}
		$content = $this->set_box_input($field, $value);
		return $content;
	}

	function insertValue_URI_TITLE($value, $rows, $old){
		$title=create_unique_slug($rows['data'], $this->tbl_master);
		return $title;
	}

	function updateValue_URI_TITLE($value, $rows, $old){
		$title=$value;
		if ($rows['data']!==$old['data'])
			$title=create_unique_slug($rows['data'], $this->tbl_master);
		return $title;
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
		$badge=['badge-danger', 'badge-primary', 'badge-info'];
		if($ad['active']==0){
			$aktif=" <i class='icon-x text-danger aktif'></i>";
		}

		if (!array_key_exists('children', $ad)) {
			$delete=" | <a href='".base_url($this->modul_name.'/delete/'.$ad['id'])."' class='edit_modul text-danger delete'>
			<i class='icon-database-remove'></i></a>";
		}
		$pos = ' - ';
		if (array_key_exists($ad['param_int'], $this->position)){
			$pos = '<span class="badge '.$badge[$ad['param_int']].'">'.$this->position[$ad['param_int']].'</span>';
		}
		$html = "<li class='dd-item dd3-item' data-id='" . $ad['id'] . "'>";
		$html .= "<div class='dd-handle dd3-handle'></div><div class='dd3-content'><span class='judul text-primary'>" . $ad['title'] . " ".$pos."</span> 
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
	function get_icon(){
		$icon = $this->load->view('icomoon',[], true);
		$hasil['combo']=$icon;
		echo json_encode($hasil);
	}

	function list_url(){
		$id=$this->input->post('id');
		$url = form_input('param_text', '', 'class="form-control  text-left" style="width:100% !important;" id="param_text"');
		if ($id>0 && $id<=3 && $id<>50){
			$cboProduct=$this->datacombo->upperGroup()->set_data()->isGroup()->set_noblank(false)->where(['kel_id'=>$id])->build('news');
			$url = form_dropdown('param_text', $cboProduct, '', 'class="form-control select" style="width:100% !important;" id="param_text"');
		}elseif ($id==4 ){
			$cboProduct=$this->datacombo->upperGroup()->set_data()->isGroup()->set_noblank(false)->build('product');
			$url = form_dropdown('param_text', $cboProduct, '', 'class="form-control select" style="width:100% !important;" id="param_text"');
		}

		$url .='<span class="text-muted" id="info_url">&nbsp;</span>';

		$hasil['combo'] = $url;
		echo json_encode($hasil);
	}
}