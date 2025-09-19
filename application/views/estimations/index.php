<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- Header Premium Estimations -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h4 class="mb-2 text-dark fw-bold">
                                <i class="ri-file-list-3-line me-2 text-primary"></i>
                                Estimations Immobilières
                                <?php if ($user_role === 'admin'): ?>
                                    <span class="badge bg-danger ms-2">Admin - Toutes</span>
                                <?php elseif ($user_role === 'manager'): ?>
                                    <span class="badge bg-warning ms-2">Manager - Agence</span>
                                <?php else: ?>
                                    <span class="badge bg-info ms-2">Agent - Mes estimations</span>
                                <?php endif; ?>
                            </h4>
                            <p class="text-muted mb-0">
                                <i class="ri-calendar-line me-1"></i>
                                Gestion et suivi de vos estimations immobilières
                            </p>
                        </div>
                        <div class="page-title-right">
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="ri-add-line me-2"></i>Actions rapides
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="<?= base_url('estimation/add'); ?>">
                                        <i class="ri-file-add-line me-2"></i>Nouvelle estimation
                                    </a></li>
                                    <li><a class="dropdown-item" href="<?= base_url('estimations/export'); ?>">
                                        <i class="ri-download-line me-2"></i>Exporter
                                    </a></li>
                                    <li><a class="dropdown-item" href="<?= base_url('estimations/reports'); ?>">
                                        <i class="ri-bar-chart-line me-2"></i>Rapports
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cartes de Statistiques -->
            <div class="row mb-4">
                <!-- Total des estimations -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100 stats-card" data-color="primary">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-lg flex-shrink-0 me-3">
                                    <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                        <i class="ri-file-list-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-muted text-truncate fs-13 mb-1">Total Estimations</p>
                                    <div class="d-flex align-items-center">
                                        <h4 class="fs-24 fw-semibold ff-secondary mb-0 text-primary counter" data-target="<?= $stats['total']; ?>">0</h4>
                                        <small class="text-success ms-2">
                                            <i class="ri-arrow-up-line me-1"></i>+<?= $stats['recent'] ?? 0; ?> ce mois
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- En attente -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100 stats-card" data-color="warning">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-lg flex-shrink-0 me-3">
                                    <span class="avatar-title bg-warning-subtle text-warning rounded-circle fs-20">
                                        <i class="ri-time-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-muted text-truncate fs-13 mb-1">En Attente</p>
                                    <div class="d-flex align-items-center">
                                        <h4 class="fs-24 fw-semibold ff-secondary mb-0 text-warning counter" data-target="<?= $stats['en_attente']; ?>">0</h4>
                                        <small class="text-info ms-2">
                                            <i class="ri-time-line me-1"></i>À traiter
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Validées -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100 stats-card" data-color="success">
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
                                        <h4 class="fs-24 fw-semibold ff-secondary mb-0 text-success counter" data-target="<?= $stats['validees']; ?>">0</h4>
                                        <small class="text-success ms-2">
                                            <i class="ri-check-line me-1"></i>Terminées
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Valeur totale -->
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
                                    <p class="text-uppercase fw-medium text-muted text-truncate fs-13 mb-1">Valeur Totale</p>
                                    <div class="d-flex align-items-center">
                                        <h4 class="fs-18 fw-semibold ff-secondary mb-0 text-info"><?= number_format($stats['montant_total'], 0, ',', ' ') ?> TND</h4>
                                        <small class="text-primary ms-2">
                                            <i class="ri-funds-line me-1"></i>Estimé
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtres et recherche -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-gradient-primary text-white">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h5 class="card-title mb-0">
                                        <i class="ri-filter-line me-2"></i>Filtres et Recherche
                                    </h5>
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-light btn-sm" id="resetFilters">
                                        <i class="ri-refresh-line me-1"></i>Réinitialiser
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Recherche</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="searchInput" placeholder="Rechercher...">
                                        <span class="input-group-text">
                                            <i class="ri-search-line"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Statut</label>
                                    <select class="form-select" id="statusFilter">
                                        <option value="">Tous les statuts</option>
                                        <option value="en_attente">En attente</option>
                                        <option value="validee">Validée</option>
                                        <option value="refusee">Refusée</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Type</label>
                                    <select class="form-select" id="typeFilter">
                                        <option value="">Tous les types</option>
                                        <option value="appartement">Appartement</option>
                                        <option value="villa">Villa</option>
                                        <option value="terrain">Terrain</option>
                                        <option value="bureau">Bureau</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Gouvernorat</label>
                                    <select class="form-select" id="regionFilter">
                                        <option value="">Toutes les régions</option>
                                        <option value="tunis">Tunis</option>
                                        <option value="ariana">Ariana</option>
                                        <option value="ben_arous">Ben Arous</option>
                                        <option value="manouba">Manouba</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Période</label>
                                    <select class="form-select" id="periodFilter">
                                        <option value="">Toutes les périodes</option>
                                        <option value="today">Aujourd'hui</option>
                                        <option value="week">Cette semaine</option>
                                        <option value="month">Ce mois</option>
                                        <option value="quarter">Ce trimestre</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table des estimations -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="ri-list-check-2 me-2"></i>
                                Liste des Estimations 
                                <span class="badge bg-primary ms-2"><?= count($estimations) ?> résultats</span>
                            </h5>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary btn-sm">
                                    <i class="ri-grid-line me-1"></i>Grille
                                </button>
                                <button type="button" class="btn btn-primary btn-sm">
                                    <i class="ri-list-unordered me-1"></i>Liste
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" id="estimationsTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-start fw-semibold">
                                                <div class="d-flex align-items-center">
                                                    <input type="checkbox" id="selectAll" class="form-check-input me-2">
                                                    Propriété
                                                </div>
                                            </th>
                                            <th class="text-center fw-semibold">Type</th>
                                            <th class="text-center fw-semibold">Valeur</th>
                                            <th class="text-center fw-semibold">Localisation</th>
                                            <th class="text-center fw-semibold">Agent</th>
                                            <th class="text-center fw-semibold">Date</th>
                                            <th class="text-center fw-semibold">Statut</th>
                                            <th class="text-center fw-semibold">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($estimations)): ?>
                                            <?php foreach ($estimations as $estimation): ?>
                                                <tr class="estimation-row">
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <input type="checkbox" class="form-check-input me-3" value="<?= $estimation['id'] ?>">
                                                            <div class="flex-grow-1">
                                                                <h6 class="mb-1 fw-semibold text-truncate">
                                                                    <?= character_limiter(($estimation['titre'] ?? 'Propriété sans titre'), 40) ?>
                                                                </h6>
                                                                <small class="text-muted">
                                                                    <i class="ri-map-pin-line me-1"></i>
                                                                    <?= $estimation['adresse'] ?? 'Adresse non renseignée' ?>
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php 
                                                        $type_badges = [
                                                            'appartement' => 'bg-primary',
                                                            'villa' => 'bg-success',
                                                            'terrain' => 'bg-warning',
                                                            'bureau' => 'bg-info'
                                                        ];
                                                        $badge_class = $type_badges[$estimation['type_propriete']] ?? 'bg-secondary';
                                                        ?>
                                                        <span class="badge <?= $badge_class ?> text-uppercase fs-12">
                                                            <?= ucfirst($estimation['type_propriete'] ?? 'N/A') ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <div>
                                                            <span class="fw-bold text-success fs-14">
                                                                <?= number_format($estimation['valeur_estimee'] ?? 0, 0, ',', ' ') ?> TND
                                                            </span>
                                                            <br>
                                                            <small class="text-muted">
                                                                <?= $estimation['surface'] ?? 0 ?> m²
                                                            </small>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <div>
                                                            <span class="fw-medium"><?= $estimation['gouvernorat'] ?? 'N/A' ?></span>
                                                            <br>
                                                            <small class="text-muted"><?= $estimation['ville'] ?? '' ?></small>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="d-flex align-items-center justify-content-center">
                                                            <div class="avatar-xs me-2">
                                                                <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                                                    <?= substr($estimation['agent_nom'] ?? 'A', 0, 1) ?>
                                                                </div>
                                                            </div>
                                                            <div class="text-start">
                                                                <span class="fw-medium fs-13"><?= $estimation['agent_nom'] ?? 'N/A' ?></span>
                                                                <br>
                                                                <small class="text-muted"><?= $estimation['agence_nom'] ?? '' ?></small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <div>
                                                            <span class="fw-medium"><?= date('d/m/Y', strtotime($estimation['date_creation'] ?? date('Y-m-d'))) ?></span>
                                                            <br>
                                                            <small class="text-muted"><?= date('H:i', strtotime($estimation['date_creation'] ?? date('Y-m-d H:i:s'))) ?></small>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php 
                                                        $status_classes = [
                                                            'en_attente' => 'bg-warning',
                                                            'validee' => 'bg-success',
                                                            'refusee' => 'bg-danger'
                                                        ];
                                                        $status_icons = [
                                                            'en_attente' => 'ri-time-line',
                                                            'validee' => 'ri-check-line',
                                                            'refusee' => 'ri-close-line'
                                                        ];
                                                        $status = $estimation['statut'] ?? 'en_attente';
                                                        ?>
                                                        <span class="badge <?= $status_classes[$status] ?? 'bg-secondary' ?> d-inline-flex align-items-center">
                                                            <i class="<?= $status_icons[$status] ?? 'ri-question-line' ?> me-1"></i>
                                                            <?= ucfirst(str_replace('_', ' ', $status)) ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="dropdown">
                                                            <button class="btn btn-light btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                                                <i class="ri-more-2-fill"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <a class="dropdown-item" href="<?= base_url('estimation/view/' . $estimation['id']) ?>">
                                                                        <i class="ri-eye-line me-2"></i>Voir détails
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item" href="<?= base_url('estimation/edit/' . $estimation['id']) ?>">
                                                                        <i class="ri-edit-line me-2"></i>Modifier
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item text-primary" href="<?= base_url('estimation/print/' . $estimation['id']) ?>">
                                                                        <i class="ri-printer-line me-2"></i>Imprimer rapport
                                                                    </a>
                                                                </li>
                                                                <li><hr class="dropdown-divider"></li>
                                                                <li>
                                                                    <a class="dropdown-item text-danger" href="#" onclick="deleteEstimation(<?= $estimation['id'] ?>)">
                                                                        <i class="ri-delete-bin-line me-2"></i>Supprimer
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="8" class="text-center py-5">
                                                    <div class="py-4">
                                                        <div class="mb-3">
                                                            <i class="ri-file-list-line text-muted" style="font-size: 3rem;"></i>
                                                        </div>
                                                        <h5 class="text-muted">Aucune estimation trouvée</h5>
                                                        <p class="text-muted mb-3">
                                                            <?php if ($user_role === 'admin'): ?>
                                                                Aucune estimation n'a été créée dans le système.
                                                            <?php elseif ($user_role === 'manager'): ?>
                                                                Aucune estimation n'a été créée pour votre agence.
                                                            <?php else: ?>
                                                                Vous n'avez créé aucune estimation pour le moment.
                                                            <?php endif; ?>
                                                        </p>
                                                        <a href="<?= base_url('estimation/add') ?>" class="btn btn-primary">
                                                            <i class="ri-add-line me-2"></i>Créer une estimation
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <?php if (!empty($estimations)): ?>
                        <!-- Pagination -->
                        <div class="card-footer border-top-dashed">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted">
                                        Affichage de <strong>1</strong> à <strong><?= count($estimations) ?></strong> 
                                        sur <strong><?= count($estimations) ?></strong> résultats
                                    </span>
                                </div>
                                <nav>
                                    <ul class="pagination mb-0">
                                        <li class="page-item disabled">
                                            <span class="page-link">Précédent</span>
                                        </li>
                                        <li class="page-item active">
                                            <span class="page-link">1</span>
                                        </li>
                                        <li class="page-item disabled">
                                            <span class="page-link">Suivant</span>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS personnalisé pour les animations -->
<style>
.stats-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.75rem 1.5rem rgba(18, 38, 63, 0.15) !important;
}

.estimation-row {
    transition: all 0.2s ease;
}

.estimation-row:hover {
    background-color: rgba(var(--bs-primary-rgb), 0.05);
}

.counter {
    animation: countUp 1s ease-out;
}

@keyframes countUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.table th {
    background-color: #f8f9fa;
    border: none;
    padding: 1rem 0.75rem;
    color: #495057;
    font-weight: 600;
}

.avatar-xs {
    width: 2rem;
    height: 2rem;
}

.avatar-lg {
    width: 4rem;
    height: 4rem;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #5156be 0%, #6366f1 100%);
}
</style>

<!-- JavaScript pour les interactions -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation des compteurs
    const counters = document.querySelectorAll('.counter');
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-target'));
        let current = 0;
        const increment = target / 50;
        
        const updateCounter = () => {
            if (current < target) {
                current += increment;
                counter.textContent = Math.floor(current);
                setTimeout(updateCounter, 30);
            } else {
                counter.textContent = target;
            }
        };
        
        // Démarrer l'animation après un délai
        setTimeout(updateCounter, 500);
    });
    
    // Filtres en temps réel
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const typeFilter = document.getElementById('typeFilter');
    const regionFilter = document.getElementById('regionFilter');
    const periodFilter = document.getElementById('periodFilter');
    const resetBtn = document.getElementById('resetFilters');
    
    function filterTable() {
        const rows = document.querySelectorAll('.estimation-row');
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;
        const typeValue = typeFilter.value;
        const regionValue = regionFilter.value;
        
        rows.forEach(row => {
            let visible = true;
            
            // Recherche textuelle
            if (searchTerm && !row.textContent.toLowerCase().includes(searchTerm)) {
                visible = false;
            }
            
            // Filtrer par statut
            if (statusValue) {
                const statusBadge = row.querySelector('td:nth-child(7) .badge');
                if (!statusBadge || !statusBadge.textContent.toLowerCase().includes(statusValue.replace('_', ' '))) {
                    visible = false;
                }
            }
            
            // Filtrer par type
            if (typeValue) {
                const typeBadge = row.querySelector('td:nth-child(2) .badge');
                if (!typeBadge || !typeBadge.textContent.toLowerCase().includes(typeValue)) {
                    visible = false;
                }
            }
            
            row.style.display = visible ? '' : 'none';
        });
    }
    
    // Événements de filtrage
    [searchInput, statusFilter, typeFilter, regionFilter, periodFilter].forEach(element => {
        element.addEventListener('input', filterTable);
        element.addEventListener('change', filterTable);
    });
    
    // Réinitialiser les filtres
    resetBtn.addEventListener('click', function() {
        [searchInput, statusFilter, typeFilter, regionFilter, periodFilter].forEach(element => {
            element.value = '';
        });
        filterTable();
    });
    
    // Sélection multiple
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
    
    selectAll.addEventListener('change', function() {
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checkedCount = document.querySelectorAll('tbody input[type="checkbox"]:checked').length;
            selectAll.checked = checkedCount === checkboxes.length;
            selectAll.indeterminate = checkedCount > 0 && checkedCount < checkboxes.length;
        });
    });
});

// Fonction pour supprimer une estimation
function deleteEstimation(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette estimation ?')) {
        // AJAX call pour supprimer
        fetch('<?= base_url("estimations/delete/") ?>' + id, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(response => {
            if (response.ok) {
                location.reload();
            } else {
                alert('Erreur lors de la suppression');
            }
        });
    }
}
</script>
