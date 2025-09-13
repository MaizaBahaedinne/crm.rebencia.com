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

    public function agents_debug() {
        echo "<h2>Debug - Agents avec avatars</h2>";
        
        $agents = $this->agent_model->get_all_agents_from_posts();
        
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Nom</th><th>Email</th><th>Avatar URL (DB)</th><th>Helper Avatar</th><th>Test Image</th></tr>";
        
        foreach ($agents as $agent) {
            $avatar_helper = get_agent_avatar_url($agent);
            
            echo "<tr>";
            echo "<td>{$agent->agent_id}</td>";
            echo "<td>" . htmlspecialchars($agent->agent_name) . "</td>";
            echo "<td>" . htmlspecialchars($agent->agent_email ?? 'N/A') . "</td>";
            echo "<td style='max-width: 300px; word-break: break-all;'>" . htmlspecialchars($agent->agent_avatar ?? 'NULL') . "</td>";
            echo "<td style='max-width: 300px; word-break: break-all;'>" . htmlspecialchars($avatar_helper ?? 'NULL') . "</td>";
            echo "<td>";
            if ($agent->agent_avatar) {
                echo "<img src='{$agent->agent_avatar}' style='width: 50px; height: 50px; object-fit: cover;' onerror='this.style.display=\"none\"; this.nextSibling.style.display=\"block\";'>";
                echo "<span style='display: none; color: red;'>ERREUR</span>";
            } else {
                echo "Pas d'image";
            }
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        echo "<br><a href='" . base_url('agents') . "'>Retour aux agents</a>";
    }
}
