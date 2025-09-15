<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- Titre de la page -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Historique des Commissions</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="<?php echo base_url('commission/settings'); ?>">Commissions</a></li>
                                <li class="breadcrumb-item active">Historique</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Messages Flash -->
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ri-check-line me-2"></i><?php echo $this->session->flashdata('success'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="ri-error-warning-line me-2"></i><?php echo $this->session->flashdata('error'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Filtres -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ri-filter-line me-2"></i>Filtres de Recherche
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="<?php echo base_url('commission/history'); ?>" method="get" id="filterForm">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="agent_id" class="form-label">Agent</label>
                                        <select class="form-select" id="agent_id" name="agent_id">
                                            <option value="">Tous les agents</option>
                                            <?php if (isset($agents) && !empty($agents)): ?>
                                                <?php foreach ($agents as $agent): ?>
                                                    <option value="<?php echo $agent->ID; ?>" 
                                                            <?php echo (isset($selected_agent) && $selected_agent == $agent->ID) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($agent->display_name); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="month" class="form-label">Mois</label>
                                        <input type="month" class="form-control" id="month" name="month" 
                                               value="<?php echo $this->input->get('month'); ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="status" class="form-label">Statut</label>
                                        <select class="form-select" id="status" name="status">
                                            <option value="">Tous les statuts</option>
                                            <option value="pending" <?php echo ($this->input->get('status') == 'pending') ? 'selected' : ''; ?>>En attente</option>
                                            <option value="paid" <?php echo ($this->input->get('status') == 'paid') ? 'selected' : ''; ?>>Payée</option>
                                            <option value="cancelled" <?php echo ($this->input->get('status') == 'cancelled') ? 'selected' : ''; ?>>Annulée</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary me-2">
                                            <i class="ri-search-line me-1"></i>Filtrer
                                        </button>
                                        <a href="<?php echo base_url('commission/history'); ?>" class="btn btn-outline-secondary">
                                            <i class="ri-refresh-line"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Résultats -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="ri-history-line me-2"></i>Historique des Commissions
                            </h5>
                            <?php if (isset($commissions) && !empty($commissions)): ?>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-outline-success btn-sm" onclick="exportToExcel()">
                                        <i class="ri-file-excel-line me-1"></i>Exporter Excel
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm" onclick="exportToPDF()">
                                        <i class="ri-file-pdf-line me-1"></i>Exporter PDF
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <?php if (isset($commissions) && !empty($commissions)): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle" id="commissionsTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Date</th>
                                                <th>Agent</th>
                                                <th>Type</th>
                                                <th>Propriété</th>
                                                <th>Montant Transaction</th>
                                                <th>Commission Agent</th>
                                                <th>Commission Agence</th>
                                                <th>Total Commission</th>
                                                <th>Statut</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $total_agent_commission = 0;
                                            $total_agency_commission = 0;
                                            $total_commission = 0;
                                            ?>
                                            <?php foreach ($commissions as $commission): ?>
                                                <?php 
                                                $total_agent_commission += $commission->agent_commission;
                                                $total_agency_commission += $commission->agency_commission;
                                                $total_commission += ($commission->agent_commission + $commission->agency_commission);
                                                ?>
                                                <tr>
                                                    <td>
                                                        <span class="fw-medium">
                                                            <?php echo date('d/m/Y', strtotime($commission->created_at)); ?>
                                                        </span>
                                                        <br>
                                                        <small class="text-muted">
                                                            <?php echo date('H:i', strtotime($commission->created_at)); ?>
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-xs me-2">
                                                                <div class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                                    <?php echo strtoupper(substr($commission->agent_name, 0, 1)); ?>
                                                                </div>
                                                            </div>
                                                            <span class="fw-medium"><?php echo htmlspecialchars($commission->agent_name); ?></span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-<?php echo ($commission->transaction_type == 'sale') ? 'success' : 'info'; ?>">
                                                            <?php echo ($commission->transaction_type == 'sale') ? 'Vente' : 'Location'; ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?php if (isset($commission->property_title) && !empty($commission->property_title)): ?>
                                                            <span class="fw-medium"><?php echo htmlspecialchars($commission->property_title); ?></span>
                                                            <?php if (isset($commission->property_reference)): ?>
                                                                <br><small class="text-muted">Réf: <?php echo htmlspecialchars($commission->property_reference); ?></small>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <span class="text-muted">Non spécifiée</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <span class="fw-bold text-primary">
                                                            <?php echo number_format($commission->transaction_amount, 2, ',', ' '); ?> €
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="text-success fw-medium">
                                                            <?php echo number_format($commission->agent_commission, 2, ',', ' '); ?> €
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="text-warning fw-medium">
                                                            <?php echo number_format($commission->agency_commission, 2, ',', ' '); ?> €
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="fw-bold">
                                                            <?php echo number_format($commission->agent_commission + $commission->agency_commission, 2, ',', ' '); ?> €
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $status_class = '';
                                                        $status_text = '';
                                                        switch ($commission->status) {
                                                            case 'pending':
                                                                $status_class = 'warning';
                                                                $status_text = 'En attente';
                                                                break;
                                                            case 'paid':
                                                                $status_class = 'success';
                                                                $status_text = 'Payée';
                                                                break;
                                                            case 'cancelled':
                                                                $status_class = 'danger';
                                                                $status_text = 'Annulée';
                                                                break;
                                                            default:
                                                                $status_class = 'secondary';
                                                                $status_text = 'Inconnu';
                                                        }
                                                        ?>
                                                        <span class="badge bg-<?php echo $status_class; ?>">
                                                            <?php echo $status_text; ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn btn-ghost-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                                <i class="ri-more-2-fill"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <a class="dropdown-item" href="#" onclick="viewCommissionDetail(<?php echo $commission->id; ?>)">
                                                                        <i class="ri-eye-line me-2"></i>Voir détails
                                                                    </a>
                                                                </li>
                                                                <?php if ($commission->status == 'pending'): ?>
                                                                    <li>
                                                                        <a class="dropdown-item text-success" href="#" onclick="markAsPaid(<?php echo $commission->id; ?>)">
                                                                            <i class="ri-check-line me-2"></i>Marquer comme payée
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="dropdown-item text-danger" href="#" onclick="cancelCommission(<?php echo $commission->id; ?>)">
                                                                            <i class="ri-close-line me-2"></i>Annuler
                                                                        </a>
                                                                    </li>
                                                                <?php endif; ?>
                                                                <li><hr class="dropdown-divider"></li>
                                                                <li>
                                                                    <a class="dropdown-item" href="#" onclick="generateCommissionReport(<?php echo $commission->id; ?>)">
                                                                        <i class="ri-file-text-line me-2"></i>Générer rapport
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot class="table-light">
                                            <tr>
                                                <th colspan="5" class="text-end">Totaux:</th>
                                                <th class="text-success">
                                                    <?php echo number_format($total_agent_commission, 2, ',', ' '); ?> €
                                                </th>
                                                <th class="text-warning">
                                                    <?php echo number_format($total_agency_commission, 2, ',', ' '); ?> €
                                                </th>
                                                <th class="fw-bold">
                                                    <?php echo number_format($total_commission, 2, ',', ' '); ?> €
                                                </th>
                                                <th colspan="2"></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            <?php elseif (isset($selected_agent) && !empty($selected_agent)): ?>
                                <div class="text-center py-5">
                                    <div class="avatar-lg mx-auto mb-4">
                                        <div class="avatar-title bg-soft-info text-info rounded-circle">
                                            <i class="ri-search-line fs-24"></i>
                                        </div>
                                    </div>
                                    <h5 class="text-muted">Aucune commission trouvée</h5>
                                    <p class="text-muted mb-0">
                                        Aucune commission ne correspond aux critères de recherche sélectionnés.
                                    </p>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-5">
                                    <div class="avatar-lg mx-auto mb-4">
                                        <div class="avatar-title bg-soft-primary text-primary rounded-circle">
                                            <i class="ri-user-search-line fs-24"></i>
                                        </div>
                                    </div>
                                    <h5 class="text-muted">Sélectionnez un agent</h5>
                                    <p class="text-muted mb-0">
                                        Veuillez sélectionner un agent pour afficher son historique de commissions.
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal de détails de commission -->
<div class="modal fade" id="commissionDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Détails de la Commission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="commissionDetailContent">
                <!-- Contenu chargé dynamiquement -->
            </div>
        </div>
    </div>
</div>

<script>
// Fonction pour changer automatiquement d'agent
document.getElementById('agent_id').addEventListener('change', function() {
    if (this.value) {
        // Construire l'URL avec les paramètres existants
        let url = '<?php echo base_url("commission/history/"); ?>' + this.value;
        let params = new URLSearchParams();
        
        // Ajouter les autres paramètres s'ils existent
        const month = document.getElementById('month').value;
        const status = document.getElementById('status').value;
        
        if (month) params.append('month', month);
        if (status) params.append('status', status);
        
        if (params.toString()) {
            url += '?' + params.toString();
        }
        
        window.location.href = url;
    } else {
        window.location.href = '<?php echo base_url("commission/history"); ?>';
    }
});

// Fonction pour voir les détails d'une commission
function viewCommissionDetail(commissionId) {
    // Charger les détails via AJAX
    fetch('<?php echo base_url("commission/get_commission_detail/"); ?>' + commissionId)
        .then(response => response.text())
        .then(data => {
            document.getElementById('commissionDetailContent').innerHTML = data;
            new bootstrap.Modal(document.getElementById('commissionDetailModal')).show();
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors du chargement des détails');
        });
}

// Fonction pour marquer comme payée
function markAsPaid(commissionId) {
    if (confirm('Êtes-vous sûr de vouloir marquer cette commission comme payée ?')) {
        fetch('<?php echo base_url("commission/mark_as_paid/"); ?>' + commissionId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
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
            alert('Erreur lors de la mise à jour');
        });
    }
}

// Fonction pour annuler une commission
function cancelCommission(commissionId) {
    if (confirm('Êtes-vous sûr de vouloir annuler cette commission ?')) {
        fetch('<?php echo base_url("commission/cancel_commission/"); ?>' + commissionId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
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
            alert('Erreur lors de l\'annulation');
        });
    }
}

// Fonction pour générer un rapport de commission
function generateCommissionReport(commissionId) {
    window.open('<?php echo base_url("commission/generate_report/"); ?>' + commissionId, '_blank');
}

// Fonction pour exporter en Excel
function exportToExcel() {
    const params = new URLSearchParams(window.location.search);
    params.append('export', 'excel');
    window.location.href = '<?php echo current_url(); ?>?' + params.toString();
}

// Fonction pour exporter en PDF
function exportToPDF() {
    const params = new URLSearchParams(window.location.search);
    params.append('export', 'pdf');
    window.open('<?php echo current_url(); ?>?' + params.toString(), '_blank');
}

// Initialiser DataTable si disponible
document.addEventListener('DOMContentLoaded', function() {
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
        $('#commissionsTable').DataTable({
            responsive: true,
            pageLength: 25,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json'
            },
            order: [[0, 'desc']], // Trier par date décroissante
            columnDefs: [
                { orderable: false, targets: [9] } // Désactiver le tri sur la colonne actions
            ]
        });
    }
});
</script>
