<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Commission extends BaseController {

    protected $wp_db;

    public function __construct() {
        parent::__construct();
        $this->load->model('Commission_model');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper('form');
        $this->load->database(); // Base CRM par défaut
        $this->wp_db = $this->load->database('wordpress', TRUE); // Base WordPress
    }
        
    /**
     * Vérification d'authentification pour toutes les méthodes
     */
    private function checkAuth() {
        $this->isLoggedIn();
    }

    /**
     * Page de gestion des paramètres de commission
     */
    public function settings() {
        $this->isLoggedIn();
        $data['title'] = 'Paramètres des Commissions';
        $data['settings'] = $this->Commission_model->get_commission_settings();
        
        $this->loadViews('commission/settings', $this->global, $data, NULL);
    }

    /**
     * Mettre à jour les paramètres de commission
     */
    public function update_settings() {
        $this->isLoggedIn();
        if ($this->input->method() !== 'post') {
            show_404();
        }

        $this->load->library('form_validation');
        
        // Validation pour vente
        $this->form_validation->set_rules('sale_agent_rate', 'Commission Agent (Vente)', 'required|numeric|greater_than[0]|less_than[100]');
        $this->form_validation->set_rules('sale_agency_rate', 'Commission Agence (Vente)', 'required|numeric|greater_than[0]|less_than[100]');
        
        // Validation pour location
        $this->form_validation->set_rules('rental_agent_rate', 'Commission Agent (Location)', 'required|numeric|greater_than[0]|less_than[100]');
        $this->form_validation->set_rules('rental_months', 'Mois de Loyer', 'required|integer|greater_than[0]|less_than[13]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', 'Erreur dans les données saisies : ' . validation_errors());
            redirect('commission/settings');
            return;
        }

        // Mettre à jour les paramètres de vente
        $sale_data = [
            'agent_rate' => $this->input->post('sale_agent_rate'),
            'agency_rate' => $this->input->post('sale_agency_rate')
        ];
        
        $sale_updated = $this->Commission_model->update_commission_settings('sale', $sale_data);

        // Mettre à jour les paramètres de location
        $rental_data = [
            'agent_rate' => $this->input->post('rental_agent_rate'),
            'agency_rate' => 0, // Pas de commission agence pour les locations selon vos spécifications
            'rental_months' => $this->input->post('rental_months')
        ];
        
        $rental_updated = $this->Commission_model->update_commission_settings('rental', $rental_data);

        if ($sale_updated && $rental_updated) {
            $this->session->set_flashdata('success', 'Paramètres de commission mis à jour avec succès !');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de la mise à jour des paramètres.');
        }

        redirect('commission/settings');
    }

    /**
     * Calculatrice de commission
     */
    public function calculator() {
        $this->isLoggedIn();
        $data['title'] = 'Calculatrice de Commission';
        $data['settings'] = $this->Commission_model->get_commission_settings();
        
        if ($this->input->method() === 'post') {
            $transaction_type = $this->input->post('transaction_type');
            $amount = floatval($this->input->post('amount'));
            $agent_id = $this->input->post('agent_id');

            if ($amount > 0) {
                $data['calculation'] = $this->Commission_model->calculate_commission($transaction_type, $amount, $agent_id);
            }
        }

        // Récupérer la liste des agents pour le select
        $data['agents'] = $this->wp_db->select('u.ID, u.display_name')
                                      ->from('users u')
                                      ->join('usermeta um', 'um.user_id = u.ID')
                                      ->where('um.meta_key', 'wp_Hrg8P_capabilities')
                                      ->like('um.meta_value', 'houzez_agent')
                                      ->order_by('u.display_name', 'ASC')
                                      ->get()
                                      ->result();

        $this->loadViews('commission/calculator', $this->global, $data, NULL);
    }

    /**
     * Historique des commissions
     */
    public function history($agent_id = null) {
        $this->isLoggedIn();
        $data['title'] = 'Historique des Commissions';
        
        $month = $this->input->get('month');
        $status = $this->input->get('status');
        
        if ($agent_id) {
            $data['commissions'] = $this->Commission_model->get_agent_commissions($agent_id, $month, $status);
            $data['selected_agent'] = $agent_id;
        } else {
            $data['commissions'] = [];
        }

        // Récupérer la liste des agents
        $this->load->database('wordpress');
        $data['agents'] = $this->wp_db->select('u.ID, u.display_name')
                                       ->from('users u')
                                       ->join('usermeta um', 'um.user_id = u.ID')
                                       ->where('um.meta_key', 'wp_Hrg8P_capabilities')
                                       ->like('um.meta_value', 'houzez_agent')
                                       ->order_by('u.display_name', 'ASC')
                                       ->get()
                                       ->result();

        $this->loadViews('commission/history', $this->global, $data, NULL);
    }

    /**
     * Statistiques des commissions
     */
    public function stats() {
        $this->isLoggedIn();
        $data['title'] = 'Statistiques des Commissions';
        
        $data['current_month_stats'] = $this->Commission_model->get_commission_stats('current_month');
        $data['last_month_stats'] = $this->Commission_model->get_commission_stats('last_month');
        $data['current_year_stats'] = $this->Commission_model->get_commission_stats('current_year');

        $this->loadViews('commission/stats', $this->global, $data, NULL);
    }

    /**
     * API pour calculer une commission (AJAX)
     */
    public function api_calculate() {
        $this->isLoggedIn();
        if ($this->input->method() !== 'post') {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Method not allowed']));
            return;
        }

        $transaction_type = $this->input->post('transaction_type');
        $amount = floatval($this->input->post('amount'));
        $agent_id = $this->input->post('agent_id');

        if (!$transaction_type || $amount <= 0) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Données invalides']));
            return;
        }

        $calculation = $this->Commission_model->calculate_commission($transaction_type, $amount, $agent_id);

        if ($calculation) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => true, 'data' => $calculation]));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Impossible de calculer la commission']));
        }
    }

    /**
     * Enregistrer une commission
     */
    public function save() {
        $this->isLoggedIn();
        if ($this->input->method() !== 'post') {
            show_404();
        }

        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('agent_id', 'Agent', 'required|integer');
        $this->form_validation->set_rules('transaction_type', 'Type de transaction', 'required|in_list[sale,rental]');
        $this->form_validation->set_rules('base_amount', 'Montant de base', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('property_id', 'Propriété', 'integer');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', 'Erreur dans les données : ' . validation_errors());
            redirect('commission/calculator');
            return;
        }

        $transaction_type = $this->input->post('transaction_type');
        $amount = floatval($this->input->post('base_amount'));
        $agent_id = $this->input->post('agent_id');

        // Calculer la commission
        $calculation = $this->Commission_model->calculate_commission($transaction_type, $amount, $agent_id);

        if (!$calculation) {
            $this->session->set_flashdata('error', 'Impossible de calculer la commission.');
            redirect('commission/calculator');
            return;
        }

        // Préparer les données pour sauvegarde
        $save_data = [
            'agent_id' => $agent_id,
            'property_id' => $this->input->post('property_id'),
            'transaction_type' => $transaction_type,
            'base_amount' => $amount,
            'agent_commission' => $calculation['agent_commission'],
            'agency_commission' => $calculation['agency_commission'],
            'total_commission' => $calculation['total_commission'],
            'agent_rate' => $calculation['agent_rate']
        ];

        if ($this->Commission_model->save_commission($save_data)) {
            $this->session->set_flashdata('success', 'Commission enregistrée avec succès !');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de l\'enregistrement de la commission.');
        }

        redirect('commission/calculator');
    }
}
