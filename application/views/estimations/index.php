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

            <!-- Barre de contrôles -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body py-3">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-primary" id="gridViewBtn">
                                                <i class="ri-grid-line me-1"></i>Grille
                                            </button>
                                            <button type="button" class="btn btn-outline-primary" id="listViewBtn">
                                                <i class="ri-list-unordered me-1"></i>Liste
                                            </button>
                                        </div>
                                        <div class="input-group" style="width: 300px;">
                                            <input type="text" class="form-control" id="searchInput" placeholder="Rechercher une estimation...">
                                            <span class="input-group-text">
                                                <i class="ri-search-line"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 text-end">
                                    <div class="d-flex align-items-center justify-content-end gap-2">
                                        <select class="form-select form-select-sm" id="statusFilter" style="width: auto;">
                                            <option value="">Tous les statuts</option>
                                            <option value="en_attente">En attente</option>
                                            <option value="validee">Validée</option>
                                            <option value="refusee">Refusée</option>
                                        </select>
                                        <select class="form-select form-select-sm" id="typeFilter" style="width: auto;">
                                            <option value="">Tous les types</option>
                                            <option value="appartement">Appartement</option>
                                            <option value="villa">Villa</option>
                                            <option value="terrain">Terrain</option>
                                            <option value="bureau">Bureau</option>
                                        </select>
                                        <button class="btn btn-outline-secondary btn-sm" id="toggleMapBtn">
                                            <i class="ri-map-2-line me-1"></i>Carte
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vue principale avec carte et grille -->
            <div class="row">
                <!-- Carte interactive (visible par défaut) -->
                <div class="col-lg-6 mb-4" id="mapContainer">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="ri-map-2-line me-2 text-primary"></i>
                                Localisation des Estimations
                            </h5>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary" id="centerMapBtn">
                                    <i class="ri-focus-3-line"></i>
                                </button>
                                <button class="btn btn-outline-primary" id="fullscreenMapBtn">
                                    <i class="ri-fullscreen-line"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div id="estimationsMap" style="height: 600px; width: 100%;"></div>
                        </div>
                    </div>
                </div>

                <!-- Grille des estimations -->
                <div class="col-lg-6" id="gridContainer">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="ri-list-check-2 me-2"></i>
                                Estimations 
                                <span class="badge bg-primary ms-2" id="resultsCount"><?= count($estimations) ?> résultats</span>
                            </h5>
                        </div>
                        <div class="card-body p-3">
                            <!-- Vue Grille -->
                            <div id="gridView" class="row g-3">
                                <?php if (!empty($estimations)): ?>
                                    <?php foreach ($estimations as $estimation): ?>
                                        <div class="col-12 estimation-card" data-estimation='<?= json_encode($estimation) ?>'>
                                            <div class="card border h-100 estimation-item">
                                                <div class="card-body p-3">
                                                    <div class="row align-items-center">
                                                        <!-- Info principale -->
                                                        <div class="col-md-5">
                                                            <div class="d-flex justify-content-between align-items-start mb-2">
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
                                                            </div>

                                                            <h6 class="card-title mb-1 fw-semibold">
                                                                <?= character_limiter(($estimation['titre'] ?? 'Propriété sans titre'), 40) ?>
                                                            </h6>
                                                            <p class="text-muted mb-2 fs-13">
                                                                <i class="ri-map-pin-line me-1"></i>
                                                                <?= character_limiter(($estimation['adresse'] ?? 'Adresse non renseignée'), 50) ?>
                                                            </p>
                                                        </div>

                                                        <!-- Prix et surface -->
                                                        <div class="col-md-3">
                                                            <div class="text-center">
                                                                <h5 class="text-success mb-1 fw-bold">
                                                                    <?= number_format($estimation['valeur_estimee'] ?? 0, 0, ',', ' ') ?>
                                                                </h5>
                                                                <small class="text-muted">TND</small>
                                                                <div class="mt-1">
                                                                    <span class="text-primary fw-medium">
                                                                        <?= $estimation['surface'] ?? 0 ?> m²
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Agent et date -->
                                                        <div class="col-md-2">
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-xs me-2">
                                                                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-12">
                                                                        <?= substr($estimation['agent_nom'] ?? 'A', 0, 1) ?>
                                                                    </div>
                                                                </div>
                                                                <div>
                                                                    <small class="fw-medium text-dark d-block"><?= $estimation['agent_nom'] ?? 'N/A' ?></small>
                                                                    <small class="text-muted"><?= date('d/m/Y', strtotime($estimation['date_creation'] ?? date('Y-m-d'))) ?></small>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Actions -->
                                                        <div class="col-md-2">
                                                            <div class="d-flex gap-1 justify-content-end">
                                                                <button class="btn btn-primary btn-sm" onclick="viewEstimation(<?= $estimation['id'] ?>)" title="Voir détails">
                                                                    <i class="ri-eye-line"></i>
                                                                </button>
                                                                <button class="btn btn-outline-primary btn-sm" onclick="showOnMap(<?= $estimation['id'] ?>)" title="Voir sur carte">
                                                                    <i class="ri-map-pin-line"></i>
                                                                </button>
                                                                <div class="dropdown">
                                                                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown" title="Plus d'actions">
                                                                        <i class="ri-more-2-fill"></i>
                                                                    </button>
                                                                    <ul class="dropdown-menu">
                                                                        <li><a class="dropdown-item" href="<?= base_url('estimation/edit/' . $estimation['id']) ?>">
                                                                            <i class="ri-edit-line me-2"></i>Modifier</a></li>
                                                                        <li><a class="dropdown-item text-primary" href="<?= base_url('estimation/print/' . $estimation['id']) ?>">
                                                                            <i class="ri-printer-line me-2"></i>Imprimer</a></li>
                                                                        <li><hr class="dropdown-divider"></li>
                                                                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteEstimation(<?= $estimation['id'] ?>)">
                                                                            <i class="ri-delete-bin-line me-2"></i>Supprimer</a></li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="col-12">
                                        <div class="text-center py-5">
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
                                    </div>
                                <?php endif; ?>
                            </div>
                            </div>

                            <!-- Vue Liste (masquée par défaut) -->
                            <div id="listView" style="display: none;">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
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
                                                                        <a class="dropdown-item" href="<?= base_url('Estimations/view/' . $estimation['id']) ?>">
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Styles CSS personnalisés -->
<style>
.stats-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.75rem 1.5rem rgba(18, 38, 63, 0.15) !important;
}

.estimation-item {
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
}

.estimation-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    border-color: #5156be;
}

.estimation-card {
    transition: all 0.3s ease;
}

.counter {
    animation: countUp 1s ease-out;
}

@keyframes countUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
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

/* Styles pour la carte */
#estimationsMap {
    border-radius: 0.375rem;
}

.leaflet-popup-content {
    font-family: inherit;
}

.leaflet-popup-content h6 {
    margin-bottom: 0.5rem;
    font-weight: 600;
}

/* Responsive */
@media (max-width: 768px) {
    .estimation-card {
        margin-bottom: 1rem;
    }
    
    #mapContainer {
        order: 2;
    }
    
    #gridContainer {
        order: 1;
    }
}
</style>

<!-- Scripts JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Variables globales
    let map = null;
    let markers = [];
    let currentView = 'grid';
    let mapVisible = true; // Carte visible par défaut
    
    // Données des estimations pour la carte
    const estimationsData = <?= json_encode($estimations) ?>;
    
    // Elements DOM
    const gridViewBtn = document.getElementById('gridViewBtn');
    const listViewBtn = document.getElementById('listViewBtn');
    const toggleMapBtn = document.getElementById('toggleMapBtn');
    const mapContainer = document.getElementById('mapContainer');
    const gridContainer = document.getElementById('gridContainer');
    const gridView = document.getElementById('gridView');
    const listView = document.getElementById('listView');
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const typeFilter = document.getElementById('typeFilter');
    
    // Initialiser la carte immédiatement
    setTimeout(() => {
        initMap();
    }, 100);
    
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
        
        setTimeout(updateCounter, 500);
    });
    
    // Initialiser la carte
    function initMap() {
        if (map) return;
        
        map = L.map('estimationsMap').setView([36.8065, 10.1815], 10); // Centré sur Tunis
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        
        // Ajouter les marqueurs
        addMarkersToMap();
        
        // Ajuster la vue pour afficher tous les marqueurs
        if (markers.length > 0) {
            const group = new L.featureGroup(markers);
            map.fitBounds(group.getBounds().pad(0.1));
        }
    }
    
    // Ajouter les marqueurs sur la carte
    function addMarkersToMap() {
        estimationsData.forEach(estimation => {
            // Utiliser les coordonnées réelles de la base de données ou coordonnées par défaut
            let lat, lng;
            
            if (estimation.latitude && estimation.longitude && 
                !isNaN(parseFloat(estimation.latitude)) && !isNaN(parseFloat(estimation.longitude))) {
                // Utiliser les coordonnées réelles
                lat = parseFloat(estimation.latitude);
                lng = parseFloat(estimation.longitude);
            } else {
                // Coordonnées par défaut pour la Tunisie si non renseignées
                lat = 36.8 + (Math.random() - 0.5) * 0.5;
                lng = 10.2 + (Math.random() - 0.5) * 0.5;
            }
            
            const marker = L.marker([lat, lng]).addTo(map);
            
            const popupContent = `
                <div class="p-2">
                    <h6 class="mb-2">${estimation.titre || 'Propriété sans titre'}</h6>
                    <p class="mb-1"><strong>Type:</strong> ${estimation.type_propriete || 'N/A'}</p>
                    <p class="mb-1"><strong>Valeur:</strong> ${parseInt(estimation.valeur_estimee || 0).toLocaleString()} TND</p>
                    <p class="mb-1"><strong>Surface:</strong> ${estimation.surface || 0} m²</p>
                    <p class="mb-1"><strong>Adresse:</strong> ${estimation.adresse || 'N/A'}</p>
                    <p class="mb-2"><strong>Gouvernorat:</strong> ${estimation.gouvernorat || 'N/A'}</p>
                    <div class="d-flex gap-1">
                        <button class="btn btn-primary btn-sm" onclick="viewEstimation(${estimation.id})">
                            <i class="ri-eye-line me-1"></i>Voir
                        </button>
                        <button class="btn btn-outline-primary btn-sm" onclick="centerOnProperty(${lat}, ${lng})">
                            <i class="ri-focus-3-line"></i>
                        </button>
                    </div>
                </div>
            `;
            
            marker.bindPopup(popupContent);
            markers.push(marker);
        });
    }
    
    // Basculer entre vue grille et liste
    gridViewBtn.addEventListener('click', function() {
        currentView = 'grid';
        gridViewBtn.classList.add('btn-primary');
        gridViewBtn.classList.remove('btn-outline-primary');
        listViewBtn.classList.add('btn-outline-primary');
        listViewBtn.classList.remove('btn-primary');
        
        gridView.style.display = 'block';
        listView.style.display = 'none';
    });
    
    listViewBtn.addEventListener('click', function() {
        currentView = 'list';
        listViewBtn.classList.add('btn-primary');
        listViewBtn.classList.remove('btn-outline-primary');
        gridViewBtn.classList.add('btn-outline-primary');
        gridViewBtn.classList.remove('btn-primary');
        
        gridView.style.display = 'none';
        listView.style.display = 'block';
    });
    
    // Basculer l'affichage de la carte
    toggleMapBtn.addEventListener('click', function() {
        mapVisible = !mapVisible;
        
        if (mapVisible) {
            mapContainer.style.display = 'block';
            gridContainer.classList.remove('col-lg-12');
            gridContainer.classList.add('col-lg-6');
            toggleMapBtn.innerHTML = '<i class="ri-map-2-fill me-1"></i>Masquer carte';
            toggleMapBtn.classList.remove('btn-outline-secondary');
            toggleMapBtn.classList.add('btn-secondary');
            
            // Initialiser la carte si pas encore fait
            setTimeout(() => {
                initMap();
                if (map) map.invalidateSize();
            }, 100);
        } else {
            mapContainer.style.display = 'none';
            gridContainer.classList.remove('col-lg-6');
            gridContainer.classList.add('col-lg-12');
            toggleMapBtn.innerHTML = '<i class="ri-map-2-line me-1"></i>Carte';
            toggleMapBtn.classList.remove('btn-secondary');
            toggleMapBtn.classList.add('btn-outline-secondary');
        }
    });
    
    // Filtrage en temps réel
    function filterEstimations() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;
        const typeValue = typeFilter.value;
        
        const cards = document.querySelectorAll('.estimation-card');
        const rows = document.querySelectorAll('.estimation-row');
        let visibleCount = 0;
        
        // Filtrer les cartes (vue grille)
        cards.forEach(card => {
            const estimation = JSON.parse(card.getAttribute('data-estimation'));
            let visible = true;
            
            // Recherche textuelle
            if (searchTerm) {
                const searchText = (estimation.titre + ' ' + estimation.adresse + ' ' + estimation.gouvernorat).toLowerCase();
                if (!searchText.includes(searchTerm)) {
                    visible = false;
                }
            }
            
            // Filtrer par statut
            if (statusValue && estimation.statut !== statusValue) {
                visible = false;
            }
            
            // Filtrer par type
            if (typeValue && estimation.type_propriete !== typeValue) {
                visible = false;
            }
            
            card.style.display = visible ? 'block' : 'none';
            if (visible) visibleCount++;
        });
        
        // Filtrer les lignes (vue liste)
        rows.forEach(row => {
            let visible = true;
            const text = row.textContent.toLowerCase();
            
            if (searchTerm && !text.includes(searchTerm)) {
                visible = false;
            }
            
            if (statusValue) {
                const statusBadge = row.querySelector('td:nth-child(7) .badge');
                if (!statusBadge || !statusBadge.textContent.toLowerCase().includes(statusValue.replace('_', ' '))) {
                    visible = false;
                }
            }
            
            if (typeValue) {
                const typeBadge = row.querySelector('td:nth-child(2) .badge');
                if (!typeBadge || !typeBadge.textContent.toLowerCase().includes(typeValue)) {
                    visible = false;
                }
            }
            
            row.style.display = visible ? '' : 'none';
        });
        
        // Mettre à jour le compteur
        document.getElementById('resultsCount').textContent = visibleCount + ' résultats';
    }
    
    // Événements de filtrage
    [searchInput, statusFilter, typeFilter].forEach(element => {
        element.addEventListener('input', filterEstimations);
        element.addEventListener('change', filterEstimations);
    });
});

// Fonctions globales
function viewEstimation(id) {
    window.location.href = '<?= base_url("estimation/view/") ?>' + id;
}

function showOnMap(id) {
    // Activer la carte si elle n'est pas visible
    if (!document.getElementById('mapContainer').style.display || document.getElementById('mapContainer').style.display === 'none') {
        document.getElementById('toggleMapBtn').click();
    }
    
    // Trouver et centrer sur le marqueur correspondant
    const estimation = estimationsData.find(e => e.id == id);
    if (estimation && estimation.latitude && estimation.longitude) {
        centerOnProperty(parseFloat(estimation.latitude), parseFloat(estimation.longitude));
        
        // Ouvrir le popup du marqueur correspondant
        markers.forEach(marker => {
            const markerLatLng = marker.getLatLng();
            if (Math.abs(markerLatLng.lat - parseFloat(estimation.latitude)) < 0.001 && 
                Math.abs(markerLatLng.lng - parseFloat(estimation.longitude)) < 0.001) {
                marker.openPopup();
            }
        });
    }
}

function centerOnProperty(lat, lng) {
    if (map) {
        map.setView([lat, lng], 16);
    }
}

function deleteEstimation(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette estimation ?')) {
        window.location.href = '<?= base_url("estimation/delete/") ?>' + id;
    }
}

// Fonction pour mettre à jour les boutons de la barre d'outils en fonction de la vue actuelle
function updateToolbarButtons() {
    const mapBtn = document.getElementById('showMapBtn');
    const mapContainer = document.getElementById('mapContainer');
    
    if (mapContainer && mapContainer.style.display !== 'none') {
        if (mapBtn) {
            mapBtn.innerHTML = '<i class="ri-layout-grid-line me-2"></i>Masquer carte';
            mapBtn.classList.remove('btn-outline-primary');
            mapBtn.classList.add('btn-primary');
        }
    }
}

// Appeler la fonction après le chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(updateToolbarButtons, 200);
});

function deleteEstimation(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette estimation ?')) {
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
