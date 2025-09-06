*-
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
                'source' => $this->input->post('source'),
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
                'source' => $this->input->post('source'),
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






}
