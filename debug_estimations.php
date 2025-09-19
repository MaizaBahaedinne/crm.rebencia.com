<?php
// Debug complet pour les estimations - Étape par étape
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔍 DEBUG ESTIMATIONS - Analyse complète</h1>";
echo "<hr>";

// Simuler une session manager pour les tests
session_start();
$_SESSION['role'] = 'manager';
$_SESSION['user_id'] = 1;
$_SESSION['agency_id'] = 5; // ID d'agence de test
$_SESSION['user_post_id'] = 1;
$_SESSION['name'] = 'Manager Test';
$_SESSION['isLoggedIn'] = true;

echo "<h2>📋 ÉTAPE 1: Vérification de la session</h2>";
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Clé Session</th><th>Valeur</th><th>Status</th></tr>";

$session_keys = ['role', 'user_id', 'agency_id', 'user_post_id', 'name', 'isLoggedIn'];
foreach ($session_keys as $key) {
    $value = $_SESSION[$key] ?? 'NON DÉFINI';
    $status = isset($_SESSION[$key]) ? '✅' : '❌';
    echo "<tr><td>$key</td><td>$value</td><td>$status</td></tr>";
}
echo "</table>";

echo "<h2>📁 ÉTAPE 2: Vérification des fichiers</h2>";
$files_to_check = [
    'application/controllers/Estimations.php',
    'application/models/Estimation_model.php',
    'application/views/estimations/index.php',
    'application/config/routes.php'
];

foreach ($files_to_check as $file) {
    if (file_exists($file)) {
        echo "<p style='color: green;'>✅ $file existe</p>";
    } else {
        echo "<p style='color: red;'>❌ $file manquant</p>";
    }
}

echo "<h2>🔗 ÉTAPE 3: Vérification des routes</h2>";
if (file_exists('application/config/routes.php')) {
    $routes_content = file_get_contents('application/config/routes.php');
    if (strpos($routes_content, "estimations") !== false) {
        echo "<p style='color: green;'>✅ Route 'estimations' trouvée dans routes.php</p>";
        
        // Extraire les routes d'estimations
        preg_match_all('/\$route\[\'([^\']*estimations[^\']*)\'\]\s*=\s*[\'"]([^\'"]*)[\'"];/', $routes_content, $matches);
        if (!empty($matches[0])) {
            echo "<h4>Routes d'estimations trouvées :</h4>";
            echo "<ul>";
            for ($i = 0; $i < count($matches[1]); $i++) {
                echo "<li><strong>{$matches[1][$i]}</strong> → {$matches[2][$i]}</li>";
            }
            echo "</ul>";
        }
    } else {
        echo "<p style='color: red;'>❌ Aucune route 'estimations' trouvée</p>";
    }
}

echo "<h2>🎯 ÉTAPE 4: Analyse du contrôleur Estimations</h2>";
if (file_exists('application/controllers/Estimations.php')) {
    $estimations_content = file_get_contents('application/controllers/Estimations.php');
    
    // Vérifier la méthode index
    if (strpos($estimations_content, "public function index()") !== false) {
        echo "<p style='color: green;'>✅ Méthode index() trouvée</p>";
    } else {
        echo "<p style='color: red;'>❌ Méthode index() manquante</p>";
    }
    
    // Vérifier la logique manager
    if (strpos($estimations_content, "case 'manager':") !== false) {
        echo "<p style='color: green;'>✅ Case 'manager' trouvée dans get_estimations_by_role()</p>";
    } else {
        echo "<p style='color: red;'>❌ Case 'manager' manquante</p>";
    }
    
    // Vérifier l'appel get_estimations_by_agency
    if (strpos($estimations_content, "get_estimations_by_agency") !== false) {
        echo "<p style='color: green;'>✅ Appel get_estimations_by_agency() trouvé</p>";
    } else {
        echo "<p style='color: red;'>❌ Appel get_estimations_by_agency() manquant</p>";
    }
    
    // Vérifier get_user_info
    if (strpos($estimations_content, "get_user_info()") !== false) {
        echo "<p style='color: green;'>✅ Appel get_user_info() trouvé</p>";
    } else {
        echo "<p style='color: red;'>❌ Appel get_user_info() manquant</p>";
    }
}

echo "<h2>🗄️ ÉTAPE 5: Analyse du modèle Estimation</h2>";
if (file_exists('application/models/Estimation_model.php')) {
    $model_content = file_get_contents('application/models/Estimation_model.php');
    
    // Vérifier les méthodes
    $methods_to_check = [
        'get_estimations_by_agency',
        'get_estimations_by_agent',
        'get_all_estimations_with_details'
    ];
    
    foreach ($methods_to_check as $method) {
        if (strpos($model_content, "function $method") !== false) {
            echo "<p style='color: green;'>✅ Méthode $method() trouvée</p>";
        } else {
            echo "<p style='color: red;'>❌ Méthode $method() manquante</p>";
        }
    }
}

echo "<h2>🔧 ÉTAPE 6: Test de connexion base de données</h2>";
try {
    // Charger CodeIgniter pour les tests de DB
    echo "<p>🔄 Test de connexion à la base de données...</p>";
    
    // Connexion manuelle pour le test
    $config = array(
        'hostname' => 'localhost',
        'username' => 'root',
        'password' => 'root',
        'database' => 'rebencia_rebencia',
        'dbdriver' => 'mysqli',
        'dbprefix' => '',
        'pconnect' => FALSE,
        'db_debug' => FALSE,
        'cache_on' => FALSE,
        'cachedir' => '',
        'char_set' => 'utf8',
        'dbcollat' => 'utf8_general_ci'
    );
    
    $connection = new mysqli($config['hostname'], $config['username'], $config['password'], $config['database']);
    
    if ($connection->connect_error) {
        echo "<p style='color: red;'>❌ Erreur de connexion DB: " . $connection->connect_error . "</p>";
    } else {
        echo "<p style='color: green;'>✅ Connexion DB principale réussie</p>";
        
        // Test des tables
        $tables_to_check = ['properties', 'wp_Hrg8P_crm_agents'];
        foreach ($tables_to_check as $table) {
            $result = $connection->query("SHOW TABLES LIKE '$table'");
            if ($result && $result->num_rows > 0) {
                echo "<p style='color: green;'>✅ Table '$table' existe</p>";
                
                // Compter les enregistrements
                $count_result = $connection->query("SELECT COUNT(*) as count FROM $table");
                if ($count_result) {
                    $count = $count_result->fetch_assoc()['count'];
                    echo "<p style='color: blue;'>ℹ️ Table '$table' contient $count enregistrements</p>";
                }
            } else {
                echo "<p style='color: red;'>❌ Table '$table' manquante</p>";
            }
        }
    }
    $connection->close();
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erreur de test DB: " . $e->getMessage() . "</p>";
}

echo "<h2>🔍 ÉTAPE 7: Test spécifique pour agency_id = {$_SESSION['agency_id']}</h2>";
try {
    $connection = new mysqli('localhost', 'root', 'root', 'rebencia_rebencia');
    
    if (!$connection->connect_error) {
        $agency_id = $_SESSION['agency_id'];
        
        // Test 1: Vérifier les agents de l'agence
        echo "<h4>Test 1: Agents de l'agence $agency_id</h4>";
        $query = "SELECT user_id, agent_name, agency_id FROM rebencia_RebenciaBD.wp_Hrg8P_crm_agents WHERE agency_id = $agency_id";
        $result = $connection->query($query);
        
        if ($result) {
            if ($result->num_rows > 0) {
                echo "<p style='color: green;'>✅ Trouvé {$result->num_rows} agent(s) dans l'agence $agency_id</p>";
                echo "<table border='1' style='border-collapse: collapse;'>";
                echo "<tr><th>User ID</th><th>Nom Agent</th><th>Agency ID</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>{$row['user_id']}</td><td>{$row['agent_name']}</td><td>{$row['agency_id']}</td></tr>";
                }
                echo "</table>";
            } else {
                echo "<p style='color: orange;'>⚠️ Aucun agent trouvé pour l'agence $agency_id</p>";
            }
        }
        
        // Test 2: Vérifier les estimations avec ces agents
        echo "<h4>Test 2: Estimations liées à l'agence $agency_id</h4>";
        $query = "
            SELECT 
                p.id,
                p.type_propriete,
                p.adresse_ville,
                p.valeur_estimee,
                p.agent_id,
                a.agent_name,
                a.agency_id
            FROM properties p
            LEFT JOIN rebencia_RebenciaBD.wp_Hrg8P_crm_agents a ON p.agent_id = a.agent_post_id
            WHERE a.agency_id = $agency_id AND p.valeur_estimee IS NOT NULL
            LIMIT 10
        ";
        
        $result = $connection->query($query);
        
        if ($result) {
            if ($result->num_rows > 0) {
                echo "<p style='color: green;'>✅ Trouvé {$result->num_rows} estimation(s) pour l'agence $agency_id</p>";
                echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
                echo "<tr><th>ID</th><th>Type</th><th>Ville</th><th>Valeur</th><th>Agent</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    $valeur = number_format($row['valeur_estimee'], 0, ',', ' ') . ' €';
                    echo "<tr><td>{$row['id']}</td><td>{$row['type_propriete']}</td><td>{$row['adresse_ville']}</td><td>$valeur</td><td>{$row['agent_name']}</td></tr>";
                }
                echo "</table>";
            } else {
                echo "<p style='color: orange;'>⚠️ Aucune estimation trouvée pour l'agence $agency_id</p>";
            }
        }
    }
    $connection->close();
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erreur test spécifique: " . $e->getMessage() . "</p>";
}

echo "<h2>🌐 ÉTAPE 8: Test des URLs</h2>";
echo "<p>URLs à tester dans le navigateur :</p>";
echo "<ul>";
echo "<li><a href='http://localhost:8888/crm.rebencia.com/estimations' target='_blank'>http://localhost:8888/crm.rebencia.com/estimations</a></li>";
echo "<li><a href='http://localhost:8888/crm.rebencia.com/index.php/estimations' target='_blank'>http://localhost:8888/crm.rebencia.com/index.php/estimations</a></li>";
echo "</ul>";

echo "<h2>📊 ÉTAPE 9: Diagnostic complet</h2>";
echo "<div style='background: #f8f9fa; padding: 15px; border-left: 4px solid #007bff; margin: 10px 0;'>";
echo "<h4>Flux attendu pour le manager :</h4>";
echo "<ol>";
echo "<li>Manager se connecte → session avec agency_id</li>";
echo "<li>Accès à /estimations → Estimations::index()</li>";
echo "<li>get_user_info() récupère agency_id depuis session</li>";
echo "<li>get_estimations_by_role() → case 'manager'</li>";
echo "<li>Estimation_model::get_estimations_by_agency(\$agency_id)</li>";
echo "<li>Requête SQL joint properties + wp_Hrg8P_crm_agents</li>";
echo "<li>Filtre WHERE a.agency_id = \$agency_id</li>";
echo "<li>Retourne toutes les estimations de l'agence</li>";
echo "</ol>";
echo "</div>";

echo "<h2>🎯 RÉSUMÉ DU DEBUG</h2>";
echo "<p><strong>Session simulée :</strong> Manager avec agency_id = {$_SESSION['agency_id']}</p>";
echo "<p><strong>Prochaine étape :</strong> Tester les URLs ci-dessus</p>";
echo "<p><strong>Si problème :</strong> Vérifier les logs d'erreur PHP et base de données</p>";

echo "<hr>";
echo "<h3>🔧 Actions recommandées :</h3>";
echo "<ul>";
echo "<li>1. Tester l'URL /estimations avec un vrai utilisateur manager connecté</li>";
echo "<li>2. Vérifier les logs d'erreur dans application/logs/</li>";
echo "<li>3. S'assurer que l'agency_id est bien en session après connexion</li>";
echo "<li>4. Vérifier que les données d'agents et estimations existent en DB</li>";
echo "</ul>";
?>

<style>
table { margin: 10px 0; }
th, td { padding: 8px; text-align: left; }
th { background-color: #f2f2f2; }
</style>
