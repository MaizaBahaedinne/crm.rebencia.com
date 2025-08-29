<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agency_model extends CI_Model {


    protected $wp_db;
    public function __construct() {
        parent::__construct();
        $this->wp_db = $this->load->database('wordpress', TRUE);
    }
    // Toutes les agences (role = houzez_agency)
    public function get_all_agencies() {
        $this->wp_db->select('u.*')
            ->from('wp_Hrg8P_users u')
            ->join('wp_Hrg8P_usermeta m', 'u.ID = m.user_id')
            ->where('m.meta_key', $this->wp_db->dbprefix('capabilities'))
            ->like('m.meta_value', 'houzez_agency');
        return $this->wp_db->get()->result();
    }

    // Une agence
    public function get_agency($agency_id) {
        return $this->wp_db->where('ID', $agency_id)->get('wp_Hrg8P_users')->row();
    }

    // Créer une agence
    public function create_agency($data) {
        $this->wp_db->insert('wp_Hrg8P_users', $data);
        return $this->wp_db->insert_id();
    }

    // Mettre à jour une agence
    public function update_agency($agency_id, $data) {
        $this->wp_db->where('ID', $agency_id);
        return $this->wp_db->update('wp_Hrg8P_users', $data);
    }

    // Supprimer une agence
    public function delete_agency($agency_id) {
        $this->wp_db->where('ID', $agency_id);
        return $this->wp_db->delete('wp_Hrg8P_users');
    }

    // Statistiques agence (exemple)
    public function get_agency_stats($agency_id) {
        // À adapter selon structure
        return [
            'agents' => $this->count_agents($agency_id),
            'properties' => $this->count_properties($agency_id)
        ];
    }

    // Compter les agents d'une agence
    public function count_agents($agency_id) {
        $this->wp_db->from('wp_Hrg8P_users u')
            ->join('wp_Hrg8P_usermeta m', 'u.ID = m.user_id')
            ->where('m.meta_key', 'houzez_agency_id')
            ->where('m.meta_value', $agency_id);
        return $this->wp_db->count_all_results();
    }

    // Compter les propriétés d'une agence
    public function count_properties($agency_id) {
        // À adapter selon la structure de la BDD
        return 0;
    }

}
