
<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'libraries/BaseController.php';
/**
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
        $this->load->library(['form_validation','session']);
        $this->load->helper(['url','form']);
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
                'email' => $this->input->post('email'),
                'telephone' => $this->input->post('telephone'),
                'adresse' => $this->input->post('adresse'),
            ];
            $this->load->model('client_model');
            $this->client_model->insert_client($data);
            redirect('client');
        } else {
            $data = $this->global;
            $data['pageTitle'] = 'Ajouter un client';
            $this->loadViews('client/form', $data, NULL, NULL);
        }
    }

    public function edit($id) {
        $this->isLoggedIn();
        $this->load->model('client_model');
        $client = $this->client_model->get_client($id);
        $data = $this->global;
        $data['pageTitle'] = 'Modifier un client';
        $data['client'] = $client;
        $this->loadViews('client/form', $data, NULL, NULL);
    }

    public function update($id) {
        $this->isLoggedIn();
        if ($this->input->post()) {
            $data = [
                'nom' => $this->input->post('nom'),
                'email' => $this->input->post('email'),
                'telephone' => $this->input->post('telephone'),
                'adresse' => $this->input->post('adresse'),
            ];
            $this->load->model('client_model');
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


    public function index() {
        $this->isLoggedIn();
        $this->load->model('client_model');
        $clients = $this->client_model->get_all_clients();
        $data = $this->global;
        $data['pageTitle'] = 'Clients CRM';
        $data['clients'] = $clients;
        $this->loadViews('client/list_grid', $data, NULL, NULL);
    }



}
