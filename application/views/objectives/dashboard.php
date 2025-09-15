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
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <form action="<?php echo base_url('objectives'); ?>" method="get" class="d-flex align-items-center">
                                <label for="month" class="form-label me-3 mb-0">Mois :</label>
                                <input type="month" 
                                       class="form-control me-3" 
                                       id="month" 
                                       name="month" 
                                       value="<?php echo $current_month; ?>"
                                       onchange="this.form.submit()">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-search-line"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-end">
                            <a href="<?php echo base_url('objectives/set_monthly'); ?>" class="btn btn-success me-2">
                                <i class="ri-add-line me-2"></i>Définir Objectifs
                            </a>
                            <a href="<?php echo base_url('objectives/bulk_set'); ?>" class="btn btn-outline-primary">
                                <i class="ri-file-list-2-line me-2"></i>Objectifs en Masse
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques globales -->
            <?php if ($stats): ?>
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm rounded-circle bg-primary-subtle">
                                        <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                            <i class="ri-team-line fs-18"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-1"><?php echo $stats->total_agents ?? 0; ?></h5>
                                    <p class="text-muted mb-0">Agents avec Objectifs</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm rounded-circle bg-success-subtle">
                                        <span class="avatar-title rounded-circle bg-success-subtle text-success">
                                            <i class="ri-file-list-3-line fs-18"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-1">
                                        <?php echo round($stats->avg_estimations_progress ?? 0, 1); ?>%
                                    </h5>
                                    <p class="text-muted mb-0">Moy. Estimations</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm rounded-circle bg-warning-subtle">
                                        <span class="avatar-title rounded-circle bg-warning-subtle text-warning">
                                            <i class="ri-phone-line fs-18"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-1">
                                        <?php echo round($stats->avg_contacts_progress ?? 0, 1); ?>%
                                    </h5>
                                    <p class="text-muted mb-0">Moy. Contacts</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm rounded-circle bg-info-subtle">
                                        <span class="avatar-title rounded-circle bg-info-subtle text-info">
                                            <i class="ri-money-euro-circle-line fs-18"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-1">
                                        <?php echo round($stats->avg_revenue_progress ?? 0, 1); ?>%
                                    </h5>
                                    <p class="text-muted mb-0">Moy. CA</p>
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
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="card-title mb-0">
                                    Objectifs et Performances - <?php echo date('F Y', strtotime($current_month . '-01')); ?>
                                </h5>
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                        <i class="ri-more-2-line"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="<?php echo base_url('objectives/team?month=' . $current_month); ?>">
                                            <i class="ri-team-line me-2"></i>Vue Équipe
                                        </a>
                                        <a class="dropdown-item" href="#" onclick="exportData()">
                                            <i class="ri-file-download-line me-2"></i>Exporter
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($objectives_data)): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Agent</th>
                                                <th class="text-center">Estimations</th>
                                                <th class="text-center">Contacts</th>
                                                <th class="text-center">Transactions</th>
                                                <th class="text-center">CA (€)</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($objectives_data as $data): ?>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-sm rounded-circle bg-primary-subtle me-3">
                                                                <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                                                    <?php echo strtoupper(substr($data->agent_name ?? 'A', 0, 1)); ?>
                                                                </span>
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-0"><?php echo htmlspecialchars($data->agent_name ?? 'Agent'); ?></h6>
                                                                <small class="text-muted">ID: <?php echo $data->agent_id; ?></small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    
                                                    <td class="text-center">
                                                        <div class="d-flex flex-column align-items-center">
                                                            <span class="fw-semibold">
                                                                <?php echo ($data->estimations_count ?? 0) . ' / ' . ($data->estimations_target ?? 0); ?>
                                                            </span>
                                                            <div class="progress mt-1" style="width: 80px; height: 6px;">
                                                                <?php 
                                                                $progress = min(100, $data->estimations_progress ?? 0);
                                                                $color = $progress >= 80 ? 'success' : ($progress >= 60 ? 'warning' : 'danger');
                                                                ?>
                                                                <div class="progress-bar bg-<?php echo $color; ?>" 
                                                                     style="width: <?php echo $progress; ?>%"></div>
                                                            </div>
                                                            <small class="text-muted"><?php echo round($progress, 1); ?>%</small>
                                                        </div>
                                                    </td>
                                                    
                                                    <td class="text-center">
                                                        <div class="d-flex flex-column align-items-center">
                                                            <span class="fw-semibold">
                                                                <?php echo ($data->contacts_count ?? 0) . ' / ' . ($data->contacts_target ?? 0); ?>
                                                            </span>
                                                            <div class="progress mt-1" style="width: 80px; height: 6px;">
                                                                <?php 
                                                                $progress = min(100, $data->contacts_progress ?? 0);
                                                                $color = $progress >= 80 ? 'success' : ($progress >= 60 ? 'warning' : 'danger');
                                                                ?>
                                                                <div class="progress-bar bg-<?php echo $color; ?>" 
                                                                     style="width: <?php echo $progress; ?>%"></div>
                                                            </div>
                                                            <small class="text-muted"><?php echo round($progress, 1); ?>%</small>
                                                        </div>
                                                    </td>
                                                    
                                                    <td class="text-center">
                                                        <div class="d-flex flex-column align-items-center">
                                                            <span class="fw-semibold">
                                                                <?php echo ($data->transactions_count ?? 0) . ' / ' . ($data->transactions_target ?? 0); ?>
                                                            </span>
                                                            <div class="progress mt-1" style="width: 80px; height: 6px;">
                                                                <?php 
                                                                $progress = min(100, $data->transactions_progress ?? 0);
                                                                $color = $progress >= 80 ? 'success' : ($progress >= 60 ? 'warning' : 'danger');
                                                                ?>
                                                                <div class="progress-bar bg-<?php echo $color; ?>" 
                                                                     style="width: <?php echo $progress; ?>%"></div>
                                                            </div>
                                                            <small class="text-muted"><?php echo round($progress, 1); ?>%</small>
                                                        </div>
                                                    </td>
                                                    
                                                    <td class="text-center">
                                                        <div class="d-flex flex-column align-items-center">
                                                            <span class="fw-semibold">
                                                                <?php echo number_format($data->revenue_amount ?? 0, 0, ',', ' '); ?> / 
                                                                <?php echo number_format($data->revenue_target ?? 0, 0, ',', ' '); ?>
                                                            </span>
                                                            <div class="progress mt-1" style="width: 80px; height: 6px;">
                                                                <?php 
                                                                $progress = min(100, $data->revenue_progress ?? 0);
                                                                $color = $progress >= 80 ? 'success' : ($progress >= 60 ? 'warning' : 'danger');
                                                                ?>
                                                                <div class="progress-bar bg-<?php echo $color; ?>" 
                                                                     style="width: <?php echo $progress; ?>%"></div>
                                                            </div>
                                                            <small class="text-muted"><?php echo round($progress, 1); ?>%</small>
                                                        </div>
                                                    </td>
                                                    
                                                    <td class="text-center">
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                                Actions
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item" href="<?php echo base_url('objectives/agent/' . $data->agent_id . '?month=' . $current_month); ?>">
                                                                    <i class="ri-eye-line me-2"></i>Voir Détails
                                                                </a>
                                                                <a class="dropdown-item" href="<?php echo base_url('objectives/calculate_performance/' . $data->agent_id . '/' . $current_month); ?>">
                                                                    <i class="ri-refresh-line me-2"></i>Recalculer
                                                                </a>
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item" href="<?php echo base_url('commission/history/' . $data->agent_id . '?month=' . $current_month); ?>">
                                                                    <i class="ri-money-euro-circle-line me-2"></i>Commissions
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-5">
                                    <i class="ri-folder-2-line text-muted" style="font-size: 4rem;"></i>
                                    <h5 class="mt-3 text-muted">Aucun Objectif Défini</h5>
                                    <p class="text-muted">Aucun objectif n'a été défini pour ce mois.</p>
                                    <a href="<?php echo base_url('objectives/set_monthly'); ?>" class="btn btn-primary">
                                        <i class="ri-add-line me-2"></i>Définir des Objectifs
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
function exportData() {
    const month = '<?php echo $current_month; ?>';
    window.location.href = `<?php echo base_url('objectives/export'); ?>?month=${month}`;
}

// Actualisation automatique toutes les 5 minutes
setInterval(function() {
    if (document.hidden === false) {
        location.reload();
    }
}, 300000);
</script>
