<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lead extends BaseController {
    public function index() {
        $this->loadViews('leads/list', []);
    }
    public function conversion() {
        $this->loadViews('leads/conversion', []);
    }
    public function followup() {
        $this->loadViews('leads/followup', []);
    }
    public function status() {
        $this->loadViews('leads/status', []);
    }
}
