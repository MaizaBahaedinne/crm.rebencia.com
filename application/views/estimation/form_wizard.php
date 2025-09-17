<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- Header du Wizard -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h4 class="mb-2">
                                <i class="ri-calculator-line me-2 text-primary"></i>
                                Assistant d'Estimation Immobilière
                            </h4>
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="<?= base_url('dashboard'); ?>">Dashboard</a></li>
                                <li class="breadcrumb-item active">Nouvelle Estimation</li>
                            </ol>
                        </div>
                        <div class="page-title-right">
                            <span class="badge bg-primary-subtle text-primary fs-12">
                                <i class="ri-magic-line me-1"></i>Mode Assistant
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Bar du Wizard -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="wizard-progress">
                                <div class="wizard-steps d-flex justify-content-between position-relative">
                                    <div class="step active" data-step="1">
                                        <div class="step-icon">
                                            <i class="ri-map-pin-line"></i>
                                        </div>
                                        <div class="step-content">
                                            <h6 class="step-title">Localisation</h6>
                                            <small class="step-description">Zone et adresse</small>
                                        </div>
                                    </div>
                                    <div class="step" data-step="2">
                                        <div class="step-icon">
                                            <i class="ri-home-4-line"></i>
                                        </div>
                                        <div class="step-content">
                                            <h6 class="step-title">Bien</h6>
                                            <small class="step-description">Type et dimensions</small>
                                        </div>
                                    </div>
                                    <div class="step" data-step="3">
                                        <div class="step-icon">
                                            <i class="ri-settings-3-line"></i>
                                        </div>
                                        <div class="step-content">
                                            <h6 class="step-title">Caractéristiques</h6>
                                            <small class="step-description">État et équipements</small>
                                        </div>
                                    </div>
                                    <div class="step" data-step="4">
                                        <div class="step-icon">
                                            <i class="ri-money-dollar-circle-line"></i>
                                        </div>
                                        <div class="step-content">
                                            <h6 class="step-title">Financier</h6>
                                            <small class="step-description">Prix et charges</small>
                                        </div>
                                    </div>
                                    <div class="step" data-step="5">
                                        <div class="step-icon">
                                            <i class="ri-image-line"></i>
                                        </div>
                                        <div class="step-content">
                                            <h6 class="step-title">Photos</h6>
                                            <small class="step-description">Images du bien</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="progress-line">
                                    <div class="progress-line-fill" style="width: 20%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulaire Wizard -->
            <form id="estimation-wizard" method="post" action="<?= base_url('estimation/calcul'); ?>" enctype="multipart/form-data">
                
                <!-- Étape 1: Localisation -->
                <div class="wizard-step active" id="step-1">
                    <div class="card border-0 shadow-lg">
                        <div class="card-header border-0 pb-0">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-3">
                                    <span class="avatar-title bg-primary text-white rounded-circle">
                                        <i class="ri-map-pin-line"></i>
                                    </span>
                                </div>
                                <div>
                                    <h5 class="card-title mb-1">Localisation du Bien</h5>
                                    <p class="text-muted mb-0">Indiquez la zone et l'adresse précise du bien à estimer</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div class="col-12">
                                    <label class="form-label fw-semibold">
                                        <i class="ri-building-2-line me-2 text-primary"></i>
                                        Zone d'estimation *
                                    </label>
                                    <select name="zone_id" class="form-select form-select-lg" required>
                                        <option value="">-- Sélectionner une zone --</option>
                                        <?php foreach($zones as $z): ?>
                                            <option value="<?= $z->id; ?>">
                                                <?= $z->nom; ?> 
                                                <span class="text-muted">(<?= number_format($z->prix_m2_moyen,0,'',' '); ?> TND/m²)</span>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="form-text">
                                        <i class="ri-information-line me-1"></i>
                                        Choisissez la zone la plus proche de votre bien pour une estimation précise
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="p-3 bg-light rounded-3">
                                        <h6 class="mb-3">
                                            <i class="ri-map-2-line me-2 text-info"></i>
                                            Positionnement sur la carte
                                        </h6>
                                        <div id="map" style="height: 400px; border-radius: 0.5rem;" class="mb-3 border"></div>
                                        <div class="d-flex gap-2 flex-wrap">
                                            <button type="button" class="btn btn-outline-primary btn-sm" id="btn-geoloc-map">
                                                <i class="ri-crosshair-line me-2"></i>Ma position
                                            </button>
                                            <button type="button" class="btn btn-outline-info btn-sm" id="btn-search-address">
                                                <i class="ri-search-line me-2"></i>Rechercher une adresse
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Latitude</label>
                                    <input type="text" name="latitude" id="latitude" class="form-control" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Longitude</label>
                                    <input type="text" name="longitude" id="longitude" class="form-control" readonly>
                                </div>

                                <div class="col-12">
                                    <div class="p-3 bg-primary-subtle rounded-3">
                                        <h6 class="mb-3 text-primary">
                                            <i class="ri-map-pin-line me-2"></i>
                                            Adresse complète
                                        </h6>
                                        <div class="row g-3">
                                            <div class="col-md-2">
                                                <label class="form-label">Numéro</label>
                                                <input type="text" name="adresse_numero" class="form-control" placeholder="Ex: 15">
                                            </div>
                                            <div class="col-md-5">
                                                <label class="form-label">Rue *</label>
                                                <input type="text" name="adresse_rue" class="form-control" placeholder="Ex: Avenue Habib Bourguiba" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Code postal</label>
                                                <input type="text" name="adresse_cp" class="form-control" placeholder="Ex: 2000">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Ville *</label>
                                                <input type="text" name="adresse_ville" class="form-control" placeholder="Ex: Tunis" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Pays</label>
                                                <input type="text" name="adresse_pays" class="form-control" value="Tunisie" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Étape 2: Type et dimensions du bien -->
                <div class="wizard-step" id="step-2">
                    <div class="card border-0 shadow-lg">
                        <div class="card-header border-0 pb-0">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-3">
                                    <span class="avatar-title bg-info text-white rounded-circle">
                                        <i class="ri-home-4-line"></i>
                                    </span>
                                </div>
                                <div>
                                    <h5 class="card-title mb-1">Caractéristiques du Bien</h5>
                                    <p class="text-muted mb-0">Définissez le type et les dimensions de votre propriété</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <!-- Type de bien -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold">
                                        <i class="ri-building-line me-2 text-info"></i>
                                        Type de bien *
                                    </label>
                                    <div class="row g-3">
                                        <?php 
                                        $types = [
                                            'appartement' => ['icon' => 'ri-building-4-line', 'title' => 'Appartement', 'desc' => 'Logement dans un immeuble'],
                                            'maison' => ['icon' => 'ri-home-4-line', 'title' => 'Maison', 'desc' => 'Maison individuelle'],
                                            'villa' => ['icon' => 'ri-home-8-line', 'title' => 'Villa', 'desc' => 'Villa avec jardin'],
                                            'studio' => ['icon' => 'ri-building-2-line', 'title' => 'Studio', 'desc' => 'Une seule pièce'],
                                            'duplex' => ['icon' => 'ri-building-3-line', 'title' => 'Duplex', 'desc' => 'Sur deux niveaux'],
                                            'penthouse' => ['icon' => 'ri-building-line', 'title' => 'Penthouse', 'desc' => 'Dernier étage premium']
                                        ];
                                        ?>
                                        <?php foreach($types as $value => $type): ?>
                                        <div class="col-md-4">
                                            <div class="type-option">
                                                <input type="radio" name="type_bien" value="<?= $value; ?>" id="type_<?= $value; ?>" required>
                                                <label for="type_<?= $value; ?>" class="type-card">
                                                    <div class="type-icon">
                                                        <i class="<?= $type['icon']; ?>"></i>
                                                    </div>
                                                    <h6 class="type-title"><?= $type['title']; ?></h6>
                                                    <small class="type-description"><?= $type['desc']; ?></small>
                                                </label>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <!-- Surfaces -->
                                <div class="col-12">
                                    <div class="p-3 bg-info-subtle rounded-3">
                                        <h6 class="mb-3 text-info">
                                            <i class="ri-ruler-line me-2"></i>
                                            Surfaces et dimensions
                                        </h6>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label">Surface habitable (m²) *</label>
                                                <div class="input-group">
                                                    <input type="number" name="surface_habitable" class="form-control" min="1" required>
                                                    <span class="input-group-text">m²</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Surface terrain (m²)</label>
                                                <div class="input-group">
                                                    <input type="number" name="surface_terrain" class="form-control" min="0">
                                                    <span class="input-group-text">m²</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Nombre de pièces *</label>
                                                <select name="nb_pieces" class="form-select" required>
                                                    <option value="">-- Choisir --</option>
                                                    <option value="1">1 pièce</option>
                                                    <option value="2">2 pièces</option>
                                                    <option value="3">3 pièces</option>
                                                    <option value="4">4 pièces</option>
                                                    <option value="5">5 pièces</option>
                                                    <option value="6">6 pièces et plus</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Étage et orientation -->
                                <div class="col-12">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Étage</label>
                                            <select name="etage" class="form-select">
                                                <option value="">-- Sélectionner --</option>
                                                <option value="rdc">Rez-de-chaussée</option>
                                                <option value="1">1er étage</option>
                                                <option value="2">2ème étage</option>
                                                <option value="3">3ème étage</option>
                                                <option value="4">4ème étage et plus</option>
                                                <option value="dernier">Dernier étage</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Ascenseur</label>
                                            <select name="ascenseur" class="form-select">
                                                <option value="">Non</option>
                                                <option value="oui">Oui</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Orientation</label>
                                            <select name="orientation" class="form-select">
                                                <option value="">-- Choisir --</option>
                                                <option value="nord">Nord</option>
                                                <option value="sud">Sud</option>
                                                <option value="est">Est</option>
                                                <option value="ouest">Ouest</option>
                                                <option value="sud-est">Sud-Est</option>
                                                <option value="sud-ouest">Sud-Ouest</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Étape 3: État et équipements -->
                <div class="wizard-step" id="step-3">
                    <div class="card border-0 shadow-lg">
                        <div class="card-header border-0 pb-0">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-3">
                                    <span class="avatar-title bg-warning text-white rounded-circle">
                                        <i class="ri-settings-3-line"></i>
                                    </span>
                                </div>
                                <div>
                                    <h5 class="card-title mb-1">État et Équipements</h5>
                                    <p class="text-muted mb-0">Précisez l'état général et les équipements disponibles</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <!-- État général -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold">
                                        <i class="ri-tools-line me-2 text-warning"></i>
                                        État général du bien *
                                    </label>
                                    <div class="row g-3">
                                        <?php 
                                        $etats = [
                                            'neuf' => ['color' => 'success', 'icon' => 'ri-star-line', 'title' => 'Neuf', 'desc' => 'Construction récente'],
                                            'renove' => ['color' => 'info', 'icon' => 'ri-brush-line', 'title' => 'Rénové', 'desc' => 'Récemment rénové'],
                                            'bon' => ['color' => 'primary', 'icon' => 'ri-thumb-up-line', 'title' => 'Bon état', 'desc' => 'Bien entretenu'],
                                            'ancien' => ['color' => 'warning', 'icon' => 'ri-time-line', 'title' => 'Ancien', 'desc' => 'Quelques travaux'],
                                            'a_renover' => ['color' => 'danger', 'icon' => 'ri-hammer-line', 'title' => 'À rénover', 'desc' => 'Gros travaux nécessaires']
                                        ];
                                        ?>
                                        <?php foreach($etats as $value => $etat): ?>
                                        <div class="col-md-4">
                                            <div class="state-option">
                                                <input type="radio" name="etat_general" value="<?= $value; ?>" id="etat_<?= $value; ?>" required>
                                                <label for="etat_<?= $value; ?>" class="state-card border-<?= $etat['color']; ?>">
                                                    <div class="state-icon text-<?= $etat['color']; ?>">
                                                        <i class="<?= $etat['icon']; ?>"></i>
                                                    </div>
                                                    <h6 class="state-title"><?= $etat['title']; ?></h6>
                                                    <small class="state-description"><?= $etat['desc']; ?></small>
                                                </label>
                                            </div>
                                        </div>
                                        <?php if(($key = array_search($value, array_keys($etats))) !== false && ($key + 1) % 3 === 0): ?>
                                        </div><div class="row g-3">
                                        <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <!-- Équipements -->
                                <div class="col-12">
                                    <div class="p-3 bg-warning-subtle rounded-3">
                                        <h6 class="mb-3 text-warning">
                                            <i class="ri-tools-line me-2"></i>
                                            Équipements et installations
                                        </h6>
                                        <div class="row g-3">
                                            <?php 
                                            $equipements = [
                                                'cuisine_equipee' => ['icon' => 'ri-restaurant-line', 'label' => 'Cuisine équipée'],
                                                'climatisation' => ['icon' => 'ri-snowy-line', 'label' => 'Climatisation'],
                                                'chauffage' => ['icon' => 'ri-fire-line', 'label' => 'Chauffage'],
                                                'isolation' => ['icon' => 'ri-shield-line', 'label' => 'Isolation'],
                                                'cave' => ['icon' => 'ri-archive-line', 'label' => 'Cave/Débarras'],
                                                'cheminee' => ['icon' => 'ri-fire-fill', 'label' => 'Cheminée']
                                            ];
                                            ?>
                                            <?php foreach($equipements as $value => $equip): ?>
                                            <div class="col-md-4">
                                                <div class="form-check equipment-check">
                                                    <input class="form-check-input" type="checkbox" name="equipements[]" value="<?= $value; ?>" id="eq_<?= $value; ?>">
                                                    <label class="form-check-label" for="eq_<?= $value; ?>">
                                                        <i class="<?= $equip['icon']; ?> me-2 text-warning"></i>
                                                        <?= $equip['label']; ?>
                                                    </label>
                                                </div>
                                            </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Extérieur et confort -->
                                <div class="col-12">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Type d'extérieur</label>
                                            <select name="type_exterieur" class="form-select">
                                                <option value="">Aucun</option>
                                                <option value="balcon">Balcon</option>
                                                <option value="terrasse">Terrasse</option>
                                                <option value="jardin">Jardin</option>
                                                <option value="cour">Cour</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Parking/Garage</label>
                                            <select name="parking" class="form-select">
                                                <option value="">Non</option>
                                                <option value="oui">Oui</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Année de construction</label>
                                            <input type="number" name="annee_construction" class="form-control" min="1800" max="<?= date('Y'); ?>" placeholder="Ex: 1995">
                                        </div>
                                    </div>
                                </div>

                                <!-- Commodités supplémentaires -->
                                <div class="col-12">
                                    <div class="p-3 bg-light rounded-3">
                                        <h6 class="mb-3">
                                            <i class="ri-service-line me-2 text-success"></i>
                                            Commodités supplémentaires
                                        </h6>
                                        <div class="row g-3">
                                            <?php 
                                            $commodites = [
                                                'piscine' => 'Piscine',
                                                'jardin' => 'Jardin',
                                                'securite' => 'Sécurité',
                                                'syndic' => 'Syndic',
                                                'meuble' => 'Meublé'
                                            ];
                                            ?>
                                            <?php foreach($commodites as $value => $label): ?>
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="<?= $value; ?>" value="oui" id="com_<?= $value; ?>">
                                                    <label class="form-check-label" for="com_<?= $value; ?>">
                                                        <i class="ri-check-line me-2 text-success"></i>
                                                        <?= $label; ?>
                                                    </label>
                                                </div>
                                            </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Étape 4: Informations financières -->
                <div class="wizard-step" id="step-4">
                    <div class="card border-0 shadow-lg">
                        <div class="card-header border-0 pb-0">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-3">
                                    <span class="avatar-title bg-success text-white rounded-circle">
                                        <i class="ri-money-dollar-circle-line"></i>
                                    </span>
                                </div>
                                <div>
                                    <h5 class="card-title mb-1">Informations Financières</h5>
                                    <p class="text-muted mb-0">Prix demandé, charges et informations budgétaires</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div class="col-12">
                                    <div class="p-3 bg-success-subtle rounded-3">
                                        <h6 class="mb-3 text-success">
                                            <i class="ri-money-dollar-circle-line me-2"></i>
                                            Prix et charges
                                        </h6>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label">Prix demandé (TND)</label>
                                                <div class="input-group">
                                                    <input type="number" name="prix_demande" class="form-control" step="0.01" placeholder="Ex: 250000">
                                                    <span class="input-group-text">TND</span>
                                                </div>
                                                <div class="form-text">Prix de vente souhaité par le propriétaire</div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Charges mensuelles (TND)</label>
                                                <div class="input-group">
                                                    <input type="number" name="charges" class="form-control" step="0.01" placeholder="Ex: 150">
                                                    <span class="input-group-text">TND/mois</span>
                                                </div>
                                                <div class="form-text">Syndic, entretien, etc.</div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Taxes annuelles (TND)</label>
                                                <div class="input-group">
                                                    <input type="number" name="taxes" class="form-control" step="0.01" placeholder="Ex: 800">
                                                    <span class="input-group-text">TND/an</span>
                                                </div>
                                                <div class="form-text">Taxe municipale, etc.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Classe énergétique</label>
                                            <select name="energie_classe" class="form-select">
                                                <option value="">-- Non définie --</option>
                                                <option value="A">A - Très économe</option>
                                                <option value="B">B - Économe</option>
                                                <option value="C">C - Convenable</option>
                                                <option value="D">D - Peu économe</option>
                                                <option value="E">E - Énergivore</option>
                                                <option value="F">F - Très énergivore</option>
                                                <option value="G">G - Extrêmement énergivore</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Titre foncier</label>
                                            <select name="titre_foncier" class="form-select">
                                                <option value="">-- Non spécifié --</option>
                                                <option value="oui">Oui</option>
                                                <option value="non">Non</option>
                                                <option value="en_cours">En cours</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Scores de proximité -->
                                <div class="col-12">
                                    <div class="p-3 bg-light rounded-3">
                                        <h6 class="mb-3">
                                            <i class="ri-map-pin-2-line me-2 text-info"></i>
                                            Scores de proximité (0 à 5)
                                        </h6>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Proximité transports en commun</label>
                                                <div class="d-flex align-items-center gap-3">
                                                    <input type="range" name="proximite_transports_score" class="form-range flex-grow-1" min="0" max="5" step="1" value="0" oninput="updateScoreDisplay(this, 'transport-score')">
                                                    <span class="badge bg-primary" id="transport-score">0/5</span>
                                                </div>
                                                <div class="form-text">Bus, métro, tramway à proximité</div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Proximité commodités</label>
                                                <div class="d-flex align-items-center gap-3">
                                                    <input type="range" name="proximite_commodites_score" class="form-range flex-grow-1" min="0" max="5" step="1" value="0" oninput="updateScoreDisplay(this, 'commodite-score')">
                                                    <span class="badge bg-success" id="commodite-score">0/5</span>
                                                </div>
                                                <div class="form-text">Commerces, écoles, services médicaux</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Étape 5: Photos -->
                <div class="wizard-step" id="step-5">
                    <div class="card border-0 shadow-lg">
                        <div class="card-header border-0 pb-0">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-3">
                                    <span class="avatar-title bg-danger text-white rounded-circle">
                                        <i class="ri-image-line"></i>
                                    </span>
                                </div>
                                <div>
                                    <h5 class="card-title mb-1">Photos du Bien</h5>
                                    <p class="text-muted mb-0">Ajoutez des photos pour améliorer la précision de l'estimation</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div class="col-12">
                                    <div class="upload-area border-2 border-dashed rounded-3 p-4 text-center">
                                        <div class="upload-icon mb-3">
                                            <i class="ri-cloud-upload-line display-4 text-muted"></i>
                                        </div>
                                        <h5 class="mb-2">Glissez vos photos ici</h5>
                                        <p class="text-muted mb-3">ou cliquez pour parcourir vos fichiers</p>
                                        <input type="file" name="photos[]" multiple accept="image/*" class="form-control d-none" id="photo-upload">
                                        <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('photo-upload').click()">
                                            <i class="ri-folder-open-line me-2"></i>Choisir des photos
                                        </button>
                                        <div class="mt-3">
                                            <small class="text-muted">
                                                <i class="ri-information-line me-1"></i>
                                                Formats acceptés: JPG, PNG, WebP. Taille max: 5MB par photo.
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12" id="photo-preview-container" style="display: none;">
                                    <h6 class="mb-3">
                                        <i class="ri-image-2-line me-2 text-primary"></i>
                                        Aperçu des photos
                                    </h6>
                                    <div class="row g-3" id="photo-preview"></div>
                                </div>

                                <div class="col-12">
                                    <div class="alert alert-info border-0">
                                        <h6 class="alert-heading">
                                            <i class="ri-lightbulb-line me-2"></i>
                                            Conseils pour de meilleures photos
                                        </h6>
                                        <ul class="mb-0">
                                            <li>Prenez des photos avec une bonne luminosité naturelle</li>
                                            <li>Incluez les pièces principales (salon, cuisine, chambres)</li>
                                            <li>Montrez les points forts du bien (vue, terrasse, équipements)</li>
                                            <li>Évitez les photos floues ou sombres</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation du Wizard -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <button type="button" class="btn btn-outline-secondary" id="btn-previous" style="display: none;">
                                        <i class="ri-arrow-left-line me-2"></i>Précédent
                                    </button>
                                    <div class="flex-grow-1 text-center">
                                        <span class="text-muted" id="step-info">Étape 1 sur 5</span>
                                    </div>
                                    <button type="button" class="btn btn-primary" id="btn-next">
                                        Suivant<i class="ri-arrow-right-line ms-2"></i>
                                    </button>
                                    <button type="submit" class="btn btn-success" id="btn-submit" style="display: none;">
                                        <i class="ri-calculator-line me-2"></i>Calculer l'Estimation
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- Scripts et Styles -->
<link rel="stylesheet" href="<?= base_url('assets/libs/leaflet/leaflet.css'); ?>" />
<script src="<?= base_url('assets/libs/leaflet/leaflet.js'); ?>"></script>

<script>
// Variables globales
let currentStep = 1;
const totalSteps = 5;
let map, marker;

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    initializeWizard();
    initializeMap();
    initializePhotoUpload();
});

// Gestion du wizard
function initializeWizard() {
    const nextBtn = document.getElementById('btn-next');
    const prevBtn = document.getElementById('btn-previous');
    const submitBtn = document.getElementById('btn-submit');

    nextBtn.addEventListener('click', function() {
        if (validateCurrentStep()) {
            nextStep();
        }
    });

    prevBtn.addEventListener('click', function() {
        previousStep();
    });

    updateStepDisplay();
}

function nextStep() {
    if (currentStep < totalSteps) {
        currentStep++;
        updateStepDisplay();
        scrollToTop();
    }
}

function previousStep() {
    if (currentStep > 1) {
        currentStep--;
        updateStepDisplay();
        scrollToTop();
    }
}

function updateStepDisplay() {
    // Masquer toutes les étapes
    document.querySelectorAll('.wizard-step').forEach(step => {
        step.classList.remove('active');
    });

    // Afficher l'étape actuelle
    document.getElementById(`step-${currentStep}`).classList.add('active');

    // Mettre à jour les indicateurs d'étapes
    document.querySelectorAll('.step').forEach((step, index) => {
        step.classList.remove('active', 'completed');
        if (index + 1 === currentStep) {
            step.classList.add('active');
        } else if (index + 1 < currentStep) {
            step.classList.add('completed');
        }
    });

    // Mettre à jour la barre de progression
    const progressPercentage = (currentStep / totalSteps) * 100;
    document.querySelector('.progress-line-fill').style.width = `${progressPercentage}%`;

    // Gestion des boutons de navigation
    const nextBtn = document.getElementById('btn-next');
    const prevBtn = document.getElementById('btn-previous');
    const submitBtn = document.getElementById('btn-submit');

    prevBtn.style.display = currentStep > 1 ? 'block' : 'none';
    
    if (currentStep === totalSteps) {
        nextBtn.style.display = 'none';
        submitBtn.style.display = 'block';
    } else {
        nextBtn.style.display = 'block';
        submitBtn.style.display = 'none';
    }

    // Mettre à jour l'info d'étape
    document.getElementById('step-info').textContent = `Étape ${currentStep} sur ${totalSteps}`;
}

function validateCurrentStep() {
    const currentStepElement = document.getElementById(`step-${currentStep}`);
    const requiredInputs = currentStepElement.querySelectorAll('[required]');
    let isValid = true;

    requiredInputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            isValid = false;
        } else {
            input.classList.remove('is-invalid');
        }
    });

    if (!isValid) {
        showNotification('Veuillez remplir tous les champs obligatoires', 'warning');
    }

    return isValid;
}

function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Initialisation de la carte
function initializeMap() {
    map = L.map('map').setView([36.8065, 10.1815], 12); // Tunis par défaut

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Événement de clic sur la carte
    map.on('click', function(e) {
        setMapPosition(e.latlng.lat, e.latlng.lng);
    });

    // Bouton de géolocalisation
    document.getElementById('btn-geoloc-map').addEventListener('click', function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                setMapPosition(lat, lng);
                map.setView([lat, lng], 15);
                showNotification('Position géolocalisée avec succès', 'success');
            }, function() {
                showNotification('Impossible d\'obtenir votre position', 'error');
            });
        } else {
            showNotification('Géolocalisation non supportée par votre navigateur', 'error');
        }
    });
}

function setMapPosition(lat, lng) {
    if (marker) {
        map.removeLayer(marker);
    }

    marker = L.marker([lat, lng]).addTo(map);
    
    document.getElementById('latitude').value = lat.toFixed(6);
    document.getElementById('longitude').value = lng.toFixed(6);
}

// Gestion de l'upload de photos
function initializePhotoUpload() {
    const fileInput = document.getElementById('photo-upload');
    const uploadArea = document.querySelector('.upload-area');
    const previewContainer = document.getElementById('photo-preview-container');
    const previewArea = document.getElementById('photo-preview');

    // Drag & Drop
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.classList.add('bg-primary-subtle');
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('bg-primary-subtle');
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('bg-primary-subtle');
        
        const files = e.dataTransfer.files;
        handleFiles(files);
    });

    fileInput.addEventListener('change', function(e) {
        handleFiles(e.target.files);
    });

    function handleFiles(files) {
        if (files.length > 0) {
            previewContainer.style.display = 'block';
            previewArea.innerHTML = '';

            Array.from(files).forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const photoCard = createPhotoPreview(e.target.result, file.name, index);
                        previewArea.appendChild(photoCard);
                    };
                    reader.readAsDataURL(file);
                }
            });

            showNotification(`${files.length} photo(s) ajoutée(s)`, 'success');
        }
    }

    function createPhotoPreview(src, name, index) {
        const div = document.createElement('div');
        div.className = 'col-md-3';
        div.innerHTML = `
            <div class="card border-0 shadow-sm">
                <img src="${src}" class="card-img-top" style="height: 150px; object-fit: cover;" alt="${name}">
                <div class="card-body p-2">
                    <small class="text-muted text-truncate d-block">${name}</small>
                    <button type="button" class="btn btn-outline-danger btn-sm w-100 mt-2" onclick="removePhoto(${index})">
                        <i class="ri-delete-bin-line me-1"></i>Supprimer
                    </button>
                </div>
            </div>
        `;
        return div;
    }
}

function removePhoto(index) {
    // Logique pour supprimer une photo
    showNotification('Photo supprimée', 'info');
}

// Fonction utilitaire pour les scores
function updateScoreDisplay(input, targetId) {
    document.getElementById(targetId).textContent = `${input.value}/5`;
}

// Fonction de notification
function showNotification(message, type = 'info') {
    const colors = {
        'success': 'success',
        'error': 'danger',
        'warning': 'warning',
        'info': 'info'
    };

    const icons = {
        'success': 'ri-check-circle-line',
        'error': 'ri-error-warning-line',
        'warning': 'ri-alert-line',
        'info': 'ri-information-line'
    };

    const toast = document.createElement('div');
    toast.className = `alert alert-${colors[type]} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        <i class="${icons[type]} me-2"></i>
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
/* Styles du Wizard */
.wizard-steps {
    position: relative;
    z-index: 1;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    position: relative;
    z-index: 2;
    cursor: pointer;
    transition: all 0.3s ease;
}

.step-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 8px;
    transition: all 0.3s ease;
    border: 3px solid #e9ecef;
}

.step-icon i {
    font-size: 20px;
    color: #6c757d;
    transition: all 0.3s ease;
}

.step.active .step-icon {
    background: #4f46e5;
    border-color: #4f46e5;
}

.step.active .step-icon i {
    color: white;
}

.step.completed .step-icon {
    background: #22c55e;
    border-color: #22c55e;
}

.step.completed .step-icon i {
    color: white;
}

.step-title {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 2px;
    color: #374151;
}

.step.active .step-title {
    color: #4f46e5;
}

.step-description {
    font-size: 12px;
    color: #6b7280;
}

.progress-line {
    position: absolute;
    top: 25px;
    left: 0;
    right: 0;
    height: 3px;
    background: #e9ecef;
    z-index: 1;
}

.progress-line-fill {
    height: 100%;
    background: linear-gradient(90deg, #4f46e5, #7c3aed);
    transition: width 0.3s ease;
    border-radius: 3px;
}

/* Étapes du wizard */
.wizard-step {
    display: none;
}

.wizard-step.active {
    display: block;
    animation: fadeInUp 0.3s ease;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Cartes de type de bien */
.type-option input[type="radio"] {
    display: none;
}

.type-card {
    display: block;
    padding: 20px;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: white;
}

.type-card:hover {
    border-color: #4f46e5;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.15);
}

.type-option input[type="radio"]:checked + .type-card {
    border-color: #4f46e5;
    background: linear-gradient(135deg, rgba(79, 70, 229, 0.1), rgba(99, 102, 241, 0.05));
}

.type-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #f8fafc;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 12px;
    transition: all 0.3s ease;
}

.type-icon i {
    font-size: 24px;
    color: #6b7280;
    transition: all 0.3s ease;
}

.type-option input[type="radio"]:checked + .type-card .type-icon {
    background: #4f46e5;
}

.type-option input[type="radio"]:checked + .type-card .type-icon i {
    color: white;
}

.type-title {
    font-weight: 600;
    margin-bottom: 4px;
    color: #374151;
}

.type-description {
    color: #6b7280;
    font-size: 12px;
}

/* Cartes d'état */
.state-option input[type="radio"] {
    display: none;
}

.state-card {
    display: block;
    padding: 16px;
    border: 2px solid;
    border-radius: 8px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: white;
}

.state-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.state-option input[type="radio"]:checked + .state-card {
    background: rgba(var(--bs-primary-rgb), 0.1);
}

.state-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(var(--bs-primary-rgb), 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 8px;
}

.state-icon i {
    font-size: 18px;
}

.state-title {
    font-weight: 600;
    margin-bottom: 2px;
    font-size: 14px;
}

.state-description {
    color: #6b7280;
    font-size: 11px;
}

/* Équipements */
.equipment-check {
    padding: 12px;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.equipment-check:hover {
    background: #f8f9fa;
}

.equipment-check input:checked + label {
    color: #4f46e5;
    font-weight: 600;
}

/* Zone d'upload */
.upload-area {
    transition: all 0.3s ease;
    cursor: pointer;
}

.upload-area:hover {
    border-color: #4f46e5 !important;
    background: rgba(79, 70, 229, 0.05) !important;
}

/* Responsive */
@media (max-width: 768px) {
    .wizard-steps {
        flex-direction: column;
        gap: 20px;
    }
    
    .progress-line {
        display: none;
    }
    
    .step {
        flex-direction: row;
        text-align: left;
    }
    
    .step-content {
        margin-left: 12px;
    }
    
    .type-card, .state-card {
        padding: 12px;
    }
    
    .type-icon {
        width: 40px;
        height: 40px;
        margin-bottom: 8px;
    }
    
    .type-icon i {
        font-size: 18px;
    }
}

/* Validation des champs */
.is-invalid {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
}

.form-range::-webkit-slider-thumb {
    background: #4f46e5;
}

.form-range::-moz-range-thumb {
    background: #4f46e5;
    border: none;
}
</style>
