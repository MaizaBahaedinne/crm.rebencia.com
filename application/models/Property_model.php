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
        $this->wp_db->from('wp_Hrg8P_posts');
        $this->wp_db->where('post_type', 'property');
        $this->wp_db->where('post_status', 'publish');

        if (!empty($filters)) {
            $i = 0;
            foreach ($filters as $key => $value) {
                $alias = 'pm_' . $i;
                $this->wp_db->join("wp_Hrg8P_postmeta as $alias", "wp_Hrg8P_posts.ID = $alias.post_id", 'left');
                $this->wp_db->where("$alias.meta_key", $key);
                $this->wp_db->where("$alias.meta_value", $value);
                $i++;
            }
        }

        $results = $this->wp_db->get()->result();
        // Enrichir chaque propriété avec ses métadonnées
        foreach ($results as &$property) {
            $metas = $this->wp_db->where('post_id', $property->ID)->get('wp_Hrg8P_postmeta')->result();
            foreach ($metas as $meta) {
                $property->{$meta->meta_key} = $meta->meta_value;
            }
        }
        return $results;
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
