<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- Page Title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Agents HOUZEZ</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Dashboard</a></li>
                                <li class="breadcrumb-item active">Agents</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtres -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ri-filter-line"></i> Filtres & Recherche
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="<?php echo base_url('agents'); ?>">
                                <div class="row g-3">
                                    <div class="col-lg-3">
                                        <label for="search" class="form-label">Recherche</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ri-search-line"></i></span>
                                            <input type="text" class="form-control" id="search" name="search" 
                                                   placeholder="Nom d'agent, email..." 
                                                   value="<?php echo $this->input->get('search'); ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="agency" class="form-label">Agence</label>
                                        <select class="form-select" id="agency" name="agency">
                                            <option value="">Toutes les agences</option>
                                            <?php if (!empty($agencies)) : ?>
                                                <?php foreach ($agencies as $agency) : ?>
                                                    <option value="<?php echo $agency->ID; ?>" 
                                                            <?php echo ($this->input->get('agency') == $agency->ID) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($agency->display_name); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="specialite" class="form-label">Spécialité</label>
                                        <select class="form-select" id="specialite" name="specialite">
                                            <option value="">Toutes</option>
                                            <option value="vente" <?php echo ($this->input->get('specialite') == 'vente') ? 'selected' : ''; ?>>Vente</option>
                                            <option value="location" <?php echo ($this->input->get('specialite') == 'location') ? 'selected' : ''; ?>>Location</option>
                                            <option value="commercial" <?php echo ($this->input->get('specialite') == 'commercial') ? 'selected' : ''; ?>>Commercial</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="performance" class="form-label">Performance</label>
                                        <select class="form-select" id="performance" name="performance">
                                            <option value="">Toutes</option>
                                            <option value="top" <?php echo ($this->input->get('performance') == 'top') ? 'selected' : ''; ?>>Top performers</option>
                                            <option value="active" <?php echo ($this->input->get('performance') == 'active') ? 'selected' : ''; ?>>Actifs</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-2">
                                        <label class="form-label">&nbsp;</label>
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="ri-search-line"></i> Filtrer
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-lg-12">
                                        <div class="d-flex gap-2">
                                            <a href="<?php echo base_url('agents'); ?>" class="btn btn-soft-secondary">
                                                <i class="ri-refresh-line"></i> Réinitialiser
                                            </a>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-outline-secondary" id="viewCards" onclick="toggleView('cards')">
                                                    <i class="ri-layout-grid-line"></i> Cards
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary" id="viewList" onclick="toggleView('list')">
                                                    <i class="ri-list-check"></i> Liste
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques rapides -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Agents</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-2">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                        <span class="counter-value" data-target="<?php echo isset($agents) ? count($agents) : 0; ?>">0</span>
                                    </h4>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-primary-subtle rounded fs-3">
                                        <i class="bx bx-user-circle text-primary"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Propriétés Gérées</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-2">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                        <span class="counter-value" data-target="0">0</span>
                                    </h4>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-info-subtle rounded fs-3">
                                        <i class="bx bx-home text-info"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Ventes ce mois</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-2">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                        <span class="counter-value" data-target="0">0</span>
                                    </h4>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-success-subtle rounded fs-3">
                                        <i class="bx bx-dollar-circle text-success"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Commissions</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-2">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                        <span class="counter-value" data-target="0">0</span>K€
                                    </h4>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-warning-subtle rounded fs-3">
                                        <i class="bx bx-trending-up text-warning"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste des agents en mode cards -->
            <div id="cardsView">
                <?php if (!empty($agents)) : ?>
                    <div class="row">
                        <?php foreach ($agents as $agent) : ?>
                            <div class="col-xl-4 col-lg-6">
                                <div class="card agent-card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start">
                                            <div class="avatar-md me-3">
                                                <?php if (!empty($agent->avatar_url)) : ?>
                                                    <img src="<?php echo $agent->avatar_url; ?>" alt="" class="avatar-md rounded-circle">
                                                <?php else : ?>
                                                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-4">
                                                        <?php echo strtoupper(substr($agent->agent_name ?: $agent->display_name ?: 'A', 0, 2)); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <h5 class="text-truncate font-size-15 mb-1">
                                                    <a href="<?php echo base_url('agents/' . $agent->user_id); ?>" class="text-dark">
                                                        <?php echo htmlspecialchars($agent->agent_name ?: $agent->display_name ?: 'Agent #' . $agent->user_id); ?>
                                                    </a>
                                                </h5>
                                                <p class="text-muted mb-2">
                                                    <i class="ri-building-line me-1"></i> 
                                                    <?php echo htmlspecialchars($agent->agency_name ?: 'Indépendant'); ?>
                                                </p>
                                                <p class="text-muted mb-0">
                                                    <i class="ri-mail-line me-1"></i> 
                                                    <?php echo htmlspecialchars($agent->agent_email ?: $agent->user_email ?: 'Email non fourni'); ?>
                                                </p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <div class="dropdown">
                                                    <a class="btn btn-sm btn-light" href="#" role="button" id="dropdownMenuLink<?php echo $agent->user_id; ?>" data-bs-toggle="dropdown">
                                                        <i class="ri-more-2-fill"></i>
                                                    </a>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink<?php echo $agent->user_id; ?>">
                                                        <li><a class="dropdown-item" href="<?php echo base_url('agents/' . $agent->user_id); ?>">
                                                            <i class="ri-eye-line me-2"></i>Voir profil</a></li>
                                                        <li><a class="dropdown-item" href="<?php echo base_url('agents/' . $agent->user_id . '/properties'); ?>">
                                                            <i class="ri-home-line me-2"></i>Propriétés (<?php echo $agent->properties_count ?? 0; ?>)</a></li>
                                                        <li><a class="dropdown-item" href="<?php echo base_url('agents/' . $agent->user_id . '/stats'); ?>">
                                                            <i class="ri-bar-chart-line me-2"></i>Statistiques</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-3">
                                            <div class="row text-center">
                                                <div class="col-4">
                                                    <h5 class="font-size-15 mb-1"><?php echo $agent->properties_count ?? 0; ?></h5>
                                                    <p class="text-muted mb-0 small">Biens</p>
                                                </div>
                                                <div class="col-4">
                                                    <h5 class="font-size-15 mb-1 text-success"><?php echo $agent->sales_count ?? 0; ?></h5>
                                                    <p class="text-muted mb-0 small">Ventes</p>
                                                </div>
                                                <div class="col-4">
                                                    <h5 class="font-size-15 mb-1 text-info"><?php echo $agent->clients_count ?? 0; ?></h5>
                                                    <p class="text-muted mb-0 small">Clients</p>
                                                </div>
                                            </div>
                                        </div>

                                        <?php if (!empty($agent->position) || !empty($agent->phone) || !empty($agent->mobile)) : ?>
                                        <div class="mt-3 pt-3 border-top">
                                            <?php if (!empty($agent->position)) : ?>
                                                <span class="badge bg-primary-subtle text-primary mb-2"><?php echo htmlspecialchars($agent->position); ?></span>
                                            <?php endif; ?>
                                            <div class="row">
                                                <?php if (!empty($agent->phone)) : ?>
                                                    <div class="col-6">
                                                        <p class="text-muted mb-1 small">
                                                            <i class="ri-phone-line me-1"></i> 
                                                            <?php echo htmlspecialchars($agent->phone); ?>
                                                        </p>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if (!empty($agent->mobile)) : ?>
                                                    <div class="col-6">
                                                        <p class="text-muted mb-1 small">
                                                            <i class="ri-smartphone-line me-1"></i> 
                                                            <?php echo htmlspecialchars($agent->mobile); ?>
                                                        </p>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <?php endif; ?>

                                        <div class="mt-3">
                                            <div class="d-flex gap-2">
                                                <a href="<?php echo base_url('agents/' . $agent->user_id); ?>" class="btn btn-primary btn-sm flex-fill">
                                                    <i class="ri-user-line me-1"></i>Profil
                                                </a>
                                                <a href="<?php echo base_url('agents/' . $agent->user_id . '/properties'); ?>" class="btn btn-soft-info btn-sm flex-fill">
                                                    <i class="ri-home-line me-1"></i>Biens
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-light border-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="ri-calendar-line me-1"></i>
                                                Inscrit <?php echo date('M Y', strtotime($agent->registration_date ?? 'now')); ?>
                                            </small>
                                            <div class="d-flex gap-1">
                                                <?php if (($agent->sales_count ?? 0) > 5) : ?>
                                                    <span class="badge bg-success-subtle text-success">Top vendeur</span>
                                                <?php endif; ?>
                                                <span class="badge bg-info-subtle text-info">Actif</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body text-center py-5">
                                    <div class="avatar-lg mx-auto mb-4">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-4">
                                            <i class="ri-user-3-line"></i>
                                        </div>
                                    </div>
                                    <h5>Aucun agent trouvé</h5>
                                    <p class="text-muted">Aucun agent ne correspond aux critères de filtrage actuels.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Liste des agents en mode tableau (masquée par défaut) -->
            <div id="listView" style="display: none;">
                <div class="card">
                    <div class="card-body">
                        <?php if (!empty($agents)) : ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Agent</th>
                                            <th>Agence</th>
                                            <th>Contact</th>
                                            <th class="text-center">Biens</th>
                                            <th class="text-center">Ventes</th>
                                            <th class="text-center">Clients</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($agents as $agent) : ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm me-3">
                                                            <?php if (!empty($agent->avatar_url)) : ?>
                                                                <img src="<?php echo $agent->avatar_url; ?>" alt="" class="avatar-sm rounded-circle">
                                                            <?php else : ?>
                                                                <div class="avatar-title bg-primary-subtle text-primary rounded">
                                                                    <?php echo strtoupper(substr($agent->agent_name ?: 'A', 0, 2)); ?>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-1"><?php echo htmlspecialchars($agent->agent_name ?: 'Agent #' . $agent->user_id); ?></h6>
                                                            <p class="text-muted mb-0 small"><?php echo htmlspecialchars($agent->position ?? 'Agent'); ?></p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="text-muted"><?php echo htmlspecialchars($agent->agency_name ?: 'Indépendant'); ?></span>
                                                </td>
                                                <td>
                                                    <p class="mb-1"><?php echo htmlspecialchars($agent->agent_email ?: '-'); ?></p>
                                                    <p class="text-muted mb-0 small"><?php echo htmlspecialchars($agent->phone ?: $agent->mobile ?: '-'); ?></p>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-info-subtle text-info"><?php echo $agent->properties_count ?? 0; ?></span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-success-subtle text-success"><?php echo $agent->sales_count ?? 0; ?></span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-warning-subtle text-warning"><?php echo $agent->clients_count ?? 0; ?></span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <a href="<?php echo base_url('agents/' . $agent->user_id); ?>" class="btn btn-outline-primary">
                                                            <i class="ri-eye-line"></i>
                                                        </a>
                                                        <a href="<?php echo base_url('agents/' . $agent->user_id . '/properties'); ?>" class="btn btn-outline-info">
                                                            <i class="ri-home-line"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else : ?>
                            <div class="text-center py-5">
                                <div class="avatar-lg mx-auto mb-4">
                                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-4">
                                        <i class="ri-user-3-line"></i>
                                    </div>
                                </div>
                                <h5>Aucun agent trouvé</h5>
                                <p class="text-muted">Aucun agent ne correspond aux critères de filtrage actuels.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
// Toggle entre vue cards et liste
function toggleView(viewType) {
    const cardsView = document.getElementById('cardsView');
    const listView = document.getElementById('listView');
    const btnCards = document.getElementById('viewCards');
    const btnList = document.getElementById('viewList');
    
    if (viewType === 'cards') {
        cardsView.style.display = 'block';
        listView.style.display = 'none';
        btnCards.classList.add('active');
        btnCards.classList.remove('btn-outline-secondary');
        btnCards.classList.add('btn-secondary');
        btnList.classList.remove('active');
        btnList.classList.add('btn-outline-secondary');
        btnList.classList.remove('btn-secondary');
        localStorage.setItem('agentsView', 'cards');
    } else {
        cardsView.style.display = 'none';
        listView.style.display = 'block';
        btnList.classList.add('active');
        btnList.classList.remove('btn-outline-secondary');
        btnList.classList.add('btn-secondary');
        btnCards.classList.remove('active');
        btnCards.classList.add('btn-outline-secondary');
        btnCards.classList.remove('btn-secondary');
        localStorage.setItem('agentsView', 'list');
    }
}

// Restaurer la vue préférée de l'utilisateur
document.addEventListener('DOMContentLoaded', function() {
    const savedView = localStorage.getItem('agentsView') || 'cards';
    toggleView(savedView);
    
    // Animation des compteurs
    const counters = document.querySelectorAll('.counter-value');
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-target'));
        let current = 0;
        const increment = target / 20;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            counter.textContent = Math.floor(current);
        }, 100);
    });
});
</script>

<style>
.agent-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.agent-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.counter-value {
    display: inline-block;
}

.card-animate {
    transition: all 0.3s ease;
}

.card-animate:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.btn-group .btn.active {
    z-index: 2;
}

.avatar-md {
    width: 3rem;
    height: 3rem;
}

.avatar-sm {
    width: 2rem;
    height: 2rem;
}
</style>
