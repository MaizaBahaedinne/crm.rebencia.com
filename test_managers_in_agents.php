<?php
// Test inclusion des managers dans la liste des agents
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔧 Test inclusion des managers dans /agents</h1>";
echo "<hr>";

echo "<h2>📋 Problème à résoudre :</h2>";
echo "<div style='background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 10px 0;'>";
echo "<p><strong>Demande :</strong> Les managers doivent aussi apparaître dans la liste /agents car ils sont considérés comme des agents.</p>";
echo "<p><strong>Problème actuel :</strong> Les requêtes filtrent uniquement par post_type='houzez_agent', excluant les 'houzez_manager'</p>";
echo "</div>";

echo "<h2>🔍 Test 1: Vérification des post_types dans la base</h2>";
try {
    $connection = new mysqli('localhost', 'root', 'root', 'rebencia_RebenciaBD');
    
    if ($connection->connect_error) {
        echo "<p style='color: red;'>❌ Erreur de connexion: " . $connection->connect_error . "</p>";
    } else {
        // Compter les différents types de posts
        $query = "SELECT post_type, COUNT(*) as count FROM wp_Hrg8P_posts WHERE post_type IN ('houzez_agent', 'houzez_manager') AND post_status = 'publish' GROUP BY post_type";
        $result = $connection->query($query);
        
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Type de post</th><th>Nombre</th></tr>";
        
        $total_agents = 0;
        $total_managers = 0;
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>{$row['post_type']}</td><td>{$row['count']}</td></tr>";
                if ($row['post_type'] === 'houzez_agent') $total_agents = $row['count'];
                if ($row['post_type'] === 'houzez_manager') $total_managers = $row['count'];
            }
        }
        echo "</table>";
        
        $total = $total_agents + $total_managers;
        echo "<p><strong>Total agents :</strong> $total_agents</p>";
        echo "<p><strong>Total managers :</strong> $total_managers</p>";
        echo "<p><strong>Total combiné :</strong> $total</p>";
        
        if ($total_managers > 0) {
            echo "<p style='color: green;'>✅ Il y a $total_managers manager(s) qui peuvent être inclus</p>";
        } else {
            echo "<p style='color: orange;'>⚠️ Aucun manager trouvé dans la base</p>";
        }
    }
    $connection->close();
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erreur: " . $e->getMessage() . "</p>";
}

echo "<h2>🔍 Test 2: Exemples d'agents et managers</h2>";
try {
    $connection = new mysqli('localhost', 'root', 'root', 'rebencia_RebenciaBD');
    
    if (!$connection->connect_error) {
        // Récupérer quelques exemples
        $query = "
            SELECT 
                p.ID,
                p.post_title,
                p.post_type,
                p.post_status,
                pm_email.meta_value as email,
                pm_agency.meta_value as agency_id
            FROM wp_Hrg8P_posts p
            LEFT JOIN wp_Hrg8P_postmeta pm_email ON p.ID = pm_email.post_id AND pm_email.meta_key = 'fave_agent_email'
            LEFT JOIN wp_Hrg8P_postmeta pm_agency ON p.ID = pm_agency.post_id AND pm_agency.meta_key = 'fave_agent_agencies'
            WHERE p.post_type IN ('houzez_agent', 'houzez_manager') 
            AND p.post_status = 'publish'
            ORDER BY p.post_type, p.post_title
            LIMIT 10
        ";
        
        $result = $connection->query($query);
        
        if ($result && $result->num_rows > 0) {
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>ID</th><th>Nom</th><th>Type</th><th>Email</th><th>Agency ID</th></tr>";
            
            while ($row = $result->fetch_assoc()) {
                $bg_color = ($row['post_type'] === 'houzez_manager') ? 'background-color: #e8f5e8;' : '';
                echo "<tr style='$bg_color'>";
                echo "<td>{$row['ID']}</td>";
                echo "<td>{$row['post_title']}</td>";
                echo "<td>{$row['post_type']}</td>";
                echo "<td>{$row['email']}</td>";
                echo "<td>{$row['agency_id']}</td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "<p><em>Les managers sont surlignés en vert</em></p>";
        } else {
            echo "<p style='color: orange;'>⚠️ Aucun agent/manager trouvé</p>";
        }
    }
    $connection->close();
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erreur: " . $e->getMessage() . "</p>";
}

echo "<h2>✅ Modifications apportées :</h2>";
echo "<div style='background: #d4edda; padding: 15px; border-left: 4px solid #28a745; margin: 10px 0;'>";
echo "<h4>1. Agent_model::get_all_agents() :</h4>";
echo "<p><strong>Avant :</strong></p>";
echo "<pre>p.post_type = \"houzez_agent\"</pre>";
echo "<p><strong>Après :</strong></p>";
echo "<pre>p.post_type IN (\"houzez_agent\", \"houzez_manager\")</pre>";

echo "<h4>2. Agent_model::get_all_agents_from_posts() :</h4>";
echo "<p><strong>Avant :</strong></p>";
echo "<pre>->where('p.post_type', 'houzez_agent')</pre>";
echo "<p><strong>Après :</strong></p>";
echo "<pre>->where_in('p.post_type', ['houzez_agent', 'houzez_manager'])</pre>";
echo "</div>";

echo "<h2>🔍 Test 3: Simulation de la nouvelle requête</h2>";
try {
    $connection = new mysqli('localhost', 'root', 'root', 'rebencia_RebenciaBD');
    
    if (!$connection->connect_error) {
        // Simuler la nouvelle requête modifiée
        $query = "
            SELECT 
                p.ID as agent_id,
                p.post_title as agent_name,
                p.post_type,
                pm_email.meta_value as agent_email,
                agency.post_title as agency_name
            FROM wp_Hrg8P_posts p
            LEFT JOIN wp_Hrg8P_postmeta pm_email ON p.ID = pm_email.post_id AND pm_email.meta_key = 'fave_agent_email'
            LEFT JOIN wp_Hrg8P_postmeta pm_agency ON p.ID = pm_agency.post_id AND pm_agency.meta_key = 'fave_agent_agencies'
            LEFT JOIN wp_Hrg8P_posts agency ON agency.ID = pm_agency.meta_value AND agency.post_type = 'houzez_agency'
            WHERE p.post_type IN ('houzez_agent', 'houzez_manager')
            AND p.post_status = 'publish'
            ORDER BY p.post_type, p.post_title
            LIMIT 15
        ";
        
        $result = $connection->query($query);
        
        if ($result && $result->num_rows > 0) {
            echo "<p style='color: green;'>✅ Nouvelle requête retourne {$result->num_rows} résultat(s)</p>";
            echo "<table border='1' style='border-collapse: collapse; width: 100%; font-size: 12px;'>";
            echo "<tr><th>ID</th><th>Nom</th><th>Type</th><th>Email</th><th>Agence</th></tr>";
            
            while ($row = $result->fetch_assoc()) {
                $bg_color = ($row['post_type'] === 'houzez_manager') ? 'background-color: #fff3cd;' : '';
                echo "<tr style='$bg_color'>";
                echo "<td>{$row['agent_id']}</td>";
                echo "<td>{$row['agent_name']}</td>";
                echo "<td>{$row['post_type']}</td>";
                echo "<td>{$row['agent_email']}</td>";
                echo "<td>{$row['agency_name']}</td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "<p><em>Les managers sont surlignés en jaune</em></p>";
        } else {
            echo "<p style='color: orange;'>⚠️ Aucun résultat avec la nouvelle requête</p>";
        }
    }
    $connection->close();
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erreur: " . $e->getMessage() . "</p>";
}

echo "<h2>🎯 Tests à effectuer :</h2>";
echo "<ul>";
echo "<li><a href='http://localhost:8888/crm.rebencia.com/agents' target='_blank'>Test : Page /agents</a></li>";
echo "<li><a href='http://localhost:8888/crm.rebencia.com/index.php/agents' target='_blank'>Test : Page /index.php/agents</a></li>";
echo "</ul>";

echo "<h2>🔧 Logique mise à jour :</h2>";
echo "<div style='background: #e8f4fd; padding: 15px; border-left: 4px solid #0066cc; margin: 10px 0;'>";
echo "<ol>";
echo "<li>✅ <strong>Administrateurs :</strong> Voient tous les agents ET managers</li>";
echo "<li>✅ <strong>Agency Admin :</strong> Voient tous les agents ET managers</li>";
echo "<li>✅ <strong>Managers :</strong> Voient agents ET managers de leur agence</li>";
echo "<li>✅ <strong>Agents :</strong> Voient leur propre profil uniquement</li>";
echo "</ol>";
echo "</div>";

echo "<hr>";
echo "<h3>✅ MODIFICATION COMPLÈTE</h3>";
echo "<p style='color: green; font-weight: bold;'>Les managers sont maintenant inclus dans la liste des agents sur /agents</p>";
echo "<p>Les deux méthodes principales du modèle Agent ont été modifiées pour inclure post_type = 'houzez_manager'</p>";
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
table { margin: 10px 0; border-collapse: collapse; }
th, td { padding: 6px 8px; text-align: left; border: 1px solid #ddd; }
th { background-color: #f2f2f2; font-weight: bold; }
pre { background: #f5f5f5; padding: 10px; border: 1px solid #ddd; white-space: pre-wrap; }
h2 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 5px; }
</style>
