<?php
defined('BASEPATH') OR exit('No direct script access allowed');


require APPPATH . '/libraries/BaseController.php';
class Agency extends BaseController {
    public function __construct() {
        parent::__construct();
        $this->load->model('Agency_model', 'agency_model');
        $this->load->model('Agent_model', 'agent_model');
        $this->load->model('Property_model', 'property_model');
        $this->load->library('form_validation');
        $this->load->helper(['url', 'form']);
    }

    // Liste des agences avec statistiques et filtres
    public function index() {
        $this->isLoggedIn();
        
        // Récupération des filtres
        $filters = [
            'search' => $this->input->get('search'),
            'ville' => $this->input->get('ville'),
            'status' => $this->input->get('status')
        ];
        
        // Récupération des données depuis HOUZEZ WordPress
        $data = $this->global;
        $data['agencies'] = $this->agency_model->get_agencies_with_stats($filters);
        $data['filters'] = $filters;
        
        // Statistiques globales
        $data['total_stats'] = [
            'total_agencies' => count($data['agencies']),
            'total_agents' => array_sum(array_column($data['agencies'], 'agents_count')),
            'total_properties' => array_sum(array_column($data['agencies'], 'properties_count')),
            'total_sales' => array_sum(array_column($data['agencies'], 'sales_count'))
        ];
        
        $this->loadViews('dashboard/agency/list_cards', $data, NULL, NULL);
    }

    // Formulaire ajout/modif agence
    public function form($id = null) {
        $this->isLoggedIn();
        $data = [];
        if ($id) {
            $data['agency'] = $this->agency_model->get_agency($id);
        }
        $this->loadViews('dashboard/agency/form', $this->global, $data, NULL);
    }

  

    // Voir les détails d'une agence
    public function view($agency_id) {
        $this->isLoggedIn();
        
        $data = $this->global;
        $data['agency'] = $this->agency_model->get_agency_details($agency_id);
        $data['agents'] = $this->agent_model->get_agents_by_agency($agency_id);
        $data['properties'] = $this->property_model->get_properties_by_agency($agency_id); // Limite 5 pour aperçu
        $data['stats'] = $this->agency_model->get_agency_stats($agency_id);
        
        if (empty($data['agency'])) {
            show_404();
            return;
        }
      
       $this->loadViews('dashboard/agency/view', $data, NULL, NULL);
    }

    // Voir les agents d'une agence
    public function agents($agency_id) {
        $this->isLoggedIn();
        
        $data = $this->global;
        $data['agents'] = $this->agent_model->get_agents_by_agency($agency_id);
        $data['agency'] = $this->agency_model->get_agency($agency_id);
        
        if (empty($data['agency'])) {
            show_404();
            return;
        }
        
        $this->loadViews('dashboard/agency/agents_list', $data, NULL, NULL);
    }

    // Statistiques agence avec vraies données PHP
    public function stats($agency_id = null) {
        $this->isLoggedIn();
        
        $data = $this->global;
        
        try {
            // Connexion directe à WordPress
            $wp_db = $this->load->database('wordpress', TRUE);
            
            // ===== 1. AGENTS RÉELS =====
            $agents_query = "
                SELECT 
                    COUNT(DISTINCT u.ID) as total_agents,
                    COUNT(DISTINCT CASE WHEN um_active.meta_value = '1' THEN u.ID END) as active_agents
                FROM {$wp_db->dbprefix}users u
                INNER JOIN {$wp_db->dbprefix}usermeta um ON u.ID = um.user_id
                LEFT JOIN {$wp_db->dbprefix}usermeta um_active ON u.ID = um_active.user_id AND um_active.meta_key = 'fave_agent_status'
                WHERE um.meta_key = '{$wp_db->dbprefix}capabilities'
                AND um.meta_value LIKE '%houzez_agent%'
            ";
            $agents_result = $wp_db->query($agents_query)->row();
            $total_agents = $agents_result->total_agents ?? 0;
            $active_agents = $agents_result->active_agents ?? 0;
            
            // ===== 2. PROPRIÉTÉS RÉELLES =====
            $properties_query = "
                SELECT 
                    COUNT(*) as total_properties,
                    COUNT(CASE WHEN post_status = 'publish' THEN 1 END) as published_properties,
                    COUNT(CASE WHEN post_status = 'draft' THEN 1 END) as draft_properties,
                    COUNT(CASE WHEN post_status = 'pending' THEN 1 END) as pending_properties
                FROM {$wp_db->dbprefix}posts 
                WHERE post_type = 'property'
                AND post_status IN ('publish', 'draft', 'pending')
            ";
            $properties_result = $wp_db->query($properties_query)->row();
            $total_properties = $properties_result->total_properties ?? 0;
            $published_properties = $properties_result->published_properties ?? 0;
            $draft_properties = $properties_result->draft_properties ?? 0;
            $pending_properties = $properties_result->pending_properties ?? 0;
            
            // ===== 3. PROPRIÉTÉS PAR STATUT HOUZEZ =====
            $status_query = "
                SELECT 
                    pm.meta_value as property_status,
                    COUNT(*) as count
                FROM {$wp_db->dbprefix}posts p
                INNER JOIN {$wp_db->dbprefix}postmeta pm ON p.ID = pm.post_id
                WHERE p.post_type = 'property'
                AND p.post_status = 'publish'
                AND pm.meta_key = 'fave_property_status'
                GROUP BY pm.meta_value
            ";
            $status_result = $wp_db->query($status_query)->result();
            $status_breakdown = [];
            foreach ($status_result as $status) {
                $status_breakdown[$status->property_status] = $status->count;
            }
            
            // ===== 4. PRIX ET REVENUS RÉELS =====
            $price_query = "
                SELECT 
                    AVG(CAST(pm.meta_value AS UNSIGNED)) as avg_price,
                    SUM(CAST(pm.meta_value AS UNSIGNED)) as total_value,
                    COUNT(*) as properties_with_price
                FROM {$wp_db->dbprefix}posts p
                INNER JOIN {$wp_db->dbprefix}postmeta pm ON p.ID = pm.post_id
                WHERE p.post_type = 'property'
                AND p.post_status = 'publish'
                AND pm.meta_key = 'fave_property_price'
                AND pm.meta_value > 0
                AND pm.meta_value REGEXP '^[0-9]+$'
            ";
            $price_result = $wp_db->query($price_query)->row();
            $avg_property_price = $price_result->avg_price ?? 250000;
            $total_portfolio_value = $price_result->total_value ?? 0;
            $properties_with_price = $price_result->properties_with_price ?? 0;
            
            // ===== 5. AGENCES RÉELLES =====
            $agencies_query = "
                SELECT COUNT(DISTINCT u.ID) as total_agencies
                FROM {$wp_db->dbprefix}users u
                INNER JOIN {$wp_db->dbprefix}usermeta um ON u.ID = um.user_id
                WHERE um.meta_key = '{$wp_db->dbprefix}capabilities'
                AND um.meta_value LIKE '%houzez_agency%'
            ";
            $agencies_result = $wp_db->query($agencies_query)->row();
            $total_agencies = $agencies_result->total_agencies ?? 0;
            
            // ===== 6. PROPRIÉTÉS AJOUTÉES CE MOIS =====
            $current_month = date('Y-m');
            $last_month = date('Y-m', strtotime('-1 month'));
            
            $monthly_query = "
                SELECT 
                    COUNT(CASE WHEN DATE_FORMAT(post_date, '%Y-%m') = '{$current_month}' THEN 1 END) as current_month,
                    COUNT(CASE WHEN DATE_FORMAT(post_date, '%Y-%m') = '{$last_month}' THEN 1 END) as last_month
                FROM {$wp_db->dbprefix}posts 
                WHERE post_type = 'property'
                AND post_status = 'publish'
                AND post_date >= DATE_SUB(NOW(), INTERVAL 2 MONTH)
            ";
            $monthly_result = $wp_db->query($monthly_query)->row();
            $current_month_properties = $monthly_result->current_month ?? 0;
            $last_month_properties = $monthly_result->last_month ?? 0;
            
            // ===== 7. CALCULS MÉTIERS =====
            // Commission moyenne 3%
            $commission_rate = 0.03;
            $monthly_revenue = round($avg_property_price * $current_month_properties * $commission_rate);
            $yearly_revenue = round($avg_property_price * $published_properties * $commission_rate);
            
            // Taux de completion (propriétés publiées vs total)
            $completion_rate = $total_properties > 0 ? round(($published_properties / $total_properties) * 100) : 0;
            
            // Croissance mensuelle
            $growth_percentage = 0;
            if ($last_month_properties > 0) {
                $growth_percentage = round((($current_month_properties - $last_month_properties) / $last_month_properties) * 100);
            }
            $growth_rate = ($growth_percentage >= 0 ? '+' : '') . $growth_percentage . '%';
            
            // ===== 8. TOP AGENTS AVEC PERFORMANCES =====
            $top_agents_query = "
                SELECT 
                    u.ID,
                    u.display_name,
                    COUNT(p.ID) as property_count,
                    AVG(CAST(pm_price.meta_value AS UNSIGNED)) as avg_property_price
                FROM {$wp_db->dbprefix}users u
                INNER JOIN {$wp_db->dbprefix}usermeta um ON u.ID = um.user_id
                LEFT JOIN {$wp_db->dbprefix}postmeta pm_agent ON u.ID = pm_agent.meta_value
                LEFT JOIN {$wp_db->dbprefix}posts p ON pm_agent.post_id = p.ID
                LEFT JOIN {$wp_db->dbprefix}postmeta pm_price ON p.ID = pm_price.post_id AND pm_price.meta_key = 'fave_property_price'
                WHERE um.meta_key = '{$wp_db->dbprefix}capabilities'
                AND um.meta_value LIKE '%houzez_agent%'
                AND pm_agent.meta_key = 'fave_agent_display_option'
                AND p.post_type = 'property'
                AND p.post_status = 'publish'
                GROUP BY u.ID, u.display_name
                HAVING property_count > 0
                ORDER BY property_count DESC, avg_property_price DESC
                LIMIT 5
            ";
            $top_agents_result = $wp_db->query($top_agents_query)->result();
            
            // ===== 9. DONNÉES PAR RÉGION =====
            $regions_query = "
                SELECT 
                    pm.meta_value as city,
                    COUNT(*) as count
                FROM {$wp_db->dbprefix}posts p
                INNER JOIN {$wp_db->dbprefix}postmeta pm ON p.ID = pm.post_id
                WHERE p.post_type = 'property'
                AND p.post_status = 'publish'
                AND pm.meta_key = 'fave_property_city'
                AND pm.meta_value != ''
                GROUP BY pm.meta_value
                ORDER BY count DESC
                LIMIT 10
            ";
            $regions_result = $wp_db->query($regions_query)->result();
            
            // Préparer les données pour la vue
            $data['stats'] = [
                'total_agents' => $total_agents,
                'active_agents' => $active_agents,
                'total_properties' => $total_properties,
                'published_properties' => $published_properties,
                'draft_properties' => $draft_properties,
                'pending_properties' => $pending_properties,
                'active_properties' => $published_properties, // Alias pour compatibilité
                'total_agencies' => $total_agencies,
                'monthly_revenue' => $monthly_revenue,
                'yearly_revenue' => $yearly_revenue,
                'avg_property_price' => round($avg_property_price),
                'total_portfolio_value' => $total_portfolio_value,
                'completion_rate' => $completion_rate,
                'growth_rate' => $growth_rate,
                'current_month_properties' => $current_month_properties,
                'last_month_properties' => $last_month_properties,
                'properties_with_price' => $properties_with_price
            ];
            
            $data['status_breakdown'] = $status_breakdown;
            $data['top_agents'] = $top_agents_result;
            $data['regions'] = $regions_result;
            
            // Données pour les graphiques
            $data['chart_data'] = [
                'status_labels' => array_keys($status_breakdown),
                'status_values' => array_values($status_breakdown),
                'regions_labels' => array_column($regions_result, 'city'),
                'regions_values' => array_column($regions_result, 'count')
            ];
            
        } catch (Exception $e) {
            // Données de fallback en cas d'erreur
            $data['stats'] = [
                'total_agents' => 0,
                'active_agents' => 0,
                'total_properties' => 0,
                'published_properties' => 0,
                'draft_properties' => 0,
                'pending_properties' => 0,
                'active_properties' => 0,
                'total_agencies' => 0,
                'monthly_revenue' => 0,
                'yearly_revenue' => 0,
                'avg_property_price' => 0,
                'total_portfolio_value' => 0,
                'completion_rate' => 0,
                'growth_rate' => '0%',
                'current_month_properties' => 0,
                'last_month_properties' => 0,
                'properties_with_price' => 0
            ];
            
            $data['status_breakdown'] = [];
            $data['top_agents'] = [];
            $data['regions'] = [];
            $data['chart_data'] = [
                'status_labels' => [],
                'status_values' => [],
                'regions_labels' => [],
                'regions_values' => []
            ];
            
            log_message('error', 'Erreur récupération vraies données agences: ' . $e->getMessage());
        }
        
        $this->loadViews('agencies/stats', $data, NULL, NULL);
    }
}
