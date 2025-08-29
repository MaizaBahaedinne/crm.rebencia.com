<?php
defined('BASEPATH') OR exit('No direct script access allowed');


require APPPATH . '/libraries/BaseController.php';
class Profile extends BaseController {
    public function index() {
        $this->loadViews('profile/index', []);
    }
    public function avatar() {
        $this->loadViews('profile/avatar', []);
    }
}
