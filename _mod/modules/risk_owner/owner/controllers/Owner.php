<?php if( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

//NamaTbl, NmFields, NmTitle, Type, input, required, search, help, isiedit, size, label 
//$tbl, 'id', 'id', 'int', false, false, false, true, 0, 4, 'l_id'

class Owner extends MY_Controller
{
	var $post = array();
	public function __construct()
	{
		parent::__construct();
	}

	function init( $action = 'list' )
	{
		$this->cbo_approval = $this->crud->combo_select( [ 'id', 'data' ] )->combo_where( 'kelompok', 'level-approval' )->combo_where( 'active', 1 )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();

		$this->set_Tbl_Master( _TBL_OWNER );

		$this->set_Open_Tab( 'Data Risk Owner / Department' );
		$this->addField( [ 'field' => 'id', 'type' => 'int', 'show' => FALSE, 'size' => 4 ] );
		$this->addField( [ 'field' => 'pid', 'input' => 'combo', 'values' => $this->get_combo_parent_dept(), 'search' => TRUE, 'size' => 20, "title" => "Risk Owner / Department" ] );
		$this->addField( [ 'field' => 'owner_code', 'search' => TRUE, 'size' => 50 ] );
		$this->addField( [ 'field' => 'owner_name', 'required' => TRUE, 'search' => TRUE, 'size' => 50 ] );
		$this->addField( [ 'field' => 'note', 'input' => 'multitext', 'size' => 500 ] );
		$this->addField( [ 'field' => 'level_approval', 'type' => 'string', 'input' => 'combo', 'values' => $this->cbo_approval, 'multiselect' => TRUE ] );
		$this->addField( [ 'field' => 'urut', 'input' => 'updown', 'default' => 1, 'size' => 50 ] );
		$this->addField( [ 'field' => 'active', 'input' => 'boolean', 'default' => 1, 'size' => 20 ] );
		$this->set_Close_Tab();

		$this->set_Field_Primary( $this->tbl_master, 'id' );
		$this->set_Join_Table( [ 'pk' => $this->tbl_master ] );

		$this->set_Sort_Table( $this->tbl_master, 'id' );

		$this->set_Table_List( $this->tbl_master, 'pid' );
		$this->set_Table_List( $this->tbl_master, 'owner_name' );
		$this->set_Table_List( $this->tbl_master, 'urut' );
		$this->set_Table_List( $this->tbl_master, 'active', '', 7, 'center' );

		$this->set_Close_Setting();

		$configuration = [
		 'show_title_header' => FALSE,
		];
		return [
		 'configuration' => $configuration,
		];
	}

	function content( $ty = 'detail' )
	{
		$this->_css_[] = 'jquery.nestable.css';
		$this->_js_[]  = 'plugins/nestable/jquery.nestable.js';
		$content       = $this->menu_posisi();
		return $content;
	}

	function optionalButton( $button, $mode )
	{
		if( $mode == 'list' )
		{
			unset( $button['delete'] );
			unset( $button['print'] );
			unset( $button['search'] );

			$button['save'] = [
			 'label' => $this->lang->line( 'btn_save' ),
			 'color' => 'bg-success-300',
			 'id'    => 'btn_save_modul',
			 'name'  => 'Save',
			 'value' => 'Simpan',
			 'type'  => 'submit',
			 'round' => ( $this->configuration['round_button'] ) ? 'rounded-round' : '',
			 'icon'  => 'icon-floppy-disk',
			 'url'   => base_url( _MODULE_NAME_ . '/save-modul/' ),
			];
		}
		return $button;
	}

	function menu_posisi()
	{

		$data['field'] = $this->data->get_data_posisi_menu();
		$outpute       = '';
		foreach( $data['field'] as $row )
		{
			$outpute .= $this->buildItem( $row );
		}

		$data['tree']        = $outpute;
		$data['source_tree'] = json_encode( $data['field'] );
		$tombol              = [];//$this->_get_list_action_button();
		$data['action']      = $tombol;
		return $this->load->view( 'dept', $data, TRUE );
	}

	function buildItem( $ad )
	{
		$o = img( $ad['photo'], 'file', $class = [ 'class' => 'rounded-circle detail-img pointer', 'data-file' => $ad['photo'], 'data-path' => 'file' ], 'tiny' );
		if( empty( $ad['photo'] ) )
			$o = '';
		$nama = '';
		if( ! empty( $ad['name'] ) )
			$nama = $ad['name'] . " - ";
		$icon = '';
		$del  = '';
		if( $ad['status'] == 0 )
		{
			$icon = ' &nbsp;<i class="fa fa-times-circle text-danger"></i> ';
		}

		if( ! array_key_exists( 'children', $ad ) )
		{
			$del = " | <a href='" . base_url( $this->modul_name . '/delete/' . $ad['id'] ) . "' class='delete_modul delete text-danger'> <i class='fa fa-trash'></i></a>";
		}
		$code = '';
		if( ! empty( $ad['code'] ) )
			$code = '<span class="text-danger">' . $ad['code'] . '</span> - ';
		$sts_approval = '';
		$sts_approval = $ad['approval'];

		$html = "<li class='dd-item dd3-item' data-id='" . $ad['id'] . "'>";
		$html .= "<div class='dd-handle dd3-handle'></div><div class='dd3-content'>" . $code . $ad['title'] . $icon . $sts_approval . "  <span class='pull-right' style='margin-top:-5px;'>" . $nama . " | <a href='" . base_url( $this->modul_name . '/edit/' . $ad['id'] ) . "' class='edit_modul'><i class='fa fa-pencil-square-o'></i></a>" . $del . "</span></div>";
		if( array_key_exists( 'children', $ad ) )
		{
			$html .= "<ol class='dd-list'>";
			foreach( $ad['children'] as $row )
			{
				$html .= $this->buildItem( $row );
			}
			$html .= "</ol>";
		}
		$html .= "</li>";
		return $html;
	}

	function save_modul()
	{
		$post   = $this->input->post();
		$result = $this->data->simpan_data( $post );
		header( 'Content-type: application/json' );
		echo json_encode( [] );
	}

	function afterSave( $id, $data, $old, $mode )
	{
		$rows  = $this->db->where( 'id', $data['pid'] )->get( _TBL_OWNER )->row();
		$level = 0;
		$urut  = 1;
		if( $rows )
		{
			$level = $rows->level + 1;
			$rows  = $this->db->select( 'max(urut) as jml' )->where( 'pid', $rows->id )->get( _TBL_OWNER )->row();
			if( $rows )
				$urut = $rows->jml + 1;
		}
		$this->crud->crud_table( _TBL_OWNER );
		$this->crud->crud_type( 'edit' );
		$this->crud->crud_field( 'level', $level, 'int' );
		$this->crud->crud_field( 'urut', $urut, 'int' );
		$this->crud->crud_where( [ 'field' => 'id', 'value' => $id ] );
		$this->crud->process_crud();
		return TRUE;
	}

	function checkBeforeSave( $data, $old_data, $mode = 'add' )
	{
		$rows = "";
		if( $mode == "add" )
		{
			$rows = $this->db->where( 'owner_code', $data['owner_code'] )->get( _TBL_OWNER )->row();
		}


		if( $rows )
		{
			$this->logdata->set_error( "Kode owner sudah ada!" );
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	function sendnotificationMitigasi()
	{
		$officerDataemail = [];
		$statusSend       = FALSE;
		$queryRcsa        = 'select * from il_rcsa_mitigasi where batas_waktu = date_format(Date(now())+reminder_email,"%Y-%m-%d") group by id';
		$getRcsaPic       = $this->db->query( $queryRcsa )->result_array();
		if( ! empty( $getRcsaPic ) )
		{
			$getTemplate = $this->db->get_where( "il_template_email", [ "code" => "NOTIF07" ] )->row_array();
			foreach( $getRcsaPic as $keyPic => $valuePic )
			{
				$getId = ( ! empty( $valuePic["penanggung_jawab_id"] ) && json_decode( $valuePic["penanggung_jawab_id"] ) ) ? implode( ",", json_decode( $valuePic["penanggung_jawab_id"] ) ) : $valuePic["penanggung_jawab_id"];

				$getOfficer = $this->db->query( "select b.email from il_owner a inner join il_officer b on a.id = b.owner_no where a.id in($getId)" )->result_array();
				if( ! empty( $getOfficer ) )
				{
					foreach( $getOfficer as $kOff => $vOff )
					{
						$officerDataemail[$kOff] = $vOff["email"];
					}
				}
				$getTemplate["content_html"] = str_replace( "[[MITIGASI]]", $valuePic['mitigasi'], $getTemplate["content_html"] );
				$getTemplate["content_html"] = str_replace( "[[day]]", $valuePic['reminder_email'], $getTemplate["content_html"] );
				$content                     = $this->load->view( "email-notification", $getTemplate, TRUE );
				$emailData['email']          = $officerDataemail;
				$emailData['subject']        = $getTemplate["subject"] ?? "Reminder Due Date Mitigasi {$valuePic["mitigasi"]}";
				$emailData['content']        = $content ?? "";

				if( $this->preference['send_notif'] == 1 )
				{
					$status = Doi::kirim_email( $emailData );
					if( $status )
					{
						$insertOutbox["sender"]       = json_encode( [ $this->preference['email_smtp_user'], $this->preference['email_title'] ] );
						$insertOutbox["recipient"]    = json_encode( $officerDataemail );
						$insertOutbox["subject"]      = $emailData['subject'];
						$insertOutbox["message"]      = $getTemplate["content_html"];
						$insertOutbox["subject"]      = $emailData['subject'];
						$insertOutbox["sent_at"]      = date( "Y-m-d H:i:s" );
						$insertOutbox["is_sent"]      = 1;
						$insertOutbox["scheduled_at"] = date( "Y-m-d H:i:s" );
						$insertOutbox["created_at"]   = date( "Y-m-d H:i:s" );
						$insertOutbox["updated_at"]   = date( "Y-m-d H:i:s" );
						$this->db->insert( _TBL_OUTBOX, $insertOutbox );

						$this->crud->crud_table( _TBL_LOG_SEND_EMAIL );
						$this->crud->crud_type( 'add' );
						$this->crud->crud_field( 'type', 1, 'int' );
						$this->crud->crud_field( 'ref_id', $valuePic["id"], 'int' );
						$this->crud->crud_field( 'subject', $valuePic["mitigasi"], 'string' );
						$this->crud->crud_field( 'message', $emailData['subject'], 'string' );
						$this->crud->crud_field( 'ket', 'Send Email Notif Reminder Due Date Rcsa Mitigasi', 'string' );
						$this->crud->crud_field( 'to', json_encode( $officerDataemail ), 'string' );
						$this->crud->process_crud();
					}
				}
			}
		}
	}
}
