<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'libraries/BaseController.php';

class Client extends BaseController {
    public function __construct(){
        parent::__construct();
        $this->isLoggedIn();
        $this->load->model('wp_client_model');
        $this->load->model('client_model');
        $this->load->model('agency_model');
        $this->load->model('agent_model');
        $this->load->library(['form_validation','session']);
        $this->load->helper(['url','form']);
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
     * Solution alternative : utiliser crm_agents pour agences ET agents
     */
    public function search_agencies_from_crm() {
        $this->isLoggedIn();
        
        $query = $this->input->post('query');
        
        if (!$query || strlen($query) < 2) {
            echo json_encode(['success' => false, 'message' => 'Minimum 2 caractères requis']);
            return;
        }
        
        try {
            $wp_db = $this->load->database('wordpress', TRUE);
            
            // Récupérer les agences distinctes de la table crm_agents
            $agencies = $wp_db->select('DISTINCT agency_id, agency_name')
                ->from($wp_db->dbprefix . 'crm_agents')
                ->like('agency_name', $query)
                ->get()->result();
            
            $filtered_agencies = [];
            foreach ($agencies as $agency) {
                $filtered_agencies[] = [
                    'id' => $agency->agency_id,
                    'name' => $agency->agency_name
                ];
            }
            
            echo json_encode(['success' => true, 'agencies' => $filtered_agencies]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la recherche: ' . $e->getMessage()]);
        }
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
