-- ===================================================================
-- INITIALISATION DES MODULES COMMISSIONS ET OBJECTIFS
-- Base de données: rebencia_rebencia
-- Date: 15 septembre 2025
-- ===================================================================

-- Table pour les paramètres de commission
CREATE TABLE IF NOT EXISTS commission_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type ENUM('sale', 'rental') NOT NULL,
    agency_rate DECIMAL(5,2) DEFAULT 5.00,
    agent_rate DECIMAL(5,2) DEFAULT 5.00,
    total_commission_rate DECIMAL(5,2) DEFAULT 10.00,
    rental_months INT DEFAULT 1,
    rental_deposit_months INT DEFAULT 2,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insérer les paramètres par défaut
INSERT INTO commission_settings (type, agency_rate, agent_rate, total_commission_rate, rental_months, rental_deposit_months) 
VALUES 
('sale', 5.00, 5.00, 10.00, 0, 0),
('rental', 10.00, 0.00, 10.00, 1, 2)
ON DUPLICATE KEY UPDATE updated_at = CURRENT_TIMESTAMP;

-- Table pour les objectifs mensuels
CREATE TABLE IF NOT EXISTS monthly_objectives (
    id INT AUTO_INCREMENT PRIMARY KEY,
    agent_id INT NOT NULL,
    month DATE NOT NULL,
    estimations_target INT DEFAULT 0,
    contacts_target INT DEFAULT 0,
    transactions_target INT DEFAULT 0,
    revenue_target DECIMAL(15,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_agent_month (agent_id, month)
);

-- Table pour suivre les performances des agents
CREATE TABLE IF NOT EXISTS agent_performance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    agent_id INT NOT NULL,
    month DATE NOT NULL,
    estimations_count INT DEFAULT 0,
    contacts_count INT DEFAULT 0,
    transactions_count INT DEFAULT 0,
    revenue_amount DECIMAL(15,2) DEFAULT 0.00,
    commission_earned DECIMAL(15,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_agent_month_perf (agent_id, month)
);

-- Table pour les commissions des agents
CREATE TABLE IF NOT EXISTS agent_commissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    agent_id INT NOT NULL,
    property_id INT,
    transaction_type ENUM('sale', 'rental') NOT NULL,
    property_price DECIMAL(15,2) NOT NULL,
    commission_rate DECIMAL(5,2) NOT NULL,
    commission_amount DECIMAL(15,2) NOT NULL,
    agency_share DECIMAL(15,2) NOT NULL,
    agent_share DECIMAL(15,2) NOT NULL,
    transaction_date DATE NOT NULL,
    status ENUM('pending', 'paid', 'cancelled') DEFAULT 'pending',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table simple pour les agents (si wp_users n'est pas disponible)
CREATE TABLE IF NOT EXISTS crm_agents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    agent_code VARCHAR(50) UNIQUE,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    email VARCHAR(255) UNIQUE,
    phone VARCHAR(20),
    is_active BOOLEAN DEFAULT TRUE,
    hire_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insérer quelques agents d'exemple
INSERT INTO crm_agents (agent_code, first_name, last_name, email, phone, hire_date) 
VALUES 
('AGT001', 'Jean', 'Dupont', 'j.dupont@rebencia.com', '0123456789', '2024-01-15'),
('AGT002', 'Marie', 'Martin', 'm.martin@rebencia.com', '0123456790', '2024-02-01'),
('AGT003', 'Pierre', 'Bernard', 'p.bernard@rebencia.com', '0123456791', '2024-03-10')
ON DUPLICATE KEY UPDATE updated_at = CURRENT_TIMESTAMP;

-- Insérer des objectifs d'exemple pour le mois courant
INSERT INTO monthly_objectives (agent_id, month, estimations_target, contacts_target, transactions_target, revenue_target)
VALUES 
(1, '2025-09-01', 20, 50, 5, 500000.00),
(2, '2025-09-01', 15, 40, 4, 400000.00),
(3, '2025-09-01', 18, 45, 3, 350000.00)
ON DUPLICATE KEY UPDATE updated_at = CURRENT_TIMESTAMP;

-- Insérer des performances d'exemple
INSERT INTO agent_performance (agent_id, month, estimations_count, contacts_count, transactions_count, revenue_amount, commission_earned)
VALUES 
(1, '2025-09-01', 12, 35, 2, 250000.00, 25000.00),
(2, '2025-09-01', 8, 28, 3, 300000.00, 30000.00),
(3, '2025-09-01', 15, 42, 1, 180000.00, 18000.00)
ON DUPLICATE KEY UPDATE updated_at = CURRENT_TIMESTAMP;

-- Index pour optimiser les performances
CREATE INDEX idx_agent_month ON monthly_objectives(agent_id, month);
CREATE INDEX idx_agent_month_perf ON agent_performance(agent_id, month);
CREATE INDEX idx_agent_transaction ON agent_commissions(agent_id, transaction_date);
CREATE INDEX idx_commission_status ON agent_commissions(status);

-- Vue pour faciliter les requêtes d'objectifs avec noms d'agents
CREATE OR REPLACE VIEW v_objectives_dashboard AS
SELECT 
    mo.*,
    COALESCE(
        CONCAT(ca.first_name, ' ', ca.last_name),
        CONCAT('Agent #', mo.agent_id)
    ) as agent_name,
    ca.agent_code,
    ca.email,
    ap.estimations_count,
    ap.contacts_count,
    ap.transactions_count,
    ap.revenue_amount,
    ap.commission_earned,
    CASE 
        WHEN mo.estimations_target > 0 THEN ROUND((ap.estimations_count / mo.estimations_target) * 100, 2)
        ELSE 0 
    END as estimations_progress,
    CASE 
        WHEN mo.contacts_target > 0 THEN ROUND((ap.contacts_count / mo.contacts_target) * 100, 2)
        ELSE 0 
    END as contacts_progress,
    CASE 
        WHEN mo.transactions_target > 0 THEN ROUND((ap.transactions_count / mo.transactions_target) * 100, 2)
        ELSE 0 
    END as transactions_progress,
    CASE 
        WHEN mo.revenue_target > 0 THEN ROUND((ap.revenue_amount / mo.revenue_target) * 100, 2)
        ELSE 0 
    END as revenue_progress
FROM monthly_objectives mo
LEFT JOIN crm_agents ca ON ca.id = mo.agent_id
LEFT JOIN agent_performance ap ON ap.agent_id = mo.agent_id AND ap.month = mo.month;

COMMIT;
