<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_commission_tables extends CI_Migration {

    public function up()
    {
        // Table des paramètres de commission
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'transaction_type' => array(
                'type' => 'ENUM',
                'constraint' => array('sale', 'rental'),
                'null' => FALSE
            ),
            'agency_commission_rate' => array(
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => FALSE,
                'default' => 5.00
            ),
            'agent_commission_rate' => array(
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => FALSE,
                'default' => 5.00
            ),
            'total_commission_rate' => array(
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => FALSE,
                'default' => 10.00
            ),
            'rental_months' => array(
                'type' => 'INT',
                'constraint' => 2,
                'null' => TRUE,
                'default' => 1
            ),
            'is_active' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1
            ),
            'created_at' => array(
                'type' => 'TIMESTAMP',
                'null' => FALSE,
                'default' => 'CURRENT_TIMESTAMP'
            ),
            'updated_at' => array(
                'type' => 'TIMESTAMP',
                'null' => FALSE,
                'default' => 'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('commission_settings');

        // Table des objectifs mensuels
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'agent_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => FALSE
            ),
            'month' => array(
                'type' => 'DATE',
                'null' => FALSE
            ),
            'estimations_target' => array(
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0
            ),
            'contacts_target' => array(
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0
            ),
            'transactions_target' => array(
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0
            ),
            'revenue_target' => array(
                'type' => 'DECIMAL',
                'constraint' => '12,2',
                'default' => 0.00
            ),
            'created_at' => array(
                'type' => 'TIMESTAMP',
                'null' => FALSE,
                'default' => 'CURRENT_TIMESTAMP'
            ),
            'updated_at' => array(
                'type' => 'TIMESTAMP',
                'null' => FALSE,
                'default' => 'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key(array('agent_id', 'month'), FALSE, TRUE);
        $this->dbforge->create_table('monthly_objectives');

        // Table des performances des agents
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'agent_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => FALSE
            ),
            'month' => array(
                'type' => 'DATE',
                'null' => FALSE
            ),
            'estimations_count' => array(
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0
            ),
            'contacts_count' => array(
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0
            ),
            'transactions_count' => array(
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0
            ),
            'revenue_amount' => array(
                'type' => 'DECIMAL',
                'constraint' => '12,2',
                'default' => 0.00
            ),
            'commission_earned' => array(
                'type' => 'DECIMAL',
                'constraint' => '12,2',
                'default' => 0.00
            ),
            'created_at' => array(
                'type' => 'TIMESTAMP',
                'null' => FALSE,
                'default' => 'CURRENT_TIMESTAMP'
            ),
            'updated_at' => array(
                'type' => 'TIMESTAMP',
                'null' => FALSE,
                'default' => 'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key(array('agent_id', 'month'), FALSE, TRUE);
        $this->dbforge->create_table('agent_performance');

        // Table des commissions des agents
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'agent_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => FALSE
            ),
            'transaction_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE
            ),
            'property_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE
            ),
            'transaction_type' => array(
                'type' => 'ENUM',
                'constraint' => array('sale', 'rental'),
                'null' => FALSE
            ),
            'property_value' => array(
                'type' => 'DECIMAL',
                'constraint' => '12,2',
                'null' => FALSE
            ),
            'total_commission_rate' => array(
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => FALSE
            ),
            'agent_commission_rate' => array(
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => FALSE
            ),
            'agency_commission_rate' => array(
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => FALSE
            ),
            'total_commission' => array(
                'type' => 'DECIMAL',
                'constraint' => '12,2',
                'null' => FALSE
            ),
            'agent_commission' => array(
                'type' => 'DECIMAL',
                'constraint' => '12,2',
                'null' => FALSE
            ),
            'agency_commission' => array(
                'type' => 'DECIMAL',
                'constraint' => '12,2',
                'null' => FALSE
            ),
            'status' => array(
                'type' => 'ENUM',
                'constraint' => array('pending', 'paid', 'cancelled'),
                'default' => 'pending'
            ),
            'payment_date' => array(
                'type' => 'DATE',
                'null' => TRUE
            ),
            'created_at' => array(
                'type' => 'TIMESTAMP',
                'null' => FALSE,
                'default' => 'CURRENT_TIMESTAMP'
            ),
            'updated_at' => array(
                'type' => 'TIMESTAMP',
                'null' => FALSE,
                'default' => 'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('agent_id');
        $this->dbforge->add_key('transaction_id');
        $this->dbforge->create_table('agent_commissions');

        // Insérer les paramètres par défaut
        $default_settings = array(
            array(
                'transaction_type' => 'sale',
                'agency_commission_rate' => 5.00,
                'agent_commission_rate' => 5.00,
                'total_commission_rate' => 10.00,
                'rental_months' => null,
                'is_active' => 1
            ),
            array(
                'transaction_type' => 'rental',
                'agency_commission_rate' => 5.00,
                'agent_commission_rate' => 5.00,
                'total_commission_rate' => 10.00,
                'rental_months' => 1,
                'is_active' => 1
            )
        );
        
        $this->db->insert_batch('commission_settings', $default_settings);
    }

    public function down()
    {
        $this->dbforge->drop_table('agent_commissions');
        $this->dbforge->drop_table('agent_performance');
        $this->dbforge->drop_table('monthly_objectives');
        $this->dbforge->drop_table('commission_settings');
    }
}
