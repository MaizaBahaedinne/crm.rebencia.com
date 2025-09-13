<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- Page Title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-sm-0 fw-bold">Dashboard Admin</h4>
                            <p class="text-muted mb-0">Vue d'ensemble de votre CRM immobilier</p>
                        </div>
                        <div class="page-title-right">
                            <div class="dropdown">
                                <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="ri-calendar-line me-2"></i><?php echo date('M Y'); ?>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" onclick="updatePeriod('month')">Ce mois</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="updatePeriod('quarter')">Ce trimestre</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="updatePeriod('year')">Cette année</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row">
                <!-- Agences -->
                <div class="col-xl-3 col-md-6">
                    <div class="card stats-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon bg-primary-subtle text-primary">
                                    <i class="ri-building-line"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="stats-number" data-target="<?php echo $stats['agencies']; ?>">0</div>
                                    <p class="stats-label">Agences</p>
                                </div>
                            </div>
                            <div class="mt-3">
                                <span class="badge bg-success-subtle text-success">
                                    <i class="ri-arrow-up-line"></i> +12%
                                </span>
                                <span class="text-muted ms-2">vs mois dernier</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Agents -->
                <div class="col-xl-3 col-md-6">
                    <div class="card stats-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon bg-success-subtle text-success">
                                    <i class="ri-user-3-line"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="stats-number" data-target="<?php echo $stats['agents']; ?>">0</div>
                                    <p class="stats-label">Agents</p>
                                </div>
                            </div>
                            <div class="mt-3">
                                <span class="badge bg-success-subtle text-success">
                                    <i class="ri-arrow-up-line"></i> +8%
                                </span>
                                <span class="text-muted ms-2">vs mois dernier</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Propriétés -->
                <div class="col-xl-3 col-md-6">
                    <div class="card stats-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon bg-warning-subtle text-warning">
                                    <i class="ri-home-4-line"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="stats-number" data-target="<?php echo $stats['properties']; ?>">0</div>
                                    <p class="stats-label">Propriétés</p>
                                </div>
                            </div>
                            <div class="mt-3">
                                <span class="badge bg-success-subtle text-success">
                                    <i class="ri-arrow-up-line"></i> <?php echo $stats['growth']; ?>%
                                </span>
                                <span class="text-muted ms-2">vs mois dernier</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Revenus -->
                <div class="col-xl-3 col-md-6">
                    <div class="card stats-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon bg-info-subtle text-info">
                                    <i class="ri-money-euro-circle-line"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="stats-number">€<?php echo number_format($stats['revenue'], 0, ',', ' '); ?></div>
                                    <p class="stats-label">Revenus</p>
                                </div>
                            </div>
                            <div class="mt-3">
                                <span class="badge bg-success-subtle text-success">
                                    <i class="ri-arrow-up-line"></i> +15%
                                </span>
                                <span class="text-muted ms-2">vs mois dernier</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row">
                <!-- Ventes Mensuelles -->
                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-header border-0 align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Évolution des Ventes</h4>
                            <div class="dropdown">
                                <button class="btn btn-soft-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    12 derniers mois
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">6 derniers mois</a></li>
                                    <li><a class="dropdown-item" href="#">12 derniers mois</a></li>
                                    <li><a class="dropdown-item" href="#">24 derniers mois</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="salesChart" height="300"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Répartition des Propriétés -->
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-header border-0">
                            <h4 class="card-title mb-0">Types de Propriétés</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="propertyTypeChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Performance & Activities Row -->
            <div class="row">
                <!-- Top Agents -->
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header border-0 align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Top Agents</h4>
                            <a href="<?php echo base_url('agents'); ?>" class="btn btn-sm btn-soft-primary">
                                Voir tous <i class="ri-arrow-right-line ms-1"></i>
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Agent</th>
                                            <th>Propriétés</th>
                                            <th>Performance</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($top_agents)): ?>
                                            <?php foreach ($top_agents as $agent): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm me-3">
                                                            <img src="<?php echo $agent->agent_avatar ?: base_url('assets/images/users/avatar-1.jpg'); ?>" 
                                                                 class="img-fluid rounded-circle" alt="Avatar">
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-1"><?php echo htmlspecialchars($agent->agent_name); ?></h6>
                                                            <p class="text-muted mb-0 fs-12"><?php echo htmlspecialchars($agent->agent_email ?? ''); ?></p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><span class="badge bg-primary-subtle text-primary"><?php echo $agent->properties_count ?? 0; ?></span></td>
                                                <td>
                                                    <div class="progress progress-sm">
                                                        <div class="progress-bar bg-success" style="width: <?php echo min(100, ($agent->properties_count ?? 0) * 10); ?>%"></div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-<?php echo ($agent->is_active ?? 1) ? 'success' : 'danger'; ?>">
                                                        <?php echo ($agent->is_active ?? 1) ? 'Actif' : 'Inactif'; ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">Aucun agent trouvé</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Activités Récentes -->
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header border-0 align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Activités Récentes</h4>
                            <div class="dropdown">
                                <button class="btn btn-soft-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Aujourd'hui
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Aujourd'hui</a></li>
                                    <li><a class="dropdown-item" href="#">Cette semaine</a></li>
                                    <li><a class="dropdown-item" href="#">Ce mois</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="activity-feed">
                                <?php if (!empty($recent_activities)): ?>
                                    <?php foreach ($recent_activities as $activity): ?>
                                    <div class="d-flex activity-item">
                                        <div class="flex-shrink-0">
                                            <div class="activity-icon bg-<?php echo $activity['post_type'] === 'property' ? 'warning' : ($activity['post_type'] === 'houzez_agent' ? 'success' : 'primary'); ?>-subtle text-<?php echo $activity['post_type'] === 'property' ? 'warning' : ($activity['post_type'] === 'houzez_agent' ? 'success' : 'primary'); ?>">
                                                <i class="ri-<?php echo $activity['post_type'] === 'property' ? 'home' : ($activity['post_type'] === 'houzez_agent' ? 'user' : 'building'); ?>-line"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-1"><?php echo htmlspecialchars($activity['post_title']); ?></h6>
                                            <p class="text-muted mb-1">
                                                <?php 
                                                $type_labels = [
                                                    'property' => 'Nouvelle propriété ajoutée',
                                                    'houzez_agent' => 'Nouvel agent inscrit',
                                                    'houzez_agency' => 'Nouvelle agence créée'
                                                ];
                                                echo $type_labels[$activity['post_type']] ?? 'Activité'; 
                                                ?>
                                            </p>
                                            <small class="text-muted"><?php echo date('d/m/Y H:i', strtotime($activity['post_date'])); ?></small>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="text-center text-muted py-3">
                                        <i class="ri-time-line fs-1"></i>
                                        <p class="mt-2">Aucune activité récente</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header border-0">
                            <h4 class="card-title mb-0">Actions Rapides</h4>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-xl-3 col-md-6">
                                    <a href="<?php echo base_url('agents'); ?>" class="quick-action-card">
                                        <div class="d-flex align-items-center">
                                            <div class="quick-action-icon bg-primary-subtle text-primary">
                                                <i class="ri-user-add-line"></i>
                                            </div>
                                            <div class="ms-3">
                                                <h6 class="mb-1">Gérer les Agents</h6>
                                                <p class="text-muted mb-0">Ajouter ou modifier</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-xl-3 col-md-6">
                                    <a href="<?php echo base_url('agencies'); ?>" class="quick-action-card">
                                        <div class="d-flex align-items-center">
                                            <div class="quick-action-icon bg-success-subtle text-success">
                                                <i class="ri-building-2-line"></i>
                                            </div>
                                            <div class="ms-3">
                                                <h6 class="mb-1">Gérer les Agences</h6>
                                                <p class="text-muted mb-0">Configuration</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-xl-3 col-md-6">
                                    <a href="<?php echo base_url('properties'); ?>" class="quick-action-card">
                                        <div class="d-flex align-items-center">
                                            <div class="quick-action-icon bg-warning-subtle text-warning">
                                                <i class="ri-home-4-line"></i>
                                            </div>
                                            <div class="ms-3">
                                                <h6 class="mb-1">Propriétés</h6>
                                                <p class="text-muted mb-0">Catalogue complet</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-xl-3 col-md-6">
                                    <a href="<?php echo base_url('reports'); ?>" class="quick-action-card">
                                        <div class="d-flex align-items-center">
                                            <div class="quick-action-icon bg-info-subtle text-info">
                                                <i class="ri-bar-chart-line"></i>
                                            </div>
                                            <div class="ms-3">
                                                <h6 class="mb-1">Rapports</h6>
                                                <p class="text-muted mb-0">Analytics avancés</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Styles CSS modernes -->
<style>
.stats-card {
    border: none;
    box-shadow: 0 0.125rem 0.375rem rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    border-radius: 12px;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1);
}

.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.stats-number {
    font-size: 2rem;
    font-weight: 700;
    line-height: 1;
    margin-bottom: 5px;
}

.stats-label {
    color: #8A92B2;
    font-size: 0.875rem;
    margin-bottom: 0;
    font-weight: 500;
}

.activity-feed .activity-item {
    padding: 15px 0;
    border-bottom: 1px solid #f0f0f0;
}

.activity-feed .activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.quick-action-card {
    display: block;
    padding: 20px;
    background: #fff;
    border: 2px solid #f0f0f0;
    border-radius: 12px;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
}

.quick-action-card:hover {
    border-color: #3577F1;
    color: inherit;
    text-decoration: none;
    transform: translateY(-2px);
}

.quick-action-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.progress-sm {
    height: 6px;
}

.avatar-sm {
    width: 40px;
    height: 40px;
}

.card {
    border-radius: 12px;
}

.card-header {
    background: transparent;
    padding: 1.5rem 1.5rem 0;
}

.card-body {
    padding: 1.5rem;
}
</style>

<!-- Scripts pour les graphiques -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation des compteurs
    document.querySelectorAll('.stats-number[data-target]').forEach(function(element) {
        const target = parseInt(element.getAttribute('data-target'));
        const increment = target / 100;
        let current = 0;
        
        const timer = setInterval(function() {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            element.textContent = Math.floor(current);
        }, 20);
    });

    // Graphique des ventes mensuelles
    const salesData = <?php echo json_encode($chart_data['monthly_sales']); ?>;
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: salesData.map(item => item.month),
            datasets: [{
                label: 'Ventes',
                data: salesData.map(item => item.sales),
                borderColor: '#3577F1',
                backgroundColor: 'rgba(53, 119, 241, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        borderDash: [5, 5]
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Graphique des types de propriétés
    const propertyData = <?php echo json_encode($chart_data['properties_by_type']); ?>;
    const propertyCtx = document.getElementById('propertyTypeChart').getContext('2d');
    new Chart(propertyCtx, {
        type: 'doughnut',
        data: {
            labels: propertyData.map(item => item.property_type || 'Non défini'),
            datasets: [{
                data: propertyData.map(item => item.count),
                backgroundColor: [
                    '#3577F1',
                    '#0AB39C',
                    '#F7B500',
                    '#F06548',
                    '#8A92B2'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                }
            }
        }
    });
});

function updatePeriod(period) {
    // Fonction pour mettre à jour la période d'affichage
    console.log('Période sélectionnée:', period);
    // Ici vous pouvez ajouter la logique pour recharger les données
}
</script>
