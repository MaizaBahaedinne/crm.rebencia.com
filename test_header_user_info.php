<?php
// Test de l'affichage des informations utilisateur dans le header
require_once 'index.php';

echo "<h2>👤 Test: Informations Utilisateur dans le Header Menu</h2>";

try {
    // Charger le CI
    $CI =& get_instance();
    if (!$CI) {
        throw new Exception("Impossible de charger CodeIgniter");
    }
    
    // Charger la session et les données utilisateur
    $CI->load->library('session');
    
    echo "<h3>🔍 Données de session utilisateur</h3>";
    
    // Récupérer les données de session
    $session_data = [
        'isLoggedIn' => $CI->session->userdata('isLoggedIn'),
        'wp_id' => $CI->session->userdata('wp_id'),
        'name' => $CI->session->userdata('name'),
        'role' => $CI->session->userdata('role'),
        'roleText' => $CI->session->userdata('roleText'),
        'user_post_id' => $CI->session->userdata('user_post_id'),
        'agency_id' => $CI->session->userdata('agency_id'),
        'wp_avatar' => $CI->session->userdata('wp_avatar')
    ];
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 10px 0;'>";
    echo "<tr><th>Clé de session</th><th>Valeur</th><th>Status</th></tr>";
    
    foreach ($session_data as $key => $value) {
        $status = !empty($value) ? '✅' : '❌';
        $color = !empty($value) ? 'green' : 'red';
        $display_value = is_null($value) ? '<em>NULL</em>' : htmlspecialchars((string)$value);
        
        echo "<tr>";
        echo "<td><strong>$key</strong></td>";
        echo "<td style='color: $color;'>$display_value</td>";
        echo "<td>$status</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Tester la récupération du nom d'agence
    echo "<h3>🏢 Test de récupération du nom d'agence</h3>";
    
    if (!empty($session_data['agency_id'])) {
        $CI->load->database('wordpress');
        $wp_db = $CI->load->database('wordpress', TRUE);
        
        $agency_query = $wp_db->query("SELECT post_title FROM rebencia_RebenciaBD.wp_Hrg8P_posts WHERE ID = ? AND post_type = 'houzez_agency'", [$session_data['agency_id']]);
        
        if ($agency_query->num_rows() > 0) {
            $agency_name = $agency_query->row()->post_title;
            echo "<p style='color: green;'>✅ <strong>Nom d'agence trouvé:</strong> " . htmlspecialchars($agency_name) . "</p>";
        } else {
            echo "<p style='color: orange;'>⚠️ <strong>Aucune agence trouvée pour l'ID:</strong> " . $session_data['agency_id'] . "</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ <strong>Aucun agency_id en session</strong></p>";
    }
    
    // Simuler l'affichage du menu
    echo "<h3>🎨 Aperçu du menu utilisateur</h3>";
    
    echo "<div style='border: 1px solid #ddd; padding: 15px; border-radius: 5px; background: #f8f9fa; max-width: 300px;'>";
    echo "<h6 style='border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 10px;'>Bienvenue " . htmlspecialchars($session_data['name'] ?? 'Utilisateur') . "!</h6>";
    
    echo "<div style='font-size: 0.85em; color: #666;'>";
    echo "<div style='margin-bottom: 8px;'>";
    echo "<i class='mdi mdi-identifier' style='color: #17a2b8;'></i> ";
    echo "<strong>User ID:</strong> " . ($session_data['wp_id'] ?? 'N/A');
    echo "</div>";
    
    echo "<div style='margin-bottom: 8px;'>";
    echo "<i class='mdi mdi-badge-account' style='color: #007bff;'></i> ";
    echo "<strong>Matricule:</strong> " . ($session_data['user_post_id'] ?? 'N/A');
    echo "</div>";
    
    if (!empty($session_data['agency_id'])) {
        echo "<div style='margin-bottom: 8px;'>";
        echo "<i class='mdi mdi-office-building' style='color: #ffc107;'></i> ";
        echo "<strong>Agency ID:</strong> " . $session_data['agency_id'];
        echo "</div>";
        
        if (isset($agency_name) && !empty($agency_name)) {
            echo "<div style='margin-bottom: 8px;'>";
            echo "<i class='mdi mdi-domain' style='color: #28a745;'></i> ";
            echo "<strong>Agence:</strong> " . htmlspecialchars($agency_name);
            echo "</div>";
        }
    }
    echo "</div>";
    
    echo "<hr style='margin: 10px 0;'>";
    echo "<div>";
    echo "<a href='#' style='text-decoration: none; color: #333; display: block; padding: 5px 0;'>";
    echo "<i class='mdi mdi-account-circle'></i> Profil";
    echo "</a>";
    echo "<a href='#' style='text-decoration: none; color: #333; display: block; padding: 5px 0;'>";
    echo "<i class='mdi mdi-logout'></i> Déconnexion";
    echo "</a>";
    echo "</div>";
    echo "</div>";
    
    // Vérification de connexion
    echo "<h3>🔐 Statut de connexion</h3>";
    if ($session_data['isLoggedIn']) {
        echo "<p style='color: green;'>✅ <strong>Utilisateur connecté</strong></p>";
        echo "<p><strong>Rôle:</strong> " . htmlspecialchars($session_data['roleText'] ?? 'N/A') . "</p>";
    } else {
        echo "<p style='color: red;'>❌ <strong>Utilisateur non connecté</strong></p>";
        echo "<p><em>Les informations utilisateur ne seront pas affichées dans le header.</em></p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ <strong>Erreur :</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre style='background: #f8f9fa; padding: 10px; border-radius: 5px;'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo "<hr>";
echo "<h3>🔗 Liens de test</h3>";
echo "<p><a href='/crm.rebencia.com/dashboard' style='display: inline-block; background: #007bff; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin: 5px;' target='_blank'>→ Tester le dashboard (avec header)</a></p>";
echo "<p><a href='/crm.rebencia.com/login' style='display: inline-block; background: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin: 5px;' target='_blank'>→ Page de connexion</a></p>";

echo "<hr>";
echo "<h3>📝 Modifications apportées au header</h3>";
echo "<ul>";
echo "<li>✅ Ajout de <strong>User ID</strong> dans le menu déroulant utilisateur</li>";
echo "<li>✅ Ajout du <strong>Matricule</strong> (user_post_id) avec icône distinctive</li>";
echo "<li>✅ Affichage de l'<strong>Agency ID</strong> si disponible</li>";
echo "<li>✅ Récupération et affichage du <strong>nom d'agence</strong> depuis la base WordPress</li>";
echo "<li>✅ Icônes Material Design distinctives pour chaque information</li>";
echo "<li>✅ Mise à jour des deux menus utilisateur (header et sidebar)</li>";
echo "<li>✅ Utilisation de l'avatar WordPress réel</li>";
echo "<li>✅ Style cohérent avec le design existant</li>";
echo "</ul>";

echo "<hr>";
echo "<h3>🎯 Informations affichées</h3>";
echo "<ul>";
echo "<li><strong>User ID:</strong> ID WordPress de l'utilisateur (wp_id)</li>";
echo "<li><strong>Matricule:</strong> ID du post agent (user_post_id) - identifiant unique</li>";
echo "<li><strong>Agency ID:</strong> ID de l'agence associée (si disponible)</li>";
echo "<li><strong>Agence:</strong> Nom complet de l'agence (récupéré dynamiquement)</li>";
echo "</ul>";

echo "<hr>";
echo "<h3>💡 Notes techniques</h3>";
echo "<ul>";
echo "<li>Les informations sont récupérées depuis la session utilisateur</li>";
echo "<li>Le nom d'agence est récupéré dynamiquement depuis wp_Hrg8P_posts</li>";
echo "<li>Gestion des cas où certaines informations ne sont pas disponibles</li>";
echo "<li>Affichage conditionnel (ne s'affiche que si les données existent)</li>";
echo "<li>Protection XSS avec htmlspecialchars()</li>";
echo "</ul>";
?>
