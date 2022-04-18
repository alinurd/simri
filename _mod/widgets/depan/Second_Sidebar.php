<?php

/*
 * Demo widget
 */
class Second_Sidebar extends Widget {
    protected $folder_template='template';
    public function display($data) {
        if ($data['params']['themes_mode']!=='default'){
			$this->folder_template=$data['params']['themes_mode'];
		}
        if (!isset($data['items'])) {
            $data['items'] = array('Home', 'About', 'Contact');
        }

        $this->view($this->folder_template.'/second_sidebar', $data);
    }
    
}