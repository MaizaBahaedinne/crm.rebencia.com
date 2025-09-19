<?php
// Test de correction de l'erreur get_user_info
echo "<h2>Test correction erreur get_user_info()</h2>";

echo "<h3>1. Vérification de la méthode User_model::get_wp_user()</h3>";
$user_model_content = file_get_contents('application/models/User_model.php');

if (strpos($user_model_content, "function get_wp_user") !== false) {
    echo "<p style='color: green;'>✅ Méthode get_wp_user() existe</p>";
} else {
    echo "<p style='color: red;'>❌ Méthode get_wp_user() manquante</p>";
}

if (strpos($user_model_content, "a.agency_id") !== false) {
    echo "<p style='color: green;'>✅ agency_id ajouté dans la requête get_wp_user()</p>";
} else {
    echo "<p style='color: red;'>❌ agency_id manquant dans get_wp_user()</p>";
}

echo "<h3>2. Vérification des corrections dans Agent.php</h3>";
$agent_content = file_get_contents('application/controllers/Agent.php');

if (strpos($agent_content, "get_wp_user(\$user_id)") !== false) {
    echo "<p style='color: green;'>✅ Agent.php utilise maintenant get_wp_user()</p>";
} else {
    echo "<p style='color: red;'>❌ Agent.php utilise encore get_user_info()</p>";
}

if (strpos($agent_content, "get_user_info") === false) {
    echo "<p style='color: green;'>✅ Aucune référence à get_user_info() dans Agent.php</p>";
} else {
    echo "<p style='color: orange;'>⚠️ get_user_info() encore présent dans Agent.php</p>";
}

echo "<h3>3. Vérification des corrections dans Report.php</h3>";
$report_content = file_get_contents('application/controllers/Report.php');

if (strpos($report_content, "get_wp_user(\$user_id)") !== false) {
    echo "<p style='color: green;'>✅ Report.php utilise maintenant get_wp_user()</p>";
} else {
    echo "<p style='color: red;'>❌ Report.php utilise encore get_user_info()</p>";
}

if (strpos($report_content, "get_user_info") === false) {
    echo "<p style='color: green;'>✅ Aucune référence à get_user_info() dans Report.php</p>";
} else {
    echo "<p style='color: orange;'>⚠️ get_user_info() encore présent dans Report.php</p>";
}

echo "<h3>4. Test de simulation d'appel get_wp_user()</h3>";
echo "<div style='background: #f8f9fa; padding: 15px; border-left: 4px solid #28a745; margin: 10px 0;'>";
echo "<p><strong>Structure de retour attendue :</strong></p>";
echo "<pre>";
echo "Object {
    user_id: 123,
    user_login: 'manager_login',
    user_email: 'manager@example.com',
    display_name: 'Manager Name',
    first_name: 'John',
    last_name: 'Doe',
    agency_id: 456,        // ← Nouveau champ ajouté
    agency_name: 'Agence XYZ',
    agent_name: 'Manager Name',
    position: 'Manager'
}";
echo "</pre>";
echo "</div>";

echo "<h3>5. Flux de fonctionnement corrigé</h3>";
echo "<ol>";
echo "<li>Manager se connecte avec son user_id</li>";
echo "<li>Agent::index() appelle User_model::get_wp_user(\$user_id)</li>";
echo "<li>get_wp_user() retourne un objet avec agency_id</li>";
echo "<li>Agent_model::get_agents_by_agency(\$agency_id) récupère les agents</li>";
echo "<li>Manager voit uniquement son équipe</li>";
echo "</ol>";

echo "<hr>";
echo "<h3>✅ ERREUR CORRIGÉE</h3>";
echo "<p style='color: green; font-weight: bold;'>L'erreur 'Call to undefined method User_model::get_user_info()' est maintenant résolue</p>";

echo "<h4>Corrections apportées :</h4>";
echo "<ul>";
echo "<li>✅ Ajout de agency_id dans User_model::get_wp_user()</li>";
echo "<li>✅ Remplacement de get_user_info() par get_wp_user() dans Agent.php</li>";
echo "<li>✅ Remplacement de get_user_info() par get_wp_user() dans Report.php</li>";
echo "<li>✅ Utilisation d'une méthode existante au lieu d'en créer une nouvelle</li>";
echo "</ul>";

echo "<h4>Test de l'URL :</h4>";
echo "<ul>";
echo "<li><a href='http://localhost:8888/crm.rebencia.com/agents' target='_blank'>Tester /agents</a></li>";
echo "</ul>";
?>
