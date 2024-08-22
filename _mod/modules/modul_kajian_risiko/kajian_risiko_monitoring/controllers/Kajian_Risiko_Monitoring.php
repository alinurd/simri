<?php if( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Kajian_Risiko_Monitoring extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	function init( $action = 'list' )
	{

		$this->cboDept    = $this->get_combo_parent_dept();
		$this->cbo_status = $this->crud->combo_value( [ 0 => 'Draft', 1 => 'Submit' ] )->result_combo();

		$this->set_Tbl_Master( _TBL_VIEW_KAJIAN_RISIKO );

		$this->set_Open_Coloums( 'Data Kajian Risiko' );
		$this->addField( [ 'field' => 'id', 'type' => 'int', 'show' => FALSE, 'size' => 4 ] );
		$this->addField( [ 'field' => 'owner_id', 'title' => 'Risk Owner', 'input' => 'combo', 'values' => $this->cboDept, 'search' => TRUE, "required" => TRUE ] );
		$this->addField( [ 'field' => 'name', 'title' => 'Nama Kajian Risiko', 'type' => 'string', 'search' => TRUE, "required" => TRUE ] );
		$this->addField( [ 'field' => 'request_date', 'type' => 'date', 'search' => TRUE, "required" => TRUE ] );
		$this->addField( [ 'field' => 'release_date', 'type' => 'date', 'search' => TRUE, "required" => TRUE ] );
		$this->addField( [ 'field' => 'status', 'title' => 'Status', 'type' => 'int', 'input' => 'combo', 'values' => $this->cbo_status, 'default' => 0, 'size' => 40 ] );
		$this->set_Close_Coloums();

		$this->set_Field_Primary( $this->tbl_master, 'id' );
		$this->set_Join_Table( [ 'pk' => $this->tbl_master ] );

		$this->set_Sort_Table( $this->tbl_master, 'id' );
		$this->set_Where_Table( [ 'field' => 'status', 'value' => 1, 'op' => '=' ] );

		$this->set_Table_List( $this->tbl_master, 'owner_id', "Risk Owner" );
		$this->set_Table_List( $this->tbl_master, 'name', "Nama Kajian Risiko" );
		$this->set_Table_List( $this->tbl_master, 'request_date', "Tanggal Permintaan" );
		$this->set_Table_List( $this->tbl_master, 'release_date', "Tanggal Release" );
		$this->set_Table_List( $this->tbl_master, 'status', "Status" );
		$this->set_Close_Setting();

		$this->set_Save_Table( _TBL_KAJIAN_RISIKO );

		$configuration = [
		 'show_title_header'  => FALSE,
		 'content_title'      => 'Kajian Risiko Monitoring',
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
				$statusContent = "<span class='btn btn-sm btn-danger' style='cursor:default'>DRAFT</span>";
				break;
			case 1:
				$statusContent = "<span class='btn btn-sm btn-success' style='cursor:default'>SUBMITTED</span>";
				break;

			default:
				$statusContent = "";
				break;
		}
		return $statusContent;

	}

	function listBox_release_date( $field, $rows, $value )
	{
		return ( ! empty( $value ) && $value != "0000-00-00" ) ? date( "d-m-Y", strtotime( $value ) ) : "";
	}
	function listBox_request_date( $field, $rows, $value )
	{
		return ( ! empty( $value ) && $value != "0000-00-00" ) ? date( "d-m-Y", strtotime( $value ) ) : "";
	}
	function optionalPersonalButton( $button, $row )
	{

		unset( $button['update'] );
		unset( $button['delete'] );
		unset( $button['view'] );
		// $button['view'] = [
		//  'label' => 'View',
		//  'id'    => 'btn_view',
		//  'class' => 'text-primary',
		//  'icon'  => 'icon-clipboard3',
		//  'url'   => "",
		//  'attr'  => ' target="_self" ',
		//    ];

		$button['update_progress'] = [
		'label' => 'Update Progress',
		'id'    => 'btn_update_progress',
		'class' => 'btn btn-sm btn-warning text-center',
		'icon'  => 'icon-stats-bars3 text-white',
		'url'   => base_url( $this->modul_name . "/progress/show/" ),
		'attr'  => ' target="_self" ',
		'align' => 'center',
		 ];

		// $button['dokumen'] = [
		// 'label' => 'Document',
		// 'id'    => 'btn_dokumen',
		// 'class' => 'text-secondary',
		// 'icon'  => 'icon-file-empty',
		// 'url'   => "",
		// 'attr'  => ' target="_self" ',
		//   ];

		return $button;
	}

	function progress( $action, $idkajian, $idRegister = NULL )
	{
		$view                          = "monitoring";
		$dataView["btn_view"]          = "btn_default";
		$dataView["module_name"]       = $this->modul_name;
		$dataView["kajian_id"]         = $idkajian;
		$dataView["action"]            = $action;
		$dataView["view"]              = $view;
		$dataView["btnEdit"]           = base_url( $this->modul_name . "/editRegisterAjax" );
		$dataView["btneditMonitoring"] = base_url( $this->modul_name . "/formMonitoringAjax" );
		$dataView["btnDelete"]         = base_url( $this->modul_name . "/submitMonitoring" );
		$dataView["btnAdd"]            = base_url( $this->modul_name . "/formMonitoringAjax" );
		$dataView["headerRisk"]        = $this->db->get_where( _TBL_VIEW_KAJIAN_RISIKO, [ "id" => $idkajian, "active" => 1 ] )->row_array();
		$getRegisterData               = $this->db->get_where( _TBL_VIEW_KAJIAN_RISIKO_REGISTER, [ "id_kajian_risiko" => $idkajian ] )->result_array();
		$dataView["register"]          = $this->setDataViewMonitoring( $getRegisterData );
		$content                       = $this->load->view( $action, $dataView, TRUE );
		$configuration                 = [
		 'show_title_header'  => FALSE,
		 'show_action_button' => FALSE,
		   ];
		$this->default_display( [ 'content' => $content, 'configuration' => $configuration ] );
	}

	function setDataViewMonitoring( $registerData )
	{
		$StatusMap = [
		 "on-progress" => "On Progress",
		 "not-started" => "Not Started",
		 "closed"      => "Closed",
		];
		if( empty( $registerData ) )
		{
			return [];
		}

		foreach( $registerData as $kReg => $vReg )
		{
			$registerData[$kReg]["mitigasi"] = $this->db->get_where( _TBL_KAJIAN_RISIKO_MITIGASI, [ "id_kajian_risiko_register" => $vReg["id"] ] )->result_array();
			if( ! empty( $registerData[$kReg]["mitigasi"] ) )
			{
				foreach( $registerData[$kReg]["mitigasi"] as $keyMit => $vMit )
				{
					$registerData[$kReg]["monitoring"]["{$vMit['id']}"] = $this->db->query( "select ikrm.id as id_mitigasi,ikrm.mitigasi,ikrm.pic,ikrm.deadline,ikrm2.id as id_monitoring, ikrm2.status,ikrm2.detail_progress,ikrm2.tanggal_update,ikrm2.dokumen_pendukung from il_kajian_risiko_mitigasi ikrm left join il_kajian_risiko_monitoring ikrm2 on ikrm.id =ikrm2.id_kajian_risiko_mitigasi where ikrm.id='{$vMit['id']}'" )->result_array();

					if( ! empty( $registerData[$kReg]["monitoring"] ) )
					{
						foreach( $registerData[$kReg]["monitoring"][$vMit["id"]] as $kMon => $vMon )
						{
							if( ! empty( $vMon["pic"] ) && json_decode( $vMon["pic"] ) )
							{
								$registerData[$kReg]["monitoring"][$vMit["id"]][$kMon]["pic"] = $this->db->select( "owner_name" )->where_in( "id", json_decode( $vMon["pic"] ) )->get( _TBL_OWNER )->result_array();

							}
							$registerData[$kReg]["monitoring"][$vMit["id"]][$kMon]["status"] = ! empty( $StatusMap[$vMon["status"]] ) ? $StatusMap[$vMon["status"]] : "";
						}
					}
				}
			}
		}
		return $registerData;
	}

	function editRegisterAjax( $idReg = NULL )
	{
		if( ! $this->input->is_ajax_request() )
		{
			exit( 'No direct script access allowed' );
		}
		$result   = "";
		$postData = $this->input->post();

		switch( $postData["mode"] )
		{
			case 'show':
				$dataView["formdata"] = $this->db->get_where( _TBL_VIEW_KAJIAN_RISIKO_REGISTER, [ "id" => $postData["id"] ] )->row_array();
				$dataView["levelImpact"] = $this->db->get_where( _TBL_LEVEL, [ "active" => 1, "category" => "impact" ] )->result_array();
				$dataView["levelLikelihood"] = $this->db->get_where( _TBL_LEVEL, [ "active" => 1, "category" => "likelihood" ] )->result_array();
				$dataView["formUrl"] = base_url( $this->modul_name . "/editRegisterAjax/" . $postData["id"] );
				$dataView["btnUrl"] = base_url( $this->modul_name . "/editRegisterAjax" );
				$result = $this->load->view( "ajax/register_edit_ajax", $dataView, TRUE );
				break;

			case 'edit':
				$dataUpdate = [
				 "risiko"                    => $postData["risiko"],
				 "inherent_risk_level"       => $postData["inherent_risk_level"],
				 "residual_risk_level"       => $postData["residual_risk_level"],
				 "impact_residual_level"     => $postData["impact_residual_level"],
				 "likelihood_residual_level" => $postData["likelihood_residual_level"],
				 "updated_at"                => date( "Y-m-d H:i:s" ),
				 "updated_by"                => $this->ion_auth->get_user_name(),
				];

				$this->db->update( _TBL_KAJIAN_RISIKO_REGISTER, $dataUpdate, [ "id" => $postData["id"] ] );
				$getRegisterData = $this->db->get_where( _TBL_VIEW_KAJIAN_RISIKO_REGISTER, [ "id_kajian_risiko" => $postData["idkajian"] ] )->result_array();
				$dataView["btnEdit"] = base_url( $this->modul_name . "/editRegisterAjax" );
				$dataView["btnDelete"] = base_url( $this->modul_name . "/" . __FUNCTION__ . "/delete/" . $postData["idkajian"] . "/" );
				$dataView["btnAdd"] = base_url( $this->modul_name . "/formMonitoringAjax" );
				$dataView["btneditMonitoring"] = base_url( $this->modul_name . "/formMonitoringAjax" );
				$dataView["register"] = $this->setDataViewMonitoring( $getRegisterData );
				$result = $this->load->view( "ajax/table_monitoring", $dataView, TRUE );
				break;

			default:
				break;
		}
		echo $result;
	}

	function formMonitoringAjax()
	{
		if( ! $this->input->is_ajax_request() )
		{
			exit( 'No direct script access allowed' );
		}
		$postData             = $this->input->post();
		$dataView["btnUrl"]   = base_url( $this->modul_name . "/submitMonitoring" );
		$dataView["idkajian"] = $postData["idkajian"];
		switch( $postData["mode"] )
		{
			case 'edit':
				$dataView["type"] = "update";
				$dataView["formdata"] = $this->db->query( "select ikrm.id as id_monitoring, ikrm.status, ikrm.detail_progress,ikrm.tanggal_update,ikrm2.id as id,ikrm2.mitigasi,ikrm2.pic,ikrm2.deadline,ikrm.dokumen_pendukung from il_kajian_risiko_monitoring ikrm left join il_kajian_risiko_mitigasi ikrm2 on ikrm.id_kajian_risiko_mitigasi =ikrm2.id where ikrm.id='{$postData['id']}'" )->row_array();
				$result = $this->load->view( "ajax/form_monitoring", $dataView, TRUE );
				break;

			case 'create':
				$dataView["type"] = "create";
				$dataView["formdata"] = $this->db->get_where( _TBL_KAJIAN_RISIKO_MITIGASI, [ "id" => $postData["idmitigasi"] ] )->row_array();
				$result = $this->load->view( "ajax/form_monitoring", $dataView, TRUE );
				break;
		}
		echo $result;
	}

	function submitMonitoring()
	{
		if( ! $this->input->is_ajax_request() )
		{
			exit( 'No direct script access allowed' );
		}
		$posTdata = $this->input->post();
		switch( $posTdata["mode"] )
		{
			case 'create':

				$dataInsert = [
				 "id"                        => generateIdString(),
				 "id_kajian_risiko_mitigasi" => $posTdata["idmitigasi"],
				 "status"                    => $posTdata["status"],
				 "detail_progress"           => $posTdata["detail_progress"],
				 "tanggal_update"            => $posTdata["tanggal_update_submit"],
				 "dokumen_pendukung"         => $this->processFile( $posTdata ),
				 "created_at"                => date( "Y-m-d H:i:s" ),
				 "created_by"                => $this->ion_auth->get_user_name(),
				 "updated_at"                => date( "Y-m-d H:i:s" ),
				 "updated_by"                => $this->ion_auth->get_user_name(),
				 ];
				$this->db->insert( _TBL_KAJIAN_RISIKO_MONITORING, $dataInsert );
				break;

			case 'update':
				$dataUpdate = [
				 "status"            => $posTdata["status"],
				 "detail_progress"   => $posTdata["detail_progress"],
				 "tanggal_update"    => $posTdata["tanggal_update_submit"],
				 "dokumen_pendukung" => $this->processFile( $posTdata ),
				 "updated_at"        => date( "Y-m-d H:i:s" ),
				 "updated_by"        => $this->ion_auth->get_user_name(),
				 ];
				if( empty( $_FILES["file"] ) )
				{
					unset( $dataUpdate["dokumen_pendukung"] );
				}

				$this->db->update( _TBL_KAJIAN_RISIKO_MONITORING, $dataUpdate, [ "id" => $posTdata["id"] ] );
				break;

			case 'delete':
				$getfile = $this->db->get_where( _TBL_KAJIAN_RISIKO_MONITORING, [ "id" => $posTdata["id"] ] )->row_array()["dokumen_pendukung"];
				if( ! empty( $getfile ) && file_exists( "./files/kajian_risiko_monitoring/{$getfile}" ) )
				{
					unlink( "./files/kajian_risiko_monitoring/" . $getfile );
				}
				$this->db->delete( _TBL_KAJIAN_RISIKO_MONITORING, [ "id" => $posTdata["id"] ] );
				break;
		}
		$getRegisterData               = $this->db->get_where( _TBL_VIEW_KAJIAN_RISIKO_REGISTER, [ "id_kajian_risiko" => $posTdata["idkajian"] ] )->result_array();
		$dataView["btnEdit"]           = base_url( $this->modul_name . "/editRegisterAjax" );
		$dataView["btnDelete"]         = base_url( $this->modul_name . "/" . __FUNCTION__ . "/delete/" . $posTdata["idkajian"] . "/" );
		$dataView["btnAdd"]            = base_url( $this->modul_name . "/formMonitoringAjax" );
		$dataView["btneditMonitoring"] = base_url( $this->modul_name . "/formMonitoringAjax" );
		$dataView["register"]          = $this->setDataViewMonitoring( $getRegisterData );
		$result                        = $this->load->view( "ajax/table_monitoring", $dataView, TRUE );
		echo $result;
	}

	function monitoringModal()
	{
		if( ! $this->input->is_ajax_request() )
		{
			exit( 'No direct script access allowed' );
		}
		$postData = $this->input->post();

		$dataMonitoring["monitoring"] = $this->db->get_where( _TBL_VIEW_KAJIAN_RISIKO_MONITORING, [ "id_kajian_risiko" => $postData["id"] ] )->result_array();
		if( ! empty( $dataMonitoring["monitoring"] ) )
		{
			foreach( $dataMonitoring["monitoring"] as $kModMon => $vModMon )
			{
				if( ! empty( $vModMon["pic"] ) && json_decode( $vModMon["pic"] ) )
				{
					$dataMonitoring["monitoring"][$kModMon]["pic"] = $this->db->select( "owner_name" )->where_in( "id", json_decode( $vModMon["pic"] ) )->get( _TBL_OWNER )->result_array();

				}
			}
		}
		$dataMonitoring["btnExport"] = base_url( $this->modul_name . "/export_excel/" . $postData["id"] );
		$result                      = $this->load->view( "ajax/monitoring_modal", $dataMonitoring, TRUE );

		header( 'Content-type: text/json' );
		header( 'Content-type: application/json' );
		echo $result;
	}

	function processFile( $postdata )
	{
		$resultUpload = [];
		$paramUpload  = [
		 "path"        => "file/kajian_risiko_monitoring",
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
			if( empty( $postdata["file_monitoring"] ) )
			{
				$getStatusUpload = $this->save_file( $paramUpload, $dataUpload );
				$status          = explode( "/", $getStatusUpload );
				$resultUpload    = $status[1];
				// $resultUpload["name"]            = $_FILES["file"]["name"];
			}
			else
			{
				unlink( "./files/kajian_risiko_monitoring/" . $postdata["file_monitoring"] );
				$getStatusUpload = $this->save_file( $paramUpload, $dataUpload );
				$status          = explode( "/", $getStatusUpload );
				$resultUpload    = $status[1];
				// $resultUpload["name"] = $_FILES["file"]["name"];
			}
		}
		return ( ! empty( $resultUpload ) ) ? $resultUpload : NULL;
	}

	function export_excel( $id )
	{
		$dataMonitoring["monitoring"] = $this->db->get_where( _TBL_VIEW_KAJIAN_RISIKO_MONITORING, [ "id_kajian_risiko" => $id ] )->result_array();
		if( ! empty( $dataMonitoring["monitoring"] ) )
		{
			foreach( $dataMonitoring["monitoring"] as $kModMon => $vModMon )
			{
				if( ! empty( $vModMon["pic"] ) && json_decode( $vModMon["pic"] ) )
				{
					$dataMonitoring["monitoring"][$kModMon]["pic"] = $this->db->select( "owner_name" )->where_in( "id", json_decode( $vModMon["pic"] ) )->get( _TBL_OWNER )->result_array();

				}
			}
		}
		$dataMonitoring["btnExport"] = base_url( $this->modul_name . "/export_excel/" . $id );
		$result                      = $this->load->view( "ajax/monitoring_modal", $dataMonitoring, TRUE );
		$nm_file                     = "Report Monitoring " . date( "Y-m-d" );
		header( "Content-type:appalication/vnd.ms-excel" );
		header( "content-disposition:attachment;filename=" . $nm_file . ".xls" );
		echo $result;
		exit;
	}
}


