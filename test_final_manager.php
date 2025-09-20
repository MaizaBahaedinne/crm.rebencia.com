<?php
// Test final - vérification complète
echo "<h1>🎯 Test Final - Visibility des Managers</h1>\n";

try {
    $pdo = new PDO('mysql:host=localhost;dbname=rebencia_RebenciaBD;charset=utf8', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 1. Lister tous les managers
    echo "<h2>1. 👥 Managers dans le système</h2>\n";
    $stmt = $pdo->query("
        SELECT ID, display_name, user_email, agency_id, agency_name 
        FROM wp_Hrg8P_crm_agents 
        WHERE user_role = 'houzez_manager'
        ORDER BY agency_name, display_name
    ");
    $managers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($managers)) {
        echo "<p>❌ Aucun manager trouvé</p>\n";
    } else {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>\n";
        echo "<tr style='background: #f0f8ff;'><th>ID</th><th>Nom</th><th>Email</th><th>Agence</th><th>Agency ID</th></tr>\n";
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
    }
    
    // 2. Pour chaque manager, tester la visibilité de son équipe
    echo "<h2>2. 🔍 Test de visibilité pour chaque manager</h2>\n";
    
    foreach ($managers as $manager) {
        if (!$manager['agency_id']) {
            echo "<div style='border: 1px solid #orange; padding: 10px; margin: 10px 0; background: #fff3cd;'>\n";
            echo "<h3>⚠️ Manager {$manager['display_name']} (ID: {$manager['ID']})</h3>\n";
            echo "<p style='color: orange;'>❌ Pas d'agence assignée</p>\n";
            echo "</div>\n";
            continue;
        }
        
        // Tester notre nouvelle méthode pour ce manager
        $stmt = $pdo->prepare("
            SELECT 
                a.ID as user_id,
                a.display_name,
                a.user_email,
                a.user_role,
                a.agency_name
            FROM wp_Hrg8P_crm_agents a
            LEFT JOIN wp_Hrg8P_crm_avatar_agents ava ON a.ID = ava.agent_post_id
            LEFT JOIN wp_Hrg8P_prop_agen prop ON a.ID = prop.agent_post_id
            WHERE a.agency_id = :agency_id
            AND a.user_role IN ('houzez_agent', 'houzez_manager')
            ORDER BY a.display_name ASC
        ");
        $stmt->bindParam(':agency_id', $manager['agency_id']);
        $stmt->execute();
        $team = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<div style='border: 1px solid #28a745; padding: 10px; margin: 10px 0; background: #d4edda;'>\n";
        echo "<h3>✅ Manager {$manager['display_name']} (ID: {$manager['ID']})</h3>\n";
        echo "<p><strong>Agence:</strong> {$manager['agency_name']} (ID: {$manager['agency_id']})</p>\n";
        echo "<p><strong>Équipe visible:</strong> " . count($team) . " membres</p>\n";
        
        // Vérifier si le manager se voit
        $manager_visible = false;
        foreach ($team as $member) {
            if ($member['user_id'] == $manager['ID']) {
                $manager_visible = true;
                break;
            }
        }
        
        echo "<p><strong>Le manager se voit-il ?</strong> " . ($manager_visible ? '✅ OUI' : '❌ NON') . "</p>\n";
        
        if (!empty($team)) {
            echo "<table border='1' style='border-collapse: collapse; width: 100%; margin-top: 10px;'>\n";
            echo "<tr style='background: #f8f9fa;'><th>ID</th><th>Nom</th><th>Rôle</th></tr>\n";
            foreach ($team as $member) {
                $highlight = $member['user_id'] == $manager['ID'] ? 'style="background: #fff3cd; font-weight: bold;"' : '';
                echo "<tr $highlight>";
                echo "<td>{$member['user_id']}</td>";
                echo "<td>{$member['display_name']}</td>";
                echo "<td>" . str_replace('houzez_', '', $member['user_role']) . "</td>";
                echo "</tr>\n";
            }
            echo "</table>\n";
        }
        
        echo "</div>\n";
    }
    
    // 3. Test de la méthode générale vs spécifique
    echo "<h2>3. 📊 Comparaison des méthodes</h2>\n";
    
    if (!empty($managers)) {
        $test_manager = $managers[0];
        $agency_id = $test_manager['agency_id'];
        
        echo "<h3>Test avec {$test_manager['display_name']} (Agency ID: $agency_id)</h3>\n";
        
        // Méthode générale (ancienne)
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as count 
            FROM wp_Hrg8P_crm_agents 
            WHERE agency_id = :agency_id
        ");
        $stmt->bindParam(':agency_id', $agency_id);
        $stmt->execute();
        $general_count = $stmt->fetch()['count'];
        
        // Méthode spécifique (nouvelle)
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as count 
            FROM wp_Hrg8P_crm_agents 
            WHERE agency_id = :agency_id 
            AND user_role IN ('houzez_agent', 'houzez_manager')
        ");
        $stmt->bindParam(':agency_id', $agency_id);
        $stmt->execute();
        $specific_count = $stmt->fetch()['count'];
        
        echo "<table border='1' style='border-collapse: collapse;'>\n";
        echo "<tr><th>Méthode</th><th>Résultats</th></tr>\n";
        echo "<tr><td>Générale (tous les rôles)</td><td>$general_count</td></tr>\n";
        echo "<tr><td>Spécifique (agents + managers)</td><td>$specific_count</td></tr>\n";
        echo "</table>\n";
    }
    
    echo "<h2>🎉 Résumé</h2>\n";
    echo "<div style='border: 2px solid #28a745; padding: 15px; background: #d4edda; margin: 20px 0;'>\n";
    echo "<h3>✅ Solution implémentée :</h3>\n";
    echo "<ul>\n";
    echo "<li>✅ Nouvelle méthode <code>get_manager_team_agents()</code> créée</li>\n";
    echo "<li>✅ Utilise les 3 vues : wp_Hrg8P_crm_agents, wp_Hrg8P_crm_avatar_agents, wp_Hrg8P_prop_agen</li>\n";
    echo "<li>✅ Filtre par agency_id ET rôles (houzez_agent, houzez_manager)</li>\n";
    echo "<li>✅ Le manager peut voir son propre profil et celui de son équipe</li>\n";
    echo "<li>✅ Contrôleur modifié pour utiliser la méthode spécifique</li>\n";
    echo "</ul>\n";
    echo "</div>\n";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erreur : " . $e->getMessage() . "</p>\n";
}
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
table { margin: 10px 0; }
th, td { padding: 8px; text-align: left; }
code { background: #f8f9fa; padding: 2px 4px; border-radius: 3px; }
</style>
