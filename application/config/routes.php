<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
|	http://codeigniter.com/user_guide/general/routing.html
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
$route['default_controller'] = 'admin_auth/index';
//$route['infonet'] = 'infonet_dashboard/index';
//$route['default_controller'] = 'auth_admin/index';

#$route['404_override'] = 'Error404_Controller';
$route['404_override'] = '';


$route['translate_uri_dashes'] = FALSE;


############
# ARRESTO ROUTING 
#$route['default_controller'] = 'arresto_auth/index';

$route['info'] = 'info/index';

// excule the API Controller from the Arresto domain routing 
$route['api_controller/(.+)'] = "api_controller/$1";
$route['asm_api/(.+)'] = "asm_api/$1"; 


#$route['admin'] = "system_admin/arresto_auth";  // admin url redirect


/*$route['admin'] = "system_admin/auth";  // admin url redirect
$route['admin/(.+)'] = "system_admin/$1";  // admin url redirect*/

$route['admin'] = "admin/auth";  // admin url redirect
$route['admin/(.+)'] = "admin/$1";  // admin url redirect
$route['pdm_api/(.+)'] = "pdm_api/$1"; // pdm Api urls

// Client_name & service_name URL redirection
$route['([A-Za-z0-9-]+)/(kare|knowledge-tree|asm)'] = "auth/index/$1/$2";

$route['([A-Za-z0-9-]+)/kare/(.+)'] = "$2";
$route['([A-Za-z0-9-]+)/knowledge-tree/(.+)'] = "$2"; 
$route['([A-Za-z0-9-]+)/asm/(.+)'] = "$2"; 

$route['([A-Za-z0-9-]+)'] = "auth/index";    // clients url redirects
$route['([A-Za-z0-9-]+)/(.+)'] = "$2";    // clients url redirects






/*$route['admin'] = 'auth/index';
$route['admin/login(.html)?'] = 'auth/index';
$route['admin/logout(.html)?'] = 	'auth/logout';

$route['admin/register']='auth/reister';
$route['admin/forgot_password(.html)?'] =  'auth/forgot_password';
$route['admin/reset_password(.html)?']  =   'auth/reset_password';


$route['admin/register']  = 	'auth/show_register';

$route['admin/profile(.html)?']  = 	'auth_admin/profile';
$route['admin/update_password(.html)?']  = 	'auth_admin/update_password';

$route['admin/dashboard(.html)?'] =     'auth_admin/dashboard';   											# makes the .html optional 
$route['admin/new_customer_invitation(.html)?']		= "auth_admin/new_customer_invitation";   				//Send invite mail to new customer
$route['admin/new_customer_approval(.html)?']			= "auth_admin/new_customer_approval";            	 	//Approve new customer

$route['admin/manage_user_accounts']		= 	"auth_admin/manage_user_accounts"; 
$route['admin/manage_user_groups']			= 	"auth_admin/manage_user_groups"; 
$route['admin/manage_privileges']			= 	"auth_admin/manage_privileges"; 
$route['admin/manage_user_privileges']		= 	"auth_admin/manage_user_privileges"; 
*/

# https://www.regular-expressions.info/optional.html
#https://regexone.com/lesson/capturing_groups

       






