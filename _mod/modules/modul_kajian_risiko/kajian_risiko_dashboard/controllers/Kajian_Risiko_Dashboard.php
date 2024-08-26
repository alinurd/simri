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
        $dataGraph           = [
             [ "value" => 1048, "name" => "Search engine" ],
             [ "value" => 735, "name" => "Direct" ],
             [ "value" => 580, "name" => "Email" ],
             [ "value" => 484, "name" => "Union Ads" ],
             [ "value" => 300, "name" => "Video Ads" ],
        ];
        $dataBar             = [ "label" => [ 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun' ], "value" => [ 120, 200, 150, 80, 70, 110, 130 ] ];
        $dataview["graph1"]  = json_encode( $dataGraph );
        $dataview["graph2"]  = json_encode( $dataGraph );
        $dataview["graph3"]  = json_encode( $dataGraph );
        $dataview["databar"] = json_encode( $dataBar );

        $dataview["graph1"] = $this->load->view( "graph1", $dataview, TRUE );
        $dataview["graph2"] = $this->load->view( "graph2", $dataview, TRUE );
        $dataview["graph3"] = $this->load->view( "graph3", $dataview, TRUE );
        $dataview["bar"]    = $this->load->view( "bar", $dataview, TRUE );
        return $this->load->view( "dashboard", $dataview, TRUE );
    }
}
