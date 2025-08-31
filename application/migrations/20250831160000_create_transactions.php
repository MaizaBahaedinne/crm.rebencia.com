<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_transactions extends CI_Migration {
    public function up() {
        if(!$this->db->table_exists('crm_transactions')) {
            $sql = "CREATE TABLE crm_transactions (\n"
                ." id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,\n"
                ." titre VARCHAR(200) NOT NULL,\n"
                ." type ENUM('vente','location') NOT NULL DEFAULT 'vente',\n"
                ." commercial VARCHAR(150) NULL,\n"
                ." montant DECIMAL(14,2) NULL,\n"
                ." statut ENUM('nouveau','actif','cloture','annule') NOT NULL DEFAULT 'nouveau',\n"
                ." date_cloture DATE NULL,\n"
                ." notes TEXT NULL,\n"
                ." created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,\n"
                ." updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,\n"
                ." KEY idx_type (type),\n"
                ." KEY idx_statut (statut),\n"
                ." KEY idx_date (date_cloture)\n"
                .") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
            $this->db->query($sql);
        } else {
            // Ajout colonnes si migration appliquée partiellement
            $fields = $this->db->list_fields('crm_transactions');
            if(!in_array('notes',$fields)) {
                $this->db->query("ALTER TABLE crm_transactions ADD notes TEXT NULL AFTER date_cloture");
            }
        }
    }

    public function down() {
        // On ne drop pas en production; commenter pour rollback si nécessaire
        // $this->db->query("DROP TABLE IF EXISTS crm_transactions");
    }
}
