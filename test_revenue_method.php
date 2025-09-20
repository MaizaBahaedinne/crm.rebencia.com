<?php
require_once('index.php');

// Test spécifique pour la méthode get_agency_previous_month_revenue
echo "<h1>Test de la méthode get_agency_previous_month_revenue</h1>";

try {
    // Simuler l'environnement CodeIgniter
    $CI =& get_instance();
    $CI->load->library('session');
    $CI->load->helper('url');
    $CI->load->database();
    
    // Charger le contrôleur Dashboard
    require_once(APPPATH . 'controllers/Dashboard.php');
    
    // Créer une instance du Dashboard
    $dashboard = new Dashboard();
    
    // Tester avec différents agency_id
    $test_agencies = [1, 18907, 999];
    
    foreach ($test_agencies as $agency_id) {
        echo "<h3>Test avec agency_id = $agency_id</h3>";
        
        try {
            // Utiliser reflection pour accéder à la méthode private
            $reflection = new ReflectionClass($dashboard);
            $method = $reflection->getMethod('get_agency_previous_month_revenue');
            $method->setAccessible(true);
            
            $result = $method->invoke($dashboard, $agency_id);
            echo "<p style='color: green;'>✅ Résultat : " . number_format($result, 0, ',', ' ') . " €</p>";
            
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Erreur : " . $e->getMessage() . "</p>";
        }
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erreur générale : " . $e->getMessage() . "</p>";
}

echo "<p><a href='" . base_url('dashboard/manager') . "'>← Retour au Dashboard Manager</a></p>";
?>
