-- Insertion de données d'exemple pour les objectifs mensuels
-- Ces données sont pour le mois de septembre 2025

-- Supprimer les données existantes pour septembre 2025
DELETE FROM monthly_objectives WHERE month = '2025-09-01';
DELETE FROM agent_performance WHERE month = '2025-09-01';

-- Insérer des objectifs pour différents agents (en supposant qu'ils existent dans WordPress)
-- Agent ID 1 - Ahmed Ben Ali
INSERT INTO monthly_objectives (agent_id, month, estimations_target, contacts_target, transactions_target, revenue_target, created_by, created_at, updated_at) VALUES
(1, '2025-09-01', 25, 50, 5, 150000.00, 1, NOW(), NOW());

-- Agent ID 2 - Fatima Gharbi
INSERT INTO monthly_objectives (agent_id, month, estimations_target, contacts_target, transactions_target, revenue_target, created_by, created_at, updated_at) VALUES
(2, '2025-09-01', 20, 40, 4, 120000.00, 1, NOW(), NOW());

-- Agent ID 3 - Mohamed Khelifi
INSERT INTO monthly_objectives (agent_id, month, estimations_target, contacts_target, transactions_target, revenue_target, created_by, created_at, updated_at) VALUES
(3, '2025-09-01', 30, 60, 6, 180000.00, 1, NOW(), NOW());

-- Agent ID 4 - Amina Sassi
INSERT INTO monthly_objectives (agent_id, month, estimations_target, contacts_target, transactions_target, revenue_target, created_by, created_at, updated_at) VALUES
(4, '2025-09-01', 15, 35, 3, 90000.00, 1, NOW(), NOW());

-- Agent ID 5 - Karim Trabelsi
INSERT INTO monthly_objectives (agent_id, month, estimations_target, contacts_target, transactions_target, revenue_target, created_by, created_at, updated_at) VALUES
(5, '2025-09-01', 22, 45, 4, 130000.00, 1, NOW(), NOW());

-- Insérer les performances réalisées (avec des progressions variables)
-- Agent ID 1 - Ahmed Ben Ali (Performance élevée)
INSERT INTO agent_performance (agent_id, month, estimations_count, contacts_count, transactions_count, revenue_amount, commission_earned, updated_at) VALUES
(1, '2025-09-01', 28, 55, 6, 175000.00, 8750.00, NOW());

-- Agent ID 2 - Fatima Gharbi (Performance moyenne)
INSERT INTO agent_performance (agent_id, month, estimations_count, contacts_count, transactions_count, revenue_amount, commission_earned, updated_at) VALUES
(2, '2025-09-01', 16, 32, 3, 95000.00, 4750.00, NOW());

-- Agent ID 3 - Mohamed Khelifi (Performance excellente)
INSERT INTO agent_performance (agent_id, month, estimations_count, contacts_count, transactions_count, revenue_amount, commission_earned, updated_at) VALUES
(3, '2025-09-01', 32, 65, 7, 200000.00, 10000.00, NOW());

-- Agent ID 4 - Amina Sassi (Performance faible)
INSERT INTO agent_performance (agent_id, month, estimations_count, contacts_count, transactions_count, revenue_amount, commission_earned, updated_at) VALUES
(4, '2025-09-01', 8, 18, 1, 45000.00, 2250.00, NOW());

-- Agent ID 5 - Karim Trabelsi (Performance correcte)
INSERT INTO agent_performance (agent_id, month, estimations_count, contacts_count, transactions_count, revenue_amount, commission_earned, updated_at) VALUES
(5, '2025-09-01', 20, 42, 4, 120000.00, 6000.00, NOW());

-- Ajout de données pour octobre 2025 également
-- Objectifs pour octobre 2025
INSERT INTO monthly_objectives (agent_id, month, estimations_target, contacts_target, transactions_target, revenue_target, created_by, created_at, updated_at) VALUES
(1, '2025-10-01', 30, 55, 6, 170000.00, 1, NOW(), NOW()),
(2, '2025-10-01', 25, 45, 5, 140000.00, 1, NOW(), NOW()),
(3, '2025-10-01', 35, 65, 7, 200000.00, 1, NOW(), NOW()),
(4, '2025-10-01', 18, 40, 4, 110000.00, 1, NOW(), NOW()),
(5, '2025-10-01', 28, 50, 5, 150000.00, 1, NOW(), NOW());

-- Performances pour octobre 2025 (en cours de mois)
INSERT INTO agent_performance (agent_id, month, estimations_count, contacts_count, transactions_count, revenue_amount, commission_earned, updated_at) VALUES
(1, '2025-10-01', 15, 28, 2, 85000.00, 4250.00, NOW()),
(2, '2025-10-01', 12, 22, 2, 70000.00, 3500.00, NOW()),
(3, '2025-10-01', 18, 32, 3, 105000.00, 5250.00, NOW()),
(4, '2025-10-01', 6, 15, 1, 35000.00, 1750.00, NOW()),
(5, '2025-10-01', 14, 25, 2, 75000.00, 3750.00, NOW());
