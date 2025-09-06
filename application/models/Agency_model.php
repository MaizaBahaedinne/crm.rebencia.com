<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agency_model extends CI_Model {
    /**
     * Connexion DB WordPress
     * @var CI_DB_query_builder
     */
    protected $wp_db;
    protected $users_table;
    protected $usermeta_table;
    protected $posts_table;
    protected $postmeta_table;
    protected $capabilities_key; // meta_key des rôles sérialisés

    protected $wanted_meta_keys = [
        'agency_email',
        'agency_phone',
        'agency_address',
        'agency_logo',
        'agency_website',
        'agency_facebook',
        'agency_instagram',
        'agency_whatsapp',
        'agency_linkedin'
    ];

    public function __construct() {
        parent::__construct();
        $this->wp_db = $this->load->database('wordpress', TRUE);
        // Normalisation des noms avec dbprefix (qui contient déjà wp_Hrg8P_)
        $this->users_table    = $this->wp_db->dbprefix('users');
        $this->usermeta_table = $this->wp_db->dbprefix('usermeta');
        $this->posts_table    = $this->wp_db->dbprefix('posts');
        $this->postmeta_table = $this->wp_db->dbprefix('postmeta');
        $this->capabilities_key = $this->wp_db->dbprefix('capabilities'); // ex: wp_Hrg8P_capabilities
    }

    /**
     * Retourne toutes les agences depuis la table posts (post_type = houzez_agency)
     * @return object[]
     */
    public function get_all_agencies() {
        $this->wp_db->select("
            p.ID as agency_id,
            p.post_title as agency_name,
            p.post_content as agency_description,
            p.post_status as post_status,
            p.post_date as created_date,
            MAX(CASE WHEN pm.meta_key = 'fave_agency_email' THEN pm.meta_value END) as agency_email,
            MAX(CASE WHEN pm.meta_key = 'fave_agency_phone' THEN pm.meta_value END) as phone,
            MAX(CASE WHEN pm.meta_key = 'fave_agency_mobile' THEN pm.meta_value END) as mobile,
            MAX(CASE WHEN pm.meta_key = 'fave_agency_address' THEN pm.meta_value END) as agency_address,
            MAX(CASE WHEN pm.meta_key = 'fave_agency_web' THEN pm.meta_value END) as website,
            MAX(CASE WHEN pm.meta_key = 'fave_agency_facebook' THEN pm.meta_value END) as facebook,
            MAX(CASE WHEN pm.meta_key = 'fave_agency_twitter' THEN pm.meta_value END) as twitter,
            MAX(CASE WHEN pm.meta_key = 'fave_agency_linkedin' THEN pm.meta_value END) as linkedin,
            MAX(CASE WHEN pm.meta_key = 'fave_agency_picture' THEN media.guid END) as agency_logo
        ", FALSE);
        
        $agencies = $this->wp_db->from($this->posts_table . ' p')
            ->join($this->postmeta_table . ' pm', 'pm.post_id = p.ID', 'left')
            ->join($this->posts_table . ' media', 'media.ID = pm.meta_value AND pm.meta_key = "fave_agency_picture" AND media.post_type = "attachment"', 'left')
            ->where('p.post_type', 'houzez_agency')
            ->where('p.post_status', 'publish')
            ->group_by('p.ID, p.post_title, p.post_content, p.post_status, p.post_date')
            ->get()->result();

        return $agencies;
    }

    /** Une agence avec métadonnées */
    public function get_agency($agency_id) {
        $this->wp_db->select("
            p.ID as agency_id,
            p.post_title as agency_name,
            p.post_content as agency_description,
            p.post_status as post_status,
            p.post_date as created_date,
            MAX(CASE WHEN pm.meta_key = 'fave_agency_email' THEN pm.meta_value END) as agency_email,
            MAX(CASE WHEN pm.meta_key = 'fave_agency_phone' THEN pm.meta_value END) as phone,
            MAX(CASE WHEN pm.meta_key = 'fave_agency_mobile' THEN pm.meta_value END) as mobile,
            MAX(CASE WHEN pm.meta_key = 'fave_agency_address' THEN pm.meta_value END) as agency_address,
            MAX(CASE WHEN pm.meta_key = 'fave_agency_web' THEN pm.meta_value END) as website,
            MAX(CASE WHEN pm.meta_key = 'fave_agency_facebook' THEN pm.meta_value END) as facebook,
            MAX(CASE WHEN pm.meta_key = 'fave_agency_twitter' THEN pm.meta_value END) as twitter,
            MAX(CASE WHEN pm.meta_key = 'fave_agency_linkedin' THEN pm.meta_value END) as linkedin,
            MAX(CASE WHEN pm.meta_key = 'fave_agency_picture' THEN media.guid END) as agency_logo
        ", FALSE);
        
        $agency = $this->wp_db->from($this->posts_table . ' p')
            ->join($this->postmeta_table . ' pm', 'pm.post_id = p.ID', 'left')
            ->join($this->posts_table . ' media', 'media.ID = pm.meta_value AND pm.meta_key = "fave_agency_picture" AND media.post_type = "attachment"', 'left')
            ->where('p.ID', (int)$agency_id)
            ->where('p.post_type', 'houzez_agency')
            ->group_by('p.ID, p.post_title, p.post_content, p.post_status, p.post_date')
            ->get()->row();
            
        return $agency;
    }

    /** Créer une agence (attention: ne gère pas mot de passe WP/roles) */
    public function create_agency($data) {
    $this->wp_db->insert($this->users_table, $data);
    return $this->getInsertId();
    }

    public function update_agency($agency_id, $data) {
        return $this->wp_db->where('ID', (int)$agency_id)->update($this->users_table, $data);
    }

    public function delete_agency($agency_id) {
        return $this->wp_db->where('ID', (int)$agency_id)->delete($this->users_table);
    }

    /** Wrapper pour insert_id (compat analyseur) */
    protected function getInsertId() {
        if(method_exists($this->wp_db, 'insert_id')) {
            return $this->wp_db->insert_id();
        }
        return 0;
    }

    /**
     * Retourne toutes les agences avec statistiques et filtres
     * @param array $filters Filtres de recherche
     * @return object[]
     */
    public function get_agencies_with_stats($filters = []) {
        // Récupérer toutes les agences depuis la table posts
        $agencies = $this->get_all_agencies();
        
        // Appliquer les filtres
        if (!empty($filters['search'])) {
            $search = strtolower($filters['search']);
            $agencies = array_filter($agencies, function($agency) use ($search) {
                return strpos(strtolower($agency->agency_name), $search) !== false ||
                       strpos(strtolower($agency->agency_email ?? ''), $search) !== false ||
                       strpos(strtolower($agency->agency_address ?? ''), $search) !== false;
            });
        }

        if (!empty($filters['ville'])) {
            $ville = strtolower($filters['ville']);
            $agencies = array_filter($agencies, function($agency) use ($ville) {
                return strpos(strtolower($agency->agency_address ?? ''), $ville) !== false;
            });
        }

        // Ajouter les statistiques pour chaque agence
        foreach ($agencies as $agency) {
            $agency->agents_count = $this->count_agents($agency->agency_id);
            $agency->properties_count = $this->count_properties($agency->agency_id);
            $agency->sales_count = $this->count_sales($agency->agency_id);
        }

        return array_values($agencies); // Réindexer le tableau
    }

    /**
     * Retourne les détails complets d'une agence
     * @param int $agency_id
     * @return object|null
     */
    public function get_agency_details($agency_id) {
        $agency = $this->get_agency($agency_id);
        if (!$agency) return null;

        // Ajouter les statistiques détaillées
        $agency->agents_count = $this->count_agents($agency_id);
        $agency->properties_count = $this->count_properties($agency_id);
        $agency->sales_count = $this->count_sales($agency_id);
        $agency->active_properties = $this->count_active_properties($agency_id);
        $agency->sold_properties = $this->count_sold_properties($agency_id);

        return $agency;
    }

    /**
     * Compte les ventes de l'agence (propriétés vendues/louées)
     * @param int $agency_id
     * @return int
     */
    public function count_sales($agency_id) {
        return (int)$this->wp_db->from($this->posts_table.' p')
            ->join($this->postmeta_table.' pm_agency', 'p.ID = pm_agency.post_id AND pm_agency.meta_key = "fave_property_agency"', 'inner', false)
            ->join($this->postmeta_table.' pm_status', 'p.ID = pm_status.post_id AND pm_status.meta_key = "fave_property_status"', 'inner', false)
            ->where('pm_agency.meta_value', (int)$agency_id)
            ->where('p.post_type', 'property')
            ->where_in('pm_status.meta_value', ['sold', 'rented'])
            ->where('p.post_status !=', 'trash')
            ->count_all_results();
    }

    /**
     * Compte les propriétés actives (à vendre/à louer)
     * @param int $agency_id
     * @return int
     */
    public function count_active_properties($agency_id) {
        return (int)$this->wp_db->from($this->posts_table.' p')
            ->join($this->postmeta_table.' pm_agency', 'p.ID = pm_agency.post_id AND pm_agency.meta_key = "fave_property_agency"', 'inner', false)
            ->join($this->postmeta_table.' pm_status', 'p.ID = pm_status.post_id AND pm_status.meta_key = "fave_property_status"', 'inner', false)
            ->where('pm_agency.meta_value', (int)$agency_id)
            ->where('p.post_type', 'property')
            ->where_in('pm_status.meta_value', ['for-sale', 'for-rent'])
            ->where('p.post_status', 'publish')
            ->count_all_results();
    }

    /**
     * Compte les propriétés vendues/louées
     * @param int $agency_id
     * @return int
     */
    public function count_sold_properties($agency_id) {
        return $this->count_sales($agency_id); // Alias
    }

    /**
     * Statistiques complètes d'une agence
     * @param int $agency_id
     * @return array
     */
    public function get_agency_stats($agency_id) {
        return [
            'agents' => $this->count_agents($agency_id),
            'properties' => $this->count_properties($agency_id)
        ];
    }

    /** Compte les agents liés via usermeta.houzez_agency_id */
    public function count_agents($agency_id) {
        return (int)$this->wp_db->from($this->users_table.' u')
            ->join($this->usermeta_table.' m', 'u.ID = m.user_id AND m.meta_key = "houzez_agency_id"', 'inner', false)
            ->where('m.meta_value', (int)$agency_id)
            ->count_all_results();
    }

    /**
     * Compte les propriétés associées à l'agence.
     * Hypothèse: post_type = 'property' et postmeta (meta_key=fave_property_agency) = agency_id
     * Adapter si autre clé utilisée.
     */
    public function count_properties($agency_id) {
        return (int)$this->wp_db->from($this->posts_table.' p')
            ->join($this->postmeta_table.' pm', 'p.ID = pm.post_id AND pm.meta_key = "fave_property_agency"', 'inner', false)
            ->where('pm.meta_value', (int)$agency_id)
            ->where('p.post_type','property')
            ->where('p.post_status !=','trash')
            ->count_all_results();
    }
}
