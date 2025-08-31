<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Estimation_extension extends CI_Migration {
    public function up() {
        // 1. Tables crm_zones
        if (!$this->db->table_exists('crm_zones')) {
            $this->db->query("CREATE TABLE crm_zones (
              id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              nom VARCHAR(150) NOT NULL,
              prix_m2_min DECIMAL(12,2) NULL,
              prix_m2_moyen DECIMAL(12,2) NOT NULL DEFAULT 0,
              prix_m2_max DECIMAL(12,2) NULL,
              rendement_locatif_moyen DECIMAL(5,2) NOT NULL DEFAULT 0,
              transport_score TINYINT UNSIGNED NULL,
              commodites_score TINYINT UNSIGNED NULL,
              securite_score TINYINT UNSIGNED NULL,
              transport_description TEXT NULL,
              commodites_description TEXT NULL,
              latitude DECIMAL(10,6) DEFAULT NULL,
              longitude DECIMAL(10,6) DEFAULT NULL,
              created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
              updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              KEY idx_nom (nom)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        }
        // Colonnes manquantes crm_zones
        $this->_addColumnIfNotExists('crm_zones','prix_m2_min','ALTER TABLE crm_zones ADD COLUMN prix_m2_min DECIMAL(12,2) NULL AFTER nom');
        $this->_addColumnIfNotExists('crm_zones','prix_m2_max','ALTER TABLE crm_zones ADD COLUMN prix_m2_max DECIMAL(12,2) NULL AFTER prix_m2_min');
        $this->_addColumnIfNotExists('crm_zones','transport_score','ALTER TABLE crm_zones ADD COLUMN transport_score TINYINT UNSIGNED NULL AFTER rendement_locatif_moyen');
        $this->_addColumnIfNotExists('crm_zones','commodites_score','ALTER TABLE crm_zones ADD COLUMN commodites_score TINYINT UNSIGNED NULL AFTER transport_score');
        $this->_addColumnIfNotExists('crm_zones','securite_score','ALTER TABLE crm_zones ADD COLUMN securite_score TINYINT UNSIGNED NULL AFTER commodites_score');
        $this->_addColumnIfNotExists('crm_zones','transport_description','ALTER TABLE crm_zones ADD COLUMN transport_description TEXT NULL AFTER securite_score');
        $this->_addColumnIfNotExists('crm_zones','commodites_description','ALTER TABLE crm_zones ADD COLUMN commodites_description TEXT NULL AFTER transport_description');

        // Init fourchette si vide
        $this->db->query("UPDATE crm_zones SET prix_m2_min = IFNULL(prix_m2_min, ROUND(prix_m2_moyen*0.90,2)), prix_m2_max = IFNULL(prix_m2_max, ROUND(prix_m2_moyen*1.10,2))");

        // 2. Table crm_properties
        if (!$this->db->table_exists('crm_properties')) {
            $this->db->query("CREATE TABLE crm_properties (
              id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              zone_id INT UNSIGNED DEFAULT NULL,
              type_bien VARCHAR(50) NULL,
              energie_classe VARCHAR(3) NULL,
              surface_habitable DECIMAL(10,2) DEFAULT NULL,
              surface_terrain DECIMAL(10,2) DEFAULT NULL,
              nb_pieces INT DEFAULT NULL,
              etage INT DEFAULT NULL,
              ascenseur TINYINT(1) NULL,
              orientation VARCHAR(30) DEFAULT NULL,
              etat_general VARCHAR(20) DEFAULT NULL,
              type_exterieur VARCHAR(20) DEFAULT NULL,
              parking VARCHAR(5) DEFAULT NULL,
              piscine TINYINT(1) NULL,
              securite TINYINT(1) NULL,
              syndic TINYINT(1) NULL,
              jardin TINYINT(1) NULL,
              cave TINYINT(1) NULL,
              cheminee TINYINT(1) NULL,
              meuble TINYINT(1) NULL,
              sdb_type VARCHAR(20) NULL,
              sol_type VARCHAR(20) NULL,
              portail_auto TINYINT(1) NULL,
              gardien TINYINT(1) NULL,
              videosurveillance TINYINT(1) NULL,
              interphone TINYINT(1) NULL,
              alarme TINYINT(1) NULL,
              fibre TINYINT(1) NULL,
              lave_linge TINYINT(1) NULL,
              seche_linge TINYINT(1) NULL,
              chauffe_eau VARCHAR(20) NULL,
              gaz_type VARCHAR(20) NULL,
              proximite_transports_score TINYINT UNSIGNED NULL,
              proximite_commodites_score TINYINT UNSIGNED NULL,
              proximite_ecoles_score TINYINT UNSIGNED NULL,
              proximite_sante_score TINYINT UNSIGNED NULL,
              proximite_commerces_score TINYINT UNSIGNED NULL,
              proximite_espaces_verts_score TINYINT UNSIGNED NULL,
              proximite_plage_score TINYINT UNSIGNED NULL,
              titre_foncier VARCHAR(5) DEFAULT NULL,
              type_propriete VARCHAR(100) DEFAULT NULL,
              charges DECIMAL(10,2) DEFAULT NULL,
              taxes DECIMAL(10,2) DEFAULT NULL,
              prix_demande DECIMAL(14,2) DEFAULT NULL,
              latitude DECIMAL(10,6) DEFAULT NULL,
              longitude DECIMAL(10,6) DEFAULT NULL,
              valeur_min_estimee DECIMAL(14,2) DEFAULT NULL,
              valeur_estimee DECIMAL(14,2) DEFAULT NULL,
              valeur_max_estimee DECIMAL(14,2) DEFAULT NULL,
              loyer_potentiel DECIMAL(12,2) DEFAULT NULL,
              rentabilite DECIMAL(6,2) DEFAULT NULL,
              coef_global DECIMAL(8,4) DEFAULT NULL,
              statut_dossier VARCHAR(30) DEFAULT NULL,
              proposition_agence DECIMAL(14,2) NULL,
              proposition_commentaire TEXT NULL,
              equipements TEXT DEFAULT NULL,
              annee_construction INT DEFAULT NULL,
              created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
              updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              CONSTRAINT fk_prop_zone FOREIGN KEY (zone_id) REFERENCES crm_zones(id) ON DELETE SET NULL ON UPDATE CASCADE,
              KEY idx_zone (zone_id),
              KEY idx_type_bien (type_bien),
              KEY idx_energie (energie_classe),
              KEY idx_statut (statut_dossier),
              KEY idx_zone_created (zone_id, created_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        }
        // Colonnes additionnelles crm_properties (liste partielle => alignée avec SQL brut)
        $columnsProps = [
            'type_bien VARCHAR(50) NULL AFTER zone_id',
            'energie_classe VARCHAR(3) NULL AFTER type_bien',
            'ascenseur TINYINT(1) NULL AFTER etage',
            'piscine TINYINT(1) NULL AFTER ascenseur',
            'securite TINYINT(1) NULL AFTER piscine',
            'syndic TINYINT(1) NULL AFTER securite',
            'jardin TINYINT(1) NULL AFTER syndic',
            'proximite_transports_score TINYINT UNSIGNED NULL AFTER jardin',
            'proximite_commodites_score TINYINT UNSIGNED NULL AFTER proximite_transports_score',
            'proposition_agence DECIMAL(14,2) NULL AFTER prix_demande',
            'proposition_commentaire TEXT NULL AFTER proposition_agence',
            'valeur_min_estimee DECIMAL(14,2) NULL AFTER valeur_estimee',
            'valeur_max_estimee DECIMAL(14,2) NULL AFTER valeur_min_estimee',
            'cave TINYINT(1) NULL AFTER jardin',
            'cheminee TINYINT(1) NULL AFTER cave',
            'meuble TINYINT(1) NULL AFTER cheminee',
            'sdb_type VARCHAR(20) NULL AFTER meuble',
            'sol_type VARCHAR(20) NULL AFTER sdb_type',
            'portail_auto TINYINT(1) NULL AFTER sol_type',
            'gardien TINYINT(1) NULL AFTER portail_auto',
            'videosurveillance TINYINT(1) NULL AFTER gardien',
            'interphone TINYINT(1) NULL AFTER videosurveillance',
            'alarme TINYINT(1) NULL AFTER interphone',
            'fibre TINYINT(1) NULL AFTER alarme',
            'lave_linge TINYINT(1) NULL AFTER fibre',
            'seche_linge TINYINT(1) NULL AFTER lave_linge',
            'chauffe_eau VARCHAR(20) NULL AFTER seche_linge',
            'gaz_type VARCHAR(20) NULL AFTER chauffe_eau',
            'proximite_ecoles_score TINYINT UNSIGNED NULL AFTER proximite_commodites_score',
            'proximite_sante_score TINYINT UNSIGNED NULL AFTER proximite_ecoles_score',
            'proximite_commerces_score TINYINT UNSIGNED NULL AFTER proximite_sante_score',
            'proximite_espaces_verts_score TINYINT UNSIGNED NULL AFTER proximite_commerces_score',
            'proximite_plage_score TINYINT UNSIGNED NULL AFTER proximite_espaces_verts_score'
        ];
        foreach ($columnsProps as $def) {
            $colName = trim(strtok($def, ' '));
            $this->_addColumnIfNotExists('crm_properties', $colName, 'ALTER TABLE crm_properties ADD COLUMN '.$def);
        }

        // Table photos
        if (!$this->db->table_exists('crm_property_photos')) {
            $this->db->query("CREATE TABLE crm_property_photos (
              id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              property_id INT UNSIGNED NOT NULL,
              file VARCHAR(255) NOT NULL,
              created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
              CONSTRAINT fk_photo_property FOREIGN KEY (property_id) REFERENCES crm_properties(id) ON DELETE CASCADE ON UPDATE CASCADE,
              KEY idx_prop (property_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        }

        // Index conditionnels crm_properties
        $this->_createIndexIfNotExists('crm_properties','idx_prop_type_bien','type_bien');
        $this->_createIndexIfNotExists('crm_properties','idx_prop_energie','energie_classe');
        $this->_createIndexIfNotExists('crm_properties','idx_prop_statut','statut_dossier');
        $this->_createIndexIfNotExists('crm_properties','idx_prop_zone_created','zone_id, created_at');
    }

    public function down() {
        // Rollback minimal : ne supprime pas les tables pour éviter perte de données
        // Pour rollback complet, décommenter ci-dessous (danger data loss)
        // $this->db->query('DROP TABLE IF EXISTS crm_property_photos');
        // $this->db->query('DROP TABLE IF EXISTS crm_properties');
        // $this->db->query('DROP TABLE IF EXISTS crm_zones');
    }

    private function _addColumnIfNotExists($table, $column, $alterSql) {
        $exists = $this->db->query("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?", [$table, $column])->num_rows() > 0;
        if(!$exists) {
            $this->db->query($alterSql);
        }
    }

    private function _createIndexIfNotExists($table, $index, $cols) {
        $exists = $this->db->query("SELECT 1 FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND INDEX_NAME = ?", [$table, $index])->num_rows() > 0;
        if(!$exists) {
            $this->db->query("CREATE INDEX $index ON $table($cols)");
        }
    }
}
