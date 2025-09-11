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
            ->join($this->posts_table . ' p', 'p.ID = pm_email.post_id AND p.post_type = "houzez_agent" AND p.post_status = "publish"', 'inner')
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
        if (!$agency_id) return [];
        
        // Récupérer tous les agents HOUZEZ de l'agence via la requête optimisée
        $houzez_agents = $this->get_all_agents(['agency_id' => $agency_id]);
        
        // Récupérer aussi les agents CRM de cette agence
        $crm_agents = $this->wp_db->select('*')
            ->from('crm_agents')
            ->where('agency_id', $agency_id)
            ->get()->result();
        
        // Fusionner les deux sources : priorité à HOUZEZ, compléter avec CRM
        $all_agents = [];
        $processed_emails = [];
        
        // D'abord ajouter tous les agents HOUZEZ
        foreach ($houzez_agents as $agent) {
            if (!empty($agent->agent_email)) {
                $processed_emails[] = strtolower($agent->agent_email);
            }
            $all_agents[] = $agent;
        }
        
        // Ajouter les agents CRM qui ne sont pas déjà dans HOUZEZ
        foreach ($crm_agents as $crm_agent) {
            // Extraire l'email selon la structure réelle de la table
            $email = null;
            if (isset($crm_agent->email) && !empty($crm_agent->email)) {
                $email = $crm_agent->email;
            } elseif (isset($crm_agent->agent_email) && !empty($crm_agent->agent_email)) {
                $email = $crm_agent->agent_email;
            } elseif (isset($crm_agent->user_email) && !empty($crm_agent->user_email)) {
                $email = $crm_agent->user_email;
            }
            
            $email_lower = strtolower($email ?? '');
            if (!empty($email) && !in_array($email_lower, $processed_emails)) {
                // Créer un objet agent compatible avec les données CRM
                $agent = new stdClass();
                $agent->user_id = null;
                $agent->user_login = null;
                $agent->user_email = $email;
                $agent->agent_id = null;
                $agent->agent_name = trim(($crm_agent->first_name ?? '') . ' ' . ($crm_agent->last_name ?? '')) ?: 'Agent CRM';
                $agent->agent_email = $email;
                $agent->phone = $crm_agent->phone ?? '';
                $agent->mobile = $crm_agent->mobile ?? '';
                $agent->position = $crm_agent->position ?? 'Agent immobilier';
                $agent->agency_id = $agency_id;
                $agent->agency_name = null; // Sera rempli si nécessaire
                $agent->crm_id = $crm_agent->id ?? $crm_agent->ID ?? null;
                $agent->is_crm_only = true;
                
                $all_agents[] = $agent;
            }
        }
        
        return $all_agents;
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
        // Cette méthode pourrait être étendue pour compter les contacts réels
        // Pour l'instant, on retourne une valeur factice
        return rand(5, 25);
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
            p.post_date as created_date,
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
            MAX(CASE WHEN pm.meta_key = 'fave_agent_instagram' THEN pm.meta_value END) as instagram
        ", FALSE);
        
        $this->wp_db->from($this->posts_table . ' p')
            ->join($this->postmeta_table . ' pm_agency', 'pm_agency.post_id = p.ID AND pm_agency.meta_key = "fave_agent_agencies"', 'left')
            ->join($this->posts_table . ' a', 'a.ID = pm_agency.meta_value AND a.post_type = "houzez_agency"', 'left')
            ->join($this->postmeta_table . ' pm', 'pm.post_id = p.ID', 'left')
            ->where('p.ID', $agent_id)
            ->where('p.post_type', 'houzez_agent')
            ->group_by('p.ID, p.post_title, p.post_content, p.post_status, p.post_date, a.ID, a.post_title');

        return $this->clean_agent_data($this->wp_db->get()->row());
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

}