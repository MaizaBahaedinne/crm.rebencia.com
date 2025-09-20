<?php
// Test des objectifs par agence pour les managers
require_once 'index.php';

echo "<h2>🎯 Test: Objectifs Mensuels - Filtrage par Agence Manager</h2>";

try {
    // Charger le CI
    $CI =& get_instance();
    if (!$CI) {
        throw new Exception("Impossible de charger CodeIgniter");
    }
    
    // Charger les modèles nécessaires
    $CI->load->model('Objective_model', 'objective_model');
    
    echo "<p style='color: green;'>✅ Modèle Objective_model chargé avec succès</p>";
    
    // Test de la nouvelle méthode get_agents_by_agency
    echo "<h3>🔍 Test de la méthode get_agents_by_agency</h3>";
    
    // Récupérer d'abord quelques agences pour tester
    $CI->load->database('wordpress');
    $wp_db = $CI->load->database('wordpress', TRUE);
    
    $agencies_query = "
        SELECT ID, post_title 
        FROM rebencia_RebenciaBD.wp_Hrg8P_posts 
        WHERE post_type = 'houzez_agency' 
        AND post_status = 'publish' 
        LIMIT 5
    ";
    
    $agencies = $wp_db->query($agencies_query)->result();
    
    echo "<p><strong>Agences trouvées :</strong> " . count($agencies) . "</p>";
    
    if (count($agencies) > 0) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 10px 0;'>";
        echo "<tr><th>ID Agence</th><th>Nom Agence</th><th>Agents dans cette agence</th><th>Détails</th></tr>";
        
        foreach ($agencies as $agency) {
            $agents_in_agency = $CI->objective_model->get_agents_by_agency($agency->ID);
            
            echo "<tr>";
            echo "<td><strong>" . $agency->ID . "</strong></td>";
            echo "<td>" . htmlspecialchars($agency->post_title) . "</td>";
            echo "<td><strong>" . count($agents_in_agency) . "</strong></td>";
            echo "<td>";
            
            if (count($agents_in_agency) > 0) {
                echo "<ul style='margin: 0; padding-left: 20px;'>";
                foreach ($agents_in_agency as $agent) {
                    echo "<li>" . htmlspecialchars($agent->display_name ?? $agent->agent_name ?? 'N/A') . 
                         " (ID: " . $agent->ID . ")</li>";
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
            echo "<h4>🔬 Test détaillé avec l'agence: " . htmlspecialchars($test_agency->post_title) . "</h4>";
            
            $agents_detailed = $CI->objective_model->get_agents_by_agency($test_agency->ID);
            
            if (count($agents_detailed) > 0) {
                echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
                echo "<tr><th>ID User</th><th>Nom Affiché</th><th>Email</th><th>ID Post Agent</th><th>Nom Agent</th><th>Agence</th></tr>";
                
                foreach ($agents_detailed as $agent) {
                    echo "<tr>";
                    echo "<td>" . ($agent->ID ?? 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($agent->display_name ?? 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($agent->user_email ?? 'N/A') . "</td>";
                    echo "<td>" . ($agent->agent_post_id ?? 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($agent->agent_name ?? 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($agent->agency_name ?? 'N/A') . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p style='color: orange;'>⚠️ Aucun agent trouvé pour cette agence</p>";
            }
        }
    } else {
        echo "<p style='color: orange;'>⚠️ Aucune agence trouvée</p>";
    }
    
    // Comparaison avec get_agents() classique
    echo "<hr>";
    echo "<h3>📊 Comparaison: Tous les agents vs Agents par agence</h3>";
    
    $all_agents = $CI->objective_model->get_agents();
    echo "<p><strong>Tous les agents :</strong> " . count($all_agents) . "</p>";
    
    $total_agents_by_agency = 0;
    foreach ($agencies as $agency) {
        $agents_in_agency = $CI->objective_model->get_agents_by_agency($agency->ID);
        $total_agents_by_agency += count($agents_in_agency);
    }
    echo "<p><strong>Total agents dans toutes les agences :</strong> " . $total_agents_by_agency . "</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ <strong>Erreur :</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre style='background: #f8f9fa; padding: 10px; border-radius: 5px;'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo "<hr>";
echo "<h3>🔗 Liens de test</h3>";
echo "<p><a href='/crm.rebencia.com/objectives/set_monthly' style='display: inline-block; background: #007bff; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin: 5px;' target='_blank'>→ Tester la page définir objectifs mensuels</a></p>";
echo "<p><a href='/crm.rebencia.com/objectives' style='display: inline-block; background: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin: 5px;' target='_blank'>→ Dashboard objectifs</a></p>";

echo "<hr>";
echo "<h3>📝 Fonctionnalités implémentées</h3>";
echo "<ul>";
echo "<li>✅ Nouvelle méthode <code>get_agents_by_agency(\$agency_id)</code> dans Objective_model</li>";
echo "<li>✅ Filtrage des agents par agence pour les managers dans le contrôleur</li>";
echo "<li>✅ Validation de sécurité : empêche un manager de définir des objectifs pour les agents d'autres agences</li>";
echo "<li>✅ Notification visuelle dans la vue pour informer le manager qu'il ne voit que ses agents</li>";
echo "<li>✅ Conservation du comportement existant pour les admins (voient tous les agents)</li>";
echo "</ul>";

echo "<hr>";
echo "<h3>🔐 Sécurité</h3>";
echo "<ul>";
echo "<li>✅ Vérification du rôle Manager et de l'agency_id</li>";
echo "<li>✅ Validation côté serveur dans _process_monthly_objectives</li>";
echo "<li>✅ Requête SQL sécurisée avec paramètres liés</li>";
echo "<li>✅ Messages d'erreur appropriés si tentative de contournement</li>";
echo "</ul>";
?>
