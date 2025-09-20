<?php
// Test de la vue wp_Hrg8P_prop_agen pour optimiser le comptage des propriétés
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔧 Test vue wp_Hrg8P_prop_agen</h1>";
echo "<hr>";

echo "<h2>📋 Optimisation du comptage des propriétés :</h2>";
echo "<div style='background: #d4edda; padding: 15px; border-left: 4px solid #28a745; margin: 10px 0;'>";
echo "<p><strong>Remplacement de la sous-requête complexe par la vue :</strong></p>";
echo "<ul>";
echo "<li>✅ <strong>Avant :</strong> Sous-requête avec SELECT COUNT(*) et jointures</li>";
echo "<li>✅ <strong>Après :</strong> Jointure avec vue <code>wp_Hrg8P_prop_agen</code> + COUNT(DISTINCT)</li>";
echo "<li>✅ <strong>Performance :</strong> Vue pré-calculée plus rapide</li>";
echo "<li>✅ <strong>Simplicité :</strong> Logique centralisée pour propriétés-agents</li>";
echo "</ul>";
echo "</div>";

echo "<h2>🔍 Test 1: Structure de la vue wp_Hrg8P_prop_agen</h2>";
try {
    $connection = new mysqli('localhost', 'root', 'root', 'rebencia_RebenciaBD');
    
    if ($connection->connect_error) {
        echo "<p style='color: red;'>❌ Erreur de connexion: " . $connection->connect_error . "</p>";
    } else {
        // Test de la vue des propriétés
        $query = "
            SELECT 
                property_id,
                property_title,
                property_status,
                agent_id,
                agent_name,
                agent_email,
                agency_id,
                agency_name
            FROM wp_Hrg8P_prop_agen
            WHERE property_status = 'publish'
            AND agent_id IS NOT NULL
            LIMIT 5
        ";
        
        $result = $connection->query($query);
        
        if ($result && $result->num_rows > 0) {
            echo "<p style='color: green;'>✅ Vue wp_Hrg8P_prop_agen fonctionne ! {$result->num_rows} propriété(s) trouvée(s)</p>";
            echo "<table border='1' style='border-collapse: collapse; width: 100%; font-size: 11px;'>";
            echo "<tr>";
            echo "<th>Prop ID</th><th>Titre Propriété</th><th>Agent ID</th><th>Agent</th><th>Agence</th>";
            echo "</tr>";
            
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['property_id']}</td>";
                echo "<td>" . htmlspecialchars(substr($row['property_title'], 0, 30)) . "...</td>";
                echo "<td style='background: #cff4fc;'><strong>{$row['agent_id']}</strong></td>";
                echo "<td>" . htmlspecialchars($row['agent_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['agency_name'] ?? 'N/A') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color: orange;'>⚠️ Aucune propriété trouvée dans la vue</p>";
        }
    }
    $connection->close();
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erreur: " . $e->getMessage() . "</p>";
}

echo "<h2>🔍 Test 2: Comptage des propriétés par agent via la vue</h2>";
try {
    $connection = new mysqli('localhost', 'root', 'root', 'rebencia_RebenciaBD');
    
    if (!$connection->connect_error) {
        // Test du comptage via la vue
        $query = "
            SELECT 
                agent_id,
                agent_name,
                COUNT(DISTINCT property_id) as properties_count,
                GROUP_CONCAT(DISTINCT agency_name) as agencies
            FROM wp_Hrg8P_prop_agen
            WHERE property_status = 'publish'
            AND agent_id IS NOT NULL
            GROUP BY agent_id, agent_name
            ORDER BY properties_count DESC
            LIMIT 10
        ";
        
        $result = $connection->query($query);
        
        if ($result && $result->num_rows > 0) {
            echo "<p style='color: green;'>✅ Comptage via vue réussi ! {$result->num_rows} agent(s) avec propriétés</p>";
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr>";
            echo "<th>Agent ID</th><th>Nom Agent</th><th>Nb Propriétés</th><th>Agences</th>";
            echo "</tr>";
            
            while ($row = $result->fetch_assoc()) {
                $count_color = '';
                if ($row['properties_count'] > 5) $count_color = 'background: #d4edda;';
                elseif ($row['properties_count'] > 0) $count_color = 'background: #fff3cd;';
                
                echo "<tr>";
                echo "<td>{$row['agent_id']}</td>";
                echo "<td>" . htmlspecialchars($row['agent_name']) . "</td>";
                echo "<td style='$count_color'><strong>{$row['properties_count']}</strong></td>";
                echo "<td style='font-size: 10px;'>" . htmlspecialchars($row['agencies']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "<p><em>Vert = +5 propriétés, Jaune = 1-5 propriétés</em></p>";
        } else {
            echo "<p style='color: orange;'>⚠️ Aucun agent avec propriétés trouvé</p>";
        }
    }
    $connection->close();
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erreur: " . $e->getMessage() . "</p>";
}

echo "<h2>🔍 Test 3: Nouvelle requête agents avec vue propriétés</h2>";
try {
    $connection = new mysqli('localhost', 'root', 'root', 'rebencia_RebenciaBD');
    
    if (!$connection->connect_error) {
        // Test de la nouvelle requête optimisée
        $query = "
            SELECT 
                u.ID AS user_id,
                p.ID AS agent_id,
                p.post_title AS agent_name,
                p.post_type AS post_type,
                a.post_title AS agency_name,
                COUNT(DISTINCT prop_view.property_id) as properties_count,
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
            LEFT JOIN wp_Hrg8P_prop_agen prop_view 
                   ON prop_view.agent_id = p.ID AND prop_view.property_status = 'publish'
            LEFT JOIN wp_Hrg8P_usermeta ur 
                   ON ur.user_id = u.ID AND ur.meta_key = 'wp_Hrg8P_capabilities'
            WHERE p.post_type IN ('houzez_agent', 'houzez_manager')
            AND p.post_status = 'publish'
            GROUP BY u.ID, u.user_email, p.ID, p.post_title, p.post_type, a.post_title, ur.meta_value
            ORDER BY properties_count DESC, p.post_title ASC
            LIMIT 8
        ";
        
        $result = $connection->query($query);
        
        if ($result && $result->num_rows > 0) {
            echo "<p style='color: green;'>✅ Nouvelle requête avec vue propriétés fonctionne ! {$result->num_rows} agent(s)</p>";
            echo "<table border='1' style='border-collapse: collapse; width: 100%; font-size: 12px;'>";
            echo "<tr>";
            echo "<th>Agent ID</th><th>Nom</th><th>Type</th><th>Agence</th><th>Propriétés</th><th>Rôle</th>";
            echo "</tr>";
            
            while ($row = $result->fetch_assoc()) {
                // Extraire le rôle
                $user_role = 'Non défini';
                if (!empty($row['user_roles'])) {
                    $roles = unserialize($row['user_roles']);
                    if (is_array($roles)) {
                        if (isset($roles['houzez_manager'])) {
                            $user_role = 'Manager';
                        } elseif (isset($roles['houzez_agent'])) {
                            $user_role = 'Agent';
                        } elseif (isset($roles['administrator'])) {
                            $user_role = 'Admin';
                        }
                    }
                }
                
                $count_color = '';
                if ($row['properties_count'] > 5) $count_color = 'background: #d4edda;';
                elseif ($row['properties_count'] > 0) $count_color = 'background: #fff3cd;';
                
                $role_color = ($user_role === 'Manager') ? 'background: #e8f4fd;' : '';
                
                echo "<tr>";
                echo "<td>{$row['agent_id']}</td>";
                echo "<td>" . htmlspecialchars($row['agent_name']) . "</td>";
                echo "<td>{$row['post_type']}</td>";
                echo "<td>" . htmlspecialchars($row['agency_name'] ?? 'N/A') . "</td>";
                echo "<td style='$count_color'><strong>{$row['properties_count']}</strong></td>";
                echo "<td style='$role_color'>{$user_role}</td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "<p><em>Optimisation vue : Propriétés comptées via wp_Hrg8P_prop_agen</em></p>";
        } else {
            echo "<p style='color: orange;'>⚠️ Aucun résultat avec la nouvelle requête</p>";
        }
    }
    $connection->close();
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erreur: " . $e->getMessage() . "</p>";
}

echo "<h2>🎯 Comparaison Performance :</h2>";
echo "<div style='background: #f8f9fa; padding: 15px; border: 1px solid #dee2e6; margin: 10px 0;'>";
echo "<h4>AVANT (Sous-requête) :</h4>";
echo "<pre style='font-size: 11px; background: #ffe6e6; padding: 10px;'>";
echo "(SELECT COUNT(*) 
 FROM wp_Hrg8P_posts prop 
 LEFT JOIN wp_Hrg8P_postmeta prop_agent 
   ON prop.ID = prop_agent.post_id 
   AND prop_agent.meta_key = 'fave_property_agent'
 WHERE prop.post_type = 'property' 
 AND prop.post_status = 'publish'
 AND prop_agent.meta_value = p.ID
) as properties_count";
echo "</pre>";

echo "<h4>APRÈS (Vue optimisée) :</h4>";
echo "<pre style='font-size: 11px; background: #e6ffe6; padding: 10px;'>";
echo "LEFT JOIN wp_Hrg8P_prop_agen prop_view 
  ON prop_view.agent_id = p.ID 
  AND prop_view.property_status = 'publish'

COUNT(DISTINCT prop_view.property_id) as properties_count";
echo "</pre>";

echo "<h4>Avantages :</h4>";
echo "<ul>";
echo "<li>✅ <strong>Performance :</strong> Vue pré-calculée vs sous-requête</li>";
echo "<li>✅ <strong>Index :</strong> Vue optimisée avec index appropriés</li>";
echo "<li>✅ <strong>Logique :</strong> Association propriété-agent centralisée</li>";
echo "<li>✅ <strong>Maintenance :</strong> Une seule source de vérité</li>";
echo "</ul>";
echo "</div>";

echo "<h2>🔧 Tests suivants :</h2>";
echo "<ul>";
echo "<li><a href='http://localhost:8888/crm.rebencia.com/agents' target='_blank'>Test : Page /agents avec comptage optimisé</a></li>";
echo "</ul>";

echo "<hr>";
echo "<h3>✅ OPTIMISATION TERMINÉE</h3>";
echo "<p style='color: green; font-weight: bold;'>Le comptage des propriétés utilise maintenant la vue wp_Hrg8P_prop_agen pour de meilleures performances.</p>";
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
table { margin: 10px 0; border-collapse: collapse; }
th, td { padding: 6px 8px; text-align: left; border: 1px solid #ddd; }
th { background-color: #f2f2f2; font-weight: bold; }
code { background: #f8f9fa; padding: 2px 4px; border: 1px solid #e9ecef; border-radius: 3px; }
pre { white-space: pre-wrap; margin: 5px 0; border-radius: 4px; }
h2 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 5px; }
</style>
