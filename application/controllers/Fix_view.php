<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fix_view extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Property_model');
    }

    /**
     * Recréer la vue avec la colonne property_date
     */
    public function recreate_view() {
        echo "<h1>🛠️ Correction de la vue wp_Hrg8P_prop_agen</h1>";
        
        try {
            // Recréer la vue avec la fonction du modèle
            $result = $this->Property_model->create_property_agent_view();
            
            if ($result) {
                echo "<p style='color: green;'>✅ Vue wp_Hrg8P_prop_agen recréée avec succès!</p>";
                echo "<p>La colonne <strong>property_date</strong> est maintenant disponible.</p>";
            } else {
                echo "<p style='color: red;'>❌ Échec de la création de la vue.</p>";
            }
            
            // Test de la vue
            echo "<h2>🧪 Test de la vue</h2>";
            $properties = $this->Property_model->get_from_property_agent_view(['limit' => 5]);
            
            echo "<p><strong>Nombre de propriétés trouvées:</strong> " . count($properties) . "</p>";
            
            if (!empty($properties)) {
                echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
                echo "<tr style='background: #f5f5f5;'>";
                echo "<th>ID</th><th>Titre</th><th>Date</th><th>Agent</th><th>Agence</th>";
                echo "</tr>";
                
                foreach (array_slice($properties, 0, 5) as $property) {
                    echo "<tr>";
                    echo "<td>" . $property->property_id . "</td>";
                    echo "<td>" . htmlspecialchars($property->property_title) . "</td>";
                    echo "<td>" . ($property->property_date ?? 'N/A') . "</td>";
                    echo "<td>" . ($property->agent_name ?? 'N/A') . "</td>";
                    echo "<td>" . ($property->agency_name ?? 'N/A') . "</td>";
                    echo "</tr>";
                }
                
                echo "</table>";
            }
            
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Erreur: " . $e->getMessage() . "</p>";
        }
        
        echo "<br><a href='" . base_url('dashboard/admin') . "' style='padding: 10px; background: #007cba; color: white; text-decoration: none;'>🔙 Retour au Dashboard</a>";
    }

    /**
     * Afficher la structure de la vue actuelle
     */
    public function check_view() {
        echo "<h1>🔍 Vérification de la vue wp_Hrg8P_prop_agen</h1>";
        
        try {
            $this->load->database('wordpress');
            $wp_db = $this->load->database('wordpress', TRUE);
            
            // Vérifier si la vue existe
            $view_name = $wp_db->dbprefix('prop_agen');
            $query = $wp_db->query("SHOW CREATE VIEW $view_name");
            
            if ($query && $query->num_rows() > 0) {
                $result = $query->row_array();
                echo "<h2>✅ La vue existe</h2>";
                echo "<h3>Structure actuelle:</h3>";
                echo "<pre style='background: #f5f5f5; padding: 10px; overflow-x: auto;'>";
                echo htmlspecialchars($result['Create View']);
                echo "</pre>";
            } else {
                echo "<h2>❌ La vue n'existe pas</h2>";
                echo "<p>La vue $view_name n'a pas été trouvée.</p>";
            }
            
            // Tester un SELECT simple
            echo "<h3>Test SELECT:</h3>";
            $test = $wp_db->query("SELECT * FROM $view_name LIMIT 1");
            if ($test && $test->num_rows() > 0) {
                $row = $test->row();
                echo "<p>✅ Vue accessible</p>";
                echo "<p><strong>Colonnes disponibles:</strong></p>";
                echo "<ul>";
                foreach (get_object_vars($row) as $column => $value) {
                    echo "<li><strong>$column:</strong> " . (is_null($value) ? 'NULL' : htmlspecialchars($value)) . "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>❌ Impossible d'accéder à la vue</p>";
            }
            
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Erreur: " . $e->getMessage() . "</p>";
        }
        
        echo "<br><a href='" . base_url('fix_view/recreate_view') . "' style='padding: 10px; background: #28a745; color: white; text-decoration: none;'>🔄 Recréer la vue</a>";
        echo " <a href='" . base_url('dashboard/admin') . "' style='padding: 10px; background: #007cba; color: white; text-decoration: none; margin-left: 10px;'>🔙 Dashboard</a>";
    }
}
