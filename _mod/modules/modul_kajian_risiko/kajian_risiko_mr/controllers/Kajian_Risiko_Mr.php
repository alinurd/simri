<?php if( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Kajian_Risiko_Mr extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	function init( $action = 'list' )
	{

		$this->cboDept             = $this->get_combo_parent_dept();
		$this->cbo_status          = $this->crud->combo_value( [ 0 => 'DRAFT', 1 => 'SUBMIT' ] )->result_combo();
		$this->cbo_status_approval = $this->crud->combo_value( [ "waiting" => 'WAITING', "rejected" => 'REJECTED', "approved" => "APPROVED" ] )->result_combo();

		$this->set_Tbl_Master( _TBL_VIEW_KAJIAN_RISIKO );

		$this->set_Open_Coloums( 'Data Kajian Risiko' );
		$this->addField( [ 'field' => 'id', 'type' => 'int', 'show' => FALSE, 'size' => 4 ] );
		$this->addField( [ 'field' => 'owner_id', 'title' => 'Risk Owner', 'input' => 'combo', 'values' => $this->cboDept, 'search' => TRUE, "required" => TRUE ] );
		$this->addField( [ 'field' => 'name', 'title' => 'Nama Kajian Risiko', 'type' => 'string', 'search' => TRUE, "required" => TRUE ] );
		$this->addField( [ 'field' => 'request_date', 'type' => 'date', 'search' => TRUE, "required" => TRUE ] );
		$this->addField( [ 'field' => 'release_date', 'type' => 'date', 'search' => TRUE, "required" => TRUE ] );
		$this->addField( [ 'field' => 'status', 'title' => 'Status', "show" => FALSE, 'type' => 'int', 'input' => 'combo', 'values' => $this->cbo_status, 'default' => 0, 'size' => 40 ] );
		$this->addField( [ 'field' => 'status_approval', 'title' => 'Status Approval', "show" => FALSE, 'type' => 'string', 'input' => 'combo', 'values' => $this->cbo_status_approval, 'default' => 'waiting', 'size' => 40 ] );
		$this->addField( [ 'field' => 'link_dokumen_kajian', "title" => "Dokumen Self-Assessment" ] );

		$this->addField( [ 'field' => 'link_dokumen_pendukung', "title" => "Dokumen Pendukung" ] );
		$this->addField( [ 'field' => 'dokumen_mr', "show" => FALSE, "save" => FALSE ] );

		$this->set_Close_Coloums();

		$this->set_Field_Primary( $this->tbl_master, 'id' );
		$this->set_Join_Table( [ 'pk' => $this->tbl_master ] );

		$this->set_Sort_Table( $this->tbl_master, 'id' );
		$this->set_Where_Table( [ 'field' => 'status', 'value' => 1, 'op' => '=' ] );
		$this->set_Table_List( $this->tbl_master, 'owner_id', "Risk Owner" );
		$this->set_Table_List( $this->tbl_master, 'name', "Nama Kajian Risiko" );
		$this->set_Table_List( $this->tbl_master, 'request_date', "Tanggal Permintaan" );
		$this->set_Table_List( $this->tbl_master, 'release_date', "Tanggal Release" );
		$this->set_Table_List( $this->tbl_master, 'status', "Status", 0, "center" );
		$this->set_Table_List( $this->tbl_master, 'status_approval', "Status Approval", 0, "center" );
		$this->set_Table_List( $this->tbl_master, 'dokumen_mr', "Dokumen MR", 0, "center" );
		$this->set_Close_Setting();

		$this->set_Save_Table( _TBL_KAJIAN_RISIKO );

		$configuration = [
		 'show_title_header'  => FALSE,
		 'content_title'      => 'Kajian Risiko MR',
		 'show_action_button' => FALSE,
		 'show_column_action' => FALSE,
		 'type_action_button' => "",
		];
		return [
		 'configuration' => $configuration,
		];
	}

	function listBox_status( $field, $rows, $value )
	{
		$statusContent = "";
		switch( $value )
		{
			case 0:
				$statusContent = "<span class='btn btn-sm btn-block btn-danger' style='cursor:default'>DRAFT</span>";
				break;
			case 1:
				$statusContent = "<span class='btn btn-sm btn-block btn-success' style='cursor:default'>SUBMITTED</span>";
				break;
			case 2:
				$statusContent = "<span class='btn btn-sm btn-block btn-warning' style='cursor:default'>REVISI</span>";
				break;

			default:
				$statusContent = "";
				break;
		}
		return $statusContent;

	}

	function listBox_status_approval( $field, $rows, $value )
	{
		$statusContent = "";
		switch( $value )
		{
			case "review":
				$statusContent = "<span class='btn btn-sm btn-block btn-warning' style='cursor:default'>REVIEW</span>";
				break;
			case "rejected":
				$statusContent = "<span class='btn btn-sm btn-block btn-danger' style='cursor:default'>REJECTED</span>";
				break;

			case "approved":
				$statusContent = "<span class='btn btn-sm btn-block btn-success' style='cursor:default'>APPROVED</span>";
				break;

			default:
				$statusContent = "";
				break;
		}
		return $statusContent;

	}

	function listBox_dokumen_mr( $field, $rows, $value )
	{
		$filepath = base_url( "files/kajian_risiko_mr/" . $value );
		if( ! empty( $value ) )
		{
			return $value = ( file_exists( "files/kajian_risiko_mr/" . $value ) ) ? "<a href='{$filepath}'><i class='icon-file-text'></i></a>" : "";
		}

	}

	function optionalPersonalButton( $button, $row )
	{

		unset( $button["update"] );
		unset( $button["delete"] );
		unset( $button["view"] );

		$button['approval'] = [
		 'label' => 'Approval',
		 'id'    => 'btn-approval',
		 'class' => 'text-center btn btn-primary btn-sm',
		 'icon'  => 'icon-file-check2',
		 'url'   => base_url( $this->modul_name . "/approval/list/" ),
		 'attr'  => ' target="_self" ',
		 'align' => 'center',
		 ];
		return $button;
	}

	function approval( $action, $idkajian, $idregister = NULL )
	{
		if( $_POST )
		{
			$this->submitApproval( $this->input->post(), $action, $idkajian );
			$action = "list";
		}
		$content                    = "";
		$btn_view                   = "btn_default";
		$dataView["module_name"]    = $this->modul_name;
		$dataView["kajian_id"]      = $idkajian;
		$dataView["action"]         = $action;
		$dataView["headerRisk"]     = $this->db->get_where( _TBL_VIEW_KAJIAN_RISIKO, [ "id" => $idkajian, "active" => 1, "status" => 1 ] )->row_array();
		$dbObj                      = $this->db->where( [ "id" => $idkajian, "status" => 1 ] );
		$dataView["disabledSubmit"] = ( $dataView["headerRisk"]["status"] == 1 ) ? "disabled" : "";
		switch( $action )
		{
			case 'submit':
				$action = "submit";
				$actionForm = "submit";
				$btn_view = "btn_submit_approval";
				break;
			case 'list':
				$actionForm = "submit";
				$action = "list";
				break;
			default:
				redirect( "access-denied" );
				break;
		}
		$dataView["view"]                     = $action;
		$dataView["btn_view"]                 = $btn_view;
		$dataView["formUrl"]                  = base_url( $this->modul_name . "/" . __FUNCTION__ . "/" . $actionForm . "/" . $idkajian . "/" . $idregister );
		$dataView["btnEdit"]                  = base_url( $this->modul_name . "/" . __FUNCTION__ . "/edit/" . $idkajian . "/" );
		$dataView["btnDelete"]                = base_url( $this->modul_name . "/" . __FUNCTION__ . "/delete/" . $idkajian . "/" );
		$dataView["getfiledata"]              = $this->setDataViewApproval( $dbObj->get( _TBL_KAJIAN_RISIKO )->row_array() );
		$dataView["getfiledata"]["kajian_id"] = $idkajian;
		$content                              = $this->load->view( "approval", $dataView, TRUE );
		$configuration                        = [
		 'show_title_header'  => FALSE,
		 'show_action_button' => FALSE,
		   ];
		$this->default_display( [ 'content' => $content, 'configuration' => $configuration ] );
	}

	function submitApproval( $dataPost, $action, $idkajian )
	{

		$dataInsrt["status_approval"] = $dataPost["status_approval"];
		$dataInsrt["date_approval"]   = date( "Y-m-d H:i:s" );
		$dataInsrt["approved_by"]     = $this->ion_auth->get_user_name();
		$dataInsrt["updated_at"]      = date( "Y-m-d H:i:s" );
		$dataInsrt["updated_by"]      = $this->ion_auth->get_user_name();
		if( ! empty( $dataPost["status_approval"] ) && $dataPost["status_approval"] == "approved" )
		{
			$dataInsrt["tiket_terbit"] = date( "Y-m-d H:i:s" );
			$dataInsrt["release_date"] = ! empty( $dataPost["release_date_submit"] ) ? $dataPost["release_date_submit"] : "";
			// $dataInsrt["release_date"] = $this->setReleaseDateKajianRisiko( $dataInsrt )["result_tiket"];
			$dataInsrt["urutan_tiket"] = $this->setReleaseDateKajianRisiko( $dataInsrt )["urutan_tiket"];
		}
		else
		{
			$this->db->update( _TBL_KAJIAN_RISIKO, [ "status" => 2 ], [ "id" => $idkajian ] );
		}
		$this->sendEmailNotification( $dataPost, $idkajian );
		$kajianUpdateId = $this->db->update( _TBL_KAJIAN_RISIKO, $dataInsrt, [ "id" => $idkajian ] );
		if( $kajianUpdateId )
		{
			$dataInsertHistory = [
			 "id"               => generateIdString(),
			 "id_kajian_risiko" => $idkajian,
			 "status_approval"  => $dataPost["status_approval"],
			 "note"             => $dataPost["note"],
			 "created_at"       => date( "Y-m-d H:i:s" ),
			 "created_by"       => $this->ion_auth->get_user_name(),
			 "updated_at"       => date( "Y-m-d H:i:s" ),
			 "updated_by"       => $this->ion_auth->get_user_name(),
			];
			$statusInsHistory  = $this->db->insert( _TBL_KAJIAN_RISIKO_APPROVAL_HISTORY, $dataInsertHistory );
			if( $statusInsHistory && ! empty( $dataPost["send_notif_approval"] ) )
			{
				$this->sendEmailNotification( $dataPost, $idkajian );
			}
		}
		redirect( $this->modul_name . "/approval/list/" . $idkajian );

	}

	function setDataViewApproval( $dataView )
	{
		if( ! empty( $dataView ) )
		{
			$dataView["link_dokumen_pendukung"] = ( ! empty( $dataView["link_dokumen_pendukung"] ) && json_decode( $dataView["link_dokumen_pendukung"] ) ) ? json_decode( $dataView["link_dokumen_pendukung"], TRUE ) : $dataView["link_dokumen_pendukung"];

			$dataView["link_dokumen_kajian"] = ( ! empty( $dataView["link_dokumen_kajian"] ) && json_decode( $dataView["link_dokumen_kajian"] ) ) ? json_decode( $dataView["link_dokumen_kajian"], TRUE ) : $dataView["link_dokumen_kajian"];

			$dataView["file_assessmen"] = $this->db->get_where( _TBL_KAJIAN_RISIKO_FILE, [ "id_kajian_risiko" => $dataView["id"], "file_type" => "dokumen_kajian" ] )->result_array();
			$dataView["file_pendukung"] = $this->db->get_where( _TBL_KAJIAN_RISIKO_FILE, [ "id_kajian_risiko" => $dataView["id"], "file_type" => "dokumen_pendukung" ] )->result_array();


		}
		return $dataView;
	}

	function setReleaseDateKajianRisiko( $dataInsrt )
	{

		$getDatakajianByTiket = $this->db->get_where( _TBL_KAJIAN_RISIKO, [ "tiket_terbit" => date( "Y-m-d", strtotime( $dataInsrt["tiket_terbit"] ) ) ] )->num_rows() + 1;
		$dateRelaseDate       = date( 'Y-m-d', strtotime( $dataInsrt["tiket_terbit"] . " +{$getDatakajianByTiket} day" ) );
		if( date( "w", strtotime( $dateRelaseDate ) ) == "6" )
		{
			$getDatakajianByTiketFromWeekend = $getDatakajianByTiket + 2;
			$dateRelaseDate                  = date( 'Y-m-d', strtotime( $dataInsrt["tiket_terbit"] . " +{$getDatakajianByTiketFromWeekend} day" ) );
		}
		elseif( date( "w", strtotime( $dateRelaseDate ) ) == "0" )
		{
			$getDatakajianByTiketFromWeekend = $getDatakajianByTiket + 1;
			$dateRelaseDate                  = date( 'Y-m-d', strtotime( $dataInsrt["tiket_terbit"] . " +{$getDatakajianByTiketFromWeekend} day" ) );
		}

		$data["result_tiket"] = $dateRelaseDate;
		$data["urutan_tiket"] = $getDatakajianByTiket;
		return $data;
	}

	function sendEmailNotification( $postData, $idkajian )
	{

		$getTemplate   = $this->db->get_where( "il_template_email", [ "code" => "NOTIF08" ] )->row_array();
		$getDataKajian = $this->db->get_where( _TBL_VIEW_KAJIAN_RISIKO, [ "id" => $idkajian ] )->row_array();
		if( ! empty( $getDataKajian ) )
		{
			$getOfficerData = $this->db->get_where( _TBL_OFFICER, [ "owner_no" => $getDataKajian['owner_id'], "active" => 1 ] )->result_array();
			if( ! empty( $getOfficerData ) )
			{
				foreach( $getOfficerData as $kOff => $vOff )
				{
					if( ! empty( $vOff["email"] ) )
					{
						$getTemplate["content_html"] = str_replace( "[[OWNER]]", $getDataKajian['owner_name'], $getTemplate["content_html"] );
						$getTemplate["content_html"] = str_replace( "[[NOTE]]", $postData['note'], $getTemplate["content_html"] );
						$getTemplate["content_html"] = str_replace( "[[STATUS]]", $postData['status_approval'], $getTemplate["content_html"] );
						$content                     = $this->load->view( "email-notification", $getTemplate, TRUE );
						// $emailData['email']          = [ $vOff["email"] ];
						$emailData['email']   = [ "rifkyr.54321@gmail.com" ];
						$emailData['subject'] = $getTemplate["subject"] ?? "Notifikasi Kajian Risiko";
						$emailData['content'] = $content ?? "";
						$status               = Doi::kirim_email( $emailData );
						if( $status )
						{
							$this->crud->crud_table( _TBL_LOG_SEND_EMAIL );
							$this->crud->crud_type( 'add' );
							$this->crud->crud_field( 'type', 1, 'int' );
							$this->crud->crud_field( 'ref_id', $getDataKajian["id"], 'int' );
							$this->crud->crud_field( 'subject', $getDataKajian["name"], 'string' );
							$this->crud->crud_field( 'message', $emailData['subject'], 'string' );
							$this->crud->crud_field( 'ket', '', 'string' );
							$this->crud->crud_field( 'to', $vOff["email"], 'string' );
							$this->crud->process_crud();
						}
					}
				}
			}
		}
	}

	function getHistoryData()
	{
		if( ! $this->input->is_ajax_request() )
		{
			exit( 'No direct script access allowed' );
		}

		$getdatakajian              = $this->input->post( 'id_kajian' );
		$getdataHistory["dataview"] = $this->data->getDataHistoryKajian( $getdatakajian );
		if( ! empty( $getdataHistory["dataview"] ) )
		{
			foreach( $getdataHistory["dataview"] as $kView => $vView )
			{
				switch( $vView["status_approval"] )
				{
					case "review":
						$getdataHistory["dataview"][$kView]["status_approval"] = "<span class='btn btn-sm btn-block btn-warning disabled' style='cursor:default'>REVIEW</span>";
						break;
					case "rejected":
						$getdataHistory["dataview"][$kView]["status_approval"] = "<span class='btn btn-sm btn-block btn-danger disabled' style='cursor:default'>REJECTED</span>";
						break;

					case "approved":
						$getdataHistory["dataview"][$kView]["status_approval"] = "<span class='btn btn-sm btn-block btn-success disabled' style='cursor:default'>APPROVED</span>";
						break;

					default:
						$getdataHistory["dataview"][$kView]["status_approval"] = "<span class='btn btn-sm btn-block btn-default disabled' style='cursor:default'> - </span>";
						break;
				}
				;
			}
		}
		$result = $this->load->view( "ajax/ajax_history", $getdataHistory, TRUE );
		echo $result;
	}
}
