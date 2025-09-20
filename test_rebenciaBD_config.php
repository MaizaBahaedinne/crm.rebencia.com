<?php
// Test de connexion rebenciaBD
echo "<h1>🔧 Test de la configuration rebenciaBD</h1>\n";

// Simuler le chargement de CodeIgniter
define('BASEPATH', true);
define('ENVIRONMENT', 'development');

// Charger la configuration
include 'application/config/database.php';

echo "<h2>📋 Configurations disponibles :</h2>\n";
echo "<table border='1' style='border-collapse: collapse;'>\n";
echo "<tr style='background: #f8f9fa;'><th>Groupe</th><th>Base de données</th><th>Statut</th></tr>\n";

$status_checks = [];

foreach ($db as $group => $config) {
    echo "<tr>";
    echo "<td><strong>$group</strong></td>";
    echo "<td>{$config['database']}</td>";
    
    // Test de connexion
    try {
        $pdo = new PDO(
            "mysql:host={$config['hostname']};dbname={$config['database']};charset=utf8",
            $config['username'],
            $config['password']
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "<td style='color: green;'>✅ Connexion OK</td>";
        $status_checks[$group] = true;
    } catch (Exception $e) {
        echo "<td style='color: red;'>❌ Erreur: " . $e->getMessage() . "</td>";
        $status_checks[$group] = false;
    }
    
    echo "</tr>\n";
}
echo "</table>\n";

// Test spécifique de rebenciaBD
if (isset($db['rebenciaBD'])) {
    echo "<h2>🎯 Test spécifique rebenciaBD :</h2>\n";
    
    if ($status_checks['rebenciaBD']) {
        echo "<div style='border: 2px solid #28a745; padding: 15px; background: #d4edda;'>\n";
        echo "<h3>✅ Configuration rebenciaBD fonctionnelle !</h3>\n";
        
        // Test d'accès aux vues
        try {
            $config = $db['rebenciaBD'];
            $pdo = new PDO(
                "mysql:host={$config['hostname']};dbname={$config['database']};charset=utf8",
                $config['username'],
                $config['password']
            );
            
            echo "<h4>Test des vues utilisées :</h4>\n";
            $views = ['wp_Hrg8P_crm_agents', 'wp_Hrg8P_crm_avatar_agents', 'wp_Hrg8P_prop_agen'];
            
            echo "<ul>\n";
            foreach ($views as $view) {
                try {
                    $stmt = $pdo->query("SELECT COUNT(*) as count FROM $view LIMIT 1");
                    $result = $stmt->fetch();
                    echo "<li>✅ <strong>$view</strong> : accessible ({$result['count']} lignes)</li>\n";
                } catch (Exception $e) {
                    echo "<li>❌ <strong>$view</strong> : " . $e->getMessage() . "</li>\n";
                }
            }
            echo "</ul>\n";
            
        } catch (Exception $e) {
            echo "<p style='color: red;'>Erreur lors du test des vues : " . $e->getMessage() . "</p>\n";
        }
        
        echo "</div>\n";
    } else {
        echo "<div style='border: 2px solid #dc3545; padding: 15px; background: #f8d7da;'>\n";
        echo "<h3>❌ Problème avec rebenciaBD</h3>\n";
        echo "<p>Vérifiez les paramètres de connexion.</p>\n";
        echo "</div>\n";
    }
} else {
    echo "<div style='border: 2px solid #dc3545; padding: 15px; background: #f8d7da;'>\n";
    echo "<h3>❌ Configuration rebenciaBD manquante</h3>\n";
    echo "<p>La configuration rebenciaBD n'a pas été trouvée dans database.php</p>\n";
    echo "</div>\n";
}

echo "<h2>💡 Résumé :</h2>\n";
echo "<div style='border: 1px solid #007bff; padding: 15px; background: #e3f2fd;'>\n";
echo "<p><strong>Configuration ajoutée :</strong></p>\n";
echo "<pre style='background: #f8f9fa; padding: 10px; border-radius: 5px;'>\n";
echo "\$db['rebenciaBD'] = array(\n";
echo "    'hostname' => 'localhost',\n";
echo "    'username' => 'rebencia_rebencia',\n";
echo "    'database' => 'rebencia_RebenciaBD',\n";
echo "    'dbprefix' => 'wp_Hrg8P_',\n";
echo "    // ... autres paramètres\n";
echo ");\n";
echo "</pre>\n";
echo "<p>Cette configuration permet d'accéder à la base WordPress avec l'alias 'rebenciaBD'.</p>\n";
echo "</div>\n";
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
table { margin: 10px 0; width: 100%; }
th, td { padding: 10px; text-align: left; }
pre { overflow-x: auto; }
</style>
