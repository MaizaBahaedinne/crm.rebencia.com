<?php
// Test session CodeIgniter
define('BASEPATH', true);

// Initialisation CodeIgniter minimale
$system_path = '/Applications/MAMP/htdocs/crm.rebencia.com/system';
$application_folder = '/Applications/MAMP/htdocs/crm.rebencia.com/application';

set_include_path($system_path);
require_once $system_path.'/core/Common.php';
require_once $system_path.'/core/CodeIgniter.php';

// Démarrer session CI
$ci =& get_instance();
$ci->load->library('session');

echo "<h1>Test Session CodeIgniter</h1>";

echo "<h2>Toutes les données de session CI:</h2>";
echo "<pre>" . print_r($ci->session->all_userdata(), true) . "</pre>";

echo "<h2>Données spécifiques:</h2>";
echo "wp_id: " . $ci->session->userdata('wp_id') . "<br>";
echo "userId: " . $ci->session->userdata('userId') . "<br>";
echo "isLoggedIn: " . ($ci->session->userdata('isLoggedIn') ? 'TRUE' : 'FALSE') . "<br>";
echo "name: " . $ci->session->userdata('name') . "<br>";
echo "role: " . $ci->session->userdata('role') . "<br>";

?>
