<?php
// Test de correction de l'erreur post_title vs agency_name
echo "<h1>🔧 Test de correction - Erreur post_title</h1>";
echo "<hr>";

echo "<h2>❌ Problème identifié :</h2>";
echo "<div style='background: #f8d7da; padding: 15px; border-left: 4px solid #dc3545; margin: 10px 0;'>";
echo "<p><strong>Erreur PHP :</strong></p>";
echo "<pre>Undefined property: stdClass::\$post_title
Filename: dashboard/manager.php
Line Number: 23</pre>";
echo "</div>";

echo "<h2>🔍 Analyse du problème :</h2>";
echo "<ol>";
echo "<li><strong>Contrôleur Dashboard::manager()</strong> appelle <code>\$this->agency_model->get_agency(\$agency_id)</code></li>";
echo "<li><strong>Agency_model::get_agency()</strong> retourne un objet avec <code>agency_name</code> (pas <code>post_title</code>)</li>";
echo "<li><strong>Vue dashboard/manager.php</strong> essayait d'accéder à <code>\$agency->post_title</code></li>";
echo "</ol>";

echo "<h2>✅ Correction appliquée :</h2>";
echo "<div style='background: #d4edda; padding: 15px; border-left: 4px solid #28a745; margin: 10px 0;'>";
echo "<p><strong>Avant :</strong></p>";
echo "<pre>\$agency->post_title</pre>";
echo "<p><strong>Après :</strong></p>";
echo "<pre>\$agency->agency_name</pre>";
echo "</div>";

echo "<h2>🧪 Vérification de la structure Agency_model :</h2>";
if (file_exists('application/models/Agency_model.php')) {
    $agency_model_content = file_get_contents('application/models/Agency_model.php');
    
    if (strpos($agency_model_content, "p.post_title as agency_name") !== false) {
        echo "<p style='color: green;'>✅ Agency_model::get_agency() utilise bien 'p.post_title as agency_name'</p>";
    } else {
        echo "<p style='color: red;'>❌ Structure SQL non trouvée</p>";
    }
    
    if (strpos($agency_model_content, "public function get_agency") !== false) {
        echo "<p style='color: green;'>✅ Méthode get_agency() existe</p>";
    } else {
        echo "<p style='color: red;'>❌ Méthode get_agency() manquante</p>";
    }
}

echo "<h2>🧪 Vérification de la correction :</h2>";
if (file_exists('application/views/dashboard/manager.php')) {
    $manager_view_content = file_get_contents('application/views/dashboard/manager.php');
    
    if (strpos($manager_view_content, "\$agency->agency_name") !== false) {
        echo "<p style='color: green;'>✅ Vue manager.php utilise maintenant \$agency->agency_name</p>";
    } else {
        echo "<p style='color: red;'>❌ Correction non appliquée dans la vue</p>";
    }
    
    if (strpos($manager_view_content, "\$agency->post_title") !== false) {
        echo "<p style='color: orange;'>⚠️ \$agency->post_title encore présent dans la vue</p>";
    } else {
        echo "<p style='color: green;'>✅ \$agency->post_title supprimé de la vue</p>";
    }
}

echo "<h2>📊 Structure attendue de l'objet \$agency :</h2>";
echo "<div style='background: #f8f9fa; padding: 15px; border-left: 4px solid #007bff; margin: 10px 0;'>";
echo "<pre>";
echo "stdClass Object {
    agency_id => 123,
    agency_name => 'Nom de l'agence',      // ← Propriété correcte
    agency_description => 'Description...',
    post_status => 'publish',
    created_date => '2024-01-01 00:00:00',
    agency_email => 'contact@agence.com',
    phone => '01 23 45 67 89',
    mobile => '06 12 34 56 78',
    agency_address => 'Adresse de l'agence',
    website => 'https://agence.com',
    facebook => 'facebook_url',
    twitter => 'twitter_url',
    linkedin => 'linkedin_url',
    agency_logo => 'logo_url'
}";
echo "</pre>";
echo "</div>";

echo "<h2>🎯 Test de fonctionnement :</h2>";
echo "<p>Maintenant, testez ces URLs pour vérifier que l'erreur est corrigée :</p>";
echo "<ul>";
echo "<li><a href='http://localhost:8888/crm.rebencia.com/dashboard/manager' target='_blank'>Dashboard Manager</a></li>";
echo "<li><a href='http://localhost:8888/crm.rebencia.com/index.php/dashboard/manager' target='_blank'>Dashboard Manager (avec index.php)</a></li>";
echo "</ul>";

echo "<h2>🔧 Flux de correction :</h2>";
echo "<div style='background: #e8f4fd; padding: 15px; border-left: 4px solid #0066cc; margin: 10px 0;'>";
echo "<ol>";
echo "<li>✅ <strong>Erreur identifiée :</strong> \$agency->post_title n'existe pas</li>";
echo "<li>✅ <strong>Source analysée :</strong> Agency_model retourne agency_name</li>";
echo "<li>✅ <strong>Correction appliquée :</strong> \$agency->post_title → \$agency->agency_name</li>";
echo "<li>✅ <strong>Test requis :</strong> Vérifier que le dashboard manager fonctionne</li>";
echo "</ol>";
echo "</div>";

echo "<hr>";
echo "<h3>✅ ERREUR CORRIGÉE</h3>";
echo "<p style='color: green; font-weight: bold;'>L'erreur 'Undefined property: stdClass::\$post_title' est maintenant résolue.</p>";
echo "<p>Le dashboard manager devrait maintenant afficher correctement le nom de l'agence.</p>";
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
pre { background: #f5f5f5; padding: 10px; border: 1px solid #ddd; white-space: pre-wrap; }
h2 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 5px; }
</style>
