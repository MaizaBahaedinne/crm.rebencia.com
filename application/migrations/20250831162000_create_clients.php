<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_clients extends CI_Migration {
    public function up() {
        if(!$this->db->table_exists('crm_clients')) {
            $this->db->query("CREATE TABLE crm_clients (\n"
                ." id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,\n"
                ." nom VARCHAR(150) NOT NULL,\n"
                ." email VARCHAR(190) NULL,\n"
                ." telephone VARCHAR(60) NULL,\n"
                ." type ENUM('acheteur','vendeur','locataire','bailleur') NOT NULL DEFAULT 'acheteur',\n"
                ." origine VARCHAR(120) NULL,\n"
                ." statut ENUM('actif','inactif') NOT NULL DEFAULT 'actif',\n"
                ." notes TEXT NULL,\n"
                ." created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,\n"
                ." updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,\n"
                ." KEY idx_type (type),\n"
                ." KEY idx_statut (statut),\n"
                ." KEY idx_email (email)\n"
                .") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        }
    }
    public function down() {
        // $this->db->query("DROP TABLE IF EXISTS crm_clients");
    }
}
