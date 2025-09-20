<?php
// Script pour vérifier et créer la vue wp_Hrg8P_crm_agents
require_once('application/config/database.php');

// Configuration de la base de données
$host = 'localhost';
$username = 'rebencia_crm';
$password = 'u2E3^lKj0&';
$wordpress_db = 'rebencia_RebenciaBD';
$crm_db = 'rebencia_rebencia';

echo "<h1>Vérification de la vue wp_Hrg8P_crm_agents</h1>";

try {
    // Connexion à la base WordPress
    $wp_pdo = new PDO("mysql:host=$host;dbname=$wordpress_db;charset=utf8", $username, $password);
    $wp_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Vérifier si la vue existe
    $check_view = $wp_pdo->query("SHOW TABLES LIKE 'wp_Hrg8P_crm_agents'");
    
    if ($check_view->rowCount() > 0) {
        echo "<p style='color: green;'>✅ La vue wp_Hrg8P_crm_agents existe déjà.</p>";
        
        // Afficher la structure
        $structure = $wp_pdo->query("DESCRIBE wp_Hrg8P_crm_agents");
        echo "<h3>Structure de la vue :</h3>";
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Champ</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        while ($row = $structure->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>$value</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
        
        // Afficher quelques exemples de données
        $sample = $wp_pdo->query("SELECT * FROM wp_Hrg8P_crm_agents LIMIT 3");
        echo "<h3>Exemples de données :</h3>";
        echo "<pre>";
        while ($row = $sample->fetch(PDO::FETCH_ASSOC)) {
            print_r($row);
        }
        echo "</pre>";
        
    } else {
        echo "<p style='color: red;'>❌ La vue wp_Hrg8P_crm_agents n'existe pas.</p>";
        
        echo "<h3>Création de la vue...</h3>";
        
        // Créer la vue en combinant les données des agents WordPress et des agences
        $create_view_sql = "
        CREATE VIEW wp_Hrg8P_crm_agents AS
        SELECT 
            u.ID,
            u.user_login,
            u.user_email,
            u.display_name,
            pm_agency.meta_value as agency_id,
            pm_mobile.meta_value as fave_author_mobile,
            pm_office.meta_value as fave_author_office_phone,
            pm_picture.meta_value as fave_author_custom_picture,
            pm_about.meta_value as fave_author_about,
            pm_title.meta_value as fave_author_title,
            pm_company.meta_value as fave_author_company,
            pm_position.meta_value as fave_author_position,
            pm_license.meta_value as fave_author_license,
            pm_tax_no.meta_value as fave_author_tax_no,
            pm_service_areas.meta_value as fave_author_service_areas,
            pm_specialties.meta_value as fave_author_specialties,
            pm_facebook.meta_value as fave_author_facebook,
            pm_twitter.meta_value as fave_author_twitter,
            pm_linkedin.meta_value as fave_author_linkedin,
            pm_instagram.meta_value as fave_author_instagram,
            pm_youtube.meta_value as fave_author_youtube,
            pm_vimeo.meta_value as fave_author_vimeo,
            pm_pinterest.meta_value as fave_author_pinterest,
            pm_website.meta_value as fave_author_website,
            p.post_title as agent_post_title,
            p.post_status as agent_post_status,
            p.post_date as agent_post_date
        FROM wp_Hrg8P_users u
        INNER JOIN wp_Hrg8P_posts p ON u.ID = p.post_author AND p.post_type = 'houzez_agent'
        LEFT JOIN wp_Hrg8P_usermeta pm_agency ON u.ID = pm_agency.user_id AND pm_agency.meta_key = 'fave_author_agency_id'
        LEFT JOIN wp_Hrg8P_usermeta pm_mobile ON u.ID = pm_mobile.user_id AND pm_mobile.meta_key = 'fave_author_mobile'
        LEFT JOIN wp_Hrg8P_usermeta pm_office ON u.ID = pm_office.user_id AND pm_office.meta_key = 'fave_author_office_phone'
        LEFT JOIN wp_Hrg8P_usermeta pm_picture ON u.ID = pm_picture.user_id AND pm_picture.meta_key = 'fave_author_custom_picture'
        LEFT JOIN wp_Hrg8P_usermeta pm_about ON u.ID = pm_about.user_id AND pm_about.meta_key = 'fave_author_about'
        LEFT JOIN wp_Hrg8P_usermeta pm_title ON u.ID = pm_title.user_id AND pm_title.meta_key = 'fave_author_title'
        LEFT JOIN wp_Hrg8P_usermeta pm_company ON u.ID = pm_company.user_id AND pm_company.meta_key = 'fave_author_company'
        LEFT JOIN wp_Hrg8P_usermeta pm_position ON u.ID = pm_position.user_id AND pm_position.meta_key = 'fave_author_position'
        LEFT JOIN wp_Hrg8P_usermeta pm_license ON u.ID = pm_license.user_id AND pm_license.meta_key = 'fave_author_license'
        LEFT JOIN wp_Hrg8P_usermeta pm_tax_no ON u.ID = pm_tax_no.user_id AND pm_tax_no.meta_key = 'fave_author_tax_no'
        LEFT JOIN wp_Hrg8P_usermeta pm_service_areas ON u.ID = pm_service_areas.user_id AND pm_service_areas.meta_key = 'fave_author_service_areas'
        LEFT JOIN wp_Hrg8P_usermeta pm_specialties ON u.ID = pm_specialties.user_id AND pm_specialties.meta_key = 'fave_author_specialties'
        LEFT JOIN wp_Hrg8P_usermeta pm_facebook ON u.ID = pm_facebook.user_id AND pm_facebook.meta_key = 'fave_author_facebook'
        LEFT JOIN wp_Hrg8P_usermeta pm_twitter ON u.ID = pm_twitter.user_id AND pm_twitter.meta_key = 'fave_author_twitter'
        LEFT JOIN wp_Hrg8P_usermeta pm_linkedin ON u.ID = pm_linkedin.user_id AND pm_linkedin.meta_key = 'fave_author_linkedin'
        LEFT JOIN wp_Hrg8P_usermeta pm_instagram ON u.ID = pm_instagram.user_id AND pm_instagram.meta_key = 'fave_author_instagram'
        LEFT JOIN wp_Hrg8P_usermeta pm_youtube ON u.ID = pm_youtube.user_id AND pm_youtube.meta_key = 'fave_author_youtube'
        LEFT JOIN wp_Hrg8P_usermeta pm_vimeo ON u.ID = pm_vimeo.user_id AND pm_vimeo.meta_key = 'fave_author_vimeo'
        LEFT JOIN wp_Hrg8P_usermeta pm_pinterest ON u.ID = pm_pinterest.user_id AND pm_pinterest.meta_key = 'fave_author_pinterest'
        LEFT JOIN wp_Hrg8P_usermeta pm_website ON u.ID = pm_website.user_id AND pm_website.meta_key = 'fave_author_website'
        WHERE p.post_status = 'publish'
        ";
        
        $wp_pdo->exec($create_view_sql);
        echo "<p style='color: green;'>✅ Vue wp_Hrg8P_crm_agents créée avec succès !</p>";
        
        // Vérifier le contenu de la vue créée
        $sample = $wp_pdo->query("SELECT COUNT(*) as total FROM wp_Hrg8P_crm_agents");
        $count = $sample->fetch(PDO::FETCH_ASSOC);
        echo "<p>Nombre d'agents dans la vue : <strong>{$count['total']}</strong></p>";
        
        if ($count['total'] > 0) {
            $sample_data = $wp_pdo->query("SELECT * FROM wp_Hrg8P_crm_agents LIMIT 3");
            echo "<h3>Exemples de données :</h3>";
            echo "<pre>";
            while ($row = $sample_data->fetch(PDO::FETCH_ASSOC)) {
                print_r($row);
            }
            echo "</pre>";
        }
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Erreur : " . $e->getMessage() . "</p>";
}

echo "<p><a href='" . base_url('dashboard/manager') . "'>← Retour au Dashboard Manager</a></p>";
?>
