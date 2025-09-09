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
                                        <?php if (!empty($property_images['thumbnail'])) : ?>
                                            <img src="<?php echo $property_images['thumbnail']; ?>" alt="Property" class="img-fluid rounded">
                                        <?php elseif (!empty($property_images['gallery']) && count($property_images['gallery']) > 0) : ?>
                                            <img src="<?php echo $property_images['gallery'][0]; ?>" alt="Property" class="img-fluid rounded">
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
                                        // Utilisation du vrai statut depuis la base de données
                                        $status_name = 'Statut inconnu';
                                        $status_class = 'secondary';
                                        
                                        if (isset($property_status) && $property_status) {
                                            $status_name = $property_status->name;
                                            // Couleurs basées sur le slug du statut
                                            switch(strtolower($property_status->slug)) {
                                                case 'for-sale':
                                                case 'a-vendre':
                                                    $status_class = 'warning'; 
                                                    break;
                                                case 'for-rent':
                                                case 'a-louer':
                                                    $status_class = 'info'; 
                                                    break;
                                                case 'sold':
                                                case 'vendu':
                                                    $status_class = 'success'; 
                                                    break;
                                                case 'rented':
                                                case 'loue':
                                                    $status_class = 'dark'; 
                                                    break;
                                                default: 
                                                    $status_class = 'secondary'; 
                                                    break;
                                            }
                                        }
                                        ?>
                                        <span class="badge bg-<?php echo $status_class; ?>-subtle text-<?php echo $status_class; ?>">
                                            <?php echo htmlspecialchars($status_name); ?>
                                        </span>
                                        <?php if (!empty($property->fave_property_price) && is_numeric($property->fave_property_price)) : ?>
                                        <span class="badge bg-success-subtle text-success fs-6">
                                            <i class="ri-price-tag-3-line me-1"></i><?php echo number_format($property->fave_property_price, 0, ',', ' '); ?> TND
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
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="ri-image-line me-1"></i>Galerie photos
                            </h5>
                            <div class="btn-group btn-group-sm" role="group">
                                <input type="radio" class="btn-check" name="viewMode" id="carouselView" checked>
                                <label class="btn btn-outline-primary" for="carouselView">
                                    <i class="ri-slideshow-line"></i> Carousel
                                </label>
                                <input type="radio" class="btn-check" name="viewMode" id="gridView">
                                <label class="btn btn-outline-primary" for="gridView">
                                    <i class="ri-layout-grid-line"></i> Toutes
                                </label>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <?php 
                            $all_images = [];
                            
                            // DEBUG: Afficher les données récupérées
                            echo "<!-- DEBUG: property_images = " . print_r($property_images ?? [], true) . " -->";
                            
                            // Ajouter l'image principale si elle existe
                            if (!empty($property_images['thumbnail'])) {
                                $all_images[] = $property_images['thumbnail'];
                                echo "<!-- DEBUG: Added thumbnail: {$property_images['thumbnail']} -->";
                            }
                            
                            // Ajouter les images de galerie si elles existent
                            if (!empty($property_images['gallery'])) {
                                echo "<!-- DEBUG: Found gallery with " . count($property_images['gallery']) . " images -->";
                                foreach ($property_images['gallery'] as $gallery_img) {
                                    if (!in_array($gallery_img, $all_images)) {
                                        $all_images[] = $gallery_img;
                                        echo "<!-- DEBUG: Added gallery image: $gallery_img -->";
                                    }
                                }
                            } else {
                                echo "<!-- DEBUG: No gallery images found -->";
                            }
                            
                            echo "<!-- DEBUG: Total images: " . count($all_images) . " -->";
                            ?>
                            
                            <!-- Vue Carousel -->
                            <div id="carouselContainer" class="gallery-view">
                                <div id="propertyCarousel" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                        <?php if (!empty($all_images)) : ?>
                                            <?php foreach ($all_images as $index => $image) : ?>
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
                                    <?php if (!empty($all_images) && count($all_images) > 1) : ?>
                                    <button class="carousel-control-prev" type="button" data-bs-target="#propertyCarousel" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon"></span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#propertyCarousel" data-bs-slide="next">
                                        <span class="carousel-control-next-icon"></span>
                                    </button>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Thumbnails Gallery -->
                                <?php if (!empty($all_images) && count($all_images) > 1) : ?>
                                <div class="mt-3 px-3">
                                    <div class="row g-2">
                                        <?php foreach ($all_images as $index => $image) : ?>
                                        <div class="col-2">
                                            <img src="<?php echo $image; ?>" 
                                                 class="img-fluid rounded thumbnail-gallery cursor-pointer <?php echo $index === 0 ? 'border border-primary border-2' : 'border'; ?>" 
                                                 style="height: 60px; object-fit: cover; width: 100%;" 
                                                 onclick="goToSlide(<?php echo $index; ?>)"
                                                 data-slide="<?php echo $index; ?>"
                                                 alt="Thumbnail <?php echo $index + 1; ?>">
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Vue Grille - Toutes les images -->
                            <div id="gridContainer" class="gallery-view d-none p-3">
                                <?php if (!empty($all_images)) : ?>
                                    <div class="row g-3">
                                        <?php foreach ($all_images as $index => $image) : ?>
                                        <div class="col-md-6 col-lg-4">
                                            <div class="image-container position-relative">
                                                <img src="<?php echo $image; ?>" 
                                                     class="img-fluid rounded shadow-sm w-100 gallery-image" 
                                                     style="height: 200px; object-fit: cover; cursor: pointer;" 
                                                     alt="Property Image <?php echo $index + 1; ?>"
                                                     onclick="openLightbox(<?php echo $index; ?>)">
                                                <div class="image-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-50 opacity-0 rounded">
                                                    <i class="ri-zoom-in-line text-white fs-4"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else : ?>
                                    <div class="text-center py-5">
                                        <i class="ri-image-line fs-1 text-muted"></i>
                                        <p class="text-muted mt-2">Aucune image disponible</p>
                                    </div>
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
                                    <?php 
                                    // Nettoyer la description : supprimer les attributs data-start et data-end
                                    $description = $property->post_content;
                                    $description = preg_replace('/\s*data-start="[^"]*"/', '', $description);
                                    $description = preg_replace('/\s*data-end="[^"]*"/', '', $description);
                                    
                                    // Si c'est du HTML, l'afficher directement, sinon l'échapper
                                    if (strip_tags($description) !== $description) {
                                        echo $description;
                                    } else {
                                        echo nl2br(htmlspecialchars($description));
                                    }
                                    ?>
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
                                                <?php echo number_format($similar->fave_property_price, 0, ',', ' '); ?> TND
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
                                <h3 class="text-primary mb-0"><?php echo number_format($property->fave_property_price, 0, ',', ' '); ?> TND</h3>
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
                                        if (isset($property_type) && $property_type) {
                                            echo htmlspecialchars($property_type->name);
                                        } else {
                                            $bedrooms = $property->fave_property_bedrooms ?? 0;
                                            echo $bedrooms > 0 ? 'S+' . $bedrooms : 'Non spécifié';
                                        }
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
                                    <span class="badge bg-<?php echo $status_class; ?>">
                                        <?php echo htmlspecialchars($status_name); ?>
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
                                <img src="<?php echo get_agent_avatar_url($agent); ?>" alt="Agent" class="img-fluid rounded-circle">
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
                                    <img src="<?php echo get_agency_logo_url($agency); ?>" alt="Agency" class="img-fluid rounded">
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

<!-- Lightbox Modal pour voir les images en grand -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-header border-0">
                <h5 class="modal-title text-white" id="imageModalTitle">Image de la propriété</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div id="lightboxCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php if (!empty($all_images)) : ?>
                            <?php foreach ($all_images as $index => $image) : ?>
                            <div class="carousel-item">
                                <img src="<?php echo $image; ?>" class="d-block w-100 rounded" style="max-height: 80vh; object-fit: contain;" alt="Property Image <?php echo $index + 1; ?>">
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($all_images) && count($all_images) > 1) : ?>
                    <button class="carousel-control-prev" type="button" data-bs-target="#lightboxCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#lightboxCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                    <div class="carousel-indicators">
                        <?php foreach ($all_images as $index => $image) : ?>
                        <button type="button" data-bs-target="#lightboxCarousel" data-bs-slide-to="<?php echo $index; ?>" class="<?php echo $index === 0 ? 'active' : ''; ?>"></button>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
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

.property-description p {
    margin-bottom: 1rem;
}

.property-description strong {
    color: #495057;
    font-weight: 600;
}

.property-description br {
    line-height: 1.8;
}

.property-description ul, .property-description ol {
    padding-left: 1.5rem;
    margin-bottom: 1rem;
}

.property-description li {
    margin-bottom: 0.5rem;
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

.thumbnail-gallery {
    cursor: pointer;
    transition: all 0.3s ease;
}

.thumbnail-gallery:hover {
    opacity: 0.8;
    transform: scale(1.05);
}

.gallery-image:hover .image-overlay {
    opacity: 1 !important;
}

.image-container:hover .image-overlay {
    opacity: 1;
    transition: opacity 0.3s ease;
}
</style>

<script>
// Variables globales
let allImages = <?php echo json_encode($all_images ?? []); ?>;

// Basculer entre les vues carousel et grille
document.getElementById('carouselView').addEventListener('change', function() {
    if (this.checked) {
        document.getElementById('carouselContainer').classList.remove('d-none');
        document.getElementById('gridContainer').classList.add('d-none');
    }
});

document.getElementById('gridView').addEventListener('change', function() {
    if (this.checked) {
        document.getElementById('carouselContainer').classList.add('d-none');
        document.getElementById('gridContainer').classList.remove('d-none');
    }
});

// Navigation du carousel principal
function goToSlide(slideIndex) {
    // Aller à la slide spécifiée
    const carousel = new bootstrap.Carousel(document.getElementById('propertyCarousel'));
    carousel.to(slideIndex);
    
    // Mettre à jour les borders des thumbnails
    document.querySelectorAll('.thumbnail-gallery').forEach((thumb, index) => {
        if (index === slideIndex) {
            thumb.classList.add('border-primary', 'border-2');
            thumb.classList.remove('border');
        } else {
            thumb.classList.remove('border-primary', 'border-2');
            thumb.classList.add('border');
        }
    });
}

// Ouvrir la lightbox avec une image spécifique
function openLightbox(imageIndex) {
    const lightboxCarousel = new bootstrap.Carousel(document.getElementById('lightboxCarousel'));
    lightboxCarousel.to(imageIndex);
    
    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
    modal.show();
    
    // Mettre à jour le titre
    document.getElementById('imageModalTitle').textContent = `Image ${imageIndex + 1} sur ${allImages.length}`;
}

// Écouter les changements de slide du carousel pour synchroniser les thumbnails
document.getElementById('propertyCarousel').addEventListener('slide.bs.carousel', function (e) {
    const slideIndex = e.to;
    document.querySelectorAll('.thumbnail-gallery').forEach((thumb, index) => {
        if (index === slideIndex) {
            thumb.classList.add('border-primary', 'border-2');
            thumb.classList.remove('border');
        } else {
            thumb.classList.remove('border-primary', 'border-2');
            thumb.classList.add('border');
        }
    });
});

// Mettre à jour le titre de la lightbox lors du changement d'image
document.getElementById('lightboxCarousel').addEventListener('slide.bs.carousel', function (e) {
    const imageIndex = e.to;
    document.getElementById('imageModalTitle').textContent = `Image ${imageIndex + 1} sur ${allImages.length}`;
});

// Navigation au clavier dans la lightbox
document.addEventListener('keydown', function(e) {
    const modal = document.getElementById('imageModal');
    if (modal.classList.contains('show')) {
        if (e.key === 'ArrowLeft') {
            const carousel = bootstrap.Carousel.getInstance(document.getElementById('lightboxCarousel'));
            carousel.prev();
        } else if (e.key === 'ArrowRight') {
            const carousel = bootstrap.Carousel.getInstance(document.getElementById('lightboxCarousel'));
            carousel.next();
        }
    }
});
</script>
