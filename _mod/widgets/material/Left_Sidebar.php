<?php

/*
 * Demo widget
 */
class Left_Sidebar extends Widget {
    protected $user=[];
    protected $menu=[];
    protected $folder_template='template';
    public function display($data) {
		if ($data['params']['themes_mode']!=='default'){
			$this->folder_template=$data['params']['themes_mode'];
		}

        if (!isset($data['items'])) {
            $data['items'] = array('Home', 'About', 'Contact');
        }
        $this->config->load('configuration', true);

        $this->user=$this->session->userdata('data_user');
        $menu = $this->get_navigator();

        // dump($this->menu['kiri']);die();
        $item='';
        foreach($this->menu['kiri'] as $row){
            $item .= $this->_menu_kiri($row);
        }
        $html = '<ul class="nav nav-sidebar"  data-nav-type="accordion">';
        
        // $html .= '<li class="header">'.lang('msg_label_header_menu').'</li>';
        $html .= '<li class="nav-item">
                    <a href="'.base_url().'"  class="nav-link">
                    <i class="icon-meter-fast"></i> &nbsp;<span>Dashboard</span>
                    </a>
                </li>';
                
        $html .=$item;
        // $html .='<li class="nav-item" >
        //             <a href="'.base_url('auth/logout').'"  id="logout"  class="nav-link" style="color:#E6F260 !important;">
        //                 <i class="icon-enter3" ></i><span> Logout </span>
        //             </a>
        //         </li>';
        $html .='</ul>';

        $data['menu']=$html;
        $this->view($this->folder_template.'/left_sidebar', $data);
    }

    public function get_navigator(){
        $pos_menu = $this->config->item('pos_menu', 'configuration');
		// echo $this->tbl_modul;
		if (!$this->user['is_admin']){
			$sql = $this->db->select('menu_id, privilege')
					->distinct()
					->from(_TBL_GROUP_PRIVILEGE)
					->join(_TBL_USERS_GROUPS, _TBL_GROUP_PRIVILEGE.'.group_id = '._TBL_USERS_GROUPS.'.group_id')
					->where('user_id', $this->user['id'])
					->get();
			$rows = $sql->result_array();

			// Doi::dump($this->db->last_query());
			// die();
			$arr_menu=array();
			foreach($rows as $row){
				$privilege  = json_decode($row['privilege'], true);
				$sts=false;
				if (array_key_exists('read', $privilege)){
					$sts=$privilege['read'];
				}
				if ($sts)
					$arr_menu[]=$row['menu_id'];
			}
			// dump($arr_menu);die();
		}
		
		foreach($pos_menu as $menu){
			$this->db->select('*');
			$this->db->from(_TBL_MODUL);
			$this->db->where('active',1);
			$this->db->where('posisi',$menu);
			if (!$this->user['is_admin']){
				if (!$arr_menu)
					$arr_menu[]=0;
				$this->db->where_in('id',$arr_menu);
			}
			$this->db->order_by('urut');
			// die($this->db->get_compiled_select());
			$query=$this->db->get();
			$rows=$query->result_array();
			// die($this->db->last_query());
			$input=array();
			foreach($rows as $row){
                $input[] = array("id" => $row['id'], "title" => $row['title'], "slug" => $row['pid'], "nm_modul" => $row['nm_modul'], "pid" => $row['pid'], "icon" => $row['icon'], "posisi" => $row['posisi'], "urut" => $row['urut']);
			}
			if (count($input)>0){
				$this->menu[$menu] = _tree($input);
			}
        }
    }
    
    function _menu_kiri($ad) {
		if ($ad['nm_modul']=='#'){
			$url='#';
		}else{
			$url=base_url($ad['nm_modul']);
		}
		
		
		if ($ad['nm_modul']=='#'){
			$bahasa_mdl=lang('msg_mdl_'.url_title(strtolower($ad['title'])));
		}else{
			$bahasa_mdl=lang('msg_mdl_'.str_replace('-','_',$ad['nm_modul']));
		}
		
		if (empty($bahasa_mdl))
			$bahasa_mdl=$ad['title'];
		
		$icon_down='';
		$class='';
		if (array_key_exists('children', $ad)) {
			// $icon_down='<span class="fa fa-chevron-down"></span>';
			$icon_down='<span class="dcjq-icon"></span>';
			$class=" class='nav child_menu' data-toggle='dropdown' ";
		}
		
		$active ='';
		if ($ad['nm_modul']==_MODULE_NAME_)
			$active ='active';

		$icon='<i class="icon-grid3"></i> ';
		if (!empty($ad['icon']))
			$icon='<i class="'.$ad['icon'].'"></i> ';
		
		$isi = sprintf("<a class='nav-link %s' href='%s' title='%s'>%s %s %s</a>", $active, $url, $bahasa_mdl, $icon, '<span>' . $bahasa_mdl . '</span>', $icon_down);
		
		if (array_key_exists('children', $ad)) {	
            $html = "<li data-modul='".$ad['nm_modul']."' class='nav-item nav-item-submenu'>".$isi;
            $html .= '<ul class="nav nav-group-sub" data-id="'.$ad['pid'].'" data-parent="'.$ad['pid'].'">';
			foreach($ad['children'] as $row){
                $html .= $this->_menu_kiri($row);
			}
			$html .= "</ul>";
		}else{
            $html = "<li class='nav-item' data-modul='".$ad['nm_modul']."'>".$isi;
        }
		$html .= "</li>";
		return $html;
	}
    
}