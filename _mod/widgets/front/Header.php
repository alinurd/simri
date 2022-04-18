<?php

/*
 * Demo widget
 */
class Header extends Widget {
    protected $folder_template='default';
    public function display($data) {
        $this->folder_template=$data['params']['front_themes_mode'];
        $dmenus=[0,1];
        foreach($dmenus as $menu){
            $data['menus'.$menu]=$this->get_menu($menu);
        }

        $rows=$this->db->where('status',1)->get(_TBL_BAHASA)->result_array();
        $data['bahasa']=$rows;
        $rows=$this->db->where('is_center', 1)->where('active', 1)->get(_TBL_STORE)->row_array();
        $data['store']=$rows;
        // dumps($data['menus1']);die();
        $this->view('template_front/'.$this->folder_template.'/header', $data);
    }

    public function list_product(){

        $rows=$this->db->where('active',1)->get(_TBL_VIEW_PRODUCT)->result_array();

        $product=[];
        foreach($rows as $row){
            $product[$row['kelompok'].'#'.$row['uri_title_category']][]=['id'=>$row['id'],'title'=>$row['product'],'uri'=>$row['uri_title'],'url'=>base_url('product/'.$row['uri_title'])];
        }

        return $product;
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
            }elseif ($row['kode']==4){
                $pages=$this->db->where('id', intval($row['param_text']))->get(_TBL_PRODUCT)->row();
                if ($pages)
                    $url.='product/'.$pages->uri_title;
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