<?php if( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class Kajian_Risiko_Dashboard extends MY_Controller
{
    private $configurationDashboard = [
    "box_content"         => FALSE,
    "show_action_button"  => FALSE,
    "show_header_content" => FALSE,

    ];
    public function __construct()
    {
        parent::__construct();
        $this->configuration = array_merge( $this->configuration, $this->configurationDashboard );
    }

    function content()
    {
        $dataview["labeltahun"] = date( "Y" );
        $dataview["graph1"]     = $this->getDataChart( "status_kajian", FALSE );
        $dataview["graph2"]     = $this->getDataChart( "status_approval", FALSE );
        $dataview["graph3"]     = $this->getDataChart( "status_progress", FALSE );
        $dataview["bar1"]       = $this->getDataChart( "tiket_terbit", FALSE );
        $dataview["bar2"]       = $this->getDataChart( "tanggal_release", FALSE );
        $dataview["graph1"]     = $this->load->view( "graph1", $dataview, TRUE );
        $dataview["graph2"]     = $this->load->view( "graph2", $dataview, TRUE );
        $dataview["graph3"]     = $this->load->view( "graph3", $dataview, TRUE );
        $dataview["bar"]        = $this->load->view( "bar1", $dataview, TRUE );
        $dataview["bar2"]       = $this->load->view( "bar2", $dataview, TRUE );
        $dataview["tahun"]      = $this->getperiodeSelect2();

        return $this->load->view( "dashboard", $dataview, TRUE );
    }

    function getperiodeSelect2()
    {
        $result = '';
        $tahun  = $this->db->order_by( "data", "desc" )->get_where( _TBL_COMBO, [ "kelompok" => "period", "active" => 1 ] )->result_array();
        if( ! empty( $tahun ) )
        {
            foreach( $tahun as $kTh => $vTh )
            {
                $result .= "<option value='{$vTh["data"]}'>" . $vTh["data"] . "</option>";
            }
        }
        return $result;
    }

    function getDataChart( $type, $filter = FALSE )
    {
        $dataChart       = [];
        $datamapStatus   = [ "Draft", "Submitted", "Revisi" ];
        $datamapApproval = [ "Rejected", "Review", "Approved" ];
        $datamapProgress = [ "Not Started", "On Progress", "Closed" ];
        $datamapfileajax = [ "tiket_terbit" => "bar1", "tanggal_release" => "bar2", "status_kajian" => "graph1", "status_approval" => "graph2", "status_progress" => "graph3" ];

        for( $m = 1; $m <= 12; $m++ )
        {
            $bulan[] = date( 'F', mktime( 0, 0, 0, $m, 1, date( 'Y' ) ) );
        }
        switch( $type )
        {
            case 'tiket_terbit':
                foreach( $bulan as $kMonth => $vMonth )
                {
                    $valueTiket         = $this->db->select( "count(id) as value" )->group_by( "month(tiket_terbit)" )->get_where( _TBL_KAJIAN_RISIKO, [ "month(tiket_terbit)" => $kMonth + 1, "year(tiket_terbit)" => ( $filter ) ? $filter : date( "Y" ), "active" => 1 ] )->row_array();
                    $dataValTiket[]     = ! empty( $valueTiket["value"] ) ? (int) $valueTiket["value"] : 0;
                    $dataChart["label"] = $bulan;
                    $dataChart["value"] = $dataValTiket;
                }
                break;
            case 'tanggal_release':
                foreach( $bulan as $kMonth => $vMonth )
                {
                    $valueRelease       = $this->db->select( "count(id) as value" )->group_by( "month(release_date)" )->get_where( _TBL_KAJIAN_RISIKO, [ "month(release_date)" => $kMonth + 1, "year(release_date)" => ( $filter ) ? $filter : date( "Y" ), "active" => 1 ] )->row_array();
                    $dataValRelease[]   = ! empty( $valueRelease["value"] ) ? (int) $valueRelease["value"] : 0;
                    $dataChart["label"] = $bulan;
                    $dataChart["value"] = $dataValRelease;
                }
                break;
            case 'status_kajian':
                foreach( $datamapStatus as $kSts => $vSts )
                {
                    $valueStatus = $this->db->select( "status,count(id) as value" )->group_by( "year(created_at)" )->get_where( _TBL_KAJIAN_RISIKO, [ "status" => $kSts, "year(created_at)" => ( $filter ) ? $filter : date( "Y" ), "active" => 1 ] )->row_array();

                    $dataValStatuse[$kSts] = ! empty( $valueStatus ) ? [ "value" => $valueStatus["value"], "name" => $vSts ] : [ "value" => 0, "name" => $vSts ];
                }
                $dataChart = $dataValStatuse;
                break;
            case 'status_approval':
                foreach( $datamapApproval as $kAppr => $vAppr )
                {
                    $valueStatus = $this->db->select( "status,count(id) as value" )->group_by( "year(created_at)" )->get_where( _TBL_KAJIAN_RISIKO, [ "status_approval" => strtolower( $vAppr ), "year(created_at)" => ( $filter ) ? $filter : date( "Y" ), "active" => 1 ] )->row_array();

                    $dataValAppr[$kAppr] = ! empty( $valueStatus ) ? [ "value" => $valueStatus["value"], "name" => $vAppr ] : [ "value" => 0, "name" => $vAppr ];
                }
                $dataChart = $dataValAppr;
                break;
            case 'status_progress':
                foreach( $datamapProgress as $kPorg => $vProg )
                {
                    $valueProg = $this->db->select( "status,count(id) as value" )->group_by( "year(created_at)" )->get_where( _TBL_KAJIAN_RISIKO_MONITORING, [ "status" => strtolower( url_title( $vProg ) ), "year(created_at)" => ( $filter ) ? $filter : date( "Y" ) ] )->row_array();

                    $dataValProg[$kPorg] = ! empty( $valueProg ) ? [ "value" => $valueProg["value"], "name" => $vProg ] : [ "value" => 0, "name" => $vProg ];
                }
                $dataChart = $dataValProg;
                break;
            default:
                break;
        }

        if( $this->input->is_ajax_request() )
        {
            $data["content"]        = $this->load->view( $datamapfileajax[$type], [ $datamapfileajax[$type] => json_encode( $dataChart ), "labeltahun" => $filter ], TRUE );
            $data["tahun"]          = $this->getperiodeSelect2();
            $data["identity"]       = $type;
            $data["identityFilter"] = "filter-" . str_replace( "_", "-", $type );
            echo $this->load->view( "ajax/ajax_filter_tahun", $data, TRUE );
        }
        else
        {
            return json_encode( $dataChart );
        }
    }

    function getDataModalDashboard()
    {
        $datamapStatus = [ "draft" => 0, "revisi" => 2, "submitted" => 1 ];
        if( ! empty( $this->input->post() ) )
        {
            $viewContent = [];
            $dataview    = [];
            $type        = $this->input->post()["type"];
            $name        = $this->input->post()["name"];
            switch( $type )
            {
                case "tiket_terbit":
                    $dataKajian["data"] = $this->db->get_where( _TBL_VIEW_KAJIAN_RISIKO, [ "month(tiket_terbit)" => date( "n", strtotime( $name ) ), "active" => 1 ] )->result_array();
                    $viewContent["content"] = $this->load->view( "ajax/ajaxlistdashboard", $dataKajian, TRUE );
                    $viewContent["title"] = "kajian Resiko Tiket Terbit Berdasarkan Bulan";
                    break;
                case "tanggal_release":
                    $dataKajian["data"] = $this->db->get_where( _TBL_VIEW_KAJIAN_RISIKO, [ "month(release_date)" => date( "n", strtotime( $name ) ), "active" => 1 ] )->result_array();
                    $viewContent["content"] = $this->load->view( "ajax/ajaxlistdashboard", $dataKajian, TRUE );
                    $viewContent["title"] = "kajian Resiko Tanggal Release Berdasarkan Bulan";
                    break;
                case "status_kajian":
                    $dataKajian["data"] = $this->db->get_where( _TBL_VIEW_KAJIAN_RISIKO, [ "status" => $datamapStatus[strtolower( $name )], "active" => 1 ] )->result_array();
                    $viewContent["content"] = $this->load->view( "ajax/ajaxlistdashboard", $dataKajian, TRUE );
                    $viewContent["title"] = "Kajian Risiko Berdasarkan Status";
                    break;
                case "status_approval":
                    $dataKajian["data"] = $this->db->get_where( _TBL_VIEW_KAJIAN_RISIKO, [ "status_approval" => strtolower( $name ), "active" => 1 ] )->result_array();
                    $viewContent["content"] = $this->load->view( "ajax/ajaxlistdashboard", $dataKajian, TRUE );
                    $viewContent["title"] = "Kajian Risiko Berdasarkan Status Approval";
                    break;
                case "status_progress":
                    $dataKajian["data"] = $this->db->get_where( _TBL_VIEW_KAJIAN_RISIKO_MONITORING, [ "status" => strtolower( url_title( $name ) ) ] )->result_array();
                    $viewContent["content"] = $this->load->view( "ajax/ajaxlistmonitoring", $dataKajian, TRUE );
                    $viewContent["title"] = "Status Progress Mitigasi";
                    break;
                default:
                    break;
            }
        }
        echo json_encode( $viewContent );
    }
}
