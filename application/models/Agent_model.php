<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agent_model extends CI_Model {
    /**
     * Connexion DB WordPress
     * @var CI_DB_query_builder
     */
    protected $wp_db;
    protected $posts_table;
    protected $postmeta_table;
    protected $users_table;
    protected $usermeta_table;

    public function __construct() {
        parent::__construct();
        $this->wp_db = $this->load->database('wordpress', TRUE);
        // Tables WordPress HOUZEZ
        $this->posts_table = $this->wp_db->dbprefix('posts');
        $this->postmeta_table = $this->wp_db->dbprefix('postmeta');
        $this->users_table = $this->wp_db->dbprefix('users');
        $this->usermeta_table = $this->wp_db->dbprefix('usermeta');
    }

    /**
     * Retourne tous les agents HOUZEZ avec leurs informations complètes
     * Utilise une requête optimisée similaire à        return $stats;
    }

    /**
     * Récupère l'avatar WordPress pour un utilisateur
     * @param int $user_id ID utilisateur WordPress
     * @param string $user_email Email de l'utilisateur
     * @return string|null URL de l'avatar
     */
    private function get_wordpress_avatar($user_id, $user_email) {
        // Méthode 1: Chercher dans wp_usermeta pour l'avatar HOUZEZ
        $avatar_meta = $this->wp_db->select('meta_value')
            ->from($this->usermeta_table)
            ->where('user_id', $user_id)
            ->where_in('meta_key', ['fave_author_custom_picture', 'wp_user_avatar', 'avatar', 'profile_picture'])
            ->get()->row();

        if ($avatar_meta && !empty($avatar_meta->meta_value)) {
            // Si c'est un ID d'attachment, récupérer l'URL
            if (is_numeric($avatar_meta->meta_value)) {
                $attachment = $this->wp_db->select('guid')
                    ->from($this->posts_table)
                    ->where('ID', $avatar_meta->meta_value)
                    ->where('post_type', 'attachment')
                    ->get()->row();
                
                if ($attachment) {
                    // Corriger l'URL vers le domaine de production
                    $avatar_url = str_replace('http://localhost/', 'https://rebencia.com/', $attachment->guid);
                    return str_replace('http://rebencia.com/', 'https://rebencia.com/', $avatar_url);
                }
            } else {
                // Si c'est déjà une URL, la corriger
                $avatar_url = str_replace('http://localhost/', 'https://rebencia.com/', $avatar_meta->meta_value);
                return str_replace('http://rebencia.com/', 'https://rebencia.com/', $avatar_url);
            }
        }

        // Méthode 2: Chercher s'il y a une image de profil attachée directement
        $profile_picture = $this->wp_db->select('media.guid')
            ->from($this->postmeta_table . ' pm')
            ->join($this->posts_table . ' media', 'media.ID = pm.meta_value', 'inner')
            ->where('pm.post_id IN (
                SELECT p.ID FROM ' . $this->posts_table . ' p
                INNER JOIN ' . $this->postmeta_table . ' pm2 ON pm2.post_id = p.ID
                WHERE p.post_type = "houzez_agent" 
                AND pm2.meta_key = "fave_agent_email" 
                AND pm2.meta_value = "' . $this->wp_db->escape_str($user_email) . '"
            )', NULL, FALSE)
            ->where('pm.meta_key', 'fave_author_custom_picture')
            ->where('media.post_type', 'attachment')
            ->get()->row();

        if ($profile_picture) {
            // Corriger l'URL vers le domaine de production
            $avatar_url = str_replace('http://localhost/', 'https://rebencia.com/', $profile_picture->guid);
            return str_replace('http://rebencia.com/', 'https://rebencia.com/', $avatar_url);
        }

        // Méthode 3: Utiliser Gravatar comme fallback
        return $this->get_gravatar_url($user_email);
    }

    /**
     * Génère l'URL Gravatar pour un email
     * @param string $email
     * @return string
     */
    private function get_gravatar_url($email) {
        $hash = md5(strtolower(trim($email)));
        return "https://www.gravatar.com/avatar/{$hash}?d=identicon&s=200";
    }

    /**
     * Retourne tous les agents depuis wp_posts uniquement (sans wp_users)
     * @param array $filters Filtres de recherche
     * @return object[]
     */
    public function get_all_agents_from_posts($filters = []) {
        $this->wp_db->select("
            p.ID as agent_id,
            p.ID as user_id,
            p.post_title as agent_name,
            p.post_content as agent_description,
            p.post_status as post_status,
            CASE 
                WHEN p.post_status = 'publish' THEN 1 
                ELSE 0 
            END as is_active,
            p.post_date as created_date,
            p.post_date as registration_date,
            p.post_modified as modified_date,
            a.ID as agency_id,
            a.post_title as agency_name,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_email' THEN pm.meta_value END) as agent_email,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_phone' THEN pm.meta_value END) as phone,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_mobile' THEN pm.meta_value END) as mobile,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_whatsapp' THEN pm.meta_value END) as whatsapp,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_skype' THEN pm.meta_value END) as skype,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_website' THEN pm.meta_value END) as website,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_position' THEN pm.meta_value END) as position,
            MAX(CASE WHEN pm.meta_key = 'fave_author_custom_picture' THEN 
                CASE 
                    WHEN pm.meta_value IS NOT NULL AND pm.meta_value != '' THEN 
                        (SELECT REPLACE(guid, 'http://localhost/', 'https://rebencia.com/') 
                         FROM {$this->posts_table} 
                         WHERE ID = pm.meta_value AND post_type = 'attachment' LIMIT 1)
                    ELSE NULL
                END
            END) as agent_avatar,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_facebook' THEN pm.meta_value END) as facebook,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_twitter' THEN pm.meta_value END) as twitter,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_linkedin' THEN pm.meta_value END) as linkedin,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_instagram' THEN pm.meta_value END) as instagram,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_address' THEN pm.meta_value END) as address,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_zip' THEN pm.meta_value END) as postal_code,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_city' THEN pm.meta_value END) as city,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_country' THEN pm.meta_value END) as country,
            (SELECT COUNT(*) FROM {$this->posts_table} prop 
             INNER JOIN {$this->postmeta_table} pm_prop ON prop.ID = pm_prop.post_id 
             WHERE pm_prop.meta_key = 'fave_property_agent' 
             AND pm_prop.meta_value = p.ID 
             AND prop.post_type = 'property' 
             AND prop.post_status = 'publish'
            ) as properties_count
        ", FALSE);
        
        $this->wp_db->from($this->posts_table . ' p')
            ->join('wp_Hrg8P_crm_avatar_agents avatar', 'avatar.agent_post_id = p.ID', 'left')
            ->join($this->postmeta_table . ' pm_agency', 'pm_agency.post_id = p.ID AND pm_agency.meta_key = "fave_agent_agencies"', 'left')
            ->join($this->posts_table . ' a', 'a.ID = pm_agency.meta_value AND a.post_type = "houzez_agency"', 'left')
            ->join($this->postmeta_table . ' pm', 'pm.post_id = p.ID', 'left')
            ->where_in('p.post_type', ['houzez_agent', 'houzez_manager'])
            ->where('p.post_status', 'publish');

        // Appliquer les filtres
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $this->wp_db->group_start()
                ->like('p.post_title', $search)
                ->or_like('p.post_content', $search)
                ->group_end();
        }

        if (!empty($filters['agency'])) {
            $this->wp_db->where('a.ID', $filters['agency']);
        }

        $this->wp_db->group_by('p.ID, p.post_title, p.post_content, p.post_status, p.post_date, p.post_modified, a.ID, a.post_title');
        $this->wp_db->order_by('p.post_title', 'ASC');

        $query = $this->wp_db->get();
        $agents = $query->result();

        // Nettoyer et améliorer les données
        foreach ($agents as $agent) {
            $agent = $this->clean_agent_data($agent);
        }

        // Affecter les URLs d'avatar pour chaque agent
        $agents = $this->assign_avatar_urls($agents);

        return $agents;
    }

    /**
     * Retourne tous les agents HOUZEZ avec leurs informations complètes
     * Utilise une requête optimisée similaire à celle fournie
     * @param array $filters Filtres de recherche
     * @return object[]
     */
    public function get_all_agents($filters = []) {
        $this->wp_db->select("
            u.ID as user_id,
            u.user_login as user_login,
            u.user_email as user_email,
            u.user_status as user_status,
            u.user_registered as registration_date,
            p.ID as agent_id,
            p.post_title as agent_name,
            p.post_content as description,
            p.post_status as post_status,
            pm_email.meta_value as agent_email,
            a.ID as agency_id,
            a.post_title as agency_name,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_phone' THEN pm.meta_value END) as phone,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_mobile' THEN pm.meta_value END) as mobile,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_whatsapp' THEN pm.meta_value END) as whatsapp,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_skype' THEN pm.meta_value END) as skype,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_website' THEN pm.meta_value END) as website,
            MAX(CASE WHEN pm.meta_key = 'fave_author_custom_picture' THEN 
                CASE 
                    WHEN pm.meta_value IS NOT NULL AND pm.meta_value != '' THEN 
                        (SELECT REPLACE(guid, 'http://localhost/', 'https://rebencia.com/') 
                         FROM {$this->posts_table} 
                         WHERE ID = pm.meta_value AND post_type = 'attachment' LIMIT 1)
                    ELSE NULL
                END
            END) as agent_avatar,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_position' THEN pm.meta_value END) as position,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_facebook' THEN pm.meta_value END) as facebook,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_twitter' THEN pm.meta_value END) as twitter,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_linkedin' THEN pm.meta_value END) as linkedin,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_instagram' THEN pm.meta_value END) as instagram,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_address' THEN pm.meta_value END) as address,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_zip' THEN pm.meta_value END) as postal_code,
            (SELECT COUNT(*) FROM {$this->posts_table} prop 
             INNER JOIN {$this->postmeta_table} pm_prop ON prop.ID = pm_prop.post_id 
             WHERE pm_prop.meta_key = 'fave_property_agent' 
             AND pm_prop.meta_value = p.ID 
             AND prop.post_type = 'property' 
             AND prop.post_status = 'publish'
            ) as properties_count
        ", FALSE);
        
        $this->wp_db->from($this->users_table . ' u')
            ->join($this->postmeta_table . ' pm_email', 'pm_email.meta_value = u.user_email AND pm_email.meta_key = "fave_agent_email"', 'inner')
            ->join($this->posts_table . ' p', 'p.ID = pm_email.post_id AND p.post_type IN ("houzez_agent", "houzez_manager") AND p.post_status = "publish"', 'inner')
            ->join($this->postmeta_table . ' pm_agency', 'pm_agency.post_id = p.ID AND pm_agency.meta_key = "fave_agent_agencies"', 'left')
            ->join($this->posts_table . ' a', 'a.ID = pm_agency.meta_value AND a.post_type = "houzez_agency"', 'left')
            ->join($this->postmeta_table . ' pm', 'pm.post_id = p.ID', 'left');

        // Appliquer les filtres
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $this->wp_db->group_start()
                ->like('p.post_title', $search)
                ->or_like('u.user_email', $search)
                ->or_like('u.user_login', $search)
                ->group_end();
        }

        if (!empty($filters['agency'])) {
            $this->wp_db->where('a.ID', $filters['agency']);
        }

        if (!empty($filters['status'])) {
            if ($filters['status'] == 'active') {
                $this->wp_db->where('u.user_status', '0');
            } else if ($filters['status'] == 'inactive') {
                $this->wp_db->where('u.user_status', '1');
            }
        }

        $this->wp_db->group_by('u.ID, u.user_login, u.user_email, u.user_status, u.user_registered, p.ID, p.post_title, p.post_content, p.post_status, pm_email.meta_value, a.ID, a.post_title');

        // Appliquer le tri
        if (!empty($filters['sort'])) {
            switch ($filters['sort']) {
                case 'name_desc':
                    $this->wp_db->order_by('p.post_title', 'DESC');
                    break;
                case 'properties_desc':
                    $this->wp_db->order_by('properties_count', 'DESC');
                    break;
                case 'recent':
                    $this->wp_db->order_by('u.user_registered', 'DESC');
                    break;
                default: // 'name_asc'
                    $this->wp_db->order_by('p.post_title', 'ASC');
            }
        } else {
            $this->wp_db->order_by('p.post_title', 'ASC');
        }

        $agents = $this->wp_db->get()->result();
        
        // Post-traitement pour ajouter des champs calculés
        foreach ($agents as $agent) {
            // Avatar par défaut si vide
            if (empty($agent->agent_avatar)) {
                $agent->agent_avatar = base_url('assets/images/users/avatar-1.jpg');
            }
            
            // Déterminer si l'agent est actif
            $agent->is_active = ($agent->user_status == '0' && $agent->post_status == 'publish');
            
            // Formater la date d'inscription
            if ($agent->registration_date) {
                $agent->registration_date = date('Y-m-d H:i:s', strtotime($agent->registration_date));
            }
        }

        return $agents;
    }

    /**
     * Agents d'une agence spécifique - approche hybride CRM + HOUZEZ
     * @param int $agency_id
     * @return object[]
     */
    public function get_agents_by_agency($agency_id) {
        
    
        // Récupérer aussi les agents CRM de cette agence
        $crm_agents = $this->wp_db->select('*')
            ->from('crm_agents')
            ->where('agency_id', $agency_id)
            ->get()->result();
        
        // Fusionner les deux sources : priorité à HOUZEZ, compléter avec CRM
        
       
        
        return $crm_agents;
    }
    
    /**
     * Récupère un agent HOUZEZ par email
     * @param string $email
     * @return object|null
     */
    private function get_houzez_agent_by_email($email) {
        if (empty($email)) return null;
        
        $this->wp_db->select("
            p.ID as agent_id,
            p.post_title as agent_name,
            p.post_content as agent_description,
            p.post_status as post_status,
            p.post_date as created_date,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_email' THEN pm.meta_value END) as agent_email,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_phone' THEN pm.meta_value END) as phone,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_position' THEN pm.meta_value END) as position,
            MAX(CASE WHEN pm.meta_key = 'fave_author_custom_picture' THEN 
                CASE 
                    WHEN media.guid IS NOT NULL THEN 
                        REPLACE(media.guid, 'http://localhost/', 'https://rebencia.com/')
                    ELSE NULL
                END
            END) as agent_avatar
        ", FALSE);
        
        $agent = $this->wp_db->from($this->posts_table . ' p')
            ->join($this->postmeta_table . ' pm', 'pm.post_id = p.ID', 'left')
            ->join($this->posts_table . ' media', 'media.ID = pm.meta_value AND pm.meta_key = "fave_author_custom_picture" AND media.post_type = "attachment"', 'left')
            ->where('p.post_type', 'houzez_agent')
            ->having('MAX(CASE WHEN pm.meta_key = "fave_agent_email" THEN pm.meta_value END) =', $email)
            ->group_by('p.ID, p.post_title, p.post_content, p.post_status, p.post_date')
            ->get()->row();
            
        return $agent;
    }

    /**
     * Retourne un agent spécifique par user_id
     * @param int $user_id
     * @return object|null
     */
    public function get_agent_by_user_id($user_id) {
        $this->wp_db->select("
            u.ID as user_id,
            u.user_login as user_login,
            u.user_email as user_email,
            u.user_status as user_status,
            u.user_registered as registration_date,
            p.ID as agent_id,
            p.post_title as agent_name,
            p.post_content as description,
            p.post_status as post_status,
            pm_email.meta_value as agent_email,
            a.ID as agency_id,
            a.post_title as agency_name,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_phone' THEN pm.meta_value END) as phone,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_mobile' THEN pm.meta_value END) as mobile,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_whatsapp' THEN pm.meta_value END) as whatsapp,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_skype' THEN pm.meta_value END) as skype,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_website' THEN pm.meta_value END) as website,
            MAX(CASE WHEN pm.meta_key = 'fave_author_custom_picture' THEN 
                CASE 
                    WHEN pm.meta_value IS NOT NULL AND pm.meta_value != '' THEN 
                        (SELECT REPLACE(guid, 'http://localhost/', 'https://rebencia.com/') 
                         FROM {$this->posts_table} 
                         WHERE ID = pm.meta_value AND post_type = 'attachment' LIMIT 1)
                    ELSE NULL
                END
            END) as agent_avatar,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_position' THEN pm.meta_value END) as position,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_facebook' THEN pm.meta_value END) as facebook,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_twitter' THEN pm.meta_value END) as twitter,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_linkedin' THEN pm.meta_value END) as linkedin,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_instagram' THEN pm.meta_value END) as instagram,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_address' THEN pm.meta_value END) as address,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_zip' THEN pm.meta_value END) as postal_code,
            (SELECT COUNT(*) FROM {$this->posts_table} prop 
             INNER JOIN {$this->postmeta_table} pm_prop ON prop.ID = pm_prop.post_id 
             WHERE pm_prop.meta_key = 'fave_property_agent' 
             AND pm_prop.meta_value = p.ID 
             AND prop.post_type = 'property' 
             AND prop.post_status = 'publish'
            ) as properties_count
        ", FALSE);
        
        $agent = $this->wp_db->from($this->users_table . ' u')
            ->join($this->postmeta_table . ' pm_email', 'pm_email.meta_value = u.user_email AND pm_email.meta_key = "fave_agent_email"', 'inner')
            ->join($this->posts_table . ' p', 'p.ID = pm_email.post_id AND p.post_type = "houzez_agent"', 'inner')
            ->join($this->postmeta_table . ' pm_agency', 'pm_agency.post_id = p.ID AND pm_agency.meta_key = "fave_agent_agencies"', 'left')
            ->join($this->posts_table . ' a', 'a.ID = pm_agency.meta_value AND a.post_type = "houzez_agency"', 'left')
            ->join($this->postmeta_table . ' pm', 'pm.post_id = p.ID', 'left')
            ->where('u.ID', $user_id)
            ->where('p.post_status', 'publish')
            ->group_by('u.ID, u.user_login, u.user_email, u.user_status, u.user_registered, p.ID, p.post_title, p.post_content, p.post_status, pm_email.meta_value, a.ID, a.post_title')
            ->get()->row();

        if ($agent) {
            // Avatar par défaut si vide
            if (empty($agent->agent_avatar)) {
                $agent->agent_avatar = $this->get_wordpress_avatar($agent->user_id, $agent->user_email);
            }
            
            // Si toujours vide, utiliser l'avatar par défaut
            if (empty($agent->agent_avatar)) {
                $agent->agent_avatar = base_url('assets/images/users/avatar-1.jpg');
            }
            
            // Déterminer si l'agent est actif
            $agent->is_active = ($agent->user_status == '0' && $agent->post_status == 'publish');
            
            // Ajouter des statistiques supplémentaires
            $agent->total_views = $this->get_agent_total_views($agent->agent_id);
            $agent->contacts_count = $this->get_agent_contacts_count($agent->agent_id);
            $agent->leads_count = $this->get_agent_leads_count($agent->agent_id);
            
            // Nettoyer les données manifestement fausses
            $agent = $this->clean_agent_data($agent);
        }

        return $agent;
    }

    /**
     * Récupère les propriétés d'un agent
     * @param int $agent_id
     * @param int $limit
     * @return object[]
     */
    public function get_agent_properties($agent_id, $limit = null) {
        $this->load->model('Property_model');
        
        $this->wp_db->select("
            p.ID,
            p.post_title as title,
            p.post_content as description,
            p.post_date,
            p.post_status,
            MAX(CASE WHEN pm.meta_key = 'fave_property_price' THEN pm.meta_value END) as price,
            MAX(CASE WHEN pm.meta_key = 'fave_property_size' THEN pm.meta_value END) as size,
            MAX(CASE WHEN pm.meta_key = 'fave_property_bedrooms' THEN pm.meta_value END) as bedrooms,
            MAX(CASE WHEN pm.meta_key = 'fave_property_bathrooms' THEN pm.meta_value END) as bathrooms,
            MAX(CASE WHEN pm.meta_key = 'fave_property_address' THEN pm.meta_value END) as location,
            MAX(CASE WHEN pm.meta_key = 'fave_property_images' THEN pm.meta_value END) as images,
            (SELECT guid FROM {$this->posts_table} img WHERE img.ID = (
                SELECT meta_value FROM {$this->postmeta_table} thumb 
                WHERE thumb.post_id = p.ID AND thumb.meta_key = '_thumbnail_id' LIMIT 1
            )) as thumbnail,
            (SELECT name FROM {$this->wp_db->dbprefix}term_taxonomy tt 
             INNER JOIN {$this->wp_db->dbprefix}terms t ON tt.term_id = t.term_id 
             INNER JOIN {$this->wp_db->dbprefix}term_relationships tr ON tt.term_taxonomy_id = tr.term_taxonomy_id 
             WHERE tr.object_id = p.ID AND tt.taxonomy = 'property_status' LIMIT 1) as status,
            (SELECT name FROM {$this->wp_db->dbprefix}term_taxonomy tt 
             INNER JOIN {$this->wp_db->dbprefix}terms t ON tt.term_id = t.term_id 
             INNER JOIN {$this->wp_db->dbprefix}term_relationships tr ON tt.term_taxonomy_id = tr.term_taxonomy_id 
             WHERE tr.object_id = p.ID AND tt.taxonomy = 'property_type' LIMIT 1) as property_type,
            COALESCE((SELECT meta_value FROM {$this->postmeta_table} WHERE post_id = p.ID AND meta_key = 'fave_total_property_views'), 0) as views
        ", FALSE);

        $this->wp_db->from($this->posts_table . ' p')
            ->join($this->postmeta_table . ' pm', 'p.ID = pm.post_id', 'left')
            ->join($this->postmeta_table . ' pm_agent', 'p.ID = pm_agent.post_id AND pm_agent.meta_key = "fave_property_agent"', 'inner')
            ->where('pm_agent.meta_value', $agent_id)
            ->where('p.post_type', 'property')
            ->where('p.post_status', 'publish')
            ->group_by('p.ID, p.post_title, p.post_content, p.post_date, p.post_status')
            ->order_by('p.post_date', 'DESC');

        if ($limit) {
            $this->wp_db->limit($limit);
        }

        return $this->wp_db->get()->result();
    }

    /**
     * Récupère les vues totales des propriétés d'un agent
     * @param int $agent_id
     * @return int
     */
    private function get_agent_total_views($agent_id) {
        $result = $this->wp_db->select_sum('CAST(pm_views.meta_value AS UNSIGNED)', 'total_views')
            ->from($this->posts_table . ' p')
            ->join($this->postmeta_table . ' pm_agent', 'p.ID = pm_agent.post_id AND pm_agent.meta_key = "fave_property_agent"', 'inner')
            ->join($this->postmeta_table . ' pm_views', 'p.ID = pm_views.post_id AND pm_views.meta_key = "fave_total_property_views"', 'left')
            ->where('pm_agent.meta_value', $agent_id)
            ->where('p.post_type', 'property')
            ->where('p.post_status', 'publish')
            ->get()->row();

        return (int)($result->total_views ?? 0);
    }

    /**
     * Récupère le nombre de contacts d'un agent
     * @param int $agent_id
     * @return int
     */
    private function get_agent_contacts_count($agent_id) {
        // Compter les clients depuis la table crm_clients
        $query = $this->db->query("
            SELECT COUNT(*) as count 
            FROM crm_clients 
            WHERE agent_id = ?
        ", [$agent_id]);
        
        $result = $query->row();
        return $result ? (int)$result->count : 0;
    }

    /**
     * Récupère le nombre de leads d'un agent
     * @param int $agent_id
     * @return int
     */
    private function get_agent_leads_count($agent_id) {
        try {
            // Vérifier si la table crm_leads existe
            $tables = $this->db->query("SHOW TABLES LIKE 'crm_leads'")->result();
            if (empty($tables)) {
                return 0; // Table n'existe pas encore
            }
            
            // Compter les leads depuis la table crm_leads
            $query = $this->db->query("
                SELECT COUNT(*) as count 
                FROM crm_leads 
                WHERE agent_id = ? 
                AND (deleted_at IS NULL OR deleted_at = '0000-00-00 00:00:00')
            ", [$agent_id]);
            
            $result = $query->row();
            return $result ? (int)$result->count : 0;
        } catch (Exception $e) {
            // En cas d'erreur, retourner 0
            return 0;
        }
    }

    /**
     * Retourne un agent spécifique
     * @param int $agent_id
     * @return object|null
     */
    public function get_agent($agent_id) {
        $this->wp_db->select("
            p.ID as agent_id,
            p.post_title as agent_name,
            p.post_content as agent_description,
            p.post_status as post_status,
            CASE 
                WHEN p.post_status = 'publish' THEN 1 
                ELSE 0 
            END as is_active,
            p.post_date as created_date,
            p.post_date as registration_date,
            a.ID as agency_id,
            a.post_title as agency_name,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_email' THEN pm.meta_value END) as agent_email,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_phone' THEN pm.meta_value END) as phone,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_mobile' THEN pm.meta_value END) as mobile,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_whatsapp' THEN pm.meta_value END) as whatsapp,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_skype' THEN pm.meta_value END) as skype,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_website' THEN pm.meta_value END) as website,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_position' THEN pm.meta_value END) as position,
            avatar.image_url as agent_avatar,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_facebook' THEN pm.meta_value END) as facebook,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_twitter' THEN pm.meta_value END) as twitter,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_linkedin' THEN pm.meta_value END) as linkedin,
            MAX(CASE WHEN pm.meta_key = 'fave_agent_instagram' THEN pm.meta_value END) as instagram
        ", FALSE);
        
        $this->wp_db->from($this->posts_table . ' p')
            ->join('wp_Hrg8P_crm_avatar_agents avatar', 'avatar.agent_post_id = p.ID', 'left')
            ->join($this->postmeta_table . ' pm_agency', 'pm_agency.post_id = p.ID AND pm_agency.meta_key = "fave_agent_agencies"', 'left')
            ->join($this->posts_table . ' a', 'a.ID = pm_agency.meta_value AND a.post_type = "houzez_agency"', 'left')
            ->join($this->postmeta_table . ' pm', 'pm.post_id = p.ID', 'left')
            ->where('p.ID', $agent_id)
            ->where('p.post_type', 'houzez_agent')
            ->group_by('p.ID, p.post_title, p.post_content, p.post_status, p.post_date, a.ID, a.post_title');

        $agent = $this->wp_db->get()->row();
        
        if ($agent) {
            $agent = $this->clean_agent_data($agent);
            // Affecter l'URL d'avatar
            if (!isset($agent->agent_avatar) || empty($agent->agent_avatar)) {
                $agent->agent_avatar = $this->get_agent_avatar_url_by_id($agent->agent_id);
            }
        }
        
        return $agent;
    }

    /**
     * Compte le nombre total d'agents
     * @return int
     */
    public function count_agents() {
        return (int)$this->wp_db->from($this->posts_table)
            ->where('post_type', 'houzez_agent')
            ->where('post_status', 'publish')
            ->count_all_results();
    }

    /**
     * Compte les propriétés d'un agent
     * @param int $agent_id
     * @return int
     */
    public function count_properties($agent_id) {
        return (int)$this->wp_db->from($this->posts_table . ' p')
            ->join($this->postmeta_table . ' pm', 'p.ID = pm.post_id AND pm.meta_key = "fave_property_agent"', 'inner')
            ->where('pm.meta_value', $agent_id)
            ->where('p.post_type', 'property')
            ->where('p.post_status', 'publish')
            ->count_all_results();
    }

    /**
     * Statistiques d'un agent
     * @param int $agent_id
     * @return array
     */
    public function get_agent_stats($agent_id) {
        $properties_count = $this->count_properties($agent_id);
        
        // Propriétés actives
        $active_properties = (int)$this->wp_db->from($this->posts_table . ' p')
            ->join($this->postmeta_table . ' pm_agent', 'p.ID = pm_agent.post_id AND pm_agent.meta_key = "fave_property_agent"', 'inner')
            ->join($this->postmeta_table . ' pm_status', 'p.ID = pm_status.post_id AND pm_status.meta_key = "fave_property_status"', 'inner')
            ->where('pm_agent.meta_value', $agent_id)
            ->where('p.post_type', 'property')
            ->where('p.post_status', 'publish')
            ->where_in('pm_status.meta_value', ['for-sale', 'for-rent'])
            ->count_all_results();

        // Propriétés vendues/louées
        $sold_properties = (int)$this->wp_db->from($this->posts_table . ' p')
            ->join($this->postmeta_table . ' pm_agent', 'p.ID = pm_agent.post_id AND pm_agent.meta_key = "fave_property_agent"', 'inner')
            ->join($this->postmeta_table . ' pm_status', 'p.ID = pm_status.post_id AND pm_status.meta_key = "fave_property_status"', 'inner')
            ->where('pm_agent.meta_value', $agent_id)
            ->where('p.post_type', 'property')
            ->where_in('pm_status.meta_value', ['sold', 'rented'])
            ->count_all_results();

        return [
            'total_properties' => $properties_count,
            'active_properties' => $active_properties,
            'sold_properties' => $sold_properties,
            'success_rate' => $properties_count > 0 ? round(($sold_properties / $properties_count) * 100, 1) : 0
        ];
    }

    /**
     * Retourne tous les agents avec leurs statistiques
     * @param array $filters
     * @return object[]
     */
    public function get_agents_with_stats($filters = []) {
        $agents = $this->get_all_agents($filters);
        
        // Ajouter les statistiques pour chaque agent
        foreach ($agents as $agent) {
            if ($agent->agent_id) {
                $stats = $this->get_agent_stats($agent->agent_id);
                $agent->total_properties = $stats['total_properties'];
                $agent->active_properties = $stats['active_properties'];
                $agent->sold_properties = $stats['sold_properties'];
                $agent->success_rate = $stats['success_rate'];
            } else {
                $agent->total_properties = 0;
                $agent->active_properties = 0;
                $agent->sold_properties = 0;
                $agent->success_rate = 0;
            }
        }
        
        return $agents;
    }

    /**
     * Retourne les détails complets d'un agent
     * @param int $agent_id
     * @return object|null
     */
    public function get_agent_details($agent_id) {
        $agent = $this->get_agent($agent_id);
        if (!$agent) return null;

        // Ajouter les statistiques
        if ($agent->agent_id) {
            $stats = $this->get_agent_stats($agent->agent_id);
            $agent->total_properties = $stats['total_properties'];
            $agent->active_properties = $stats['active_properties'];
            $agent->sold_properties = $stats['sold_properties'];
            $agent->success_rate = $stats['success_rate'];
        }

        return $agent;
    }

    /**
     * Recherche d'agents par terme
     * @param string $term
     * @param int $limit
     * @return object[]
     */
    public function search_agents($term, $limit = 10) {
        if (empty($term)) return [];

        $this->wp_db->select("
            u.ID as user_id,
            u.user_email,
            p.ID as agent_id,
            p.post_title as agent_name,
            pm_email.meta_value as agent_email,
            a.post_title as agency_name,
            MAX(CASE WHEN pm.meta_key = 'fave_author_custom_picture' THEN 
                CASE 
                    WHEN pm.meta_value IS NOT NULL AND pm.meta_value != '' THEN 
                        (SELECT REPLACE(guid, 'http://localhost/', 'https://rebencia.com/') 
                         FROM {$this->posts_table} 
                         WHERE ID = pm.meta_value AND post_type = 'attachment' LIMIT 1)
                    ELSE NULL
                END
            END) as agent_avatar
        ", FALSE);
        
        $this->wp_db->from($this->users_table . ' u')
            ->join($this->postmeta_table . ' pm_email', 'pm_email.meta_value = u.user_email AND pm_email.meta_key = "fave_agent_email"', 'inner')
            ->join($this->posts_table . ' p', 'p.ID = pm_email.post_id AND p.post_type = "houzez_agent" AND p.post_status = "publish"', 'inner')
            ->join($this->postmeta_table . ' pm_agency', 'pm_agency.post_id = p.ID AND pm_agency.meta_key = "fave_agent_agencies"', 'left')
            ->join($this->posts_table . ' a', 'a.ID = pm_agency.meta_value AND a.post_type = "houzez_agency"', 'left')
            ->join($this->postmeta_table . ' pm', 'pm.post_id = p.ID', 'left')
            ->group_start()
                ->like('p.post_title', $term)
                ->or_like('u.user_email', $term)
                ->or_like('u.user_login', $term)
            ->group_end()
            ->group_by('u.ID, u.user_email, p.ID, p.post_title, pm_email.meta_value, a.post_title')
            ->limit($limit);

        $agents = $this->wp_db->get()->result();
        
        // Post-traitement pour ajouter des champs par défaut
        foreach ($agents as $agent) {
            if (empty($agent->agent_avatar)) {
                $agent->agent_avatar = base_url('assets/images/users/avatar-1.jpg');
            }
        }

        return $agents;
    }

    /**
     * Méthode améliorée pour récupérer les propriétés d'un agent
     * Teste différentes approches pour trouver les associations
     * @param int $agent_id
     * @param string $agent_email
     * @param int $limit
     * @return object[]
     */
    public function get_agent_properties_enhanced($agent_id, $agent_email = null, $limit = null) {
        $properties = [];
        
        // Méthode 1: Par agent_id dans fave_property_agent
        $this->wp_db->select("
            p.ID,
            p.post_title as title,
            p.post_content as description,
            p.post_date,
            p.post_status,
            p.post_name as slug,
            MAX(CASE WHEN pm.meta_key = 'fave_property_price' THEN pm.meta_value END) as price,
            MAX(CASE WHEN pm.meta_key = 'fave_property_size' THEN pm.meta_value END) as size,
            MAX(CASE WHEN pm.meta_key = 'fave_property_bedrooms' THEN pm.meta_value END) as bedrooms,
            MAX(CASE WHEN pm.meta_key = 'fave_property_bathrooms' THEN pm.meta_value END) as bathrooms,
            MAX(CASE WHEN pm.meta_key = 'fave_property_address' THEN pm.meta_value END) as location,
            MAX(CASE WHEN pm.meta_key = 'fave_property_images' THEN pm.meta_value END) as images,
            (SELECT guid FROM {$this->posts_table} img WHERE img.ID = (
                SELECT meta_value FROM {$this->postmeta_table} thumb 
                WHERE thumb.post_id = p.ID AND thumb.meta_key = '_thumbnail_id' LIMIT 1
            )) as thumbnail,
            (SELECT t.name FROM {$this->wp_db->dbprefix}term_taxonomy tt 
             INNER JOIN {$this->wp_db->dbprefix}terms t ON tt.term_id = t.term_id 
             INNER JOIN {$this->wp_db->dbprefix}term_relationships tr ON tt.term_taxonomy_id = tr.term_taxonomy_id 
             WHERE tr.object_id = p.ID AND tt.taxonomy = 'property_status' LIMIT 1) as status,
            (SELECT t.name FROM {$this->wp_db->dbprefix}term_taxonomy tt 
             INNER JOIN {$this->wp_db->dbprefix}terms t ON tt.term_id = t.term_id 
             INNER JOIN {$this->wp_db->dbprefix}term_relationships tr ON tt.term_taxonomy_id = tr.term_taxonomy_id 
             WHERE tr.object_id = p.ID AND tt.taxonomy = 'property_type' LIMIT 1) as property_type,
            COALESCE((SELECT meta_value FROM {$this->postmeta_table} WHERE post_id = p.ID AND meta_key = 'fave_total_property_views'), 0) as views,
            'agent_id' as found_by
        ", FALSE);

        $method1 = $this->wp_db->from($this->posts_table . ' p')
            ->join($this->postmeta_table . ' pm', 'p.ID = pm.post_id', 'left')
            ->join($this->postmeta_table . ' pm_agent', 'p.ID = pm_agent.post_id AND pm_agent.meta_key = "fave_property_agent"', 'inner')
            ->where('pm_agent.meta_value', $agent_id)
            ->where('p.post_type', 'property')
            ->where('p.post_status', 'publish')
            ->group_by('p.ID, p.post_title, p.post_content, p.post_date, p.post_status, p.post_name')
            ->order_by('p.post_date', 'DESC');

        if ($limit) $method1->limit($limit);
        $properties = array_merge($properties, $method1->get()->result());

        // Si pas de résultats et qu'on a un email, essayons par email
        if (empty($properties) && $agent_email) {
            
            // Méthode 2: Par email dans fave_property_agent
            $this->wp_db->select("
                p.ID,
                p.post_title as title,
                p.post_content as description,
                p.post_date,
                p.post_status,
                p.post_name as slug,
                MAX(CASE WHEN pm.meta_key = 'fave_property_price' THEN pm.meta_value END) as price,
                MAX(CASE WHEN pm.meta_key = 'fave_property_size' THEN pm.meta_value END) as size,
                MAX(CASE WHEN pm.meta_key = 'fave_property_bedrooms' THEN pm.meta_value END) as bedrooms,
                MAX(CASE WHEN pm.meta_key = 'fave_property_bathrooms' THEN pm.meta_value END) as bathrooms,
                MAX(CASE WHEN pm.meta_key = 'fave_property_address' THEN pm.meta_value END) as location,
                MAX(CASE WHEN pm.meta_key = 'fave_property_images' THEN pm.meta_value END) as images,
                (SELECT guid FROM {$this->posts_table} img WHERE img.ID = (
                    SELECT meta_value FROM {$this->postmeta_table} thumb 
                    WHERE thumb.post_id = p.ID AND thumb.meta_key = '_thumbnail_id' LIMIT 1
                )) as thumbnail,
                (SELECT t.name FROM {$this->wp_db->dbprefix}term_taxonomy tt 
                 INNER JOIN {$this->wp_db->dbprefix}terms t ON tt.term_id = t.term_id 
                 INNER JOIN {$this->wp_db->dbprefix}term_relationships tr ON tt.term_taxonomy_id = tr.term_taxonomy_id 
                 WHERE tr.object_id = p.ID AND tt.taxonomy = 'property_status' LIMIT 1) as status,
                (SELECT t.name FROM {$this->wp_db->dbprefix}term_taxonomy tt 
                 INNER JOIN {$this->wp_db->dbprefix}terms t ON tt.term_id = t.term_id 
                 INNER JOIN {$this->wp_db->dbprefix}term_relationships tr ON tt.term_taxonomy_id = tr.term_taxonomy_id 
                 WHERE tr.object_id = p.ID AND tt.taxonomy = 'property_type' LIMIT 1) as property_type,
                COALESCE((SELECT meta_value FROM {$this->postmeta_table} WHERE post_id = p.ID AND meta_key = 'fave_total_property_views'), 0) as views,
                'email' as found_by
            ", FALSE);

            $method2 = $this->wp_db->from($this->posts_table . ' p')
                ->join($this->postmeta_table . ' pm', 'p.ID = pm.post_id', 'left')
                ->join($this->postmeta_table . ' pm_agent', 'p.ID = pm_agent.post_id AND pm_agent.meta_key = "fave_property_agent"', 'inner')
                ->where('pm_agent.meta_value', $agent_email)
                ->where('p.post_type', 'property')
                ->where('p.post_status', 'publish')
                ->group_by('p.ID, p.post_title, p.post_content, p.post_date, p.post_status, p.post_name')
                ->order_by('p.post_date', 'DESC');

            if ($limit) $method2->limit($limit);
            $properties = array_merge($properties, $method2->get()->result());
        }

        // Méthode 3: Recherche par author_id (post_author)
        if (empty($properties)) {
            
            // D'abord, trouver le user_id WordPress correspondant à l'agent
            $user_id = $this->wp_db->select('u.ID')
                ->from($this->users_table . ' u')
                ->join($this->postmeta_table . ' pm', 'pm.meta_value = u.user_email AND pm.meta_key = "fave_agent_email"', 'inner')
                ->join($this->posts_table . ' p', 'p.ID = pm.post_id AND p.post_type = "houzez_agent"', 'inner')
                ->where('p.ID', $agent_id)
                ->get()->row();

            if ($user_id) {
                $this->wp_db->select("
                    p.ID,
                    p.post_title as title,
                    p.post_content as description,
                    p.post_date,
                    p.post_status,
                    p.post_name as slug,
                    MAX(CASE WHEN pm.meta_key = 'fave_property_price' THEN pm.meta_value END) as price,
                    MAX(CASE WHEN pm.meta_key = 'fave_property_size' THEN pm.meta_value END) as size,
                    MAX(CASE WHEN pm.meta_key = 'fave_property_bedrooms' THEN pm.meta_value END) as bedrooms,
                    MAX(CASE WHEN pm.meta_key = 'fave_property_bathrooms' THEN pm.meta_value END) as bathrooms,
                    MAX(CASE WHEN pm.meta_key = 'fave_property_address' THEN pm.meta_value END) as location,
                    MAX(CASE WHEN pm.meta_key = 'fave_property_images' THEN pm.meta_value END) as images,
                    (SELECT guid FROM {$this->posts_table} img WHERE img.ID = (
                        SELECT meta_value FROM {$this->postmeta_table} thumb 
                        WHERE thumb.post_id = p.ID AND thumb.meta_key = '_thumbnail_id' LIMIT 1
                    )) as thumbnail,
                    (SELECT t.name FROM {$this->wp_db->dbprefix}term_taxonomy tt 
                     INNER JOIN {$this->wp_db->dbprefix}terms t ON tt.term_id = t.term_id 
                     INNER JOIN {$this->wp_db->dbprefix}term_relationships tr ON tt.term_taxonomy_id = tr.term_taxonomy_id 
                     WHERE tr.object_id = p.ID AND tt.taxonomy = 'property_status' LIMIT 1) as status,
                    (SELECT t.name FROM {$this->wp_db->dbprefix}term_taxonomy tt 
                     INNER JOIN {$this->wp_db->dbprefix}terms t ON tt.term_id = t.term_id 
                     INNER JOIN {$this->wp_db->dbprefix}term_relationships tr ON tt.term_taxonomy_id = tr.term_taxonomy_id 
                     WHERE tr.object_id = p.ID AND tt.taxonomy = 'property_type' LIMIT 1) as property_type,
                    COALESCE((SELECT meta_value FROM {$this->postmeta_table} WHERE post_id = p.ID AND meta_key = 'fave_total_property_views'), 0) as views,
                    'post_author' as found_by
                ", FALSE);

                $method3 = $this->wp_db->from($this->posts_table . ' p')
                    ->join($this->postmeta_table . ' pm', 'p.ID = pm.post_id', 'left')
                    ->where('p.post_author', $user_id->ID)
                    ->where('p.post_type', 'property')
                    ->where('p.post_status', 'publish')
                    ->group_by('p.ID, p.post_title, p.post_content, p.post_date, p.post_status, p.post_name')
                    ->order_by('p.post_date', 'DESC');

                if ($limit) $method3->limit($limit);
                $properties = array_merge($properties, $method3->get()->result());
            }
        }

        // Dédoublonnage par ID
        $unique_properties = [];
        $seen_ids = [];
        foreach ($properties as $property) {
            if (!in_array($property->ID, $seen_ids)) {
                $seen_ids[] = $property->ID;
                $unique_properties[] = $property;
            }
        }

        return $unique_properties;
    }

    /**
     * Récupère les estimations d'un agent
     * @param int $agent_id
     * @param int $limit
     * @return array
     */
    public function get_agent_estimations($agent_id, $limit = null) {
        if (!$this->db->table_exists('crm_properties')) {
            return [];
        }

        $this->db->select('
            p.id,
            p.type_bien,
            p.zone_id,
            p.surface_habitable as superficie,
            p.valeur_estimee as prix_estime,
            p.statut_dossier as statut,
            p.created_at as date_creation,
            p.agent_id,
            z.nom as zone_nom,
            z.nom as adresse
        ');
        
        $this->db->from('crm_properties p');
        $this->db->join('crm_zones z', 'z.id = p.zone_id', 'left');
        $this->db->where('p.agent_id', $agent_id);
        $this->db->order_by('p.created_at', 'DESC');
        
        if ($limit) {
            $this->db->limit($limit);
        }
        
        return $this->db->get()->result();
    }

    /**
     * Récupère les transactions d'un agent
     * @param int $agent_id
     * @param int $limit
     * @return array
     */
    public function get_agent_transactions($agent_id, $limit = null) {
        if (!$this->db->table_exists('crm_transactions')) {
            return [];
        }

        $this->db->select('
            t.*,
            z.nom as property_address,
            p.type_bien as property_type,
            c.nom as client_nom,
            c.prenom as client_prenom,
            c.email as client_email
        ');
        
        $this->db->from('crm_transactions t');
        $this->db->join('crm_properties p', 'p.id = t.property_id', 'left');
        $this->db->join('crm_zones z', 'z.id = p.zone_id', 'left');
        $this->db->join('crm_clients c', 'c.id = t.client_id', 'left');
        $this->db->where('t.agent_id', $agent_id);
        $this->db->order_by('t.date_cloture', 'DESC');
        
        if ($limit) {
            $this->db->limit($limit);
        }
        
        return $this->db->get()->result();
    }

    /**
     * Statistiques complètes d'un agent incluant estimations et transactions
     * @param int $agent_id
     * @return array
     */
    public function get_agent_complete_stats($agent_id) {
        $stats = [
            'properties_count' => 0,
            'estimations_count' => 0,
            'transactions_count' => 0,
            'total_commission' => 0,
            'avg_estimation_value' => 0,
            'transactions_this_month' => 0,
            'estimations_this_month' => 0
        ];

        // Compter les propriétés HOUZEZ
        $properties_count = (int)$this->wp_db->from($this->posts_table . ' p')
            ->join($this->postmeta_table . ' pm', 'p.ID = pm.post_id AND pm.meta_key = "fave_property_agent"', 'inner')
            ->where('pm.meta_value', $agent_id)
            ->where('p.post_type', 'property')
            ->where('p.post_status', 'publish')
            ->count_all_results();
        $stats['properties_count'] = $properties_count;

        // Compter les estimations CRM
        if ($this->db->table_exists('crm_properties')) {
            $estimations = $this->db->where('agent_id', $agent_id)->get('crm_properties');
            $stats['estimations_count'] = $estimations->num_rows();
            
            if ($estimations->num_rows() > 0) {
                $avg_price = $this->db->select_avg('valeur_estimee')
                    ->where('agent_id', $agent_id)
                    ->where('valeur_estimee >', 0)
                    ->get('crm_properties')
                    ->row();
                $stats['avg_estimation_value'] = (float)($avg_price->valeur_estimee ?? 0);
            }

            // Estimations de ce mois
            $this_month = date('Y-m-01');
            $stats['estimations_this_month'] = $this->db
                ->where('agent_id', $agent_id)
                ->where('created_at >=', $this_month)
                ->count_all_results('crm_properties');
        }

        // Compter les transactions CRM
        if ($this->db->table_exists('crm_transactions')) {
            $transactions = $this->db->where('agent_id', $agent_id)->get('crm_transactions');
            $stats['transactions_count'] = $transactions->num_rows();

            if ($transactions->num_rows() > 0) {
                $total_commission = $this->db->select_sum('commission')
                    ->where('agent_id', $agent_id)
                    ->get('crm_transactions')
                    ->row();
                $stats['total_commission'] = (float)($total_commission->commission ?? 0);
            }

            // Transactions de ce mois
            $this_month = date('Y-m-01');
            $stats['transactions_this_month'] = $this->db
                ->where('agent_id', $agent_id)
                ->where('date_cloture >=', $this_month)
                ->count_all_results('crm_transactions');
        }

        return $stats;
    }

    /**
     * Nettoie et corrige les données d'un agent
     * @param object $agent
     * @return object
     */
    public function clean_agent_data($agent) {
        if (!$agent) return $agent;
        
        // Correction des données manifestement fausses ou de test
        $fake_data_patterns = [
            'phone' => ['123 456 789', '321 456 9874', '000 000 000'],
            'mobile' => ['123 456 789', '321 456 9874', '000 000 000'],
            'whatsapp' => ['123 456 789', '321 456 9874', '000 000 000']
        ];
        
        foreach ($fake_data_patterns as $field => $fake_values) {
            if (isset($agent->$field) && in_array($agent->$field, $fake_values)) {
                $agent->$field = ''; // Vider les données manifestement fausses
            }
        }
        
        // Correction des URLs avec fautes de frappe
        if (!empty($agent->website)) {
            // Correction rebencia.com mal écrit
            $agent->website = str_replace('rebenecia.com', 'rebencia.com', $agent->website);
            
            // Ajouter https:// si manquant
            if (!preg_match('/^https?:\/\//', $agent->website)) {
                $agent->website = 'https://' . $agent->website;
            }
        }
        
        // Nettoyer les données de contact suspectes
        if (!empty($agent->contacts_count) && $agent->contacts_count == 14) {
            // Cette valeur semble suspecte, la remettre à 0 pour éviter la confusion
            $agent->contacts_count = 0;
        }
        
        return $agent;
    }

    /**
     * Corrige les métadonnées erronées dans WordPress pour un agent spécifique
     * @param int $agent_id
     * @return bool
     */
    public function fix_agent_metadata($agent_id) {
        if (!$agent_id) return false;
        
        $corrections = [
            // Corrections pour l'agent Montasar Barkouti ou autres
            'fave_agent_mobile' => '', // Vider le mobile s'il est faux
            'fave_agent_whatsapp' => '', // Vider le WhatsApp s'il est faux
            'fave_agent_website' => 'https://rebencia.com' // Corriger l'URL
        ];
        
        foreach ($corrections as $meta_key => $meta_value) {
            // Vérifier si la métadonnée existe et contient des données suspectes
            $current_value = $this->wp_db->select('meta_value')
                ->from($this->postmeta_table)
                ->where('post_id', $agent_id)
                ->where('meta_key', $meta_key)
                ->get()->row();
                
            if ($current_value) {
                $current = $current_value->meta_value;
                
                // Définir les conditions de correction
                $needs_correction = false;
                
                if ($meta_key == 'fave_agent_mobile' || $meta_key == 'fave_agent_whatsapp') {
                    // Corriger les numéros manifestement faux
                    if (in_array($current, ['123 456 789', '321 456 9874', '000 000 000'])) {
                        $needs_correction = true;
                    }
                } elseif ($meta_key == 'fave_agent_website') {
                    // Corriger les URLs avec fautes de frappe
                    if (strpos($current, 'rebenecia.com') !== false) {
                        $meta_value = str_replace('rebenecia.com', 'rebencia.com', $current);
                        $needs_correction = true;
                    }
                }
                
                if ($needs_correction) {
                    $this->wp_db->where('post_id', $agent_id)
                        ->where('meta_key', $meta_key)
                        ->update($this->postmeta_table, ['meta_value' => $meta_value]);
                }
            }
        }
        
        return true;
    }

    /**
     * Test de la nouvelle méthode pour récupérer les avatars avec _thumbnail_id et fave_author_custom_picture
     * Basé sur votre requête SQL proposée
     */
    public function get_agents_with_better_avatars() {
        $this->wp_db->select("
            a.ID as agent_id,
            a.post_title as agent_name,
            a.post_status,
            a.post_date,
            -- Image avec priorité : _thumbnail_id puis fave_author_custom_picture
            COALESCE(
                (SELECT REPLACE(i1.guid, 'http://localhost/', 'https://rebencia.com/') 
                 FROM {$this->postmeta_table} m1 
                 INNER JOIN {$this->posts_table} i1 ON m1.meta_value = i1.ID 
                 WHERE m1.post_id = a.ID 
                 AND m1.meta_key = '_thumbnail_id' 
                 AND i1.post_type = 'attachment' 
                 LIMIT 1),
                (SELECT REPLACE(i2.guid, 'http://localhost/', 'https://rebencia.com/') 
                 FROM {$this->postmeta_table} m2 
                 INNER JOIN {$this->posts_table} i2 ON m2.meta_value = i2.ID 
                 WHERE m2.post_id = a.ID 
                 AND m2.meta_key = 'fave_author_custom_picture' 
                 AND i2.post_type = 'attachment' 
                 LIMIT 1),
                NULL
            ) as agent_avatar,
            -- Emails et autres infos
            (SELECT meta_value FROM {$this->postmeta_table} WHERE post_id = a.ID AND meta_key = 'fave_agent_email' LIMIT 1) as agent_email,
            (SELECT meta_value FROM {$this->postmeta_table} WHERE post_id = a.ID AND meta_key = 'fave_agent_phone' LIMIT 1) as phone
        ", FALSE);
        
        $this->wp_db->from($this->posts_table . ' a')
            ->where('a.post_type', 'houzez_agent')
            ->where('a.post_status', 'publish')
            ->order_by('a.post_title', 'ASC');
            
        return $this->wp_db->get()->result();
    }

    /**
     * Récupère l'URL de l'avatar d'un agent par son ID
     * @param int $agent_id
     * @return string|null
     */
    public function get_agent_avatar_url_by_id($agent_id) {
        $result = $this->wp_db->select('image_url')
            ->from('wp_Hrg8P_crm_avatar_agents')
            ->where('agent_post_id', $agent_id)
            ->get()
            ->row();
            
        return $result ? $result->image_url : null;
    }

    /**
     * Affecte l'URL de l'avatar à chaque agent dans la liste
     * Version optimisée avec une seule requête
     * @param array $agents
     * @return array
     */
    public function assign_avatar_urls($agents) {
        if (empty($agents)) {
            return $agents;
        }
        
        // Récupérer tous les IDs d'agents
        $agent_ids = array_map(function($agent) {
            return $agent->agent_id;
        }, $agents);
        
        // Récupérer tous les avatars en une seule requête
        $avatars = $this->wp_db->select('agent_post_id, image_url')
            ->from('wp_Hrg8P_crm_avatar_agents')
            ->where_in('agent_post_id', $agent_ids)
            ->get()
            ->result();
        
        // Créer un tableau associatif pour un accès rapide
        $avatar_map = [];
        foreach ($avatars as $avatar) {
            $avatar_map[$avatar->agent_post_id] = $avatar->image_url;
        }
        
        // Affecter les avatars aux agents
        foreach ($agents as $agent) {
            if (!isset($agent->agent_avatar) || empty($agent->agent_avatar)) {
                $agent->agent_avatar = isset($avatar_map[$agent->agent_id]) 
                    ? $avatar_map[$agent->agent_id] 
                    : null;
            }
        }
        
        return $agents;
    }

    /**
     * Récupère tous les agents avec leurs rôles et agences depuis wp_Hrg8P_crm_agents
     * Basé sur la vue wp_Hrg8P_crm_agents fournie par l'utilisateur
     * @param array $filters Filtres de recherche
     * @return object[]
     */
    public function get_agents_with_roles_and_agencies($filters = []) {
        $this->wp_db->select("
            u.ID AS user_id,
            u.user_login AS user_login,
            u.user_email AS user_email,
            u.user_status AS user_status,
            u.user_registered AS registration_date,
            
            p.ID AS agent_post_id,
            p.ID AS agent_id,
            p.post_title AS agent_name,
            p.post_status AS post_status,
            p.post_type AS post_type,
            pm_email.meta_value AS agent_email,
            
            a.ID AS agency_id,
            a.post_title AS agency_name,
            
            MAX(CASE WHEN pm_contact.meta_key = 'fave_agent_phone' THEN pm_contact.meta_value END) AS phone,
            MAX(CASE WHEN pm_contact.meta_key = 'fave_agent_mobile' THEN pm_contact.meta_value END) AS mobile,
            MAX(CASE WHEN pm_contact.meta_key = 'fave_agent_whatsapp' THEN pm_contact.meta_value END) AS whatsapp,
            avatar_view.image_url AS agent_avatar,
            MAX(CASE WHEN pm_contact.meta_key = 'fave_agent_position' THEN pm_contact.meta_value END) AS position,
            
            COUNT(DISTINCT prop_view.property_id) as properties_count,
            
            ur.meta_value AS user_roles
        ", FALSE);
        
        $this->wp_db->from($this->users_table . ' u')
            ->join($this->postmeta_table . ' pm_email', 'pm_email.meta_value = u.user_email', 'left')
            ->join($this->posts_table . ' p', 'p.ID = pm_email.post_id AND p.post_type IN ("houzez_agent", "houzez_manager")', 'left')
            ->join($this->postmeta_table . ' pm_agency', 'pm_agency.post_id = p.ID AND pm_agency.meta_key = "fave_agent_agencies"', 'left')
            ->join($this->posts_table . ' a', 'a.ID = pm_agency.meta_value AND a.post_type = "houzez_agency"', 'left')
            ->join($this->postmeta_table . ' pm_contact', 'pm_contact.post_id = p.ID', 'left')
            ->join('wp_Hrg8P_crm_avatar_agents avatar_view', 'avatar_view.agent_post_id = p.ID', 'left')
            ->join('wp_Hrg8P_prop_agen prop_view', 'prop_view.agent_id = p.ID AND prop_view.property_status = "publish"', 'left')
            ->join($this->usermeta_table . ' ur', 'ur.user_id = u.ID AND ur.meta_key = "wp_Hrg8P_capabilities"', 'left')
            ->where('p.post_type IN ("houzez_agent", "houzez_manager")', NULL, FALSE)
            ->where('p.post_status', 'publish');

        // Appliquer les filtres
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $this->wp_db->group_start()
                ->like('p.post_title', $search)
                ->or_like('u.user_email', $search)
                ->or_like('u.user_login', $search)
                ->group_end();
        }

        if (!empty($filters['agency'])) {
            $this->wp_db->where('a.ID', $filters['agency']);
        }

        $this->wp_db->group_by('
            u.ID, u.user_login, u.user_email, u.user_status, u.user_registered,
            p.ID, p.post_title, p.post_status, p.post_type, pm_email.meta_value,
            a.ID, a.post_title, ur.meta_value
        ');
        $this->wp_db->order_by('p.post_title', 'ASC');

        $query = $this->wp_db->get();
        $agents = $query->result();

        // Nettoyer et formater les données
        foreach ($agents as $agent) {
            // Extraire le rôle principal depuis user_roles
            if (!empty($agent->user_roles)) {
                $roles = unserialize($agent->user_roles);
                if (is_array($roles)) {
                    if (isset($roles['houzez_manager'])) {
                        $agent->user_role = 'Manager';
                    } elseif (isset($roles['houzez_agent'])) {
                        $agent->user_role = 'Agent';
                    } elseif (isset($roles['houzez_agency'])) {
                        $agent->user_role = 'Agence';
                    } elseif (isset($roles['administrator'])) {
                        $agent->user_role = 'Administrateur';
                    } else {
                        $agent->user_role = 'Utilisateur';
                    }
                } else {
                    $agent->user_role = 'Non défini';
                }
            } else {
                $agent->user_role = 'Non défini';
            }

            // Déterminer le statut actif basé sur post_status
            $agent->is_active = ($agent->post_status === 'publish') ? 1 : 0;
            
            // Ajouter des propriétés par défaut si manquantes
            if (!isset($agent->properties_count)) {
                $agent->properties_count = 0;
            }
            
            // S'assurer que les dates existent
            if (!isset($agent->created_date)) {
                $agent->created_date = $agent->registration_date ?? date('Y-m-d H:i:s');
            }
            
            // Nettoyer les données
            $agent = $this->clean_agent_data($agent);
        }

        return $agents;
    }

    public function get_manager_team_agents($manager_agency_id) {
        $this->load->database('wordpress');
        $wp_db = $this->load->database('wordpress', TRUE);
        
        // Tables WordPress avec préfixe dynamique
        $wp_db->select('
            p.ID as user_id,
            p.post_title as display_name,
            u.user_email,
            u.user_nicename,
            p.ID as agent_post_id,
            pm_avatar.meta_value as avatar_id,
            COALESCE(prop_count.property_count, 0) as property_count,
            p.post_type as user_role,
            agency.post_title as agency_name,
            agency.ID as agency_id
        ', FALSE);

        $wp_db->from($wp_db->dbprefix('posts') . ' p');
        $wp_db->join($wp_db->dbprefix('postmeta') . ' pm_email', 'pm_email.post_id = p.ID AND pm_email.meta_key = "fave_agent_email"', 'inner');
        $wp_db->join($wp_db->dbprefix('users') . ' u', 'u.user_email = pm_email.meta_value', 'inner');
        $wp_db->join($wp_db->dbprefix('postmeta') . ' pm_agency', 'pm_agency.post_id = p.ID AND pm_agency.meta_key = "fave_agent_agencies"', 'left');
        $wp_db->join($wp_db->dbprefix('posts') . ' agency', 'agency.ID = pm_agency.meta_value AND agency.post_type = "houzez_agency"', 'left');
        $wp_db->join($wp_db->dbprefix('postmeta') . ' pm_avatar', 'pm_avatar.post_id = p.ID AND pm_avatar.meta_key = "_thumbnail_id"', 'left');

        // Sous-requête pour compter les propriétés
        $wp_db->join('(
            SELECT pm_prop.meta_value as agent_id, COUNT(*) as property_count
            FROM ' . $wp_db->dbprefix('posts') . ' prop
            INNER JOIN ' . $wp_db->dbprefix('postmeta') . ' pm_prop ON prop.ID = pm_prop.post_id
            WHERE pm_prop.meta_key = "fave_property_agent"
            AND prop.post_type = "property"
            AND prop.post_status = "publish"
            GROUP BY pm_prop.meta_value
        ) prop_count', 'prop_count.agent_id = p.ID', 'left');

        $wp_db->where('agency.ID', $manager_agency_id);
        $wp_db->where_in('p.post_type', ['houzez_agent', 'houzez_manager']);
        $wp_db->where('p.post_status', 'publish');
        $wp_db->order_by('p.post_title', 'ASC');

        $query = $wp_db->get();
        $agents = $query->result();
        // Nettoyer et enrichir les données
        foreach ($agents as &$agent) {
            // Nettoyer les données
            $agent = $this->clean_agent_data($agent);
        }

        return $agents;
    }

    public function get_agents_by_agency_with_avatars($agency_id) {
        if (!$agency_id) return [];
        
        $this->load->database('wordpress');
        $wp_db = $this->load->database('wordpress', TRUE);
        
        // Utiliser les vraies tables WordPress - le préfixe wp_Hrg8P_ est déjà configuré
        $wp_db->select('
            p.ID as user_id,
            p.post_title as display_name,
            u.user_email,
            u.user_nicename,
            p.ID as agent_post_id,
            pm_avatar.meta_value as avatar_id,
            COALESCE(prop_count.property_count, 0) as property_count,
            p.post_type as user_role,
            agency.post_title as agency_name,
            agency.ID as agency_id
        ', FALSE);
        
        $wp_db->from('posts p');
        $wp_db->join('postmeta pm_email', 'pm_email.post_id = p.ID AND pm_email.meta_key = "fave_agent_email"', 'inner');
        $wp_db->join('users u', 'u.user_email = pm_email.meta_value', 'inner');
        $wp_db->join('postmeta pm_agency', 'pm_agency.post_id = p.ID AND pm_agency.meta_key = "fave_agent_agencies"', 'left');
        $wp_db->join('posts agency', 'agency.ID = pm_agency.meta_value AND agency.post_type = "houzez_agency"', 'left');
        $wp_db->join('postmeta pm_avatar', 'pm_avatar.post_id = p.ID AND pm_avatar.meta_key = "_thumbnail_id"', 'left');
        
        // Sous-requête pour compter les propriétés
        $wp_db->join('(SELECT pm_prop.meta_value as agent_id, COUNT(*) as property_count 
                                 FROM ' . $wp_db->dbprefix . 'posts prop 
                                 INNER JOIN ' . $wp_db->dbprefix . 'postmeta pm_prop ON prop.ID = pm_prop.post_id 
                                 WHERE pm_prop.meta_key = "fave_property_agent" 
                                 AND prop.post_type = "property" 
                                 AND prop.post_status = "publish"
                                 GROUP BY pm_prop.meta_value) prop_count', 'prop_count.agent_id = p.ID', 'left');
        
        // Filtrer par agence et inclure agents et managers
        $wp_db->where('agency.ID', $agency_id);
        $wp_db->where_in('p.post_type', ['houzez_agent', 'houzez_manager']);
        $wp_db->where('p.post_status', 'publish');
        
        $wp_db->order_by('p.post_title', 'ASC');
        
        $query = $wp_db->get();
        $agents = $query->result();
        
        // Nettoyer et enrichir les données
        foreach ($agents as &$agent) {
            // Nettoyer les données
            $agent = $this->clean_agent_data($agent);
            
            // S'assurer que user_nicename existe
            if (!isset($agent->user_nicename) || empty($agent->user_nicename)) {
                $agent->user_nicename = $agent->user_email ? explode('@', $agent->user_email)[0] : 'agent';
            }
            
            // Récupérer l'avatar complet
            $agent->avatar_url = $this->get_agent_avatar_url($agent->user_id, $agent->avatar_id);
            
            // Ajouter des statistiques par défaut si manquantes
            if (!isset($agent->properties_count)) {
                $agent->properties_count = 0;
            }
            if (!isset($agent->sales_count)) {
                $agent->sales_count = 0;
            }
            if (!isset($agent->contacts_count)) {
                $agent->contacts_count = 0;
            }
        }

        return $agents;
    }
    
    /**
     * Récupère l'URL de l'avatar d'un agent
     * @param int $user_id ID de l'utilisateur WordPress
     * @param int $avatar_id ID de l'attachment avatar
     * @return string|null URL de l'avatar
     */
    private function get_agent_avatar_url($user_id, $avatar_id = null) {
        // Méthode 1: Avatar personnalisé HOUZEZ (fave_author_custom_picture)
        $custom_avatar = $this->wp_db->select('meta_value')
            ->from($this->usermeta_table)
            ->where('user_id', $user_id)
            ->where('meta_key', 'fave_author_custom_picture')
            ->get()->row();
            
        if ($custom_avatar && !empty($custom_avatar->meta_value)) {
            // Vérifier si c'est une URL directe ou un ID d'attachment
            if (filter_var($custom_avatar->meta_value, FILTER_VALIDATE_URL)) {
                return $custom_avatar->meta_value;
            } elseif (is_numeric($custom_avatar->meta_value)) {
                // Récupérer l'URL depuis l'attachment
                $attachment = $this->wp_db->select('guid')
                    ->from($this->posts_table)
                    ->where('ID', $custom_avatar->meta_value)
                    ->where('post_type', 'attachment')
                    ->get()->row();
                    
                if ($attachment && !empty($attachment->guid)) {
                    return $attachment->guid;
                }
            }
        }
        
        // Méthode 2: Thumbnail ID (si fourni)
        if ($avatar_id && is_numeric($avatar_id)) {
            $thumbnail = $this->wp_db->select('guid')
                ->from($this->posts_table)
                ->where('ID', $avatar_id)
                ->where('post_type', 'attachment')
                ->get()->row();
                
            if ($thumbnail && !empty($thumbnail->guid)) {
                return $thumbnail->guid;
            }
        }
        
        // Méthode 3: Avatar WordPress standard dans usermeta
        $wp_avatar = $this->wp_db->select('meta_value')
            ->from($this->usermeta_table)
            ->where('user_id', $user_id)
            ->where_in('meta_key', ['wp_user_avatar', 'avatar', 'profile_picture'])
            ->get()->row();
            
        if ($wp_avatar && !empty($wp_avatar->meta_value)) {
            if (filter_var($wp_avatar->meta_value, FILTER_VALIDATE_URL)) {
                return $wp_avatar->meta_value;
            } elseif (is_numeric($wp_avatar->meta_value)) {
                $attachment = $this->wp_db->select('guid')
                    ->from($this->posts_table)
                    ->where('ID', $wp_avatar->meta_value)
                    ->where('post_type', 'attachment')
                    ->get()->row();
                    
                if ($attachment && !empty($attachment->guid)) {
                    return $attachment->guid;
                }
            }
        }
        
        // Aucun avatar trouvé
        return null;
    }

}