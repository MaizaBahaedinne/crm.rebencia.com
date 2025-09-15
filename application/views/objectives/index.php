<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- Titre de la page -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Tableau de Bord des Objectifs</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Dashboard</a></li>
                                <li class="breadcrumb-item active">Objectifs</li>
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
                                        <button type="button" id="refresh_data" class="btn btn-success">
                                            <i class="ri-refresh-line me-2"></i>Actualiser Données
                                        </button>
                                        <a href="<?php echo base_url('objectives/set_monthly'); ?>" class="btn btn-primary">
                                            <i class="ri-target-line me-2"></i>Définir Objectifs
                                        </a>
                                        <a href="<?php echo base_url('objectives/team'); ?>" class="btn btn-outline-primary">
                                            <i class="ri-team-line me-2"></i>Vue Équipe
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques globales -->
            <?php 
            $total_agents = 0;
            $agents_on_track = 0;
            $total_revenue_target = 0;
            $total_revenue_achieved = 0;
            
            if (isset($objectives_data) && !empty($objectives_data)) {
                $total_agents = count($objectives_data);
                foreach ($objectives_data as $obj) {
                    if ($obj->revenue_progress >= 80) $agents_on_track++;
                    $total_revenue_target += $obj->revenue_target;
                    $total_revenue_achieved += $obj->revenue_amount;
                }
            }
            
            $global_revenue_progress = $total_revenue_target > 0 ? round(($total_revenue_achieved / $total_revenue_target) * 100, 1) : 0;
            ?>
            
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-3">
                                        <i class="ri-user-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">Total Agents</p>
                                    <h4 class="mb-0"><?php echo $total_agents; ?></h4>
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
                                        <i class="ri-check-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">En Bonne Voie</p>
                                    <h4 class="mb-0"><?php echo $agents_on_track; ?></h4>
                                    <p class="text-muted mb-0 fs-12">(≥ 80% de l'objectif)</p>
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
                                        <i class="ri-money-euro-circle-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">CA Objectif</p>
                                    <h4 class="mb-0"><?php echo number_format($total_revenue_target, 0, ',', ' '); ?> TND</h4>
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
                                        <i class="ri-line-chart-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">Progression Globale</p>
                                    <h4 class="mb-0"><?php echo $global_revenue_progress; ?>%</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tableau des objectifs par agent -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ri-target-line me-2"></i>Objectifs et Performances par Agent
                                <span class="badge bg-primary ms-2">
                                    <?php 
                                    $month_names = [
                                        '01' => 'Janvier', '02' => 'Février', '03' => 'Mars', '04' => 'Avril',
                                        '05' => 'Mai', '06' => 'Juin', '07' => 'Juillet', '08' => 'Août',
                                        '09' => 'Septembre', '10' => 'Octobre', '11' => 'Novembre', '12' => 'Décembre'
                                    ];
                                    $current_month_display = isset($current_month) ? $current_month : date('Y-m');
                                    $year = substr($current_month_display, 0, 4);
                                    $month = substr($current_month_display, 5, 2);
                                    echo $month_names[$month] . ' ' . $year;
                                    ?>
                                </span>
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <?php if (isset($objectives_data) && !empty($objectives_data)): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Agent</th>
                                                <th>Estimations</th>
                                                <th>Contacts</th>
                                                <th>Transactions</th>
                                                <th>Chiffre d'Affaires</th>
                                                <th>Commissions</th>
                                                <th>Performance</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($objectives_data as $data): ?>
                                                <tr>
                                                    <!-- Nom de l'agent -->
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-xs me-2">
                                                                <div class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                                    <?php echo strtoupper(substr($data->agent_name, 0, 1)); ?>
                                                                </div>
                                                            </div>
                                                            <span class="fw-medium"><?php echo htmlspecialchars($data->agent_name); ?></span>
                                                        </div>
                                                    </td>
                                                    
                                                    <!-- Estimations -->
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-grow-1">
                                                                <div class="d-flex justify-content-between mb-1">
                                                                    <span class="fw-medium"><?php echo $data->estimations_count; ?></span>
                                                                    <span class="text-muted">/ <?php echo $data->estimations_target; ?></span>
                                                                </div>
                                                                <div class="progress" style="height: 6px;">
                                                                    <div class="progress-bar" 
                                                                         style="width: <?php echo min($data->estimations_progress, 100); ?>%"
                                                                         role="progressbar"></div>
                                                                </div>
                                                            </div>
                                                            <span class="badge bg-<?php echo $data->estimations_progress >= 100 ? 'success' : ($data->estimations_progress >= 80 ? 'warning' : 'danger'); ?> ms-2">
                                                                <?php echo $data->estimations_progress; ?>%
                                                            </span>
                                                        </div>
                                                    </td>
                                                    
                                                    <!-- Contacts -->
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-grow-1">
                                                                <div class="d-flex justify-content-between mb-1">
                                                                    <span class="fw-medium"><?php echo $data->contacts_count; ?></span>
                                                                    <span class="text-muted">/ <?php echo $data->contacts_target; ?></span>
                                                                </div>
                                                                <div class="progress" style="height: 6px;">
                                                                    <div class="progress-bar bg-info" 
                                                                         style="width: <?php echo min($data->contacts_progress, 100); ?>%"
                                                                         role="progressbar"></div>
                                                                </div>
                                                            </div>
                                                            <span class="badge bg-<?php echo $data->contacts_progress >= 100 ? 'success' : ($data->contacts_progress >= 80 ? 'warning' : 'danger'); ?> ms-2">
                                                                <?php echo $data->contacts_progress; ?>%
                                                            </span>
                                                        </div>
                                                    </td>
                                                    
                                                    <!-- Transactions -->
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-grow-1">
                                                                <div class="d-flex justify-content-between mb-1">
                                                                    <span class="fw-medium"><?php echo $data->transactions_count; ?></span>
                                                                    <span class="text-muted">/ <?php echo $data->transactions_target; ?></span>
                                                                </div>
                                                                <div class="progress" style="height: 6px;">
                                                                    <div class="progress-bar bg-success" 
                                                                         style="width: <?php echo min($data->transactions_progress, 100); ?>%"
                                                                         role="progressbar"></div>
                                                                </div>
                                                            </div>
                                                            <span class="badge bg-<?php echo $data->transactions_progress >= 100 ? 'success' : ($data->transactions_progress >= 80 ? 'warning' : 'danger'); ?> ms-2">
                                                                <?php echo $data->transactions_progress; ?>%
                                                            </span>
                                                        </div>
                                                    </td>
                                                    
                                                    <!-- Chiffre d'affaires -->
                                                    <td>
                                                        <div class="text-center">
                                                            <div class="fw-medium text-primary">
                                                                <?php echo number_format($data->revenue_amount, 0, ',', ' '); ?> TND
                                                            </div>
                                                            <div class="text-muted fs-12">
                                                                / <?php echo number_format($data->revenue_target, 0, ',', ' '); ?> TND
                                                            </div>
                                                            <div class="progress mt-1" style="height: 6px;">
                                                                <div class="progress-bar bg-warning" 
                                                                     style="width: <?php echo min($data->revenue_progress, 100); ?>%"
                                                                     role="progressbar"></div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    
                                                    <!-- Commissions -->
                                                    <td>
                                                        <span class="fw-medium text-success">
                                                            <?php echo number_format($data->commission_earned, 2, ',', ' '); ?> TND
                                                        </span>
                                                    </td>
                                                    
                                                    <!-- Performance globale -->
                                                    <td>
                                                        <?php 
                                                        $global_progress = round(($data->estimations_progress + $data->contacts_progress + $data->transactions_progress + $data->revenue_progress) / 4, 1);
                                                        $performance_class = '';
                                                        $performance_text = '';
                                                        
                                                        if ($global_progress >= 90) {
                                                            $performance_class = 'success';
                                                            $performance_text = 'Excellent';
                                                        } elseif ($global_progress >= 80) {
                                                            $performance_class = 'warning';
                                                            $performance_text = 'Bon';
                                                        } elseif ($global_progress >= 60) {
                                                            $performance_class = 'info';
                                                            $performance_text = 'Moyen';
                                                        } else {
                                                            $performance_class = 'danger';
                                                            $performance_text = 'Faible';
                                                        }
                                                        ?>
                                                        <div class="text-center">
                                                            <div class="avatar-sm mx-auto mb-1">
                                                                <div class="avatar-title bg-<?php echo $performance_class; ?>-subtle text-<?php echo $performance_class; ?> rounded-circle">
                                                                    <?php echo $global_progress; ?>%
                                                                </div>
                                                            </div>
                                                            <span class="badge bg-<?php echo $performance_class; ?>"><?php echo $performance_text; ?></span>
                                                        </div>
                                                    </td>
                                                    
                                                    <!-- Actions -->
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn btn-ghost-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                                <i class="ri-more-2-fill"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <a class="dropdown-item" href="<?php echo base_url('objectives/agent/' . $data->agent_id); ?>">
                                                                        <i class="ri-eye-line me-2"></i>Voir détails
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item" href="<?php echo base_url('objectives/set_monthly?agent_id=' . $data->agent_id . '&month=' . $current_month); ?>">
                                                                        <i class="ri-edit-line me-2"></i>Modifier objectifs
                                                                    </a>
                                                                </li>
                                                                <li><hr class="dropdown-divider"></li>
                                                                <li>
                                                                    <a class="dropdown-item" href="<?php echo base_url('commission/history/' . $data->agent_id . '?month=' . $current_month); ?>">
                                                                        <i class="ri-money-euro-circle-line me-2"></i>Voir commissions
                                                                    </a>
                                                                </li>
                                                            </ul>
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
                                        <div class="avatar-title bg-soft-primary text-primary rounded-circle">
                                            <i class="ri-target-line fs-24"></i>
                                        </div>
                                    </div>
                                    <h5 class="text-muted">Aucun objectif défini</h5>
                                    <p class="text-muted mb-4">
                                        Aucun objectif n'a été défini pour la période sélectionnée.
                                    </p>
                                    <a href="<?php echo base_url('objectives/set_monthly'); ?>" class="btn btn-primary">
                                        <i class="ri-target-line me-2"></i>Définir des Objectifs
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Actions rapides -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ri-links-line me-2"></i>Actions Rapides
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <a href="<?php echo base_url('objectives/set_monthly'); ?>" class="btn btn-outline-primary btn-lg w-100">
                                        <i class="ri-target-line me-2"></i>Nouveaux Objectifs
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="<?php echo base_url('objectives/bulk_set'); ?>" class="btn btn-outline-success btn-lg w-100">
                                        <i class="ri-file-list-line me-2"></i>Objectifs en Lot
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="<?php echo base_url('objectives/team'); ?>" class="btn btn-outline-info btn-lg w-100">
                                        <i class="ri-team-line me-2"></i>Vue Équipe
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-outline-warning btn-lg w-100" onclick="exportObjectives()">
                                        <i class="ri-download-line me-2"></i>Exporter
                                    </button>
                                </div>
                            </div>
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
    window.location.href = '<?php echo base_url("objectives"); ?>?month=' + month;
}

// Fonction pour exporter les objectifs
function exportObjectives() {
    const month = document.getElementById('month_selector').value;
    window.open('<?php echo base_url("objectives/export"); ?>?month=' + month + '&format=excel', '_blank');
}

// Fonction pour actualiser les performances
function refreshPerformances() {
    if (confirm('Voulez-vous actualiser les données de performance pour tous les agents ?')) {
        fetch('<?php echo base_url("objectives/update_performance"); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                month: document.getElementById('month_selector').value
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la mise à jour des performances');
        });
    }
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    // Tooltip pour les barres de progression
    const progressBars = document.querySelectorAll('.progress-bar');
    progressBars.forEach(bar => {
        bar.setAttribute('title', 'Progression: ' + bar.style.width);
    });

    // Événement pour le bouton d'actualisation
    const refreshButton = document.getElementById('refresh_data');
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
                    // Afficher un message de succès
                    showToast('success', data.message);
                    // Recharger la page après 1 seconde
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
});

// Fonction pour afficher des notifications toast
function showToast(type, message) {
    // Créer un élément toast
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        <i class="ri-${type === 'success' ? 'check-circle' : 'error-warning'}-line me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(toast);
    
    // Supprimer le toast après 5 secondes
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
</style>
