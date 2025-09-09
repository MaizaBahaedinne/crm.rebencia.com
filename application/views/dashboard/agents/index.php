<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- Page Title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">Agents immobiliers</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Dashboard</a></li>
                                <li class="breadcrumb-item active">Agents</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Row -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card card-height-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-3">
                                        <i class="ri-team-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-medium text-muted mb-1">Total Agents</p>
                                    <h4 class="mb-0"><?php echo count($agents); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-height-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-success-subtle text-success rounded-circle fs-3">
                                        <i class="ri-user-star-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-medium text-muted mb-1">Agents Actifs</p>
                                    <h4 class="mb-0"><?php echo count(array_filter($agents, function($a) { return $a->is_active; })); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-height-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-info-subtle text-info rounded-circle fs-3">
                                        <i class="ri-building-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-medium text-muted mb-1">Agences</p>
                                    <h4 class="mb-0"><?php echo count(array_unique(array_column($agents, 'agency_name'))); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-height-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-warning-subtle text-warning rounded-circle fs-3">
                                        <i class="ri-home-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-medium text-muted mb-1">Propriétés Gérées</p>
                                    <h4 class="mb-0"><?php echo array_sum(array_column($agents, 'properties_count')); ?></h4>
                                </div>
                            </div>
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
                                <div class="col-md-4">
                                    <label for="search" class="form-label">Recherche</label>
                                    <input type="text" class="form-control" id="search" name="search" 
                                           placeholder="Nom, email..." value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="agency" class="form-label">Agence</label>
                                    <select class="form-select" id="agency" name="agency">
                                        <option value="">Toutes les agences</option>
                                        <?php foreach ($agencies as $agency): ?>
                                            <option value="<?= $agency->ID ?>" <?php echo ($filters['agency'] ?? '') == $agency->ID ? 'selected' : ''; ?>><?= $agency->post_title ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="status" class="form-label">Statut</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="">Tous statuts</option>
                                        <option value="active" <?php echo ($filters['status'] ?? '') == 'active' ? 'selected' : ''; ?>>Actif</option>
                                        <option value="inactive" <?php echo ($filters['status'] ?? '') == 'inactive' ? 'selected' : ''; ?>>Inactif</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="sort" class="form-label">Tri</label>
                                    <select class="form-select" id="sort" name="sort">
                                        <option value="name_asc" <?php echo ($filters['sort'] ?? '') == 'name_asc' ? 'selected' : ''; ?>>Nom A-Z</option>
                                        <option value="name_desc" <?php echo ($filters['sort'] ?? '') == 'name_desc' ? 'selected' : ''; ?>>Nom Z-A</option>
                                        <option value="properties_desc" <?php echo ($filters['sort'] ?? '') == 'properties_desc' ? 'selected' : ''; ?>>Plus de biens</option>
                                        <option value="recent" <?php echo ($filters['sort'] ?? '') == 'recent' ? 'selected' : ''; ?>>Plus récent</option>
                                    </select>
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
                        <h6 class="mb-0 me-3"><?php echo count($agents); ?> agent(s) trouvé(s)</h6>
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
                    <button class="btn btn-primary">
                        <i class="ri-add-line me-1"></i>Ajouter un agent
                    </button>
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

            <!-- Agents Grid -->
            <div class="row" id="agentsContainer">
                <?php if (!empty($agents)) : ?>
                    <?php foreach ($agents as $agent) : ?>
                    <div class="col-xl-4 col-lg-6 col-md-6 mb-4 agent-card">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <!-- Agent Header -->
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar-lg flex-shrink-0">
                                        <?php
                                        // Fonction pour obtenir l'avatar avec fallback Gravatar
                                        function get_agent_avatar_list($agent) {
                                            if (!empty($agent->agent_avatar) && filter_var($agent->agent_avatar, FILTER_VALIDATE_URL)) {
                                                return $agent->agent_avatar;
                                            }
                                            // Fallback vers Gravatar
                                            $email = !empty($agent->agent_email) ? $agent->agent_email : $agent->user_email;
                                            if (!empty($email)) {
                                                $hash = md5(strtolower(trim($email)));
                                                return "https://www.gravatar.com/avatar/{$hash}?d=identicon&s=200";
                                            }
                                            // Dernier fallback
                                            return base_url('assets/images/users/avatar-1.jpg');
                                        }
                                        $avatar_url = get_agent_avatar_list($agent);
                                        ?>
                                        <img src="<?php echo $avatar_url; ?>" alt="<?php echo htmlspecialchars($agent->agent_name); ?>" 
                                             class="img-fluid rounded-circle"
                                             onerror="this.onerror=null; this.src='<?php echo base_url('assets/images/users/avatar-1.jpg'); ?>';">
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="mb-1">
                                            <a href="<?php echo base_url('agents/view/' . $agent->user_id); ?>" class="text-decoration-none">
                                                <?php echo htmlspecialchars($agent->agent_name); ?>
                                            </a>
                                        </h5>
                                        <p class="text-muted mb-1"><?php echo htmlspecialchars($agent->agent_email); ?></p>
                                        <span class="badge bg-<?php echo $agent->is_active ? 'success' : 'danger'; ?>-subtle text-<?php echo $agent->is_active ? 'success' : 'danger'; ?>">
                                            <?php echo $agent->is_active ? 'Actif' : 'Inactif'; ?>
                                        </span>
                                    </div>
                                </div>

                                <!-- Agent Details -->
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <small class="text-muted">Agence:</small><br>
                                        <span class="fw-medium"><?php echo htmlspecialchars($agent->agency_name ?: 'Indépendant'); ?></span>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Propriétés:</small><br>
                                        <span class="fw-medium"><?php echo $agent->properties_count; ?> biens</span>
                                    </div>
                                </div>

                                <!-- Contact Info -->
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    <?php if (!empty($agent->phone)) : ?>
                                    <a href="tel:<?php echo $agent->phone; ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="ri-phone-line me-1"></i><?php echo $agent->phone; ?>
                                    </a>
                                    <?php endif; ?>
                                    <?php if (!empty($agent->mobile)) : ?>
                                    <a href="tel:<?php echo $agent->mobile; ?>" class="btn btn-sm btn-outline-success">
                                        <i class="ri-smartphone-line me-1"></i><?php echo $agent->mobile; ?>
                                    </a>
                                    <?php endif; ?>
                                    <?php if (!empty($agent->whatsapp)) : ?>
                                    <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $agent->whatsapp); ?>" class="btn btn-sm btn-outline-success" target="_blank">
                                        <i class="ri-whatsapp-line me-1"></i>WhatsApp
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Card Footer -->
                            <div class="card-footer bg-transparent border-top-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="ri-calendar-line me-1"></i>
                                        Inscrit le <?php echo date('d/m/Y', strtotime($agent->registration_date)); ?>
                                    </small>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?php echo base_url('agents/view/' . $agent->user_id); ?>" class="btn btn-outline-primary">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="ri-more-line"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="<?php echo base_url('agents/view/' . $agent->user_id); ?>"><i class="ri-eye-line me-2"></i>Voir profil</a></li>
                                                <li><a class="dropdown-item" href="<?php echo base_url('properties?agent=' . $agent->user_id); ?>"><i class="ri-home-line me-2"></i>Ses propriétés</a></li>
                                                <li><a class="dropdown-item" href="mailto:<?php echo $agent->agent_email; ?>"><i class="ri-mail-line me-2"></i>Envoyer email</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item" href="#"><i class="ri-edit-line me-2"></i>Modifier</a></li>
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
                                    <i class="ri-team-line"></i>
                                </div>
                            </div>
                            <h5 class="mb-3">Aucun agent trouvé</h5>
                            <p class="text-muted mb-4">Aucun agent ne correspond aux critères de recherche sélectionnés.</p>
                            <a href="<?php echo base_url('agents'); ?>" class="btn btn-primary">
                                <i class="ri-refresh-line me-1"></i>Réinitialiser les filtres
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
/* Styles pour les cartes agents */
.agent-card {
    transition: transform 0.3s ease;
}

.agent-card:hover {
    transform: translateY(-2px);
}

.avatar-lg {
    width: 4rem;
    height: 4rem;
}

.avatar-lg img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Vue liste */
.list-view .agent-card {
    margin-bottom: 1rem;
}

.list-view .agent-card .card {
    flex-direction: row;
    align-items: center;
}

.list-view .card-body {
    flex: 1;
}

@media (max-width: 768px) {
    .list-view .card {
        flex-direction: column;
    }
}
</style>

<script>
// Basculer entre les vues grille et liste
document.getElementById('gridView').addEventListener('change', function() {
    if (this.checked) {
        document.getElementById('agentsContainer').classList.remove('list-view');
    }
});

document.getElementById('listView').addEventListener('change', function() {
    if (this.checked) {
        document.getElementById('agentsContainer').classList.add('list-view');
    }
});

// Auto-submit des filtres
document.getElementById('filtersForm').addEventListener('change', function() {
    this.submit();
});
</script>
