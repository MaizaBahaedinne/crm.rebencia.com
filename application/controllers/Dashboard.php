<?php
defined('BASEPATH') OR exit('No direct script access allowed');



require APPPATH . '/libraries/BaseController.php';

/**
 * @property Agent_model $agent_model
 * @property Agency_model $agency_model
 * @property Activity_model $activity_model
 * @property Transaction_model $transaction_model
 * @property Task_model $task_model
 * @property CI_DB_query_builder $db
 * @property CI_Session $session
 * @property CI_Input $input
 */
/**
 * @property CI_Input $input
 */
class Dashboard extends BaseController {
    public function __construct() {
        parent::__construct();
        $this->load->model('Agent_model','agent_model');
        $this->load->model('Agency_model','agency_model');
        $this->load->model('Activity_model','activity_model');
        $this->load->model('Transaction_model','transaction_model');
        $this->load->model('Task_model','task_model');
        $this->load->library('session');
        $this->load->helper('url');
        
        // Charger la connexion WordPress comme dans Agent_model
        $this->wp_db = $this->load->database('wordpress', TRUE);
        $this->posts_table = $this->wp_db->dbprefix('posts');
        $this->postmeta_table = $this->wp_db->dbprefix('postmeta');
    }


    public function index() {
        $this->isLoggedIn();
        $role = $this->session->userdata('role');
        if ($role === 'administrator') {
            redirect('dashboard/admin');
        } elseif ($role === 'agency') {
            $agency_id = $this->session->userdata('agency_id');
            redirect('dashboard/agency/' . $agency_id);
        } elseif ($role === 'agent') {
            $agent_id = $this->session->userdata('agent_id');
            redirect('dashboard/agent/' . $agent_id);
        } else {
            redirect('login');
        }
    }


    // Vue Admin : toutes les agences, agents, stats globales
    public function admin() {
        $this->isLoggedIn();
        
        $data = $this->global;
        $data['pageTitle'] = 'Dashboard Admin';
        
        // Statistiques de base
        try {
            $agencies = $this->agency_model->get_all_agencies_from_posts();
            $agents = $this->agent_model->get_all_agents_from_posts();
            
            $data['stats'] = [
                'agencies' => count($agencies),
                'agents' => count($agents),
                'properties' => 150, // Valeur par défaut pour le moment
                'transactions' => 25,
                'leads' => 40,
                'clients' => 85,
                'revenue' => 750000,
                'growth' => 12.5
            ];
            
            // Données pour les graphiques (données de test)
            $data['chart_data'] = [
                'monthly_sales' => [
                    ['month' => 'Jan 2025', 'sales' => 5],
                    ['month' => 'Feb 2025', 'sales' => 8],
                    ['month' => 'Mar 2025', 'sales' => 12],
                    ['month' => 'Apr 2025', 'sales' => 7],
                    ['month' => 'May 2025', 'sales' => 15],
                    ['month' => 'Jun 2025', 'sales' => 18],
                    ['month' => 'Jul 2025', 'sales' => 22],
                    ['month' => 'Aug 2025', 'sales' => 16],
                    ['month' => 'Sep 2025', 'sales' => 25],
                    ['month' => 'Oct 2025', 'sales' => 20],
                    ['month' => 'Nov 2025', 'sales' => 28],
                    ['month' => 'Dec 2025', 'sales' => 30]
                ],
                'properties_by_type' => [
                    ['property_type' => 'Appartement', 'count' => 45],
                    ['property_type' => 'Maison', 'count' => 35],
                    ['property_type' => 'Villa', 'count' => 25],
                    ['property_type' => 'Studio', 'count' => 20],
                    ['property_type' => 'Bureau', 'count' => 15]
                ]
            ];
            
            // Activités récentes (données de test)
            $data['recent_activities'] = [
                ['post_title' => 'Villa moderne à Tunis', 'post_type' => 'property', 'post_date' => date('Y-m-d H:i:s')],
                ['post_title' => 'Ahmed Ben Ali', 'post_type' => 'houzez_agent', 'post_date' => date('Y-m-d H:i:s', strtotime('-1 hour'))],
                ['post_title' => 'Appartement centre-ville', 'post_type' => 'property', 'post_date' => date('Y-m-d H:i:s', strtotime('-2 hours'))],
                ['post_title' => 'Agence Immobilière Nord', 'post_type' => 'houzez_agency', 'post_date' => date('Y-m-d H:i:s', strtotime('-3 hours'))],
                ['post_title' => 'Maison avec jardin', 'post_type' => 'property', 'post_date' => date('Y-m-d H:i:s', strtotime('-4 hours'))]
            ];
            
            // Top agents et agences
            $data['top_agents'] = array_slice($agents, 0, 5);
            $data['top_agencies'] = array_slice($agencies, 0, 5);
            
        } catch (Exception $e) {
            // En cas d'erreur, utiliser des données par défaut
            $data['stats'] = [
                'agencies' => 0,
                'agents' => 0,
                'properties' => 0,
                'transactions' => 0,
                'leads' => 0,
                'clients' => 0,
                'revenue' => 0,
                'growth' => 0
            ];
            
            $data['chart_data'] = [
                'monthly_sales' => [],
                'properties_by_type' => []
            ];
            
            $data['recent_activities'] = [];
            $data['top_agents'] = [];
            $data['top_agencies'] = [];
        }
        
        $this->loadViews('dashboard/admin_modern', $this->global, $data, NULL);
    }
    
    /**
     * Récupère les statistiques générales pour le dashboard
     */
    private function get_admin_statistics() {
        // Utiliser les modèles existants pour éviter les erreurs de base de données
        $agencies = $this->agency_model->get_all_agencies_from_posts();
        $agents = $this->agent_model->get_all_agents_from_posts();
        
        return [
            'agencies' => count($agencies),
            'agents' => count($agents),
            'properties' => 150, // Valeur par défaut
            'transactions' => 25,
            'leads' => 40,
            'clients' => 85,
            'revenue' => 750000,
            'growth' => 12.5
        ];
    }

    // Vue Agence : données d'une agence
    public function agency($agency_id) {
        $this->isLoggedIn();
        $data['agency'] = $this->agency_model->get_agency($agency_id);
        $data['agents'] = $this->agent_model->get_agents_by_agency($agency_id);
        $data['stats'] = $this->activity_model->get_agency_stats($agency_id);
        $this->loadViews('dashboard/agency', $this->global, $data, NULL);
    }

    // Vue Agent : données d'un agent
    public function agent($agent_id) {
        $this->isLoggedIn();
        $data['agent'] = $this->agent_model->get_agent($agent_id);
        $data['stats'] = $this->activity_model->get_agent_stats($agent_id);
        $this->loadViews('dashboard/agent', $this->global, $data, NULL);
    }
}
