<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">Statistiques agent<?= isset($agent->display_name)?' : '.htmlspecialchars($agent->display_name):''; ?></h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="<?= base_url('dashboard'); ?>">Dashboard</a></li>
                                <li class="breadcrumb-item active">Agent</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                $stats = isset($stats)?$stats:[]; 
                $kpis = [
                    // ['label'=>'Leads','icon'=>'ri-user-search-line','value'=>$stats['leads'] ?? 0,'class'=>'primary'],
                    ['label'=>'Propriétés','icon'=>'ri-home-4-line','value'=>$stats['properties'] ?? 0,'class'=>'info'],
                    ['label'=>'Transactions','icon'=>'ri-exchange-dollar-line','value'=>$stats['sales'] ?? $stats['transactions'] ?? 0,'class'=>'success'],
                    ['label'=>'Tâches','icon'=>'ri-task-line','value'=>$stats['tasks'] ?? 0,'class'=>'warning'],
                ];
            ?>
            <div class="row g-3 mb-4">
                <?php foreach($kpis as $k): ?>
                <div class="col-6 col-md-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="text-muted small"><?= $k['label']; ?></span>
                                <i class="<?= $k['icon']; ?> text-<?= $k['class']; ?>"></i>
                            </div>
                            <h3 class="mb-0 fw-semibold"><?= (int)$k['value']; ?></h3>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="row g-3">
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Activité récente</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-0 small">(À implémenter : timeline des dernières actions de l’agent)</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Pipeline</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0 small">
                                <!-- Leads actifs supprimés -->
                                <li><span class="text-muted">Propriétés en cours :</span> <strong><?= (int)($stats['properties_open'] ?? 0); ?></strong></li>
                                <li><span class="text-muted">Transactions ouvertes :</span> <strong><?= (int)($stats['transactions_open'] ?? 0); ?></strong></li>
                                <li><span class="text-muted">Transactions clôturées (30j) :</span> <strong><?= (int)($stats['closed_30d'] ?? 0); ?></strong></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>