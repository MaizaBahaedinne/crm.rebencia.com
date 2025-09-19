<?php
/**
 * DOCUMENTATION STRUCTURE BASE DE DONNÉES - rebencia_rebencia
 * Base principale du CRM avec les propriétés, estimations, etc.
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuration base de données principale CRM
$db_config = [
    'hostname' => 'localhost',
    'username' => 'rebencia_rebencia',
    'password' => 'Rebencia1402!!',
    'database' => 'rebencia_rebencia'
];

try {
    $mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);
    
    if ($mysqli->connect_error) {
        die('Erreur de connexion: ' . $mysqli->connect_error);
    }
    
    echo "<!DOCTYPE html>
    <html lang='fr'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Structure BDD - rebencia_rebencia (CRM)</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
            .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
            h1 { color: #2c3e50; border-bottom: 3px solid #3498db; padding-bottom: 10px; }
            h2 { color: #e74c3c; background: #f8f9fa; padding: 10px; border-left: 5px solid #e74c3c; }
            .table-info { background: #e8f4fd; padding: 15px; border-radius: 5px; margin: 10px 0; }
            table { width: 100%; border-collapse: collapse; margin: 10px 0; }
            th { background: #34495e; color: white; padding: 12px; text-align: left; }
            td { padding: 8px; border-bottom: 1px solid #ddd; }
            tr:nth-child(even) { background: #f9f9f9; }
            .key-primary { background: #f39c12 !important; color: white; }
            .key-mul { background: #e67e22 !important; color: white; }
            .sample-data { background: #d5f4e6; padding: 10px; border-radius: 5px; margin: 10px 0; }
            .navigation { position: fixed; top: 20px; right: 20px; background: white; padding: 15px; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
            .navigation a { display: block; margin: 5px 0; color: #3498db; text-decoration: none; }
            .navigation a:hover { text-decoration: underline; }
        </style>
    </head>
    <body>";
    
    echo "<div class='navigation'>
        <strong>Navigation Rapide</strong><br>
        <a href='#tables-list'>Liste des Tables</a>
        <a href='db_structure_wordpress.php'>Base WordPress</a>
        <a href='/estimations'>Retour CRM</a>
    </div>";
    
    echo "<div class='container'>";
    echo "<h1>📊 Structure Base de Données - rebencia_rebencia (CRM Principal)</h1>";
    echo "<p><strong>Connexion:</strong> {$db_config['hostname']} | <strong>Base:</strong> {$db_config['database']}</p>";
    
    // 1. Liste de toutes les tables
    echo "<h2 id='tables-list'>📋 Liste des Tables</h2>";
    $tables_query = "SHOW TABLES";
    $tables_result = $mysqli->query($tables_query);
    
    if ($tables_result) {
        $tables = [];
        echo "<div class='table-info'>";
        echo "<strong>Nombre de tables:</strong> " . $tables_result->num_rows . "<br>";
        echo "<strong>Tables disponibles:</strong><br>";
        
        while ($table_row = $tables_result->fetch_array()) {
            $table_name = $table_row[0];
            $tables[] = $table_name;
            echo "• <a href='#{$table_name}'>{$table_name}</a><br>";
        }
        echo "</div>";
        
        // 2. Détail de chaque table
        foreach ($tables as $table_name) {
            echo "<h2 id='{$table_name}'>🗃️ Table: {$table_name}</h2>";
            
            // Structure de la table
            $structure_query = "DESCRIBE {$table_name}";
            $structure_result = $mysqli->query($structure_query);
            
            if ($structure_result) {
                echo "<table>";
                echo "<tr><th>Champ</th><th>Type</th><th>Null</th><th>Clé</th><th>Défaut</th><th>Extra</th></tr>";
                
                while ($field = $structure_result->fetch_assoc()) {
                    $row_class = '';
                    if ($field['Key'] == 'PRI') $row_class = 'key-primary';
                    elseif ($field['Key'] == 'MUL') $row_class = 'key-mul';
                    
                    echo "<tr class='{$row_class}'>";
                    echo "<td><strong>{$field['Field']}</strong></td>";
                    echo "<td>{$field['Type']}</td>";
                    echo "<td>{$field['Null']}</td>";
                    echo "<td>{$field['Key']}</td>";
                    echo "<td>{$field['Default']}</td>";
                    echo "<td>{$field['Extra']}</td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
            
            // Comptage des enregistrements
            $count_query = "SELECT COUNT(*) as total FROM {$table_name}";
            $count_result = $mysqli->query($count_query);
            if ($count_result) {
                $count = $count_result->fetch_assoc()['total'];
                echo "<div class='table-info'><strong>Nombre d'enregistrements:</strong> {$count}</div>";
            }
            
            // Échantillon de données pour les tables importantes
            if (in_array($table_name, ['crm_properties', 'crm_zones', 'crm_property_photos']) && $count > 0) {
                echo "<div class='sample-data'>";
                echo "<strong>📝 Échantillon de données:</strong><br>";
                
                $sample_query = "SELECT * FROM {$table_name} LIMIT 2";
                $sample_result = $mysqli->query($sample_query);
                
                if ($sample_result) {
                    while ($sample_row = $sample_result->fetch_assoc()) {
                        echo "<pre style='background: white; padding: 10px; border-radius: 3px; margin: 5px 0;'>";
                        foreach ($sample_row as $key => $value) {
                            $display_value = strlen($value) > 100 ? substr($value, 0, 100) . '...' : $value;
                            echo "{$key}: {$display_value}\n";
                        }
                        echo "</pre>";
                    }
                }
                echo "</div>";
            }
            
            echo "<hr style='margin: 30px 0;'>";
        }
        
    } else {
        echo "Erreur lors de la récupération des tables: " . $mysqli->error;
    }
    
    // 3. Informations sur la base de données
    echo "<h2>ℹ️ Informations Générales</h2>";
    echo "<div class='table-info'>";
    
    // Version MySQL
    $version_query = "SELECT VERSION() as version";
    $version_result = $mysqli->query($version_query);
    if ($version_result) {
        $version = $version_result->fetch_assoc()['version'];
        echo "<strong>Version MySQL:</strong> {$version}<br>";
    }
    
    // Charset de la base
    $charset_query = "SELECT DEFAULT_CHARACTER_SET_NAME, DEFAULT_COLLATION_NAME 
                     FROM information_schema.SCHEMATA 
                     WHERE SCHEMA_NAME = '{$db_config['database']}'";
    $charset_result = $mysqli->query($charset_query);
    if ($charset_result) {
        $charset = $charset_result->fetch_assoc();
        echo "<strong>Charset:</strong> {$charset['DEFAULT_CHARACTER_SET_NAME']}<br>";
        echo "<strong>Collation:</strong> {$charset['DEFAULT_COLLATION_NAME']}<br>";
    }
    
    echo "<strong>Date de génération:</strong> " . date('Y-m-d H:i:s') . "<br>";
    echo "</div>";
    
    echo "</div></body></html>";
    
    $mysqli->close();
    
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage();
}
?>
