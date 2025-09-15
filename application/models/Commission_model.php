<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Commission_model extends CI_Model {

    protected $wp_db;

    public function __construct() {
        parent::__construct();
        $this->load->database(); // Base CRM par défaut
        $this->wp_db = $this->load->database('wordpress', TRUE); // Base WordPress
    }

    /**
     * Récupérer les paramètres de commission
     */
    public function get_commission_settings($type = null) {
        if ($type) {
            return $this->db->where('type', $type)
                           ->where('is_active', 1)
                           ->get('commission_settings')
                           ->row();
        }
        
        return $this->db->where('is_active', 1)
                       ->get('commission_settings')
                       ->result();
    }

    /**
     * Mettre à jour les paramètres de commission
     */
    public function update_commission_settings($type, $data) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        return $this->db->where('type', $type)
                       ->update('commission_settings', $data);
    }

    /**
     * Calculer la commission pour une transaction
     */
    public function calculate_commission($transaction_type, $amount, $agent_id = null) {
        $settings = $this->get_commission_settings($transaction_type);
        
        if (!$settings) {
            return false;
        }

        $result = [
            'transaction_type' => $transaction_type,
            'base_amount' => $amount,
            'agent_rate' => $settings->agent_rate,
            'agency_rate' => $settings->agency_rate,
            'agent_commission' => 0,
            'agency_commission' => 0,
            'total_commission' => 0
        ];

        if ($transaction_type === 'sale') {
            // Pour les ventes : pourcentage du prix de vente
            $total_commission_rate = $settings->agent_rate + $settings->agency_rate;
            $result['total_commission'] = ($amount * $total_commission_rate) / 100;
            $result['agent_commission'] = ($amount * $settings->agent_rate) / 100;
            $result['agency_commission'] = ($amount * $settings->agency_rate) / 100;
            
        } elseif ($transaction_type === 'rental') {
            // Pour les locations : commission sur X mois de loyer
            $months = $settings->rental_months ?: 1;
            $monthly_rent = $amount;
            $commission_base = $monthly_rent * $months;
            
            $result['rental_months'] = $months;
            $result['commission_base'] = $commission_base;
            $result['agent_commission'] = ($commission_base * $settings->agent_rate) / 100;
            $result['agency_commission'] = ($commission_base * $settings->agency_rate) / 100;
            $result['total_commission'] = $result['agent_commission'] + $result['agency_commission'];
        }

        return $result;
    }

    /**
     * Enregistrer une commission calculée
     */
    public function save_commission($data) {
        $commission_data = [
            'agent_id' => $data['agent_id'],
            'transaction_id' => $data['transaction_id'] ?? null,
            'property_id' => $data['property_id'] ?? null,
            'transaction_type' => $data['transaction_type'],
            'base_amount' => $data['base_amount'],
            'agent_commission' => $data['agent_commission'],
            'agency_commission' => $data['agency_commission'],
            'total_commission' => $data['total_commission'],
            'commission_rate' => $data['agent_rate'],
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ];

        return $this->db->insert('agent_commissions', $commission_data);
    }

    /**
     * Récupérer les commissions d'un agent
     */
    public function get_agent_commissions($agent_id, $month = null, $status = null) {
        $this->db->select('ac.*, p.post_title as property_title, u.display_name as agent_name')
                 ->from('agent_commissions ac')
                 ->join('wp_posts p', 'p.ID = ac.property_id', 'left')
                 ->join('wp_users u', 'u.ID = ac.agent_id', 'left')
                 ->where('ac.agent_id', $agent_id);

        if ($month) {
            $this->db->where('DATE_FORMAT(ac.created_at, "%Y-%m")', $month);
        }

        if ($status) {
            $this->db->where('ac.status', $status);
        }

        return $this->db->order_by('ac.created_at', 'DESC')->get()->result();
    }

    /**
     * Statistiques des commissions
     */
    public function get_commission_stats($period = 'current_month') {
        $where_clause = '';
        
        switch ($period) {
            case 'current_month':
                $where_clause = "DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')";
                break;
            case 'last_month':
                $where_clause = "DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 1 MONTH), '%Y-%m')";
                break;
            case 'current_year':
                $where_clause = "YEAR(created_at) = YEAR(NOW())";
                break;
        }

        if ($where_clause) {
            $this->db->where($where_clause);
        }

        $query = $this->db->select('
            COUNT(*) as total_transactions,
            SUM(agent_commission) as total_agent_commission,
            SUM(agency_commission) as total_agency_commission,
            SUM(total_commission) as total_commission,
            AVG(agent_commission) as avg_agent_commission,
            transaction_type
        ')
        ->from('agent_commissions')
        ->group_by('transaction_type')
        ->get();

        return $query->result();
    }
}
