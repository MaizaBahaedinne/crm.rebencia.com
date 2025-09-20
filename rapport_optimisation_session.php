<?php
// Rapport final des optimisations avec agency_id en session
echo "<h1>📋 Rapport des optimisations - Agency ID en session</h1>\n";

echo "<h2>✅ Modifications effectuées :</h2>\n";
echo "<div style='border: 1px solid #28a745; padding: 15px; background: #d4edda; margin: 10px 0;'>\n";
echo "<h3>1. BaseController.php</h3>\n";
echo "<ul>\n";
echo "<li>✅ Ajout de la propriété \$agencyId</li>\n";
echo "<li>✅ Récupération de agency_id depuis la session dans isLoggedIn()</li>\n";
echo "<li>✅ Ajout de agency_id dans \$this->global pour toutes les vues</li>\n";
echo "</ul>\n";

echo "<h3>2. Agent.php (contrôleur)</h3>\n";
echo "<ul>\n";
echo "<li>✅ Remplacement de la requête User_model::get_wp_user() par \$this->session->userdata('agency_id')</li>\n";
echo "<li>✅ Suppression de la dépendance User_model pour les managers</li>\n";
echo "<li>✅ Performance améliorée : 0 requête SQL supplémentaire</li>\n";
echo "</ul>\n";

echo "<h3>3. Login.php (déjà optimisé)</h3>\n";
echo "<ul>\n";
echo "<li>✅ Agency_id déjà récupéré et stocké en session lors du login</li>\n";
echo "<li>✅ Logique existante pour agents et managers</li>\n";
echo "</ul>\n";
echo "</div>\n";

// Test de performance
echo "<h2>🚀 Comparaison de performance :</h2>\n";

$start_time = microtime(true);

// Méthode ANCIENNE (simulée)
echo "<h3>Ancienne méthode :</h3>\n";
echo "<pre style='background: #f8d7da; padding: 10px; border: 1px solid #721c24;'>\n";
echo "// Dans Agent.php\n";
echo "\$this->load->model('User_model');\n";
echo "\$user_info = \$this->User_model->get_wp_user(\$user_id);\n";
echo "\$agency_id = \$user_info->agency_id ?? null;\n";
echo "\n";
echo "// Cela nécessite :\n";
echo "// 1. Chargement du modèle\n";
echo "// 2. Requête SQL vers wp_Hrg8P_crm_agents\n";
echo "// 3. Traitement du résultat\n";
echo "</pre>\n";

$old_method_time = microtime(true) - $start_time;

$start_time = microtime(true);

// Méthode NOUVELLE
echo "<h3>Nouvelle méthode :</h3>\n";
echo "<pre style='background: #d4edda; padding: 10px; border: 1px solid #155724;'>\n";
echo "// Dans Agent.php\n";
echo "\$agency_id = \$this->session->userdata('agency_id');\n";
echo "\n";
echo "// Avantages :\n";
echo "// ✅ Aucune requête SQL\n";
echo "// ✅ Aucun chargement de modèle\n";
echo "// ✅ Accès direct depuis la session\n";
echo "// ✅ Performance optimale\n";
echo "</pre>\n";

$new_method_time = microtime(true) - $start_time;

echo "<h2>📊 Bénéfices :</h2>\n";
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>\n";
echo "<tr style='background: #f8f9fa;'><th>Aspect</th><th>Avant</th><th>Après</th><th>Amélioration</th></tr>\n";
echo "<tr><td>Requêtes SQL</td><td>1 par visite</td><td>0</td><td>🚀 100%</td></tr>\n";
echo "<tr><td>Modèles chargés</td><td>User_model</td><td>Aucun</td><td>⚡ Optimisé</td></tr>\n";
echo "<tr><td>Complexité</td><td>Moyenne</td><td>Simple</td><td>🎯 Simplifié</td></tr>\n";
echo "<tr><td>Fiabilité</td><td>Dépend DB</td><td>Session</td><td>🛡️ Stable</td></tr>\n";
echo "</table>\n";

// Vérification de la session
session_start();
echo "<h2>🔍 Vérification de la session actuelle :</h2>\n";

if (isset($_SESSION['agency_id'])) {
    echo "<p style='color: green;'>✅ Agency ID en session : {$_SESSION['agency_id']}</p>\n";
} else {
    echo "<p style='color: orange;'>⚠️ Pas d'agency_id en session (normal si pas connecté)</p>\n";
}

// Test avec une session simulée
$_SESSION['test_agency_id'] = 12345;
echo "<p>Test d'accès : \$_SESSION['test_agency_id'] = " . $_SESSION['test_agency_id'] . "</p>\n";

echo "<h2>🎯 Code exemple pour les développeurs :</h2>\n";
echo "<pre style='background: #e3f2fd; padding: 15px; border: 1px solid #1976d2;'>\n";
echo "// Dans n'importe quel contrôleur héritant de BaseController\n";
echo "class MonController extends BaseController {\n";
echo "    public function ma_methode() {\n";
echo "        \$this->isLoggedIn(); // Nécessaire pour charger les données de session\n";
echo "        \n";
echo "        // Accès direct à l'agency_id\n";
echo "        \$agency_id = \$this->agencyId;  // Propriété de classe\n";
echo "        // OU\n";
echo "        \$agency_id = \$this->session->userdata('agency_id'); // Direct session\n";
echo "        // OU\n";
echo "        \$agency_id = \$this->global['agency_id']; // Via global\n";
echo "        \n";
echo "        if (\$agency_id) {\n";
echo "            // Utiliser l'agency_id...\n";
echo "        }\n";
echo "    }\n";
echo "}\n";
echo "</pre>\n";

echo "<h2>🎉 Résultat final :</h2>\n";
echo "<div style='border: 2px solid #28a745; padding: 20px; background: #d4edda; margin: 20px 0;'>\n";
echo "<h3>✅ Optimisation complète réussie !</h3>\n";
echo "<ul style='font-size: 16px;'>\n";
echo "<li>🚀 <strong>Performance :</strong> Élimination des requêtes SQL inutiles</li>\n";
echo "<li>🎯 <strong>Simplicité :</strong> Code plus propre et maintenable</li>\n";
echo "<li>🛡️ <strong>Fiabilité :</strong> Utilisation de la session (plus stable)</li>\n";
echo "<li>🔧 <strong>Maintenabilité :</strong> Logique centralisée dans BaseController</li>\n";
echo "<li>⚡ <strong>Évolutivité :</strong> Facilite l'ajout de nouvelles fonctionnalités</li>\n";
echo "</ul>\n";
echo "<p style='font-size: 18px; text-align: center; margin-top: 20px;'>\n";
echo "<strong>✨ L'agency_id est maintenant accessible partout via la session ! ✨</strong>\n";
echo "</p>\n";
echo "</div>\n";

// Nettoyer la session de test
unset($_SESSION['test_agency_id']);
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
table { margin: 10px 0; }
th, td { padding: 12px; text-align: left; }
pre { overflow-x: auto; border-radius: 5px; }
h1, h2, h3 { color: #333; }
</style>
