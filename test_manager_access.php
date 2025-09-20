<?php
// Simuler l'accès manager à la page agents
session_start();

// Trouver un manager dans la base pour simuler la session
try {
    $pdo = new PDO('mysql:host=localhost;dbname=rebencia_RebenciaBD;charset=utf8', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Récupérer un manager existant
    $stmt = $pdo->query("
        SELECT ID, display_name, user_email, agency_id, agency_name 
        FROM wp_Hrg8P_crm_agents 
        WHERE user_role = 'houzez_manager' 
        AND agency_id IS NOT NULL 
        LIMIT 1
    ");
    $manager = $stmt->fetch(PDO::FETCH_OBJ);
    
    if ($manager) {
        echo "<h2>🧪 Simulation de l'accès manager</h2>\n";
        echo "<p><strong>Manager simulé :</strong></p>\n";
        echo "<ul>\n";
        echo "<li>ID: {$manager->ID}</li>\n";
        echo "<li>Nom: {$manager->display_name}</li>\n";
        echo "<li>Email: {$manager->user_email}</li>\n";
        echo "<li>Agency ID: {$manager->agency_id}</li>\n";
        echo "<li>Agency Name: {$manager->agency_name}</li>\n";
        echo "</ul>\n";
        
        // Simuler les données de session
        $_SESSION['UserID'] = $manager->ID;
        $_SESSION['role'] = 'manager';
        
        echo "<p><a href='agent' target='_blank'>👉 Tester l'accès à la page agents</a></p>\n";
        
        // Tester directement notre méthode
        echo "<hr>\n";
        echo "<h3>Test direct de get_manager_team_agents({$manager->agency_id})</h3>\n";
        
        $sql = "
            SELECT 
                a.ID as user_id,
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
            ORDER BY a.display_name ASC
        ";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':agency_id', $manager->agency_id);
        $stmt->execute();
        $team = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        echo "<p><strong>Équipe trouvée : " . count($team) . " membres</strong></p>\n";
        
        if (!empty($team)) {
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>\n";
            echo "<tr style='background: #f5f5f5;'><th>ID</th><th>Nom</th><th>Rôle</th><th>Agence</th><th>Avatar</th><th>Properties</th></tr>\n";
            
            foreach ($team as $member) {
                $role_class = $member->user_role === 'houzez_manager' ? 'style="background: #e3f2fd;"' : '';
                echo "<tr $role_class>";
                echo "<td>{$member->user_id}</td>";
                echo "<td>{$member->display_name}</td>";
                echo "<td>" . str_replace('houzez_', '', $member->user_role) . "</td>";
                echo "<td>{$member->agency_name}</td>";
                echo "<td>" . ($member->avatar_url ? '✅' : '❌') . "</td>";
                echo "<td>" . ($member->property_count ?? '0') . "</td>";
                echo "</tr>\n";
            }
            echo "</table>\n";
            
            // Vérifier si le manager se voit lui-même
            $manager_in_team = false;
            foreach ($team as $member) {
                if ($member->user_id == $manager->ID) {
                    $manager_in_team = true;
                    break;
                }
            }
            
            echo "<p><strong>Le manager se voit-il dans la liste ? " . ($manager_in_team ? '✅ OUI' : '❌ NON') . "</strong></p>\n";
        }
        
    } else {
        echo "<p style='color: red;'>❌ Aucun manager trouvé avec une agence assignée</p>\n";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Erreur : " . $e->getMessage() . "</p>\n";
}
?>

<style>
table { margin: 10px 0; }
th, td { padding: 8px; text-align: left; }
</style>
