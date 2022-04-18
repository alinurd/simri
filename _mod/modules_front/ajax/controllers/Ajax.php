<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends MY_Frontend {
	public function __construct()
	{
        parent::__construct();
	}

	function list_price(){
		$this->lang->load('product');
		$id=$this->input->get('id');
		$kel=$this->input->get('kel');
		$rows = $this->db->where('id', $id)->get(_TBL_VIEW_PRODUCT)->row_array();

        if ($rows){
			$rows['param'] = $this->_set_data($rows)->_set_params(['photo', 'param_meta', 'param_lang'])->_row_array()->convert_data_param()->convert_data_lang(['note', 'instruction'])->_build();
		}
		$rows['m_input'] = explode(',',$rows['m_input']);
		$m_input = array_map(function($val){
			$dont=['laminated_price','spanram_price','price_sheet'];
			$val=str_replace('-','_', $val);
			if (!in_array($val,$dont))
			return $val.'_id';
			else
			return $val;
		},$rows['m_input']);
		$m_input = implode(',',$m_input);
		$data['info'] = $rows;
		$data['kel'] = $kel;
		$data['field'] = $this->db->where('product_id', $id)->order_by($m_input)->get(_TBL_VIEW_PRODUCTPRICE)->result_array();
		$hasil['combo']=$this->load->view('price', $data, true);
		echo json_encode($hasil);
	}

	function get_price(){
		$post=$this->input->get();

		$rows = $this->db->select('id, product, uri_title, m_input')->where('id', $post['product_id'])->get(_TBL_VIEW_PRODUCT)->row_array();
		$rows['m_input'] = explode(',',$rows['m_input']);

		$this->db->where('product_id',$post['product_id']);
		$jml=count($rows['m_input'])-1;
		$target='';
		foreach($rows['m_input'] as $key=>$row){
			$dont=['laminated_price','spanram_price','price_sheet'];
			$val=str_replace('-','_', $row);

			if ($key<=$post['no']){
				if (!in_array($val,$dont)){
					$x= $val.'_id';
					$this->db->where($val,$post[$val]);
				}
			}else{
				if (!in_array($val,$dont)){
					$target=$rows['m_input'][$key];
				}
				break;
			}
		}

		$cbo='';
		$price=0;
		$pricetext='';
		$id=0;
		$field = $this->db->get(_TBL_VIEW_PRODUCTPRICE)->result_array();
		if ($target){
			$fld=$target;
			$x=str_replace('-','_',$fld);
			$combo[0]=$this->lang->line('cbo_select');
			foreach($field as $ros){
				$combo[$ros[$x]]=$ros[$x];
			}

			foreach($combo as $ros){
				$cbo.='<option value="'.$ros.'">'.$ros.'</option>';
			}
		}else{
			if (count($field)==1){
				$price=floatval($field[0]['mall_price']);
				$pricetext=number_format($field[0]['mall_price']);
				$id=$field[0]['id'];
			}
		}
		$pic='';
		$nama='';
		$field = $this->db->where('kelompok',$post['pilih'])->where('data',$post[$post['pilih']])->get(_TBL_COMBO)->row_array();
		if ($field){
			if (!empty($field['param_other'])){
				$pic = file_url($field['param_other']);
				$nama = $field['data'];
			}
		}

		$hasil['sql']=$this->db->last_query();
		$hasil['price']=$price;
		$hasil['priceText']=$pricetext;
		$hasil['id']=$id;
		$hasil['combo']=$cbo;
		$hasil['target']=str_replace('-','_',$target);
		$hasil['info']=$rows;
		$hasil['pic']=$pic;
		$hasil['nama']=$nama;
		// dumps($hasil);die();
		echo json_encode($hasil);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */