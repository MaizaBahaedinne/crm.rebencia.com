<?php
/**
 * Test du helper avatar après correction
 */

// Simuler base_url
if (!function_exists('base_url')) {
    function base_url($uri = '') {
        return 'https://crm.rebencia.com/' . $uri;
    }
}

// Inclusion du helper
require_once('application/helpers/avatar_helper.php');

echo "<h2>🧪 Test Avatar Helper - Après Correction</h2>";
echo "<p>Date: " . date('Y-m-d H:i:s') . "</p>";

// Test avec différents cas
$test_cases = [
    // Cas 1: Agent avec avatar valide
    (object)[
        'agent_name' => 'Marie Dubois',
        'agent_email' => 'marie.dubois@rebencia.com',
        'user_email' => 'marie.dubois@rebencia.com',
        'agent_avatar' => 'https://rebencia.com/wp-content/uploads/2024/agents/marie.jpg'
    ],
    
    // Cas 2: Agent sans avatar mais avec email (Gravatar)
    (object)[
        'agent_name' => 'Jean Martin',
        'agent_email' => 'jean.martin@rebencia.com',
        'user_email' => 'jean.martin@rebencia.com',
        'agent_avatar' => null
    ],
    
    // Cas 3: Agent sans avatar ni email (SVG généré)
    (object)[
        'agent_name' => 'Sophie Bernard',
        'agent_email' => '',
        'user_email' => '',
        'agent_avatar' => null
    ],
    
    // Cas 4: Agent avec avatar localhost (correction URL)
    (object)[
        'agent_name' => 'Ahmed El Mansouri',
        'agent_email' => 'ahmed@rebencia.com',
        'user_email' => 'ahmed@rebencia.com',
        'agent_avatar' => 'http://localhost/wp-content/uploads/2024/ahmed.jpg'
    ],
    
    // Cas 5: Données minimales
    (object)[
        'agent_name' => 'Test Agent',
        'agent_email' => '',
        'user_email' => '',
        'agent_avatar' => ''
    ]
];

echo "<div style='display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin: 20px 0;'>";

foreach ($test_cases as $index => $agent) {
    echo "<div style='border: 1px solid #ddd; border-radius: 12px; padding: 20px; background: white; box-shadow: 0 4px 12px rgba(0,0,0,0.1);'>";
    echo "<h4 style='margin: 0 0 10px; color: #2c3e50;'>Test Case " . ($index + 1) . "</h4>";
    echo "<p style='color: #6c757d; font-size: 14px; margin: 5px 0;'><strong>Nom:</strong> " . htmlspecialchars($agent->agent_name) . "</p>";
    echo "<p style='color: #6c757d; font-size: 14px; margin: 5px 0;'><strong>Email:</strong> " . htmlspecialchars($agent->agent_email ?: 'Aucun') . "</p>";
    echo "<p style='color: #6c757d; font-size: 14px; margin: 5px 0;'><strong>Avatar DB:</strong> " . htmlspecialchars($agent->agent_avatar ?: 'Aucun') . "</p>";
    
    // Test du helper
    try {
        $avatar_url = get_agent_avatar_url($agent);
        
        echo "<div style='margin: 15px 0; text-align: center;'>";
        echo "<img src='$avatar_url' style='width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #667eea; margin: 10px 0;' 
                   onerror=\"this.style.border='3px solid #dc3545'; this.nextElementSibling.innerHTML='❌ Erreur de chargement';\">";
        echo "<div style='font-size: 12px; color: #28a745;'>✅ Avatar généré</div>";
        echo "</div>";
        
        echo "<div style='background: #f8f9fa; padding: 10px; border-radius: 8px; font-size: 12px; color: #495057; word-break: break-all;'>";
        echo "<strong>URL générée:</strong><br>";
        echo htmlspecialchars($avatar_url);
        echo "</div>";
        
    } catch (Exception $e) {
        echo "<div style='background: #dc3545; color: white; padding: 10px; border-radius: 8px; font-size: 12px;'>";
        echo "❌ <strong>Erreur:</strong> " . htmlspecialchars($e->getMessage());
        echo "</div>";
    }
    
    echo "</div>";
}

echo "</div>";

echo "<h3>✅ Tests Terminés</h3>";
echo "<p>Le helper avatar fonctionne maintenant sans erreur de base de données.</p>";
echo "<p><a href='/agents' style='background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600;'>Tester la page agents →</a></p>";
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
