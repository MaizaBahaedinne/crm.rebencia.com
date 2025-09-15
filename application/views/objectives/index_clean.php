<?php $this->load->view('includes/header'); ?>

<!-- CSS Moderne et Professionnel -->
<style>
.objectives-container {
    background: #f8fafc;
    min-height: 100vh;
    padding: 2rem 0;
}

.page-header {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
    padding: 2rem;
}

.page-title {
    font-size: 2rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.page-subtitle {
    color: #64748b;
    font-size: 1rem;
}

.stats-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    padding: 1.5rem;
    text-align: center;
    transition: all 0.2s ease;
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
    color: white;
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

.badge-success {
    background: #dcfce7;
    color: #166534;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.875rem;
    font-weight: 500;
}

.badge-warning {
    background: #fef3c7;
    color: #92400e;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.875rem;
    font-weight: 500;
}

.badge-danger {
    background: #fee2e2;
    color: #dc2626;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.875rem;
    font-weight: 500;
}
</style>

<div class="objectives-container">
    <div class="container-fluid">
        
        <!-- En-tête de la page -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="page-title">
                        <i class="ri-target-line me-2"></i>Gestion des Objectifs
                    </h1>
                    <p class="page-subtitle">
                        Définissez et suivez les objectifs de vos agents
                    </p>
                </div>
                <div>
                    <a href="<?php echo base_url('objectives/set_monthly'); ?>" class="btn btn-modern">
                        <i class="ri-add-line me-2"></i>Nouvel Objectif
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number"><?php echo isset($total_objectives) ? $total_objectives : '0'; ?></div>
                    <div class="stats-label">Objectifs Totaux</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number"><?php echo isset($completed_objectives) ? $completed_objectives : '0'; ?></div>
                    <div class="stats-label">Atteints</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number"><?php echo isset($pending_objectives) ? $pending_objectives : '0'; ?></div>
                    <div class="stats-label">En Cours</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number"><?php echo isset($completion_rate) ? $completion_rate : '0'; ?>%</div>
                    <div class="stats-label">Taux de Réussite</div>
                </div>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="row">
            <div class="col-12">
                <div class="content-card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="ri-list-check-2 me-2"></i>Liste des Objectifs
                        </h5>
                    </div>
                    
                    <div class="card-body p-0">
                        <?php if(isset($objectives) && !empty($objectives)): ?>
                            <div class="table-responsive">
                                <table class="table table-modern">
                                    <thead>
                                        <tr>
                                            <th>Agent</th>
                                            <th>Mois</th>
                                            <th>Objectif</th>
                                            <th>Réalisé</th>
                                            <th>Progression</th>
                                            <th>Statut</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($objectives as $objective): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="<?php echo base_url('assets/images/avatar-default.png'); ?>" 
                                                         class="rounded-circle me-3" width="40" height="40" alt="Avatar">
                                                    <div>
                                                        <div class="fw-medium"><?php echo $objective['agent_name']; ?></div>
                                                        <small class="text-muted"><?php echo $objective['agent_email']; ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="fw-medium"><?php echo date('F Y', strtotime($objective['month'])); ?></div>
                                            </td>
                                            <td>
                                                <div class="fw-medium"><?php echo number_format($objective['target_amount']); ?> €</div>
                                            </td>
                                            <td>
                                                <div class="fw-medium text-success"><?php echo number_format($objective['achieved_amount']); ?> €</div>
                                            </td>
                                            <td>
                                                <?php 
                                                $progress = ($objective['achieved_amount'] / $objective['target_amount']) * 100;
                                                $progress = min(100, $progress);
                                                ?>
                                                <div class="d-flex align-items-center">
                                                    <div class="progress me-2" style="width: 100px; height: 8px;">
                                                        <div class="progress-bar bg-success" style="width: <?php echo $progress; ?>%"></div>
                                                    </div>
                                                    <span class="fw-medium"><?php echo number_format($progress, 1); ?>%</span>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if($progress >= 100): ?>
                                                    <span class="badge-success">Atteint</span>
                                                <?php elseif($progress >= 75): ?>
                                                    <span class="badge-warning">En bonne voie</span>
                                                <?php else: ?>
                                                    <span class="badge-danger">À risque</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button class="btn btn-outline-modern btn-sm" onclick="viewObjective(<?php echo $objective['id']; ?>)">
                                                        <i class="ri-eye-line"></i>
                                                    </button>
                                                    <button class="btn btn-outline-modern btn-sm" onclick="editObjective(<?php echo $objective['id']; ?>)">
                                                        <i class="ri-edit-line"></i>
                                                    </button>
                                                    <button class="btn btn-outline-modern btn-sm text-danger" onclick="deleteObjective(<?php echo $objective['id']; ?>)">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="ri-target-line"></i>
                                </div>
                                <h5 class="empty-state-title">Aucun objectif défini</h5>
                                <p class="mb-3">Commencez par définir des objectifs pour vos agents</p>
                                <a href="<?php echo base_url('objectives/set_monthly'); ?>" class="btn btn-modern">
                                    <i class="ri-add-line me-2"></i>Définir des Objectifs
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bouton d'action flottant -->
        <div class="floating-action">
            <button class="fab-modern" onclick="quickActions()" title="Actions rapides">
                <i class="ri-add-line"></i>
            </button>
        </div>

    </div>
</div>

<script>
function viewObjective(id) {
    window.location.href = '<?php echo base_url("objectives/view/"); ?>' + id;
}

function editObjective(id) {
    window.location.href = '<?php echo base_url("objectives/edit/"); ?>' + id;
}

function deleteObjective(id) {
    if(confirm('Êtes-vous sûr de vouloir supprimer cet objectif ?')) {
        window.location.href = '<?php echo base_url("objectives/delete/"); ?>' + id;
    }
}

function quickActions() {
    window.location.href = '<?php echo base_url("objectives/set_monthly"); ?>';
}
</script>

<?php $this->load->view('includes/footer'); ?>
