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
    public $lead_model; // doit être public pour l'injection CI

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
        // Récupérer la pièce jointe identité si existante
        // Récupérer toutes les pièces jointes identité existantes
        $data['lead_files'] = [];
        if($id && !empty($data['lead']['id'])) {
            $data['lead_files'] = $this->lead_model->db->where(['lead_id'=>$id,'categorie'=>'id'])->order_by('uploaded_at','DESC')->get('crm_lead_files')->result_array();
        }
        $data['wp_clients'] = $this->wp_client_model->all(300,0,['role'=>null]);
        $data['pageTitle'] = $id? 'Modifier lead':'Nouveau lead';
        $this->loadViews('leads/form',$data,$data,NULL);
    }

    private function _rules() {
        $this->form_validation->set_rules('wp_user_id','Utilisateur WordPress','required|integer');
        $this->form_validation->set_rules('type','Type','required|in_list[acheteur,locataire]');
        $this->form_validation->set_rules('status','Statut','required|in_list[nouveau,qualifie,en_cours,converti,perdu]');
        $this->form_validation->set_rules('email','Email','valid_email');
        $this->form_validation->set_rules('client_type','Type de client','required|in_list[personne,societe]');
        $this->form_validation->set_rules('client_identite_type','Type d\'identité','required|in_list[cin,passeport,titre_sejour,rc,mf,autre]');
        $this->form_validation->set_rules('client_identite_numero','Numéro identité','required');
    $this->form_validation->set_rules('client_identite_date','Date de délivrance','trim');
    $this->form_validation->set_rules('client_identite_lieu','Lieu de délivrance','trim');
    $this->form_validation->set_rules('client_identite_date_expiration','Date d\'expiration','trim');
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
            // Champs identité client
            'client_type' => $this->input->post('client_type',TRUE),
            'client_identite_type' => $this->input->post('client_identite_type',TRUE),
            'client_identite_numero' => $this->input->post('client_identite_numero',TRUE),
            'client_identite_date' => $this->input->post('client_identite_date',TRUE),
            'client_identite_lieu' => $this->input->post('client_identite_lieu',TRUE),
            'client_identite_date_expiration' => $this->input->post('client_identite_date_expiration',TRUE),
        ];
        $tag_ids = $this->input->post('tag_ids');
        $ptype_ids = $this->input->post('property_type_ids');

        // Gestion upload pièce jointe identité
        $lead_id = $id;
        $is_new = false;
        if($id) {
            $ok = $this->lead_model->update($id,$payload);
            if($ok) {
                if(is_array($tag_ids)) $this->lead_model->set_tags($id,$tag_ids);
                if(is_array($ptype_ids)) $this->lead_model->set_property_types($id,$ptype_ids);
            }
            $this->session->set_flashdata($ok? 'success':'error',$ok? 'Lead mis à jour':'Erreur mise à jour');
        } else {
            $lead_id = $this->lead_model->create($payload);
            $is_new = true;
            if($lead_id) {
                if(is_array($tag_ids)) $this->lead_model->set_tags($lead_id,$tag_ids);
                if(is_array($ptype_ids)) $this->lead_model->set_property_types($lead_id,$ptype_ids);
            }
            $this->session->set_flashdata($lead_id? 'success':'error',$lead_id? 'Lead créé':'Création impossible');
        }

        // Upload de plusieurs fichiers si présents
        if($lead_id && isset($_FILES['piece_identite_scan']) && !empty($_FILES['piece_identite_scan']['name'][0])) {
            $upload_dir = FCPATH.'uploads/leads/';
            if(!is_dir($upload_dir)) @mkdir($upload_dir,0775,true);
            foreach ($_FILES['piece_identite_scan']['name'] as $i => $name) {
                if (!is_uploaded_file($_FILES['piece_identite_scan']['tmp_name'][$i])) continue;
                $ext = pathinfo($name, PATHINFO_EXTENSION);
                $filename = 'lead_'.$lead_id.'_id_'.uniqid().'_'.date('YmdHis').'.'.strtolower($ext);
                $dest = $upload_dir.$filename;
                if (move_uploaded_file($_FILES['piece_identite_scan']['tmp_name'][$i], $dest)) {
                    $this->lead_model->db->insert('crm_lead_files', [
                        'lead_id' => $lead_id,
                        'filename' => $filename,
                        'original_name' => $name,
                        'mime_type' => $_FILES['piece_identite_scan']['type'][$i],
                        'taille' => $_FILES['piece_identite_scan']['size'][$i],
                        'categorie' => 'id',
                        'uploaded_by' => ($this->session->userdata('userId') ?? null),
                        'uploaded_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }
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
