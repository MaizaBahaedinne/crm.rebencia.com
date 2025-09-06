<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- Page Title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Agences HOUZEZ</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Dashboard</a></li>
                                <li class="breadcrumb-item active">Agences</li>
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
                            <form method="GET" action="<?php echo base_url('agencies'); ?>">
                                <div class="row g-3">
                                    <div class="col-lg-4">
                                        <label for="search" class="form-label">Recherche</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ri-search-line"></i></span>
                                            <input type="text" class="form-control" id="search" name="search" 
                                                   placeholder="Nom d'agence, email..." 
                                                   value="<?php echo $this->input->get('search'); ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="ville" class="form-label">Ville</label>
                                        <select class="form-select" id="ville" name="ville">
                                            <option value="">Toutes les villes</option>
                                            <option value="tunis" <?php echo ($this->input->get('ville') == 'tunis') ? 'selected' : ''; ?>>Tunis</option>
                                            <option value="sfax" <?php echo ($this->input->get('ville') == 'sfax') ? 'selected' : ''; ?>>Sfax</option>
                                            <option value="sousse" <?php echo ($this->input->get('ville') == 'sousse') ? 'selected' : ''; ?>>Sousse</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="status" class="form-label">Statut</label>
                                        <select class="form-select" id="status" name="status">
                                            <option value="">Tous les statuts</option>
                                            <option value="active" <?php echo ($this->input->get('status') == 'active') ? 'selected' : ''; ?>>Active</option>
                                            <option value="inactive" <?php echo ($this->input->get('status') == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
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
                                            <a href="<?php echo base_url('agencies'); ?>" class="btn btn-soft-secondary">
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
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Agences</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-success fs-14 mb-0">
                                        <i class="ri-building-line fs-13 align-middle"></i>
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-2">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                        <span class="counter-value" data-target="<?php echo isset($agencies) ? count($agencies) : 0; ?>">0</span>
                                    </h4>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-success-subtle rounded fs-3">
                                        <i class="bx bx-buildings text-success"></i>
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
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Agents Total</p>
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
                                        <i class="bx bx-user-circle text-info"></i>
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
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Propriétés</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-2">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                        <span class="counter-value" data-target="0">0</span>
                                    </h4>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-warning-subtle rounded fs-3">
                                        <i class="bx bx-home text-warning"></i>
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
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Ventes Mois</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-2">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                        <span class="counter-value" data-target="0">0</span>
                                    </h4>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-primary-subtle rounded fs-3">
                                        <i class="bx bx-dollar-circle text-primary"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste des agences en mode cards -->
            <div id="cardsView">
                <?php if (!empty($agencies)) : ?>
                    <div class="row">
                        <?php foreach ($agencies as $agency) : ?>
                            <div class="col-xl-4 col-lg-6">
                                <div class="card agency-card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <h5 class="text-truncate font-size-15">
                                                    <a href="<?php echo base_url('agency/view/' . $agency->ID); ?>" class="text-dark">
                                                        <?php echo htmlspecialchars($agency->display_name ?: $agency->agency_name ?: 'Agence #' . $agency->ID); ?>
                                                    </a>
                                                </h5>
                                                <p class="text-muted mb-2">
                                                    <i class="ri-mail-line me-1"></i> 
                                                    <?php echo htmlspecialchars($agency->agency_email ?: $agency->user_email ?: 'Email non fourni'); ?>
                                                </p>
                                                <p class="text-muted mb-2">
                                                    <i class="ri-phone-line me-1"></i> 
                                                    <?php echo htmlspecialchars($agency->agency_phone ?: 'Téléphone non fourni'); ?>
                                                </p>
                                                <p class="text-muted mb-3">
                                                    <i class="ri-map-pin-line me-1"></i> 
                                                    <?php echo htmlspecialchars(substr($agency->agency_address ?: 'Adresse non fournie', 0, 50)); ?>
                                                    <?php if (strlen($agency->agency_address ?: '') > 50) echo '...'; ?>
                                                </p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <div class="dropdown">
                                                    <a class="btn btn-sm btn-light" href="#" role="button" id="dropdownMenuLink<?php echo $agency->ID; ?>" data-bs-toggle="dropdown">
                                                        <i class="ri-more-2-fill"></i>
                                                    </a>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink<?php echo $agency->ID; ?>">
                                                        <li><a class="dropdown-item" href="<?php echo base_url('agency/view/' . $agency->ID); ?>">
                                                            <i class="ri-eye-line me-2"></i>Voir détails</a></li>
                                                        <li><a class="dropdown-item" href="<?php echo base_url('agency/agents/' . $agency->ID); ?>">
                                                            <i class="ri-user-3-line me-2"></i>Agents (<?php echo $agency->agents_count ?? 0; ?>)</a></li>
                                                        <li><a class="dropdown-item" href="<?php echo base_url('agency/properties/' . $agency->ID); ?>">
                                                            <i class="ri-home-line me-2"></i>Propriétés</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mt-3">
                                            <div class="col-4">
                                                <div class="text-center">
                                                    <h5 class="font-size-15 mb-1"><?php echo $agency->agents_count ?? 0; ?></h5>
                                                    <p class="text-muted mb-0">Agents</p>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="text-center">
                                                    <h5 class="font-size-15 mb-1"><?php echo $agency->properties_count ?? 0; ?></h5>
                                                    <p class="text-muted mb-0">Biens</p>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="text-center">
                                                    <h5 class="font-size-15 mb-1 text-success"><?php echo $agency->sales_count ?? 0; ?></h5>
                                                    <p class="text-muted mb-0">Ventes</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-3">
                                            <div class="d-flex gap-2">
                                                <a href="<?php echo base_url('agency/view/' . $agency->ID); ?>" class="btn btn-primary btn-sm flex-fill">
                                                    <i class="ri-eye-line me-1"></i>Détails
                                                </a>
                                                <a href="<?php echo base_url('agency/agents/' . $agency->ID); ?>" class="btn btn-soft-info btn-sm flex-fill">
                                                    <i class="ri-user-3-line me-1"></i>Agents
                                                </a>
                                            </div>
                                        </div>

                                        <?php if (!empty($agency->agency_website)) : ?>
                                        <div class="mt-2">
                                            <a href="<?php echo $agency->agency_website; ?>" target="_blank" class="btn btn-link btn-sm p-0">
                                                <i class="ri-external-link-line me-1"></i>Site web
                                            </a>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-footer bg-light border-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="ri-calendar-line me-1"></i>
                                                Membre depuis <?php echo date('M Y', strtotime($agency->user_registered ?? 'now')); ?>
                                            </small>
                                            <span class="badge bg-success-subtle text-success">Active</span>
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
                                            <i class="ri-building-line"></i>
                                        </div>
                                    </div>
                                    <h5>Aucune agence trouvée</h5>
                                    <p class="text-muted">Aucune agence ne correspond aux critères de filtrage actuels.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Liste des agences en mode tableau (masquée par défaut) -->
            <div id="listView" style="display: none;">
                <div class="card">
                    <div class="card-body">
                        <?php if (!empty($agencies)) : ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Agence</th>
                                            <th>Contact</th>
                                            <th>Adresse</th>
                                            <th class="text-center">Agents</th>
                                            <th class="text-center">Propriétés</th>
                                            <th class="text-center">Ventes</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($agencies as $agency) : ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm me-3">
                                                            <div class="avatar-title bg-primary-subtle text-primary rounded">
                                                                <?php echo strtoupper(substr($agency->display_name ?: 'A', 0, 2)); ?>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-1"><?php echo htmlspecialchars($agency->display_name ?: 'Agence #' . $agency->ID); ?></h6>
                                                            <p class="text-muted mb-0 small">ID: <?php echo $agency->ID; ?></p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="mb-1"><?php echo htmlspecialchars($agency->agency_email ?: $agency->user_email ?: '-'); ?></p>
                                                    <p class="text-muted mb-0 small"><?php echo htmlspecialchars($agency->agency_phone ?: '-'); ?></p>
                                                </td>
                                                <td>
                                                    <span class="text-muted">
                                                        <?php echo htmlspecialchars(substr($agency->agency_address ?: 'Non fournie', 0, 40)); ?>
                                                        <?php if (strlen($agency->agency_address ?: '') > 40) echo '...'; ?>
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-info-subtle text-info"><?php echo $agency->agents_count ?? 0; ?></span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-warning-subtle text-warning"><?php echo $agency->properties_count ?? 0; ?></span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-success-subtle text-success"><?php echo $agency->sales_count ?? 0; ?></span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <a href="<?php echo base_url('agency/view/' . $agency->ID); ?>" class="btn btn-outline-primary">
                                                            <i class="ri-eye-line"></i>
                                                        </a>
                                                        <a href="<?php echo base_url('agency/agents/' . $agency->ID); ?>" class="btn btn-outline-info">
                                                            <i class="ri-user-3-line"></i>
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
                                        <i class="ri-building-line"></i>
                                    </div>
                                </div>
                                <h5>Aucune agence trouvée</h5>
                                <p class="text-muted">Aucune agence ne correspond aux critères de filtrage actuels.</p>
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
        localStorage.setItem('agenciesView', 'cards');
    } else {
        cardsView.style.display = 'none';
        listView.style.display = 'block';
        btnList.classList.add('active');
        btnList.classList.remove('btn-outline-secondary');
        btnList.classList.add('btn-secondary');
        btnCards.classList.remove('active');
        btnCards.classList.add('btn-outline-secondary');
        btnCards.classList.remove('btn-secondary');
        localStorage.setItem('agenciesView', 'list');
    }
}

// Restaurer la vue préférée de l'utilisateur
document.addEventListener('DOMContentLoaded', function() {
    const savedView = localStorage.getItem('agenciesView') || 'cards';
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
.agency-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.agency-card:hover {
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
</style>
