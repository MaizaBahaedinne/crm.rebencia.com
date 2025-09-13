<?php
// Test direct des agents - Bypass CodeIgniter routing
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Changer vers le répertoire de CodeIgniter
chdir('/Applications/MAMP/htdocs/crm.rebencia.com');

// Définir l'environnement
defined('BASEPATH') OR define('BASEPATH', dirname(__FILE__) . '/system/');
define('APPPATH', dirname(__FILE__) . '/application/');
define('VIEWPATH', APPPATH . 'views/');

// Charger CodeIgniter
require_once BASEPATH . 'core/CodeIgniter.php';

echo "<h1>Test Direct Agents</h1>";

try {
    // Créer une instance du contrôleur Agent
    require_once(APPPATH . 'libraries/BaseController.php');
    require_once(APPPATH . 'controllers/Agent.php');
    
    $CI =& get_instance();
    $CI->load->model('Agent_model', 'agent_model');
    
    echo "<h2>1. Test get_all_agents()</h2>";
    $all_agents = $CI->agent_model->get_all_agents([]);
    
    if ($all_agents && count($all_agents) > 0) {
        echo "✅ " . count($all_agents) . " agents trouvés<br><br>";
        
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Nom</th><th>Email</th><th>Agence</th><th>Test View</th></tr>";
        
        foreach (array_slice($all_agents, 0, 10) as $agent) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($agent->agent_id ?? 'N/A') . "</td>";
            echo "<td>" . htmlspecialchars($agent->agent_name ?? 'N/A') . "</td>";
            echo "<td>" . htmlspecialchars($agent->agent_email ?? 'N/A') . "</td>";
            echo "<td>" . htmlspecialchars($agent->agency_name ?? 'N/A') . "</td>";
            echo "<td><a href='https://crm.rebencia.com/index.php/agents/view/" . ($agent->agent_id ?? 0) . "' target='_blank'>Voir</a></td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Test de récupération d'un agent spécifique
        $first_agent = $all_agents[0];
        $agent_id = $first_agent->agent_id;
        
        echo "<h2>2. Test get_agent($agent_id)</h2>";
        $single_agent = $CI->agent_model->get_agent($agent_id);
        
        if ($single_agent) {
            echo "✅ Agent $agent_id récupéré avec succès<br>";
            echo "<pre>" . print_r($single_agent, true) . "</pre>";
        } else {
            echo "❌ Impossible de récupérer l'agent $agent_id<br>";
        }
        
    } else {
        echo "❌ Aucun agent trouvé<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>
