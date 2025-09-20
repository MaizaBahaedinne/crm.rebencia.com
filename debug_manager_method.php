<?php
// Script de debug pour tester la nouvelle méthode manager
require_once 'application/config/config.php';
require_once 'application/config/database.php';

// Simulation CodeIgniter
class Agent_model {
    private $rebenciaBD;
    
    public function __construct() {
        // Configuration de la base de données WordPress
        $dsn = 'mysql:host=' . $GLOBALS['db']['rebenciaBD']['hostname'] . ';dbname=' . $GLOBALS['db']['rebenciaBD']['database'] . ';charset=utf8';
        $this->rebenciaBD = new PDO($dsn, $GLOBALS['db']['rebenciaBD']['username'], $GLOBALS['db']['rebenciaBD']['password']);
        $this->rebenciaBD->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    public function get_manager_team_agents($manager_agency_id) {
        echo "<h3>🔍 Debug: get_manager_team_agents(agency_id: $manager_agency_id)</h3>\n";
        
        $sql = "
            SELECT 
                a.ID as user_id,
                a.display_name,
                a.user_email,
                a.user_nicename,
                ava.agent_post_id,
                ava.avatar_url,
                ava.avatar_id,
                prop.property_count,
                a.user_role,
                a.agency_name,
                a.agency_id
            FROM wp_Hrg8P_crm_agents a
            LEFT JOIN wp_Hrg8P_crm_avatar_agents ava ON a.ID = ava.agent_post_id
            LEFT JOIN wp_Hrg8P_prop_agen prop ON a.ID = prop.agent_post_id
            WHERE a.agency_id = :agency_id
            AND a.user_role IN ('houzez_agent', 'houzez_manager')
            ORDER BY a.display_name ASC
        ";
        
        echo "<p><strong>SQL Query:</strong></p>\n";
        echo "<pre>" . htmlspecialchars($sql) . "</pre>\n";
        
        try {
            $stmt = $this->rebenciaBD->prepare($sql);
            $stmt->bindParam(':agency_id', $manager_agency_id);
            $stmt->execute();
            $agents = $stmt->fetchAll(PDO::FETCH_OBJ);
            
            echo "<p><strong>Résultats trouvés : " . count($agents) . "</strong></p>\n";
            
            if (!empty($agents)) {
                echo "<table border='1' style='border-collapse: collapse; width: 100%;'>\n";
                echo "<tr><th>ID</th><th>Nom</th><th>Email</th><th>Rôle</th><th>Agence</th><th>Avatar</th><th>Properties</th></tr>\n";
                
                foreach ($agents as $agent) {
                    // Simuler clean_agent_data
                    $agent->contact_display_option = 'call_email';
                    
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($agent->user_id) . "</td>";
                    echo "<td>" . htmlspecialchars($agent->display_name) . "</td>";
                    echo "<td>" . htmlspecialchars($agent->user_email) . "</td>";
                    echo "<td>" . htmlspecialchars(str_replace('houzez_', '', $agent->user_role)) . "</td>";
                    echo "<td>" . htmlspecialchars($agent->agency_name ?? 'N/A') . "</td>";
                    echo "<td>" . ($agent->avatar_url ? '✅' : '❌') . "</td>";
                    echo "<td>" . ($agent->property_count ?? '0') . "</td>";
                    echo "</tr>\n";
                }
                echo "</table>\n";
            }
            
            return $agents;
            
        } catch (Exception $e) {
            echo "<p style='color: red;'>Erreur SQL : " . htmlspecialchars($e->getMessage()) . "</p>\n";
            return [];
        }
    }
    
    public function clean_agent_data($agent) {
        $agent->contact_display_option = 'call_email';
        return $agent;
    }
}

// Test avec différents agency_id
try {
    $model = new Agent_model();
    
    echo "<h2>🧪 Test de la méthode get_manager_team_agents</h2>\n";
    
    // D'abord récupérer les agency_id existants
    $pdo = new PDO('mysql:host=localhost;dbname=rebencia_RebenciaBD;charset=utf8', 'root', 'root');
    $stmt = $pdo->query("SELECT DISTINCT agency_id, agency_name FROM wp_Hrg8P_crm_agents WHERE agency_id IS NOT NULL ORDER BY agency_id");
    $agencies = $stmt->fetchAll();
    
    echo "<h3>📋 Agences disponibles :</h3>\n";
    foreach ($agencies as $agency) {
        echo "<p>Agency ID: {$agency['agency_id']} - {$agency['agency_name']}</p>\n";
    }
    
    // Tester avec la première agence
    if (!empty($agencies)) {
        $test_agency_id = $agencies[0]['agency_id'];
        echo "<hr>\n";
        echo "<h3>🎯 Test avec agency_id = $test_agency_id</h3>\n";
        $results = $model->get_manager_team_agents($test_agency_id);
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Erreur générale : " . htmlspecialchars($e->getMessage()) . "</p>\n";
}
?>
