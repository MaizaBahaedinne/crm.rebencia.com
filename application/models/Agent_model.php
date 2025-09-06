<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agent_model extends CI_Model {
    /**
     * Connexion DB WordPress
     * @var CI_DB_query_builder
     */
    protected $wp_db;
    protected $users_table;
    protected $usermeta_table;
    protected $capabilities_key; // meta_key des rôles sérialisés
    
    protected $wanted_meta_keys = [
        'first_name',
        'last_name',
        'houzez_agency_id',
        'agent_phone',
        'agent_email',
        'agent_photo'
    ];

    public function __construct() {
        parent::__construct();
        $this->wp_db = $this->load->database('wordpress', TRUE);
        // Normalisation des noms avec dbprefix (qui contient déjà wp_Hrg8P_)
        $this->users_table    = $this->wp_db->dbprefix('users');
        $this->usermeta_table = $this->wp_db->dbprefix('usermeta');
        $this->capabilities_key = $this->wp_db->dbprefix('capabilities'); // ex: wp_Hrg8P_capabilities
    }

    // Tous les agents (basé sur le rôle houzez_agent)
    public function get_all_agents() {
        $agents = $this->wp_db->select('u.ID,u.user_login,u.user_email,u.display_name,u.user_registered')
            ->from($this->users_table.' u')
            ->join($this->usermeta_table.' m', 'u.ID = m.user_id AND m.meta_key = '.$this->wp_db->escape($this->capabilities_key), 'inner', false)
            ->like('m.meta_value', 'houzez_agent')
            ->get()->result();

        if (empty($agents)) return [];
        
        $this->_add_agent_metadata($agents);
        return $agents;
    }
    

    // Agents d'une agence
    public function get_agents_by_agency($agency_id) {
        if (!$agency_id) return [];
        
        // Récupérer les agents qui ont le rôle houzez_agent ET appartiennent à l'agence
        $agents = $this->wp_db->select('u.ID,u.user_login,u.user_email,u.display_name,u.user_registered')
            ->from($this->users_table.' u')
            ->join($this->usermeta_table.' m1', 'u.ID = m1.user_id AND m1.meta_key = '.$this->wp_db->escape($this->capabilities_key), 'inner', false)
            ->join($this->usermeta_table.' m2', 'u.ID = m2.user_id AND m2.meta_key = "houzez_agency_id"', 'inner', false)
            ->like('m1.meta_value', 'houzez_agent')
            ->where('m2.meta_value', $agency_id)
            ->get()->result();

        if (empty($agents)) return [];
        
        $this->_add_agent_metadata($agents);
        return $agents;
    }
    
    /**
     * Ajouter les métadonnées aux agents
     */
    private function _add_agent_metadata($agents) {
        $ids = array_map(function($a){ return (int)$a->ID; }, $agents);

        // Récupérer en bloc les métadatas requises
        $meta_rows = $this->wp_db->select('user_id, meta_key, meta_value')
            ->from($this->usermeta_table)
            ->where_in('user_id', $ids)
            ->where_in('meta_key', $this->wanted_meta_keys)
            ->get()->result();

        $byUser = [];
        foreach ($meta_rows as $r) {
            if(!isset($byUser[$r->user_id])) $byUser[$r->user_id] = [];
            $byUser[$r->user_id][$r->meta_key] = $r->meta_value;
        }

        foreach ($agents as $a) {
            $m = $byUser[$a->ID] ?? [];
            foreach ($this->wanted_meta_keys as $k) {
                $prop = $k; // on mappe directement
                $a->$prop = $m[$k] ?? '';
            }
            
            // Construire un nom complet pour l'affichage
            $first_name = $a->first_name ?? '';
            $last_name = $a->last_name ?? '';
            if ($first_name || $last_name) {
                $a->full_name = trim($first_name . ' ' . $last_name);
            } else {
                $a->full_name = $a->display_name;
            }
        }
    }

    // Un agent
    public function get_agent($agent_id) {
        return $this->wp_db->where('ID', $agent_id)->get('wp_Hrg8P_users')->row();
    }

    // Créer un agent
    public function create_agent($data) {
        $this->wp_db->insert('wp_Hrg8P_users', $data);
        return $this->wp_db->insert_id();
    }

    // Mettre à jour un agent
    public function update_agent($agent_id, $data) {
        $this->wp_db->where('ID', $agent_id);
        return $this->wp_db->update('wp_Hrg8P_users', $data);
    }

    // Supprimer un agent
    public function delete_agent($agent_id) {
        $this->wp_db->where('ID', $agent_id);
        return $this->wp_db->delete('wp_Hrg8P_users');
    }

    // Statistiques agent (exemple)
    public function get_agent_stats($agent_id) {
        // À adapter selon structure
        return [
            'leads' => 0,
            'properties' => 0,
            'sales' => 0
        ];
    }

}
