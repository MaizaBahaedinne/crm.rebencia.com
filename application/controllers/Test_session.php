<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Contrôleur de test pour vérifier le user_post_id en session
 */
class Test_session extends BaseController {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index() {
        echo "<h1>Test de la Session - User Post ID</h1>";
        
        // Vérifier si l'utilisateur est connecté
        if ($this->session->userdata('isLoggedIn')) {
            $this->isLoggedIn(); // Charger les données globales
            
            echo "<h3>✅ Session Active</h3>";
            echo "<p><strong>User ID (wp_id):</strong> " . $this->session->userdata('wp_id') . "</p>";
            echo "<p><strong>Name:</strong> " . $this->session->userdata('name') . "</p>";
            echo "<p><strong>Role:</strong> " . $this->session->userdata('role') . "</p>";
            echo "<p><strong>🆕 User Post ID:</strong> " . $this->session->userdata('user_post_id') . "</p>";
            
            echo "<h4>Variables globales BaseController:</h4>";
            echo "<pre>";
            foreach($this->global as $key => $value) {
                echo "$key => $value\n";
            }
            echo "</pre>";
            
            echo "<h4>Propriétés protégées:</h4>";
            echo "<p><strong>userPostId:</strong> " . $this->userPostId . "</p>";
            
        } else {
            echo "<p>❌ Aucune session active</p>";
            echo "<p><a href='/login'>Se connecter</a></p>";
        }
        
        echo "<p><a href='/dashboard'>Retour au Dashboard</a></p>";
    }
}
