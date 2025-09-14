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
        $this->load->model('Property_model','property_model');
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
        
        // Statistiques réelles avec la nouvelle vue
        try {
            // Données de base
            $agencies = $this->agency_model->get_all_agencies();
            $agents = $this->agent_model->get_all_agents_from_posts();
            $properties = $this->property_model->get_from_property_agent_view();
            
            // Calcul du chiffre d'affaires réel basé sur les prix des propriétés
            $total_revenue = 0;
            $monthly_revenue = 0;
            $properties_for_sale = 0;
            $properties_for_rent = 0;
            $current_month = date('Y-m');
            
            foreach ($properties as $property) {
                // Calcul du revenu basé sur les prix réels
                $price = floatval($property->property_price ?? 0);
                if ($price > 0) {
                    // Commission estimée à 3% pour les ventes
                    $commission = $price * 0.03;
                    $total_revenue += $commission;
                    
                    // Revenu du mois actuel
                    $property_month = date('Y-m', strtotime($property->property_date ?? 'now'));
                    if ($property_month === $current_month) {
                        $monthly_revenue += $commission;
                    }
                }
                
                // Comptage par type
                $status = $property->property_status ?? '';
                if (strpos($status, 'sale') !== false || strpos($status, 'vente') !== false) {
                    $properties_for_sale++;
                } elseif (strpos($status, 'rent') !== false || strpos($status, 'location') !== false) {
                    $properties_for_rent++;
                }
            }
            
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
            
            // Calcul de la croissance (comparaison avec le mois dernier)
            $current_month_properties = 0;
            $last_month_properties = 0;
            $last_month = date('Y-m', strtotime('-1 month'));
            
            foreach ($properties as $property) {
                $property_month = date('Y-m', strtotime($property->property_date ?? 'now'));
                if ($property_month === $current_month) {
                    $current_month_properties++;
                } elseif ($property_month === $last_month) {
                    $last_month_properties++;
                }
            }
            
            $growth = $last_month_properties > 0 ? 
                round((($current_month_properties - $last_month_properties) / $last_month_properties) * 100, 1) : 
                ($current_month_properties > 0 ? 100 : 0);
            
            // Calcul des top performers réels
            $top_performers_count = 0;
            $agent_performance = [];
            foreach ($properties as $property) {
                $agent_id = $property->agent_id;
                if ($agent_id) {
                    if (!isset($agent_performance[$agent_id])) {
                        $agent_performance[$agent_id] = 0;
                    }
                    $agent_performance[$agent_id]++;
                }
            }
            arsort($agent_performance);
            $top_performers_count = min(15, count(array_filter($agent_performance, function($count) { return $count >= 3; })));
            
            $data['stats'] = [
                'agencies' => count($agencies),
                'agents' => count($agents),
                'properties' => count($properties),
                'active_agents' => count($active_agents),
                'active_agencies' => count($active_agencies),
                'properties_with_agents' => $properties_with_agents,
                'properties_with_agencies' => $properties_with_agencies,
                'revenue' => $total_revenue, // Utilisation du revenu réel calculé
                'growth' => $growth,
                'current_month_properties' => $current_month_properties,
                'last_month_properties' => $last_month_properties,
                'properties_for_sale' => $properties_for_sale,
                'properties_for_rent' => $properties_for_rent,
                'top_performers' => $top_performers_count
            ];
            
            // Données pour les graphiques réelles
            $data['chart_data'] = $this->get_real_chart_data($properties);
            
            // Activités récentes (basées sur les propriétés réelles)
            $data['recent_activities'] = $this->get_recent_activities($properties);
            
            // Top performers
            $data['top_agents'] = $this->get_top_agents_performance($properties);
            $data['top_agencies'] = $this->get_top_agencies_performance($properties);
            
            // Ajout des données premium pour le nouveau design
            $data['stats']['total_revenue'] = $total_revenue;
            $data['stats']['monthly_revenue'] = $monthly_revenue;
            $data['stats']['yearly_revenue'] = $total_revenue; // Pour simplifier, on garde le total
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
        // 1. Revenus mensuels basés sur les prix réels des propriétés
        $months = [];
        
        // Préparer les 12 derniers mois
        for ($i = 11; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $months[$month] = [
                'month' => date('M Y', strtotime($month)),
                'revenue' => 0,
                'count' => 0
            ];
        }
        
        // Calculer les revenus réels par mois
        foreach ($properties as $property) {
            $property_month = date('Y-m', strtotime($property->property_date ?? 'now'));
            if (isset($months[$property_month])) {
                $months[$property_month]['count']++;
                
                // Calcul du revenu basé sur le prix réel (commission 3%)
                $price = floatval($property->property_price ?? 0);
                if ($price > 0) {
                    $commission = $price * 0.03;
                    $months[$property_month]['revenue'] += $commission;
                }
            }
        }
        
        $monthly_revenues = array_column(array_values($months), 'revenue');
        
        // 2. Répartition des propriétés par type réel
        $property_types = [
            'Appartements' => 0,
            'Maisons' => 0,
            'Commerces' => 0,
            'Terrains' => 0,
            'Autres' => 0
        ];
        
        foreach ($properties as $property) {
            $type = strtolower($property->property_type ?? '');
            
            if (strpos($type, 'apartment') !== false || strpos($type, 'appartement') !== false) {
                $property_types['Appartements']++;
            } elseif (strpos($type, 'house') !== false || strpos($type, 'maison') !== false || strpos($type, 'villa') !== false) {
                $property_types['Maisons']++;
            } elseif (strpos($type, 'commercial') !== false || strpos($type, 'office') !== false || strpos($type, 'shop') !== false) {
                $property_types['Commerces']++;
            } elseif (strpos($type, 'land') !== false || strpos($type, 'terrain') !== false) {
                $property_types['Terrains']++;
            } else {
                $property_types['Autres']++;
            }
        }
        
        // Filtrer les types avec 0 propriétés
        $property_types = array_filter($property_types, function($count) { return $count > 0; });
        
        // 3. Répartition des propriétés par agent (top 10)
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
                    'properties_count' => $properties_count
                ];
                $count++;
            }
        }
        
        return [
            'revenues' => $monthly_revenues,
            'monthly_sales' => array_column(array_values($months), 'count'),
            'properties_by_type' => $property_types,
            'top_agents' => $top_agents_data
        ];
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

    // Vue Agent : données d'un agent
    public function agent($agent_id) {
        $this->isLoggedIn();
        $data['agent'] = $this->agent_model->get_agent($agent_id);
        $data['stats'] = $this->activity_model->get_agent_stats($agent_id);
        $this->loadViews('dashboard/agent', $this->global, $data, NULL);
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
}
