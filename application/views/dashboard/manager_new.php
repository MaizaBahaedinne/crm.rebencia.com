<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- Header Premium Manager avec Navigation Rapide -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm bg-gradient bg-primary">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h3 class="text-white mb-2 fw-bold">
                                        <i class="ri-organization-chart me-2"></i>
                                        Tableau de bord Manager
                                    </h3>
                                    <p class="text-white-50 mb-0">
                                        <i class="ri-calendar-line me-1"></i>
                                        <?= date('l d F Y'); ?> - Pilotage et supervision de votre agence
                                    </p>
                                    <?php if(isset($agency) && !empty($agency)): ?>
                                    <div class="mt-2">
                                        <span class="badge bg-white text-primary fs-13">
                                            <i class="ri-building-line me-1"></i>
                                            <?= is_object($agency) ? $agency->agency_name : (is_array($agency) ? $agency['name'] : 'Agence') ?>
                                        </span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-4 text-end">
                                    <!-- Navigation Rapide -->
                                    <div class="btn-group" role="group">
                                        <a href="<?= base_url('objectives/agency'); ?>" class="btn btn-outline-light btn-sm">
                                            <i class="ri-target-line me-1"></i>Objectifs
                                        </a>
                                        <a href="<?= base_url('agents'); ?>" class="btn btn-outline-light btn-sm">
                                            <i class="ri-team-line me-1"></i>Équipe
                                        </a>
                                        <a href="<?= base_url('reports/manager'); ?>" class="btn btn-outline-light btn-sm">
                                            <i class="ri-bar-chart-box-line me-1"></i>Rapports
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 1: Indicateurs Clés de Performance (KPI) -->
            <div class="row mb-4">
                <div class="col-12">
                    <h5 class="mb-3 fw-semibold text-dark">
                        <i class="ri-dashboard-3-line me-2 text-primary"></i>
                        Indicateurs Clés de Performance
                    </h5>
                </div>
                
                <!-- KPI Équipe -->
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="card border-0 shadow-sm kpi-card" data-color="primary">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar-lg flex-shrink-0 me-3">
                                    <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                        <i class="ri-team-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-muted text-truncate fs-12 mb-1">Agents Actifs</p>
                                    <h3 class="fs-28 fw-bold mb-0 text-primary counter" data-target="<?= $stats['total_agents'] ?? 0 ?>">0</h3>
                                    <small class="text-success">
                                        <i class="ri-arrow-up-line me-1"></i>100% Opérationnels
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KPI Revenus -->
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="card border-0 shadow-sm kpi-card" data-color="success">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar-lg flex-shrink-0 me-3">
                                    <span class="avatar-title bg-success-subtle text-success rounded-circle fs-20">
                                        <i class="ri-money-euro-circle-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-muted text-truncate fs-12 mb-1">CA Mensuel</p>
                                    <h3 class="fs-28 fw-bold mb-0 text-success"><?= number_format($stats['monthly_revenue'] ?? 0, 0, ',', ' ') ?> €</h3>
                                    <small class="text-success">
                                        <i class="ri-arrow-up-line me-1"></i>+12% vs mois dernier
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KPI Propriétés -->
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="card border-0 shadow-sm kpi-card" data-color="info">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar-lg flex-shrink-0 me-3">
                                    <span class="avatar-title bg-info-subtle text-info rounded-circle fs-20">
                                        <i class="ri-home-4-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-muted text-truncate fs-12 mb-1">Portfolio</p>
                                    <h3 class="fs-28 fw-bold mb-0 text-info counter" data-target="<?= $stats['total_properties'] ?? 0 ?>">0</h3>
                                    <small class="text-info">
                                        <i class="ri-building-line me-1"></i><?= $stats['active_listings'] ?? 0 ?> actives
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KPI Performance -->
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="card border-0 shadow-sm kpi-card" data-color="warning">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar-lg flex-shrink-0 me-3">
                                    <span class="avatar-title bg-warning-subtle text-warning rounded-circle fs-20">
                                        <i class="ri-trophy-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-muted text-truncate fs-12 mb-1">Obj. Atteints</p>
                                    <h3 class="fs-28 fw-bold mb-0 text-warning">85%</h3>
                                    <small class="text-warning">
                                        <i class="ri-target-line me-1"></i>Bon niveau
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Supervision & Pilotage -->
            <div class="row mb-4">
                <div class="col-12">
                    <h5 class="mb-3 fw-semibold text-dark">
                        <i class="ri-eye-line me-2 text-primary"></i>
                        Supervision & Pilotage
                    </h5>
                </div>

                <!-- Évolution des Transactions -->
                <div class="col-xl-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header border-0 bg-white">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="card-title mb-0 fw-semibold">
                                    <i class="ri-line-chart-line me-2 text-primary"></i>
                                    Évolution des Transactions
                                </h6>
                                <div class="btn-group btn-group-sm" role="group">
                                    <input type="radio" class="btn-check" name="chartPeriod" id="chart3m" checked>
                                    <label class="btn btn-outline-primary" for="chart3m">3M</label>
                                    <input type="radio" class="btn-check" name="chartPeriod" id="chart6m">
                                    <label class="btn btn-outline-primary" for="chart6m">6M</label>
                                    <input type="radio" class="btn-check" name="chartPeriod" id="chart12m">
                                    <label class="btn btn-outline-primary" for="chart12m">1A</label>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row text-center mb-3">
                                <div class="col-4">
                                    <h4 class="text-primary mb-1"><?= isset($transactions_data['sales_evolution']) ? count($transactions_data['sales_evolution']) : 0 ?></h4>
                                    <p class="text-muted mb-0 fs-13">Ventes</p>
                                </div>
                                <div class="col-4">
                                    <h4 class="text-success mb-1"><?= isset($transactions_data['rentals_evolution']) ? count($transactions_data['rentals_evolution']) : 0 ?></h4>
                                    <p class="text-muted mb-0 fs-13">Locations</p>
                                </div>
                                <div class="col-4">
                                    <h4 class="text-warning mb-1"><?= number_format(($stats['monthly_revenue'] ?? 0) / 1000, 0) ?>K €</h4>
                                    <p class="text-muted mb-0 fs-13">Volume</p>
                                </div>
                            </div>
                            <canvas id="transactionsChart" height="280"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Actions Rapides -->
                <div class="col-xl-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header border-0 bg-white">
                            <h6 class="card-title mb-0 fw-semibold">
                                <i class="ri-flash-line me-2 text-warning"></i>
                                Actions Rapides
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="<?= base_url('objectives/agency'); ?>" class="btn btn-outline-primary">
                                    <i class="ri-target-line me-2"></i>Définir Objectifs
                                </a>
                                <a href="<?= base_url('agents/performance'); ?>" class="btn btn-outline-success">
                                    <i class="ri-trophy-line me-2"></i>Évaluer Performance
                                </a>
                                <a href="<?= base_url('estimation'); ?>" class="btn btn-outline-info">
                                    <i class="ri-file-add-line me-2"></i>Nouvelle Estimation
                                </a>
                                <a href="<?= base_url('clients/add'); ?>" class="btn btn-outline-secondary">
                                    <i class="ri-user-add-line me-2"></i>Nouveau Client
                                </a>
                            </div>
                            
                            <hr class="my-3">
                            
                            <!-- Alertes Rapides -->
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <i class="ri-alarm-warning-line me-2"></i>
                                <strong><?= $stats['pending_tasks'] ?? 0 ?></strong> tâches en attente
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Équipe & Performance -->
            <div class="row mb-4">
                <div class="col-12">
                    <h5 class="mb-3 fw-semibold text-dark">
                        <i class="ri-team-line me-2 text-primary"></i>
                        Équipe & Performance
                    </h5>
                </div>

                <!-- Top Performers -->
                <div class="col-xl-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header border-0 bg-white">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="card-title mb-0 fw-semibold">
                                    <i class="ri-trophy-line me-2 text-warning"></i>
                                    Top Performers
                                </h6>
                                <a href="<?= base_url('agents/performance'); ?>" class="btn btn-sm btn-outline-warning">
                                    Détails
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($stats['top_performers'])): ?>
                                <?php foreach (array_slice($stats['top_performers'], 0, 5) as $index => $agent): ?>
                                <div class="d-flex align-items-center mb-3 position-relative">
                                    <div class="position-absolute top-0 start-0">
                                        <span class="badge bg-<?= $index === 0 ? 'warning' : ($index === 1 ? 'info' : 'success') ?>-subtle text-<?= $index === 0 ? 'warning' : ($index === 1 ? 'info' : 'success') ?> fs-10">
                                            #<?= $index + 1 ?>
                                        </span>
                                    </div>
                                    <div class="flex-shrink-0 me-3 ms-3">
                                        <?php 
                                        $avatar_url = $agent->avatar_url ?? $agent->wp_avatar ?? '';
                                        ?>
                                        <?php if ($avatar_url): ?>
                                            <img src="<?= htmlspecialchars($avatar_url) ?>" 
                                                 alt="Avatar" 
                                                 class="avatar-sm rounded-circle"
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                        <?php else: ?>
                                            <span class="avatar-sm bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center">
                                                <?= strtoupper(substr($agent->display_name ?? 'A', 0, 2)) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-medium"><?= htmlspecialchars($agent->display_name ?? 'Agent') ?></h6>
                                        <div class="d-flex justify-content-between">
                                            <small class="text-muted"><?= $agent->sales_count ?? 0 ?> ventes</small>
                                            <small class="text-success fw-medium"><?= number_format($agent->revenue ?? 0, 0, ',', ' ') ?> €</small>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center text-muted py-4">
                                    <i class="ri-team-line fs-48 text-muted mb-3"></i>
                                    <p>Aucune donnée de performance disponible</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Équipe Complète -->
                <div class="col-xl-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header border-0 bg-white">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="card-title mb-0 fw-semibold">
                                    <i class="ri-group-line me-2 text-primary"></i>
                                    Équipe de l'Agence
                                </h6>
                                <a href="<?= base_url('agents'); ?>" class="btn btn-sm btn-primary">
                                    <i class="ri-settings-3-line me-1"></i>Gérer l'équipe
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($agents)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="border-0">Agent</th>
                                            <th class="border-0">Contact</th>
                                            <th class="border-0">Rôle</th>
                                            <th class="border-0">Propriétés</th>
                                            <th class="border-0">Performance</th>
                                            <th class="border-0">Statut</th>
                                            <th class="border-0">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach (array_slice($agents, 0, 6) as $agent): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-2">
                                                        <?php if (!empty($agent->avatar_url)): ?>
                                                            <img src="<?= $agent->avatar_url ?>" alt="Avatar" class="avatar-xs rounded-circle">
                                                        <?php else: ?>
                                                            <span class="avatar-xs bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center">
                                                                <?= strtoupper(substr($agent->display_name ?? 'A', 0, 2)) ?>
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-medium"><?= htmlspecialchars($agent->display_name ?? 'Agent') ?></h6>
                                                        <small class="text-muted"><?= htmlspecialchars($agent->job_title ?? 'Agent immobilier') ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <small class="d-block"><?= htmlspecialchars($agent->user_email ?? 'N/A') ?></small>
                                                    <small class="text-muted"><?= htmlspecialchars($agent->fave_author_mobile ?? 'N/A') ?></small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info-subtle text-info">
                                                    <?= ucfirst($agent->role ?? 'agent') ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="fw-medium"><?= $agent->properties_count ?? 0 ?></span>
                                                <small class="text-muted d-block">propriétés</small>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1 me-2">
                                                        <div class="progress progress-sm">
                                                            <div class="progress-bar bg-success" style="width: <?= min(100, ($agent->performance_score ?? 0)) ?>%"></div>
                                                        </div>
                                                    </div>
                                                    <small class="text-success fw-medium"><?= $agent->performance_score ?? 0 ?>%</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-success-subtle text-success">
                                                    <i class="ri-checkbox-circle-line me-1"></i>Actif
                                                </span>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                        <i class="ri-more-line"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="<?= base_url('agents/view/' . ($agent->ID ?? '')) ?>">
                                                            <i class="ri-eye-line me-2"></i>Voir profil
                                                        </a></li>
                                                        <li><a class="dropdown-item" href="<?= base_url('objectives/agent/' . ($agent->ID ?? '')) ?>">
                                                            <i class="ri-target-line me-2"></i>Objectifs
                                                        </a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <?php if (count($agents) > 6): ?>
                            <div class="text-center mt-3">
                                <a href="<?= base_url('agents'); ?>" class="btn btn-outline-primary">
                                    Voir tous les agents (<?= count($agents) ?>)
                                </a>
                            </div>
                            <?php endif; ?>
                            
                            <?php else: ?>
                                <div class="text-center text-muted py-4">
                                    <i class="ri-team-line fs-48 text-muted mb-3"></i>
                                    <p>Aucun agent trouvé dans cette agence</p>
                                    <a href="<?= base_url('agents/add'); ?>" class="btn btn-primary">
                                        <i class="ri-user-add-line me-1"></i>Ajouter un agent
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 4: Activités & Alertes -->
            <div class="row">
                <div class="col-12">
                    <h5 class="mb-3 fw-semibold text-dark">
                        <i class="ri-notification-3-line me-2 text-primary"></i>
                        Activités Récentes & Alertes
                    </h5>
                </div>

                <!-- Activités Récentes -->
                <div class="col-xl-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header border-0 bg-white">
                            <h6 class="card-title mb-0 fw-semibold">
                                <i class="ri-time-line me-2 text-info"></i>
                                Dernières Activités
                            </h6>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($stats['recent_activities'])): ?>
                                <div class="timeline">
                                    <?php foreach (array_slice($stats['recent_activities'], 0, 5) as $activity): ?>
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-primary"></div>
                                        <div class="timeline-content">
                                            <h6 class="timeline-title"><?= htmlspecialchars($activity->title ?? 'Activité') ?></h6>
                                            <p class="timeline-text text-muted"><?= htmlspecialchars($activity->description ?? '') ?></p>
                                            <small class="text-muted">
                                                <i class="ri-time-line me-1"></i>
                                                <?= date('d/m/Y H:i', strtotime($activity->created_at ?? 'now')) ?>
                                            </small>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center text-muted py-4">
                                    <i class="ri-history-line fs-48 text-muted mb-3"></i>
                                    <p>Aucune activité récente</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Alertes & Notifications -->
                <div class="col-xl-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header border-0 bg-white">
                            <h6 class="card-title mb-0 fw-semibold">
                                <i class="ri-alarm-warning-line me-2 text-warning"></i>
                                Alertes & Notifications
                            </h6>
                        </div>
                        <div class="card-body">
                            <!-- Alerte Tâches -->
                            <?php if (($stats['pending_tasks'] ?? 0) > 0): ?>
                            <div class="alert alert-warning d-flex align-items-center" role="alert">
                                <i class="ri-task-line me-2"></i>
                                <div>
                                    <strong><?= $stats['pending_tasks'] ?></strong> tâches en attente
                                    <br><small>Nécessitent votre attention</small>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- Alerte Objectifs -->
                            <div class="alert alert-info d-flex align-items-center" role="alert">
                                <i class="ri-target-line me-2"></i>
                                <div>
                                    Objectifs mensuels à <strong>85%</strong>
                                    <br><small>Bon progress, continuez !</small>
                                </div>
                            </div>

                            <!-- Alerte Nouveaux Leads -->
                            <div class="alert alert-success d-flex align-items-center" role="alert">
                                <i class="ri-user-add-line me-2"></i>
                                <div>
                                    <strong>3</strong> nouveaux leads cette semaine
                                    <br><small>À répartir entre les agents</small>
                                </div>
                            </div>

                            <!-- Actions Recommandées -->
                            <div class="mt-3">
                                <h6 class="fw-semibold mb-2">Actions Recommandées</h6>
                                <div class="list-group list-group-flush">
                                    <a href="<?= base_url('objectives/review'); ?>" class="list-group-item list-group-item-action py-2">
                                        <i class="ri-target-line me-2 text-primary"></i>
                                        Réviser les objectifs du trimestre
                                    </a>
                                    <a href="<?= base_url('agents/training'); ?>" class="list-group-item list-group-item-action py-2">
                                        <i class="ri-graduation-cap-line me-2 text-success"></i>
                                        Planifier formation équipe
                                    </a>
                                    <a href="<?= base_url('reports/weekly'); ?>" class="list-group-item list-group-item-action py-2">
                                        <i class="ri-file-chart-line me-2 text-info"></i>
                                        Générer rapport hebdomadaire
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

<!-- Scripts pour les graphiques et animations -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation des compteurs
    const counters = document.querySelectorAll('.counter');
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-target'));
        const increment = target / 100;
        let current = 0;
        
        const updateCounter = () => {
            if (current < target) {
                current += increment;
                counter.textContent = Math.ceil(current);
                setTimeout(updateCounter, 20);
            } else {
                counter.textContent = target;
            }
        };
        updateCounter();
    });

    // Graphique des transactions (exemple avec Chart.js)
    if (document.getElementById('transactionsChart')) {
        const ctx = document.getElementById('transactionsChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun'],
                datasets: [{
                    label: 'Ventes',
                    data: [12, 19, 3, 5, 2, 3],
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }, {
                    label: 'Locations',
                    data: [8, 11, 7, 9, 6, 8],
                    borderColor: 'rgb(255, 99, 132)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
});
</script>

<style>
.kpi-card {
    transition: transform 0.2s ease-in-out;
}

.kpi-card:hover {
    transform: translateY(-2px);
}

.timeline {
    position: relative;
    padding-left: 20px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -25px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    top: 5px;
}

.timeline-content {
    padding-left: 15px;
}

.timeline-title {
    font-size: 14px;
    margin-bottom: 5px;
}

.timeline-text {
    font-size: 13px;
    margin-bottom: 5px;
}

.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>
