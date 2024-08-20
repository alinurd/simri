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
		$this->addField( [ 'field' => 'request_date', 'type' => 'date', "show" => FALSE, "save" => TRUE ] );
		$this->addField( [ 'field' => 'release_date', 'type' => 'date', "show" => FALSE,] );
		$this->addField( [ 'field' => 'tiket_terbit', 'type' => 'date', "show" => FALSE,] );
		$this->addField( [ 'field' => 'urutan_tiket', 'type' => 'int', "show" => FALSE,] );
		$this->addField( [ 'field' => 'status_approval', 'type' => 'string', "show" => FALSE,] );
		$this->addField( [ 'field' => 'status', 'title' => 'Status', "show" => FALSE, 'type' => 'int', 'input' => 'combo', 'values' => $this->cbo_status, 'default' => 0, 'size' => 40 ] );
		$this->addField( [ 'field' => 'link_dokumen_kajian', "title" => "Dokumen Self-Assessment" ] );
		$this->addField( [ 'field' => 'link_dokumen_pendukung', "title" => "Dokumen Pendukung" ] );
		$this->addField( [ 'field' => 'dokumen_mr', "show" => FALSE, "save" => FALSE ] );
		$this->set_Close_Coloums();
		$this->set_Field_Primary( $this->tbl_master, 'id' );
		$this->set_Join_Table( [ 'pk' => $this->tbl_master ] );

		$this->set_Sort_Table( $this->tbl_master, 'id' );

		$this->set_Table_List( $this->tbl_master, 'owner_id', "Risk Owner" );
		$this->set_Table_List( $this->tbl_master, 'name', "Nama Kajian Risiko" );
		$this->set_Table_List( $this->tbl_master, 'request_date', "Tanggal Permintaan" );
		$this->set_Table_List( $this->tbl_master, 'tiket_terbit', "Tanggal Tiket Terbit" );
		$this->set_Table_List( $this->tbl_master, 'release_date', "Max Tanggal Release" );

		// $this->set_Table_List( $this->tbl_master, 'urutan_tiket', "Urutan Tiket" );

		$this->set_Table_List( $this->tbl_master, 'status', "Status", 0, "center" );
		$this->set_Table_List( $this->tbl_master, 'status_approval', "Status Approval", 0, "center" );
		$this->set_Table_List( $this->tbl_master, 'dokumen_mr', "Dokumen MR", 0, "center" );
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

	function inputContent( $mode, $data )
	{

		$get_id = $this->db->query( "select * from il_kajian_risiko_file where id_kajian_risiko not in (select id from il_kajian_risiko)" )->result_array();
		if( ! empty( $get_id ) )
		{
			foreach( $get_id as $kID => $vId )
			{
				if( ! empty( $vId['server_filename'] ) && file_exists( $vId["file_path"] . $vId["server_filename"] ) )
				{
					unlink( $vId["file_path"] . $vId["server_filename"] );
					$this->db->delete( 'il_kajian_risiko_file', array( 'id' => $vId["id"] ) );
				}
			}
		}
		if( $this->_mode_ == "add" )
		{
			$data["fields"]["id"]["isi"]           = $this->data->getIdIncrementDb();
			$data["fields"]["request_date"]["isi"] = date( "Y-m-d H:i:s" );

		}
		return $this->load->view( 'material/input', $data, TRUE );

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

	function listBox_release_date( $field, $rows, $value )
	{
		return ( ! empty( $value ) && $value != "0000-00-00" ) ? date( "Y-m-d", strtotime( $value ) ) : "";
	}
	function listBox_request_date( $field, $rows, $value )
	{
		return ( ! empty( $value ) && $value != "0000-00-00" ) ? date( "Y-m-d", strtotime( $value ) ) : "";
	}
	function listBox_tiket_terbit( $field, $rows, $value )
	{
		return ( ! empty( $value ) && $value != "0000-00-00" ) ? date( "Y-m-d", strtotime( $value ) ) : "";
	}
	function listBox_dokumen_mr( $field, $rows, $value )
	{
		$filepath = base_url( "files/kajian_risiko_mr/" . $value );
		if( ! empty( $value ) )
		{
			return $value = ( file_exists( "files/kajian_risiko_mr/" . $value ) ) ? "<a href='{$filepath}'><i class='icon-file-text'></i></a>" : "";
		}

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
		 "file_type"   => "pdf|doc|xls|xlsx|docx",
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
		if( ! empty( $row["status_approval"] ) && $row["status_approval"] == "approved" )
		{
			unset( $button["delete"] );
		}
		// if( ! empty( $row["release_date"] ) )
		// {
		// 	$button['risk_register'] = [
		// 	 'label' => 'Risk Register',
		// 	 'id'    => 'btn-kajian-risk-register',
		// 	 'class' => 'text-primary',
		// 	 'icon'  => 'icon-file-upload2',
		// 	 'url'   => base_url( $this->modul_name . "/register/list/" ),
		// 	 'attr'  => ' target="_self" ',
		// 	 ];
		// }
		if( $row["status"] != 1 )
		{
			// $button['submit'] = [
			//  'label' => 'Submitted',
			//  'id'    => 'btn_submitted',
			//  'class' => 'text-success disabled',
			//  'icon'  => 'icon-checkmark-circle',
			//  'url'   => "javascript:void(0);",
			//  'attr'  => '',
			//    ];
			$button['propose'] = [
			 'label' => 'SUBMIT',
			 'id'    => 'btn_schedule_one',
			 'class' => 'text-success',
			 'icon'  => 'icon-paperplane',
			 'url'   => base_url( $this->modul_name . "/register/submit/" ),
			 'attr'  => ' target="_self" ',
			   ];
		}
		// else
		// {
		// 	$button['propose'] = [
		// 	 'label' => 'SUBMIT',
		// 	 'id'    => 'btn_schedule_one',
		// 	 'class' => 'text-success',
		// 	 'icon'  => 'icon-paperplane',
		// 	 'url'   => base_url( $this->modul_name . "/register/submit/" ),
		// 	 'attr'  => ' target="_self" ',
		// 	   ];
		// }

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

	function optionalButton( $button, $mode )
	{
		if( $mode == "edit" && $this->data_fields["data"]["status_approval"] == "approved" )
		{
			unset( $button["delete"] );
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

	function afterDelete( $id )
	{
		if( empty( $id[0] ) || ! is_numeric( (int) $id[0] ) )
		{
			return FALSE;
		}
		$getFile = $this->db->get_where( _TBL_KAJIAN_RISIKO_FILE, [ "id_kajian_risiko" => $id[0] ] )->result_array();
		if( ! empty( $getFile ) )
		{
			foreach( $getFile as $kFile => $vFile )
			{
				if( ! empty( $vFile["server_filename"] ) )
				{
					$this->db->delete( _TBL_KAJIAN_RISIKO_FILE, [ "id" => $vFile["id"] ] );
					if( file_exists( "files/kajian_risiko/" . $vFile["server_filename"] ) )
					{
						unlink( "files/kajian_risiko/" . $vFile["server_filename"] );
					}
					if( file_exists( "files/kajian_risiko_mr/" . $vFile["dokumen_mr"] ) )
					{
						unlink( "files/kajian_risiko_mr/" . $vFile["dokumen_mr"] );
					}
				}
			}
		}

		$getregister = $this->db->get_where( _TBL_KAJIAN_RISIKO_REGISTER, [ "id_kajian_risiko" => $id[0] ] )->result_array();
		if( ! empty( $getregister ) )
		{
			foreach( $getregister as $kReg => $vReg )
			{
				$getMitigasi = $this->db->get_where( _TBL_KAJIAN_RISIKO_MITIGASI, [ "id_kajian_risiko_register" => $vReg["id"] ] )->result_array();
				if( ! empty( $getMitigasi ) )
				{
					foreach( $getMitigasi as $kMitigasi => $vMitigasi )
					{
						$getMonitoring = $this->db->get_where( _TBL_KAJIAN_RISIKO_MONITORING, [ "id_kajian_risiko_mitigasi" => $vMitigasi["id"] ] )->result_array();
						if( ! empty( $getMonitoring ) )
						{
							foreach( $getMonitoring as $kMonitoring => $vMonitoring )
							{
								if( ! empty( $vMonitoring["id"] ) )
								{
									$this->db->delete( _TBL_KAJIAN_RISIKO_MONITORING, [ "id_kajian_mitigasi" => $vMitigasi["id"] ] );
									if( file_exists( "files/kajian_risiko_monitoring/" . $vMonitoring["dokumen_pendukung"] ) )
									{
										unlink( "files/kajian_risiko_monitoring/" . $vMonitoring["dokumen_pendukung"] );
									}
								}
							}
						}
					}
				}
				$this->db->delete( _TBL_KAJIAN_RISIKO_MITIGASI, [ "id_kajian_risiko_register" => $vReg["id"] ] );
			}
		}
		$this->db->delete( _TBL_KAJIAN_RISIKO_REGISTER, [ "id_kajian_risiko" => $id[0] ] );
		$this->db->delete( _TBL_KAJIAN_RISIKO_APPROVAL_HISTORY, [ "id_kajian_risiko" => $id[0] ] );
		return ( ! empty( $id ) ) ? TRUE : FALSE;

	}

	function register( $action, $idkajian )
	{
		$dataView["headerRisk"] = $this->db->get_where( _TBL_VIEW_KAJIAN_RISIKO, [ "id" => $idkajian, "active" => 1 ] )->row_array();

		if( $action == "submit" && $dataView["headerRisk"]["status"] != 1 )
		{
			$this->db->update( _TBL_KAJIAN_RISIKO, [ "status" => 1, "date_submit" => date( "Y-m-d H:i:s" ), "updated_at" => date( "Y-m-d H:i:s" ), "updated_by" => $this->ion_auth->get_user_name(), "status_approval" => "review" ], [ "id" => $idkajian ] );
			$this->proposeRisikoHistory( $idkajian );
			$dataView["headerRisk"]["status"] = 1;
			$this->session->set_flashdata( 'message_crud', "Berhasil Submit Data {$dataView["headerRisk"]['name']} !" );
			redirect( $this->modul_name );
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
}
