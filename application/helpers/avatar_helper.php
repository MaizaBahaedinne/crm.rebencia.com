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
        if (!empty($agent->agent_avatar)) {
            $avatar_url = $agent->agent_avatar;
            
            // Correction de l'URL si elle contient localhost
            $avatar_url = str_replace('http://localhost/', 'https://rebencia.com/', $avatar_url);
            $avatar_url = str_replace('http://rebencia.com/', 'https://rebencia.com/', $avatar_url);
            
            if (filter_var($avatar_url, FILTER_VALIDATE_URL)) {
                return $avatar_url;
            }
        }
        
        // Fallback vers Gravatar
        $email = !empty($agent->agent_email) ? $agent->agent_email : (isset($agent->user_email) ? $agent->user_email : '');
        if (!empty($email)) {
            $hash = md5(strtolower(trim($email)));
            return "https://www.gravatar.com/avatar/{$hash}?d=identicon&s=200";
        }
        
        // Avatar par défaut
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
