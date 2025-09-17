<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- Header Premium avec Statistiques -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h4 class="mb-2 text-dark fw-bold">
                                <i class="ri-file-list-3-line me-2 text-primary"></i>
                                Portefeuille d'Estimations
                            </h4>
                            <nav>
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard'); ?>">Accueil</a></li>
                                    <li class="breadcrumb-item active">Estimations</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="page-title-right">
                            <a href="<?= base_url('estimation'); ?>" class="btn btn-primary btn-lg shadow-sm">
                                <i class="ri-add-circle-line me-2"></i>
                                Nouvelle Estimation
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cartes de Statistiques -->
            <div class="row mb-4">
                <?php 
                $total_estimations = count($estimations);
                $valeur_totale = 0;
                $rentabilite_moyenne = 0;
                $estimations_recentes = 0;
                
                foreach($estimations as $e) {
                    $valeur_totale += $e['valeur_estimee'];
                    $rentabilite_moyenne += $e['rentabilite'];
                    if(strtotime($e['created_at']) > strtotime('-30 days')) {
                        $estimations_recentes++;
                    }
                }
                $rentabilite_moyenne = $total_estimations > 0 ? $rentabilite_moyenne / $total_estimations : 0;
                ?>
                
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0 me-3">
                                    <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-18">
                                        <i class="ri-file-list-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-muted text-truncate fs-13 mb-1">Total Estimations</p>
                                    <div class="d-flex align-items-center">
                                        <h4 class="fs-22 fw-semibold ff-secondary mb-0 text-primary"><?= $total_estimations; ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0 me-3">
                                    <span class="avatar-title bg-success-subtle text-success rounded-circle fs-18">
                                        <i class="ri-money-dollar-circle-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-muted text-truncate fs-13 mb-1">Valeur Totale</p>
                                    <div class="d-flex align-items-center">
                                        <h4 class="fs-22 fw-semibold ff-secondary mb-0 text-success"><?= number_format($valeur_totale, 0, '', ' '); ?> TND</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0 me-3">
                                    <span class="avatar-title bg-warning-subtle text-warning rounded-circle fs-18">
                                        <i class="ri-percent-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-muted text-truncate fs-13 mb-1">Rentabilité Moyenne</p>
                                    <div class="d-flex align-items-center">
                                        <h4 class="fs-22 fw-semibold ff-secondary mb-0 text-warning"><?= number_format($rentabilite_moyenne, 1); ?>%</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0 me-3">
                                    <span class="avatar-title bg-info-subtle text-info rounded-circle fs-18">
                                        <i class="ri-calendar-event-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-medium text-muted text-truncate fs-13 mb-1">Ce Mois</p>
                                    <div class="d-flex align-items-center">
                                        <h4 class="fs-22 fw-semibold ff-secondary mb-0 text-info"><?= $estimations_recentes; ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtres Premium -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <form class="row g-3" method="get">
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Statut</label>
                                    <select name="statut" class="form-select">
                                        <option value="">Tous les statuts</option>
                                        <option value="en_cours" <?= ($this->input->get('statut')==='en_cours')?'selected':''; ?>>
                                            <i class="ri-time-line"></i> En cours
                                        </option>
                                        <option value="valide" <?= ($this->input->get('statut')==='valide')?'selected':''; ?>>
                                            <i class="ri-check-line"></i> Validé
                                        </option>
                                        <option value="rejete" <?= ($this->input->get('statut')==='rejete')?'selected':''; ?>>
                                            <i class="ri-close-line"></i> Rejeté
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Zone</label>
                                    <select name="zone_id" class="form-select">
                                        <option value="">Toutes les zones</option>
                                        <?php foreach($zones as $z): ?>
                                            <option value="<?= $z->id; ?>" <?= ($this->input->get('zone_id')==$z->id)?'selected':''; ?>>
                                                <?= $z->nom; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Période</label>
                                    <select name="periode" class="form-select">
                                        <option value="">Toute période</option>
                                        <option value="7j" <?= ($this->input->get('periode')==='7j')?'selected':''; ?>>7 derniers jours</option>
                                        <option value="30j" <?= ($this->input->get('periode')==='30j')?'selected':''; ?>>30 derniers jours</option>
                                        <option value="90j" <?= ($this->input->get('periode')==='90j')?'selected':''; ?>>90 derniers jours</option>
                                    </select>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="ri-search-line me-2"></i>Filtrer
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste des Estimations en Cartes -->
            <div class="row">
                <?php if(empty($estimations)): ?>
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center py-5">
                                <div class="avatar-lg mx-auto mb-4">
                                    <div class="avatar-title bg-light text-muted rounded-circle fs-24">
                                        <i class="ri-file-list-line"></i>
                                    </div>
                                </div>
                                <h5 class="mb-3">Aucune estimation trouvée</h5>
                                <p class="text-muted mb-4">Commencez par créer votre première estimation immobilière</p>
                                <a href="<?= base_url('estimation'); ?>" class="btn btn-primary">
                                    <i class="ri-add-line me-2"></i>Créer une estimation
                                </a>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach($estimations as $e): ?>
                        <div class="col-lg-6 col-xl-4 mb-4">
                            <div class="card border-0 shadow-sm h-100 estimation-card" data-id="<?= $e['id']; ?>">
                                <div class="card-header border-0 pb-0">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3">
                                                <span class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                                    <i class="ri-home-4-line"></i>
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="mb-1 text-dark fw-semibold">
                                                    Estimation #<?= $e['id']; ?>
                                                </h6>
                                                <small class="text-muted">
                                                    <i class="ri-calendar-line me-1"></i>
                                                    <?= date('d M Y', strtotime($e['created_at'])); ?>
                                                </small>
                                            </div>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-ghost-primary btn-icon btn-sm" data-bs-toggle="dropdown">
                                                <i class="ri-more-2-fill"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" href="<?= base_url('estimation/resultat/'.$e['id']); ?>">
                                                        <i class="ri-eye-line me-2"></i>Voir les détails
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item text-success" href="<?= base_url('estimation/statut/'.$e['id'].'/valide'); ?>">
                                                        <i class="ri-check-line me-2"></i>Valider
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item text-danger" href="<?= base_url('estimation/statut/'.$e['id'].'/rejete'); ?>">
                                                        <i class="ri-close-line me-2"></i>Rejeter
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card-body pt-2">
                                    <!-- Zone et Surface -->
                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <div class="p-2 bg-light rounded">
                                                <small class="text-muted d-block">Zone</small>
                                                <span class="fw-medium"><?= $e['zone_nom'] ?? 'Non définie'; ?></span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-2 bg-light rounded">
                                                <small class="text-muted d-block">Surface</small>
                                                <span class="fw-medium"><?= $e['surface_habitable']; ?> m²</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Valeurs d'Estimation -->
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <small class="text-muted">Estimation</small>
                                            <span class="badge bg-success-subtle text-success fs-12">
                                                <?= number_format($e['valeur_estimee'], 0, '', ' '); ?> TND
                                            </span>
                                        </div>
                                        
                                        <!-- Barre de progression pour la fourchette -->
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-gradient" role="progressbar" style="width: 100%"></div>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between mt-1">
                                            <small class="text-muted">
                                                Min: <?= number_format($e['valeur_min_estimee'] ?? 0, 0, '', ' '); ?> TND
                                            </small>
                                            <small class="text-muted">
                                                Max: <?= number_format($e['valeur_max_estimee'] ?? 0, 0, '', ' '); ?> TND
                                            </small>
                                        </div>
                                    </div>

                                    <!-- Loyer et Rentabilité -->
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div class="text-center">
                                                <h6 class="text-primary mb-0">
                                                    <?= number_format($e['loyer_potentiel'], 0, '', ' '); ?> TND
                                                </h6>
                                                <small class="text-muted">Loyer/mois</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-center">
                                                <h6 class="text-warning mb-0">
                                                    <?= number_format($e['rentabilite'], 1); ?>%
                                                </h6>
                                                <small class="text-muted">Rentabilité</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer border-0 pt-0">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs me-2">
                                                <span class="avatar-title bg-light text-dark rounded-circle fs-11">
                                                    <i class="ri-user-line"></i>
                                                </span>
                                            </div>
                                            <small class="text-muted">
                                                <?php if(!empty($e['agent_id'])): ?>
                                                    <?php $agent = isset($agents[$e['agent_id']]) ? $agents[$e['agent_id']] : null; ?>
                                                    <?= $agent ? htmlspecialchars($agent['display_name'] ?? $agent['user_login'] ?? 'Agent') : 'Agent #'.$e['agent_id']; ?>
                                                <?php else: ?>
                                                    Non assigné
                                                <?php endif; ?>
                                            </small>
                                        </div>
                                        
                                        <div>
                                            <?php 
                                            $statut = $e['statut_dossier'] ?? 'en_cours';
                                            $badgeClass = '';
                                            $icon = '';
                                            switch($statut) {
                                                case 'valide':
                                                    $badgeClass = 'bg-success-subtle text-success';
                                                    $icon = 'ri-check-line';
                                                    break;
                                                case 'rejete':
                                                    $badgeClass = 'bg-danger-subtle text-danger';
                                                    $icon = 'ri-close-line';
                                                    break;
                                                default:
                                                    $badgeClass = 'bg-warning-subtle text-warning';
                                                    $icon = 'ri-time-line';
                                                    $statut = 'en_cours';
                                            }
                                            ?>
                                            <span class="badge <?= $badgeClass; ?>">
                                                <i class="<?= $icon; ?> me-1"></i>
                                                <?= ucfirst(str_replace('_', ' ', $statut)); ?>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <a href="<?= base_url('estimation/resultat/'.$e['id']); ?>" class="btn btn-primary btn-sm w-100">
                                            <i class="ri-eye-line me-2"></i>Voir l'estimation
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <?php if(!empty($estimations) && count($estimations) >= 12): ?>
                <div class="row">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <nav aria-label="Pagination des estimations">
                                    <ul class="pagination justify-content-center mb-0">
                                        <li class="page-item disabled">
                                            <a class="page-link" href="#" tabindex="-1">Précédent</a>
                                        </li>
                                        <li class="page-item active">
                                            <a class="page-link" href="#">1</a>
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link" href="#">2</a>
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link" href="#">3</a>
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link" href="#">Suivant</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<style>
/* Styles Premium pour les Estimations */
.estimation-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.estimation-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.75rem 1.5rem rgba(18, 38, 63, 0.15) !important;
}

.progress-bar.bg-gradient {
    background: linear-gradient(90deg, #198754 0%, #ffc107 50%, #0d6efd 100%);
}

.avatar-title {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}

.avatar-sm {
    width: 32px;
    height: 32px;
}

.avatar-xs {
    width: 24px;
    height: 24px;
}

.avatar-lg {
    width: 64px;
    height: 64px;
}

/* Animation pour les compteurs */
@keyframes countUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.ff-secondary {
    animation: countUp 0.6s ease;
}

/* Responsive */
@media (max-width: 768px) {
    .estimation-card {
        margin-bottom: 1rem;
    }
    
    .page-title-box {
        text-align: center;
    }
    
    .page-title-box .d-sm-flex {
        flex-direction: column;
        gap: 1rem;
    }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .bg-light {
        background-color: #2d3436 !important;
        color: #ffffff;
    }
}
</style>

<script>
// Animations et interactions
document.addEventListener('DOMContentLoaded', function() {
    // Animation des cartes au scroll
    const cards = document.querySelectorAll('.estimation-card');
    
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
    
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = `all 0.6s ease ${index * 0.1}s`;
        observer.observe(card);
    });
    
    // Clic sur les cartes pour redirection
    cards.forEach(card => {
        card.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown') && !e.target.closest('.btn')) {
                const id = this.dataset.id;
                window.location.href = `<?= base_url('estimation/resultat/'); ?>${id}`;
            }
        });
    });
    
    // Animation des statistiques
    animateCounters();
});

function animateCounters() {
    const counters = document.querySelectorAll('.ff-secondary');
    
    counters.forEach(counter => {
        const target = parseInt(counter.textContent.replace(/[^\d]/g, ''));
        let count = 0;
        const increment = target / 30; // 30 frames d'animation
        
        const timer = setInterval(() => {
            count += increment;
            if (count >= target) {
                count = target;
                clearInterval(timer);
            }
            
            // Formatage selon le type de données
            if (counter.textContent.includes('TND')) {
                counter.textContent = Math.floor(count).toLocaleString('fr-FR') + ' TND';
            } else if (counter.textContent.includes('%')) {
                counter.textContent = count.toFixed(1) + '%';
            } else {
                counter.textContent = Math.floor(count);
            }
        }, 50);
    });
}
</script>
