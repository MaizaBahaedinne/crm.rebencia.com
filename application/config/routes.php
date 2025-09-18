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
$route['dashboard/agent'] = 'Dashboard/agent';  // Route sans paramètre utilisant user_post_id de session
$route['dashboard/agent/(:num)'] = 'Dashboard/agent/$1';  // Route avec paramètre pour compatibilité
$route['dashboard/agency/(:num)'] = 'Dashboard/agency/$1';
$route['dashboard/admin'] = 'Dashboard/admin';

/*********** USER MANAGEMENT *******************/
$route['userListing'] = 'User/userListing';
$route['userListing/(:num)'] = "User/userListing/$1";
$route['addNew'] = "User/addNew";
$route['addNewUser'] = "User/addNewUser";
$route['editOld'] = "User/editOld";
$route['editOld/(:num)'] = "User/editOld/$1";
$route['editUser'] = "User/editUser";
$route['deleteUser'] = "User/deleteUser";
// Routes déplacées vers Profile controller
// $route['profile'] = "User/profile";
// $route['profile/(:any)'] = "User/profile/$1";
$route['profileUpdate'] = "User/profileUpdate";
$route['profileUpdate/(:any)'] = "User/profileUpdate/$1";
$route['roleListing/(:num)/(:num)'] = "Roles/roleListing/$1/$2";

/*********** AGENCIES (HOUZEZ - Consultation) *******************/
$route['agencies'] = 'Agency/index';
$route['agencies/stats'] = 'Agency/stats';
$route['agency/info'] = 'Agency/info';
$route['agency/agents'] = 'Agency/agents';
$route['agency/view/(:num)'] = 'Agency/view/$1';

/*********** AGENTS (HOUZEZ - Consultation) *******************/
$route['agents'] = 'Agent/index';

/*********** COMMISSION MODULE *******************/
$route['commission'] = 'Commission/settings';
$route['commission/settings'] = 'Commission/settings';
$route['commission/update_settings'] = 'Commission/update_settings';
$route['commission/calculator'] = 'Commission/calculator';
$route['commission/api_calculate'] = 'Commission/api_calculate';
$route['commission/save'] = 'Commission/save';
$route['commission/history'] = 'Commission/history';
$route['commission/history/(:num)'] = 'Commission/history/$1';
$route['commission/stats'] = 'Commission/stats';

/*********** OBJECTIVES MODULE *******************/
$route['objectives'] = 'Objectives/index';
$route['objectives/set_objectives'] = 'Objectives/set_monthly';
$route['objectives/set_monthly'] = 'Objectives/set_monthly';
$route['objectives/agent/(:num)'] = 'Objectives/agent/$1';
$route['objectives/update_performance'] = 'Objectives/update_performance';
$route['objectives/calculate_performance/(:num)/(:any)'] = 'Objectives/calculate_performance/$1/$2';
$route['objectives/team'] = 'Objectives/team';
$route['objectives/bulk_set'] = 'Objectives/bulk_set';
$route['objectives/api_get_data'] = 'Objectives/api_get_data';
$route['agents/test'] = 'Agent/test';
$route['agents/view/(:num)'] = 'Agent/view/$1';
$route['agents/(:num)'] = 'Agent/view/$1';
$route['agents/(:num)/stats'] = 'Agent/stats/$1';
$route['agents/(:num)/properties'] = 'Agent/properties/$1';

/*********** PROPERTIES (HOUZEZ - Consultation) *******************/
$route['properties'] = 'Properties/index';
$route['properties/status'] = 'Properties/status';
$route['properties/view/(:num)'] = 'Properties/view/$1';
$route['properties/details/(:num)'] = 'Properties/details/$1';
$route['properties/ajax_list'] = 'Properties/ajax_list';
$route['properties/ajax_search'] = 'Properties/ajax_search';

/*********** CLIENTS *******************/
$route['clients'] = 'Client/index';
$route['client/add'] = 'Client/add';
$route['client/edit/(:num)'] = 'Client/edit/$1';
$route['client/view/(:num)'] = 'Client/view/$1';
$route['client/delete/(:num)'] = 'Client/delete/$1';

// Routes AJAX pour autocomplétion
$route['client/search_agencies_from_crm'] = 'Client/search_agencies_from_crm';
$route['client/search_agents_from_crm'] = 'Client/search_agents_from_crm';
$route['client/get_user_context'] = 'Client/get_user_context';

/*********** TRANSACTIONS *******************/
$route['transactions'] = 'Transaction/index';
$route['transactions/sales'] = 'Transaction/sales';
$route['transactions/rentals'] = 'Transaction/rentals';
$route['transactions/nouveau'] = 'Transaction/form';
$route['transactions/edit/(:num)'] = 'Transaction/form/$1';
$route['transactions/save'] = 'Transaction/save';
$route['transactions/save/(:num)'] = 'Transaction/save/$1';
$route['transactions/delete/(:num)'] = 'Transaction/delete/$1';
$route['transactions/sync/houzez'] = 'Transaction/sync_houzez';
$route['transactions/properties/by-type'] = 'Transaction/properties_by_type';

/*********** REPORTS *******************/
$route['reports/sales'] = 'Report/sales';
$route['reports/agency-performance'] = 'Report/agency_performance';
$route['reports/agency'] = 'Report/agency';

/*********** SETTINGS *******************/
$route['settings/roles'] = 'Settings/roles';
$route['settings/wordpress'] = 'Settings/wordpress';
$route['settings/crm'] = 'Settings/crm';



/*********** PROFILE *******************/
$route['profile'] = 'Profile/index';
$route['profile/avatar'] = 'Profile/avatar';

/*********** ESTIMATION IMMOBILIERE *******************/
$route['estimation'] = 'Estimation/index';
$route['estimation/nouveau'] = 'Estimation/index';
$route['estimation/calcul'] = 'Estimation/calculate';
$route['estimation/resultat/(:num)'] = 'Estimation/resultat/$1';
$route['estimation/proposition/(:num)'] = 'Estimation/proposition/$1';
$route['estimations'] = 'Estimations/index';  // Nouveau contrôleur avec gestion des rôles
$route['estimations/view/(:num)'] = 'Estimations/view/$1';
$route['estimations/edit/(:num)'] = 'Estimations/edit/$1';
$route['estimations/filter'] = 'Estimations/filter';
$route['estimations/export/(:any)'] = 'Estimations/export/$1';
$route['estimations/export_pdf/(:num)'] = 'Estimations/export_pdf/$1';
$route['estimation/statut/(:num)/(:any)'] = 'Estimation/statut/$1/$2';
$route['zones'] = 'Estimation/zones';
$route['zones/create'] = 'Estimation/zone_create';
$route['zones/edit/(:num)'] = 'Estimation/zone_edit/$1';
$route['zones/delete/(:num)'] = 'Estimation/zone_delete/$1';

/* End of file routes.php */
/* Location: ./application/config/routes.php */
