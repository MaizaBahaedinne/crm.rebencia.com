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
     * @param array $filters Filtres de recherche
     * @return object[]
     */
    public function get_all_agents($filters = []) {
        // Requête complète pour récupérer tous les agents HOUZEZ avec leurs informations
        $this->wp_db->select("
            u.ID as user_id,
            u.user_login as user_login,
            u.user_email as user_email,
            u.user_status as user_status,
            u.user_registered as registration_date,
            p.ID as agent_post_id,
            p.post_title as agent_name,
            p.post_status as post_status,
            pm_email.meta_value as agent_email,
            a.ID as agency_id,
            a.post_title as agency_name,
            MAX(CASE WHEN pm_contact.meta_key = 'fave_agent_phone' THEN pm_contact.meta_value END) as phone,
            MAX(CASE WHEN pm_contact.meta_key = 'fave_agent_mobile' THEN pm_contact.meta_value END) as mobile,
            MAX(CASE WHEN pm_contact.meta_key = 'fave_agent_whatsapp' THEN pm_contact.meta_value END) as whatsapp,
            MAX(CASE WHEN pm_contact.meta_key = 'fave_agent_position' THEN pm_contact.meta_value END) as position,
            MAX(CASE WHEN pm_contact.meta_key = 'fave_agent_picture' THEN media.guid END) as agent_avatar
        ", FALSE);
        
        $this->wp_db->from($this->users_table . ' u')
            ->join($this->postmeta_table . ' pm_email', 'pm_email.meta_value = u.user_email', 'left')
            ->join($this->posts_table . ' p', 'p.ID = pm_email.post_id AND p.post_type = "houzez_agent"', 'left')
            ->join($this->postmeta_table . ' pm_agency', 'pm_agency.post_id = p.ID AND pm_agency.meta_key = "fave_agent_agencies"', 'left')
            ->join($this->posts_table . ' a', 'a.ID = pm_agency.meta_value AND a.post_type = "houzez_agency"', 'left')
            ->join($this->postmeta_table . ' pm_contact', 'pm_contact.post_id = p.ID', 'left')
            ->join($this->posts_table . ' media', 'media.ID = pm_contact.meta_value AND pm_contact.meta_key = "fave_agent_picture" AND media.post_type = "attachment"', 'left')
            ->where('p.post_type', 'houzez_agent');

        // Appliquer les filtres
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $this->wp_db->group_start()
                ->like('p.post_title', $search)
                ->or_like('u.user_email', $search)
                ->or_like('u.user_login', $search)
                ->group_end();
        }

        if (!empty($filters['agency_id'])) {
            $this->wp_db->where('a.ID', $filters['agency_id']);
        }

        $this->wp_db->group_by('u.ID, u.user_login, u.user_email, u.user_status, u.user_registered, p.ID, p.post_title, p.post_status, pm_email.meta_value, a.ID, a.post_title');

        return $this->wp_db->get()->result();
    }

    /**
     * Agents d'une agence spécifique
     * @param int $agency_id
     * @return object[]
     */
    public function get_agents_by_agency($agency_id) {
        if (!$agency_id) return [];
            
        
        return $this->get_all_agents(['agency_id' => $agency_id]);
    }

    /**
     * Retourne un agent spécifique
     * @param int $agent_id
     * @return object|null
     */
    public function get_agent($agent_id) {
        $this->wp_db->select("
            u.ID as user_id,
            u.user_login as user_login,
            u.user_email as user_email,
            u.user_status as user_status,
            u.user_registered as registration_date,
            p.ID as agent_post_id,
            p.post_title as agent_name,
            p.post_status as post_status,
            p.post_content as agent_description,
            a.ID as agency_id,
            a.post_title as agency_name,
            MAX(CASE WHEN pm_contact.meta_key = 'fave_agent_phone' THEN pm_contact.meta_value END) as phone,
            MAX(CASE WHEN pm_contact.meta_key = 'fave_agent_mobile' THEN pm_contact.meta_value END) as mobile,
            MAX(CASE WHEN pm_contact.meta_key = 'fave_agent_whatsapp' THEN pm_contact.meta_value END) as whatsapp,
            MAX(CASE WHEN pm_contact.meta_key = 'fave_agent_skype' THEN pm_contact.meta_value END) as skype,
            MAX(CASE WHEN pm_contact.meta_key = 'fave_agent_website' THEN pm_contact.meta_value END) as website,
            MAX(CASE WHEN pm_contact.meta_key = 'fave_agent_position' THEN pm_contact.meta_value END) as position,
            MAX(CASE WHEN pm_contact.meta_key = 'fave_agent_picture' THEN media.guid END) as agent_avatar,
            MAX(CASE WHEN pm_contact.meta_key = 'fave_agent_facebook' THEN pm_contact.meta_value END) as facebook,
            MAX(CASE WHEN pm_contact.meta_key = 'fave_agent_twitter' THEN pm_contact.meta_value END) as twitter,
            MAX(CASE WHEN pm_contact.meta_key = 'fave_agent_linkedin' THEN pm_contact.meta_value END) as linkedin,
            MAX(CASE WHEN pm_contact.meta_key = 'fave_agent_instagram' THEN pm_contact.meta_value END) as instagram
        ", FALSE);
        
        $this->wp_db->from($this->users_table . ' u')
            ->join($this->postmeta_table . ' pm_email', 'pm_email.meta_value = u.user_email', 'left')
            ->join($this->posts_table . ' p', 'p.ID = pm_email.post_id AND p.post_type = "houzez_agent"', 'left')
            ->join($this->postmeta_table . ' pm_agency', 'pm_agency.post_id = p.ID AND pm_agency.meta_key = "fave_agent_agencies"', 'left')
            ->join($this->posts_table . ' a', 'a.ID = pm_agency.meta_value AND a.post_type = "houzez_agency"', 'left')
            ->join($this->postmeta_table . ' pm_contact', 'pm_contact.post_id = p.ID', 'left')
            ->join($this->posts_table . ' media', 'media.ID = pm_contact.meta_value AND pm_contact.meta_key = "fave_agent_picture" AND media.post_type = "attachment"', 'left')
            ->where('u.ID', $agent_id)
            ->where('p.post_type', 'houzez_agent')
            ->group_by('u.ID, u.user_login, u.user_email, u.user_status, u.user_registered, p.ID, p.post_title, p.post_status, p.post_content, a.ID, a.post_title');

        return $this->wp_db->get()->row();
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
            if ($agent->agent_post_id) {
                $stats = $this->get_agent_stats($agent->agent_post_id);
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
        if ($agent->agent_post_id) {
            $stats = $this->get_agent_stats($agent->agent_post_id);
            $agent->total_properties = $stats['total_properties'];
            $agent->active_properties = $stats['active_properties'];
            $agent->sold_properties = $stats['sold_properties'];
            $agent->success_rate = $stats['success_rate'];
        }

        return $agent;
    }

}