<?php
// Chargement de la configuration CodeIgniter
define('BASEPATH', 'application/');
require_once 'application/config/database.php';

// Configuration de la base de données
$host = $db['default']['hostname'];
$username = $db['default']['username'];
$password = $db['default']['password'];
$database = $db['default']['database'];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h1>🔍 Vérification des User IDs - CRM Rebencia</h1>";
    echo "<p><strong>Date:</strong> " . date('d/m/Y H:i:s') . "</p>";
    echo "<hr>";
    
    // 1. Vérification utilisateur Montasar Barkouti
    echo "<h2>👤 Vérification Montasar Barkouti (User ID: 7)</h2>";
    $stmt = $pdo->prepare("
        SELECT 
            u.ID as wordpress_user_id,
            u.user_login,
            u.display_name,
            a.agent_post_id,
            a.agency_id,
            a.agent_name,
            a.agency_name,
            CASE 
                WHEN a.agent_post_id IS NULL THEN 'PAS DE PROFIL AGENT'
                WHEN a.agent_post_id = u.ID THEN 'DUPLICATION DÉTECTÉE'
                ELSE 'PROFIL AGENT VALIDE'
            END as diagnostic
        FROM wp_Hrg8P_users u
        LEFT JOIN wp_Hrg8P_crm_agents a ON u.ID = a.user_id
        WHERE u.ID = 7
    ");
    $stmt->execute();
    $montasar = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($montasar) {
        echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "<strong>WordPress User ID:</strong> " . $montasar['wordpress_user_id'] . "<br>";
        echo "<strong>Login:</strong> " . $montasar['user_login'] . "<br>";
        echo "<strong>Nom affiché:</strong> " . $montasar['display_name'] . "<br>";
        echo "<strong>Agent Post ID:</strong> " . ($montasar['agent_post_id'] ?: 'NULL') . "<br>";
        echo "<strong>Agency ID:</strong> " . ($montasar['agency_id'] ?: 'NULL') . "<br>";
        echo "<strong>Agence:</strong> " . ($montasar['agency_name'] ?: 'Non définie') . "<br>";
        
        $color = ($montasar['diagnostic'] === 'PAS DE PROFIL AGENT') ? '#28a745' : 
                (($montasar['diagnostic'] === 'DUPLICATION DÉTECTÉE') ? '#dc3545' : '#007bff');
        echo "<strong>Diagnostic:</strong> <span style='color: $color; font-weight: bold;'>" . $montasar['diagnostic'] . "</span><br>";
        echo "</div>";
    }
    
    // 2. Vue d'ensemble des duplications
    echo "<h2>📊 Statistiques générales</h2>";
    $stmt = $pdo->prepare("
        SELECT 
            COUNT(*) as total_users,
            COUNT(a.agent_post_id) as users_with_agent_profile,
            COUNT(CASE WHEN a.agent_post_id = u.ID THEN 1 END) as duplications_detected,
            COUNT(CASE WHEN a.agent_post_id IS NULL THEN 1 END) as no_agent_profile
        FROM wp_Hrg8P_users u
        LEFT JOIN wp_Hrg8P_crm_agents a ON u.ID = a.user_id
        WHERE u.ID IN (SELECT DISTINCT user_id FROM wp_Hrg8P_usermeta WHERE meta_key LIKE '%capabilities%')
    ");
    $stmt->execute();
    $stats = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "<div style='background: #e9ecef; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<strong>Total utilisateurs:</strong> " . $stats['total_users'] . "<br>";
    echo "<strong>Avec profil agent:</strong> " . $stats['users_with_agent_profile'] . "<br>";
    echo "<strong>Sans profil agent:</strong> " . $stats['no_agent_profile'] . "<br>";
    echo "<strong>Duplications détectées:</strong> <span style='color: " . 
         ($stats['duplications_detected'] > 0 ? '#dc3545' : '#28a745') . "; font-weight: bold;'>" . 
         $stats['duplications_detected'] . "</span><br>";
    echo "</div>";
    
    // 3. Détail agence Ben arous
    echo "<h2>🏢 Agence Ben arous (ID: 18907)</h2>";
    $stmt = $pdo->prepare("
        SELECT 
            a.user_id,
            a.agent_post_id,
            a.agent_name,
            a.agent_email,
            u.user_login,
            CASE 
                WHEN a.agent_post_id IS NULL THEN 'Pas de profil agent'
                WHEN a.agent_post_id = a.user_id THEN 'Duplication détectée'
                ELSE 'Profil agent valide'
            END as status
        FROM wp_Hrg8P_crm_agents a
        LEFT JOIN wp_Hrg8P_users u ON a.user_id = u.ID
        WHERE a.agency_id = 18907
        ORDER BY a.user_id
    ");
    $stmt->execute();
    $agents_ben_arous = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($agents_ben_arous) {
        echo "<table style='width: 100%; border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr style='background: #007bff; color: white;'>";
        echo "<th style='padding: 8px; border: 1px solid #ddd;'>User ID</th>";
        echo "<th style='padding: 8px; border: 1px solid #ddd;'>Agent Post ID</th>";
        echo "<th style='padding: 8px; border: 1px solid #ddd;'>Login</th>";
        echo "<th style='padding: 8px; border: 1px solid #ddd;'>Nom Agent</th>";
        echo "<th style='padding: 8px; border: 1px solid #ddd;'>Status</th>";
        echo "</tr>";
        
        foreach ($agents_ben_arous as $agent) {
            $bg_color = ($agent['status'] === 'Duplication détectée') ? '#ffebee' : 
                       (($agent['status'] === 'Pas de profil agent') ? '#fff3e0' : '#e8f5e8');
            $text_color = ($agent['status'] === 'Duplication détectée') ? '#c62828' : 
                         (($agent['status'] === 'Pas de profil agent') ? '#ef6c00' : '#2e7d32');
            
            echo "<tr style='background: $bg_color;'>";
            echo "<td style='padding: 8px; border: 1px solid #ddd;'>" . $agent['user_id'] . "</td>";
            echo "<td style='padding: 8px; border: 1px solid #ddd;'>" . ($agent['agent_post_id'] ?: 'NULL') . "</td>";
            echo "<td style='padding: 8px; border: 1px solid #ddd;'>" . $agent['user_login'] . "</td>";
            echo "<td style='padding: 8px; border: 1px solid #ddd;'>" . $agent['agent_name'] . "</td>";
            echo "<td style='padding: 8px; border: 1px solid #ddd; color: $text_color; font-weight: bold;'>" . $agent['status'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // 4. Requêtes SQL brutes
    echo "<h2>📝 Requêtes SQL pour vérification manuelle</h2>";
    echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<h4>Vérification Montasar Barkouti:</h4>";
    echo "<code style='display: block; background: #e9ecef; padding: 10px; border-radius: 3px;'>";
    echo "SELECT u.ID, u.user_login, a.agent_post_id, a.agency_id<br>";
    echo "FROM wp_Hrg8P_users u<br>";
    echo "LEFT JOIN wp_Hrg8P_crm_agents a ON u.ID = a.user_id<br>";
    echo "WHERE u.ID = 7;";
    echo "</code>";
    
    echo "<h4>Statistiques globales:</h4>";
    echo "<code style='display: block; background: #e9ecef; padding: 10px; border-radius: 3px;'>";
    echo "SELECT <br>";
    echo "&nbsp;&nbsp;COUNT(*) as total_users,<br>";
    echo "&nbsp;&nbsp;COUNT(a.agent_post_id) as with_agent_profile,<br>";
    echo "&nbsp;&nbsp;COUNT(CASE WHEN a.agent_post_id = u.ID THEN 1 END) as duplications<br>";
    echo "FROM wp_Hrg8P_users u<br>";
    echo "LEFT JOIN wp_Hrg8P_crm_agents a ON u.ID = a.user_id;";
    echo "</code>";
    echo "</div>";
    
    echo "<div style='margin-top: 20px; padding: 15px; background: #d4edda; border-radius: 5px;'>";
    echo "<h3>✅ Résumé de la correction</h3>";
    echo "<p><strong>Avant:</strong> user_post_id dupliquait user_id quand pas de profil agent</p>";
    echo "<p><strong>Après:</strong> user_post_id = NULL quand pas de profil agent</p>";
    echo "<p><strong>Affichage:</strong> 'Pas de profil agent' au lieu de la duplication</p>";
    echo "</div>";
    
} catch (PDOException $e) {
    echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; color: #721c24;'>";
    echo "<h3>❌ Erreur de connexion à la base de données</h3>";
    echo "<p>Erreur: " . $e->getMessage() . "</p>";
    echo "</div>";
}
?>
