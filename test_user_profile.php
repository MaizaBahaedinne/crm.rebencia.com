<?php
// Test des données utilisateur pour le profil
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuration base de données WordPress
$db_config = [
    'hostname' => 'localhost',
    'username' => 'rebencia_rebencia',
    'password' => 'Rebencia1402!!',
    'database' => 'rebencia_RebenciaBD'
];

try {
    $mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);
    
    if ($mysqli->connect_error) {
        die('Erreur de connexion: ' . $mysqli->connect_error);
    }
    
    echo "<h1>Test Données Utilisateur - Profil</h1>";
    
    // 1. Vérifier si la vue v_users_profile existe
    echo "<h2>1. Test vue v_users_profile</h2>";
    $query1 = "SELECT * FROM v_users_profile LIMIT 3";
    $result1 = $mysqli->query($query1);
    if ($result1) {
        echo "Vue v_users_profile trouvée ! Données:<br>";
        while ($row1 = $result1->fetch_assoc()) {
            echo "<pre>" . print_r($row1, true) . "</pre>";
        }
    } else {
        echo "❌ Vue v_users_profile introuvable: " . $mysqli->error . "<br>";
    }
    
    // 2. Test direct avec wp_Hrg8P_users
    echo "<h2>2. Test direct wp_Hrg8P_users</h2>";
    $query2 = "SELECT ID, user_login, user_email, display_name, user_registered, user_status FROM wp_Hrg8P_users LIMIT 5";
    $result2 = $mysqli->query($query2);
    if ($result2) {
        echo "Utilisateurs WordPress trouvés:<br>";
        while ($row2 = $result2->fetch_assoc()) {
            echo "ID: {$row2['ID']}, Login: {$row2['user_login']}, Email: {$row2['user_email']}, Nom: {$row2['display_name']}<br>";
        }
    } else {
        echo "Erreur wp_Hrg8P_users: " . $mysqli->error . "<br>";
    }
    
    // 3. Test avec wp_Hrg8P_usermeta pour métadonnées
    echo "<h2>3. Test métadonnées utilisateur</h2>";
    $query3 = "
        SELECT 
            u.ID,
            u.user_login,
            u.user_email,
            u.display_name,
            MAX(CASE WHEN um.meta_key = 'first_name' THEN um.meta_value END) as first_name,
            MAX(CASE WHEN um.meta_key = 'last_name' THEN um.meta_value END) as last_name,
            MAX(CASE WHEN um.meta_key = 'description' THEN um.meta_value END) as description,
            MAX(CASE WHEN um.meta_key = 'phone' THEN um.meta_value END) as phone
        FROM wp_Hrg8P_users u
        LEFT JOIN wp_Hrg8P_usermeta um ON u.ID = um.user_id
        WHERE u.ID IN (1,2,3)
        GROUP BY u.ID, u.user_login, u.user_email, u.display_name
        LIMIT 3
    ";
    
    $result3 = $mysqli->query($query3);
    if ($result3) {
        echo "Utilisateurs avec métadonnées:<br>";
        while ($row3 = $result3->fetch_assoc()) {
            echo "<pre>" . print_r($row3, true) . "</pre>";
        }
    } else {
        echo "Erreur métadonnées: " . $mysqli->error . "<br>";
    }
    
    // 4. Test avec la vue wp_Hrg8P_crm_agents
    echo "<h2>4. Test liaison avec agents CRM</h2>";
    $query4 = "
        SELECT 
            u.ID,
            u.user_login,
            u.user_email,
            u.display_name,
            a.agent_name,
            a.agency_name,
            a.agent_email,
            a.phone,
            a.mobile
        FROM wp_Hrg8P_users u
        LEFT JOIN wp_Hrg8P_crm_agents a ON u.ID = a.user_id
        WHERE a.agent_name IS NOT NULL
        LIMIT 5
    ";
    
    $result4 = $mysqli->query($query4);
    if ($result4) {
        echo "Utilisateurs agents trouvés:<br>";
        while ($row4 = $result4->fetch_assoc()) {
            echo "<pre>" . print_r($row4, true) . "</pre>";
        }
    } else {
        echo "Erreur liaison agents: " . $mysqli->error . "<br>";
    }
    
    $mysqli->close();
    
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage();
}
?>
