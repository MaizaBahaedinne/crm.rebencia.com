<?php
// Script de test pour vérifier la session
// À supprimer après test

require_once 'application/libraries/BaseController.php';

class TestSession extends BaseController {
    public function __construct() {
        parent::__construct();
    }

    public function check_session() {
        // Vérifier si l'utilisateur est connecté
        if ($this->session->userdata('isLoggedIn')) {
            echo "<h3>Session Active</h3>";
            echo "<p><strong>User ID:</strong> " . $this->session->userdata('wp_id') . "</p>";
            echo "<p><strong>Name:</strong> " . $this->session->userdata('name') . "</p>";
            echo "<p><strong>Role:</strong> " . $this->session->userdata('role') . "</p>";
            echo "<p><strong>User Post ID:</strong> " . $this->session->userdata('user_post_id') . "</p>";
            
            echo "<h4>Toutes les données de session:</h4>";
            echo "<pre>";
            print_r($this->session->all_userdata());
            echo "</pre>";
            
            echo "<h4>Variables globales BaseController:</h4>";
            echo "<pre>";
            print_r($this->global);
            echo "</pre>";
        } else {
            echo "<p>Aucune session active</p>";
        }
    }
}

// Initialiser CodeIgniter
$_ENV['CI_ENVIRONMENT'] = 'development';
define('ENVIRONMENT', isset($_ENV['CI_ENVIRONMENT']) ? $_ENV['CI_ENVIRONMENT'] : 'development');
define('BASEPATH', __DIR__ . '/system/');
define('APPPATH', __DIR__ . '/application/');

// Pour test rapide uniquement
?>

<!DOCTYPE html>
<html>
<head>
    <title>Test Session - User Post ID</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h3 { color: #333; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>Test de la Session - User Post ID</h1>
    <p>Ce script vérifie si le champ user_post_id est bien ajouté à la session.</p>
    
    <?php
    // Affichage basique pour test
    session_start();
    
    if (isset($_SESSION)) {
        echo "<h3>Session PHP Active</h3>";
        echo "<pre>";
        print_r($_SESSION);
        echo "</pre>";
    } else {
        echo "<p>Aucune session PHP trouvée</p>";
    }
    ?>
    
    <p><a href="/login">Se connecter</a> | <a href="/dashboard">Dashboard</a></p>
</body>
</html>
