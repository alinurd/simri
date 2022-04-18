<?php

/*
 * Demo widget
 */
class Footer extends Widget {
    protected $folder_template='template';
    public function display($data) {
        if ($data['params']['themes_mode']!=='default'){
			$this->folder_template=$data['params']['themes_mode'];
        }
        if (!isset($data['items'])) {
            $data['items'] = array('Home', 'About', 'Contact');
        }
        $data['modal']=$this->load->view($this->folder_template.'/modal',[],true);
        $this->view($this->folder_template.'/footer', $data);
    }
    
}