<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Property_model extends CI_Model {
    protected $wp_db;
    public function __construct() {
        parent::__construct();
        $this->wp_db = $this->load->database('wordpress', TRUE);
    }
    // Toutes les propriétés (avec filtres)
    public function get_all_properties($filters = []) 
    {
    $this->wp_db->from('wp_Hrg8P_prop_agen');
    $this->wp_db->where('property_status', 'publish');

    // Filtres simples sur les colonnes principales
    if (!empty($filters['agent'])) {
        $this->wp_db->where('agent_id', $filters['agent']);
    }
    if (!empty($filters['property_id'])) {
        $this->wp_db->where_in('ID', (array)$filters['property_id']);
    }
    if (!empty($filters['post_date_gmt'])) {
        $this->wp_db->where('post_date_gmt', $filters['post_date_gmt']);
    }

    $results = $this->wp_db->get()->result();
    return $results;
    }


    // Une propriété
    public function get_property($property_id) {
        return $this->wp_db->where('ID', $property_id)->get('wp_Hrg8P_posts')->row();
    }
    
    // Métadonnées d'une propriété sous forme de tableau clé => valeur
    public function get_property_metas($property_id) {
        $metas = $this->wp_db->where('post_id', $property_id)->get('wp_Hrg8P_postmeta')->result();
        $meta_array = [];
        foreach ($metas as $meta) {
            $meta_array[$meta->meta_key] = $meta->meta_value;
        }
        return $meta_array;
    }
    
    // Propriétés similaires
    public function get_similar_properties($property_id, $limit = 4) {
        $current_property = $this->get_property($property_id);
        if (!$current_property) return [];
        
        $this->wp_db->select('p.*, pm1.meta_value as fave_property_price, pm2.meta_value as fave_property_address');
        $this->wp_db->from('wp_Hrg8P_posts p');
        $this->wp_db->join('wp_Hrg8P_postmeta pm1', 'p.ID = pm1.post_id AND pm1.meta_key = "fave_property_price"', 'left');
        $this->wp_db->join('wp_Hrg8P_postmeta pm2', 'p.ID = pm2.post_id AND pm2.meta_key = "fave_property_address"', 'left');
        $this->wp_db->where('p.post_type', 'property');
        $this->wp_db->where('p.post_status', 'publish');
        $this->wp_db->where('p.ID !=', $property_id);
        $this->wp_db->order_by('p.post_date', 'DESC');
        $this->wp_db->limit($limit);
        
        return $this->wp_db->get()->result();
    }
    
    // Recherche de propriétés
    public function search_properties($term, $limit = 10) {
        $this->wp_db->select('p.*, pm1.meta_value as fave_property_price, pm2.meta_value as fave_property_address');
        $this->wp_db->from('wp_Hrg8P_posts p');
        $this->wp_db->join('wp_Hrg8P_postmeta pm1', 'p.ID = pm1.post_id AND pm1.meta_key = "fave_property_price"', 'left');
        $this->wp_db->join('wp_Hrg8P_postmeta pm2', 'p.ID = pm2.post_id AND pm2.meta_key = "fave_property_address"', 'left');
        $this->wp_db->where('p.post_type', 'property');
        $this->wp_db->where('p.post_status', 'publish');
        $this->wp_db->group_start();
        $this->wp_db->like('p.post_title', $term);
        $this->wp_db->or_like('p.post_content', $term);
        $this->wp_db->or_like('pm2.meta_value', $term);
        $this->wp_db->group_end();
        $this->wp_db->order_by('p.post_date', 'DESC');
        $this->wp_db->limit($limit);
        
        return $this->wp_db->get()->result();
    }
    
    // Récupérer tous les statuts de propriétés depuis HOUZEZ
    public function get_property_statuses() {
        $this->wp_db->select('t.term_id, t.name, t.slug');
        $this->wp_db->from('wp_Hrg8P_terms t');
        $this->wp_db->join('wp_Hrg8P_term_taxonomy tt', 't.term_id = tt.term_id', 'inner');
        $this->wp_db->where('tt.taxonomy', 'property_status');
        $this->wp_db->order_by('t.name', 'ASC');
        
        return $this->wp_db->get()->result();
    }
    
    // Récupérer tous les types de propriétés depuis HOUZEZ
    public function get_property_types() {
        $this->wp_db->select('t.term_id, t.name, t.slug');
        $this->wp_db->from('wp_Hrg8P_terms t');
        $this->wp_db->join('wp_Hrg8P_term_taxonomy tt', 't.term_id = tt.term_id', 'inner');
        $this->wp_db->where('tt.taxonomy', 'property_type');
        $this->wp_db->order_by('t.name', 'ASC');
        
        return $this->wp_db->get()->result();
    }
    
    // Récupérer les villes/zones depuis HOUZEZ
    public function get_property_cities() {
        $this->wp_db->select('t.term_id, t.name, t.slug');
        $this->wp_db->from('wp_Hrg8P_terms t');
        $this->wp_db->join('wp_Hrg8P_term_taxonomy tt', 't.term_id = tt.term_id', 'inner');
        $this->wp_db->where('tt.taxonomy', 'property_city');
        $this->wp_db->order_by('t.name', 'ASC');
        
        return $this->wp_db->get()->result();
    }
    
    // Récupérer le statut d'une propriété spécifique
    public function get_property_status($property_id) {
        $this->wp_db->select('t.name, t.slug');
        $this->wp_db->from('wp_Hrg8P_terms t');
        $this->wp_db->join('wp_Hrg8P_term_taxonomy tt', 't.term_id = tt.term_id', 'inner');
        $this->wp_db->join('wp_Hrg8P_term_relationships tr', 'tt.term_taxonomy_id = tr.term_taxonomy_id', 'inner');
        $this->wp_db->where('tt.taxonomy', 'property_status');
        $this->wp_db->where('tr.object_id', $property_id);
        
        $result = $this->wp_db->get()->row();
        return $result ? $result : (object)['name' => 'Non défini', 'slug' => 'undefined'];
    }
    
    // Récupérer le type d'une propriété spécifique
    public function get_property_type($property_id) {
        $this->wp_db->select('t.name, t.slug');
        $this->wp_db->from('wp_Hrg8P_terms t');
        $this->wp_db->join('wp_Hrg8P_term_taxonomy tt', 't.term_id = tt.term_id', 'inner');
        $this->wp_db->join('wp_Hrg8P_term_relationships tr', 'tt.term_taxonomy_id = tr.term_taxonomy_id', 'inner');
        $this->wp_db->where('tt.taxonomy', 'property_type');
        $this->wp_db->where('tr.object_id', $property_id);
        
        $result = $this->wp_db->get()->row();
        return $result ? $result : (object)['name' => 'Non défini', 'slug' => 'undefined'];
    }
    
    // Récupérer les images d'une propriété (utilise la requête SQL fournie par l'utilisateur)
    public function get_property_images($property_id) {
        // Utiliser exactement la requête fournie par l'utilisateur
        $query = "SELECT p.ID as property_id, p.post_title, m.meta_key, m.meta_value 
                  FROM wp_Hrg8P_posts p 
                  JOIN wp_Hrg8P_postmeta m ON p.ID = m.post_id 
                  WHERE p.post_type = 'property' 
                  AND p.ID = ? 
                  AND (m.meta_key = '_thumbnail_id' OR m.meta_key = 'fave_property_images')";
        
        $results = $this->wp_db->query($query, [$property_id])->result();
        
        // Debug: afficher ce qui est récupéré
        error_log("DEBUG Property Images for ID $property_id - Found " . count($results) . " meta records");
        
        $images = [];
        foreach ($results as $result) {
            error_log("  Meta: {$result->meta_key} = " . substr($result->meta_value, 0, 100) . "...");
            
            if ($result->meta_key == '_thumbnail_id') {
                // Récupérer l'URL de l'image principale
                $thumbnail_url = $this->get_attachment_url($result->meta_value);
                error_log("  Thumbnail URL for ID {$result->meta_value}: $thumbnail_url");
                if ($thumbnail_url) {
                    $images['thumbnail'] = $thumbnail_url;
                }
            } elseif ($result->meta_key == 'fave_property_images') {
                // Les images de galerie sont stockées sérialisées
                $gallery_data = $result->meta_value;
                error_log("  Raw gallery data length: " . strlen($gallery_data));
                
                // Tenter plusieurs approches de désérialisation
                $gallery_images = null;
                
                // 1. Vérifier si c'est sérialisé PHP
                if ($this->is_serialized($gallery_data)) {
                    $gallery_images = @unserialize($gallery_data);
                    error_log("  Unserialized as PHP: " . print_r($gallery_images, true));
                }
                
                // 2. Vérifier si c'est JSON
                if (!$gallery_images) {
                    $json_data = @json_decode($gallery_data, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $gallery_images = $json_data;
                        error_log("  Decoded as JSON: " . print_r($gallery_images, true));
                    }
                }
                
                // 3. Vérifier si c'est une liste d'IDs séparés par des virgules
                if (!$gallery_images && strpos($gallery_data, ',') !== false) {
                    $gallery_images = explode(',', $gallery_data);
                    $gallery_images = array_map('trim', $gallery_images);
                    error_log("  Split by comma: " . print_r($gallery_images, true));
                }
                
                if (is_array($gallery_images) && !empty($gallery_images)) {
                    $images['gallery'] = [];
                    foreach ($gallery_images as $image_id) {
                        if (!empty($image_id) && is_numeric($image_id)) {
                            $image_url = $this->get_attachment_url($image_id);
                            error_log("    Gallery image ID $image_id -> URL: $image_url");
                            if ($image_url) {
                                $images['gallery'][] = $image_url;
                            }
                        }
                    }
                } else {
                    error_log("  Gallery images is not a valid array: " . print_r($gallery_images, true));
                }
            }
        }
        
        error_log("Final images result: " . print_r($images, true));
        return $images;
    }
    
    // Récupérer l'URL d'un attachement WordPress
    private function get_attachment_url($attachment_id) {
        if (!$attachment_id) return null;
        
        $this->wp_db->select('meta_value');
        $this->wp_db->from('wp_Hrg8P_postmeta');
        $this->wp_db->where('post_id', $attachment_id);
        $this->wp_db->where('meta_key', '_wp_attached_file');
        
        $result = $this->wp_db->get()->row();
        
        if ($result && $result->meta_value) {
            // Construire l'URL complète vers le site officiel rebencia.com
            return 'https://rebencia.com/wp-content/uploads/' . $result->meta_value;
        }
        
        return null;
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
            if (!empty($agent->agent_post_id)) {
                $user_id = $agent->agent_post_id;
            }

            if ($user_id) {
                $agent_user_ids[] = $user_id;
            }
        }

        if (empty($agent_user_ids)) {
            return [];
        }
        
        // Récupérer les propriétés HOUZEZ de ces agents
        $this->wp_db->select('p.*, pm1.meta_value as fave_property_price, pm2.meta_value as fave_property_address, pm3.meta_value as fave_property_size, pm4.meta_value as fave_agents');
        $this->wp_db->from('wp_Hrg8P_posts p');
        $this->wp_db->join('wp_Hrg8P_postmeta pm1', 'p.ID = pm1.post_id AND pm1.meta_key = "fave_property_price"', 'left');
        $this->wp_db->join('wp_Hrg8P_postmeta pm2', 'p.ID = pm2.post_id AND pm2.meta_key = "fave_property_address"', 'left');
        $this->wp_db->join('wp_Hrg8P_postmeta pm3', 'p.ID = pm3.post_id AND pm3.meta_key = "fave_property_size"', 'left');
        $this->wp_db->join('wp_Hrg8P_postmeta pm4', 'p.ID = pm4.post_id AND pm4.meta_key = "fave_agents"', 'left');
        $this->wp_db->where('p.post_type', 'property');
        $this->wp_db->where('p.post_status', 'publish');
        $this->wp_db->where_in('pm4.meta_value', $agent_user_ids);
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
            $property->price = $property->fave_property_price ? number_format($property->fave_property_price, 0, ',', ' ') . ' TND' : 'Prix sur demande';
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
    
    /**
     * Vérifie si une chaîne est sérialisée (équivalent WordPress)
     */
    private function is_serialized($data) {
        // Si ce n'est pas une chaîne, ce n'est pas sérialisé
        if (!is_string($data)) {
            return false;
        }
        
        $data = trim($data);
        if ('N;' == $data) {
            return true;
        }
        
        if (strlen($data) < 4) {
            return false;
        }
        
        if (':' !== $data[1]) {
            return false;
        }
        
        $lastc = substr($data, -1);
        if (';' !== $lastc && '}' !== $lastc) {
            return false;
        }
        
        $token = $data[0];
        switch ($token) {
            case 's':
                if ('"' !== substr($data, -2, 1)) {
                    return false;
                }
                // Pas de break intentionnel
            case 'a':
            case 'O':
                return (bool) preg_match("/^{$token}:[0-9]+:/s", $data);
            case 'b':
            case 'i':
            case 'd':
                $end = '';
                return (bool) preg_match("/^{$token}:[0-9.E+-]+;{$end}$/", $data);
        }
        
        return false;
    }

    /**
     * Compte les propriétés d'un agent par son user_id
     * @param int $user_id
     * @return int
     */
    public function count_properties_by_agent($user_id) {
        if (!$user_id) return 0;
        
        // D'abord, récupérer l'agent_id à partir du user_id
        $agent_query = $this->wp_db->select('p.ID as agent_id')
            ->from('wp_Hrg8P_users u')
            ->join('wp_Hrg8P_postmeta pm_email', 'pm_email.meta_value = u.user_email AND pm_email.meta_key = "fave_agent_email"', 'inner')
            ->join('wp_Hrg8P_posts p', 'p.ID = pm_email.post_id AND p.post_type = "houzez_agent"', 'inner')
            ->where('u.ID', $user_id)
            ->get()->row();
        
        if (!$agent_query) return 0;
        
        $agent_id = $agent_query->agent_id;
        
        // Compter les propriétés de cet agent
        return (int)$this->wp_db->from('wp_Hrg8P_posts p')
            ->join('wp_Hrg8P_postmeta pm', 'p.ID = pm.post_id AND pm.meta_key = "fave_property_agent"', 'inner')
            ->where('pm.meta_value', $agent_id)
            ->where('p.post_type', 'property')
            ->where('p.post_status', 'publish')
            ->count_all_results();
    }

    /**
     * Récupère toutes les propriétés avec leurs agents et agences liés
     * Utilise une requête complexe pour optimiser les jointures
     * 
     * @return array Liste des propriétés avec agent et agence
     */
    public function get_properties_with_agents_agencies() {
        $sql = "
        SELECT 
            p.ID AS property_id,
            p.post_title AS property_title,
            p.post_status AS property_status,
            p.post_date AS property_date,
            
            -- Agent lié
            a.ID AS agent_id,
            a.post_title AS agent_name,
            agent_email.meta_value AS agent_email,
            agent_phone.meta_value AS agent_phone,
            img.guid AS agent_photo,
            
            -- Agence finale (priorité : agence de l'agent, sinon agence affectée directement à la propriété)
            COALESCE(ag_agent.ID, ag_prop.ID) AS agency_id,
            COALESCE(ag_agent.post_title, ag_prop.post_title) AS agency_name,
            COALESCE(agency_agent_phone.meta_value, agency_prop_phone.meta_value) AS agency_phone,
            COALESCE(agency_agent_email.meta_value, agency_prop_email.meta_value) AS agency_email
            
        FROM " . $this->wp_db->dbprefix('posts') . " p

        -- Lien propriété -> agent
        LEFT JOIN " . $this->wp_db->dbprefix('postmeta') . " pm_agent 
               ON pm_agent.post_id = p.ID AND pm_agent.meta_key = 'fave_agents'
        LEFT JOIN " . $this->wp_db->dbprefix('posts') . " a 
               ON a.ID = pm_agent.meta_value AND a.post_type = 'houzez_agent'

        -- Infos agent
        LEFT JOIN " . $this->wp_db->dbprefix('postmeta') . " agent_email 
               ON agent_email.post_id = a.ID AND agent_email.meta_key = 'fave_agent_email'
        LEFT JOIN " . $this->wp_db->dbprefix('postmeta') . " agent_phone 
               ON agent_phone.post_id = a.ID AND agent_phone.meta_key = 'fave_agent_mobile'

        -- Photo agent
        LEFT JOIN " . $this->wp_db->dbprefix('postmeta') . " agent_thumb 
               ON agent_thumb.post_id = a.ID AND agent_thumb.meta_key = '_thumbnail_id'
        LEFT JOIN " . $this->wp_db->dbprefix('posts') . " img 
               ON img.ID = agent_thumb.meta_value

        -- Agence depuis l'agent
        LEFT JOIN " . $this->wp_db->dbprefix('postmeta') . " pm_agency_agent 
               ON pm_agency_agent.post_id = a.ID AND pm_agency_agent.meta_key = 'fave_agent_agencies'
        LEFT JOIN " . $this->wp_db->dbprefix('posts') . " ag_agent 
               ON ag_agent.ID = pm_agency_agent.meta_value AND ag_agent.post_type = 'houzez_agency'
        LEFT JOIN " . $this->wp_db->dbprefix('postmeta') . " agency_agent_phone 
               ON agency_agent_phone.post_id = ag_agent.ID AND agency_agent_phone.meta_key = 'fave_agency_phone'
        LEFT JOIN " . $this->wp_db->dbprefix('postmeta') . " agency_agent_email 
               ON agency_agent_email.post_id = ag_agent.ID AND agency_agent_email.meta_key = 'fave_agency_email'

        -- Agence directement liée à la propriété
        LEFT JOIN " . $this->wp_db->dbprefix('postmeta') . " pm_agency_prop 
               ON pm_agency_prop.post_id = p.ID AND pm_agency_prop.meta_key = 'fave_property_agency'
        LEFT JOIN " . $this->wp_db->dbprefix('posts') . " ag_prop 
               ON ag_prop.ID = pm_agency_prop.meta_value AND ag_prop.post_type = 'houzez_agency'
        LEFT JOIN " . $this->wp_db->dbprefix('postmeta') . " agency_prop_phone 
               ON agency_prop_phone.post_id = ag_prop.ID AND agency_prop_phone.meta_key = 'fave_agency_phone'
        LEFT JOIN " . $this->wp_db->dbprefix('postmeta') . " agency_prop_email 
               ON agency_prop_email.post_id = ag_prop.ID AND agency_prop_email.meta_key = 'fave_agency_email'

        WHERE p.post_type = 'property' 
        AND p.post_status = 'publish'
        ORDER BY p.post_date DESC
        ";

        return $this->wp_db->query($sql)->result();
    }

    /**
     * Crée la vue wp_Hrg8P_prop_agen pour optimiser les requêtes futures
     * 
     * @return bool Succès de la création
     */
    public function create_property_agent_view() {
        $view_name = $this->wp_db->dbprefix('prop_agen');
        
        $sql = "
        CREATE OR REPLACE VIEW {$view_name} AS
        SELECT 
            p.ID AS property_id,
            p.post_title AS property_title,
            p.post_status AS property_status,
            p.post_date AS property_date,
            
            -- Agent lié
            a.ID AS agent_id,
            a.post_title AS agent_name,
            agent_email.meta_value AS agent_email,
            agent_phone.meta_value AS agent_phone,
            img.guid AS agent_photo,
            
            -- Agence finale (priorité : agence de l'agent, sinon agence affectée directement à la propriété)
            COALESCE(ag_agent.ID, ag_prop.ID) AS agency_id,
            COALESCE(ag_agent.post_title, ag_prop.post_title) AS agency_name,
            COALESCE(agency_agent_phone.meta_value, agency_prop_phone.meta_value) AS agency_phone,
            COALESCE(agency_agent_email.meta_value, agency_prop_email.meta_value) AS agency_email
            
        FROM " . $this->wp_db->dbprefix('posts') . " p

        -- Lien propriété -> agent
        LEFT JOIN " . $this->wp_db->dbprefix('postmeta') . " pm_agent 
               ON pm_agent.post_id = p.ID AND pm_agent.meta_key = 'fave_agents'
        LEFT JOIN " . $this->wp_db->dbprefix('posts') . " a 
               ON a.ID = pm_agent.meta_value AND a.post_type = 'houzez_agent'

        -- Infos agent
        LEFT JOIN " . $this->wp_db->dbprefix('postmeta') . " agent_email 
               ON agent_email.post_id = a.ID AND agent_email.meta_key = 'fave_agent_email'
        LEFT JOIN " . $this->wp_db->dbprefix('postmeta') . " agent_phone 
               ON agent_phone.post_id = a.ID AND agent_phone.meta_key = 'fave_agent_mobile'

        -- Photo agent
        LEFT JOIN " . $this->wp_db->dbprefix('postmeta') . " agent_thumb 
               ON agent_thumb.post_id = a.ID AND agent_thumb.meta_key = '_thumbnail_id'
        LEFT JOIN " . $this->wp_db->dbprefix('posts') . " img 
               ON img.ID = agent_thumb.meta_value

        -- Agence depuis l'agent
        LEFT JOIN " . $this->wp_db->dbprefix('postmeta') . " pm_agency_agent 
               ON pm_agency_agent.post_id = a.ID AND pm_agency_agent.meta_key = 'fave_agent_agencies'
        LEFT JOIN " . $this->wp_db->dbprefix('posts') . " ag_agent 
               ON ag_agent.ID = pm_agency_agent.meta_value AND ag_agent.post_type = 'houzez_agency'
        LEFT JOIN " . $this->wp_db->dbprefix('postmeta') . " agency_agent_phone 
               ON agency_agent_phone.post_id = ag_agent.ID AND agency_agent_phone.meta_key = 'fave_agency_phone'
        LEFT JOIN " . $this->wp_db->dbprefix('postmeta') . " agency_agent_email 
               ON agency_agent_email.post_id = ag_agent.ID AND agency_agent_email.meta_key = 'fave_agency_email'

        -- Agence directement liée à la propriété
        LEFT JOIN " . $this->wp_db->dbprefix('postmeta') . " pm_agency_prop 
               ON pm_agency_prop.post_id = p.ID AND pm_agency_prop.meta_key = 'fave_property_agency'
        LEFT JOIN " . $this->wp_db->dbprefix('posts') . " ag_prop 
               ON ag_prop.ID = pm_agency_prop.meta_value AND ag_prop.post_type = 'houzez_agency'
        LEFT JOIN " . $this->wp_db->dbprefix('postmeta') . " agency_prop_phone 
               ON agency_prop_phone.post_id = ag_prop.ID AND agency_prop_phone.meta_key = 'fave_agency_phone'
        LEFT JOIN " . $this->wp_db->dbprefix('postmeta') . " agency_prop_email 
               ON agency_prop_email.post_id = ag_prop.ID AND agency_prop_email.meta_key = 'fave_agency_email'

        WHERE p.post_type = 'property'
        ";

        try {
            $this->wp_db->query($sql);
            return true;
        } catch (Exception $e) {
            log_message('error', 'Erreur création vue prop_agen: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Utilise la vue pour récupérer les données optimisées
     * 
     * @param array $filters Filtres optionnels
     * @return array Résultats de la vue
     */
    public function get_from_property_agent_view($filters = []) {
        $view_name = $this->wp_db->dbprefix('prop_agen');
        
        $this->wp_db->from($view_name);
        
        // Appliquer les filtres si fournis
        if (!empty($filters['agent_id'])) {
            $this->wp_db->where('agent_id', $filters['agent_id']);
        }
        
        if (!empty($filters['agency_id'])) {
            $this->wp_db->where('agency_id', $filters['agency_id']);
        }
        
        if (!empty($filters['property_status'])) {
            $this->wp_db->where('property_status', $filters['property_status']);
        }
        
        if (isset($filters['limit'])) {
            $this->wp_db->limit($filters['limit']);
        }
        
        $this->wp_db->order_by('property_date', 'DESC');
        
        return $this->wp_db->get()->result();
    }


  public function get_properties_agency($agency_id) 
{
    $view_name = $this->wp_db->dbprefix('prop_agen');
    $this->wp_db->from($view_name);
    $this->wp_db->where('agency_id', $agency_id);
    $prop = $this->wp_db->get()->result();
    return $this->get_all_properties(['property_id' => array_column($prop, 'property_id')]);
}

       
}
