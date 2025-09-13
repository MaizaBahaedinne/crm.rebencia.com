<?php
// Test simple pour diagnostiquer le problème de routage
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Diagnostic Routage Agent</h1>";

// Test 1: Vérifier que l'URL fonctionne avec index.php
echo "<h2>Test 1: URLs de test</h2>";
$test_urls = [
    'https://crm.rebencia.com/index.php/agents/view/7',
    'https://crm.rebencia.com/agents/view/7',
    'https://crm.rebencia.com/agents',
];

foreach ($test_urls as $url) {
    echo "<p>🔗 <a href='$url' target='_blank'>$url</a></p>";
}

// Test 2: Vérifier les fichiers
echo "<h2>Test 2: Vérification des fichiers</h2>";

$controller_file = '/Applications/MAMP/htdocs/crm.rebencia.com/application/controllers/Agent.php';
if (file_exists($controller_file)) {
    echo "✅ Contrôleur Agent.php existe<br>";
    
    $content = file_get_contents($controller_file);
    if (strpos($content, 'public function view') !== false) {
        echo "✅ Méthode view() trouvée<br>";
    } else {
        echo "❌ Méthode view() non trouvée<br>";
    }
} else {
    echo "❌ Contrôleur Agent.php manquant<br>";
}

// Test 3: Vérifier la base de données pour voir s'il y a des agents
echo "<h2>Test 3: Agents disponibles</h2>";
try {
    $pdo = new PDO("mysql:host=localhost;dbname=wordpress;charset=utf8", 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query("SELECT ID, post_title FROM wp_posts WHERE post_type = 'houzez_agent' AND post_status = 'publish' LIMIT 5");
    $agents = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($agents) {
        echo "<p>✅ " . count($agents) . " agents trouvés:</p>";
        echo "<ul>";
        foreach ($agents as $agent) {
            $id = $agent['ID'];
            $name = htmlspecialchars($agent['post_title']);
            echo "<li>ID: $id - $name 
                    <a href='https://crm.rebencia.com/index.php/agents/view/$id' target='_blank'>[Test avec index.php]</a>
                    <a href='https://crm.rebencia.com/agents/view/$id' target='_blank'>[Test sans index.php]</a>
                  </li>";
        }
        echo "</ul>";
    } else {
        echo "<p>❌ Aucun agent trouvé</p>";
    }
} catch (Exception $e) {
    echo "<p>❌ Erreur base de données: " . htmlspecialchars($e->getMessage()) . "</p>";
}

// Test 4: Configuration CodeIgniter
echo "<h2>Test 4: Configuration</h2>";
$config_file = '/Applications/MAMP/htdocs/crm.rebencia.com/application/config/config.php';
if (file_exists($config_file)) {
    $config_content = file_get_contents($config_file);
    if (strpos($config_content, "index_page = ''") !== false || strpos($config_content, 'index_page = ""') !== false) {
        echo "✅ index_page configuré pour URL rewriting<br>";
    } else {
        echo "⚠️ index_page peut ne pas être configuré correctement<br>";
    }
} else {
    echo "❌ Fichier de configuration non trouvé<br>";
}

// Test 5: .htaccess
echo "<h2>Test 5: URL Rewriting</h2>";
$htaccess_file = '/Applications/MAMP/htdocs/crm.rebencia.com/.htaccess';
if (file_exists($htaccess_file)) {
    echo "✅ Fichier .htaccess existe<br>";
    $htaccess_content = file_get_contents($htaccess_file);
    if (strpos($htaccess_content, 'RewriteEngine on') !== false) {
        echo "✅ RewriteEngine activé<br>";
    } else {
        echo "❌ RewriteEngine non trouvé<br>";
    }
} else {
    echo "❌ Fichier .htaccess manquant<br>";
}

echo "<hr><p><small>Test effectué le " . date('Y-m-d H:i:s') . "</small></p>";
?>
