<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
*/

$route['default_controller'] = "Login";
$route['404_override'] = 'error_404';
$route['translate_uri_dashes'] = FALSE;

/*********** AUTH & USER ROUTES *******************/
$route['loginMe'] = 'Login/loginMe';
$route['logout'] = 'User/logout';
$route['forgotPassword'] = "Login/forgotPassword";
$route['resetPasswordUser'] = "Login/resetPasswordUser";
$route['resetPasswordConfirmUser'] = "Login/resetPasswordConfirmUser";
$route['resetPasswordConfirmUser/(:any)'] = "Login/resetPasswordConfirmUser/$1";
$route['resetPasswordConfirmUser/(:any)/(:any)'] = "Login/resetPasswordConfirmUser/$1/$2";
$route['createPasswordUser'] = "Login/createPasswordUser";

/*********** DASHBOARD *******************/
$route['dashboard'] = 'Dashboard/index';

/*********** USER MANAGEMENT *******************/
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
$route['checkEmailExists'] = "User/checkEmailExists";
$route['login-history'] = "User/loginHistory";
$route['login-history/(:num)'] = "User/loginHistory/$1";
$route['login-history/(:num)/(:num)'] = "User/loginHistory/$1/$2";

/*********** ROLES *******************/
$route['roleListing'] = "Roles/roleListing";
$route['roleListing/(:num)'] = "Roles/roleListing/$1";
$route['roleListing/(:num)/(:num)'] = "Roles/roleListing/$1/$2";

/*********** AGENCIES *******************/
$route['agencies'] = 'Agency/index';
$route['agencies/create'] = 'Agency/create';
$route['agencies/stats'] = 'Agency/stats';
$route['agency/info'] = 'Agency/info';
$route['agency/agents'] = 'Agency/agents';

/*********** AGENTS *******************/
$route['agents'] = 'Agent/index';
$route['agents/create'] = 'Agent/create';
$route['agents/performance'] = 'Agent/performance';

/*********** PROPERTIES *******************/
$route['properties'] = 'Property/index';
$route['properties/create'] = 'Property/create';
$route['properties/status'] = 'Property/status';

/*********** LEADS *******************/
$route['leads'] = 'Lead/index';
$route['leads/conversion'] = 'Lead/conversion';
$route['leads/followup'] = 'Lead/followup';
$route['leads/status'] = 'Lead/status';

/*********** TRANSACTIONS *******************/
$route['transactions'] = 'Transaction/index';
$route['transactions/sales'] = 'Transaction/sales';
$route['transactions/rentals'] = 'Transaction/rentals';

/*********** REPORTS & ANALYTICS *******************/
$route['reports/sales'] = 'Report/sales';
$route['reports/leads'] = 'Report/leads';
$route['reports/agency-performance'] = 'Report/agency_performance';
$route['reports/agency'] = 'Report/agency';

/*********** SETTINGS *******************/
$route['settings/roles'] = 'Settings/roles';
$route['settings/wordpress'] = 'Settings/wordpress';
$route['settings/crm'] = 'Settings/crm';

/*********** MAIL *******************/
$route['mail'] = 'Mail/index';
$route['mail/inbox'] = 'Mail/inbox';
$route['mail/compose'] = 'Mail/compose';
$route['mail/send'] = 'Mail/send';
$route['mail/view/(:num)'] = 'Mail/view/$1';
$route['mail/read/(:num)'] = 'Mail/markRead/$1';
$route['mail/unread/(:num)'] = 'Mail/markUnread/$1';
$route['mail/download/(:num)/(:num)'] = 'Mail/download/$1/$2';

/*********** PROFILE *******************/
$route['profile'] = 'Profile/index';
$route['profile/avatar'] = 'Profile/avatar';

/* End of file routes.php */
/* Location: ./application/config/routes.php */
