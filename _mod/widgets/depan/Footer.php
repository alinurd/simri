<?php

/*
 * Demo widget
 */
class Footer extends Widget {
    protected $folder_template='depan';
    public function display($data) {
        if (!isset($data['items'])) {
            $data['items'] = array('Home', 'About', 'Contact');
        }
        $data['modal']=$this->load->view($this->folder_template.'/modal',[],true);
        $this->view($this->folder_template.'/footer', $data);
    }
    
}