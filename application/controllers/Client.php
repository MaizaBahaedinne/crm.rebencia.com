<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'libraries/BaseController.php';

class Client extends BaseController {
    public function __construct(){
        parent::__construct();
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper(['url','form']);
        
        $this->isLoggedIn();
        
        $this->load->model('wp_client_model');
        $this->load->model('client_model');
        $this->load->model('agency_model');
        $this->load->model('agent_model');
    }

    public function index() {
        $this->isLoggedIn();
        $filters = $this->_filters();
        $data = $this->global;
        $data['pageTitle'] = 'Clients Rebencia';
        $data['filters'] = $filters;
        $data['clients'] = $this->client_model->all(1000, 0, $filters);
        $this->loadViews('client/list_grid', $data, NULL, NULL);
    }

    private function _filters(){
        return [
            'q'=>$this->input->get('q',TRUE),
            'role'=>$this->input->get('role',TRUE),
            'statut'=>$this->input->get('statut',TRUE)
        ];
    }

    public function add() {
        $this->isLoggedIn();
        if ($this->input->post()) {
            // Gestion de la source d'information
            $source = $this->input->post('source');
            if ($source === 'Autre') {
                $source = $this->input->post('source_autre_detail');
            }
            
            $data = [
                'nom' => $this->input->post('nom'),
                'prenom' => $this->input->post('prenom'),
                'email' => $this->input->post('email'),
                'telephone' => $this->input->post('telephone'),
                'adresse' => $this->input->post('adresse'),
                'type_client' => $this->input->post('type_client'),
                'identite_type' => $this->input->post('identite_type'),
                'identite_numero' => $this->input->post('identite_numero'),
                'contact_principal' => $this->input->post('contact_principal'),
                'contact_secondaire' => $this->input->post('contact_secondaire'),
                'ville' => $this->input->post('ville'),
                'code_postal' => $this->input->post('code_postal'),
                'pays' => $this->input->post('pays'),
                'source' => $source,
                'notes' => $this->input->post('notes'),
                'agency_id' => $this->input->post('agency_id'),
                'agent_id' => $this->input->post('agent_id'),
            ];
            
            $this->loadViews('client/form', $data, NULL, NULL);
        } else {
            $data = $this->global;
            $data['pageTitle'] = 'Ajouter un client';
            $this->loadViews('client/form', $data, NULL, NULL);
        }
    }

    /**
     * Version améliorée avec toutes les données disponibles
     */
    public function search_agencies_enhanced() {
        header('Content-Type: application/json');
        ob_clean();
        
        $query = $this->input->post('query');
        
        if (!$query || strlen($query) < 2) {
            echo json_encode(['success' => false, 'message' => 'Minimum 2 caractères requis']);
            exit;
        }
        
        try {
            $wp_db = $this->load->database('wordpress', TRUE);
            
            if (!$wp_db) {
                throw new Exception('Impossible de se connecter à la base WordPress');
            }
            
            // Récupérer les agences avec le nombre d'agents
            $agencies = $wp_db->select('DISTINCT agency_id, agency_name, COUNT(user_id) as agent_count')
                ->from($wp_db->dbprefix . 'crm_agents')
                ->like('agency_name', $query)
                ->where('agency_name IS NOT NULL')
                ->where('agency_name !=', '')
                ->group_by('agency_id, agency_name')
                ->order_by('agency_name', 'ASC')
                ->limit(10)
                ->get()->result();
            
            $filtered_agencies = [];
            foreach ($agencies as $agency) {
                $filtered_agencies[] = [
                    'id' => $agency->agency_id,
                    'name' => $agency->agency_name,
                    'agent_count' => $agency->agent_count,
                    'display' => $agency->agency_name . " ({$agency->agent_count} agent" . ($agency->agent_count > 1 ? 's' : '') . ")"
                ];
            }
            
            echo json_encode([
                'success' => true, 
                'agencies' => $filtered_agencies,
                'count' => count($filtered_agencies)
            ]);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false, 
                'message' => 'Erreur lors de la recherche: ' . $e->getMessage()
            ]);
        }
        
        exit;
    }

    /**
     * Version agents avec informations de contact complètes
     */
    public function search_agents_enhanced() {
        header('Content-Type: application/json');
        ob_clean();
        
        $agency_id = $this->input->post('agency_id');
        $query = $this->input->post('query');
        
        if (!$agency_id) {
            echo json_encode(['success' => false, 'message' => 'ID agence requis']);
            exit;
        }
        
        try {
            $wp_db = $this->load->database('wordpress', TRUE);
            
            if (!$wp_db) {
                throw new Exception('Impossible de se connecter à la base WordPress');
            }
            
            $wp_db->select('user_id, agent_name, agent_email, phone, mobile, position, agency_name')
                ->from($wp_db->dbprefix . 'crm_agents')
                ->where('agency_id', $agency_id)
                ->where('agent_name IS NOT NULL')
                ->where('agent_name !=', '');
            
            // Si une query est fournie, filtrer par nom
            if ($query && strlen($query) >= 2) {
                $wp_db->like('agent_name', $query);
            }
            
            $agents = $wp_db->order_by('agent_name', 'ASC')->limit(10)->get()->result();
            
            $filtered_agents = [];
            foreach ($agents as $agent) {
                $contact_info = [];
                if (!empty($agent->phone)) $contact_info[] = $agent->phone;
                if (!empty($agent->mobile)) $contact_info[] = $agent->mobile;
                if (!empty($agent->agent_email)) $contact_info[] = $agent->agent_email;
                
                $display_name = $agent->agent_name;
                if (!empty($agent->position)) {
                    $display_name .= " - " . $agent->position;
                }
                if (!empty($contact_info)) {
                    $display_name .= " (" . implode(' / ', array_slice($contact_info, 0, 2)) . ")";
                }
                
                $filtered_agents[] = [
                    'id' => $agent->user_id,
                    'name' => $agent->agent_name,
                    'display' => $display_name,
                    'email' => $agent->agent_email,
                    'phone' => $agent->phone,
                    'mobile' => $agent->mobile,
                    'position' => $agent->position,
                    'agency' => $agent->agency_name
                ];
            }
            
            echo json_encode(['success' => true, 'agents' => $filtered_agents]);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la recherche des agents: ' . $e->getMessage()]);
        }
        
        exit;
    }

    /**
     * Recherche d'agences avec gestion des rôles utilisateur
     */
    public function search_agencies_from_crm() {
        header('Content-Type: application/json');
        ob_clean();
        
        // Vérification de session sans redirection pour AJAX
        $CI =& get_instance();
        $CI->load->library('session');
        $isLoggedIn = $CI->session->userdata('isLoggedIn');
        
        if (!isset($isLoggedIn) || $isLoggedIn != TRUE) {
            echo json_encode(['success' => false, 'message' => 'Session expirée']);
            exit;
        }
        
        $query = $this->input->post('query');
        
        if (!$query || strlen($query) < 2) {
            echo json_encode(['success' => false, 'message' => 'Minimum 2 caractères requis']);
            exit;
        }
        
        try {
            $wp_db = $this->load->database('wordpress', TRUE);
            
            if (!$wp_db) {
                throw new Exception('Impossible de se connecter à la base WordPress');
            }
            
            $table_name = $wp_db->dbprefix . 'crm_agents';
            
            if (!$wp_db->table_exists('crm_agents')) {
                throw new Exception('Table crm_agents introuvable');
            }
            
            // Filtrage selon le rôle utilisateur
            $wp_db->select('DISTINCT agency_id, agency_name')
                ->from($table_name)
                ->like('agency_name', $query);
            
            // Si pas admin, limiter aux agences de l'utilisateur
            if ($this->isAdmin != 1 && !empty($this->vendorId)) {
                $wp_db->where('agency_id', $this->vendorId);
            }
            
            $agencies = $wp_db->limit(10)->get()->result();
            
            $filtered_agencies = [];
            foreach ($agencies as $agency) {
                if (!empty($agency->agency_name) && !empty($agency->agency_id)) {
                    $filtered_agencies[] = [
                        'id' => $agency->agency_id,
                        'name' => $agency->agency_name
                    ];
                }
            }
            
            echo json_encode([
                'success' => true, 
                'agencies' => $filtered_agencies,
                'count' => count($filtered_agencies)
            ]);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false, 
                'message' => 'Erreur lors de la recherche: ' . $e->getMessage()
            ]);
        }
        
        exit;
    }

    /**
     * Recherche d'agents avec gestion des rôles utilisateur
     */
    public function search_agents_from_crm() {
        header('Content-Type: application/json');
        ob_clean();
        
        // Vérification de session
        $CI =& get_instance();
        $CI->load->library('session');
        $isLoggedIn = $CI->session->userdata('isLoggedIn');
        
        if (!isset($isLoggedIn) || $isLoggedIn != TRUE) {
            echo json_encode(['success' => false, 'message' => 'Session expirée']);
            exit;
        }
        
        $agency_id = $this->input->post('agency_id');
        $query = $this->input->post('query');
        
        if (!$agency_id) {
            echo json_encode(['success' => false, 'message' => 'ID agence requis']);
            exit;
        }
        
        try {
            $wp_db = $this->load->database('wordpress', TRUE);
            
            $wp_db->select('user_id, agent_name, agent_email, agency_name')
                ->from($wp_db->dbprefix . 'crm_agents')
                ->where('agency_id', $agency_id);
            
            // Filtrer par nom d'agent si fourni
            if ($query && strlen($query) >= 2) {
                $wp_db->like('agent_name', $query);
            }
            
            $agents = $wp_db->limit(10)->get()->result();
            
            $filtered_agents = [];
            foreach ($agents as $agent) {
                $filtered_agents[] = [
                    'id' => $agent->user_id,
                    'name' => $agent->agent_name
                ];
            }
            
            echo json_encode(['success' => true, 'agents' => $filtered_agents]);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la recherche des agents: ' . $e->getMessage()]);
        }
        
        exit;
    }

    /**
     * Endpoint pour récupérer le contexte utilisateur connecté
     */
    public function get_user_context() {
        header('Content-Type: application/json');
        
        try {
            // Récupérer les données utilisateur depuis BaseController
            $context = [
                'user_id' => $this->vendorId,
                'name' => $this->name,
                'role' => $this->role,
                'role_text' => $this->roleText,
                'is_admin' => $this->isAdmin,
                'last_login' => $this->lastLogin,
                'wp_avatar' => $this->wp_avatar
            ];
            
            // Récupérer les informations agent/agence depuis la DB WordPress
            $wp_db = $this->load->database('wordpress', TRUE);
            
            $stmt = $wp_db->query("
                SELECT 
                    u.ID as user_id,
                    u.user_login,
                    u.user_email,
                    u.display_name,
                    p.ID as agent_post_id,
                    p.post_title as agent_name,
                    a.ID as agency_id,
                    a.post_title as agency_name,
                    MAX(CASE WHEN pm_contact.meta_key = 'fave_agent_position' THEN pm_contact.meta_value END) AS position
                FROM {$wp_db->dbprefix}users u
                LEFT JOIN {$wp_db->dbprefix}postmeta pm_email ON pm_email.meta_value = u.user_email
                LEFT JOIN {$wp_db->dbprefix}posts p ON (p.ID = pm_email.post_id AND p.post_type = 'houzez_agent')
                LEFT JOIN {$wp_db->dbprefix}postmeta pm_agency ON (pm_agency.post_id = p.ID AND pm_agency.meta_key = 'fave_agent_agencies')
                LEFT JOIN {$wp_db->dbprefix}posts a ON (a.ID = pm_agency.meta_value AND a.post_type = 'houzez_agency')
                LEFT JOIN {$wp_db->dbprefix}postmeta pm_contact ON pm_contact.post_id = p.ID
                WHERE u.ID = {$this->vendorId}
                GROUP BY u.ID, p.ID, a.ID
            ");
            
            $agent_info = $stmt->result();
            $context['agent_info'] = !empty($agent_info) ? $agent_info[0] : null;
            
            // Déterminer les permissions selon le rôle
            if ($this->isAdmin == 1) {
                $context['permissions'] = [
                    'can_choose_agency' => true,
                    'can_choose_agent' => true,
                    'auto_fill_agency' => false,
                    'auto_fill_agent' => false,
                    'description' => 'Admin - Accès complet à toutes les agences et agents'
                ];
            } elseif ($this->role === 'manager' && !empty($agent_info) && $agent_info[0]->agency_id) {
                $context['permissions'] = [
                    'can_choose_agency' => false,
                    'can_choose_agent' => true,
                    'auto_fill_agency' => true,
                    'auto_fill_agent' => false,
                    'agency_id' => $agent_info[0]->agency_id,
                    'agency_name' => $agent_info[0]->agency_name,
                    'description' => 'Manager - Limité à son agence'
                ];
            } elseif ($this->role === 'agent' && !empty($agent_info)) {
                $context['permissions'] = [
                    'can_choose_agency' => false,
                    'can_choose_agent' => false,
                    'auto_fill_agency' => true,
                    'auto_fill_agent' => true,
                    'agency_id' => $agent_info[0]->agency_id,
                    'agency_name' => $agent_info[0]->agency_name,
                    'agent_id' => $agent_info[0]->user_id,
                    'agent_name' => $agent_info[0]->agent_name,
                    'description' => 'Agent - Remplissage automatique'
                ];
            } else {
                $context['permissions'] = [
                    'can_choose_agency' => true,
                    'can_choose_agent' => true,
                    'auto_fill_agency' => false,
                    'auto_fill_agent' => false,
                    'description' => 'Utilisateur standard'
                ];
            }
            
            echo json_encode(['success' => true, 'context' => $context]);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
        
        exit;
    }
}
