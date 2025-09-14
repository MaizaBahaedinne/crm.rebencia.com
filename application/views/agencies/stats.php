<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- Page Title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-sm-0 fw-bold">Statistiques Agences</h4>
                            <p class="text-muted mb-0">Analyse des performances des agences immobilières</p>
                        </div>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="<?php echo base_url('agencies'); ?>">Agences</a></li>
                                <li class="breadcrumb-item active">Statistiques</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Overview -->
            <div class="row g-4 mb-4">
                <!-- Total Agents -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm rounded-circle bg-primary-subtle">
                                        <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                            <i class="ri-user-3-line fs-20"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="fw-semibold mb-1"><?php echo $stats['total_agents'] ?? 0; ?></h5>
                                    <p class="text-muted mb-0">Agents Total</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="badge bg-success-subtle text-success">
                                        <i class="ri-arrow-up-line"></i> +5%
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Properties -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm rounded-circle bg-success-subtle">
                                        <span class="avatar-title rounded-circle bg-success-subtle text-success">
                                            <i class="ri-home-4-line fs-20"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="fw-semibold mb-1"><?php echo $stats['total_properties'] ?? 0; ?></h5>
                                    <p class="text-muted mb-0">Propriétés Gérées</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="badge bg-success-subtle text-success">
                                        <i class="ri-arrow-up-line"></i> +12%
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Properties -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm rounded-circle bg-info-subtle">
                                        <span class="avatar-title rounded-circle bg-info-subtle text-info">
                                            <i class="ri-checkbox-circle-line fs-20"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="fw-semibold mb-1"><?php echo $stats['active_properties'] ?? 0; ?></h5>
                                    <p class="text-muted mb-0">Biens Actifs</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="badge bg-info-subtle text-info">
                                        <?php echo $stats['completion_rate'] ?? 0; ?>% Taux
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monthly Revenue -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm rounded-circle bg-warning-subtle">
                                        <span class="avatar-title rounded-circle bg-warning-subtle text-warning">
                                            <i class="ri-money-euro-circle-line fs-20"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="fw-semibold mb-1">€<?php echo number_format($stats['monthly_revenue'] ?? 0, 0, ',', ' '); ?></h5>
                                    <p class="text-muted mb-0">Revenus Mensuels</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="badge bg-warning-subtle text-warning">
                                        <?php echo $stats['growth_rate'] ?? '0%'; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Performance Charts -->
            <div class="row g-4 mb-4">
                <!-- Performance Trends -->
                <div class="col-xl-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <h5 class="card-title mb-0">Tendances de Performance</h5>
                                <div class="ms-auto">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary active">6M</button>
                                        <button type="button" class="btn btn-sm btn-outline-primary">3M</button>
                                        <button type="button" class="btn btn-sm btn-outline-primary">1M</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="performanceChart" height="300"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Agency Distribution -->
                <div class="col-xl-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Répartition par Région</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="regionChart" height="300"></canvas>
                        </div>
                        <div class="card-footer">
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="border-end">
                                        <h6 class="mb-0">Paris</h6>
                                        <p class="text-muted mb-0">35%</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h6 class="mb-0">Province</h6>
                                    <p class="text-muted mb-0">65%</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Agencies Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <h5 class="card-title mb-0">Top Agences Performers</h5>
                                <div class="ms-auto">
                                    <a href="<?php echo base_url('agencies'); ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="ri-external-link-line me-1"></i>Voir Toutes
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Rang</th>
                                            <th>Agence</th>
                                            <th>Agents</th>
                                            <th>Propriétés</th>
                                            <th>Revenus</th>
                                            <th>Performance</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <span class="badge bg-warning fs-12">🥇</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm rounded-circle bg-primary-subtle me-2">
                                                        <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                                            <i class="ri-building-line"></i>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">Agence Premium Paris</h6>
                                                        <small class="text-muted">Secteur Centre</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><span class="fw-semibold">12</span></td>
                                            <td><span class="fw-semibold">45</span></td>
                                            <td><span class="fw-semibold text-success">€85K</span></td>
                                            <td>
                                                <div class="progress progress-sm">
                                                    <div class="progress-bar bg-success" style="width: 92%"></div>
                                                </div>
                                                <small class="text-muted">92%</small>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                        Actions
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#">
                                                            <i class="ri-eye-line me-2"></i>Voir Détails
                                                        </a>
                                                        <a class="dropdown-item" href="#">
                                                            <i class="ri-file-download-line me-2"></i>Rapport
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="badge bg-secondary fs-12">🥈</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm rounded-circle bg-success-subtle me-2">
                                                        <span class="avatar-title rounded-circle bg-success-subtle text-success">
                                                            <i class="ri-building-line"></i>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">Rebencia Immobilier</h6>
                                                        <small class="text-muted">Multi-secteurs</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><span class="fw-semibold">8</span></td>
                                            <td><span class="fw-semibold">38</span></td>
                                            <td><span class="fw-semibold text-success">€72K</span></td>
                                            <td>
                                                <div class="progress progress-sm">
                                                    <div class="progress-bar bg-success" style="width: 88%"></div>
                                                </div>
                                                <small class="text-muted">88%</small>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                        Actions
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#">
                                                            <i class="ri-eye-line me-2"></i>Voir Détails
                                                        </a>
                                                        <a class="dropdown-item" href="#">
                                                            <i class="ri-file-download-line me-2"></i>Rapport
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="badge bg-warning fs-12" style="color: #cd7f32 !important;">🥉</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm rounded-circle bg-info-subtle me-2">
                                                        <span class="avatar-title rounded-circle bg-info-subtle text-info">
                                                            <i class="ri-building-line"></i>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">Agence du Marché</h6>
                                                        <small class="text-muted">Résidentiel</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><span class="fw-semibold">6</span></td>
                                            <td><span class="fw-semibold">28</span></td>
                                            <td><span class="fw-semibold text-success">€58K</span></td>
                                            <td>
                                                <div class="progress progress-sm">
                                                    <div class="progress-bar bg-warning" style="width: 75%"></div>
                                                </div>
                                                <small class="text-muted">75%</small>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                        Actions
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#">
                                                            <i class="ri-eye-line me-2"></i>Voir Détails
                                                        </a>
                                                        <a class="dropdown-item" href="#">
                                                            <i class="ri-file-download-line me-2"></i>Rapport
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Chart.js for graphs -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Performance Trends Chart
    const performanceCtx = document.getElementById('performanceChart');
    if (performanceCtx) {
        new Chart(performanceCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun'],
                datasets: [{
                    label: 'Revenus Agences',
                    data: [45000, 52000, 48000, 61000, 58000, 72000],
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'Propriétés Ajoutées',
                    data: [20, 25, 22, 28, 26, 32],
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        ticks: {
                            callback: function(value) {
                                return '€' + (value / 1000) + 'K';
                            }
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false,
                        },
                    }
                }
            }
        });
    }

    // Region Distribution Chart
    const regionCtx = document.getElementById('regionChart');
    if (regionCtx) {
        new Chart(regionCtx, {
            type: 'doughnut',
            data: {
                labels: ['Paris', 'Lyon', 'Marseille', 'Toulouse', 'Autres'],
                datasets: [{
                    data: [35, 20, 15, 12, 18],
                    backgroundColor: [
                        '#6366f1',
                        '#10b981',
                        '#f59e0b',
                        '#ef4444',
                        '#8b5cf6'
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
                    }
                }
            }
        });
    }
});
</script>
