<?php
/**
 * Test de la page agents après correction
 */

// Simuler base_url
if (!function_exists('base_url')) {
    function base_url($uri = '') {
        return 'https://crm.rebencia.com/' . $uri;
    }
}

echo "<h2>🧪 Test Page Agents - Diagnostic</h2>";
echo "<p>Date: " . date('Y-m-d H:i:s') . "</p>";

// Test 1: Vérifier que les fichiers essentiels existent
echo "<h3>📁 Vérification des Fichiers</h3>";

$files_to_check = [
    'application/controllers/Agent.php',
    'application/models/Agent_model.php',
    'application/views/dashboard/agents/index.php',
    'application/helpers/avatar_helper.php'
];

foreach ($files_to_check as $file) {
    $exists = file_exists($file);
    $status = $exists ? '✅' : '❌';
    echo "<p>{$status} {$file}</p>";
}

// Test 2: Vérifier la syntaxe PHP du contrôleur
echo "<h3>🔍 Vérification Syntaxe PHP</h3>";

$output = [];
$return_var = 0;
exec('php -l application/controllers/Agent.php 2>&1', $output, $return_var);

if ($return_var === 0) {
    echo "<p>✅ Syntaxe PHP du contrôleur Agent: OK</p>";
} else {
    echo "<p>❌ Erreur de syntaxe dans le contrôleur Agent:</p>";
    echo "<pre style='background: #f8d7da; padding: 10px; border-radius: 5px;'>";
    echo htmlspecialchars(implode("\n", $output));
    echo "</pre>";
}

// Test 3: Vérifier la syntaxe du helper avatar
$output = [];
$return_var = 0;
exec('php -l application/helpers/avatar_helper.php 2>&1', $output, $return_var);

if ($return_var === 0) {
    echo "<p>✅ Syntaxe PHP du helper avatar: OK</p>";
} else {
    echo "<p>❌ Erreur de syntaxe dans le helper avatar:</p>";
    echo "<pre style='background: #f8d7da; padding: 10px; border-radius: 5px;'>";
    echo htmlspecialchars(implode("\n", $output));
    echo "</pre>";
}

// Test 4: Vérifier la syntaxe de la vue
$output = [];
$return_var = 0;
exec('php -l application/views/dashboard/agents/index.php 2>&1', $output, $return_var);

if ($return_var === 0) {
    echo "<p>✅ Syntaxe PHP de la vue agents: OK</p>";
} else {
    echo "<p>❌ Erreur de syntaxe dans la vue agents:</p>";
    echo "<pre style='background: #f8d7da; padding: 10px; border-radius: 5px;'>";
    echo htmlspecialchars(implode("\n", $output));
    echo "</pre>";
}

// Test 5: Tester les fonctions essentielles
echo "<h3>⚙️ Test des Fonctions</h3>";

try {
    // Inclure le helper avatar
    require_once('application/helpers/avatar_helper.php');
    
    // Tester la fonction avatar
    $test_agent = (object)[
        'agent_name' => 'Test Agent',
        'agent_email' => 'test@example.com',
        'agent_avatar' => null
    ];
    
    $avatar_url = get_agent_avatar_url($test_agent);
    
    if (!empty($avatar_url)) {
        echo "<p>✅ Fonction get_agent_avatar_url: OK</p>";
        echo "<p style='font-size: 12px; color: #666;'>URL générée: " . htmlspecialchars($avatar_url) . "</p>";
    } else {
        echo "<p>❌ Fonction get_agent_avatar_url: Erreur</p>";
    }
    
} catch (Exception $e) {
    echo "<p>❌ Erreur lors du test des fonctions: " . htmlspecialchars($e->getMessage()) . "</p>";
}

// Test 6: Vérifier la configuration CodeIgniter
echo "<h3>🔧 Configuration CodeIgniter</h3>";

if (file_exists('application/config/config.php')) {
    echo "<p>✅ Fichier de configuration principal: OK</p>";
} else {
    echo "<p>❌ Fichier de configuration principal: Manquant</p>";
}

if (file_exists('application/config/database.php')) {
    echo "<p>✅ Configuration base de données: OK</p>";
} else {
    echo "<p>❌ Configuration base de données: Manquante</p>";
}

// Résumé
echo "<h3>📋 Résumé</h3>";
echo "<p>Si tous les tests sont ✅, le problème de page blanche peut venir de:</p>";
echo "<ul>";
echo "<li>🔐 <strong>Authentification:</strong> La page redirige vers login si non connecté</li>";
echo "<li>🗄️ <strong>Base de données:</strong> Problème de connexion à la base WordPress</li>";
echo "<li>📝 <strong>Logs PHP:</strong> Erreur dans les logs du serveur</li>";
echo "<li>🎨 <strong>Rendu:</strong> Problème dans le template ou les CSS</li>";
echo "</ul>";

echo "<h3>🚀 Actions Recommandées</h3>";
echo "<p><strong>1. Tester en étant connecté:</strong></p>";
echo "<p><a href='/login' style='background: #007bff; color: white; padding: 8px 16px; border-radius: 5px; text-decoration: none;'>Se connecter d'abord</a></p>";

echo "<p><strong>2. Vérifier les logs d'erreur:</strong></p>";
echo "<p>Regarder les logs PHP du serveur pour voir les erreurs détaillées</p>";

echo "<p><strong>3. Test avec données minimales:</strong></p>";
echo "<p><a href='/agent/test' style='background: #28a745; color: white; padding: 8px 16px; border-radius: 5px; text-decoration: none;'>Tester le contrôleur Agent</a></p>";

?>

<style>
body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    margin: 0;
    padding: 20px;
    line-height: 1.6;
}

h2, h3 {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

pre {
    overflow-x: auto;
    font-size: 12px;
}
</style>
