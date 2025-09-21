<?php
session_start();

echo "<h2>Diagnostic : Liste des agents visibles pour le manager connecté</h2>";

if (!isset($_SESSION['vendor_id'])) {
    echo "<p style='color:red;'>Utilisateur non connecté.</p>";
    exit;
}

$manager_id = $_SESSION['vendor_id'];
$agency_id = $_SESSION['agency_id'] ?? null;
$role = $_SESSION['role'] ?? $_SESSION['roleText'] ?? '';

require_once 'application/config/database.php';

$host = $db['wordpress']['hostname'];
$username = $db['wordpress']['username'];
$password = $db['wordpress']['password'];
$database = $db['wordpress']['database'];
$prefix = $db['wordpress']['dbprefix'];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<div style='background:#f8f9fa;padding:10px;border-radius:5px;'>";
    echo "<strong>Manager ID:</strong> $manager_id<br>";
    echo "<strong>Role:</strong> $role<br>";
    echo "<strong>Agency ID (session):</strong> $agency_id<br>";
    echo "</div>";
    
    if ($role !== 'manager') {
        echo "<p style='color:orange;'>Attention : le rôle n'est pas 'manager' !</p>";
    }
    if (empty($agency_id)) {
        echo "<p style='color:red;'>Aucun agency_id en session !</p>";
        exit;
    }
    
    $sql = "SELECT agent_post_id, agent_name, agency_id, agency_name FROM {$prefix}crm_agents WHERE agency_id = ? AND post_status = 'publish' ORDER BY agent_name ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$agency_id]);
    $agents = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Agents de l'agence (ID: $agency_id)</h3>";
    echo "<table border='1' cellpadding='6' style='border-collapse:collapse;'>";
    echo "<tr><th>Matricule</th><th>Nom</th><th>Agency ID</th><th>Agence</th></tr>";
    foreach ($agents as $a) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($a['agent_post_id']) . "</td>";
        echo "<td>" . htmlspecialchars($a['agent_name']) . "</td>";
        echo "<td>" . htmlspecialchars($a['agency_id']) . "</td>";
        echo "<td>" . htmlspecialchars($a['agency_name']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Vérification croisée : y a-t-il des agents d'autres agences ?
    $sql_all = "SELECT agent_post_id, agent_name, agency_id, agency_name FROM {$prefix}crm_agents WHERE post_status = 'publish' ORDER BY agent_name ASC";
    $stmt_all = $pdo->query($sql_all);
    $all_agents = $stmt_all->fetchAll(PDO::FETCH_ASSOC);
    $autres = [];
    foreach ($all_agents as $a) {
        if ($a['agency_id'] != $agency_id) {
            $autres[] = $a;
        }
    }
    if (count($autres) > 0) {
        echo "<h4 style='color:#dc3545;'>Agents d'autres agences (ne doivent PAS être visibles) :</h4>";
        echo "<table border='1' cellpadding='6' style='border-collapse:collapse;'>";
        echo "<tr><th>Matricule</th><th>Nom</th><th>Agency ID</th><th>Agence</th></tr>";
        foreach ($autres as $a) {
            echo "<tr style='background:#ffeaea;'>";
            echo "<td>" . htmlspecialchars($a['agent_post_id']) . "</td>";
            echo "<td>" . htmlspecialchars($a['agent_name']) . "</td>";
            echo "<td>" . htmlspecialchars($a['agency_id']) . "</td>";
            echo "<td>" . htmlspecialchars($a['agency_name']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color:red;'>Erreur SQL : ".$e->getMessage()."</p>";
}
?>
