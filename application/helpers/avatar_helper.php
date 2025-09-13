<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Avatar Helper
 * Fonctions helper pour gérer l'affichage des avatars dans l'application
 */

if (!function_exists('get_agent_avatar_url')) {
    /**
     * Récupère l'URL de l'avatar d'un agent
     * 
     * @param object $agent L'objet agent
     * @return string URL de l'avatar
     */
    function get_agent_avatar_url($agent) {
        // Méthode 1: Avatar déjà dans l'objet et valide
        if (!empty($agent->agent_avatar) && 
            $agent->agent_avatar !== 'NULL' && 
            strlen($agent->agent_avatar) > 10 &&
            !strpos($agent->agent_avatar, 'avatar-1.jpg')) {
            
            $avatar_url = $agent->agent_avatar;
            
            // Correction de l'URL si elle contient localhost
            $avatar_url = str_replace('http://localhost/', 'https://rebencia.com/', $avatar_url);
            $avatar_url = str_replace('http://rebencia.com/', 'https://rebencia.com/', $avatar_url);
            
            // Vérifier que l'URL est valide
            if (filter_var($avatar_url, FILTER_VALIDATE_URL)) {
                return $avatar_url;
            }
        }
        
        // Méthode 2: Gravatar avec l'email de l'agent
        $email = '';
        if (!empty($agent->agent_email)) {
            $email = $agent->agent_email;
        } elseif (!empty($agent->user_email)) {
            $email = $agent->user_email;
        }
        
        if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $hash = md5(strtolower(trim($email)));
            return "https://www.gravatar.com/avatar/{$hash}?d=identicon&s=200";
        }
        
        // Méthode 3: Avatar par défaut basé sur le nom
        if (!empty($agent->agent_name)) {
            $initials = '';
            $name_parts = explode(' ', trim($agent->agent_name));
            foreach (array_slice($name_parts, 0, 2) as $part) {
                $initials .= strtoupper(substr($part, 0, 1));
            }
            
            if (strlen($initials) > 0) {
                // Générer une couleur basée sur le nom
                $colors = ['#667eea', '#764ba2', '#f093fb', '#f5576c', '#4facfe', '#00f2fe', '#43e97b', '#38f9d7'];
                $color_index = abs(crc32($agent->agent_name)) % count($colors);
                $bg_color = $colors[$color_index];
                
                // Créer un avatar SVG avec les initiales
                $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 200 200">';
                $svg .= '<rect width="200" height="200" fill="' . $bg_color . '"/>';
                $svg .= '<text x="100" y="120" font-family="Arial, sans-serif" font-size="80" fill="white" text-anchor="middle" font-weight="bold">' . $initials . '</text>';
                $svg .= '</svg>';
                
                return 'data:image/svg+xml;base64,' . base64_encode($svg);
            }
        }
        
        // Avatar par défaut final
        return base_url('assets/images/users/avatar-1.jpg');
    }
}

if (!function_exists('get_agency_logo_url')) {
    /**
     * Récupère l'URL du logo d'une agence
     * 
     * @param object $agency L'objet agence
     * @return string URL du logo
     */
    function get_agency_logo_url($agency) {
        if (!empty($agency->agency_logo)) {
            $logo_url = $agency->agency_logo;
            
            // Correction de l'URL si elle contient localhost
            if (strpos($logo_url, 'localhost') !== false) {
                $logo_url = str_replace('localhost/wp-content', 'rebencia.com/wp-content', $logo_url);
                $logo_url = str_replace('http://rebencia.com', 'https://rebencia.com', $logo_url);
            }
            
            return $logo_url;
        }
        
        // Logo par défaut
        return base_url('assets/images/logo-light.png');
    }
}

if (!function_exists('get_property_featured_image_url')) {
    /**
     * Récupère l'URL de l'image principale d'une propriété
     * 
     * @param object $property L'objet propriété
     * @return string URL de l'image
     */
    function get_property_featured_image_url($property) {
        if (!empty($property->featured_image)) {
            $image_url = $property->featured_image;
            
            // Correction de l'URL si elle contient localhost
            if (strpos($image_url, 'localhost') !== false) {
                $image_url = str_replace('localhost/wp-content', 'rebencia.com/wp-content', $image_url);
                $image_url = str_replace('http://rebencia.com', 'https://rebencia.com', $image_url);
            }
            
            return $image_url;
        }
        
        // Image par défaut
        return base_url('assets/images/property-placeholder.jpg');
    }
}

if (!function_exists('render_avatar_img')) {
    /**
     * Génère une balise img pour un avatar d'agent
     * 
     * @param object $agent L'objet agent
     * @param string $class Classes CSS pour l'image
     * @param string $alt Texte alternatif
     * @param string $size Taille de l'image (ex: '48x48')
     * @return string HTML de l'image
     */
    function render_avatar_img($agent, $class = 'img-fluid rounded-circle', $alt = 'Agent', $size = '') {
        $avatar_url = get_agent_avatar_url($agent);
        $size_attr = $size ? 'style="width: ' . $size . '; height: ' . $size . ';"' : '';
        
        return '<img src="' . $avatar_url . '" alt="' . $alt . '" class="' . $class . '" ' . $size_attr . '>';
    }
}

if (!function_exists('render_agency_logo_img')) {
    /**
     * Génère une balise img pour un logo d'agence
     * 
     * @param object $agency L'objet agence
     * @param string $class Classes CSS pour l'image
     * @param string $alt Texte alternatif
     * @param string $size Taille de l'image (ex: '100x60')
     * @return string HTML de l'image
     */
    function render_agency_logo_img($agency, $class = 'img-fluid rounded', $alt = 'Agency', $size = '') {
        $logo_url = get_agency_logo_url($agency);
        $size_attr = $size ? 'style="width: ' . $size . '; height: ' . $size . ';"' : '';
        $alt_text = !empty($agency->agency_name) ? $agency->agency_name : $alt;
        
        return '<img src="' . $logo_url . '" alt="' . $alt_text . '" class="' . $class . '" ' . $size_attr . '>';
    }
}

if (!function_exists('render_property_image_img')) {
    /**
     * Génère une balise img pour une image de propriété
     * 
     * @param object $property L'objet propriété
     * @param string $class Classes CSS pour l'image
     * @param string $alt Texte alternatif
     * @param string $size Taille de l'image (ex: '300x200')
     * @return string HTML de l'image
     */
    function render_property_image_img($property, $class = 'img-fluid', $alt = 'Property', $size = '') {
        $image_url = get_property_featured_image_url($property);
        $size_attr = $size ? 'style="width: ' . $size . '; height: ' . $size . '; object-fit: cover;"' : '';
        $alt_text = !empty($property->post_title) ? $property->post_title : $alt;
        
        return '<img src="' . $image_url . '" alt="' . $alt_text . '" class="' . $class . '" ' . $size_attr . '>';
    }
}
