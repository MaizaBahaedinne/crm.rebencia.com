<?php
defined('BASEPATH') OR exit('No direct script access allowed');


require APPPATH . '/libraries/BaseController.php';
class Report extends BaseController {
    public function sales() {
        $this->loadViews('reports/sales', []);
    }
    public function leads() {
        $this->loadViews('reports/leads', []);
    }
    public function agency_performance() {
        $this->loadViews('reports/agency_performance', []);
    }
    public function agency() {
        $this->loadViews('reports/agency', []);
    }
}
