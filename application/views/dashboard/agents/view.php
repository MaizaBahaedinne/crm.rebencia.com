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
                                                                        <div class="metric-value text-success"><?php echo $agent->clients_count ?? 0; ?></div>
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
                                        <div class="text-center py-5">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Chargement...</span>
                                            </div>
                                            <p class="mt-2 text-muted">Chargement des propriétés...</p>
                                        </div>
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
            <div class="row g-3">
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100 hover-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center flex-grow-1">
                                    <div class="avatar-md flex-shrink-0 me-3">
                                        <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-2">
                                            <i class="ri-home-line"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-uppercase fw-medium text-muted mb-1 fs-11">Propriétés</p>
                                        <h3 class="mb-1 text-primary"><?php echo $agent->properties_count ?? 0; ?></h3>
                                        <small class="text-muted fs-12">
                                            <?php if (($agent->properties_count ?? 0) == 0) : ?>
                                                Aucune propriété
                                            <?php else : ?>
                                                <?php echo ($agent->properties_count > 1) ? 'propriétés actives' : 'propriété active'; ?>
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <a href="#" class="btn btn-sm btn-soft-primary" onclick="showPropertiesDetails(<?php echo $agent->user_id; ?>); return false;" title="Voir détails">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100 hover-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center flex-grow-1">
                                    <div class="avatar-md flex-shrink-0 me-3">
                                        <span class="avatar-title bg-info-subtle text-info rounded-circle fs-2">
                                            <i class="ri-calculator-line"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-uppercase fw-medium text-muted mb-1 fs-11">Estimations</p>
                                        <h3 class="mb-1 text-info"><?php echo $agent->estimations_count ?? 0; ?></h3>
                                        <small class="text-muted fs-12">
                                            <?php if (($agent->estimations_count ?? 0) == 0) : ?>
                                                Aucune estimation
                                            <?php else : ?>
                                                Total estimations
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <a href="#" class="btn btn-sm btn-soft-info" onclick="showEstimationsDetails(<?php echo $agent->user_id; ?>); return false;" title="Voir détails">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center flex-grow-1">
                                    <div class="avatar-md flex-shrink-0 me-3">
                                        <span class="avatar-title bg-success-subtle text-success rounded-circle fs-2">
                                            <i class="ri-exchange-line"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-uppercase fw-medium text-muted mb-1 fs-11">Transactions</p>
                                        <h3 class="mb-1 text-success"><?php echo $agent->transactions_count ?? 0; ?></h3>
                                        <small class="text-muted fs-12">
                                            <?php if (($agent->transactions_count ?? 0) == 0) : ?>
                                                Aucune transaction
                                            <?php else : ?>
                                                Total transactions
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <a href="#" class="btn btn-sm btn-soft-success" onclick="showTransactionsDetails(<?php echo $agent->user_id; ?>); return false;" title="Voir détails">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row g-3 mt-2">
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-md flex-shrink-0">
                                    <span class="avatar-title bg-warning-subtle text-warning rounded-circle fs-2">
                                        <i class="ri-money-dollar-circle-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-medium text-muted mb-1">Commissions</p>
                                    <h3 class="mb-0 text-warning"><?php echo number_format($agent->total_commission ?? 0, 0, ',', ' '); ?> <small class="fs-6">TND</small></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-md flex-shrink-0">
                                    <span class="avatar-title bg-secondary-subtle text-secondary rounded-circle fs-2">
                                        <i class="ri-eye-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-medium text-muted mb-1">Vues Totales</p>
                                    <h3 class="mb-0 text-secondary"><?php echo number_format($agent->total_views ?? 0); ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center flex-grow-1">
                                    <div class="avatar-md flex-shrink-0 me-3">
                                        <span class="avatar-title bg-danger-subtle text-danger rounded-circle fs-2">
                                            <i class="ri-contacts-line"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-uppercase fw-medium text-muted mb-1 fs-11">Contacts</p>
                                        <h3 class="mb-1 text-danger"><?php echo $agent->contacts_count ?? 0; ?></h3>
                                        <small class="text-muted fs-12">
                                            <?php if (($agent->contacts_count ?? 0) == 0) : ?>
                                                Aucun contact
                                            <?php else : ?>
                                                Total contacts
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <a href="#" class="btn btn-sm btn-soft-danger" onclick="showContactsDetails(<?php echo $agent->user_id; ?>); return false;" title="Voir détails">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                </div>
                            </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Agent Details & Properties -->
            <div class="row">
                <!-- Agent Information -->
                <div class="col-xl-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Informations personnelles</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="ps-0 text-muted">Email:</td>
                                            <td class="pe-0"><?php echo htmlspecialchars($agent->agent_email); ?></td>
                                        </tr>
                                        <?php if (!empty($agent->phone)) : ?>
                                        <tr>
                                            <td class="ps-0 text-muted">Téléphone:</td>
                                            <td class="pe-0"><?php echo htmlspecialchars($agent->phone); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($agent->mobile)) : ?>
                                        <tr>
                                            <td class="ps-0 text-muted">Mobile:</td>
                                            <td class="pe-0"><?php echo htmlspecialchars($agent->mobile); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($agent->whatsapp)) : ?>
                                        <tr>
                                            <td class="ps-0 text-muted">WhatsApp:</td>
                                            <td class="pe-0"><?php echo htmlspecialchars($agent->whatsapp); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($agent->website)) : ?>
                                        <tr>
                                            <td class="ps-0 text-muted">Site Web:</td>
                                            <td class="pe-0">
                                                <a href="<?php echo $agent->website; ?>" target="_blank" class="text-decoration-none">
                                                    <?php echo htmlspecialchars($agent->website); ?> <i class="ri-external-link-line"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($agent->position)) : ?>
                                        <tr>
                                            <td class="ps-0 text-muted">Poste:</td>
                                            <td class="pe-0"><?php echo htmlspecialchars($agent->position); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($agent->address)) : ?>
                                        <tr>
                                            <td class="ps-0 text-muted">Adresse:</td>
                                            <td class="pe-0"><?php echo htmlspecialchars($agent->address); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <tr>
                                            <td class="ps-0 text-muted">Inscrit le:</td>
                                            <td class="pe-0"><?php echo date('d/m/Y', strtotime($agent->registration_date)); ?></td>
                                        </tr>
                                        <?php if ($agent->agency_name): ?>
                                        <tr>
                                            <td class="ps-0 text-muted">Agence:</td>
                                            <td class="pe-0"><?php echo htmlspecialchars($agent->agency_name); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <?php if (!empty($agent->description)) : ?>
                            <hr>
                            <div>
                                <h6>Bio</h6>
                                <div class="text-muted"><?php echo strip_tags($agent->description, '<p><br><strong><em><ul><li><ol>'); ?></div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <?php if (!empty($agent->facebook) || !empty($agent->twitter) || !empty($agent->linkedin) || !empty($agent->instagram)) : ?>
                    <div class="card border-0 shadow-sm">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Réseaux sociaux</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-wrap gap-2">
                                <?php if (!empty($agent->facebook)) : ?>
                                <a href="<?php echo $agent->facebook; ?>" class="btn btn-primary btn-sm" target="_blank">
                                    <i class="ri-facebook-line me-1"></i>Facebook
                                </a>
                                <?php endif; ?>
                                <?php if (!empty($agent->twitter)) : ?>
                                <a href="<?php echo $agent->twitter; ?>" class="btn btn-info btn-sm" target="_blank">
                                    <i class="ri-twitter-line me-1"></i>Twitter
                                </a>
                                <?php endif; ?>
                                <?php if (!empty($agent->linkedin)) : ?>
                                <a href="<?php echo $agent->linkedin; ?>" class="btn btn-primary btn-sm" target="_blank">
                                    <i class="ri-linkedin-line me-1"></i>LinkedIn
                                </a>
                                <?php endif; ?>
                                <?php if (!empty($agent->instagram)) : ?>
                                <a href="<?php echo $agent->instagram; ?>" class="btn btn-danger btn-sm" target="_blank">
                                    <i class="ri-instagram-line me-1"></i>Instagram
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Monthly Performance -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Performance du mois</h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="border-end">
                                        <h4 class="text-info"><?php echo $agent->estimations_this_month ?? 0; ?></h4>
                                        <p class="text-muted mb-0 small">Estimations</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h4 class="text-success"><?php echo $agent->transactions_this_month ?? 0; ?></h4>
                                    <p class="text-muted mb-0 small">Transactions</p>
                                </div>
                            </div>

                            <?php if (($agent->avg_estimation_value ?? 0) > 0) : ?>
                            <hr>
                            <div class="text-center">
                                <h6 class="text-muted mb-1">Valeur moyenne des estimations</h6>
                                <h5 class="text-primary mb-0"><?php echo number_format($agent->avg_estimation_value, 0, ',', ' '); ?> TND</h5>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Agent Properties -->
                <div class="col-xl-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Propriétés de l'agent (<?php echo count($properties); ?>)</h5>
                            <a href="<?php echo base_url('properties?agent=' . $agent->user_id); ?>" class="btn btn-primary btn-sm">
                                Voir toutes
                            </a>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($properties)) : ?>
                            <div class="row">
                                <?php foreach (array_slice($properties, 0, 6) as $property) : ?>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card border h-100">
                                        <div class="position-relative">
                                            <img src="<?php echo $property->thumbnail; ?>" alt="<?php echo htmlspecialchars($property->title); ?>" class="card-img-top" style="height: 180px; object-fit: cover;">
                                            <div class="position-absolute top-0 start-0 p-2">
                                                <span class="badge bg-<?php echo $property->status == 'for-rent' ? 'info' : 'success'; ?>">
                                                    <?php echo $property->status == 'for-rent' ? 'À louer' : 'À vendre'; ?>
                                                </span>
                                            </div>
                                            <div class="position-absolute top-0 end-0 p-2">
                                                <span class="badge bg-dark"><?php echo $property->property_type; ?></span>
                                            </div>
                                        </div>
                                        <div class="card-body p-3">
                                            <h6 class="card-title mb-2">
                                                <a href="<?php echo base_url('properties/view/' . $property->ID); ?>" class="text-decoration-none">
                                                    <?php echo htmlspecialchars($property->title); ?>
                                                </a>
                                            </h6>
                                            <p class="text-muted small mb-2">
                                                <i class="ri-map-pin-line me-1"></i>
                                                <?php echo htmlspecialchars($property->location); ?>
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="fw-bold text-primary">
                                                    <?php echo number_format($property->price, 0, ',', ' '); ?> TND
                                                </div>
                                                <small class="text-muted">
                                                    <i class="ri-eye-line me-1"></i><?php echo $property->views; ?>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <?php if (count($properties) > 6) : ?>
                            <div class="text-center mt-3">
                                <a href="<?php echo base_url('properties?agent=' . $agent->user_id); ?>" class="btn btn-outline-primary">
                                    Voir les <?php echo count($properties) - 6; ?> propriétés restantes
                                </a>
                            </div>
                            <?php endif; ?>
                            
                            <?php else : ?>
                            <div class="text-center py-4">
                                <div class="avatar-lg mx-auto mb-3">
                                    <div class="avatar-title bg-light text-muted rounded-circle fs-24">
                                        <i class="ri-home-line"></i>
                                    </div>
                                </div>
                                <h6 class="mb-2">Aucune propriété</h6>
                                <p class="text-muted">Cet agent n'a pas encore ajouté de propriétés.</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estimations & Transactions Row -->
            <div class="row mt-4">
                <!-- Estimations Section -->
                <div class="col-xl-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="ri-calculator-line me-2 text-info"></i>Estimations Récentes (<?php echo count($estimations ?? []); ?>)
                            </h5>
                            <a href="<?php echo base_url('estimations?agent=' . $agent->user_id); ?>" class="btn btn-info btn-sm">
                                Voir toutes
                            </a>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($estimations)) : ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($estimations as $estimation) : ?>
                                <div class="list-group-item border-0 px-0">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1"><?php echo htmlspecialchars($estimation->type_bien); ?></h6>
                                            <p class="text-muted small mb-1">
                                                <i class="ri-map-pin-line me-1"></i>
                                                <?php echo htmlspecialchars($estimation->adresse); ?>
                                            </p>
                                            <div class="d-flex align-items-center gap-3">
                                                <span class="text-muted small">
                                                    <i class="ri-ruler-line me-1"></i><?php echo $estimation->superficie; ?> m²
                                                </span>
                                                <?php if ($estimation->zone_nom) : ?>
                                                <span class="badge bg-light text-dark"><?php echo htmlspecialchars($estimation->zone_nom); ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-bold text-info fs-6">
                                                <?php echo number_format($estimation->prix_estime, 0, ',', ' '); ?> TND
                                            </div>
                                            <small class="text-muted">
                                                <?php echo date('d/m/Y', strtotime($estimation->date_creation)); ?>
                                            </small>
                                            <br>
                                            <span class="badge bg-<?php 
                                                echo $estimation->statut == 'valide' ? 'success' : 
                                                    ($estimation->statut == 'rejete' ? 'danger' : 'warning'); 
                                            ?>-subtle text-<?php 
                                                echo $estimation->statut == 'valide' ? 'success' : 
                                                    ($estimation->statut == 'rejete' ? 'danger' : 'warning'); 
                                            ?>">
                                                <?php echo ucfirst($estimation->statut); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>

                            <?php if (($agent->estimations_count ?? 0) > count($estimations)) : ?>
                            <div class="text-center mt-3">
                                <a href="<?php echo base_url('estimations?agent=' . $agent->user_id); ?>" class="btn btn-outline-info">
                                    Voir les <?php echo ($agent->estimations_count ?? 0) - count($estimations); ?> estimations restantes
                                </a>
                            </div>
                            <?php endif; ?>

                            <?php else : ?>
                            <div class="text-center py-4">
                                <div class="avatar-lg mx-auto mb-3">
                                    <div class="avatar-title bg-info-subtle text-info rounded-circle fs-24">
                                        <i class="ri-calculator-line"></i>
                                    </div>
                                </div>
                                <h6 class="mb-2">Aucune estimation</h6>
                                <p class="text-muted">Cet agent n'a pas encore créé d'estimations.</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Transactions Section -->
                <div class="col-xl-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="ri-exchange-line me-2 text-success"></i>Transactions Récentes (<?php echo count($transactions ?? []); ?>)
                            </h5>
                            <a href="<?php echo base_url('transactions?agent=' . $agent->user_id); ?>" class="btn btn-success btn-sm">
                                Voir toutes
                            </a>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($transactions)) : ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($transactions as $transaction) : ?>
                                <div class="list-group-item border-0 px-0">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">
                                                <?php echo ucfirst($transaction->type); ?>
                                                <?php if ($transaction->property_type) : ?>
                                                - <?php echo htmlspecialchars($transaction->property_type); ?>
                                                <?php endif; ?>
                                            </h6>
                                            <?php if ($transaction->property_address) : ?>
                                            <p class="text-muted small mb-1">
                                                <i class="ri-map-pin-line me-1"></i>
                                                <?php echo htmlspecialchars($transaction->property_address); ?>
                                            </p>
                                            <?php endif; ?>
                                            <?php if ($transaction->client_nom || $transaction->client_prenom) : ?>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="text-muted small">
                                                    <i class="ri-user-line me-1"></i>
                                                    <?php echo htmlspecialchars(trim($transaction->client_prenom . ' ' . $transaction->client_nom)); ?>
                                                </span>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-bold text-success fs-6">
                                                <?php echo number_format($transaction->montant ?? 0, 0, ',', ' '); ?> TND
                                            </div>
                                            <?php if ($transaction->commission) : ?>
                                            <div class="small text-muted">
                                                Commission: <?php echo number_format($transaction->commission, 0, ',', ' '); ?> TND
                                            </div>
                                            <?php endif; ?>
                                            <small class="text-muted">
                                                <?php echo date('d/m/Y', strtotime($transaction->date_cloture)); ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>

                            <?php if (($agent->transactions_count ?? 0) > count($transactions)) : ?>
                            <div class="text-center mt-3">
                                <a href="<?php echo base_url('transactions?agent=' . $agent->user_id); ?>" class="btn btn-outline-success">
                                    Voir les <?php echo ($agent->transactions_count ?? 0) - count($transactions); ?> transactions restantes
                                </a>
                            </div>
                            <?php endif; ?>

                            <?php else : ?>
                            <div class="text-center py-4">
                                <div class="avatar-lg mx-auto mb-3">
                                    <div class="avatar-title bg-success-subtle text-success rounded-circle fs-24">
                                        <i class="ri-exchange-line"></i>
                                    </div>
                                </div>
                                <h6 class="mb-2">Aucune transaction</h6>
                                <p class="text-muted">Cet agent n'a pas encore finalisé de transactions.</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Debug Links (pour développement) -->
            <?php if (ENVIRONMENT === 'development' || ($this->session->userdata('user_type') === 'admin')) : ?>
            <div class="row mt-3">
                <div class="col-12">
                    <div class="card border-warning">
                        <div class="card-header bg-warning-subtle">
                            <h6 class="card-title mb-0 text-warning"><i class="ri-bug-line me-1"></i>Outils de débogage</h6>
                        </div>
                        <div class="card-body">
                            <div class="btn-group" role="group">
                                <a href="<?php echo base_url('agents/debug_properties/' . $agent->user_id); ?>" class="btn btn-outline-warning btn-sm">
                                    <i class="ri-search-line me-1"></i>Debug Propriétés
                                </a>
                                <a href="<?php echo base_url('agents/explore_structure'); ?>" class="btn btn-outline-info btn-sm">
                                    <i class="ri-database-line me-1"></i>Explorer Structure
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.avatar-xxl {
    width: 6rem;
    height: 6rem;
}

.avatar-xxl img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.profile-cover-img {
    position: relative;
}

.hover-card {
    transition: all 0.3s ease;
}

.hover-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
}

.btn-soft-primary {
    background-color: rgba(13, 110, 253, 0.1);
    border-color: transparent;
    color: #0d6efd;
}

.btn-soft-primary:hover {
    background-color: rgba(13, 110, 253, 0.2);
    color: #0d6efd;
}

.btn-soft-info {
    background-color: rgba(13, 202, 240, 0.1);
    border-color: transparent;
    color: #0dcaf0;
}

.btn-soft-info:hover {
    background-color: rgba(13, 202, 240, 0.2);
    color: #0dcaf0;
}

.btn-soft-success {
    background-color: rgba(25, 135, 84, 0.1);
    border-color: transparent;
    color: #198754;
}

.btn-soft-success:hover {
    background-color: rgba(25, 135, 84, 0.2);
    color: #198754;
}

.btn-soft-danger {
    background-color: rgba(220, 53, 69, 0.1);
    border-color: transparent;
    color: #dc3545;
}

.btn-soft-danger:hover {
    background-color: rgba(220, 53, 69, 0.2);
    color: #dc3545;
}

.stats-card {
    border-left: 3px solid transparent;
    transition: all 0.3s ease;
}

.stats-card:hover {
    border-left-color: var(--vz-primary);
}

.profile-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    padding: 2rem;
    color: white;
    margin-bottom: 2rem;
}

.agent-info-card {
    backdrop-filter: blur(10px);
    background: rgba(255, 255, 255, 0.95);
    border-radius: 15px;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.modal-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid #dee2e6;
}

.fs-11 {
    font-size: 0.6875rem !important;
}

.fs-12 {
    font-size: 0.75rem !important;
}

@media (max-width: 768px) {
    .profile-header {
        padding: 1.5rem;
        text-align: center;
    }
    
    .hover-card:hover {
        transform: none;
    }
}
</style>

<!-- Modales pour les détails -->
<!-- Modal Propriétés -->
<div class="modal fade" id="propertiesModal" tabindex="-1" aria-labelledby="propertiesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="propertiesModalLabel">
                    <i class="ri-home-line me-2"></i>Détails des Propriétés
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="propertiesContent">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Estimations -->
<div class="modal fade" id="estimationsModal" tabindex="-1" aria-labelledby="estimationsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="estimationsModalLabel">
                    <i class="ri-calculator-line me-2"></i>Détails des Estimations
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="estimationsContent">
                <div class="text-center">
                    <div class="spinner-border text-info" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Transactions -->
<div class="modal fade" id="transactionsModal" tabindex="-1" aria-labelledby="transactionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transactionsModalLabel">
                    <i class="ri-exchange-line me-2"></i>Détails des Transactions
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="transactionsContent">
                <div class="text-center">
                    <div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Contacts -->
<div class="modal fade" id="contactsModal" tabindex="-1" aria-labelledby="contactsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contactsModalLabel">
                    <i class="ri-contacts-line me-2"></i>Détails des Contacts
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="contactsContent">
                <div class="text-center">
                    <div class="spinner-border text-danger" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showPropertiesDetails(userId) {
    // Utilisation de Bootstrap 5 modal (sans jQuery)
    const modal = new bootstrap.Modal(document.getElementById('propertiesModal'));
    modal.show();
    
    // Fetch API au lieu de jQuery AJAX
    fetch('<?php echo base_url("agent/get_properties_details/"); ?>' + userId)
        .then(response => response.text())
        .then(data => {
            document.getElementById('propertiesContent').innerHTML = data;
        })
        .catch(error => {
            document.getElementById('propertiesContent').innerHTML = '<div class="alert alert-danger"><i class="ri-error-warning-line me-2"></i>Erreur lors du chargement des propriétés</div>';
        });
}

function showEstimationsDetails(userId) {
    const modal = new bootstrap.Modal(document.getElementById('estimationsModal'));
    modal.show();
    
    fetch('<?php echo base_url("agent/get_estimations_details/"); ?>' + userId)
        .then(response => response.text())
        .then(data => {
            document.getElementById('estimationsContent').innerHTML = data;
        })
        .catch(error => {
            document.getElementById('estimationsContent').innerHTML = '<div class="alert alert-danger"><i class="ri-error-warning-line me-2"></i>Erreur lors du chargement des estimations</div>';
        });
}

function showTransactionsDetails(userId) {
    const modal = new bootstrap.Modal(document.getElementById('transactionsModal'));
    modal.show();
    
    fetch('<?php echo base_url("agent/get_transactions_details/"); ?>' + userId)
        .then(response => response.text())
        .then(data => {
            document.getElementById('transactionsContent').innerHTML = data;
        })
        .catch(error => {
            document.getElementById('transactionsContent').innerHTML = '<div class="alert alert-danger"><i class="ri-error-warning-line me-2"></i>Erreur lors du chargement des transactions</div>';
        });
}

function showContactsDetails(userId) {
    const modal = new bootstrap.Modal(document.getElementById('contactsModal'));
    modal.show();
    
    fetch('<?php echo base_url("agent/get_contacts_details/"); ?>' + userId)
        .then(response => response.text())
        .then(data => {
            document.getElementById('contactsContent').innerHTML = data;
        })
        .catch(error => {
            document.getElementById('contactsContent').innerHTML = '<div class="alert alert-danger"><i class="ri-error-warning-line me-2"></i>Erreur lors du chargement des contacts</div>';
        });
}

function resetContactsCount(userId) {
    if (confirm('Êtes-vous sûr de vouloir remettre à zéro le compteur de contacts ?')) {
        fetch('<?php echo base_url("agent/reset_contacts_count/"); ?>' + userId, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur lors de la remise à zéro');
            }
        })
        .catch(error => {
            alert('Erreur lors de la remise à zéro');
        });
    }
}
</script>

<style>
/* Modern Profile Styles */
.modern-profile-card {
    border-radius: 20px;
    overflow: hidden;
}

.profile-cover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 200px;
    position: relative;
}

.profile-gradient-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.8) 0%, rgba(118, 75, 162, 0.8) 100%);
}

.profile-avatar-container {
    position: relative;
}

.profile-avatar {
    width: 120px;
    height: 120px;
    border: 4px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    overflow: hidden;
    background: white;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.profile-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.profile-status-indicator {
    position: absolute;
    bottom: 8px;
    right: 8px;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    border: 3px solid white;
}

.profile-status-indicator.active {
    background-color: #10b981;
}

.profile-status-indicator.inactive {
    background-color: #ef4444;
}

.text-white-75 {
    color: rgba(255, 255, 255, 0.75) !important;
}

.btn-modern {
    border-radius: 12px;
    font-weight: 500;
    padding: 8px 20px;
    border: none;
    transition: all 0.3s ease;
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

/* Modern Stats Cards */
.modern-stat-card {
    border-radius: 16px;
    transition: all 0.3s ease;
    border: none !important;
}

.modern-stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1) !important;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
}

.stat-primary .stat-icon {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.stat-success .stat-icon {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.stat-info .stat-icon {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
}

.stat-warning .stat-icon {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.stat-number {
    font-size: 28px;
    font-weight: 700;
    line-height: 1;
    margin-bottom: 4px;
}

.stat-label {
    font-size: 14px;
    color: #6b7280;
    font-weight: 500;
}

.stat-action {
    width: 36px;
    height: 36px;
    border: none;
    transition: all 0.3s ease;
}

.stat-action:hover {
    transform: scale(1.1);
}

/* Modern Tabs */
.nav-modern {
    border: none;
    gap: 8px;
}

.nav-modern .nav-link {
    border: none;
    border-radius: 12px;
    padding: 12px 20px;
    color: #6b7280;
    font-weight: 500;
    transition: all 0.3s ease;
    background: transparent;
}

.nav-modern .nav-link:hover {
    background: #f3f4f6;
    color: #374151;
}

.nav-modern .nav-link.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

/* Performance Metrics */
.performance-metric {
    padding: 16px;
    border-radius: 12px;
    background: white;
    text-align: center;
}

.metric-value {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 4px;
}

.metric-label {
    font-size: 12px;
    color: #6b7280;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* Activity Timeline */
.activity-timeline {
    position: relative;
}

.activity-item {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
    position: relative;
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 16px;
    margin-right: 16px;
    flex-shrink: 0;
}

.activity-content {
    flex-grow: 1;
}

.activity-title {
    font-weight: 500;
    color: #374151;
    margin-bottom: 2px;
}

.activity-time {
    font-size: 12px;
    color: #6b7280;
}

/* Info Items */
.info-item {
    padding: 12px 0;
    border-bottom: 1px solid #f3f4f6;
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    font-size: 12px;
    color: #6b7280;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 4px;
}

.info-value {
    color: #374151;
    font-weight: 500;
}

/* Contact Info */
.contact-info {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 12px;
}

.contact-item i {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    background: rgba(102, 126, 234, 0.1);
}

.contact-label {
    font-size: 12px;
    color: #6b7280;
    font-weight: 500;
    margin-bottom: 2px;
}

.contact-value {
    color: #374151;
    font-weight: 500;
}

.contact-value a {
    color: inherit;
    text-decoration: none;
}

.contact-value a:hover {
    color: #667eea;
}

/* Loading States */
.spinner-border {
    width: 2rem;
    height: 2rem;
}

/* Responsive */
@media (max-width: 768px) {
    .profile-avatar {
        width: 80px;
        height: 80px;
    }
    
    .stat-number {
        font-size: 24px;
    }
    
    .profile-actions {
        margin-top: 20px;
    }
    
    .profile-actions .btn {
        width: 100%;
        margin-bottom: 8px;
    }
}

/* Card hover effects */
.card {
    transition: all 0.3s ease;
}

.hover-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
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
            
            // Load content dynamically for properties tab
            if (targetTab === '#properties') {
                loadPropertiesContent();
            }
        });
    });
    
    // Animate stats on page load
    animateStats();
});

function animateStats() {
    const statNumbers = document.querySelectorAll('.stat-number');
    statNumbers.forEach(stat => {
        const targetValue = parseInt(stat.textContent);
        let currentValue = 0;
        const increment = targetValue / 30;
        
        const timer = setInterval(() => {
            currentValue += increment;
            if (currentValue >= targetValue) {
                stat.textContent = targetValue;
                clearInterval(timer);
            } else {
                stat.textContent = Math.floor(currentValue);
            }
        }, 50);
    });
}

function loadPropertiesContent() {
    const propertiesContent = document.getElementById('properties-content');
    if (propertiesContent.innerHTML.includes('Chargement')) {
        fetch('<?php echo base_url("agent/get_properties_details/"); ?><?php echo $agent->user_id; ?>')
            .then(response => response.text())
            .then(data => {
                propertiesContent.innerHTML = data;
            })
            .catch(error => {
                propertiesContent.innerHTML = `
                    <div class="text-center py-5">
                        <i class="ri-error-warning-line fs-1 text-danger"></i>
                        <p class="text-muted mt-2">Erreur lors du chargement des propriétés</p>
                    </div>
                `;
            });
    }
}

// Enhanced modal functions with better UX
function showPropertiesDetails(userId) {
    const modal = new bootstrap.Modal(document.getElementById('propertiesModal'));
    modal.show();
    
    document.getElementById('propertiesContent').innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2 text-muted">Chargement des propriétés...</p>
        </div>
    `;
    
    fetch('<?php echo base_url("agent/get_properties_details/"); ?>' + userId)
        .then(response => response.text())
        .then(data => {
            document.getElementById('propertiesContent').innerHTML = data;
        })
        .catch(error => {
            document.getElementById('propertiesContent').innerHTML = `
                <div class="alert alert-danger">
                    <i class="ri-error-warning-line me-2"></i>
                    Erreur lors du chargement des propriétés
                </div>
            `;
        });
}
