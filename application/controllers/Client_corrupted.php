
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
            
            echo "<h4>3. Recommandation</h4>";
            echo "<p>D'après les données, il semble que:</p>";
            echo "<ul>";
            echo "<li><strong>agency_id</strong> dans crm_agents fait référence aux <strong>posts d'agences</strong> (ID 18907)</li>";
            echo "<li>Pas aux <strong>utilisateurs WordPress</strong> (ID 3, 12)</li>";
            echo "<li>Il faut soit:</li>";
            echo "<ul>";
            echo "<li>Trouver la table de correspondance entre posts d'agences et utilisateurs WordPress</li>";
            echo "<li>Ou utiliser directement les données de crm_agents pour l'autocomplétion</li>";
            echo "</ul>";
            echo "</ul>";
            
        } catch (Exception $e) {
            echo "<p><strong>Erreur:</strong> " . $e->getMessage() . "</p>";
        }
    }

    /**
     * Debug pour examiner la table crm_agents
     */
    public function debug_crm_agents_table() {
        $this->isLoggedIn();
        
        echo "<h3>Debug: Table wp_Hrg8P_crm_agents</h3>";
        
        try {
            // Charger la DB WordPress directement
            $wp_db = $this->load->database('wordpress', TRUE);
            
            $crm_agents_table = $wp_db->dbprefix . 'crm_agents';
            
            echo "<h4>1. Vérification de l'existence de la table</h4>";
            $tables = $wp_db->list_tables();
            
            if (in_array($crm_agents_table, $tables)) {
                echo "<p>✅ Table <strong>$crm_agents_table</strong> existe</p>";
                
                // Compter les enregistrements
                $count = $wp_db->count_all($crm_agents_table);
                echo "<p><strong>Nombre d'agents dans la table:</strong> $count</p>";
                
                if ($count > 0) {
                    echo "<h4>2. Structure de la table</h4>";
                    
                    // Récupérer la structure
                    $fields = $wp_db->field_data($crm_agents_table);
                    echo "<table border='1' style='border-collapse: collapse;'>";
                    echo "<tr><th>Nom du champ</th><th>Type</th><th>Max Length</th></tr>";
                    foreach ($fields as $field) {
                        echo "<tr>";
                        echo "<td>{$field->name}</td>";
                        echo "<td>{$field->type}</td>";
                        echo "<td>{$field->max_length}</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                    
                    echo "<h4>3. Exemples de données</h4>";
                    
                    // Récupérer quelques enregistrements
                    $sample_agents = $wp_db->select('*')
                        ->from($crm_agents_table)
                        ->limit(10)
                        ->get()->result();
                    
                    if (!empty($sample_agents)) {
                        // Créer un tableau HTML avec toutes les colonnes
                        $first_agent = $sample_agents[0];
                        $columns = array_keys((array)$first_agent);
                        
                        echo "<table border='1' style='border-collapse: collapse; width: 100%; font-size: 12px;'>";
                        echo "<tr>";
                        foreach ($columns as $column) {
                            echo "<th>$column</th>";
                        }
                        echo "</tr>";
                        
                        foreach ($sample_agents as $agent) {
                            echo "<tr>";
                            foreach ($columns as $column) {
                                $value = $agent->$column ?? '';
                                echo "<td>" . htmlspecialchars($value) . "</td>";
                            }
                            echo "</tr>";
                        }
                        echo "</table>";
                    }
                    
                    echo "<h4>4. Recherche par agence</h4>";
                    
                    // Chercher les agents de l'agence 3
                    $agents_agency_3 = $wp_db->select('*')
                        ->from($crm_agents_table)
                        ->where('agency_id', 3)
                        ->get()->result();
                    
                    echo "<p><strong>Agents pour agence ID 3:</strong> " . count($agents_agency_3) . "</p>";
                    
                    if (!empty($agents_agency_3)) {
                        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
                        $columns = array_keys((array)$agents_agency_3[0]);
                        echo "<tr>";
                        foreach ($columns as $column) {
                            echo "<th>$column</th>";
                        }
                        echo "</tr>";
                        
                        foreach ($agents_agency_3 as $agent) {
                            echo "<tr>";
                            foreach ($columns as $column) {
                                $value = $agent->$column ?? '';
                                echo "<td>" . htmlspecialchars($value) . "</td>";
                            }
                            echo "</tr>";
                        }
                        echo "</table>";
                    }
                    
                    // Chercher aussi avec d'autres noms de colonnes possibles
                    echo "<h4>5. Test avec différents noms de colonnes</h4>";
                    
                    $possible_agency_columns = ['agency_id', 'houzez_agency_id', 'fave_agency_id', 'parent_agency'];
                    
                    foreach ($possible_agency_columns as $col) {
                        try {
                            $test_agents = $wp_db->select('COUNT(*) as count')
                                ->from($crm_agents_table)
                                ->where($col, 3)
                                ->get()->row();
                            
                            echo "<p><strong>Agents avec $col = 3:</strong> " . ($test_agents->count ?? 0) . "</p>";
                        } catch (Exception $e) {
                            echo "<p><strong>Colonne $col:</strong> n'existe pas</p>";
                        }
                    }
                }
                
            } else {
                echo "<p>❌ Table <strong>$crm_agents_table</strong> n'existe pas</p>";
                echo "<p>Tables disponibles contenant 'agent' ou 'crm':</p>";
                
                $relevant_tables = array_filter($tables, function($table) {
                    return (stripos($table, 'agent') !== false || stripos($table, 'crm') !== false);
                });
                
                if (!empty($relevant_tables)) {
                    echo "<ul>";
                    foreach ($relevant_tables as $table) {
                        echo "<li>$table</li>";
                    }
                    echo "</ul>";
                } else {
                    echo "<p>Aucune table pertinente trouvée.</p>";
                }
            }
            
        } catch (Exception $e) {
            echo "<p><strong>Erreur:</strong> " . $e->getMessage() . "</p>";
        }
    }

    /**
     * Debug avancé pour analyser les agents et leurs métadonnées
     */
    public function debug_agents_detailed() {
        $this->isLoggedIn();
        
        echo "<h3>Debug: Analyse détaillée des agents</h3>";
        
        try {
            // Charger la DB WordPress directement
            $wp_db = $this->load->database('wordpress', TRUE);
            
            $users_table = $wp_db->dbprefix . 'users';
            $usermeta_table = $wp_db->dbprefix . 'usermeta';
            $capabilities_key = $wp_db->dbprefix . 'capabilities';
            
            echo "<h4>1. Tous les utilisateurs avec rôle agent</h4>";
            
            // Chercher tous les agents (sans filtre d'agence)
            $agents = $wp_db->select('u.ID, u.user_login, u.display_name, u.user_email')
                ->from($users_table . ' u')
                ->join($usermeta_table . ' m', 'u.ID = m.user_id')
                ->where('m.meta_key', $capabilities_key)
                ->like('m.meta_value', 'houzez_agent')
                ->get()->result();
            
            echo "<p><strong>Agents trouvés (tous):</strong> " . count($agents) . "</p>";
            
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
                
                echo "<h4>2. Métadonnées des agents</h4>";
                
                $agent_ids = array_map(function($a) { return $a->ID; }, $agents);
                
                // Récupérer toutes les métadonnées des agents
                $agent_metas = $wp_db->select('user_id, meta_key, meta_value')
                    ->from($usermeta_table)
                    ->where_in('user_id', $agent_ids)
                    ->where("(meta_key LIKE '%agency%' OR meta_key LIKE '%houzez%' OR meta_key = 'first_name' OR meta_key = 'last_name')")
                    ->order_by('user_id, meta_key')
                    ->get()->result();
                
                if (!empty($agent_metas)) {
                    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
                    echo "<tr><th>User ID</th><th>Meta Key</th><th>Meta Value</th></tr>";
                    foreach ($agent_metas as $meta) {
                        echo "<tr>";
                        echo "<td>{$meta->user_id}</td>";
                        echo "<td>{$meta->meta_key}</td>";
                        echo "<td>" . htmlspecialchars($meta->meta_value) . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>Aucune métadonnée pertinente trouvée pour les agents.</p>";
                }
            }
            
            echo "<h4>3. Recherche alternative - meta_keys contenant 'agency'</h4>";
            
            // Rechercher tous les meta_keys contenant 'agency'
            $agency_keys = $wp_db->select('DISTINCT meta_key, COUNT(*) as count')
                ->from($usermeta_table)
                ->like('meta_key', 'agency')
                ->group_by('meta_key')
                ->get()->result();
            
            if (!empty($agency_keys)) {
                echo "<table border='1' style='border-collapse: collapse;'>";
                echo "<tr><th>Meta Key</th><th>Count</th></tr>";
                foreach ($agency_keys as $key) {
                    echo "<tr><td>{$key->meta_key}</td><td>{$key->count}</td></tr>";
                }
                echo "</table>";
                
                // Tester avec le premier meta_key trouvé
                if (isset($agency_keys[0])) {
                    $test_key = $agency_keys[0]->meta_key;
                    echo "<h4>4. Test avec meta_key '$test_key'</h4>";
                    
                    $test_agents = $wp_db->select('u.ID, u.user_login, u.display_name, m2.meta_value as agency_ref')
                        ->from($users_table . ' u')
                        ->join($usermeta_table . ' m1', 'u.ID = m1.user_id')
                        ->join($usermeta_table . ' m2', 'u.ID = m2.user_id')
                        ->where('m1.meta_key', $capabilities_key)
                        ->like('m1.meta_value', 'houzez_agent')
                        ->where('m2.meta_key', $test_key)
                        ->where('m2.meta_value', '3') // Tester avec l'agence ID 3
                        ->get()->result();
                    
                    echo "<p><strong>Agents trouvés avec $test_key = 3:</strong> " . count($test_agents) . "</p>";
                    
                    if (!empty($test_agents)) {
                        echo "<table border='1' style='border-collapse: collapse;'>";
                        echo "<tr><th>ID</th><th>Login</th><th>Display Name</th><th>Agency Ref</th></tr>";
                        foreach ($test_agents as $agent) {
                            echo "<tr>";
                            echo "<td>{$agent->ID}</td>";
                            echo "<td>{$agent->user_login}</td>";
                            echo "<td>{$agent->display_name}</td>";
                            echo "<td>{$agent->agency_ref}</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    }
                }
            } else {
                echo "<p>Aucune meta_key contenant 'agency' trouvée.</p>";
            }
            
        } catch (Exception $e) {
            echo "<p><strong>Erreur:</strong> " . $e->getMessage() . "</p>";
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
