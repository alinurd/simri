<?php if( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Kajian_Risiko extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	function init( $action = 'list' )
	{

		$this->cboDept    = $this->get_combo_parent_dept();
		$this->cbo_status = $this->crud->combo_value( [ 0 => 'DRAFT', 1 => 'SUBMIT' ] )->result_combo();

		$this->set_Tbl_Master( _TBL_VIEW_KAJIAN_RISIKO );

		$this->set_Open_Coloums( 'Data Kajian Risiko' );
		$this->addField( [ 'field' => 'id', 'type' => 'int', 'show' => FALSE, 'size' => 4 ] );
		$this->addField( [ 'field' => 'owner_id', 'title' => 'Risk Owner', 'input' => 'combo', 'values' => $this->cboDept, 'search' => TRUE, "required" => TRUE ] );
		$this->addField( [ 'field' => 'name', 'title' => 'Nama Kajian Risiko', 'type' => 'string', 'search' => TRUE, "required" => TRUE ] );
		$this->addField( [ 'field' => 'request_date', 'type' => 'date', 'search' => TRUE, "required" => TRUE ] );
		$this->addField( [ 'field' => 'release_date', 'type' => 'date', 'search' => TRUE, "required" => TRUE ] );
		$this->addField( [ 'field' => 'status', 'title' => 'Status', "show" => FALSE, 'type' => 'int', 'input' => 'combo', 'values' => $this->cbo_status, 'default' => 0, 'size' => 40 ] );

		if( $this->_mode_ != "add" )
		{
			$this->addField( [ 'field' => 'link_dokumen_kajian', "title" => "Dokumen Self-Assessment" ] );

			$this->addField( [ 'field' => 'link_dokumen_pendukung', "title" => "Dokumen Pendukung" ] );
		}


		$this->set_Close_Coloums();

		$this->set_Field_Primary( $this->tbl_master, 'id' );
		$this->set_Join_Table( [ 'pk' => $this->tbl_master ] );

		$this->set_Sort_Table( $this->tbl_master, 'id' );

		$this->set_Table_List( $this->tbl_master, 'owner_id', "Risk Owner" );
		$this->set_Table_List( $this->tbl_master, 'name', "Nama Kajian Risiko" );
		$this->set_Table_List( $this->tbl_master, 'request_date', "Tanggal Permintaan" );
		$this->set_Table_List( $this->tbl_master, 'release_date', "Tanggal Release" );
		$this->set_Table_List( $this->tbl_master, 'status', "Status", 0, "center" );
		$this->set_Close_Setting();

		$this->set_Save_Table( _TBL_KAJIAN_RISIKO );

		$configuration = [
		 'show_title_header' => FALSE,
		 'content_title'     => 'Kajian Risiko',
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

			default:
				$statusContent = "";
				break;
		}
		return $statusContent;

	}

	function inputBox_link_dokumen_pendukung( $mode, $field, $row, $value )
	{
		if( $_POST )
		{
			$_POST["link_dokumen_pendukung"] = json_encode( $this->validateInputLink( $field["field"], $value ) );
		}
		else
		{
			$dataView["inputname"]  = $field['field'];
			$dataView["attachment"] = ( ! empty( $value ) ) ? json_decode( $value ) : [];
			return $this->load->view( "risk-attachment", $dataView, TRUE );
		}
	}

	function inputBox_link_dokumen_kajian( $mode, $field, $row, $value )
	{
		if( $_POST )
		{
			$_POST["link_dokumen_kajian"] = json_encode( $this->validateInputLink( $field["field"], $value ) );
		}
		else
		{
			$dataView["inputname"]  = $field['field'];
			$dataView["attachment"] = ( ! empty( $value ) ) ? json_decode( $value ) : [];
			return $this->load->view( "risk-attachment", $dataView, TRUE );
		}
	}

	function uploadAttachmentFile()
	{
		if( ! $this->input->is_ajax_request() )
		{
			exit( 'No direct script access allowed' );
		}
		$id           = $this->input->post( "id" );
		$resultUpload = FALSE;
		$paramUpload  = [
		 "path"        => "file/kajian_risiko",
		 "field"       => ( ! empty( $_POST["field"] ) ) ? $_POST["field"] : "",
		 "file_type"   => "gif|jpg|jpeg|png|pdf|xlsx|docx|ppt",
		 "file_thumb"  => FALSE,
		 "file_size"   => "10000",
		 "file_random" => FALSE,
		];
		if( ! empty( $_FILES[$_POST["field"]]["name"] ) )
		{
			$dataUpload["name"] = generateIdString();
			// $dataUpload["full_path"] = $_FILES[$_POST["field"]]["full_path"];
			$dataUpload["type"]     = $_FILES[$_POST["field"]]["type"];
			$dataUpload["tmp_name"] = $_FILES[$_POST["field"]]["tmp_name"];
			$dataUpload["error"]    = $_FILES[$_POST["field"]]["error"];
			$dataUpload["size"]     = $_FILES[$_POST["field"]]["size"];
			$getStatusUpload        = $this->save_file( $paramUpload, $dataUpload );
			$status                 = explode( "/", $getStatusUpload );
			$resultUpload           = $status[1];

			if( $resultUpload !== "error" )
			{
				$dataInsrt = [
				 "id"               => $dataUpload["name"],
				 "id_kajian_risiko" => $id,
				 "server_filename"  => $resultUpload,
				 "file_type"        => $paramUpload["field"],
				 "file_path"        => "./files/kajian_risiko/",
				 "filename"         => $_FILES[$_POST["field"]]["name"],
				 "created_at"       => date( "Y-m-d h:i:s" ),
				 "created_by"       => $this->ion_auth->get_user_name(),
				 "updated_at"       => date( "Y-m-d h:i:s" ),
				 "updated_by"       => $this->ion_auth->get_user_name(),
				];
				$this->db->insert( _TBL_KAJIAN_RISIKO_FILE, $dataInsrt );
			}
		}
		$dataresult = [ "id" => $dataInsrt['id'], "server_filename" => $dataInsrt['server_filename'], "file_type" => $dataInsrt["file_type"], "file_path" => $dataInsrt["file_path"], "filename" => $dataInsrt["filename"] ];

		header( 'Content-type: text/json' );
		header( 'Content-type: application/json' );
		echo json_encode( $dataresult );
	}

	function deleteAttachmentFile()
	{
		if( ! $this->input->is_ajax_request() )
		{
			exit( 'No direct script access allowed' );
		}
		$result          = FALSE;
		$server_filename = $this->input->post( "server_filename" );
		$idrisk          = $this->input->post( "idrisk" );
		$idfile          = $this->input->post( "id" );
		$file_type       = $this->input->post( "file_type" );
		$file_path       = $this->input->post( "file_path" );
		$pathFile        = $file_path . $server_filename;
		if( file_exists( $pathFile ) )
		{
			$result = unlink( $pathFile );
			if( $result )
			{
				$this->db->delete( _TBL_KAJIAN_RISIKO_FILE, [ 'id' => $idfile, 'file_type' => $file_type, 'id_kajian_risiko' => $idrisk ] );
			}
		}
		header( 'Content-type: text/json' );
		header( 'Content-type: application/json' );
		echo json_encode( $result );
	}

	function getAttachmentFile()
	{

		$dataresult  = [];
		$getId       = $this->input->get( "idrisk" );
		$file_type   = $this->input->get( "field" );
		$getdataRisk = $this->db->get_where( _TBL_KAJIAN_RISIKO_FILE, [ "id_kajian_risiko" => $getId, "file_type" => $file_type ] )->result_array();
		if( ! empty( $getdataRisk ) )
		{
			foreach( $getdataRisk as $key => $value )
			{
				if( file_exists( $value["file_path"] . $value["server_filename"] ) )
				{
					$dataresult[$key]["id"]              = $value["id"];
					$dataresult[$key]["name"]            = $value["filename"];
					$dataresult[$key]["size"]            = filesize( $value["file_path"] . $value["server_filename"] );
					$dataresult[$key]["server_filename"] = $value["server_filename"];
					$dataresult[$key]["file_type"]       = $value["file_type"];
					$dataresult[$key]["file_path"]       = $value["file_path"];
				}
			}
		}
		header( 'Content-type: text/json' );
		header( 'Content-type: application/json' );
		echo json_encode( $dataresult );
	}

	function optionalPersonalButton( $button, $row )
	{
		$button['document']      = [
		 'label' => 'Dokumen',
		 'id'    => 'btn-kajian-risk-document',
		 'class' => 'text-warning',
		 'icon'  => 'icon-file-text',
		 'url'   => base_url( $this->modul_name . "/edit/" ),
		 'attr'  => ' target="_self" ',
		 ];
		$button['risk_register'] = [
		 'label' => 'Risk Register',
		 'id'    => 'btn-kajian-risk-register',
		 'class' => 'text-primary',
		 'icon'  => 'icon-file-upload2',
		 'url'   => base_url( $this->modul_name . "/register/list/" ),
		 'attr'  => ' target="_self" ',
		 ];

		if( $row["status"] == 1 )
		{
			$button['submit'] = [
			 'label' => 'Submitted',
			 'id'    => 'btn_submitted',
			 'class' => 'text-success disabled',
			 'icon'  => 'icon-checkmark-circle',
			 'url'   => "javascript:void(0);",
			 'attr'  => '',
			   ];
		}
		else
		{
			$button['propose'] = [
			 'label' => 'Propose',
			 'id'    => 'btn_schedule_one',
			 'class' => 'text-warning',
			 'icon'  => 'icon-paperplane',
			 'url'   => base_url( $this->modul_name . "/register/propose/" ),
			 'attr'  => ' target="_self" ',
			   ];
		}
		return $button;
	}

	function validateInputLink( $field, $value )
	{
		if( ! empty( $value ) )
		{
			foreach( $value as $key => $vUrlValidate )
			{
				if( ! empty( $vUrlValidate ) && ! filter_var( $vUrlValidate, FILTER_VALIDATE_URL ) )
				{
					$this->session->set_flashdata( "alert_{$field}", "'<b>" . $vUrlValidate . "</b>' is Not valid Url" );
					unset( $value[$key] );
				}
			}
		}
		return $value;
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

		if( $action == "submit" && $dataView["headerRisk"]["status"] != 1 )
		{
			$this->db->update( _TBL_KAJIAN_RISIKO, [ "status" => 1, "date_submit" => date( "Y-m-d H:i:s" ), "updated_at" => date( "Y-m-d H:i:s" ), "updated_by" => $this->ion_auth->get_user_name() ], [ "id" => $idkajian ] );
		}

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
			case 'submit':
				$action = "propose";
				$actionForm = "propose";
				$btn_view = "btn_propose";
				break;
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
}
