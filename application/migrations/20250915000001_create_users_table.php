<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_users_table extends CI_Migration {

    public function up()
    {
        // Vérifier si la table wp_users existe déjà
        if (!$this->db->table_exists('wp_users')) {
            // Table des utilisateurs (agents)
            $this->dbforge->add_field(array(
                'ID' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
                ),
                'user_login' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 60,
                    'null' => FALSE,
                    'default' => ''
                ),
                'user_pass' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => FALSE,
                    'default' => ''
                ),
                'user_nicename' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'null' => FALSE,
                    'default' => ''
                ),
                'user_email' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'null' => FALSE,
                    'default' => ''
                ),
                'user_url' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'null' => FALSE,
                    'default' => ''
                ),
                'user_registered' => array(
                    'type' => 'DATETIME',
                    'null' => FALSE,
                    'default' => '0000-00-00 00:00:00'
                ),
                'user_activation_key' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => FALSE,
                    'default' => ''
                ),
                'user_status' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => FALSE,
                    'default' => 0
                ),
                'display_name' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 250,
                    'null' => FALSE,
                    'default' => ''
                )
            ));
            $this->dbforge->add_key('ID', TRUE);
            $this->dbforge->add_key('user_login');
            $this->dbforge->add_key('user_nicename');
            $this->dbforge->add_key('user_email');
            $this->dbforge->create_table('wp_users');

            // Insérer quelques agents de test
            $test_agents = array(
                array(
                    'user_login' => 'agent1',
                    'user_pass' => password_hash('password123', PASSWORD_DEFAULT),
                    'user_nicename' => 'agent-1',
                    'user_email' => 'agent1@rebencia.com',
                    'user_registered' => date('Y-m-d H:i:s'),
                    'display_name' => 'Marie Dupont',
                    'user_status' => 0
                ),
                array(
                    'user_login' => 'agent2',
                    'user_pass' => password_hash('password123', PASSWORD_DEFAULT),
                    'user_nicename' => 'agent-2',
                    'user_email' => 'agent2@rebencia.com',
                    'user_registered' => date('Y-m-d H:i:s'),
                    'display_name' => 'Pierre Martin',
                    'user_status' => 0
                ),
                array(
                    'user_login' => 'agent3',
                    'user_pass' => password_hash('password123', PASSWORD_DEFAULT),
                    'user_nicename' => 'agent-3',
                    'user_email' => 'agent3@rebencia.com',
                    'user_registered' => date('Y-m-d H:i:s'),
                    'display_name' => 'Sophie Dubois',
                    'user_status' => 0
                )
            );
            
            $this->db->insert_batch('wp_users', $test_agents);
        }

        // Créer la table wp_usermeta si elle n'existe pas
        if (!$this->db->table_exists('wp_usermeta')) {
            $this->dbforge->add_field(array(
                'umeta_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
                ),
                'user_id' => array(
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => TRUE,
                    'null' => FALSE,
                    'default' => 0
                ),
                'meta_key' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => TRUE
                ),
                'meta_value' => array(
                    'type' => 'LONGTEXT',
                    'null' => TRUE
                )
            ));
            $this->dbforge->add_key('umeta_id', TRUE);
            $this->dbforge->add_key('user_id');
            $this->dbforge->add_key('meta_key');
            $this->dbforge->create_table('wp_usermeta');

            // Insérer les capabilities pour nos agents de test
            $agents_result = $this->db->get('wp_users');
            $agent_capabilities = array();
            
            foreach ($agents_result->result() as $agent) {
                $agent_capabilities[] = array(
                    'user_id' => $agent->ID,
                    'meta_key' => 'wp_capabilities',
                    'meta_value' => 'a:1:{s:12:"houzez_agent";b:1;}'
                );
                $agent_capabilities[] = array(
                    'user_id' => $agent->ID,
                    'meta_key' => 'wp_user_level',
                    'meta_value' => '0'
                );
                $agent_capabilities[] = array(
                    'user_id' => $agent->ID,
                    'meta_key' => 'fave_agent_agency',
                    'meta_value' => '1'
                );
            }
            
            if (!empty($agent_capabilities)) {
                $this->db->insert_batch('wp_usermeta', $agent_capabilities);
            }
        }

        // Créer la vue pour le dashboard des objectifs
        $view_sql = "CREATE OR REPLACE VIEW v_objectives_dashboard AS
        SELECT 
            mo.id,
            mo.agent_id,
            mo.month,
            mo.estimations_target,
            mo.contacts_target,
            mo.transactions_target,
            mo.revenue_target,
            u.display_name as agent_name,
            COALESCE(ap.estimations_count, 0) as estimations_count,
            COALESCE(ap.contacts_count, 0) as contacts_count,
            COALESCE(ap.transactions_count, 0) as transactions_count,
            COALESCE(ap.revenue_amount, 0) as revenue_amount,
            COALESCE(ap.commission_earned, 0) as commission_earned,
            CASE 
                WHEN mo.estimations_target > 0 THEN ROUND((COALESCE(ap.estimations_count, 0) / mo.estimations_target) * 100, 2)
                ELSE 0 
            END as estimations_progress,
            CASE 
                WHEN mo.contacts_target > 0 THEN ROUND((COALESCE(ap.contacts_count, 0) / mo.contacts_target) * 100, 2)
                ELSE 0 
            END as contacts_progress,
            CASE 
                WHEN mo.transactions_target > 0 THEN ROUND((COALESCE(ap.transactions_count, 0) / mo.transactions_target) * 100, 2)
                ELSE 0 
            END as transactions_progress,
            CASE 
                WHEN mo.revenue_target > 0 THEN ROUND((COALESCE(ap.revenue_amount, 0) / mo.revenue_target) * 100, 2)
                ELSE 0 
            END as revenue_progress
        FROM monthly_objectives mo
        LEFT JOIN wp_users u ON u.ID = mo.agent_id
        LEFT JOIN agent_performance ap ON ap.agent_id = mo.agent_id AND ap.month = mo.month";
        
        $this->db->query($view_sql);
    }

    public function down()
    {
        $this->db->query("DROP VIEW IF EXISTS v_objectives_dashboard");
        
        if ($this->db->table_exists('wp_usermeta')) {
            $this->dbforge->drop_table('wp_usermeta');
        }
        
        if ($this->db->table_exists('wp_users')) {
            $this->dbforge->drop_table('wp_users');
        }
    }
}
