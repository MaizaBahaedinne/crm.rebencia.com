/*********** ROUTES CRM REBENCIA (menu header) ***********/

// Dashboard
$route['dashboard'] = 'Dashboard/index';

// Agences
$route['agencies'] = 'Agency/index';
$route['agencies/create'] = 'Agency/create';
$route['agencies/stats'] = 'Agency/stats';
$route['agency/info'] = 'Agency/info';
$route['agency/agents'] = 'Agency/agents';

// Agents
$route['agents'] = 'Agent/index';
$route['agents/create'] = 'Agent/create';
$route['agents/performance'] = 'Agent/performance';

// Propriétés
$route['properties'] = 'Property/index';
$route['properties/create'] = 'Property/create';
$route['properties/status'] = 'Property/status';

// Leads
$route['leads'] = 'Lead/index';
$route['leads/conversion'] = 'Lead/conversion';
$route['leads/followup'] = 'Lead/followup';
$route['leads/status'] = 'Lead/status';

// Transactions
$route['transactions'] = 'Transaction/index';
$route['transactions/sales'] = 'Transaction/sales';
$route['transactions/rentals'] = 'Transaction/rentals';

// Rapports & Analytics
$route['reports/sales'] = 'Report/sales';
$route['reports/leads'] = 'Report/leads';
$route['reports/agency-performance'] = 'Report/agency_performance';
$route['reports/agency'] = 'Report/agency';

// Paramètres
$route['settings/roles'] = 'Settings/roles';
$route['settings/wordpress'] = 'Settings/wordpress';
$route['settings/crm'] = 'Settings/crm';

// Profil
$route['profile'] = 'Profile/index';
$route['profile/avatar'] = 'Profile/avatar';
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

$route['default_controller'] = "Login";
$route['404_override'] = 'error_404';
$route['translate_uri_dashes'] = FALSE;


/*********** USER DEFINED ROUTES *******************/

$route['loginMe'] = 'Login/loginMe';
$route['dashboard'] = 'User';

$route['logout'] = 'User/logout';
$route['userListing'] = 'User/userListing';
$route['userListing/(:num)'] = "User/userListing/$1";
$route['addNew'] = "User/addNew";
$route['addNewUser'] = "User/addNewUser";
$route['editOld'] = "User/editOld";
$route['editOld/(:num)'] = "User/editOld/$1";
$route['editUser'] = "User/editUser";
$route['deleteUser'] = "User/deleteUser";
$route['profile'] = "User/profile";
$route['profile/(:any)'] = "User/profile/$1";
$route['profileUpdate'] = "User/profileUpdate";
$route['profileUpdate/(:any)'] = "User/profileUpdate/$1";

$route['loadChangePass'] = "User/loadChangePass";
$route['changePassword'] = "User/changePassword";
$route['changePassword/(:any)'] = "User/changePassword/$1";
$route['pageNotFound'] = "User/pageNotFound";
$route['checkEmailExists'] = "User/checkEmailExists";
$route['login-history'] = "User/loginHistory";
$route['login-history/(:num)'] = "User/loginHistory/$1";
$route['login-history/(:num)/(:num)'] = "User/loginHistory/$1/$2";

$route['forgotPassword'] = "Login/forgotPassword";
$route['resetPasswordUser'] = "Login/resetPasswordUser";
$route['resetPasswordConfirmUser'] = "Login/resetPasswordConfirmUser";
$route['resetPasswordConfirmUser/(:any)'] = "Login/resetPasswordConfirmUser/$1";
$route['resetPasswordConfirmUser/(:any)/(:any)'] = "Login/resetPasswordConfirmUser/$1/$2";
$route['createPasswordUser'] = "Login/createPasswordUser";

$route['roleListing'] = "Roles/roleListing";
$route['roleListing/(:num)'] = "Roles/roleListing/$1";
$route['roleListing/(:num)/(:num)'] = "Roles/roleListing/$1/$2";

/* End of file routes.php */
/* Location: ./application/config/routes.php */
