<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_client_identity_to_leads extends CI_Migration {
    public function up() {
        // Ajout des champs identité client
        if ($this->db->table_exists('crm_leads')) {
            $this->_addColumnIfNotExists('crm_leads', 'client_type', "ALTER TABLE crm_leads ADD COLUMN client_type ENUM('personne','societe') NULL AFTER status");
            $this->_addColumnIfNotExists('crm_leads', 'client_identite_type', "ALTER TABLE crm_leads ADD COLUMN client_identite_type ENUM('cin','passeport','titre_sejour','rc','mf','autre') NULL AFTER client_type");
            $this->_addColumnIfNotExists('crm_leads', 'client_identite_numero', "ALTER TABLE crm_leads ADD COLUMN client_identite_numero VARCHAR(80) NULL AFTER client_identite_type");
            $this->_addColumnIfNotExists('crm_leads', 'client_identite_date', "ALTER TABLE crm_leads ADD COLUMN client_identite_date DATE NULL AFTER client_identite_numero");
            $this->_addColumnIfNotExists('crm_leads', 'client_identite_lieu', "ALTER TABLE crm_leads ADD COLUMN client_identite_lieu VARCHAR(120) NULL AFTER client_identite_date");
            $this->_addColumnIfNotExists('crm_leads', 'client_identite_date_expiration', "ALTER TABLE crm_leads ADD COLUMN client_identite_date_expiration DATE NULL AFTER client_identite_lieu");
        }
    }
    private function _addColumnIfNotExists($table, $column, $alter) {
        $cols = $this->db->field_data($table);
        foreach($cols as $c) if($c->name == $column) return;
        $this->db->query($alter);
    }
    public function down() {
        if ($this->db->table_exists('crm_leads')) {
            foreach(['client_type','client_identite_type','client_identite_numero','client_identite_date','client_identite_lieu','client_identite_date_expiration'] as $col) {
                $this->_dropColumnIfExists('crm_leads', $col);
            }
        }
    }
    private function _dropColumnIfExists($table, $column) {
        $cols = $this->db->field_data($table);
        foreach($cols as $c) if($c->name == $column) {
            $this->db->query("ALTER TABLE $table DROP COLUMN $column");
            return;
        }
    }
}
