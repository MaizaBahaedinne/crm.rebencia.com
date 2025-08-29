<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agent_model extends CI_Model {
    protected $wp_db;
    public function __construct() {
        parent::__construct();
        $this->wp_db = $this->load->database('wordpress', TRUE);
    }
    // Tous les agents
    public function get_all_agents() {
        return $this->wp_db->where('user_status', 0)->get('wp_Hrg8P_users')->result();
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
}
