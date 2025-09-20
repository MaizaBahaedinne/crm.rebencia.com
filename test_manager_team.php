<?php
// Test de la méthode get_manager_team_agents
require_once 'application/config/config.php';
require_once 'application/config/database.php';

class Test_Database {
    private $db;
    
    public function __construct() {
        // Configuration de la base de données WordPress
        $dsn = 'mysql:host=' . $db['rebenciaBD']['hostname'] . ';dbname=' . $db['rebenciaBD']['database'] . ';charset=utf8';
        $this->db = new PDO($dsn, $db['rebenciaBD']['username'], $db['rebenciaBD']['password']);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    public function test_manager_team_method() {
        echo "<h2>Test de la méthode get_manager_team_agents</h2>\n";
        
        try {
            // Simuler la nouvelle méthode
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
                WHERE a.user_role IN ('houzez_agent', 'houzez_manager')
                ORDER BY a.display_name ASC
            ";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_OBJ);
            
            echo "<h3>Tous les agents et managers :</h3>\n";
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>\n";
            echo "<tr><th>ID</th><th>Nom</th><th>Email</th><th>Rôle</th><th>Agence</th><th>Agency ID</th><th>Avatar</th><th>Properties</th></tr>\n";
            
            foreach ($results as $agent) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($agent->user_id) . "</td>";
                echo "<td>" . htmlspecialchars($agent->display_name) . "</td>";
                echo "<td>" . htmlspecialchars($agent->user_email) . "</td>";
                echo "<td>" . htmlspecialchars($agent->user_role) . "</td>";
                echo "<td>" . htmlspecialchars($agent->agency_name ?? 'N/A') . "</td>";
                echo "<td>" . htmlspecialchars($agent->agency_id ?? 'N/A') . "</td>";
                echo "<td>" . ($agent->avatar_url ? 'Oui' : 'Non') . "</td>";
                echo "<td>" . ($agent->property_count ?? '0') . "</td>";
                echo "</tr>\n";
            }
            echo "</table>\n";
            
            // Test avec un agency_id spécifique (si on en trouve un)
            $agencies = array_unique(array_filter(array_column($results, 'agency_id')));
            if (!empty($agencies)) {
                $test_agency_id = $agencies[0];
                
                echo "<h3>Test pour agency_id = $test_agency_id :</h3>\n";
                
                $sql_filtered = "
                    SELECT 
                        a.ID as user_id,
                        a.display_name,
                        a.user_email,
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
                
                $stmt_filtered = $this->db->prepare($sql_filtered);
                $stmt_filtered->bindParam(':agency_id', $test_agency_id);
                $stmt_filtered->execute();
                $filtered_results = $stmt_filtered->fetchAll(PDO::FETCH_OBJ);
                
                echo "<table border='1' style='border-collapse: collapse; width: 100%;'>\n";
                echo "<tr><th>ID</th><th>Nom</th><th>Email</th><th>Rôle</th><th>Agence</th></tr>\n";
                
                foreach ($filtered_results as $agent) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($agent->user_id) . "</td>";
                    echo "<td>" . htmlspecialchars($agent->display_name) . "</td>";
                    echo "<td>" . htmlspecialchars($agent->user_email) . "</td>";
                    echo "<td>" . htmlspecialchars($agent->user_role) . "</td>";
                    echo "<td>" . htmlspecialchars($agent->agency_name ?? 'N/A') . "</td>";
                    echo "</tr>\n";
                }
                echo "</table>\n";
                
                echo "<p><strong>Nombre d'agents/managers dans cette agence : " . count($filtered_results) . "</strong></p>\n";
            }
            
        } catch (Exception $e) {
            echo "<p style='color: red;'>Erreur : " . htmlspecialchars($e->getMessage()) . "</p>\n";
        }
    }
}

// Exécuter le test
$test = new Test_Database();
$test->test_manager_team_method();
?>
