<?php
session_start();

echo "<h2>🔄 Force la mise à jour de la session</h2>";

// Afficher la session actuelle
echo "<h3>Session actuelle:</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// Chargement de la configuration CodeIgniter
define('BASEPATH', 'application/');
require_once 'application/config/database.php';

$host = $db['default']['hostname'];
$username = $db['default']['username'];
$password = $db['default']['password'];
$database = $db['default']['database'];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if (isset($_SESSION['vendor_id'])) {
        $user_id = $_SESSION['vendor_id'];
        
        // Récupérer les vraies données depuis wp_Hrg8P_crm_agents
        $stmt = $pdo->prepare("
            SELECT agent_post_id, agency_id 
            FROM wp_Hrg8P_crm_agents 
            WHERE user_id = ?
        ");
        $stmt->execute([$user_id]);
        $agent_data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "<h3>🔍 Données trouvées dans wp_Hrg8P_crm_agents:</h3>";
        echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 5px;'>";
        if ($agent_data) {
            echo "<strong>Agent Post ID trouvé:</strong> " . ($agent_data['agent_post_id'] ?: 'NULL') . "<br>";
            echo "<strong>Agency ID trouvé:</strong> " . ($agent_data['agency_id'] ?: 'NULL') . "<br>";
            
            // Mettre à jour la session avec les vraies données
            if ($agent_data['agent_post_id']) {
                $_SESSION['user_post_id'] = $agent_data['agent_post_id'];
                echo "<br>✅ <strong>Session mise à jour:</strong> user_post_id = " . $agent_data['agent_post_id'];
            }
            if ($agent_data['agency_id']) {
                $_SESSION['agency_id'] = $agent_data['agency_id'];
                echo "<br>✅ <strong>Session mise à jour:</strong> agency_id = " . $agent_data['agency_id'];
            }
        } else {
            echo "❌ Aucune donnée agent trouvée pour user_id: $user_id";
        }
        echo "</div>";
        
        echo "<h3>Session après mise à jour:</h3>";
        echo "<pre>";
        print_r($_SESSION);
        echo "</pre>";
        
    } else {
        echo "<p style='color: red;'>❌ Utilisateur non connecté</p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>Erreur: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<div style='margin-top: 20px;'>";
echo "<a href='index.php/dashboard' style='padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin-right: 10px;'>🏠 Retour Dashboard</a>";
echo "<a href='index.php/login/logout' style='padding: 10px 20px; background: #dc3545; color: white; text-decoration: none; border-radius: 5px; margin-right: 10px;'>🚪 Se déconnecter</a>";
echo "<a href='index.php/login' style='padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 5px;'>🔑 Se reconnecter</a>";
echo "</div>";
?>
