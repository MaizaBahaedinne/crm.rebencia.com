<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- Modern Hero Section -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-lg overflow-hidden modern-profile-card">
                        <!-- Background Cover -->
                        <div class="profile-cover position-relative">
                            <div class="profile-gradient-overlay"></div>
                            
                            <!-- Profile Content -->
                            <div class="position-relative p-4 p-lg-5">
                                <div class="row align-items-end">
                                    <div class="col-lg-8">
                                        <div class="d-flex align-items-center">
                                            <!-- Avatar with modern styling -->
                                            <div class="profile-avatar-container position-relative me-4">
                                                <?php $avatar_url = get_agent_avatar_url($agent); ?>
                                                <div class="profile-avatar">
                                                    <img src="<?php echo $avatar_url; ?>" 
                                                         alt="<?php echo htmlspecialchars($agent->agent_name); ?>" 
                                                         class="img-fluid rounded-circle"
                                                         onerror="this.onerror=null; this.src='<?php echo base_url('assets/images/users/avatar-1.jpg'); ?>';">
                                                </div>
                                                <div class="profile-status-indicator <?php echo $agent->is_active ? 'active' : 'inactive'; ?>"></div>
                                            </div>
                                            
                                            <!-- Profile Info -->
                                            <div class="text-white">
                                                <h2 class="text-white mb-2 fw-bold"><?php echo htmlspecialchars($agent->agent_name); ?></h2>
                                                <div class="profile-meta mb-3">
                                                    <?php if (!empty($agent->position)) : ?>
                                                    <span class="badge bg-white bg-opacity-20 text-white me-2 px-3 py-2">
                                                        <i class="ri-briefcase-line me-1"></i><?php echo htmlspecialchars($agent->position); ?>
                                                    </span>
                                                    <?php endif; ?>
                                                    <?php if ($agent->agency_name): ?>
                                                    <span class="badge bg-white bg-opacity-20 text-white px-3 py-2">
                                                        <i class="ri-building-line me-1"></i><?php echo htmlspecialchars($agent->agency_name); ?>
                                                    </span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="d-flex align-items-center gap-3">
                                                    <span class="text-white-75">
                                                        <i class="ri-mail-line me-1"></i><?php echo htmlspecialchars($agent->agent_email); ?>
                                                    </span>
                                                    <?php if (!empty($agent->phone)) : ?>
                                                    <span class="text-white-75">
                                                        <i class="ri-phone-line me-1"></i><?php echo htmlspecialchars($agent->phone); ?>
                                                    </span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Quick Actions -->
                                    <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                                        <div class="profile-actions">
                                            <button class="btn btn-light btn-modern me-2" data-bs-toggle="modal" data-bs-target="#editAgentModal">
                                                <i class="ri-edit-line me-1"></i>Modifier
                                            </button>
                                            <div class="btn-group">
                                                <button class="btn btn-light btn-modern dropdown-toggle" data-bs-toggle="dropdown">
                                                    <i class="ri-more-line"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="#"><i class="ri-mail-send-line me-2"></i>Envoyer email</a></li>
                                                    <li><a class="dropdown-item" href="#"><i class="ri-phone-line me-2"></i>Appeler</a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item text-danger" href="#"><i class="ri-delete-bin-line me-2"></i>Désactiver</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modern Stats Cards -->
            <div class="row mt-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm modern-stat-card stat-primary">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon">
                                    <i class="ri-home-4-line"></i>
                                </div>
                                <div class="ms-3 flex-grow-1">
                                    <div class="stat-number"><?php echo $agent->properties_count ?? 0; ?></div>
                                    <div class="stat-label">Propriétés</div>
                                </div>
                                <button class="btn btn-sm btn-light rounded-circle stat-action" 
                                        onclick="showPropertiesDetails(<?php echo $agent->agent_id; ?>)">
                                    <i class="ri-eye-line"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm modern-stat-card stat-success">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon">
                                    <i class="ri-user-heart-line"></i>
                                </div>
                                <div class="ms-3 flex-grow-1">
                                    <div class="stat-number"><?php echo $agent->contacts_count ?? 0; ?></div>
                                    <div class="stat-label">Clients</div>
                                </div>
                                <button class="btn btn-sm btn-light rounded-circle stat-action" 
                                        onclick="showClientsDetails(<?php echo $agent->agent_id; ?>)">
                                    <i class="ri-eye-line"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm modern-stat-card stat-info">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon">
                                    <i class="ri-contacts-book-line"></i>
                                </div>
                                <div class="ms-3 flex-grow-1">
                                    <div class="stat-number"><?php echo $agent->contacts_count ?? 0; ?></div>
                                    <div class="stat-label">Contacts</div>
                                </div>
                                <button class="btn btn-sm btn-light rounded-circle stat-action" 
                                        onclick="showContactsDetails(<?php echo $agent->agent_id; ?>)">
                                    <i class="ri-eye-line"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm modern-stat-card stat-warning">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon">
                                    <i class="ri-line-chart-line"></i>
                                </div>
                                <div class="ms-3 flex-grow-1">
                                    <div class="stat-number"><?php echo $agent->leads_count ?? 0; ?></div>
                                    <div class="stat-label">Leads</div>
                                </div>
                                <button class="btn btn-sm btn-light rounded-circle stat-action" 
                                        onclick="showLeadsDetails(<?php echo $agent->agent_id; ?>)">
                                    <i class="ri-eye-line"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modern Content Tabs -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header border-0 bg-transparent">
                            <ul class="nav nav-pills nav-modern" id="agentTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="overview-tab" data-bs-toggle="pill" 
                                            data-bs-target="#overview" type="button" role="tab">
                                        <i class="ri-dashboard-line me-2"></i>Vue d'ensemble
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="properties-tab" data-bs-toggle="pill" 
                                            data-bs-target="#properties" type="button" role="tab">
                                        <i class="ri-home-4-line me-2"></i>Propriétés
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="activity-tab" data-bs-toggle="pill" 
                                            data-bs-target="#activity" type="button" role="tab">
                                        <i class="ri-time-line me-2"></i>Activités
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="contact-tab" data-bs-toggle="pill" 
                                            data-bs-target="#contact" type="button" role="tab">
                                        <i class="ri-contacts-line me-2"></i>Contact
                                    </button>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="card-body">
                            <div class="tab-content" id="agentTabsContent">
                                <!-- Overview Tab -->
                                <div class="tab-pane fade show active" id="overview" role="tabpanel">
                                    <div class="row">
                                        <!-- Quick Info Panel -->
                                        <div class="col-lg-8">
                                            <div class="row g-4">
                                                <!-- Performance Chart -->
                                                <div class="col-12">
                                                    <div class="card border-0 bg-light">
                                                        <div class="card-body">
                                                            <h6 class="card-title mb-3">
                                                                <i class="ri-bar-chart-line me-2 text-primary"></i>Performance
                                                            </h6>
                                                            <div class="row text-center">
                                                                <div class="col-3">
                                                                    <div class="performance-metric">
                                                                        <div class="metric-value text-primary"><?php echo $agent->properties_count ?? 0; ?></div>
                                                                        <div class="metric-label">Propriétés</div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-3">
                                                                    <div class="performance-metric">
                                                                        <div class="metric-value text-success"><?php echo $agent->contacts_count ?? 0; ?></div>
                                                                        <div class="metric-label">Clients</div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-3">
                                                                    <div class="performance-metric">
                                                                        <div class="metric-value text-info"><?php echo $agent->contacts_count ?? 0; ?></div>
                                                                        <div class="metric-label">Contacts</div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-3">
                                                                    <div class="performance-metric">
                                                                        <div class="metric-value text-warning"><?php echo $agent->leads_count ?? 0; ?></div>
                                                                        <div class="metric-label">Leads</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Recent Activity -->
                                                <div class="col-12">
                                                    <div class="card border-0 bg-light">
                                                        <div class="card-body">
                                                            <h6 class="card-title mb-3">
                                                                <i class="ri-time-line me-2 text-success"></i>Activité récente
                                                            </h6>
                                                            <div class="activity-timeline">
                                                                <div class="activity-item">
                                                                    <div class="activity-icon bg-primary">
                                                                        <i class="ri-home-line"></i>
                                                                    </div>
                                                                    <div class="activity-content">
                                                                        <div class="activity-title">Nouvelle propriété ajoutée</div>
                                                                        <div class="activity-time">Il y a 2 heures</div>
                                                                    </div>
                                                                </div>
                                                                <div class="activity-item">
                                                                    <div class="activity-icon bg-success">
                                                                        <i class="ri-user-line"></i>
                                                                    </div>
                                                                    <div class="activity-content">
                                                                        <div class="activity-title">Nouveau client contacté</div>
                                                                        <div class="activity-time">Il y a 5 heures</div>
                                                                    </div>
                                                                </div>
                                                                <div class="activity-item">
                                                                    <div class="activity-icon bg-info">
                                                                        <i class="ri-mail-line"></i>
                                                                    </div>
                                                                    <div class="activity-content">
                                                                        <div class="activity-title">Email envoyé à un prospect</div>
                                                                        <div class="activity-time">Hier</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Info Panel -->
                                        <div class="col-lg-4">
                                            <div class="card border-0 bg-light h-100">
                                                <div class="card-body">
                                                    <h6 class="card-title mb-4">
                                                        <i class="ri-information-line me-2 text-info"></i>Informations
                                                    </h6>
                                                    
                                                    <div class="info-item mb-3">
                                                        <div class="info-label">Date d'inscription</div>
                                                        <div class="info-value"><?php echo date('d/m/Y', strtotime($agent->registration_date)); ?></div>
                                                    </div>
                                                    
                                                    <?php if (!empty($agent->phone)) : ?>
                                                    <div class="info-item mb-3">
                                                        <div class="info-label">Téléphone</div>
                                                        <div class="info-value">
                                                            <a href="tel:<?php echo $agent->phone; ?>" class="text-decoration-none">
                                                                <?php echo htmlspecialchars($agent->phone); ?>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <?php endif; ?>
                                                    
                                                    <?php if (!empty($agent->address)) : ?>
                                                    <div class="info-item mb-3">
                                                        <div class="info-label">Adresse</div>
                                                        <div class="info-value"><?php echo htmlspecialchars($agent->address); ?></div>
                                                    </div>
                                                    <?php endif; ?>
                                                    
                                                    <!-- Social Links -->
                                                    <div class="social-links mt-4">
                                                        <div class="info-label mb-2">Réseaux sociaux</div>
                                                        <div class="d-flex gap-2">
                                                            <?php if (!empty($agent->facebook)) : ?>
                                                            <a href="<?php echo $agent->facebook; ?>" class="btn btn-sm btn-outline-primary rounded-circle" target="_blank">
                                                                <i class="ri-facebook-line"></i>
                                                            </a>
                                                            <?php endif; ?>
                                                            <?php if (!empty($agent->twitter)) : ?>
                                                            <a href="<?php echo $agent->twitter; ?>" class="btn btn-sm btn-outline-info rounded-circle" target="_blank">
                                                                <i class="ri-twitter-line"></i>
                                                            </a>
                                                            <?php endif; ?>
                                                            <?php if (!empty($agent->linkedin)) : ?>
                                                            <a href="<?php echo $agent->linkedin; ?>" class="btn btn-sm btn-outline-primary rounded-circle" target="_blank">
                                                                <i class="ri-linkedin-line"></i>
                                                            </a>
                                                            <?php endif; ?>
                                                            <?php if (!empty($agent->instagram)) : ?>
                                                            <a href="<?php echo $agent->instagram; ?>" class="btn btn-sm btn-outline-danger rounded-circle" target="_blank">
                                                                <i class="ri-instagram-line"></i>
                                                            </a>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Properties Tab -->
                                <div class="tab-pane fade" id="properties" role="tabpanel">
                                    <div id="properties-content">
                                        <!-- Debug Info -->
                                        <div class="alert alert-info">
                                            <strong>Debug:</strong> 
                                            Agent ID: <?php echo $agent->agent_id ?? 'N/A'; ?> | 
                                            Properties count: <?php echo count($properties ?? []); ?> |
                                            Properties type: <?php echo gettype($properties ?? null); ?>
                                            <?php if (!empty($properties)): ?>
                                                <br>First property: <?php echo json_encode($properties[0]); ?>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <?php if (!empty($properties) && is_array($properties)) : ?>
                                            <div class="row g-3">
                                                <?php foreach ($properties as $property) : ?>
                                                    <div class="col-lg-4 col-md-6">
                                                        <div class="card border-0 shadow-sm hover-card h-100">
                                                            <?php if (!empty($property->thumbnail)) : ?>
                                                                <div class="property-image position-relative">
                                                                    <img src="<?php echo $property->thumbnail; ?>" 
                                                                         class="card-img-top" 
                                                                         style="height: 200px; object-fit: cover;" 
                                                                         alt="<?php echo htmlspecialchars($property->title); ?>">
                                                                    <div class="property-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-end p-3">
                                                                        <span class="badge bg-primary text-white">
                                                                            <?php echo ucfirst($property->property_type ?? 'Propriété'); ?>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            <?php else : ?>
                                                                <div class="property-placeholder bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                                                    <i class="ri-home-line fs-1 text-muted"></i>
                                                                </div>
                                                            <?php endif; ?>
                                                            
                                                            <div class="card-body">
                                                                <h6 class="property-title mb-2">
                                                                    <?php 
                                                                    $property_url = !empty($property->slug) ? 
                                                                        'https://rebencia.com/property/' . $property->slug : '#';
                                                                    ?>
                                                                    <a href="<?php echo $property_url; ?>" class="text-decoration-none text-dark" target="_blank">
                                                                        <?php echo htmlspecialchars($property->title); ?>
                                                                    </a>
                                                                </h6>
                                                                
                                                                <div class="property-location mb-2">
                                                                    <i class="ri-map-pin-line text-muted me-1"></i>
                                                                    <small class="text-muted"><?php echo htmlspecialchars($property->location ?? 'Localisation non spécifiée'); ?></small>
                                                                </div>
                                                                
                                                                <?php if (!empty($property->price)) : ?>
                                                                    <div class="property-price mb-3">
                                                                        <span class="text-primary fw-bold fs-5">
                                                                            <?php echo number_format($property->price, 0, ',', ' ') . ' €'; ?>
                                                                        </span>
                                                                    </div>
                                                                <?php endif; ?>
                                                                
                                                                <div class="property-details">
                                                                    <div class="row g-2 text-center">
                                                                        <?php if (!empty($property->bedrooms)) : ?>
                                                                        <div class="col-4">
                                                                            <div class="detail-item">
                                                                                <i class="ri-hotel-bed-line text-primary"></i>
                                                                                <small class="d-block text-muted"><?php echo $property->bedrooms; ?> ch</small>
                                                                            </div>
                                                                        </div>
                                                                        <?php endif; ?>
                                                                        
                                                                        <?php if (!empty($property->bathrooms)) : ?>
                                                                        <div class="col-4">
                                                                            <div class="detail-item">
                                                                                <i class="ri-drop-line text-info"></i>
                                                                                <small class="d-block text-muted"><?php echo $property->bathrooms; ?> sdb</small>
                                                                            </div>
                                                                        </div>
                                                                        <?php endif; ?>
                                                                        
                                                                        <?php if (!empty($property->size)) : ?>
                                                                        <div class="col-4">
                                                                            <div class="detail-item">
                                                                                <i class="ri-ruler-line text-success"></i>
                                                                                <small class="d-block text-muted"><?php echo $property->size; ?> m²</small>
                                                                            </div>
                                                                        </div>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="mt-3">
                                                                    <a href="<?php echo $property_url; ?>" class="btn btn-primary btn-sm w-100" target="_blank">
                                                                        <i class="ri-eye-line me-1"></i>Voir détails
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php else : ?>
                                            <div class="text-center py-5">
                                                <i class="ri-home-line fs-1 text-muted mb-3"></i>
                                                <h5 class="text-muted">Aucune propriété trouvée</h5>
                                                <p class="text-muted">Cet agent n'a actuellement aucune propriété assignée.</p>
                                                <button class="btn btn-outline-primary btn-sm" onclick="location.reload()">
                                                    <i class="ri-refresh-line me-1"></i>Actualiser
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <!-- Activity Tab -->
                                <div class="tab-pane fade" id="activity" role="tabpanel">
                                    <div class="timeline-modern">
                                        <!-- Activity content will be loaded here -->
                                        <div class="text-center py-5">
                                            <i class="ri-time-line fs-1 text-muted"></i>
                                            <p class="text-muted mt-2">Aucune activité récente</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Contact Tab -->
                                <div class="tab-pane fade" id="contact" role="tabpanel">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="card border-0 bg-light">
                                                <div class="card-body">
                                                    <h6 class="card-title mb-3">Informations de contact</h6>
                                                    
                                                    <div class="contact-info">
                                                        <div class="contact-item">
                                                            <i class="ri-mail-line text-primary"></i>
                                                            <div>
                                                                <div class="contact-label">Email</div>
                                                                <div class="contact-value">
                                                                    <a href="mailto:<?php echo $agent->agent_email; ?>">
                                                                        <?php echo htmlspecialchars($agent->agent_email); ?>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <?php if (!empty($agent->phone)) : ?>
                                                        <div class="contact-item">
                                                            <i class="ri-phone-line text-success"></i>
                                                            <div>
                                                                <div class="contact-label">Téléphone</div>
                                                                <div class="contact-value">
                                                                    <a href="tel:<?php echo $agent->phone; ?>">
                                                                        <?php echo htmlspecialchars($agent->phone); ?>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php endif; ?>
                                                        
                                                        <?php if (!empty($agent->mobile)) : ?>
                                                        <div class="contact-item">
                                                            <i class="ri-smartphone-line text-info"></i>
                                                            <div>
                                                                <div class="contact-label">Mobile</div>
                                                                <div class="contact-value">
                                                                    <a href="tel:<?php echo $agent->mobile; ?>">
                                                                        <?php echo htmlspecialchars($agent->mobile); ?>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php endif; ?>
                                                        
                                                        <?php if (!empty($agent->whatsapp)) : ?>
                                                        <div class="contact-item">
                                                            <i class="ri-whatsapp-line text-success"></i>
                                                            <div>
                                                                <div class="contact-label">WhatsApp</div>
                                                                <div class="contact-value">
                                                                    <a href="https://wa.me/<?php echo $agent->whatsapp; ?>" target="_blank">
                                                                        <?php echo htmlspecialchars($agent->whatsapp); ?>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-6">
                                            <div class="card border-0 bg-light">
                                                <div class="card-body">
                                                    <h6 class="card-title mb-3">Actions rapides</h6>
                                                    
                                                    <div class="d-grid gap-2">
                                                        <button class="btn btn-primary btn-modern">
                                                            <i class="ri-mail-send-line me-2"></i>Envoyer un email
                                                        </button>
                                                        <button class="btn btn-success btn-modern">
                                                            <i class="ri-phone-line me-2"></i>Programmer un appel
                                                        </button>
                                                        <button class="btn btn-info btn-modern">
                                                            <i class="ri-calendar-line me-2"></i>Planifier une réunion
                                                        </button>
                                                        <button class="btn btn-warning btn-modern">
                                                            <i class="ri-file-text-line me-2"></i>Créer une note
                                                        </button>
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
    </div>
</div>

<style>
/* Enhanced modern CSS styles */
.hero-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    overflow: hidden;
}

.hero-gradient::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.1);
    z-index: 1;
}

.hero-gradient > * {
    position: relative;
    z-index: 2;
}

.profile-cover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 200px;
}

.profile-gradient-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.1);
}

.profile-avatar {
    width: 120px;
    height: 120px;
    border: 4px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    overflow: hidden;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
}

.profile-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.profile-status-indicator {
    position: absolute;
    bottom: 10px;
    right: 10px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 3px solid white;
}

.profile-status-indicator.active {
    background: #28a745;
}

.profile-status-indicator.inactive {
    background: #dc3545;
}

.text-white-75 {
    color: rgba(255, 255, 255, 0.75) !important;
}

.modern-stat-card {
    transition: all 0.3s ease;
    border-radius: 12px;
}

.modern-stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.stat-primary .stat-icon {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

.stat-success .stat-icon {
    background: linear-gradient(135deg, #11998e, #38ef7d);
    color: white;
}

.stat-info .stat-icon {
    background: linear-gradient(135deg, #17a2b8, #20c997);
    color: white;
}

.stat-warning .stat-icon {
    background: linear-gradient(135deg, #f093fb, #f5576c);
    color: white;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
}

.stat-label {
    color: #6c757d;
    font-size: 0.875rem;
    margin: 0;
}

.stat-action {
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-modern {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.nav-modern .nav-link {
    border-radius: 10px;
    margin-right: 0.5rem;
    transition: all 0.3s ease;
    border: none;
    background: transparent;
}

.nav-modern .nav-link:hover {
    background: rgba(102, 126, 234, 0.1);
    transform: translateY(-2px);
}

.nav-modern .nav-link.active {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.tab-content {
    animation: fadeInUp 0.5s ease;
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

.performance-metric {
    padding: 1rem;
    border-radius: 8px;
    background: white;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.metric-value {
    font-size: 1.5rem;
    font-weight: 700;
}

.metric-label {
    font-size: 0.8rem;
    color: #6c757d;
    margin-top: 0.25rem;
}

.activity-timeline {
    position: relative;
}

.activity-item {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.activity-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    color: white;
    font-size: 1.1rem;
}

.activity-content {
    flex: 1;
}

.activity-title {
    font-weight: 500;
    margin-bottom: 0.25rem;
}

.activity-time {
    font-size: 0.8rem;
    color: #6c757d;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 500;
    color: #6c757d;
}

.info-value {
    font-weight: 600;
}

.contact-item {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
    padding: 1rem;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.contact-item i {
    font-size: 1.5rem;
    margin-right: 1rem;
    width: 30px;
    text-align: center;
}

.contact-label {
    font-size: 0.8rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
}

.contact-value {
    font-weight: 500;
}

.contact-value a {
    text-decoration: none;
    color: inherit;
}

.contact-value a:hover {
    color: #667eea;
}

.hover-card {
    transition: all 0.3s ease;
}

.hover-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.property-image {
    overflow: hidden;
}

.property-overlay {
    background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0) 100%);
}

.property-title a:hover {
    color: #667eea !important;
}

.property-placeholder {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
}

.detail-item {
    padding: 0.5rem;
    background: rgba(102, 126, 234, 0.1);
    border-radius: 8px;
}

.detail-item i {
    font-size: 1.2rem;
    margin-bottom: 0.25rem;
}
</style>

<script>
// Enhanced JavaScript for modern interactions
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching with smooth animations
    const tabButtons = document.querySelectorAll('#agentTabs button[data-bs-toggle="pill"]');
    tabButtons.forEach(button => {
        button.addEventListener('shown.bs.tab', function(e) {
            const targetTab = e.target.getAttribute('data-bs-target');
            
            // Load content based on tab
            if (targetTab === '#properties') {
                loadPropertiesTab();
            } else if (targetTab === '#activity') {
                loadActivityTab();
            }
        });
    });
    
    // Animate stat cards on page load
    setTimeout(() => {
        document.querySelectorAll('.modern-stat-card').forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'all 0.5s ease';
                
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 50);
            }, index * 100);
        });
    }, 100);
});

function loadPropertiesTab() {
    // Properties are already loaded from PHP, no need for AJAX
    console.log('Properties tab loaded');
}

function loadActivityTab() {
    // Activity tab content already loaded
}

// Modal functions
function showPropertiesDetails(agentId) {
    // Function placeholder
    console.log('Show properties for agent:', agentId);
}

function showClientsDetails(agentId) {
    // Function placeholder
    console.log('Show clients for agent:', agentId);
}

function showContactsDetails(agentId) {
    // Function placeholder
    console.log('Show contacts for agent:', agentId);
}

function showLeadsDetails(agentId) {
    // Function placeholder
    console.log('Show leads for agent:', agentId);
}
</script>
