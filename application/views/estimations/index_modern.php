<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="content-wrapper">
    <!-- En-tête de la page -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="page-title mb-0">
                    <i class="ri-file-list-3-line me-2 text-primary"></i>
                    Estimations
                </h1>
                <p class="text-muted mb-0">Gestion des estimations immobilières</p>
            </div>
            <div class="col-md-6 text-end">
                <button class="btn btn-primary btn-lg">
                    <i class="ri-add-line me-2"></i>
                    Nouvelle estimation
                </button>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-lg flex-shrink-0 me-3">
                            <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                <i class="ri-file-list-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted text-truncate fs-13 mb-1">Total</p>
                            <div class="d-flex align-items-center">
                                <h4 class="fs-24 fw-semibold ff-secondary mb-0 text-primary"><?= $stats['total'] ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-lg flex-shrink-0 me-3">
                            <span class="avatar-title bg-warning-subtle text-warning rounded-circle fs-20">
                                <i class="ri-time-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted text-truncate fs-13 mb-1">En attente</p>
                            <div class="d-flex align-items-center">
                                <h4 class="fs-24 fw-semibold ff-secondary mb-0 text-warning"><?= $stats['en_attente'] ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-lg flex-shrink-0 me-3">
                            <span class="avatar-title bg-success-subtle text-success rounded-circle fs-20">
                                <i class="ri-check-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted text-truncate fs-13 mb-1">Validées</p>
                            <div class="d-flex align-items-center">
                                <h4 class="fs-24 fw-semibold ff-secondary mb-0 text-success"><?= $stats['validees'] ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-lg flex-shrink-0 me-3">
                            <span class="avatar-title bg-info-subtle text-info rounded-circle fs-20">
                                <i class="ri-money-euro-circle-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted text-truncate fs-13 mb-1">Valeur totale</p>
                            <div class="d-flex align-items-center">
                                <h4 class="fs-18 fw-semibold ff-secondary mb-0 text-info"><?= number_format($stats['montant_total'], 0, ',', ' ') ?> TND</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="card-title mb-0">
                <i class="ri-filter-3-line me-2"></i>
                Filtres de recherche
            </h5>
        </div>
        <div class="card-body">
            <form id="filters-form" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Statut</label>
                    <select name="statut" class="form-select">
                        <option value="">Tous les statuts</option>
                        <option value="en_attente">En attente</option>
                        <option value="en_cours">En cours</option>
                        <option value="valide">Validé</option>
                        <option value="rejete">Rejeté</option>
                    </select>
                </div>
                
                <?php if ($user_info['role'] === 'admin'): ?>
                <div class="col-md-3">
                    <label class="form-label">Agence</label>
                    <select name="agency_id" class="form-select">
                        <option value="">Toutes les agences</option>
                        <?php if (isset($filters['agencies'])): ?>
                            <?php foreach ($filters['agencies'] as $agency): ?>
                                <option value="<?= $agency['id'] ?>"><?= $agency['post_title'] ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <?php endif; ?>
                
                <div class="col-md-3">
                    <label class="form-label">Agent</label>
                    <select name="agent_id" class="form-select">
                        <option value="">Tous les agents</option>
                        <?php if (isset($filters['agents'])): ?>
                            <?php foreach ($filters['agents'] as $agent): ?>
                                <option value="<?= $agent['ID'] ?>"><?= $agent['display_name'] ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Période</label>
                    <select name="periode" class="form-select">
                        <option value="">Toute période</option>
                        <option value="today">Aujourd'hui</option>
                        <option value="week">Cette semaine</option>
                        <option value="month">Ce mois</option>
                        <option value="quarter">Ce trimestre</option>
                        <option value="year">Cette année</option>
                    </select>
                </div>
                
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-search-line me-2"></i>
                        Filtrer
                    </button>
                    <button type="reset" class="btn btn-outline-secondary ms-2">
                        <i class="ri-refresh-line me-2"></i>
                        Réinitialiser
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des estimations -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="card-title mb-0">
                        <i class="ri-list-check-2 me-2"></i>
                        Liste des estimations
                    </h5>
                </div>
                <div class="col-auto">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-primary btn-sm active" data-view="table">
                            <i class="ri-table-line"></i>
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm" data-view="grid">
                            <i class="ri-grid-line"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="estimations-table">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Type de bien</th>
                            <th>Localisation</th>
                            <th>Surface</th>
                            <th>Valeur estimée</th>
                            <th>Agent</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="estimations-body">
                        <?php if (!empty($estimations)): ?>
                            <?php foreach ($estimations as $estimation): ?>
                            <tr>
                                <td>
                                    <span class="fw-medium">#<?= str_pad($estimation['id'], 4, '0', STR_PAD_LEFT) ?></span>
                                </td>
                                <td>
                                    <small class="text-muted"><?= date('d/m/Y', strtotime($estimation['created_at'])) ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark"><?= $estimation['type_bien'] ?: 'Non défini' ?></span>
                                </td>
                                <td>
                                    <div class="text-truncate" style="max-width: 150px;">
                                        <?= ($estimation['adresse_ville'] ?? '') . ($estimation['adresse_cp'] ? ' (' . $estimation['adresse_cp'] . ')' : '') ?>
                                    </div>
                                </td>
                                <td>
                                    <?= $estimation['surface_habitable'] ? number_format($estimation['surface_habitable'], 0) . ' m²' : '-' ?>
                                </td>
                                <td>
                                    <?php if ($estimation['valeur_estimee']): ?>
                                        <span class="fw-semibold text-success"><?= number_format($estimation['valeur_estimee'], 0, ',', ' ') ?> TND</span>
                                    <?php else: ?>
                                        <span class="text-muted">En cours</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="text-primary"><?= $estimation['agent_name'] ?? 'Non assigné' ?></span>
                                </td>
                                <td>
                                    <?php
                                    $statut = $estimation['statut_dossier'] ?: 'en_attente';
                                    $badge_class = [
                                        'en_attente' => 'bg-warning',
                                        'en_cours' => 'bg-info',
                                        'valide' => 'bg-success',
                                        'rejete' => 'bg-danger'
                                    ];
                                    $statut_labels = [
                                        'en_attente' => 'En attente',
                                        'en_cours' => 'En cours',
                                        'valide' => 'Validé',
                                        'rejete' => 'Rejeté'
                                    ];
                                    ?>
                                    <span class="badge <?= $badge_class[$statut] ?? 'bg-secondary' ?>">
                                        <?= $statut_labels[$statut] ?? 'Inconnu' ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?= base_url('estimations/view/' . $estimation['id']) ?>" 
                                           class="btn btn-sm btn-outline-primary" title="Voir détails">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <a href="<?= base_url('estimations/edit/' . $estimation['id']) ?>" 
                                           class="btn btn-sm btn-outline-warning" title="Modifier">
                                            <i class="ri-edit-line"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                onclick="deleteEstimation(<?= $estimation['id'] ?>)" title="Supprimer">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i class="ri-file-list-line fs-1 text-muted"></i>
                                    <p class="text-muted mt-2">Aucune estimation trouvée</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.page-header {
    padding: 1.5rem 0;
    border-bottom: 1px solid #eee;
}

.page-title {
    font-size: 1.75rem;
    font-weight: 600;
    color: #2d3748;
}

.avatar-title {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.table-hover tbody tr:hover {
    background-color: #f8f9fa;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
}

.btn-group .btn {
    border-radius: 0;
}

.btn-group .btn:first-child {
    border-top-left-radius: 0.375rem;
    border-bottom-left-radius: 0.375rem;
}

.btn-group .btn:last-child {
    border-top-right-radius: 0.375rem;
    border-bottom-right-radius: 0.375rem;
}

.bg-primary-subtle { background-color: rgba(13, 110, 253, 0.1) !important; }
.bg-warning-subtle { background-color: rgba(255, 193, 7, 0.1) !important; }
.bg-success-subtle { background-color: rgba(25, 135, 84, 0.1) !important; }
.bg-info-subtle { background-color: rgba(13, 202, 240, 0.1) !important; }
</style>

<script>
$(document).ready(function() {
    // Gestion du formulaire de filtres
    $('#filters-form').on('submit', function(e) {
        e.preventDefault();
        applyFilters();
    });

    // Gestion du reset des filtres
    $('#filters-form').on('reset', function() {
        setTimeout(function() {
            applyFilters();
        }, 100);
    });
});

function applyFilters() {
    const formData = $('#filters-form').serialize();
    
    $.ajax({
        url: '<?= base_url("estimations/filter") ?>',
        type: 'POST',
        data: formData,
        success: function(response) {
            $('#estimations-body').html(response.html);
        },
        error: function() {
            console.error('Erreur lors de l\'application des filtres');
        }
    });
}

function deleteEstimation(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette estimation ?')) {
        window.location.href = '<?= base_url("estimations/delete/") ?>' + id;
    }
}
</script>
