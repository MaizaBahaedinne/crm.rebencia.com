<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends BaseController {
    public function index() {
        $this->loadViews('profile/index', []);
    }
    public function avatar() {
        $this->loadViews('profile/avatar', []);
    }
}
