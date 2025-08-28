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

        // Authentification via Application Passwords (WordPress)
        $url = 'https://rebencia.com/wp-json/wp/v2/users/me';
        $auth = base64_encode("$email:$password");

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Basic $auth"
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $user = json_decode($response, true);

        if(isset($user['id'])) {
            // Authentifié avec succès
            $role = isset($user['roles']) ? implode(',', $user['roles']) : null;
            $avatar = isset($user['avatar_urls']['96']) ? $user['avatar_urls']['96'] : null;

            $sessionData = [
                'email' => $email,
                'user_id' => $user['id'],
                'name' => $user['name'],
                'role' => $role,
                'avatar' => $avatar,
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
