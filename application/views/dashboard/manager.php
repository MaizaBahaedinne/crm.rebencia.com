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
                                    <?= is_object($agency) ? $agency->post_title : (is_array($agency) ? $agency['name'] : 'Agence') ?>
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
                                        <h4 class="fs-24 fw-semibold ff-secondary mb-0 text-primary counter" data-target="<?= isset($stats['total_agents']) ? $stats['total_agents'] : count($agents ?? []) ?>">0</h4>
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
                                        <h4 class="fs-24 fw-semibold ff-secondary mb-0 text-success counter" data-target="<?= isset($stats['total_properties']) ? $stats['total_properties'] : 0 ?>">0</h4>
                                        <small class="text-info ms-2">
                                            <i class="ri-building-line me-1"></i>Portfolio
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transactions -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100 stats-card" data-color="warning">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-lg flex-shrink-0 me-3">
                                    <span class="avatar-title bg-warning-subtle text-warning rounded-circle fs-20">
                                        <i class="ri-exchange-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-muted text-truncate fs-13 mb-1">Transactions</p>
                                    <div class="d-flex align-items-center">
                                        <h4 class="fs-24 fw-semibold ff-secondary mb-0 text-warning counter" data-target="<?= isset($stats['total_transactions']) ? $stats['total_transactions'] : 0 ?>">0</h4>
                                        <small class="text-success ms-2">
                                            <i class="ri-check-line me-1"></i>Finalisées
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Revenus -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100 stats-card" data-color="info">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-lg flex-shrink-0 me-3">
                                    <span class="avatar-title bg-info-subtle text-info rounded-circle fs-20">
                                        <i class="ri-money-euro-circle-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-muted text-truncate fs-13 mb-1">Revenus agence</p>
                                    <div class="d-flex align-items-center">
                                        <h4 class="fs-18 fw-semibold ff-secondary mb-0 text-info"><?= isset($stats['total_revenue']) ? number_format($stats['total_revenue'], 0, ',', ' ') : '0' ?> TND</h4>
                                        <small class="text-success ms-2">
                                            <i class="ri-trending-up-line me-1"></i>Ce mois
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section Agents de l'agence -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="ri-team-line me-2 text-primary"></i>
                                Agence d'agents
                                <span class="badge bg-primary ms-2"><?= count($agents ?? []) ?> agents</span>
                            </h5>
                            <a href="<?= base_url('agents'); ?>" class="btn btn-outline-primary btn-sm">
                                <i class="ri-eye-line me-1"></i>Voir tous
                            </a>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($agents)): ?>
                                <div class="row g-3">
                                    <?php foreach ($agents as $agent): ?>
                                        <div class="col-md-6 col-lg-4">
                                            <div class="card border h-100">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm flex-shrink-0 me-3">
                                                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                                                <i class="ri-user-line"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1 fw-semibold">
                                                                <?= is_object($agent) ? ($agent->display_name ?? $agent->agent_name ?? 'Agent') : (is_array($agent) ? ($agent['display_name'] ?? $agent['name'] ?? 'Agent') : 'Agent') ?>
                                                            </h6>
                                                            <p class="text-muted mb-0 fs-12">
                                                                <i class="ri-mail-line me-1"></i>
                                                                <?= is_object($agent) ? ($agent->user_email ?? 'Email non défini') : (is_array($agent) ? ($agent['email'] ?? 'Email non défini') : 'Email non défini') ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="mt-3">
                                                        <div class="row text-center">
                                                            <div class="col-4">
                                                                <p class="mb-0 fs-13 text-muted">Propriétés</p>
                                                                <h6 class="mb-0 text-primary">
                                                                    <?= is_object($agent) ? ($agent->properties_count ?? 0) : (is_array($agent) ? ($agent['properties_count'] ?? 0) : 0) ?>
                                                                </h6>
                                                            </div>
                                                            <div class="col-4">
                                                                <p class="mb-0 fs-13 text-muted">Ventes</p>
                                                                <h6 class="mb-0 text-success">
                                                                    <?= is_object($agent) ? ($agent->sales_count ?? 0) : (is_array($agent) ? ($agent['sales_count'] ?? 0) : 0) ?>
                                                                </h6>
                                                            </div>
                                                            <div class="col-4">
                                                                <p class="mb-0 fs-13 text-muted">Contacts</p>
                                                                <h6 class="mb-0 text-info">
                                                                    <?= is_object($agent) ? ($agent->contacts_count ?? 0) : (is_array($agent) ? ($agent['contacts_count'] ?? 0) : 0) ?>
                                                                </h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <div class="avatar-lg mx-auto mb-3">
                                        <div class="avatar-title bg-light text-muted rounded-circle fs-20">
                                            <i class="ri-team-line"></i>
                                        </div>
                                    </div>
                                    <h5 class="mb-2">Aucun agent trouvé</h5>
                                    <p class="text-muted mb-3">Votre agence d'agents apparaîtra ici</p>
                                    <a href="<?= base_url('agents/add'); ?>" class="btn btn-primary">
                                        <i class="ri-user-add-line me-2"></i>Ajouter un agent
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="row">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="avatar-lg mx-auto mb-3">
                                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                    <i class="ri-file-list-3-line"></i>
                                </div>
                            </div>
                            <h5 class="mb-2">Estimations</h5>
                            <p class="text-muted mb-3">Gérer les estimations de votre agence</p>
                            <a href="<?= base_url('estimations'); ?>" class="btn btn-primary">
                                <i class="ri-eye-line me-2"></i>Voir les estimations
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="avatar-lg mx-auto mb-3">
                                <div class="avatar-title bg-success-subtle text-success rounded-circle fs-20">
                                    <i class="ri-target-line"></i>
                                </div>
                            </div>
                            <h5 class="mb-2">Objectifs</h5>
                            <p class="text-muted mb-3">Définir et suivre les objectifs de l'agence</p>
                            <a href="<?= base_url('objectives'); ?>" class="btn btn-success">
                                <i class="ri-settings-4-line me-2"></i>Gérer les objectifs
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="avatar-lg mx-auto mb-3">
                                <div class="avatar-title bg-warning-subtle text-warning rounded-circle fs-20">
                                    <i class="ri-bar-chart-box-line"></i>
                                </div>
                            </div>
                            <h5 class="mb-2">Rapports</h5>
                            <p class="text-muted mb-3">Analyser les performances de l'agence</p>
                            <a href="<?= base_url('reports'); ?>" class="btn btn-warning">
                                <i class="ri-file-chart-line me-2"></i>Voir les rapports
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Scripts pour les compteurs -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation des compteurs
    const counters = document.querySelectorAll('.counter');
    
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-target'));
        const duration = 2000;
        const step = target / (duration / 16);
        let current = 0;
        
        const updateCounter = () => {
            current += step;
            if (current < target) {
                counter.textContent = Math.floor(current);
                requestAnimationFrame(updateCounter);
            } else {
                counter.textContent = target;
            }
        };
        
        updateCounter();
    });
});
</script>
