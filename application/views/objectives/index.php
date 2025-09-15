<?php $this->load->view('includes/header'); ?>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- Page Title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Dashboard des Objectifs</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Dashboard</a></li>
                                <li class="breadcrumb-item active">Objectifs</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Month Selector and Actions -->
            <div class="row mb-4">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <form method="GET" action="<?php echo base_url('objectives'); ?>">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-8">
                                        <label class="form-label">Sélectionner le mois</label>
                                        <input type="month" class="form-control" name="month" 
                                               value="<?php echo $current_month; ?>" 
                                               onchange="this.form.submit()">
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="ri-search-line me-1"></i>Filtrer
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="card-title mb-0">Actions rapides</h6>
                                <div>
                                    <a href="<?php echo base_url('objectives/set_objectives'); ?>" class="btn btn-success btn-sm me-2">
                                        <i class="ri-add-line me-1"></i>Définir Objectifs
                                    </a>
                                    <a href="<?php echo base_url('objectives/performance'); ?>" class="btn btn-info btn-sm">
                                        <i class="ri-bar-chart-line me-1"></i>Performances
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Statistics -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm rounded-circle bg-primary bg-gradient me-3">
                                    <span class="avatar-title rounded-circle bg-primary text-white">
                                        <i class="ri-group-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-muted mb-1">Agents avec Objectifs</p>
                                    <h4 class="mb-0"><?php echo $summary['agents_with_objectives'] ?? 0; ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm rounded-circle bg-success bg-gradient me-3">
                                    <span class="avatar-title rounded-circle bg-success text-white">
                                        <i class="ri-file-list-3-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-muted mb-1">Estimations Target</p>
                                    <h4 class="mb-0"><?php echo number_format($summary['total_estimations_target'] ?? 0); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm rounded-circle bg-warning bg-gradient me-3">
                                    <span class="avatar-title rounded-circle bg-warning text-white">
                                        <i class="ri-phone-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-muted mb-1">Contacts Target</p>
                                    <h4 class="mb-0"><?php echo number_format($summary['total_contacts_target'] ?? 0); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm rounded-circle bg-info bg-gradient me-3">
                                    <span class="avatar-title rounded-circle bg-info text-white">
                                        <i class="ri-exchange-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-muted mb-1">Transactions Target</p>
                                    <h4 class="mb-0"><?php echo number_format($summary['total_transactions_target'] ?? 0); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Objectives Dashboard Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <h5 class="card-title mb-0">
                                    <i class="ri-dashboard-3-line me-2"></i>
                                    Dashboard des Objectifs - <?php echo date('F Y', strtotime($current_month . '-01')); ?>
                                </h5>
                                <div class="ms-auto">
                                    <button class="btn btn-outline-primary btn-sm" onclick="exportToExcel()">
                                        <i class="ri-file-excel-2-line me-1"></i>Exporter Excel
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($objectives_data)): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0" id="objectives-table">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Agent</th>
                                                <th>Estimations</th>
                                                <th>Contacts</th>
                                                <th>Transactions</th>
                                                <th>Revenus</th>
                                                <th>Performance Globale</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($objectives_data as $objective): ?>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-sm rounded-circle bg-primary bg-gradient me-2">
                                                                <span class="avatar-title rounded-circle bg-primary text-white">
                                                                    <i class="ri-user-3-line"></i>
                                                                </span>
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-0"><?php echo htmlspecialchars($objective->agent_name ?? 'Agent'); ?></h6>
                                                                <small class="text-muted">ID: <?php echo $objective->agent_id; ?></small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0 me-2">
                                                                <span class="badge bg-light text-dark">
                                                                    <?php echo $objective->estimations_count ?? 0; ?> / <?php echo $objective->estimations_target; ?>
                                                                </span>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <div class="progress progress-sm">
                                                                    <?php 
                                                                    $progress = min(100, $objective->estimations_progress ?? 0);
                                                                    $color = $progress >= 80 ? 'success' : ($progress >= 60 ? 'warning' : 'danger');
                                                                    ?>
                                                                    <div class="progress-bar bg-<?php echo $color; ?>" 
                                                                         style="width: <?php echo $progress; ?>%"></div>
                                                                </div>
                                                                <small class="text-muted"><?php echo number_format($progress, 1); ?>%</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0 me-2">
                                                                <span class="badge bg-light text-dark">
                                                                    <?php echo $objective->contacts_count ?? 0; ?> / <?php echo $objective->contacts_target; ?>
                                                                </span>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <div class="progress progress-sm">
                                                                    <?php 
                                                                    $progress = min(100, $objective->contacts_progress ?? 0);
                                                                    $color = $progress >= 80 ? 'success' : ($progress >= 60 ? 'warning' : 'danger');
                                                                    ?>
                                                                    <div class="progress-bar bg-<?php echo $color; ?>" 
                                                                         style="width: <?php echo $progress; ?>%"></div>
                                                                </div>
                                                                <small class="text-muted"><?php echo number_format($progress, 1); ?>%</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0 me-2">
                                                                <span class="badge bg-light text-dark">
                                                                    <?php echo $objective->transactions_count ?? 0; ?> / <?php echo $objective->transactions_target; ?>
                                                                </span>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <div class="progress progress-sm">
                                                                    <?php 
                                                                    $progress = min(100, $objective->transactions_progress ?? 0);
                                                                    $color = $progress >= 80 ? 'success' : ($progress >= 60 ? 'warning' : 'danger');
                                                                    ?>
                                                                    <div class="progress-bar bg-<?php echo $color; ?>" 
                                                                         style="width: <?php echo $progress; ?>%"></div>
                                                                </div>
                                                                <small class="text-muted"><?php echo number_format($progress, 1); ?>%</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0 me-2">
                                                                <span class="badge bg-light text-dark">
                                                                    €<?php echo number_format($objective->revenue_amount ?? 0, 0, ',', ' '); ?> / €<?php echo number_format($objective->revenue_target, 0, ',', ' '); ?>
                                                                </span>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <div class="progress progress-sm">
                                                                    <?php 
                                                                    $progress = min(100, $objective->revenue_progress ?? 0);
                                                                    $color = $progress >= 80 ? 'success' : ($progress >= 60 ? 'warning' : 'danger');
                                                                    ?>
                                                                    <div class="progress-bar bg-<?php echo $color; ?>" 
                                                                         style="width: <?php echo $progress; ?>%"></div>
                                                                </div>
                                                                <small class="text-muted"><?php echo number_format($progress, 1); ?>%</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <?php 
                                                        $overall = (($objective->estimations_progress ?? 0) + 
                                                                   ($objective->contacts_progress ?? 0) + 
                                                                   ($objective->transactions_progress ?? 0) + 
                                                                   ($objective->revenue_progress ?? 0)) / 4;
                                                        $overall_color = $overall >= 80 ? 'success' : ($overall >= 60 ? 'warning' : 'danger');
                                                        ?>
                                                        <div class="text-center">
                                                            <div class="progress progress-sm mx-auto" style="width: 60px;">
                                                                <div class="progress-bar bg-<?php echo $overall_color; ?>" 
                                                                     style="width: <?php echo min(100, $overall); ?>%"></div>
                                                            </div>
                                                            <small class="text-<?php echo $overall_color; ?> fw-semibold">
                                                                <?php echo number_format($overall, 1); ?>%
                                                            </small>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                                    data-bs-toggle="dropdown">
                                                                Actions
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item" 
                                                                   href="<?php echo base_url('objectives/edit/' . $objective->id); ?>">
                                                                    <i class="ri-edit-line me-2"></i>Modifier Objectifs
                                                                </a>
                                                                <a class="dropdown-item" 
                                                                   href="<?php echo base_url('objectives/performance/' . $objective->agent_id); ?>">
                                                                    <i class="ri-bar-chart-line me-2"></i>Voir Performances
                                                                </a>
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item text-danger" 
                                                                   href="#" onclick="deleteObjective(<?php echo $objective->id; ?>)">
                                                                    <i class="ri-delete-bin-line me-2"></i>Supprimer
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
                                    <div class="avatar-xl mx-auto mb-4">
                                        <div class="avatar-title bg-primary bg-gradient rounded-circle">
                                            <i class="ri-bar-chart-line display-4"></i>
                                        </div>
                                    </div>
                                    <h5>Aucun objectif défini pour ce mois</h5>
                                    <p class="text-muted">Commencez par définir des objectifs pour vos agents.</p>
                                    <a href="<?php echo base_url('objectives/set_objectives'); ?>" class="btn btn-primary">
                                        <i class="ri-add-line me-1"></i>Définir des Objectifs
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
function deleteObjective(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet objectif ?')) {
        window.location.href = '<?php echo base_url("objectives/delete/"); ?>' + id;
    }
}

function exportToExcel() {
    // Fonctionnalité d'export Excel à implémenter
    alert('Fonctionnalité d\'export Excel en cours de développement.');
}
</script>

<?php $this->load->view('includes/footer'); ?>
