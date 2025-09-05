<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Properties extends CI_Controller {
    public function index() {
        $this->load->model('Property_model');
        $filters = $this->input->get();
        $data['properties'] = $this->Property_model->get_properties($filters);
        $this->load->view('include/header');
        $this->load->view('include/list', $data);
        $this->load->view('include/footer');
    }

    public function ajax_list() {
        $this->load->model('Property_model');
        $filters = $this->input->get();
        $data['properties'] = $this->Property_model->get_properties($filters);
        $this->load->view('include/list_grid', $data);
    }
}
