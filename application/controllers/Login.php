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
        
        
        $this->form_validation->set_rules('password', 'Password', 'required|max_length[32]');

        if ($this->form_validation->run() == FALSE) {
            $this->index();
            return;
        }

        $username = $this->input->post('email');
        $app_password = $this->input->post('password');

        $url = 'https://rebencia.com/wp-json/wp/v2/users/me';
        $auth = base64_encode("$username:$app_password");

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Basic $auth"
        ]);

        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpcode == 200) {
            $user = json_decode($response, true);

            // Stocker info dans la session CI3
            $this->session->set_userdata([
                'logged_in' => true,
                'wp_id'        => $user['id'],
                'wp_login'     => $user['slug'],
                'name'      => $user['name'],
                'role'     => 'role',
                'wp_avatar'    => isset($user['avatar_urls']['96']) ? $user['avatar_urls']['96'] : null,
                'isLoggedIn'   => TRUE,
                'wp_url'       => isset($user['link']) ? $user['link'] : null
            ]);

            redirect('dashboard');
        } else {
            $this->session->set_flashdata('error', 'Login WordPress échoué. Vérifie le mot de passe applicatif.');
            redirect('login');
        }
    }

    // Fonctions forgot/reset password idem, ajoute check file_exists avant load->view
}
?>
