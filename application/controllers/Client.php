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
            'type'=>$this->input->get('type',TRUE),
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
