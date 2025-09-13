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
        
        // Test des routes
        echo "<h2>Test de navigation</h2>";
        echo "<a href='" . base_url('agents') . "'>Liste des agents</a><br>";
        echo "<a href='" . base_url('agents/view/7') . "'>Voir agent 7</a><br>";
        echo "<a href='" . base_url('agent/view/7') . "'>Voir agent 7 (sans s)</a><br>";
    }
}
