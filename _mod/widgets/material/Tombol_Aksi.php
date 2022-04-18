<?php

/*
 * Demo widget
 */
class Tombol_Aksi extends Widget {
    protected $folder_template='template';
    public function display($data) {
        if ($data['params']['themes_mode']!=='default'){
			$this->folder_template=$data['params']['themes_mode'];
		}
        if (!isset($data['items'])) {
            $data['items'] = array('Home', 'About', 'Contact');
        }
        $data['rows']=$this->db->get('groups')->result_array();

        if ($data['params']['show_action_button'])
            $this->view($this->folder_template.'/tombol_aksi', $data);
    }
    
}