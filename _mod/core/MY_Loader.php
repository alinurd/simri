<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Loader class */
require APPPATH."third_party/MX/Loader.php";

class MY_Loader extends MX_Loader {

    /**
 * is_view
 *
 * Check if a view exists or not through the loaded paths
 *
 * @param   string          $view           The relative path of the file
 *
 * @return  string|bool     string          containing the path if file exists
 *                          false           if file is not found
 */
public function is_view($view)
{
    // ! BEWARE $path contains a beginning trailing slash !
    list($paths, $_view) = Modules::find($view, $this->_module, 'views/');
    $path_file=$paths.$_view;
    if( is_file( $path_file ) ) 
        return true;
    else
        return false;
}

}