-- Table: agent_mail_credentials
-- Stocke les identifiants de messagerie spécifiques à chaque agent (ID WordPress ou interne)
-- ATTENTION : le mot de passe IMAP/SMTP doit être chiffré (réversible) car il faut le redécrypter pour la connexion.
-- Vous pouvez commencer en clair en DEV puis implémenter un chiffrement (openssl_encrypt) avant PROD.

CREATE TABLE IF NOT EXISTS `agent_mail_credentials` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `agent_id` BIGINT UNSIGNED NOT NULL COMMENT 'Référence ID agent (WP users.ID)',
  `email_address` VARCHAR(190) NOT NULL,
  `password_encrypted` TEXT NOT NULL COMMENT 'Mot de passe chiffré (ou provisoirement en clair)',
  `imap_host` VARCHAR(190) DEFAULT NULL,
  `imap_port` SMALLINT UNSIGNED DEFAULT 993,
  `imap_flags` VARCHAR(100) DEFAULT '/imap/ssl',
  `imap_folder` VARCHAR(100) DEFAULT 'INBOX',
  `smtp_host` VARCHAR(190) DEFAULT NULL,
  `smtp_port` SMALLINT UNSIGNED DEFAULT 465,
  `smtp_crypto` VARCHAR(10) DEFAULT 'ssl',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_agent` (`agent_id`),
  KEY `k_email` (`email_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exemple d’insertion (à adapter):
-- INSERT INTO agent_mail_credentials (agent_id, email_address, password_encrypted, imap_host, smtp_host)
-- VALUES (123, 'agent@example.com', 'MOTDEPASSE_A_CHIFFRER', 'imap.mail.ovh.net', 'smtp.mail.ovh.net');
