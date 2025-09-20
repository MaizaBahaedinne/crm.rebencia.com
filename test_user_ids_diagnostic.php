<?php
// Diagnostic des valeurs User ID vs User Post ID
require_once 'index.php';

echo "<h2>🔍 Diagnostic: User ID vs User Post ID</h2>";

try {
    // Charger le CI
    $CI =& get_instance();
    if (!$CI) {
        throw new Exception("Impossible de charger CodeIgniter");
    }
    
    // Charger la session
    $CI->load->library('session');
    
    echo "<h3>📊 Valeurs actuelles en session</h3>";
    
    // Récupérer toutes les données de session utilisateur
    $all_session_data = [
        'isLoggedIn' => $CI->session->userdata('isLoggedIn'),
        'wp_id' => $CI->session->userdata('wp_id'),
        'userId' => $CI->session->userdata('userId'),
        'user_id' => $CI->session->userdata('user_id'),
        'user_post_id' => $CI->session->userdata('user_post_id'),
        'vendorId' => $CI->session->userdata('vendorId'),
        'name' => $CI->session->userdata('name'),
        'role' => $CI->session->userdata('role'),
        'roleText' => $CI->session->userdata('roleText'),
        'agency_id' => $CI->session->userdata('agency_id'),
        'wp_avatar' => $CI->session->userdata('wp_avatar')
    ];
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 10px 0;'>";
    echo "<tr><th>Clé de session</th><th>Valeur</th><th>Type</th><th>Note</th></tr>";
    
    foreach ($all_session_data as $key => $value) {
        $type = gettype($value);
        $display_value = is_null($value) ? '<em>NULL</em>' : htmlspecialchars((string)$value);
        
        // Analyser les clés liées aux IDs
        $note = '';
        if (in_array($key, ['wp_id', 'userId', 'user_id', 'vendorId'])) {
            $note = '🆔 ID utilisateur WordPress';
        } elseif ($key === 'user_post_id') {
            $note = '📄 ID du post agent (houzez_agent)';
        } elseif ($key === 'agency_id') {
            $note = '🏢 ID de l\'agence';
        }
        
        $color = is_null($value) ? '#999' : '#000';
        
        echo "<tr>";
        echo "<td><strong>$key</strong></td>";
        echo "<td style='color: $color;'>$display_value</td>";
        echo "<td>$type</td>";
        echo "<td>$note</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Vérifier si les valeurs sont identiques
    echo "<h3>🔍 Analyse des doublons potentiels</h3>";
    
    $user_ids = [
        'wp_id' => $all_session_data['wp_id'],
        'userId' => $all_session_data['userId'],
        'user_id' => $all_session_data['user_id'],
        'vendorId' => $all_session_data['vendorId']
    ];
    
    echo "<p><strong>Valeurs des IDs utilisateur :</strong></p>";
    echo "<ul>";
    foreach ($user_ids as $key => $value) {
        echo "<li><strong>$key:</strong> " . ($value ?? 'NULL') . "</li>";
    }
    echo "</ul>";
    
    // Vérifier si certaines valeurs sont identiques
    $unique_values = array_unique(array_filter($user_ids, function($v) { return !is_null($v); }));
    
    if (count($unique_values) == 1) {
        echo "<p style='color: orange;'>⚠️ <strong>Toutes les valeurs d'ID utilisateur sont identiques !</strong></p>";
    } else {
        echo "<p style='color: green;'>✅ Les valeurs d'ID utilisateur sont différentes</p>";
    }
    
    echo "<p><strong>user_post_id:</strong> " . ($all_session_data['user_post_id'] ?? 'NULL') . "</p>";
    
    // Comparaison avec la base de données
    echo "<h3>🗄️ Vérification dans la base de données</h3>";
    
    if (!empty($all_session_data['wp_id'])) {
        $user_wp_id = $all_session_data['wp_id'];
        
        // Connexion WordPress
        $CI->load->database('wordpress');
        $wp_db = $CI->load->database('wordpress', TRUE);
        
        // Récupérer les infos de l'utilisateur WordPress
        echo "<h4>👤 Données utilisateur WordPress</h4>";
        $user_query = "SELECT ID, user_login, user_email, display_name FROM rebencia_RebenciaBD.wp_Hrg8P_users WHERE ID = ?";
        $user_result = $wp_db->query($user_query, [$user_wp_id]);
        
        if ($user_result->num_rows() > 0) {
            $user_data = $user_result->row();
            echo "<table border='1' style='border-collapse: collapse;'>";
            echo "<tr><th>Champ</th><th>Valeur</th></tr>";
            echo "<tr><td><strong>ID (WordPress)</strong></td><td>" . $user_data->ID . "</td></tr>";
            echo "<tr><td><strong>Login</strong></td><td>" . htmlspecialchars($user_data->user_login) . "</td></tr>";
            echo "<tr><td><strong>Email</strong></td><td>" . htmlspecialchars($user_data->user_email) . "</td></tr>";
            echo "<tr><td><strong>Display Name</strong></td><td>" . htmlspecialchars($user_data->display_name) . "</td></tr>";
            echo "</table>";
        } else {
            echo "<p style='color: red;'>❌ Utilisateur non trouvé dans wp_users</p>";
        }
        
        // Récupérer les posts agent liés à cet utilisateur
        echo "<h4>📄 Posts agent liés à cet utilisateur</h4>";
        $agent_posts_query = "
            SELECT p.ID, p.post_title, p.post_author, p.post_type, p.post_status
            FROM rebencia_RebenciaBD.wp_Hrg8P_posts p
            WHERE p.post_author = ? 
            AND p.post_type = 'houzez_agent'
            ORDER BY p.ID
        ";
        
        $agent_posts_result = $wp_db->query($agent_posts_query, [$user_wp_id]);
        
        if ($agent_posts_result->num_rows() > 0) {
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>Post ID</th><th>Titre</th><th>Auteur</th><th>Type</th><th>Status</th></tr>";
            
            foreach ($agent_posts_result->result() as $post) {
                $highlight = ($post->ID == $all_session_data['user_post_id']) ? 'background: #fff3cd;' : '';
                echo "<tr style='$highlight'>";
                echo "<td><strong>" . $post->ID . "</strong></td>";
                echo "<td>" . htmlspecialchars($post->post_title) . "</td>";
                echo "<td>" . $post->post_author . "</td>";
                echo "<td>" . $post->post_type . "</td>";
                echo "<td>" . $post->post_status . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "<p><small>💡 La ligne surlignée correspond au user_post_id en session</small></p>";
        } else {
            echo "<p style='color: orange;'>⚠️ Aucun post agent trouvé pour cet utilisateur</p>";
        }
        
        // Vérifier via la vue crm_agents
        echo "<h4>🔍 Vérification via wp_Hrg8P_crm_agents</h4>";
        $crm_agents_query = "
            SELECT user_id, agent_post_id, agent_name, user_login, user_email, agency_id, agency_name
            FROM rebencia_RebenciaBD.wp_Hrg8P_crm_agents
            WHERE user_id = ?
        ";
        
        $crm_result = $wp_db->query($crm_agents_query, [$user_wp_id]);
        
        if ($crm_result->num_rows() > 0) {
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>User ID</th><th>Agent Post ID</th><th>Nom Agent</th><th>Login</th><th>Email</th><th>Agency ID</th><th>Agence</th></tr>";
            
            foreach ($crm_result->result() as $agent) {
                echo "<tr>";
                echo "<td><strong>" . $agent->user_id . "</strong></td>";
                echo "<td><strong>" . $agent->agent_post_id . "</strong></td>";
                echo "<td>" . htmlspecialchars($agent->agent_name ?? 'N/A') . "</td>";
                echo "<td>" . htmlspecialchars($agent->user_login ?? 'N/A') . "</td>";
                echo "<td>" . htmlspecialchars($agent->user_email ?? 'N/A') . "</td>";
                echo "<td>" . ($agent->agency_id ?? 'N/A') . "</td>";
                echo "<td>" . htmlspecialchars($agent->agency_name ?? 'N/A') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color: red;'>❌ Utilisateur non trouvé dans wp_Hrg8P_crm_agents</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ Aucun wp_id en session pour effectuer les vérifications</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ <strong>Erreur :</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre style='background: #f8f9fa; padding: 10px; border-radius: 5px;'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo "<hr>";
echo "<h3>📝 Analyse et recommandations</h3>";
echo "<div style='background: #e9ecef; padding: 15px; border-radius: 5px;'>";
echo "<p><strong>🎯 Définitions correctes :</strong></p>";
echo "<ul>";
echo "<li><strong>User ID / wp_id / userId :</strong> ID de l'utilisateur dans wp_users (identifiant de connexion)</li>";
echo "<li><strong>User Post ID :</strong> ID du post 'houzez_agent' créé pour cet utilisateur (profil agent)</li>";
echo "<li><strong>Agency ID :</strong> ID de l'agence à laquelle l'agent est rattaché</li>";
echo "</ul>";
echo "<p><strong>⚠️ Problème identifié :</strong></p>";
echo "<p>Si User ID et User Post ID affichent la même valeur, cela signifie que :</p>";
echo "<ol>";
echo "<li>Soit les variables utilisent la même source de données</li>";
echo "<li>Soit il y a une erreur dans la récupération du user_post_id</li>";
echo "</ol>";
echo "</div>";

echo "<hr>";
echo "<h3>🔗 Actions suggérées</h3>";
echo "<p><a href='/crm.rebencia.com/dashboard' style='display: inline-block; background: #007bff; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin: 5px;' target='_blank'>→ Vérifier dans le dashboard</a></p>";
echo "<p><a href='/crm.rebencia.com/login' style='display: inline-block; background: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin: 5px;' target='_blank'>→ Reconnecter pour test</a></p>";
?>
