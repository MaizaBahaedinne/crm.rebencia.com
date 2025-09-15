<?php
/**
 * Script d'installation des modules Commissions et Objectifs
 * À exécuter une seule fois pour initialiser les tables
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Install extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function commission_objectives() {
        echo "<h2>Installation des modules Commissions et Objectifs</h2>";
        
        // Lire le fichier SQL
        $sql_file = APPPATH . '../db/init_commission_objectives.sql';
        
        if (!file_exists($sql_file)) {
            echo "<p style='color: red;'>Erreur: Fichier SQL non trouvé: $sql_file</p>";
            return;
        }
        
        $sql_content = file_get_contents($sql_file);
        
        // Diviser en requêtes individuelles
        $queries = explode(';', $sql_content);
        
        $success_count = 0;
        $error_count = 0;
        
        echo "<div style='background: #f5f5f5; padding: 10px; margin: 10px 0; border-radius: 5px;'>";
        
        foreach ($queries as $query) {
            $query = trim($query);
            if (empty($query) || substr($query, 0, 2) === '--' || strtoupper(substr($query, 0, 6)) === 'COMMIT') {
                continue;
            }
            
            try {
                $this->db->query($query);
                $success_count++;
                echo "<p style='color: green;'>✓ Requête exécutée avec succès</p>";
            } catch (Exception $e) {
                $error_count++;
                echo "<p style='color: red;'>✗ Erreur: " . $e->getMessage() . "</p>";
                echo "<pre style='color: #666; font-size: 12px;'>" . substr($query, 0, 100) . "...</pre>";
            }
        }
        
        echo "</div>";
        
        echo "<h3>Résumé de l'installation:</h3>";
        echo "<p><strong>Requêtes réussies:</strong> $success_count</p>";
        echo "<p><strong>Erreurs:</strong> $error_count</p>";
        
        if ($error_count === 0) {
            echo "<p style='color: green; font-weight: bold;'>🎉 Installation terminée avec succès!</p>";
            echo "<p><a href='" . base_url('commissions') . "'>→ Accéder aux Commissions</a></p>";
            echo "<p><a href='" . base_url('objectives') . "'>→ Accéder aux Objectifs</a></p>";
        } else {
            echo "<p style='color: red; font-weight: bold;'>⚠️ Installation terminée avec des erreurs.</p>";
        }
        
        // Vérifier les tables créées
        echo "<h3>Vérification des tables:</h3>";
        $tables = ['commission_settings', 'monthly_objectives', 'agent_performance', 'agent_commissions', 'crm_agents'];
        
        foreach ($tables as $table) {
            $result = $this->db->query("SHOW TABLES LIKE '$table'");
            if ($result->num_rows() > 0) {
                echo "<p style='color: green;'>✓ Table '$table' créée</p>";
            } else {
                echo "<p style='color: red;'>✗ Table '$table' non trouvée</p>";
            }
        }
    }
    
    public function check_tables() {
        echo "<h2>Vérification des tables existantes</h2>";
        
        $result = $this->db->query("SHOW TABLES");
        $tables = $result->result_array();
        
        echo "<ul>";
        foreach ($tables as $table) {
            $table_name = array_values($table)[0];
            echo "<li>$table_name</li>";
        }
        echo "</ul>";
    }
}
