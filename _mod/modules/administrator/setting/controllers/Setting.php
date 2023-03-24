<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Setting extends MY_Controller
{
	var $table = "";
	var $post = [];
	var $sts_cetak = false;
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('file');
	}

	function init($action = 'list')
	{
		$this->cboRobot = $this->crud->combo_value(['index' => 'index', 'follow' => 'follow', 'Noindex' => 'Noindex', 'Nofollow' => 'Nofollow', 'Noarchive' => 'Noarchive', 'Nosnippet' => 'Nosnippet', 'None' => 'None', 'NOODP' => 'NOODP', 'NOYDIR' => 'NOYDIR'])->noSelect()->result_combo();
		$this->barcode = $this->crud->combo_value(['angka' => 'Angka', 'huruf' => 'Huruf Besar', 'angkahuruf_upper' => 'Angka + Huruf Besar', 'angkahuruf' => 'Angka + Huruf'])->noSelect()->result_combo();

		$this->set_Tbl_Master(_TBL_PREFERENCE);

		$this->set_Open_Tab('General Setting');
		$this->addField(['field' => 'nama_kantor', 'required' => true, 'size' => 100]);
		$this->addField(['field' => 'alamat_kantor', 'input' => 'multitext', 'size' => 250]);
		$this->addField(['field' => 'telp_kantor']);
		$this->addField(['field' => 'fax_kantor']);
		$this->addField(['field' => 'email_kantor']);
		$this->addField(['field' => 'web_kantor']);
		$this->addField(['field' => 'jam_kantor']);
		$this->addField(['field' => 'map_kantor']);
		$this->addField(['field' => 'nama_pimpinan']);
		$this->addField(['field' => 'bahasa']);
		$this->addField(['field' => 'judul_atas']);
		$this->addField(['field' => 'judul_bawah', 'size' => 100]);
		$this->addField(['field' => 'image_login', 'input' => 'upload', 'path' => 'img', 'file_thumb' => false]);
		$this->addField(['field' => 'image_login_repeat', 'type' => 'int', 'input' => 'bool:switch']);
		$this->addField(['field' => 'logo_kantor', 'input' => 'upload', 'path' => 'img', 'file_thumb' => false]);
		$this->set_Close_Tab();
		$this->set_Open_Tab('Setting App');
		$this->addField(['field' => 'sts_app', 'type' => 'int', 'input' => 'bool:switch']);
		$this->addField(['field' => 'placeholder_tool', 'type' => 'int', 'input' => 'bool:switch']);
		$this->addField(['field' => 'help_tool', 'type' => 'int', 'input' => 'bool:switch']);
		$this->addField(['field' => 'help_popup', 'type' => 'int', 'input' => 'bool:switch']);
		$this->addField(['field' => 'show_list_photo', 'type' => 'int', 'input' => 'bool:switch']);
		$this->addField(['field' => 'type_action_button', 'type' => 'string', 'values' => ['list' => 'List', 'drop' => 'Dropdown']]);
		$this->addField(['field' => 'themes_mode', 'type' => 'string', 'values' => ['default' => 'Default', 'material' => 'Material']]);
		$this->addField(['field' => 'align_label', 'type' => 'string', 'values' => ['left' => 'Left', 'right' => 'right', 'center' => 'Center']]);
		$this->addField(['field' => 'round_button', 'type' => 'int', 'input' => 'bool:switch']);
		$this->addField(['field' => 'max_record_list', 'input' => 'updown', 'minrange' => 10, 'steprange' => 4, 'size' => 20]);
		$this->addField(['field' => 'warna_inherent', 'input' => 'color', 'line' => true, 'line-text' => 'Appearance', 'line-icon' => 'icon-grid']);
		$this->addField(['field' => 'warna_residual', 'input' => 'color']);
		$this->addField(['field' => 'warna_target', 'input' => 'color']);
		$this->addField(['field' => 'warna_mitigasi_selesai', 'input' => 'color', 'line' => true, 'line-text' => 'Appearance - Warna chart Mitigasi', 'line-icon' => 'icon-grid']);
		$this->addField(['field' => 'warna_mitigasi_belum_on_schedule', 'input' => 'color']);
		$this->addField(['field' => 'warna_mitigasi_belum_terlambat', 'input' => 'color']);
		$this->addField(['field' => 'warna_mitigasi_belum_dilaksanakan', 'input' => 'color']);
		$this->addField(['field' => 'warna_ketepatan_tepat', 'input' => 'color', 'line' => true, 'line-text' => 'Appearance - Warna chart Ketepatan', 'line-icon' => 'icon-grid', 'show' => false]);
		$this->addField(['field' => 'warna_ketepatan_terlambat', 'input' => 'color', 'show' => false]);
		$this->addField(['field' => 'warna_komitmen_lengkap', 'input' => 'color', 'line' => true, 'line-text' => 'Appearance - Warna chart Komitment', 'line-icon' => 'icon-grid']);
		$this->addField(['field' => 'warna_komitmen_tidak_lengkap', 'input' => 'color']);
		$this->addField(['field' => 'warna_komitmen_tidak_dibicarakan', 'input' => 'color']);
		$this->set_Close_Tab();
		$this->set_Open_Tab('Setting Notice & Email');
		$this->addField(['field' => 'send_notif', 'type' => 'int', 'input' => 'bool:switch', 'line' => true, 'line-text' => 'Email Server Setting', 'line-icon' => 'icon-users']);
		$this->addField(['field' => 'email_protocol']);
		$this->addField(['field' => 'email_smtp_host']);
		$this->addField(['field' => 'email_smtp_port']);
		$this->addField(['field' => 'email_smtp_user']);
		$this->addField(['field' => 'email_smtp_pass']);
		$this->addField(['field' => 'email_mailtype']);
		$this->addField(['field' => 'email_charset']);
		$this->addField(['field' => 'email_wordwrap']);
		$this->addField(['field' => 'tombol_test', 'title' => 'To', 'type' => 'free']);
		$this->addField(['field' => 'email_title', 'line' => true, 'line-text' => 'Email Notification Setting', 'line-icon' => 'icon-users']);
		$this->addField(['field' => 'email_admin']);
		$this->addField(['field' => 'forward_email_to_admin', 'input' => 'bool']);
		$this->addField(['field' => 'footer_email', 'type' => 'string', 'input' => 'html']);
		$this->set_Close_Tab();
		$this->set_Open_Tab('Sosial Media');
		$this->addField(['field' => 'sos_wa']);
		$this->addField(['field' => 'sos_fb']);
		$this->addField(['field' => 'sos_ig']);
		$this->addField(['field' => 'sos_linkedin']);
		$this->addField(['field' => 'sos_youtube']);
		$this->addField(['field' => 'sos_play_store']);
		$this->addField(['field' => 'sos_app_store']);
		$this->addField(['field' => 'sos_mobile_web']);
		$this->addField(['field' => 'sos_cs1', 'title' => 'Customer Case 1']);
		$this->addField(['field' => 'sos_cs2', 'title' => 'Customer Case 2']);
		$this->set_Close_Tab();
		$this->set_Open_Tab('Security');
		$this->addField(array('field' => 'pass_min', 'input' => 'updown', 'size' => 90));
		$this->addField(array('field' => 'pass_max', 'input' => 'updown', 'size' => 90));
		$this->addField(array('field' => 'pass_letter', 'input' => 'boolean', 'size' => 40));
		$this->addField(array('field' => 'pass_number', 'input' => 'boolean', 'size' => 40));
		$this->addField(array('field' => 'pass_lower', 'input' => 'boolean', 'size' => 40));
		$this->addField(array('field' => 'pass_upper', 'input' => 'boolean', 'size' => 40));
		$this->addField(array('field' => 'pass_symbol', 'input' => 'boolean', 'size' => 40));
		$this->addField(array('field' => 'notif_email', 'input' => 'boolean', 'size' => 40));
		$this->addField(array('field' => 'notif_email_waktu', 'size' => 10));
		$this->addField(array('field' => 'password_expr_sts', 'input' => 'boolean', 'size' => 40));
		$this->addField(array('field' => 'password_expr', 'size' => 10));
		$this->set_Close_Tab();
		$this->set_Open_Tab('SEO');
		$this->addField(['field' => 'meta_description', 'input' => 'multitext', 'size' => 300]);
		$this->addField(['field' => 'meta_keywords', 'input' => 'multitext', 'max' => 250]);
		$this->addField(['field' => 'meta_author', 'input' => 'multitext', 'max' => 250]);
		$this->addField(['field' => 'meta_viewport', 'default' => 'width=device-width, initial-scale=1.0', 'max' => 250]);
		$this->addField(['field' => 'meta_robots', 'input' => 'combo', 'search' => true, 'values' => $this->cboRobot, 'multiselect' => true]);
		$this->set_Close_Tab();

		$this->set_Field_Primary(_TBL_PREFERENCE, 'id', false);
		$this->set_Join_Table(['pk' => $this->tbl_master]);

		$this->data_fields['master'] = $this->tmp_data;

		if ($x = $this->input->post())
			$this->post = $this->input->post();
		elseif ($x = $this->session->userdata('_' . $this->modul_name . '_search_')) {
			$this->post = $this->session->userdata('_' . $this->modul_name . '_search_');
		}

		$this->set_Close_Setting();

		$configuration = [];
		return [
			'configuration'	=> $configuration
		];
	}

	public function index()
	{
		$this->_mode_ = 'edit';
		$this->__update(1);
	}

	function inputManualData()
	{
		$result = $this->data->get_data();
		return $result;
	}

	function inputBox_TOMBOL_TEST($mode, $field, $row, $value)
	{

		$content = '<div class="input-group" style="width:50%;">' . form_input('email_to', '', 'class="form-control"') . '
					<span class="input-group-append pointer" id="test_email">
						<span class="input-group-text"> Test Email </span>
						</span></div>';
		return $content;
	}

	function inputBox_META_ROBOTSx($mode, $field, $row, $value)
	{
		$value = explode(',', $value);
		$content = $this->set_box_input($field, $value);
		return $content;
	}

	function manualSave($id, $data, $old_data,  $mode)
	{
		$result = $this->data->save_data($id, $data, $old_data, $mode);
		//$this->data_config->set_Preference(1);
		return $result;
	}

	public function MANIPULATE_BUTTON_ACTION($tombol = [])
	{
		$this->_tombol['add'] = '';
		$this->_tombol['add_input'] = '';
		$this->_tombol['savequit'] = '';
		$this->_tombol['act_personal']['default']['edit'] = ['url' => base_url($this->_Snippets_['modul'] . '/reply'), 'label' => 'Reply'];
		$tbl = $this->_tombol;
		return $tbl;
	}

	function POST_UPDATE_REDIRECT_URL($url)
	{
		$url = base_url($this->_Snippets_['modul']);
		return $url;
	}

	function sent_email()
	{
		$p = $this->input->post();

		$dat['email'] = [$p['email_to']];
		$dat['subject'] = 'Test Email Simri Inalum';
		$dat['content'] = 'Ini adalah konten test dari aplikasi Simri Inalum';
		$dat['content_text'] = 'Ini adalah konten test dari aplikasi Simri Inalum';
		$dat['config'] = [
			'protocol' => $p['email_protocol'],
			'smtp_host' => $p['email_smtp_host'],
			'smtp_port' => $p['email_smtp_port'],
			'smtp_user' => $p['email_smtp_user'],
			'smtp_pass' => $p['email_smtp_pass'],
			'mailtype' => $p['email_mailtype'],
			'charset' => $p['email_charset'],
			'wordwrap' => $p['email_wordwrap'],
		];
		$x['combo'] = Doi::kirim_email($dat);
		header('Content-type: application/json');
		echo json_encode($x);
	}
}
