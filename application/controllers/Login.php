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
        $password = $this->input->post('password');

        // Load WordPress database
        $wp_db = $this->load->database('wordpress', TRUE);

        // Get user by email from wp_Hrg8P_users
        $wp_db->where('user_email', $username);
        $user = $wp_db->get('wp_Hrg8P_users')->row();

        if ($user) {
            // Verify password using WordPress hash
            require_once(APPPATH . '../wp-includes/class-phpass.php');
            if (!function_exists('maybe_unserialize')) {
                require_once(APPPATH . '../wp-includes/functions.php');
            }
            $wp_hasher = new PasswordHash(8, TRUE);

            if ($wp_hasher->CheckPassword($password, $user->user_pass)) {
                // Get user meta for avatar and role from wp_Hrg8P_usermeta
                $wp_db->where('user_id', $user->ID);
                $meta = $wp_db->get('wp_Hrg8P_usermeta')->result();

                $role = 'subscriber';
                foreach ($meta as $m) {
                    if ($m->meta_key == $wp_db->dbprefix('capabilities')) {
                        $roles = maybe_unserialize($m->meta_value);
                        if (is_array($roles)) {
                            $role = key($roles);
                        }
                    }
                }

                $this->session->set_userdata([
                    'logged_in'   => true,
                    'wp_id'       => $user->ID,
                    'wp_login'    => $user->user_login,
                    'name'        => $user->display_name,
                    'role'        => $role,
                    'wp_avatar'   => null, // You can fetch avatar via Gravatar if needed
                    'isLoggedIn'  => TRUE,
                    'wp_url'      => null // Set if you store user URL
                ]);
                redirect('dashboard');
            }
        }

        $this->session->set_flashdata('error', 'Login WordPress échoué. Vérifie le mot de passe.');
        redirect('login');
    }

    // Fonctions forgot/reset password idem, ajoute check file_exists avant load->view
}
?>
