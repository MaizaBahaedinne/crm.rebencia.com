<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- Page Title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">Propriétés</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Dashboard</a></li>
                                <li class="breadcrumb-item active">Propriétés</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters Row -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <form method="GET" id="filtersForm" class="row g-3">
                                <div class="col-md-3">
                                    <label for="search" class="form-label">Recherche</label>
                                    <input type="text" class="form-control" id="search" name="search" 
                                           placeholder="Titre, adresse..." value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>">
                                </div>
                                <div class="col-md-2">
                                    <label for="type_bien" class="form-label">Type</label>
                                    <select class="form-select" id="type_bien" name="type_bien">
                                        <option value="">Tous types</option>
                                        <?php if (isset($property_types)) : ?>
                                            <?php foreach ($property_types as $type): ?>
                                                <option value="<?= $type->slug ?>" <?php echo ($filters['type_bien'] ?? '') == $type->slug ? 'selected' : ''; ?>><?= $type->name ?></option>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <option value="S+1" <?php echo ($filters['type_bien'] ?? '') == 'S+1' ? 'selected' : ''; ?>>Studio</option>
                                            <option value="S+2" <?php echo ($filters['type_bien'] ?? '') == 'S+2' ? 'selected' : ''; ?>>S+2</option>
                                            <option value="S+3" <?php echo ($filters['type_bien'] ?? '') == 'S+3' ? 'selected' : ''; ?>>S+3</option>
                                            <option value="S+4" <?php echo ($filters['type_bien'] ?? '') == 'S+4' ? 'selected' : ''; ?>>S+4</option>
                                            <option value="S+5" <?php echo ($filters['type_bien'] ?? '') == 'S+5' ? 'selected' : ''; ?>>S+5+</option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="statut_houzez" class="form-label">Statut</label>
                                    <select class="form-select" id="statut_houzez" name="statut_houzez">
                                        <option value="">Tous statuts</option>
                                        <?php if (isset($property_statuses)) : ?>
                                            <?php foreach ($property_statuses as $status): ?>
                                                <option value="<?= $status->slug ?>" <?php echo ($filters['statut_houzez'] ?? '') == $status->slug ? 'selected' : ''; ?>><?= $status->name ?></option>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <option value="for-sale" <?php echo ($filters['statut_houzez'] ?? '') == 'for-sale' ? 'selected' : ''; ?>>À vendre</option>
                                            <option value="for-rent" <?php echo ($filters['statut_houzez'] ?? '') == 'for-rent' ? 'selected' : ''; ?>>À louer</option>
                                            <option value="sold" <?php echo ($filters['statut_houzez'] ?? '') == 'sold' ? 'selected' : ''; ?>>Vendu</option>
                                            <option value="rented" <?php echo ($filters['statut_houzez'] ?? '') == 'rented' ? 'selected' : ''; ?>>Loué</option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="prix_min" class="form-label">Prix min</label>
                                    <input type="number" class="form-control" id="prix_min" name="prix_min" 
                                           placeholder="0" value="<?php echo htmlspecialchars($filters['prix_min'] ?? ''); ?>">
                                </div>
                                <div class="col-md-2">
                                    <label for="prix_max" class="form-label">Prix max</label>
                                    <input type="number" class="form-control" id="prix_max" name="prix_max" 
                                           placeholder="∞" value="<?php echo htmlspecialchars($filters['prix_max'] ?? ''); ?>">
                                </div>
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="ri-search-line"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Results Header -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <h6 class="mb-0 me-3"><?php echo count($properties); ?> propriété(s) trouvée(s)</h6>
                        <div class="btn-group btn-group-sm" role="group">
                            <input type="radio" class="btn-check" name="viewMode" id="gridView" checked>
                            <label class="btn btn-outline-primary" for="gridView">
                                <i class="ri-layout-grid-line"></i>
                            </label>
                            <input type="radio" class="btn-check" name="viewMode" id="listView">
                            <label class="btn btn-outline-primary" for="listView">
                                <i class="ri-list-check"></i>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="ri-sort-desc"></i> Trier par
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?<?php echo http_build_query(array_merge($filters, ['sort' => 'date_desc'])); ?>">Plus récent</a></li>
                            <li><a class="dropdown-item" href="?<?php echo http_build_query(array_merge($filters, ['sort' => 'date_asc'])); ?>">Plus ancien</a></li>
                            <li><a class="dropdown-item" href="?<?php echo http_build_query(array_merge($filters, ['sort' => 'price_asc'])); ?>">Prix croissant</a></li>
                            <li><a class="dropdown-item" href="?<?php echo http_build_query(array_merge($filters, ['sort' => 'price_desc'])); ?>">Prix décroissant</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Properties Grid -->
            <div class="row" id="propertiesContainer">
                <?php if (!empty($properties)) : ?>
                    <?php foreach ($properties as $property) : ?>
                    <div class="col-xl-4 col-lg-6 col-md-6 mb-4 property-card">
                        <div class="card border-0 shadow-sm h-100">
                            <!-- Property Image -->
                            <div class="position-relative">
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <?php if (!empty($property->images['thumbnail'])) : ?>
                                        <img src="<?php echo $property->images['thumbnail']; ?>" alt="<?php echo htmlspecialchars($property->post_title); ?>" class="img-fluid rounded-top" style="width: 100%; height: 100%; object-fit: cover;">
                                    <?php elseif (!empty($property->images['gallery']) && count($property->images['gallery']) > 0) : ?>
                                        <img src="<?php echo $property->images['gallery'][0]; ?>" alt="<?php echo htmlspecialchars($property->post_title); ?>" class="img-fluid rounded-top" style="width: 100%; height: 100%; object-fit: cover;">
                                    <?php else : ?>
                                        <div class="text-center text-muted">
                                            <i class="ri-home-line fs-1"></i>
                                            <p class="mb-0">Aucune image</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Images Count Badge -->
                                <?php 
                                $total_images = 0;
                                if (!empty($property->images['thumbnail'])) $total_images++;
                                if (!empty($property->images['gallery'])) $total_images += count($property->images['gallery']);
                                if ($total_images > 1) : ?>
                                <div class="position-absolute bottom-0 start-0 m-2">
                                    <span class="badge bg-dark bg-opacity-75">
                                        <i class="ri-camera-line me-1"></i><?php echo $total_images; ?>
                                    </span>
                                </div>
                                <?php endif; ?>
                                
                                <!-- Status Badge -->
                                <div class="position-absolute top-0 start-0 m-2">
                                    <?php 
                                    $status_name = 'Non défini';
                                    $status_color = 'secondary';
                                    
                                    if (isset($property->status) && $property->status) {
                                        $status_name = $property->status->name;
                                        // Couleurs basées sur le slug du statut
                                        switch(strtolower($property->status->slug)) {
                                            case 'for-sale':
                                            case 'a-vendre':
                                                $status_color = 'warning'; 
                                                break;
                                            case 'for-rent':
                                            case 'a-louer':
                                                $status_color = 'info'; 
                                                break;
                                            case 'sold':
                                            case 'vendu':
                                                $status_color = 'success'; 
                                                break;
                                            case 'rented':
                                            case 'loue':
                                                $status_color = 'dark'; 
                                                break;
                                            default: 
                                                $status_color = 'secondary'; 
                                                break;
                                        }
                                    }
                                    ?>
                                    <span class="badge bg-<?php echo $status_color; ?>">
                                        <?php echo htmlspecialchars($status_name); ?>
                                    </span>
                                </div>
                                <!-- Price Badge -->
                                <?php if (!empty($property->fave_property_price) && is_numeric($property->fave_property_price)) : ?>
                                <div class="position-absolute top-0 end-0 m-2">
                                    <span class="badge bg-primary fs-6">
                                        <?php echo number_format($property->fave_property_price, 0, ',', ' '); ?> TND
                                    </span>
                                </div>
                                <?php endif; ?>
                            </div>

                            <div class="card-body">
                                <!-- Property Title -->
                                <h5 class="card-title">
                                    <a href="<?php echo base_url('properties/view/' . $property->ID); ?>" class="text-decoration-none">
                                        <?php echo htmlspecialchars($property->post_title ?: 'Propriété sans titre'); ?>
                                    </a>
                                </h5>

                                <!-- Property Details -->
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <small class="text-muted">Type:</small><br>
                                        <span class="fw-medium">
                                            <?php 
                                            if (isset($property->type) && $property->type) {
                                                echo htmlspecialchars($property->type->name);
                                            } else {
                                                echo htmlspecialchars($property->type_bien ?: 'Non spécifié'); 
                                            }
                                            ?>
                                        </span>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Surface:</small><br>
                                        <span class="fw-medium">
                                            <?php echo $property->surface_habitable ? $property->surface_habitable . ' m²' : 'Non spécifiée'; ?>
                                        </span>
                                    </div>
                                </div>

                                <!-- Property Address -->
                                <div class="d-flex align-items-start mb-3">
                                    <i class="ri-map-pin-line text-muted me-2 mt-1"></i>
                                    <small class="text-muted">
                                        <?php echo htmlspecialchars($property->zone_nom ?: 'Adresse non fournie'); ?>
                                    </small>
                                </div>

                                <!-- Property Features -->
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    <?php if (!empty($property->fave_property_bedrooms)) : ?>
                                    <span class="badge bg-light text-dark">
                                        <i class="ri-hotel-bed-line me-1"></i><?php echo $property->fave_property_bedrooms; ?> ch.
                                    </span>
                                    <?php endif; ?>
                                    <?php if (!empty($property->fave_property_bathrooms)) : ?>
                                    <span class="badge bg-light text-dark">
                                        <i class="ri-drop-line me-1"></i><?php echo $property->fave_property_bathrooms; ?> sdb
                                    </span>
                                    <?php endif; ?>
                                    <?php if (!empty($property->fave_property_garage)) : ?>
                                    <span class="badge bg-light text-dark">
                                        <i class="ri-car-line me-1"></i><?php echo $property->fave_property_garage; ?> gar.
                                    </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Card Footer -->
                            <div class="card-footer bg-transparent border-top-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="ri-calendar-line me-1"></i>
                                        <?php echo date('d/m/Y', strtotime($property->post_date)); ?>
                                    </small>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?php echo base_url('properties/view/' . $property->ID); ?>" class="btn btn-outline-primary">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="ri-more-line"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="<?php echo base_url('properties/view/' . $property->ID); ?>"><i class="ri-eye-line me-2"></i>Voir détails</a></li>
                                                <li><a class="dropdown-item" href="#"><i class="ri-edit-line me-2"></i>Modifier</a></li>
                                                <li><a class="dropdown-item" href="#"><i class="ri-share-line me-2"></i>Partager</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-danger" href="#"><i class="ri-delete-bin-line me-2"></i>Supprimer</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="col-12">
                        <div class="text-center py-5">
                            <div class="avatar-xl mx-auto mb-4">
                                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-24">
                                    <i class="ri-home-line"></i>
                                </div>
                            </div>
                            <h5 class="mb-3">Aucune propriété trouvée</h5>
                            <p class="text-muted mb-4">Aucune propriété ne correspond aux critères de recherche sélectionnés.</p>
                            <a href="<?php echo base_url('properties'); ?>" class="btn btn-primary">
                                <i class="ri-refresh-line me-1"></i>Réinitialiser les filtres
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <?php if (!empty($properties) && count($properties) >= 20) : ?>
            <div class="row">
                <div class="col-12">
                    <nav aria-label="Properties pagination">
                        <ul class="pagination justify-content-center">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1">Précédent</a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">Suivant</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle between grid and list view
    const gridView = document.getElementById('gridView');
    const listView = document.getElementById('listView');
    const container = document.getElementById('propertiesContainer');

    listView.addEventListener('change', function() {
        if (this.checked) {
            container.className = 'row list-view';
            document.querySelectorAll('.property-card').forEach(card => {
                card.className = 'col-12 mb-3 property-card';
            });
        }
    });

    gridView.addEventListener('change', function() {
        if (this.checked) {
            container.className = 'row';
            document.querySelectorAll('.property-card').forEach(card => {
                card.className = 'col-xl-4 col-lg-6 col-md-6 mb-4 property-card';
            });
        }
    });

    // Auto-submit form on filter change
    document.getElementById('filtersForm').addEventListener('change', function() {
        this.submit();
    });
});
</script>

<style>
.list-view .card {
    flex-direction: row;
}

.list-view .card-img-top {
    width: 200px;
    height: 150px;
    flex-shrink: 0;
}

.list-view .card-body {
    flex: 1;
}

.property-card:hover {
    transform: translateY(-2px);
    transition: transform 0.2s ease-in-out;
}

.property-card .card {
    transition: all 0.2s ease-in-out;
}

.property-card:hover .card {
    box-shadow: 0 8px 25px rgba(0,0,0,.15) !important;
}
</style>
