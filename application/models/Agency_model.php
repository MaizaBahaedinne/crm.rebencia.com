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
}
