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
});
</script>
    border: 1px solid #e2e8f0;
}

.stats-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.stats-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: #059669;
    margin-bottom: 0.5rem;
}

.stats-label {
    color: #64748b;
    font-size: 0.875rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.content-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e2e8f0;
}

.card-header {
    padding: 1.5rem;
    border-bottom: 1px solid #e2e8f0;
    background: #f8fafc;
    border-radius: 12px 12px 0 0;
}

.card-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0;
}

.table-modern {
    margin: 0;
}

.table-modern th {
    background: #f8fafc;
    border: none;
    color: #475569;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.025em;
    padding: 1rem;
}

.table-modern td {
    border: none;
    border-bottom: 1px solid #e2e8f0;
    padding: 1rem;
    vertical-align: middle;
}

.btn-modern {
    background: #3b82f6;
    border: none;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-modern:hover {
    background: #2563eb;
    transform: translateY(-1px);
}

.btn-outline-modern {
    background: transparent;
    border: 2px solid #e2e8f0;
    color: #64748b;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-outline-modern:hover {
    border-color: #3b82f6;
    color: #3b82f6;
    background: #eff6ff;
}

.floating-action {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    z-index: 1000;
}

.fab-modern {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: #3b82f6;
    color: white;
    border: none;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    font-size: 1.5rem;
    transition: all 0.2s ease;
}

.fab-modern:hover {
    background: #2563eb;
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(59, 130, 246, 0.5);
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: #64748b;
}

.empty-state-icon {
    font-size: 4rem;
    color: #cbd5e1;
    margin-bottom: 1rem;
}

.empty-state-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
}
    padding: 2rem;
    color: white;
}

.premium-title {
    font-size: 2.5rem;
    font-weight: 700;
    background: linear-gradient(135deg, #fff, #f8f9fa);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 0.5rem;
}

.breadcrumb-premium {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50px;
    padding: 0.5rem 1.5rem;
    backdrop-filter: blur(10px);
}

.breadcrumb-premium .breadcrumb-item a {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    transition: color 0.3s;
}

.breadcrumb-premium .breadcrumb-item a:hover {
    color: white;
}

.breadcrumb-premium .breadcrumb-item.active {
    color: white;
    font-weight: 600;
}

.action-card {
    background: linear-gradient(145deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.1));
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 20px;
    padding: 1.5rem;
    transition: all 0.3s ease;
}

.btn-premium {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border: none;
    border-radius: 15px;
    padding: 0.75rem 2rem;
    color: white;
    font-weight: 600;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.btn-premium::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: all 0.6s;
}

.btn-premium:hover::before {
    left: 100%;
}

.btn-premium:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
}

.month-selector {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 15px;
    color: white;
    backdrop-filter: blur(10px);
}

.month-selector:focus {
    background: rgba(255, 255, 255, 0.15);
    border-color: rgba(255, 255, 255, 0.5);
    color: white;
    box-shadow: 0 0 0 0.25rem rgba(255, 255, 255, 0.1);
}

.objectives-table {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    overflow: hidden;
}

.table-premium {
    margin: 0;
    color: white;
}

.table-premium thead th {
    background: rgba(255, 255, 255, 0.1);
    border: none;
    color: white;
    font-weight: 600;
    letter-spacing: 0.5px;
    padding: 1.5rem 1rem;
}

.table-premium tbody td {
    border-color: rgba(255, 255, 255, 0.1);
    padding: 1.25rem 1rem;
    vertical-align: middle;
}

.progress-premium {
    height: 8px;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.2);
    overflow: hidden;
}

.progress-premium .progress-bar {
    border-radius: 10px;
    background: linear-gradient(90deg, #00d4aa, #00d4ff);
    transition: width 0.6s ease;
}

.floating-action {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    z-index: 1000;
}

.fab-premium {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border: none;
    color: white;
    font-size: 1.5rem;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
    transition: all 0.3s ease;
}

.fab-premium:hover {
    transform: translateY(-3px) scale(1.1);
    box-shadow: 0 15px 40px rgba(102, 126, 234, 0.6);
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fadeInUp {
    animation: fadeInUp 0.6s ease-out;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.pulse-animation {
    animation: pulse 2s infinite;
}
</style>

<div class="objectives-premium">
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                
                <!-- Premium Header -->
                <div class="premium-header animate-fadeInUp">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="premium-title">Dashboard des Objectifs</h1>
                            <p class="mb-0" style="color: rgba(255, 255, 255, 0.8); font-size: 1.1rem;">
                                Suivez et gérez les performances de vos équipes
                            </p>
                        </div>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb breadcrumb-premium mb-0">
                                <li class="breadcrumb-item">
                                    <a href="<?php echo base_url('dashboard'); ?>">
                                        <i class="ri-home-4-line me-1"></i>Dashboard
                                    </a>
                                </li>
                                <li class="breadcrumb-item active">Objectifs</li>
                            </ol>
                        </nav>
                    </div>
                </div>

                <!-- Month Selector and Actions -->
                <div class="row mb-4">
                    <div class="col-lg-6">
                        <div class="action-card animate-fadeInUp" style="animation-delay: 0.2s;">
                            <h6 class="mb-3" style="color: white; font-weight: 600;">
                                <i class="ri-calendar-line me-2"></i>Période d'analyse
                            </h6>
                            <form method="GET" action="<?php echo base_url('objectives'); ?>">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-8">
                                        <label class="form-label" style="color: rgba(255, 255, 255, 0.9);">
                                            Sélectionner le mois
                                        </label>
                                        <input type="month" class="form-control month-selector" name="month" 
                                               value="<?php echo $current_month; ?>" 
                                               onchange="this.form.submit()">
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-premium w-100">
                                            <i class="ri-search-line me-1"></i>Analyser
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="action-card animate-fadeInUp" style="animation-delay: 0.4s;">
                            <h6 class="mb-3" style="color: white; font-weight: 600;">
                                <i class="ri-settings-3-line me-2"></i>Actions rapides
                            </h6>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="<?php echo base_url('objectives/set_monthly'); ?>" 
                                   class="btn btn-premium flex-fill">
                                    <i class="ri-target-line me-1"></i>Définir Objectifs
                                </a>
                                <button class="btn btn-premium flex-fill" onclick="exportData()">
                                    <i class="ri-download-line me-1"></i>Exporter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Statistics Overview Premium -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="stats-card animate-fadeInUp" style="animation-delay: 0.6s;">
                            <div class="stats-icon">
                                <i class="ri-group-line"></i>
                            </div>
                            <div class="stats-number pulse-animation">
                                <?php echo $summary['agents_with_objectives'] ?? 0; ?>
                            </div>
                            <div class="stats-label">Agents avec Objectifs</div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="stats-card animate-fadeInUp" style="animation-delay: 0.8s;">
                            <div class="stats-icon">
                                <i class="ri-file-list-3-line"></i>
                            </div>
                            <div class="stats-number pulse-animation">
                                <?php echo $summary['total_objectives'] ?? 0; ?>
                            </div>
                            <div class="stats-label">Objectifs Définis</div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="stats-card animate-fadeInUp" style="animation-delay: 1.0s;">
                            <div class="stats-icon">
                                <i class="ri-trophy-line"></i>
                            </div>
                            <div class="stats-number pulse-animation">
                                <?php echo number_format($summary['avg_completion'] ?? 0, 1); ?>%
                            </div>
                            <div class="stats-label">Taux Moyen</div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="stats-card animate-fadeInUp" style="animation-delay: 1.2s;">
                            <div class="stats-icon">
                                <i class="ri-money-euro-circle-line"></i>
                            </div>
                            <div class="stats-number pulse-animation">
                                <?php echo number_format($summary['total_revenue_target'] ?? 0 / 1000, 0); ?>K€
                            </div>
                            <div class="stats-label">CA Objectif</div>
                        </div>
                    </div>
                </div>
                <!-- Objectives Table Premium -->
                <div class="objectives-table animate-fadeInUp" style="animation-delay: 1.4s;">
                    <div class="p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0" style="color: white; font-weight: 600;">
                                <i class="ri-table-line me-2"></i>Objectifs du mois
                            </h5>
                            <div class="d-flex gap-2">
                                <button class="btn btn-premium btn-sm" onclick="refreshData()">
                                    <i class="ri-refresh-line me-1"></i>Actualiser
                                </button>
                                <a href="<?php echo base_url('objectives/set_monthly'); ?>" 
                                   class="btn btn-premium btn-sm">
                                    <i class="ri-add-line me-1"></i>Ajouter
                                </a>
                            </div>
                        </div>

            <!-- Objectives Dashboard Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header">
                        
                        <?php if (!empty($objectives_data)): ?>
                            <div class="table-responsive">
                                <table class="table table-premium">
                                    <thead>
                                        <tr>
                                            <th style="border-bottom: 2px solid rgba(255, 255, 255, 0.2);">
                                                <i class="ri-user-3-line me-2"></i>Agent
                                            </th>
                                            <th style="border-bottom: 2px solid rgba(255, 255, 255, 0.2);">
                                                <i class="ri-file-list-3-line me-2"></i>Estimations
                                            </th>
                                            <th style="border-bottom: 2px solid rgba(255, 255, 255, 0.2);">
                                                <i class="ri-phone-line me-2"></i>Contacts
                                            </th>
                                            <th style="border-bottom: 2px solid rgba(255, 255, 255, 0.2);">
                                                <i class="ri-exchange-line me-2"></i>Transactions
                                            </th>
                                            <th style="border-bottom: 2px solid rgba(255, 255, 255, 0.2);">
                                                <i class="ri-money-euro-circle-line me-2"></i>Revenus
                                            </th>
                                            <th style="border-bottom: 2px solid rgba(255, 255, 255, 0.2);">
                                                <i class="ri-trophy-line me-2"></i>Performance
                                            </th>
                                            <th style="border-bottom: 2px solid rgba(255, 255, 255, 0.2);">
                                                <i class="ri-settings-3-line me-2"></i>Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($objectives_data as $objective): ?>
                                            <tr style="transition: all 0.3s ease;" 
                                                onmouseover="this.style.background='rgba(255,255,255,0.1)'" 
                                                onmouseout="this.style.background='transparent'">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-premium me-3">
                                                            <div style="width: 45px; height: 45px; border-radius: 50%; 
                                                                        background: linear-gradient(135deg, #667eea, #764ba2);
                                                                        display: flex; align-items: center; justify-content: center;
                                                                        color: white; font-weight: 600; font-size: 1.1rem;">
                                                                <?php echo strtoupper(substr($objective->agent_name ?? 'A', 0, 1)); ?>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <div style="color: white; font-weight: 600; font-size: 1rem;">
                                                                <?php echo htmlspecialchars($objective->agent_name ?? 'Agent'); ?>
                                                            </div>
                                                            <small style="color: rgba(255, 255, 255, 0.7);">
                                                                ID: <?php echo $objective->agent_id; ?>
                                                            </small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="objective-metric">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <span style="color: rgba(255, 255, 255, 0.9); font-weight: 500;">
                                                                <?php echo $objective->estimations_count ?? 0; ?> / <?php echo $objective->estimations_target; ?>
                                                            </span>
                                                            <span style="color: #00d4aa; font-weight: 600;">
                                                                <?php 
                                                                $progress = min(100, $objective->estimations_progress ?? 0);
                                                                echo number_format($progress, 1); 
                                                                ?>%
                                                            </span>
                                                        </div>
                                                        <div class="progress-premium">
                                                            <div class="progress-bar" style="width: <?php echo $progress; ?>%"></div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="objective-metric">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <span style="color: rgba(255, 255, 255, 0.9); font-weight: 500;">
                                                                <?php echo $objective->contacts_count ?? 0; ?> / <?php echo $objective->contacts_target; ?>
                                                            </span>
                                                            <span style="color: #00d4aa; font-weight: 600;">
                                                                <?php 
                                                                $progress = min(100, $objective->contacts_progress ?? 0);
                                                                echo number_format($progress, 1); 
                                                                ?>%
                                                            </span>
                                                        </div>
                                                        <div class="progress-premium">
                                                            <div class="progress-bar" style="width: <?php echo $progress; ?>%"></div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="objective-metric">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <span style="color: rgba(255, 255, 255, 0.9); font-weight: 500;">
                                                                <?php echo $objective->transactions_count ?? 0; ?> / <?php echo $objective->transactions_target; ?>
                                                            </span>
                                                            <span style="color: #00d4aa; font-weight: 600;">
                                                                <?php 
                                                                $progress = min(100, $objective->transactions_progress ?? 0);
                                                                echo number_format($progress, 1); 
                                                                ?>%
                                                            </span>
                                                        </div>
                                                        <div class="progress-premium">
                                                            <div class="progress-bar" style="width: <?php echo $progress; ?>%"></div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="objective-metric">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <span style="color: rgba(255, 255, 255, 0.9); font-weight: 500;">
                                                                <?php echo number_format($objective->revenue_current ?? 0); ?>€ / <?php echo number_format($objective->revenue_target); ?>€
                                                            </span>
                                                            <span style="color: #00d4aa; font-weight: 600;">
                                                                <?php 
                                                                $progress = min(100, $objective->revenue_progress ?? 0);
                                                                echo number_format($progress, 1); 
                                                                ?>%
                                                            </span>
                                                        </div>
                                                        <div class="progress-premium">
                                                            <div class="progress-bar" style="width: <?php echo $progress; ?>%"></div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-center">
                                                        <?php 
                                                        $overall = $objective->overall_progress ?? 0;
                                                        $badge_class = $overall >= 80 ? 'success' : ($overall >= 60 ? 'warning' : 'danger');
                                                        $badge_color = $overall >= 80 ? '#00d4aa' : ($overall >= 60 ? '#ffc107' : '#ff6b6b');
                                                        ?>
                                                        <div style="display: inline-block; padding: 0.5rem 1rem; 
                                                                    background: rgba(255, 255, 255, 0.1); 
                                                                    border: 2px solid <?php echo $badge_color; ?>;
                                                                    border-radius: 50px; color: <?php echo $badge_color; ?>;
                                                                    font-weight: 600;">
                                                            <?php echo number_format($overall, 1); ?>%
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <button class="btn btn-premium btn-sm" 
                                                                onclick="viewDetails(<?php echo $objective->agent_id; ?>)">
                                                            <i class="ri-eye-line"></i>
                                                        </button>
                                                        <button class="btn btn-premium btn-sm" 
                                                                onclick="editObjective(<?php echo $objective->agent_id; ?>)">
                                                            <i class="ri-edit-line"></i>
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
                                <div style="opacity: 0.6;">
                                    <i class="ri-file-list-3-line" style="font-size: 4rem; color: rgba(255, 255, 255, 0.5);"></i>
                                    <h5 style="color: rgba(255, 255, 255, 0.8); margin-top: 1rem;">
                                        Aucun objectif défini pour ce mois
                                    </h5>
                                    <p style="color: rgba(255, 255, 255, 0.6);">
                                        Commencez par définir des objectifs pour vos agents
                                    </p>
                                    <a href="<?php echo base_url('objectives/set_monthly'); ?>" 
                                       class="btn btn-premium mt-3">
                                        <i class="ri-add-line me-2"></i>Définir des Objectifs
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Floating Action Button -->
                <div class="floating-action">
                    <button class="fab-premium" onclick="quickActions()" title="Actions rapides">
                        <i class="ri-add-line"></i>
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- JavaScript Premium -->
<script>
// Animation d'entrée progressive
document.addEventListener('DOMContentLoaded', function() {
    // Ajouter des effets d'hover dynamiques
    const statsCards = document.querySelectorAll('.stats-card');
    statsCards.forEach((card, index) => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-15px) scale(1.03)';
            this.style.boxShadow = '0 40px 80px rgba(0, 0, 0, 0.2)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(-5px) scale(1)';
            this.style.boxShadow = '0 25px 45px rgba(0, 0, 0, 0.1)';
        });
    });

    // Animation des barres de progression
    const progressBars = document.querySelectorAll('.progress-bar');
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.width = width;
        }, 500);
    });
});

// Actions rapides
function quickActions() {
    const actions = [
        { 
            text: 'Définir Objectifs Mensuels', 
            icon: 'ri-target-line',
            action: () => window.location.href = '<?php echo base_url("objectives/set_monthly"); ?>'
        },
        { 
            text: 'Voir Performances', 
            icon: 'ri-bar-chart-line',
            action: () => window.location.href = '<?php echo base_url("objectives/performance"); ?>'
        },
        { 
            text: 'Exporter Données', 
            icon: 'ri-download-line',
            action: () => exportData()
        },
        { 
            text: 'Actualiser', 
            icon: 'ri-refresh-line',
            action: () => window.location.reload()
        }
    ];

    let menuHTML = '<div class="quick-menu" style="position: fixed; bottom: 5rem; right: 2rem; z-index: 1001;">';
    actions.forEach((action, index) => {
        menuHTML += `
            <div class="quick-action-item" style="
                background: linear-gradient(135deg, #667eea, #764ba2);
                color: white; padding: 1rem; margin-bottom: 0.5rem;
                border-radius: 50px; cursor: pointer; 
                transform: translateX(100px); opacity: 0;
                transition: all 0.3s ease; box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
                animation: slideInRight 0.5s forwards ${index * 0.1}s;
            " onclick="${action.action.toString().replace('() => ', '')}">
                <i class="${action.icon} me-2"></i>${action.text}
            </div>
        `;
    });
    menuHTML += '</div>';

    // Ajouter le style CSS pour l'animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideInRight {
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        .quick-action-item:hover {
            transform: translateX(-5px) scale(1.05) !important;
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.6) !important;
        }
    `;
    document.head.appendChild(style);

    // Retirer le menu existant s'il y en a un
    const existingMenu = document.querySelector('.quick-menu');
    if (existingMenu) {
        existingMenu.remove();
        return;
    }

    // Ajouter le nouveau menu
    document.body.insertAdjacentHTML('beforeend', menuHTML);

    // Retirer le menu après 5 secondes ou au clic ailleurs
    setTimeout(() => {
        const menu = document.querySelector('.quick-menu');
        if (menu) menu.remove();
    }, 5000);
}

// Fonctions d'action
function viewDetails(agentId) {
    window.location.href = `<?php echo base_url("objectives/agent/"); ?>${agentId}`;
}

function editObjective(agentId) {
    window.location.href = `<?php echo base_url("objectives/set_monthly?agent="); ?>${agentId}`;
}

function refreshData() {
    // Animation de refresh
    const fab = document.querySelector('.fab-premium');
    fab.style.transform = 'rotate(360deg)';
    setTimeout(() => {
        window.location.reload();
    }, 500);
}

function exportData() {
    // Animation d'export
    const notification = document.createElement('div');
    notification.innerHTML = `
        <div style="
            position: fixed; top: 2rem; right: 2rem; z-index: 1002;
            background: linear-gradient(135deg, #00d4aa, #00d4ff);
            color: white; padding: 1rem 2rem; border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 212, 170, 0.4);
            animation: slideInDown 0.5s ease;
        ">
            <i class="ri-download-line me-2"></i>Export des données en cours...
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
        // Ici, déclencher le vrai export
        window.print(); // Ou autre logique d'export
    }, 2000);
}

// Effet de particules (optionnel)
function createParticleEffect() {
    for (let i = 0; i < 3; i++) {
        const particle = document.createElement('div');
        particle.style.cssText = `
            position: fixed; width: 4px; height: 4px; background: white;
            border-radius: 50%; pointer-events: none; z-index: 999;
            top: ${Math.random() * 100}vh; left: ${Math.random() * 100}vw;
            animation: float ${3 + Math.random() * 4}s linear infinite;
            opacity: ${0.1 + Math.random() * 0.3};
        `;
        document.body.appendChild(particle);
        
        setTimeout(() => particle.remove(), 7000);
    }
}

// Style pour l'animation de flottement des particules
const particleStyle = document.createElement('style');
particleStyle.textContent = `
    @keyframes float {
        0% { transform: translateY(100vh) rotate(0deg); }
        100% { transform: translateY(-10vh) rotate(360deg); }
    }
`;
document.head.appendChild(particleStyle);

// Créer des particules périodiquement
setInterval(createParticleEffect, 3000);
</script>

<?php $this->load->view('includes/footer'); ?>
