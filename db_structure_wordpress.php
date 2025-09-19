<?php
/**
 * DOCUMENTATION STRUCTURE BASE DE DONNÉES - rebencia_RebenciaBD
 * Base WordPress avec agents, agences, utilisateurs, etc.
 */
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
    
    echo "<!DOCTYPE html>
    <html lang='fr'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Structure BDD - rebencia_RebenciaBD (WordPress)</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
            .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
            h1 { color: #2c3e50; border-bottom: 3px solid #9b59b6; padding-bottom: 10px; }
            h2 { color: #8e44ad; background: #f8f9fa; padding: 10px; border-left: 5px solid #8e44ad; }
            .table-info { background: #f4e8fd; padding: 15px; border-radius: 5px; margin: 10px 0; }
            table { width: 100%; border-collapse: collapse; margin: 10px 0; }
            th { background: #8e44ad; color: white; padding: 12px; text-align: left; }
            td { padding: 8px; border-bottom: 1px solid #ddd; }
            tr:nth-child(even) { background: #f9f9f9; }
            .key-primary { background: #e74c3c !important; color: white; }
            .key-mul { background: #f39c12 !important; color: white; }
            .sample-data { background: #e8f5e8; padding: 10px; border-radius: 5px; margin: 10px 0; }
            .navigation { position: fixed; top: 20px; right: 20px; background: white; padding: 15px; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
            .navigation a { display: block; margin: 5px 0; color: #8e44ad; text-decoration: none; }
            .navigation a:hover { text-decoration: underline; }
            .important-table { background: #fff3cd; border: 2px solid #ffc107; }
            .view-table { background: #d1ecf1; border: 2px solid #17a2b8; }
            .filter-buttons { margin: 20px 0; }
            .filter-btn { padding: 10px 15px; margin: 5px; border: none; border-radius: 5px; cursor: pointer; background: #6c757d; color: white; }
            .filter-btn.active { background: #8e44ad; }
        </style>
        <script>
            function filterTables(type) {
                const tables = document.querySelectorAll('[id^=\"wp_Hrg8P_\"]');
                const buttons = document.querySelectorAll('.filter-btn');
                
                // Reset button states
                buttons.forEach(btn => btn.classList.remove('active'));
                event.target.classList.add('active');
                
                tables.forEach(table => {
                    const tableId = table.id;
                    const tableContainer = table.parentElement;
                    
                    if (type === 'all') {
                        tableContainer.style.display = 'block';
                    } else if (type === 'crm' && tableId.includes('crm_agents')) {
                        tableContainer.style.display = 'block';
                    } else if (type === 'users' && (tableId.includes('users') || tableId.includes('usermeta'))) {
                        tableContainer.style.display = 'block';
                    } else if (type === 'posts' && (tableId.includes('posts') || tableId.includes('postmeta'))) {
                        tableContainer.style.display = 'block';
                    } else if (type === 'important' && 
                              (tableId.includes('crm_agents') || tableId.includes('users') || 
                               tableId.includes('posts') || tableId.includes('prop_agen'))) {
                        tableContainer.style.display = 'block';
                    } else {
                        tableContainer.style.display = 'none';
                    }
                });
            }
        </script>
    </head>
    <body>";
    
    echo "<div class='navigation'>
        <strong>Navigation Rapide</strong><br>
        <a href='#tables-list'>Liste des Tables</a>
        <a href='#wp_Hrg8P_crm_agents'>Vue CRM Agents</a>
        <a href='#wp_Hrg8P_prop_agen'>Table Prop-Agents</a>
        <a href='#wp_Hrg8P_users'>Utilisateurs WP</a>
        <a href='db_structure_crm.php'>Base CRM</a>
        <a href='/estimations'>Retour CRM</a>
    </div>";
    
    echo "<div class='container'>";
    echo "<h1>🔌 Structure Base de Données - rebencia_RebenciaBD (WordPress)</h1>";
    echo "<p><strong>Connexion:</strong> {$db_config['hostname']} | <strong>Base:</strong> {$db_config['database']}</p>";
    
    // Filtres
    echo "<div class='filter-buttons'>";
    echo "<button class='filter-btn active' onclick='filterTables(\"all\")'>Toutes les Tables</button>";
    echo "<button class='filter-btn' onclick='filterTables(\"important\")'>Tables Importantes</button>";
    echo "<button class='filter-btn' onclick='filterTables(\"crm\")'>CRM/Agents</button>";
    echo "<button class='filter-btn' onclick='filterTables(\"users\")'>Utilisateurs</button>";
    echo "<button class='filter-btn' onclick='filterTables(\"posts\")'>Posts/Contenus</button>";
    echo "</div>";
    
    // 1. Liste de toutes les tables
    echo "<h2 id='tables-list'>📋 Liste des Tables WordPress</h2>";
    $tables_query = "SHOW TABLES";
    $tables_result = $mysqli->query($tables_query);
    
    if ($tables_result) {
        $tables = [];
        $important_tables = ['wp_Hrg8P_crm_agents', 'wp_Hrg8P_prop_agen', 'wp_Hrg8P_users', 'wp_Hrg8P_posts', 'wp_Hrg8P_postmeta'];
        
        echo "<div class='table-info'>";
        echo "<strong>Nombre de tables:</strong> " . $tables_result->num_rows . "<br>";
        echo "<strong>Tables disponibles:</strong><br>";
        
        // Séparer les tables importantes
        $regular_tables = [];
        $priority_tables = [];
        
        while ($table_row = $tables_result->fetch_array()) {
            $table_name = $table_row[0];
            $tables[] = $table_name;
            
            if (in_array($table_name, $important_tables)) {
                $priority_tables[] = $table_name;
                echo "🔥 <a href='#{$table_name}'><strong>{$table_name}</strong></a> (Important)<br>";
            } else {
                $regular_tables[] = $table_name;
            }
        }
        
        echo "<br><strong>Autres tables:</strong><br>";
        foreach ($regular_tables as $table_name) {
            echo "• <a href='#{$table_name}'>{$table_name}</a><br>";
        }
        echo "</div>";
        
        // 2. Détail des tables importantes en premier
        $all_tables = array_merge($priority_tables, $regular_tables);
        
        foreach ($all_tables as $table_name) {
            $is_important = in_array($table_name, $important_tables);
            $is_view = strpos($table_name, 'crm_agents') !== false;
            
            $section_class = '';
            if ($is_important && $is_view) $section_class = 'view-table';
            elseif ($is_important) $section_class = 'important-table';
            
            echo "<div class='{$section_class}'>";
            echo "<h2 id='{$table_name}'>🗃️ " . ($is_view ? 'VUE:' : 'Table:') . " {$table_name}</h2>";
            
            if ($is_important) {
                echo "<div class='table-info'>";
                if ($table_name == 'wp_Hrg8P_crm_agents') {
                    echo "🔥 <strong>VUE IMPORTANTE:</strong> Données complètes des agents avec leurs agences<br>";
                } elseif ($table_name == 'wp_Hrg8P_prop_agen') {
                    echo "🔥 <strong>TABLE IMPORTANTE:</strong> Relation propriétés-agents<br>";
                } elseif ($table_name == 'wp_Hrg8P_users') {
                    echo "🔥 <strong>TABLE IMPORTANTE:</strong> Utilisateurs WordPress<br>";
                } elseif ($table_name == 'wp_Hrg8P_posts') {
                    echo "🔥 <strong>TABLE IMPORTANTE:</strong> Posts WordPress (agents, agences)<br>";
                }
                echo "</div>";
            }
            
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
            if ($is_important && $count > 0) {
                echo "<div class='sample-data'>";
                echo "<strong>📝 Échantillon de données:</strong><br>";
                
                $limit = ($table_name == 'wp_Hrg8P_posts') ? 3 : 2;
                $sample_query = "SELECT * FROM {$table_name} LIMIT {$limit}";
                $sample_result = $mysqli->query($sample_query);
                
                if ($sample_result) {
                    while ($sample_row = $sample_result->fetch_assoc()) {
                        echo "<pre style='background: white; padding: 10px; border-radius: 3px; margin: 5px 0;'>";
                        foreach ($sample_row as $key => $value) {
                            $display_value = strlen($value) > 150 ? substr($value, 0, 150) . '...' : $value;
                            echo "{$key}: {$display_value}\n";
                        }
                        echo "</pre>";
                    }
                }
                echo "</div>";
            }
            
            echo "</div>";
            echo "<hr style='margin: 30px 0;'>";
        }
        
    } else {
        echo "Erreur lors de la récupération des tables: " . $mysqli->error;
    }
    
    // 3. Relations importantes pour le CRM
    echo "<h2>🔗 Relations Importantes pour le CRM</h2>";
    echo "<div class='table-info'>";
    echo "<strong>Relations clés identifiées:</strong><br>";
    echo "• <code>crm_properties.agent_id</code> → <code>wp_Hrg8P_crm_agents.agent_post_id</code><br>";
    echo "• <code>wp_Hrg8P_crm_agents.user_id</code> → <code>wp_Hrg8P_users.ID</code><br>";
    echo "• <code>wp_Hrg8P_crm_agents.agency_id</code> → <code>wp_Hrg8P_posts.ID</code> (type='houzez_agency')<br>";
    echo "• <code>wp_Hrg8P_prop_agen.agent_id</code> → <code>wp_Hrg8P_crm_agents.agent_post_id</code><br>";
    echo "</div>";
    
    // 4. Informations sur la base de données
    echo "<h2>ℹ️ Informations Générales WordPress</h2>";
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
    
    // Préfixe WordPress
    echo "<strong>Préfixe WordPress:</strong> wp_Hrg8P_<br>";
    echo "<strong>Date de génération:</strong> " . date('Y-m-d H:i:s') . "<br>";
    echo "</div>";
    
    echo "</div></body></html>";
    
    $mysqli->close();
    
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage();
}
?>
