<?php
// Test direct des données d'estimations
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuration base de données
$db_config = [
    'hostname' => 'localhost',
    'username' => 'rebencia_rebencia',
    'password' => 'Rebencia1402!!',
    'database' => 'rebencia_rebencia'
];

try {
    // Connexion à la base de données
    $mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);
    
    if ($mysqli->connect_error) {
        die('Erreur de connexion: ' . $mysqli->connect_error);
    }
    
    echo "<h1>Test des Données d'Estimations</h1>";
    
    // 1. Test table crm_properties
    echo "<h2>1. Test table crm_properties</h2>";
    $query1 = "SELECT COUNT(*) as total FROM crm_properties";
    $result1 = $mysqli->query($query1);
    if ($result1) {
        $row1 = $result1->fetch_assoc();
        echo "Nombre de propriétés: " . $row1['total'] . "<br>";
    } else {
        echo "Erreur requête crm_properties: " . $mysqli->error . "<br>";
    }
    
    // 2. Test table wp_Hrg8P_prop_agen (cross-database)
    echo "<h2>2. Test table wp_Hrg8P_prop_agen (cross-database)</h2>";
    $query2 = "SELECT COUNT(*) as total FROM rebencia_RebenciaBD.wp_Hrg8P_prop_agen";
    $result2 = $mysqli->query($query2);
    if ($result2) {
        $row2 = $result2->fetch_assoc();
        echo "Nombre d'agents: " . $row2['total'] . "<br>";
    } else {
        echo "Erreur requête wp_Hrg8P_prop_agen: " . $mysqli->error . "<br>";
    }
    
    // 3. Test table wp_Hrg8P_users (cross-database)
    echo "<h2>3. Test table wp_Hrg8P_users (cross-database)</h2>";
    $query3 = "SELECT COUNT(*) as total FROM rebencia_RebenciaBD.wp_Hrg8P_users";
    $result3 = $mysqli->query($query3);
    if ($result3) {
        $row3 = $result3->fetch_assoc();
        echo "Nombre d'utilisateurs WordPress: " . $row3['total'] . "<br>";
    } else {
        echo "Erreur requête wp_Hrg8P_users: " . $mysqli->error . "<br>";
    }
    
    // 4. Test jointure complète comme dans le modèle
    echo "<h2>4. Test jointure complète</h2>";
    $query4 = "
        SELECT 
            p.id,
            p.type_propriete,
            p.adresse_ville,
            p.valeur_estimee,
            u.display_name as agent_nom
        FROM crm_properties p
        LEFT JOIN rebencia_RebenciaBD.wp_Hrg8P_prop_agen a ON p.agent_id = a.agent_post_id
        LEFT JOIN rebencia_RebenciaBD.wp_Hrg8P_users u ON a.user_id = u.ID
        WHERE p.valeur_estimee IS NOT NULL
        LIMIT 5
    ";
    
    $result4 = $mysqli->query($query4);
    if ($result4) {
        echo "Résultats de la jointure:<br>";
        while ($row4 = $result4->fetch_assoc()) {
            echo "ID: {$row4['id']}, Type: {$row4['type_propriete']}, Ville: {$row4['adresse_ville']}, Valeur: {$row4['valeur_estimee']}, Agent: {$row4['agent_nom']}<br>";
        }
    } else {
        echo "Erreur jointure: " . $mysqli->error . "<br>";
    }
    
    // 5. Structure de la table crm_properties
    echo "<h2>5. Structure table crm_properties</h2>";
    $query5 = "DESCRIBE crm_properties";
    $result5 = $mysqli->query($query5);
    if ($result5) {
        echo "<table border='1'><tr><th>Champ</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
        while ($row5 = $result5->fetch_assoc()) {
            echo "<tr><td>{$row5['Field']}</td><td>{$row5['Type']}</td><td>{$row5['Null']}</td><td>{$row5['Key']}</td><td>{$row5['Default']}</td></tr>";
        }
        echo "</table>";
    }
    
    $mysqli->close();
    
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage();
}
?>
