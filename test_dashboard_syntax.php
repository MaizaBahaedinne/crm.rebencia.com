<?php
// Script de test pour vérifier Dashboard.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Test du contrôleur Dashboard</h1>";

try {
    // Définir les constantes nécessaires
    if (!defined('BASEPATH')) {
        define('BASEPATH', '/Applications/MAMP/htdocs/crm.rebencia.com/');
    }
    
    if (!defined('APPPATH')) {
        define('APPPATH', '/Applications/MAMP/htdocs/crm.rebencia.com/application/');
    }
    
    // Inclure le fichier pour vérifier la syntaxe
    include_once '/Applications/MAMP/htdocs/crm.rebencia.com/application/controllers/Dashboard.php';
    
    echo "<p style='color: green;'>✅ Aucune erreur de syntaxe détectée dans Dashboard.php</p>";
    echo "<p>Le fichier a été inclus avec succès.</p>";
    
} catch (ParseError $e) {
    echo "<p style='color: red;'>❌ Erreur de syntaxe PHP : " . $e->getMessage() . "</p>";
    echo "<p>Ligne : " . $e->getLine() . "</p>";
} catch (Error $e) {
    echo "<p style='color: orange;'>⚠️ Erreur PHP : " . $e->getMessage() . "</p>";
    echo "<p>Ligne : " . $e->getLine() . "</p>";
} catch (Exception $e) {
    echo "<p style='color: orange;'>⚠️ Exception : " . $e->getMessage() . "</p>";
}

echo "<p><strong>Test terminé</strong></p>";
?>
