-- Schéma pour module d'estimation immobilière

CREATE TABLE `crm_zones` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `nom` VARCHAR(150) NOT NULL,
  `prix_m2_moyen` DECIMAL(12,2) NOT NULL DEFAULT 0,
  `rendement_locatif_moyen` DECIMAL(5,2) NOT NULL DEFAULT 0, -- %
  `latitude` DECIMAL(10,6) DEFAULT NULL,
  `longitude` DECIMAL(10,6) DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `idx_nom` (`nom`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `crm_properties` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `zone_id` INT UNSIGNED DEFAULT NULL,
  `surface_habitable` DECIMAL(10,2) DEFAULT NULL,
  `surface_terrain` DECIMAL(10,2) DEFAULT NULL,
  `nb_pieces` INT DEFAULT NULL,
  `etage` INT DEFAULT NULL,
  `orientation` VARCHAR(30) DEFAULT NULL,
  `etat_general` VARCHAR(20) DEFAULT NULL,
  `type_exterieur` VARCHAR(20) DEFAULT NULL,
  `parking` VARCHAR(5) DEFAULT NULL,
  `annee_construction` INT DEFAULT NULL,
  `equipements` TEXT DEFAULT NULL,
  `titre_foncier` VARCHAR(5) DEFAULT NULL,
  `type_propriete` VARCHAR(100) DEFAULT NULL,
  `charges` DECIMAL(10,2) DEFAULT NULL,
  `taxes` DECIMAL(10,2) DEFAULT NULL,
  `prix_demande` DECIMAL(14,2) DEFAULT NULL,
  `latitude` DECIMAL(10,6) DEFAULT NULL,
  `longitude` DECIMAL(10,6) DEFAULT NULL,
  `valeur_estimee` DECIMAL(14,2) DEFAULT NULL,
  `loyer_potentiel` DECIMAL(12,2) DEFAULT NULL,
  `rentabilite` DECIMAL(6,2) DEFAULT NULL,
  `coef_global` DECIMAL(8,4) DEFAULT NULL,
  `statut_dossier` VARCHAR(30) DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_prop_zone` FOREIGN KEY (`zone_id`) REFERENCES `crm_zones`(`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  KEY `idx_zone` (`zone_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `crm_property_photos` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `property_id` INT UNSIGNED NOT NULL,
  `file` VARCHAR(255) NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_photo_property` FOREIGN KEY (`property_id`) REFERENCES `crm_properties`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  KEY `idx_prop` (`property_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
