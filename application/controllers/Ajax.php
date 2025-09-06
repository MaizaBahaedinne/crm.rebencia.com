<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Contrôleur spécialement pour les appels AJAX
 * N'hérite pas de BaseController pour éviter les redirections automatiques
 */
class Ajax extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        // Pas d'appel à isLoggedIn() pour éviter les redirections
        $this->load->library('session');
        $this->load->library('input');
        $this->load->helper('url');
        $this->load->database();
    }
    
    /**
     * Vérification simple de session pour AJAX
     */
    private function isLoggedInAjax() {
        // Utilisation directe des superglobales PHP si CodeIgniter pose problème
        if (isset($_SESSION) && isset($_SESSION['isLoggedIn'])) {
            return $_SESSION['isLoggedIn'] == TRUE;
        }
        // Fallback avec CodeIgniter si disponible
        if (isset($this->session)) {
            $isLoggedIn = $this->session->userdata('isLoggedIn');
            return (isset($isLoggedIn) && $isLoggedIn == TRUE);
        }
        return false;
    }
    
    /**
     * Recherche d'agences pour autocomplétion
     */
    public function search_agencies() {
        header('Content-Type: application/json');
        ob_clean();
        
        // Vérification simple sans redirection
        if (!$this->isLoggedInAjax()) {
            echo json_encode(['success' => false, 'message' => 'Session expirée']);
            exit;
        }
        
        $query = isset($_POST['query']) ? $_POST['query'] : null;
        
        if (!$query || strlen($query) < 2) {
            echo json_encode(['success' => false, 'message' => 'Minimum 2 caractères requis']);
            exit;
        }
        
        try {
            // Charger la DB WordPress
            $wp_db = $this->load->database('wordpress', TRUE);
            
            if (!$wp_db) {
                throw new Exception('Connexion WordPress échouée');
            }
            
            // Récupérer les agences
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
                'message' => 'Erreur: ' . $e->getMessage()
            ]);
        }
        
        exit;
    }
    
    /**
     * Recherche d'agents par agence
     */
    public function search_agents() {
        header('Content-Type: application/json');
        ob_clean();
        
        if (!$this->isLoggedInAjax()) {
            echo json_encode(['success' => false, 'message' => 'Session expirée']);
            exit;
        }
        
        $agency_id = isset($_POST['agency_id']) ? $_POST['agency_id'] : null;
        $query = isset($_POST['query']) ? $_POST['query'] : null;
        
        if (!$agency_id) {
            echo json_encode(['success' => false, 'message' => 'ID agence requis']);
            exit;
        }
        
        try {
            $wp_db = $this->load->database('wordpress', TRUE);
            
            $wp_db->select('user_id, agent_name, agent_email, phone, mobile, position, agency_name')
                ->from($wp_db->dbprefix . 'crm_agents')
                ->where('agency_id', $agency_id)
                ->where('agent_name IS NOT NULL')
                ->where('agent_name !=', '');
            
            if ($query && strlen($query) >= 2) {
                $wp_db->like('agent_name', $query);
            }
            
            $agents = $wp_db->order_by('agent_name', 'ASC')->limit(10)->get()->result();
            
            $filtered_agents = [];
            foreach ($agents as $agent) {
                $contact_info = [];
                if (!empty($agent->phone)) $contact_info[] = $agent->phone;
                if (!empty($agent->mobile)) $contact_info[] = $agent->mobile;
                
                $display_name = $agent->agent_name;
                if (!empty($agent->position)) {
                    $display_name .= " - " . $agent->position;
                }
                if (!empty($contact_info)) {
                    $display_name .= " (" . implode(' / ', array_slice($contact_info, 0, 1)) . ")";
                }
                
                $filtered_agents[] = [
                    'id' => $agent->user_id,
                    'name' => $agent->agent_name,
                    'display' => $display_name,
                    'email' => $agent->agent_email,
                    'phone' => $agent->phone,
                    'mobile' => $agent->mobile,
                    'position' => $agent->position
                ];
            }
            
            echo json_encode(['success' => true, 'agents' => $filtered_agents]);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()]);
        }
        
        exit;
    }
    
    /**
     * Test simple pour vérifier que le contrôleur fonctionne
     */
    public function ping() {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => 'Ajax controller working',
            'timestamp' => time()
        ]);
        exit;
    }
}
