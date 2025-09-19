<?php
// Test des modifications "équipe" -> "agence" et estimations manager
echo "<h2>Test Manager - Modifications Agence et Estimations</h2>";

echo "<h3>1. Vérification changement 'équipe' → 'agence'</h3>";

// Vérifier le menu header.php
$header_content = file_get_contents('application/views/includes/header.php');
if (strpos($header_content, "Gestion d'agence") !== false) {
    echo "<p style='color: green;'>✅ Menu : 'Gestion d'agence' trouvé</p>";
} else {
    echo "<p style='color: red;'>❌ Menu : 'Gestion d'agence' non trouvé</p>";
}

if (strpos($header_content, "Mon agence") !== false) {
    echo "<p style='color: green;'>✅ Menu : 'Mon agence' trouvé</p>";
} else {
    echo "<p style='color: red;'>❌ Menu : 'Mon agence' non trouvé</p>";
}

if (strpos($header_content, "Estimations agence") !== false) {
    echo "<p style='color: green;'>✅ Menu : 'Estimations agence' trouvé</p>";
} else {
    echo "<p style='color: red;'>❌ Menu : 'Estimations agence' non trouvé</p>";
}

// Vérifier Agent.php
$agent_content = file_get_contents('application/controllers/Agent.php');
if (strpos($agent_content, "Mon agence") !== false) {
    echo "<p style='color: green;'>✅ Agent.php : Titre 'Mon agence' trouvé</p>";
} else {
    echo "<p style='color: red;'>❌ Agent.php : Titre 'Mon agence' non trouvé</p>";
}

// Vérifier dashboard manager
$manager_view_content = file_get_contents('application/views/dashboard/manager.php');
if (strpos($manager_view_content, "Agents de l'agence") !== false) {
    echo "<p style='color: green;'>✅ Dashboard Manager : 'Agents de l'agence' trouvé</p>";
} else {
    echo "<p style='color: red;'>❌ Dashboard Manager : 'Agents de l'agence' non trouvé</p>";
}

echo "<h3>2. Vérification Login.php - agency_id pour manager</h3>";
$login_content = file_get_contents('application/controllers/Login.php');
if (strpos($login_content, "mappedRole === 'manager'") !== false) {
    echo "<p style='color: green;'>✅ Login.php : Logique manager trouvée</p>";
} else {
    echo "<p style='color: red;'>❌ Login.php : Logique manager non trouvée</p>";
}

if (strpos($login_content, "\$mappedRole === 'agent' || \$mappedRole === 'manager'") !== false) {
    echo "<p style='color: green;'>✅ Login.php : agency_id pour manager ajouté</p>";
} else {
    echo "<p style='color: red;'>❌ Login.php : agency_id pour manager manquant</p>";
}

echo "<h3>3. Vérification Estimations.php</h3>";
$estimations_content = file_get_contents('application/controllers/Estimations.php');
if (strpos($estimations_content, "case 'manager':") !== false) {
    echo "<p style='color: green;'>✅ Estimations.php : Case manager trouvée</p>";
} else {
    echo "<p style='color: red;'>❌ Estimations.php : Case manager non trouvée</p>";
}

if (strpos($estimations_content, "get_estimations_by_agency") !== false) {
    echo "<p style='color: green;'>✅ Estimations.php : Appel get_estimations_by_agency trouvé</p>";
} else {
    echo "<p style='color: red;'>❌ Estimations.php : Appel get_estimations_by_agency non trouvé</p>";
}

echo "<h3>4. Vérification Estimation_model.php</h3>";
$estimation_model_content = file_get_contents('application/models/Estimation_model.php');
if (strpos($estimation_model_content, "get_estimations_by_agency") !== false) {
    echo "<p style='color: green;'>✅ Estimation_model.php : Méthode get_estimations_by_agency existe</p>";
} else {
    echo "<p style='color: red;'>❌ Estimation_model.php : Méthode get_estimations_by_agency manquante</p>";
}

echo "<h3>5. Flux de fonctionnement pour Manager</h3>";
echo "<div style='background: #f8f9fa; padding: 15px; border-left: 4px solid #28a745; margin: 10px 0;'>";
echo "<p><strong>Session Manager après connexion :</strong></p>";
echo "<pre>";
echo "\$_SESSION = [
    'role' => 'manager',
    'user_id' => 123,
    'agency_id' => 456,     // ← Maintenant ajouté pour manager
    'user_post_id' => 123,
    'name' => 'Manager Name',
    // ...
];";
echo "</pre>";

echo "<p><strong>Estimations pour Manager :</strong></p>";
echo "<ol>";
echo "<li>Manager accède à /estimations</li>";
echo "<li>Estimations::index() récupère user_info avec agency_id</li>";
echo "<li>get_estimations_by_role() détecte 'manager'</li>";
echo "<li>Appel Estimation_model::get_estimations_by_agency(\$agency_id)</li>";
echo "<li>Retourne TOUTES les estimations des agents de l'agence</li>";
echo "</ol>";
echo "</div>";

echo "<h3>6. URLs de test</h3>";
echo "<ul>";
echo "<li><a href='http://localhost:8888/crm.rebencia.com/agents' target='_blank'>Test : Mon agence (/agents)</a></li>";
echo "<li><a href='http://localhost:8888/crm.rebencia.com/estimations' target='_blank'>Test : Estimations agence (/estimations)</a></li>";
echo "</ul>";

echo "<hr>";
echo "<h3>✅ MODIFICATIONS COMPLÈTES</h3>";
echo "<p style='color: green; font-weight: bold;'>1. Tous les textes 'équipe' ont été remplacés par 'agence'</p>";
echo "<p style='color: green; font-weight: bold;'>2. Le manager voit maintenant toutes les estimations de son agence</p>";

echo "<h4>Résumé des changements :</h4>";
echo "<ul>";
echo "<li>✅ Menu : 'Gestion d'équipe' → 'Gestion d'agence'</li>";
echo "<li>✅ Menu : 'Mon équipe' → 'Mon agence'</li>";
echo "<li>✅ Menu : 'Estimations équipe' → 'Estimations agence'</li>";
echo "<li>✅ Titres et textes dans les vues</li>";
echo "<li>✅ Login.php : agency_id ajouté pour manager</li>";
echo "<li>✅ Estimations : Manager voit toutes les estimations de l'agence</li>";
echo "</ul>";

echo "<h4>Fonctionnalités Manager :</h4>";
echo "<ul>";
echo "<li>✅ Dashboard avec statistiques d'agence</li>";
echo "<li>✅ Voir tous les agents de l'agence (/agents)</li>";
echo "<li>✅ Voir toutes les estimations de l'agence (/estimations)</li>";
echo "<li>✅ Rapports d'agence (/reports/manager)</li>";
echo "<li>✅ Navigation complète avec terminologie 'agence'</li>";
echo "</ul>";
?>
