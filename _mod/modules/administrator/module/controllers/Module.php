<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Module extends MY_Controller {
	var $post=array();
	public function __construct()
	{
		parent::__construct();

	}

	function init($action='list'){
		$this->set_Tbl_Master(_TBL_MODUL);

		$this->arr_privilege=$this->crud->combo_value(['read'=>'read', 'insert'=>'insert','update'=>'update','delete'=>'delete','print'=>'print'])->noSelect()->result_combo();

		$aksi=array_keys($this->arr_privilege);
		$this->set_Open_Tab('Data Module');
			$this->addField(['field'=>'id', 'type'=>'int', 'show'=>false, 'size'=>4]);
			$this->addField(['field'=>'pid', 'input'=>'combo', 'values'=>$this->get_combo_parent(), 'search'=>true, 'size'=>20]);
			$this->addField(['field'=>'icon', 'size'=>50, 'prepend'=>'  ', 'append'=>' ... ']);
			$this->addField(['field'=>'privilege', 'type'=>'string', 'input'=>'combo', 'values'=>$this->arr_privilege, 'json'=>true, 'multiselect'=>true, 'search'=>true, 'default'=>$aksi, 'size'=>20]);
			$this->addField(['field'=>'nm_modul', 'required'=>true, 'search'=>true, 'size'=>50]);
			$this->addField(['field'=>'title', 'required'=>true, 'search'=>true, 'size'=>50]);
			$this->addField(['field'=>'urut', 'input'=>'updown', 'size'=>20]);
			$this->addField(['field'=>'posisi', 'show'=>false, 'save'=>true, 'default'=>'kiri']);
			$this->addField(['field'=>'active', 'type'=>'int', 'input'=>'boolean', 'size'=>20]);
		$this->set_Close_Tab();

		$this->set_Field_Primary(_TBL_MODUL, 'id');
		$this->set_Join_Table(['pk'=>$this->tbl_master]);

		$this->set_Sort_Table($this->tbl_master,'id');

		$this->set_Table_List($this->tbl_master,'title');
		$this->set_Table_List($this->tbl_master,'nm_modul');
		$this->set_Table_List($this->tbl_master,'icon');
		$this->set_Table_List($this->tbl_master,'posisi');
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

	function list_MANIPULATE_ACTION(){
		$tombol['urut']='<a class="add btn btn-primary" href="'.base_url($this->modul_name.'/menu-posisi').'" data-toggle="popover" data-content="Atur Menu Posisi"><i class="icon-list"></i> Edit All </a>&nbsp;&nbsp;';
		return $tombol;
	}
	
	function get_combo_parent(){
		$data=$this->data->get_data_posisi_menu();
		$this->output_parent = array(0=>' - Parent - ');
		foreach($data as $row){
			$this->buildItem_parent($row);
		}
		return $this->output_parent;
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
		return $this->load->view('modul',$data, true); 
	}
	
	function buildItem($ad) {
		$aktif = '';
		$delete = '';
		if($ad['active']==0){
			$aktif=" <i class='icon-x text-danger aktif'></i>";
		}

		if (!array_key_exists('children', $ad)) {	
			$delete=" | <a href='".base_url($this->modul_name.'/delete/'.$ad['id'])."' class='edit_modul text-danger delete'>
			<i class='icon-database-remove'></i></a>";
		}
		
		$html = "<li class='dd-item dd3-item' data-id='" . $ad['id'] . "'>";
		$html .= "<div class='dd-handle dd3-handle'></div><div class='dd3-content'><i class='".$ad['icon']."'></i> <span class='judul text-primary'>" . $ad['title'] . ' <em>[<sub>'. $ad['nm_modul'] . '</sub>]</em> ' . $aktif . "</span> 
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
		header('Content-type: application/json');
		echo json_encode([]);
	}
	
	function POST_INSERT_PROCESSOR($id , $new_data){
		$this->authentication->set_Menu_Navigator();
		
		return true;
	}
	
	function POST_UPDATE_PROCESSOR($id , $new_data, $old_data){
		$this->authentication->set_Menu_Navigator();
		
		return true;
	}

	function get_icon(){
		$icon = $this->load->view('icomoon',[], true);
		$hasil['combo']=$icon;
		header('Content-type: application/json');
		echo json_encode($hasil);
	}
}