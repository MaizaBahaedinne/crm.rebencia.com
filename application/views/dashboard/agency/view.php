<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <style>
                .agency-description p {
                    margin-bottom: 1rem;
                    line-height: 1.6;
                    color: #6c757d;
                }
                .agency-description b {
                    color: #495057 !important;
                }
                .agency-description p:last-child {
                    margin-bottom: 0;
                }
            </style>
            
            <!-- Page Title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0"><?php echo htmlspecialchars($agency->agency_name); ?></h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard/agency'); ?>">Agences</a></li>
                                <li class="breadcrumb-item active">Détails</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Agency Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <div class="avatar-lg">
                                        <img src="<?php echo get_agency_logo_url($agency); ?>" alt="<?php echo htmlspecialchars($agency->agency_name); ?>" class="img-fluid rounded">
                                    </div>
                                </div>
                                <div class="col">
                                    <h4 class="mb-1"><?php echo htmlspecialchars($agency->agency_name); ?></h4>
                                    <?php 
                                    // Fonction pour nettoyer le contenu WordPress Gutenberg
                                    function clean_gutenberg_content($content) {
                                        if (empty($content)) return 'Aucune description disponible';
                                        
                                        // Supprimer les blocs WordPress Gutenberg
                                        $content = preg_replace('/<!-- wp:[\s\S]*? -->/m', '', $content);
                                        $content = preg_replace('/<!-- \/wp:[\s\S]*? -->/m', '', $content);
                                        
                                        // Nettoyer les balises HTML vides
                                        $content = preg_replace('/<p>\s*<\/p>/i', '', $content);
                                        $content = preg_replace('/<p><\/p>/i', '', $content);
                                        
                                        // Convertir les balises <strong> en <b> et ajouter des classes Bootstrap
                                        $content = str_replace('<strong>', '<b class="fw-bold text-dark">', $content);
                                        $content = str_replace('</strong>', '</b>', $content);
                                        
                                        // Ajouter des classes aux paragraphes
                                        $content = preg_replace('/<p>/i', '<p class="mb-2">', $content);
                                        
                                        // Nettoyer les espaces multiples
                                        $content = preg_replace('/\s+/', ' ', $content);
                                        $content = trim($content);
                                        
                                        return $content ?: 'Aucune description disponible';
                                    }
                                    
                                    $clean_description = clean_gutenberg_content($agency->agency_description);
                                    ?>
                                    <div class="text-muted mb-2">
                                        <?php echo $clean_description; ?>
                                    </div>
                                    <div class="d-flex flex-wrap gap-3">
                                        <?php if (!empty($agency->agency_email)) : ?>
                                        <span class="badge bg-info-subtle text-info">
                                            <i class="ri-mail-line me-1"></i><?php echo htmlspecialchars($agency->agency_email); ?>
                                        </span>
                                        <?php endif; ?>
                                        <?php if (!empty($agency->phone)) : ?>
                                        <span class="badge bg-success-subtle text-success">
                                            <i class="ri-phone-line me-1"></i><?php echo htmlspecialchars($agency->phone); ?>
                                        </span>
                                        <?php endif; ?>
                                        <span class="badge bg-warning-subtle text-warning">
                                            <i class="ri-calendar-line me-1"></i>Membre depuis <?php echo date('M Y', strtotime($agency->created_date)); ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="d-flex gap-2">
                                        <a href="<?php echo base_url('agency/agents/' . $agency->agency_id); ?>" class="btn btn-primary">
                                            <i class="ri-user-3-line me-1"></i>Voir agents
                                        </a>
                                        <div class="dropdown">
                                            <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="ri-more-2-line"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#"><i class="ri-edit-line me-2"></i>Modifier</a></li>
                                                <li><a class="dropdown-item" href="#"><i class="ri-mail-line me-2"></i>Envoyer email</a></li>
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

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-primary-subtle rounded fs-3">
                                        <i class="ri-user-3-line text-primary"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">Agents</p>
                                    <h4 class="mb-0"><?php echo $agency->agents_count ?? 0; ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-warning-subtle rounded fs-3">
                                        <i class="ri-home-line text-warning"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">Propriétés</p>
                                    <h4 class="mb-0"><?php echo $agency->properties_count ?? 0; ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-info-subtle rounded fs-3">
                                        <i class="ri-building-line text-info"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">Actives</p>
                                    <h4 class="mb-0"><?php echo $agency->active_properties ?? 0; ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-success-subtle rounded fs-3">
                                        <i class="ri-check-double-line text-success"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">Ventes</p>
                                    <h4 class="mb-0"><?php echo $agency->sales_count ?? 0; ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Agency Description Section -->
            <?php if (!empty($agency->agency_description) && strlen(trim(strip_tags($agency->agency_description))) > 50) : ?>
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-transparent">
                            <h5 class="card-title mb-0">
                                <i class="ri-information-line me-2"></i>À propos de <?php echo htmlspecialchars($agency->agency_name); ?>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="agency-description">
                                <?php echo clean_gutenberg_content($agency->agency_description); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="row">
                <!-- Agency Details -->
                <div class="col-lg-8">
                    <!-- Contact Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ri-contacts-book-line me-1"></i>Informations de contact
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="text-muted mb-1">Email</label>
                                        <p class="mb-0">
                                            <?php if (!empty($agency->agency_email)) : ?>
                                                <a href="mailto:<?php echo $agency->agency_email; ?>" class="text-primary">
                                                    <?php echo htmlspecialchars($agency->agency_email); ?>
                                                </a>
                                            <?php else : ?>
                                                <span class="text-muted">Non fourni</span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted mb-1">Téléphone</label>
                                        <p class="mb-0">
                                            <?php if (!empty($agency->phone)) : ?>
                                                <a href="tel:<?php echo $agency->phone; ?>" class="text-primary">
                                                    <?php echo htmlspecialchars($agency->phone); ?>
                                                </a>
                                            <?php else : ?>
                                                <span class="text-muted">Non fourni</span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted mb-1">Mobile</label>
                                        <p class="mb-0">
                                            <?php if (!empty($agency->mobile)) : ?>
                                                <a href="tel:<?php echo $agency->mobile; ?>" class="text-primary">
                                                    <?php echo htmlspecialchars($agency->mobile); ?>
                                                </a>
                                            <?php else : ?>
                                                <span class="text-muted">Non fourni</span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="text-muted mb-1">Site web</label>
                                        <p class="mb-0">
                                            <?php if (!empty($agency->website)) : ?>
                                                <a href="<?php echo $agency->website; ?>" target="_blank" class="text-primary">
                                                    <?php echo htmlspecialchars($agency->website); ?> 
                                                    <i class="ri-external-link-line ms-1"></i>
                                                </a>
                                            <?php else : ?>
                                                <span class="text-muted">Non fourni</span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted mb-1">Adresse</label>
                                        <p class="mb-0">
                                            <?php if (!empty($agency->agency_address)) : ?>
                                                <?php echo htmlspecialchars($agency->agency_address); ?>
                                            <?php else : ?>
                                                <span class="text-muted">Non fournie</span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted mb-1">Réseaux sociaux</label>
                                        <div class="d-flex gap-2">
                                            <?php if (!empty($agency->facebook)) : ?>
                                            <a href="<?php echo $agency->facebook; ?>" target="_blank" class="btn btn-soft-primary btn-sm">
                                                <i class="ri-facebook-line"></i>
                                            </a>
                                            <?php endif; ?>
                                            <?php if (!empty($agency->twitter)) : ?>
                                            <a href="<?php echo $agency->twitter; ?>" target="_blank" class="btn btn-soft-info btn-sm">
                                                <i class="ri-twitter-line"></i>
                                            </a>
                                            <?php endif; ?>
                                            <?php if (!empty($agency->linkedin)) : ?>
                                            <a href="<?php echo $agency->linkedin; ?>" target="_blank" class="btn btn-soft-secondary btn-sm">
                                                <i class="ri-linkedin-line"></i>
                                            </a>
                                            <?php endif; ?>
                                            <?php if (empty($agency->facebook) && empty($agency->twitter) && empty($agency->linkedin)) : ?>
                                            <span class="text-muted">Aucun réseau social</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Agents -->
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="ri-user-3-line me-1"></i>Agents de l'agence
                            </h5>
                            <a href="<?php echo base_url('agency/agents/' . $agency->agency_id); ?>" class="btn btn-sm btn-primary">
                                Voir tout <i class="ri-arrow-right-line ms-1"></i>
                            </a>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($agents)) : ?>
                                <!-- DEBUG TEMPORAIRE -->
                                <?php if (isset($_GET['debug'])) : ?>
                                <div class="alert alert-info">
                                    <h6>Debug Agents (<?php echo count($agents); ?> agents trouvés)</h6>
                                    <?php foreach (array_slice($agents, 0, 3) as $i => $agent) : ?>
                                        <p><strong>Agent <?php echo $i+1; ?>:</strong><br>
                                        - Nom: <?php echo htmlspecialchars($agent->agent_name ?? 'N/A'); ?><br>
                                        - Email: <?php echo htmlspecialchars($agent->agent_email ?? 'N/A'); ?><br>
                                        - Avatar brut: <?php echo htmlspecialchars($agent->agent_avatar ?? 'N/A'); ?><br>
                                        - Avatar helper: <?php echo htmlspecialchars(get_agent_avatar_url($agent)); ?></p>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
                                <!-- FIN DEBUG -->
                                <div class="row">
                                    <?php foreach (array_slice($agents, 0, 6) as $agent) : ?>
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="d-flex align-items-center p-2 border rounded">
                                            <div class="avatar-sm me-3">
                                                <img src="<?php echo get_agent_avatar_url($agent); ?>" alt="" class="img-fluid rounded-circle">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1"><?php echo htmlspecialchars($agent->agent_name ?: 'Agent sans nom'); ?></h6>
                                                <p class="text-muted mb-0 small"><?php echo htmlspecialchars($agent->position ?: 'Agent immobilier'); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else : ?>
                                <div class="text-center py-4">
                                    <div class="avatar-md mx-auto mb-3">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                            <i class="ri-user-3-line"></i>
                                        </div>
                                    </div>
                                    <h6 class="mb-1">Aucun agent</h6>
                                    <p class="text-muted mb-0">Cette agence n'a pas encore d'agents enregistrés.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Recent Properties -->
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="ri-home-line me-1"></i>Propriétés récentes
                            </h5>
                            <a href="<?php echo base_url('agency/properties/' . $agency->agency_id); ?>" class="btn btn-sm btn-primary">
                                Voir tout <i class="ri-arrow-right-line ms-1"></i>
                            </a>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($properties)) : ?>
                                <div class="table-responsive">
                                    <table class="table table-borderless table-nowrap align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th scope="col">Propriété</th>
                                                <th scope="col">Type</th>
                                                <th scope="col">Prix</th>
                                                <th scope="col">Statut</th>
                                                <th scope="col">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($properties as $property) : ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm me-3">
                                                            <div class="avatar-title bg-warning-subtle text-warning rounded">
                                                                <i class="ri-home-line"></i>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-1"><?php echo htmlspecialchars($property->title ?? 'Propriété sans titre'); ?></h6>
                                                            <p class="text-muted mb-0 small"><?php echo htmlspecialchars($property->address ?? 'Adresse non fournie'); ?></p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><?php echo htmlspecialchars($property->type ?? 'Non spécifié'); ?></td>
                                                <td><?php echo htmlspecialchars($property->price ?? 'Prix sur demande'); ?></td>
                                                <td>
                                                    <span class="badge bg-<?php echo ($property->status ?? '') === 'sold' ? 'success' : (($property->status ?? '') === 'for-sale' ? 'warning' : 'info'); ?>">
                                                        <?php 
                                                        $status_labels = [
                                                            'for-sale' => 'À vendre',
                                                            'for-rent' => 'À louer', 
                                                            'sold' => 'Vendu',
                                                            'rented' => 'Loué'
                                                        ];
                                                        echo $status_labels[$property->status ?? ''] ?? 'Inconnu';
                                                        ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button type="button" class="btn btn-outline-primary">
                                                            <i class="ri-eye-line"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-outline-secondary">
                                                            <i class="ri-edit-line"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else : ?>
                                <div class="text-center py-4">
                                    <div class="avatar-md mx-auto mb-3">
                                        <div class="avatar-title bg-warning-subtle text-warning rounded-circle fs-20">
                                            <i class="ri-home-line"></i>
                                        </div>
                                    </div>
                                    <h6 class="mb-1">Aucune propriété</h6>
                                    <p class="text-muted mb-0">Cette agence n'a pas encore de propriétés enregistrées.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Agency Stats -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ri-bar-chart-line me-1"></i>Statistiques détaillées
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted">Propriétés actives</span>
                                    <span class="fw-semibold"><?php echo $agency->active_properties ?? 0; ?></span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-info" style="width: <?php echo ($agency->properties_count ?? 0) > 0 ? round((($agency->active_properties ?? 0) / ($agency->properties_count ?? 1)) * 100) : 0; ?>%"></div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted">Propriétés vendues</span>
                                    <span class="fw-semibold"><?php echo $agency->sales_count ?? 0; ?></span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-success" style="width: <?php echo ($agency->properties_count ?? 0) > 0 ? round((($agency->sales_count ?? 0) / ($agency->properties_count ?? 1)) * 100) : 0; ?>%"></div>
                                </div>
                            </div>
                            <div class="mb-0">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted">Taux de réussite</span>
                                    <span class="fw-semibold">
                                        <?php echo ($agency->properties_count ?? 0) > 0 ? round((($agency->sales_count ?? 0) / ($agency->properties_count ?? 1)) * 100, 1) : 0; ?>%
                                    </span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-warning" style="width: <?php echo ($agency->properties_count ?? 0) > 0 ? round((($agency->sales_count ?? 0) / ($agency->properties_count ?? 1)) * 100) : 0; ?>%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ri-flashlight-line me-1"></i>Actions rapides
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="<?php echo base_url('agency/agents/' . $agency->agency_id); ?>" class="btn btn-outline-primary">
                                    <i class="ri-user-add-line me-2"></i>Gérer les agents
                                </a>
                                <a href="<?php echo base_url('agency/properties/' . $agency->agency_id); ?>" class="btn btn-outline-warning">
                                    <i class="ri-home-add-line me-2"></i>Gérer les propriétés
                                </a>
                                <a href="<?php echo base_url('agency/stats/' . $agency->agency_id); ?>" class="btn btn-outline-info">
                                    <i class="ri-bar-chart-2-line me-2"></i>Voir statistiques
                                </a>
                                <hr>
                                <a href="<?php echo base_url('agency/edit/' . $agency->agency_id); ?>" class="btn btn-outline-secondary">
                                    <i class="ri-edit-2-line me-2"></i>Modifier l'agence
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Activity Timeline -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ri-time-line me-1"></i>Activité récente
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="timeline-sm">
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-primary"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Agence créée</h6>
                                        <p class="timeline-text text-muted mb-0">
                                            <?php echo date('d/m/Y à H:i', strtotime($agency->created_date)); ?>
                                        </p>
                                    </div>
                                </div>
                                <?php if (($agency->agents_count ?? 0) > 0) : ?>
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-info"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Agents ajoutés</h6>
                                        <p class="timeline-text text-muted mb-0">
                                            <?php echo $agency->agents_count; ?> agent(s) dans l'équipe
                                        </p>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <?php if (($agency->properties_count ?? 0) > 0) : ?>
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-warning"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Propriétés ajoutées</h6>
                                        <p class="timeline-text text-muted mb-0">
                                            <?php echo $agency->properties_count; ?> propriété(s) en gestion
                                        </p>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <?php if (($agency->sales_count ?? 0) > 0) : ?>
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-success"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Premières ventes</h6>
                                        <p class="timeline-text text-muted mb-0">
                                            <?php echo $agency->sales_count; ?> vente(s) réalisée(s)
                                        </p>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline-sm .timeline-item {
    position: relative;
    padding-left: 30px;
    margin-bottom: 20px;
}

.timeline-sm .timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-sm .timeline-marker {
    position: absolute;
    left: 0;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.timeline-sm .timeline-marker::after {
    content: '';
    position: absolute;
    left: 50%;
    top: 12px;
    width: 1px;
    height: 25px;
    background-color: #e5e7eb;
    transform: translateX(-50%);
}

.timeline-sm .timeline-item:last-child .timeline-marker::after {
    display: none;
}

.timeline-title {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 2px;
}

.timeline-text {
    font-size: 13px;
    line-height: 1.4;
}
</style>
