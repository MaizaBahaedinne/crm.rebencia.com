<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Test_Profile_Fix extends BaseController {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
    }

    public function index() {
        echo "<h1>Test Correction Profile</h1>";
        
        // Simuler ce que fait le contrôleur Profile
        $this->isLoggedIn();
        
        echo "<h2>1. Session après isLoggedIn():</h2>";
        echo "vendorId: " . $this->vendorId . "<br>";
        echo "global[userId]: " . $this->global['userId'] . "<br>";
        echo "wp_id from session: " . $this->session->userdata('wp_id') . "<br>";
        
        // Logique corrigée du Profile
        $userId = $this->global['userId'] ?? $this->vendorId ?? $this->session->userdata('wp_id');
        echo "<h2>2. userId final: " . $userId . "</h2>";
        
        if (empty($userId)) {
            echo "<strong>ERREUR: Pas d'ID utilisateur trouvé</strong>";
            return;
        }
        
        echo "<h2>3. Test User_model->get_wp_user($userId):</h2>";
        $user = $this->user_model->get_wp_user($userId);
        
        if (empty($user)) {
            echo "<strong>ERREUR: Aucun utilisateur trouvé pour ID = $userId</strong>";
        } else {
            echo "<strong>SUCCESS: Utilisateur trouvé !</strong><br>";
            echo "<pre>" . print_r($user, true) . "</pre>";
        }
        
        echo "<h2>4. Test structure de données pour la vue:</h2>";
        if (!empty($user)) {
            $profile_data = [
                'name' => $user['display_name'] ?? 'Nom non défini',
                'user_email' => $user['user_email'] ?? '',
                'email' => $user['user_email'] ?? '',
                'mobile' => $user['mobile'] ?? '',
                'phone' => $user['phone'] ?? '',
                'location' => $user['location'] ?? '',
                'bio' => $user['bio'] ?? '',
                'agency_name' => $user['agency_name'] ?? '',
                'agent_name' => $user['agent_name'] ?? ''
            ];
            echo "<pre>" . print_r($profile_data, true) . "</pre>";
        }
    }
}
?>
