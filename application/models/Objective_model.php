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
        $data = [
            'agent_id' => $agent_id,
            'month' => $month . '-01', // Premier jour du mois
            'estimations_target' => $objectives['estimations_target'] ?? 0,
            'contacts_target' => $objectives['contacts_target'] ?? 0,
            'transactions_target' => $objectives['transactions_target'] ?? 0,
            'revenue_target' => $objectives['revenue_target'] ?? 0,
            'created_by' => $created_by,
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
        $this->db->select('mo.*, u.display_name as agent_name, cb.display_name as created_by_name')
                 ->from('monthly_objectives mo')
                 ->join('wp_users u', 'u.ID = mo.agent_id', 'left')
                 ->join('wp_users cb', 'cb.ID = mo.created_by', 'left')
                 ->where('mo.agent_id', $agent_id);

        if ($month) {
            $this->db->where('mo.month', $month . '-01');
        }

        return $this->db->order_by('mo.month', 'DESC')->get()->result();
    }

    /**
     * Récupérer tous les objectifs pour un mois donné
     */
    public function get_monthly_objectives($month) {
        return $this->db->select('mo.*, u.display_name as agent_name')
                       ->from('monthly_objectives mo')
                       ->join('wp_users u', 'u.ID = mo.agent_id', 'left')
                       ->where('mo.month', $month . '-01')
                       ->order_by('u.display_name', 'ASC')
                       ->get()
                       ->result();
    }

    /**
     * Mettre à jour les performances réelles d'un agent
     */
    public function update_agent_performance($agent_id, $month, $performance_data) {
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
    public function get_objectives_dashboard($month = null) {
        if (!$month) {
            $month = date('Y-m');
        }

        // Version simplifiée utilisant Active Record
        $objectives = $this->db->select('*')
                              ->from('monthly_objectives')
                              ->where('month', $month . '-01')
                              ->get()
                              ->result();

        $result = array();
        
        foreach ($objectives as $objective) {
            // Récupérer les infos de l'agent depuis WordPress
            $agent = $this->wp_db->select('ID, display_name')
                                 ->from('users')
                                 ->where('ID', $objective->agent_id)
                                 ->get()
                                 ->row();

            // Récupérer les performances depuis la base CRM
            $performance = $this->db->select('*')
                                   ->from('agent_performance')
                                   ->where('agent_id', $objective->agent_id)
                                   ->where('month', $month . '-01')
                                   ->get()
                                   ->row();

            // Calculer les progressions
            $estimations_progress = ($objective->estimations_target > 0) ? 
                round((($performance->estimations_count ?? 0) / $objective->estimations_target) * 100, 2) : 0;
                
            $contacts_progress = ($objective->contacts_target > 0) ? 
                round((($performance->contacts_count ?? 0) / $objective->contacts_target) * 100, 2) : 0;
                
            $transactions_progress = ($objective->transactions_target > 0) ? 
                round((($performance->transactions_count ?? 0) / $objective->transactions_target) * 100, 2) : 0;
                
            $revenue_progress = ($objective->revenue_target > 0) ? 
                round((($performance->revenue_amount ?? 0) / $objective->revenue_target) * 100, 2) : 0;

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
            $obj->estimations_count = $performance->estimations_count ?? 0;
            $obj->contacts_count = $performance->contacts_count ?? 0;
            $obj->transactions_count = $performance->transactions_count ?? 0;
            $obj->revenue_amount = $performance->revenue_amount ?? 0;
            $obj->commission_earned = $performance->commission_earned ?? 0;
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
     * Statistiques globales des objectifs
     */
    public function get_objectives_stats($month = null) {
        if (!$month) {
            $month = date('Y-m');
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
            WHERE mo.month = ?
        ";

        return $this->db->query($sql, [$month . '-01'])->row();
    }

    /**
     * Récupérer la liste des agents depuis WordPress
     */
    public function get_agents() {
        return $this->wp_db->select('u.ID, u.display_name, u.user_email')
                           ->from('users u')
                           ->join('usermeta um', 'um.user_id = u.ID')
                           ->where('um.meta_key', 'wp_Hrg8P_capabilities')
                           ->like('um.meta_value', 'houzez_agent')
                           ->order_by('u.display_name', 'ASC')
                           ->get()
                           ->result();
    }
}
