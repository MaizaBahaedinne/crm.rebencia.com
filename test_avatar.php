<?php
require_once 'index.php';

// Créer un objet agent de test
$test_agent = new stdClass();
$test_agent->agent_avatar = null; // Pas d'avatar
$test_agent->agent_email = 'test@rebencia.com';
$test_agent->user_email = 'test@rebencia.com';

echo "Test avatar helper:\n";
echo "URL avatar: " . get_agent_avatar_url($test_agent) . "\n";

// Test avec avatar existant
$test_agent2 = new stdClass();
$test_agent2->agent_avatar = 'http://localhost/wp-content/uploads/2025/08/690.jpeg';
echo "URL avatar avec correction: " . get_agent_avatar_url($test_agent2) . "\n";
?>
