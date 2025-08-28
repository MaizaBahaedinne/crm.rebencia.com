<?php
defined('BASEPATH') OR exit('No direct script access allowed');



class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('login_model');
    }

    public function index() {
        $this->isLoggedIn();
    }

    public function isLoggedIn() {
        $isLoggedIn = $this->session->userdata('isLoggedIn');
     
        if(empty($isLoggedIn)) {
            
                $this->load->view('users/login');
            
        } else {
            redirect('dashboard');
        }
    }

    public function loginMe() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('email','Email','required|valid_email|max_length[128]|trim');
        $this->form_validation->set_rules('password','Password','required|max_length[32]');

        if($this->form_validation->run() == FALSE) {
            $this->index();
            return;
        }

        $email = strtolower($this->security->xss_clean($this->input->post('email')));
        $password = $this->input->post('password');

        $user = $this->login_model->loginMe($email, $password);
        if(empty($user)) {
            $this->session->set_flashdata('error', 'Email ou mot de passe incorrect');
            redirect('login');
        }

        $sessionData = [
            'userId' => $user->userId,
            'role' => $user->roleId,
            'roleText' => $user->role,
            'name' => $user->name,
            'isAdmin' => $user->isAdmin,
            'isLoggedIn' => TRUE
        ];
        $this->session->set_userdata($sessionData);
        redirect('dashboard');
    }

    // Fonctions forgot/reset password idem, ajoute check file_exists avant load->view
}
?>
