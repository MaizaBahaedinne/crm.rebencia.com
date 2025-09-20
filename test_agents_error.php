<?php
// Test pour diagnostiquer les erreurs des agents
require_once 'index.php';

echo "<h2>🔍 Diagnostic des erreurs agents</h2>";

// Simuler l'appel du contrôleur Agent
try {
    // Charger le CI directement
    $CI =& get_instance();
    if (!$CI) {
        throw new Exception("Impossible de charger CodeIgniter");
    }
    
    // Charger les modèles nécessaires
    $CI->load->model('Agent_model', 'agent_model');
    $CI->load->model('Agency_model', 'agency_model');
    
    echo "<p style='color: green;'>✅ Modèles chargés avec succès</p>";
    
    // Test de récupération des agents
    echo "<h3>📊 Test de récupération des agents</h3>";
    
    $filters = [];
    $agents = $CI->agent_model->get_agents_with_roles_and_agencies($filters);
    
    echo "<p><strong>Nombre d'agents trouvés :</strong> " . count($agents) . "</p>";
    
    if (count($agents) > 0) {
        echo "<h4>🔎 Structure du premier agent :</h4>";
        $first_agent = $agents[0];
        
        echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 10px 0;'>";
        echo "<tr><th>Propriété</th><th>Valeur</th><th>Type</th></tr>";
        
        $properties = get_object_vars($first_agent);
        foreach ($properties as $prop => $value) {
            $value_display = is_null($value) ? '<em>NULL</em>' : 
                            (is_string($value) ? htmlspecialchars($value) : 
                            (is_numeric($value) ? $value : 
                            (is_bool($value) ? ($value ? 'true' : 'false') : 
                            gettype($value))));
            
            $color = is_null($value) ? 'color: red;' : '';
            echo "<tr>";
            echo "<td><strong>$prop</strong></td>";
            echo "<td style='$color'>$value_display</td>";
            echo "<td>" . gettype($value) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Vérifier les propriétés critiques
        echo "<h4>🎯 Vérification des propriétés critiques :</h4>";
        $critical_props = ['agent_id', 'agent_email', 'agent_post_id', 'user_id', 'user_email', 'agent_name'];
        
        echo "<ul>";
        foreach ($critical_props as $prop) {
            $exists = property_exists($first_agent, $prop);
            $value = $exists ? $first_agent->$prop : 'N/A';
            $status = $exists ? '✅' : '❌';
            $color = $exists ? 'green' : 'red';
            
            echo "<li style='color: $color;'>$status <strong>$prop</strong>: " . htmlspecialchars($value) . "</li>";
        }
        echo "</ul>";
        
        // Test de la logique de fallback
        echo "<h4>🔄 Test de la logique de fallback :</h4>";
        $agent_id_fallback = isset($first_agent->agent_id) ? $first_agent->agent_id : 
                           (isset($first_agent->agent_post_id) ? $first_agent->agent_post_id : 
                           (isset($first_agent->user_id) ? $first_agent->user_id : ''));
                           
        $agent_email_fallback = isset($first_agent->agent_email) ? $first_agent->agent_email : 
                              (isset($first_agent->user_email) ? $first_agent->user_email : '');
        
        echo "<p><strong>ID final (avec fallback) :</strong> " . htmlspecialchars($agent_id_fallback) . "</p>";
        echo "<p><strong>Email final (avec fallback) :</strong> " . htmlspecialchars($agent_email_fallback) . "</p>";
        
        // Afficher quelques autres agents pour comparaison
        if (count($agents) > 1) {
            echo "<h4>📋 Aperçu des premiers agents :</h4>";
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>Nom</th><th>agent_id</th><th>agent_email</th><th>user_id</th><th>user_email</th></tr>";
            
            foreach (array_slice($agents, 0, 5) as $agent) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($agent->agent_name ?? 'N/A') . "</td>";
                echo "<td>" . htmlspecialchars($agent->agent_id ?? 'N/A') . "</td>";
                echo "<td>" . htmlspecialchars($agent->agent_email ?? 'N/A') . "</td>";
                echo "<td>" . htmlspecialchars($agent->user_id ?? 'N/A') . "</td>";
                echo "<td>" . htmlspecialchars($agent->user_email ?? 'N/A') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    } else {
        echo "<p style='color: orange;'>⚠️ Aucun agent trouvé</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ <strong>Erreur :</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>Trace :</strong></p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo "<hr>";
echo "<h3>🔗 Test des liens</h3>";
echo "<p><a href='/crm.rebencia.com/agents' target='_blank'>→ Tester la page agents (sans index.php)</a></p>";
echo "<p><a href='/crm.rebencia.com/index.php/agents' target='_blank'>→ Tester la page agents (avec index.php)</a></p>";
?>
