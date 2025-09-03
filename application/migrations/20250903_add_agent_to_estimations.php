<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_agent_to_estimations extends CI_Migration {
    public function up() {
        $fields = [
            'agent_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => TRUE,
                'after' => 'zone_id'
            ]
        ];
        $this->dbforge->add_column('crm_properties', $fields);
    }
    public function down() {
        $this->dbforge->drop_column('crm_properties', 'agent_id');
    }
}
