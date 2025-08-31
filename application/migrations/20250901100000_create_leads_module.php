<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_leads_module extends CI_Migration {
    public function up() {
        // 1. Sources
        if(!$this->db->table_exists('crm_lead_sources')) {
            $this->db->query("CREATE TABLE crm_lead_sources (\n"
                ." id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,\n"
                ." code VARCHAR(50) NOT NULL UNIQUE,\n"
                ." libelle VARCHAR(150) NOT NULL,\n"
                ." actif TINYINT(1) NOT NULL DEFAULT 1\n"
                .") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
            $this->db->insert_batch('crm_lead_sources', [
                ['code'=>'site','libelle'=>'Formulaire site'],
                ['code'=>'appel','libelle'=>'Appel entrant'],
                ['code'=>'email','libelle'=>'Email'],
                ['code'=>'ref','libelle'=>'Recommandation'],
                ['code'=>'salon','libelle'=>'Salon / Évènement'],
                ['code'=>'reseaux','libelle'=>'Réseaux sociaux']
            ]);
        }

        // 2. Tags
        if(!$this->db->table_exists('crm_tags')) {
            $this->db->query("CREATE TABLE crm_tags (\n"
                ." id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,\n"
                ." slug VARCHAR(80) NOT NULL UNIQUE,\n"
                ." libelle VARCHAR(120) NOT NULL,\n"
                ." type ENUM('lead','global') DEFAULT 'lead',\n"
                ." couleur VARCHAR(20) NULL\n"
                .") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
            $this->db->insert_batch('crm_tags', [
                ['slug'=>'chaud','libelle'=>'Chaud','type'=>'lead','couleur'=>'#dc3545'],
                ['slug'=>'froid','libelle'=>'Froid','type'=>'lead','couleur'=>'#6c757d'],
                ['slug'=>'invest','libelle'=>'Investisseur','type'=>'lead','couleur'=>'#0d6efd'],
                ['slug'=>'premium','libelle'=>'Premium','type'=>'lead','couleur'=>'#198754']
            ]);
        }

        // 3. Property types
        if(!$this->db->table_exists('crm_property_types')) {
            $this->db->query("CREATE TABLE crm_property_types (\n"
                ." id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,\n"
                ." code VARCHAR(60) NOT NULL UNIQUE,\n"
                ." libelle VARCHAR(120) NOT NULL\n"
                .") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
            $this->db->insert_batch('crm_property_types', [
                ['code'=>'appartement','libelle'=>'Appartement'],
                ['code'=>'villa','libelle'=>'Villa'],
                ['code'=>'maison','libelle'=>'Maison'],
                ['code'=>'bureau','libelle'=>'Bureau'],
                ['code'=>'terrain','libelle'=>'Terrain']
            ]);
        }

        // 4. Leads main table
        if(!$this->db->table_exists('crm_leads')) {
            $this->db->query("CREATE TABLE crm_leads (\n"
                ." id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,\n"
                ." wp_user_id BIGINT UNSIGNED NOT NULL,\n"
                ." type ENUM('acheteur','locataire') NOT NULL,\n"
                ." status ENUM('nouveau','qualifie','en_cours','converti','perdu') NOT NULL DEFAULT 'nouveau',\n"
                ." lead_score INT DEFAULT 0,\n"
                ." source_id INT UNSIGNED NULL,\n"
                ." civilite ENUM('mr','mme','mlle','autre') NULL,\n"
                ." prenom VARCHAR(100) NULL,\n"
                ." nom VARCHAR(150) NULL,\n"
                ." email VARCHAR(190) NULL,\n"
                ." telephone VARCHAR(40) NULL,\n"
                ." telephone_alt VARCHAR(40) NULL,\n"
                ." whatsapp VARCHAR(40) NULL,\n"
                ." pays VARCHAR(100) NULL,\n"
                ." ville VARCHAR(120) NULL,\n"
                ." adresse TEXT NULL,\n"
                ." code_postal VARCHAR(20) NULL,\n"
                ." company_name VARCHAR(190) NULL,\n"
                ." siret VARCHAR(32) NULL,\n"
                ." tva_intracom VARCHAR(32) NULL,\n"
                ." billing_address TEXT NULL,\n"
                ." budget_min DECIMAL(12,2) NULL,\n"
                ." budget_max DECIMAL(12,2) NULL,\n"
                ." loyer_max DECIMAL(12,2) NULL,\n"
                ." surface_min INT NULL,\n"
                ." surface_max INT NULL,\n"
                ." chambres_min TINYINT NULL,\n"
                ." localisation_pref TEXT NULL,\n"
                ." notes_interne TEXT NULL,\n"
                ." financement_type ENUM('cash','credit','mixte','inconnu') DEFAULT 'inconnu',\n"
                ." apport DECIMAL(12,2) NULL,\n"
                ." financement_statut ENUM('non_demarre','en_cours','accord_principe','accord_final') DEFAULT 'non_demarre',\n"
                ." consent_email TINYINT(1) NOT NULL DEFAULT 0,\n"
                ." consent_sms TINYINT(1) NOT NULL DEFAULT 0,\n"
                ." consent_phone TINYINT(1) NOT NULL DEFAULT 0,\n"
                ." consent_date DATETIME NULL,\n"
                ." id_type ENUM('cin','passeport','permis','autre') NULL,\n"
                ." id_number VARCHAR(80) NULL,\n"
                ." id_expiry DATE NULL,\n"
                ." email_phone_hash CHAR(64) NULL,\n"
                ." actif TINYINT(1) NOT NULL DEFAULT 1,\n"
                ." created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,\n"
                ." updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,\n"
                ." CONSTRAINT uq_lead_wp UNIQUE (wp_user_id,type),\n"
                ." INDEX idx_leads_wp (wp_user_id),\n"
                ." INDEX idx_leads_type (type),\n"
                ." INDEX idx_leads_status (status),\n"
                ." INDEX idx_leads_score (lead_score),\n"
                ." INDEX idx_leads_hash (email_phone_hash),\n"
                ." INDEX idx_leads_source (source_id)\n"
                .") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

            // Triggers pour hash (pas besoin de DELIMITER via driver CI)
            $this->db->query("CREATE TRIGGER trg_crm_leads_hash_ins BEFORE INSERT ON crm_leads FOR EACH ROW SET NEW.email_phone_hash = SHA2(CONCAT(LOWER(COALESCE(NEW.email,'')),'#',COALESCE(NEW.telephone,'')),256)");
            $this->db->query("CREATE TRIGGER trg_crm_leads_hash_upd BEFORE UPDATE ON crm_leads FOR EACH ROW IF (NEW.email <> OLD.email) OR (NEW.telephone <> OLD.telephone) THEN SET NEW.email_phone_hash = SHA2(CONCAT(LOWER(COALESCE(NEW.email,'')),'#',COALESCE(NEW.telephone,'')),256); END IF");
        }

        // 5. Pivot lead_tags
        if(!$this->db->table_exists('crm_lead_tags')) {
            $this->db->query("CREATE TABLE crm_lead_tags (\n"
                ." lead_id BIGINT UNSIGNED NOT NULL,\n"
                ." tag_id INT UNSIGNED NOT NULL,\n"
                ." PRIMARY KEY (lead_id, tag_id),\n"
                ." INDEX idx_lead_tags_tag (tag_id)\n"
                .") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        }

        // 6. Pivot lead_property_types
        if(!$this->db->table_exists('crm_lead_property_types')) {
            $this->db->query("CREATE TABLE crm_lead_property_types (\n"
                ." lead_id BIGINT UNSIGNED NOT NULL,\n"
                ." property_type_id INT UNSIGNED NOT NULL,\n"
                ." PRIMARY KEY (lead_id, property_type_id),\n"
                ." INDEX idx_lead_ptype_type (property_type_id)\n"
                .") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        }

        // 7. Activities
        if(!$this->db->table_exists('crm_lead_activities')) {
            $this->db->query("CREATE TABLE crm_lead_activities (\n"
                ." id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,\n"
                ." lead_id BIGINT UNSIGNED NOT NULL,\n"
                ." user_id INT UNSIGNED NULL,\n"
                ." type ENUM('note','appel','email','visite','maj','tache') NOT NULL DEFAULT 'note',\n"
                ." titre VARCHAR(190) NULL,\n"
                ." contenu TEXT NULL,\n"
                ." meta JSON NULL,\n"
                ." created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,\n"
                ." INDEX idx_act_lead (lead_id),\n"
                ." INDEX idx_act_type (type)\n"
                .") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        }

        // 8. Files
        if(!$this->db->table_exists('crm_lead_files')) {
            $this->db->query("CREATE TABLE crm_lead_files (\n"
                ." id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,\n"
                ." lead_id BIGINT UNSIGNED NOT NULL,\n"
                ." filename VARCHAR(255) NOT NULL,\n"
                ." original_name VARCHAR(255) NULL,\n"
                ." mime_type VARCHAR(120) NULL,\n"
                ." taille INT UNSIGNED NULL,\n"
                ." categorie ENUM('id','justif_domicile','contrat','autre') DEFAULT 'autre',\n"
                ." uploaded_by INT UNSIGNED NULL,\n"
                ." uploaded_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,\n"
                ." INDEX idx_files_lead (lead_id)\n"
                .") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        }

        // 9. Preferences (optionnel)
        if(!$this->db->table_exists('crm_lead_preferences')) {
            $this->db->query("CREATE TABLE crm_lead_preferences (\n"
                ." lead_id BIGINT UNSIGNED PRIMARY KEY,\n"
                ." json_prefs JSON NULL,\n"
                ." updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP\n"
                .") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        }
    }

    public function down() {
        // Suppression dans ordre inverse (triggers d'abord)
        if($this->db->table_exists('crm_leads')) {
            $this->db->query("DROP TRIGGER IF EXISTS trg_crm_leads_hash_ins");
            $this->db->query("DROP TRIGGER IF EXISTS trg_crm_leads_hash_upd");
        }
        $tables = [
            'crm_lead_preferences',
            'crm_lead_files',
            'crm_lead_activities',
            'crm_lead_property_types',
            'crm_lead_tags',
            'crm_leads',
            'crm_property_types',
            'crm_tags',
            'crm_lead_sources'
        ];
        foreach($tables as $t) {
            if($this->db->table_exists($t)) $this->db->query("DROP TABLE IF EXISTS `{$t}`");
        }
    }
}
