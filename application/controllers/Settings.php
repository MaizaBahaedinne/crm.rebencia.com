<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends BaseController {
    public function roles() {
        $this->loadViews('settings/roles', []);
    }
    public function wordpress() {
        $this->loadViews('settings/wordpress', []);
    }
    public function crm() {
        $this->loadViews('settings/crm', []);
    }
}
