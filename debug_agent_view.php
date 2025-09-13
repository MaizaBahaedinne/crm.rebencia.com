<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Debug Agent View</h1>";

// Chemins et fichiers à vérifier
$checks = [
    'Controller Agent.php' => '/Applications/MAMP/htdocs/crm.rebencia.com/application/controllers/Agent.php',
    'Vue view.php' => '/Applications/MAMP/htdocs/crm.rebencia.com/application/views/dashboard/agents/view.php',
    'Routes.php' => '/Applications/MAMP/htdocs/crm.rebencia.com/application/config/routes.php',
    'BaseController' => '/Applications/MAMP/htdocs/crm.rebencia.com/application/libraries/BaseController.php',
    'Model Agent_model' => '/Applications/MAMP/htdocs/crm.rebencia.com/application/models/Agent_model.php'
];

foreach ($checks as $name => $path) {
    echo "<h3>$name</h3>";
    if (file_exists($path)) {
        echo "✅ Existe<br>";
        echo "Taille: " . filesize($path) . " bytes<br>";
        echo "Dernière modification: " . date('Y-m-d H:i:s', filemtime($path)) . "<br>";
    } else {
        echo "❌ N'existe pas<br>";
    }
    echo "<br>";
}

// Vérifier les méthodes dans Agent.php
$controller_content = file_get_contents('/Applications/MAMP/htdocs/crm.rebencia.com/application/controllers/Agent.php');

echo "<h3>Méthodes dans Agent.php</h3>";
if (preg_match_all('/public function (\w+)\s*\(/', $controller_content, $matches)) {
    foreach ($matches[1] as $method) {
        echo "✅ $method()<br>";
    }
} else {
    echo "❌ Aucune méthode publique trouvée<br>";
}

// Vérifier les routes pour agents
echo "<h3>Routes pour agents</h3>";
$routes_content = file_get_contents('/Applications/MAMP/htdocs/crm.rebencia.com/application/config/routes.php');
if (preg_match_all('/\$route\[\'([^\']*agents[^\']*)\'\]/', $routes_content, $matches)) {
    foreach ($matches[1] as $route) {
        echo "✅ $route<br>";
    }
} else {
    echo "❌ Aucune route agents trouvée<br>";
}

// Test de l'URL avec différentes variantes
echo "<h3>Test des URLs</h3>";
$test_urls = [
    '/agents' => 'https://crm.rebencia.com/agents',
    '/index.php/agents' => 'https://crm.rebencia.com/index.php/agents',
    '/agents/view/7' => 'https://crm.rebencia.com/agents/view/7',
    '/index.php/agents/view/7' => 'https://crm.rebencia.com/index.php/agents/view/7'
];

foreach ($test_urls as $label => $url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $status = ($http_code == 200) ? "✅" : (($http_code == 404) ? "❌" : "⚠️");
    echo "$status $label: HTTP $http_code<br>";
}
?>
