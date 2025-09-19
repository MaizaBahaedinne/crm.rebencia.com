<?php
// Test structure vue wp_Hrg8P_crm_agents
error_reporting(E_ALL);
ini_set('display_errors', 1);

$mysqli = new mysqli('localhost', 'rebencia_rebencia', 'Rebencia1402!!', 'rebencia_rebencia');

if ($mysqli->connect_error) {
    die('Erreur de connexion: ' . $mysqli->connect_error);
}

echo "<h1>Structure vue wp_Hrg8P_crm_agents</h1>";

$query = "DESCRIBE rebencia_RebenciaBD.wp_Hrg8P_crm_agents";
$result = $mysqli->query($query);

if ($result) {
    echo "<table border='1'><tr><th>Champ</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>{$row['Field']}</td><td>{$row['Type']}</td><td>{$row['Null']}</td><td>{$row['Key']}</td><td>{$row['Default']}</td></tr>";
    }
    echo "</table>";
} else {
    echo "Erreur: " . $mysqli->error;
}

echo "<h2>Quelques données de la vue</h2>";
$query2 = "SELECT * FROM rebencia_RebenciaBD.wp_Hrg8P_crm_agents LIMIT 3";
$result2 = $mysqli->query($query2);

if ($result2) {
    while ($row2 = $result2->fetch_assoc()) {
        echo "<pre>" . print_r($row2, true) . "</pre>";
    }
} else {
    echo "Erreur: " . $mysqli->error;
}

echo "<h2>Test Jointure</h2>";
$query3 = "
    SELECT 
        p.id,
        p.agent_id as property_agent_id,
        a.agent_post_id,
        a.agent_name,
        a.agency_name
    FROM rebencia_rebencia.crm_properties p
    LEFT JOIN rebencia_RebenciaBD.wp_Hrg8P_crm_agents a ON p.agent_id = a.agent_post_id
    WHERE p.valeur_estimee IS NOT NULL
    LIMIT 3
";

$result3 = $mysqli->query($query3);
if ($result3) {
    echo "Jointure réussie:<br>";
    while ($row3 = $result3->fetch_assoc()) {
        echo "<pre>" . print_r($row3, true) . "</pre>";
    }
} else {
    echo "Erreur jointure: " . $mysqli->error;
}

$mysqli->close();
?>
