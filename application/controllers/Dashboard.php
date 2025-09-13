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
        
        // Statistiques générales
        $data['stats'] = $this->get_admin_statistics();
        
        // Données pour les graphiques
        $data['chart_data'] = $this->get_chart_data();
        
        // Activités récentes
        $data['recent_activities'] = $this->get_recent_activities();
        
        // Top agents et agences
        $data['top_agents'] = $this->get_top_agents();
        $data['top_agencies'] = $this->get_top_agencies();
        
        $this->loadViews('dashboard/admin_modern', $this->global, $data, NULL);
    }
    
    /**
     * Récupère les statistiques générales pour le dashboard
     */
    private function get_admin_statistics() {
        // Compter les agences
        $agencies_count = $this->db->from('wp_Hrg8P_posts')
            ->where('post_type', 'houzez_agency')
            ->where('post_status', 'publish')
            ->count_all_results();
            
        // Compter les agents
        $agents_count = $this->db->from('wp_Hrg8P_posts')
            ->where('post_type', 'houzez_agent')
            ->where('post_status', 'publish')
            ->count_all_results();
            
        // Compter les propriétés
        $properties_count = $this->db->from('wp_Hrg8P_posts')
            ->where('post_type', 'property')
            ->where('post_status', 'publish')
            ->count_all_results();
            
        // Compter les transactions (si la table existe)
        $transactions_count = 0;
        if ($this->db->table_exists('crm_transactions')) {
            $transactions_count = $this->db->count_all('crm_transactions');
        }
        
        // Compter les leads (si la table existe)
        $leads_count = 0;
        if ($this->db->table_exists('crm_leads')) {
            $leads_count = $this->db->count_all('crm_leads');
        }
        
        // Compter les clients (si la table existe)
        $clients_count = 0;
        if ($this->db->table_exists('crm_clients')) {
            $clients_count = $this->db->count_all('crm_clients');
        }
        
        return [
            'agencies' => $agencies_count,
            'agents' => $agents_count,
            'properties' => $properties_count,
            'transactions' => $transactions_count,
            'leads' => $leads_count,
            'clients' => $clients_count,
            'revenue' => $this->calculate_total_revenue(),
            'growth' => $this->calculate_growth_percentage()
        ];
    }
    
    /**
     * Récupère les données pour les graphiques
     */
    private function get_chart_data() {
        return [
            'monthly_sales' => $this->get_monthly_sales_data(),
            'properties_by_type' => $this->get_properties_by_type(),
            'agents_performance' => $this->get_agents_performance(),
            'monthly_activities' => $this->get_monthly_activities()
        ];
    }
    
    /**
     * Données des ventes mensuelles
     */
    private function get_monthly_sales_data() {
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $sales = 0;
            
            if ($this->db->table_exists('crm_transactions')) {
                $sales = $this->db->where("DATE_FORMAT(created_at, '%Y-%m')", $month)
                    ->where('transaction_type', 'sale')
                    ->count_all_results('crm_transactions');
            }
            
            $data[] = [
                'month' => date('M Y', strtotime($month)),
                'sales' => $sales
            ];
        }
        return $data;
    }
    
    /**
     * Répartition des propriétés par type
     */
    private function get_properties_by_type() {
        $query = $this->db->select('pm.meta_value as property_type, COUNT(*) as count')
            ->from('wp_Hrg8P_posts p')
            ->join('wp_Hrg8P_postmeta pm', 'p.ID = pm.post_id AND pm.meta_key = "fave_property_type"', 'left')
            ->where('p.post_type', 'property')
            ->where('p.post_status', 'publish')
            ->group_by('pm.meta_value')
            ->get();
            
        return $query->result_array();
    }
    
    /**
     * Performance des agents
     */
    private function get_agents_performance() {
        $query = $this->db->select('p.post_title as agent_name, COUNT(prop.ID) as properties_count')
            ->from('wp_Hrg8P_posts p')
            ->join('wp_Hrg8P_postmeta pm', 'p.ID = pm.post_id', 'left')
            ->join('wp_Hrg8P_posts prop', 'prop.ID = pm.post_id AND pm.meta_key = "fave_property_agent" AND pm.meta_value = p.ID', 'left')
            ->where('p.post_type', 'houzez_agent')
            ->where('p.post_status', 'publish')
            ->group_by('p.ID, p.post_title')
            ->order_by('properties_count', 'DESC')
            ->limit(10)
            ->get();
            
        return $query->result_array();
    }
    
    /**
     * Activités mensuelles
     */
    private function get_monthly_activities() {
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $activities = $this->db->where("DATE_FORMAT(post_date, '%Y-%m')", $month)
                ->where_in('post_type', ['property', 'houzez_agent', 'houzez_agency'])
                ->where('post_status', 'publish')
                ->count_all_results('wp_Hrg8P_posts');
                
            $data[] = [
                'month' => date('M', strtotime($month)),
                'activities' => $activities
            ];
        }
        return $data;
    }
    
    /**
     * Calcule le revenu total
     */
    private function calculate_total_revenue() {
        if (!$this->db->table_exists('crm_transactions')) {
            return 0;
        }
        
        $result = $this->db->select_sum('amount')
            ->where('transaction_type', 'sale')
            ->get('crm_transactions')
            ->row();
            
        return $result ? $result->amount : 0;
    }
    
    /**
     * Calcule le pourcentage de croissance
     */
    private function calculate_growth_percentage() {
        $current_month = date('Y-m');
        $last_month = date('Y-m', strtotime('-1 month'));
        
        $current_count = $this->db->where("DATE_FORMAT(post_date, '%Y-%m')", $current_month)
            ->where('post_type', 'property')
            ->where('post_status', 'publish')
            ->count_all_results('wp_Hrg8P_posts');
            
        $last_count = $this->db->where("DATE_FORMAT(post_date, '%Y-%m')", $last_month)
            ->where('post_type', 'property')
            ->where('post_status', 'publish')
            ->count_all_results('wp_Hrg8P_posts');
            
        if ($last_count == 0) return 0;
        
        return round((($current_count - $last_count) / $last_count) * 100, 1);
    }
    
    /**
     * Récupère les activités récentes
     */
    private function get_recent_activities() {
        return $this->db->select('post_title, post_type, post_date')
            ->from('wp_Hrg8P_posts')
            ->where_in('post_type', ['property', 'houzez_agent', 'houzez_agency'])
            ->where('post_status', 'publish')
            ->order_by('post_date', 'DESC')
            ->limit(10)
            ->get()
            ->result_array();
    }
    
    /**
     * Top agents par nombre de propriétés
     */
    private function get_top_agents() {
        return $this->agent_model->get_all_agents_from_posts(['limit' => 5]);
    }
    
    /**
     * Top agences par nombre d'agents
     */
    private function get_top_agencies() {
        return $this->agency_model->get_all_agencies_from_posts(['limit' => 5]);
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
