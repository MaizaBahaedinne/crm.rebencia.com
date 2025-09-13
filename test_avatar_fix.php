<?php
/**
 * Test des avatars après modernisation
 */

// Configuration
defined('BASEPATH') OR define('BASEPATH', 'application/');
define('APPPATH', 'application/');

// Inclusion des fichiers nécessaires
require_once('application/config/database.php');
require_once('application/helpers/avatar_helper.php');

// Simuler base_url
if (!function_exists('base_url')) {
    function base_url($uri = '') {
        return 'https://crm.rebencia.com/' . $uri;
    }
}

// Simuler log_message
if (!function_exists('log_message')) {
    function log_message($level, $message) {
        echo "[$level] $message\n";
    }
}

// Connexion WordPress directe
try {
    $wp_host = 'localhost';
    $wp_user = 'rebencia_crm';
    $wp_pass = 'Rebencia@2024';
    $wp_db = 'rebencia_crm';
    
    $pdo = new PDO("mysql:host=$wp_host;dbname=$wp_db;charset=utf8", $wp_user, $wp_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>🧪 Test des Avatars - Page Agents Modernisée</h2>";
    echo "<p>Date: " . date('Y-m-d H:i:s') . "</p>";
    
    // Récupérer quelques agents pour tester
    $query = $pdo->prepare("
        SELECT 
            u.ID as user_id,
            u.user_email,
            p.ID as agent_id,
            p.post_title as agent_name,
            pm_email.meta_value as agent_email,
            pm_avatar.meta_value as avatar_meta,
            (SELECT guid FROM wp_posts WHERE ID = pm_avatar.meta_value AND post_type = 'attachment' LIMIT 1) as agent_avatar
        FROM wp_users u
        INNER JOIN wp_postmeta pm_email ON pm_email.meta_value = u.user_email AND pm_email.meta_key = 'fave_agent_email'
        INNER JOIN wp_posts p ON p.ID = pm_email.post_id AND p.post_type = 'houzez_agent' AND p.post_status = 'publish'
        LEFT JOIN wp_postmeta pm_avatar ON pm_avatar.post_id = p.ID AND pm_avatar.meta_key = 'fave_author_custom_picture'
        LIMIT 5
    ");
    
    $query->execute();
    $agents = $query->fetchAll(PDO::FETCH_OBJ);
    
    echo "<div style='display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin: 20px 0;'>";
    
    foreach ($agents as $agent) {
        echo "<div style='border: 1px solid #ddd; border-radius: 12px; padding: 20px; background: white; box-shadow: 0 4px 12px rgba(0,0,0,0.1);'>";
        echo "<h4 style='margin: 0 0 10px; color: #2c3e50;'>" . htmlspecialchars($agent->agent_name) . "</h4>";
        echo "<p style='color: #6c757d; font-size: 14px; margin: 5px 0;'>Email: " . htmlspecialchars($agent->agent_email) . "</p>";
        
        // Test du helper avatar
        $avatar_url = get_agent_avatar_url($agent);
        
        echo "<div style='margin: 15px 0;'>";
        echo "<strong>Avatar généré:</strong><br>";
        echo "<img src='$avatar_url' style='width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #667eea; margin: 10px 0;' 
                   onerror=\"this.style.border='3px solid #dc3545'; this.nextElementSibling.innerHTML='❌ Erreur de chargement';\">";
        echo "<div style='font-size: 12px; color: #28a745;'>✅ Avatar chargé</div>";
        echo "</div>";
        
        echo "<div style='background: #f8f9fa; padding: 10px; border-radius: 8px; font-size: 12px; color: #495057;'>";
        echo "<strong>Données brutes:</strong><br>";
        echo "Avatar DB: " . htmlspecialchars($agent->agent_avatar ?: 'NULL') . "<br>";
        echo "Meta Value: " . htmlspecialchars($agent->avatar_meta ?: 'NULL') . "<br>";
        echo "URL générée: " . htmlspecialchars($avatar_url);
        echo "</div>";
        
        echo "</div>";
    }
    
    echo "</div>";
    
    echo "<h3>🎨 Test des Avatars SVG Générés</h3>";
    echo "<p>Test avec des noms fictifs pour vérifier les avatars SVG avec initiales :</p>";
    
    $test_agents = [
        (object)['agent_name' => 'Marie Dubois', 'agent_email' => '', 'user_email' => ''],
        (object)['agent_name' => 'Jean Martin', 'agent_email' => '', 'user_email' => ''],
        (object)['agent_name' => 'Sophie Bernard', 'agent_email' => '', 'user_email' => ''],
        (object)['agent_name' => 'Ahmed El Mansouri', 'agent_email' => '', 'user_email' => '']
    ];
    
    echo "<div style='display: flex; flex-wrap: wrap; gap: 20px; margin: 20px 0;'>";
    foreach ($test_agents as $test_agent) {
        $avatar_url = get_agent_avatar_url($test_agent);
        echo "<div style='text-align: center; padding: 15px; border: 1px solid #e9ecef; border-radius: 8px;'>";
        echo "<img src='$avatar_url' style='width: 60px; height: 60px; border-radius: 50%; margin-bottom: 10px;'>";
        echo "<div style='font-size: 14px; font-weight: 600;'>" . htmlspecialchars($test_agent->agent_name) . "</div>";
        echo "</div>";
    }
    echo "</div>";
    
    echo "<h3>✅ Tests Terminés</h3>";
    echo "<p><a href='/agents' style='background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600;'>Voir la page agents modernisée →</a></p>";
    
} catch (Exception $e) {
    echo "<div style='background: #dc3545; color: white; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
    echo "❌ <strong>Erreur:</strong> " . $e->getMessage();
    echo "</div>";
}
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
</style>
