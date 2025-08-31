<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Activity_model extends CI_Model {
    protected $wp_db;
    public function __construct() {
        parent::__construct();
        $this->wp_db = $this->load->database('wordpress', TRUE);
    }
    // Stats globales
    public function get_global_stats() {
        return [
            'leads' => $this->count_leads(),
            'properties' => $this->count_properties(),
            'sales' => $this->count_sales()
        ];
    }
    // Stats agence
    public function get_agency_stats($agency_id) {
        return [
            'leads' => $this->count_leads($agency_id),
            'properties' => $this->count_properties($agency_id),
            'sales' => $this->count_sales($agency_id)
        ];
    }
    // Stats agent
    public function get_agent_stats($agent_id) {
        return [
            'leads' => $this->count_leads(null, $agent_id),
            'properties' => $this->count_properties(null, $agent_id),
            'sales' => $this->count_sales(null, $agent_id)
        ];
    }
    // Compteurs (simplifiés)
    private function count_leads($agency_id = null, $agent_id = null) {
        $this->wp_db->from('wp_Hrg8P_postmeta');
        $this->wp_db->where('meta_key', 'fave_agent_id');
        if ($agent_id) $this->wp_db->where('meta_value', $agent_id);
        // TODO: filtrer par agence si besoin
        return $this->wp_db->count_all_results();
    }
    private function count_properties($agency_id = null, $agent_id = null) {
        $this->wp_db->from('wp_Hrg8P_posts');
        $this->wp_db->where('post_type', 'property');
        // TODO: filtrer par agence/agent si besoin
        return $this->wp_db->count_all_results();
    }
    private function count_sales($agency_id = null, $agent_id = null) {
        $this->wp_db->from('wp_Hrg8P_postmeta');
        $this->wp_db->where('meta_key', 'fave_property_status');
        $this->wp_db->where('meta_value', 'sold');
        // TODO: filtrer par agence/agent si besoin
        return $this->wp_db->count_all_results();
    }

    /* === Expositions publiques simplifiées === */
    public function get_clients_count() { return $this->count_leads(); }
    public function get_transactions_count() { return $this->count_sales(); }
}
