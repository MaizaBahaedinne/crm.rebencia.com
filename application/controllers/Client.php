    public function add() {
        if ($this->input->post()) {
            $data = [
                'nom' => $this->input->post('nom'),
                'email' => $this->input->post('email'),
                'telephone' => $this->input->post('telephone'),
                'adresse' => $this->input->post('adresse'),
            ];
            $this->load->model('Client_model');
            $this->Client_model->insert_client($data);
            redirect('client');
        } else {
            $this->load->view('include/header');
            $this->load->view('client/form');
            $this->load->view('include/footer');
        }
    }

    public function edit($id) {
        $this->load->model('Client_model');
        $client = $this->Client_model->get_client($id);
        $this->load->view('include/header');
        $this->load->view('client/form', ['client' => $client]);
        $this->load->view('include/footer');
    }

    public function update($id) {
        if ($this->input->post()) {
            $data = [
                'nom' => $this->input->post('nom'),
                'email' => $this->input->post('email'),
                'telephone' => $this->input->post('telephone'),
                'adresse' => $this->input->post('adresse'),
            ];
            $this->load->model('Client_model');
            $this->Client_model->update_client($id, $data);
            redirect('client');
        }
    }

    public function delete($id) {
        $this->load->model('Client_model');
        $this->Client_model->delete_client($id);
        redirect('client');
    }

    public function view($id) {
        $this->load->model('Client_model');
        $client = $this->Client_model->get_client($id);
        $this->load->view('include/header');
        $this->load->view('client/form', ['client' => $client]);
        $this->load->view('include/footer');
    }
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
    // Remplace par clients WordPress (Houzez)
    $this->load->model('Wp_client_model','client_model');
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
    
    public function index(){
        $this->isLoggedIn();
        $filters=$this->_filters();
        $data=$this->global;
        $data['pageTitle']='Clients (Houzez)';
        $data['filters']=$filters;
        $data['clients']=$this->client_model->all(200,0,$filters);
        $this->loadViews('clients/list',$data,$data,NULL);
    }
    // Désactivation des opérations d'édition côté CRM (gérées dans WordPress)
    public function form(){ redirect('clients'); }
    public function save(){ redirect('clients'); }
    public function delete(){ redirect('clients'); }
}
