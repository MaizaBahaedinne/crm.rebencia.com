-- Table pour les paramètres de commission
CREATE TABLE `commission_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('sale','rental') NOT NULL COMMENT 'Type de transaction: vente ou location',
  `agent_rate` decimal(5,2) NOT NULL DEFAULT 5.00 COMMENT 'Pourcentage commission agent',
  `agency_rate` decimal(5,2) NOT NULL DEFAULT 5.00 COMMENT 'Pourcentage commission agence',
  `rental_months` int(2) DEFAULT NULL COMMENT 'Nombre de mois pour location',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertion des valeurs par défaut
-- Pour les ventes : 5% du prix total, réparti 10% agent / 90% agence
-- Les valeurs agent_rate et agency_rate représentent maintenant la répartition de la commission totale
INSERT INTO `commission_settings` (`type`, `agent_rate`, `agency_rate`, `rental_months`) VALUES
('sale', 10.00, 90.00, NULL),
('rental', 10.00, 0.00, 1);

-- Table pour les objectifs mensuels par agent
CREATE TABLE `monthly_objectives` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agent_id` int(11) NOT NULL,
  `month` date NOT NULL COMMENT 'Premier jour du mois cible',
  `estimations_target` int(11) NOT NULL DEFAULT 0 COMMENT 'Objectif nombre estimations',
  `contacts_target` int(11) NOT NULL DEFAULT 0 COMMENT 'Objectif nombre contacts',
  `transactions_target` int(11) NOT NULL DEFAULT 0 COMMENT 'Objectif nombre transactions',
  `revenue_target` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Objectif CA en dinars tunisiens',
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_agent_month` (`agent_id`, `month`),
  KEY `idx_month` (`month`),
  KEY `idx_agent` (`agent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table pour tracker les performances réelles
CREATE TABLE `agent_performance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agent_id` int(11) NOT NULL,
  `month` date NOT NULL,
  `estimations_count` int(11) NOT NULL DEFAULT 0,
  `contacts_count` int(11) NOT NULL DEFAULT 0,
  `transactions_count` int(11) NOT NULL DEFAULT 0,
  `revenue_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `commission_earned` decimal(10,2) NOT NULL DEFAULT 0.00,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_agent_month_perf` (`agent_id`, `month`),
  KEY `idx_month_perf` (`month`),
  KEY `idx_agent_perf` (`agent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
