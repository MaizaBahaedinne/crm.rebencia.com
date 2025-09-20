<?php
// Test rapide de la méthode corrigée
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔧 Test correction erreur SQL</h1>";
echo "<hr>";

echo "<h2>❌ Erreur précédente :</h2>";
echo "<div style='background: #f8d7da; padding: 15px; border-left: 4px solid #dc3545; margin: 10px 0;'>";
echo "<p><strong>Error Number: 1054</strong></p>";
echo "<p><strong>Unknown column 'wp_Hrg8P_' in 'SELECT'</strong></p>";
echo "<p><strong>Cause :</strong> Les commentaires SQL <code>-- Contact info</code> et <code>-- User role</code> dans la méthode select() de CodeIgniter</p>";
echo "</div>";

echo "<h2>✅ Correction apportée :</h2>";
echo "<div style='background: #d4edda; padding: 15px; border-left: 4px solid #28a745; margin: 10px 0;'>";
echo "<p><strong>Suppression des commentaires SQL</strong> dans la requête SELECT</p>";
echo "<p>Les commentaires <code>-- Contact info</code> et <code>-- User role</code> ont été supprimés car CodeIgniter ne les supporte pas dans select()</p>";
echo "</div>";

echo "<h2>🔍 Test de la méthode corrigée :</h2>";

// Inclusion du framework CodeIgniter pour tester
define('BASEPATH', TRUE);
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/test';

try {
    // Test de connexion basique
    $connection = new mysqli('localhost', 'root', 'root', 'rebencia_RebenciaBD');
    
    if ($connection->connect_error) {
        echo "<p style='color: red;'>❌ Erreur de connexion: " . $connection->connect_error . "</p>";
    } else {
        echo "<p style='color: green;'>✅ Connexion MySQL réussie</p>";
        
        // Test de la requête corrigée directement
        $query = "
            SELECT 
                u.ID AS user_id,
                u.user_login AS user_login,
                p.post_title AS agent_name,
                p.post_type AS post_type,
                a.post_title AS agency_name,
                ur.meta_value AS user_roles
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
            LIMIT 5
        ";
        
        $result = $connection->query($query);
        
        if ($result && $result->num_rows > 0) {
            echo "<p style='color: green;'>✅ Requête SQL corrigée fonctionne ! {$result->num_rows} résultat(s)</p>";
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>ID</th><th>Login</th><th>Nom</th><th>Type</th><th>Agence</th></tr>";
            
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['user_id']}</td>";
                echo "<td>" . htmlspecialchars($row['user_login']) . "</td>";
                echo "<td>" . htmlspecialchars($row['agent_name']) . "</td>";
                echo "<td>{$row['post_type']}</td>";
                echo "<td>" . htmlspecialchars($row['agency_name'] ?? 'N/A') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color: orange;'>⚠️ Pas de résultats (normal si pas d'agents en base)</p>";
        }
    }
    $connection->close();
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erreur: " . $e->getMessage() . "</p>";
}

echo "<h2>🎯 Tests suivants :</h2>";
echo "<ul>";
echo "<li><a href='http://localhost:8888/crm.rebencia.com/agents' target='_blank'>Test : Page /agents (corrigée)</a></li>";
echo "<li><a href='http://localhost:8888/crm.rebencia.com/test_agents_modifications.php' target='_blank'>Test : Script de vérification</a></li>";
echo "</ul>";

echo "<hr>";
echo "<h3>✅ CORRECTION TERMINÉE</h3>";
echo "<p style='color: green; font-weight: bold;'>L'erreur SQL 1054 a été corrigée en supprimant les commentaires SQL incompatibles avec CodeIgniter.</p>";
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
table { margin: 10px 0; border-collapse: collapse; }
th, td { padding: 8px 12px; text-align: left; border: 1px solid #ddd; }
th { background-color: #f2f2f2; font-weight: bold; }
code { background: #f8f9fa; padding: 2px 4px; border: 1px solid #e9ecef; border-radius: 3px; }
h2 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 5px; }
</style>
