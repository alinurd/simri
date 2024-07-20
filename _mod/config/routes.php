<?php
defined( 'BASEPATH' ) or exit( 'No direct script access allowed' );

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
// $route['default_controller'] = 'dashboard';
// $route['login'] = 'auth/login';
// $route['forgot-password'] = 'auth/forgot_password';
// $route['lock-screen'] = 'auth/lock-screen';
// $route['404_override'] = 'access_denied/modul';
// $route['translate_uri_dashes'] = TRUE;

// var_dump($_SERVER);
$route['default_controller'] = 'auth/login';
$route['login']              = 'auth/login';
// $route['forgot-password']         = 'auth/forgot-password';
$route['recovery-password']       = 'auth/forgot-password';
$route['code-confirmation']       = 'auth/code-confirmation';
$route['lock-screen']             = 'auth/lock-screen';
$route['api/frontend/(:any)']     = 'api-frontend/$1';
$route['proses-registration']     = 'cli/proses-registration';
$route['proses-registration-web'] = 'cli/proses-registration-web';
$route['proses-forgot-password']  = 'cli/proses-forgot-password';
$route['404_override']            = 'errorpage/modul';
// $route['404_override'] = 'api/error';
$route['translate_uri_dashes'] = TRUE;
