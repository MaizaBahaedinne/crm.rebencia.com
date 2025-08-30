<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agent_model extends CI_Model {
    protected $wp_db;
    public function __construct() {
        parent::__construct();
        $this->wp_db = $this->load->database('wordpress', TRUE);
    }
    // Tous les agents (basé sur la vue crm_agents)
    public function get_all_agents() {
        return $this->wp_db->get('crm_agents')->result();
    }
    

    // Agents d'une agence
    public function get_agents_by_agency($agency_id) {
        $this->wp_db->select('u.*')
            ->from('wp_Hrg8P_users u')
            ->join('wp_Hrg8P_usermeta m', 'u.ID = m.user_id')
            ->where('m.meta_key', 'houzez_agency_id')
            ->where('m.meta_value', $agency_id);
        return $this->wp_db->get()->result();
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
