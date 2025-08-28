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
        
        $this->form_validation->set_rules('password','Password','required|max_length[32]');

        if($this->form_validation->run() == FALSE) {
            $this->index();
            return;
        }

        $email = strtolower($this->security->xss_clean($this->input->post('email')));
        $password = $this->input->post('password');

        // Authentification via API externe
        $url = 'https://rebencia.com/wp-json/jwt-auth/v1/token';
        $data = [
            "username" => $email,
            "password" => $password
        ];
        $data_json = json_encode($data);

        $options = [
            'http' => [
                'header'  => "Content-type: application/json\r\n",
                'method'  => 'POST',
                'content' => $data_json,
                'timeout' => 10
            ]
        ];

        $context = stream_context_create($options);
        $result = @file_get_contents($url, false, $context);

        if ($result === FALSE) {
            $this->session->set_flashdata('error', 'Erreur de connexion au service distant');
            redirect('login');
            return;
        }

        $apiResponse = json_decode($result, true);

        if (isset($apiResponse['token'])) {
            // Authentifié avec succès via l'API externe
            // Vous pouvez stocker le token JWT si besoin
            $sessionData = [
                'email' => $email,
                'jwt_token' => $apiResponse['token'],
                'isLoggedIn' => TRUE
            ];
            $this->session->set_userdata($sessionData);
            redirect('dashboard');
        } else {
            $this->session->set_flashdata('error', 'Email ou mot de passe incorrect');
            redirect('login');
        }
    }

    // Fonctions forgot/reset password idem, ajoute check file_exists avant load->view
}
?>
