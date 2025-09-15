<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migrate extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->library('migration');
        $this->load->database();
    }

    public function index() {
        echo "<h1>Migration du CRM Rebencia</h1>";
        echo "<p>Cliquez sur les liens ci-dessous pour exécuter les migrations :</p>";
        echo "<ul>";
        echo "<li><a href='" . base_url('migrate/run_all') . "'>Exécuter toutes les migrations</a></li>";
        echo "<li><a href='" . base_url('migrate/create_tables') . "'>Créer les tables de base</a></li>";
        echo "<li><a href='" . base_url('migrate/status') . "'>Voir le statut des migrations</a></li>";
        echo "</ul>";
    }

    public function run_all() {
        try {
            if ($this->migration->latest() === FALSE) {
                show_error($this->migration->error_string());
            } else {
                echo "<h2>✅ Toutes les migrations ont été exécutées avec succès !</h2>";
                echo "<p>Les tables suivantes ont été créées :</p>";
                echo "<ul>";
                echo "<li>wp_users (utilisateurs/agents)</li>";
                echo "<li>commission_settings (paramètres de commission)</li>";
                echo "<li>monthly_objectives (objectifs mensuels)</li>";
                echo "<li>agent_performance (performances des agents)</li>";
                echo "<li>agent_commissions (commissions des agents)</li>";
                echo "<li>v_objectives_dashboard (vue pour le dashboard)</li>";
                echo "</ul>";
                echo "<p><a href='" . base_url('objectives') . "'>Aller au module Objectifs</a> | ";
                echo "<a href='" . base_url('commissions') . "'>Aller au module Commissions</a></p>";
            }
        } catch (Exception $e) {
            echo "<h2>❌ Erreur lors de la migration :</h2>";
            echo "<p>" . $e->getMessage() . "</p>";
        }
    }

    public function status() {
        echo "<h2>Statut des migrations</h2>";
        
        $version = $this->migration->current();
        echo "<p>Version actuelle de la base de données : " . $version . "</p>";
        
        // Vérifier les tables existantes
        $tables = array(
            'wp_users',
            'commission_settings', 
            'monthly_objectives',
            'agent_performance',
            'agent_commissions'
        );
        
        echo "<h3>Tables existantes :</h3>";
        echo "<ul>";
        foreach ($tables as $table) {
            if ($this->db->table_exists($table)) {
                echo "<li>✅ " . $table . "</li>";
            } else {
                echo "<li>❌ " . $table . " (manquante)</li>";
            }
        }
        echo "</ul>";
        
        echo "<p><a href='" . base_url('migrate') . "'>Retour</a></p>";
    }
}
