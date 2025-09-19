<?php
// Test pour vérifier le système manager
session_start();

echo "<h1>Test Dashboard Manager</h1>";

// Test des données de session
echo "<h2>Session actuelle:</h2>";
if (isset($_SESSION) && !empty($_SESSION)) {
    echo "<pre>" . print_r($_SESSION, true) . "</pre>";
} else {
    echo "Aucune session active";
}

// Test direct de la base WordPress pour voir les utilisateurs manager
echo "<h2>Utilisateurs avec rôle manager dans WordPress:</h2>";

try {
    $mysqli = new mysqli('localhost', 'rebencia_rebencia', 'Rebencia1402!!', 'rebencia_RebenciaBD');
    
    if ($mysqli->connect_error) {
        die('Erreur de connexion: ' . $mysqli->connect_error);
    }
    
    // Chercher les utilisateurs avec le rôle houzez_manager
    $query = "
        SELECT 
            u.ID,
            u.user_login,
            u.user_email,
            u.display_name,
            um.meta_value as capabilities
        FROM wp_Hrg8P_users u
        LEFT JOIN wp_Hrg8P_usermeta um ON u.ID = um.user_id 
        WHERE um.meta_key = 'wp_Hrg8P_capabilities'
        AND um.meta_value LIKE '%houzez_manager%'
    ";
    
    $result = $mysqli->query($query);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div style='border: 1px solid #ddd; padding: 10px; margin: 5px;'>";
            echo "<strong>ID:</strong> " . $row['ID'] . "<br>";
            echo "<strong>Login:</strong> " . $row['user_login'] . "<br>";
            echo "<strong>Email:</strong> " . $row['user_email'] . "<br>";
            echo "<strong>Nom:</strong> " . $row['display_name'] . "<br>";
            echo "<strong>Capabilities:</strong> " . $row['capabilities'] . "<br>";
            echo "</div>";
        }
    } else {
        echo "Aucun utilisateur manager trouvé.";
        
        // Chercher tous les types de rôles disponibles
        echo "<h3>Tous les rôles dans la base:</h3>";
        $query2 = "
            SELECT DISTINCT meta_value 
            FROM wp_Hrg8P_usermeta 
            WHERE meta_key = 'wp_Hrg8P_capabilities'
            LIMIT 10
        ";
        $result2 = $mysqli->query($query2);
        if ($result2) {
            while ($row2 = $result2->fetch_assoc()) {
                echo "- " . $row2['meta_value'] . "<br>";
            }
        }
    }
    
    $mysqli->close();
    
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage();
}

echo "<h2>Tests de redirection:</h2>";
echo '<a href="https://crm.rebencia.com/dashboard/manager">Test Dashboard Manager</a><br>';
echo '<a href="https://crm.rebencia.com/login">Page de connexion</a>';
?>
