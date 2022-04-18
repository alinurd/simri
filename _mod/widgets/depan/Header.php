<?php

/*
 * Demo widget
 */
class Header extends Widget {
    protected $folder_template='depan';
    public function display($data) {
        if (!isset($data['items'])) {
            $data['items'] = array('Home', 'About', 'Contact');
        }
        $this->view($this->folder_template.'/header', $data);
    }
    
}