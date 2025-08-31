-- Migration extension estimation & zones (2025-08-31)
-- Usage : exécuter ce fichier dans votre base CRM.
-- Il ajoute / complète toutes les colonnes nécessaires sans planter si elles existent déjà (MySQL 8+).

/* =============================================================
   1. CREATION DES TABLES (si nouvelle installation)
   ------------------------------------------------------------- */
CREATE TABLE IF NOT EXISTS crm_zones (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS crm_properties (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS crm_property_photos (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  property_id INT UNSIGNED NOT NULL,
  file VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_photo_property FOREIGN KEY (property_id) REFERENCES crm_properties(id) ON DELETE CASCADE ON UPDATE CASCADE,
  KEY idx_prop (property_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/* =============================================================
   2. MIGRATION INCREMENTALE (ajouts conditionnels) - MySQL >= 8
   ------------------------------------------------------------- */
-- ZONES : colonnes (ignore si déjà créées)
ALTER TABLE crm_zones
  ADD COLUMN IF NOT EXISTS prix_m2_min DECIMAL(12,2) NULL AFTER nom,
  ADD COLUMN IF NOT EXISTS prix_m2_max DECIMAL(12,2) NULL AFTER prix_m2_min,
  ADD COLUMN IF NOT EXISTS transport_score TINYINT UNSIGNED NULL AFTER rendement_locatif_moyen,
  ADD COLUMN IF NOT EXISTS commodites_score TINYINT UNSIGNED NULL AFTER transport_score,
  ADD COLUMN IF NOT EXISTS securite_score TINYINT UNSIGNED NULL AFTER commodites_score,
  ADD COLUMN IF NOT EXISTS transport_description TEXT NULL AFTER securite_score,
  ADD COLUMN IF NOT EXISTS commodites_description TEXT NULL AFTER transport_description;

-- Initialisation fourchette si vide
UPDATE crm_zones
  SET prix_m2_min = IFNULL(prix_m2_min, ROUND(prix_m2_moyen*0.90,2)),
      prix_m2_max = IFNULL(prix_m2_max, ROUND(prix_m2_moyen*1.10,2));

-- PROPERTIES : ajout conditionnel
ALTER TABLE crm_properties
  ADD COLUMN IF NOT EXISTS type_bien VARCHAR(50) NULL AFTER zone_id,
  ADD COLUMN IF NOT EXISTS energie_classe VARCHAR(3) NULL AFTER type_bien,
  ADD COLUMN IF NOT EXISTS ascenseur TINYINT(1) NULL AFTER etage,
  ADD COLUMN IF NOT EXISTS piscine TINYINT(1) NULL AFTER ascenseur,
  ADD COLUMN IF NOT EXISTS securite TINYINT(1) NULL AFTER piscine,
  ADD COLUMN IF NOT EXISTS syndic TINYINT(1) NULL AFTER securite,
  ADD COLUMN IF NOT EXISTS jardin TINYINT(1) NULL AFTER syndic,
  ADD COLUMN IF NOT EXISTS proximite_transports_score TINYINT UNSIGNED NULL AFTER jardin,
  ADD COLUMN IF NOT EXISTS proximite_commodites_score TINYINT UNSIGNED NULL AFTER proximite_transports_score,
  ADD COLUMN IF NOT EXISTS proposition_agence DECIMAL(14,2) NULL AFTER prix_demande,
  ADD COLUMN IF NOT EXISTS proposition_commentaire TEXT NULL AFTER proposition_agence,
  ADD COLUMN IF NOT EXISTS valeur_min_estimee DECIMAL(14,2) NULL AFTER valeur_estimee,
  ADD COLUMN IF NOT EXISTS valeur_max_estimee DECIMAL(14,2) NULL AFTER valeur_min_estimee,
  ADD COLUMN IF NOT EXISTS cave TINYINT(1) NULL AFTER jardin,
  ADD COLUMN IF NOT EXISTS cheminee TINYINT(1) NULL AFTER cave,
  ADD COLUMN IF NOT EXISTS meuble TINYINT(1) NULL AFTER cheminee,
  ADD COLUMN IF NOT EXISTS sdb_type VARCHAR(20) NULL AFTER meuble,
  ADD COLUMN IF NOT EXISTS sol_type VARCHAR(20) NULL AFTER sdb_type,
  ADD COLUMN IF NOT EXISTS portail_auto TINYINT(1) NULL AFTER sol_type,
  ADD COLUMN IF NOT EXISTS gardien TINYINT(1) NULL AFTER portail_auto,
  ADD COLUMN IF NOT EXISTS videosurveillance TINYINT(1) NULL AFTER gardien,
  ADD COLUMN IF NOT EXISTS interphone TINYINT(1) NULL AFTER videosurveillance,
  ADD COLUMN IF NOT EXISTS alarme TINYINT(1) NULL AFTER interphone,
  ADD COLUMN IF NOT EXISTS fibre TINYINT(1) NULL AFTER alarme,
  ADD COLUMN IF NOT EXISTS lave_linge TINYINT(1) NULL AFTER fibre,
  ADD COLUMN IF NOT EXISTS seche_linge TINYINT(1) NULL AFTER lave_linge,
  ADD COLUMN IF NOT EXISTS chauffe_eau VARCHAR(20) NULL AFTER seche_linge,
  ADD COLUMN IF NOT EXISTS gaz_type VARCHAR(20) NULL AFTER chauffe_eau,
  ADD COLUMN IF NOT EXISTS proximite_ecoles_score TINYINT UNSIGNED NULL AFTER proximite_commodites_score,
  ADD COLUMN IF NOT EXISTS proximite_sante_score TINYINT UNSIGNED NULL AFTER proximite_ecoles_score,
  ADD COLUMN IF NOT EXISTS proximite_commerces_score TINYINT UNSIGNED NULL AFTER proximite_sante_score,
  ADD COLUMN IF NOT EXISTS proximite_espaces_verts_score TINYINT UNSIGNED NULL AFTER proximite_commerces_score,
  ADD COLUMN IF NOT EXISTS proximite_plage_score TINYINT UNSIGNED NULL AFTER proximite_espaces_verts_score;

-- Index (créés si absents) - MySQL n'a pas IF NOT EXISTS pour CREATE INDEX; à ignorer si erreurs 'duplicate'
-- Index : version directe (désactiver si déjà créés)
-- CREATE INDEX idx_prop_type_bien ON crm_properties(type_bien);
-- CREATE INDEX idx_prop_energie ON crm_properties(energie_classe);
-- CREATE INDEX idx_prop_statut ON crm_properties(statut_dossier);
-- CREATE INDEX idx_prop_zone_created ON crm_properties(zone_id, created_at);

/* Création conditionnelle des index (compatible MySQL 5.7 & 8, sans multi‑statements) */
/* idx_prop_type_bien */
SET @sql := IF(
  (SELECT COUNT(1) FROM INFORMATION_SCHEMA.STATISTICS
     WHERE TABLE_SCHEMA = DATABASE()
       AND TABLE_NAME = 'crm_properties'
       AND INDEX_NAME = 'idx_prop_type_bien') = 0,
  'CREATE INDEX idx_prop_type_bien ON crm_properties(type_bien)',
  'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

/* idx_prop_energie */
SET @sql := IF(
  (SELECT COUNT(1) FROM INFORMATION_SCHEMA.STATISTICS
     WHERE TABLE_SCHEMA = DATABASE()
       AND TABLE_NAME = 'crm_properties'
       AND INDEX_NAME = 'idx_prop_energie') = 0,
  'CREATE INDEX idx_prop_energie ON crm_properties(energie_classe)',
  'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

/* idx_prop_statut */
SET @sql := IF(
  (SELECT COUNT(1) FROM INFORMATION_SCHEMA.STATISTICS
     WHERE TABLE_SCHEMA = DATABASE()
       AND TABLE_NAME = 'crm_properties'
       AND INDEX_NAME = 'idx_prop_statut') = 0,
  'CREATE INDEX idx_prop_statut ON crm_properties(statut_dossier)',
  'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

/* idx_prop_zone_created */
SET @sql := IF(
  (SELECT COUNT(1) FROM INFORMATION_SCHEMA.STATISTICS
     WHERE TABLE_SCHEMA = DATABASE()
       AND TABLE_NAME = 'crm_properties'
       AND INDEX_NAME = 'idx_prop_zone_created') = 0,
  'CREATE INDEX idx_prop_zone_created ON crm_properties(zone_id, created_at)',
  'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

/* =============================================================
   3. FALLBACK POUR MySQL < 8 (sans ADD COLUMN IF NOT EXISTS)
   - Commentez la section 2 et utilisez requêtes INFORMATION_SCHEMA
   Exemple :
   SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='crm_zones' AND COLUMN_NAME='transport_score';
   Si 0 ligne => ALTER TABLE crm_zones ADD COLUMN transport_score TINYINT UNSIGNED NULL AFTER rendement_locatif_moyen;
   Répéter pour chaque colonne nécessaire.
   ------------------------------------------------------------- */

/* =============================================================
   4. ROLLBACK (OPTIONNEL) - SUPPRIMER COLONNES AJOUTEES
   -------------------------------------------------------------
-- Exemple rollback zones :
-- ALTER TABLE crm_zones DROP COLUMN commodites_description, DROP COLUMN transport_description,
--   DROP COLUMN securite_score, DROP COLUMN commodites_score, DROP COLUMN transport_score,
--   DROP COLUMN prix_m2_max, DROP COLUMN prix_m2_min;

-- Exemple rollback properties (adapter) :
-- ALTER TABLE crm_properties
--   DROP COLUMN proximite_plage_score, DROP COLUMN proximite_espaces_verts_score,
--   DROP COLUMN proximite_commerces_score, DROP COLUMN proximite_sante_score,
--   DROP COLUMN proximite_ecoles_score, DROP COLUMN gaz_type, DROP COLUMN chauffe_eau,
--   DROP COLUMN seche_linge, DROP COLUMN lave_linge, DROP COLUMN fibre, DROP COLUMN alarme,
--   DROP COLUMN interphone, DROP COLUMN videosurveillance, DROP COLUMN gardien, DROP COLUMN portail_auto,
--   DROP COLUMN sol_type, DROP COLUMN sdb_type, DROP COLUMN meuble, DROP COLUMN cheminee, DROP COLUMN cave,
--   DROP COLUMN valeur_max_estimee, DROP COLUMN valeur_min_estimee, DROP COLUMN proposition_commentaire,
--   DROP COLUMN proposition_agence, DROP COLUMN proximite_commodites_score, DROP COLUMN proximite_transports_score,
--   DROP COLUMN jardin, DROP COLUMN syndic, DROP COLUMN securite, DROP COLUMN piscine, DROP COLUMN ascenseur,
--   DROP COLUMN energie_classe, DROP COLUMN type_bien;
============================================================= */

-- Fin migration
