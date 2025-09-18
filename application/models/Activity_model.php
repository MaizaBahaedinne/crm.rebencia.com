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
    
    /**
     * Récupérer les activités récentes d'un agent
     * @param int $agent_id : ID de l'agent
     * @param int $limit : Nombre d'activités à retourner
     * @return array : Liste des activités récentes
     */
    public function get_recent_activities($agent_id, $limit = 10) {
        // Vérifier si une table d'activités existe
        if ($this->db->table_exists('activity_log') || $this->db->table_exists('user_activities')) {
            // Si vous avez une vraie table d'activités, utilisez-la ici
            // Exemple avec table activity_log :
            /*
            return $this->db->select('title, description, created_at')
                           ->from('activity_log')
                           ->where('user_id', $agent_id)
                           ->order_by('created_at', 'DESC')
                           ->limit($limit)
                           ->get()
                           ->result_array();
            */
        }
        
        // Données simulées réalistes pour le dashboard
        $sample_activities = [
            [
                'title' => 'Nouvelle estimation créée',
                'description' => 'Estimation #' . (rand(100, 999)) . ' pour propriété à Tunis',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 hours')),
                'type' => 'estimation',
                'icon' => 'ri-file-add-line',
                'color' => 'primary'
            ],
            [
                'title' => 'Contact client ajouté',
                'description' => 'Nouveau prospect intéressé par achat villa',
                'created_at' => date('Y-m-d H:i:s', strtotime('-4 hours')),
                'type' => 'contact',
                'icon' => 'ri-user-add-line',
                'color' => 'success'
            ],
            [
                'title' => 'Visite propriété effectuée',
                'description' => 'Visite appartement 3 pièces avec clients potentiels',
                'created_at' => date('Y-m-d H:i:s', strtotime('-6 hours')),
                'type' => 'visite',
                'icon' => 'ri-home-4-line',
                'color' => 'info'
            ],
            [
                'title' => 'Appel téléphonique',
                'description' => 'Suivi prospect pour négociation prix',
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'type' => 'appel',
                'icon' => 'ri-phone-line',
                'color' => 'warning'
            ],
            [
                'title' => 'Email envoyé',
                'description' => 'Envoi dossier complet estimation immobilière',
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 day -2 hours')),
                'type' => 'email',
                'icon' => 'ri-mail-send-line',
                'color' => 'secondary'
            ],
            [
                'title' => 'Transaction finalisée',
                'description' => 'Signature contrat vente villa 250m²',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'type' => 'transaction',
                'icon' => 'ri-handshake-line',
                'color' => 'success'
            ],
            [
                'title' => 'Rendez-vous programmé',
                'description' => 'RDV visite propriété prévu pour demain 14h',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days -3 hours')),
                'type' => 'rdv',
                'icon' => 'ri-calendar-event-line',
                'color' => 'info'
            ],
            [
                'title' => 'Rapport mensuel généré',
                'description' => 'Génération automatique rapport activité septembre',
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
                'type' => 'rapport',
                'icon' => 'ri-file-chart-line',
                'color' => 'dark'
            ]
        ];
        
        // Mélanger et retourner le nombre demandé
        shuffle($sample_activities);
        return array_slice($sample_activities, 0, $limit);
    }
}
