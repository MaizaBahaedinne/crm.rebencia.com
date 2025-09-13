<?php
// Test direct des agents - contournement du routage CodeIgniter
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔍 Test Direct Agents - Contournement</h1>";

// Inclure le framework CodeIgniter manuellement
$GLOBALS['EXT'] = '.php';
$GLOBALS['CFG'] = & load_class('Config', 'core');
$GLOBALS['UNI'] = & load_class('Utf8', 'core');
$GLOBALS['SEC'] = & load_class('Security', 'core');
$GLOBALS['IN'] = & load_class('Input', 'core');

// Démarrage basique
define('BASEPATH', dirname(__FILE__) . '/system/');
define('APPPATH', dirname(__FILE__) . '/application/');

// Chargement manuel de la base
require_once(dirname(__FILE__) . '/system/core/Common.php');

try {
    // Configuration base de données
    $config = array(
        'dsn' => '',
        'hostname' => 'localhost',
        'username' => 'root',
        'password' => 'root',
        'database' => 'wordpress',  // Base WordPress avec les agents HOUZEZ
        'dbdriver' => 'mysqli',
        'dbprefix' => 'wp_',
        'pconnect' => FALSE,
        'db_debug' => TRUE,
        'cache_on' => FALSE,
        'cachedir' => '',
        'char_set' => 'utf8',
        'dbcollat' => 'utf8_general_ci'
    );

    // Connexion PDO directe (plus simple)
    $pdo = new PDO("mysql:host=localhost;dbname=wordpress;charset=utf8", 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connexion base de données réussie<br><br>";
    
    // 1. Compter les agents
    $stmt = $pdo->query("SELECT COUNT(*) FROM wp_posts WHERE post_type = 'houzez_agent' AND post_status = 'publish'");
    $count = $stmt->fetchColumn();
    echo "<h2>📊 Résultat: $count agents trouvés</h2>";
    
    if ($count > 0) {
        // 2. Lister les premiers agents
        $stmt = $pdo->query("
            SELECT ID, post_title, post_name, post_date 
            FROM wp_posts 
            WHERE post_type = 'houzez_agent' 
            AND post_status = 'publish' 
            ORDER BY ID ASC 
            LIMIT 10
        ");
        $agents = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<h3>🎯 Agents disponibles:</h3>";
        echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 10px 0;'>";
        echo "<tr style='background: #f0f0f0;'><th>ID</th><th>Nom</th><th>Slug</th><th>Date</th><th>Test View</th></tr>";
        
        foreach ($agents as $agent) {
            $id = $agent['ID'];
            $name = htmlspecialchars($agent['post_title']);
            $slug = htmlspecialchars($agent['post_name']);
            $date = $agent['post_date'];
            
            echo "<tr>";
            echo "<td><strong>$id</strong></td>";
            echo "<td>$name</td>";
            echo "<td>$slug</td>";
            echo "<td>$date</td>";
            echo "<td>";
            echo "<a href='https://crm.rebencia.com/index.php/agents/view/$id' target='_blank' style='color: blue;'>🔗 Tester</a> | ";
            echo "<a href='https://crm.rebencia.com/agents/view/$id' target='_blank' style='color: green;'>🔗 Sans index.php</a>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // 3. Test du premier agent
        $first_agent_id = $agents[0]['ID'];
        echo "<h3>🧪 Test détaillé de l'agent #$first_agent_id:</h3>";
        
        // Requête pour récupérer toutes les meta-données
        $stmt = $pdo->prepare("
            SELECT p.ID, p.post_title, pm.meta_key, pm.meta_value
            FROM wp_posts p
            LEFT JOIN wp_postmeta pm ON p.ID = pm.post_id
            WHERE p.ID = ? AND p.post_type = 'houzez_agent'
            ORDER BY pm.meta_key
        ");
        $stmt->execute([$first_agent_id]);
        $metas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if ($metas) {
            echo "<details><summary>📋 Méta-données de l'agent (cliquer pour voir)</summary>";
            echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
            echo "<tr><th>Meta Key</th><th>Meta Value</th></tr>";
            foreach ($metas as $meta) {
                if (!empty($meta['meta_key'])) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($meta['meta_key']) . "</td>";
                    echo "<td>" . htmlspecialchars(substr($meta['meta_value'], 0, 100)) . "</td>";
                    echo "</tr>";
                }
            }
            echo "</table>";
            echo "</details>";
        }
        
        echo "<h3>🚀 Actions rapides:</h3>";
        echo "<p>";
        echo "🔵 <a href='https://crm.rebencia.com/index.php/agents/view/$first_agent_id' target='_blank'>Tester avec le premier agent (ID: $first_agent_id)</a><br>";
        echo "🟢 <a href='https://crm.rebencia.com/index.php/agents' target='_blank'>Page principale des agents</a><br>";
        echo "🟠 <a href='https://crm.rebencia.com/agents' target='_blank'>Page agents sans index.php</a>";
        echo "</p>";
        
    } else {
        echo "<p style='color: red;'>❌ Aucun agent trouvé dans la base de données.</p>";
        echo "<p>Vérifiez que:</p>";
        echo "<ul>";
        echo "<li>La base de données 'wordpress' existe</li>";
        echo "<li>Les tables wp_posts contiennent des agents HOUZEZ</li>";
        echo "<li>Le plugin HOUZEZ est bien installé</li>";
        echo "</ul>";
    }
    
} catch (Exception $e) {
    echo "<div style='background: #ffebee; border: 1px solid #f44336; padding: 10px; margin: 10px 0;'>";
    echo "<h3 style='color: #d32f2f;'>❌ Erreur:</h3>";
    echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>Fichier:</strong> " . $e->getFile() . " ligne " . $e->getLine() . "</p>";
    echo "</div>";
    
    echo "<h3>💡 Solutions possibles:</h3>";
    echo "<ol>";
    echo "<li>Vérifier que MAMP est démarré</li>";
    echo "<li>Vérifier les paramètres de connexion MySQL</li>";
    echo "<li>Vérifier que la base 'wordpress' existe</li>";
    echo "</ol>";
}

echo "<hr>";
echo "<p><small>Generated: " . date('Y-m-d H:i:s') . " | <a href='https://crm.rebencia.com/agents'>← Retour aux agents</a></small></p>";
?>
