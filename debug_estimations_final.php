<?php
// Debug final - Test en conditions réelles
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🚀 DEBUG FINAL - Test en conditions réelles</h1>";
echo "<hr>";

// Simuler une session manager complète
session_start();
$_SESSION = [
    'role' => 'manager',
    'user_id' => 1,
    'agency_id' => 5,
    'user_post_id' => 1,
    'name' => 'Manager Test',
    'isLoggedIn' => true,
    'wp_id' => 1,
    'wp_login' => 'manager_test',
    'logged_in' => true
];

echo "<h2>📋 Session actuelle</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo "<h2>🔍 Test 1: Vérification que l'agence a des agents</h2>";
try {
    $connection = new mysqli('localhost', 'root', 'root', 'rebencia_RebenciaBD');
    
    if ($connection->connect_error) {
        echo "<p style='color: red;'>❌ Erreur connexion WordPress DB: " . $connection->connect_error . "</p>";
    } else {
        $agency_id = $_SESSION['agency_id'];
        
        // Lister tous les agents de l'agence
        $query = "SELECT user_id, agent_name, agent_post_id, agency_id FROM wp_Hrg8P_crm_agents WHERE agency_id = $agency_id";
        $result = $connection->query($query);
        
        if ($result && $result->num_rows > 0) {
            echo "<p style='color: green;'>✅ Trouvé {$result->num_rows} agent(s) dans l'agence $agency_id</p>";
            echo "<table border='1' style='border-collapse: collapse;'>";
            echo "<tr><th>User ID</th><th>Nom</th><th>Post ID</th><th>Agency ID</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>{$row['user_id']}</td><td>{$row['agent_name']}</td><td>{$row['agent_post_id']}</td><td>{$row['agency_id']}</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color: red;'>❌ Aucun agent trouvé pour l'agence $agency_id</p>";
            
            // Lister toutes les agences disponibles
            $all_agencies = $connection->query("SELECT DISTINCT agency_id, COUNT(*) as agents_count FROM wp_Hrg8P_crm_agents GROUP BY agency_id ORDER BY agency_id");
            if ($all_agencies && $all_agencies->num_rows > 0) {
                echo "<h4>Agences disponibles dans la base :</h4>";
                echo "<table border='1' style='border-collapse: collapse;'>";
                echo "<tr><th>Agency ID</th><th>Nombre d'agents</th></tr>";
                while ($row = $all_agencies->fetch_assoc()) {
                    echo "<tr><td>{$row['agency_id']}</td><td>{$row['agents_count']}</td></tr>";
                }
                echo "</table>";
            }
        }
    }
    $connection->close();
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erreur: " . $e->getMessage() . "</p>";
}

echo "<h2>🔍 Test 2: Vérification des propriétés avec estimations</h2>";
try {
    $connection = new mysqli('localhost', 'root', 'root', 'rebencia_rebencia');
    
    if (!$connection->connect_error) {
        // Compter les propriétés avec valeur_estimee
        $count_query = "SELECT COUNT(*) as count FROM properties WHERE valeur_estimee IS NOT NULL AND valeur_estimee > 0";
        $count_result = $connection->query($count_query);
        $total_estimations = $count_result->fetch_assoc()['count'];
        
        echo "<p>📊 Total des propriétés avec estimation : <strong>$total_estimations</strong></p>";
        
        if ($total_estimations > 0) {
            // Afficher quelques exemples
            $sample_query = "SELECT id, agent_id, type_propriete, adresse_ville, valeur_estimee, created_at FROM properties WHERE valeur_estimee IS NOT NULL AND valeur_estimee > 0 ORDER BY created_at DESC LIMIT 10";
            $sample_result = $connection->query($sample_query);
            
            echo "<h4>Exemples d'estimations récentes :</h4>";
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>ID</th><th>Agent ID</th><th>Type</th><th>Ville</th><th>Valeur</th><th>Date</th></tr>";
            while ($row = $sample_result->fetch_assoc()) {
                $valeur = number_format($row['valeur_estimee'], 0, ',', ' ') . ' €';
                $date = date('d/m/Y', strtotime($row['created_at']));
                echo "<tr><td>{$row['id']}</td><td>{$row['agent_id']}</td><td>{$row['type_propriete']}</td><td>{$row['adresse_ville']}</td><td>$valeur</td><td>$date</td></tr>";
            }
            echo "</table>";
        }
    }
    $connection->close();
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erreur: " . $e->getMessage() . "</p>";
}

echo "<h2>🔍 Test 3: Test de la requête complète (cross-database)</h2>";
try {
    $connection = new mysqli('localhost', 'root', 'root', 'rebencia_rebencia');
    
    if (!$connection->connect_error) {
        $agency_id = $_SESSION['agency_id'];
        
        // La même requête que dans le modèle, avec database prefix explicite
        $query = "
            SELECT 
                p.id,
                p.type_propriete as titre,
                CONCAT(COALESCE(p.adresse_numero, ''), ' ', COALESCE(p.adresse_rue, ''), ', ', COALESCE(p.adresse_ville, '')) as adresse,
                p.type_bien as type_propriete,
                p.surface_habitable as surface,
                p.adresse_ville as ville,
                p.valeur_estimee,
                p.created_at as date_creation,
                'en_attente' as statut,
                COALESCE(a.agent_name, 'Agent inconnu') as agent_nom,
                COALESCE(a.agency_name, 'Rebencia Immobilier') as agence_nom,
                a.agency_id,
                a.agent_post_id
            FROM properties p
            LEFT JOIN rebencia_RebenciaBD.wp_Hrg8P_crm_agents a ON p.agent_id = a.agent_post_id
            WHERE p.valeur_estimee IS NOT NULL 
            AND p.valeur_estimee > 0
            ORDER BY p.created_at DESC
            LIMIT 20
        ";
        
        echo "<h4>Requête complète (sans filtre agence d'abord) :</h4>";
        $result = $connection->query($query);
        
        if ($result && $result->num_rows > 0) {
            echo "<p style='color: green;'>✅ Trouvé {$result->num_rows} estimation(s) avec liaison agent</p>";
            
            echo "<table border='1' style='border-collapse: collapse; width: 100%; font-size: 11px;'>";
            echo "<tr><th>ID</th><th>Titre</th><th>Ville</th><th>Valeur</th><th>Agent</th><th>Agency ID</th><th>Post ID</th></tr>";
            
            $agency_match_count = 0;
            while ($row = $result->fetch_assoc()) {
                $valeur = number_format($row['valeur_estimee'], 0, ',', ' ') . ' €';
                $bg_color = ($row['agency_id'] == $agency_id) ? 'background-color: #d4edda;' : '';
                if ($row['agency_id'] == $agency_id) $agency_match_count++;
                
                echo "<tr style='$bg_color'>";
                echo "<td>{$row['id']}</td>";
                echo "<td>{$row['titre']}</td>";
                echo "<td>{$row['ville']}</td>";
                echo "<td>$valeur</td>";
                echo "<td>{$row['agent_nom']}</td>";
                echo "<td>{$row['agency_id']}</td>";
                echo "<td>{$row['agent_post_id']}</td>";
                echo "</tr>";
            }
            echo "</table>";
            
            echo "<p style='color: blue;'>📊 Estimations correspondant à l'agence $agency_id : <strong>$agency_match_count</strong></p>";
            
            if ($agency_match_count == 0) {
                echo "<p style='color: orange;'>⚠️ Aucune estimation ne correspond à l'agence $agency_id</p>";
            }
            
        } else {
            echo "<p style='color: red;'>❌ Aucune estimation trouvée avec liaison agent</p>";
        }
        
        // Test avec le filtre agence
        echo "<h4>Maintenant avec filtre agence $agency_id :</h4>";
        $filtered_query = $query . " AND a.agency_id = $agency_id";
        
        $filtered_result = $connection->query(str_replace("LIMIT 20", "AND a.agency_id = $agency_id LIMIT 20", $query));
        
        if ($filtered_result && $filtered_result->num_rows > 0) {
            echo "<p style='color: green;'>✅ Avec filtre agence : {$filtered_result->num_rows} estimation(s)</p>";
        } else {
            echo "<p style='color: orange;'>⚠️ Avec filtre agence : 0 estimation</p>";
        }
    }
    $connection->close();
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erreur: " . $e->getMessage() . "</p>";
}

echo "<h2>🛠️ Test 4: Simulation du comportement attendu</h2>";
echo "<div style='background: #e8f4fd; padding: 15px; border-left: 4px solid #0066cc; margin: 10px 0;'>";
echo "<h4>Scénario de test :</h4>";
echo "<ol>";
echo "<li>✅ Session manager configurée avec agency_id = {$_SESSION['agency_id']}</li>";
echo "<li>✅ Méthode get_user_info() retournerait : agency_id = {$_SESSION['agency_id']}</li>";
echo "<li>✅ get_estimations_by_role() appellerait case 'manager'</li>";
echo "<li>✅ get_estimations_by_agency({$_SESSION['agency_id']}) serait exécutée</li>";
echo "<li>✅ La requête SQL filtrerait par a.agency_id = {$_SESSION['agency_id']}</li>";
echo "</ol>";
echo "</div>";

echo "<h2>🎯 CONCLUSIONS</h2>";
echo "<div style='background: #f8d7da; padding: 15px; border-left: 4px solid #dc3545; margin: 10px 0;'>";
echo "<h4>Problèmes potentiels identifiés :</h4>";
echo "<ul>";
echo "<li>Vérifier que l'agency_id utilisé dans les tests correspond à une agence réelle</li>";
echo "<li>S'assurer que les agent_post_id correspondent bien aux IDs dans properties.agent_id</li>";
echo "<li>Vérifier que les estimations existent pour les agents de l'agence testée</li>";
echo "</ul>";
echo "</div>";

echo "<h2>🔧 ACTIONS RECOMMANDÉES</h2>";
echo "<ol>";
echo "<li><strong>Changer l'agency_id de test :</strong> Utiliser un ID d'agence qui a vraiment des agents et des estimations</li>";
echo "<li><strong>Tester avec un vrai utilisateur :</strong> Se connecter avec un manager réel</li>";
echo "<li><strong>Vérifier les logs :</strong> Consulter application/logs/ pour les erreurs PHP</li>";
echo "<li><strong>Test direct URL :</strong> <a href='http://localhost:8888/crm.rebencia.com/estimations' target='_blank'>Tester /estimations</a></li>";
echo "</ol>";

echo "<hr>";
echo "<p><strong>Debug terminé.</strong> Utilisez les informations ci-dessus pour identifier le problème.</p>";
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
table { margin: 10px 0; border-collapse: collapse; }
th, td { padding: 6px 8px; text-align: left; border: 1px solid #ddd; }
th { background-color: #f2f2f2; font-weight: bold; }
pre { background: #f5f5f5; padding: 10px; border: 1px solid #ddd; overflow-x: auto; }
h2 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 5px; }
h4 { color: #555; }
</style>
