-- Table pour les commissions calculées des agents
CREATE TABLE `agent_commissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agent_id` int(11) NOT NULL,
  `transaction_id` int(11) DEFAULT NULL,
  `property_id` int(11) DEFAULT NULL,
  `transaction_type` enum('sale','rental') NOT NULL,
  `base_amount` decimal(10,2) NOT NULL COMMENT 'Prix de vente ou loyer mensuel',
  `agent_commission` decimal(10,2) NOT NULL DEFAULT 0.00,
  `agency_commission` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_commission` decimal(10,2) NOT NULL DEFAULT 0.00,
  `commission_rate` decimal(5,2) NOT NULL COMMENT 'Taux appliqué',
  `status` enum('pending','paid','cancelled') NOT NULL DEFAULT 'pending',
  `payment_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_agent` (`agent_id`),
  KEY `idx_property` (`property_id`),
  KEY `idx_transaction` (`transaction_id`),
  KEY `idx_status` (`status`),
  KEY `idx_date` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
