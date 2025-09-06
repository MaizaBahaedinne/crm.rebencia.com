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
$route['agents/(:num)'] = 'Agent/stats/$1';
$route['agents/(:num)/stats'] = 'Agent/stats/$1';
$route['agents/(:num)/proprietes'] = 'Agent/properties/$1';

/*********** PROPERTIES *******************/
$route['properties'] = 'Property/index';
$route['properties/create'] = 'Property/create';
$route['properties/status'] = 'Property/status';

/*********** LEADS *******************/
$route['leads'] = 'Lead/index';
$route['leads/page/(:num)'] = 'Lead/index/$1';
$route['leads/nouveau'] = 'Lead/form';
$route['leads/edit/(:num)'] = 'Lead/form/$1';
$route['leads/save'] = 'Lead/save';
$route['leads/save/(:num)'] = 'Lead/save/$1';
$route['leads/delete/(:num)'] = 'Lead/delete/$1';
$route['leads/conversion'] = 'Lead/conversion';
$route['leads/followup'] = 'Lead/followup';
$route['leads/status'] = 'Lead/status';

/*********** CLIENTS (Houzez, lecture seule) *******************/
$route['clients'] = 'Client/index';

/*********** CRM CLIENTS *******************/
$route['crm_clients'] = 'Client/crm_clients';

// Routes pour l'autocomplétion
$route['client/search_agencies'] = 'Client/search_agencies';
$route['client/search_agents_by_agency'] = 'Client/search_agents_by_agency';
$route['client/get_agents_by_agency'] = 'Client/get_agents_by_agency';

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

/*********** REPORTS & ANALYTICS *******************/
$route['reports/sales'] = 'Report/sales';
$route['reports/leads'] = 'Report/leads';
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
$route['estimation/resultat/(:num)'] = 'Estimation/result/$1';
$route['estimation/proposition/(:num)'] = 'Estimation/proposition/$1';
$route['estimations'] = 'Estimation/liste';
$route['estimation/statut/(:num)/(:any)'] = 'Estimation/statut/$1/$2';
$route['zones'] = 'Estimation/zones';
$route['zones/create'] = 'Estimation/zone_create';
$route['zones/edit/(:num)'] = 'Estimation/zone_edit/$1';
$route['zones/delete/(:num)'] = 'Estimation/zone_delete/$1';

/* End of file routes.php */
/* Location: ./application/config/routes.php */
