<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agent extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
    }

    // Test simple
    public function test() {
        echo "Agent controller fonctionne !";
        echo "<br>Date: " . date('Y-m-d H:i:s');
        echo "<br><a href='" . base_url('agents') . "'>Retour aux agents</a>";
    }

    public function index() {
        echo "Page agents - index";
    }
}
