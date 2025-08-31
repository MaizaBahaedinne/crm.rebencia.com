<?php
defined('BASEPATH') OR exit('No direct script access allowed');


require APPPATH . '/libraries/BaseController.php';
/**
 * @property CI_Input $input
 * @property CI_Form_validation $form_validation
 * @property Transaction_model $transaction_model
 */
class Transaction extends BaseController {
    public function __construct() {
        parent::__construct();
        $this->load->model('Transaction_model','transaction_model');
        $this->load->library(['session','form_validation']);
        $this->load->helper(['url','form']);
    }

    private function _filters() {
        return [
            'type' => $this->input->get('type', TRUE),
            'statut' => $this->input->get('statut', TRUE),
            'q' => $this->input->get('q', TRUE),
            'date_min' => $this->input->get('date_min', TRUE),
            'date_max' => $this->input->get('date_max', TRUE),
        ];
    }

    public function index() {
        $this->isLoggedIn();
        $filters = $this->_filters();
        $data = $this->global;
        $data['pageTitle'] = 'Transactions';
        $data['filters'] = $filters;
        $data['transactions'] = $this->transaction_model->filter($filters,100,0);
        $this->loadViews('transactions/list', $data, $data, NULL);
    }

    public function sales() {
        $this->isLoggedIn();
        $filters = ['type'=>'vente'];
        $data = $this->global;
        $data['pageTitle'] = 'Ventes';
        $data['transactions'] = $this->transaction_model->filter($filters,100,0);
        $this->loadViews('transactions/sales', $data, $data, NULL);
    }

    public function rentals() {
        $this->isLoggedIn();
        $filters = ['type'=>'location'];
        $data = $this->global;
        $data['pageTitle'] = 'Locations';
        $data['transactions'] = $this->transaction_model->filter($filters,100,0);
        $this->loadViews('transactions/rentals', $data, $data, NULL);
    }

    public function form($id = null) {
        $this->isLoggedIn();
        $data = $this->global;
        $data['transaction'] = $id ? $this->transaction_model->get($id) : null;
        $data['pageTitle'] = $id ? 'Modifier transaction' : 'Nouvelle transaction';
        $this->loadViews('transactions/form', $data, $data, NULL);
    }

    public function save($id = null) {
        $this->isLoggedIn();
        $this->form_validation->set_rules('titre','Titre','required|trim');
        $this->form_validation->set_rules('type','Type','required|in_list[vente,location]');
        $this->form_validation->set_rules('statut','Statut','required|in_list[nouveau,actif,cloture,annule]');
        if(!$this->form_validation->run()) {
            return $this->form($id);
        }
        $payload = [
            'titre' => $this->input->post('titre', TRUE),
            'type' => $this->input->post('type', TRUE),
            'commercial' => $this->input->post('commercial', TRUE),
            'montant' => $this->input->post('montant', TRUE) !== '' ? (float)$this->input->post('montant', TRUE) : null,
            'statut' => $this->input->post('statut', TRUE),
            'date_cloture' => $this->input->post('date_cloture', TRUE) ?: null,
            'notes' => $this->input->post('notes', TRUE)
        ];
        if($id) {
            $this->transaction_model->update($id,$payload);
        } else {
            $id = $this->transaction_model->create($payload);
        }
        redirect('transactions');
    }

    public function delete($id) {
        $this->isLoggedIn();
        if($id) $this->transaction_model->delete($id);
        redirect('transactions');
    }

    public function sync_houzez() {
        $this->isLoggedIn();
        // Placeholder: future integration with WP Houzez
        $this->session->set_flashdata('success','Synchronisation Houzez lancée (placeholder).');
        redirect('transactions');
    }
}
