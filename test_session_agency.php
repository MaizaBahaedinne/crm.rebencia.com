<?php
// Test de l'agency_id en session
session_start();

echo "<h1>🔍 Test de l'agency_id en session</h1>\n";

// Simuler une connexion manager pour tester
try {
    $pdo = new PDO('mysql:host=localhost;dbname=rebencia_RebenciaBD;charset=utf8', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Récupérer un manager avec agency_id
    $stmt = $pdo->query("
        SELECT ID, display_name, user_email, agency_id, agency_name 
        FROM wp_Hrg8P_crm_agents 
        WHERE user_role = 'houzez_manager' 
        AND agency_id IS NOT NULL 
        LIMIT 1
    ");
    $manager = $stmt->fetch(PDO::FETCH_OBJ);
    
    if ($manager) {
        echo "<h2>📋 Manager trouvé :</h2>\n";
        echo "<ul>\n";
        echo "<li>ID: {$manager->ID}</li>\n";
        echo "<li>Nom: {$manager->display_name}</li>\n";
        echo "<li>Agency ID: {$manager->agency_id}</li>\n";
        echo "<li>Agency Name: {$manager->agency_name}</li>\n";
        echo "</ul>\n";
        
        // Simuler les données de session comme dans Login.php
        $_SESSION['isLoggedIn'] = TRUE;
        $_SESSION['UserID'] = $manager->ID;
        $_SESSION['wp_id'] = $manager->ID;
        $_SESSION['role'] = 'manager';
        $_SESSION['agency_id'] = $manager->agency_id;
        $_SESSION['name'] = $manager->display_name;
        
        echo "<h2>✅ Session simulée :</h2>\n";
        echo "<table border='1' style='border-collapse: collapse;'>\n";
        echo "<tr><th>Clé</th><th>Valeur</th></tr>\n";
        echo "<tr><td>UserID</td><td>" . $_SESSION['UserID'] . "</td></tr>\n";
        echo "<tr><td>wp_id</td><td>" . $_SESSION['wp_id'] . "</td></tr>\n";
        echo "<tr><td>role</td><td>" . $_SESSION['role'] . "</td></tr>\n";
        echo "<tr><td>agency_id</td><td>" . $_SESSION['agency_id'] . "</td></tr>\n";
        echo "<tr><td>name</td><td>" . $_SESSION['name'] . "</td></tr>\n";
        echo "</table>\n";
        
        // Test de récupération comme dans le contrôleur
        $agency_id_from_session = $_SESSION['agency_id'] ?? null;
        
        echo "<h2>🎯 Test de récupération :</h2>\n";
        echo "<p><strong>Agency ID récupéré :</strong> ";
        if ($agency_id_from_session) {
            echo "<span style='color: green;'>✅ $agency_id_from_session</span></p>\n";
        } else {
            echo "<span style='color: red;'>❌ Null ou vide</span></p>\n";
        }
        
        // Test de la requête avec cet agency_id
        if ($agency_id_from_session) {
            echo "<h2>🧪 Test de la requête avec agency_id = $agency_id_from_session :</h2>\n";
            
            $sql = "
                SELECT 
                    a.ID as user_id,
                    a.display_name,
                    a.user_role,
                    a.agency_name
                FROM wp_Hrg8P_crm_agents a
                WHERE a.agency_id = :agency_id
                AND a.user_role IN ('houzez_agent', 'houzez_manager')
                ORDER BY a.display_name ASC
            ";
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':agency_id', $agency_id_from_session);
            $stmt->execute();
            $team = $stmt->fetchAll(PDO::FETCH_OBJ);
            
            echo "<p><strong>Équipe trouvée :</strong> " . count($team) . " membres</p>\n";
            
            if (!empty($team)) {
                echo "<table border='1' style='border-collapse: collapse; width: 100%;'>\n";
                echo "<tr style='background: #f5f5f5;'><th>ID</th><th>Nom</th><th>Rôle</th><th>Agence</th></tr>\n";
                
                foreach ($team as $member) {
                    $highlight = $member->user_id == $manager->ID ? 'style="background: #e3f2fd; font-weight: bold;"' : '';
                    echo "<tr $highlight>";
                    echo "<td>{$member->user_id}</td>";
                    echo "<td>{$member->display_name}</td>";
                    echo "<td>" . str_replace('houzez_', '', $member->user_role) . "</td>";
                    echo "<td>{$member->agency_name}</td>";
                    echo "</tr>\n";
                }
                echo "</table>\n";
            }
        }
        
        echo "<h2>🚀 Avantages de l'utilisation de la session :</h2>\n";
        echo "<div style='border: 1px solid #28a745; padding: 15px; background: #d4edda;'>\n";
        echo "<ul>\n";
        echo "<li>✅ <strong>Performance :</strong> Pas de requête supplémentaire pour récupérer l'agency_id</li>\n";
        echo "<li>✅ <strong>Simplicité :</strong> Directement accessible via \$this->session->userdata('agency_id')</li>\n";
        echo "<li>✅ <strong>Cohérence :</strong> L'agency_id est déjà récupéré lors du login</li>\n";
        echo "<li>✅ <strong>Fiabilité :</strong> Les données de session persistent pendant toute la session</li>\n";
        echo "</ul>\n";
        echo "</div>\n";
        
        echo "<p><a href='agent' target='_blank' style='padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;'>👉 Tester la page agents</a></p>\n";
        
    } else {
        echo "<p style='color: red;'>❌ Aucun manager trouvé avec une agence assignée</p>\n";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Erreur : " . $e->getMessage() . "</p>\n";
}
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
table { margin: 10px 0; }
th, td { padding: 8px; text-align: left; }
</style>
