<?php if( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Ajax extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();

		if( ! $this->input->is_ajax_request() )
		{
			header( 'location' . base_url() );
			exit();
		}
		$this->load->language( 'risk_context' );
	}

	function download()
	{
		$this->load->helper( 'download' );
		$kel    = $this->uri->segment( 3 );
		$nmfile = $this->uri->segment( 4 );
		// $nmfile=cek_nama_file();

		switch( $kel )
		{
			case "sertifikat":
				if( file_exists( sertifikat_path_relative( $nmfile ) ) )
				{
					force_download( sertifikat_path_relative( $nmfile ), NULL, TRUE );
				}
				break;
			case "bukti":
				if( file_exists( bukti_path_relative( $nmfile ) ) )
				{
					force_download( bukti_path_relative( $nmfile ), NULL, TRUE );
				}
				break;
			case "events":
				if( file_exists( events_path_relative( $nmfile ) ) )
				{
					force_download( events_path_relative( $nmfile ), NULL, TRUE );
				}
				break;
			case "backup":
				if( file_exists( backup_path_relative( $nmfile ) ) )
				{
					force_download( backup_path_relative( $nmfile ), NULL, TRUE );
				}
				break;
			default:
				header( 'location:' . base_url() );
				break;
		}
	}

	function download_preview()
	{
		$this->load->helper( 'download' );
		$kel    = $this->input->post( 'kel' );
		$nmfile = $this->input->post( 'file' );
		// $nmfile=cek_nama_file();
		$hasil = 'Tidak ada file';
		$path  = $kel . '_path_relative';
		$url   = $kel . '_url';
		if( file_exists( $path( $nmfile ) ) )
		{
			$hasil = '<div class="row"><div class="col-sm-offset-2 col-sm-8"><span class="media" href="' . $url( $nmfile ) . '"></span></div></div>
					
					<script type="text/javascript">
						$(function () {
							$(".media").media();
						});

						$(document).ready(function() {
							$("iframe").attr("width", "100%");
							$(".media").css("width", "100%");
							$(".media").find("img").attr("width", "100%");
						})
					</script>';
		}
		echo $hasil;
	}

	function get_register()
	{
		$id   = $this->input->post( 'rcsa_id' );
		$data = $this->data->get_data_register( $id );
		if( ! empty( $data["rows"] ) )
		{
			foreach( $data["rows"] as $key => $value )
			{
				$data['rows'][$key]["like_target"]        = ( ! empty( $value["treatment"] ) && strtolower( $value["treatment"] ) == "menerima" ? "" : $value["like_target"] );
				$data['rows'][$key]["impact_target"]      = ( ! empty( $value["treatment"] ) && strtolower( $value["treatment"] ) == "menerima" ? "" : $value["impact_target"] );
				$data['rows'][$key]["risiko_target_text"] = ( ! empty( $value["treatment"] ) && strtolower( $value["treatment"] ) == "menerima" ? "" : $value["risiko_target_text"] );
				$data['rows'][$key]["level_color_target"] = ( ! empty( $value["treatment"] ) && strtolower( $value["treatment"] ) == "menerima" ? "" : $value["level_color_target"] );
				$data['rows'][$key]["color_target"]       = ( ! empty( $value["treatment"] ) && strtolower( $value["treatment"] ) == "menerima" ? "" : $value["color_target"] );
				$data['rows'][$key]["color_text_target"]  = ( ! empty( $value["treatment"] ) && strtolower( $value["treatment"] ) == "menerima" ? "" : $value["color_text_target"] );
				$data['rows'][$key]["efek_mitigasi_text"] = ( ! empty( $value["treatment"] ) && strtolower( $value["treatment"] ) == "menerima" ? "" : $value["efek_mitigasi_text"] );
			}
		}
		$data['id'] = $id;
		$cbominggu  = $this->data->get_data_minggu_per_bulan( $data['parent']['term_id'] );

		$minggu                  = ( $data['parent']['minggu_id'] ) ? $cbominggu[$data['parent']['minggu_id']] : '';
		$term                    = $this->crud->combo_select( [ 'id', 'data' ] )->combo_where( 'kelompok', 'term' )->combo_where( 'active', 1 )->combo_tbl( _TBL_COMBO )->get_combo()->result_combo();
		$data['parent']['bulan'] = ( ! empty( $term[$data['parent']['term_id']] ) ) ? $term[$data['parent']['term_id']] . ' - ' . $minggu : "";
		$x['combo']              = $this->load->view( 'risk_context/register', $data, TRUE );
		header( 'Content-type: application/json' );
		echo json_encode( $x );
	}

	function get_monitoring()
	{
		$id         = $this->input->post( 'rcsa_id' );
		$data       = $this->data->get_data_monitoring( $id );
		$data['id'] = $id;
		$x['combo'] = $this->load->view( 'risk_context/monitoring', $data, TRUE );
		header( 'Content-type: application/json' );
		echo json_encode( $x );
	}

	function get_rist_type()
	{
		$id = $this->input->post( 'id' );
		$x  = $this->data->get_data_type_risk( $id );
		header( 'Content-type: application/json' );
		echo json_encode( $x );
	}

	function get_term()
	{
		$id = $this->input->post( 'id' );
		$x  = $this->data->get_data_term( $id );
		header( 'Content-type: application/json' );
		echo json_encode( $x );
	}

	function get_minggu()
	{
		$id = $this->input->post( 'id' );
		$x  = $this->data->get_data_minggu( $id );
		header( 'Content-type: application/json' );
		echo json_encode( $x );
	}

	function get_minggu_by_tahun()
	{
		$id = $this->input->post( 'id' );
		$x  = $this->data->get_data_minggu_by_tahun( $id );
		header( 'Content-type: application/json' );
		echo json_encode( $x );
	}

	function get_risiko_inherent()
	{
		$post = $this->input->post();
		// var_dump( $post );
		// exit;
		if( isset( $post['tipe'] ) )
		{
			if( $post['tipe'] == 3 )
			{
				$x = $this->data->get_data_inherent_semi( $post );
			}
			else
			{
				$x = $this->data->get_data_inherent( $post );
			}
		}
		else
		{
			$x = $this->data->get_data_inherent( $post );
		}
		header( 'Content-type: application/json' );
		echo json_encode( $x );
	}

	function get_risiko_inherent_semi()
	{
		$post = $this->input->post();
		$x    = $this->data->get_data_inherent_semi( $post );
		header( 'Content-type: application/json' );
		echo json_encode( $x );
	}

	function get_risiko_dampak()
	{
		$post = $this->input->post();
		$x    = $this->data->get_data_dampak( $post );
		header( 'Content-type: application/json' );
		echo json_encode( $x );
	}

	function get_library()
	{
		$post = $this->input->post();
		$x    = $this->data->get_data_library( $post );
		header( 'Content-type: application/json' );
		echo json_encode( $x );
	}

	function get_detail_library()
	{
		$id           = $this->input->post();
		$x            = $this->data->get_data_used_library( $id );
		$x['library'] = $this->load->view( 'used-library', $x, TRUE );
		header( 'Content-type: application/json' );
		echo json_encode( $x );
	}

	function get_kri()
	{
		$post = $this->input->post();
		$x    = $this->data->get_data_kri( $post['id'] );
		header( 'Content-type: application/json' );
		echo json_encode( $x );
	}
	function get_detail_map()
	{
		$post            = $this->input->post();
		$this->data->pos = $post;
		$x               = $this->data->get_data_map();
		$hasil['combo']  = $this->load->view( 'identifikasi', $x, TRUE );
		header( 'Content-type: application/json' );
		echo json_encode( $hasil );
	}

	function indikator_like( $post = [] )
	{
		// if(!$post){
		// $post=$this->input->post();

		$post['hasil'] = $this->data->update_list_indi_like( [ 'rcsa_detail_no' => $post['id'], 'bk_tipe' => 2, 'dampak_id' => $post['dampak_id'] ] );

		// }

		$data['param'] = $post['hasil'];
		// $data['mLike']=$x;
		$data['list_like_indi'] = $this->db->where( 'bk_tipe', 2 )->where( 'rcsa_detail_id', intval( $post['id'] ) )->or_group_start()->where( 'rcsa_detail_id', 0 )->where( 'created_by', $this->ion_auth->get_user_name() )->group_end()->get( _TBL_VIEW_RCSA_DET_LIKE_INDI )->result_array();

		$data['parent'] = $post['id'];

		$data['sub_title'] = ' Residual';
		$result            = $this->load->view( 'indikator-like-residual', $data, TRUE );

		return $result;
	}

	function get_detail_rcsa()
	{
		$post            = $this->input->post();
		$this->data->pos = $post;
		$x               = $this->data->get_data_detail_rcsa();
		$x['kpi']        = $this->indikator_like( $post );

		$hasil['combo'] = $this->load->view( 'mitigasi', $x, TRUE );
		header( 'Content-type: application/json' );
		echo json_encode( $hasil );
	}
	function get_detail_mitigasi()
	{
		$post            = $this->input->post();
		$this->data->pos = $post;
		$x               = $this->data->get_data_detail_mitigasi();
		$hasil['combo']  = $this->load->view( 'aktifitas-mitigasi', $x, TRUE );
		header( 'Content-type: application/json' );
		echo json_encode( $hasil );
	}
	function get_detail_progres_mitigasi()
	{
		$post            = $this->input->post();
		$this->data->pos = $post;
		$x               = $this->data->get_detail_progres_mitigasi();
		$hasil['combo']  = $this->load->view( 'progres-aktifitas-mitigasi', $x, TRUE );
		header( 'Content-type: application/json' );
		echo json_encode( $hasil );
	}

	function get_like_aspekrisiko()
	{
		$post            = $this->input->post();
		$this->data->pos = $post;
		$x               = $this->data->get_data_like_aspek_risiko();
		header( 'Content-type: application/json' );
		echo json_encode( $x );
	}

	function get_owner_code()
	{
		$post       = $this->input->post();
		$data       = $this->db->get_where( _TBL_OWNER, array( 'id' => $post['id'] ), 1 );
		$x['combo'] = $data->row()->owner_code;
		header( 'Content-type: application/json' );
		echo json_encode( $x );
	}

	function get_peristiwa()
	{
		$post = $this->input->post();
		$rows = $this->crud->combo_select( [ 'id', 'CONCAT(kode_dept,"-",kode_aktifitas,"-",LPAD(kode_risiko_dept,3,0),"  ",risiko_dept) as kode' ] )->combo_where( 'owner_id', $post['id'] )->combo_sort( 'kode_dept' )->combo_sort( 'kode' )->combo_tbl( _TBL_VIEW_RCSA_DETAIL )->get_combo()->result_combo();

		$option = '';
		foreach( $rows as $key => $row )
		{
			// $option .= '<option value="'.$row->id.'">'.$row->param_string.' Minggu ke - '.$row->data. '  ('.date('d-m-Y',strtotime($row->param_date)).' s.d '.date('d-m-Y',strtotime($row->param_date_after)).')</option>';
			$option .= '<option value="' . $key . '">' . $row . '</option>';
		}

		$x['combo'] = $option;

		header( 'Content-type: application/json' );
		echo json_encode( $x );
	}

	function check_similarity_lib()
	{
		$entry = $this->input->post( 'library' );
		$type  = $this->input->post( 'type' );
		$perc  = $this->input->post( 'percent' );
		$tn    = "Semua Library";
		if( $type > 0 )
		{
			if( $type = 2 )
			{
				$tn = "Peristiwa Risiko";
			}
			elseif( $type = 1 )
			{
				$tn = "Penyebab Risiko";
			}
			elseif( $type = 4 )
			{
				$tn = "Dampak Risiko";
			}
			$this->db->where( 'type', $type );
		}
		$library_data = $this->db->get( _TBL_VIEW_LIBRARY, 10 )->result();
		$results      = [];
		foreach( $library_data as $row )
		{
			$entry   = trim( strtolower( $entry ) );
			$library = trim( strtolower( $row->library ) );
			similar_text( $entry, $library, $percent );
			if( $percent > $perc )
			{
				$results[] = [
				 'id'            => $row->id,
				 'nama'          => $row->library,
				 'nama_kelompok' => $row->nama_kelompok,
				 'risk_type'     => $row->risk_type,
				 'similarity'    => round( $percent, 2 ),
				];
			}
		}

		usort( $results, function ($a, $b)
		{

			return $b['similarity'] <=> $a['similarity'];
		} );

		ob_clean();
		$x['lib']       = $tn;
		$x['percent']   = $perc;
		$x['entry']     = $entry;
		$x['rows']      = $results;
		$hasil['combo'] = $this->load->view( 'lib-similarity', $x, TRUE );
		header( 'Content-type: application/json' );
		echo json_encode( $hasil );
	}



}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
