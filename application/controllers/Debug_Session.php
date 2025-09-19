<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Debug_Session extends BaseController {
    
    public function __construct() {
        parent::__construct();
        $this->load->library('session');
    }

    public function index() {
        echo "<h1>Debug Session Actuelle</h1>";
        
        echo "<h2>1. Toutes les données de session:</h2>";
        echo "<pre>" . print_r($this->session->all_userdata(), true) . "</pre>";
        
        echo "<h2>2. Données BaseController après isLoggedIn():</h2>";
        
        // Vérifier si l'utilisateur est connecté
        $isLoggedIn = $this->session->userdata('isLoggedIn');
        echo "isLoggedIn: " . ($isLoggedIn ? 'TRUE' : 'FALSE') . "<br>";
        
        if ($isLoggedIn) {
            // Simuler ce que fait isLoggedIn() dans BaseController
            $this->role = $this->session->userdata('role');
            $this->vendorId = $this->session->userdata('wp_id');
            $this->name = $this->session->userdata('name');
            $this->global['userId'] = $this->vendorId;
            
            echo "role: " . $this->role . "<br>";
            echo "vendorId (wp_id): " . $this->vendorId . "<br>";
            echo "name: " . $this->name . "<br>";
            echo "global[userId]: " . $this->global['userId'] . "<br>";
            
            echo "<h3>Variables disponibles:</h3>";
            echo "wp_id: " . $this->session->userdata('wp_id') . "<br>";
            echo "userId: " . $this->session->userdata('userId') . "<br>";
            echo "user_post_id: " . $this->session->userdata('user_post_id') . "<br>";
        } else {
            echo "<strong>Utilisateur non connecté - redirection vers login</strong>";
        }
        
        echo "<h2>3. Test User_model:</h2>";
        $this->load->model('user_model');
        
        if ($isLoggedIn && !empty($this->vendorId)) {
            echo "Test avec wp_id = " . $this->vendorId . "<br>";
            $user = $this->user_model->get_wp_user($this->vendorId);
            echo "<pre>" . print_r($user, true) . "</pre>";
        } else {
            echo "Pas d'ID utilisateur disponible pour le test";
        }
    }
}
?>
