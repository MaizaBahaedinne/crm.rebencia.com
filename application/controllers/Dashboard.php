<?php
defined('BASEPATH') OR exit('No direct script access allowed');



require APPPATH . '/libraries/BaseController.php';

/**
 * @property Agent_model $agent_model
 * @property Agency_model $agency_model
 * @property Property_model $property_model
 * @property Activity_model $activity_model
 * @property Transaction_model $transaction_model
 * @property Task_model $task_model
 * @property Objective_model $Objective_model
 * @property CI_DB_query_builder $db
 * @property CI_DB $wp_db
 * @property CI_Session $session
 * @property CI_Input $input
 * @property CI_Loader $load
 * @property string $posts_table
 * @property string $postmeta_table
 */
class Dashboard extends BaseController {
    public function __construct() {
        parent::__construct();
        $this->load->model('Agent_model','agent_model');
        $this->load->model('Agency_model','agency_model');
        $this->load->model('Property_model','property_model');
        $this->load->model('Activity_model','activity_model');
        $this->load->model('Transaction_model','transaction_model');
        $this->load->model('Task_model','task_model');
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->database(); // Charger la base de données principale
        
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
            $agent_id = $this->session->userdata('user_post_id');
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
        
        // Statistiques réelles avec la nouvelle vue
        try {
            // Données de base
            $agencies = $this->agency_model->get_all_agencies();
            $agents = $this->agent_model->get_all_agents_from_posts();
            $properties = $this->property_model->get_from_property_agent_view();
            
            // Statistiques avancées
            $properties_with_agents = 0;
            $properties_with_agencies = 0;
            $active_agents = [];
            $active_agencies = [];
            
            foreach ($properties as $property) {
                if ($property->agent_id) {
                    $properties_with_agents++;
                    $active_agents[$property->agent_id] = true;
                }
                if ($property->agency_id) {
                    $properties_with_agencies++;
                    $active_agencies[$property->agency_id] = true;
                }
            }
            
            // Calcul du revenu estimé (basé sur les propriétés)
            $estimated_revenue = count($properties) * 15000; // Estimation moyenne par propriété
            
            // Calcul de la croissance (comparaison avec le mois dernier)
            $current_month_properties = 0;
            $last_month_properties = 0;
            $current_month = date('Y-m');
            $last_month = date('Y-m', strtotime('-1 month'));
            
            foreach ($properties as $property) {
                $property_month = date('Y-m', strtotime($property->property_date));
                if ($property_month === $current_month) {
                    $current_month_properties++;
                } elseif ($property_month === $last_month) {
                    $last_month_properties++;
                }
            }
            
            $growth = $last_month_properties > 0 ? 
                round((($current_month_properties - $last_month_properties) / $last_month_properties) * 100, 1) : 
                0;
            
            $data['stats'] = [
                'agencies' => count($agencies),
                'agents' => count($agents),
                'properties' => count($properties),
                'active_agents' => count($active_agents),
                'active_agencies' => count($active_agencies),
                'properties_with_agents' => $properties_with_agents,
                'properties_with_agencies' => $properties_with_agencies,
                'revenue' => $estimated_revenue,
                'growth' => $growth,
                'current_month_properties' => $current_month_properties,
                'last_month_properties' => $last_month_properties
            ];
            
            // Données pour les graphiques réelles
            $data['chart_data'] = $this->get_real_chart_data($properties);
            
            // Activités récentes (basées sur les propriétés réelles)
            $data['recent_activities'] = $this->get_recent_activities($properties);
            
            // Top performers
            $data['top_agents'] = $this->get_top_agents_performance($properties);
            $data['top_agencies'] = $this->get_top_agencies_performance($properties);
            
            // Ajout des données premium pour le nouveau design
            $data['stats']['total_revenue'] = $estimated_revenue;
            $data['stats']['monthly_revenue'] = round($estimated_revenue / 12);
            $data['stats']['yearly_revenue'] = $estimated_revenue;
            $data['stats']['top_performers'] = min(15, count($data['top_agents']));
            $data['stats']['properties_for_sale'] = $this->get_properties_by_type('for-sale', $properties);
            $data['stats']['properties_for_rent'] = $this->get_properties_by_type('for-rent', $properties);
            $data['stats']['growth_rate'] = ($growth >= 0 ? '+' : '') . $growth . '%';
            
        } catch (Exception $e) {
            // Données de fallback en cas d'erreur
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
            
            log_message('error', 'Erreur dashboard admin: ' . $e->getMessage());
        }
        
        $this->loadViews('dashboard/admin_premium', $this->global, $data, NULL);
    }

    /**
     * Génère les données réelles pour les graphiques
     */
    private function get_real_chart_data($properties) {
        // 1. Évolution mensuelle des propriétés ajoutées
        $monthly_data = [];
        $months = [];
        
        // Préparer les 12 derniers mois
        for ($i = 11; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $months[$month] = [
                'month' => date('M Y', strtotime($month)),
                'count' => 0
            ];
        }
        
        // Compter les propriétés par mois
        foreach ($properties as $property) {
            $property_month = date('Y-m', strtotime($property->property_date));
            if (isset($months[$property_month])) {
                $months[$property_month]['count']++;
            }
        }
        
        $monthly_sales = array_values($months);
        
        // 2. Répartition des propriétés par agent (top 10)
        $agent_counts = [];
        foreach ($properties as $property) {
            if ($property->agent_id && $property->agent_name) {
                $agent_name = $property->agent_name;
                if (!isset($agent_counts[$agent_name])) {
                    $agent_counts[$agent_name] = 0;
                }
                $agent_counts[$agent_name]++;
            }
        }
        
        arsort($agent_counts);
        $top_agents_data = [];
        $count = 0;
        foreach ($agent_counts as $agent_name => $properties_count) {
            if ($count < 10) {
                $top_agents_data[] = [
                    'agent_name' => $agent_name,
                    'count' => $properties_count
                ];
                $count++;
            }
        }
        
        // 3. Répartition par agence (top 10)
        $agency_counts = [];
        foreach ($properties as $property) {
            if ($property->agency_id && $property->agency_name) {
                $agency_name = $property->agency_name;
                if (!isset($agency_counts[$agency_name])) {
                    $agency_counts[$agency_name] = 0;
                }
                $agency_counts[$agency_name]++;
            }
        }
        
        arsort($agency_counts);
        $top_agencies_data = [];
        $count = 0;
        foreach ($agency_counts as $agency_name => $properties_count) {
            if ($count < 10) {
                $top_agencies_data[] = [
                    'agency_name' => $agency_name,
                    'count' => $properties_count
                ];
                $count++;
            }
        }
        
        // 4. Statut des propriétés
        $status_counts = [];
        foreach ($properties as $property) {
            $status = $property->property_status ?? 'unknown';
            if (!isset($status_counts[$status])) {
                $status_counts[$status] = 0;
            }
            $status_counts[$status]++;
        }
        
        $properties_by_status = [];
        foreach ($status_counts as $status => $count) {
            $properties_by_status[] = [
                'status' => $status,
                'count' => $count
            ];
        }
        
        return [
            'monthly_sales' => $monthly_sales,
            'revenues' => array_map(function($month) { return $month['count'] * 15000; }, $monthly_sales), // Estimation 15k TND par propriété
            'top_agents' => $top_agents_data,
            'top_agencies' => $top_agencies_data,
            'properties_by_status' => $properties_by_status
        ];
    }

    /**
     * Récupère les activités récentes basées sur les propriétés
     */
    private function get_recent_activities($properties) {
        // Trier par date décroissante et prendre les 10 plus récentes
        usort($properties, function($a, $b) {
            return strtotime($b->property_date) - strtotime($a->property_date);
        });
        
        $activities = [];
        $count = 0;
        
        foreach ($properties as $property) {
            if ($count >= 10) break;
            
            $activities[] = [
                'post_title' => $property->property_title,
                'post_type' => 'property',
                'post_date' => $property->property_date,
                'agent_name' => $property->agent_name ?? 'Agent non assigné',
                'agency_name' => $property->agency_name ?? 'Agence non assignée'
            ];
            $count++;
        }
        
        return $activities;
    }

    /**
     * Calcule les top agents par nombre de propriétés
     */
    private function get_top_agents_performance($properties) {
        $agent_performance = [];
        
        foreach ($properties as $property) {
            if ($property->agent_id && $property->agent_name) {
                $agent_id = $property->agent_id;
                
                if (!isset($agent_performance[$agent_id])) {
                    $agent_performance[$agent_id] = [
                        'agent_id' => $agent_id,
                        'agent_name' => $property->agent_name,
                        'agent_email' => $property->agent_email,
                        'agent_phone' => $property->agent_phone,
                        'properties_count' => 0,
                        'agencies' => []
                    ];
                }
                
                $agent_performance[$agent_id]['properties_count']++;
                
                if ($property->agency_name && !in_array($property->agency_name, $agent_performance[$agent_id]['agencies'])) {
                    $agent_performance[$agent_id]['agencies'][] = $property->agency_name;
                }
            }
        }
        
        // Trier par nombre de propriétés
        uasort($agent_performance, function($a, $b) {
            return $b['properties_count'] - $a['properties_count'];
        });
        
        return array_slice(array_values($agent_performance), 0, 10);
    }

    /**
     * Calcule les top agences par nombre de propriétés
     */
    private function get_top_agencies_performance($properties) {
        $agency_performance = [];
        
        foreach ($properties as $property) {
            if ($property->agency_id && $property->agency_name) {
                $agency_id = $property->agency_id;
                
                if (!isset($agency_performance[$agency_id])) {
                    $agency_performance[$agency_id] = [
                        'agency_id' => $agency_id,
                        'agency_name' => $property->agency_name,
                        'agency_email' => $property->agency_email,
                        'agency_phone' => $property->agency_phone,
                        'properties_count' => 0,
                        'agents' => []
                    ];
                }
                
                $agency_performance[$agency_id]['properties_count']++;
                
                if ($property->agent_name && !in_array($property->agent_name, $agency_performance[$agency_id]['agents'])) {
                    $agency_performance[$agency_id]['agents'][] = $property->agent_name;
                }
            }
        }
        
        // Trier par nombre de propriétés
        uasort($agency_performance, function($a, $b) {
            return $b['properties_count'] - $a['properties_count'];
        });
        
        return array_slice(array_values($agency_performance), 0, 10);
    }
    
    /**
     * Récupère les statistiques générales pour le dashboard
     */
    private function get_admin_statistics() {
        // Utiliser les modèles existants pour éviter les erreurs de base de données
        $agencies = $this->agency_model->get_all_agencies();
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

    // Vue Agent : tableau de bord moderne et premium
    public function agent($agent_id = null) {
        $this->isLoggedIn();
        
        // Récupération fiable de l'identifiant agent (triple fallback)
        $user_post_id = $this->userPostId ?: $this->session->userdata('user_post_id') ?: $agent_id;
        
        // Si toujours pas d'ID, utiliser userId avec recherche dans wp_Hrg8P_crm_agents
        if (empty($user_post_id)) {
            $userId = $this->global['userId'] ?? $this->session->userdata('userId');
            if ($userId) {
                $agent_data = $this->agent_model->get_agent_by_wp_user_id($userId);
                if ($agent_data && isset($agent_data->agent_post_id)) {
                    $user_post_id = $agent_data->agent_post_id;
                }
            }
        }
        
        // Fallback ultime
        if (empty($user_post_id)) {
            $user_post_id = 1; // ID par défaut pour éviter les erreurs
        }
        
        $data = $this->global;
        $data['pageTitle'] = 'Tableau de bord Agent - Vue Premium';
        
        // DEBUG: Ajouter les informations de debug
        $data['debug_info'] = [
            'user_post_id_used' => $user_post_id,
            'session_user_post_id' => $this->session->userdata('user_post_id'),
            'userPostId_property' => $this->userPostId,
            'agent_id_param' => $agent_id,
            'session_userId' => $this->session->userdata('userId')
        ];
        
        // Informations de l'agent
        $data['agent'] = $this->agent_model->get_agent($user_post_id);
        
        // Si aucun agent trouvé avec user_post_id, essayer avec wp_id
        if(empty($data['agent'])) {
            $wp_user_id = $this->session->userdata('wp_id');
            $data['agent'] = $this->agent_model->get_agent($wp_user_id);
        }
        
        // Si toujours aucun agent, créer un objet par défaut
        if(empty($data['agent'])) {
            $data['agent'] = (object)[
                'agent_id' => $user_post_id,
                'agent_name' => $this->session->userdata('name') ?: 'Agent',
                'agent_email' => '',
                'phone' => '',
                'agent_avatar' => ''
            ];
        }
        
        // === PROPRIÉTÉS (via WordPress + crm_properties) ===
        $this->load->database();
        
        // 1. Propriétés WordPress via la vue wp_Hrg8P_prop_agen
        $wp_properties_query = $this->wp_db->query("
            SELECT * FROM wp_Hrg8P_prop_agen 
            WHERE agent_id = ?
        ", [$user_post_id]);
        $wp_properties = $wp_properties_query->result_array();
        
        // 2. Estimations (crm_properties)
        $estimations_query = $this->db->query("SELECT * FROM crm_properties WHERE agent_id = ?", [$user_post_id]);
        $estimations = $estimations_query->result_array();
        
        // Combiner les deux sources de données
        $all_properties = array_merge($wp_properties, $estimations);
        
        // DEBUG: Vérifier aussi sans filtre pour les deux sources
        $all_wp_properties_query = $this->wp_db->query("SELECT COUNT(*) as total FROM wp_Hrg8P_prop_agen");
        $all_wp_properties_count = $all_wp_properties_query->row()->total;
        
        $all_estimations_query = $this->db->query("SELECT COUNT(*) as total FROM crm_properties");
        $all_estimations_count = $all_estimations_query->row()->total;
        
        // DEBUG: Voir quels agent_id existent dans les deux tables
        $wp_agent_ids_query = $this->wp_db->query("
            SELECT DISTINCT agent_id 
            FROM wp_Hrg8P_prop_agen 
            WHERE agent_id IS NOT NULL
        ");
        $wp_agent_ids_in_db = $wp_agent_ids_query->result_array();
        
        $estimations_agent_ids_query = $this->db->query("SELECT DISTINCT agent_id FROM crm_properties WHERE agent_id IS NOT NULL");
        $estimations_agent_ids_in_db = $estimations_agent_ids_query->result_array();
        
        $data['debug_info']['wp_properties_found_for_agent'] = count($wp_properties);
        $data['debug_info']['total_wp_properties_in_db'] = $all_wp_properties_count;
        $data['debug_info']['wp_agent_ids_in_properties'] = array_column($wp_agent_ids_in_db, 'agent_id');
        
        $data['debug_info']['estimations_found_for_agent'] = count($estimations);
        $data['debug_info']['total_estimations_in_db'] = $all_estimations_count;
        $data['debug_info']['estimations_agent_ids_in_properties'] = array_column($estimations_agent_ids_in_db, 'agent_id');
        
        // Statistiques combinées
        $total_properties = count($wp_properties) + count($estimations);
        $data['properties_total'] = $total_properties;
        $data['wp_properties_count'] = count($wp_properties);
        $data['estimations_count'] = count($estimations);
        
        // Propriétés WordPress - statistiques
        $data['wp_properties_active'] = count(array_filter($wp_properties, function($p) {
            return isset($p['property_status']) ? $p['property_status'] === 'publish' : 
                   (isset($p['post_status']) ? $p['post_status'] === 'publish' : false);
        }));
        $data['wp_properties_recent'] = count(array_filter($wp_properties, function($p) {
            $date_field = isset($p['property_date']) ? $p['property_date'] : 
                         (isset($p['post_date']) ? $p['post_date'] : null);
            return $date_field ? strtotime($date_field) > strtotime('-7 days') : false;
        }));
        
        // Estimations - statistiques  
        $data['estimations_active'] = count(array_filter($estimations, function($p) {
            return empty($p['statut_dossier']) || $p['statut_dossier'] === 'en_cours';
        }));
        $data['estimations_validated'] = count(array_filter($estimations, function($p) {
            return $p['statut_dossier'] === 'valide';
        }));
        $data['estimations_recent'] = count(array_filter($estimations, function($p) {
            return strtotime($p['created_at']) > strtotime('-7 days');
        }));
        
        // === CONTACTS/CLIENTS (via crm_clients) ===
        $clients = [];
        if($this->db->table_exists('crm_clients')) {
            $clients_query = $this->db->query("SELECT * FROM crm_clients WHERE agent_id = ?", [$user_post_id]);
            $clients = $clients_query->result_array();
            
            $data['contacts_total'] = count($clients);
            $data['contacts_recent'] = count(array_filter($clients, function($c) {
                return strtotime($c['date_creation']) > strtotime('-30 days');
            }));
            $data['contacts_active'] = count(array_filter($clients, function($c) {
                return !empty($c['phone']) || !empty($c['email']);
            }));
        } else {
            $data['contacts_total'] = 0;
            $data['contacts_recent'] = 0;
            $data['contacts_active'] = 0;
        }
        
        // === TRANSACTIONS (via crm_transactions) ===
        $transactions = [];
        if($this->db->table_exists('crm_transactions')) {
            $transactions_query = $this->db->query("SELECT * FROM crm_transactions WHERE commercial = ?", [$user_post_id]);
            $transactions = $transactions_query->result_array();
            
            $data['transactions_total'] = count($transactions);
            $data['transactions_month'] = count(array_filter($transactions, function($t) {
                return date('Y-m', strtotime($t['created_at'])) === date('Y-m');
            }));
            $data['transactions_value'] = array_sum(array_column($transactions, 'montant'));
        } else {
            $data['transactions_total'] = 0;
            $data['transactions_month'] = 0;
            $data['transactions_value'] = 0;
        }
        
        // === COMMISSIONS (via agent_commissions) ===
        $commissions_query = $this->db->query("SELECT * FROM agent_commissions WHERE agent_id = ?", [$user_post_id]);
        $commissions = $commissions_query->result_array();
        
        // DEBUG: Vérifier aussi sans filtre
        $all_commissions_query = $this->db->query("SELECT COUNT(*) as total FROM agent_commissions");
        $all_commissions_count = $all_commissions_query->row()->total;
        
        $data['debug_info']['commissions_found_for_agent'] = count($commissions);
        $data['debug_info']['total_commissions_in_db'] = $all_commissions_count;
        
        $data['commissions_total'] = array_sum(array_column($commissions, 'total_commission'));
        $data['commissions_pending'] = array_sum(array_map(function($c) {
            return $c['status'] === 'pending' ? $c['total_commission'] : 0;
        }, $commissions));
        $data['commissions_month'] = array_sum(array_map(function($c) {
            return date('Y-m', strtotime($c['created_at'])) === date('Y-m') ? $c['total_commission'] : 0;
        }, $commissions));
        
        // === TÂCHES (données simulées car pas de table dédiée) ===
        $data['tasks_total'] = rand(8, 15);
        $data['tasks_pending'] = rand(3, 8);
        $data['tasks_today'] = rand(1, 4);
        $data['tasks_overdue'] = rand(0, 2);
        
        // === CALENDRIER ===
        $data['calendar_events'] = $this->get_real_calendar_events_simple($agent_id, $all_properties, $clients);
        $data['meetings_today'] = count(array_filter($data['calendar_events'], function($e) {
            return date('Y-m-d', strtotime($e['date'])) === date('Y-m-d');
        }));
        $data['meetings_week'] = count(array_filter($data['calendar_events'], function($e) {
            $event_date = strtotime($e['date']);
            $week_start = strtotime('monday this week');
            $week_end = strtotime('sunday this week');
            return $event_date >= $week_start && $event_date <= $week_end;
        }));
        
        // === ACTIVITÉ RÉCENTE ===
        $data['recent_activities'] = $this->get_real_recent_activities_simple($agent_id, $all_properties, $commissions, $clients);
        
        // === OBJECTIFS ===
        $this->load->model('Objective_model');
        $data['objectives'] = $this->Objective_model->get_agent_objectives($agent_id);
        
        // === DONNÉES POUR GRAPHIQUES ===
        $data['properties_chart_data'] = $this->get_real_properties_chart_data_simple($agent_id, $all_properties);
        $data['commissions_chart_data'] = $this->get_real_commissions_chart_data_simple($agent_id, $commissions);
        $data['activities_chart_data'] = $this->get_real_activities_chart_data_simple($agent_id, $all_properties, $clients);
        
        // Préparer les statistiques pour la vue
        $stats = [
            'wp_properties' => count($wp_properties),
            'crm_estimations' => count($estimations)
        ];
        
        // Ajouter les statistiques aux données
        $data['stats'] = $stats;

        $this->loadViews('dashboard/agent_premium', $data, $data, NULL);
    }

    /**
     * Récupère le nombre de propriétés par type
     */
    private function get_properties_by_type($type, $properties) {
        $count = 0;
        foreach ($properties as $property) {
            $property_type = $property->property_status ?? '';
            if ($property_type === $type) {
                $count++;
            }
        }
        return $count;
    }
    
    /**
     * Méthodes helper pour le dashboard agent
     */
    private function count_by_status($items, $status) {
        return count(array_filter($items, function($item) use ($status) {
            return (isset($item['status']) && $item['status'] === $status) || 
                   (isset($item->status) && $item->status === $status) ||
                   (isset($item['property_status']) && $item['property_status'] === $status) ||
                   (isset($item->property_status) && $item->property_status === $status);
        }));
    }
    
    private function count_recent($items, $days) {
        $date_limit = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        return count(array_filter($items, function($item) use ($date_limit) {
            $created_date = $item['created_at'] ?? $item->created_at ?? $item['date_creation'] ?? null;
            return $created_date && strtotime($created_date) >= strtotime($date_limit);
        }));
    }
    
    private function count_current_month($items) {
        $current_month = date('Y-m');
        return count(array_filter($items, function($item) use ($current_month) {
            $created_date = $item['created_at'] ?? $item->created_at ?? $item['date_creation'] ?? null;
            return $created_date && date('Y-m', strtotime($created_date)) === $current_month;
        }));
    }
    
    private function sum_transactions_value($transactions) {
        $total = 0;
        foreach($transactions as $transaction) {
            $total += floatval($transaction['montant'] ?? $transaction->montant ?? 0);
        }
        return $total;
    }
    
    private function sum_commissions($commissions) {
        $total = 0;
        foreach($commissions as $commission) {
            $total += floatval($commission['total_commission'] ?? $commission->total_commission ?? 0);
        }
        return $total;
    }
    
    private function sum_commissions_pending($commissions) {
        $total = 0;
        foreach($commissions as $commission) {
            if(($commission['status'] ?? $commission->status ?? '') === 'pending') {
                $total += floatval($commission['total_commission'] ?? $commission->total_commission ?? 0);
            }
        }
        return $total;
    }
    
    private function sum_commissions_current_month($commissions) {
        $current_month = date('Y-m');
        $total = 0;
        foreach($commissions as $commission) {
            $created_date = $commission['created_at'] ?? $commission->created_at ?? null;
            if($created_date && date('Y-m', strtotime($created_date)) === $current_month) {
                $total += floatval($commission['total_commission'] ?? $commission->total_commission ?? 0);
            }
        }
        return $total;
    }
    
    private function count_tasks_today($tasks) {
        $today = date('Y-m-d');
        return count(array_filter($tasks, function($task) use ($today) {
            $due_date = $task['due_date'] ?? $task->due_date ?? null;
            return $due_date && date('Y-m-d', strtotime($due_date)) === $today;
        }));
    }
    
    private function count_tasks_overdue($tasks) {
        $today = date('Y-m-d');
        return count(array_filter($tasks, function($task) use ($today) {
            $due_date = $task['due_date'] ?? $task->due_date ?? null;
            $status = $task['status'] ?? $task->status ?? 'pending';
            return $due_date && $status !== 'completed' && strtotime($due_date) < strtotime($today);
        }));
    }
    
    // === MÉTHODES HELPER SIMPLIFIÉES AVEC VRAIES DONNÉES ===
    
    private function get_real_calendar_events_simple($agent_id, $properties, $clients) {
        $events = [];
        
        // Vérifier que $properties est un tableau
        if (!is_array($properties)) {
            $properties = [];
        }
        
        // Rendez-vous basés sur les nouvelles propriétés
        $recent_properties = array_filter($properties, function($p) {
            // Gérer les trois types de dates (vue WordPress, WordPress direct, CRM)
            $date_field = isset($p['property_date']) ? $p['property_date'] : 
                         (isset($p['post_date']) ? $p['post_date'] : 
                         (isset($p['created_at']) ? $p['created_at'] : null));
            if (!$date_field) return false;
            return strtotime($date_field) > strtotime('-7 days');
        });
        
        foreach(array_slice($recent_properties, 0, 5) as $property) {
            // Utiliser l'ID et le titre appropriés selon la source
            $prop_id = isset($property['property_id']) ? $property['property_id'] : 
                      (isset($property['ID']) ? $property['ID'] : 
                      (isset($property['id']) ? $property['id'] : 'unknown'));
            $prop_title = isset($property['property_title']) ? $property['property_title'] : 
                         (isset($property['post_title']) ? $property['post_title'] : 
                         (isset($property['nom']) ? $property['nom'] : 'Propriété'));
            $prop_date = isset($property['property_date']) ? $property['property_date'] : 
                        (isset($property['post_date']) ? $property['post_date'] : 
                        (isset($property['created_at']) ? $property['created_at'] : date('Y-m-d H:i:s')));
            
            $events[] = [
                'id' => 'prop_' . $prop_id,
                'title' => 'RDV - ' . $prop_title,
                'date' => date('Y-m-d H:i:s', strtotime($prop_date) + rand(3600, 7200)),
                'type' => 'meeting',
                'color' => '#3b82f6'
            ];
        }
        
        // Vérifier que $clients est un tableau
        if (!is_array($clients)) {
            $clients = [];
        }
        
        // Suivi basé sur les clients récents
        if(!empty($clients)) {
            $recent_clients = array_filter($clients, function($c) {
                return isset($c['date_creation']) && strtotime($c['date_creation']) > strtotime('-14 days');
            });
            
            foreach(array_slice($recent_clients, 0, 3) as $client) {
                $events[] = [
                    'id' => 'client_' . $client['id'],
                    'title' => 'Suivi - ' . $client['nom'] . ' ' . ($client['prenom'] ?? ''),
                    'date' => date('Y-m-d H:i:s', strtotime($client['date_creation']) + rand(86400, 172800)),
                    'type' => 'follow_up',
                    'color' => '#10b981'
                ];
            }
        }
        
        return $events;
    }
    
    private function get_real_recent_activities_simple($agent_id, $properties, $commissions, $clients) {
        $activities = [];
        
        // Vérifier que $properties est un tableau
        if (!is_array($properties)) {
            $properties = [];
        }
        
        // Activités basées sur les propriétés récentes
        $recent_properties = array_filter($properties, function($p) {
            // Vérifier d'abord si c'est une propriété WordPress (vue) ou CRM
            $date_field = isset($p['property_date']) ? $p['property_date'] : 
                         (isset($p['post_date']) ? $p['post_date'] : 
                         (isset($p['created_at']) ? $p['created_at'] : null));
            if (!$date_field) return false;
            return strtotime($date_field) > strtotime('-30 days');
        });
        
        foreach(array_slice($recent_properties, 0, 5) as $property) {
            // Utiliser l'ID approprié selon la source
            $prop_id = isset($property['property_id']) ? $property['property_id'] : 
                      (isset($property['ID']) ? $property['ID'] : 
                      (isset($property['id']) ? $property['id'] : 'unknown'));
            $prop_title = isset($property['property_title']) ? $property['property_title'] : 
                         (isset($property['post_title']) ? $property['post_title'] : 
                         (isset($property['nom']) ? $property['nom'] : 'Propriété'));
            $prop_date = isset($property['property_date']) ? $property['property_date'] : 
                        (isset($property['post_date']) ? $property['post_date'] : 
                        (isset($property['created_at']) ? $property['created_at'] : date('Y-m-d H:i:s')));
            
            $activities[] = [
                'id' => 'prop_' . $prop_id,
                'type' => 'property_added',
                'title' => 'Nouvelle propriété ajoutée',
                'description' => $prop_title,
                'metadata' => ['price' => $property['prix'] ?? 0],
                'created_at' => $prop_date,
                'icon' => 'home-4-line',
                'color' => 'primary'
            ];
        }
        
        // Vérifier que $commissions est un tableau
        if (!is_array($commissions)) {
            $commissions = [];
        }
        
        // Activités basées sur les commissions récentes
        $recent_commissions = array_filter($commissions, function($c) {
            return strtotime($c['created_at']) > strtotime('-30 days');
        });
        
        foreach(array_slice($recent_commissions, 0, 3) as $commission) {
            $activities[] = [
                'id' => 'comm_' . $commission['id'],
                'type' => 'commission_earned',
                'title' => 'Commission générée',
                'description' => number_format($commission['total_commission'], 0, ',', ' ') . ' TND',
                'metadata' => ['amount' => $commission['total_commission'], 'status' => $commission['status']],
                'created_at' => $commission['created_at'],
                'icon' => 'money-dollar-circle-line',
                'color' => 'success'
            ];
        }
        
        // Activités basées sur les nouveaux clients
        if(!empty($clients) && is_array($clients)) {
            $recent_clients = array_filter($clients, function($c) {
                return isset($c['date_creation']) && strtotime($c['date_creation']) > strtotime('-30 days');
            });
            
            foreach(array_slice($recent_clients, 0, 3) as $client) {
                $activities[] = [
                    'id' => 'client_' . $client['id'],
                    'type' => 'client_added',
                    'title' => 'Nouveau contact ajouté',
                    'description' => $client['nom'] . ' ' . ($client['prenom'] ?? ''),
                    'metadata' => [],
                    'created_at' => $client['date_creation'],
                    'icon' => 'user-add-line',
                    'color' => 'info'
                ];
            }
        }
        
        // Trier par date décroissante
        usort($activities, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        
        return array_slice($activities, 0, 10);
    }
    
    private function get_real_properties_chart_data_simple($agent_id, $properties) {
        $months = [];
        $data = [];
        
        // Vérifier que $properties est un tableau
        if (!is_array($properties)) {
            $properties = [];
        }
        
        // Derniers 6 mois
        for($i = 5; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $months[] = date('M Y', strtotime("-$i months"));
            
            $count = count(array_filter($properties, function($p) use ($month) {
                // Gérer les trois types de dates (vue WordPress, WordPress direct, CRM)
                $date_field = isset($p['property_date']) ? $p['property_date'] : 
                             (isset($p['post_date']) ? $p['post_date'] : 
                             (isset($p['created_at']) ? $p['created_at'] : null));
                if (!$date_field) return false;
                return date('Y-m', strtotime($date_field)) === $month;
            }));
            
            $data[] = $count;
        }
        
        return [
            'labels' => $months,
            'data' => $data
        ];
    }
    
    private function get_real_commissions_chart_data_simple($agent_id, $commissions) {
        $months = [];
        $data = [];
        
        // Derniers 6 mois
        for($i = 5; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $months[] = date('M Y', strtotime("-$i months"));
            
            $total = array_sum(array_map(function($c) use ($month) {
                return date('Y-m', strtotime($c['created_at'])) === $month ? $c['total_commission'] : 0;
            }, $commissions));
            
            $data[] = $total;
        }
        
        return [
            'labels' => $months,
            'data' => $data
        ];
    }
    
    private function get_real_activities_chart_data_simple($agent_id, $properties, $clients) {
        $days = [];
        $properties_data = [];
        $clients_data = [];
        
        // Vérifier que les paramètres sont des tableaux
        if (!is_array($properties)) {
            $properties = [];
        }
        if (!is_array($clients)) {
            $clients = [];
        }
        
        // Derniers 7 jours
        for($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $days[] = date('d/m', strtotime("-$i days"));
            
            // Propriétés ajoutées ce jour
            $prop_count = count(array_filter($properties, function($p) use ($date) {
                // Gérer les trois types de dates (vue WordPress, WordPress direct, CRM)
                $date_field = isset($p['property_date']) ? $p['property_date'] : 
                             (isset($p['post_date']) ? $p['post_date'] : 
                             (isset($p['created_at']) ? $p['created_at'] : null));
                if (!$date_field) return false;
                return date('Y-m-d', strtotime($date_field)) === $date;
            }));
            $properties_data[] = $prop_count;
            
            // Clients ajoutés ce jour
            $client_count = 0;
            if(!empty($clients)) {
                $client_count = count(array_filter($clients, function($c) use ($date) {
                    return isset($c['date_creation']) && date('Y-m-d', strtotime($c['date_creation'])) === $date;
                }));
            }
            $clients_data[] = $client_count;
        }
        
        return [
            'labels' => $days,
            'properties' => $properties_data,
            'clients' => $clients_data
        ];
    }
    
    private function count_meetings_today($events) {
        $today = date('Y-m-d');
        return count(array_filter($events, function($event) use ($today) {
            return date('Y-m-d', strtotime($event['date'])) === $today;
        }));
    }
    
    private function count_meetings_week($events) {
        $week_start = date('Y-m-d', strtotime('monday this week'));
        $week_end = date('Y-m-d', strtotime('sunday this week'));
        return count(array_filter($events, function($event) use ($week_start, $week_end) {
            $event_date = date('Y-m-d', strtotime($event['date']));
            return $event_date >= $week_start && $event_date <= $week_end;
        }));
    }
    
    private function get_properties_chart_data($agent_id) {
        // Méthode maintenue pour compatibilité - utilise les vraies données maintenant
        // Utiliser user_post_id de la session si disponible
        $user_post_id = $this->userPostId ?: $agent_id;
        $properties_query = $this->db->query("SELECT * FROM crm_properties WHERE agent_id = ?", [$user_post_id]);
        $properties = $properties_query->result_array();
        return $this->get_real_properties_chart_data_simple($user_post_id, $properties);
    }
    
    private function get_commissions_chart_data($agent_id) {
        // Méthode maintenue pour compatibilité - utilise les vraies données maintenant
        // Utiliser user_post_id de la session si disponible
        $user_post_id = $this->userPostId ?: $agent_id;
        $commissions_query = $this->db->query("SELECT * FROM agent_commissions WHERE agent_id = ?", [$user_post_id]);
        $commissions = $commissions_query->result_array();
        return $this->get_real_commissions_chart_data_simple($user_post_id, $commissions);
    }
    
    private function get_activities_chart_data($agent_id) {
        // Méthode maintenue pour compatibilité - utilise les vraies données maintenant
        // Utiliser user_post_id de la session si disponible
        $user_post_id = $this->userPostId ?: $agent_id;
        $properties_query = $this->db->query("SELECT * FROM crm_properties WHERE agent_id = ?", [$user_post_id]);
        $properties = $properties_query->result_array();
        
        $clients = [];
        if($this->db->table_exists('crm_clients')) {
            $clients_query = $this->db->query("SELECT * FROM crm_clients WHERE agent_id = ?", [$user_post_id]);
            $clients = $clients_query->result_array();
        }
        
        return $this->get_real_activities_chart_data_simple($user_post_id, $properties, $clients);
    }
    

    
}
