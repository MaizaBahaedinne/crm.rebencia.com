<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Debug Base de Données - Agents</h1>";

// Configuration de la base de données (similaire à CodeIgniter)
$hostname = 'localhost';
$username = 'root'; // Ou votre utilisateur MAMP
$password = 'root'; // Ou votre mot de passe MAMP
$database = 'crm_rebencia'; // Nom de votre base de données

try {
    $pdo = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connexion à la base réussie<br><br>";
    
    // Vérifier les tables d'agents
    echo "<h2>1. Tables contenant 'agent'</h2>";
    $stmt = $pdo->query("SHOW TABLES LIKE '%agent%'");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if ($tables) {
        foreach ($tables as $table) {
            echo "📋 Table: <strong>$table</strong><br>";
            
            // Compter les lignes
            $count_stmt = $pdo->query("SELECT COUNT(*) FROM `$table`");
            $count = $count_stmt->fetchColumn();
            echo "&nbsp;&nbsp;&nbsp;→ $count lignes<br>";
            
            // Afficher la structure
            $desc_stmt = $pdo->query("DESCRIBE `$table`");
            $columns = $desc_stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "&nbsp;&nbsp;&nbsp;→ Colonnes: ";
            foreach ($columns as $col) {
                echo $col['Field'] . " (" . $col['Type'] . "), ";
            }
            echo "<br><br>";
        }
    }
    
    // Regarder dans wp_posts pour les agents (HOUZEZ)
    echo "<h2>2. Agents dans wp_posts</h2>";
    $stmt = $pdo->query("SELECT COUNT(*) FROM wp_posts WHERE post_type = 'houzez_agent' AND post_status = 'publish'");
    $agent_count = $stmt->fetchColumn();
    echo "📊 $agent_count agents trouvés dans wp_posts<br><br>";
    
    if ($agent_count > 0) {
        echo "<h3>Liste des agents :</h3>";
        $stmt = $pdo->query("
            SELECT ID, post_title, post_name, post_date 
            FROM wp_posts 
            WHERE post_type = 'houzez_agent' 
            AND post_status = 'publish' 
            ORDER BY ID 
            LIMIT 10
        ");
        $agents = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Titre</th><th>Slug</th><th>Date</th><th>Test</th></tr>";
        
        foreach ($agents as $agent) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($agent['ID']) . "</td>";
            echo "<td>" . htmlspecialchars($agent['post_title']) . "</td>";
            echo "<td>" . htmlspecialchars($agent['post_name']) . "</td>";
            echo "<td>" . htmlspecialchars($agent['post_date']) . "</td>";
            echo "<td><a href='https://crm.rebencia.com/index.php/agents/view/" . $agent['ID'] . "' target='_blank'>Tester</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Vérifier les méta-données d'agents
    echo "<h2>3. Méta-données des agents</h2>";
    $stmt = $pdo->query("
        SELECT meta_key, COUNT(*) as count 
        FROM wp_postmeta pm
        JOIN wp_posts p ON pm.post_id = p.ID
        WHERE p.post_type = 'houzez_agent'
        AND meta_key LIKE '%agent%'
        GROUP BY meta_key
        ORDER BY count DESC
        LIMIT 10
    ");
    $metas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($metas) {
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Meta Key</th><th>Count</th></tr>";
        foreach ($metas as $meta) {
            echo "<tr><td>" . htmlspecialchars($meta['meta_key']) . "</td><td>" . $meta['count'] . "</td></tr>";
        }
        echo "</table>";
    }
    
} catch (PDOException $e) {
    echo "❌ Erreur de connexion: " . $e->getMessage();
}
?>
