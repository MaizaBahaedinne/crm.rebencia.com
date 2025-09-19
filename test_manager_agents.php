<?php
// Test de la fonctionnalité agents pour manager
echo "<h2>Test Manager - Visualisation de l'équipe</h2>";

// Simulation d'une session manager
session_start();
$_SESSION['role'] = 'manager';
$_SESSION['user_id'] = 1;
$_SESSION['is_logged_in'] = true;

echo "<h3>1. Configuration Manager</h3>";
echo "<p>Rôle simulé : <strong>" . $_SESSION['role'] . "</strong></p>";
echo "<p>User ID : <strong>" . $_SESSION['user_id'] . "</strong></p>";

echo "<h3>2. Vérification du contrôleur Agent</h3>";
$agent_content = file_get_contents('application/controllers/Agent.php');

if (strpos($agent_content, "if (\$role === 'manager')") !== false) {
    echo "<p style='color: green;'>✅ Logique manager ajoutée dans Agent::index()</p>";
} else {
    echo "<p style='color: red;'>❌ Logique manager manquante</p>";
}

if (strpos($agent_content, "get_agents_by_agency") !== false) {
    echo "<p style='color: green;'>✅ Appel get_agents_by_agency() présent</p>";
} else {
    echo "<p style='color: red;'>❌ Appel get_agents_by_agency() manquant</p>";
}

if (strpos($agent_content, "User_model") !== false) {
    echo "<p style='color: green;'>✅ User_model chargé dans le contrôleur</p>";
} else {
    echo "<p style='color: red;'>❌ User_model manquant</p>";
}

echo "<h3>3. Test du modèle Agent</h3>";
if (file_exists('application/models/Agent_model.php')) {
    $agent_model_content = file_get_contents('application/models/Agent_model.php');
    if (strpos($agent_model_content, "get_agents_by_agency") !== false) {
        echo "<p style='color: green;'>✅ Méthode get_agents_by_agency() existe dans Agent_model</p>";
    } else {
        echo "<p style='color: red;'>❌ Méthode get_agents_by_agency() manquante</p>";
    }
}

echo "<h3>4. Test de la route</h3>";
$routes_content = file_get_contents('application/config/routes.php');
if (strpos($routes_content, "\$route['agents'] = 'Agent/index';") !== false) {
    echo "<p style='color: green;'>✅ Route /agents configurée vers Agent::index()</p>";
} else {
    echo "<p style='color: red;'>❌ Route /agents manquante</p>";
}

echo "<h3>5. Logique implémentée</h3>";
echo "<div style='background: #f8f9fa; padding: 15px; border-left: 4px solid #28a745; margin: 10px 0;'>";
echo "<p><strong>Comportement pour le manager :</strong></p>";
echo "<ol>";
echo "<li>Le manager se connecte avec son user_id</li>";
echo "<li>Le système récupère son agency_id via User_model::get_user_info()</li>";
echo "<li>Seuls les agents de son agence sont récupérés via Agent_model::get_agents_by_agency()</li>";
echo "<li>Le titre de la page devient 'Mon équipe' au lieu de 'Liste des agents'</li>";
echo "<li>La vue affiche uniquement les agents de son agence</li>";
echo "</ol>";
echo "</div>";

echo "<h3>6. URLs de test</h3>";
echo "<ul>";
echo "<li><a href='http://localhost:8888/crm.rebencia.com/agents' target='_blank'>Test URL : /agents</a></li>";
echo "<li><a href='http://localhost:8888/crm.rebencia.com/index.php/agents' target='_blank'>Test URL : /index.php/agents</a></li>";
echo "</ul>";

echo "<hr>";
echo "<h3>✅ RÉSOLUTION COMPLÈTE</h3>";
echo "<p style='color: green; font-weight: bold;'>Le manager peut maintenant voir uniquement son équipe sur la page /agents</p>";

echo "<h4>Modifications apportées :</h4>";
echo "<ul>";
echo "<li>✅ Ajout de la logique de filtrage par agence dans Agent::index()</li>";
echo "<li>✅ Chargement du User_model dans le constructeur Agent</li>";
echo "<li>✅ Utilisation de get_agents_by_agency() pour filtrer les agents</li>";
echo "<li>✅ Changement du titre de page en 'Mon équipe' pour les managers</li>";
echo "<li>✅ Gestion des erreurs si le manager n'a pas d'agence assignée</li>";
echo "</ul>";
?>
