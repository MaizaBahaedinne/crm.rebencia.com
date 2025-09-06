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
    // Filtres sur les métadonnées WordPress (après enrichissement)
        $this->wp_db->from('wp_Hrg8P_posts');
        $this->wp_db->where('post_type', 'property');
        $this->wp_db->where('post_status', 'publish');
        if (!empty($filters['post_author'])) {
            $this->wp_db->where('post_author', $filters['post_author']);
        }
        if (!empty($filters['post_date_gmt'])) {
            $this->wp_db->where('post_date_gmt', $filters['post_date_gmt']);
        }

    $results = $this->wp_db->get()->result();
    $filtered = [];
        foreach ($results as &$property) {
            // Récupérer la ville (taxonomie property_city)
            $ville_query = $this->wp_db->select('t.name')
                ->from('wp_Hrg8P_term_relationships tr')
                ->join('wp_Hrg8P_term_taxonomy tt', 'tr.term_taxonomy_id = tt.term_taxonomy_id', 'left')
                ->join('wp_Hrg8P_terms t', 'tt.term_id = t.term_id', 'left')
                ->where('tr.object_id', $property->ID)
                ->where('tt.taxonomy', 'property_city')
                ->get();
            $ville = $ville_query->row();
            $property->zone_nom = $ville ? $ville->name : '-';
            // Récupérer le statut (location/vente) via la taxonomie property_status
            $statut_query = $this->wp_db->select('t.name')
                ->from('wp_Hrg8P_term_relationships tr')
                ->join('wp_Hrg8P_term_taxonomy tt', 'tr.term_taxonomy_id = tt.term_taxonomy_id', 'left')
                ->join('wp_Hrg8P_terms t', 'tt.term_id = t.term_id', 'left')
                ->where('tr.object_id', $property->ID)
                ->where('tt.taxonomy', 'property_status')
                ->get();
            $statut = $statut_query->row();
            $property->statut_houzez = $statut ? $statut->name : '-';
            $ok = true;
            // Filtres sur les meta (valeurs exactes)
            if (!empty($filters['fave_property_bathrooms']) && isset($meta_map['fave_property_bathrooms']) && $meta_map['fave_property_bathrooms'] != $filters['fave_property_bathrooms']) $ok = false;
            if (!empty($filters['fave_property_bedrooms']) && isset($meta_map['fave_property_bedrooms']) && $meta_map['fave_property_bedrooms'] != $filters['fave_property_bedrooms']) $ok = false;
            if (!empty($filters['fave_property_size']) && isset($meta_map['fave_property_size']) && $meta_map['fave_property_size'] != $filters['fave_property_size']) $ok = false;
            if (!empty($filters['fave_property_size_prefix']) && isset($meta_map['fave_property_size_prefix']) && $meta_map['fave_property_size_prefix'] != $filters['fave_property_size_prefix']) $ok = false;
            if (!empty($filters['fave_property_garage']) && isset($meta_map['fave_property_garage']) && $meta_map['fave_property_garage'] != $filters['fave_property_garage']) $ok = false;
            if (!empty($filters['fave_property_year']) && isset($meta_map['fave_property_year']) && $meta_map['fave_property_year'] != $filters['fave_property_year']) $ok = false;
            if (!empty($filters['fave_property_price']) && isset($meta_map['fave_property_price']) && $meta_map['fave_property_price'] != $filters['fave_property_price']) $ok = false;
            // Filtre sur le statut (location/vente)
            if (!empty($filters['statut_houzez']) && isset($property->statut_houzez) && $property->statut_houzez != $filters['statut_houzez']) $ok = false;
            // Filtre sur la ville
            if (!empty($filters['zone_nom']) && isset($property->zone_nom) && $property->zone_nom != $filters['zone_nom']) $ok = false;
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
                $ok = true; // Initialize $ok to true before filtering
                if (!empty($filters['type_bien']) && $filters['type_bien'] !== $property->type_bien) $ok = false;
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
    
    // Propriétés d'une agence
    public function get_properties_by_agency($agency_id, $limit = null) {
        // Récupérer les agents de l'agence depuis crm_agents (base WordPress)
        $this->wp_db->select('*');
        $this->wp_db->from('crm_agents');
        $this->wp_db->where('agency_id', $agency_id);
        $agents_query = $this->wp_db->get();
        $agents = $agents_query->result();
        
        if (empty($agents)) {
            return [];
        }
        
        // Collecter les user_ids des agents (avec détection flexible des colonnes)
        $agent_user_ids = [];
        foreach ($agents as $agent) {
            // Essayer différentes colonnes possibles pour user_id
            $user_id = null;
            if (!empty($agent->user_id)) {
                $user_id = $agent->user_id;
            } elseif (!empty($agent->wp_user_id)) {
                $user_id = $agent->wp_user_id;
            } elseif (!empty($agent->id)) {
                $user_id = $agent->id;
            }
            
            if ($user_id) {
                $agent_user_ids[] = $user_id;
            }
        }
        
        if (empty($agent_user_ids)) {
            return [];
        }
        
        // Récupérer les propriétés HOUZEZ de ces agents
        $this->wp_db->select('p.*, pm1.meta_value as fave_property_price, pm2.meta_value as fave_property_address, pm3.meta_value as fave_property_size');
        $this->wp_db->from('wp_Hrg8P_posts p');
        $this->wp_db->join('wp_Hrg8P_postmeta pm1', 'p.ID = pm1.post_id AND pm1.meta_key = "fave_property_price"', 'left');
        $this->wp_db->join('wp_Hrg8P_postmeta pm2', 'p.ID = pm2.post_id AND pm2.meta_key = "fave_property_address"', 'left');
        $this->wp_db->join('wp_Hrg8P_postmeta pm3', 'p.ID = pm3.post_id AND pm3.meta_key = "fave_property_size"', 'left');
        $this->wp_db->where('p.post_type', 'property');
        $this->wp_db->where('p.post_status', 'publish');
        $this->wp_db->where_in('p.post_author', $agent_user_ids);
        $this->wp_db->order_by('p.post_date', 'DESC');
        
        if ($limit) {
            $this->wp_db->limit($limit);
        }
        
        $properties = $this->wp_db->get()->result();
        
        // Enrichir les propriétés avec les métadonnées et statuts
        foreach ($properties as &$property) {
            // Récupérer le type via taxonomie property_type
            $type_query = $this->wp_db->select('t.name')
                ->from('wp_Hrg8P_term_relationships tr')
                ->join('wp_Hrg8P_term_taxonomy tt', 'tr.term_taxonomy_id = tt.term_taxonomy_id', 'left')
                ->join('wp_Hrg8P_terms t', 'tt.term_id = t.term_id', 'left')
                ->where('tr.object_id', $property->ID)
                ->where('tt.taxonomy', 'property_type')
                ->get();
            $type = $type_query->row();
            $property->type = $type ? $type->name : 'Non spécifié';
            
            // Récupérer le statut via taxonomie property_status
            $status_query = $this->wp_db->select('t.slug')
                ->from('wp_Hrg8P_term_relationships tr')
                ->join('wp_Hrg8P_term_taxonomy tt', 'tr.term_taxonomy_id = tt.term_taxonomy_id', 'left')
                ->join('wp_Hrg8P_terms t', 'tt.term_id = t.term_id', 'left')
                ->where('tr.object_id', $property->ID)
                ->where('tt.taxonomy', 'property_status')
                ->get();
            $status = $status_query->row();
            $property->status = $status ? $status->slug : 'unknown';
            
            // Nettoyer les données
            $property->title = $property->post_title;
            $property->address = $property->fave_property_address ?: 'Adresse non fournie';
            $property->price = $property->fave_property_price ? number_format($property->fave_property_price, 0, ',', ' ') . ' €' : 'Prix sur demande';
        }
        
        return $properties;
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
