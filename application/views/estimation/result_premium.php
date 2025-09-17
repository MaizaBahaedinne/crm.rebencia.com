<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- Header Premium avec Breadcrumb et Actions -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h4 class="mb-2">
                                <i class="ri-calculator-line me-2 text-primary"></i>
                                Résultat d'Estimation Premium
                            </h4>
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="<?= base_url('dashboard'); ?>">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="<?= base_url('estimation'); ?>">Estimations</a></li>
                                <li class="breadcrumb-item active">Résultat #<?= $property['id']; ?></li>
                            </ol>
                        </div>
                        <div class="page-title-right">
                            <div class="btn-group">
                                <a href="<?= base_url('estimation'); ?>" class="btn btn-outline-primary">
                                    <i class="ri-add-line me-2"></i>Nouvelle Estimation
                                </a>
                                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="ri-share-line me-2"></i>Actions
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" onclick="generatePDF()">
                                        <i class="ri-file-pdf-line me-2"></i>Exporter PDF
                                    </a></li>
                                    <li><a class="dropdown-item" href="#" onclick="shareEstimation()">
                                        <i class="ri-share-forward-line me-2"></i>Partager
                                    </a></li>
                                    <li><a class="dropdown-item" href="#" onclick="printEstimation()">
                                        <i class="ri-printer-line me-2"></i>Imprimer
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cartes Principales d'Estimation -->
            <div class="row g-4 mb-4">
                <!-- Valeur Estimée -->
                <div class="col-xl-4 col-lg-6">
                    <div class="card border-0 shadow-lg h-100 card-animate">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="flex-grow-1">
                                    <div class="avatar-sm flex-shrink-0 mb-3">
                                        <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-3">
                                            <i class="ri-money-dollar-circle-line"></i>
                                        </span>
                                    </div>
                                    <p class="text-uppercase fw-medium text-muted text-truncate fs-13 mb-2">
                                        Valeur Estimée
                                    </p>
                                    <h4 class="fw-semibold mb-3">
                                        <span class="counter-value" data-target="<?= $property['valeur_estimee']; ?>">0</span>
                                        <small class="text-primary fs-16">TND</small>
                                    </h4>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-success-subtle text-success fs-12">
                                            <i class="ri-arrow-up-line me-1"></i>Fourchette
                                        </span>
                                        <span class="text-muted fs-12">
                                            <?= number_format($property['valeur_min_estimee'],0,'',' '); ?> - <?= number_format($property['valeur_max_estimee'],0,'',' '); ?> TND
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="d-flex flex-column align-items-end">
                                        <div class="badge bg-primary-subtle text-primary fs-11 mb-2">Premium</div>
                                        <small class="text-muted">Coef: <?= $property['coef_global']; ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Loyer Potentiel -->
                <div class="col-xl-4 col-lg-6">
                    <div class="card border-0 shadow-lg h-100 card-animate">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="flex-grow-1">
                                    <div class="avatar-sm flex-shrink-0 mb-3">
                                        <span class="avatar-title bg-info-subtle text-info rounded-circle fs-3">
                                            <i class="ri-home-4-line"></i>
                                        </span>
                                    </div>
                                    <p class="text-uppercase fw-medium text-muted text-truncate fs-13 mb-2">
                                        Loyer Potentiel
                                    </p>
                                    <h4 class="fw-semibold mb-3">
                                        <span class="counter-value" data-target="<?= $property['loyer_potentiel']; ?>">0</span>
                                        <small class="text-info fs-16">TND/mois</small>
                                    </h4>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-info-subtle text-info fs-12">
                                            <i class="ri-calendar-line me-1"></i>Mensuel
                                        </span>
                                        <span class="text-muted fs-12">
                                            <?= number_format($property['loyer_potentiel'] * 12, 0, '', ' '); ?> TND/an
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="mini-stat-icon">
                                        <div class="avatar-title rounded-circle bg-info-subtle">
                                            <i class="ri-trend-up-line text-info"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rentabilité -->
                <div class="col-xl-4 col-lg-6">
                    <div class="card border-0 shadow-lg h-100 card-animate">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="flex-grow-1">
                                    <div class="avatar-sm flex-shrink-0 mb-3">
                                        <span class="avatar-title bg-warning-subtle text-warning rounded-circle fs-3">
                                            <i class="ri-percent-line"></i>
                                        </span>
                                    </div>
                                    <p class="text-uppercase fw-medium text-muted text-truncate fs-13 mb-2">
                                        Rentabilité Brute
                                    </p>
                                    <h4 class="fw-semibold mb-3">
                                        <span class="counter-value" data-target="<?= round($property['rentabilite'], 2); ?>">0</span>
                                        <small class="text-warning fs-16">%</small>
                                    </h4>
                                    <div class="d-flex align-items-center gap-2">
                                        <?php $profitability_level = $property['rentabilite'] >= 8 ? 'success' : ($property['rentabilite'] >= 5 ? 'warning' : 'danger'); ?>
                                        <span class="badge bg-<?= $profitability_level; ?>-subtle text-<?= $profitability_level; ?> fs-12">
                                            <i class="ri-bar-chart-line me-1"></i>
                                            <?= $property['rentabilite'] >= 8 ? 'Excellente' : ($property['rentabilite'] >= 5 ? 'Bonne' : 'Faible'); ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="mini-stat-icon">
                                        <div class="avatar-title rounded-circle bg-warning-subtle">
                                            <i class="ri-line-chart-line text-warning"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section Principale avec Tabs -->
            <div class="row g-4">
                <div class="col-xl-8">
                    <div class="card border-0 shadow-lg">
                        <div class="card-header border-0 pb-0">
                            <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#property-details" role="tab">
                                        <i class="ri-home-line me-2"></i>Détails du Bien
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#location-map" role="tab">
                                        <i class="ri-map-pin-line me-2"></i>Localisation
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#photos-gallery" role="tab">
                                        <i class="ri-image-line me-2"></i>Photos
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body p-4">
                            <div class="tab-content">
                                <!-- Détails du Bien -->
                                <div class="tab-pane active" id="property-details" role="tabpanel">
                                    <div class="row g-4">
                                        <!-- Informations Générales -->
                                        <div class="col-12">
                                            <div class="p-3 bg-light rounded-3">
                                                <h6 class="mb-3">
                                                    <i class="ri-information-line me-2 text-primary"></i>
                                                    Informations Générales
                                                </h6>
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-xs me-3">
                                                                <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                                                    <i class="ri-building-line"></i>
                                                                </span>
                                                            </div>
                                                            <div>
                                                                <small class="text-muted">Type de bien</small>
                                                                <div class="fw-medium"><?= $property['type_bien']; ?></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-xs me-3">
                                                                <span class="avatar-title rounded-circle bg-success-subtle text-success">
                                                                    <i class="ri-ruler-line"></i>
                                                                </span>
                                                            </div>
                                                            <div>
                                                                <small class="text-muted">Surface habitable</small>
                                                                <div class="fw-medium"><?= $property['surface_habitable']; ?> m²</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-xs me-3">
                                                                <span class="avatar-title rounded-circle bg-info-subtle text-info">
                                                                    <i class="ri-landscape-line"></i>
                                                                </span>
                                                            </div>
                                                            <div>
                                                                <small class="text-muted">Surface terrain</small>
                                                                <div class="fw-medium"><?= $property['surface_terrain']; ?> m²</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-xs me-3">
                                                                <span class="avatar-title rounded-circle bg-warning-subtle text-warning">
                                                                    <i class="ri-door-line"></i>
                                                                </span>
                                                            </div>
                                                            <div>
                                                                <small class="text-muted">Nombre de pièces</small>
                                                                <div class="fw-medium"><?= $property['nb_pieces']; ?> pièces</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Détails Techniques -->
                                        <div class="col-md-6">
                                            <div class="p-3 bg-primary-subtle rounded-3">
                                                <h6 class="mb-3 text-primary">
                                                    <i class="ri-settings-line me-2"></i>
                                                    Caractéristiques Techniques
                                                </h6>
                                                <div class="vstack gap-2">
                                                    <div class="d-flex justify-content-between">
                                                        <span class="text-muted">Étage :</span>
                                                        <span class="fw-medium"><?= $property['etage']; ?></span>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <span class="text-muted">État général :</span>
                                                        <span class="fw-medium"><?= $property['etat_general']; ?></span>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <span class="text-muted">Année construction :</span>
                                                        <span class="fw-medium"><?= $property['annee_construction']; ?></span>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <span class="text-muted">Classe énergétique :</span>
                                                        <span class="badge bg-<?= $property['energie_classe'] === 'A' ? 'success' : ($property['energie_classe'] === 'B' ? 'info' : ($property['energie_classe'] === 'C' ? 'warning' : 'danger')); ?>">
                                                            <?= $property['energie_classe']; ?>
                                                        </span>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <span class="text-muted">Titre foncier :</span>
                                                        <span class="fw-medium"><?= $property['titre_foncier']; ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Détails Financiers -->
                                        <div class="col-md-6">
                                            <div class="p-3 bg-success-subtle rounded-3">
                                                <h6 class="mb-3 text-success">
                                                    <i class="ri-money-dollar-circle-line me-2"></i>
                                                    Informations Financières
                                                </h6>
                                                <div class="vstack gap-2">
                                                    <div class="d-flex justify-content-between">
                                                        <span class="text-muted">Prix demandé :</span>
                                                        <span class="fw-medium"><?= number_format($property['prix_demande'],0,'',' '); ?> TND</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <span class="text-muted">Charges :</span>
                                                        <span class="fw-medium"><?= number_format($property['charges'],0,'',' '); ?> TND</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <span class="text-muted">Taxes :</span>
                                                        <span class="fw-medium"><?= number_format($property['taxes'],0,'',' '); ?> TND</span>
                                                    </div>
                                                    <?php 
                                                    $difference = $property['valeur_estimee'] - $property['prix_demande'];
                                                    $percentage = ($difference / $property['prix_demande']) * 100;
                                                    ?>
                                                    <div class="d-flex justify-content-between">
                                                        <span class="text-muted">Écart estimation :</span>
                                                        <span class="fw-medium text-<?= $difference > 0 ? 'success' : 'danger'; ?>">
                                                            <?= $difference > 0 ? '+' : ''; ?><?= number_format($difference, 0, '', ' '); ?> TND
                                                            <small>(<?= $percentage > 0 ? '+' : ''; ?><?= number_format($percentage, 1); ?>%)</small>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Équipements et Extérieur -->
                                        <div class="col-12">
                                            <div class="p-3 bg-warning-subtle rounded-3">
                                                <h6 class="mb-3 text-warning">
                                                    <i class="ri-tools-line me-2"></i>
                                                    Équipements et Extérieur
                                                </h6>
                                                <div class="row g-3">
                                                    <div class="col-md-4">
                                                        <div>
                                                            <small class="text-muted">Équipements</small>
                                                            <div class="fw-medium"><?= $property['equipements'] ?: 'Non renseigné'; ?></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div>
                                                            <small class="text-muted">Type extérieur</small>
                                                            <div class="fw-medium"><?= $property['type_exterieur'] ?: 'Non renseigné'; ?></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div>
                                                            <small class="text-muted">Extras</small>
                                                            <div class="d-flex gap-2 flex-wrap">
                                                                <?php if($property['jardin'] === 'oui'): ?>
                                                                    <span class="badge bg-success-subtle text-success">
                                                                        <i class="ri-plant-line me-1"></i>Jardin
                                                                    </span>
                                                                <?php endif; ?>
                                                                <?php if($property['piscine'] === 'oui'): ?>
                                                                    <span class="badge bg-info-subtle text-info">
                                                                        <i class="ri-water-flash-line me-1"></i>Piscine
                                                                    </span>
                                                                <?php endif; ?>
                                                                <?php if($property['syndic'] === 'oui'): ?>
                                                                    <span class="badge bg-primary-subtle text-primary">
                                                                        <i class="ri-shield-check-line me-1"></i>Syndic
                                                                    </span>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php if(!empty($property['securite'])): ?>
                                                <div class="mt-3 pt-3 border-top">
                                                    <small class="text-muted">Sécurité</small>
                                                    <div class="fw-medium"><?= $property['securite']; ?></div>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Localisation -->
                                <div class="tab-pane" id="location-map" role="tabpanel">
                                    <div class="row g-4">
                                        <div class="col-12">
                                            <div class="p-3 bg-info-subtle rounded-3">
                                                <h6 class="mb-3 text-info">
                                                    <i class="ri-map-pin-line me-2"></i>
                                                    Adresse Complète
                                                </h6>
                                                <div class="d-flex align-items-start">
                                                    <div class="avatar-sm me-3">
                                                        <span class="avatar-title rounded-circle bg-info text-white">
                                                            <i class="ri-map-pin-fill"></i>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold mb-1">
                                                            <?= ($property['adresse_numero'] ? $property['adresse_numero'].' ' : '') ?><?= $property['adresse_rue']; ?>
                                                        </div>
                                                        <div class="text-muted">
                                                            <?= $property['adresse_cp']; ?> <?= $property['adresse_ville']; ?><br>
                                                            <?= $property['adresse_pays']; ?>
                                                        </div>
                                                        <div class="d-flex gap-3 mt-2">
                                                            <small class="text-muted">
                                                                <i class="ri-navigation-line me-1"></i>
                                                                Lat: <?= $property['latitude']; ?>
                                                            </small>
                                                            <small class="text-muted">
                                                                <i class="ri-navigation-line me-1"></i>
                                                                Lng: <?= $property['longitude']; ?>
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="card border-0 shadow-sm">
                                                <div class="card-body p-0">
                                                    <div id="map-result" style="height: 400px; border-radius: 0.5rem;"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="p-3 bg-light rounded-3">
                                                <h6 class="mb-3">
                                                    <i class="ri-building-2-line me-2 text-primary"></i>
                                                    Zone et Quartier
                                                </h6>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-3">
                                                        <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                                            <i class="ri-community-line"></i>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold"><?= $property['zone_nom']; ?></div>
                                                        <small class="text-muted">Zone d'estimation</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Photos -->
                                <div class="tab-pane" id="photos-gallery" role="tabpanel">
                                    <?php if(!empty($property['photos'])): ?>
                                    <div class="row g-3">
                                        <?php foreach($property['photos'] as $index => $photo): ?>
                                        <div class="col-lg-4 col-md-6">
                                            <div class="card border-0 shadow-sm">
                                                <div class="position-relative">
                                                    <img src="<?= base_url($photo['file']); ?>" 
                                                         class="card-img-top" 
                                                         style="height: 200px; object-fit: cover;"
                                                         alt="Photo <?= $index + 1; ?>">
                                                    <div class="position-absolute top-0 end-0 p-2">
                                                        <span class="badge bg-dark bg-opacity-75">
                                                            <?= $index + 1; ?>/<?= count($property['photos']); ?>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="card-body p-3">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <small class="text-muted">Photo <?= $index + 1; ?></small>
                                                        <div class="btn-group btn-group-sm">
                                                            <a href="<?= base_url($photo['file']); ?>" 
                                                               target="_blank" 
                                                               class="btn btn-outline-primary">
                                                                <i class="ri-eye-line"></i>
                                                            </a>
                                                            <a href="<?= base_url($photo['file']); ?>" 
                                                               download 
                                                               class="btn btn-outline-success">
                                                                <i class="ri-download-line"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php else: ?>
                                    <div class="text-center py-5">
                                        <div class="avatar-lg mx-auto mb-4">
                                            <div class="avatar-title bg-light text-muted rounded-circle">
                                                <i class="ri-image-line display-4"></i>
                                            </div>
                                        </div>
                                        <h5 class="text-muted">Aucune photo disponible</h5>
                                        <p class="text-muted mb-0">Aucune photo n'a été ajoutée pour ce bien.</p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Actions -->
                <div class="col-xl-4">
                    <div class="row g-4">
                        <!-- Proposition Agence -->
                        <div class="col-12">
                            <div class="card border-0 shadow-lg">
                                <div class="card-header border-0 pb-0">
                                    <h6 class="card-title mb-0">
                                        <i class="ri-hand-coin-line me-2 text-success"></i>
                                        Proposition Agence
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <form method="post" action="<?= base_url('estimation/proposition/'.$property['id']); ?>">
                                        <div class="mb-3">
                                            <label class="form-label">Montant proposé (TND)</label>
                                            <div class="input-group">
                                                <input type="number" 
                                                       step="0.01" 
                                                       name="proposition_agence" 
                                                       class="form-control form-control-lg" 
                                                       value="<?= htmlspecialchars($property['proposition_agence'] ?? '', ENT_QUOTES); ?>"
                                                       placeholder="Ex: 250,000">
                                                <span class="input-group-text">TND</span>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Commentaire</label>
                                            <textarea name="proposition_commentaire" 
                                                      class="form-control" 
                                                      rows="3" 
                                                      placeholder="Justification de votre proposition..."><?= htmlspecialchars($property['proposition_commentaire'] ?? ''); ?></textarea>
                                        </div>
                                        <button class="btn btn-success w-100" type="submit">
                                            <i class="ri-save-line me-2"></i>Enregistrer la Proposition
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Statut du Dossier -->
                        <div class="col-12">
                            <div class="card border-0 shadow-lg">
                                <div class="card-header border-0 pb-0">
                                    <h6 class="card-title mb-0">
                                        <i class="ri-file-check-line me-2 text-primary"></i>
                                        Statut du Dossier
                                    </h6>
                                </div>
                                <div class="card-body text-center">
                                    <?php
                                        $status_config = [
                                            'valide' => ['color' => 'success', 'icon' => 'ri-checkbox-circle-line', 'text' => 'Validé'],
                                            'rejete' => ['color' => 'danger', 'icon' => 'ri-close-circle-line', 'text' => 'Rejeté'],
                                            'en_cours' => ['color' => 'warning', 'icon' => 'ri-time-line', 'text' => 'En Cours'],
                                            'default' => ['color' => 'secondary', 'icon' => 'ri-question-line', 'text' => 'En Attente']
                                        ];
                                        
                                        $current_status = $status_config[$property['statut_dossier']] ?? $status_config['default'];
                                    ?>
                                    
                                    <div class="avatar-lg mx-auto mb-3">
                                        <span class="avatar-title bg-<?= $current_status['color']; ?>-subtle text-<?= $current_status['color']; ?> rounded-circle fs-2">
                                            <i class="<?= $current_status['icon']; ?>"></i>
                                        </span>
                                    </div>
                                    
                                    <h5 class="text-<?= $current_status['color']; ?> mb-3">
                                        <?= $current_status['text']; ?>
                                    </h5>
                                    
                                    <?php if($property['statut_dossier'] !== 'valide' && $property['statut_dossier'] !== 'rejete'): ?>
                                    <div class="d-grid gap-2">
                                        <a href="<?= base_url('estimation/statut/'.$property['id'].'/valide'); ?>" 
                                           class="btn btn-success" 
                                           onclick="return confirm('Valider cette estimation ?');">
                                            <i class="ri-check-line me-2"></i>Valider l'Estimation
                                        </a>
                                        <a href="<?= base_url('estimation/statut/'.$property['id'].'/rejete'); ?>" 
                                           class="btn btn-outline-danger" 
                                           onclick="return confirm('Rejeter cette estimation ?');">
                                            <i class="ri-close-line me-2"></i>Rejeter l'Estimation
                                        </a>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Statistiques Rapides -->
                        <div class="col-12">
                            <div class="card border-0 shadow-lg">
                                <div class="card-header border-0 pb-0">
                                    <h6 class="card-title mb-0">
                                        <i class="ri-bar-chart-box-line me-2 text-info"></i>
                                        Analyse Rapide
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="vstack gap-3">
                                        <!-- Prix au m² -->
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <small class="text-muted">Prix au m²</small>
                                                <div class="fw-semibold">
                                                    <?= number_format($property['valeur_estimee'] / $property['surface_habitable'], 0, '', ' '); ?> TND/m²
                                                </div>
                                            </div>
                                            <div class="avatar-xs">
                                                <span class="avatar-title rounded bg-primary-subtle text-primary">
                                                    <i class="ri-ruler-line"></i>
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <!-- Loyer au m² -->
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <small class="text-muted">Loyer au m²</small>
                                                <div class="fw-semibold">
                                                    <?= number_format($property['loyer_potentiel'] / $property['surface_habitable'], 2, ',', ' '); ?> TND/m²
                                                </div>
                                            </div>
                                            <div class="avatar-xs">
                                                <span class="avatar-title rounded bg-info-subtle text-info">
                                                    <i class="ri-home-line"></i>
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <!-- Ratio Prix/Loyer -->
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <small class="text-muted">Ratio Prix/Loyer</small>
                                                <div class="fw-semibold">
                                                    <?= round($property['valeur_estimee'] / ($property['loyer_potentiel'] * 12), 1); ?> années
                                                </div>
                                            </div>
                                            <div class="avatar-xs">
                                                <span class="avatar-title rounded bg-warning-subtle text-warning">
                                                    <i class="ri-time-line"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Scripts et Styles -->
<link rel="stylesheet" href="<?= base_url('assets/libs/leaflet/leaflet.css'); ?>" />
<script src="<?= base_url('assets/libs/leaflet/leaflet.js'); ?>"></script>

<script>
// Animation des compteurs
document.addEventListener('DOMContentLoaded', function() {
    const counters = document.querySelectorAll('.counter-value');
    
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-target'));
        const duration = 2000; // 2 secondes
        const step = target / (duration / 16); // 60 FPS
        let current = 0;
        
        const updateCounter = () => {
            current += step;
            if (current < target) {
                counter.textContent = Math.floor(current).toLocaleString('fr-FR');
                requestAnimationFrame(updateCounter);
            } else {
                counter.textContent = target.toLocaleString('fr-FR');
            }
        };
        
        updateCounter();
    });
});

// Initialisation de la carte
(function(){
    var lat = parseFloat('<?= $property['latitude'] ?>');
    var lng = parseFloat('<?= $property['longitude'] ?>');
    
    if(!isNaN(lat) && !isNaN(lng) && lat && lng) {
        var map = L.map('map-result').setView([lat, lng], 15);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        
        // Marqueur custom
        var customIcon = L.divIcon({
            className: 'custom-marker',
            html: '<div class="marker-pin bg-primary"></div>',
            iconSize: [30, 40],
            iconAnchor: [15, 40]
        });
        
        L.marker([lat, lng], {icon: customIcon}).addTo(map);
        
        // Popup avec informations
        L.marker([lat, lng]).addTo(map)
            .bindPopup(`
                <div class="p-2">
                    <strong><?= $property['type_bien']; ?></strong><br>
                    <small class="text-muted"><?= $property['surface_habitable']; ?> m² • <?= $property['nb_pieces']; ?> pièces</small><br>
                    <strong class="text-primary"><?= number_format($property['valeur_estimee'], 0, '', ' '); ?> TND</strong>
                </div>
            `);
    } else {
        document.getElementById('map-result').innerHTML = `
            <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                <div class="text-center">
                    <i class="ri-map-pin-line display-4 mb-3"></i>
                    <div>Coordonnées non disponibles</div>
                </div>
            </div>
        `;
    }
})();

// Fonctions utilitaires
function generatePDF() {
    window.print();
}

function shareEstimation() {
    if (navigator.share) {
        navigator.share({
            title: 'Estimation Immobilière',
            text: 'Découvrez cette estimation immobilière',
            url: window.location.href
        });
    } else {
        // Fallback pour navigateurs non compatibles
        const textarea = document.createElement('textarea');
        textarea.value = window.location.href;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        
        // Notification
        showNotification('Lien copié dans le presse-papiers !', 'success');
    }
}

function printEstimation() {
    window.print();
}

// Fonction de notification
function showNotification(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        <i class="ri-${type === 'success' ? 'check-circle' : 'information'}-line me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }, 5000);
}
</script>

<style>
/* Animations et effets visuels */
.card-animate {
    transition: all 0.3s ease;
}

.card-animate:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}

.counter-value {
    font-variant-numeric: tabular-nums;
}

/* Marqueur de carte personnalisé */
.custom-marker .marker-pin {
    width: 30px;
    height: 30px;
    border-radius: 50% 50% 50% 0;
    position: absolute;
    transform: rotate(-45deg);
    left: 50%;
    top: 50%;
    margin: -15px 0 0 -15px;
}

.custom-marker .marker-pin::after {
    content: '';
    width: 16px;
    height: 16px;
    margin: 7px 0 0 7px;
    background: #fff;
    position: absolute;
    border-radius: 50%;
}

/* Améliorations des tabs */
.nav-tabs-custom .nav-link {
    border: none;
    background: transparent;
    color: #64748b;
    font-weight: 500;
    padding: 12px 20px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.nav-tabs-custom .nav-link.active {
    background: linear-gradient(45deg, #4f46e5, #7c3aed);
    color: white;
    box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
}

.nav-tabs-custom .nav-link:hover:not(.active) {
    background: #f8fafc;
    color: #4f46e5;
}

/* Badge amélioré */
.badge {
    font-weight: 500;
    letter-spacing: 0.025em;
}

/* Cartes avec gradient subtil */
.bg-primary-subtle {
    background: linear-gradient(135deg, rgba(79, 70, 229, 0.1), rgba(99, 102, 241, 0.05)) !important;
}

.bg-success-subtle {
    background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(22, 163, 74, 0.05)) !important;
}

.bg-info-subtle {
    background: linear-gradient(135deg, rgba(14, 165, 233, 0.1), rgba(2, 132, 199, 0.05)) !important;
}

.bg-warning-subtle {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(217, 119, 6, 0.05)) !important;
}

/* Responsive amélioré */
@media (max-width: 768px) {
    .card-animate:hover {
        transform: none;
    }
    
    .btn-group {
        flex-direction: column;
        width: 100%;
    }
    
    .btn-group .btn {
        border-radius: 0.375rem !important;
        margin-bottom: 0.25rem;
    }
}

/* Impression */
@media print {
    .page-title-right,
    .btn,
    .dropdown-menu {
        display: none !important;
    }
    
    .card {
        border: 1px solid #ddd !important;
        box-shadow: none !important;
    }
    
    .nav-tabs-custom {
        display: none;
    }
    
    .tab-content .tab-pane {
        display: block !important;
        opacity: 1 !important;
    }
}
</style>
