<?php
// Debug avancé - Test direct du contrôleur Estimations
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔬 DEBUG AVANCÉ - Test direct du système d'estimations</h1>";
echo "<hr>";

// Simuler l'environnement CodeIgniter
define('BASEPATH', true);
define('APPPATH', 'application/');

// Simuler une session manager
session_start();
$_SESSION['role'] = 'manager';
$_SESSION['user_id'] = 1;
$_SESSION['agency_id'] = 5;
$_SESSION['user_post_id'] = 1;
$_SESSION['name'] = 'Manager Test';
$_SESSION['isLoggedIn'] = true;

echo "<h2>🧪 TEST 1: Simulation de get_user_info()</h2>";
// Reproduire la logique de get_user_info() depuis Estimations.php
function simulate_get_user_info() {
    $role = $_SESSION['role'] ?? 'agent';
    $user_post_id = $_SESSION['user_post_id'] ?? 0;
    $agency_id = $_SESSION['agency_id'] ?? 1;
    $userId = $_SESSION['user_id'] ?? 0;
    $name = $_SESSION['name'] ?? 'Utilisateur';
    
    return [
        'user_id' => $userId,
        'user_post_id' => $user_post_id,
        'role' => $role,
        'agency_id' => $agency_id,
        'name' => $name
    ];
}

$user_info = simulate_get_user_info();
echo "<table border='1' style='border-collapse: collapse;'>";
echo "<tr><th>Clé</th><th>Valeur</th></tr>";
foreach ($user_info as $key => $value) {
    echo "<tr><td>$key</td><td>$value</td></tr>";
}
echo "</table>";

echo "<h2>🧪 TEST 2: Simulation de get_estimations_by_role()</h2>";
$role = $user_info['role'];
echo "<p><strong>Rôle détecté :</strong> $role</p>";

if ($role === 'manager') {
    $agency_id = $user_info['agency_id'];
    echo "<p style='color: green;'>✅ Manager détecté - Agency ID: $agency_id</p>";
    echo "<p>🔄 Appel simulé: Estimation_model::get_estimations_by_agency($agency_id)</p>";
} else {
    echo "<p style='color: orange;'>⚠️ Rôle non-manager détecté</p>";
}

echo "<h2>🧪 TEST 3: Test direct de la requête SQL</h2>";
try {
    $connection = new mysqli('localhost', 'root', 'root', 'rebencia_rebencia');
    
    if ($connection->connect_error) {
        echo "<p style='color: red;'>❌ Erreur de connexion: " . $connection->connect_error . "</p>";
    } else {
        echo "<p style='color: green;'>✅ Connexion DB réussie</p>";
        
        // Reproduire exactement la requête du modèle
        $agency_id = $user_info['agency_id'];
        $query = "
            SELECT 
                p.id,
                p.type_propriete as titre,
                CONCAT(p.adresse_numero, ' ', p.adresse_rue, ', ', p.adresse_ville) as adresse,
                p.type_bien as type_propriete,
                p.surface_habitable as surface,
                p.adresse_ville as ville,
                p.adresse_ville as gouvernorat,
                p.valeur_estimee,
                p.latitude,
                p.longitude,
                p.created_at as date_creation,
                'en_attente' as statut,
                COALESCE(a.agent_name, 'Agent inconnu') as agent_nom,
                COALESCE(a.agency_name, 'Rebencia Immobilier') as agence_nom
            FROM properties p
            LEFT JOIN rebencia_RebenciaBD.wp_Hrg8P_crm_agents a ON p.agent_id = a.agent_post_id
            WHERE a.agency_id = ? AND p.valeur_estimee IS NOT NULL
            ORDER BY p.created_at DESC
            LIMIT 20
        ";
        
        echo "<h4>Requête SQL exécutée :</h4>";
        echo "<pre style='background: #f5f5f5; padding: 10px; border: 1px solid #ddd;'>";
        echo htmlspecialchars($query);
        echo "</pre>";
        echo "<p><strong>Paramètre :</strong> agency_id = $agency_id</p>";
        
        $stmt = $connection->prepare($query);
        if ($stmt) {
            $stmt->bind_param("i", $agency_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            echo "<h4>Résultats :</h4>";
            if ($result->num_rows > 0) {
                echo "<p style='color: green;'>✅ <strong>{$result->num_rows} estimation(s) trouvée(s)</strong></p>";
                
                echo "<table border='1' style='border-collapse: collapse; width: 100%; font-size: 12px;'>";
                echo "<tr><th>ID</th><th>Titre</th><th>Adresse</th><th>Surface</th><th>Valeur</th><th>Agent</th><th>Date</th></tr>";
                
                $count = 0;
                while ($row = $result->fetch_assoc() && $count < 10) {
                    $valeur = number_format($row['valeur_estimee'], 0, ',', ' ') . ' €';
                    $date = date('d/m/Y', strtotime($row['date_creation']));
                    echo "<tr>";
                    echo "<td>{$row['id']}</td>";
                    echo "<td>{$row['titre']}</td>";
                    echo "<td>{$row['adresse']}</td>";
                    echo "<td>{$row['surface']} m²</td>";
                    echo "<td>$valeur</td>";
                    echo "<td>{$row['agent_nom']}</td>";
                    echo "<td>$date</td>";
                    echo "</tr>";
                    $count++;
                }
                echo "</table>";
                
                if ($result->num_rows > 10) {
                    echo "<p><em>... et " . ($result->num_rows - 10) . " autre(s)</em></p>";
                }
            } else {
                echo "<p style='color: orange;'>⚠️ <strong>Aucune estimation trouvée</strong></p>";
                
                // Debug : vérifier pourquoi aucune estimation
                echo "<h4>Debug - Vérification des données :</h4>";
                
                // 1. Vérifier les agents de l'agence
                $debug_query1 = "SELECT COUNT(*) as count FROM rebencia_RebenciaBD.wp_Hrg8P_crm_agents WHERE agency_id = $agency_id";
                $debug_result1 = $connection->query($debug_query1);
                $agents_count = $debug_result1->fetch_assoc()['count'];
                echo "<p>📊 Agents dans l'agence $agency_id : <strong>$agents_count</strong></p>";
                
                // 2. Vérifier les propriétés avec estimations
                $debug_query2 = "SELECT COUNT(*) as count FROM properties WHERE valeur_estimee IS NOT NULL";
                $debug_result2 = $connection->query($debug_query2);
                $properties_count = $debug_result2->fetch_assoc()['count'];
                echo "<p>📊 Propriétés avec estimations : <strong>$properties_count</strong></p>";
                
                // 3. Vérifier la correspondance agent_id / agent_post_id
                $debug_query3 = "
                    SELECT 
                        COUNT(*) as count 
                    FROM properties p 
                    LEFT JOIN rebencia_RebenciaBD.wp_Hrg8P_crm_agents a ON p.agent_id = a.agent_post_id 
                    WHERE p.valeur_estimee IS NOT NULL AND a.agent_post_id IS NOT NULL
                ";
                $debug_result3 = $connection->query($debug_query3);
                $matched_count = $debug_result3->fetch_assoc()['count'];
                echo "<p>📊 Propriétés avec agent correspondant : <strong>$matched_count</strong></p>";
            }
            
            $stmt->close();
        } else {
            echo "<p style='color: red;'>❌ Erreur de préparation de la requête: " . $connection->error . "</p>";
        }
    }
    $connection->close();
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erreur: " . $e->getMessage() . "</p>";
}

echo "<h2>🧪 TEST 4: Vérification de la structure des données</h2>";
try {
    $connection = new mysqli('localhost', 'root', 'root', 'rebencia_rebencia');
    
    if (!$connection->connect_error) {
        // Vérifier la structure de la table properties
        echo "<h4>Structure table 'properties' :</h4>";
        $result = $connection->query("DESCRIBE properties");
        if ($result) {
            echo "<table border='1' style='border-collapse: collapse;'>";
            echo "<tr><th>Champ</th><th>Type</th><th>Null</th><th>Key</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>{$row['Field']}</td><td>{$row['Type']}</td><td>{$row['Null']}</td><td>{$row['Key']}</td></tr>";
            }
            echo "</table>";
        }
        
        // Vérifier quelques enregistrements
        echo "<h4>Échantillon de données properties (avec agent_id) :</h4>";
        $result = $connection->query("SELECT id, agent_id, type_propriete, adresse_ville, valeur_estimee FROM properties WHERE agent_id IS NOT NULL LIMIT 5");
        if ($result && $result->num_rows > 0) {
            echo "<table border='1' style='border-collapse: collapse;'>";
            echo "<tr><th>ID</th><th>Agent ID</th><th>Type</th><th>Ville</th><th>Valeur</th></tr>";
            while ($row = $result->fetch_assoc()) {
                $valeur = $row['valeur_estimee'] ? number_format($row['valeur_estimee'], 0, ',', ' ') . ' €' : 'Non estimée';
                echo "<tr><td>{$row['id']}</td><td>{$row['agent_id']}</td><td>{$row['type_propriete']}</td><td>{$row['adresse_ville']}</td><td>$valeur</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color: orange;'>⚠️ Aucune propriété avec agent_id trouvée</p>";
        }
    }
    $connection->close();
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erreur structure: " . $e->getMessage() . "</p>";
}

echo "<h2>🎯 RECOMMANDATIONS</h2>";
echo "<div style='background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 10px 0;'>";
echo "<h4>Selon les résultats du debug :</h4>";
echo "<ul>";
echo "<li><strong>Si estimations trouvées :</strong> Le système fonctionne ✅</li>";
echo "<li><strong>Si aucune estimation :</strong> Vérifier les données d'agents et la correspondance agent_id/agent_post_id</li>";
echo "<li><strong>Si erreur SQL :</strong> Vérifier la structure des tables et les permissions</li>";
echo "</ul>";
echo "</div>";

echo "<h2>🔗 TESTS FINAUX</h2>";
echo "<p>Maintenant, testez ces URLs avec un vrai utilisateur manager connecté :</p>";
echo "<ul>";
echo "<li><a href='http://localhost:8888/crm.rebencia.com/estimations' target='_blank'>Test direct : /estimations</a></li>";
echo "<li><a href='http://localhost:8888/crm.rebencia.com/index.php/estimations' target='_blank'>Test avec index.php : /index.php/estimations</a></li>";
echo "</ul>";

echo "<hr>";
echo "<p><em>Debug généré le " . date('d/m/Y H:i:s') . "</em></p>";
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
table { margin: 10px 0; }
th, td { padding: 8px; text-align: left; border: 1px solid #ddd; }
th { background-color: #f2f2f2; }
pre { white-space: pre-wrap; word-wrap: break-word; }
</style>
