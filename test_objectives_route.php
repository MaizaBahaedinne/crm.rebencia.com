<?php
// Test de debug pour les routes des objectifs
require_once 'index.php';

echo "<h2>Test des Routes Objectifs</h2>";

// Simuler l'appel de la route objectives
$_SERVER['REQUEST_URI'] = '/objectives';
$_SERVER['REQUEST_METHOD'] = 'GET';

try {
    // Charger CodeIgniter
    echo "<p>Chargement de CodeIgniter...</p>";
    
    // Test de la classe Objectives
    if (class_exists('Objectives')) {
        echo "<p style='color: green;'>✓ Classe Objectives trouvée</p>";
    } else {
        echo "<p style='color: red;'>✗ Classe Objectives non trouvée</p>";
    }
    
    // Test du fichier contrôleur
    $controller_path = 'application/controllers/Objectives.php';
    if (file_exists($controller_path)) {
        echo "<p style='color: green;'>✓ Fichier Objectives.php trouvé</p>";
    } else {
        echo "<p style='color: red;'>✗ Fichier Objectives.php non trouvé</p>";
    }
    
    // Test du modèle
    $model_path = 'application/models/Objective_model.php';
    if (file_exists($model_path)) {
        echo "<p style='color: green;'>✓ Modèle Objective_model.php trouvé</p>";
    } else {
        echo "<p style='color: red;'>✗ Modèle Objective_model.php non trouvé</p>";
    }
    
    // Test des vues
    $view_path = 'application/views/objectives/index.php';
    if (file_exists($view_path)) {
        echo "<p style='color: green;'>✓ Vue objectives/index.php trouvée</p>";
    } else {
        echo "<p style='color: red;'>✗ Vue objectives/index.php non trouvée</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Erreur: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h3>Configuration des Routes</h3>";
$routes_file = 'application/config/routes.php';
if (file_exists($routes_file)) {
    $routes_content = file_get_contents($routes_file);
    if (strpos($routes_content, 'objectives') !== false) {
        echo "<p style='color: green;'>✓ Routes objectives trouvées dans routes.php</p>";
        
        // Extraire les routes objectives
        preg_match_all('/\$route\[\'[^\']*objectives[^\']*\'\]\s*=\s*[^;]+;/', $routes_content, $matches);
        if (!empty($matches[0])) {
            echo "<h4>Routes objectives configurées :</h4>";
            echo "<pre>";
            foreach ($matches[0] as $route) {
                echo htmlspecialchars($route) . "\n";
            }
            echo "</pre>";
        }
    } else {
        echo "<p style='color: red;'>✗ Aucune route objectives trouvée dans routes.php</p>";
    }
}

echo "<hr>";
echo "<h3>Test Direct URL</h3>";
echo "<p><a href='/crm.rebencia.com/objectives' target='_blank'>Tester: /crm.rebencia.com/objectives</a></p>";
echo "<p><a href='/crm.rebencia.com/index.php/objectives' target='_blank'>Tester: /crm.rebencia.com/index.php/objectives</a></p>";
?>
