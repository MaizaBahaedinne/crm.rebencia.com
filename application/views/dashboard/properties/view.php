<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- Page Title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0"><?php echo htmlspecialchars($property->post_title ?? 'Propriété'); ?></h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="<?php echo base_url('properties'); ?>">Propriétés</a></li>
                                <li class="breadcrumb-item active">Détails</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Property Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <div class="avatar-lg">
                                        <?php if (!empty($property->featured_image)) : ?>
                                            <img src="<?php echo $property->featured_image; ?>" alt="Property" class="img-fluid rounded">
                                        <?php else : ?>
                                            <div class="avatar-title bg-primary-subtle text-primary fs-22 rounded">
                                                <i class="ri-home-line"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col">
                                    <h4 class="mb-1"><?php echo htmlspecialchars($property->post_title ?? 'Propriété sans titre'); ?></h4>
                                    <p class="text-muted mb-2">
                                        <i class="ri-map-pin-line me-1"></i>
                                        <?php echo htmlspecialchars($property->fave_property_address ?? 'Adresse non fournie'); ?>
                                    </p>
                                    <div class="d-flex flex-wrap gap-3">
                                        <?php 
                                        $status_labels = [
                                            'for-sale' => ['label' => 'À vendre', 'class' => 'warning'],
                                            'for-rent' => ['label' => 'À louer', 'class' => 'info'], 
                                            'sold' => ['label' => 'Vendu', 'class' => 'success'],
                                            'rented' => ['label' => 'Loué', 'class' => 'secondary']
                                        ];
                                        $status = $property->fave_property_status ?? 'unknown';
                                        $status_info = isset($status_labels[$status]) ? $status_labels[$status] : ['label' => 'Statut inconnu', 'class' => 'light'];
                                        ?>
                                        <span class="badge bg-<?php echo $status_info['class']; ?>-subtle text-<?php echo $status_info['class']; ?>">
                                            <?php echo $status_info['label']; ?>
                                        </span>
                                        <?php if (!empty($property->fave_property_price) && is_numeric($property->fave_property_price)) : ?>
                                        <span class="badge bg-success-subtle text-success fs-6">
                                            <i class="ri-price-tag-3-line me-1"></i><?php echo number_format($property->fave_property_price, 0, ',', ' '); ?> €
                                        </span>
                                        <?php endif; ?>
                                        <span class="badge bg-info-subtle text-info">
                                            <i class="ri-calendar-line me-1"></i>Ajouté le <?php echo date('d/m/Y', strtotime($property->post_date)); ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-primary">
                                            <i class="ri-edit-line me-1"></i>Modifier
                                        </button>
                                        <div class="dropdown">
                                            <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="ri-more-2-line"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#"><i class="ri-share-line me-2"></i>Partager</a></li>
                                                <li><a class="dropdown-item" href="#"><i class="ri-download-line me-2"></i>Télécharger</a></li>
                                                <li><a class="dropdown-item" href="#"><i class="ri-printer-line me-2"></i>Imprimer</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-danger" href="#"><i class="ri-delete-bin-line me-2"></i>Supprimer</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <!-- Property Images Gallery -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ri-image-line me-1"></i>Galerie photos
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div id="propertyCarousel" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    <?php if (!empty($property->gallery_images)) : ?>
                                        <?php foreach ($property->gallery_images as $index => $image) : ?>
                                        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                            <img src="<?php echo $image; ?>" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="Property Image <?php echo $index + 1; ?>">
                                        </div>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <div class="carousel-item active">
                                            <div class="d-flex align-items-center justify-content-center bg-light text-muted" style="height: 400px;">
                                                <div class="text-center">
                                                    <i class="ri-image-line fs-1"></i>
                                                    <p class="mt-2">Aucune image disponible</p>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <?php if (!empty($property->gallery_images) && count($property->gallery_images) > 1) : ?>
                                <button class="carousel-control-prev" type="button" data-bs-target="#propertyCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon"></span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#propertyCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon"></span>
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Property Description -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ri-file-text-line me-1"></i>Description
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($property->post_content)) : ?>
                                <div class="property-description">
                                    <?php echo nl2br(htmlspecialchars($property->post_content)); ?>
                                </div>
                            <?php else : ?>
                                <p class="text-muted mb-0">Aucune description disponible pour cette propriété.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Property Features -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ri-building-line me-1"></i>Caractéristiques
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <?php if (!empty($property->fave_property_bedrooms)) : ?>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center p-3 border rounded">
                                        <i class="ri-hotel-bed-line fs-4 text-primary me-3"></i>
                                        <div>
                                            <h6 class="mb-0"><?php echo $property->fave_property_bedrooms; ?> Chambres</h6>
                                            <small class="text-muted">Nombre de chambres</small>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <?php if (!empty($property->fave_property_bathrooms)) : ?>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center p-3 border rounded">
                                        <i class="ri-drop-line fs-4 text-info me-3"></i>
                                        <div>
                                            <h6 class="mb-0"><?php echo $property->fave_property_bathrooms; ?> Salles de bain</h6>
                                            <small class="text-muted">Nombre de salles de bain</small>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <?php if (!empty($property->fave_property_size)) : ?>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center p-3 border rounded">
                                        <i class="ri-ruler-line fs-4 text-warning me-3"></i>
                                        <div>
                                            <h6 class="mb-0"><?php echo $property->fave_property_size; ?> m²</h6>
                                            <small class="text-muted">Surface habitable</small>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <?php if (!empty($property->fave_property_garage)) : ?>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center p-3 border rounded">
                                        <i class="ri-car-line fs-4 text-success me-3"></i>
                                        <div>
                                            <h6 class="mb-0"><?php echo $property->fave_property_garage; ?> Garage(s)</h6>
                                            <small class="text-muted">Places de parking</small>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <?php if (!empty($property->fave_property_year)) : ?>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center p-3 border rounded">
                                        <i class="ri-calendar-2-line fs-4 text-secondary me-3"></i>
                                        <div>
                                            <h6 class="mb-0"><?php echo $property->fave_property_year; ?></h6>
                                            <small class="text-muted">Année de construction</small>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <?php if (!empty($property->fave_property_land)) : ?>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center p-3 border rounded">
                                        <i class="ri-landscape-line fs-4 text-success me-3"></i>
                                        <div>
                                            <h6 class="mb-0"><?php echo $property->fave_property_land; ?> m²</h6>
                                            <small class="text-muted">Surface terrain</small>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Similar Properties -->
                    <?php if (!empty($similar_properties)) : ?>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ri-home-4-line me-1"></i>Propriétés similaires
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php foreach (array_slice($similar_properties, 0, 3) as $similar) : ?>
                                <div class="col-md-4">
                                    <div class="card border h-100">
                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 120px;">
                                            <i class="ri-home-line fs-1 text-muted"></i>
                                        </div>
                                        <div class="card-body p-3">
                                            <h6 class="card-title mb-2">
                                                <a href="<?php echo base_url('properties/view/' . $similar->ID); ?>" class="text-decoration-none">
                                                    <?php echo htmlspecialchars($similar->post_title); ?>
                                                </a>
                                            </h6>
                                            <small class="text-muted d-block mb-2">
                                                <i class="ri-map-pin-line me-1"></i>
                                                <?php echo htmlspecialchars($similar->fave_property_address ?: 'Adresse non fournie'); ?>
                                            </small>
                                            <?php if (!empty($similar->fave_property_price)) : ?>
                                            <strong class="text-primary">
                                                <?php echo number_format($similar->fave_property_price, 0, ',', ' '); ?> €
                                            </strong>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Property Price -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ri-price-tag-3-line me-1"></i>Prix et détails
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($property->fave_property_price) && is_numeric($property->fave_property_price)) : ?>
                            <div class="text-center mb-3">
                                <h3 class="text-primary mb-0"><?php echo number_format($property->fave_property_price, 0, ',', ' '); ?> €</h3>
                                <small class="text-muted">Prix affiché</small>
                            </div>
                            <?php endif; ?>

                            <div class="list-group list-group-flush">
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="text-muted">Référence</span>
                                    <strong>#<?php echo $property->ID; ?></strong>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="text-muted">Type</span>
                                    <strong>
                                        <?php 
                                        $bedrooms = $property->fave_property_bedrooms ?? 0;
                                        echo $bedrooms > 0 ? 'S+' . $bedrooms : 'Non spécifié';
                                        ?>
                                    </strong>
                                </div>
                                <?php if (!empty($property->fave_property_size)) : ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="text-muted">Surface</span>
                                    <strong><?php echo $property->fave_property_size; ?> m²</strong>
                                </div>
                                <?php endif; ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="text-muted">Statut</span>
                                    <span class="badge bg-<?php echo $status_info['class']; ?>">
                                        <?php echo $status_info['label']; ?>
                                    </span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="text-muted">Publié le</span>
                                    <strong><?php echo date('d/m/Y', strtotime($property->post_date)); ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Agent Information -->
                    <?php if (!empty($agent)) : ?>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ri-user-3-line me-1"></i>Agent responsable
                            </h5>
                        </div>
                        <div class="card-body text-center">
                            <div class="avatar-lg mx-auto mb-3">
                                <?php if (!empty($agent->agent_avatar)) : ?>
                                    <img src="<?php echo $agent->agent_avatar; ?>" alt="Agent" class="img-fluid rounded-circle">
                                <?php else : ?>
                                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                        <?php echo strtoupper(substr($agent->agent_name ?? 'A', 0, 2)); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <h6 class="mb-1"><?php echo htmlspecialchars($agent->agent_name ?? 'Agent non identifié'); ?></h6>
                            <p class="text-muted mb-3"><?php echo htmlspecialchars($agent->position ?? 'Agent immobilier'); ?></p>
                            
                            <div class="d-grid gap-2">
                                <?php if (!empty($agent->phone)) : ?>
                                <a href="tel:<?php echo $agent->phone; ?>" class="btn btn-outline-primary btn-sm">
                                    <i class="ri-phone-line me-1"></i><?php echo $agent->phone; ?>
                                </a>
                                <?php endif; ?>
                                <?php if (!empty($agent->agent_email)) : ?>
                                <a href="mailto:<?php echo $agent->agent_email; ?>" class="btn btn-outline-secondary btn-sm">
                                    <i class="ri-mail-line me-1"></i>Contacter
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Agency Information -->
                    <?php if (!empty($agency)) : ?>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ri-building-line me-1"></i>Agence
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-sm me-3">
                                    <?php if (!empty($agency->agency_logo)) : ?>
                                        <img src="<?php echo $agency->agency_logo; ?>" alt="Agency" class="img-fluid rounded">
                                    <?php else : ?>
                                        <div class="avatar-title bg-warning-subtle text-warning rounded">
                                            <i class="ri-building-line"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <h6 class="mb-1">
                                        <a href="<?php echo base_url('agency/view/' . $agency->agency_id); ?>" class="text-decoration-none">
                                            <?php echo htmlspecialchars($agency->agency_name); ?>
                                        </a>
                                    </h6>
                                    <small class="text-muted"><?php echo htmlspecialchars($agency->agency_address ?? 'Adresse non fournie'); ?></small>
                                </div>
                            </div>
                            
                            <?php if (!empty($agency->phone) || !empty($agency->agency_email)) : ?>
                            <div class="d-grid gap-2">
                                <?php if (!empty($agency->phone)) : ?>
                                <a href="tel:<?php echo $agency->phone; ?>" class="btn btn-outline-warning btn-sm">
                                    <i class="ri-phone-line me-1"></i><?php echo $agency->phone; ?>
                                </a>
                                <?php endif; ?>
                                <?php if (!empty($agency->agency_email)) : ?>
                                <a href="mailto:<?php echo $agency->agency_email; ?>" class="btn btn-outline-secondary btn-sm">
                                    <i class="ri-mail-line me-1"></i>Contacter l'agence
                                </a>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Quick Actions -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ri-flashlight-line me-1"></i>Actions rapides
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button class="btn btn-primary">
                                    <i class="ri-edit-line me-2"></i>Modifier la propriété
                                </button>
                                <button class="btn btn-outline-info">
                                    <i class="ri-share-line me-2"></i>Partager
                                </button>
                                <button class="btn btn-outline-secondary">
                                    <i class="ri-printer-line me-2"></i>Imprimer la fiche
                                </button>
                                <hr>
                                <button class="btn btn-outline-danger">
                                    <i class="ri-delete-bin-line me-2"></i>Supprimer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.property-description {
    line-height: 1.6;
    font-size: 15px;
}

.carousel-item img {
    border-radius: 0.375rem;
}

.list-group-item {
    border-left: none !important;
    border-right: none !important;
}

.list-group-item:first-child {
    border-top: none !important;
}

.list-group-item:last-child {
    border-bottom: none !important;
}
</style>
