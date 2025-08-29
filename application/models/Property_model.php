<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Property_model extends CI_Model {
    protected $wp_db;
    public function __construct() {
        parent::__construct();
        $this->wp_db = $this->load->database('wordpress', TRUE);
    }
    // Toutes les propriétés (avec filtres)
    public function get_all_properties($filters = []) {
        $this->wp_db->from('wp_posts');
        $this->wp_db->where('post_type', 'property');
        $this->wp_db->where('post_status', 'publish');

        // Ajout des filtres dynamiques selon la structure Houzez
        if (!empty($filters)) {
            foreach ($filters as $key => $value) {
                // Exemple: filtre par meta_key (ACF ou meta Houzez)
                $this->wp_db->join('wp_postmeta as pm_' . $key, 'wp_posts.ID = pm_' . $key . '.post_id', 'left');
                $this->wp_db->where('pm_' . $key . '.meta_key', $key);
                $this->wp_db->where('pm_' . $key . '.meta_value', $value);
            }
        }

        return $this->wp_db->get()->result();
    }
    // Une propriété
    public function get_property($property_id) {
        return $this->wp_db->where('ID', $property_id)->get('wp_Hrg8P_posts')->row();
    }
    // Propriétés d'un agent
    public function get_properties_by_agent($agent_id) {
        // À adapter selon structure
        return [];
    }
    // Statistiques propriétés (exemple)
    public function get_properties_stats() {
        // À adapter selon structure
        return [
            'total' => 0,
            'sold' => 0,
            'rented' => 0
        ];
    }
}
