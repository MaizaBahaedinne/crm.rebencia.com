<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Test Agents Base de Données</h1>";

try {
    // Connexion directe à la base de données
    $pdo = new PDO("mysql:host=localhost;dbname=crm_rebencia;charset=utf8", "root", "root");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connexion réussie<br><br>";
    
    // Récupérer les agents depuis wp_posts
    echo "<h2>1. Agents dans wp_posts</h2>";
    $stmt = $pdo->prepare("
        SELECT 
            p.ID as agent_id,
            p.post_title as agent_name,
            p.post_status,
            p.post_date,
            (SELECT meta_value FROM wp_postmeta WHERE post_id = p.ID AND meta_key = 'fave_agent_email' LIMIT 1) as agent_email
        FROM wp_posts p
        WHERE p.post_type = 'houzez_agent' 
        AND p.post_status = 'publish'
        ORDER BY p.ID
        LIMIT 10
    ");
    $stmt->execute();
    $agents = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($agents) {
        echo "✅ " . count($agents) . " agents trouvés<br><br>";
        
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Nom</th><th>Email</th><th>Status</th><th>Date</th><th>Test</th></tr>";
        
        foreach ($agents as $agent) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($agent['agent_id']) . "</td>";
            echo "<td>" . htmlspecialchars($agent['agent_name']) . "</td>";
            echo "<td>" . htmlspecialchars($agent['agent_email'] ?? 'N/A') . "</td>";
            echo "<td>" . htmlspecialchars($agent['post_status']) . "</td>";
            echo "<td>" . htmlspecialchars($agent['post_date']) . "</td>";
            echo "<td><a href='https://crm.rebencia.com/index.php/agents/view/" . $agent['agent_id'] . "' target='_blank'>Tester</a></td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Test avec le premier agent trouvé
        $first_agent_id = $agents[0]['agent_id'];
        echo "<h2>2. Test avec l'agent ID: $first_agent_id</h2>";
        echo "<p><a href='https://crm.rebencia.com/index.php/agents/view/$first_agent_id' target='_blank'>🔗 Tester l'agent $first_agent_id</a></p>";
        
    } else {
        echo "❌ Aucun agent trouvé<br>";
    }
    
    // Vérifier aussi les méta-données importantes
    echo "<h2>3. Méta-données d'agents</h2>";
    $stmt = $pdo->prepare("
        SELECT 
            meta_key,
            COUNT(*) as count
        FROM wp_postmeta pm
        JOIN wp_posts p ON pm.post_id = p.ID
        WHERE p.post_type = 'houzez_agent'
        AND meta_key LIKE 'fave_agent_%'
        GROUP BY meta_key
        ORDER BY count DESC
    ");
    $stmt->execute();
    $metas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($metas) {
        echo "<ul>";
        foreach ($metas as $meta) {
            echo "<li><strong>" . htmlspecialchars($meta['meta_key']) . "</strong>: " . $meta['count'] . " entrées</li>";
        }
        echo "</ul>";
    }
    
} catch (PDOException $e) {
    echo "❌ Erreur: " . $e->getMessage();
}
?>
