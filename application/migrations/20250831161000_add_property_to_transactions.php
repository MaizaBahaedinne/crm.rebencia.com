<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_property_to_transactions extends CI_Migration {
    public function up() {
        if($this->db->table_exists('crm_transactions')) {
            $fields = $this->db->list_fields('crm_transactions');
            if(!in_array('property_id',$fields)) {
                $this->db->query("ALTER TABLE crm_transactions ADD property_id INT UNSIGNED NULL AFTER id, ADD KEY idx_property (property_id)");
            }
        }
    }
    public function down() {
        // Pas de suppression en production
        // $this->db->query("ALTER TABLE crm_transactions DROP COLUMN property_id");
    }
}
