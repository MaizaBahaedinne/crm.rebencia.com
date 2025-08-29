<?php
defined('BASEPATH') OR exit('No direct script access allowed');


require APPPATH . '/libraries/BaseController.php';
class Transaction extends BaseController {
    public function index() {
        $this->loadViews('transactions/list', []);
    }
    public function sales() {
        $this->loadViews('transactions/sales', []);
    }
    public function rentals() {
        $this->loadViews('transactions/rentals', []);
    }
}
