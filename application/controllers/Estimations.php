<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Contrôleur des Estimations avec contrôle d'accès par rôle
 * - Admin : voit toutes les estimations
 * - Manager : voit les estimations de son agence
 * - Agent : voit ses propres estimations
 */
class Estimations extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();
        $this->load->model('Estimation_model');
        $this->load->model('User_model');
    }

    /**
     * Page principale des estimations
     */
    public function index()
    {
        $this->global['pageTitle'] = 'CRM Rebencia : Estimations';
        
        // Récupérer les informations de l'utilisateur connecté
        $user_info = $this->get_user_info();
        $role = $user_info['role'];
        $user_post_id = $this->userPostId;
        
        // Récupérer les estimations selon le rôle
        $estimations = $this->get_estimations_by_role($role, $user_post_id);
        
        // Statistiques
        $stats = $this->calculate_estimations_stats($estimations);
        
        // Filtres pour la recherche
        $filters = $this->get_filters_by_role($role, $user_post_id);
        
        $data = [
            'estimations' => $estimations,
            'stats' => $stats,
            'filters' => $filters,
            'user_role' => $role,
            'user_info' => $user_info
        ];

        $this->loadViews("estimations/index", $this->global, $data, NULL);
    }

    /**
     * Récupérer les estimations selon le rôle de l'utilisateur
     */
    private function get_estimations_by_role($role, $user_post_id)
    {
        switch ($role) {
            case 'admin':
                // Admin voit toutes les estimations
                return $this->Estimation_model->get_all_estimations_with_details();
                
            case 'manager':
                // Manager voit les estimations de son agence
                $agency_id = $this->get_user_agency_id($user_post_id);
                return $this->Estimation_model->get_estimations_by_agency($agency_id);
                
            case 'agent':
            default:
                // Agent voit ses propres estimations
                return $this->Estimation_model->get_estimations_by_agent($user_post_id);
        }
    }

    /**
     * Calculer les statistiques des estimations
     */
    private function calculate_estimations_stats($estimations)
    {
        $total = count($estimations);
        $en_cours = 0;
        $validees = 0;
        $rejetees = 0;
        $montant_total = 0;
        $montant_mois = 0;
        
        $current_month = date('Y-m');
        
        foreach ($estimations as $estimation) {
            // Compter par statut
            switch ($estimation['statut_dossier']) {
                case 'en_cours':
                    $en_cours++;
                    break;
                case 'valide':
                    $validees++;
                    break;
                case 'rejete':
                    $rejetees++;
                    break;
            }
            
            // Montants
            $montant_total += (float)$estimation['prix_estimation'];
            
            if (date('Y-m', strtotime($estimation['created_at'])) === $current_month) {
                $montant_mois += (float)$estimation['prix_estimation'];
            }
        }
        
        return [
            'total' => $total,
            'en_cours' => $en_cours,
            'validees' => $validees,
            'rejetees' => $rejetees,
            'montant_total' => $montant_total,
            'montant_mois' => $montant_mois,
            'taux_validation' => $total > 0 ? round(($validees / $total) * 100, 1) : 0
        ];
    }

    /**
     * Obtenir les filtres disponibles selon le rôle
     */
    private function get_filters_by_role($role, $user_post_id)
    {
        $filters = [
            'statuts' => [
                'en_cours' => 'En cours',
                'valide' => 'Validée',
                'rejete' => 'Rejetée'
            ],
            'periodes' => [
                'today' => "Aujourd'hui",
                'week' => 'Cette semaine',
                'month' => 'Ce mois',
                'quarter' => 'Ce trimestre',
                'year' => 'Cette année'
            ]
        ];

        if ($role === 'admin') {
            // Admin peut filtrer par agent et agence
            $filters['agents'] = $this->User_model->get_all_agents();
            $filters['agencies'] = $this->User_model->get_all_agencies();
        } elseif ($role === 'manager') {
            // Manager peut filtrer par agent de son agence
            $agency_id = $this->get_user_agency_id($user_post_id);
            $filters['agents'] = $this->User_model->get_agents_by_agency($agency_id);
        }

        return $filters;
    }

    /**
     * Obtenir l'ID de l'agence de l'utilisateur
     */
    private function get_user_agency_id($user_post_id)
    {
        // Récupérer l'agence depuis la table wp_Hrg8P_crm_agents
        $query = $this->db->query(
            "SELECT agency_id FROM wp_Hrg8P_crm_agents WHERE user_post_id = ?", 
            [$user_post_id]
        );
        
        $result = $query->row();
        return $result ? $result->agency_id : null;
    }

    /**
     * Voir les détails d'une estimation
     */
    public function view($estimation_id)
    {
        $estimation = $this->Estimation_model->get_estimation_details($estimation_id);
        
        if (!$estimation) {
            show_404();
        }

        // Vérifier les droits d'accès
        if (!$this->can_access_estimation($estimation)) {
            show_error('Accès non autorisé à cette estimation', 403);
        }

        $this->global['pageTitle'] = 'CRM Rebencia : Estimation #' . $estimation['id'];

        $data = [
            'estimation' => $estimation,
            'user_role' => $this->get_user_info()['role']
        ];

        $this->loadViews("estimations/view", $this->global, $data, NULL);
    }

    /**
     * Vérifier si l'utilisateur peut accéder à cette estimation
     */
    private function can_access_estimation($estimation)
    {
        $user_info = $this->get_user_info();
        $role = $user_info['role'];
        $user_post_id = $this->userPostId;

        switch ($role) {
            case 'admin':
                return true; // Admin peut tout voir
                
            case 'manager':
                // Manager peut voir les estimations de son agence
                $agency_id = $this->get_user_agency_id($user_post_id);
                $estimation_agency = $this->get_estimation_agency($estimation['agent_id']);
                return $agency_id === $estimation_agency;
                
            case 'agent':
            default:
                // Agent ne peut voir que ses estimations
                return $estimation['agent_id'] == $user_post_id;
        }
    }

    /**
     * Obtenir l'agence d'une estimation via son agent
     */
    private function get_estimation_agency($agent_id)
    {
        $query = $this->db->query(
            "SELECT agency_id FROM wp_Hrg8P_crm_agents WHERE user_post_id = ?", 
            [$agent_id]
        );
        
        $result = $query->row();
        return $result ? $result->agency_id : null;
    }

    /**
     * API pour filtrer les estimations (AJAX)
     */
    public function filter()
    {
        if ($this->input->method() !== 'post') {
            show_404();
        }

        $filters = $this->input->post();
        $user_info = $this->get_user_info();
        
        $estimations = $this->Estimation_model->get_filtered_estimations(
            $filters, 
            $user_info['role'], 
            $this->userPostId
        );

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => true,
                'data' => $estimations,
                'count' => count($estimations)
            ]));
    }

    /**
     * Exporter les estimations (Excel/PDF)
     */
    public function export($format = 'excel')
    {
        $user_info = $this->get_user_info();
        $estimations = $this->get_estimations_by_role($user_info['role'], $this->userPostId);

        if ($format === 'excel') {
            $this->export_excel($estimations);
        } else {
            $this->export_pdf($estimations);
        }
    }

    /**
     * Obtenir les informations de l'utilisateur connecté
     */
    private function get_user_info()
    {
        return [
            'id' => $this->vendorId,
            'role' => $this->role,
            'name' => $this->name,
            'email' => $this->email
        ];
    }

    /**
     * Export Excel des estimations
     */
    private function export_excel($estimations)
    {
        // TODO: Implémenter l'export Excel
        // Utiliser PhpSpreadsheet ou simple CSV
    }

    /**
     * Export PDF des estimations
     */
    private function export_pdf($estimations)
    {
        // TODO: Implémenter l'export PDF
        // Utiliser TCPDF ou mPDF
    }
}
