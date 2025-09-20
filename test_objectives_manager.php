<?php
// Test des objectifs par agence pour les managers - Version mise à jour avec wp_Hrg8P_crm_agents
require_once 'index.php';

echo "<h2>🎯 Test: Objectifs Mensuels - Filtrage par Agence Manager (Vue wp_Hrg8P_crm_agents)</h2>";

try {
    // Charger le CI
    $CI =& get_instance();
    if (!$CI) {
        throw new Exception("Impossible de charger CodeIgniter");
    }
    
    // Charger les modèles nécessaires
    $CI->load->model('Objective_model', 'objective_model');
    
    echo "<p style='color: green;'>✅ Modèle Objective_model chargé avec succès</p>";
    
    // Test de la vue wp_Hrg8P_crm_agents
    echo "<h3>🔍 Test de la vue wp_Hrg8P_crm_agents</h3>";
    
    $CI->load->database('wordpress');
    $wp_db = $CI->load->database('wordpress', TRUE);
    
    // Vérifier que la vue existe
    $view_check = $wp_db->query("SHOW TABLES LIKE 'wp_Hrg8P_crm_agents'")->result();
    if (count($view_check) > 0) {
        echo "<p style='color: green;'>✅ Vue wp_Hrg8P_crm_agents trouvée</p>";
        
        // Compter le nombre total d'agents dans la vue
        $total_agents_query = "SELECT COUNT(*) as total FROM rebencia_RebenciaBD.wp_Hrg8P_crm_agents WHERE post_status = 'publish'";
        $total_result = $wp_db->query($total_agents_query)->row();
        echo "<p><strong>Total agents dans la vue :</strong> " . $total_result->total . "</p>";
        
    } else {
        echo "<p style='color: red;'>❌ Vue wp_Hrg8P_crm_agents non trouvée</p>";
    }
    
    // Test de la nouvelle méthode get_agents_by_agency
    echo "<h3>🔍 Test de la méthode get_agents_by_agency (mise à jour)</h3>";
    
    // Récupérer quelques agences pour tester
    $agencies_query = "
        SELECT DISTINCT agency_id, agency_name 
        FROM rebencia_RebenciaBD.wp_Hrg8P_crm_agents 
        WHERE agency_id IS NOT NULL 
        AND agency_name IS NOT NULL
        AND post_status = 'publish'
        LIMIT 5
    ";
    
    $agencies = $wp_db->query($agencies_query)->result();
    
    echo "<p><strong>Agences trouvées dans la vue :</strong> " . count($agencies) . "</p>";
    
    if (count($agencies) > 0) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 10px 0;'>";
        echo "<tr><th>ID Agence</th><th>Nom Agence</th><th>Agents dans cette agence</th><th>Détails</th></tr>";
        
        foreach ($agencies as $agency) {
            $agents_in_agency = $CI->objective_model->get_agents_by_agency($agency->agency_id);
            
            echo "<tr>";
            echo "<td><strong>" . $agency->agency_id . "</strong></td>";
            echo "<td>" . htmlspecialchars($agency->agency_name) . "</td>";
            echo "<td><strong>" . count($agents_in_agency) . "</strong></td>";
            echo "<td>";
            
            if (count($agents_in_agency) > 0) {
                echo "<ul style='margin: 0; padding-left: 20px;'>";
                foreach ($agents_in_agency as $agent) {
                    echo "<li>" . htmlspecialchars($agent->agent_name ?? 'N/A') . 
                         " (User ID: " . $agent->user_id . ", Email: " . htmlspecialchars($agent->user_email ?? 'N/A') . ")</li>";
                }
                echo "</ul>";
            } else {
                echo "<em>Aucun agent</em>";
            }
            
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Test avec une agence spécifique
        if (count($agencies) > 0) {
            $test_agency = $agencies[0];
            echo "<h4>🔬 Test détaillé avec l'agence: " . htmlspecialchars($test_agency->agency_name) . "</h4>";
            
            $agents_detailed = $CI->objective_model->get_agents_by_agency($test_agency->agency_id);
            
            if (count($agents_detailed) > 0) {
                echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
                echo "<tr><th>User ID</th><th>Login</th><th>Email</th><th>Nom Agent</th><th>Agence</th><th>Téléphone</th><th>Mobile</th></tr>";
                
                foreach ($agents_detailed as $agent) {
                    echo "<tr>";
                    echo "<td>" . ($agent->user_id ?? 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($agent->user_login ?? 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($agent->user_email ?? 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($agent->agent_name ?? 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($agent->agency_name ?? 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($agent->phone ?? 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($agent->mobile ?? 'N/A') . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p style='color: orange;'>⚠️ Aucun agent trouvé pour cette agence</p>";
            }
        }
    } else {
        echo "<p style='color: orange;'>⚠️ Aucune agence trouvée dans la vue</p>";
    }
    
    // Comparaison avec get_agents() classique
    echo "<hr>";
    echo "<h3>📊 Comparaison: Tous les agents vs Agents par agence</h3>";
    
    $all_agents = $CI->objective_model->get_agents();
    echo "<p><strong>Tous les agents (nouvelle méthode) :</strong> " . count($all_agents) . "</p>";
    
    if (count($all_agents) > 0) {
        echo "<h4>📋 Aperçu des premiers agents (tous) :</h4>";
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>User ID</th><th>Nom Agent</th><th>Email</th><th>Agence</th><th>Téléphone</th></tr>";
        
        foreach (array_slice($all_agents, 0, 5) as $agent) {
            echo "<tr>";
            echo "<td>" . ($agent->user_id ?? 'N/A') . "</td>";
            echo "<td>" . htmlspecialchars($agent->agent_name ?? 'N/A') . "</td>";
            echo "<td>" . htmlspecialchars($agent->user_email ?? 'N/A') . "</td>";
            echo "<td>" . htmlspecialchars($agent->agency_name ?? 'N/A') . "</td>";
            echo "<td>" . htmlspecialchars($agent->phone ?? 'N/A') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    echo "<hr>";
    echo "<h3>🧪 Test de compatibilité des champs</h3>";
    if (count($all_agents) > 0) {
        $test_agent = $all_agents[0];
        echo "<h4>Structure d'un agent type :</h4>";
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Propriété</th><th>Valeur</th></tr>";
        
        $properties = get_object_vars($test_agent);
        foreach ($properties as $prop => $value) {
            $display_value = is_null($value) ? '<em>NULL</em>' : htmlspecialchars((string)$value);
            echo "<tr><td><strong>$prop</strong></td><td>$display_value</td></tr>";
        }
        echo "</table>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ <strong>Erreur :</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre style='background: #f8f9fa; padding: 10px; border-radius: 5px;'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo "<hr>";
echo "<h3>🔗 Liens de test</h3>";
echo "<p><a href='/crm.rebencia.com/objectives/set_monthly' style='display: inline-block; background: #007bff; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin: 5px;' target='_blank'>→ Tester la page définir objectifs mensuels</a></p>";
echo "<p><a href='/crm.rebencia.com/objectives' style='display: inline-block; background: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin: 5px;' target='_blank'>→ Dashboard objectifs</a></p>";

echo "<hr>";
echo "<h3>📝 Améliorations apportées</h3>";
echo "<ul>";
echo "<li>✅ Utilisation de la vue complète <code>wp_Hrg8P_crm_agents</code> au lieu de requêtes manuelles</li>";
echo "<li>✅ Accès à tous les champs : téléphone, mobile, whatsapp, réseaux sociaux, etc.</li>";
echo "<li>✅ Structure de données cohérente entre <code>get_agents()</code> et <code>get_agents_by_agency()</code></li>";
echo "<li>✅ Ajout du nom de l'agence dans la liste déroulante pour plus de clarté</li>";
echo "<li>✅ Compatibilité avec les alias <code>ID</code> et <code>display_name</code> pour la rétrocompatibilité</li>";
echo "<li>✅ Validation de sécurité mise à jour pour utiliser <code>user_id</code></li>";
echo "</ul>";

echo "<hr>";
echo "<h3>🔐 Sécurité maintenue</h3>";
echo "<ul>";
echo "<li>✅ Filtrage par <code>post_status = 'publish'</code></li>";
echo "<li>✅ Requêtes SQL sécurisées avec paramètres liés</li>";
echo "<li>✅ Validation côté serveur dans le contrôleur</li>";
echo "<li>✅ Messages d'erreur appropriés</li>";
echo "</ul>";
?>
