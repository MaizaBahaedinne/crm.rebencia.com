<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_lead_files extends CI_Migration {
    public function up() {
        if(!$this->db->table_exists('crm_lead_files')) {
            $this->db->query("CREATE TABLE crm_lead_files (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                lead_id BIGINT UNSIGNED NOT NULL,
                filename VARCHAR(255) NOT NULL,
                original_name VARCHAR(255) NULL,
                mime_type VARCHAR(120) NULL,
                taille INT UNSIGNED NULL,
                categorie ENUM('id','justif_domicile','contrat','autre') DEFAULT 'autre',
                uploaded_by INT UNSIGNED NULL,
                uploaded_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_files_lead (lead_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        }
    }
    public function down() {
        if($this->db->table_exists('crm_lead_files')) {
            $this->db->query("DROP TABLE crm_lead_files");
        }
    }
}
