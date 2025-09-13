<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Debug_Agency extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('agent_model');
        $this->load->helper('avatar');
    }
    
    public function test_agents($agency_id = 18907) {
        $agents = $this->agent_model->get_agents_by_agency($agency_id);
        
        echo "<h1>Debug Agents for Agency $agency_id</h1>";
        echo "<p>Total agents: " . count($agents) . "</p>";
        
        foreach ($agents as $agent) {
            echo "<hr>";
            echo "<h3>Agent: " . htmlspecialchars($agent->agent_name ?? 'N/A') . "</h3>";
            echo "<p><strong>Email:</strong> " . htmlspecialchars($agent->agent_email ?? 'N/A') . "</p>";
            echo "<p><strong>Avatar brut:</strong> " . htmlspecialchars($agent->agent_avatar ?? 'N/A') . "</p>";
            
            if (function_exists('get_agent_avatar_url')) {
                $avatar_url = get_agent_avatar_url($agent);
                echo "<p><strong>Avatar helper:</strong> " . htmlspecialchars($avatar_url) . "</p>";
                echo "<p><img src='$avatar_url' alt='Avatar' style='width: 64px; height: 64px; border-radius: 50%;'></p>";
            } else {
                echo "<p><strong>Helper non disponible</strong></p>";
            }
        }
    }
}
