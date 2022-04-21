<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Cek_Data extends MY_Controller {

    function init($action='list'){
        $this->set_Tbl_Master(_TBL_CEK);
        $this->set_Field_Primary($this->tbl_master, 'id');
		$this->set_Sort_Table($this->tbl_master,'id');
		// $this->set_Where_Table(['tbl'=>$this->tbl_master, 'field'=>'type', 'op'=>'=', 'value'=>$this->nama]);
      

        $this->set_Open_Tab('Data Risk Event Library');

        $this->addField(['field'=>'id', 'show'=>false]);
        $this->addField(['field'=>'nama', 'show'=>false]);
        $this->addField(['field'=>'alamat', 'show'=>false]);
        $this->addField(['field'=>'tanggal', 'show'=>false]);

        $this->set_Close_Tab();

        // $this->set_Table_List($this->tbl_master,'id', 'ID');
        $this->set_Table_List($this->tbl_master,'nama', 'Nama');
		$this->set_Table_List($this->tbl_master,'alamat', 'Alamat');
		$this->set_Table_List($this->tbl_master,'tanggal', 'Tanggal');
        $this->set_Close_Setting();

        $configuration = [
			'show_title_header' => false,
			'content_title' =>'Risk Cause Library List'
		];
		return [
			'configuration'	=> $configuration
		];
    }

    function get_cause()
	{
		$id=intval($this->uri->segment(3));
		$data=$this->data->get_library($id, 2);
		$data['angka']="10";
		// $data['cbogroup']=$this->crud->combo_select(['id', 'library'])->combo_where('type', 2)->combo_where('active', 1)->combo_tbl(_TBL_LIBRARY)->get_combo()->result_combo();

		$result=$this->load->view('cause',$data,true);
		return $result;
	}

    function optionalButton($button, $mode){
		// if ($mode=='list'){
			// unset($button['delete']);
			// unset($button['print']);
			// unset($button['search']);

		

			// $button['print']['detail']['lap'] = [
			// 	'label' => 'Excel Register',
			// 	'color' => 'bg-green',
			// 	'id' => 'btn_lap',
			// 	'tag' => 'a',
			// 	'round' => ($this->configuration['round_button']) ? 'rounded-round' : '',
			// 	'icon' => 'icon-import',
			// 	'url' => '#!',

			// 	'attr' => ' target="" data-url="'.base_url(_MODULE_NAME_.'/export-lap/').'"',
			// 	'align' => 'left'
			// ];

			// $button['print']['detail']['lap-sum'] = [
			// 	'label' => 'Excel Register (Summary)',
			// 	'color' => 'bg-green',
			// 	'id' => 'btn_lap_sum',
			// 	'tag' => 'a',
			// 	'round' => ($this->configuration['round_button']) ? 'rounded-round' : '',
			// 	'icon' => 'icon-import',
			// 	'url' => '#!',

			// 	'attr' => ' target="" data-url="'.base_url(_MODULE_NAME_.'/export-lap-summary/').'"',
			// 	'align' => 'left'
			// ];
		// }

		return $button;
	}
}