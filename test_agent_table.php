<?php
// Test structure table wp_Hrg8P_prop_agen
error_reporting(E_ALL);
ini_set('display_errors', 1);

$mysqli = new mysqli('localhost', 'rebencia_rebencia', 'Rebencia1402!!', 'rebencia_rebencia');

if ($mysqli->connect_error) {
    die('Erreur de connexion: ' . $mysqli->connect_error);
}

echo "<h1>Structure table wp_Hrg8P_prop_agen</h1>";

$query = "DESCRIBE rebencia_RebenciaBD.wp_Hrg8P_prop_agen";
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

echo "<h2>Quelques données de la table</h2>";
$query2 = "SELECT * FROM rebencia_RebenciaBD.wp_Hrg8P_prop_agen LIMIT 3";
$result2 = $mysqli->query($query2);

if ($result2) {
    while ($row2 = $result2->fetch_assoc()) {
        echo "<pre>" . print_r($row2, true) . "</pre>";
    }
} else {
    echo "Erreur: " . $mysqli->error;
}

$mysqli->close();
?>
