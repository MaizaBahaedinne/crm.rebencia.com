<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Test_agent_view extends BaseController {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Agent_model', 'agent_model');
    }
    
    public function index() {
        echo "<h1>Test Agent View Direct</h1>";
        
        try {
            // Test de récupération d'un agent
            $agent = $this->agent_model->get_agent(7);
            
            if ($agent) {
                echo "<h2>✅ Agent trouvé !</h2>";
                echo "<pre>" . print_r($agent, true) . "</pre>";
            } else {
                echo "<h2>❌ Agent non trouvé</h2>";
            }
            
        } catch (Exception $e) {
            echo "<h2>❌ Erreur</h2>";
            echo "<p>" . $e->getMessage() . "</p>";
            echo "<pre>" . $e->getTraceAsString() . "</pre>";
        }
    }

    /**
     * Test de la fonction d'affectation des avatars
     */
    public function avatar_test() {
        echo "<h2>Test des fonctions d'avatar</h2>";
        
        // Test 1: Récupération d'un avatar par ID
        echo "<h3>Test 1: Avatar par ID</h3>";
        $agent_id = 18914; // ID d'exemple
        $avatar_url = $this->agent_model->get_agent_avatar_url_by_id($agent_id);
        echo "<p>Avatar pour l'agent $agent_id: " . htmlspecialchars($avatar_url ?? 'NULL') . "</p>";
        
        if ($avatar_url) {
            echo "<img src='$avatar_url' style='width: 100px; height: 100px; object-fit: cover; border-radius: 50%;'>";
        }
        
        // Test 2: Liste des agents avec avatars
        echo "<h3>Test 2: Liste des agents avec avatars</h3>";
        $agents = $this->agent_model->get_all_agents_from_posts();
        
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Nom</th><th>Avatar URL</th><th>Image</th></tr>";
        
        $count = 0;
        foreach ($agents as $agent) {
            if ($count >= 5) break; // Limiter à 5 pour le test
            
            echo "<tr>";
            echo "<td>{$agent->agent_id}</td>";
            echo "<td>" . htmlspecialchars($agent->agent_name) . "</td>";
            echo "<td style='max-width: 300px; word-break: break-all;'>" . htmlspecialchars($agent->agent_avatar ?? 'NULL') . "</td>";
            echo "<td>";
            if ($agent->agent_avatar) {
                echo "<img src='{$agent->agent_avatar}' style='width: 50px; height: 50px; object-fit: cover; border-radius: 50%;' onerror='this.style.display=\"none\";'>";
            } else {
                echo "Pas d'image";
            }
            echo "</td>";
            echo "</tr>";
            $count++;
        }
        echo "</table>";
        
        echo "<br><a href='" . base_url('agents') . "'>Voir la liste complète des agents</a>";
    }
}
