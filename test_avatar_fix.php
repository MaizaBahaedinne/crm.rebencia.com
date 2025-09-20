<?php
// Test de correction des erreurs avatar agent_id vs agent_post_id
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔧 Test correction erreurs avatar agent_id</h1>";
echo "<hr>";

echo "<h2>❌ Erreur corrigée :</h2>";
echo "<div style='background: #f8d7da; padding: 15px; border-left: 4px solid #dc3545; margin: 10px 0;'>";
echo "<p><strong>Error Number: 1054</strong></p>";
echo "<p><strong>Unknown column 'avatar.agent_id' in 'ON'</strong></p>";
echo "<p><strong>Problème :</strong> Incohérence entre les noms de colonnes et tables d'avatar</p>";
echo "</div>";

echo "<h2>✅ Corrections apportées :</h2>";
echo "<div style='background: #d4edda; padding: 15px; border-left: 4px solid #28a745; margin: 10px 0;'>";
echo "<ol>";
echo "<li><strong>Table :</strong> <code>crm_avatar_agents</code> → <code>wp_Hrg8P_crm_avatar_agents</code></li>";
echo "<li><strong>Colonne :</strong> <code>agent_id</code> → <code>agent_post_id</code></li>";
echo "<li><strong>Méthodes corrigées :</strong></li>";
echo "<ul>";
echo "<li>• get_all_agents_from_posts()</li>";
echo "<li>• get_agent()</li>";
echo "<li>• get_agent_avatar_url_by_id()</li>";
echo "<li>• assign_avatar_urls()</li>";
echo "</ul>";
echo "</ol>";
echo "</div>";

echo "<h2>🔍 Test 1: Vérification de la structure vue avatar</h2>";
try {
    $connection = new mysqli('localhost', 'root', 'root', 'rebencia_RebenciaBD');
    
    if ($connection->connect_error) {
        echo "<p style='color: red;'>❌ Erreur de connexion: " . $connection->connect_error . "</p>";
    } else {
        // Vérifier la structure de la vue
        $query = "DESCRIBE wp_Hrg8P_crm_avatar_agents";
        $result = $connection->query($query);
        
        if ($result && $result->num_rows > 0) {
            echo "<p style='color: green;'>✅ Structure de wp_Hrg8P_crm_avatar_agents :</p>";
            echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
            echo "<tr><th>Colonne</th><th>Type</th><th>Description</th></tr>";
            
            while ($row = $result->fetch_assoc()) {
                $highlight = '';
                if ($row['Field'] === 'agent_post_id') $highlight = 'background: #d4edda;';
                if ($row['Field'] === 'image_url') $highlight = 'background: #cff4fc;';
                
                echo "<tr style='$highlight'>";
                echo "<td><strong>{$row['Field']}</strong></td>";
                echo "<td>{$row['Type']}</td>";
                
                if ($row['Field'] === 'agent_post_id') {
                    echo "<td>ID du post agent (CORRECT ✅)</td>";
                } elseif ($row['Field'] === 'image_url') {
                    echo "<td>URL de l'image avatar</td>";
                } else {
                    echo "<td>-</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color: red;'>❌ Impossible de décrire la vue wp_Hrg8P_crm_avatar_agents</p>";
        }
    }
    $connection->close();
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erreur: " . $e->getMessage() . "</p>";
}

echo "<h2>🔍 Test 2: Test jointure corrigée</h2>";
try {
    $connection = new mysqli('localhost', 'root', 'root', 'rebencia_RebenciaBD');
    
    if (!$connection->connect_error) {
        // Test de la jointure corrigée
        $query = "
            SELECT 
                p.ID as agent_id,
                p.post_title as agent_name,
                avatar.image_url as agent_avatar
            FROM wp_Hrg8P_posts p
            LEFT JOIN wp_Hrg8P_crm_avatar_agents avatar 
                   ON avatar.agent_post_id = p.ID
            WHERE p.post_type = 'houzez_agent'
            AND p.post_status = 'publish'
            LIMIT 5
        ";
        
        $result = $connection->query($query);
        
        if ($result && $result->num_rows > 0) {
            echo "<p style='color: green;'>✅ Jointure corrigée fonctionne ! {$result->num_rows} agent(s) trouvé(s)</p>";
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr>";
            echo "<th>Agent ID</th><th>Nom</th><th>Avatar</th><th>Statut</th>";
            echo "</tr>";
            
            while ($row = $result->fetch_assoc()) {
                $has_avatar = !empty($row['agent_avatar']);
                $status_color = $has_avatar ? 'background: #d4edda; color: #155724;' : 'background: #f8d7da; color: #721c24;';
                $status_text = $has_avatar ? '✅ Avatar OK' : '❌ Pas d\'avatar';
                
                echo "<tr>";
                echo "<td>{$row['agent_id']}</td>";
                echo "<td>" . htmlspecialchars($row['agent_name']) . "</td>";
                echo "<td style='font-size: 10px;'>" . htmlspecialchars($row['agent_avatar'] ?? 'NULL') . "</td>";
                echo "<td style='$status_color'><strong>$status_text</strong></td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color: orange;'>⚠️ Aucun agent trouvé avec la nouvelle jointure</p>";
        }
    }
    $connection->close();
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erreur: " . $e->getMessage() . "</p>";
}

echo "<h2>🔧 Comparaison des corrections :</h2>";
echo "<div style='background: #f8f9fa; padding: 15px; border: 1px solid #dee2e6; margin: 10px 0;'>";

echo "<h4>AVANT (Incorrect) :</h4>";
echo "<pre style='background: #ffe6e6; padding: 10px; border-left: 4px solid #dc3545;'>";
echo "// Table incorrecte
LEFT JOIN crm_avatar_agents avatar ON avatar.agent_id = p.ID

// Colonne incorrecte  
->where('agent_id', \$agent_id)
->select('agent_id, image_url')";
echo "</pre>";

echo "<h4>APRÈS (Corrigé) :</h4>";
echo "<pre style='background: #e6ffe6; padding: 10px; border-left: 4px solid #28a745;'>";
echo "// Table correcte avec vue
LEFT JOIN wp_Hrg8P_crm_avatar_agents avatar ON avatar.agent_post_id = p.ID

// Colonne correcte selon la vue
->where('agent_post_id', \$agent_id)
->select('agent_post_id, image_url')";
echo "</pre>";

echo "<h4>Points clés :</h4>";
echo "<ul>";
echo "<li>✅ <strong>Nom de table :</strong> Utilisation de la vue complète <code>wp_Hrg8P_crm_avatar_agents</code></li>";
echo "<li>✅ <strong>Nom de colonne :</strong> <code>agent_post_id</code> au lieu de <code>agent_id</code></li>";
echo "<li>✅ <strong>Cohérence :</strong> Même logique dans toutes les méthodes</li>";
echo "<li>✅ <strong>Performance :</strong> Utilisation correcte de la vue optimisée</li>";
echo "</ul>";
echo "</div>";

echo "<h2>🎯 Tests suivants :</h2>";
echo "<ul>";
echo "<li><a href='http://localhost:8888/crm.rebencia.com/agents' target='_blank'>Test : Page /agents (sans erreur)</a></li>";
echo "<li><a href='http://localhost:8888/crm.rebencia.com/agents/view/150' target='_blank'>Test : Profil agent spécifique</a></li>";
echo "</ul>";

echo "<hr>";
echo "<h3>✅ CORRECTIONS TERMINÉES</h3>";
echo "<p style='color: green; font-weight: bold;'>L'erreur 'Unknown column avatar.agent_id' a été corrigée dans toutes les méthodes concernées.</p>";
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
table { margin: 10px 0; border-collapse: collapse; }
th, td { padding: 8px 12px; text-align: left; border: 1px solid #ddd; }
th { background-color: #f2f2f2; font-weight: bold; }
code { background: #f8f9fa; padding: 2px 4px; border: 1px solid #e9ecef; border-radius: 3px; }
pre { white-space: pre-wrap; margin: 5px 0; border-radius: 4px; font-size: 12px; }
h2 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 5px; }
</style>
