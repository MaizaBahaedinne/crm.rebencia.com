<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_direct extends CI_Controller {
    
    public function index() {
        echo "<h1>Test Direct - " . date('Y-m-d H:i:s') . "</h1>";
        
        // Test du chargement de base
        echo "<h3>Tests de base:</h3>";
        echo "✓ CodeIgniter chargé<br>";
        echo "✓ Contrôleur fonctionnel<br>";
        
        // Test de la base de données
        echo "<h3>Test base de données:</h3>";
        try {
            $this->load->database();
            if ($this->db->conn_id) {
                echo "✓ Connexion base de données OK<br>";
                
                // Test de requête simple
                $query = $this->db->query("SELECT COUNT(*) as count FROM tbl_users");
                if ($query) {
                    $result = $query->row();
                    echo "✓ Requête de test réussie - Nombre d'utilisateurs: " . $result->count . "<br>";
                } else {
                    echo "✗ Erreur de requête<br>";
                }
            } else {
                echo "✗ Pas de connexion base de données<br>";
            }
        } catch (Exception $e) {
            echo "✗ Erreur base de données: " . $e->getMessage() . "<br>";
        }
        
        // Test des modèles
        echo "<h3>Test des modèles:</h3>";
        try {
            $this->load->model('agent_model');
            echo "✓ Modèle Agent_model chargé<br>";
            
            // Test méthode du modèle
            if (method_exists($this->agent_model, 'get_all_agents')) {
                echo "✓ Méthode get_all_agents existe<br>";
                
                try {
                    $agents = $this->agent_model->get_all_agents();
                    echo "✓ Agents récupérés: " . count($agents) . "<br>";
                } catch (Exception $e) {
                    echo "✗ Erreur get_all_agents: " . $e->getMessage() . "<br>";
                }
            } else {
                echo "✗ Méthode get_all_agents manquante<br>";
            }
        } catch (Exception $e) {
            echo "✗ Erreur chargement Agent_model: " . $e->getMessage() . "<br>";
        }
        
        echo "<h3>Test terminé</h3>";
    }
    
    public function agents_test() {
        echo "<h1>Test Agents Direct - " . date('Y-m-d H:i:s') . "</h1>";
        
        try {
            $this->load->database();
            $this->load->model('agent_model');
            $this->load->helper('avatar');
            
            echo "<h3>Récupération des agents:</h3>";
            $agents = $this->agent_model->get_all_agents();
            echo "Nombre d'agents: " . count($agents) . "<br><br>";
            
            if (count($agents) > 0) {
                echo "<h4>Premier agent:</h4>";
                $first_agent = $agents[0];
                echo "<pre>";
                print_r($first_agent);
                echo "</pre>";
                
                echo "<h4>Avatar du premier agent:</h4>";
                $avatar_url = get_agent_avatar_url($first_agent);
                echo "URL avatar: " . $avatar_url . "<br>";
                echo '<img src="' . $avatar_url . '" style="max-width: 100px; max-height: 100px;" alt="Avatar">';
            }
            
        } catch (Exception $e) {
            echo "Erreur: " . $e->getMessage() . "<br>";
            echo "Trace: <pre>" . $e->getTraceAsString() . "</pre>";
        }
    }
}
?>
