<?php
// Test session utilisateur actuel
session_start();

echo "<h1>Session Utilisateur Actuel</h1>";

echo "<h2>Données de session PHP:</h2>";
echo "<pre>" . print_r($_SESSION, true) . "</pre>";

// Test avec CodeIgniter pour voir les données de session
define('BASEPATH', true);
require_once('/Applications/MAMP/htdocs/crm.rebencia.com/application/config/database.php');

echo "<h2>Configuration DB:</h2>";
echo "Base par défaut: " . $db['default']['database'] . "<br>";
echo "Base WordPress: " . $db['wordpress']['database'] . "<br>";

// Test simple de connexion à WordPress
try {
    $mysqli = new mysqli('localhost', 'rebencia_rebencia', 'Rebencia1402!!', 'rebencia_RebenciaBD');
    
    if ($mysqli->connect_error) {
        die('Erreur de connexion: ' . $mysqli->connect_error);
    }
    
    echo "<h2>Test utilisateur ID=1:</h2>";
    $query = "
        SELECT 
            u.ID,
            u.user_login,
            u.user_email,
            u.display_name,
            a.agent_name,
            a.agency_name
        FROM wp_Hrg8P_users u
        LEFT JOIN wp_Hrg8P_crm_agents a ON u.ID = a.user_id
        WHERE u.ID = 1
    ";
    
    $result = $mysqli->query($query);
    if ($result) {
        $user = $result->fetch_assoc();
        echo "<pre>" . print_r($user, true) . "</pre>";
    } else {
        echo "Erreur: " . $mysqli->error;
    }
    
    $mysqli->close();
    
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage();
}
?>
