<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property CI_Session $session
 * @property CI_Form_validation $form_validation
 * @property CI_Input $input
 */
class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('login_model');
        $this->load->library(['form_validation', 'session']);
        $this->load->helper(['url', 'form']);
    }

    public function index() {
        if ($this->session->userdata('isLoggedIn')) {
            redirect('dashboard');
        } else {
            $this->load->view('users/login');
        }
    }

    public function isLoggedIn() {
        if (!$this->session->userdata('isLoggedIn')) {
            $this->load->view('users/login');
        } else {
            redirect('dashboard');
        }
    }

    public function loginMe() {
        $this->form_validation->set_rules('password', 'Password', 'required|max_length[32]');

        if ($this->form_validation->run() == FALSE) {
            $this->index();
            return;
        }

        $username = $this->input->post('email');
        $password = $this->input->post('password');
        $wp_db = $this->load->database('wordpress', TRUE);

        $wp_db->where('user_login', $username);
        $user = $wp_db->get('wp_Hrg8P_users')->row();

        if ($user) {
            // Load WordPress password hasher if needed
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
                    if (!is_string($data)) return false;
                    $data = trim($data);
                    if ('N;' == $data) return true;
                    if (!preg_match('/^([adObis]):/', $data, $badions)) return false;
                    switch ($badions[1]) {
                        case 'a':
                        case 'O':
                        case 's':
                            if (preg_match("/^{$badions[1]}:[0-9]+:/s", $data)) return true;
                            break;
                        case 'b':
                        case 'i':
                        case 'd':
                            if (preg_match("/^{$badions[1]}:[0-9.E-]+;$/", $data)) return true;
                            break;
                    }
                    return false;
                }
            }
            $wp_hasher = new \PasswordHash(8, TRUE);

            if ($wp_hasher->CheckPassword($password, $user->user_pass)) {
                // Get user meta
                $wp_db->where('user_id', $user->ID);
                $meta = $wp_db->get('wp_Hrg8P_usermeta')->result();
                $role = '';
                $avatar_url = null;
                $agency_id = null;
                $prefix = $wp_db->dbprefix;

                foreach ($meta as $m) {
                    if ($m->meta_key === $prefix.'capabilities') {
                        $roles = maybe_unserialize($m->meta_value);
                        if (is_array($roles) && !empty($roles)) {
                            $role = key($roles);
                        }
                    }
                    if ($m->meta_key === 'houzez_agency_id') {
                        $agency_id = $m->meta_value;
                    }
                    if ($m->meta_key === 'fave_author_custom_picture') {
                        $avatar_url = $m->meta_value;
                    }
                }

                // Map roles
                $mappedRole = $role;
                if ($role === 'houzez_agency') $mappedRole = 'agency';
                if ($role === 'houzez_agent') $mappedRole = 'agent';
                if ($role === 'houzez_manager') $mappedRole = 'manager';

                $sessionData = [
                    'logged_in'   => true,
                    'wp_id'       => $user->ID,
                    'wp_login'    => $user->user_login,
                    'name'        => $user->display_name,
                    'role'        => $mappedRole,
                    'raw_role'    => $role,
                    'wp_avatar'   => $avatar_url,
                    'isLoggedIn'  => TRUE,
                    'wp_url'      => null
                ];

                // Agent specific
                if ($mappedRole === 'agent') {
                    $agent_post_id = null;
                    if ($wp_db->table_exists('wp_Hrg8P_crm_agents')) {
                        $agent_record = $wp_db->where('user_id', $user->ID)->get('wp_Hrg8P_crm_agents')->row();
                        if ($agent_record) {
                            if (isset($agent_record->agent_post_id)) {
                                $agent_post_id = $agent_record->agent_post_id;
                            }
                            if (isset($agent_record->agency_id)) {
                                $sessionData['agency_id'] = $agent_record->agency_id;
                            }
                        }
                    }
                    // Ne pas utiliser user->ID comme fallback pour user_post_id
                    // Si agent_post_id n'existe pas, laisser null pour indiquer qu'il n'y a pas de profil agent
                    $sessionData['user_post_id'] = $agent_post_id ?: null;
                    $sessionData['agent_id'] = $user->ID;
                } else {
                    $sessionData['user_post_id'] = $user->ID;
                }

                if ($mappedRole === 'agency') {
                    $sessionData['agency_id'] = $agency_id;
                }

                $this->session->set_userdata($sessionData);

                // Redirect by role
                if ($mappedRole === 'administrator') {
                    redirect('dashboard/admin');
                } elseif ($mappedRole === 'agency') {
                    redirect('dashboard/agency/'.$agency_id);
                } elseif ($mappedRole === 'agent') {
                    redirect('dashboard/agent');
                } else {
                    redirect('dashboard');
                }
            }
        }

        $this->session->set_flashdata('error', 'Login WordPress échoué. Vérifie le mot de passe.');
        redirect('login');
    }
}
?>
