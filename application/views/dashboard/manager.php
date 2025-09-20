<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- Header Premium Manager -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h4 class="mb-2 text-dark fw-bold">
                                <i class="ri-organization-chart me-2 text-warning"></i>
                                Tableau de bord Manager
                            </h4>
                            <p class="text-muted mb-0">
                                <i class="ri-calendar-line me-1"></i>
                                <?= date('l d F Y'); ?> - Gestion et supervision de votre agence
                            </p>
                            <?php if(isset($agency) && !empty($agency)): ?>
                            <div class="mt-2">
                                <span class="badge bg-warning-subtle text-warning fs-13">
                                    <i class="ri-building-line me-1"></i>
                                    <?= is_object($agency) ? $agency->agency_name : (is_array($agency) ? $agency['name'] : 'Agence') ?>
                                </span>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="page-title-right">
                            <div class="dropdown">
                                <button class="btn btn-warning dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="ri-settings-4-line me-2"></i>Actions Manager
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="<?= base_url('agents'); ?>">
                                        <i class="ri-team-line me-2"></i>Gérer les agents
                                    </a></li>
                                    <li><a class="dropdown-item" href="<?= base_url('objectives'); ?>">
                                        <i class="ri-target-line me-2"></i>Objectifs
                                    </a></li>
                                    <li><a class="dropdown-item" href="<?= base_url('reports'); ?>">
                                        <i class="ri-bar-chart-box-line me-2"></i>Rapports
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="<?= base_url('estimation'); ?>">
                                        <i class="ri-file-add-line me-2"></i>Nouvelle estimation
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cartes de Statistiques Manager -->
            <div class="row mb-4">
                <!-- Total Agents -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100 stats-card" data-color="primary">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-lg flex-shrink-0 me-3">
                                    <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                        <i class="ri-team-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-muted text-truncate fs-13 mb-1">Agents de l'agence</p>
                                    <div class="d-flex align-items-center">
                                        <h4 class="fs-24 fw-semibold ff-secondary mb-0 text-primary counter" data-target="<?= $stats['total_agents'] ?? 0 ?>">0</h4>
                                        <small class="text-success ms-2">
                                            <i class="ri-arrow-up-line me-1"></i>Actifs
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Propriétés de l'agence -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100 stats-card" data-color="success">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-lg flex-shrink-0 me-3">
                                    <span class="avatar-title bg-success-subtle text-success rounded-circle fs-20">
                                        <i class="ri-home-4-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-muted text-truncate fs-13 mb-1">Propriétés gérées</p>
                                    <div class="d-flex align-items-center">
                                        <h4 class="fs-24 fw-semibold ff-secondary mb-0 text-success counter" data-target="<?= $stats['total_properties'] ?? 0 ?>">0</h4>
                                        <small class="text-info ms-2">
                                            <i class="ri-building-line me-1"></i>Portfolio
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Revenus mensuels -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100 stats-card" data-color="warning">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-lg flex-shrink-0 me-3">
                                    <span class="avatar-title bg-warning-subtle text-warning rounded-circle fs-20">
                                        <i class="ri-money-euro-circle-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-muted text-truncate fs-13 mb-1">Revenus du mois</p>
                                    <div class="d-flex align-items-center">
                                        <h4 class="fs-24 fw-semibold ff-secondary mb-0 text-warning"><?= number_format($stats['monthly_revenue'] ?? 0, 0, ',', ' ') ?> €</h4>
                                        <small class="text-success ms-2">
                                            <i class="ri-arrow-up-line me-1"></i>+12%
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tâches en attente -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100 stats-card" data-color="danger">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-lg flex-shrink-0 me-3">
                                    <span class="avatar-title bg-danger-subtle text-danger rounded-circle fs-20">
                                        <i class="ri-task-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-muted text-truncate fs-13 mb-1">Tâches en attente</p>
                                    <div class="d-flex align-items-center">
                                        <h4 class="fs-24 fw-semibold ff-secondary mb-0 text-danger counter" data-target="<?= $stats['pending_tasks'] ?? 0 ?>">0</h4>
                                        <small class="text-warning ms-2">
                                            <i class="ri-time-line me-1"></i>Urgent
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Graphiques et Tableaux -->
            <div class="row">
                <!-- Graphique des ventes mensuelles -->
                <div class="col-xl-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header border-0 bg-white">
                            <div class="d-flex align-items-center">
                                <h5 class="card-title mb-0 fw-semibold">
                                    <i class="ri-line-chart-line me-2 text-primary"></i>
                                    Évolution des ventes
                                </h5>
                                <div class="ms-auto">
                                    <select class="form-select form-select-sm">
                                        <option>6 derniers mois</option>
                                        <option>12 derniers mois</option>
                                        <option>Cette année</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="salesChart" height="300"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Top Performers -->
                <div class="col-xl-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header border-0 bg-white">
                            <h5 class="card-title mb-0 fw-semibold">
                                <i class="ri-trophy-line me-2 text-warning"></i>
                                Meilleurs agents
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($stats['top_performers'])): ?>
                                <?php foreach ($stats['top_performers'] as $index => $agent): ?>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar-sm">
                                            <?php 
                                            $avatar_url = '';
                                            // Vérifier plusieurs propriétés possibles pour l'avatar
                                            if (!empty($agent->avatar_url)) {
                                                $avatar_url = $agent->avatar_url;
                                            } elseif (!empty($agent->wp_avatar)) {
                                                $avatar_url = $agent->wp_avatar;
                                            } elseif (!empty($agent->fave_author_custom_picture)) {
                                                $avatar_url = $agent->fave_author_custom_picture;
                                            }
                                            
                                            // Nettoyer l'URL si nécessaire
                                            if ($avatar_url && !filter_var($avatar_url, FILTER_VALIDATE_URL)) {
                                                // Si c'est un ID d'attachment, construire l'URL WordPress
                                                if (is_numeric($avatar_url)) {
                                                    $avatar_url = ''; // Laisser vide pour utiliser le placeholder
                                                }
                                            }
                                            ?>
                                            
                                            <?php if ($avatar_url): ?>
                                                <img src="<?= htmlspecialchars($avatar_url) ?>" 
                                                     alt="Avatar <?= htmlspecialchars($agent->display_name) ?>" 
                                                     class="avatar-sm rounded-circle"
                                                     style="width: 40px; height: 40px; object-fit: cover;"
                                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                <span class="avatar-sm bg-primary-subtle text-primary rounded-circle d-none align-items-center justify-content-center"
                                                      style="width: 40px; height: 40px; font-size: 14px; font-weight: bold;">
                                                    <?= strtoupper(substr($agent->display_name ?? 'A', 0, 2)) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="avatar-sm bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center"
                                                      style="width: 40px; height: 40px; font-size: 14px; font-weight: bold;">
                                                    <?= strtoupper(substr($agent->display_name ?? 'A', 0, 2)) ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-medium"><?= htmlspecialchars($agent->display_name ?? 'Agent') ?></h6>
                                        <p class="text-muted mb-0 fs-13">
                                            <?= $agent->sales_count ?? 0 ?> ventes • 
                                            <?= number_format($agent->revenue ?? 0, 0, ',', ' ') ?> €
                                        </p>
                                        <?php if (empty($avatar_url)): ?>
                                            <small class="text-warning">
                                                <i class="ri-image-line me-1"></i>Avatar non défini
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <span class="badge bg-<?= $index === 0 ? 'warning' : ($index === 1 ? 'info' : 'success') ?>-subtle text-<?= $index === 0 ? 'warning' : ($index === 1 ? 'info' : 'success') ?>">#<?= $index + 1 ?></span>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted text-center">Aucune donnée disponible</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tableau des agents et Activités récentes -->
            <div class="row mt-4">
                <!-- Tableau des agents -->
                <div class="col-xl-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header border-0 bg-white">
                            <div class="d-flex align-items-center">
                                <h5 class="card-title mb-0 fw-semibold">
                                    <i class="ri-team-line me-2 text-primary"></i>
                                    Équipe de l'agence
                                </h5>
                                <div class="ms-auto">
                                    <a href="<?= base_url('agents') ?>" class="btn btn-outline-primary btn-sm">
                                        <i class="ri-add-line me-1"></i>Gérer les agents
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($agents)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Agent</th>
                                            <th>Email</th>
                                            <th>Rôle</th>
                                            <th>Propriétés</th>
                                            <th>Performance</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($agents as $agent): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-3">
                                                        <?php if (!empty($agent->avatar_url)): ?>
                                                            <img src="<?= $agent->avatar_url ?>" alt="Avatar" class="avatar-xs rounded-circle">
                                                        <?php else: ?>
                                                            <span class="avatar-xs bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center">
                                                                <?= strtoupper(substr($agent->display_name, 0, 2)) ?>
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-medium"><?= htmlspecialchars($agent->display_name) ?></h6>
                                                        <small class="text-muted">
                                                            <?= htmlspecialchars($agent->user_nicename ?? $agent->user_email ?? 'Agent') ?>
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-muted"><?= htmlspecialchars($agent->user_email) ?></span>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= $agent->user_role === 'houzez_manager' ? 'warning' : 'primary' ?>-subtle text-<?= $agent->user_role === 'houzez_manager' ? 'warning' : 'primary' ?>">
                                                    <?= $agent->user_role === 'houzez_manager' ? 'Manager' : 'Agent' ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="fw-medium"><?= $agent->property_count ?? 0 ?></span>
                                            </td>
                                            <td>
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar bg-success" style="width: <?= min(100, ($agent->property_count ?? 0) * 10) ?>%"></div>
                                                </div>
                                                <small class="text-muted"><?= min(100, ($agent->property_count ?? 0) * 10) ?>%</small>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                        Actions
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="#">Voir profil</a></li>
                                                        <li><a class="dropdown-item" href="#">Assigner tâche</a></li>
                                                        <li><a class="dropdown-item" href="#">Voir propriétés</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <div class="avatar-lg mx-auto mb-3">
                                        <span class="avatar-title bg-light text-muted rounded-circle fs-20">
                                            <i class="ri-team-line"></i>
                                        </span>
                                    </div>
                                    <h5 class="text-muted">Aucun agent trouvé</h5>
                                    <p class="text-muted">Aucun agent n'est actuellement assigné à cette agence.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Activités récentes -->
                <div class="col-xl-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header border-0 bg-white">
                            <h5 class="card-title mb-0 fw-semibold">
                                <i class="ri-time-line me-2 text-info"></i>
                                Activités récentes
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($stats['recent_activities'])): ?>
                                <?php foreach ($stats['recent_activities'] as $activity): ?>
                                <div class="d-flex align-items-start mb-3">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar-xs">
                                            <span class="avatar-title bg-info-subtle text-info rounded-circle">
                                                <i class="ri-notification-line fs-12"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-1 fw-medium fs-14"><?= htmlspecialchars($activity['message']) ?></p>
                                        <small class="text-muted">Il y a <?= $activity['time'] ?></small>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted text-center">Aucune activité récente</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Section Transactions : Évolution des ventes et locations + Objectifs -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header border-0 pb-0">
                            <h5 class="card-title mb-0">
                                <i class="ri-exchange-line me-2 text-success"></i>
                                Rubrique Transactions
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Évolution des ventes et locations -->
                            <div class="row mb-4">
                                <div class="col-lg-6">
                                    <div class="card bg-light border-0">
                                        <div class="card-header bg-transparent border-0">
                                            <h6 class="mb-0 text-success">
                                                <i class="ri-home-4-line me-2"></i>
                                                Évolution des Ventes
                                            </h6>
                                        </div>
                                        <div class="card-body pt-2">
                                            <canvas id="salesEvolutionChart" height="200"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="card bg-light border-0">
                                        <div class="card-header bg-transparent border-0">
                                            <h6 class="mb-0 text-info">
                                                <i class="ri-key-2-line me-2"></i>
                                                Évolution des Locations
                                            </h6>
                                        </div>
                                        <div class="card-body pt-2">
                                            <canvas id="rentalsEvolutionChart" height="200"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Affichage des objectifs et pourcentages d'avancement -->
                            <div class="row">
                                <div class="col-12">
                                    <h6 class="mb-3">
                                        <i class="ri-target-line me-2 text-warning"></i>
                                        Objectifs et Progression des Agents
                                    </h6>
                                    <div class="table-responsive">
                                        <table class="table table-striped align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Agent</th>
                                                    <th class="text-center">Estimations</th>
                                                    <th class="text-center">Transactions</th>
                                                    <th class="text-center">Chiffre d'Affaires</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($transactions_data['objectives_progress'])): ?>
                                                    <?php foreach ($transactions_data['objectives_progress'] as $objective): ?>
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-sm rounded-circle bg-light d-flex align-items-center justify-content-center me-3">
                                                                    <span class="text-primary fw-bold">
                                                                        <?= strtoupper(substr($objective['agent_name'], 0, 2)) ?>
                                                                    </span>
                                                                </div>
                                                                <div>
                                                                    <h6 class="mb-0"><?= htmlspecialchars($objective['agent_name']) ?></h6>
                                                                    <small class="text-muted">Agent ID: <?= $objective['agent_id'] ?></small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="mb-2">
                                                                <small class="text-muted">
                                                                    <?= $objective['estimations_actual'] ?> / <?= $objective['estimations_target'] ?>
                                                                </small>
                                                            </div>
                                                            <div class="progress" style="height: 8px;">
                                                                <div class="progress-bar bg-primary" 
                                                                     style="width: <?= min($objective['estimations_progress'], 100) ?>%"
                                                                     title="<?= $objective['estimations_progress'] ?>%">
                                                                </div>
                                                            </div>
                                                            <div class="mt-1">
                                                                <span class="badge <?= $objective['estimations_progress'] >= 100 ? 'bg-success' : 'bg-warning' ?> fs-12">
                                                                    <?= $objective['estimations_progress'] ?>%
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="mb-2">
                                                                <small class="text-muted">
                                                                    <?= $objective['transactions_actual'] ?> / <?= $objective['transactions_target'] ?>
                                                                </small>
                                                            </div>
                                                            <div class="progress" style="height: 8px;">
                                                                <div class="progress-bar bg-success" 
                                                                     style="width: <?= min($objective['transactions_progress'], 100) ?>%"
                                                                     title="<?= $objective['transactions_progress'] ?>%">
                                                                </div>
                                                            </div>
                                                            <div class="mt-1">
                                                                <span class="badge <?= $objective['transactions_progress'] >= 100 ? 'bg-success' : 'bg-warning' ?> fs-12">
                                                                    <?= $objective['transactions_progress'] ?>%
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="mb-2">
                                                                <small class="text-muted">
                                                                    <?= number_format($objective['revenue_actual'], 0, ',', ' ') ?> TND / <?= number_format($objective['revenue_target'], 0, ',', ' ') ?> TND
                                                                </small>
                                                            </div>
                                                            <div class="progress" style="height: 8px;">
                                                                <div class="progress-bar bg-info" 
                                                                     style="width: <?= min($objective['revenue_progress'], 100) ?>%"
                                                                     title="<?= $objective['revenue_progress'] ?>%">
                                                                </div>
                                                            </div>
                                                            <div class="mt-1">
                                                                <span class="badge <?= $objective['revenue_progress'] >= 100 ? 'bg-success' : 'bg-warning' ?> fs-12">
                                                                    <?= $objective['revenue_progress'] ?>%
                                                                </span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="4" class="text-center text-muted py-4">
                                                            <i class="ri-information-line me-2"></i>
                                                            Aucun objectif défini pour ce mois
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
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
    </div>
</div>

<!-- Scripts pour les graphiques -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphique des ventes
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: [<?php 
                if (!empty($chart_data['monthly_sales'])) {
                    echo implode(',', array_map(function($item) { 
                        return '"' . $item['month'] . '"'; 
                    }, $chart_data['monthly_sales']));
                }
            ?>],
            datasets: [{
                label: 'Ventes',
                data: [<?php 
                    if (!empty($chart_data['monthly_sales'])) {
                        echo implode(',', array_map(function($item) { 
                            return $item['sales']; 
                        }, $chart_data['monthly_sales']));
                    }
                ?>],
                borderColor: '#0ab39c',
                backgroundColor: 'rgba(10, 179, 156, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Revenus (€)',
                data: [<?php 
                    if (!empty($chart_data['monthly_sales'])) {
                        echo implode(',', array_map(function($item) { 
                            return $item['revenue'] / 1000; // Diviser par 1000 pour l'affichage
                        }, $chart_data['monthly_sales']));
                    }
                ?>],
                borderColor: '#f7b84b',
                backgroundColor: 'rgba(247, 184, 75, 0.1)',
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
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Nombre de ventes'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Revenus (k€)'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            }
        }
    });

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
    
    // Graphique d'évolution des ventes
    const salesEvolutionCtx = document.getElementById('salesEvolutionChart');
    if (salesEvolutionCtx) {
        new Chart(salesEvolutionCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: [<?php 
                    if (!empty($transactions_data['sales_evolution'])) {
                        echo implode(',', array_map(function($item) { 
                            return '"' . $item['month'] . '"'; 
                        }, $transactions_data['sales_evolution']));
                    }
                ?>],
                datasets: [{
                    label: 'Nombre de ventes',
                    data: [<?php 
                        if (!empty($transactions_data['sales_evolution'])) {
                            echo implode(',', array_map(function($item) { 
                                return $item['count']; 
                            }, $transactions_data['sales_evolution']));
                        }
                    ?>],
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    tension: 0.4,
                    fill: true,
                    yAxisID: 'y'
                }, {
                    label: 'Montant des ventes (TND)',
                    data: [<?php 
                        if (!empty($transactions_data['sales_evolution'])) {
                            echo implode(',', array_map(function($item) { 
                                return $item['amount']; 
                            }, $transactions_data['sales_evolution']));
                        }
                    ?>],
                    borderColor: '#17a2b8',
                    backgroundColor: 'rgba(23, 162, 184, 0.1)',
                    tension: 0.4,
                    fill: false,
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Nombre de ventes'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Montant (TND)'
                        },
                        grid: {
                            drawOnChartArea: false,
                        },
                    }
                }
            }
        });
    }
    
    // Graphique d'évolution des locations
    const rentalsEvolutionCtx = document.getElementById('rentalsEvolutionChart');
    if (rentalsEvolutionCtx) {
        new Chart(rentalsEvolutionCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: [<?php 
                    if (!empty($transactions_data['rentals_evolution'])) {
                        echo implode(',', array_map(function($item) { 
                            return '"' . $item['month'] . '"'; 
                        }, $transactions_data['rentals_evolution']));
                    }
                ?>],
                datasets: [{
                    label: 'Nombre de locations',
                    data: [<?php 
                        if (!empty($transactions_data['rentals_evolution'])) {
                            echo implode(',', array_map(function($item) { 
                                return $item['count']; 
                            }, $transactions_data['rentals_evolution']));
                        }
                    ?>],
                    borderColor: '#6f42c1',
                    backgroundColor: 'rgba(111, 66, 193, 0.1)',
                    tension: 0.4,
                    fill: true,
                    yAxisID: 'y'
                }, {
                    label: 'Montant des locations (TND)',
                    data: [<?php 
                        if (!empty($transactions_data['rentals_evolution'])) {
                            echo implode(',', array_map(function($item) { 
                                return $item['amount']; 
                            }, $transactions_data['rentals_evolution']));
                        }
                    ?>],
                    borderColor: '#20c997',
                    backgroundColor: 'rgba(32, 201, 151, 0.1)',
                    tension: 0.4,
                    fill: false,
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Nombre de locations'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Montant (TND)'
                        },
                        grid: {
                            drawOnChartArea: false,
                        },
                    }
                }
            }
        });
    }
});
</script>

<style>
.stats-card {
    transition: transform 0.2s;
}

.stats-card:hover {
    transform: translateY(-2px);
}

.avatar-xs {
    width: 32px;
    height: 32px;
    font-size: 12px;
}

.avatar-sm {
    width: 40px;
    height: 40px;
    font-size: 14px;
}

.avatar-lg {
    width: 56px;
    height: 56px;
    font-size: 20px;
}

.progress {
    border-radius: 6px;
}

.card {
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.table th {
    font-weight: 600;
    border-bottom: 2px solid #e9ecef;
}
</style>
