<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_transactions_add_agent_lead extends CI_Migration {
    public function up() {
        if($this->db->table_exists('crm_transactions')) {
            $fields = $this->db->list_fields('crm_transactions');
            if(!in_array('agent_id',$fields)) {
                $this->db->query("ALTER TABLE crm_transactions ADD agent_id INT UNSIGNED NULL AFTER commercial, ADD KEY idx_agent (agent_id)");
            }
            if(!in_array('lead_id',$fields)) {
                $this->db->query("ALTER TABLE crm_transactions ADD lead_id BIGINT UNSIGNED NULL AFTER agent_id, ADD KEY idx_lead (lead_id)");
            }
        }
    }
    public function down() {
        // Pas de suppression rétroactive en production
        // if($this->db->table_exists('crm_transactions')) { $this->db->query("ALTER TABLE crm_transactions DROP COLUMN lead_id, DROP COLUMN agent_id"); }
    }
}
