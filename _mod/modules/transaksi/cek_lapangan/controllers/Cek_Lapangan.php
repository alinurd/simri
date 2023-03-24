<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Cek_Lapangan extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('file');
		$this->kd_sequence = 'TK';
	}

	function init($action = 'list')
	{

		$this->cboPosisi = $this->crud->combo_select(['id', 'note'])->combo_where(['active' => 1])->combo_tbl(_TBL_TUTUPAN_LAHAN)->combo_sort('urut')->get_combo()->result_combo();
		$this->cboUser = $this->crud->combo_select(['id', 'real_name'])->combo_where(['active' => 1])->combo_tbl(_TBL_USERS)->combo_sort('real_name')->get_combo()->result_combo();
		$this->cboSts = $this->crud->combo_value(['1' => 'Proses', '2' => 'Valid', '3' => 'Tidak Valid'])->result_combo();

		$this->set_Tbl_Master(_TBL_CEK_LAPANGAN);
		$this->set_Open_Tab(lang(_MODULE_NAME_REAL_ . '_title'));

		$this->addField(['field' => 'id', 'show' => false]);
		$this->addField(['field' => 'staft_id', 'readonly' => false, 'save' => false, 'title' => 'Petugas', 'type' => 'int', 'input' => 'combo', 'search' => true, 'values' => $this->cboUser]);
		$this->addField(['field' => 'tutupan_lahan_id', 'title' => 'Class Tutupan Lahan', 'type' => 'int', 'input' => 'combo', 'search' => true, 'values' => $this->cboPosisi]);
		$this->addField(['field' => 'description', 'required' => true, 'input' => 'multitext', 'size' => 500]);
		$this->addField(['field' => 'photo', 'readonly' => true, 'show' => false, 'save' => false]);
		$this->addField(['field' => 'view_photo', 'type' => 'free']);
		$this->addField(['field' => 'lat', 'readonly' => true, 'save' => false]);
		$this->addField(['field' => 'lng', 'readonly' => true, 'save' => false]);
		$this->addField(['field' => 'lap_no', 'readonly' => false, 'save' => false]);
		$this->addField(['field' => 'status_id', 'title' => 'Status', 'type' => 'int', 'input' => 'combo', 'search' => true, 'values' => $this->cboSts]);
		$this->addField(['field' => 'approval_date', 'show' => false]);
		$this->addField(['field' => 'approval_by', 'show' => false]);

		$this->set_Close_Tab();
		$this->set_Field_Primary($this->tbl_master, 'id');

		$this->set_Sort_Table($this->tbl_master, 'created_at', 'desc');

		$this->set_Table_List($this->tbl_master, 'staft_id');
		$this->set_Table_List($this->tbl_master, 'tutupan_lahan_id');
		$this->set_Table_List($this->tbl_master, 'lat');
		$this->set_Table_List($this->tbl_master, 'status_id', '', 0, 'center');
		$this->set_Table_List($this->tbl_master, 'photo', '', 0, 'center');
		$this->set_Table_List($this->tbl_master, 'approval_by');
		$this->set_Table_List($this->tbl_master, 'approval_date', '', 10);

		$this->set_Close_Setting();
		$configuration = [
			'tab_list'	=> false,
			'content_title'	=> '<i class="icon-comment-discussion"></i> Cek Lapangan',
		];
		return [
			'configuration'	=> $configuration
		];
	}

	function listBox_PHOTO($field, $rows, $value)
	{
		$value = '<i class="fa fa-download pointer text-primary" title="klik untuk mendownload file image dan informasi cek lapangan"></i>';

		return $value;
	}


	function listBox_STATUS_ID($field, $rows, $value)
	{
		$x = $value;
		if ($value == 1) {
			$class = "badge bg-success-300";
		} elseif ($value == 2) {
			$class = "badge bg-blue";
		} elseif ($value == 3) {
			$class = "badge bg-danger";
		} else {
			$class = "lbadge bg-grey-400";
			$x = 0;
		}
		$value = '<span class="' . $class . '">' . $this->cboSts[$x] . '</span>';

		return $value;
	}

	function inputBox_VIEW_PHOTO($mode, $field, $row, $value)
	{
		$content = '';
		if ($mode == 'edit' || $mode == 'view') {
			$photo = json_decode($row['photo'], true);
			foreach ($photo as $x) {
				$content .= $x['name'] . '<br/>';
				foreach ($x['photos'] as $y) {
					if ($y['photo_path'] !== null) {
						$class = ['class' => 'rounded-circle detail-img pointer', 'data-file' => $y['photo_path'], 'data-path' => 'file'];
						$content .= img($y['photo_path'], 'file', $class, 'tiny');
					}
				}
				$content .= '<br/><hr>';
			}
		}
		// $content = $this->set_box_input($field, $value);
		// $content = $row['photo'];
		return $content;
	}


	function afterSave($id, $data, $data_old, $mode)
	{

		if ($mode == 'edit') {
			if ($data['status_id'] !== $data_old['status_id']) {
				$rows = $this->db->where('id', $data['staft_id'])->get(_TBL_USERS)->row_array();
				$link = '<a href="' . base_front_url('cek-laporan/' . $id) . '">disini</a>';
				$content_replace = ['[[nama]]' => $rows['real_name'], '[[nolap]]' => $data['lap_no'], '[[status]]' => $this->cboSts[$data['status_id']], '[[disini]]' => $link, '[[footer]]' => $this->_preference_['footer_email']];

				$datasOutbox = [
					'recipient' => [$rows['email']],
					'kel_id' => 4,
				];
				if ($this->_preference_['send_notif'] == 1) {
					$this->load->library('outbox');
					$this->outbox->setTemplate('EML-LAP-02');
					$this->outbox->setParams($content_replace);
					$this->outbox->setDatas($datasOutbox);
					$this->outbox->send();
				}
			}
		}

		return true;
	}
}
