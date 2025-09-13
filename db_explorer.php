<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔗 Test de connexion base de données - Multiple configurations</h1>";

// Configurations à tester
$configs = [
    [
        'name' => 'Configuration 1 - Serveur distant',
        'hostname' => '146.59.94.214',
        'port' => 3306,
        'username' => 'rebencia_rebencia',
        'password' => 'Rebencia1402!!',
        'database' => 'rebencia_RebenciaBD'
    ],
    [
        'name' => 'Configuration 2 - Serveur distant (sans préfixe)',
        'hostname' => '146.59.94.214',
        'port' => 3306,
        'username' => 'root',
        'password' => 'Rebencia1402!!',
        'database' => 'wordpress'
    ],
    [
        'name' => 'Configuration 3 - Serveur distant (autre database)',
        'hostname' => '146.59.94.214',
        'port' => 3306,
        'username' => 'rebencia_rebencia',
        'password' => 'Rebencia1402!!',
        'database' => 'rebencia_rebencia'
    ],
    [
        'name' => 'Configuration 4 - MAMP Local',
        'hostname' => 'localhost',
        'port' => 3306,
        'username' => 'root',
        'password' => 'root',
        'database' => 'wordpress'
    ]
];

foreach ($configs as $index => $config) {
    echo "<div style='border: 1px solid #ddd; margin: 15px 0; padding: 15px; border-radius: 5px;'>";
    echo "<h2>🧪 Test " . ($index + 1) . ": " . $config['name'] . "</h2>";
    echo "<p><strong>Serveur:</strong> {$config['hostname']}:{$config['port']}</p>";
    echo "<p><strong>Database:</strong> {$config['database']}</p>";
    echo "<p><strong>Username:</strong> {$config['username']}</p>";
    
    try {
        $dsn = "mysql:host={$config['hostname']};port={$config['port']};charset=utf8";
        if (!empty($config['database'])) {
            $dsn .= ";dbname={$config['database']}";
        }
        
        echo "<p>⏳ Tentative de connexion...</p>";
        
        $pdo = new PDO($dsn, $config['username'], $config['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 5 // Timeout de 5 secondes
        ]);
        
        echo "<div style='background: #e8f5e8; border: 1px solid #4caf50; padding: 10px; margin: 10px 0;'>";
        echo "<h3 style='color: #2e7d32; margin: 0;'>✅ Connexion réussie !</h3>";
        echo "</div>";
        
        // Lister les bases de données disponibles
        echo "<h4>📋 Bases de données disponibles:</h4>";
        $stmt = $pdo->query("SHOW DATABASES");
        $databases = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo "<ul>";
        foreach ($databases as $db) {
            echo "<li><strong>$db</strong>";
            if (stripos($db, 'rebencia') !== false || stripos($db, 'wordpress') !== false) {
                echo " 🎯 <em>(Potentiellement intéressant)</em>";
            }
            echo "</li>";
        }
        echo "</ul>";
        
        // Si une base est sélectionnée, lister les tables
        if (!empty($config['database'])) {
            echo "<h4>📊 Tables dans {$config['database']}:</h4>";
            try {
                $pdo->exec("USE `{$config['database']}`");
                $stmt = $pdo->query("SHOW TABLES");
                $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
                
                if ($tables) {
                    echo "<p>" . count($tables) . " tables trouvées:</p>";
                    echo "<div style='max-height: 200px; overflow-y: auto; border: 1px solid #eee; padding: 10px;'>";
                    foreach ($tables as $table) {
                        echo "<span style='display: inline-block; margin: 2px; padding: 3px 8px; background: #f0f0f0; border-radius: 3px; font-size: 12px;'>$table</span>";
                    }
                    echo "</div>";
                    
                    // Chercher des tables d'agents
                    $agent_tables = array_filter($tables, function($table) {
                        return stripos($table, 'agent') !== false || 
                               stripos($table, 'posts') !== false ||
                               stripos($table, 'user') !== false;
                    });
                    
                    if ($agent_tables) {
                        echo "<p><strong>🎯 Tables potentiellement liées aux agents:</strong></p>";
                        foreach ($agent_tables as $table) {
                            echo "<li><strong>$table</strong></li>";
                        }
                    }
                } else {
                    echo "<p>Aucune table trouvée.</p>";
                }
            } catch (Exception $e) {
                echo "<p style='color: orange;'>⚠️ Impossible d'accéder aux tables: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
        }
        
        // Bouton pour explorer cette configuration
        echo "<p><a href='?use_config=$index' style='background: #4caf50; color: white; padding: 8px 15px; text-decoration: none; border-radius: 3px;'>🔍 Explorer cette configuration</a></p>";
        
    } catch (PDOException $e) {
        echo "<div style='background: #ffebee; border: 1px solid #f44336; padding: 10px; margin: 10px 0;'>";
        echo "<h4 style='color: #d32f2f; margin: 0 0 10px 0;'>❌ Échec de connexion</h4>";
        echo "<p><strong>Erreur:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<p><strong>Code:</strong> " . $e->getCode() . "</p>";
        echo "</div>";
    }
    
    echo "</div>";
}

// Si une configuration spécifique est sélectionnée pour exploration
if (isset($_GET['use_config']) && is_numeric($_GET['use_config'])) {
    $config_index = (int)$_GET['use_config'];
    if (isset($configs[$config_index])) {
        $config = $configs[$config_index];
        
        echo "<hr>";
        echo "<h2>🔍 Exploration détaillée: " . $config['name'] . "</h2>";
        
        try {
            $dsn = "mysql:host={$config['hostname']};port={$config['port']};dbname={$config['database']};charset=utf8";
            $pdo = new PDO($dsn, $config['username'], $config['password']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Interface pour explorer les tables
            echo "<form method='GET'>";
            echo "<input type='hidden' name='use_config' value='$config_index'>";
            echo "<label>Table à explorer: </label>";
            echo "<select name='table'>";
            echo "<option value=''>Choisir une table...</option>";
            
            $stmt = $pdo->query("SHOW TABLES");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            foreach ($tables as $table) {
                $selected = (isset($_GET['table']) && $_GET['table'] === $table) ? 'selected' : '';
                echo "<option value='$table' $selected>$table</option>";
            }
            echo "</select>";
            echo " <button type='submit'>Explorer</button>";
            echo "</form>";
            
            // Afficher les détails de la table sélectionnée
            if (isset($_GET['table']) && !empty($_GET['table'])) {
                $table = $_GET['table'];
                
                echo "<h3>📊 Table: <code>$table</code></h3>";
                
                // Structure
                $desc_stmt = $pdo->query("DESCRIBE `$table`");
                $columns = $desc_stmt->fetchAll(PDO::FETCH_ASSOC);
                
                echo "<h4>Structure:</h4>";
                echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
                echo "<tr><th>Colonne</th><th>Type</th><th>Null</th><th>Clé</th><th>Défaut</th></tr>";
                foreach ($columns as $col) {
                    echo "<tr>";
                    echo "<td><strong>" . htmlspecialchars($col['Field']) . "</strong></td>";
                    echo "<td>" . htmlspecialchars($col['Type']) . "</td>";
                    echo "<td>" . htmlspecialchars($col['Null']) . "</td>";
                    echo "<td>" . htmlspecialchars($col['Key']) . "</td>";
                    echo "<td>" . htmlspecialchars($col['Default']) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
                
                // Exemples de données
                echo "<h4>Exemples de données:</h4>";
                $data_stmt = $pdo->query("SELECT * FROM `$table` LIMIT 3");
                $sample_data = $data_stmt->fetchAll(PDO::FETCH_ASSOC);
                
                if ($sample_data) {
                    echo "<div style='overflow-x: auto;'>";
                    echo "<table border='1' style='border-collapse: collapse; font-size: 12px;'>";
                    
                    // En-têtes
                    echo "<tr style='background: #f0f0f0;'>";
                    foreach (array_keys($sample_data[0]) as $header) {
                        echo "<th>" . htmlspecialchars($header) . "</th>";
                    }
                    echo "</tr>";
                    
                    // Données
                    foreach ($sample_data as $row) {
                        echo "<tr>";
                        foreach ($row as $value) {
                            $display_value = htmlspecialchars(substr($value, 0, 30));
                            if (strlen($value) > 30) $display_value .= '...';
                            echo "<td>$display_value</td>";
                        }
                        echo "</tr>";
                    }
                    echo "</table>";
                    echo "</div>";
                }
            }
            
        } catch (Exception $e) {
            echo "<p style='color: red;'>Erreur: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }
}

echo "<hr>";
echo "<p><small>Test effectué le " . date('Y-m-d H:i:s') . "</small></p>";
?>
    
    echo "<div style='background: #e8f5e8; border: 1px solid #4caf50; padding: 10px; margin: 10px 0;'>";
    echo "<h3 style='color: #2e7d32;'>✅ Connexion réussie !</h3>";
    echo "</div>";
    
    // Lister toutes les tables
    echo "<h2>📋 Tables disponibles</h2>";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<p><strong>" . count($tables) . " tables trouvées:</strong></p>";
    echo "<div style='max-height: 300px; overflow-y: auto; border: 1px solid #ddd; padding: 10px;'>";
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li><strong>$table</strong> 
                <a href='#' onclick=\"showTableInfo('$table')\" style='color: blue; cursor: pointer;'>[Structure]</a>
                <a href='#' onclick=\"showTableData('$table')\" style='color: green; cursor: pointer;'>[Données]</a>
              </li>";
    }
    echo "</ul>";
    echo "</div>";
    
    // Rechercher spécifiquement les tables d'agents
    echo "<h2>🎯 Tables liées aux agents</h2>";
    $agent_tables = array_filter($tables, function($table) {
        return stripos($table, 'agent') !== false || 
               stripos($table, 'houzez') !== false ||
               stripos($table, 'posts') !== false ||
               stripos($table, 'meta') !== false;
    });
    
    if ($agent_tables) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 10px 0;'>";
        echo "<tr style='background: #f5f5f5;'><th>Table</th><th>Nombre de lignes</th><th>Colonnes principales</th></tr>";
        
        foreach ($agent_tables as $table) {
            echo "<tr>";
            echo "<td><strong>$table</strong></td>";
            
            // Compter les lignes
            try {
                $count_stmt = $pdo->query("SELECT COUNT(*) FROM `$table`");
                $count = number_format($count_stmt->fetchColumn());
                echo "<td>$count</td>";
            } catch (Exception $e) {
                echo "<td>Erreur</td>";
            }
            
            // Afficher les premières colonnes
            try {
                $desc_stmt = $pdo->query("DESCRIBE `$table`");
                $columns = $desc_stmt->fetchAll(PDO::FETCH_COLUMN);
                $first_columns = array_slice($columns, 0, 3);
                echo "<td>" . implode(', ', $first_columns) . "...</td>";
            } catch (Exception $e) {
                echo "<td>Erreur</td>";
            }
            
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Interface pour explorer une table spécifique
    echo "<h2>🔍 Explorer une table</h2>";
    echo "<form method='GET' style='margin: 10px 0;'>";
    echo "<label>Nom de la table: </label>";
    echo "<select name='explore_table'>";
    echo "<option value=''>Choisir une table...</option>";
    foreach ($tables as $table) {
        $selected = (isset($_GET['explore_table']) && $_GET['explore_table'] === $table) ? 'selected' : '';
        echo "<option value='$table' $selected>$table</option>";
    }
    echo "</select>";
    echo " <button type='submit'>Explorer</button>";
    echo "</form>";
    
    // Afficher les détails de la table sélectionnée
    if (isset($_GET['explore_table']) && !empty($_GET['explore_table'])) {
        $table_to_explore = $_GET['explore_table'];
        
        echo "<h3>📊 Détails de la table: <code>$table_to_explore</code></h3>";
        
        // Structure de la table
        echo "<h4>Structure des colonnes:</h4>";
        $desc_stmt = $pdo->query("DESCRIBE `$table_to_explore`");
        $columns = $desc_stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>Colonne</th><th>Type</th><th>Null</th><th>Clé</th><th>Défaut</th></tr>";
        foreach ($columns as $col) {
            echo "<tr>";
            echo "<td><strong>" . htmlspecialchars($col['Field']) . "</strong></td>";
            echo "<td>" . htmlspecialchars($col['Type']) . "</td>";
            echo "<td>" . htmlspecialchars($col['Null']) . "</td>";
            echo "<td>" . htmlspecialchars($col['Key']) . "</td>";
            echo "<td>" . htmlspecialchars($col['Default']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Quelques exemples de données
        echo "<h4>Exemples de données (5 premières lignes):</h4>";
        try {
            $data_stmt = $pdo->query("SELECT * FROM `$table_to_explore` LIMIT 5");
            $sample_data = $data_stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if ($sample_data) {
                echo "<div style='overflow-x: auto;'>";
                echo "<table border='1' style='border-collapse: collapse; font-size: 12px;'>";
                
                // En-têtes
                echo "<tr style='background: #f0f0f0;'>";
                foreach (array_keys($sample_data[0]) as $header) {
                    echo "<th>" . htmlspecialchars($header) . "</th>";
                }
                echo "</tr>";
                
                // Données
                foreach ($sample_data as $row) {
                    echo "<tr>";
                    foreach ($row as $value) {
                        $display_value = htmlspecialchars(substr($value, 0, 50));
                        if (strlen($value) > 50) $display_value .= '...';
                        echo "<td>$display_value</td>";
                    }
                    echo "</tr>";
                }
                echo "</table>";
                echo "</div>";
            } else {
                echo "<p>Aucune donnée trouvée.</p>";
            }
        } catch (Exception $e) {
            echo "<p style='color: red;'>Erreur lors de la récupération des données: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }
    
} catch (PDOException $e) {
    echo "<div style='background: #ffebee; border: 1px solid #f44336; padding: 10px; margin: 10px 0;'>";
    echo "<h3 style='color: #d32f2f;'>❌ Erreur de connexion</h3>";
    echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>Code:</strong> " . $e->getCode() . "</p>";
    echo "</div>";
    
    echo "<h3>💡 Solutions possibles:</h3>";
    echo "<ul>";
    echo "<li>Vérifier que l'adresse IP et le port sont corrects</li>";
    echo "<li>Vérifier les identifiants de connexion</li>";
    echo "<li>Vérifier que le serveur MySQL autorise les connexions externes</li>";
    echo "<li>Vérifier que le pare-feu n'est pas bloquant</li>";
    echo "</ul>";
    
    echo "<h3>🔧 Paramètres à vérifier:</h3>";
    echo "<ul>";
    echo "<li><strong>Hostname:</strong> $hostname</li>";
    echo "<li><strong>Port:</strong> $port</li>";
    echo "<li><strong>Username:</strong> $username</li>";
    echo "<li><strong>Database:</strong> $database</li>";
    echo "</ul>";
}

echo "<hr>";
echo "<p><small>Généré le " . date('Y-m-d H:i:s') . "</small></p>";
?>

<script>
function showTableInfo(tableName) {
    window.location.href = '?explore_table=' + encodeURIComponent(tableName);
}

function showTableData(tableName) {
    window.location.href = '?explore_table=' + encodeURIComponent(tableName) + '#data';
}
</script>
