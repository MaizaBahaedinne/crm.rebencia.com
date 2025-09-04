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
        if (!empty($filters['post_author'])) {
            $this->wp_db->where('post_author', $filters['post_author']);
        }
        if (!empty($filters['post_date_gmt'])) {
            $this->wp_db->where('post_date_gmt', $filters['post_date_gmt']);
        }

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
        $filtered = [];
        foreach ($results as &$property) {
            $metas = $this->wp_db->where('post_id', $property->ID)->get('wp_Hrg8P_postmeta')->result();
            $meta_map = [];
            foreach ($metas as $meta) {
                $meta_map[$meta->meta_key] = $meta->meta_value;
                $property->{$meta->meta_key} = $meta->meta_value;
            }
            $property->nom = isset($property->post_title) ? $property->post_title : '-';
            if (isset($meta_map['fave_property_bedrooms']) && isset($meta_map['fave_property_bathrooms'])) {
                $property->type_bien = 'S+' . $meta_map['fave_property_bedrooms'] . ' – ' . $meta_map['fave_property_bathrooms'] . ' salle(s) de bain';
            } elseif (isset($meta_map['fave_property_bedrooms'])) {
                $property->type_bien = 'S+' . $meta_map['fave_property_bedrooms'];
            } else {
                $property->type_bien = '-';
            }
            $property->zone_nom = isset($meta_map['fave_property_address']) ? $meta_map['fave_property_address'] : '-';
            $property->surface_habitable = isset($meta_map['fave_property_size']) ? $meta_map['fave_property_size'] : '-';
            $property->prix_demande = isset($meta_map['fave_property_price']) ? $meta_map['fave_property_price'] : '-';
            $property->created_at = isset($property->post_date) ? $property->post_date : '-';

            // Application des filtres dynamiques
            $ok = true;
            if (!empty($filters['nom']) && stripos($property->nom, $filters['nom']) === false) $ok = false;
            // Type bien : doit matcher exactement S+1, S+2, etc.
            if (!empty($filters['type_bien'])) {
                $type_val = 'S+' . intval($property->fave_property_bedrooms);
                if ($filters['type_bien'] !== $type_val) $ok = false;
            }
            // Zone : doit matcher exactement la zone sélectionnée
            if (!empty($filters['zone_nom']) && isset($property->fave_property_address)) {
                if (stripos($property->fave_property_address, $filters['zone_nom']) === false) $ok = false;
            }
            // Surface (select)
            if (!empty($filters['surface_habitable']) && is_numeric($property->surface_habitable)) {
                $surf = (float)$property->surface_habitable;
                if ($filters['surface_habitable'] == '<50' && $surf >= 50) $ok = false;
                if ($filters['surface_habitable'] == '50-100' && ($surf < 50 || $surf > 100)) $ok = false;
                if ($filters['surface_habitable'] == '100-150' && ($surf < 100 || $surf > 150)) $ok = false;
                if ($filters['surface_habitable'] == '>150' && $surf <= 150) $ok = false;
            }
            // Prix (select)
            if (!empty($filters['prix_demande']) && is_numeric($property->prix_demande)) {
                $prix = (float)$property->prix_demande;
                if ($filters['prix_demande'] == '<500' && $prix >= 500) $ok = false;
                if ($filters['prix_demande'] == '500-1000' && ($prix < 500 || $prix > 1000)) $ok = false;
                if ($filters['prix_demande'] == '1000-2000' && ($prix < 1000 || $prix > 2000)) $ok = false;
                if ($filters['prix_demande'] == '>2000' && $prix <= 2000) $ok = false;
            }
            if ($ok) $filtered[] = $property;
        }
        return $filtered;
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
