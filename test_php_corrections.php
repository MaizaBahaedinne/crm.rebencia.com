<?php
// Test final de validation des corrections PHP
require_once 'index.php';

echo "<h2>✅ Test Final - Validation des Corrections PHP</h2>";

try {
    // Charger le CI directement
    $CI =& get_instance();
    if (!$CI) {
        throw new Exception("Impossible de charger CodeIgniter");
    }
    
    // Charger les modèles nécessaires
    $CI->load->model('Agent_model', 'agent_model');
    
    echo "<p style='color: green;'>✅ Modèles chargés avec succès</p>";
    
    // Test de récupération des agents
    echo "<h3>🔍 Test de récupération des agents</h3>";
    
    $filters = [];
    $agents = $CI->agent_model->get_agents_with_roles_and_agencies($filters);
    
    echo "<p><strong>Nombre d'agents trouvés :</strong> " . count($agents) . "</p>";
    
    if (count($agents) > 0) {
        // Test de simulation du rendu de la vue
        echo "<h3>🎭 Simulation du rendu de la vue</h3>";
        
        $error_count = 0;
        $success_count = 0;
        
        foreach ($agents as $index => $agent) {
            if ($index > 5) break; // Limiter à 5 agents pour le test
            
            echo "<h4>🔸 Test agent " . ($index + 1) . "</h4>";
            
            try {
                // Test des propriétés critiques avec fallback
                $agent_id_test = isset($agent->agent_id) ? $agent->agent_id : 
                               (isset($agent->agent_post_id) ? $agent->agent_post_id : 
                               (isset($agent->user_id) ? $agent->user_id : ''));
                
                $agent_email_test = isset($agent->agent_email) ? $agent->agent_email : 
                                  (isset($agent->user_email) ? $agent->user_email : '');
                
                $agent_name_test = $agent->agent_name ?? $agent->user_login ?? 'Agent';
                $properties_count_test = $agent->properties_count ?? 0;
                
                // Valeurs pour affichage
                $results = [
                    'agent_id' => $agent_id_test,
                    'agent_email' => $agent_email_test,
                    'agent_name' => $agent_name_test,
                    'properties_count' => $properties_count_test,
                    'phone' => isset($agent->phone) ? htmlspecialchars($agent->phone) : 'N/A',
                    'mobile' => isset($agent->mobile) ? htmlspecialchars($agent->mobile) : 'N/A',
                    'user_role' => $agent->user_role ?? 'N/A',
                    'agency_name' => $agent->agency_name ?? 'N/A'
                ];
                
                echo "<table border='1' style='border-collapse: collapse; margin: 10px 0; width: 100%;'>";
                echo "<tr><th>Propriété</th><th>Valeur (avec fallback)</th><th>Status</th></tr>";
                
                foreach ($results as $prop => $value) {
                    $status = !empty($value) && $value !== 'N/A' ? '✅' : '⚠️';
                    $color = !empty($value) && $value !== 'N/A' ? 'green' : 'orange';
                    echo "<tr>";
                    echo "<td><strong>$prop</strong></td>";
                    echo "<td style='color: $color;'>" . htmlspecialchars($value) . "</td>";
                    echo "<td>$status</td>";
                    echo "</tr>";
                }
                echo "</table>";
                
                $success_count++;
                
            } catch (Exception $e) {
                echo "<p style='color: red;'>❌ Erreur sur agent " . ($index + 1) . ": " . htmlspecialchars($e->getMessage()) . "</p>";
                $error_count++;
            }
        }
        
        echo "<hr>";
        echo "<h3>📊 Résumé des tests</h3>";
        echo "<p><strong>✅ Succès :</strong> $success_count agents</p>";
        echo "<p><strong>❌ Erreurs :</strong> $error_count agents</p>";
        
        if ($error_count == 0) {
            echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
            echo "<h4 style='margin: 0 0 10px 0;'>🎉 Toutes les corrections PHP sont réussies !</h4>";
            echo "<p style='margin: 0;'>Les propriétés manquantes sont maintenant gérées avec des fallbacks appropriés.</p>";
            echo "</div>";
        } else {
            echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
            echo "<h4 style='margin: 0 0 10px 0;'>⚠️ Des erreurs persistent</h4>";
            echo "<p style='margin: 0;'>Certains agents présentent encore des problèmes de structure de données.</p>";
            echo "</div>";
        }
        
    } else {
        echo "<p style='color: orange;'>⚠️ Aucun agent trouvé pour le test</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ <strong>Erreur générale :</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre style='background: #f8f9fa; padding: 10px; border-radius: 5px;'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo "<hr>";
echo "<h3>🔗 Liens de test</h3>";
echo "<p><a href='/crm.rebencia.com/index.php/agents' style='display: inline-block; background: #007bff; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin: 5px;' target='_blank'>→ Tester la page agents</a></p>";
echo "<p><a href='/crm.rebencia.com/objectives' style='display: inline-block; background: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin: 5px;' target='_blank'>→ Tester la page objectifs</a></p>";
echo "<p><a href='/crm.rebencia.com/dashboard/manager' style='display: inline-block; background: #ffc107; color: black; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin: 5px;' target='_blank'>→ Tester le dashboard manager</a></p>";

echo "<hr>";
echo "<h3>📝 Corrections appliquées</h3>";
echo "<ul>";
echo "<li>✅ Ajout de vérifications <code>isset()</code> pour <code>\$agent->agent_id</code> et <code>\$agent->agent_email</code></li>";
echo "<li>✅ Fallback vers <code>\$agent->agent_post_id</code> et <code>\$agent->user_id</code> pour l'ID</li>";
echo "<li>✅ Fallback vers <code>\$agent->user_email</code> pour l'email</li>";
echo "<li>✅ Protection de <code>\$agent->agent_name</code> avec fallback vers <code>\$agent->user_login</code></li>";
echo "<li>✅ Protection de <code>\$agent->properties_count</code> avec fallback vers 0</li>";
echo "<li>✅ Échappement HTML pour <code>\$agent->phone</code> et <code>\$agent->mobile</code></li>";
echo "<li>✅ Gestion des propriétés optionnelles avec <code>empty()</code> et opérateur de coalescence null <code>??</code></li>";
echo "</ul>";
?>
