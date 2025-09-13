<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- Modern Hero Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="hero-section rounded-4 p-4 position-relative overflow-hidden">
                        <div class="hero-bg"></div>
                        <div class="position-relative z-index-2">
                            <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between">
                                <div class="hero-content mb-3 mb-lg-0">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="hero-icon me-3">
                                            <i class="ri-team-line"></i>
                                        </div>
                                        <div>
                                            <h1 class="hero-title mb-2">Nos Agents Immobiliers</h1>
                                            <p class="hero-subtitle mb-0">Découvrez notre équipe d'experts en immobilier</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="hero-actions">
                                    <button class="btn btn-hero btn-lg me-2">
                                        <i class="ri-add-line me-2"></i>Ajouter un agent
                                    </button>
                                    <div class="btn-group">
                                        <button class="btn btn-hero-outline btn-lg dropdown-toggle" data-bs-toggle="dropdown">
                                            <i class="ri-download-line me-2"></i>Exporter
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#"><i class="ri-file-excel-line me-2"></i>Excel</a></li>
                                            <li><a class="dropdown-item" href="#"><i class="ri-file-pdf-line me-2"></i>PDF</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modern Stats Row -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="stats-card h-100">
                        <div class="stats-card-body">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon stats-icon-primary">
                                    <i class="ri-team-line"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="stats-label">Total Agents</p>
                                    <h3 class="stats-value mb-0"><?php echo count($agents); ?></h3>
                                    <p class="stats-trend mb-0">
                                        <span class="trend-positive">
                                            <i class="ri-arrow-up-line"></i> +5.2%
                                        </span>
                                        vs mois dernier
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="stats-card h-100">
                        <div class="stats-card-body">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon stats-icon-success">
                                    <i class="ri-user-star-line"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="stats-label">Agents Actifs</p>
                                    <h3 class="stats-value mb-0"><?php echo count(array_filter($agents, function($a) { return $a->is_active; })); ?></h3>
                                    <p class="stats-trend mb-0">
                                        <span class="trend-positive">
                                            <i class="ri-arrow-up-line"></i> +2.1%
                                        </span>
                                        cette semaine
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="stats-card h-100">
                        <div class="stats-card-body">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon stats-icon-info">
                                    <i class="ri-building-line"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="stats-label">Agences</p>
                                    <h3 class="stats-value mb-0"><?php echo count(array_unique(array_column($agents, 'agency_name'))); ?></h3>
                                    <p class="stats-trend mb-0">
                                        <span class="trend-neutral">
                                            <i class="ri-subtract-line"></i> Stable
                                        </span>
                                        ce trimestre
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="stats-card h-100">
                        <div class="stats-card-body">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon stats-icon-warning">
                                    <i class="ri-home-line"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="stats-label">Propriétés Gérées</p>
                                    <h3 class="stats-value mb-0"><?php echo array_sum(array_column($agents, 'properties_count')); ?></h3>
                                    <p class="stats-trend mb-0">
                                        <span class="trend-positive">
                                            <i class="ri-arrow-up-line"></i> +12.3%
                                        </span>
                                        ce mois-ci
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modern Filters Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="filter-section">
                        <div class="filter-header">
                            <h5 class="filter-title">
                                <i class="ri-filter-3-line me-2"></i>Filtres & Recherche
                            </h5>
                            <button class="btn btn-filter-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#filtersCollapse">
                                <i class="ri-arrow-down-s-line"></i>
                            </button>
                        </div>
                        <div class="collapse show" id="filtersCollapse">
                            <form method="GET" id="filtersForm" class="filter-form">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="filter-group">
                                            <label for="search" class="filter-label">
                                                <i class="ri-search-line me-1"></i>Recherche globale
                                            </label>
                                            <input type="text" class="form-control filter-input" id="search" name="search" 
                                                   placeholder="Nom, email, téléphone..." value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="filter-group">
                                            <label for="agency" class="filter-label">
                                                <i class="ri-building-line me-1"></i>Agence
                                            </label>
                                            <select class="form-select filter-select" id="agency" name="agency">
                                                <option value="">Toutes les agences</option>
                                                <?php foreach ($agencies as $agency): ?>
                                                    <option value="<?= $agency->ID ?>" <?php echo ($filters['agency'] ?? '') == $agency->ID ? 'selected' : ''; ?>><?= $agency->post_title ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="filter-group">
                                            <label for="status" class="filter-label">
                                                <i class="ri-user-settings-line me-1"></i>Statut
                                            </label>
                                            <select class="form-select filter-select" id="status" name="status">
                                                <option value="">Tous statuts</option>
                                                <option value="active" <?php echo ($filters['status'] ?? '') == 'active' ? 'selected' : ''; ?>>Actif</option>
                                                <option value="inactive" <?php echo ($filters['status'] ?? '') == 'inactive' ? 'selected' : ''; ?>>Inactif</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="filter-group">
                                            <label for="sort" class="filter-label">
                                                <i class="ri-sort-asc me-1"></i>Tri
                                            </label>
                                            <select class="form-select filter-select" id="sort" name="sort">
                                                <option value="name_asc" <?php echo ($filters['sort'] ?? '') == 'name_asc' ? 'selected' : ''; ?>>Nom A-Z</option>
                                                <option value="name_desc" <?php echo ($filters['sort'] ?? '') == 'name_desc' ? 'selected' : ''; ?>>Nom Z-A</option>
                                                <option value="properties_desc" <?php echo ($filters['sort'] ?? '') == 'properties_desc' ? 'selected' : ''; ?>>Plus de biens</option>
                                                <option value="recent" <?php echo ($filters['sort'] ?? '') == 'recent' ? 'selected' : ''; ?>>Plus récent</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="filter-actions">
                                            <button type="submit" class="btn btn-filter-search">
                                                <i class="ri-search-line me-1"></i>Rechercher
                                            </button>
                                            <button type="button" class="btn btn-filter-reset" onclick="location.href='<?php echo base_url('agents'); ?>'">
                                                <i class="ri-refresh-line"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modern Results Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="results-header">
                        <div class="results-info">
                            <h6 class="results-count">
                                <span class="count-number"><?php echo count($agents); ?></span>
                                <span class="count-text">agent(s) trouvé(s)</span>
                            </h6>
                            <p class="results-subtitle mb-0">Affichage des résultats filtrés</p>
                        </div>
                        <div class="results-actions">
                            <div class="view-mode-selector me-3">
                                <input type="radio" class="btn-check" name="viewMode" id="gridView" checked>
                                <label class="btn btn-view-mode" for="gridView" title="Vue en grille">
                                    <i class="ri-layout-grid-line"></i>
                                </label>
                                <input type="radio" class="btn-check" name="viewMode" id="listView">
                                <label class="btn btn-view-mode" for="listView" title="Vue en liste">
                                    <i class="ri-list-check"></i>
                                </label>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-results-action dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="ri-more-line me-1"></i>Actions
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#"><i class="ri-download-line me-2"></i>Exporter la sélection</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="ri-mail-send-line me-2"></i>Envoyer email groupé</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#"><i class="ri-settings-3-line me-2"></i>Gérer les colonnes</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Error Display -->
            <?php if (isset($error)) : ?>
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert alert-danger">
                        <i class="ri-error-warning-line me-2"></i><?php echo $error; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Modern Agents Grid -->
            <div class="row" id="agentsContainer">
                <?php if (!empty($agents)) : ?>
                    <?php foreach ($agents as $agent) : ?>
                    <div class="col-xl-4 col-lg-6 col-md-6 mb-4 agent-card-wrapper" data-agent-id="<?= $agent->agent_id ?>" data-agent-email="<?= htmlspecialchars($agent->agent_email) ?>">
                        <div class="agent-card h-100">
                            <!-- Agent Card Header -->
                            <div class="agent-card-header">
                                <div class="agent-avatar-wrapper">
                                    <?php
                                    // Utiliser directement la vue crm_avatar_agents
                                    $avatar_url = !empty($agent->agent_avatar) ? $agent->agent_avatar : base_url('assets/images/users/avatar-1.jpg');
                                    $fallback_avatar = base_url('assets/images/users/avatar-1.jpg');
                                    ?>
                                    <div class="agent-avatar">
                                        <img src="<?php echo $avatar_url; ?>" 
                                             alt="<?php echo htmlspecialchars($agent->agent_name); ?>" 
                                             class="avatar-img"
                                             onerror="this.onerror=null; this.src='<?php echo $fallback_avatar; ?>';">
                                        <div class="agent-status <?php echo ($agent->is_active ?? 1) ? 'active' : 'inactive'; ?>"></div>
                                    </div>
                                </div>
                                <div class="agent-info">
                                    <h5 class="agent-name">
                                        <a href="<?php echo base_url('agents/view/' . ($agent->agent_id ?? $agent->user_id ?? 0)); ?>" class="agent-link">
                                            <?php echo htmlspecialchars($agent->agent_name); ?>
                                        </a>
                                    </h5>
                                    <p class="agent-email"><?php echo htmlspecialchars($agent->agent_email); ?></p>
                                    <div class="agent-badges">
                                        <span class="agent-badge agent-badge-<?php echo ($agent->is_active ?? 1) ? 'active' : 'inactive'; ?>">
                                            <i class="ri-<?php echo ($agent->is_active ?? 1) ? 'check' : 'close'; ?>-line me-1"></i>
                                            <?php echo ($agent->is_active ?? 1) ? 'Actif' : 'Inactif'; ?>
                                        </span>
                                        <?php if (!empty($agent->agency_name)) : ?>
                                        <span class="agent-badge agent-badge-agency">
                                            <i class="ri-building-line me-1"></i>
                                            <?php echo htmlspecialchars($agent->agency_name); ?>
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Agent Stats -->
                            <div class="agent-stats">
                                <div class="stat-item">
                                    <div class="stat-icon">
                                        <i class="ri-home-line"></i>
                                    </div>
                                    <div class="stat-content">
                                        <span class="stat-value"><?php echo $agent->properties_count; ?></span>
                                        <span class="stat-label">Propriétés</span>
                                    </div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-icon">
                                        <i class="ri-calendar-line"></i>
                                    </div>
                                    <div class="stat-content">
                                        <span class="stat-value"><?php echo date('Y', strtotime($agent->registration_date ?? $agent->created_date ?? date('Y-m-d'))); ?></span>
                                        <span class="stat-label">Depuis</span>
                                    </div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-icon">
                                        <i class="ri-star-line"></i>
                                    </div>
                                    <div class="stat-content">
                                        <span class="stat-value">4.8</span>
                                        <span class="stat-label">Note</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Actions -->
                            <div class="agent-contact">
                                <?php if (!empty($agent->phone)) : ?>
                                <a href="tel:<?php echo $agent->phone; ?>" class="contact-btn contact-btn-phone" title="Appeler">
                                    <i class="ri-phone-line"></i>
                                    <span><?php echo $agent->phone; ?></span>
                                </a>
                                <?php endif; ?>
                                <?php if (!empty($agent->mobile)) : ?>
                                <a href="tel:<?php echo $agent->mobile; ?>" class="contact-btn contact-btn-mobile" title="Mobile">
                                    <i class="ri-smartphone-line"></i>
                                    <span><?php echo $agent->mobile; ?></span>
                                </a>
                                <?php endif; ?>
                                <?php if (!empty($agent->whatsapp)) : ?>
                                <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $agent->whatsapp); ?>" 
                                   class="contact-btn contact-btn-whatsapp" target="_blank" title="WhatsApp">
                                    <i class="ri-whatsapp-line"></i>
                                    <span>WhatsApp</span>
                                </a>
                                <?php endif; ?>
                                <a href="mailto:<?php echo $agent->agent_email; ?>" class="contact-btn contact-btn-email" title="Email">
                                    <i class="ri-mail-line"></i>
                                    <span>Email</span>
                                </a>
                            </div>

                            <!-- Agent Actions -->
                            <div class="agent-actions">
                                <a href="<?php echo base_url('agents/view/' . ($agent->agent_id ?? $agent->user_id ?? 0)); ?>" class="btn btn-agent-primary">
                                    <i class="ri-eye-line me-1"></i>Voir profil
                                </a>
                                <div class="dropdown">
                                    <button class="btn btn-agent-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                        <i class="ri-more-line"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="<?php echo base_url('properties?agent=' . ($agent->agent_id ?? $agent->user_id ?? 0)); ?>">
                                            <i class="ri-home-line me-2"></i>Ses propriétés
                                        </a></li>
                                        <li><a class="dropdown-item" href="mailto:<?php echo $agent->agent_email; ?>">
                                            <i class="ri-mail-line me-2"></i>Envoyer email
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="#">
                                            <i class="ri-edit-line me-2"></i>Modifier
                                        </a></li>
                                        <li><a class="dropdown-item text-danger" href="#">
                                            <i class="ri-delete-bin-line me-2"></i>Supprimer
                                        </a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="col-12">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="ri-team-line"></i>
                            </div>
                            <h5 class="empty-state-title">Aucun agent trouvé</h5>
                            <p class="empty-state-text">Aucun agent ne correspond aux critères de recherche sélectionnés.</p>
                            <div class="empty-state-actions">
                                <a href="<?php echo base_url('agents'); ?>" class="btn btn-empty-primary">
                                    <i class="ri-refresh-line me-1"></i>Réinitialiser les filtres
                                </a>
                                <button class="btn btn-empty-secondary">
                                    <i class="ri-add-line me-1"></i>Ajouter un agent
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
/* Modern Agents List Styles */
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    --warning-gradient: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
    --info-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    --card-shadow-hover: 0 20px 40px rgba(0, 0, 0, 0.15);
    --border-radius: 12px;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Hero Section */
.hero-section {
    background: var(--primary-gradient);
    color: white;
    border-radius: var(--border-radius);
    position: relative;
    overflow: hidden;
}

.hero-bg {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.1;
}

.z-index-2 { z-index: 2; }

.hero-icon {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    backdrop-filter: blur(10px);
}

.hero-title {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
}

.hero-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
}

.btn-hero {
    background: rgba(255, 255, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    backdrop-filter: blur(10px);
    transition: var(--transition);
}

.btn-hero:hover {
    background: rgba(255, 255, 255, 0.3);
    color: white;
    transform: translateY(-2px);
}

.btn-hero-outline {
    background: transparent;
    border: 1px solid rgba(255, 255, 255, 0.5);
    color: white;
    backdrop-filter: blur(10px);
}

.btn-hero-outline:hover {
    background: rgba(255, 255, 255, 0.1);
    color: white;
}

/* Stats Cards */
.stats-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--card-shadow);
    border: 1px solid rgba(0, 0, 0, 0.05);
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.stats-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--primary-gradient);
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--card-shadow-hover);
}

.stats-card-body {
    padding: 1.5rem;
}

.stats-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: white;
}

.stats-icon-primary { background: var(--primary-gradient); }
.stats-icon-success { background: var(--success-gradient); }
.stats-icon-info { background: var(--info-gradient); }
.stats-icon-warning { background: var(--warning-gradient); color: #333; }

.stats-label {
    font-size: 0.875rem;
    color: #6c757d;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stats-value {
    font-size: 2rem;
    font-weight: 700;
    color: #2c3e50;
    line-height: 1;
}

.stats-trend {
    font-size: 0.875rem;
    margin-top: 0.5rem;
}

.trend-positive { color: #28a745; }
.trend-negative { color: #dc3545; }
.trend-neutral { color: #6c757d; }

/* Filter Section */
.filter-section {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--card-shadow);
    overflow: hidden;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.filter-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 1rem 1.5rem;
    display: flex;
    align-items: center;
    justify-content: between;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.filter-title {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
    flex: 1;
}

.btn-filter-toggle {
    background: none;
    border: none;
    color: #6c757d;
    font-size: 1.25rem;
    transition: var(--transition);
}

.btn-filter-toggle:hover {
    color: #2c3e50;
    transform: rotate(180deg);
}

.filter-form {
    padding: 1.5rem;
}

.filter-group {
    margin-bottom: 0;
}

.filter-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
}

.filter-input, .filter-select {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    transition: var(--transition);
    font-size: 0.875rem;
}

.filter-input:focus, .filter-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
}

.filter-actions {
    display: flex;
    align-items: end;
    gap: 0.5rem;
    height: 100%;
}

.btn-filter-search {
    background: var(--primary-gradient);
    border: none;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    transition: var(--transition);
}

.btn-filter-search:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    color: white;
}

.btn-filter-reset {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    color: #6c757d;
    padding: 0.75rem;
    border-radius: 8px;
    transition: var(--transition);
}

.btn-filter-reset:hover {
    background: #e9ecef;
    color: #495057;
}

/* Results Header */
.results-header {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--card-shadow);
    padding: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: between;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.results-count {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 700;
    color: #2c3e50;
}

.count-number {
    color: #667eea;
    font-size: 1.5rem;
}

.results-subtitle {
    color: #6c757d;
    font-size: 0.875rem;
}

.results-actions {
    display: flex;
    align-items: center;
}

.view-mode-selector {
    display: flex;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    overflow: hidden;
}

.btn-view-mode {
    background: white;
    border: none;
    color: #6c757d;
    padding: 0.5rem 0.75rem;
    transition: var(--transition);
}

.btn-check:checked + .btn-view-mode {
    background: var(--primary-gradient);
    color: white;
}

.btn-results-action {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    color: #495057;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.875rem;
    transition: var(--transition);
}

.btn-results-action:hover {
    background: #e9ecef;
    color: #2c3e50;
}

/* Agent Cards */
.agent-card-wrapper {
    transition: var(--transition);
}

.agent-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--card-shadow);
    border: 1px solid rgba(0, 0, 0, 0.05);
    overflow: hidden;
    transition: var(--transition);
    position: relative;
}

.agent-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--card-shadow-hover);
}

.agent-card-header {
    padding: 1.5rem 1.5rem 1rem;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.agent-avatar-wrapper {
    display: flex;
    justify-content: center;
    margin-bottom: 1rem;
}

.agent-avatar {
    position: relative;
    width: 80px;
    height: 80px;
}

.avatar-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
    border: 4px solid white;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: var(--transition);
}

.agent-card:hover .avatar-img {
    transform: scale(1.05);
}

.agent-status {
    position: absolute;
    bottom: 5px;
    right: 5px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 3px solid white;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.agent-status.active { background: #28a745; }
.agent-status.inactive { background: #dc3545; }

.agent-info {
    text-align: center;
}

.agent-name {
    margin: 0 0 0.5rem;
    font-size: 1.1rem;
    font-weight: 700;
}

.agent-link {
    color: #2c3e50;
    text-decoration: none;
    transition: var(--transition);
}

.agent-link:hover {
    color: #667eea;
}

.agent-email {
    color: #6c757d;
    font-size: 0.875rem;
    margin: 0 0 1rem;
}

.agent-badges {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.agent-badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-weight: 600;
    display: flex;
    align-items: center;
}

.agent-badge-active {
    background: rgba(40, 167, 69, 0.1);
    color: #28a745;
}

.agent-badge-inactive {
    background: rgba(220, 53, 69, 0.1);
    color: #dc3545;
}

.agent-badge-agency {
    background: rgba(102, 126, 234, 0.1);
    color: #667eea;
}

/* Agent Stats */
.agent-stats {
    padding: 1rem 1.5rem;
    background: #f8f9fa;
    display: flex;
    justify-content: space-around;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.stat-item {
    text-align: center;
    flex: 1;
}

.stat-icon {
    width: 32px;
    height: 32px;
    background: var(--primary-gradient);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    margin: 0 auto 0.5rem;
    font-size: 0.875rem;
}

.stat-value {
    display: block;
    font-size: 1.1rem;
    font-weight: 700;
    color: #2c3e50;
    line-height: 1;
}

.stat-label {
    display: block;
    font-size: 0.75rem;
    color: #6c757d;
    margin-top: 0.25rem;
}

/* Contact Actions */
.agent-contact {
    padding: 1rem 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.contact-btn {
    display: flex;
    align-items: center;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    transition: var(--transition);
    border: 1px solid;
}

.contact-btn i {
    margin-right: 0.5rem;
    width: 16px;
}

.contact-btn-phone {
    background: rgba(13, 110, 253, 0.1);
    color: #0d6efd;
    border-color: rgba(13, 110, 253, 0.2);
}

.contact-btn-phone:hover {
    background: rgba(13, 110, 253, 0.2);
    color: #0d6efd;
    transform: translateX(5px);
}

.contact-btn-mobile {
    background: rgba(25, 135, 84, 0.1);
    color: #198754;
    border-color: rgba(25, 135, 84, 0.2);
}

.contact-btn-mobile:hover {
    background: rgba(25, 135, 84, 0.2);
    color: #198754;
    transform: translateX(5px);
}

.contact-btn-whatsapp {
    background: rgba(37, 211, 102, 0.1);
    color: #25d366;
    border-color: rgba(37, 211, 102, 0.2);
}

.contact-btn-whatsapp:hover {
    background: rgba(37, 211, 102, 0.2);
    color: #25d366;
    transform: translateX(5px);
}

.contact-btn-email {
    background: rgba(220, 53, 69, 0.1);
    color: #dc3545;
    border-color: rgba(220, 53, 69, 0.2);
}

.contact-btn-email:hover {
    background: rgba(220, 53, 69, 0.2);
    color: #dc3545;
    transform: translateX(5px);
}

/* Agent Actions */
.agent-actions {
    padding: 1rem 1.5rem;
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.btn-agent-primary {
    background: var(--primary-gradient);
    border: none;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    flex: 1;
    text-align: center;
    transition: var(--transition);
}

.btn-agent-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    color: white;
}

.btn-agent-secondary {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    color: #6c757d;
    padding: 0.75rem;
    border-radius: 8px;
    transition: var(--transition);
}

.btn-agent-secondary:hover {
    background: #e9ecef;
    color: #495057;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--card-shadow);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.empty-state-icon {
    width: 80px;
    height: 80px;
    background: rgba(102, 126, 234, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 2rem;
    color: #667eea;
}

.empty-state-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 1rem;
}

.empty-state-text {
    color: #6c757d;
    font-size: 1rem;
    margin-bottom: 2rem;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

.empty-state-actions {
    display: flex;
    justify-content: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.btn-empty-primary {
    background: var(--primary-gradient);
    border: none;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    transition: var(--transition);
}

.btn-empty-primary:hover {
    transform: translateY(-2px);
    color: white;
}

.btn-empty-secondary {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    color: #495057;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    transition: var(--transition);
}

.btn-empty-secondary:hover {
    background: #e9ecef;
    color: #2c3e50;
}

/* List View */
.list-view .agent-card-wrapper {
    margin-bottom: 1rem;
}

.list-view .agent-card {
    display: flex;
    flex-direction: row;
    align-items: center;
    padding: 1.5rem;
}

.list-view .agent-card-header {
    background: none;
    border-bottom: none;
    padding: 0;
    margin-right: 2rem;
    display: flex;
    align-items: center;
    flex-shrink: 0;
}

.list-view .agent-avatar-wrapper {
    margin-bottom: 0;
    margin-right: 1rem;
}

.list-view .agent-avatar {
    width: 60px;
    height: 60px;
}

.list-view .agent-info {
    text-align: left;
}

.list-view .agent-stats {
    background: none;
    border-bottom: none;
    padding: 0;
    margin: 0 2rem;
    flex-shrink: 0;
}

.list-view .agent-contact {
    flex-direction: row;
    flex-wrap: wrap;
    margin: 0 1rem;
    padding: 0;
}

.list-view .agent-actions {
    padding: 0;
    flex-shrink: 0;
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-section {
        text-align: center;
    }
    
    .hero-content .d-flex {
        flex-direction: column;
        align-items: center;
    }
    
    .hero-actions {
        margin-top: 1rem;
    }
    
    .filter-form .row {
        --bs-gutter-x: 0.5rem;
    }
    
    .results-header {
        flex-direction: column;
        align-items: stretch;
        gap: 1rem;
    }
    
    .list-view .agent-card {
        flex-direction: column;
        text-align: center;
    }
    
    .list-view .agent-card-header {
        margin-right: 0;
        margin-bottom: 1rem;
        flex-direction: column;
    }
    
    .list-view .agent-stats {
        margin: 1rem 0;
    }
    
    .list-view .agent-contact {
        margin: 1rem 0;
    }
}

@media (max-width: 576px) {
    .hero-title {
        font-size: 1.5rem;
    }
    
    .stats-value {
        font-size: 1.5rem;
    }
    
    .agent-contact {
        padding: 0.75rem 1rem;
    }
    
    .agent-actions {
        padding: 0.75rem 1rem;
        flex-direction: column;
    }
    
    .btn-agent-primary {
        margin-bottom: 0.5rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // View Mode Toggle
    const gridViewBtn = document.getElementById('gridView');
    const listViewBtn = document.getElementById('listView');
    const agentsContainer = document.getElementById('agentsContainer');
    
    gridViewBtn.addEventListener('change', function() {
        if (this.checked) {
            agentsContainer.classList.remove('list-view');
            localStorage.setItem('agentsViewMode', 'grid');
        }
    });
    
    listViewBtn.addEventListener('change', function() {
        if (this.checked) {
            agentsContainer.classList.add('list-view');
            localStorage.setItem('agentsViewMode', 'list');
        }
    });
    
    // Restore saved view mode
    const savedViewMode = localStorage.getItem('agentsViewMode');
    if (savedViewMode === 'list') {
        listViewBtn.checked = true;
        agentsContainer.classList.add('list-view');
    }
    
    // Filter Form Auto-submit
    const filterForm = document.getElementById('filtersForm');
    const filterInputs = filterForm.querySelectorAll('select, input[type="text"]');
    
    filterInputs.forEach(input => {
        if (input.type === 'text') {
            // Debounce text input
            let timeout;
            input.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    filterForm.submit();
                }, 500);
            });
        } else {
            // Immediate submit for selects
            input.addEventListener('change', function() {
                filterForm.submit();
            });
        }
    });
    
    // Filter Collapse Toggle
    const filterToggle = document.querySelector('.btn-filter-toggle');
    const filtersCollapse = document.getElementById('filtersCollapse');
    
    if (filterToggle && filtersCollapse) {
        filterToggle.addEventListener('click', function() {
            const icon = this.querySelector('i');
            if (filtersCollapse.classList.contains('show')) {
                icon.style.transform = 'rotate(0deg)';
            } else {
                icon.style.transform = 'rotate(180deg)';
            }
        });
    }
    
    // Smooth scroll to top when filters change
    filterForm.addEventListener('submit', function() {
        setTimeout(() => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }, 100);
    });
    
    // Agent Card Animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    // Observe agent cards
    document.querySelectorAll('.agent-card-wrapper').forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
        observer.observe(card);
    });
    
    // Avatar Error Handling with Retry
    document.querySelectorAll('.avatar-img').forEach(img => {
        let retryCount = 0;
        const maxRetries = 2;
        
        function handleImageError() {
            retryCount++;
            if (retryCount <= maxRetries) {
                // Try Gravatar
                const email = img.closest('.agent-card').dataset.agentEmail;
                if (email && retryCount === 1) {
                    const hash = md5(email.toLowerCase().trim());
                    img.src = `https://www.gravatar.com/avatar/${hash}?d=identicon&s=200`;
                    return;
                }
            }
            // Final fallback
            img.src = '<?php echo base_url('assets/images/users/avatar-1.jpg'); ?>';
        }
        
        img.addEventListener('error', handleImageError);
    });
    
    // Contact Button Interactions
    document.querySelectorAll('.contact-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            // Add ripple effect
            const ripple = document.createElement('span');
            ripple.classList.add('ripple');
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
    
    // Search Input Enhancement
    const searchInput = document.getElementById('search');
    if (searchInput) {
        const searchIcon = searchInput.parentElement.querySelector('i');
        
        searchInput.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        searchInput.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });
        
        searchInput.addEventListener('input', function() {
            if (this.value.length > 0) {
                searchIcon.className = 'ri-close-line me-1';
                searchIcon.style.cursor = 'pointer';
                searchIcon.onclick = () => {
                    searchInput.value = '';
                    searchIcon.className = 'ri-search-line me-1';
                    searchIcon.style.cursor = 'default';
                    searchIcon.onclick = null;
                    filterForm.submit();
                };
            } else {
                searchIcon.className = 'ri-search-line me-1';
                searchIcon.style.cursor = 'default';
                searchIcon.onclick = null;
            }
        });
    }
    
    // Keyboard Shortcuts
    document.addEventListener('keydown', function(e) {
        // Alt + G = Grid View
        if (e.altKey && e.key === 'g') {
            e.preventDefault();
            gridViewBtn.click();
        }
        
        // Alt + L = List View  
        if (e.altKey && e.key === 'l') {
            e.preventDefault();
            listViewBtn.click();
        }
        
        // Alt + F = Focus Search
        if (e.altKey && e.key === 'f') {
            e.preventDefault();
            searchInput?.focus();
        }
    });
    
    // Add tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// MD5 hash function for Gravatar
function md5(string) {
    function md5cycle(x, k) {
        var a = x[0], b = x[1], c = x[2], d = x[3];
        a = ff(a, b, c, d, k[0], 7, -680876936);
        d = ff(d, a, b, c, k[1], 12, -389564586);
        c = ff(c, d, a, b, k[2], 17, 606105819);
        b = ff(b, c, d, a, k[3], 22, -1044525330);
        a = ff(a, b, c, d, k[4], 7, -176418897);
        d = ff(d, a, b, c, k[5], 12, 1200080426);
        c = ff(c, d, a, b, k[6], 17, -1473231341);
        b = ff(b, c, d, a, k[7], 22, -45705983);
        a = ff(a, b, c, d, k[8], 7, 1770035416);
        d = ff(d, a, b, c, k[9], 12, -1958414417);
        c = ff(c, d, a, b, k[10], 17, -42063);
        b = ff(b, c, d, a, k[11], 22, -1990404162);
        a = ff(a, b, c, d, k[12], 7, 1804603682);
        d = ff(d, a, b, c, k[13], 12, -40341101);
        c = ff(c, d, a, b, k[14], 17, -1502002290);
        b = ff(b, c, d, a, k[15], 22, 1236535329);
        
        a = gg(a, b, c, d, k[1], 5, -165796510);
        d = gg(d, a, b, c, k[6], 9, -1069501632);
        c = gg(c, d, a, b, k[11], 14, 643717713);
        b = gg(b, c, d, a, k[0], 20, -373897302);
        a = gg(a, b, c, d, k[5], 5, -701558691);
        d = gg(d, a, b, c, k[10], 9, 38016083);
        c = gg(c, d, a, b, k[15], 14, -660478335);
        b = gg(b, c, d, a, k[4], 20, -405537848);
        a = gg(a, b, c, d, k[9], 5, 568446438);
        d = gg(d, a, b, c, k[14], 9, -1019803690);
        c = gg(c, d, a, b, k[3], 14, -187363961);
        b = gg(b, c, d, a, k[8], 20, 1163531501);
        a = gg(a, b, c, d, k[13], 5, -1444681467);
        d = gg(d, a, b, c, k[2], 9, -51403784);
        c = gg(c, d, a, b, k[7], 14, 1735328473);
        b = gg(b, c, d, a, k[12], 20, -1926607734);
        
        a = hh(a, b, c, d, k[5], 4, -378558);
        d = hh(d, a, b, c, k[8], 11, -2022574463);
        c = hh(c, d, a, b, k[11], 16, 1839030562);
        b = hh(b, c, d, a, k[14], 23, -35309556);
        a = hh(a, b, c, d, k[1], 4, -1530992060);
        d = hh(d, a, b, c, k[4], 11, 1272893353);
        c = hh(c, d, a, b, k[7], 16, -155497632);
        b = hh(b, c, d, a, k[10], 23, -1094730640);
        a = hh(a, b, c, d, k[13], 4, 681279174);
        d = hh(d, a, b, c, k[0], 11, -358537222);
        c = hh(c, d, a, b, k[3], 16, -722521979);
        b = hh(b, c, d, a, k[6], 23, 76029189);
        a = hh(a, b, c, d, k[9], 4, -640364487);
        d = hh(d, a, b, c, k[12], 11, -421815835);
        c = hh(c, d, a, b, k[15], 16, 530742520);
        b = hh(b, c, d, a, k[2], 23, -995338651);
        
        a = ii(a, b, c, d, k[0], 6, -198630844);
        d = ii(d, a, b, c, k[7], 10, 1126891415);
        c = ii(c, d, a, b, k[14], 15, -1416354905);
        b = ii(b, c, d, a, k[5], 21, -57434055);
        a = ii(a, b, c, d, k[12], 6, 1700485571);
        d = ii(d, a, b, c, k[3], 10, -1894986606);
        c = ii(c, d, a, b, k[10], 15, -1051523);
        b = ii(b, c, d, a, k[1], 21, -2054922799);
        a = ii(a, b, c, d, k[8], 6, 1873313359);
        d = ii(d, a, b, c, k[15], 10, -30611744);
        c = ii(c, d, a, b, k[6], 15, -1560198380);
        b = ii(b, c, d, a, k[13], 21, 1309151649);
        a = ii(a, b, c, d, k[4], 6, -145523070);
        d = ii(d, a, b, c, k[11], 10, -1120210379);
        c = ii(c, d, a, b, k[2], 15, 718787259);
        b = ii(b, c, d, a, k[9], 21, -343485551);
        
        x[0] = add32(a, x[0]);
        x[1] = add32(b, x[1]);
        x[2] = add32(c, x[2]);
        x[3] = add32(d, x[3]);
    }
    
    function cmn(q, a, b, x, s, t) {
        a = add32(add32(a, q), add32(x, t));
        return add32((a << s) | (a >>> (32 - s)), b);
    }
    
    function ff(a, b, c, d, x, s, t) {
        return cmn((b & c) | ((~b) & d), a, b, x, s, t);
    }
    
    function gg(a, b, c, d, x, s, t) {
        return cmn((b & d) | (c & (~d)), a, b, x, s, t);
    }
    
    function hh(a, b, c, d, x, s, t) {
        return cmn(b ^ c ^ d, a, b, x, s, t);
    }
    
    function ii(a, b, c, d, x, s, t) {
        return cmn(c ^ (b | (~d)), a, b, x, s, t);
    }
    
    function md51(s) {
        var n = s.length,
            state = [1732584193, -271733879, -1732584194, 271733878], i;
        for (i = 64; i <= s.length; i += 64) {
            md5cycle(state, md5blk(s.substring(i - 64, i)));
        }
        s = s.substring(i - 64);
        var tail = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        for (i = 0; i < s.length; i++)
            tail[i >> 2] |= s.charCodeAt(i) << ((i % 4) << 3);
        tail[i >> 2] |= 0x80 << ((i % 4) << 3);
        if (i > 55) {
            md5cycle(state, tail);
            for (i = 0; i < 16; i++) tail[i] = 0;
        }
        tail[14] = n * 8;
        md5cycle(state, tail);
        return state;
    }
    
    function md5blk(s) {
        var md5blks = [], i;
        for (i = 0; i < 64; i += 4) {
            md5blks[i >> 2] = s.charCodeAt(i)
                + (s.charCodeAt(i + 1) << 8)
                + (s.charCodeAt(i + 2) << 16)
                + (s.charCodeAt(i + 3) << 24);
        }
        return md5blks;
    }
    
    var hex_chr = '0123456789abcdef'.split('');
    
    function rhex(n) {
        var s = '', j = 0;
        for (; j < 4; j++)
            s += hex_chr[(n >> (j * 8 + 4)) & 0x0F]
                + hex_chr[(n >> (j * 8)) & 0x0F];
        return s;
    }
    
    function hex(x) {
        for (var i = 0; i < x.length; i++)
            x[i] = rhex(x[i]);
        return x.join('');
    }
    
    function add32(a, b) {
        return (a + b) & 0xFFFFFFFF;
    }
    
    if (string === undefined) return string;
    return hex(md51(string));
}
</script>
