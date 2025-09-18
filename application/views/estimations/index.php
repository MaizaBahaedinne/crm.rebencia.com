<div class="content-wrapper">
    <!-- En-tête de page -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <i class="fas fa-calculator"></i>
                        Estimations Immobilières
                        <?php if ($user_role === 'admin'): ?>
                            <span class="badge badge-danger ml-2">Admin - Toutes</span>
                        <?php elseif ($user_role === 'manager'): ?>
                            <span class="badge badge-warning ml-2">Manager - Agence</span>
                        <?php else: ?>
                            <span class="badge badge-info ml-2">Agent - Mes estimations</span>
                        <?php endif; ?>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Estimations</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Cartes de statistiques -->
    <div class="content">
        <div class="container-fluid">
            <div class="row mb-4">
                <!-- Total des estimations -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?= number_format($stats['total']) ?></h3>
                            <p>Total Estimations</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calculator"></i>
                        </div>
                    </div>
                </div>

                <!-- En cours -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?= number_format($stats['en_cours']) ?></h3>
                            <p>En Cours</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>

                <!-- Validées -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3><?= number_format($stats['validees']) ?></h3>
                            <p>Validées</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>

                <!-- Montant total -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3><?= number_format($stats['montant_total'], 0, ',', ' ') ?> TND</h3>
                            <p>Valeur Totale</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-euro-sign"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtres et contrôles -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-filter"></i>
                        Filtres et Actions
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-success btn-sm" onclick="exportEstimations('excel')">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </button>
                        <button type="button" class="btn btn-danger btn-sm ml-2" onclick="exportEstimations('pdf')">
                            <i class="fas fa-file-pdf"></i> Export PDF
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form id="filterForm" class="row">
                        <!-- Filtre par statut -->
                        <div class="col-md-3">
                            <label for="filter_statut">Statut</label>
                            <select class="form-control" id="filter_statut" name="statut">
                                <option value="">Tous les statuts</option>
                                <?php foreach ($filters['statuts'] as $key => $label): ?>
                                    <option value="<?= $key ?>"><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Filtre par période -->
                        <div class="col-md-3">
                            <label for="filter_periode">Période</label>
                            <select class="form-control" id="filter_periode" name="periode">
                                <option value="">Toutes les périodes</option>
                                <?php foreach ($filters['periodes'] as $key => $label): ?>
                                    <option value="<?= $key ?>"><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Filtre par agent (admin/manager seulement) -->
                        <?php if (isset($filters['agents']) && !empty($filters['agents'])): ?>
                        <div class="col-md-3">
                            <label for="filter_agent">Agent</label>
                            <select class="form-control" id="filter_agent" name="agent_id">
                                <option value="">Tous les agents</option>
                                <?php foreach ($filters['agents'] as $agent): ?>
                                    <option value="<?= $agent['ID'] ?>"><?= $agent['display_name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php endif; ?>

                        <!-- Montant minimum -->
                        <div class="col-md-2">
                            <label for="filter_montant_min">Montant min.</label>
                            <input type="number" class="form-control" id="filter_montant_min" name="montant_min" placeholder="0">
                        </div>

                        <!-- Bouton appliquer -->
                        <div class="col-md-1">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tableau des estimations -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list"></i>
                        Liste des Estimations
                        <span class="badge badge-secondary ml-2" id="estimations-count"><?= count($estimations) ?></span>
                    </h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap" id="estimationsTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Adresse</th>
                                <th>Client</th>
                                <?php if ($user_role !== 'agent'): ?>
                                    <th>Agent</th>
                                <?php endif; ?>
                                <?php if ($user_role === 'admin'): ?>
                                    <th>Agence</th>
                                <?php endif; ?>
                                <th>Type</th>
                                <th>Prix Estimation</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="estimationsBody">
                            <?php foreach ($estimations as $estimation): ?>
                            <tr>
                                <td>
                                    <span class="badge badge-outline-primary">#<?= $estimation['id'] ?></span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?= date('d/m/Y', strtotime($estimation['created_at'])) ?>
                                    </small>
                                </td>
                                <td>
                                    <strong><?= $estimation['adresse'] ?? 'N/A' ?></strong><br>
                                    <small class="text-muted"><?= $estimation['ville'] ?? '' ?></small>
                                </td>
                                <td>
                                    <?php if (!empty($estimation['client_nom'])): ?>
                                        <strong><?= $estimation['client_nom'] ?> <?= $estimation['client_prenom'] ?? '' ?></strong><br>
                                        <small class="text-muted"><?= $estimation['client_email'] ?? '' ?></small>
                                    <?php else: ?>
                                        <span class="text-muted">Client non défini</span>
                                    <?php endif; ?>
                                </td>
                                
                                <?php if ($user_role !== 'agent'): ?>
                                <td>
                                    <?= $estimation['agent_name'] ?? 'N/A' ?>
                                </td>
                                <?php endif; ?>
                                
                                <?php if ($user_role === 'admin'): ?>
                                <td>
                                    <?= $estimation['agency_name'] ?? 'N/A' ?>
                                </td>
                                <?php endif; ?>
                                
                                <td>
                                    <span class="badge badge-info">
                                        <?= ucfirst($estimation['type_bien'] ?? 'N/A') ?>
                                    </span>
                                </td>
                                <td>
                                    <strong class="text-success">
                                        <?= number_format($estimation['prix_estimation'] ?? 0, 0, ',', ' ') ?> TND
                                    </strong>
                                </td>
                                <td>
                                    <?php
                                    $statut_class = '';
                                    $statut_text = '';
                                    switch ($estimation['statut_dossier']) {
                                        case 'en_cours':
                                            $statut_class = 'warning';
                                            $statut_text = 'En cours';
                                            break;
                                        case 'valide':
                                            $statut_class = 'success';
                                            $statut_text = 'Validée';
                                            break;
                                        case 'rejete':
                                            $statut_class = 'danger';
                                            $statut_text = 'Rejetée';
                                            break;
                                        default:
                                            $statut_class = 'secondary';
                                            $statut_text = 'Inconnu';
                                    }
                                    ?>
                                    <span class="badge badge-<?= $statut_class ?>"><?= $statut_text ?></span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?= base_url('estimations/view/' . $estimation['id']) ?>" 
                                           class="btn btn-sm btn-info" title="Voir les détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if ($user_role === 'admin' || $estimation['agent_id'] == $user_info['id']): ?>
                                        <a href="<?= base_url('estimations/edit/' . $estimation['id']) ?>" 
                                           class="btn btn-sm btn-warning" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    
                    <?php if (empty($estimations)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-calculator fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Aucune estimation trouvée</h5>
                        <p class="text-muted">
                            <?php if ($user_role === 'agent'): ?>
                                Vous n'avez pas encore créé d'estimation.
                            <?php else: ?>
                                Aucune estimation ne correspond à vos critères.
                            <?php endif; ?>
                        </p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestionnaire de filtres
    document.getElementById('filterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        applyFilters();
    });

    // Application des filtres en temps réel
    document.querySelectorAll('#filterForm select, #filterForm input').forEach(function(element) {
        element.addEventListener('change', applyFilters);
    });
});

/**
 * Appliquer les filtres via AJAX
 */
function applyFilters() {
    const form = document.getElementById('filterForm');
    const formData = new FormData(form);
    
    // Afficher un loader
    document.getElementById('estimationsBody').innerHTML = '<tr><td colspan="10" class="text-center"><i class="fas fa-spinner fa-spin"></i> Chargement...</td></tr>';
    
    fetch('<?= base_url("estimations/filter") ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateEstimationsTable(data.data);
            document.getElementById('estimations-count').textContent = data.count;
        } else {
            console.error('Erreur lors du filtrage:', data.message);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        document.getElementById('estimationsBody').innerHTML = '<tr><td colspan="10" class="text-center text-danger"><i class="fas fa-exclamation-triangle"></i> Erreur lors du chargement</td></tr>';
    });
}

/**
 * Mettre à jour le tableau des estimations
 */
function updateEstimationsTable(estimations) {
    const tbody = document.getElementById('estimationsBody');
    
    if (estimations.length === 0) {
        tbody.innerHTML = '<tr><td colspan="10" class="text-center text-muted"><i class="fas fa-calculator fa-2x mb-2"></i><br>Aucune estimation trouvée</td></tr>';
        return;
    }
    
    tbody.innerHTML = estimations.map(estimation => {
        const statut_classes = {
            'en_cours': 'warning',
            'valide': 'success', 
            'rejete': 'danger'
        };
        const statut_texts = {
            'en_cours': 'En cours',
            'valide': 'Validée',
            'rejete': 'Rejetée'
        };
        
        return `
            <tr>
                <td><span class="badge badge-outline-primary">#${estimation.id}</span></td>
                <td><small class="text-muted">${new Date(estimation.created_at).toLocaleDateString('fr-FR')}</small></td>
                <td>
                    <strong>${estimation.adresse || 'N/A'}</strong><br>
                    <small class="text-muted">${estimation.ville || ''}</small>
                </td>
                <td>
                    ${estimation.client_nom ? 
                        `<strong>${estimation.client_nom} ${estimation.client_prenom || ''}</strong>` : 
                        '<span class="text-muted">Client non défini</span>'
                    }
                </td>
                <?php if ($user_role !== 'agent'): ?>
                <td>${estimation.agent_name || 'N/A'}</td>
                <?php endif; ?>
                <?php if ($user_role === 'admin'): ?>
                <td>${estimation.agency_name || 'N/A'}</td>
                <?php endif; ?>
                <td><span class="badge badge-info">${(estimation.type_bien || 'N/A').charAt(0).toUpperCase() + (estimation.type_bien || 'N/A').slice(1)}</span></td>
                <td><strong class="text-success">${Number(estimation.prix_estimation || 0).toLocaleString('fr-FR')} TND</strong></td>
                <td><span class="badge badge-${statut_classes[estimation.statut_dossier] || 'secondary'}">${statut_texts[estimation.statut_dossier] || 'Inconnu'}</span></td>
                <td>
                    <div class="btn-group">
                        <a href="<?= base_url('estimations/view/') ?>${estimation.id}" class="btn btn-sm btn-info" title="Voir les détails">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="<?= base_url('estimations/edit/') ?>${estimation.id}" class="btn btn-sm btn-warning" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </a>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

/**
 * Exporter les estimations
 */
function exportEstimations(format) {
    window.open('<?= base_url("estimations/export/") ?>' + format, '_blank');
}
</script>

<style>
.small-box {
    border-radius: 10px;
    position: relative;
    overflow: hidden;
}

.small-box .icon {
    transition: all 0.3s ease-in-out;
}

.small-box:hover .icon {
    transform: scale(1.1);
}

.badge-outline-primary {
    color: #007bff;
    border: 1px solid #007bff;
    background: transparent;
}

.table th {
    border-top: none;
    font-weight: 600;
    background-color: #f8f9fa;
}

.btn-group .btn {
    margin-right: 2px;
}

.card {
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
    border: none;
}

.content-header h1 {
    font-size: 1.8rem;
    font-weight: 300;
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .btn-group .btn {
        padding: 0.25rem 0.4rem;
    }
}
</style>
