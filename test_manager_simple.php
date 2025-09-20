<?php
// Test simple des vues
try {
    $pdo = new PDO('mysql:host=localhost;dbname=rebencia_RebenciaBD;charset=utf8', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Test des vues pour les managers</h2>\n";
    
    // Test 1: Vérifier que les vues existent
    echo "<h3>1. Vérification des vues :</h3>\n";
    
    $views = ['wp_Hrg8P_crm_agents', 'wp_Hrg8P_crm_avatar_agents', 'wp_Hrg8P_prop_agen'];
    foreach ($views as $view) {
        try {
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM $view LIMIT 1");
            $result = $stmt->fetch();
            echo "✅ Vue $view existe (contient {$result['count']} lignes)<br>\n";
        } catch (Exception $e) {
            echo "❌ Vue $view : " . $e->getMessage() . "<br>\n";
        }
    }
    
    // Test 2: Managers existants
    echo "<h3>2. Managers dans le système :</h3>\n";
    $stmt = $pdo->query("
        SELECT ID, display_name, user_email, user_role, agency_name, agency_id 
        FROM wp_Hrg8P_crm_agents 
        WHERE user_role = 'houzez_manager'
        ORDER BY display_name
    ");
    $managers = $stmt->fetchAll();
    
    if (empty($managers)) {
        echo "<p>❌ Aucun manager trouvé dans le système</p>\n";
    } else {
        echo "<table border='1' style='border-collapse: collapse;'>\n";
        echo "<tr><th>ID</th><th>Nom</th><th>Email</th><th>Agence</th><th>Agency ID</th></tr>\n";
        foreach ($managers as $manager) {
            echo "<tr>";
            echo "<td>{$manager['ID']}</td>";
            echo "<td>{$manager['display_name']}</td>";
            echo "<td>{$manager['user_email']}</td>";
            echo "<td>{$manager['agency_name']}</td>";
            echo "<td>{$manager['agency_id']}</td>";
            echo "</tr>\n";
        }
        echo "</table>\n";
        
        // Test 3: Pour le premier manager, voir son équipe
        if (count($managers) > 0) {
            $test_manager = $managers[0];
            $agency_id = $test_manager['agency_id'];
            
            echo "<h3>3. Équipe du manager {$test_manager['display_name']} (agency_id: $agency_id) :</h3>\n";
            
            $stmt = $pdo->prepare("
                SELECT 
                    a.ID, 
                    a.display_name, 
                    a.user_email, 
                    a.user_role, 
                    a.agency_name,
                    ava.avatar_url,
                    prop.property_count
                FROM wp_Hrg8P_crm_agents a
                LEFT JOIN wp_Hrg8P_crm_avatar_agents ava ON a.ID = ava.agent_post_id
                LEFT JOIN wp_Hrg8P_prop_agen prop ON a.ID = prop.agent_post_id
                WHERE a.agency_id = :agency_id 
                AND a.user_role IN ('houzez_agent', 'houzez_manager')
                ORDER BY a.display_name
            ");
            $stmt->bindParam(':agency_id', $agency_id);
            $stmt->execute();
            $team = $stmt->fetchAll();
            
            echo "<table border='1' style='border-collapse: collapse;'>\n";
            echo "<tr><th>ID</th><th>Nom</th><th>Email</th><th>Rôle</th><th>Avatar</th><th>Properties</th></tr>\n";
            foreach ($team as $member) {
                echo "<tr>";
                echo "<td>{$member['ID']}</td>";
                echo "<td>{$member['display_name']}</td>";
                echo "<td>{$member['user_email']}</td>";
                echo "<td>" . str_replace('houzez_', '', $member['user_role']) . "</td>";
                echo "<td>" . ($member['avatar_url'] ? '✅' : '❌') . "</td>";
                echo "<td>" . ($member['property_count'] ?? '0') . "</td>";
                echo "</tr>\n";
            }
            echo "</table>\n";
            echo "<p><strong>Nombre total de membres (incluant le manager) : " . count($team) . "</strong></p>\n";
        }
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Erreur de connexion : " . $e->getMessage() . "</p>\n";
}
?>
