<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Sekati CodeIgniter Asset Helper
 *
 * @package		Sekati
 * @author		Jason M Horwitz
 * @copyright	Copyright (c) 2013, Sekati LLC.
 * @license		http://www.opensource.org/licenses/mit-license.php
 * @link		http://sekati.com
 * @version		v1.2.7
 * @filesource
 *
 * @usage 		$autoload['config'] = array('asset');
 * 				$autoload['helper'] = array('asset');
 * @example		<img src="<?=asset_url();?>imgs/photo.jpg" />
 * @example		<?=img('photo.jpg')?>
 *
 * @install		Copy config/asset.php to your CI application/config directory
 *				& helpers/asset_helper.php to your application/helpers/ directory.
 * 				Then add both files as autoloads in application/autoload.php:
 *
 *				$autoload['config'] = array('asset');
 * 				$autoload['helper'] = array('asset');
 *
 *				Autoload CodeIgniter's url_helper in application/config/autoload.php:
 *				$autoload['helper'] = array('url');
 *
 * @notes		Organized assets in the top level of your CodeIgniter 2.x app:
 *					- assets/
 *						-- css/
 *						-- download/
 *						-- img/
 *						-- js/
 *						-- less/
 *						-- swf/
 *						-- upload/
 *						-- xml/
 *					- application/
 * 						-- config/asset.php
 * 						-- helpers/asset_helper.php
 */

// ------------------------------------------------------------------------
// URL HELPERS

/**
 * Get asset URL
 *
 * @access  public
 * @return  string
 */
if ( ! function_exists('asset_url'))
{
    function asset_url($value='')
    {
        //get an instance of CI so we can access our configuration
        $CI =& get_instance();
        //return the full asset path
        return base_url() . $CI->config->item('asset_path') . $value;
    }
}

if ( ! function_exists('css_url'))
{
    function css_url($value='')
    {
        $CI =& get_instance();
        return base_url() . $CI->config->item('css_path') . $value;
    }
}

if ( ! function_exists('css_frontend_url'))
{
    function css_frontend_url($value='')
    {
        $CI =& get_instance();
        return base_url() . $CI->config->item('css_frontend_path') . $value;
    }
}

if ( ! function_exists('less_url'))
{
    function less_url($value='')
    {
        $CI =& get_instance();
        return base_url()  . $CI->config->item('less_path') . $value;
    }
}

if ( ! function_exists('js_url'))
{
    function js_url($value='')
    {
        $CI =& get_instance();
        return base_url()  . $CI->config->item('js_path') . $value ;
    }
}

if ( ! function_exists('js_frontend_url'))
{
    function js_frontend_url($value='')
    {
        $CI =& get_instance();
        return base_url()  . $CI->config->item('js_frontend_path') . $value ;
    }
}


if ( ! function_exists('img_url'))
{
    function img_url($value='')
    {
		// die($value);
        $CI =& get_instance();
        return base_url()  . $CI->config->item('img_path') . $value;
    }
}

if ( ! function_exists('img_admin_url'))
{
    function img_admin_url($value='')
    {
		// die($value);
        $CI =& get_instance();
        return base_url()  . $CI->config->item('img_admin_path') . $value;
    }
}

if ( ! function_exists('swf_url'))
{
    function swf_url($value='')
    {
        $CI =& get_instance();
        return base_url()  . $CI->config->item('swf_path') . $value;
    }
}

if ( ! function_exists('file_url'))
{
    function file_url($value='')
    {
        $CI =& get_instance();
        return base_url()  . $CI->config->item('file_path') . $value;
    }
}

// ------------------------------------------------------------------------
// PATH HELPERS

/**
 * Get asset Path
 *
 * @access  public
 * @return  string
 */
if ( ! function_exists('asset_path'))
{
    function asset_path($value='')
    {
        //get an instance of CI so we can access our configuration
        $CI =& get_instance();
        return FCPATH . $CI->config->item('asset_path') . $value;
    }
}

if ( ! function_exists('css_path'))
{
    function css_path($value='')
    {
        //get an instance of CI so we can access our configuration
        $CI =& get_instance();
        return FCPATH . $CI->config->item('css_path') . $value;
    }
}

if ( ! function_exists('css_frontend_path'))
{
    function css_frontend_path($value='')
    {
        //get an instance of CI so we can access our configuration
        $CI =& get_instance();
        return FCPATH . $CI->config->item('css_frontend_path') . $value;
    }
}

if ( ! function_exists('less_path'))
{
    function less_path($value='')
    {
        //get an instance of CI so we can access our configuration
        $CI =& get_instance();
        return FCPATH . $CI->config->item('less_path') . $value;
    }
}

if ( ! function_exists('js_path'))
{
    function js_path($value='')
    {
        //get an instance of CI so we can access our configuration
        $CI =& get_instance();
        return FCPATH . $CI->config->item('js_path') . $value;
    }
}

if ( ! function_exists('js_frontend_path'))
{
    function js_frontend_path($value='')
    {
        //get an instance of CI so we can access our configuration
        $CI =& get_instance();
        return FCPATH . $CI->config->item('js_frontend_path') . $value;
    }
}

if ( ! function_exists('img_path'))
{
    function img_path($value='')
    {
        //get an instance of CI so we can access our configuration
        $CI =& get_instance();
        return FCPATH . $CI->config->item('img_path') . $value;
    }
}



if ( ! function_exists('img_frontend_path'))
{
    function img_frontend_path($value='')
    {
        //get an instance of CI so we can access our configuration
        $CI =& get_instance();
        return FCPATH . $CI->config->item('img_frontend_path') . $value;
    }
}

if ( ! function_exists('swf_path'))
{
    function swf_path($halu='')
    {
        $CI =& get_instance();
        return FCPATH . $CI->config->item('swf_path') . $value;
    }
}

if ( ! function_exists('xml_path'))
{
    function xml_path($value='')
    {
        $CI =& get_instance();
        return FCPATH . $CI->config->item('xml_path') . $value;
    }
}


if ( ! function_exists('file_path'))
{
    function file_path($value='')
    {
        $CI =& get_instance();
        return FCPATH . $CI->config->item('file_path') . $value;
    }
}


if ( ! function_exists('file_path_relative'))
{
    function file_path_relative($value='')
    {
        $CI =& get_instance();
        return './' . $CI->config->item('file_path') . $value;
    }
}


if ( ! function_exists('img_path_relative'))
{
    function img_path_relative($value='')
    {
        $CI =& get_instance();
        return './' . $CI->config->item('img_path') . $value;
    }
}




if ( ! function_exists('order_path_relative'))
{
    function order_path_relative($value='')
    {
        $CI =& get_instance();
        return './' . $CI->config->item('order_path') . $value;
    }
}

// ------------------------------------------------------------------------
// EMBED HELPERS

/**
 * Load CSS
 * Creates the <link> tag that links all requested css file
 * @access  public
 * @param   string
 * @return  string
 */
if ( ! function_exists('css'))
{
    function css($file, $media='all')
    {
        return '<link rel="stylesheet" type="text/css" href="' . css_url() . $file . '" media="' . $media . '">'."\n";
    }
}

if ( ! function_exists('css_frontend'))
{
    function css_frontend($file, $media='all')
    {
        return '<link rel="stylesheet" type="text/css" href="' . css_url() . $file . '" media="' . $media . '">'."\n";
    }
}

/**
 * Load LESS
 * Creates the <link> tag that links all requested LESS file
 * @access  public
 * @param   string
 * @return  string
 */
if ( ! function_exists('less'))
{
    function less($file)
    {
        return '<link rel="stylesheet/less" type="text/css" href="' . less_url() . $file . '">'."\n";
    }
}

/**
 * Load JS
 * Creates the <script> tag that links all requested js file
 * @access  public
 * @param   string
 * @param 	array 	$atts Optional, additional key/value attributes to include in the SCRIPT tag
 * @return  string
 */
if ( ! function_exists('js'))
{
    function js($file, $atts = array())
    {
        $element = '<script type="text/javascript" src="' . js_url() . $file . '"';

		foreach ( $atts as $key => $val )
			$element .= ' ' . $key . '="' . $val . '"';
		$element .= '></script>'."\n";

		return $element;
    }
}

if ( ! function_exists('js_frontend'))
{
    function js_frontend($file, $atts = array())
    {
        $element = '<script type="text/javascript" src="' . js_url() . $file . '"';

		foreach ( $atts as $key => $val )
			$element .= ' ' . $key . '="' . $val . '"';
		$element .= '></script>'."\n";

		return $element;
    }
}


if ( ! function_exists('img'))
{
    function img($file, $paths='img',  $atts = array(), $preset)
    {
        $ci = &get_instance();
        
        // load the allowed image presets
        $ci->load->config("configuration", true);
        $sizes = $ci->config->item("image_sizes", 'configuration');
        $url = $paths.'_url';
        $path = $paths.'_path_relative';
        $image_path=$path($file);
        $pathinfo = pathinfo($image_path);
        $new_path = $image_path;
        $dir=explode('/',$pathinfo['dirname']);
        unset($dir[0]);
        unset($dir[1]);
        $dir=implode('/',$dir);
        // check if requested preset exists
        if (array_key_exists($preset,$sizes)) {
            if (array_key_exists("extension", $pathinfo)){
                $new_path = $url($dir."/thumb-" . implode("x", $sizes[$preset]) .'/' . $pathinfo["filename"] . "-" . implode("x", $sizes[$preset]) . "." . $pathinfo["extension"]);
            }
        }else{
            $new_path = $url($dir."/" . $pathinfo["filename"] . "." . $pathinfo["extension"]);

        }

		$url = '<img src="' . $new_path . '"';
		foreach ( $atts as $key => $val )
			$url .= ' ' . $key . '="' . $val . '"';
		$url .= " />\n";
        return $url;
    }
}

if ( ! function_exists('img_frontend'))
{
    function img_frontend($file,  $atts = array())
    {
		$url = '<img src="' . img_url() . $file . '"';
		foreach ( $atts as $key => $val )
			$url .= ' ' . $key . '="' . $val . '"';
		$url .= " />\n";
        return $url;
    }
}

if ( ! function_exists('jquery'))
{
    function jquery($version='')
    {
    	// Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline
  		$out = '<script src="//ajax.googleapis.com/ajax/libs/jquery/'.$version.'/jquery.min.js"></script>'."\n";
  		$out .= '<script>window.jQuery || document.write(\'<script src="'.js_url().'jquery-'.$version.'.min.js"><\/script>\')</script>'."\n";
        return $out;
    }
}

if ( ! function_exists('google_analytics'))
{
    function google_analytics($ua='')
    {
    	// Change UA-XXXXX-X to be your site's ID
	    $out = "<!-- Google Webmaster Tools & Analytics -->\n";
	    $out .='<script type="text/javascript">';
		$out .='	var _gaq = _gaq || [];';
		$out .="    _gaq.push(['_setAccount', '$ua']);";
		$out .="    _gaq.push(['_trackPageview']);";
		$out .='    (function() {';
		$out .="      var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;";
		$out .="      ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';";
		$out .="      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);";
		$out .="    })();";
	    $out .="</script>";
        return $out;
    }
}
/* End of file asset_helper.php */