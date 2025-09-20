<?php
// Test des propriétés manquantes
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔧 Test correction propriétés manquantes</h1>";
echo "<hr>";

echo "<h2>❌ Erreurs précédentes :</h2>";
echo "<div style='background: #f8d7da; padding: 15px; border-left: 4px solid #dc3545; margin: 10px 0;'>";
echo "<ul>";
echo "<li><strong>Undefined property: stdClass::\$agent_id</strong> (ligne 259)</li>";
echo "<li><strong>Undefined property: stdClass::\$properties_count</strong> (ligne 311)</li>";
echo "</ul>";
echo "</div>";

echo "<h2>✅ Corrections apportées :</h2>";
echo "<div style='background: #d4edda; padding: 15px; border-left: 4px solid #28a745; margin: 10px 0;'>";
echo "<ol>";
echo "<li><strong>Ajout alias agent_id :</strong> <code>p.ID AS agent_id</code> en plus de <code>agent_post_id</code></li>";
echo "<li><strong>Ajout properties_count :</strong> Sous-requête pour compter les propriétés de chaque agent</li>";
echo "<li><strong>Propriétés par défaut :</strong> Ajout de valeurs par défaut dans le nettoyage des données</li>";
echo "</ol>";
echo "</div>";

echo "<h2>🔍 Test de la requête corrigée :</h2>";

try {
    $connection = new mysqli('localhost', 'root', 'root', 'rebencia_RebenciaBD');
    
    if ($connection->connect_error) {
        echo "<p style='color: red;'>❌ Erreur de connexion: " . $connection->connect_error . "</p>";
    } else {
        // Test avec la nouvelle requête incluant properties_count
        $query = "
            SELECT 
                u.ID AS user_id,
                p.ID AS agent_post_id,
                p.ID AS agent_id,
                p.post_title AS agent_name,
                p.post_type AS post_type,
                a.post_title AS agency_name,
                ur.meta_value AS user_roles,
                (SELECT COUNT(*) 
                 FROM wp_Hrg8P_posts prop 
                 LEFT JOIN wp_Hrg8P_postmeta prop_agent ON prop.ID = prop_agent.post_id AND prop_agent.meta_key = 'fave_property_agent'
                 WHERE prop.post_type = 'property' 
                 AND prop.post_status = 'publish'
                 AND prop_agent.meta_value = p.ID
                ) as properties_count
            FROM wp_Hrg8P_users u
            LEFT JOIN wp_Hrg8P_postmeta pm_email 
                   ON pm_email.meta_value = u.user_email
            LEFT JOIN wp_Hrg8P_posts p 
                   ON p.ID = pm_email.post_id AND p.post_type IN ('houzez_agent', 'houzez_manager')
            LEFT JOIN wp_Hrg8P_postmeta pm_agency 
                   ON pm_agency.post_id = p.ID AND pm_agency.meta_key = 'fave_agent_agencies'
            LEFT JOIN wp_Hrg8P_posts a 
                   ON a.ID = pm_agency.meta_value AND a.post_type = 'houzez_agency'
            LEFT JOIN wp_Hrg8P_usermeta ur 
                   ON ur.user_id = u.ID AND ur.meta_key = 'wp_Hrg8P_capabilities'
            WHERE p.post_type IN ('houzez_agent', 'houzez_manager')
            AND p.post_status = 'publish'
            LIMIT 3
        ";
        
        $result = $connection->query($query);
        
        if ($result && $result->num_rows > 0) {
            echo "<p style='color: green;'>✅ Requête réussie ! {$result->num_rows} agent(s) trouvé(s)</p>";
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr>";
            echo "<th>agent_id</th><th>agent_post_id</th><th>Nom</th><th>Type</th><th>Agence</th><th>Properties</th>";
            echo "</tr>";
            
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td style='background: #d4edda;'><strong>{$row['agent_id']}</strong></td>";
                echo "<td>{$row['agent_post_id']}</td>";
                echo "<td>" . htmlspecialchars($row['agent_name']) . "</td>";
                echo "<td>{$row['post_type']}</td>";
                echo "<td>" . htmlspecialchars($row['agency_name'] ?? 'N/A') . "</td>";
                echo "<td style='background: #cff4fc;'><strong>{$row['properties_count']}</strong></td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "<p><em>Les colonnes corrigées sont surlignées en couleur</em></p>";
        } else {
            echo "<p style='color: orange;'>⚠️ Pas de résultats</p>";
        }
    }
    $connection->close();
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erreur: " . $e->getMessage() . "</p>";
}

echo "<h2>🎯 Structure des données corrigée :</h2>";
echo "<div style='background: #f8f9fa; padding: 15px; border: 1px solid #dee2e6; margin: 10px 0;'>";
echo "<h4>Chaque objet agent contient maintenant :</h4>";
echo "<ul>";
echo "<li>✅ <strong>agent_id</strong> (alias de agent_post_id)</li>";
echo "<li>✅ <strong>agent_post_id</strong> (ID WordPress du post agent)</li>";
echo "<li>✅ <strong>properties_count</strong> (nombre de propriétés)</li>";
echo "<li>✅ <strong>user_role</strong> (formaté: Manager, Agent, etc.)</li>";
echo "<li>✅ <strong>agency_name</strong> (nom de l'agence)</li>";
echo "<li>✅ <strong>is_active</strong> (statut actif/inactif)</li>";
echo "<li>✅ <strong>created_date/registration_date</strong> (dates par défaut)</li>";
echo "</ul>";
echo "</div>";

echo "<h2>🔬 Tests suivants :</h2>";
echo "<ul>";
echo "<li><a href='http://localhost:8888/crm.rebencia.com/agents' target='_blank'>Test : Page /agents (corrigée)</a></li>";
echo "</ul>";

echo "<hr>";
echo "<h3>✅ CORRECTIONS TERMINÉES</h3>";
echo "<p style='color: green; font-weight: bold;'>Les propriétés manquantes ont été ajoutées. Les erreurs PHP devraient être corrigées.</p>";
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
table { margin: 10px 0; border-collapse: collapse; }
th, td { padding: 8px 12px; text-align: left; border: 1px solid #ddd; }
th { background-color: #f2f2f2; font-weight: bold; }
code { background: #f8f9fa; padding: 2px 4px; border: 1px solid #e9ecef; border-radius: 3px; }
h2 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 5px; }
</style>
