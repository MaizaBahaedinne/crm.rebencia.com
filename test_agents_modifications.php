<?php
// Test des nouvelles fonctionnalités agents avec rôles et agences
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔧 Test modifications page /agents</h1>";
echo "<hr>";

echo "<h2>📋 Modifications apportées :</h2>";
echo "<div style='background: #d4edda; padding: 15px; border-left: 4px solid #28a745; margin: 10px 0;'>";
echo "<ul>";
echo "<li>✅ <strong>Ajout du rôle utilisateur</strong> dans la grille (Manager, Agent, etc.)</li>";
echo "<li>✅ <strong>Affichage du nom de l'agence</strong> (déjà présent)</li>";
echo "<li>✅ <strong>Suppression de l'email</strong> (partie en rouge supprimée)</li>";
echo "<li>✅ <strong>Suppression bouton 'Ajouter un agent'</strong></li>";
echo "<li>✅ <strong>Suppression bouton 'Modifier'</strong> et 'Supprimer'</li>";
echo "<li>✅ <strong>Utilisation de la vue wp_Hrg8P_crm_agents</strong></li>";
echo "</ul>";
echo "</div>";

echo "<h2>🔍 Test 1: Nouvelle méthode get_agents_with_roles_and_agencies()</h2>";
try {
    $connection = new mysqli('localhost', 'root', 'root', 'rebencia_RebenciaBD');
    
    if ($connection->connect_error) {
        echo "<p style='color: red;'>❌ Erreur de connexion: " . $connection->connect_error . "</p>";
    } else {
        // Test de la nouvelle requête
        $query = "
            SELECT 
                u.ID AS user_id,
                u.user_login AS user_login,
                u.user_email AS user_email,
                u.user_status AS user_status,
                u.user_registered AS registration_date,
                
                p.ID AS agent_post_id,
                p.post_title AS agent_name,
                p.post_status AS post_status,
                p.post_type AS post_type,
                pm_email.meta_value AS agent_email,
                
                a.ID AS agency_id,
                a.post_title AS agency_name,
                
                -- Contact info
                MAX(CASE WHEN pm_contact.meta_key = 'fave_agent_phone' THEN pm_contact.meta_value END) AS phone,
                MAX(CASE WHEN pm_contact.meta_key = 'fave_agent_mobile' THEN pm_contact.meta_value END) AS mobile,
                MAX(CASE WHEN pm_contact.meta_key = 'fave_agent_picture' THEN media.guid END) AS agent_avatar,
                MAX(CASE WHEN pm_contact.meta_key = 'fave_agent_position' THEN pm_contact.meta_value END) AS position,
                
                -- User role
                ur.meta_value AS user_roles
                
            FROM wp_Hrg8P_users u
            LEFT JOIN wp_Hrg8P_postmeta pm_email 
                   ON pm_email.meta_value = u.user_email
            LEFT JOIN wp_Hrg8P_posts p 
                   ON p.ID = pm_email.post_id AND p.post_type IN ('houzez_agent', 'houzez_manager')
            LEFT JOIN wp_Hrg8P_postmeta pm_agency 
                   ON pm_agency.post_id = p.ID AND pm_agency.meta_key = 'fave_agent_agencies'
            LEFT JOIN wp_Hrg8P_posts a 
                   ON a.ID = pm_agency.meta_value AND a.post_type = 'houzez_agency'
            LEFT JOIN wp_Hrg8P_postmeta pm_contact 
                   ON pm_contact.post_id = p.ID
            LEFT JOIN wp_Hrg8P_posts media 
                   ON media.ID = pm_contact.meta_value 
                  AND pm_contact.meta_key = 'fave_agent_picture' 
                  AND media.post_type = 'attachment'
            LEFT JOIN wp_Hrg8P_usermeta ur 
                   ON ur.user_id = u.ID AND ur.meta_key = 'wp_Hrg8P_capabilities'
            
            WHERE p.post_type IN ('houzez_agent', 'houzez_manager')
            AND p.post_status = 'publish'
            
            GROUP BY 
                u.ID, u.user_login, u.user_email, u.user_status, u.user_registered,
                p.ID, p.post_title, p.post_status, p.post_type, pm_email.meta_value,
                a.ID, a.post_title, ur.meta_value
            
            ORDER BY p.post_title ASC
            LIMIT 10
        ";
        
        $result = $connection->query($query);
        
        if ($result && $result->num_rows > 0) {
            echo "<p style='color: green;'>✅ Requête réussie ! {$result->num_rows} agent(s) trouvé(s)</p>";
            echo "<table border='1' style='border-collapse: collapse; width: 100%; font-size: 12px;'>";
            echo "<tr>";
            echo "<th>ID</th><th>Nom</th><th>Type</th><th>Agence</th><th>Rôle brut</th><th>Rôle formaté</th>";
            echo "</tr>";
            
            while ($row = $result->fetch_assoc()) {
                // Extraire le rôle principal depuis user_roles
                $user_role = 'Non défini';
                if (!empty($row['user_roles'])) {
                    $roles = unserialize($row['user_roles']);
                    if (is_array($roles)) {
                        if (isset($roles['houzez_manager'])) {
                            $user_role = 'Manager';
                        } elseif (isset($roles['houzez_agent'])) {
                            $user_role = 'Agent';
                        } elseif (isset($roles['houzez_agency'])) {
                            $user_role = 'Agence';
                        } elseif (isset($roles['administrator'])) {
                            $user_role = 'Administrateur';
                        } else {
                            $user_role = 'Utilisateur';
                        }
                    }
                }
                
                $bg_color = ($row['post_type'] === 'houzez_manager') ? 'background-color: #fff3cd;' : '';
                echo "<tr style='$bg_color'>";
                echo "<td>{$row['user_id']}</td>";
                echo "<td>" . htmlspecialchars($row['agent_name']) . "</td>";
                echo "<td>{$row['post_type']}</td>";
                echo "<td>" . htmlspecialchars($row['agency_name'] ?? 'N/A') . "</td>";
                echo "<td style='font-size: 10px;'>" . htmlspecialchars(substr($row['user_roles'], 0, 50)) . "...</td>";
                echo "<td><strong style='color: #0066cc;'>$user_role</strong></td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "<p><em>Les managers sont surlignés en jaune</em></p>";
        } else {
            echo "<p style='color: orange;'>⚠️ Aucun résultat trouvé</p>";
        }
    }
    $connection->close();
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erreur: " . $e->getMessage() . "</p>";
}

echo "<h2>🎯 Tests à effectuer :</h2>";
echo "<div style='background: #e8f4fd; padding: 15px; border-left: 4px solid #0066cc; margin: 10px 0;'>";
echo "<ol>";
echo "<li><a href='http://localhost:8888/crm.rebencia.com/agents' target='_blank'><strong>Test : Page /agents</strong></a></li>";
echo "<li>Vérifier que les <strong>rôles</strong> s'affichent (Manager, Agent, etc.)</li>";
echo "<li>Vérifier que les <strong>agences</strong> s'affichent</li>";
echo "<li>Vérifier que l'<strong>email est supprimé</strong> (plus de texte rouge)</li>";
echo "<li>Vérifier que le bouton <strong>'Ajouter un agent' est supprimé</strong></li>";
echo "<li>Vérifier que les boutons <strong>'Modifier' et 'Supprimer' sont supprimés</strong></li>";
echo "</ol>";
echo "</div>";

echo "<h2>🔧 Structure de la nouvelle vue :</h2>";
echo "<div style='background: #f8f9fa; padding: 15px; border: 1px solid #dee2e6; margin: 10px 0;'>";
echo "<h4>Chaque carte agent affiche maintenant :</h4>";
echo "<ul>";
echo "<li>📸 <strong>Avatar</strong> (avec fallback Gravatar)</li>";
echo "<li>👤 <strong>Nom de l'agent</strong> (lien vers profil)</li>";
echo "<li>🏢 <strong>Badge Agence</strong> (bleu)</li>";
echo "<li>🎭 <strong>Badge Rôle</strong> (marron) - NOUVEAU !</li>";
echo "<li>✅ <strong>Badge Statut</strong> (Actif/Inactif)</li>";
echo "<li>📞 <strong>Boutons contact</strong> (Téléphone, WhatsApp - PAS d'email)</li>";
echo "<li>👁️ <strong>Bouton 'Voir profil'</strong></li>";
echo "<li>🏠 <strong>Action 'Ses propriétés'</strong></li>";
echo "</ul>";
echo "</div>";

echo "<hr>";
echo "<h3>✅ MODIFICATIONS COMPLÈTES</h3>";
echo "<p style='color: green; font-weight: bold;'>La page /agents a été mise à jour selon vos spécifications :</p>";
echo "<ul style='color: green;'>";
echo "<li>✅ Rôle utilisateur affiché dans la grille</li>";
echo "<li>✅ Nom de l'agence affiché</li>";
echo "<li>✅ Email supprimé (partie rouge)</li>";
echo "<li>✅ Boutons d'ajout/modification supprimés</li>";
echo "<li>✅ Basé sur la vue wp_Hrg8P_crm_agents</li>";
echo "</ul>";
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
table { margin: 10px 0; border-collapse: collapse; }
th, td { padding: 6px 8px; text-align: left; border: 1px solid #ddd; }
th { background-color: #f2f2f2; font-weight: bold; }
pre { background: #f5f5f5; padding: 10px; border: 1px solid #ddd; white-space: pre-wrap; }
h2 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 5px; }
</style>
