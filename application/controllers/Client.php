
<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'libraries/BaseController.php';
/***
 * @property CI_Input $input
 * @property CI_Form_validation $form_validation
 * @property Wp_client_model $client_model
 */
class Client extends BaseController {
    public function __construct(){
        parent::__construct();
        $this->isLoggedIn();
    // Remplace par clients WordPress (Houzez)
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
    
    public function crm_cleints(){
        $this->isLoggedIn();
        $filters=$this->_filters();
        $data=$this->global;
        $data['pageTitle']='Clients CRM';
        $data['filters']=$filters;
        $data['clients']=$this->client_model->all(1000,0,$filters);
        $this->loadViews('client/list_grid',$data,$data,NULL);
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
            $this->client_model->insert_client($data);
            redirect('client');
        } else {
            $data = $this->global;
            $data['pageTitle'] = 'Ajouter un client';
            // Test avec des données factices si les modèles ne fonctionnent pas
            try {
                $data['agencies'] = $this->agency_model->get_all_agencies();
            } catch (Exception $e) {
                $data['agencies'] = [];
            }
            try {
                $data['agents'] = $this->agent_model->get_all_agents();
            } catch (Exception $e) {
                $data['agents'] = [];
            }
            // Données factices pour tester
            if (empty($data['agencies'])) {
                $data['agencies'] = [
                    (object)['id' => 1, 'nom' => 'Agence Test 1'],
                    (object)['id' => 2, 'nom' => 'Agence Test 2']
                ];
            }
            if (empty($data['agents'])) {
                $data['agents'] = [
                    (object)['id' => 1, 'nom' => 'Agent Test 1'],
                    (object)['id' => 2, 'nom' => 'Agent Test 2']
                ];
            }
            $this->loadViews('client/form', $data, NULL, NULL);
        }
    }

    public function edit($id) {
        $this->isLoggedIn();
        $client = $this->client_model->get_client($id);
        $data = $this->global;
        $data['pageTitle'] = 'Modifier un client';
        $data['client'] = $client;
        // Test avec des données factices si les modèles ne fonctionnent pas
        try {
            $data['agencies'] = $this->agency_model->get_all_agencies();
        } catch (Exception $e) {
            $data['agencies'] = [];
        }
        try {
            $data['agents'] = $this->agent_model->get_all_agents();
        } catch (Exception $e) {
            $data['agents'] = [];
        }
        // Données factices pour tester
        if (empty($data['agencies'])) {
            $data['agencies'] = [
                (object)['id' => 1, 'nom' => 'Agence Test 1'],
                (object)['id' => 2, 'nom' => 'Agence Test 2']
            ];
        }
        if (empty($data['agents'])) {
            $data['agents'] = [
                (object)['id' => 1, 'nom' => 'Agent Test 1'],
                (object)['id' => 2, 'nom' => 'Agent Test 2']
            ];
        }
        $this->loadViews('client/form', $data, NULL, NULL);
    }

    public function update($id) {
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
            $this->client_model->update_client($id, $data);
            redirect('client');
        }
    }

    public function delete($id) {
        $this->isLoggedIn();
    $this->load->model('client_model');
    $this->client_model->delete_client($id);
        redirect('client');
    }

    /**
     * Méthode AJAX pour la recherche d'agences (autocomplétion)
     */
    public function search_agencies() {
        $this->isLoggedIn();
        
        $query = $this->input->post('query');
        
        if (!$query || strlen($query) < 2) {
            echo json_encode(['success' => false, 'message' => 'Minimum 2 caractères requis']);
            return;
        }
        
        try {
            $all_agencies = $this->agency_model->get_all_agencies();
            $filtered_agencies = [];
            
            foreach ($all_agencies as $agency) {
                $agency_id = isset($agency->ID) ? $agency->ID : (isset($agency->id) ? $agency->id : '');
                $agency_name = isset($agency->display_name) ? $agency->display_name : (isset($agency->nom) ? $agency->nom : (isset($agency->name) ? $agency->name : (isset($agency->libelle) ? $agency->libelle : 'Agence')));
                
                // Recherche insensible à la casse
                if (stripos($agency_name, $query) !== false) {
                    $filtered_agencies[] = [
                        'id' => $agency_id,
                        'name' => $agency_name
                    ];
                }
            }
            
            echo json_encode(['success' => true, 'agencies' => $filtered_agencies]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la recherche: ' . $e->getMessage()]);
        }
    }

    /**
     * Test simple pour vérifier les données HOUZEZ
     */
    public function test_houzez_data() {
        $this->isLoggedIn();
        
        echo "<h3>Test: Données HOUZEZ directes</h3>";
        
        try {
            // Charger la DB WordPress directement
            $wp_db = $this->load->database('wordpress', TRUE);
            
            $users_table = $wp_db->dbprefix . 'users';
            $usermeta_table = $wp_db->dbprefix . 'usermeta';
            $capabilities_key = $wp_db->dbprefix . 'capabilities';
            
            echo "<h4>1. Recherche d'agences (méthode simple)</h4>";
            
            // Recherche simple des agences
            $agencies = $wp_db->select('u.ID, u.user_login, u.display_name, u.user_email')
                ->from($users_table . ' u')
                ->join($usermeta_table . ' m', 'u.ID = m.user_id')
                ->where('m.meta_key', $capabilities_key)
                ->like('m.meta_value', 'houzez_agency')
                ->get()->result();
            
            echo "<p><strong>Agences trouvées:</strong> " . count($agencies) . "</p>";
            
            if (!empty($agencies)) {
                echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
                echo "<tr><th>ID</th><th>Login</th><th>Display Name</th><th>Email</th></tr>";
                foreach ($agencies as $agency) {
                    echo "<tr>";
                    echo "<td>{$agency->ID}</td>";
                    echo "<td>{$agency->user_login}</td>";
                    echo "<td>{$agency->display_name}</td>";
                    echo "<td>{$agency->user_email}</td>";
                    echo "</tr>";
                }
                echo "</table>";
                
                // Tester avec la première agence
                $first_agency = $agencies[0];
                echo "<h4>2. Recherche d'agents pour l'agence ID {$first_agency->ID}</h4>";
                
                $agents = $wp_db->select('u.ID, u.user_login, u.display_name, u.user_email')
                    ->from($users_table . ' u')
                    ->join($usermeta_table . ' m1', 'u.ID = m1.user_id')
                    ->join($usermeta_table . ' m2', 'u.ID = m2.user_id')
                    ->where('m1.meta_key', $capabilities_key)
                    ->like('m1.meta_value', 'houzez_agent')
                    ->where('m2.meta_key', 'houzez_agency_id')
                    ->where('m2.meta_value', $first_agency->ID)
                    ->get()->result();
                
                echo "<p><strong>Agents trouvés:</strong> " . count($agents) . "</p>";
                
                if (!empty($agents)) {
                    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
                    echo "<tr><th>ID</th><th>Login</th><th>Display Name</th><th>Email</th></tr>";
                    foreach ($agents as $agent) {
                        echo "<tr>";
                        echo "<td>{$agent->ID}</td>";
                        echo "<td>{$agent->user_login}</td>";
                        echo "<td>{$agent->display_name}</td>";
                        echo "<td>{$agent->user_email}</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                }
            }
            
        } catch (Exception $e) {
            echo "<p><strong>Erreur:</strong> " . $e->getMessage() . "</p>";
        }
    }

    /**
     * Méthode de debug pour examiner la structure de la DB WordPress
     */
    public function debug_wordpress_structure() {
        $this->isLoggedIn();
        
        echo "<h3>Debug: Structure WordPress/HOUZEZ</h3>";
        
        try {
            // Charger la DB WordPress
            $wp_db = $this->load->database('wordpress', TRUE);
            
            echo "<h4>1. Configuration de connexion</h4>";
            echo "<p><strong>Database:</strong> " . $wp_db->database . "</p>";
            echo "<p><strong>Prefix:</strong> " . $wp_db->dbprefix . "</p>";
            
            // Tester la connexion
            echo "<h4>2. Test de connexion</h4>";
            $tables = $wp_db->list_tables();
            echo "<p><strong>Nombre de tables trouvées:</strong> " . count($tables) . "</p>";
            
            // Vérifier les tables users et usermeta
            $users_table = $wp_db->dbprefix . 'users';
            $usermeta_table = $wp_db->dbprefix . 'usermeta';
            
            echo "<h4>3. Tables utilisateurs</h4>";
            if (in_array($users_table, $tables)) {
                $user_count = $wp_db->count_all($users_table);
                echo "<p>✅ Table <strong>$users_table</strong> existe avec $user_count utilisateurs</p>";
            } else {
                echo "<p>❌ Table <strong>$users_table</strong> n'existe pas</p>";
            }
            
            if (in_array($usermeta_table, $tables)) {
                $meta_count = $wp_db->count_all($usermeta_table);
                echo "<p>✅ Table <strong>$usermeta_table</strong> existe avec $meta_count métadonnées</p>";
            } else {
                echo "<p>❌ Table <strong>$usermeta_table</strong> n'existe pas</p>";
            }
            
            // Vérifier les rôles HOUZEZ
            echo "<h4>4. Rôles HOUZEZ</h4>";
            $capabilities_key = $wp_db->dbprefix . 'capabilities';
            
            $agency_roles = $wp_db->select('COUNT(*) as count')
                ->from($usermeta_table)
                ->where('meta_key', $capabilities_key)
                ->like('meta_value', 'houzez_agency')
                ->get()->row();
            
            $agent_roles = $wp_db->select('COUNT(*) as count')
                ->from($usermeta_table)
                ->where('meta_key', $capabilities_key)
                ->like('meta_value', 'houzez_agent')
                ->get()->row();
            
            echo "<p><strong>Utilisateurs avec rôle houzez_agency:</strong> " . ($agency_roles->count ?? 0) . "</p>";
            echo "<p><strong>Utilisateurs avec rôle houzez_agent:</strong> " . ($agent_roles->count ?? 0) . "</p>";
            
            // Examiner quelques exemples de capabilities
            echo "<h4>5. Exemples de capabilities</h4>";
            $sample_caps = $wp_db->select('user_id, meta_value')
                ->from($usermeta_table)
                ->where('meta_key', $capabilities_key)
                ->limit(5)
                ->get()->result();
            
            if (!empty($sample_caps)) {
                echo "<table border='1' style='border-collapse: collapse;'>";
                echo "<tr><th>User ID</th><th>Capabilities</th></tr>";
                foreach ($sample_caps as $cap) {
                    echo "<tr><td>{$cap->user_id}</td><td>" . htmlspecialchars($cap->meta_value) . "</td></tr>";
                }
                echo "</table>";
            }
            
            // Vérifier les meta_keys liés aux agences
            echo "<h4>6. Meta keys des agences</h4>";
            $agency_meta_keys = $wp_db->select('DISTINCT meta_key, COUNT(*) as count')
                ->from($usermeta_table)
                ->like('meta_key', 'agency')
                ->or_like('meta_key', 'houzez_agency')
                ->group_by('meta_key')
                ->get()->result();
            
            if (!empty($agency_meta_keys)) {
                echo "<table border='1' style='border-collapse: collapse;'>";
                echo "<tr><th>Meta Key</th><th>Count</th></tr>";
                foreach ($agency_meta_keys as $meta) {
                    echo "<tr><td>{$meta->meta_key}</td><td>{$meta->count}</td></tr>";
                }
                echo "</table>";
            } else {
                echo "<p>Aucune meta_key liée aux agences trouvée</p>";
            }
            
        } catch (Exception $e) {
            echo "<p><strong>Erreur:</strong> " . $e->getMessage() . "</p>";
        }
    }

    /**
     * Méthode de debug pour lister toutes les agences
     */
    public function debug_agencies() {
        $this->isLoggedIn();
        
        echo "<h3>Debug: Liste des agences</h3>";
        
        try {
            $agencies = $this->agency_model->get_all_agencies();
            
            echo "<p><strong>Nombre d'agences trouvées:</strong> " . count($agencies) . "</p>";
            
            if (!empty($agencies)) {
                echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
                echo "<tr><th>ID</th><th>Login</th><th>Display Name</th><th>Email</th><th>Test Agents</th></tr>";
                
                foreach ($agencies as $agency) {
                    $agency_id = $agency->ID ?? 'N/A';
                    echo "<tr>";
                    echo "<td>$agency_id</td>";
                    echo "<td>" . ($agency->user_login ?? 'N/A') . "</td>";
                    echo "<td>" . ($agency->display_name ?? 'N/A') . "</td>";
                    echo "<td>" . ($agency->user_email ?? 'N/A') . "</td>";
                    echo "<td><a href='" . base_url("client/debug_agents_by_agency?agency_id=$agency_id") . "' target='_blank'>Voir agents</a></td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p><em>Aucune agence trouvée.</em></p>";
            }
            
        } catch (Exception $e) {
            echo "<p><strong>Erreur:</strong> " . $e->getMessage() . "</p>";
        }
    }

    /**
     * Méthode de debug pour vérifier les agents d'une agence
     */
    public function debug_agents_by_agency() {
        $this->isLoggedIn();
        
        $agency_id = $this->input->get('agency_id');
        
        if (!$agency_id) {
            echo "Paramètre agency_id requis";
            return;
        }
        
        echo "<h3>Debug: Agents de l'agence ID = $agency_id</h3>";
        
        try {
            $agents = $this->agent_model->get_agents_by_agency($agency_id);
            
            echo "<p><strong>Nombre d'agents trouvés:</strong> " . count($agents) . "</p>";
            
            if (!empty($agents)) {
                echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
                echo "<tr><th>ID</th><th>Login</th><th>Display Name</th><th>Email</th><th>Full Name</th><th>Agency ID</th></tr>";
                
                foreach ($agents as $agent) {
                    echo "<tr>";
                    echo "<td>" . ($agent->ID ?? 'N/A') . "</td>";
                    echo "<td>" . ($agent->user_login ?? 'N/A') . "</td>";
                    echo "<td>" . ($agent->display_name ?? 'N/A') . "</td>";
                    echo "<td>" . ($agent->user_email ?? 'N/A') . "</td>";
                    echo "<td>" . ($agent->full_name ?? 'N/A') . "</td>";
                    echo "<td>" . ($agent->houzez_agency_id ?? 'N/A') . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p><em>Aucun agent trouvé pour cette agence.</em></p>";
            }
            
        } catch (Exception $e) {
            echo "<p><strong>Erreur:</strong> " . $e->getMessage() . "</p>";
        }
    }

    /**
     * Méthode AJAX pour la recherche d'agents par agence (autocomplétion)
     */
    public function search_agents_by_agency() {
        $this->isLoggedIn();
        
        $agency_id = $this->input->post('agency_id');
        $query = $this->input->post('query');
        
        if (!$agency_id) {
            echo json_encode(['success' => false, 'message' => 'ID agence requis']);
            return;
        }
        
        try {
            $agents = $this->agent_model->get_agents_by_agency($agency_id);
            $filtered_agents = [];
            
            foreach ($agents as $agent) {
                $agent_id = isset($agent->ID) ? $agent->ID : (isset($agent->id) ? $agent->id : '');
                $agent_name = isset($agent->full_name) ? $agent->full_name : (isset($agent->display_name) ? $agent->display_name : (isset($agent->nom) ? $agent->nom : (isset($agent->name) ? $agent->name : (isset($agent->prenom) ? $agent->prenom : 'Agent'))));
                
                // Si pas de query ou si le nom correspond à la recherche
                if (!$query || strlen($query) < 2 || stripos($agent_name, $query) !== false) {
                    $filtered_agents[] = [
                        'id' => $agent_id,
                        'name' => $agent_name
                    ];
                }
            }
            
            echo json_encode(['success' => true, 'agents' => $filtered_agents]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la recherche des agents: ' . $e->getMessage()]);
        }
    }

    /**
     * Méthode AJAX pour récupérer les agents d'une agence
     */
    public function get_agents_by_agency() {
        $this->isLoggedIn();
        
        $agency_id = $this->input->post('agency_id');
        
        if (!$agency_id) {
            echo json_encode(['success' => false, 'message' => 'ID agence requis']);
            return;
        }
        
        try {
            $agents = $this->agent_model->get_agents_by_agency($agency_id);
            
            $agents_data = [];
            foreach ($agents as $agent) {
                $agent_id = isset($agent->ID) ? $agent->ID : (isset($agent->id) ? $agent->id : '');
                $agent_name = isset($agent->full_name) ? $agent->full_name : (isset($agent->display_name) ? $agent->display_name : (isset($agent->nom) ? $agent->nom : (isset($agent->name) ? $agent->name : (isset($agent->prenom) ? $agent->prenom : 'Agent'))));
                
                $agents_data[] = [
                    'id' => $agent_id,
                    'name' => $agent_name
                ];
            }
            
            echo json_encode(['success' => true, 'agents' => $agents_data]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Erreur lors du chargement des agents: ' . $e->getMessage()]);
        }
    }






}
