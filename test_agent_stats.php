<?php
// Test des statistiques de l'agent
define('BASEPATH', true);
require_once 'application/config/database.php';
require_once 'application/models/Agent_model.php';

class TestAgentStats {
    private $db;
    private $wp_db;
    private $agent_model;
    
    public function __construct() {
        // Configuration de la base de données
        $config = $db['default'];
        
        try {
            $this->db = new PDO(
                "mysql:host={$config['hostname']};dbname={$config['database']};charset=utf8",
                $config['username'],
                $config['password']
            );
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Simuler l'environnement CodeIgniter
            $CI = new stdClass();
            $CI->db = new stdClass();
            $CI->db->query = function($sql, $params = []) {
                $stmt = $this->db->prepare($sql);
                $stmt->execute($params);
                
                $result = new stdClass();
                $result->row = function() use ($stmt) {
                    return $stmt->fetch(PDO::FETCH_OBJ);
                };
                return $result;
            };
            
            echo "<h2>Test des statistiques de l'agent ID 7</h2>";
            
            // Test direct de la base de données
            $this->testDirectStats();
            
        } catch (Exception $e) {
            echo "Erreur: " . $e->getMessage();
        }
    }
    
    private function testDirectStats() {
        try {
            // Test 1: Compter les propriétés
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as count 
                FROM wp_posts prop 
                INNER JOIN wp_postmeta pm_prop ON prop.ID = pm_prop.post_id 
                WHERE pm_prop.meta_key = 'fave_property_agent' 
                AND pm_prop.meta_value = ? 
                AND prop.post_type = 'property' 
                AND prop.post_status = 'publish'
            ");
            $stmt->execute([7]);
            $properties_count = $stmt->fetch(PDO::FETCH_OBJ);
            
            echo "<p><strong>Propriétés:</strong> " . ($properties_count->count ?? 0) . "</p>";
            
            // Test 2: Compter les clients
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as count 
                FROM crm_clients 
                WHERE agent_id = ? 
                AND (statut IS NULL OR statut != 'supprime')
            ");
            $stmt->execute([7]);
            $clients_count = $stmt->fetch(PDO::FETCH_OBJ);
            
            echo "<p><strong>Clients:</strong> " . ($clients_count->count ?? 0) . "</p>";
            
            // Test 3: Compter les leads
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as count 
                FROM crm_leads 
                WHERE agent_id = ? 
                AND (deleted_at IS NULL)
            ");
            $stmt->execute([7]);
            $leads_count = $stmt->fetch(PDO::FETCH_OBJ);
            
            echo "<p><strong>Leads:</strong> " . ($leads_count->count ?? 0) . "</p>";
            
            // Test 4: Vues totales des propriétés
            $stmt = $this->db->prepare("
                SELECT SUM(CAST(pm_views.meta_value AS UNSIGNED)) as total_views
                FROM wp_posts p
                INNER JOIN wp_postmeta pm_agent ON p.ID = pm_agent.post_id AND pm_agent.meta_key = 'fave_property_agent'
                LEFT JOIN wp_postmeta pm_views ON p.ID = pm_views.post_id AND pm_views.meta_key = 'fave_total_property_views'
                WHERE pm_agent.meta_value = ?
                AND p.post_type = 'property'
                AND p.post_status = 'publish'
            ");
            $stmt->execute([7]);
            $views_count = $stmt->fetch(PDO::FETCH_OBJ);
            
            echo "<p><strong>Vues totales:</strong> " . ($views_count->total_views ?? 0) . "</p>";
            
        } catch (Exception $e) {
            echo "<p>Erreur lors du test: " . $e->getMessage() . "</p>";
        }
    }
}

new TestAgentStats();
?>
