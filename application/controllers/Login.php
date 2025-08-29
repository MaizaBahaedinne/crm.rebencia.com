<?php
defined('BASEPATH') OR exit('No direct script access allowed');



class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('login_model');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('form');
        // $this->input is available by default in CI_Controller, but you can ensure it's loaded
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
        // Ensure form_validation, session, and input are loaded
        if (!isset($this->form_validation)) {
            $this->load->library('form_validation');
        }
        if (!isset($this->session)) {
            $this->load->library('session');
        }
        if (!isset($this->input)) {
            $this->load->library('input');
        }

        // Set validation rules
       
        $this->form_validation->set_rules('password', 'Password', 'required|max_length[32]');

        if ($this->form_validation->run() == FALSE) {
            $this->index();
            return;
        }

        // Get input values
        $username = $this->input->post('email');
        $password = $this->input->post('password');

        // Load WordPress database connection (add 'wordpress' config in application/config/database.php)
        $wp_db = $this->load->database('wordpress', TRUE);

        // Get user by email from wp_Hrg8P_users
        $wp_db->where('user_login', $username);
        $user = $wp_db->get('wp_Hrg8P_users')->row();

        if ($user) {
            // Verify password using WordPress hash
            if (!class_exists('PasswordHash')) {
                require_once(APPPATH . 'libraries/class-phpass.php');
            }
            if (!function_exists('maybe_unserialize')) {
                function maybe_unserialize($original) {
                    if (is_serialized($original)) {
                        return @unserialize($original);
                    }
                    return $original;
                }
                function is_serialized($data) {
                    // if it isn't a string, it isn't serialized
                    if (!is_string($data)) {
                        return false;
                    }
                    $data = trim($data);
                    if ('N;' == $data) return true;
                    if (!preg_match('/^([adObis]):/', $data, $badions)) return false;
                    switch ($badions[1]) {
                        case 'a':
                        case 'O':
                        case 's':
                            if (preg_match( "/^{$badions[1]}:[0-9]+:/s", $data )) return true;
                            break;
                        case 'b':
                        case 'i':
                        case 'd':
                            if (preg_match( "/^{$badions[1]}:[0-9.E-]+;$/", $data )) return true;
                            break;
                    }
                    return false;
                }
            }
            $wp_hasher = new \PasswordHash(8, TRUE);

            if ($wp_hasher->CheckPassword($password, $user->user_pass)) {
                // Get user meta for avatar and role from wp_Hrg8P_usermeta
                $wp_db->where('user_id', $user->ID);
                $meta = $wp_db->get('wp_Hrg8P_usermeta')->result();
                $role = '';
                $avatar_url = 'null';
                foreach ($meta as $m) {
                    if ($m->meta_key == $wp_db->dbprefix('wp_Hrg8P_capabilities')) {
                        $roles = maybe_unserialize($m->meta_value);
                        if (is_array($roles)) {
                            $role = key($roles);
                        }
                    }
                    if ($m->meta_key == 'fave_author_custom_picture') {
                        $avatar_url = $m->meta_value;
                    }
                }

                print_r($avatar_url); // Uncomment for debugging if needed
                $this->session->set_userdata([
                    'logged_in'   => true,
                    'wp_id'       => $user->ID,
                    'wp_login'    => $user->user_login,
                    'name'        => $user->display_name,
                    'role'        => $role,
                    'wp_avatar'   => $avatar_url,
                    'isLoggedIn'  => TRUE,
                    'wp_url'      => null // Set if you store user URL
                ]);

                // Redirection selon le rôle
                if ($role === 'administrator' || $role === 'admin') {
                    redirect('dashboard/admin');
                } elseif ($role === 'houzez_agency') {
                    // Récupérer l'ID agence depuis usermeta si besoin
                    $agency_id = null;
                    foreach ($meta as $m) {
                        if ($m->meta_key == 'houzez_agency_id') {
                            $agency_id = $m->meta_value;
                            break;
                        }
                    }
                    if ($agency_id) {
                        redirect('Dashboard/agency/' . $agency_id);
                    } else {
                        redirect('Dashboard');
                    }
                } elseif ($role === 'houzez_agent' || $role === 'agent') {
                    redirect('Dashboard/agent/' . $user->ID);
                } else {
                    redirect('dashboard');
                }
            }
        }

        $this->session->set_flashdata('error', 'Login WordPress échoué. Vérifie le mot de passe.');
        redirect('login');
    }

    // Fonctions forgot/reset password idem, ajoute check file_exists avant load->view
}
?>
