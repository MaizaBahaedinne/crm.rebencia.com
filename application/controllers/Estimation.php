<?php defined('BASEPATH') OR exit('No direct script access allowed');


require APPPATH . '/libraries/BaseController.php';
/**
 * Contrôleur Estimation
 * @property Estimation_model $estim
 * @property CI_Input $input
 * @property CI_Upload $upload
 */
class Estimation extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('Estimation_model','estim'); // $this->estim
    // Input library est chargée par défaut dans CI; model alias estim disponible
    $this->isLoggedIn();
    }

    public function index() {
        $this->isLoggedIn();
        $data = $this->global;
        $data['zones'] = $this->estim->get_zones();
        $data['pageTitle'] = 'Nouvelle estimation';
        $this->loadViews('estimation/form', $data, $data, NULL);
    }

    public function calculate() {
        $this->isLoggedIn();
        $post = $this->input->post();
        if(!$post) { redirect('estimation'); }
        $zone = NULL;
        if(!empty($post['zone_id'])) { $zone = $this->estim->get_zone($post['zone_id']); }

        // Photos upload
        $uploaded = [];
    if(!empty($_FILES['photos']['name'][0])) {
            $files = $_FILES['photos'];
            $count = count($files['name']);
            $upload_path = FCPATH.'uploads/estimations/';
            if(!is_dir($upload_path)) mkdir($upload_path,0775,true);
            for($i=0;$i<$count;$i++) {
                $_FILES['single']['name'] = $files['name'][$i];
                $_FILES['single']['type'] = $files['type'][$i];
                $_FILES['single']['tmp_name'] = $files['tmp_name'][$i];
                $_FILES['single']['error'] = $files['error'][$i];
                $_FILES['single']['size'] = $files['size'][$i];
                $config = [
                    'upload_path' => $upload_path,
                    'allowed_types' => 'jpg|jpeg|png|webp',
                    'max_size' => 4096,
                    'encrypt_name' => TRUE
                ];
                $this->load->library('upload', $config); // recharge config à chaque fichier
                if($this->upload->do_upload('single')) {
                    $d = $this->upload->data();
                    $uploaded[] = 'uploads/estimations/'.$d['file_name'];
                }
            }
        }

        // Normalisation equipements en CSV
        if(isset($post['equipements']) && is_array($post['equipements'])) {
            $post['equipements'] = implode(',', $post['equipements']);
        }
        $estim = $this->estim->compute_estimation($post, $zone);

        $save = $post;
    $save['valeur_min_estimee'] = $estim['valeur_min_estimee'];
    $save['valeur_estimee'] = $estim['valeur_estimee'];
    $save['valeur_max_estimee'] = $estim['valeur_max_estimee'];
        $save['loyer_potentiel'] = $estim['loyer_potentiel'];
        $save['rentabilite'] = $estim['rentabilite'];
        $save['coef_global'] = $estim['coef_global'];
        $save['statut_dossier'] = 'en_cours';
        $property_id = $this->estim->save_property($save, $uploaded);

        redirect('estimation/resultat/'.$property_id);
    }

    public function result($id) {
        $this->isLoggedIn();
        $data = $this->global;
        $data['pageTitle'] = 'Résultat estimation';
        $data['property'] = $this->estim->get_property($id);
        if(!$data['property']) { redirect('estimation'); }
        $this->loadViews('estimation/result', $data, $data, NULL);
    }

    public function liste() {
        $this->isLoggedIn();
        $data = $this->global;
        $data['pageTitle'] = 'Estimations';
        $filters = [];
        if($this->input->get('statut')) $filters['statut'] = $this->input->get('statut');
        if($this->input->get('zone_id')) $filters['zone_id'] = $this->input->get('zone_id');
        $data['allowed_status'] = $this->estim->get_allowed_status();
        $data['zones'] = $this->estim->get_zones();
        $data['estimations'] = $this->estim->list_estimations(200,0,$filters);
        $this->loadViews('estimation/list', $data, $data, NULL);
    }

    public function statut($id, $new) {
        $this->isLoggedIn();
        if(!$id || !$new) redirect('estimations');
        $ok = $this->estim->update_status($id, $new);
        redirect('estimations');
    }

    /* ================== ZONES ================== */
    public function zones() {
        $this->isLoggedIn();
        $data = $this->global;
        $data['pageTitle'] = 'Zones';
        $data['zones'] = $this->estim->get_zones();
        $this->loadViews('estimation/zones_list', $data, $data, NULL);
    }

    public function zone_create() {
        $this->isLoggedIn();
        if($this->input->method()==='post') {
            $post = $this->input->post();
            // Normalisation pour % (s'il vient sans conversion)
            if(isset($post['rendement_locatif_moyen'])) {
                $post['rendement_locatif_moyen'] = (float)$post['rendement_locatif_moyen'];
            }
            $this->estim->create_zone($post);
            redirect('zones');
        }
        $data = $this->global; $data['pageTitle'] = 'Créer zone';
        $this->loadViews('estimation/zones_form', $data, $data, NULL);
    }

    public function zone_edit($id) {
        $this->isLoggedIn();
        $zone = $this->estim->get_zone($id);
        if(!$zone) redirect('zones');
        if($this->input->method()==='post') {
            $post = $this->input->post();
            if(isset($post['rendement_locatif_moyen'])) {
                $post['rendement_locatif_moyen'] = (float)$post['rendement_locatif_moyen'];
            }
            $this->estim->update_zone($id, $post);
            redirect('zones');
        }
        $data = $this->global; $data['pageTitle'] = 'Modifier zone';
        $data['zone'] = $zone;
        $this->loadViews('estimation/zones_form', $data, $data, NULL);
    }

    public function zone_delete($id) {
        $this->isLoggedIn();
        if($id) { $this->estim->delete_zone($id); }
        redirect('zones');
    }

    public function proposition($id) {
        $this->isLoggedIn();
        if($this->input->method() !== 'post') redirect('estimation/resultat/'.$id);
        $data = [
            'proposition_agence' => $this->input->post('proposition_agence'),
            'proposition_commentaire' => $this->input->post('proposition_commentaire')
        ];
        $this->estim->update_proposition($id, $data);
        redirect('estimation/resultat/'.$id);
    }
}
