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
		//  'type_action_button' => "",
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

	function listBox_release_date( $field, $rows, $value )
	{
		return ( ! empty( $value ) && $value != "0000-00-00" ) ? date( "Y-m-d", strtotime( $value ) ) : "";
	}

	function listBox_request_date( $field, $rows, $value )
	{
		return ( ! empty( $value ) && $value != "0000-00-00" ) ? date( "Y-m-d", strtotime( $value ) ) : "";
	}

	function optionalPersonalButton( $button, $row )
	{

		unset( $button["update"] );
		unset( $button["delete"] );
		unset( $button["view"] );

		$button['approval'] = [
		 'label' => 'Review',
		 'id'    => 'btn-approval',
		 'class' => 'text-center text-warning',
		 'icon'  => 'icon-file-check2',
		 'url'   => base_url( $this->modul_name . "/approval/list/" ),
		 'attr'  => ' target="_self" ',
		 'align' => 'center',
		 ];

		$button['risk-register'] = [
		'label' => 'Risk Register',
		'id'    => 'btn-kajian-risk-register',
		'class' => 'text-center text-primary',
		'icon'  => 'icon-file-upload2',
		'url'   => base_url( $this->modul_name . "/register/propose/" ),
		'attr'  => ' target="_self" ',
		'align' => 'center',
		];

		$button['history'] = [
		 'label' => 'History',
		 'id'    => 'btn-history',
		 'class' => 'text-warning',
		 'icon'  => 'icon-history',
		 'url'   => base_url( $this->modul_name . "/history/" ),
		 'attr'  => ' target="_self" ',
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
		$dataView["headerRisk"]     = $this->db->get_where( _TBL_VIEW_KAJIAN_RISIKO, [ "id" => $idkajian, "active" => 1, "status !=" => 0 ] )->row_array();
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
			$update_status = $this->db->update( _TBL_KAJIAN_RISIKO, [ "status" => 2 ], [ "id" => $idkajian ] );
		}
		$kajianUpdateId = $this->db->update( _TBL_KAJIAN_RISIKO, $dataInsrt, [ "id" => $idkajian ] );
		if( $kajianUpdateId )
		{
			$dataInsertHistory = [
			 "id"               => generateIdString(),
			 "id_kajian_risiko" => $idkajian,
			 "status_kajian"    => ( ! empty( $update_status ) && $update_status ) ? 2 : 1,
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
						$emailData['email']   = [ "rifkyr.personal@gmail.com" ];
						$emailData['subject'] = $getTemplate["subject"] ?? "Notifikasi Kajian Risiko";
						$emailData['content'] = $content ?? "";
						$status               = Doi::kirim_email( $emailData );
						if( $status == "success" )
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

	function register( $action, $idkajian, $idregister = NULL )
	{
		if( $_POST )
		{
			$this->submitregister( $this->input->post(), $action, $idkajian, $idregister );
			$action = "list";
		}
		$content                 = "";
		$btn_view                = "btn_default";
		$dataView["module_name"] = $this->modul_name;
		$dataView["kajian_id"]   = $idkajian;
		$dataView["action"]      = $action;
		$dataView["headerRisk"]  = $this->db->get_where( _TBL_VIEW_KAJIAN_RISIKO, [ "id" => $idkajian, "active" => 1 ] )->row_array();

		// if( $action == "submit" && $dataView["headerRisk"]["status"] != 1 )
		// {
		// 	$this->db->update( _TBL_KAJIAN_RISIKO, [ "status" => 1, "date_submit" => date( "Y-m-d H:i:s" ), "updated_at" => date( "Y-m-d H:i:s" ), "updated_by" => $this->ion_auth->get_user_name(), "status_approval" => "review" ], [ "id" => $idkajian ] );
		// 	$this->proposeRisikoHistory( $idkajian );
		// 	$dataView["headerRisk"]["status"] = 1;
		// 	$this->session->set_flashdata( 'message_crud', "Berhasil Submit Data {$dataView["headerRisk"]['name']} !" );
		// }

		$dataView["disabledSubmit"]  = ( $dataView["headerRisk"]["status"] == 1 ) ? "disabled" : "";
		$getLevelMapImpact           = $this->db->get_where( _TBL_LEVEL, [ "active" => 1, "category" => "impact" ] )->result_array();
		$getLevelMapLikelihood       = $this->db->get_where( _TBL_LEVEL, [ "active" => 1, "category" => "likelihood" ] )->result_array();
		$dbObj                       = $this->db->where( [ "id_kajian_risiko" => $idkajian ] );
		$dataView["mitigasiPicData"] = $this->cboDept;
		switch( $action )
		{
			case 'create':
				$dbObj->where( [ "id" => $idregister ] );
				$dataView["levelImpact"] = $getLevelMapImpact;
				$dataView["levelLikelihood"] = $getLevelMapLikelihood;
				$action = "form";
				$actionForm = "create";
				break;
			case 'edit':
				$dbObj->where( [ "id" => $idregister ] );
				$dataView["levelImpact"] = $getLevelMapImpact;
				$dataView["levelLikelihood"] = $getLevelMapLikelihood;
				$action = "form";
				$actionForm = "edit";
				break;
			case 'delete':
				$resultDelete = $this->db->query( "delete a,b from il_kajian_risiko_register a left join il_kajian_risiko_mitigasi b on a.id = b.id_kajian_risiko_register where a.id ='{$idregister}'" );
				$action = "list";
				$actionForm = "delete";
				break;
			case 'propose':
				$action = "propose";
				$actionForm = "propose";
				$btn_view = "btn_propose";
				$dataView["mapData"] = $this->data->getRowMapData( $idkajian );
				$this->db->where( [ "id_kajian_risiko" => $idkajian ] );
				break;
			// case 'submit':
			// 	redirect( $this->modul_name );
			// 	$action = "propose";
			// 	$actionForm = "propose";
			// 	$btn_view = "btn_propose";
			// 	$dataView["mapData"] = $this->data->getRowMapData( $idkajian );
			// 	$this->db->where( [ "id_kajian_risiko" => $idkajian ] );
			// 	break;
			default:
				$actionForm = "";
				$action = "list";
				break;
		}
		$dataView["view"]      = $action;
		$dataView["btn_view"]  = $btn_view;
		$dataView["formUrl"]   = base_url( $this->modul_name . "/" . __FUNCTION__ . "/" . $actionForm . "/" . $idkajian . "/" . $idregister );
		$dataView["btnEdit"]   = base_url( $this->modul_name . "/" . __FUNCTION__ . "/edit/" . $idkajian . "/" );
		$dataView["btnDelete"] = base_url( $this->modul_name . "/" . __FUNCTION__ . "/delete/" . $idkajian . "/" );
		$dataView["register"]  = $this->setDataViewRegister( $dbObj->get( _TBL_VIEW_KAJIAN_RISIKO_REGISTER )->result_array() );
		if( $actionForm == "edit" )
		{
			$dataView["mitigasi"] = $this->db->get_where( _TBL_KAJIAN_RISIKO_MITIGASI, [ "id_kajian_risiko_register" => $idregister ] )->result_array();
			if( ! empty( $dataView["mitigasi"] ) )
			{
				foreach( $dataView["mitigasi"] as $kgetMit => $vgetMit )
				{
					if( ! empty( $vgetMit["pic"] ) )
					{
						$setPicDataSelect[$kgetMit] = json_decode( $vgetMit["pic"] );
					}
				}
				$dataView["setPicSelect"] = json_encode( $setPicDataSelect );
			}
		}
		$content       = $this->load->view( "register", $dataView, TRUE );
		$configuration = [
		 'show_title_header'  => FALSE,
		 'show_action_button' => FALSE,
		   ];
		$this->default_display( [ 'content' => $content, 'configuration' => $configuration ] );
	}

	function submitregister( $dataPost, $action, $idkajian, $idregister )
	{

		$dataMitigasi                 = $dataPost["risk_mitigasi"];
		$dataPost["id_kajian_risiko"] = $idkajian;
		switch( $action )
		{
			case 'create':
				$dataPost["id"] = generateIdString();
				$dataPost["risk_cause"] = json_encode( $dataPost["risk_cause"] );
				$dataPost["risk_impact"] = json_encode( $dataPost["risk_impact"] );
				$dataPost["created_at"] = date( "Y-m-d H:i:s" );
				$dataPost["created_by"] = $this->ion_auth->get_user_name();
				$dataPost["updated_at"] = date( "Y-m-d H:i:s" );
				$dataPost["updated_by"] = $this->ion_auth->get_user_name();
				unset( $dataPost["risk_mitigasi"] );
				$registerInsertId = $this->db->insert( _TBL_KAJIAN_RISIKO_REGISTER, $dataPost );
				if( $registerInsertId )
				{
					foreach( $dataMitigasi["mitigasi"] as $kmitigasi => $vmitigasi )
					{
						$dataInsertMitigasi = [
						 "id"                        => generateIdString(),
						 "id_kajian_risiko_register" => $dataPost["id"],
						 "mitigasi"                  => $dataMitigasi["mitigasi"][$kmitigasi],
						 "pic"                       => json_encode( $dataMitigasi["pic"][$kmitigasi]["list"] ),
						 "deadline"                  => $dataMitigasi["deadline"][$kmitigasi],
						 "created_at"                => date( "Y-m-d H:i:s" ),
						 "created_by"                => $this->ion_auth->get_user_name(),
						 "updated_at"                => date( "Y-m-d H:i:s" ),
						 "updated_by"                => $this->ion_auth->get_user_name(),
						];
						$this->db->insert( _TBL_KAJIAN_RISIKO_MITIGASI, $dataInsertMitigasi );
					}
				}
				break;
			case 'edit':
				$dataPost["risk_cause"] = json_encode( $dataPost["risk_cause"] );
				$dataPost["risk_impact"] = json_encode( $dataPost["risk_impact"] );
				$dataPost["updated_at"] = date( "Y-m-d H:i:s" );
				$dataPost["updated_by"] = $this->ion_auth->get_user_name();
				unset( $dataPost["risk_mitigasi"] );
				$resultUpdate = $this->db->update( _TBL_KAJIAN_RISIKO_REGISTER, $dataPost, [ "id" => $idregister ] );
				if( $resultUpdate )
				{
					$this->db->delete( _TBL_KAJIAN_RISIKO_MITIGASI, [ "id_kajian_risiko_register" => $idregister ] );
					foreach( $dataMitigasi["mitigasi"] as $kmitigasi => $vmitigasi )
					{
						$dataInsertMitigasi = [
						 "id"                        => generateIdString(),
						 "id_kajian_risiko_register" => $idregister,
						 "mitigasi"                  => $dataMitigasi["mitigasi"][$kmitigasi],
						 "pic"                       => json_encode( $dataMitigasi["pic"][$kmitigasi]["list"] ),
						 "deadline"                  => $dataMitigasi["deadline"][$kmitigasi],
						 "created_at"                => date( "Y-m-d H:i:s" ),
						 "created_by"                => $this->ion_auth->get_user_name(),
						 "updated_at"                => date( "Y-m-d H:i:s" ),
						 "updated_by"                => $this->ion_auth->get_user_name(),
						];
						$this->db->insert( _TBL_KAJIAN_RISIKO_MITIGASI, $dataInsertMitigasi );
					}
				}
				break;

			default:
				# code...
				break;
		}

	}

	function setDataViewRegister( $dataView )
	{

		if( ! empty( $dataView ) )
		{
			foreach( $dataView as $kView => $vView )
			{
				$getdatariskCause = json_decode( $vView["risk_cause"] );
				if( ! empty( $getdatariskCause ) )
				{
					$dataView[$kView]["risk_cause"] = [];
					foreach( $getdatariskCause as $kRiskCause => $vRiskCause )
					{
						$dataView[$kView]["risk_cause"][$kRiskCause] = [ "risk_cause_id" => $vRiskCause, "risk_cause_name" => $this->db->get_where( _TBL_LIBRARY, [ "id" => $vRiskCause ] )->row_array()["library"] ];
					}
				}
				$getdatariskImpact = json_decode( $vView["risk_impact"] );
				if( ! empty( $getdatariskImpact ) )
				{
					$dataView[$kView]["risk_impact"] = [];
					foreach( $getdatariskImpact as $kRiskImpact => $vRiskImpact )
					{
						$dataView[$kView]["risk_impact"][$kRiskImpact] = [ "risk_impact_id" => $vRiskImpact, "risk_impact_name" => $this->db->get_where( _TBL_LIBRARY, [ "id" => $vRiskImpact ] )->row_array()["library"] ];
					}
				}
			}
		}
		return $dataView;
	}

	function riskRegisterModal()
	{
		if( ! $this->input->is_ajax_request() )
		{
			exit( 'No direct script access allowed' );
		}
		$postData                     = $this->input->post();
		$getdataRegister["register"]  = $this->db->get_where( _TBL_VIEW_KAJIAN_RISIKO_REGISTER, [ "id_kajian_risiko" => $postData["id_kajian"] ] )->result_array();
		$getdataRegister["btnExport"] = base_url( $this->modul_name . "/export_excel/" . $postData["id_kajian"] );
		$result                       = $this->load->view( "ajax/register_modal", $getdataRegister, TRUE );

		header( 'Content-type: text/json' );
		header( 'Content-type: application/json' );
		echo $result;
	}

	function export_excel( $id )
	{
		$getdataRegister["register"]  = $this->db->get_where( _TBL_VIEW_KAJIAN_RISIKO_REGISTER, [ "id_kajian_risiko" => $id ] )->result_array();
		$getdataRegister["btnExport"] = base_url( $this->modul_name . "/export_excel/" . $id );
		$result                       = $this->load->view( "ajax/register_modal", $getdataRegister, TRUE );
		$nm_file                      = "Report Risk Register " . date( "Y-m-d" );
		header( "Content-type:appalication/vnd.ms-excel" );
		header( "content-disposition:attachment;filename=" . $nm_file . ".xls" );
		echo $result;
		exit;
	}

	function getlevelrisk( $impact = NULL, $likelihood = NULL )
	{
		if( ! $this->input->is_ajax_request() )
		{
			$result = $this->db->get_where( _TBL_VIEW_LEVEL_MAPPING, [ "like_code" => $likelihood, "impact_code" => $impact ] )->row_array();
			return $result;
		}
		else
		{
			$postData = $this->input->post();
			$result   = $this->db->get_where( _TBL_VIEW_LEVEL_MAPPING, [ "like_code" => $postData["likelihood"], "impact_code" => $postData["impact"] ] )->row_array();
			header( 'Content-type: text/json' );
			header( 'Content-type: application/json' );
			echo json_encode( $result );
		}

	}

	function proposeRisikoHistory( $idkajidan )
	{
		$getdatakajian                     = $this->db->get_where( _TBL_KAJIAN_RISIKO, [ "id" => $idkajidan ] )->row_array();
		$dataInsertHistoryFromSubmitKajian = [
		 "id"               => generateIdString(),
		 "id_kajian_risiko" => $idkajidan,
		 "status_approval"  => $getdatakajian["status_approval"],
		 "note"             => "",
		 "created_at"       => date( "Y-m-d H:i:s" ),
		 "created_by"       => $this->ion_auth->get_user_name(),
		 "updated_at"       => date( "Y-m-d H:i:s" ),
		 "updated_by"       => $this->ion_auth->get_user_name(),
		];
		$this->db->insert( _TBL_KAJIAN_RISIKO_APPROVAL_HISTORY, $dataInsertHistoryFromSubmitKajian );

	}

	function history( $idkajian )
	{
		$getdataHistory["dataview"] = $this->data->getDataHistoryKajian( $idkajian );
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
		$result        = $this->load->view( "history", $getdataHistory, TRUE );
		$configuration = [
		 'show_title_header'  => FALSE,
		 'show_action_button' => FALSE,
		   ];
		$this->default_display( [ 'content' => $result, 'configuration' => $configuration ] );

	}

	function uploadDokumenMr( $idkajian )
	{
		$resultUpload = FALSE;
		$content      = "";
		$paramUpload  = [
		 "path"        => "file/kajian_risiko_mr",
		 "field"       => "file",
		 "file_type"   => "pdf|xlsx|docx|doc|docx",
		 "file_thumb"  => FALSE,
		 "file_size"   => "10000",
		 "file_random" => FALSE,
		 "multi"       => FALSE,
		 "image-no"    => FALSE,
		];
		if( ! empty( $_FILES["file"] ) )
		{
			$dataUpload["name"]     = generateIdString();
			$dataUpload["type"]     = $_FILES["file"]["type"];
			$dataUpload["tmp_name"] = $_FILES["file"]["tmp_name"];
			$dataUpload["error"]    = $_FILES["file"]["error"];
			$dataUpload["size"]     = $_FILES["file"]["size"];
			if( ! empty( $this->input->post( "fileexist" ) ) )
			{
				unlink( "files/kajian_risiko_mr/" . $this->input->post( "fileexist" ) );
			}
			$getStatusUpload = $this->save_file( $paramUpload, $dataUpload );
			$status          = explode( "/", $getStatusUpload );
			$getnameFile     = $status[1];
			if( $getnameFile != "error" )
			{
				$status = $this->db->update( _TBL_KAJIAN_RISIKO, [ "dokumen_mr" => $getnameFile ], [ "id" => $idkajian ] );
				if( $status )
				{
					$dataview["filename"]    = ( file_exists( "files/kajian_risiko_mr/" . $getnameFile ) ) ? $getnameFile : "";
					$dataview["urlclearbtn"] = base_url( $this->modul_name . "/clearDokumen/" . $idkajian );
					$content                 = $this->load->view( "ajax/upload-dokumen-mr", $dataview, TRUE );
				}
			}
		}
		echo $content;
	}

	function getDokumenMr( $idkajian )
	{
		$result                  = $this->db->get_where( _TBL_KAJIAN_RISIKO, [ "id" => $idkajian ] )->row_array()["dokumen_mr"];
		$dataview["filename"]    = ( file_exists( "files/kajian_risiko_mr/" . $result ) ) ? $result : "";
		$dataview["urlclearbtn"] = base_url( $this->modul_name . "/clearDokumen/" . $idkajian );
		$content                 = $this->load->view( "ajax/upload-dokumen-mr", $dataview, TRUE );
		echo $content;
	}

	function clearDokumen( $idkajian )
	{

		$status = $this->db->update( _TBL_KAJIAN_RISIKO, [ "dokumen_mr" => NULL ], [ "id" => $idkajian ] );
		if( $status )
		{
			unlink( "files/kajian_risiko_mr/" . $this->input->post( "filename" ) );
		}
		$result                  = $this->db->get_where( _TBL_KAJIAN_RISIKO, [ "id" => $idkajian ] )->row_array()["dokumen_mr"];
		$dataview["filename"]    = ( file_exists( "files/kajian_risiko_mr/" . $result ) ) ? $result : "";
		$dataview["urlclearbtn"] = base_url( $this->modul_name . "/clearDokumen/" . $idkajian );
		$content                 = $this->load->view( "ajax/upload-dokumen-mr", $dataview, TRUE );
		echo $content;
	}

	function setReminderNotificationEmail()
	{
		$countDays              = 1;
		$sqlQuery               = "select id,owner_id,name from il_kajian_risiko ikr where date_format(DATE_ADD(release_date , INTERVAL -{$countDays} DAY),'%Y-%m-%d')=date_format(NOW(),'%Y-%m-%d') And status = 1";
		$getTemplate            = $this->db->get_where( "il_template_email", [ "code" => "NOTIF09" ] )->row_array();
		$getdateReminderRelease = $this->db->query( $sqlQuery )->result_array();
		if( ! empty( $getdateReminderRelease ) )
		{
			foreach( $getdateReminderRelease as $kRerelease => $vRelease )
			{
				$getOfficerData = $this->db->get_where( _TBL_OFFICER, [ "owner_no" => $vRelease['owner_id'], "active" => 1 ] )->result_array();
				if( ! empty( $getOfficerData ) )
				{
					foreach( $getOfficerData as $kOff => $vOff )
					{
						if( ! empty( $vOff["email"] ) )
						{
							$getTemplate["content_html"] = str_replace( "[[OWNER]]", $vOff['officer_name'], $getTemplate["content_html"] );
							$getTemplate["content_html"] = str_replace( "[[KAJIAN]]", $vRelease['name'], $getTemplate["content_html"] );
							$content                     = $this->load->view( "email-notification", $getTemplate, TRUE );
							$emailData['email']          = [ $vOff["email"] ];
							$emailData['subject']        = $getTemplate["subject"] ?? "Notifikasi Reminder Kajian Risiko";
							$emailData['content']        = $content ?? "";
							$status                      = Doi::kirim_email( $emailData );
							if( $status == "success" )
							{
								$this->crud->crud_table( _TBL_LOG_SEND_EMAIL );
								$this->crud->crud_type( 'add' );
								$this->crud->crud_field( 'type', 1, 'int' );
								$this->crud->crud_field( 'ref_id', $vOff["id"], 'int' );
								$this->crud->crud_field( 'subject', $vOff["officer_name"], 'string' );
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
	}
}
