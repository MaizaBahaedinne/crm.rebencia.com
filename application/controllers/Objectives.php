<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Objectives extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('Objective_model');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->helper('form');
        
        // Configuration de la base de données WordPress
        $this->wp_db = $this->load->database('wordpress', TRUE);
    }

    /**
     * Dashboard des objectifs
     */
    public function index() {
        $this->isLoggedIn();
        $month = $this->input->get('month') ?: date('Y-m');
        
        $data['title'] = 'Tableau de Bord des Objectifs';
        $data['current_month'] = $month;
        $data['objectives_data'] = $this->Objective_model->get_objectives_dashboard($month);
        $data['stats'] = $this->Objective_model->get_objectives_stats($month);
        
        // Si aucune donnée n'est trouvée, utiliser des données d'exemple pour le mois de septembre 2025
        if (empty($data['objectives_data']) && $month == '2025-09') {
            $data['objectives_data'] = $this->get_sample_objectives_data();
        }
        
        $this->loadViews('objectives/index', $this->global, $data, NULL);
    }

    /**
     * Données d'exemple pour les tests
     */
    private function get_sample_objectives_data() {
        return [
            (object) [
                'id' => 1,
                'agent_id' => 1,
                'agent_name' => 'Ahmed Ben Ali',
                'month' => '2025-09-01',
                'estimations_target' => 25,
                'contacts_target' => 50,
                'transactions_target' => 5,
                'revenue_target' => 150000,
                'estimations_count' => 28,
                'contacts_count' => 55,
                'transactions_count' => 6,
                'revenue_amount' => 175000,
                'commission_earned' => 8750.00,
                'estimations_progress' => 112.0,
                'contacts_progress' => 110.0,
                'transactions_progress' => 120.0,
                'revenue_progress' => 116.7
            ],
            (object) [
                'id' => 2,
                'agent_id' => 2,
                'agent_name' => 'Fatima Gharbi',
                'month' => '2025-09-01',
                'estimations_target' => 20,
                'contacts_target' => 40,
                'transactions_target' => 4,
                'revenue_target' => 120000,
                'estimations_count' => 16,
                'contacts_count' => 32,
                'transactions_count' => 3,
                'revenue_amount' => 95000,
                'commission_earned' => 4750.00,
                'estimations_progress' => 80.0,
                'contacts_progress' => 80.0,
                'transactions_progress' => 75.0,
                'revenue_progress' => 79.2
            ],
            (object) [
                'id' => 3,
                'agent_id' => 3,
                'agent_name' => 'Mohamed Khelifi',
                'month' => '2025-09-01',
                'estimations_target' => 30,
                'contacts_target' => 60,
                'transactions_target' => 6,
                'revenue_target' => 180000,
                'estimations_count' => 32,
                'contacts_count' => 65,
                'transactions_count' => 7,
                'revenue_amount' => 200000,
                'commission_earned' => 10000.00,
                'estimations_progress' => 106.7,
                'contacts_progress' => 108.3,
                'transactions_progress' => 116.7,
                'revenue_progress' => 111.1
            ],
            (object) [
                'id' => 4,
                'agent_id' => 4,
                'agent_name' => 'Amina Sassi',
                'month' => '2025-09-01',
                'estimations_target' => 15,
                'contacts_target' => 35,
                'transactions_target' => 3,
                'revenue_target' => 90000,
                'estimations_count' => 8,
                'contacts_count' => 18,
                'transactions_count' => 1,
                'revenue_amount' => 45000,
                'commission_earned' => 2250.00,
                'estimations_progress' => 53.3,
                'contacts_progress' => 51.4,
                'transactions_progress' => 33.3,
                'revenue_progress' => 50.0
            ],
            (object) [
                'id' => 5,
                'agent_id' => 5,
                'agent_name' => 'Karim Trabelsi',
                'month' => '2025-09-01',
                'estimations_target' => 22,
                'contacts_target' => 45,
                'transactions_target' => 4,
                'revenue_target' => 130000,
                'estimations_count' => 20,
                'contacts_count' => 42,
                'transactions_count' => 4,
                'revenue_amount' => 120000,
                'commission_earned' => 6000.00,
                'estimations_progress' => 90.9,
                'contacts_progress' => 93.3,
                'transactions_progress' => 100.0,
                'revenue_progress' => 92.3
            ]
        ];
    }

    /**
     * Définir les objectifs mensuels
     */
    public function set_monthly() {
        $this->isLoggedIn();
        
        try {
            $data['title'] = 'Définir les Objectifs Mensuels';
            $data['selected_month'] = $this->input->get('month') ?: date('Y-m');
            
            // Récupérer la liste des agents selon le rôle de l'utilisateur
            if ($this->roleText === 'manager' && !empty($this->agencyId)) {
                // manager : récupérer seulement les agents de son agence
                $data['agents'] = $this->Objective_model->get_agents_by_agency($this->agencyId);
                $data['is_manager_view'] = true;
                $data['agency_id'] = $this->agencyId;
            } else {
                // Admin ou autres rôles : récupérer tous les agents
                $data['agents'] = $this->Objective_model->get_agents();
                $data['is_manager_view'] = false;
            }
            
            // Récupérer les objectifs existants si il y en a
            $existing_objectives = $this->Objective_model->get_monthly_objectives($data['selected_month']);
            $data['existing_objectives'] = array();
            
            if ($existing_objectives) {
                foreach ($existing_objectives as $obj) {
                    $data['existing_objectives'][$obj->agent_id] = $obj;
                }
            }

            // Si c'est une soumission
            if ($this->input->method() === 'post') {
                $this->_process_monthly_objectives();
                return;
            }

            $this->loadViews('objectives/set_monthly', $this->global, $data, NULL);
            
        } catch (Exception $e) {
            log_message('error', 'Erreur dans set_monthly: ' . $e->getMessage());
            show_error('Erreur lors du chargement de la page: ' . $e->getMessage());
        }
    }

    /**
     * Traiter la soumission des objectifs mensuels
     */
    private function _process_monthly_objectives() {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('month', 'Mois', 'required|regex_match[/^\d{4}-\d{2}$/]');
        $this->form_validation->set_rules('agent_id', 'Agent', 'required|integer');
        $this->form_validation->set_rules('estimations_target', 'Objectif Estimations', 'required|integer|greater_than_equal_to[0]');
        $this->form_validation->set_rules('contacts_target', 'Objectif Contacts', 'required|integer|greater_than_equal_to[0]');
        $this->form_validation->set_rules('transactions_target', 'Objectif Transactions', 'required|integer|greater_than_equal_to[0]');
        $this->form_validation->set_rules('revenue_target', 'Objectif CA', 'required|numeric|greater_than_equal_to[0]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', 'Erreur dans les données : ' . validation_errors());
            return;
        }

        $agent_id = $this->input->post('agent_id');
        $month = $this->input->post('month');
        $created_by = $this->vendorId; // ID WordPress de l'utilisateur connecté
        
        // Validation de l'utilisateur connecté
        if (empty($created_by)) {
            $this->session->set_flashdata('error', 'Erreur de session : utilisateur non identifié.');
            redirect('objectives/set_monthly');
            return;
        }

    // Validation supplémentaire pour les managers : vérifier que l'agent appartient à leur agence
    if ($this->roleText === 'manager' && !empty($this->agencyId)) {
            $agency_agents = $this->Objective_model->get_agents_by_agency($this->agencyId);
            $allowed_agent_ids = array_column($agency_agents, 'user_id');
            
            if (!in_array($agent_id, $allowed_agent_ids)) {
                $this->session->set_flashdata('error', 'Vous ne pouvez définir des objectifs que pour les agents de votre agence.');
                redirect('objectives/set_monthly');
                return;
            }
        }

        $objectives = [
            'estimations_target' => $this->input->post('estimations_target'),
            'contacts_target' => $this->input->post('contacts_target'),
            'transactions_target' => $this->input->post('transactions_target'),
            'revenue_target' => $this->input->post('revenue_target')
        ];

        if ($this->Objective_model->set_monthly_objectives($agent_id, $month, $objectives, $created_by)) {
            $this->session->set_flashdata('success', 'Objectifs définis avec succès !');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de la définition des objectifs.');
        }
    }

    /**
     * Voir les objectifs d'un agent
     */
    public function agent($agent_id = null) {
        $this->isLoggedIn();
        if (!$agent_id) {
            show_404();
        }

        $month = $this->input->get('month');
        
        $data['title'] = 'Objectifs de l\'Agent';
        $data['agent_id'] = $agent_id;
        $data['current_month'] = $month ?: date('Y-m');
        $data['objectives'] = $this->Objective_model->get_agent_objectives($agent_id, $month);
        $data['performance'] = $this->Objective_model->get_agent_performance($agent_id, $month);

        // Récupérer les infos de l'agent
        $this->load->database('wordpress');
        $data['agent'] = $this->db->select('ID, display_name, user_email')
                                 ->where('ID', $agent_id)
                                 ->get('wp_users')
                                 ->row();

        if (!$data['agent']) {
            show_404();
        }

        $this->loadViews('objectives/agent_detail', $this->global, $data, NULL);
    }

    /**
     * Mettre à jour les performances d'un agent manuellement
     */
    public function update_agent_performance() {
        $this->isLoggedIn();
        if ($this->input->method() !== 'post') {
            show_404();
        }

        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('agent_id', 'Agent', 'required|integer');
        $this->form_validation->set_rules('month', 'Mois', 'required|regex_match[/^\d{4}-\d{2}$/]');
        $this->form_validation->set_rules('estimations_count', 'Nombre Estimations', 'required|integer|greater_than_equal_to[0]');
        $this->form_validation->set_rules('contacts_count', 'Nombre Contacts', 'required|integer|greater_than_equal_to[0]');
        $this->form_validation->set_rules('transactions_count', 'Nombre Transactions', 'required|integer|greater_than_equal_to[0]');
        $this->form_validation->set_rules('revenue_amount', 'CA Réalisé', 'required|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('commission_earned', 'Commission Gagnée', 'required|numeric|greater_than_equal_to[0]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', 'Erreur dans les données : ' . validation_errors());
            redirect('objectives/agent/' . $this->input->post('agent_id'));
            return;
        }

        $agent_id = $this->input->post('agent_id');
        $month = $this->input->post('month');

        $performance_data = [
            'estimations_count' => $this->input->post('estimations_count'),
            'contacts_count' => $this->input->post('contacts_count'),
            'transactions_count' => $this->input->post('transactions_count'),
            'revenue_amount' => $this->input->post('revenue_amount'),
            'commission_earned' => $this->input->post('commission_earned')
        ];

        if ($this->Objective_model->update_agent_performance($agent_id, $month, $performance_data)) {
            $this->session->set_flashdata('success', 'Performances mises à jour avec succès !');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de la mise à jour des performances.');
        }

        redirect('objectives/agent/' . $agent_id . '?month=' . $month);
    }

    /**
     * Calculer automatiquement les performances
     */
    public function calculate_performance($agent_id = null, $month = null) {
        $this->isLoggedIn();
        if (!$agent_id || !$month) {
            $this->session->set_flashdata('error', 'Agent et mois requis.');
            redirect('objectives');
            return;
        }

        $performance = $this->Objective_model->calculate_performance($agent_id, $month);

        if ($performance) {
            $this->session->set_flashdata('success', 'Performances calculées automatiquement !');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors du calcul automatique.');
        }

        redirect('objectives/agent/' . $agent_id . '?month=' . $month);
    }

    /**
     * Mettre à jour les performances en temps réel (AJAX)
     */
    public function update_performance() {
        $this->isLoggedIn();
        
        if ($this->input->method() !== 'post') {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'message' => 'Méthode non autorisée']));
            return;
        }

        $month = $this->input->post('month') ?: date('Y-m');
        $agent_id = $this->input->post('agent_id');
        
        try {
            if ($agent_id) {
                // Recalculer les performances pour un agent spécifique
                $performance = $this->Objective_model->calculate_real_performance($agent_id, $month);
                
                if ($performance) {
                    $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode([
                            'success' => true, 
                            'message' => 'Performances mises à jour avec succès',
                            'data' => $performance
                        ]));
                } else {
                    $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode([
                            'success' => false, 
                            'message' => 'Aucune donnée trouvée pour cet agent'
                        ]));
                }
            } else {
                // Recalculer les performances pour tous les agents
                $updated_count = $this->Objective_model->update_all_agents_performance($month);
                
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                        'success' => true, 
                        'message' => "{$updated_count} agent(s) mis à jour avec succès",
                        'updated_count' => $updated_count
                    ]));
            }
        } catch (Exception $e) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false, 
                    'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
                ]));
        }
    }

    /**
     * Objectifs par équipe/agence
     */
    public function team() {
        $this->isLoggedIn();
        $month = $this->input->get('month') ?: date('Y-m');
        
        $data['title'] = 'Objectifs par Équipe';
        $data['current_month'] = $month;
        $data['team_objectives'] = $this->Objective_model->get_objectives_dashboard($month);
        $data['team_stats'] = $this->Objective_model->get_objectives_stats($month);

        $this->loadViews('objectives/team', $this->global, $data, NULL);
    }

    /**
     * API pour récupérer les données d'objectifs (AJAX)
     */
    public function api_get_data() {
        $this->isLoggedIn();
        $month = $this->input->get('month') ?: date('Y-m');
        $agent_id = $this->input->get('agent_id');

        if ($agent_id) {
            $data = $this->Objective_model->get_agent_objectives($agent_id, $month);
        } else {
            $data = $this->Objective_model->get_monthly_objectives($month);
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['success' => true, 'data' => $data]));
    }

    /**
     * Définition d'objectifs en masse
     */
    public function bulk_set() {
        $this->isLoggedIn();
        $data['title'] = 'Définir Objectifs en Masse';
        
        // Récupérer tous les agents
        $this->load->database('wordpress');
        $data['agents'] = $this->db->select('u.ID, u.display_name')
                                  ->from('wp_users u')
                                  ->join('wp_usermeta um', 'um.user_id = u.ID')
                                  ->where('um.meta_key', 'wp_capabilities')
                                  ->like('um.meta_value', 'houzez_agent')
                                  ->order_by('u.display_name', 'ASC')
                                  ->get()
                                  ->result();

        if ($this->input->method() === 'post') {
            $this->_process_bulk_objectives();
        }

        $this->loadViews('objectives/bulk_set', $this->global, $data, NULL);
    }

    /**
     * Traiter la définition d'objectifs en masse
     */
    private function _process_bulk_objectives() {
        $month = $this->input->post('month');
        $agents_data = $this->input->post('agents');
        $created_by = $this->vendorId; // ID WordPress de l'utilisateur connecté
        
        // Validation de l'utilisateur connecté
        if (empty($created_by)) {
            $this->session->set_flashdata('error', 'Erreur de session : utilisateur non identifié.');
            redirect('objectives/bulk_set');
            return;
        }

        if (!$month || !$agents_data) {
            $this->session->set_flashdata('error', 'Mois et données agents requis.');
            return;
        }

        $success_count = 0;
        $error_count = 0;

        foreach ($agents_data as $agent_id => $objectives) {
            if (!empty($objectives['estimations_target']) || 
                !empty($objectives['contacts_target']) || 
                !empty($objectives['transactions_target']) || 
                !empty($objectives['revenue_target'])) {
                
                if ($this->Objective_model->set_monthly_objectives($agent_id, $month, $objectives, $created_by)) {
                    $success_count++;
                } else {
                    $error_count++;
                }
            }
        }

        if ($success_count > 0) {
            $this->session->set_flashdata('success', "Objectifs définis pour {$success_count} agent(s) !");
        }

        if ($error_count > 0) {
            $this->session->set_flashdata('error', "Erreur pour {$error_count} agent(s).");
        }
    }
}
