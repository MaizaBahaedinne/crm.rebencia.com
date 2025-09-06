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
     * Version ultra-simple pour identifier le problème
     */
    public function test_basic_json() {
        // Pas de vérification de session, pas de DB, juste du JSON
        try {
            header('Content-Type: application/json');
            ob_clean(); // Nettoyer le buffer de sortie
            
            $query = isset($_POST['query']) ? $_POST['query'] : null;
            
            // Test avec données statiques
            $agencies = [
                ['id' => 18907, 'name' => 'Agence Ben arous'],
                ['id' => 12345, 'name' => 'Agence Test']
            ];
            
            $result = [
                'success' => true,
                'agencies' => $agencies,
                'query' => $query,
                'method' => 'test_basic_json',
                'timestamp' => time()
            ];
            
            echo json_encode($result);
            exit();
            
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage(),
                'file' => __FILE__,
                'line' => __LINE__
            ]);
            exit();
        }
    }

    /**
     * Test sans extends BaseController pour éviter isLoggedIn
     */
    public function test_no_base() {
        header('Content-Type: application/json');
        
        echo json_encode([
            'success' => true,
            'message' => 'Test sans BaseController réussi',
            'post_data' => $_POST,
            'get_data' => $_GET
        ]);
        exit();
    }

    /**
     * Version de test sans vérification de session
     */
    public function search_agencies_no_auth() {
        // Définir le header JSON en premier
        header('Content-Type: application/json');
        
        // Éviter toute sortie HTML non désirée
        ob_clean();
        
        $query = $this->input->post('query');
        
        if (!$query || strlen($query) < 2) {
            echo json_encode(['success' => false, 'message' => 'Minimum 2 caractères requis']);
            exit;
        }
        
        try {
            // Vérification de la connexion à la DB WordPress
            $wp_db = $this->load->database('wordpress', TRUE);
            
            if (!$wp_db) {
                throw new Exception('Impossible de se connecter à la base WordPress');
            }
            
            // Test de la table crm_agents
            $table_name = $wp_db->dbprefix . 'crm_agents';
            
            // Vérifier que la table existe
            if (!$wp_db->table_exists('crm_agents')) {
                throw new Exception('Table crm_agents introuvable');
            }
            
            // Récupérer les agences distinctes de la table crm_agents
            $agencies = $wp_db->select('DISTINCT agency_id, agency_name')
                ->from($table_name)
                ->like('agency_name', $query)
                ->limit(10)
                ->get()->result();
            
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
                'message' => 'Erreur lors de la recherche: ' . $e->getMessage(),
                'debug' => [
                    'query' => $query,
                    'table' => isset($table_name) ? $table_name : 'N/A'
                ]
            ]);
        }
        
        exit; // Importante pour éviter toute sortie supplémentaire
    }

    /**
     * Solution alternative : utiliser crm_agents pour agences ET agents
     * Version qui évite les redirections pour les appels AJAX
     */
    public function search_agencies_from_crm() {
        // Définir le header JSON en premier
        header('Content-Type: application/json');
        
        // Éviter toute sortie HTML non désirée
        ob_clean();
        
        // Vérification simple de la session sans redirection
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
            // Vérification de la connexion à la DB WordPress
            $wp_db = $this->load->database('wordpress', TRUE);
            
            if (!$wp_db) {
                throw new Exception('Impossible de se connecter à la base WordPress');
            }
            
            // Test de la table crm_agents
            $table_name = $wp_db->dbprefix . 'crm_agents';
            
            // Vérifier que la table existe
            if (!$wp_db->table_exists('crm_agents')) {
                throw new Exception('Table crm_agents introuvable');
            }
            
            // Récupérer les agences distinctes de la table crm_agents
            $agencies = $wp_db->select('DISTINCT agency_id, agency_name')
                ->from($table_name)
                ->like('agency_name', $query)
                ->limit(10)
                ->get()->result();
            
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
                'message' => 'Erreur lors de la recherche: ' . $e->getMessage(),
                'debug' => [
                    'query' => $query,
                    'table' => isset($table_name) ? $table_name : 'N/A'
                ]
            ]);
        }
        
        exit; // Importante pour éviter toute sortie supplémentaire
    }

    /**
     * Version agents sans authentification pour tests
     */
    public function search_agents_no_auth() {
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
            
            $wp_db->select('user_id, agent_name, agent_email, agency_name')
                ->from($wp_db->dbprefix . 'crm_agents')
                ->where('agency_id', $agency_id);
            
            // Si une query est fournie, filtrer par nom
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
     * Solution alternative : récupérer agents directement de crm_agents
     */
    public function search_agents_from_crm() {
        $this->isLoggedIn();
        
        $agency_id = $this->input->post('agency_id');
        $query = $this->input->post('query');
        
        if (!$agency_id) {
            echo json_encode(['success' => false, 'message' => 'ID agence requis']);
            return;
        }
        
        try {
            $wp_db = $this->load->database('wordpress', TRUE);
            
            $wp_db->select('user_id, agent_name, agent_email, agency_name')
                ->from($wp_db->dbprefix . 'crm_agents')
                ->where('agency_id', $agency_id);
            
            // Si une query est fournie, filtrer par nom
            if ($query && strlen($query) >= 2) {
                $wp_db->like('agent_name', $query);
            }
            
            $agents = $wp_db->get()->result();
            
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
    }

    /**
     * Test ultra-simple sans aucune dépendance
     */
    public function ping() {
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'pong', 'timestamp' => time()]);
    }

    /**
     * Version simplifiée pour tester sans DB
     */
    public function test_json_simple() {
        header('Content-Type: application/json');
        
        try {
            $query = $this->input->post('query');
            
            // Test avec données statiques pour éviter les erreurs DB
            $mock_agencies = [
                ['id' => 18907, 'name' => 'Agence Ben arous'],
                ['id' => 12345, 'name' => 'Agence Test']
            ];
            
            // Filtrer par query si fournie
            $filtered = [];
            if (!empty($query)) {
                foreach ($mock_agencies as $agency) {
                    if (stripos($agency['name'], $query) !== false) {
                        $filtered[] = $agency;
                    }
                }
            } else {
                $filtered = $mock_agencies;
            }
            
            echo json_encode([
                'success' => true,
                'agencies' => $filtered,
                'count' => count($filtered),
                'query' => $query,
                'debug' => 'Version simplifiée sans DB'
            ]);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Test simple pour débugger l'erreur 500
     */
    public function debug_agencies() {
        header('Content-Type: application/json');
        
        try {
            echo json_encode([
                'success' => true, 
                'message' => 'Test de base réussi',
                'php_version' => phpversion(),
                'post_data' => $this->input->post(),
                'session' => 'connecté (isLoggedIn() passé)'
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Affichage détaillé des données crm_agents pour une agence
     */
    public function debug_agency_details() {
        $agency_id = $this->input->get('agency_id') ?: 18907; // Par défaut Agence Ben arous
        
        echo "<h3>Détails de l'agence ID: {$agency_id}</h3>";
        
        try {
            $wp_db = $this->load->database('wordpress', TRUE);
            
            $agents = $wp_db->select('*')
                ->from($wp_db->dbprefix . 'crm_agents')
                ->where('agency_id', $agency_id)
                ->get()->result();
            
            if (empty($agents)) {
                echo "<p>Aucun agent trouvé pour cette agence.</p>";
                return;
            }
            
            echo "<h4>Agents trouvés: " . count($agents) . "</h4>";
            
            foreach ($agents as $agent) {
                echo "<div style='border: 1px solid #ccc; margin: 10px 0; padding: 10px; border-radius: 5px;'>";
                echo "<h5>" . htmlspecialchars($agent->agent_name) . " (ID: {$agent->user_id})</h5>";
                echo "<p><strong>Agence:</strong> " . htmlspecialchars($agent->agency_name) . "</p>";
                echo "<p><strong>Email:</strong> " . htmlspecialchars($agent->agent_email ?? 'N/A') . "</p>";
                echo "<p><strong>Téléphone:</strong> " . htmlspecialchars($agent->phone ?? 'N/A') . "</p>";
                echo "<p><strong>Mobile:</strong> " . htmlspecialchars($agent->mobile ?? 'N/A') . "</p>";
                echo "<p><strong>Position:</strong> " . htmlspecialchars($agent->position ?? 'N/A') . "</p>";
                echo "<p><strong>WhatsApp:</strong> " . htmlspecialchars($agent->whatsapp ?? 'N/A') . "</p>";
                echo "<p><strong>Date d'inscription:</strong> " . htmlspecialchars($agent->registration_date ?? 'N/A') . "</p>";
                echo "</div>";
            }
            
            // Afficher aussi la requête SQL complexe d'origine en commentaire
            echo "<hr><h4>Informations techniques</h4>";
            echo "<p>Cette table <code>crm_agents</code> est générée par la requête complexe suivante :</p>";
            echo "<details><summary>Voir la requête SQL complète</summary>";
            echo "<pre style='background: #f5f5f5; padding: 10px; font-size: 11px; overflow-x: auto;'>";
            echo htmlspecialchars("SELECT 
    u.ID AS user_id,
    u.user_login AS user_login,
    u.user_email AS user_email,
    u.user_status AS user_status,
    u.user_registered AS registration_date,
    p.ID AS agent_post_id,
    p.post_title AS agent_name,
    p.post_status AS post_status,
    pm_email.meta_value AS agent_email,
    a.ID AS agency_id,
    a.post_title AS agency_name,
    MAX(CASE WHEN pm_contact.meta_key = 'fave_agent_phone' THEN pm_contact.meta_value END) AS phone,
    MAX(CASE WHEN pm_contact.meta_key = 'fave_agent_mobile' THEN pm_contact.meta_value END) AS mobile,
    MAX(CASE WHEN pm_contact.meta_key = 'fave_agent_whatsapp' THEN pm_contact.meta_value END) AS whatsapp,
    MAX(CASE WHEN pm_contact.meta_key = 'fave_agent_position' THEN pm_contact.meta_value END) AS position
FROM wp_Hrg8P_users u
LEFT JOIN wp_Hrg8P_postmeta pm_email ON pm_email.meta_value = u.user_email
LEFT JOIN wp_Hrg8P_posts p ON p.ID = pm_email.post_id AND p.post_type = 'houzez_agent'
LEFT JOIN wp_Hrg8P_postmeta pm_agency ON pm_agency.post_id = p.ID AND pm_agency.meta_key = 'fave_agent_agencies'
LEFT JOIN wp_Hrg8P_posts a ON a.ID = pm_agency.meta_value AND a.post_type = 'houzez_agency'
LEFT JOIN wp_Hrg8P_postmeta pm_contact ON pm_contact.post_id = p.ID
WHERE p.post_type = 'houzez_agent'
GROUP BY u.ID, p.ID, a.ID");
            echo "</pre></details>";
            
        } catch (Exception $e) {
            echo "<p><strong>Erreur:</strong> " . $e->getMessage() . "</p>";
        }
    }

    /**
     * Test simple pour vérifier le mapping agences-agents
     */
    public function test_agency_agent_mapping() {
        $this->isLoggedIn();
        
        echo "<h3>Test: Mapping Agences WordPress ↔ Agents HOUZEZ</h3>";
        
        try {
            $wp_db = $this->load->database('wordpress', TRUE);
            
            $users_table = $wp_db->dbprefix . 'users';
            $usermeta_table = $wp_db->dbprefix . 'usermeta';
            $crm_agents_table = $wp_db->dbprefix . 'crm_agents';
            $capabilities_key = $wp_db->dbprefix . 'capabilities';
            
            echo "<h4>1. Agences WordPress</h4>";
            
            // Récupérer les agences WordPress
            $wp_agencies = $wp_db->select('u.ID, u.user_login, u.display_name')
                ->from($users_table . ' u')
                ->join($usermeta_table . ' m', 'u.ID = m.user_id')
                ->where('m.meta_key', $capabilities_key)
                ->like('m.meta_value', 'houzez_agency')
                ->get()->result();
            
            echo "<table border='1' style='border-collapse: collapse;'>";
            echo "<tr><th>WP User ID</th><th>Login</th><th>Display Name</th><th>Agents associés</th></tr>";
            
            foreach ($wp_agencies as $agency) {
                // Chercher les agents pour cette agence dans crm_agents
                $agents_count = $wp_db->select('COUNT(*) as count')
                    ->from($crm_agents_table)
                    ->where('agency_id', $agency->ID)
                    ->get()->row();
                
                echo "<tr>";
                echo "<td>{$agency->ID}</td>";
                echo "<td>{$agency->user_login}</td>";
                echo "<td>" . htmlspecialchars($agency->display_name) . "</td>";
                echo "<td>" . ($agents_count->count ?? 0) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            
            echo "<h4>2. Tous les agents dans crm_agents</h4>";
            
            $all_agents = $wp_db->select('user_id, agent_name, agency_id, agency_name')
                ->from($crm_agents_table)
                ->get()->result();
            
            echo "<table border='1' style='border-collapse: collapse;'>";
            echo "<tr><th>User ID</th><th>Agent Name</th><th>Agency ID (dans crm_agents)</th><th>Agency Name</th><th>WP Agency existe?</th></tr>";
            
            foreach ($all_agents as $agent) {
                // Vérifier si l'agency_id correspond à un utilisateur WordPress
                $wp_agency_exists = $wp_db->select('display_name')
                    ->from($users_table)
                    ->where('ID', $agent->agency_id)
                    ->get()->row();
                
                echo "<tr>";
                echo "<td>{$agent->user_id}</td>";
                echo "<td>" . htmlspecialchars($agent->agent_name) . "</td>";
                echo "<td>{$agent->agency_id}</td>";
                echo "<td>" . htmlspecialchars($agent->agency_name) . "</td>";
                echo "<td>" . ($wp_agency_exists ? "✅ " . htmlspecialchars($wp_agency_exists->display_name) : "❌ Non trouvé") . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            
        } catch (Exception $e) {
            echo "<p><strong>Erreur:</strong> " . $e->getMessage() . "</p>";
        }
    }
}
