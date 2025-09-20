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
        } elseif ($role === 'manager') {
            redirect('dashboard/manager');
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
            $data['stats']['properties_for_sale'] = $this->get_properties_by_status('for-sale', $properties);
            $data['stats']['properties_for_rent'] = $this->get_properties_by_status('for-rent', $properties);
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

    // Vue Manager : tableau de bord manager avec gestion d'agence - VERSION RÉORGANISÉE
    public function manager() {
        $this->isLoggedIn();
        
        // Configuration de base
        $data = $this->global;
        $data['pageTitle'] = 'Tableau de bord Manager - Vue d\'Ensemble';
        
        // Récupération agency_id avec validation
        $agency_id = $this->get_validated_agency_id();
        
        // 1. DONNÉES FONDAMENTALES
        $data['agency'] = $this->get_agency_data($agency_id);
        $data['agents'] = $this->get_agents_with_performance($agency_id);
        
        // 2. KPI PRINCIPAUX (Section 1)
        $data['kpi'] = $this->get_manager_kpis($agency_id);
        
        // 3. DONNÉES DE SUPERVISION (Section 2)
        $data['supervision'] = $this->get_supervision_data($agency_id);
        
        // 4. DONNÉES D'ÉQUIPE (Section 3)
        $data['team'] = $this->get_team_performance_data($agency_id);
        
        // 5. ACTIVITÉS & ALERTES (Section 4)
        $data['activities'] = $this->get_activities_and_alerts($agency_id);
        
        // 6. DONNÉES POUR GRAPHIQUES
        $data['charts'] = $this->get_charts_data($agency_id);
        
        // Charger la vue manager réorganisée
        $this->loadViews('dashboard/manager_new', $this->global, $data, NULL);
    }
    
    /**
     * Ancienne version du dashboard manager (pour comparaison)
     */
    public function manager_old() {
        $this->isLoggedIn();
        
        $data = $this->global;
        $data['pageTitle'] = 'Tableau de bord Manager - Version Classique';
        
        // Récupérer l'agency_id depuis BaseController (session)
        $agency_id = $this->agencyId ?: $this->session->userdata('agency_id');
        
        // Fallback agency_id par défaut si toujours pas trouvé
        if (!$agency_id) {
            $agency_id = 1; // ID par défaut
        }
        
        // Récupérer les données de l'agence et ses agents avec avatars
        $data['agency'] = $this->agency_model->get_agency($agency_id);
        $data['agents'] = $this->get_filtered_agents_from_view($agency_id);
        
        // Statistiques détaillées pour le tableau de bord
        $data['stats'] = [
            'total_agents' => count($data['agents']),
            'total_properties' => $this->get_agency_properties_count($agency_id),
            'active_listings' => $this->get_agency_active_listings($agency_id),
            'monthly_revenue' => $this->get_agency_monthly_revenue($agency_id),
            'pending_tasks' => $this->get_agency_pending_tasks($agency_id),
            'recent_activities' => $this->get_agency_recent_activities($agency_id),
            'top_performers' => $this->get_top_performing_agents($agency_id),
            'properties_by_type' => $this->get_properties_by_type($agency_id),
            'monthly_stats' => $this->get_monthly_statistics($agency_id)
        ];
        
        // Données pour les graphiques
        $data['chart_data'] = [
            'monthly_sales' => $this->get_monthly_sales_data($agency_id),
            'agents_performance' => $this->get_agents_performance_data($agency_id),
            'properties_status' => $this->get_properties_status_data($agency_id)
        ];
        
        // Nouvelles données pour la section transactions
        $data['transactions_data'] = [
            'sales_evolution' => $this->get_sales_evolution($agency_id),
            'rentals_evolution' => $this->get_rentals_evolution($agency_id),
            'objectives_progress' => $this->get_objectives_with_progress($agency_id)
        ];
        
        // Charger l'ancienne vue manager
        $this->loadViews('dashboard/manager', $this->global, $data, NULL);
    }
    
    /**
     * Récupération et validation de l'agency_id
     */
    private function get_validated_agency_id() {
        $agency_id = $this->agencyId ?: $this->session->userdata('agency_id');
        
        if (!$agency_id) {
            $agency_id = 1; // ID par défaut
        }
        
        return $agency_id;
    }
    
    /**
     * Récupération des données d'agence
     */
    private function get_agency_data($agency_id) {
        try {
            return $this->agency_model->get_agency($agency_id);
        } catch (Exception $e) {
            log_message('error', 'Erreur récupération agence: ' . $e->getMessage());
            return (object) ['agency_name' => 'Agence par défaut', 'id' => $agency_id];
        }
    }
    
    /**
     * Récupération des agents avec données de performance
     */
    private function get_agents_with_performance($agency_id) {
        $agents = $this->get_filtered_agents_from_view($agency_id);
        
        // Enrichir chaque agent avec des données de performance
        foreach ($agents as &$agent) {
            $agent->properties_count = $this->get_agent_properties_count($agent->ID ?? 0);
            $agent->performance_score = $this->calculate_agent_performance_score($agent->ID ?? 0);
            $agent->sales_count = $this->get_agent_sales_count($agent->ID ?? 0);
            $agent->revenue = $this->get_agent_revenue($agent->ID ?? 0);
        }
        
        return $agents;
    }
    
    /**
     * KPI principaux pour la section 1
     */
    private function get_manager_kpis($agency_id) {
        return [
            'total_agents' => $this->get_agency_active_agents_count($agency_id),
            'monthly_revenue' => $this->get_agency_monthly_revenue($agency_id),
            'total_properties' => $this->get_agency_properties_count($agency_id),
            'active_listings' => $this->get_agency_active_listings($agency_id),
            'objectives_completion' => $this->get_objectives_completion_rate($agency_id),
            'growth_rate' => $this->get_monthly_growth_rate($agency_id)
        ];
    }
    
    /**
     * Données de supervision pour la section 2
     */
    private function get_supervision_data($agency_id) {
        return [
            'transactions' => [
                'sales_evolution' => $this->get_sales_evolution($agency_id),
                'rentals_evolution' => $this->get_rentals_evolution($agency_id),
                'monthly_trends' => $this->get_monthly_trends($agency_id)
            ],
            'pipeline' => [
                'prospects' => $this->get_prospects_count($agency_id),
                'appointments' => $this->get_scheduled_appointments($agency_id),
                'negotiations' => $this->get_active_negotiations($agency_id)
            ]
        ];
    }
    
    /**
     * Données de performance d'équipe pour la section 3
     */
    private function get_team_performance_data($agency_id) {
        $agents = $this->get_agents_with_performance($agency_id);
        
        return [
            'top_performers' => $this->get_top_performing_agents_enhanced($agency_id),
            'team_overview' => $agents,
            'performance_metrics' => [
                'average_score' => $this->calculate_team_average_performance($agents),
                'best_performer_id' => $this->get_best_performer_id($agents),
                'improvement_needed' => $this->get_agents_needing_improvement($agents)
            ]
        ];
    }
    
    /**
     * Activités et alertes pour la section 4
     */
    private function get_activities_and_alerts($agency_id) {
        return [
            'recent_activities' => $this->get_agency_recent_activities($agency_id),
            'alerts' => [
                'pending_tasks' => $this->get_agency_pending_tasks($agency_id),
                'overdue_follow_ups' => $this->get_overdue_follow_ups($agency_id),
                'low_performers' => $this->get_underperforming_agents($agency_id),
                'objectives_at_risk' => $this->get_at_risk_objectives($agency_id)
            ],
            'recommendations' => $this->get_manager_recommendations($agency_id)
        ];
    }
    
    /**
     * Données pour les graphiques
     */
    private function get_charts_data($agency_id) {
        return [
            'transactions_chart' => [
                'labels' => $this->get_last_6_months_labels(),
                'sales_data' => $this->get_monthly_sales_data($agency_id),
                'rentals_data' => $this->get_monthly_rentals_data($agency_id)
            ],
            'performance_chart' => $this->get_agents_performance_chart_data($agency_id),
            'revenue_chart' => $this->get_revenue_evolution_data($agency_id)
        ];
    }
    
    /**
     * Méthode de débogage pour voir la structure des données d'agents
     */
    public function debug_agents() {
        $this->isLoggedIn();
        
        $agency_id = $this->agencyId ?: $this->session->userdata('agency_id') ?: 1;
        
        echo "<h1>Debug - Structure des agents</h1>";
        echo "<p><strong>Agency ID:</strong> $agency_id</p>";
        
        // Test de la vue directement
        try {
            $this->load->database('wordpress');
            $wp_db = $this->load->database('wordpress', TRUE);
            
            // Vérifier l'existence de la vue
            $check = $wp_db->query("SHOW TABLES LIKE 'wp_Hrg8P_crm_agents'");
            echo "<p><strong>Vue wp_Hrg8P_crm_agents existe:</strong> " . ($check->num_rows() > 0 ? 'OUI' : 'NON') . "</p>";
            
            if ($check->num_rows() > 0) {
                // Afficher la structure
                $columns = $wp_db->query("SHOW COLUMNS FROM wp_Hrg8P_crm_agents");
                echo "<h2>Colonnes disponibles:</h2><ul>";
                foreach ($columns->result() as $col) {
                    echo "<li><strong>" . $col->Field . "</strong> (" . $col->Type . ")</li>";
                }
                echo "</ul>";
                
                // Afficher un échantillon de données
                $sample = $wp_db->query("SELECT * FROM wp_Hrg8P_crm_agents WHERE agency_id = $agency_id LIMIT 3");
                echo "<h2>Données d'exemple (agency_id = $agency_id):</h2>";
                if ($sample->num_rows() > 0) {
                    echo "<pre>";
                    foreach ($sample->result() as $row) {
                        print_r($row);
                        echo "\n---\n";
                    }
                    echo "</pre>";
                } else {
                    echo "<p>Aucune donnée trouvée pour agency_id = $agency_id</p>";
                    
                    // Essayer avec toutes les agences
                    $all_sample = $wp_db->query("SELECT * FROM wp_Hrg8P_crm_agents LIMIT 3");
                    echo "<h3>Données d'exemple (toutes agences):</h3>";
                    if ($all_sample->num_rows() > 0) {
                        echo "<pre>";
                        foreach ($all_sample->result() as $row) {
                            print_r($row);
                            echo "\n---\n";
                        }
                        echo "</pre>";
                    }
                }
            }
            
        } catch (Exception $e) {
            echo "<p style='color: red;'>Erreur: " . $e->getMessage() . "</p>";
        }
        
        // Test de la méthode filtrée
        echo "<h2>Test de get_filtered_agents_from_view():</h2>";
        $agents = $this->get_filtered_agents_from_view($agency_id);
        echo "<p><strong>Nombre d'agents retournés:</strong> " . count($agents) . "</p>";
        if (!empty($agents)) {
            echo "<h3>Premier agent:</h3><pre>";
            print_r($agents[0]);
            echo "</pre>";
        }
    }
    
    /**
     * Vérifie les propriétés des agents pour éviter les erreurs PHP Notice
     */
    public function debug_agent_properties() {
        $this->isLoggedIn();
        
        echo "<!DOCTYPE html>";
        echo "<html><head>";
        echo "<title>Debug Agent Properties - CRM Rebencia</title>";
        echo "<style>";
        echo "body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }";
        echo ".container { max-width: 1000px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }";
        echo "h1, h2 { color: #2c3e50; }";
        echo ".agent-card { background: #f8f9fa; border: 1px solid #dee2e6; padding: 15px; margin: 10px 0; border-radius: 5px; }";
        echo ".property-check { margin: 5px 0; padding: 5px; }";
        echo ".missing { background: #f8d7da; color: #721c24; }";
        echo ".present { background: #d4edda; color: #155724; }";
        echo ".empty { background: #fff3cd; color: #856404; }";
        echo "table { width: 100%; border-collapse: collapse; margin: 15px 0; }";
        echo "th, td { border: 1px solid #dee2e6; padding: 8px 12px; text-align: left; }";
        echo "th { background: #f8f9fa; font-weight: bold; }";
        echo "</style>";
        echo "</head><body>";
        
        echo "<div class='container'>";
        echo "<h1>🔍 Debug - Propriétés des Agents</h1>";
        
        $agency_id = $this->agencyId ?: $this->session->userdata('agency_id') ?: 1;
        echo "<p><strong>Agency ID utilisé:</strong> $agency_id</p>";
        
        // Propriétés requises pour l'affichage
        $required_properties = [
            'display_name' => 'Nom d\'affichage',
            'user_nicename' => 'Nom d\'utilisateur',
            'user_email' => 'Email',
            'user_role' => 'Rôle',
            'avatar_url' => 'URL Avatar',
            'property_count' => 'Nombre de propriétés',
            'user_id' => 'ID utilisateur',
            'agent_post_id' => 'ID post agent'
        ];
        
        try {
            // Test avec get_agents_by_agency_with_avatars
            echo "<h2>📋 Test avec get_agents_by_agency_with_avatars()</h2>";
            $agents_method1 = $this->agent_model->get_agents_by_agency_with_avatars($agency_id);
            echo "<p><strong>Nombre d'agents:</strong> " . count($agents_method1) . "</p>";
            
            echo "<table>";
            echo "<tr><th>Agent</th>";
            foreach ($required_properties as $prop => $label) {
                echo "<th>$label</th>";
            }
            echo "</tr>";
            
            foreach ($agents_method1 as $agent) {
                echo "<tr>";
                echo "<td><strong>" . htmlspecialchars($agent->display_name ?? 'N/A') . "</strong></td>";
                
                foreach ($required_properties as $prop => $label) {
                    $status = 'missing';
                    $value = 'MANQUANT';
                    
                    if (isset($agent->$prop)) {
                        if (!empty($agent->$prop)) {
                            $status = 'present';
                            $value = htmlspecialchars($agent->$prop);
                        } else {
                            $status = 'empty';
                            $value = 'VIDE';
                        }
                    }
                    
                    echo "<td class='$status'>$value</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
            
            // Test avec get_filtered_agents_from_view
            echo "<h2>🔍 Test avec get_filtered_agents_from_view()</h2>";
            $agents_method2 = $this->get_filtered_agents_from_view($agency_id);
            echo "<p><strong>Nombre d'agents:</strong> " . count($agents_method2) . "</p>";
            
            echo "<table>";
            echo "<tr><th>Agent</th>";
            foreach ($required_properties as $prop => $label) {
                echo "<th>$label</th>";
            }
            echo "</tr>";
            
            foreach ($agents_method2 as $agent) {
                echo "<tr>";
                echo "<td><strong>" . htmlspecialchars($agent->display_name ?? 'N/A') . "</strong></td>";
                
                foreach ($required_properties as $prop => $label) {
                    $status = 'missing';
                    $value = 'MANQUANT';
                    
                    if (isset($agent->$prop)) {
                        if (!empty($agent->$prop)) {
                            $status = 'present';
                            $value = htmlspecialchars($agent->$prop);
                        } else {
                            $status = 'empty';
                            $value = 'VIDE';
                        }
                    }
                    
                    echo "<td class='$status'>$value</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
            
            // Comparaison des méthodes
            echo "<h2>⚖️ Comparaison des Méthodes</h2>";
            echo "<div class='agent-card'>";
            echo "<p><strong>get_agents_by_agency_with_avatars():</strong> " . count($agents_method1) . " agents</p>";
            echo "<p><strong>get_filtered_agents_from_view():</strong> " . count($agents_method2) . " agents</p>";
            
            if (count($agents_method1) !== count($agents_method2)) {
                echo "<p style='color: red;'>⚠️ Les deux méthodes retournent un nombre différent d'agents!</p>";
            } else {
                echo "<p style='color: green;'>✅ Les deux méthodes retournent le même nombre d'agents.</p>";
            }
            echo "</div>";
            
        } catch (Exception $e) {
            echo "<div class='agent-card missing'>";
            echo "<strong>Erreur:</strong> " . $e->getMessage();
            echo "</div>";
        }
        
        echo "<p><a href='" . base_url('dashboard/manager') . "' style='display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Retour au Dashboard Manager</a></p>";
        echo "</div>";
        echo "</body></html>";
    }
    
    /**
     * Débogue les données de transactions pour le dashboard manager
     */
    public function debug_transactions() {
        $this->isLoggedIn();
        
        echo "<!DOCTYPE html>";
        echo "<html><head>";
        echo "<title>Debug Transactions - CRM Rebencia</title>";
        echo "<style>";
        echo "body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }";
        echo ".container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }";
        echo "h1, h2 { color: #2c3e50; }";
        echo ".result { background: #f8f9fa; border: 1px solid #dee2e6; padding: 15px; margin: 10px 0; border-radius: 5px; }";
        echo ".success { background: #d4edda; border-color: #c3e6cb; color: #155724; }";
        echo ".error { background: #f8d7da; border-color: #f5c6cb; color: #721c24; }";
        echo ".info { background: #d1ecf1; border-color: #bee5eb; color: #0c5460; }";
        echo "table { width: 100%; border-collapse: collapse; margin: 15px 0; }";
        echo "th, td { border: 1px solid #dee2e6; padding: 8px 12px; text-align: left; }";
        echo "th { background: #f8f9fa; font-weight: bold; }";
        echo "pre { background: #2c3e50; color: #ecf0f1; padding: 10px; border-radius: 5px; overflow-x: auto; font-size: 12px; }";
        echo "</style>";
        echo "</head><body>";
        
        echo "<div class='container'>";
        echo "<h1>🔍 Debug - Données des Transactions</h1>";
        
        $agency_id = $this->agencyId ?: $this->session->userdata('agency_id') ?: 1;
        echo "<p><strong>Agency ID utilisé:</strong> $agency_id</p>";
        
        // 1. Vérifier les agents de l'agence
        echo "<h2>👥 Agents de l'agence</h2>";
        $agents = $this->get_filtered_agents_from_view($agency_id);
        $agent_ids = array_column($agents, 'ID');
        
        echo "<div class='result info'>";
        echo "<strong>Nombre d'agents trouvés:</strong> " . count($agents) . "<br>";
        echo "<strong>IDs des agents:</strong> " . implode(', ', $agent_ids) . "<br>";
        echo "</div>";
        
        if (!empty($agents)) {
            echo "<table>";
            echo "<tr><th>ID</th><th>Nom</th><th>Email</th></tr>";
            foreach (array_slice($agents, 0, 5) as $agent) {
                echo "<tr>";
                echo "<td>" . ($agent->ID ?? 'N/A') . "</td>";
                echo "<td>" . htmlspecialchars($agent->display_name ?? 'N/A') . "</td>";
                echo "<td>" . htmlspecialchars($agent->user_email ?? 'N/A') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        
        // 2. Vérifier la table crm_transactions
        echo "<h2>💰 Table crm_transactions</h2>";
        
        try {
            // Compter toutes les transactions
            $total_count = $this->db->query("SELECT COUNT(*) as count FROM crm_transactions")->row()->count;
            echo "<div class='result info'>";
            echo "<strong>Total transactions dans crm_transactions:</strong> $total_count<br>";
            echo "</div>";
            
            if ($total_count > 0) {
                // Échantillon de données
                $sample = $this->db->query("SELECT * FROM crm_transactions ORDER BY created_at DESC LIMIT 3")->result();
                echo "<h3>Échantillon de transactions:</h3>";
                echo "<table>";
                echo "<tr><th>ID</th><th>Agent ID</th><th>Type</th><th>Montant</th><th>Statut</th><th>Date</th></tr>";
                foreach ($sample as $transaction) {
                    echo "<tr>";
                    echo "<td>" . ($transaction->id ?? 'N/A') . "</td>";
                    echo "<td>" . ($transaction->agent_id ?? 'N/A') . "</td>";
                    echo "<td>" . ($transaction->type ?? 'N/A') . "</td>";
                    echo "<td>" . ($transaction->montant ?? 'N/A') . "</td>";
                    echo "<td>" . ($transaction->statut ?? 'N/A') . "</td>";
                    echo "<td>" . ($transaction->created_at ?? 'N/A') . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
                
                // Statistiques par type
                $types_count = $this->db->query("SELECT type, COUNT(*) as count FROM crm_transactions GROUP BY type")->result();
                echo "<h3>Répartition par type:</h3>";
                echo "<table>";
                echo "<tr><th>Type</th><th>Nombre</th></tr>";
                foreach ($types_count as $type) {
                    echo "<tr><td>" . ($type->type ?? 'N/A') . "</td><td>" . $type->count . "</td></tr>";
                }
                echo "</table>";
                
                // Transactions pour les agents de l'agence
                if (!empty($agent_ids)) {
                    $agent_ids_str = implode(',', array_map('intval', $agent_ids));
                    $agency_transactions = $this->db->query("SELECT * FROM crm_transactions WHERE agent_id IN ($agent_ids_str) ORDER BY created_at DESC LIMIT 5")->result();
                    
                    echo "<h3>Transactions pour cette agence (5 dernières):</h3>";
                    if (!empty($agency_transactions)) {
                        echo "<table>";
                        echo "<tr><th>ID</th><th>Agent ID</th><th>Type</th><th>Montant</th><th>Statut</th><th>Date</th></tr>";
                        foreach ($agency_transactions as $transaction) {
                            echo "<tr>";
                            echo "<td>" . ($transaction->id ?? 'N/A') . "</td>";
                            echo "<td>" . ($transaction->agent_id ?? 'N/A') . "</td>";
                            echo "<td>" . ($transaction->type ?? 'N/A') . "</td>";
                            echo "<td>" . ($transaction->montant ?? 'N/A') . "</td>";
                            echo "<td>" . ($transaction->statut ?? 'N/A') . "</td>";
                            echo "<td>" . ($transaction->created_at ?? 'N/A') . "</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "<div class='result error'>Aucune transaction trouvée pour les agents de cette agence</div>";
                    }
                }
            }
            
        } catch (Exception $e) {
            echo "<div class='result error'>";
            echo "<strong>Erreur crm_transactions:</strong> " . $e->getMessage();
            echo "</div>";
        }
        
        // 3. Vérifier la table agent_commissions
        echo "<h2>💼 Table agent_commissions</h2>";
        
        try {
            $total_commissions = $this->db->query("SELECT COUNT(*) as count FROM agent_commissions")->row()->count;
            echo "<div class='result info'>";
            echo "<strong>Total commissions dans agent_commissions:</strong> $total_commissions<br>";
            echo "</div>";
            
            if ($total_commissions > 0) {
                // Échantillon de données
                $sample_commissions = $this->db->query("SELECT * FROM agent_commissions ORDER BY created_at DESC LIMIT 3")->result();
                echo "<h3>Échantillon de commissions:</h3>";
                echo "<table>";
                echo "<tr><th>ID</th><th>Agent ID</th><th>Type</th><th>Montant</th><th>Statut</th><th>Date</th></tr>";
                foreach ($sample_commissions as $commission) {
                    echo "<tr>";
                    echo "<td>" . ($commission->id ?? 'N/A') . "</td>";
                    echo "<td>" . ($commission->agent_id ?? 'N/A') . "</td>";
                    echo "<td>" . ($commission->transaction_type ?? 'N/A') . "</td>";
                    echo "<td>" . ($commission->base_amount ?? 'N/A') . "</td>";
                    echo "<td>" . ($commission->status ?? 'N/A') . "</td>";
                    echo "<td>" . ($commission->created_at ?? 'N/A') . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
            
        } catch (Exception $e) {
            echo "<div class='result error'>";
            echo "<strong>Erreur agent_commissions:</strong> " . $e->getMessage();
            echo "</div>";
        }
        
        // 4. Test des méthodes de récupération des données
        echo "<h2>🧪 Test des méthodes de données</h2>";
        
        try {
            echo "<h3>Évolution des ventes:</h3>";
            $sales_data = $this->get_sales_evolution($agency_id);
            echo "<div class='result'>";
            echo "<pre>" . print_r($sales_data, true) . "</pre>";
            echo "</div>";
            
            echo "<h3>Évolution des locations:</h3>";
            $rentals_data = $this->get_rentals_evolution($agency_id);
            echo "<div class='result'>";
            echo "<pre>" . print_r($rentals_data, true) . "</pre>";
            echo "</div>";
            
            echo "<h3>Objectifs et progression:</h3>";
            $objectives_data = $this->get_objectives_with_progress($agency_id);
            echo "<div class='result'>";
            echo "<pre>" . print_r($objectives_data, true) . "</pre>";
            echo "</div>";
            
        } catch (Exception $e) {
            echo "<div class='result error'>";
            echo "<strong>Erreur test méthodes:</strong> " . $e->getMessage();
            echo "</div>";
        }
        
        echo "<p><a href='" . base_url('dashboard/manager') . "' style='display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Retour au Dashboard Manager</a></p>";
        echo "</div>";
        echo "</body></html>";
    }
    
    /**
     * Remplit les tables avec des données de test pour démonstration
     */
    public function populate_test_data() {
        $this->isLoggedIn();
        
        echo "<!DOCTYPE html>";
        echo "<html><head>";
        echo "<title>Générer Données Test - CRM Rebencia</title>";
        echo "<style>";
        echo "body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }";
        echo ".container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }";
        echo "h1, h2 { color: #2c3e50; }";
        echo ".result { background: #f8f9fa; border: 1px solid #dee2e6; padding: 15px; margin: 10px 0; border-radius: 5px; }";
        echo ".success { background: #d4edda; border-color: #c3e6cb; color: #155724; }";
        echo ".error { background: #f8d7da; border-color: #f5c6cb; color: #721c24; }";
        echo ".info { background: #d1ecf1; border-color: #bee5eb; color: #0c5460; }";
        echo "</style>";
        echo "</head><body>";
        
        echo "<div class='container'>";
        echo "<h1>🎲 Génération de Données Test</h1>";
        
        $agency_id = $this->agencyId ?: $this->session->userdata('agency_id') ?: 1;
        echo "<p><strong>Agency ID utilisé:</strong> $agency_id</p>";
        
        // Récupérer les agents de l'agence
        $agents = $this->get_filtered_agents_from_view($agency_id);
        $agent_ids = array_column($agents, 'ID');
        
        echo "<div class='result info'>";
        echo "<strong>Agents trouvés:</strong> " . count($agents) . "<br>";
        echo "<strong>IDs:</strong> " . implode(', ', $agent_ids);
        echo "</div>";
        
        if (empty($agent_ids)) {
            echo "<div class='result error'>Aucun agent trouvé pour cette agence. Impossible de générer des données.</div>";
            echo "</div></body></html>";
            return;
        }
        
        // 1. Générer des transactions de test
        echo "<h2>💰 Génération de transactions</h2>";
        
        try {
            $transactions_created = 0;
            
            // Générer des transactions pour les 6 derniers mois
            for ($i = 5; $i >= 0; $i--) {
                $month = date('Y-m', strtotime("-$i months"));
                $month_start = $month . '-01';
                $month_end = date('Y-m-t', strtotime($month_start));
                
                // Pour chaque agent, créer quelques transactions
                foreach ($agent_ids as $agent_id) {
                    // Générer 1-3 ventes par mois
                    $sales_count = rand(1, 3);
                    for ($j = 0; $j < $sales_count; $j++) {
                        $transaction_data = [
                            'agent_id' => $agent_id,
                            'type' => 'vente',
                            'montant' => rand(50000, 200000), // Entre 50k et 200k TND
                            'statut' => rand(0, 10) > 7 ? 'cloture' : 'actif', // 70% actif, 30% clôturé
                            'description' => 'Transaction de vente générée automatiquement',
                            'created_at' => date('Y-m-d H:i:s', strtotime($month_start . ' +' . rand(0, 28) . ' days +' . rand(8, 18) . ' hours')),
                            'updated_at' => date('Y-m-d H:i:s')
                        ];
                        
                        $this->db->insert('crm_transactions', $transaction_data);
                        $transactions_created++;
                    }
                    
                    // Générer 1-2 locations par mois
                    $rentals_count = rand(1, 2);
                    for ($j = 0; $j < $rentals_count; $j++) {
                        $transaction_data = [
                            'agent_id' => $agent_id,
                            'type' => 'location',
                            'montant' => rand(1000, 5000), // Entre 1k et 5k TND/mois
                            'statut' => rand(0, 10) > 6 ? 'cloture' : 'actif', // 60% actif, 40% clôturé
                            'description' => 'Transaction de location générée automatiquement',
                            'created_at' => date('Y-m-d H:i:s', strtotime($month_start . ' +' . rand(0, 28) . ' days +' . rand(8, 18) . ' hours')),
                            'updated_at' => date('Y-m-d H:i:s')
                        ];
                        
                        $this->db->insert('crm_transactions', $transaction_data);
                        $transactions_created++;
                    }
                }
            }
            
            echo "<div class='result success'>";
            echo "✅ <strong>$transactions_created transactions créées</strong>";
            echo "</div>";
            
        } catch (Exception $e) {
            echo "<div class='result error'>";
            echo "❌ Erreur lors de la création des transactions: " . $e->getMessage();
            echo "</div>";
        }
        
        // 2. Générer des objectifs mensuels
        echo "<h2>🎯 Génération d'objectifs</h2>";
        
        try {
            $objectives_created = 0;
            $current_month = date('Y-m-01');
            
            foreach ($agent_ids as $agent_id) {
                // Vérifier si l'objectif existe déjà
                $existing = $this->db->where('agent_id', $agent_id)
                                   ->where('month', $current_month)
                                   ->get('monthly_objectives');
                
                if ($existing->num_rows() == 0) {
                    $objective_data = [
                        'agent_id' => $agent_id,
                        'month' => $current_month,
                        'estimations_target' => rand(10, 25), // 10-25 estimations par mois
                        'transactions_target' => rand(5, 15), // 5-15 transactions par mois
                        'revenue_target' => rand(100000, 300000), // 100k-300k TND de CA
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                    
                    $this->db->insert('monthly_objectives', $objective_data);
                    $objectives_created++;
                }
            }
            
            echo "<div class='result success'>";
            echo "✅ <strong>$objectives_created objectifs créés pour le mois " . date('M Y') . "</strong>";
            echo "</div>";
            
        } catch (Exception $e) {
            echo "<div class='result error'>";
            echo "❌ Erreur lors de la création des objectifs: " . $e->getMessage();
            echo "</div>";
        }
        
        // 3. Générer des commissions
        echo "<h2>💼 Génération de commissions</h2>";
        
        try {
            $commissions_created = 0;
            
            // Récupérer les transactions clôturées pour générer des commissions
            $closed_transactions = $this->db->where('statut', 'cloture')
                                           ->where_in('agent_id', $agent_ids)
                                           ->get('crm_transactions')
                                           ->result();
            
            foreach ($closed_transactions as $transaction) {
                // Vérifier si la commission existe déjà
                $existing = $this->db->where('transaction_id', $transaction->id)
                                   ->get('agent_commissions');
                
                if ($existing->num_rows() == 0) {
                    $commission_rate = 0.05; // 5% de commission
                    $commission_data = [
                        'agent_id' => $transaction->agent_id,
                        'transaction_id' => $transaction->id,
                        'transaction_type' => $transaction->type == 'vente' ? 'sale' : 'rental',
                        'base_amount' => $transaction->montant,
                        'commission_rate' => $commission_rate,
                        'commission_amount' => $transaction->montant * $commission_rate,
                        'status' => 'confirmed',
                        'created_at' => $transaction->created_at,
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                    
                    $this->db->insert('agent_commissions', $commission_data);
                    $commissions_created++;
                }
            }
            
            echo "<div class='result success'>";
            echo "✅ <strong>$commissions_created commissions créées</strong>";
            echo "</div>";
            
        } catch (Exception $e) {
            echo "<div class='result error'>";
            echo "❌ Erreur lors de la création des commissions: " . $e->getMessage();
            echo "</div>";
        }
        
        echo "<div class='result info'>";
        echo "<h3>✅ Génération terminée!</h3>";
        echo "<p>Vous pouvez maintenant retourner au dashboard manager pour voir les vraies données.</p>";
        echo "<p><a href='" . base_url('dashboard/manager') . "' style='display: inline-block; padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; margin-right: 10px;'>Dashboard Manager</a>";
        echo "<a href='" . base_url('dashboard/debug_transactions') . "' style='display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Debug Transactions</a></p>";
        echo "</div>";
        
        echo "</div>";
        echo "</body></html>";
    }
    
    /**
     * Ajoute des avatars par défaut pour les agents sans avatar
     */
    public function set_default_avatars() {
        $this->isLoggedIn();
        
        echo "<!DOCTYPE html>";
        echo "<html><head>";
        echo "<title>Avatars par Défaut - CRM Rebencia</title>";
        echo "<style>";
        echo "body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }";
        echo ".container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }";
        echo "h1, h2 { color: #2c3e50; }";
        echo ".result { background: #f8f9fa; border: 1px solid #dee2e6; padding: 15px; margin: 10px 0; border-radius: 5px; }";
        echo ".success { background: #d4edda; border-color: #c3e6cb; color: #155724; }";
        echo ".error { background: #f8d7da; border-color: #f5c6cb; color: #721c24; }";
        echo ".info { background: #d1ecf1; border-color: #bee5eb; color: #0c5460; }";
        echo ".avatar-demo { width: 60px; height: 60px; border-radius: 50%; margin: 10px; display: inline-block; }";
        echo "</style>";
        echo "</head><body>";
        
        echo "<div class='container'>";
        echo "<h1>👤 Configuration des Avatars par Défaut</h1>";
        
        // URLs d'avatars par défaut de qualité
        $default_avatars = [
            'https://i.pravatar.cc/150?img=1',  // Avatar homme 1
            'https://i.pravatar.cc/150?img=2',  // Avatar femme 1
            'https://i.pravatar.cc/150?img=3',  // Avatar homme 2
            'https://i.pravatar.cc/150?img=4',  // Avatar femme 2
            'https://i.pravatar.cc/150?img=5',  // Avatar homme 3
            'https://i.pravatar.cc/150?img=6',  // Avatar femme 3
            'https://i.pravatar.cc/150?img=7',  // Avatar homme 4
            'https://i.pravatar.cc/150?img=8',  // Avatar femme 4
        ];
        
        echo "<h2>🎨 Aperçu des Avatars par Défaut</h2>";
        echo "<div class='result info'>";
        echo "<p>Voici les avatars par défaut qui seront assignés :</p>";
        foreach ($default_avatars as $index => $avatar_url) {
            echo "<img src='$avatar_url' alt='Avatar " . ($index + 1) . "' class='avatar-demo'>";
        }
        echo "</div>";
        
        $agency_id = $this->agencyId ?: $this->session->userdata('agency_id') ?: 1;
        
        try {
            $agents = $this->agent_model->get_agents_by_agency_with_avatars($agency_id);
            $updated_count = 0;
            
            echo "<h2>📝 Attribution des Avatars</h2>";
            
            foreach ($agents as $index => $agent) {
                $has_avatar = !empty($agent->avatar_url);
                
                echo "<div class='result " . ($has_avatar ? 'success' : 'info') . "'>";
                echo "<strong>{$agent->display_name}</strong> (User ID: {$agent->user_id})<br>";
                
                if ($has_avatar) {
                    echo "✅ Possède déjà un avatar: " . htmlspecialchars($agent->avatar_url);
                } else {
                    // Assigner un avatar par défaut basé sur l'index
                    $default_avatar = $default_avatars[$index % count($default_avatars)];
                    
                    echo "🎯 Avatar par défaut assigné: <br>";
                    echo "<img src='$default_avatar' alt='Avatar par défaut' class='avatar-demo'><br>";
                    echo "URL: " . htmlspecialchars($default_avatar) . "<br>";
                    
                    // Ici, vous pourriez ajouter la logique pour sauvegarder l'avatar dans WordPress
                    // Par exemple, en mettant à jour wp_usermeta avec fave_author_custom_picture
                    
                    try {
                        $this->load->database('wordpress');
                        $wp_db = $this->load->database('wordpress', TRUE);
                        
                        // Vérifier si l'utilisateur a déjà une entrée fave_author_custom_picture
                        $existing = $wp_db->select('umeta_id')
                                          ->from('usermeta')
                                          ->where('user_id', $agent->user_id)
                                          ->where('meta_key', 'fave_author_custom_picture')
                                          ->get()->row();
                        
                        if ($existing) {
                            // Mettre à jour
                            $wp_db->where('umeta_id', $existing->umeta_id)
                                  ->update('usermeta', ['meta_value' => $default_avatar]);
                            echo "✅ Avatar mis à jour dans WordPress";
                        } else {
                            // Insérer
                            $wp_db->insert('usermeta', [
                                'user_id' => $agent->user_id,
                                'meta_key' => 'fave_author_custom_picture',
                                'meta_value' => $default_avatar
                            ]);
                            echo "✅ Avatar ajouté dans WordPress";
                        }
                        
                        $updated_count++;
                        
                    } catch (Exception $e) {
                        echo "❌ Erreur lors de la sauvegarde: " . $e->getMessage();
                    }
                }
                echo "</div>";
            }
            
            echo "<div class='result success'>";
            echo "<strong>✅ Terminé!</strong><br>";
            echo "Avatars assignés: $updated_count<br>";
            echo "Total d'agents: " . count($agents);
            echo "</div>";
            
        } catch (Exception $e) {
            echo "<div class='result error'>";
            echo "<strong>Erreur:</strong> " . $e->getMessage();
            echo "</div>";
        }
        
        echo "<div class='result info'>";
        echo "<p><strong>Note:</strong> Les avatars par défaut sont fournis par Pravatar.cc et sont de haute qualité. Ils seront visibles immédiatement dans le dashboard.</p>";
        echo "<p><a href='" . base_url('dashboard/manager') . "' class='btn btn-primary' style='display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Voir le Dashboard Manager</a></p>";
        echo "</div>";
        
        echo "</div>";
        echo "</body></html>";
    }
    
    /**
     * Force la mise à jour des avatars depuis différentes sources
     */
    public function update_avatars() {
        $this->isLoggedIn();
        
        echo "<!DOCTYPE html>";
        echo "<html><head>";
        echo "<title>Mise à jour Avatars - CRM Rebencia</title>";
        echo "<style>";
        echo "body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }";
        echo ".container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }";
        echo "h1, h2 { color: #2c3e50; }";
        echo ".result { background: #f8f9fa; border: 1px solid #dee2e6; padding: 15px; margin: 10px 0; border-radius: 5px; }";
        echo ".success { background: #d4edda; border-color: #c3e6cb; color: #155724; }";
        echo ".error { background: #f8d7da; border-color: #f5c6cb; color: #721c24; }";
        echo ".info { background: #d1ecf1; border-color: #bee5eb; color: #0c5460; }";
        echo "</style>";
        echo "</head><body>";
        
        echo "<div class='container'>";
        echo "<h1>🔄 Mise à jour des Avatars</h1>";
        
        $agency_id = $this->agencyId ?: $this->session->userdata('agency_id') ?: 1;
        
        try {
            $this->load->database('wordpress');
            $wp_db = $this->load->database('wordpress', TRUE);
            
            // Rechercher tous les utilisateurs WordPress avec des avatars
            echo "<h2>1. Recherche des avatars dans wp_usermeta</h2>";
            
            $avatars_meta = $wp_db->select('u.ID, u.user_login, u.user_email, um.meta_key, um.meta_value')
                                  ->from('users u')
                                  ->join('usermeta um', 'u.ID = um.user_id')
                                  ->where_in('um.meta_key', ['fave_author_custom_picture', 'wp_user_avatar', 'avatar', '_wp_attachment_id'])
                                  ->where('um.meta_value !=', '')
                                  ->get();
            
            echo "<div class='result info'>";
            echo "<strong>Avatars trouvés dans usermeta:</strong> " . $avatars_meta->num_rows() . "<br>";
            
            $avatar_data = [];
            foreach ($avatars_meta->result() as $meta) {
                if (!isset($avatar_data[$meta->ID])) {
                    $avatar_data[$meta->ID] = [
                        'user_login' => $meta->user_login,
                        'user_email' => $meta->user_email,
                        'avatars' => []
                    ];
                }
                $avatar_data[$meta->ID]['avatars'][$meta->meta_key] = $meta->meta_value;
            }
            
            foreach ($avatar_data as $user_id => $data) {
                echo "<strong>User ID $user_id ({$data['user_login']}):</strong><br>";
                foreach ($data['avatars'] as $key => $value) {
                    echo "&nbsp;&nbsp;- $key: " . htmlspecialchars($value) . "<br>";
                }
                echo "<br>";
            }
            echo "</div>";
            
            // Rechercher les thumbnails dans wp_postmeta
            echo "<h2>2. Recherche des thumbnails dans wp_postmeta</h2>";
            
            $thumbnails = $wp_db->select('p.ID, p.post_title, pm.meta_value as thumbnail_id, att.guid')
                                ->from('posts p')
                                ->join('postmeta pm', 'p.ID = pm.post_id AND pm.meta_key = "_thumbnail_id"')
                                ->join('posts att', 'att.ID = pm.meta_value AND att.post_type = "attachment"')
                                ->where_in('p.post_type', ['houzez_agent', 'houzez_manager'])
                                ->where('p.post_status', 'publish')
                                ->get();
            
            echo "<div class='result info'>";
            echo "<strong>Thumbnails trouvés:</strong> " . $thumbnails->num_rows() . "<br>";
            
            foreach ($thumbnails->result() as $thumb) {
                echo "<strong>{$thumb->post_title} (ID: {$thumb->ID}):</strong><br>";
                echo "&nbsp;&nbsp;Thumbnail ID: {$thumb->thumbnail_id}<br>";
                echo "&nbsp;&nbsp;URL: " . htmlspecialchars($thumb->guid) . "<br><br>";
            }
            echo "</div>";
            
            // Mise à jour des agents de l'agence
            echo "<h2>3. Mise à jour des agents de l'agence $agency_id</h2>";
            
            $agents = $this->agent_model->get_agents_by_agency_with_avatars($agency_id);
            $updated_count = 0;
            
            echo "<div class='result'>";
            foreach ($agents as $agent) {
                $has_avatar = !empty($agent->avatar_url);
                $status_class = $has_avatar ? 'success' : 'error';
                
                echo "<div class='result $status_class'>";
                echo "<strong>{$agent->display_name}</strong><br>";
                echo "User ID: {$agent->user_id}<br>";
                echo "Avatar: " . ($has_avatar ? htmlspecialchars($agent->avatar_url) : 'AUCUN') . "<br>";
                
                if ($has_avatar) {
                    $updated_count++;
                    echo "✅ Avatar disponible";
                } else {
                    echo "❌ Avatar manquant";
                    
                    // Essayer de trouver un avatar alternatif
                    if (isset($avatar_data[$agent->user_id])) {
                        echo "<br>Avatars alternatifs trouvés:";
                        foreach ($avatar_data[$agent->user_id]['avatars'] as $key => $value) {
                            echo "<br>&nbsp;&nbsp;- $key: " . htmlspecialchars($value);
                        }
                    }
                }
                echo "</div>";
            }
            
            echo "</div>";
            
            echo "<div class='result " . ($updated_count > 0 ? 'success' : 'error') . "'>";
            echo "<strong>Résultat:</strong> $updated_count agents sur " . count($agents) . " ont un avatar.";
            echo "</div>";
            
        } catch (Exception $e) {
            echo "<div class='result error'>";
            echo "<strong>Erreur:</strong> " . $e->getMessage();
            echo "</div>";
        }
        
        echo "<p><a href='" . base_url('dashboard/manager') . "' class='btn btn-primary'>Retour au Dashboard Manager</a></p>";
        echo "</div>";
        echo "</body></html>";
    }
    
    /**
     * Test de la récupération d'avatars des agents
     */
    public function test_avatars() {
        $this->isLoggedIn();
        
        echo "<!DOCTYPE html>";
        echo "<html><head>";
        echo "<title>Test Avatars - CRM Rebencia</title>";
        echo "<style>";
        echo "body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }";
        echo ".container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }";
        echo "h1, h2 { color: #2c3e50; }";
        echo ".agent-card { background: #f8f9fa; border: 1px solid #dee2e6; padding: 15px; margin: 10px 0; border-radius: 5px; display: flex; align-items: center; }";
        echo ".avatar { width: 60px; height: 60px; border-radius: 50%; margin-right: 15px; object-fit: cover; }";
        echo ".avatar-placeholder { width: 60px; height: 60px; border-radius: 50%; margin-right: 15px; background: #007bff; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; }";
        echo ".success { background: #d4edda; border-color: #c3e6cb; }";
        echo ".error { background: #f8d7da; border-color: #f5c6cb; }";
        echo "pre { background: #2c3e50; color: #ecf0f1; padding: 10px; border-radius: 5px; overflow-x: auto; font-size: 12px; }";
        echo "</style>";
        echo "</head><body>";
        
        echo "<div class='container'>";
        echo "<h1>🖼️ Test des Avatars des Agents</h1>";
        
        // Récupérer l'agency_id
        $agency_id = $this->agencyId ?: $this->session->userdata('agency_id') ?: 1;
        echo "<p><strong>Agency ID utilisé:</strong> $agency_id</p>";
        
        // Récupérer les agents
        echo "<h2>📋 Agents avec Avatars</h2>";
        
        try {
            $agents = $this->agent_model->get_agents_by_agency_with_avatars($agency_id);
            echo "<p><strong>Nombre d'agents trouvés:</strong> " . count($agents) . "</p>";
            
            if (!empty($agents)) {
                foreach ($agents as $agent) {
                    $has_avatar = !empty($agent->avatar_url);
                    echo "<div class='agent-card " . ($has_avatar ? 'success' : 'error') . "'>";
                    
                    // Avatar ou placeholder
                    if ($has_avatar) {
                        echo "<img src='" . htmlspecialchars($agent->avatar_url) . "' alt='Avatar' class='avatar' onerror='this.style.display=\"none\"; this.nextElementSibling.style.display=\"flex\";'>";
                        echo "<div class='avatar-placeholder' style='display: none;'>" . strtoupper(substr($agent->display_name, 0, 2)) . "</div>";
                    } else {
                        echo "<div class='avatar-placeholder'>" . strtoupper(substr($agent->display_name, 0, 2)) . "</div>";
                    }
                    
                    echo "<div>";
                    echo "<h3>" . htmlspecialchars($agent->display_name) . "</h3>";
                    echo "<p><strong>Email:</strong> " . htmlspecialchars($agent->user_email) . "</p>";
                    echo "<p><strong>User ID:</strong> " . $agent->user_id . "</p>";
                    echo "<p><strong>Avatar URL:</strong> " . ($has_avatar ? htmlspecialchars($agent->avatar_url) : '<em>Aucun avatar</em>') . "</p>";
                    
                    if (isset($agent->avatar_id) && $agent->avatar_id) {
                        echo "<p><strong>Avatar ID:</strong> " . $agent->avatar_id . "</p>";
                    }
                    
                    echo "<details>";
                    echo "<summary>Données complètes</summary>";
                    echo "<pre>" . print_r($agent, true) . "</pre>";
                    echo "</details>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<p class='error'>Aucun agent trouvé pour l'agence $agency_id</p>";
            }
            
        } catch (Exception $e) {
            echo "<div class='error'>";
            echo "<strong>Erreur:</strong> " . $e->getMessage();
            echo "</div>";
        }
        
        // Test des top performers
        echo "<h2>🏆 Top Performers avec Avatars</h2>";
        
        try {
            $top_performers = $this->get_top_performing_agents($agency_id);
            echo "<p><strong>Nombre de top performers:</strong> " . count($top_performers) . "</p>";
            
            if (!empty($top_performers)) {
                foreach ($top_performers as $index => $agent) {
                    $has_avatar = !empty($agent->avatar_url);
                    echo "<div class='agent-card " . ($has_avatar ? 'success' : 'error') . "'>";
                    
                    // Avatar ou placeholder
                    if ($has_avatar) {
                        echo "<img src='" . htmlspecialchars($agent->avatar_url) . "' alt='Avatar' class='avatar' onerror='this.style.display=\"none\"; this.nextElementSibling.style.display=\"flex\";'>";
                        echo "<div class='avatar-placeholder' style='display: none;'>" . strtoupper(substr($agent->display_name, 0, 2)) . "</div>";
                    } else {
                        echo "<div class='avatar-placeholder'>" . strtoupper(substr($agent->display_name, 0, 2)) . "</div>";
                    }
                    
                    echo "<div>";
                    echo "<h3>#" . ($index + 1) . " - " . htmlspecialchars($agent->display_name) . "</h3>";
                    echo "<p><strong>Ventes:</strong> " . ($agent->sales_count ?? 0) . " | <strong>Revenus:</strong> " . number_format($agent->revenue ?? 0, 0, ',', ' ') . " €</p>";
                    echo "<p><strong>Avatar URL:</strong> " . ($has_avatar ? htmlspecialchars($agent->avatar_url) : '<em>Aucun avatar</em>') . "</p>";
                    echo "</div>";
                    echo "</div>";
                }
            }
            
        } catch (Exception $e) {
            echo "<div class='error'>";
            echo "<strong>Erreur Top Performers:</strong> " . $e->getMessage();
            echo "</div>";
        }
        
        // Test de recherche directe dans la base de données
        echo "<h2>🔍 Recherche Directe Avatar dans WordPress</h2>";
        
        try {
            $this->load->database('wordpress');
            $wp_db = $this->load->database('wordpress', TRUE);
            
            // Rechercher les avatars personnalisés HOUZEZ
            $avatars_query = $wp_db->select('u.ID, u.user_login, u.user_email, um.meta_value as avatar')
                                   ->from('users u')
                                   ->join('usermeta um', 'u.ID = um.user_id')
                                   ->where('um.meta_key', 'fave_author_custom_picture')
                                   ->where('um.meta_value !=', '')
                                   ->limit(10)
                                   ->get();
            
            echo "<h3>Avatars HOUZEZ trouvés (fave_author_custom_picture):</h3>";
            if ($avatars_query->num_rows() > 0) {
                foreach ($avatars_query->result() as $avatar_user) {
                    echo "<div class='agent-card'>";
                    
                    // Tester si c'est une URL ou un ID
                    $avatar_value = $avatar_user->avatar;
                    if (filter_var($avatar_value, FILTER_VALIDATE_URL)) {
                        echo "<img src='" . htmlspecialchars($avatar_value) . "' alt='Avatar' class='avatar' onerror='this.style.display=\"none\"; this.nextElementSibling.style.display=\"flex\";'>";
                        echo "<div class='avatar-placeholder' style='display: none;'>" . strtoupper(substr($avatar_user->user_login, 0, 2)) . "</div>";
                    } else {
                        echo "<div class='avatar-placeholder'>" . strtoupper(substr($avatar_user->user_login, 0, 2)) . "</div>";
                    }
                    
                    echo "<div>";
                    echo "<strong>" . htmlspecialchars($avatar_user->user_login) . "</strong><br>";
                    echo "<small>" . htmlspecialchars($avatar_user->user_email) . "</small><br>";
                    echo "<small>Avatar: " . htmlspecialchars($avatar_value) . "</small>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<p>Aucun avatar HOUZEZ trouvé</p>";
            }
            
        } catch (Exception $e) {
            echo "<div class='error'>";
            echo "<strong>Erreur recherche directe:</strong> " . $e->getMessage();
            echo "</div>";
        }
        
        echo "</div>";
        echo "</body></html>";
    }
    
    /**
     * Test de la récupération d'agency_id depuis wp_Hrg8P_crm_agents
     */
    public function test_agency_id_recovery() {
        $this->isLoggedIn();
        
        echo "<!DOCTYPE html>";
        echo "<html><head>";
        echo "<title>Test Agency ID Recovery - CRM Rebencia</title>";
        echo "<style>";
        echo "body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }";
        echo ".container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }";
        echo "h1, h2 { color: #2c3e50; }";
        echo ".result { background: #f8f9fa; border: 1px solid #dee2e6; padding: 15px; margin: 10px 0; border-radius: 5px; }";
        echo ".success { background: #d4edda; border-color: #c3e6cb; color: #155724; }";
        echo ".error { background: #f8d7da; border-color: #f5c6cb; color: #721c24; }";
        echo ".info { background: #d1ecf1; border-color: #bee5eb; color: #0c5460; }";
        echo "pre { background: #2c3e50; color: #ecf0f1; padding: 15px; border-radius: 5px; overflow-x: auto; }";
        echo "</style>";
        echo "</head><body>";
        
        echo "<div class='container'>";
        echo "<h1>🔧 Test de Récupération Agency ID</h1>";
        
        // 1. Informations actuelles
        echo "<h2>📊 État Actuel</h2>";
        echo "<div class='result info'>";
        echo "<strong>agencyId (BaseController):</strong> " . ($this->agencyId ?: 'NULL') . "<br>";
        echo "<strong>agency_id (Session):</strong> " . ($this->session->userdata('agency_id') ?: 'NULL') . "<br>";
        echo "<strong>user_post_id (Session):</strong> " . ($this->session->userdata('user_post_id') ?: 'NULL') . "<br>";
        echo "<strong>wp_id (Session):</strong> " . ($this->session->userdata('wp_id') ?: 'NULL') . "<br>";
        echo "</div>";
        
        // 2. Test de la méthode de récupération
        $user_post_id = $this->session->userdata('user_post_id');
        if ($user_post_id) {
            echo "<h2>🔍 Test de Récupération via wp_Hrg8P_crm_agents</h2>";
            
            try {
                $this->load->database('wordpress');
                $wp_db = $this->load->database('wordpress', TRUE);
                
                // Test direct de la requête
                $query = $wp_db->select('*')
                               ->from('wp_Hrg8P_crm_agents')
                               ->where('agent_post_id', $user_post_id)
                               ->get();
                
                echo "<div class='result'>";
                echo "<strong>Requête SQL exécutée:</strong><br>";
                echo "<code>SELECT * FROM wp_Hrg8P_crm_agents WHERE agent_post_id = $user_post_id</code><br><br>";
                echo "<strong>Résultats trouvés:</strong> " . $query->num_rows() . "<br>";
                
                if ($query->num_rows() > 0) {
                    $agent = $query->row();
                    echo "<div class='result success'>";
                    echo "<strong>✅ Agency ID trouvé:</strong> " . ($agent->agency_id ?: 'NULL') . "<br>";
                    echo "<strong>Données complètes:</strong><br>";
                    echo "<pre>" . print_r($agent, true) . "</pre>";
                    echo "</div>";
                    
                    // Test de mise à jour en session
                    if (!empty($agent->agency_id)) {
                        $this->session->set_userdata('agency_id', $agent->agency_id);
                        echo "<div class='result success'>";
                        echo "✅ Agency ID sauvegardé en session: " . $agent->agency_id;
                        echo "</div>";
                    }
                } else {
                    echo "<div class='result error'>";
                    echo "❌ Aucun résultat trouvé pour agent_post_id = $user_post_id<br>";
                    echo "</div>";
                    
                    // Essayer avec d'autres colonnes
                    echo "<h3>🔍 Recherche alternative...</h3>";
                    
                    // Essayer avec user_id (colonne correcte)
                    $query_id = $wp_db->select('*')
                                      ->from('wp_Hrg8P_crm_agents')
                                      ->where('user_id', $user_post_id)
                                      ->get();
                    
                    echo "<div class='result'>";
                    echo "<strong>Test avec user_id = $user_post_id:</strong> " . $query_id->num_rows() . " résultat(s)<br>";
                    if ($query_id->num_rows() > 0) {
                        $agent = $query_id->row();
                        echo "<div class='result success'>";
                        echo "<strong>✅ Agency ID trouvé via user_id:</strong> " . ($agent->agency_id ?: 'NULL') . "<br>";
                        echo "<pre>" . print_r($agent, true) . "</pre>";
                        echo "</div>";
                    }
                    echo "</div>";
                    
                    // Montrer un échantillon des données
                    echo "<h3>📋 Échantillon des données disponibles</h3>";
                    $sample = $wp_db->select('agent_post_id, user_id, agency_id, display_name')
                                    ->from('wp_Hrg8P_crm_agents')
                                    ->limit(10)
                                    ->get();
                    
                    if ($sample->num_rows() > 0) {
                        echo "<div class='result info'>";
                        echo "<strong>Premiers agents dans la vue:</strong><br>";
                        echo "<table border='1' cellpadding='5' cellspacing='0'>";
                        echo "<tr><th>agent_post_id</th><th>user_id</th><th>agency_id</th><th>display_name</th></tr>";
                        foreach ($sample->result() as $row) {
                            echo "<tr>";
                            echo "<td>" . ($row->agent_post_id ?: 'NULL') . "</td>";
                            echo "<td>" . ($row->user_id ?: 'NULL') . "</td>";
                            echo "<td>" . ($row->agency_id ?: 'NULL') . "</td>";
                            echo "<td>" . ($row->display_name ?: 'NULL') . "</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                        echo "</div>";
                    }
                }
                echo "</div>";
                
            } catch (Exception $e) {
                echo "<div class='result error'>";
                echo "<strong>❌ Erreur:</strong> " . $e->getMessage();
                echo "</div>";
            }
        } else {
            echo "<div class='result error'>";
            echo "❌ Aucun user_post_id en session pour effectuer le test";
            echo "</div>";
        }
        
        // 3. Test final
        echo "<h2>🎯 Test Final</h2>";
        echo "<div class='result'>";
        echo "<strong>Agency ID final (après test):</strong> " . ($this->session->userdata('agency_id') ?: 'NULL') . "<br>";
        echo "<strong>Recommandation:</strong> ";
        if ($this->session->userdata('agency_id')) {
            echo "<span style='color: green;'>✅ Agency ID récupéré avec succès!</span>";
        } else {
            echo "<span style='color: red;'>❌ Agency ID non récupéré. Vérifier les données dans wp_Hrg8P_crm_agents.</span>";
        }
        echo "</div>";
        
        echo "</div>";
        echo "</body></html>";
    }
    
    /**
     * Affiche tous les détails de la session utilisateur connecté
     */
    public function debug_session() {
        $this->isLoggedIn();
        
        $data = $this->global;
        $data['pageTitle'] = 'Debug Session - CRM Rebencia';
        
        // Préparer toutes les données pour la vue
        $debug_data = [];
        
        // 1. Données de session CodeIgniter
        $debug_data['session_data'] = $this->session->all_userdata();
        
        // 2. Propriétés du BaseController
        $debug_data['base_controller'] = [
            'role' => $this->role,
            'vendorId' => $this->vendorId,
            'name' => $this->name,
            'roleText' => $this->roleText,
            'isAdmin' => $this->isAdmin,
            'lastLogin' => $this->lastLogin,
            'module' => $this->module,
            'wp_avatar' => $this->wp_avatar,
            'userPostId' => $this->userPostId,
            'agencyId' => $this->agencyId,
        ];
        
        // 3. Variable globale $this->global
        $debug_data['global_var'] = $this->global;
        
        // 4. Informations sur l'utilisateur WordPress
        $debug_data['wp_user_info'] = [];
        $wp_id = $this->session->userdata('wp_id');
        if ($wp_id) {
            try {
                $this->load->database('wordpress');
                $wp_db = $this->load->database('wordpress', TRUE);
                
                // Récupérer les données utilisateur
                $user_query = $wp_db->where('ID', $wp_id)->get('users');
                if ($user_query->num_rows() > 0) {
                    $debug_data['wp_user_info']['user'] = $user_query->row();
                }
                
                // Récupérer les métadonnées utilisateur
                $meta_query = $wp_db->where('user_id', $wp_id)->get('usermeta');
                if ($meta_query->num_rows() > 0) {
                    $debug_data['wp_user_info']['meta'] = $meta_query->result();
                }
                
            } catch (Exception $e) {
                $debug_data['wp_user_info']['error'] = $e->getMessage();
            }
        }
        
        // 5. Test de récupération d'agency_id depuis wp_Hrg8P_crm_agents
        $debug_data['agency_test'] = [];
        $debug_data['agency_test']['current_agency_id'] = $this->agencyId;
        $debug_data['agency_test']['session_agency_id'] = $this->session->userdata('agency_id');
        $debug_data['agency_test']['user_post_id'] = $this->session->userdata('user_post_id');
        
        $user_post_id = $this->session->userdata('user_post_id');
        if ($user_post_id) {
            try {
                $this->load->database('wordpress');
                $wp_db = $this->load->database('wordpress', TRUE);
                
                // Vérifier l'existence de la vue
                $check = $wp_db->query("SHOW TABLES LIKE 'wp_Hrg8P_crm_agents'");
                $debug_data['agency_test']['view_exists'] = $check->num_rows() > 0;
                
                if ($check->num_rows() > 0) {
                    // Rechercher l'agent par agent_post_id
                    $agent_query = $wp_db->select('*')
                                         ->from('wp_Hrg8P_crm_agents')
                                         ->where('agent_post_id', $user_post_id)
                                         ->get();
                    
                    $debug_data['agency_test']['agents_found_by_agent_post_id'] = $agent_query->num_rows();
                    
                    if ($agent_query->num_rows() > 0) {
                        $debug_data['agency_test']['agent_data'] = $agent_query->row();
                    } else {
                        // Essayer avec user_id
                        $agent_query_id = $wp_db->select('*')
                                                ->from('wp_Hrg8P_crm_agents')
                                                ->where('user_id', $user_post_id)
                                                ->get();
                        
                        $debug_data['agency_test']['agents_found_by_user_id'] = $agent_query_id->num_rows();
                        
                        if ($agent_query_id->num_rows() > 0) {
                            $debug_data['agency_test']['agent_data_via_user_id'] = $agent_query_id->row();
                        } else {
                            // Échantillon des données
                            $sample_query = $wp_db->select('*')
                                                  ->from('wp_Hrg8P_crm_agents')
                                                  ->limit(3)
                                                  ->get();
                            
                            $debug_data['agency_test']['sample_data'] = $sample_query->result();
                        }
                    }
                }
                
            } catch (Exception $e) {
                $debug_data['agency_test']['error'] = $e->getMessage();
            }
        }
        
        // 6. Variables serveur
        $debug_data['server_vars'] = [];
        $server_vars = ['HTTP_HOST', 'REQUEST_URI', 'HTTP_USER_AGENT', 'REMOTE_ADDR'];
        foreach ($server_vars as $var) {
            if (isset($_SERVER[$var])) {
                $debug_data['server_vars'][$var] = $_SERVER[$var];
            }
        }
        
        $data['debug_data'] = $debug_data;
        
        // Charger la vue dédiée
        $this->loadViews('dashboard/debug_session', $this->global, $data, NULL);
    }
    
    // Méthodes helper pour les statistiques du dashboard manager
    private function get_agency_properties_count($agency_id) {
        try {
            $wp_db = $this->load->database('wordpress', TRUE);
            $wp_db->select('COUNT(*) as count');
            $wp_db->from('posts p');
            $wp_db->join('postmeta pm', 'p.ID = pm.post_id AND pm.meta_key = "fave_property_agency"');
            $wp_db->where('p.post_type', 'property');
            $wp_db->where('p.post_status', 'publish');
            $wp_db->where('pm.meta_value', $agency_id);
            $query = $wp_db->get();
            return $query->row()->count ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }
    
    private function get_agency_active_listings($agency_id) {
        try {
            $wp_db = $this->load->database('wordpress', TRUE);
            $wp_db->select('COUNT(*) as count');
            $wp_db->from('posts p');
            $wp_db->join('postmeta pm_agency', 'p.ID = pm_agency.post_id AND pm_agency.meta_key = "fave_property_agency"');
            $wp_db->join('postmeta pm_status', 'p.ID = pm_status.post_id AND pm_status.meta_key = "fave_property_status"');
            $wp_db->where('p.post_type', 'property');
            $wp_db->where('p.post_status', 'publish');
            $wp_db->where('pm_agency.meta_value', $agency_id);
            $wp_db->where('pm_status.meta_value', 'for-sale');
            $query = $wp_db->get();
            return $query->row()->count ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }
    
    private function get_agency_monthly_revenue($agency_id) {
        // Simuler des données de revenus (à adapter selon votre système)
        return rand(50000, 150000);
    }
    
    private function get_agency_pending_tasks($agency_id) {
        try {
            // Vérifier si la table tasks existe
            $query = $this->db->query("SHOW TABLES LIKE 'tasks'");
            if ($query->num_rows() > 0) {
                $this->db->select('COUNT(*) as count');
                $this->db->from('tasks');
                $this->db->where('agency_id', $agency_id);
                $this->db->where('status', 'pending');
                $query = $this->db->get();
                return $query->row()->count ?? 0;
            } else {
                // Si la table n'existe pas, retourner une valeur simulée
                return rand(5, 25);
            }
        } catch (Exception $e) {
            return rand(5, 25);
        }
    }
    
    private function get_agency_recent_activities($agency_id) {
        // Récupérer les activités récentes de l'agence
        return [
            ['type' => 'property_added', 'message' => 'Nouvelle propriété ajoutée', 'time' => '2 heures'],
            ['type' => 'client_contact', 'message' => 'Nouveau contact client', 'time' => '4 heures'],
            ['type' => 'viewing_scheduled', 'message' => 'Visite programmée', 'time' => '6 heures']
        ];
    }
    
    private function get_top_performing_agents($agency_id) {
        // Retourner les 3 meilleurs agents avec leurs performances
        $agents = $this->agent_model->get_agents_by_agency_with_avatars($agency_id);
        $top_agents = array_slice($agents, 0, 3);
        foreach ($top_agents as &$agent) {
            $agent->sales_count = rand(3, 15);
            $agent->revenue = rand(20000, 80000);
        }
        return $top_agents;
    }
    
    private function get_properties_by_type($agency_id) {
        return [
            'Appartement' => rand(15, 45),
            'Maison' => rand(10, 30),
            'Villa' => rand(5, 15),
            'Bureau' => rand(2, 10)
        ];
    }
    
    private function get_monthly_statistics($agency_id) {
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = date('M Y', strtotime("-$i months"));
            $months[$month] = [
                'properties' => rand(5, 20),
                'sales' => rand(2, 8),
                'revenue' => rand(30000, 100000)
            ];
        }
        return $months;
    }
    
    private function get_monthly_sales_data($agency_id) {
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = date('M', strtotime("-$i months"));
            $data[] = [
                'month' => $month,
                'sales' => rand(2, 15),
                'revenue' => rand(20000, 120000)
            ];
        }
        return $data;
    }
    
    private function get_agents_performance_data($agency_id) {
        $agents = $this->agent_model->get_agents_by_agency_with_avatars($agency_id);
        $performance = [];
        foreach ($agents as $agent) {
            $performance[] = [
                'name' => $agent->display_name,
                'properties' => $agent->property_count ?? rand(1, 10),
                'sales' => rand(1, 8)
            ];
        }
        return $performance;
    }
    
    private function get_properties_status_data($agency_id) {
        return [
            'À vendre' => rand(20, 40),
            'À louer' => rand(15, 35),
            'Vendu' => rand(5, 15),
            'Loué' => rand(8, 20)
        ];
    }
    
    /**
     * Récupère les agents filtrés depuis la vue wp_Hrg8P_crm_agents par agency_id
     */
    private function get_filtered_agents_from_view($agency_id) {
        try {
            $this->load->database('wordpress');
            $wp_db = $this->load->database('wordpress', TRUE);
            
            // D'abord, récupérer la structure de la vue pour connaître les colonnes disponibles
            $columns_query = $wp_db->query("SHOW COLUMNS FROM wp_Hrg8P_crm_agents");
            $available_columns = [];
            if ($columns_query) {
                foreach ($columns_query->result() as $col) {
                    $available_columns[] = $col->Field;
                }
            }
            
            // Utiliser directement la vue wp_Hrg8P_crm_agents avec filtre par agency_id
            $wp_db->select('*');
            $wp_db->from('wp_Hrg8P_crm_agents');
            $wp_db->where('agency_id', $agency_id);
            
            $query = $wp_db->get();
            $agents = $query->result();
            
            // Nettoyer et enrichir les données selon les colonnes disponibles
            foreach ($agents as &$agent) {
                // Mapping intelligent des champs selon les colonnes disponibles
                $this->map_agent_fields($agent, $available_columns);
            }
            
            // Tri en PHP selon le champ de nom disponible
            $this->sort_agents_by_name($agents);
            
            return $agents;
            
        } catch (Exception $e) {
            // En cas d'erreur, utiliser la méthode de fallback
            return $this->agent_model->get_agents_by_agency_with_avatars($agency_id);
        }
    }
    
    /**
     * Mappe les champs de l'agent selon les colonnes disponibles
     */
    private function map_agent_fields(&$agent, $available_columns) {
        // S'assurer que les propriétés nécessaires existent
        if (!isset($agent->property_count)) {
            $agent->property_count = 0;
        }
        if (!isset($agent->avatar_url)) {
            $agent->avatar_url = '';
        }
        
        // Mapping du nom d'affichage selon les colonnes disponibles
        if (!isset($agent->display_name)) {
            if (in_array('user_nicename', $available_columns) && !empty($agent->user_nicename)) {
                $agent->display_name = $agent->user_nicename;
            } elseif (in_array('user_login', $available_columns) && !empty($agent->user_login)) {
                $agent->display_name = $agent->user_login;
            } elseif (in_array('user_email', $available_columns) && !empty($agent->user_email)) {
                $agent->display_name = explode('@', $agent->user_email)[0];
            } elseif (in_array('post_title', $available_columns) && !empty($agent->post_title)) {
                $agent->display_name = $agent->post_title;
            } else {
                $agent->display_name = 'Agent #' . ($agent->ID ?? 'N/A');
            }
        }
        
        // Mapping de l'email
        if (!isset($agent->user_email) && in_array('user_email', $available_columns)) {
            $agent->user_email = $agent->user_email ?? '';
        }
        
        // Mapping du nom d'utilisateur
        if (!isset($agent->user_nicename)) {
            if (in_array('user_nicename', $available_columns) && !empty($agent->user_nicename)) {
                // Déjà défini
            } elseif (in_array('user_login', $available_columns) && !empty($agent->user_login)) {
                $agent->user_nicename = $agent->user_login;
            } elseif (in_array('user_email', $available_columns) && !empty($agent->user_email)) {
                $agent->user_nicename = explode('@', $agent->user_email)[0];
            } else {
                $agent->user_nicename = $agent->display_name ?? 'agent';
            }
        }
        
        // S'assurer que user_nicename n'est jamais vide
        if (empty($agent->user_nicename)) {
            $agent->user_nicename = $agent->user_email ? explode('@', $agent->user_email)[0] : 'agent';
        }
        
        // Mapping du rôle utilisateur
        if (!isset($agent->user_role)) {
            if (in_array('post_type', $available_columns) && !empty($agent->post_type)) {
                $agent->user_role = $agent->post_type;
            } elseif (in_array('user_role', $available_columns) && !empty($agent->user_role)) {
                // Déjà défini
            } else {
                $agent->user_role = 'houzez_agent'; // Valeur par défaut
            }
        }
        
        // Mapping des IDs
        $agent->user_id = $agent->ID ?? $agent->user_id ?? 0;
        $agent->agent_post_id = $agent->ID ?? $agent->agent_post_id ?? 0;
    }
    
    /**
     * Trie les agents par nom
     */
    private function sort_agents_by_name(&$agents) {
        usort($agents, function($a, $b) {
            $name_a = $a->display_name ?? '';
            $name_b = $b->display_name ?? '';
            return strcmp($name_a, $name_b);
        });
    }

    // Vue Agent : tableau de bord moderne et premium
    public function agent($agent_id = null) {
        $this->isLoggedIn();
        
        // Récupération fiable de l'identifiant agent (triple fallback)
        $user_post_id = $this->userPostId ?: $this->session->userdata('user_post_id') ?: $agent_id;
        
        // Si toujours pas d'ID, utiliser userId avec recherche dans agent model
        if (empty($user_post_id)) {
            $userId = $this->global['userId'] ?? $this->session->userdata('userId');
            if ($userId) {
                $agent_data = $this->agent_model->get_agent_by_user_id($userId);
                if ($agent_data && isset($agent_data->agent_post_id)) {
                    $user_post_id = $agent_data->agent_post_id;
                } elseif ($agent_data && isset($agent_data->ID)) {
                    $user_post_id = $agent_data->ID;
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
     * Récupère le nombre de propriétés par type de statut
     */
    private function get_properties_by_status($type, $properties) {
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
    
    /**
     * Récupérer l'évolution des ventes par mois pour une agence
     */
    private function get_sales_evolution($agency_id) {
        try {
            // Récupérer les agents de l'agence
            $agents = $this->get_filtered_agents_from_view($agency_id);
            $agent_ids = array_column($agents, 'ID');
            
            if (empty($agent_ids)) {
                return $this->get_sample_sales_data();
            }
            
            // Récupérer les vraies transactions de vente des 6 derniers mois depuis crm_transactions
            $sales_data = [];
            for ($i = 5; $i >= 0; $i--) {
                $month = date('Y-m', strtotime("-$i months"));
                $month_start = $month . '-01';
                $month_end = date('Y-m-t', strtotime($month_start));
                
                // Utiliser la vraie table crm_transactions
                $agent_ids_str = implode(',', array_map('intval', $agent_ids));
                $sql = "SELECT COUNT(*) as count, SUM(montant) as total_amount 
                       FROM crm_transactions 
                       WHERE agent_id IN ($agent_ids_str) 
                       AND type = 'vente'
                       AND DATE(created_at) BETWEEN ? AND ?
                       AND statut IN ('actif', 'cloture')";
                
                $result = $this->db->query($sql, [$month_start, $month_end]);
                $row = $result ? $result->row() : null;
                
                $sales_data[] = [
                    'month' => date('M Y', strtotime($month_start)),
                    'count' => $row ? (int)$row->count : 0,
                    'amount' => $row ? (float)$row->total_amount : 0
                ];
            }
            
            // Si aucune donnée dans crm_transactions, essayer agent_commissions
            $has_data = array_sum(array_column($sales_data, 'count')) > 0;
            if (!$has_data) {
                for ($i = 5; $i >= 0; $i--) {
                    $month = date('Y-m', strtotime("-$i months"));
                    $month_start = $month . '-01';
                    $month_end = date('Y-m-t', strtotime($month_start));
                    
                    $sql = "SELECT COUNT(*) as count, SUM(base_amount) as total_amount 
                           FROM agent_commissions 
                           WHERE agent_id IN ($agent_ids_str) 
                           AND transaction_type = 'sale'
                           AND DATE(created_at) BETWEEN ? AND ?
                           AND status != 'cancelled'";
                    
                    $result = $this->db->query($sql, [$month_start, $month_end]);
                    $row = $result ? $result->row() : null;
                    
                    $sales_data[$i] = [
                        'month' => date('M Y', strtotime($month_start)),
                        'count' => $row ? (int)$row->count : 0,
                        'amount' => $row ? (float)$row->total_amount : 0
                    ];
                }
            }
            
            return $sales_data;
            
        } catch (Exception $e) {
            return $this->get_sample_sales_data();
        }
    }

    /**
     * Récupérer l'évolution des locations par mois pour une agence
     */
    private function get_rentals_evolution($agency_id) {
        try {
            // Récupérer les agents de l'agence
            $agents = $this->get_filtered_agents_from_view($agency_id);
            $agent_ids = array_column($agents, 'ID');
            
            if (empty($agent_ids)) {
                return $this->get_sample_rentals_data();
            }
            
            // Récupérer les vraies transactions de location des 6 derniers mois depuis crm_transactions
            $rentals_data = [];
            for ($i = 5; $i >= 0; $i--) {
                $month = date('Y-m', strtotime("-$i months"));
                $month_start = $month . '-01';
                $month_end = date('Y-m-t', strtotime($month_start));
                
                // Utiliser la vraie table crm_transactions
                $agent_ids_str = implode(',', array_map('intval', $agent_ids));
                $sql = "SELECT COUNT(*) as count, SUM(montant) as total_amount 
                       FROM crm_transactions 
                       WHERE agent_id IN ($agent_ids_str) 
                       AND type = 'location'
                       AND DATE(created_at) BETWEEN ? AND ?
                       AND statut IN ('actif', 'cloture')";
                
                $result = $this->db->query($sql, [$month_start, $month_end]);
                $row = $result ? $result->row() : null;
                
                $rentals_data[] = [
                    'month' => date('M Y', strtotime($month_start)),
                    'count' => $row ? (int)$row->count : 0,
                    'amount' => $row ? (float)$row->total_amount : 0
                ];
            }
            
            // Si aucune donnée dans crm_transactions, essayer agent_commissions
            $has_data = array_sum(array_column($rentals_data, 'count')) > 0;
            if (!$has_data) {
                for ($i = 5; $i >= 0; $i--) {
                    $month = date('Y-m', strtotime("-$i months"));
                    $month_start = $month . '-01';
                    $month_end = date('Y-m-t', strtotime($month_start));
                    
                    $sql = "SELECT COUNT(*) as count, SUM(base_amount) as total_amount 
                           FROM agent_commissions 
                           WHERE agent_id IN ($agent_ids_str) 
                           AND transaction_type = 'rental'
                           AND DATE(created_at) BETWEEN ? AND ?
                           AND status != 'cancelled'";
                    
                    $result = $this->db->query($sql, [$month_start, $month_end]);
                    $row = $result ? $result->row() : null;
                    
                    $rentals_data[$i] = [
                        'month' => date('M Y', strtotime($month_start)),
                        'count' => $row ? (int)$row->count : 0,
                        'amount' => $row ? (float)$row->total_amount : 0
                    ];
                }
            }
            
            return $rentals_data;
            
        } catch (Exception $e) {
            return $this->get_sample_rentals_data();
        }
    }

    /**
     * Récupérer les objectifs avec progression pour une agence
     */
    private function get_objectives_with_progress($agency_id) {
        try {
            // Récupérer les agents de l'agence
            $agents = $this->get_filtered_agents_from_view($agency_id);
            $agent_ids = array_column($agents, 'ID');
            
            if (empty($agent_ids)) {
                return $this->get_sample_objectives_data();
            }
            
            // Récupérer les objectifs du mois courant depuis la vraie table monthly_objectives
            $current_month = date('Y-m');
            $objectives_data = [];
            
            foreach ($agent_ids as $agent_id) {
                // Récupérer les objectifs depuis monthly_objectives
                $sql_objectives = "SELECT * FROM monthly_objectives 
                                  WHERE agent_id = ? AND month = ?";
                $objectives_result = $this->db->query($sql_objectives, [$agent_id, $current_month . '-01']);
                
                if ($objectives_result && $objectives_result->num_rows() > 0) {
                    $objective = $objectives_result->row();
                    
                    // Calculer les performances réelles pour ce mois
                    $month_start = $current_month . '-01';
                    $month_end = date('Y-m-t', strtotime($month_start));
                    
                    // 1. Estimations (depuis crm_properties)
                    $sql_estimations = "SELECT COUNT(*) as count FROM crm_properties 
                                       WHERE agent_id = ? AND DATE(created_at) BETWEEN ? AND ?";
                    $estimations_result = $this->db->query($sql_estimations, [$agent_id, $month_start, $month_end]);
                    $estimations_count = $estimations_result ? $estimations_result->row()->count : 0;
                    
                    // 2. Transactions (depuis crm_transactions)
                    $sql_transactions = "SELECT COUNT(*) as count FROM crm_transactions 
                                        WHERE agent_id = ? AND DATE(created_at) BETWEEN ? AND ? 
                                        AND statut IN ('actif', 'cloture')";
                    $transactions_result = $this->db->query($sql_transactions, [$agent_id, $month_start, $month_end]);
                    $transactions_count = $transactions_result ? $transactions_result->row()->count : 0;
                    
                    // 3. Chiffre d'affaires (depuis crm_transactions)
                    $sql_revenue = "SELECT SUM(montant) as revenue FROM crm_transactions 
                                   WHERE agent_id = ? AND DATE(created_at) BETWEEN ? AND ? 
                                   AND statut = 'cloture'";
                    $revenue_result = $this->db->query($sql_revenue, [$agent_id, $month_start, $month_end]);
                    $revenue_amount = $revenue_result ? ($revenue_result->row()->revenue ?? 0) : 0;
                    
                    // Si pas de données dans crm_transactions, essayer agent_commissions
                    if ($transactions_count == 0 && $revenue_amount == 0) {
                        $sql_commissions = "SELECT COUNT(*) as count, SUM(base_amount) as revenue 
                                           FROM agent_commissions 
                                           WHERE agent_id = ? AND DATE(created_at) BETWEEN ? AND ? 
                                           AND status != 'cancelled'";
                        $commissions_result = $this->db->query($sql_commissions, [$agent_id, $month_start, $month_end]);
                        if ($commissions_result) {
                            $commission_row = $commissions_result->row();
                            $transactions_count = $commission_row->count ?? 0;
                            $revenue_amount = $commission_row->revenue ?? 0;
                        }
                    }
                    
                    $objectives_data[] = [
                        'agent_id' => $agent_id,
                        'agent_name' => $this->get_agent_name($agent_id),
                        'estimations_target' => $objective->estimations_target,
                        'estimations_actual' => $estimations_count,
                        'estimations_progress' => $objective->estimations_target > 0 ? 
                            round(($estimations_count / $objective->estimations_target) * 100, 1) : 0,
                        'transactions_target' => $objective->transactions_target,
                        'transactions_actual' => $transactions_count,
                        'transactions_progress' => $objective->transactions_target > 0 ? 
                            round(($transactions_count / $objective->transactions_target) * 100, 1) : 0,
                        'revenue_target' => $objective->revenue_target,
                        'revenue_actual' => $revenue_amount,
                        'revenue_progress' => $objective->revenue_target > 0 ? 
                            round(($revenue_amount / $objective->revenue_target) * 100, 1) : 0
                    ];
                }
            }
            
            return !empty($objectives_data) ? $objectives_data : $this->get_sample_objectives_data();
            
        } catch (Exception $e) {
            return $this->get_sample_objectives_data();
        }
    }

    /**
     * Obtenir le nom d'un agent depuis WordPress
     */
    private function get_agent_name($agent_id) {
        try {
            $wp_db = $this->load->database('wordpress', TRUE);
            $query = "SELECT display_name FROM wp_Hrg8P_users WHERE ID = ? LIMIT 1";
            $result = $wp_db->query($query, [$agent_id]);
            
            if ($result && $result->num_rows() > 0) {
                return $result->row()->display_name;
            }
            
            return "Agent #$agent_id";
        } catch (Exception $e) {
            return "Agent #$agent_id";
        }
    }

    /**
     * Données d'exemple pour les ventes
     */
    private function get_sample_sales_data() {
        return [
            ['month' => 'Jul 2024', 'count' => 8, 'amount' => 450000],
            ['month' => 'Aug 2024', 'count' => 12, 'amount' => 680000],
            ['month' => 'Sep 2024', 'count' => 15, 'amount' => 750000],
            ['month' => 'Oct 2024', 'count' => 10, 'amount' => 520000],
            ['month' => 'Nov 2024', 'count' => 18, 'amount' => 890000],
            ['month' => 'Dec 2024', 'count' => 22, 'amount' => 1200000]
        ];
    }

    /**
     * Données d'exemple pour les locations
     */
    private function get_sample_rentals_data() {
        return [
            ['month' => 'Jul 2024', 'count' => 25, 'amount' => 85000],
            ['month' => 'Aug 2024', 'count' => 30, 'amount' => 95000],
            ['month' => 'Sep 2024', 'count' => 28, 'amount' => 90000],
            ['month' => 'Oct 2024', 'count' => 35, 'amount' => 110000],
            ['month' => 'Nov 2024', 'count' => 40, 'amount' => 125000],
            ['month' => 'Dec 2024', 'count' => 45, 'amount' => 140000]
        ];
    }

    /**
     * Données d'exemple pour les objectifs
     */
    private function get_sample_objectives_data() {
        return [
            [
                'agent_id' => 1,
                'agent_name' => 'Ahmed Ben Ali',
                'estimations_target' => 25,
                'estimations_actual' => 28,
                'estimations_progress' => 112.0,
                'transactions_target' => 5,
                'transactions_actual' => 6,
                'transactions_progress' => 120.0,
                'revenue_target' => 150000,
                'revenue_actual' => 175000,
                'revenue_progress' => 116.7
            ],
            [
                'agent_id' => 2,
                'agent_name' => 'Fatima Gharbi',
                'estimations_target' => 20,
                'estimations_actual' => 16,
                'estimations_progress' => 80.0,
                'transactions_target' => 4,
                'transactions_actual' => 3,
                'transactions_progress' => 75.0,
                'revenue_target' => 120000,
                'revenue_actual' => 95000,
                'revenue_progress' => 79.2
            ],
            [
                'agent_id' => 3,
                'agent_name' => 'Mohamed Khelifi',
                'estimations_target' => 30,
                'estimations_actual' => 32,
                'estimations_progress' => 106.7,
                'transactions_target' => 6,
                'transactions_actual' => 7,
                'transactions_progress' => 116.7,
                'revenue_target' => 180000,
                'revenue_actual' => 200000,
                'revenue_progress' => 111.1
            ]
        ];
    }
    
    // ============================================================================
    // NOUVELLES MÉTHODES HELPER POUR LE DASHBOARD MANAGER RÉORGANISÉ
    // ============================================================================
    
    /**
     * Calcul du score de performance d'un agent
     */
    private function calculate_agent_performance_score($agent_id) {
        try {
            // Calcul basé sur objectifs atteints, ventes, estimations
            $objectives_score = $this->get_agent_objectives_completion($agent_id);
            $sales_score = min(100, ($this->get_agent_sales_count($agent_id) / 5) * 100); // Base sur 5 ventes/mois
            $activity_score = min(100, ($this->get_agent_activities_count($agent_id) / 20) * 100); // Base sur 20 activités/mois
            
            return round(($objectives_score + $sales_score + $activity_score) / 3, 1);
        } catch (Exception $e) {
            return 75; // Score par défaut
        }
    }
    
    /**
     * Nombre de propriétés d'un agent
     */
    private function get_agent_properties_count($agent_id) {
        try {
            $wp_db = $this->load->database('wordpress', TRUE);
            $query = "SELECT COUNT(*) as count FROM wp_Hrg8P_posts 
                     WHERE post_author = ? AND post_type = 'property' AND post_status = 'publish'";
            $result = $wp_db->query($query, [$agent_id]);
            
            return $result ? $result->row()->count : 0;
        } catch (Exception $e) {
            return rand(3, 15); // Données d'exemple
        }
    }
    
    /**
     * Nombre de ventes d'un agent
     */
    private function get_agent_sales_count($agent_id) {
        try {
            $query = "SELECT COUNT(*) as count FROM crm_transactions 
                     WHERE agent_id = ? AND type = 'sale' 
                     AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
            $result = $this->db->query($query, [$agent_id]);
            
            return $result ? $result->row()->count : 0;
        } catch (Exception $e) {
            return rand(2, 8); // Données d'exemple
        }
    }
    
    /**
     * Revenus générés par un agent
     */
    private function get_agent_revenue($agent_id) {
        try {
            $query = "SELECT SUM(montant) as total FROM crm_transactions 
                     WHERE agent_id = ? AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
            $result = $this->db->query($query, [$agent_id]);
            
            return $result ? ($result->row()->total ?? 0) : 0;
        } catch (Exception $e) {
            return rand(50000, 200000); // Données d'exemple
        }
    }
    
    /**
     * Nombre d'agents actifs dans l'agence
     */
    private function get_agency_active_agents_count($agency_id) {
        $agents = $this->get_filtered_agents_from_view($agency_id);
        return count($agents);
    }
    
    /**
     * Taux de completion des objectifs
     */
    private function get_objectives_completion_rate($agency_id) {
        try {
            // Calculer le taux moyen de completion des objectifs de l'agence
            $objectives = $this->get_objectives_with_progress($agency_id);
            if (empty($objectives)) return 85; // Valeur par défaut
            
            $total_progress = 0;
            $count = 0;
            
            foreach ($objectives as $obj) {
                $total_progress += ($obj['transactions_progress'] + $obj['revenue_progress']) / 2;
                $count++;
            }
            
            return $count > 0 ? round($total_progress / $count, 1) : 85;
        } catch (Exception $e) {
            return 85; // Valeur par défaut
        }
    }
    
    /**
     * Taux de croissance mensuel
     */
    private function get_monthly_growth_rate($agency_id) {
        try {
            $current_month = $this->get_agency_monthly_revenue($agency_id);
            $previous_month = $this->get_agency_previous_month_revenue($agency_id);
            
            if ($previous_month > 0) {
                return round((($current_month - $previous_month) / $previous_month) * 100, 1);
            }
            
            return 12.5; // Valeur par défaut
        } catch (Exception $e) {
            return 12.5; // Valeur par défaut
        }
    }
    
    /**
     * Revenus du mois précédent
     */
    private function get_agency_previous_month_revenue($agency_id) {
        try {
            $query = "SELECT SUM(montant) as total FROM crm_transactions ct
                     JOIN wp_Hrg8P_crm_agents ca ON ct.agent_id = ca.ID
                     WHERE ca.agency_id = ? 
                     AND ct.created_at >= DATE_SUB(DATE_SUB(NOW(), INTERVAL 1 MONTH), INTERVAL 1 MONTH)
                     AND ct.created_at < DATE_SUB(NOW(), INTERVAL 1 MONTH)";
            $result = $this->db->query($query, [$agency_id]);
            
            return $result ? ($result->row()->total ?? 0) : 0;
        } catch (Exception $e) {
            return 450000; // Valeur d'exemple
        }
    }
    
    /**
     * Trends mensuels des transactions
     */
    private function get_monthly_trends($agency_id) {
        return [
            'sales_trend' => 'up',
            'rentals_trend' => 'up',
            'revenue_trend' => 'up',
            'volume_change' => '+12%'
        ];
    }
    
    /**
     * Nombre de prospects
     */
    private function get_prospects_count($agency_id) {
        return rand(15, 35); // Données d'exemple
    }
    
    /**
     * Rendez-vous programmés
     */
    private function get_scheduled_appointments($agency_id) {
        return rand(8, 20); // Données d'exemple
    }
    
    /**
     * Négociations actives
     */
    private function get_active_negotiations($agency_id) {
        return rand(5, 12); // Données d'exemple
    }
    
    /**
     * Top performers avec données enrichies
     */
    private function get_top_performing_agents_enhanced($agency_id) {
        $agents = $this->get_filtered_agents_from_view($agency_id);
        
        // Enrichir avec des scores de performance
        foreach ($agents as &$agent) {
            $agent->sales_count = $this->get_agent_sales_count($agent->ID ?? 0);
            $agent->revenue = $this->get_agent_revenue($agent->ID ?? 0);
            $agent->performance_score = $this->calculate_agent_performance_score($agent->ID ?? 0);
        }
        
        // Trier par performance
        usort($agents, function($a, $b) {
            return ($b->performance_score ?? 0) <=> ($a->performance_score ?? 0);
        });
        
        return array_slice($agents, 0, 5);
    }
    
    /**
     * Score moyen de l'équipe
     */
    private function calculate_team_average_performance($agents) {
        if (empty($agents)) return 0;
        
        $total = 0;
        foreach ($agents as $agent) {
            $total += ($agent->performance_score ?? 0);
        }
        
        return round($total / count($agents), 1);
    }
    
    /**
     * ID du meilleur performer
     */
    private function get_best_performer_id($agents) {
        if (empty($agents)) return null;
        
        $best = null;
        $best_score = 0;
        
        foreach ($agents as $agent) {
            if (($agent->performance_score ?? 0) > $best_score) {
                $best_score = $agent->performance_score ?? 0;
                $best = $agent->ID ?? null;
            }
        }
        
        return $best;
    }
    
    /**
     * Agents nécessitant une amélioration
     */
    private function get_agents_needing_improvement($agents) {
        $threshold = 60; // Seuil de performance
        $needing_improvement = [];
        
        foreach ($agents as $agent) {
            if (($agent->performance_score ?? 0) < $threshold) {
                $needing_improvement[] = $agent;
            }
        }
        
        return $needing_improvement;
    }
    
    /**
     * Follow-ups en retard
     */
    private function get_overdue_follow_ups($agency_id) {
        return rand(3, 8); // Données d'exemple
    }
    
    /**
     * Agents sous-performants
     */
    private function get_underperforming_agents($agency_id) {
        $agents = $this->get_filtered_agents_from_view($agency_id);
        $underperforming = [];
        
        foreach ($agents as $agent) {
            $score = $this->calculate_agent_performance_score($agent->ID ?? 0);
            if ($score < 60) {
                $underperforming[] = $agent;
            }
        }
        
        return $underperforming;
    }
    
    /**
     * Objectifs à risque
     */
    private function get_at_risk_objectives($agency_id) {
        return rand(2, 5); // Données d'exemple
    }
    
    /**
     * Recommandations pour le manager
     */
    private function get_manager_recommendations($agency_id) {
        return [
            'high_priority' => [
                'Réviser les objectifs Q4 avec l\'équipe',
                'Organiser formation vente pour agents sous 60%'
            ],
            'medium_priority' => [
                'Analyser pipeline prospects',
                'Programmer réunion équipe hebdomadaire'
            ],
            'low_priority' => [
                'Mettre à jour avatars agents',
                'Optimiser processus estimation'
            ]
        ];
    }
    
    /**
     * Labels des 6 derniers mois
     */
    private function get_last_6_months_labels() {
        $labels = [];
        for ($i = 5; $i >= 0; $i--) {
            $labels[] = date('M Y', strtotime("-$i months"));
        }
        return $labels;
    }
    
    /**
     * Données mensuelles de ventes
     */
    private function get_monthly_sales_data($agency_id) {
        return [15, 18, 22, 19, 25, 28]; // Données d'exemple pour 6 mois
    }
    
    /**
     * Données mensuelles de locations
     */
    private function get_monthly_rentals_data($agency_id) {
        return [35, 42, 38, 45, 48, 52]; // Données d'exemple pour 6 mois
    }
    
    /**
     * Données pour graphique de performance des agents
     */
    private function get_agents_performance_chart_data($agency_id) {
        $agents = $this->get_filtered_agents_from_view($agency_id);
        $data = [];
        
        foreach ($agents as $agent) {
            $data[] = [
                'name' => $agent->display_name ?? 'Agent',
                'score' => $this->calculate_agent_performance_score($agent->ID ?? 0)
            ];
        }
        
        return $data;
    }
    
    /**
     * Données d'évolution des revenus
     */
    private function get_revenue_evolution_data($agency_id) {
        return [
            'labels' => $this->get_last_6_months_labels(),
            'data' => [450000, 520000, 480000, 610000, 680000, 750000] // Données d'exemple
        ];
    }
    
    /**
     * Completion des objectifs d'un agent
     */
    private function get_agent_objectives_completion($agent_id) {
        try {
            // Logique pour calculer la completion des objectifs
            return rand(60, 100); // Données d'exemple
        } catch (Exception $e) {
            return 75;
        }
    }
    
    /**
     * Nombre d'activités d'un agent
     */
    private function get_agent_activities_count($agent_id) {
        try {
            // Compter les activités récentes de l'agent
            return rand(10, 30); // Données d'exemple
        } catch (Exception $e) {
            return 15;
        }
    }
    
}
