<?php
require_once 'application/config/database.php';

// Configuration de la connexion à la base de données WordPress
$wp_config = array(
    'hostname' => $db['wp']['hostname'],
    'username' => $db['wp']['username'], 
    'password' => $db['wp']['password'],
    'database' => $db['wp']['database'],
    'dbdriver' => 'mysqli'
);

$wp_db = new mysqli($wp_config['hostname'], $wp_config['username'], $wp_config['password'], $wp_config['database']);

if ($wp_db->connect_error) {
    die("Erreur de connexion: " . $wp_db->connect_error);
}

echo "<h2>Test des images des propriétés HOUZEZ</h2>";

// 1. Trouver quelques propriétés
echo "<h3>1. Propriétés disponibles:</h3>";
$properties_query = "SELECT ID, post_title FROM wp_Hrg8P_posts WHERE post_type = 'property' AND post_status = 'publish' LIMIT 5";
$properties_result = $wp_db->query($properties_query);

if ($properties_result && $properties_result->num_rows > 0) {
    while($property = $properties_result->fetch_assoc()) {
        echo "- ID: {$property['ID']} - Titre: {$property['post_title']}<br>";
        
        // 2. Vérifier les métadonnées d'images pour cette propriété
        $property_id = $property['ID'];
        $images_query = "SELECT meta_key, meta_value FROM wp_Hrg8P_postmeta 
                        WHERE post_id = $property_id 
                        AND (meta_key = '_thumbnail_id' OR meta_key = 'fave_property_images')";
        $images_result = $wp_db->query($images_query);
        
        if ($images_result && $images_result->num_rows > 0) {
            while($meta = $images_result->fetch_assoc()) {
                echo "&nbsp;&nbsp;Meta: {$meta['meta_key']} = " . substr($meta['meta_value'], 0, 100) . "...<br>";
                
                if ($meta['meta_key'] == '_thumbnail_id') {
                    // Vérifier l'URL du thumbnail
                    $thumb_id = $meta['meta_value'];
                    $thumb_query = "SELECT meta_value FROM wp_Hrg8P_postmeta WHERE post_id = $thumb_id AND meta_key = '_wp_attached_file'";
                    $thumb_result = $wp_db->query($thumb_query);
                    if ($thumb_result && $thumb_result->num_rows > 0) {
                        $thumb_data = $thumb_result->fetch_assoc();
                        echo "&nbsp;&nbsp;&nbsp;&nbsp;Thumbnail URL: https://rebencia.com/wp-content/uploads/{$thumb_data['meta_value']}<br>";
                    }
                }
                
                if ($meta['meta_key'] == 'fave_property_images') {
                    // Tenter de désérialiser
                    $gallery_data = $meta['meta_value'];
                    if (@unserialize($gallery_data) !== false) {
                        $gallery_images = unserialize($gallery_data);
                        echo "&nbsp;&nbsp;&nbsp;&nbsp;Gallery images: " . print_r($gallery_images, true) . "<br>";
                        
                        if (is_array($gallery_images)) {
                            foreach ($gallery_images as $img_id) {
                                $img_query = "SELECT meta_value FROM wp_Hrg8P_postmeta WHERE post_id = $img_id AND meta_key = '_wp_attached_file'";
                                $img_result = $wp_db->query($img_query);
                                if ($img_result && $img_result->num_rows > 0) {
                                    $img_data = $img_result->fetch_assoc();
                                    echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- https://rebencia.com/wp-content/uploads/{$img_data['meta_value']}<br>";
                                }
                            }
                        }
                    } else {
                        echo "&nbsp;&nbsp;&nbsp;&nbsp;Gallery data not serialized or invalid<br>";
                    }
                }
            }
        } else {
            echo "&nbsp;&nbsp;Aucune métadonnée d'image trouvée<br>";
        }
        echo "<br>";
    }
} else {
    echo "Aucune propriété trouvée!<br>";
}

$wp_db->close();
?>
