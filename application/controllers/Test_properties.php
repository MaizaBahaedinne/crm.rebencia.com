<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_properties extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Property_model');
        $this->load->helper('url');
    }

    /**
     * Test de la vue wp_Hrg8P_prop_agen
     */
    public function index() {
        echo "<h1>🏠 Test de la vue wp_Hrg8P_prop_agen</h1>";
        echo "<style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            .property-card { 
                border: 1px solid #ddd; 
                margin: 10px 0; 
                padding: 15px; 
                border-radius: 8px; 
                background: #f9f9f9;
            }
            .property-title { color: #2c5aa0; font-weight: bold; font-size: 18px; }
            .agent-info { color: #5a9; margin: 5px 0; }
            .agency-info { color: #a59; margin: 5px 0; }
            .stats { background: #e7f3ff; padding: 10px; border-radius: 5px; margin: 20px 0; }
            .btn { 
                display: inline-block; 
                padding: 8px 16px; 
                margin: 5px; 
                background: #007cba; 
                color: white; 
                text-decoration: none; 
                border-radius: 4px; 
            }
            .btn:hover { background: #005a87; }
        </style>";
        
        // Navigation
        echo "<div>";
        echo "<a href='" . base_url('test_properties') . "' class='btn'>📋 Toutes les propriétés</a>";
        echo "<a href='" . base_url('test_properties/by_agent/1') . "' class='btn'>👤 Par Agent (ID: 1)</a>";
        echo "<a href='" . base_url('test_properties/by_agency/1') . "' class='btn'>🏢 Par Agence (ID: 1)</a>";
        echo "<a href='" . base_url('test_properties/test_methods') . "' class='btn'>🔧 Test Méthodes</a>";
        echo "</div>";

        try {
            // Récupérer les données de la vue
            $properties = $this->Property_model->get_from_property_agent_view(['limit' => 10]);
            
            echo "<div class='stats'>";
            echo "<h3>📊 Statistiques</h3>";
            echo "<p><strong>Nombre de propriétés trouvées:</strong> " . count($properties) . "</p>";
            echo "</div>";
            
            if (empty($properties)) {
                echo "<p style='color: orange;'>⚠️ Aucune propriété trouvée dans la vue.</p>";
                return;
            }
            
            echo "<h2>🏠 Propriétés avec Agents et Agences</h2>";
            
            foreach ($properties as $property) {
                echo "<div class='property-card'>";
                
                // Infos propriété
                echo "<div class='property-title'>🏠 " . ($property->property_title ?? 'Titre non défini') . "</div>";
                echo "<p><strong>ID:</strong> " . $property->property_id . " | ";
                echo "<strong>Statut:</strong> " . ($property->property_status ?? 'N/A') . " | ";
                echo "<strong>Date:</strong> " . ($property->property_date ?? 'N/A') . "</p>";
                
                // Infos agent
                if ($property->agent_id) {
                    echo "<div class='agent-info'>";
                    echo "<strong>👤 Agent:</strong> " . ($property->agent_name ?? 'Nom non défini');
                    echo " (ID: " . $property->agent_id . ")";
                    if ($property->agent_email) echo " | 📧 " . $property->agent_email;
                    if ($property->agent_phone) echo " | 📱 " . $property->agent_phone;
                    if ($property->agent_photo) echo " | 📸 <a href='" . $property->agent_photo . "' target='_blank'>Photo</a>";
                    echo "</div>";
                } else {
                    echo "<div class='agent-info' style='color: #999;'>👤 Aucun agent assigné</div>";
                }
                
                // Infos agence
                if ($property->agency_id) {
                    echo "<div class='agency-info'>";
                    echo "<strong>🏢 Agence:</strong> " . ($property->agency_name ?? 'Nom non défini');
                    echo " (ID: " . $property->agency_id . ")";
                    if ($property->agency_email) echo " | 📧 " . $property->agency_email;
                    if ($property->agency_phone) echo " | 📱 " . $property->agency_phone;
                    echo "</div>";
                } else {
                    echo "<div class='agency-info' style='color: #999;'>🏢 Aucune agence assignée</div>";
                }
                
                echo "</div>";
            }
            
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Erreur: " . $e->getMessage() . "</p>";
        }
    }

    /**
     * Test par agent spécifique
     */
    public function by_agent($agent_id = 1) {
        echo "<h1>👤 Propriétés de l'Agent ID: $agent_id</h1>";
        echo "<a href='" . base_url('test_properties') . "' class='btn'>⬅️ Retour</a>";
        
        try {
            $properties = $this->Property_model->get_from_property_agent_view([
                'agent_id' => $agent_id,
                'limit' => 20
            ]);
            
            echo "<p><strong>Résultats trouvés:</strong> " . count($properties) . "</p>";
            
            if (empty($properties)) {
                echo "<p style='color: orange;'>⚠️ Aucune propriété trouvée pour cet agent.</p>";
                return;
            }
            
            foreach ($properties as $property) {
                echo "<div style='border: 1px solid #ddd; margin: 10px 0; padding: 10px;'>";
                echo "<strong>" . $property->property_title . "</strong><br>";
                echo "Agent: " . $property->agent_name . "<br>";
                echo "Agence: " . ($property->agency_name ?? 'Non assignée') . "<br>";
                echo "</div>";
            }
            
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Erreur: " . $e->getMessage() . "</p>";
        }
    }

    /**
     * Test par agence spécifique
     */
    public function by_agency($agency_id = 1) {
        echo "<h1>🏢 Propriétés de l'Agence ID: $agency_id</h1>";
        echo "<a href='" . base_url('test_properties') . "' class='btn'>⬅️ Retour</a>";
        
        try {
            $properties = $this->Property_model->get_from_property_agent_view([
                'agency_id' => $agency_id,
                'limit' => 20
            ]);
            
            echo "<p><strong>Résultats trouvés:</strong> " . count($properties) . "</p>";
            
            if (empty($properties)) {
                echo "<p style='color: orange;'>⚠️ Aucune propriété trouvée pour cette agence.</p>";
                return;
            }
            
            foreach ($properties as $property) {
                echo "<div style='border: 1px solid #ddd; margin: 10px 0; padding: 10px;'>";
                echo "<strong>" . $property->property_title . "</strong><br>";
                echo "Agent: " . ($property->agent_name ?? 'Non assigné') . "<br>";
                echo "Agence: " . $property->agency_name . "<br>";
                echo "</div>";
            }
            
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Erreur: " . $e->getMessage() . "</p>";
        }
    }

    /**
     * Test de toutes les méthodes
     */
    public function test_methods() {
        echo "<h1>🔧 Test de toutes les méthodes</h1>";
        echo "<a href='" . base_url('test_properties') . "' class='btn'>⬅️ Retour</a>";
        
        echo "<style>
            .method-test { 
                border: 1px solid #ccc; 
                margin: 20px 0; 
                padding: 15px; 
                background: #f8f8f8; 
            }
            .method-title { 
                background: #007cba; 
                color: white; 
                padding: 8px; 
                margin: -15px -15px 10px -15px; 
            }
        </style>";
        
        // Test 1: Méthode avec requête directe
        echo "<div class='method-test'>";
        echo "<div class='method-title'>1. get_properties_with_agents_agencies()</div>";
        try {
            $start = microtime(true);
            $properties = $this->Property_model->get_properties_with_agents_agencies();
            $time = round((microtime(true) - $start) * 1000, 2);
            
            echo "<p>✅ <strong>Succès</strong> - " . count($properties) . " propriétés trouvées en {$time}ms</p>";
            
            if (!empty($properties)) {
                $sample = $properties[0];
                echo "<p><strong>Exemple:</strong> " . $sample->property_title . " (Agent: " . ($sample->agent_name ?? 'N/A') . ")</p>";
            }
        } catch (Exception $e) {
            echo "<p>❌ <strong>Erreur:</strong> " . $e->getMessage() . "</p>";
        }
        echo "</div>";
        
        // Test 2: Méthode avec vue
        echo "<div class='method-test'>";
        echo "<div class='method-title'>2. get_from_property_agent_view()</div>";
        try {
            $start = microtime(true);
            $properties = $this->Property_model->get_from_property_agent_view(['limit' => 100]);
            $time = round((microtime(true) - $start) * 1000, 2);
            
            echo "<p>✅ <strong>Succès</strong> - " . count($properties) . " propriétés trouvées en {$time}ms</p>";
            
            if (!empty($properties)) {
                $sample = $properties[0];
                echo "<p><strong>Exemple:</strong> " . $sample->property_title . " (Agent: " . ($sample->agent_name ?? 'N/A') . ")</p>";
            }
        } catch (Exception $e) {
            echo "<p>❌ <strong>Erreur:</strong> " . $e->getMessage() . "</p>";
        }
        echo "</div>";
        
        // Test 3: Filtres spécifiques
        echo "<div class='method-test'>";
        echo "<div class='method-title'>3. Tests avec filtres</div>";
        
        $filters = [
            ['property_status' => 'publish', 'limit' => 5],
            ['agent_id' => 1, 'limit' => 5],
            ['agency_id' => 1, 'limit' => 5]
        ];
        
        foreach ($filters as $i => $filter) {
            try {
                $properties = $this->Property_model->get_from_property_agent_view($filter);
                echo "<p>✅ Filtre " . ($i + 1) . ": " . count($properties) . " résultats - " . json_encode($filter) . "</p>";
            } catch (Exception $e) {
                echo "<p>❌ Filtre " . ($i + 1) . ": Erreur - " . $e->getMessage() . "</p>";
            }
        }
        echo "</div>";
    }

    /**
     * Test de création de vue (pour information)
     */
    public function create_view() {
        echo "<h1>🛠️ Création de la vue</h1>";
        
        try {
            $result = $this->Property_model->create_property_agent_view();
            
            if ($result) {
                echo "<p style='color: green;'>✅ Vue wp_Hrg8P_prop_agen créée avec succès!</p>";
            } else {
                echo "<p style='color: red;'>❌ Échec de la création de la vue.</p>";
            }
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Erreur: " . $e->getMessage() . "</p>";
        }
        
        echo "<a href='" . base_url('test_properties') . "' class='btn'>⬅️ Retour</a>";
    }
}
