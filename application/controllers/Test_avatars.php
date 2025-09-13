<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_avatars extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Agent_model', 'agent_model');
    }

    /**
     * Test de la nouvelle méthode d'avatar proposée
     */
    public function index() {
        echo "<h2>Test des avatars d'agents</h2>";
        echo "<p>Comparaison entre l'ancienne et la nouvelle méthode</p>";
        
        // Test de la nouvelle méthode
        $agents_new = $this->agent_model->get_agents_with_better_avatars();
        
        echo "<h3>Nouvelle méthode (avec _thumbnail_id + fave_author_custom_picture)</h3>";
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Nom</th><th>Email</th><th>Avatar URL</th><th>Image</th></tr>";
        
        foreach ($agents_new as $agent) {
            echo "<tr>";
            echo "<td>{$agent->agent_id}</td>";
            echo "<td>" . htmlspecialchars($agent->agent_name) . "</td>";
            echo "<td>" . htmlspecialchars($agent->agent_email ?? 'N/A') . "</td>";
            echo "<td>" . htmlspecialchars($agent->agent_avatar ?? 'N/A') . "</td>";
            echo "<td>";
            if ($agent->agent_avatar) {
                echo "<img src='{$agent->agent_avatar}' style='width: 50px; height: 50px; object-fit: cover;' onerror='this.style.display=\"none\"'>";
            } else {
                echo "Pas d'image";
            }
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        echo "<br><hr><br>";
        
        // Test de l'ancienne méthode pour comparaison
        $agents_old = $this->agent_model->get_all_agents_from_posts();
        
        echo "<h3>Ancienne méthode (seulement fave_author_custom_picture)</h3>";
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Nom</th><th>Email</th><th>Avatar URL</th><th>Image</th></tr>";
        
        foreach ($agents_old as $agent) {
            echo "<tr>";
            echo "<td>{$agent->agent_id}</td>";
            echo "<td>" . htmlspecialchars($agent->agent_name) . "</td>";
            echo "<td>" . htmlspecialchars($agent->agent_email ?? 'N/A') . "</td>";
            echo "<td>" . htmlspecialchars($agent->agent_avatar ?? 'N/A') . "</td>";
            echo "<td>";
            if ($agent->agent_avatar) {
                echo "<img src='{$agent->agent_avatar}' style='width: 50px; height: 50px; object-fit: cover;' onerror='this.style.display=\"none\"'>";
            } else {
                echo "Pas d'image";
            }
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        echo "<br><br>";
        echo "<a href='" . base_url('agents') . "'>Retour à la liste des agents</a>";
    }
}
