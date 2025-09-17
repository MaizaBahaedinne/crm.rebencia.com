-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : mer. 17 sep. 2025 à 19:26
-- Version du serveur : 11.4.8-MariaDB-ubu2404
-- Version de PHP : 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `rebencia_rebencia`
--

-- --------------------------------------------------------

--
-- Structure de la table `agent_commissions`
--

CREATE TABLE `agent_commissions` (
  `id` int(11) NOT NULL,
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `agent_mail_credentials`
--

CREATE TABLE `agent_mail_credentials` (
  `id` int(10) UNSIGNED NOT NULL,
  `agent_id` bigint(20) UNSIGNED NOT NULL COMMENT 'Référence ID agent (WP users.ID)',
  `email_address` varchar(190) NOT NULL,
  `password_encrypted` text NOT NULL COMMENT 'Mot de passe chiffré (ou provisoirement en clair)',
  `imap_host` varchar(190) DEFAULT NULL,
  `imap_port` smallint(5) UNSIGNED DEFAULT 993,
  `imap_flags` varchar(100) DEFAULT '/imap/ssl',
  `imap_folder` varchar(100) DEFAULT 'INBOX',
  `smtp_host` varchar(190) DEFAULT NULL,
  `smtp_port` smallint(5) UNSIGNED DEFAULT 465,
  `smtp_crypto` varchar(10) DEFAULT 'ssl',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `agent_performance`
--

CREATE TABLE `agent_performance` (
  `id` int(11) NOT NULL,
  `agent_id` int(11) NOT NULL,
  `month` date NOT NULL,
  `estimations_count` int(11) NOT NULL DEFAULT 0,
  `contacts_count` int(11) NOT NULL DEFAULT 0,
  `transactions_count` int(11) NOT NULL DEFAULT 0,
  `revenue_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `commission_earned` decimal(10,2) NOT NULL DEFAULT 0.00,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `user_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `commission_settings`
--

CREATE TABLE `commission_settings` (
  `id` int(11) NOT NULL,
  `type` enum('sale','rental') NOT NULL COMMENT 'Type de transaction: vente ou location',
  `agent_rate` decimal(5,2) NOT NULL DEFAULT 5.00 COMMENT 'Pourcentage commission agent',
  `agency_rate` decimal(5,2) NOT NULL DEFAULT 5.00 COMMENT 'Pourcentage commission agence',
  `rental_months` int(2) DEFAULT NULL COMMENT 'Nombre de mois pour location',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `crm_clients`
--

CREATE TABLE `crm_clients` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `type_client` varchar(50) DEFAULT NULL,
  `identite_type` varchar(50) DEFAULT NULL,
  `identite_numero` varchar(100) DEFAULT NULL,
  `identite_doc` varchar(255) DEFAULT NULL,
  `contact_principal` varchar(100) DEFAULT NULL,
  `contact_secondaire` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `adresse` text DEFAULT NULL,
  `ville` varchar(100) DEFAULT NULL,
  `code_postal` varchar(20) DEFAULT NULL,
  `pays` varchar(100) DEFAULT NULL,
  `source` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `agency_id` int(10) UNSIGNED DEFAULT NULL,
  `agent_id` int(10) UNSIGNED DEFAULT NULL,
  `date_creation` datetime DEFAULT current_timestamp(),
  `date_modification` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `crm_properties`
--

CREATE TABLE `crm_properties` (
  `id` int(10) UNSIGNED NOT NULL,
  `zone_id` int(10) UNSIGNED DEFAULT NULL,
  `type_bien` varchar(50) DEFAULT NULL,
  `energie_classe` varchar(3) DEFAULT NULL,
  `surface_habitable` decimal(10,2) DEFAULT NULL,
  `surface_terrain` decimal(10,2) DEFAULT NULL,
  `nb_pieces` int(11) DEFAULT NULL,
  `etage` int(11) DEFAULT NULL,
  `ascenseur` tinyint(1) DEFAULT NULL,
  `piscine` tinyint(1) DEFAULT NULL,
  `securite` tinyint(1) DEFAULT NULL,
  `syndic` tinyint(1) DEFAULT NULL,
  `jardin` tinyint(1) DEFAULT NULL,
  `cave` tinyint(1) DEFAULT NULL,
  `cheminee` tinyint(1) DEFAULT NULL,
  `meuble` tinyint(1) DEFAULT NULL,
  `sdb_type` varchar(20) DEFAULT NULL,
  `sol_type` varchar(20) DEFAULT NULL,
  `portail_auto` tinyint(1) DEFAULT NULL,
  `gardien` tinyint(1) DEFAULT NULL,
  `videosurveillance` tinyint(1) DEFAULT NULL,
  `interphone` tinyint(1) DEFAULT NULL,
  `alarme` tinyint(1) DEFAULT NULL,
  `fibre` tinyint(1) DEFAULT NULL,
  `lave_linge` tinyint(1) DEFAULT NULL,
  `seche_linge` tinyint(1) DEFAULT NULL,
  `chauffe_eau` varchar(20) DEFAULT NULL,
  `gaz_type` varchar(20) DEFAULT NULL,
  `proximite_transports_score` tinyint(3) UNSIGNED DEFAULT NULL,
  `proximite_commodites_score` tinyint(3) UNSIGNED DEFAULT NULL,
  `proximite_ecoles_score` tinyint(3) UNSIGNED DEFAULT NULL,
  `proximite_sante_score` tinyint(3) UNSIGNED DEFAULT NULL,
  `proximite_commerces_score` tinyint(3) UNSIGNED DEFAULT NULL,
  `proximite_espaces_verts_score` tinyint(3) UNSIGNED DEFAULT NULL,
  `proximite_plage_score` tinyint(3) UNSIGNED DEFAULT NULL,
  `orientation` varchar(30) DEFAULT NULL,
  `etat_general` varchar(20) DEFAULT NULL,
  `type_exterieur` varchar(20) DEFAULT NULL,
  `parking` varchar(5) DEFAULT NULL,
  `annee_construction` int(11) DEFAULT NULL,
  `equipements` text DEFAULT NULL,
  `titre_foncier` varchar(5) DEFAULT NULL,
  `type_propriete` varchar(100) DEFAULT NULL,
  `charges` decimal(10,2) DEFAULT NULL,
  `taxes` decimal(10,2) DEFAULT NULL,
  `prix_demande` decimal(14,2) DEFAULT NULL,
  `proposition_agence` decimal(14,2) DEFAULT NULL,
  `proposition_commentaire` text DEFAULT NULL,
  `latitude` decimal(10,6) DEFAULT NULL,
  `longitude` decimal(10,6) DEFAULT NULL,
  `adresse_numero` varchar(50) DEFAULT NULL,
  `adresse_rue` varchar(255) DEFAULT NULL,
  `adresse_ville` varchar(100) DEFAULT NULL,
  `adresse_cp` varchar(20) DEFAULT NULL,
  `adresse_pays` varchar(100) DEFAULT NULL,
  `objectif` varchar(20) NOT NULL DEFAULT 'vente',
  `agent_id` int(11) NOT NULL,
  `valeur_estimee` decimal(14,2) DEFAULT NULL,
  `valeur_min_estimee` decimal(14,2) DEFAULT NULL,
  `valeur_max_estimee` decimal(14,2) DEFAULT NULL,
  `loyer_potentiel` decimal(12,2) DEFAULT NULL,
  `rentabilite` decimal(6,2) DEFAULT NULL,
  `coef_global` decimal(8,4) DEFAULT NULL,
  `statut_dossier` varchar(30) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `crm_property_photos`
--

CREATE TABLE `crm_property_photos` (
  `id` int(10) UNSIGNED NOT NULL,
  `property_id` int(10) UNSIGNED NOT NULL,
  `file` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `crm_transactions`
--

CREATE TABLE `crm_transactions` (
  `id` int(10) UNSIGNED NOT NULL,
  `property_id` int(10) UNSIGNED DEFAULT NULL,
  `titre` varchar(200) NOT NULL,
  `type` enum('vente','location') NOT NULL DEFAULT 'vente',
  `commercial` varchar(150) DEFAULT NULL,
  `montant` decimal(14,2) DEFAULT NULL,
  `statut` enum('nouveau','actif','cloture','annule') NOT NULL DEFAULT 'nouveau',
  `date_cloture` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `agent_id` int(11) NOT NULL,
  `lead_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `crm_zones`
--

CREATE TABLE `crm_zones` (
  `id` int(10) UNSIGNED NOT NULL,
  `nom` varchar(150) NOT NULL,
  `prix_m2_min` decimal(12,2) DEFAULT NULL,
  `prix_m2_max` decimal(12,2) DEFAULT NULL,
  `prix_m2_moyen` decimal(12,2) NOT NULL DEFAULT 0.00,
  `rendement_locatif_moyen` decimal(5,2) NOT NULL DEFAULT 0.00,
  `transport_score` tinyint(3) UNSIGNED DEFAULT NULL,
  `commodites_score` tinyint(3) UNSIGNED DEFAULT NULL,
  `securite_score` tinyint(3) UNSIGNED DEFAULT NULL,
  `transport_description` text DEFAULT NULL,
  `commodites_description` text DEFAULT NULL,
  `latitude` decimal(10,6) DEFAULT NULL,
  `longitude` decimal(10,6) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `geometry` text DEFAULT NULL COMMENT 'Coordonnées GeoJSON ou tableau de points (polygone)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `version` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `monthly_objectives`
--

CREATE TABLE `monthly_objectives` (
  `id` int(11) NOT NULL,
  `agent_id` int(11) NOT NULL,
  `month` date NOT NULL COMMENT 'Premier jour du mois cible',
  `estimations_target` int(11) NOT NULL DEFAULT 0 COMMENT 'Objectif nombre estimations',
  `contacts_target` int(11) NOT NULL DEFAULT 0 COMMENT 'Objectif nombre contacts',
  `transactions_target` int(11) NOT NULL DEFAULT 0 COMMENT 'Objectif nombre transactions',
  `revenue_target` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Objectif CA en euros',
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_access_matrix`
--

CREATE TABLE `tbl_access_matrix` (
  `id` int(11) NOT NULL,
  `access` text DEFAULT NULL,
  `roleId` int(11) NOT NULL,
  `isDeleted` tinyint(4) NOT NULL DEFAULT 0,
  `createdBy` int(11) NOT NULL,
  `createdDtm` datetime NOT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `updatedDtm` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_booking`
--

CREATE TABLE `tbl_booking` (
  `bookingId` int(4) NOT NULL,
  `roomName` varchar(256) NOT NULL,
  `description` varchar(1024) DEFAULT NULL,
  `isDeleted` tinyint(4) NOT NULL DEFAULT 0,
  `createdBy` int(11) NOT NULL,
  `createdDtm` datetime NOT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `updatedDtm` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_last_login`
--

CREATE TABLE `tbl_last_login` (
  `id` bigint(20) NOT NULL,
  `userId` bigint(20) NOT NULL,
  `sessionData` varchar(2048) NOT NULL,
  `machineIp` varchar(1024) NOT NULL,
  `userAgent` varchar(128) NOT NULL,
  `agentString` varchar(1024) NOT NULL,
  `platform` varchar(128) NOT NULL,
  `createdDtm` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_reset_password`
--

CREATE TABLE `tbl_reset_password` (
  `id` bigint(20) NOT NULL,
  `email` varchar(128) NOT NULL,
  `activation_id` varchar(32) NOT NULL,
  `agent` varchar(512) NOT NULL,
  `client_ip` varchar(32) NOT NULL,
  `isDeleted` tinyint(4) NOT NULL DEFAULT 0,
  `createdBy` bigint(20) NOT NULL DEFAULT 1,
  `createdDtm` datetime NOT NULL,
  `updatedBy` bigint(20) DEFAULT NULL,
  `updatedDtm` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_roles`
--

CREATE TABLE `tbl_roles` (
  `roleId` tinyint(4) NOT NULL COMMENT 'role id',
  `role` varchar(50) NOT NULL COMMENT 'role text',
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `isDeleted` tinyint(4) NOT NULL DEFAULT 0,
  `createdBy` int(11) NOT NULL,
  `createdDtm` datetime NOT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `updatedDtm` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_task`
--

CREATE TABLE `tbl_task` (
  `taskId` int(4) NOT NULL,
  `taskTitle` varchar(256) NOT NULL,
  `description` varchar(1024) DEFAULT NULL,
  `isDeleted` tinyint(4) NOT NULL DEFAULT 0,
  `createdBy` int(11) NOT NULL,
  `createdDtm` datetime NOT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `updatedDtm` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `userId` int(11) NOT NULL,
  `email` varchar(128) NOT NULL COMMENT 'login email',
  `password` varchar(128) NOT NULL COMMENT 'hashed login password',
  `name` varchar(128) DEFAULT NULL COMMENT 'full name of user',
  `mobile` varchar(20) DEFAULT NULL,
  `roleId` tinyint(4) NOT NULL,
  `isAdmin` tinyint(4) NOT NULL DEFAULT 2,
  `isDeleted` tinyint(4) NOT NULL DEFAULT 0,
  `createdBy` int(11) NOT NULL,
  `createdDtm` datetime NOT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `updatedDtm` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `agent_commissions`
--
ALTER TABLE `agent_commissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_agent` (`agent_id`),
  ADD KEY `idx_property` (`property_id`),
  ADD KEY `idx_transaction` (`transaction_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_date` (`created_at`);

--
-- Index pour la table `agent_mail_credentials`
--
ALTER TABLE `agent_mail_credentials`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_agent` (`agent_id`),
  ADD KEY `k_email` (`email_address`);

--
-- Index pour la table `agent_performance`
--
ALTER TABLE `agent_performance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_agent_month_perf` (`agent_id`,`month`),
  ADD KEY `idx_month_perf` (`month`),
  ADD KEY `idx_agent_perf` (`agent_id`),
  ADD KEY `idx_agent_month_perf` (`agent_id`,`month`);

--
-- Index pour la table `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `last_activity_idx` (`last_activity`);

--
-- Index pour la table `commission_settings`
--
ALTER TABLE `commission_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_type` (`type`);

--
-- Index pour la table `crm_clients`
--
ALTER TABLE `crm_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_agency_id` (`agency_id`),
  ADD KEY `idx_agent_id` (`agent_id`);

--
-- Index pour la table `crm_properties`
--
ALTER TABLE `crm_properties`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_zone` (`zone_id`),
  ADD KEY `idx_prop_type_bien` (`type_bien`),
  ADD KEY `idx_prop_energie` (`energie_classe`),
  ADD KEY `idx_prop_statut` (`statut_dossier`),
  ADD KEY `idx_prop_zone_created` (`zone_id`,`created_at`);

--
-- Index pour la table `crm_property_photos`
--
ALTER TABLE `crm_property_photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_prop` (`property_id`);

--
-- Index pour la table `crm_transactions`
--
ALTER TABLE `crm_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_property` (`property_id`),
  ADD KEY `idx_type` (`type`),
  ADD KEY `idx_statut` (`statut`),
  ADD KEY `idx_date` (`date_cloture`),
  ADD KEY `idx_lead` (`lead_id`);

--
-- Index pour la table `crm_zones`
--
ALTER TABLE `crm_zones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_nom` (`nom`);

--
-- Index pour la table `monthly_objectives`
--
ALTER TABLE `monthly_objectives`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_agent_month` (`agent_id`,`month`),
  ADD KEY `idx_month` (`month`),
  ADD KEY `idx_agent` (`agent_id`);

--
-- Index pour la table `tbl_access_matrix`
--
ALTER TABLE `tbl_access_matrix`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_booking`
--
ALTER TABLE `tbl_booking`
  ADD PRIMARY KEY (`bookingId`);

--
-- Index pour la table `tbl_last_login`
--
ALTER TABLE `tbl_last_login`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_reset_password`
--
ALTER TABLE `tbl_reset_password`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_roles`
--
ALTER TABLE `tbl_roles`
  ADD PRIMARY KEY (`roleId`);

--
-- Index pour la table `tbl_task`
--
ALTER TABLE `tbl_task`
  ADD PRIMARY KEY (`taskId`);

--
-- Index pour la table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`userId`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `agent_commissions`
--
ALTER TABLE `agent_commissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `agent_mail_credentials`
--
ALTER TABLE `agent_mail_credentials`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `agent_performance`
--
ALTER TABLE `agent_performance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `commission_settings`
--
ALTER TABLE `commission_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `crm_clients`
--
ALTER TABLE `crm_clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `crm_properties`
--
ALTER TABLE `crm_properties`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `crm_property_photos`
--
ALTER TABLE `crm_property_photos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `crm_transactions`
--
ALTER TABLE `crm_transactions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `crm_zones`
--
ALTER TABLE `crm_zones`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `monthly_objectives`
--
ALTER TABLE `monthly_objectives`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tbl_access_matrix`
--
ALTER TABLE `tbl_access_matrix`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tbl_booking`
--
ALTER TABLE `tbl_booking`
  MODIFY `bookingId` int(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tbl_last_login`
--
ALTER TABLE `tbl_last_login`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tbl_reset_password`
--
ALTER TABLE `tbl_reset_password`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tbl_roles`
--
ALTER TABLE `tbl_roles`
  MODIFY `roleId` tinyint(4) NOT NULL AUTO_INCREMENT COMMENT 'role id';

--
-- AUTO_INCREMENT pour la table `tbl_task`
--
ALTER TABLE `tbl_task`
  MODIFY `taskId` int(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `crm_properties`
--
ALTER TABLE `crm_properties`
  ADD CONSTRAINT `fk_prop_zone` FOREIGN KEY (`zone_id`) REFERENCES `crm_zones` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `crm_property_photos`
--
ALTER TABLE `crm_property_photos`
  ADD CONSTRAINT `fk_photo_property` FOREIGN KEY (`property_id`) REFERENCES `crm_properties` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
