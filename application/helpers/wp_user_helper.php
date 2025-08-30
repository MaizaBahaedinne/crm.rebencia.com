<?php
if (!function_exists('get_wp_current_user')) {
    /**
     * Récupère l'utilisateur WordPress courant depuis la session CodeIgniter
     * ou retourne null si non connecté.
     *
     * @return object|null
     */
    function get_wp_current_user() {
        $CI =& get_instance();
        // Pour CI3, on charge la session localement si besoin
        if (!class_exists('CI_Session')) {
            $CI->load->library('session');
        }
        $user = isset($CI->session) ? $CI->session->userdata('wp_user') : null;
        if ($user) {
            return (object)$user;
        }
        return null;
    }
}
