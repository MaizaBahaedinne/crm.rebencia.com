<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Objective_model extends CI_Model 
{
    protected $wp_db;
    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        // Charger la base WordPress séparément
        $this->wp_db = $this->load->database('wordpress', TRUE);
    }

    /**
     * Créer ou mettre à jour les objectifs d'un agent pour un mois
     */
    public function set_monthly_objectives($agent_id, $month, $objectives, $created_by) {
        // Validation des paramètres obligatoires
        if (empty($created_by)) {
            throw new Exception('created_by ne peut pas être vide');
        }
        
        $data = [
            'agent_id' => $agent_id,
            'month' => $month . '-01', // Premier jour du mois
            'estimations_target' => $objectives['estimations_target'] ?? 0,
            'contacts_target' => $objectives['contacts_target'] ?? 0,
            'transactions_target' => $objectives['transactions_target'] ?? 0,
            'revenue_target' => $objectives['revenue_target'] ?? 0,
            'created_by' => (int)$created_by, // S'assurer que c'est un entier
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Vérifier si des objectifs existent déjà
        $existing = $this->db->where('agent_id', $agent_id)
                            ->where('month', $data['month'])
                            ->get('monthly_objectives')
                            ->row();

        if ($existing) {
            unset($data['created_by']); // Ne pas modifier le créateur
            return $this->db->where('id', $existing->id)
                           ->update('monthly_objectives', $data);
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            return $this->db->insert('monthly_objectives', $data);
        }
    }

    /**
     * Récupérer les objectifs d'un agent
     */
    public function get_agent_objectives($agent_id, $month = null) {
        // Récupérer les objectifs depuis la base CRM
        $this->db->select('*')
                 ->from('monthly_objectives')
                 ->where('agent_id', $agent_id);

        if ($month) {
            $this->db->where('month', $month . '-01');
        }

        $objectives = $this->db->order_by('month', 'DESC')->get()->result();
        
        // Enrichir avec les noms depuis WordPress si nécessaire
        if (!empty($objectives)) {
            $user_ids = array_unique(array_merge(
                array_column($objectives, 'agent_id'),
                array_column($objectives, 'created_by')
            ));
            
            if (!empty($user_ids)) {
                $user_ids_str = implode(',', $user_ids);
                $query = "
                    SELECT ID, display_name
                    FROM rebencia_RebenciaBD.wp_Hrg8P_users
                    WHERE ID IN ({$user_ids_str})
                ";
                $users = $this->wp_db->query($query)->result();
                
                $user_names = [];
                foreach ($users as $user) {
                    $user_names[$user->ID] = $user->display_name;
                }
                
                foreach ($objectives as $objective) {
                    $objective->agent_name = $user_names[$objective->agent_id] ?? 'Agent #' . $objective->agent_id;
                    $objective->created_by_name = $user_names[$objective->created_by] ?? 'Utilisateur #' . $objective->created_by;
                }
            }
        }
        
        return $objectives;
    }

    /**
     * Récupérer tous les objectifs pour un mois donné
     */
    public function get_monthly_objectives($month) {
        // D'abord récupérer les objectifs depuis la base CRM
        $objectives = $this->db->select('*')
                              ->from('monthly_objectives')
                              ->where('month', $month . '-01')
                              ->get()
                              ->result();
        
        // Ensuite enrichir avec les noms des agents depuis WordPress
        if (!empty($objectives)) {
            $agent_ids = array_column($objectives, 'agent_id');
            
            if (!empty($agent_ids)) {
                // Utiliser une requête SQL directe pour forcer la bonne base
                $agent_ids_str = implode(',', $agent_ids);
                $query = "
                    SELECT ID, display_name
                    FROM rebencia_RebenciaBD.wp_Hrg8P_users
                    WHERE ID IN ({$agent_ids_str})
                ";
                $agents = $this->wp_db->query($query)->result();
                
                // Créer un mapping agent_id => display_name
                $agent_names = [];
                foreach ($agents as $agent) {
                    $agent_names[$agent->ID] = $agent->display_name;
                }
                
                // Enrichir les objectifs avec les noms
                foreach ($objectives as $objective) {
                    $objective->agent_name = $agent_names[$objective->agent_id] ?? 'Agent #' . $objective->agent_id;
                }
                
                // Trier par nom d'agent
                usort($objectives, function($a, $b) {
                    return strcmp($a->agent_name, $b->agent_name);
                });
            }
        }
        
        return $objectives;
    }

    /**
     * Mettre à jour les performances réelles d'un agent
     * Version améliorée qui calcule automatiquement les vraies données
     */
    public function update_agent_performance($agent_id, $month, $performance_data = null) {
        // Si aucune donnée fournie, calculer les vraies performances
        if ($performance_data === null) {
            $performance_data = $this->calculate_real_performance($agent_id, $month);
        }
        
        $data = [
            'agent_id' => $agent_id,
            'month' => $month . '-01',
            'estimations_count' => $performance_data['estimations_count'] ?? 0,
            'contacts_count' => $performance_data['contacts_count'] ?? 0,
            'transactions_count' => $performance_data['transactions_count'] ?? 0,
            'revenue_amount' => $performance_data['revenue_amount'] ?? 0,
            'commission_earned' => $performance_data['commission_earned'] ?? 0,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Vérifier si une entrée existe déjà
        $existing = $this->db->where('agent_id', $agent_id)
                            ->where('month', $data['month'])
                            ->get('agent_performance')
                            ->row();

        if ($existing) {
            return $this->db->where('id', $existing->id)
                           ->update('agent_performance', $data);
        } else {
            return $this->db->insert('agent_performance', $data);
        }
    }

    /**
     * Récupérer les performances d'un agent
     */
    public function get_agent_performance($agent_id, $month = null) {
        $this->db->where('agent_id', $agent_id);
        
        if ($month) {
            $this->db->where('month', $month . '-01');
        }

        return $this->db->order_by('month', 'DESC')
                       ->get('agent_performance')
                       ->result();
    }

    /**
     * Calculer automatiquement les performances depuis les données existantes
     */
    public function calculate_performance($agent_id, $month) {
        $month_start = $month . '-01';
        $month_end = $month . '-31';

        // Compter les estimations (depuis la table estimations si elle existe)
        $estimations = $this->db->where('agent_id', $agent_id)
                               ->where('DATE(created_at) >=', $month_start)
                               ->where('DATE(created_at) <=', $month_end)
                               ->count_all_results('estimations');

        // Compter les contacts (depuis la table leads/clients)
        $contacts = $this->db->where('agent_id', $agent_id)
                            ->where('DATE(created_at) >=', $month_start)
                            ->where('DATE(created_at) <=', $month_end)
                            ->count_all_results('leads');

        // Compter les transactions
        $transactions = $this->db->where('agent_id', $agent_id)
                                ->where('DATE(created_at) >=', $month_start)
                                ->where('DATE(created_at) <=', $month_end)
                                ->count_all_results('transactions');

        // Calculer le CA et commissions
        $revenue_query = $this->db->select('SUM(base_amount) as revenue, SUM(agent_commission) as commission')
                                 ->where('agent_id', $agent_id)
                                 ->where('DATE(created_at) >=', $month_start)
                                 ->where('DATE(created_at) <=', $month_end)
                                 ->get('agent_commissions')
                                 ->row();

        $performance_data = [
            'estimations_count' => $estimations,
            'contacts_count' => $contacts,
            'transactions_count' => $transactions,
            'revenue_amount' => $revenue_query->revenue ?? 0,
            'commission_earned' => $revenue_query->commission ?? 0
        ];

        // Sauvegarder les performances calculées
        $this->update_agent_performance($agent_id, $month, $performance_data);

        return $performance_data;
    }

    /**
     * Récupérer le tableau de bord des objectifs vs performances
     */
    public function get_objectives_dashboard($month = null, $agency_id = null ) {
        if (!$month) {
            $month = date('Y-m');
        }

        // Version avec filtre sur l'agence si agency_id est fourni
        if ($agency_id) {
            // Récupérer les IDs des agents de l'agence
            $agents = $this->get_agents_by_agency($agency_id);
            $agent_ids = array_column($agents, 'ID');
            if (empty($agent_ids)) {
            $objectives = [];
            } else {
            $this->db->select('*')
                 ->from('monthly_objectives')
                 ->where('month', $month . '-01')
                 ->where_in('agent_id', $agent_ids);
            $objectives = $this->db->get()->result();
            }
        } else {
            // Tous les objectifs du mois
            $objectives = $this->db->select('*')
                    ->from('monthly_objectives')
                    ->where('month', $month . '-01')
                    ->get()
                    ->result();
        }



        $result = array();
        
        foreach ($objectives as $objective) {
            // Récupérer les infos de l'agent depuis WordPress
            $query = "
                SELECT ID, display_name
                FROM rebencia_RebenciaBD.wp_Hrg8P_users
                WHERE ID = {$objective->agent_id}
                LIMIT 1
            ";
            $agent = $this->wp_db->query($query)->row();

            // Calculer les vraies performances depuis les données réelles
            $real_performance = $this->calculate_real_performance($objective->agent_id, $month);

            // Récupérer les performances depuis la base CRM (fallback)
            $performance = $this->db->select('*')
                                   ->from('agent_performance')
                                   ->where('agent_id', $objective->agent_id)
                                   ->where('month', $month . '-01')
                                   ->get()
                                   ->row();

            // Utiliser les vraies performances si disponibles, sinon utiliser les données de la table agent_performance
            $estimations_count = $real_performance['estimations_count'] ?? ($performance->estimations_count ?? 0);
            $contacts_count = $real_performance['contacts_count'] ?? ($performance->contacts_count ?? 0);
            $transactions_count = $real_performance['transactions_count'] ?? ($performance->transactions_count ?? 0);
            $revenue_amount = $real_performance['revenue_amount'] ?? ($performance->revenue_amount ?? 0);
            $commission_earned = $real_performance['commission_earned'] ?? ($performance->commission_earned ?? 0);

            // Calculer les progressions
            $estimations_progress = ($objective->estimations_target > 0) ? 
                round(($estimations_count / $objective->estimations_target) * 100, 2) : 0;
                
            $contacts_progress = ($objective->contacts_target > 0) ? 
                round(($contacts_count / $objective->contacts_target) * 100, 2) : 0;
                
            $transactions_progress = ($objective->transactions_target > 0) ? 
                round(($transactions_count / $objective->transactions_target) * 100, 2) : 0;
                
            $revenue_progress = ($objective->revenue_target > 0) ? 
                round(($revenue_amount / $objective->revenue_target) * 100, 2) : 0;

            // Créer l'objet résultat
            $obj = new stdClass();
            $obj->id = $objective->id;
            $obj->agent_id = $objective->agent_id;
            $obj->month = $objective->month;
            $obj->estimations_target = $objective->estimations_target;
            $obj->contacts_target = $objective->contacts_target;
            $obj->transactions_target = $objective->transactions_target;
            $obj->revenue_target = $objective->revenue_target;
            $obj->agent_name = $agent ? $agent->display_name : 'Agent inconnu';
            $obj->estimations_count = $estimations_count;
            $obj->contacts_count = $contacts_count;
            $obj->transactions_count = $transactions_count;
            $obj->revenue_amount = $revenue_amount;
            $obj->commission_earned = $commission_earned;
            $obj->estimations_progress = $estimations_progress;
            $obj->contacts_progress = $contacts_progress;
            $obj->transactions_progress = $transactions_progress;
            $obj->revenue_progress = $revenue_progress;

            $result[] = $obj;
        }

        // Trier par nom d'agent
        usort($result, function($a, $b) {
            return strcmp($a->agent_name, $b->agent_name);
        });

        return $result;
    }

    /**
     * Calculer les vraies performances d'un agent pour un mois donné
     * En récupérant les données réelles depuis les différentes tables
     */
    private function calculate_real_performance($agent_id, $month) {
        $month_start = $month . '-01';
        $month_end = date('Y-m-t', strtotime($month_start));
        
        $performance = [
            'estimations_count' => 0,
            'contacts_count' => 0,
            'transactions_count' => 0,
            'revenue_amount' => 0,
            'commission_earned' => 0
        ];

        // 1. Compter les estimations (depuis crm_properties)
        $estimations_query = "
            SELECT COUNT(*) as count
            FROM crm_properties 
            WHERE agent_id = ? 
            AND DATE(created_at) BETWEEN ? AND ?
        ";
        $estimations_result = $this->db->query($estimations_query, [$agent_id, $month_start, $month_end]);
        if ($estimations_result && $estimations_result->num_rows() > 0) {
            $performance['estimations_count'] = $estimations_result->row()->count;
        }

        // 2. Compter les contacts (depuis crm_clients)
        $contacts_query = "
            SELECT COUNT(*) as count
            FROM crm_clients 
            WHERE agent_id = ? 
            AND DATE(date_creation) BETWEEN ? AND ?
        ";
        $contacts_result = $this->db->query($contacts_query, [$agent_id, $month_start, $month_end]);
        if ($contacts_result && $contacts_result->num_rows() > 0) {
            $performance['contacts_count'] = $contacts_result->row()->count;
        }

        // 3. Compter les transactions et calculer le CA (depuis agent_commissions)
        $transactions_query = "
            SELECT 
                COUNT(*) as count,
                SUM(base_amount) as total_revenue,
                SUM(agent_commission + agency_commission) as total_commission
            FROM agent_commissions 
            WHERE agent_id = ? 
            AND DATE(created_at) BETWEEN ? AND ?
            AND status != 'cancelled'
        ";
        $transactions_result = $this->db->query($transactions_query, [$agent_id, $month_start, $month_end]);
        if ($transactions_result && $transactions_result->num_rows() > 0) {
            $row = $transactions_result->row();
            $performance['transactions_count'] = $row->count ?? 0;
            $performance['revenue_amount'] = $row->total_revenue ?? 0;
            $performance['commission_earned'] = $row->total_commission ?? 0;
        }

        // 4. Si pas de données dans agent_commissions, essayer depuis crm_transactions
        if ($performance['transactions_count'] == 0) {
            $crm_transactions_query = "
                SELECT 
                    COUNT(*) as count,
                    SUM(CASE 
                        WHEN statut = 'cloture' THEN montant 
                        ELSE 0 
                    END) as total_revenue
                FROM crm_transactions 
                WHERE agent_id = ? 
                AND DATE(created_at) BETWEEN ? AND ?
                AND statut IN ('actif', 'cloture')
            ";
            $crm_result = $this->db->query($crm_transactions_query, [$agent_id, $month_start, $month_end]);
            if ($crm_result && $crm_result->num_rows() > 0) {
                $row = $crm_result->row();
                $performance['transactions_count'] = $row->count ?? 0;
                $performance['revenue_amount'] = $row->total_revenue ?? 0;
                // Calculer une commission estimée (5% du CA)
                $performance['commission_earned'] = ($row->total_revenue ?? 0) * 0.05;
            }
        }

        return $performance;
    }

    /**
     * Statistiques globales des objectifs
     */
    public function get_objectives_stats($month = null, $agency_id = null) {
        if (!$month) {
            $month = date('Y-m');
        }

        $params = [$month . '-01'];
        $agency_filter = '';

        if ($agency_id) {
            // Get agent IDs for the agency
            $agents = $this->get_agents_by_agency($agency_id);
            $agent_ids = array_column($agents, 'ID');
            if (empty($agent_ids)) {
                // No agents, return zeros
                return (object)[
                    'total_agents' => 0,
                    'total_estimations_target' => 0,
                    'total_contacts_target' => 0,
                    'total_transactions_target' => 0,
                    'total_revenue_target' => 0,
                    'total_estimations_actual' => 0,
                    'total_contacts_actual' => 0,
                    'total_transactions_actual' => 0,
                    'total_revenue_actual' => 0,
                    'avg_estimations_progress' => 0,
                    'avg_contacts_progress' => 0,
                    'avg_transactions_progress' => 0,
                    'avg_revenue_progress' => 0
                ];
            }
            $placeholders = implode(',', array_fill(0, count($agent_ids), '?'));
            $agency_filter = " AND mo.agent_id IN ($placeholders)";
            $params = array_merge($params, $agent_ids);
        }

        $sql = "
            SELECT 
                COUNT(mo.id) as total_agents,
                SUM(mo.estimations_target) as total_estimations_target,
                SUM(mo.contacts_target) as total_contacts_target,
                SUM(mo.transactions_target) as total_transactions_target,
                SUM(mo.revenue_target) as total_revenue_target,
                SUM(COALESCE(ap.estimations_count, 0)) as total_estimations_actual,
                SUM(COALESCE(ap.contacts_count, 0)) as total_contacts_actual,
                SUM(COALESCE(ap.transactions_count, 0)) as total_transactions_actual,
                SUM(COALESCE(ap.revenue_amount, 0)) as total_revenue_actual,
                AVG(CASE 
                    WHEN mo.estimations_target > 0 THEN (ap.estimations_count / mo.estimations_target) * 100
                    ELSE NULL 
                END) as avg_estimations_progress,
                AVG(CASE 
                    WHEN mo.contacts_target > 0 THEN (ap.contacts_count / mo.contacts_target) * 100
                    ELSE NULL 
                END) as avg_contacts_progress,
                AVG(CASE 
                    WHEN mo.transactions_target > 0 THEN (ap.transactions_count / mo.transactions_target) * 100
                    ELSE NULL 
                END) as avg_transactions_progress,
                AVG(CASE 
                    WHEN mo.revenue_target > 0 THEN (ap.revenue_amount / mo.revenue_target) * 100
                    ELSE NULL 
                END) as avg_revenue_progress
            FROM monthly_objectives mo
            LEFT JOIN agent_performance ap ON ap.agent_id = mo.agent_id AND ap.month = mo.month
            WHERE mo.month = ? $agency_filter
        ";

        return $this->db->query($sql, $params)->row();
    }

    /**
     * Récupérer la liste des agents depuis WordPress
     */
    public function get_agents() {
        // Utiliser la vue wp_Hrg8P_crm_agents pour avoir toutes les données complètes
        $query = "
            SELECT 
                user_id,
                user_login,
                user_email,
                user_status,
                registration_date,
                agent_post_id,
                agent_name,
                post_status,
                agent_email,
                agency_id,
                agency_name,
                phone,
                mobile,
                whatsapp,
                skype,
                website,
                agent_avatar,
                position,
                facebook,
                twitter,
                linkedin,
                googleplus,
                youtube,
                tiktok,
                instagram,
                pinterest,
                vimeo,
                postal_code,
                user_roles,
                user_id as ID,
                agent_name as display_name
            FROM rebencia_RebenciaBD.wp_Hrg8P_crm_agents
            WHERE post_status = 'publish'
            ORDER BY agent_name ASC
        ";
        
        return $this->wp_db->query($query)->result();
    }

    /**
     * Récupérer la liste des agents d'une agence spécifique
     * @param int $agency_id L'ID de l'agence
     * @return object[] Liste des agents de l'agence
     */
    public function get_agents_by_agency($agency_id) {
        if (empty($agency_id)) {
            return [];
        }
        
        // Utiliser la vue wp_Hrg8P_crm_agents existante pour avoir toutes les données complètes
        $query = "
            SELECT 
                user_id,
                user_login,
                user_email,
                user_status,
                registration_date,
                agent_post_id,
                agent_name,
                post_status,
                agent_email,
                agency_id,
                agency_name,
                phone,
                mobile,
                whatsapp,
                skype,
                website,
                agent_avatar,
                position,
                facebook,
                twitter,
                linkedin,
                googleplus,
                youtube,
                tiktok,
                instagram,
                pinterest,
                vimeo,
                postal_code,
                user_roles,
                user_id as ID,
                agent_name as display_name
            FROM rebencia_RebenciaBD.wp_Hrg8P_crm_agents
            WHERE agency_id = ?
            AND post_status = 'publish'
            ORDER BY agent_name ASC
        ";
        
        return $this->wp_db->query($query, [$agency_id])->result();
    }

    /**
     * Mettre à jour les performances de tous les agents pour un mois donné
     */
    public function update_all_agents_performance($month) {
        $agents = $this->get_agents();
        $updated_count = 0;

        foreach ($agents as $agent) {
            $real_performance = $this->calculate_real_performance($agent->ID, $month);
            
            if ($real_performance) {
                // Mettre à jour ou insérer les performances dans la table agent_performance
                $existing = $this->db->select('id')
                                   ->from('agent_performance')
                                   ->where('agent_id', $agent->ID)
                                   ->where('month', $month . '-01')
                                   ->get()
                                   ->row();

                $performance_data = [
                    'agent_id' => $agent->ID,
                    'month' => $month . '-01',
                    'estimations_count' => $real_performance['estimations_count'],
                    'contacts_count' => $real_performance['contacts_count'],
                    'transactions_count' => $real_performance['transactions_count'],
                    'revenue_amount' => $real_performance['revenue_amount'],
                    'commission_earned' => $real_performance['commission_earned'],
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                if ($existing) {
                    $this->db->where('id', $existing->id);
                    $this->db->update('agent_performance', $performance_data);
                } else {
                    $performance_data['created_at'] = date('Y-m-d H:i:s');
                    $this->db->insert('agent_performance', $performance_data);
                }
                
                $updated_count++;
            }
        }

        return $updated_count;
    }
}
