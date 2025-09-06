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
     * Recherche d'agences pour autocomplétion - Version simplifiée
     */
    public function search_agencies_simple() {
        try {
            header('Content-Type: application/json');
            
            $query = isset($_POST['query']) ? $_POST['query'] : null;
            
            if (!$query || strlen($query) < 2) {
                echo json_encode(['success' => false, 'message' => 'Minimum 2 caractères requis']);
                exit;
            }
            
            // Données statiques pour test
            $all_agencies = [
                ['id' => 18907, 'name' => 'Agence Ben arous', 'agent_count' => 1],
                ['id' => 12345, 'name' => 'Agence Test', 'agent_count' => 2],
                ['id' => 67890, 'name' => 'Agence Centre Ville', 'agent_count' => 3]
            ];
            
            // Filtrer par query
            $filtered_agencies = [];
            foreach ($all_agencies as $agency) {
                if (stripos($agency['name'], $query) !== false) {
                    $agency['display'] = $agency['name'] . " ({$agency['agent_count']} agent" . ($agency['agent_count'] > 1 ? 's' : '') . ")";
                    $filtered_agencies[] = $agency;
                }
            }
            
            echo json_encode([
                'success' => true,
                'agencies' => $filtered_agencies,
                'count' => count($filtered_agencies),
                'query' => $query,
                'debug' => 'Version statique simplifiée'
            ]);
            exit;
            
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage(),
                'file' => __FILE__,
                'line' => __LINE__
            ]);
            exit;
        }
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
     * Agents simplifiés avec données statiques
     */
    public function search_agents_simple() {
        try {
            header('Content-Type: application/json');
            
            $agency_id = isset($_POST['agency_id']) ? $_POST['agency_id'] : null;
            $query = isset($_POST['query']) ? $_POST['query'] : null;
            
            if (!$agency_id) {
                echo json_encode(['success' => false, 'message' => 'ID agence requis']);
                exit;
            }
            
            // Données statiques par agence
            $agents_by_agency = [
                18907 => [
                    ['id' => 123, 'name' => 'Montasar Barkouti', 'email' => 'montasar@example.com', 'phone' => '+216 12345678', 'position' => 'Agent Commercial'],
                    ['id' => 124, 'name' => 'Ahmed Ben Ali', 'email' => 'ahmed@example.com', 'phone' => '+216 87654321', 'position' => 'Responsable Ventes']
                ],
                12345 => [
                    ['id' => 125, 'name' => 'Sarah Trabelsi', 'email' => 'sarah@example.com', 'phone' => '+216 11111111', 'position' => 'Agent'],
                    ['id' => 126, 'name' => 'Karim Souissi', 'email' => 'karim@example.com', 'phone' => '+216 22222222', 'position' => 'Manager']
                ]
            ];
            
            $agents = isset($agents_by_agency[$agency_id]) ? $agents_by_agency[$agency_id] : [];
            
            // Filtrer par query si fournie
            if ($query && strlen($query) >= 2) {
                $agents = array_filter($agents, function($agent) use ($query) {
                    return stripos($agent['name'], $query) !== false;
                });
                $agents = array_values($agents); // Réindexer
            }
            
            // Formater pour l'affichage
            $filtered_agents = [];
            foreach ($agents as $agent) {
                $display = $agent['name'];
                if ($agent['position']) {
                    $display .= " - " . $agent['position'];
                }
                if ($agent['phone']) {
                    $display .= " (" . $agent['phone'] . ")";
                }
                
                $filtered_agents[] = [
                    'id' => $agent['id'],
                    'name' => $agent['name'],
                    'display' => $display,
                    'email' => $agent['email'],
                    'phone' => $agent['phone'],
                    'position' => $agent['position']
                ];
            }
            
            echo json_encode(['success' => true, 'agents' => $filtered_agents]);
            exit;
            
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            exit;
        }
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
     * Test ultra-simple sans aucune dépendance
     */
    public function test() {
        try {
            header('Content-Type: application/json');
            
            $response = [
                'success' => true,
                'message' => 'Ajax controller test réussi',
                'timestamp' => time(),
                'post' => $_POST,
                'get' => $_GET
            ];
            
            echo json_encode($response);
            exit;
            
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
            exit;
        }
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
