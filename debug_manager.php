<?php
// Debug session et test manager
define('BASEPATH', true);
require_once('/Applications/MAMP/htdocs/crm.rebencia.com/application/config/database.php');

session_start();

echo "<h1>Debug Session Manager</h1>";

// 1. Vérifier session actuelle
echo "<h2>Session actuelle :</h2>";
if (isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn']) {
    echo "✅ Utilisateur connecté<br>";
    echo "Role : " . ($_SESSION['role'] ?? 'Non défini') . "<br>";
    echo "wp_id : " . ($_SESSION['wp_id'] ?? 'Non défini') . "<br>";
    echo "name : " . ($_SESSION['name'] ?? 'Non défini') . "<br>";
} else {
    echo "❌ Pas d'utilisateur connecté<br>";
    echo "Contenu session : <pre>" . print_r($_SESSION, true) . "</pre>";
}

// 2. Tester la création temporaire d'une session manager pour les tests
echo "<h2>Actions de test :</h2>";

if (isset($_GET['action']) && $_GET['action'] === 'simulate_manager') {
    // Simuler une session manager
    $_SESSION['isLoggedIn'] = TRUE;
    $_SESSION['logged_in'] = true;
    $_SESSION['wp_id'] = 1;
    $_SESSION['wp_login'] = 'manager_test';
    $_SESSION['name'] = 'Manager Test';
    $_SESSION['role'] = 'manager';
    $_SESSION['raw_role'] = 'houzez_manager';
    $_SESSION['wp_avatar'] = null;
    $_SESSION['wp_url'] = null;
    $_SESSION['user_post_id'] = 1;
    $_SESSION['agency_id'] = 1;
    
    echo "✅ Session manager simulée créée !<br>";
    echo '<a href="/dashboard/manager">Tester Dashboard Manager</a><br>';
    echo '<a href="?action=clear">Nettoyer session</a><br>';
} elseif (isset($_GET['action']) && $_GET['action'] === 'clear') {
    session_destroy();
    echo "✅ Session nettoyée !<br>";
    echo '<a href="?action=simulate_manager">Simuler session manager</a><br>';
} else {
    echo '<a href="?action=simulate_manager">Simuler une session manager pour test</a><br>';
    echo '<a href="?action=clear">Nettoyer session</a><br>';
}

// 3. Vérifier les utilisateurs manager dans la base
echo "<h2>Utilisateurs manager dans la base :</h2>";

try {
    $mysqli = new mysqli('localhost', 'rebencia_rebencia', 'Rebencia1402!!', 'rebencia_RebenciaBD');
    
    if ($mysqli->connect_error) {
        throw new Exception('Erreur de connexion: ' . $mysqli->connect_error);
    }
    
    $query = "
        SELECT 
            u.ID,
            u.user_login,
            u.user_email,
            u.display_name,
            um.meta_value as capabilities,
            (SELECT meta_value FROM wp_Hrg8P_usermeta WHERE user_id = u.ID AND meta_key = 'houzez_agency_id') as agency_id
        FROM wp_Hrg8P_users u
        LEFT JOIN wp_Hrg8P_usermeta um ON u.ID = um.user_id 
        WHERE um.meta_key = 'wp_Hrg8P_capabilities'
        AND (um.meta_value LIKE '%houzez_manager%' OR um.meta_value LIKE '%manager%')
    ";
    
    $result = $mysqli->query($query);
    if ($result && $result->num_rows > 0) {
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>ID</th><th>Login</th><th>Email</th><th>Nom</th><th>Agency ID</th><th>Capabilities</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['ID'] . "</td>";
            echo "<td>" . $row['user_login'] . "</td>";
            echo "<td>" . $row['user_email'] . "</td>";
            echo "<td>" . $row['display_name'] . "</td>";
            echo "<td>" . ($row['agency_id'] ?? 'Non défini') . "</td>";
            echo "<td>" . substr($row['capabilities'], 0, 50) . "...</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "❌ Aucun utilisateur manager trouvé.<br>";
        echo "Voulez-vous créer un utilisateur manager de test ?<br>";
        echo '<a href="?action=create_manager">Créer un manager de test</a>';
    }
    
    $mysqli->close();
    
} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage();
}

echo "<h2>Links de test :</h2>";
echo '<a href="/dashboard">Dashboard principal</a><br>';
echo '<a href="/dashboard/manager">Dashboard Manager</a><br>';
echo '<a href="/login">Page de connexion</a><br>';
?>
