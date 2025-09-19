<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Estimations extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Estimation_model');
        $this->load->helper('text');
    }

    public function index()
    {
        // Vérifier si l'utilisateur est connecté
        $this->isLoggedIn();
        
        // Récupérer les informations utilisateur depuis la session
        $user_info = $this->get_user_info();
        $user_role = $user_info['role'];
        
        // Récupérer les estimations selon le rôle
        $estimations = $this->get_estimations_by_role($user_role, $user_info);
        
        // Calculer les statistiques
        $stats = $this->calculate_estimations_stats($estimations);
        
        // Préparer les données pour la vue
        $data = [
            'estimations' => $estimations,
            'stats' => $stats,
            'user_role' => $user_role,
            'user_info' => $user_info,
            'page_title' => 'Estimations Immobilières'
        ];
        
        // Charger la vue avec la méthode correcte du BaseController
        $this->global['pageTitle'] = 'Estimations Immobilières';
        $this->global['page_heading'] = 'Estimations';
        
        $this->loadViews("estimations/index", $this->global, $data, NULL);
    }

    private function get_estimations_by_role($role, $user_info)
    {
        try {
            switch ($role) {
                case 'administrator':
                    return $this->Estimation_model->get_all_estimations_with_details();
                case 'manager':
                    $agency_id = $user_info['agency_id'] ?? 1;
                    return $this->Estimation_model->get_estimations_by_agency($agency_id);
                default:
                    $user_post_id = $user_info['user_post_id'] ?? 1;
                    return $this->Estimation_model->get_estimations_by_agent($user_post_id);
            }
        } catch (Exception $e) {
            // En cas d'erreur, retourner un tableau vide
            error_log("Erreur estimations: " . $e->getMessage());
            return [];
        }
    }

    private function calculate_estimations_stats($estimations)
    {
        $stats = [
            'total' => count($estimations),
            'en_attente' => 0,
            'validees' => 0,
            'montant_total' => 0,
            'recent' => 0
        ];

        foreach ($estimations as $estimation) {
            // Compter par statut
            $statut = $estimation['statut'] ?? 'en_attente';
            if ($statut === 'en_attente') {
                $stats['en_attente']++;
            } elseif ($statut === 'validee') {
                $stats['validees']++;
            }
            
            // Calculer le montant total
            $stats['montant_total'] += (float)($estimation['valeur_estimee'] ?? 0);
            
            // Compter les récentes (ce mois)
            $date_creation = $estimation['date_creation'] ?? date('Y-m-d');
            if (date('Y-m', strtotime($date_creation)) === date('Y-m')) {
                $stats['recent']++;
            }
        }

        return $stats;
    }

    private function get_user_info()
    {
        // Récupérer les infos comme dans Dashboard
        $role = $this->session->userdata('role');
        $user_post_id = $this->session->userdata('user_post_id');
        $agency_id = $this->session->userdata('agency_id');
        $userId = $this->session->userdata('userId');
        $name = $this->session->userdata('name');
        
        return [
            'user_id' => $userId ?? 0,
            'user_post_id' => $user_post_id ?? 0,
            'role' => $role ?? 'agent',
            'agency_id' => $agency_id ?? 1,
            'name' => $name ?? 'Utilisateur'
        ];
    }
}
