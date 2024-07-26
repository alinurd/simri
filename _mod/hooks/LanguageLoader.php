<?php
defined( 'BASEPATH' ) or exit( 'No direct script access allowed' );

class LanguageLoader
{
    function initialize()
    {
        $ci       =& get_instance();
        $siteLang = $ci->session->userdata( 'site_lang' );
        if( ! $siteLang )
            $siteLang = 'english';
        define( '_BAHASA_', $siteLang );
        $arr_bahasa = array( 'datatable', 'share', $ci->router->fetch_module() );
        foreach( $arr_bahasa as $bhs )
        {
            if( file_exists( APPPATH . '/language/' . $siteLang . '/' . $bhs . '_lang.php' ) )
            {
                $ci->lang->load( $bhs, $siteLang );
            }
        }
    }
}
