<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'libraries/BaseController.php';

/**
 * @property CI_Input $input
 * @property CI_Form_validation $form_validation
 * @property CI_Session $session
 * @property Lead_model $lead_model
 * @property Wp_client_model $wp_client_model
 */
class Lead extends BaseController {
    /** @var Lead_model */
    protected $lead_model;

    public function __construct() {
        parent::__construct();
        $this->load->model('Lead_model','lead_model');
        $this->load->model('Wp_client_model','wp_client_model'); // pour sélection WP users / clients
        $this->load->library(['form_validation','session']);
        $this->load->helper(['url','form']);
    }

    public function index($page=1) {
        $this->isLoggedIn();
        $filters = [
            'q'=>$this->input->get('q',TRUE),
            'type'=>$this->input->get('type',TRUE),
            'status'=>$this->input->get('status',TRUE)
        ];
        $perPage = 25; $page = max(1,(int)$page); $offset = ($page-1)*$perPage;
        $rows = $this->lead_model->filter($filters,$perPage,$offset);
        $total = $this->lead_model->count($filters);
        $data = $this->global;
        $data['pageTitle'] = 'Leads';
        $data['leads'] = $rows;
        $data['filters'] = $filters;
        $data['pagination'] = ['page'=>$page,'per_page'=>$perPage,'total'=>$total];
        $this->loadViews('leads/list',$data,$data,NULL);
    }

    public function form($id=null) {
        $this->isLoggedIn();
        $data = $this->global;
        $data['lead'] = $id ? $this->lead_model->get($id) : null;
        $data['wp_clients'] = $this->wp_client_model->all(300,0,['role'=>null]);
        $data['pageTitle'] = $id? 'Modifier lead':'Nouveau lead';
        $this->loadViews('leads/form',$data,$data,NULL);
    }

    private function _rules() {
        $this->form_validation->set_rules('wp_user_id','Utilisateur WordPress','required|integer');
        $this->form_validation->set_rules('type','Type','required|in_list[acheteur,locataire]');
        $this->form_validation->set_rules('status','Statut','required|in_list[nouveau,qualifie,en_cours,converti,perdu]');
        $this->form_validation->set_rules('email','Email','valid_email');
    }

    public function save($id=null) {
        $this->isLoggedIn();
        $this->_rules();
        if(!$this->form_validation->run()) {
            return $this->form($id);
        }
        $payload = [
            'wp_user_id' => (int)$this->input->post('wp_user_id',TRUE),
            'type' => $this->input->post('type',TRUE),
            'status' => $this->input->post('status',TRUE),
            'prenom' => $this->input->post('prenom',TRUE),
            'nom' => $this->input->post('nom',TRUE),
            'email' => $this->input->post('email',TRUE),
            'telephone' => $this->input->post('telephone',TRUE),
            'telephone_alt' => $this->input->post('telephone_alt',TRUE),
            'whatsapp' => $this->input->post('whatsapp',TRUE),
            'pays' => $this->input->post('pays',TRUE),
            'ville' => $this->input->post('ville',TRUE),
            'adresse' => $this->input->post('adresse',TRUE),
            'code_postal' => $this->input->post('code_postal',TRUE),
            'notes_interne' => $this->input->post('notes_interne',TRUE),
            'lead_score' => (int)$this->input->post('lead_score',TRUE),
        ];
        if($id) {
            $ok = $this->lead_model->update($id,$payload);
            $this->session->set_flashdata($ok? 'success':'error',$ok? 'Lead mis à jour':'Erreur mise à jour');
        } else {
            $newId = $this->lead_model->create($payload);
            $this->session->set_flashdata($newId? 'success':'error',$newId? 'Lead créé':'Création impossible');
        }
        redirect('leads');
    }

    public function delete($id) {
        $this->isLoggedIn();
        if($id) $this->lead_model->delete($id);
        redirect('leads');
    }

    // Placeholders pour futures fonctionnalités
    public function conversion() { $this->index(); }
    public function followup() { $this->index(); }
    public function status() { $this->index(); }
}
