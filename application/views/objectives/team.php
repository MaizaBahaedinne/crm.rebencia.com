<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- Titre de la page -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Objectifs par Équipe</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="<?php echo base_url('objectives'); ?>">Objectifs</a></li>
                                <li class="breadcrumb-item active">Équipe</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sélecteur de mois et actions -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <label for="month_selector" class="form-label me-3 mb-0">
                                            <i class="ri-calendar-line me-1"></i>Période :
                                        </label>
                                        <input type="month" 
                                               id="month_selector" 
                                               class="form-control" 
                                               style="width: auto;"
                                               value="<?php echo isset($current_month) ? $current_month : date('Y-m'); ?>"
                                               onchange="changeMonth(this.value)">
                                    </div>
                                </div>
                                <div class="col-md-6 text-end">
                                    <div class="btn-group">
                                        <button type="button" id="refresh_team_data" class="btn btn-success">
                                            <i class="ri-refresh-line me-2"></i>Actualiser Données
                                        </button>
                                        <a href="<?php echo base_url('objectives'); ?>" class="btn btn-outline-primary">
                                            <i class="ri-dashboard-line me-2"></i>Vue Individuelle
                                        </a>
                                        <a href="<?php echo base_url('objectives/bulk_set'); ?>" class="btn btn-primary">
                                            <i class="ri-settings-line me-2"></i>Définir Objectifs
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques globales de l'équipe -->
            <?php if (isset($team_stats) && $team_stats): ?>
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-3">
                                        <i class="ri-team-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">Agents Actifs</p>
                                    <h4 class="mb-0"><?php echo $team_stats->total_agents ?? 0; ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-success-subtle text-success rounded-circle fs-3">
                                        <i class="ri-contacts-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">Total Contacts</p>
                                    <h4 class="mb-0"><?php echo number_format($team_stats->total_contacts ?? 0); ?></h4>
                                    <p class="text-muted mb-0">
                                        <small>Objectif: <?php echo number_format($team_stats->total_contacts_target ?? 0); ?></small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-warning-subtle text-warning rounded-circle fs-3">
                                        <i class="ri-exchange-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">Transactions</p>
                                    <h4 class="mb-0"><?php echo number_format($team_stats->total_transactions ?? 0); ?></h4>
                                    <p class="text-muted mb-0">
                                        <small>Objectif: <?php echo number_format($team_stats->total_transactions_target ?? 0); ?></small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-info-subtle text-info rounded-circle fs-3">
                                        <i class="ri-money-dollar-circle-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">CA Total</p>
                                    <h4 class="mb-0"><?php echo number_format($team_stats->total_revenue ?? 0, 0, ',', ' '); ?> TND</h4>
                                    <p class="text-muted mb-0">
                                        <small>Objectif: <?php echo number_format($team_stats->total_revenue_target ?? 0, 0, ',', ' '); ?> TND</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Tableau des objectifs par agent -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h4 class="card-title mb-0">
                                        <i class="ri-target-line me-2"></i>Performance par Agent
                                    </h4>
                                </div>
                                <div class="col-auto">
                                    <div class="btn-group">
                                        <button class="btn btn-outline-success btn-sm" onclick="exportTeamData()">
                                            <i class="ri-download-line me-1"></i>Exporter
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (isset($team_objectives) && !empty($team_objectives)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 200px;">Agent</th>
                                            <th class="text-center">Estimations</th>
                                            <th class="text-center">Contacts</th>
                                            <th class="text-center">Transactions</th>
                                            <th class="text-center">Chiffre d'Affaires</th>
                                            <th class="text-center">Commissions</th>
                                            <th class="text-center">Performance Globale</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($team_objectives as $objective): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-xs me-3">
                                                        <span class="avatar-title rounded-circle bg-primary text-white">
                                                            <?php echo strtoupper(substr($objective->agent_name, 0, 2)); ?>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0"><?php echo htmlspecialchars($objective->agent_name); ?></h6>
                                                        <small class="text-muted">ID: <?php echo $objective->agent_id; ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <!-- Estimations -->
                                            <td class="text-center">
                                                <div class="mb-1">
                                                    <small class="text-muted"><?php echo $objective->estimations_count; ?> / <?php echo $objective->estimations_target; ?></small>
                                                </div>
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar bg-primary" 
                                                         style="width: <?php echo min($objective->estimations_progress, 100); ?>%"
                                                         title="<?php echo $objective->estimations_progress; ?>%">
                                                    </div>
                                                </div>
                                                <small class="text-<?php echo $objective->estimations_progress >= 100 ? 'success' : ($objective->estimations_progress >= 75 ? 'warning' : 'danger'); ?>">
                                                    <?php echo number_format($objective->estimations_progress, 1); ?>%
                                                </small>
                                            </td>
                                            
                                            <!-- Contacts -->
                                            <td class="text-center">
                                                <div class="mb-1">
                                                    <small class="text-muted"><?php echo $objective->contacts_count; ?> / <?php echo $objective->contacts_target; ?></small>
                                                </div>
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar bg-success" 
                                                         style="width: <?php echo min($objective->contacts_progress, 100); ?>%"
                                                         title="<?php echo $objective->contacts_progress; ?>%">
                                                    </div>
                                                </div>
                                                <small class="text-<?php echo $objective->contacts_progress >= 100 ? 'success' : ($objective->contacts_progress >= 75 ? 'warning' : 'danger'); ?>">
                                                    <?php echo number_format($objective->contacts_progress, 1); ?>%
                                                </small>
                                            </td>
                                            
                                            <!-- Transactions -->
                                            <td class="text-center">
                                                <div class="mb-1">
                                                    <small class="text-muted"><?php echo $objective->transactions_count; ?> / <?php echo $objective->transactions_target; ?></small>
                                                </div>
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar bg-warning" 
                                                         style="width: <?php echo min($objective->transactions_progress, 100); ?>%"
                                                         title="<?php echo $objective->transactions_progress; ?>%">
                                                    </div>
                                                </div>
                                                <small class="text-<?php echo $objective->transactions_progress >= 100 ? 'success' : ($objective->transactions_progress >= 75 ? 'warning' : 'danger'); ?>">
                                                    <?php echo number_format($objective->transactions_progress, 1); ?>%
                                                </small>
                                            </td>
                                            
                                            <!-- Chiffre d'Affaires -->
                                            <td class="text-center">
                                                <div class="mb-1">
                                                    <small class="text-muted"><?php echo number_format($objective->revenue_amount, 0, ',', ' '); ?> / <?php echo number_format($objective->revenue_target, 0, ',', ' '); ?> TND</small>
                                                </div>
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar bg-info" 
                                                         style="width: <?php echo min($objective->revenue_progress, 100); ?>%"
                                                         title="<?php echo $objective->revenue_progress; ?>%">
                                                    </div>
                                                </div>
                                                <small class="text-<?php echo $objective->revenue_progress >= 100 ? 'success' : ($objective->revenue_progress >= 75 ? 'warning' : 'danger'); ?>">
                                                    <?php echo number_format($objective->revenue_progress, 1); ?>%
                                                </small>
                                            </td>
                                            
                                            <!-- Commissions -->
                                            <td class="text-center">
                                                <div class="mb-1">
                                                    <small class="text-muted"><?php echo number_format($objective->commission_earned, 0, ',', ' '); ?> TND</small>
                                                </div>
                                                <span class="badge bg-<?php echo $objective->commission_earned > 0 ? 'success' : 'secondary'; ?>">
                                                    <?php echo $objective->commission_earned > 0 ? 'Actif' : 'Inactif'; ?>
                                                </span>
                                            </td>
                                            
                                            <!-- Performance Globale -->
                                            <td class="text-center">
                                                <?php 
                                                $global_performance = ($objective->estimations_progress + $objective->contacts_progress + $objective->transactions_progress + $objective->revenue_progress) / 4;
                                                ?>
                                                <div class="progress mx-auto" style="height: 8px; width: 80px;">
                                                    <div class="progress-bar bg-<?php echo $global_performance >= 75 ? 'success' : ($global_performance >= 50 ? 'warning' : 'danger'); ?>" 
                                                         style="width: <?php echo min($global_performance, 100); ?>%"
                                                         title="Performance globale: <?php echo number_format($global_performance, 1); ?>%">
                                                    </div>
                                                </div>
                                                <small class="text-<?php echo $global_performance >= 75 ? 'success' : ($global_performance >= 50 ? 'warning' : 'danger'); ?>">
                                                    <?php echo number_format($global_performance, 1); ?>%
                                                </small>
                                            </td>
                                            
                                            <!-- Actions -->
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="<?php echo base_url('objectives/agent/' . $objective->agent_id . '?month=' . $current_month); ?>" 
                                                       class="btn btn-outline-primary" title="Voir détails">
                                                        <i class="ri-eye-line"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-success" 
                                                            onclick="refreshAgentData(<?php echo $objective->agent_id; ?>)" 
                                                            title="Actualiser">
                                                        <i class="ri-refresh-line"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php else: ?>
                            <div class="text-center py-5">
                                <div class="avatar-lg mx-auto mb-4">
                                    <div class="avatar-title bg-light text-muted rounded-circle">
                                        <i class="ri-team-line display-4"></i>
                                    </div>
                                </div>
                                <h5 class="text-muted">Aucun objectif défini</h5>
                                <p class="text-muted mb-4">Aucun objectif n'a été défini pour cette période.</p>
                                <a href="<?php echo base_url('objectives/bulk_set'); ?>" class="btn btn-primary">
                                    <i class="ri-settings-line me-2"></i>Définir des Objectifs
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
// Fonction pour changer de mois
function changeMonth(month) {
    window.location.href = '<?php echo base_url("objectives/team"); ?>?month=' + month;
}

// Fonction pour exporter les données de l'équipe
function exportTeamData() {
    const month = document.getElementById('month_selector').value;
    window.open('<?php echo base_url("objectives/export"); ?>?month=' + month + '&format=excel&type=team', '_blank');
}

// Fonction pour actualiser les données d'un agent spécifique
function refreshAgentData(agentId) {
    const month = document.getElementById('month_selector').value;
    
    fetch('<?php echo base_url("objectives/update_performance"); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: new URLSearchParams({
            month: month,
            agent_id: agentId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', 'Données mises à jour pour l\'agent');
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showToast('error', 'Erreur: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showToast('error', 'Erreur lors de la mise à jour');
    });
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    // Événement pour le bouton d'actualisation globale
    const refreshButton = document.getElementById('refresh_team_data');
    if (refreshButton) {
        refreshButton.addEventListener('click', function() {
            refreshButton.innerHTML = '<i class="ri-loader-2-line me-2 spin"></i>Actualisation...';
            refreshButton.disabled = true;
            
            const month = document.getElementById('month_selector').value;
            
            fetch('<?php echo base_url("objectives/update_performance"); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({
                    month: month
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('success', data.message);
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showToast('error', 'Erreur: ' + data.message);
                    refreshButton.innerHTML = '<i class="ri-refresh-line me-2"></i>Actualiser Données';
                    refreshButton.disabled = false;
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showToast('error', 'Erreur lors de la mise à jour des performances');
                refreshButton.innerHTML = '<i class="ri-refresh-line me-2"></i>Actualiser Données';
                refreshButton.disabled = false;
            });
        });
    }

    // Tooltip pour les barres de progression
    const progressBars = document.querySelectorAll('.progress-bar');
    progressBars.forEach(bar => {
        bar.setAttribute('title', 'Progression: ' + bar.style.width);
    });
});

// Fonction pour afficher des notifications toast
function showToast(type, message) {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        <i class="ri-${type === 'success' ? 'check-circle' : 'error-warning'}-line me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }, 5000);
}
</script>

<style>
/* Animation de rotation pour l'icône de chargement */
.spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

/* Amélioration de l'affichage des petites progressions */
.progress {
    background-color: #f3f4f6;
}

.table th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 11px;
    letter-spacing: 0.5px;
}

.avatar-xs {
    width: 32px;
    height: 32px;
}

.avatar-xs .avatar-title {
    width: 32px;
    height: 32px;
    font-size: 12px;
    line-height: 32px;
}
</style>
