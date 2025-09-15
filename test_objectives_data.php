<?php
// Fichier de test pour simuler des données d'objectifs
// À utiliser si la base de données n'est pas accessible

// Simulation de données d'objectifs pour septembre 2025
$sample_objectives_data = [
    (object) [
        'id' => 1,
        'agent_id' => 1,
        'agent_name' => 'Ahmed Ben Ali',
        'month' => '2025-09-01',
        'estimations_target' => 25,
        'contacts_target' => 50,
        'transactions_target' => 5,
        'revenue_target' => 150000,
        'estimations_count' => 28,
        'contacts_count' => 55,
        'transactions_count' => 6,
        'revenue_amount' => 175000,
        'commission_earned' => 8750.00,
        'estimations_progress' => 112.0,
        'contacts_progress' => 110.0,
        'transactions_progress' => 120.0,
        'revenue_progress' => 116.7
    ],
    (object) [
        'id' => 2,
        'agent_id' => 2,
        'agent_name' => 'Fatima Gharbi',
        'month' => '2025-09-01',
        'estimations_target' => 20,
        'contacts_target' => 40,
        'transactions_target' => 4,
        'revenue_target' => 120000,
        'estimations_count' => 16,
        'contacts_count' => 32,
        'transactions_count' => 3,
        'revenue_amount' => 95000,
        'commission_earned' => 4750.00,
        'estimations_progress' => 80.0,
        'contacts_progress' => 80.0,
        'transactions_progress' => 75.0,
        'revenue_progress' => 79.2
    ],
    (object) [
        'id' => 3,
        'agent_id' => 3,
        'agent_name' => 'Mohamed Khelifi',
        'month' => '2025-09-01',
        'estimations_target' => 30,
        'contacts_target' => 60,
        'transactions_target' => 6,
        'revenue_target' => 180000,
        'estimations_count' => 32,
        'contacts_count' => 65,
        'transactions_count' => 7,
        'revenue_amount' => 200000,
        'commission_earned' => 10000.00,
        'estimations_progress' => 106.7,
        'contacts_progress' => 108.3,
        'transactions_progress' => 116.7,
        'revenue_progress' => 111.1
    ],
    (object) [
        'id' => 4,
        'agent_id' => 4,
        'agent_name' => 'Amina Sassi',
        'month' => '2025-09-01',
        'estimations_target' => 15,
        'contacts_target' => 35,
        'transactions_target' => 3,
        'revenue_target' => 90000,
        'estimations_count' => 8,
        'contacts_count' => 18,
        'transactions_count' => 1,
        'revenue_amount' => 45000,
        'commission_earned' => 2250.00,
        'estimations_progress' => 53.3,
        'contacts_progress' => 51.4,
        'transactions_progress' => 33.3,
        'revenue_progress' => 50.0
    ],
    (object) [
        'id' => 5,
        'agent_id' => 5,
        'agent_name' => 'Karim Trabelsi',
        'month' => '2025-09-01',
        'estimations_target' => 22,
        'contacts_target' => 45,
        'transactions_target' => 4,
        'revenue_target' => 130000,
        'estimations_count' => 20,
        'contacts_count' => 42,
        'transactions_count' => 4,
        'revenue_amount' => 120000,
        'commission_earned' => 6000.00,
        'estimations_progress' => 90.9,
        'contacts_progress' => 93.3,
        'transactions_progress' => 100.0,
        'revenue_progress' => 92.3
    ]
];

// Pour utiliser ces données dans le contrôleur, vous pouvez les retourner comme ceci :
return $sample_objectives_data;
?>
