<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Contrôleur d'estimation immobilière
 * @property Estimation_model $estim
 * @property CI_Upload $upload
 * @property CI_Input $input
 */
class Estimation extends BaseController {
    public function __construct() {
        parent::__construct();
    $this->load->model('Estimation_model','estim'); // $this->estim
        $this->isLoggedIn();
    }

    public function index() {
        $data = $this->global;
        $data['zones'] = $this->estim->get_zones();
        $data['pageTitle'] = 'Nouvelle estimation';
        $this->loadViews('estimation/form', $data, $data, NULL);
    }

    public function calculate() {
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
        $save['valeur_estimee'] = $estim['valeur_estimee'];
        $save['loyer_potentiel'] = $estim['loyer_potentiel'];
        $save['rentabilite'] = $estim['rentabilite'];
        $save['coef_global'] = $estim['coef_global'];
        $save['statut_dossier'] = 'en_cours';
        $property_id = $this->estim->save_property($save, $uploaded);

        redirect('estimation/resultat/'.$property_id);
    }

    public function result($id) {
        $data = $this->global;
        $data['pageTitle'] = 'Résultat estimation';
        $data['property'] = $this->estim->get_property($id);
        if(!$data['property']) { redirect('estimation'); }
        $this->loadViews('estimation/result', $data, $data, NULL);
    }
}
