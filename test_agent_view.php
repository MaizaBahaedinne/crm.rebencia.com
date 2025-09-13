<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Test Agent View</h1>";

// Test direct de l'URL
$test_urls = [
    'https://crm.rebencia.com/agents',
    'https://crm.rebencia.com/agents/view/7',
    'https://crm.rebencia.com/agent/view/7',
    'https://crm.rebencia.com/Agent/view/7'
];

foreach ($test_urls as $url) {
    echo "<h3>Test: $url</h3>";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    curl_close($ch);
    
    echo "Status: $http_code<br>";
    if ($http_code == 404) {
        echo "❌ 404 - Page not found<br>";
    } elseif ($http_code == 200) {
        echo "✅ 200 - OK<br>";
    } else {
        echo "⚠️ $http_code<br>";
    }
    echo "<br>";
}

// Vérifier si le fichier de contrôleur existe
$controller_path = '/Applications/MAMP/htdocs/crm.rebencia.com/application/controllers/Agent.php';
echo "<h3>Vérification du contrôleur</h3>";
if (file_exists($controller_path)) {
    echo "✅ Contrôleur Agent.php existe<br>";
    
    // Vérifier le contenu
    $content = file_get_contents($controller_path);
    if (strpos($content, 'public function view') !== false) {
        echo "✅ Méthode view() trouvée<br>";
    } else {
        echo "❌ Méthode view() non trouvée<br>";
    }
    
    if (strpos($content, 'class Agent') !== false) {
        echo "✅ Classe Agent trouvée<br>";
    } else {
        echo "❌ Classe Agent non trouvée<br>";
    }
} else {
    echo "❌ Contrôleur Agent.php n'existe pas<br>";
}

// Vérifier les routes
$routes_path = '/Applications/MAMP/htdocs/crm.rebencia.com/application/config/routes.php';
echo "<h3>Vérification des routes</h3>";
if (file_exists($routes_path)) {
    echo "✅ Fichier routes.php existe<br>";
    
    $routes_content = file_get_contents($routes_path);
    if (strpos($routes_content, "agents/view/(:num)") !== false) {
        echo "✅ Route agents/view/(:num) trouvée<br>";
    } else {
        echo "❌ Route agents/view/(:num) non trouvée<br>";
    }
} else {
    echo "❌ Fichier routes.php n'existe pas<br>";
}
?>
