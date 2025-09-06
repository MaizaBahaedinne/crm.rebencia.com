<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PropertiesTest extends CI_Controller {
    
    public function index() {
        echo "Properties Controller Test - OK";
        echo "<br>";
        echo "Base URL: " . base_url();
        echo "<br>";
        echo "Current URL: " . current_url();
        
        // Test si les modèles se chargent
        try {
            $this->load->model('Property_model');
            echo "<br>Property_model loaded successfully";
        } catch (Exception $e) {
            echo "<br>Error loading Property_model: " . $e->getMessage();
        }
        
        try {
            $properties = $this->Property_model->get_all_properties();
            echo "<br>Found " . count($properties) . " properties";
        } catch (Exception $e) {
            echo "<br>Error getting properties: " . $e->getMessage();
        }
    }
}
