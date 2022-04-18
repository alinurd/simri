<?php

/*
 * Demo widget
 */
class Footer extends Widget {
    protected $folder_template='default';
    public function display($data) {
        $this->folder_template=$data['params']['front_themes_mode'];

        if (!isset($data['items'])) {
            $data['items'] = array('Home', 'About', 'Contact');
        }
        $dmenus=[2];
        foreach($dmenus as $menu){
            $data['menus'.$menu]=$this->get_menu($menu);
        }
        $this->view('template_front/'.$this->folder_template.'/footer', $data);
    }
    
    function get_menu($param){
        $rows=$this->db->where('param_int', $param)->where('kelompok', 'menu')->where('active',1)->order_by('param_int, urut')->get(_TBL_COMBO)->result_array();
        $result=[];
        $input=[];
        foreach($rows as $row){
            $url=base_url();
            $detail='';
            if ($row['kode']>=1 and $row['kode']<=3){
                $parent='pages/';
                if ($row['kode']==1){
                    $parent='news/';
                }elseif($row['kode']==2){
                    $parent='blog/';
                }
                $pages=$this->db->where('id', intval($row['param_text']))->get(_TBL_NEWS)->row();
                if ($pages)
                    $url.=$parent.$pages->uri_title;
            }elseif ($row['kode']==10){
                $url.=$row['param_text'];
            }elseif ($row['kode']==50){
                $cls=str_replace('-', '_',strtolower($row['param_text']));
                $detail=$this->$cls();
            }

            $row['url']=$url;
            $input[] = array("id" => $row['id'], "title" => $row['data'], "slug" => $row['pid'], "pid" => $row['pid'], "urut" => $row['urut'], "active" => $row['active'], 'url'=>$url, 'detail'=>$detail);
        }
        $result = _tree($input);
        return $result;
    }

}