<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- Titre de la page -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Définir les Objectifs Mensuels</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="<?php echo base_url('objectives'); ?>">Objectifs</a></li>
                                <li class="breadcrumb-item active">Définir</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Messages Flash -->
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ri-check-line me-2"></i><?php echo $this->session->flashdata('success'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="ri-error-warning-line me-2"></i><?php echo $this->session->flashdata('error'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <?php if (isset($is_manager_view) && $is_manager_view): ?>
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="ri-information-line me-2"></i>
                            <strong>Vue Manager :</strong> Vous ne voyez que les agents de votre agence.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <div class="card border-0 shadow-sm">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ri-target-line me-2"></i>Définir les Objectifs
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="<?php echo base_url('objectives/set_monthly'); ?>" method="post" id="objectivesForm">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="month" class="form-label">Mois <span class="text-danger">*</span></label>
                                            <input type="month" 
                                                   class="form-control" 
                                                   id="month" 
                                                   name="month" 
                                                   value="<?php echo date('Y-m'); ?>" 
                                                   required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="agent_id" class="form-label">Agent <span class="text-danger">*</span></label>
                                            <select class="form-select" id="agent_id" name="agent_id" required>
                                                <option value="">Sélectionner un agent</option>
                                                <?php if (!empty($agents)): ?>
                                                    <?php foreach ($agents as $agent): ?>
                                                        <option value="<?php echo $agent->agent_post_id ?? $agent->ID; ?>">
                                                            <?php echo htmlspecialchars($agent->agent_name ?? $agent->display_name); ?>
                                                            <?php if (!empty($agent->agency_name)): ?>
                                                                <small> - <?php echo htmlspecialchars($agent->agency_name); ?></small>
                                                            <?php endif; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <h6 class="mb-3">Objectifs Quantitatifs</h6>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="estimations_target" class="form-label">
                                                <i class="ri-file-list-3-line me-2 text-primary"></i>Nombre d'Estimations
                                            </label>
                                            <input type="number" 
                                                   class="form-control" 
                                                   id="estimations_target" 
                                                   name="estimations_target" 
                                                   min="0" 
                                                   placeholder="Ex: 20"
                                                   required>
                                            <div class="form-text">Objectif d'estimations à réaliser ce mois</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="contacts_target" class="form-label">
                                                <i class="ri-phone-line me-2 text-success"></i>Nombre de Contacts
                                            </label>
                                            <input type="number" 
                                                   class="form-control" 
                                                   id="contacts_target" 
                                                   name="contacts_target" 
                                                   min="0" 
                                                   placeholder="Ex: 50"
                                                   required>
                                            <div class="form-text">Objectif de nouveaux contacts ce mois</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="transactions_target" class="form-label">
                                                <i class="ri-handshake-line me-2 text-warning"></i>Nombre de Transactions
                                            </label>
                                            <input type="number" 
                                                   class="form-control" 
                                                   id="transactions_target" 
                                                   name="transactions_target" 
                                                   min="0" 
                                                   placeholder="Ex: 5"
                                                   required>
                                            <div class="form-text">Objectif de ventes/locations finalisées</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="revenue_target" class="form-label">
                                                <i class="ri-money-euro-circle-line me-2 text-info"></i>Chiffre d'Affaires (€)
                                            </label>
                                            <input type="number" 
                                                   class="form-control" 
                                                   id="revenue_target" 
                                                   name="revenue_target" 
                                                   min="0" 
                                                   step="0.01" 
                                                   placeholder="Ex: 50000"
                                                   required>
                                            <div class="form-text">Objectif de CA à générer ce mois</div>
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <!-- Aperçu des Objectifs -->
                                <div class="card bg-light border-0">
                                    <div class="card-header bg-transparent">
                                        <h6 class="card-title mb-0">
                                            <i class="ri-eye-line me-2"></i>Aperçu des Objectifs
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-6 col-md-3">
                                                <div class="border rounded p-3 bg-white">
                                                    <i class="ri-file-list-3-line text-primary fs-24 mb-2"></i>
                                                    <h5 class="mb-1" id="preview_estimations">0</h5>
                                                    <small class="text-muted">Estimations</small>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-3">
                                                <div class="border rounded p-3 bg-white">
                                                    <i class="ri-phone-line text-success fs-24 mb-2"></i>
                                                    <h5 class="mb-1" id="preview_contacts">0</h5>
                                                    <small class="text-muted">Contacts</small>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-3">
                                                <div class="border rounded p-3 bg-white">
                                                    <i class="ri-handshake-line text-warning fs-24 mb-2"></i>
                                                    <h5 class="mb-1" id="preview_transactions">0</h5>
                                                    <small class="text-muted">Transactions</small>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-3">
                                                <div class="border rounded p-3 bg-white">
                                                    <i class="ri-money-euro-circle-line text-info fs-24 mb-2"></i>
                                                    <h5 class="mb-1" id="preview_revenue">0€</h5>
                                                    <small class="text-muted">CA Objectif</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 text-center">
                                    <button type="submit" class="btn btn-primary btn-lg me-3">
                                        <i class="ri-save-line me-2"></i>Définir les Objectifs
                                    </button>
                                    <a href="<?php echo base_url('objectives'); ?>" class="btn btn-outline-secondary btn-lg">
                                        <i class="ri-arrow-left-line me-2"></i>Retour
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Card d'aide -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="ri-lightbulb-line me-2"></i>Conseils pour les Objectifs
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h6 class="text-primary">
                                    <i class="ri-check-line me-2"></i>Objectifs SMART
                                </h6>
                                <p class="text-muted small">
                                    Définissez des objectifs Spécifiques, Mesurables, Atteignables, Réalistes et Temporels.
                                </p>
                            </div>

                            <div class="mb-3">
                                <h6 class="text-success">
                                    <i class="ri-bar-chart-line me-2"></i>Recommandations
                                </h6>
                                <ul class="text-muted small">
                                    <li>Estimations : 15-25 par mois pour un agent actif</li>
                                    <li>Contacts : 40-60 nouveaux prospects par mois</li>
                                    <li>Transactions : 3-7 selon l'expérience</li>
                                    <li>CA : Basé sur le prix moyen local</li>
                                </ul>
                            </div>

                            <div class="alert alert-info">
                                <i class="ri-information-line me-2"></i>
                                <strong>Note :</strong> Les objectifs peuvent être modifiés en cours de mois si nécessaire.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
// Mise à jour de l'aperçu en temps réel
function updatePreview() {
    const estimations = document.getElementById('estimations_target').value || 0;
    const contacts = document.getElementById('contacts_target').value || 0;
    const transactions = document.getElementById('transactions_target').value || 0;
    const revenue = document.getElementById('revenue_target').value || 0;

    document.getElementById('preview_estimations').textContent = estimations;
    document.getElementById('preview_contacts').textContent = contacts;
    document.getElementById('preview_transactions').textContent = transactions;
    document.getElementById('preview_revenue').textContent = 
        new Intl.NumberFormat('fr-FR', {style: 'currency', currency: 'EUR'}).format(revenue);
}

// Événements pour mise à jour automatique
document.addEventListener('DOMContentLoaded', function() {
    ['estimations_target', 'contacts_target', 'transactions_target', 'revenue_target'].forEach(id => {
        document.getElementById(id).addEventListener('input', updatePreview);
    });
    
    updatePreview();
});

// Validation du formulaire
document.getElementById('objectivesForm').addEventListener('submit', function(e) {
    const agent = document.getElementById('agent_id').value;
    const month = document.getElementById('month').value;
    
    if (!agent || !month) {
        e.preventDefault();
        alert('Veuillez sélectionner un agent et un mois.');
        return false;
    }
    
    // Vérifier qu'au moins un objectif est défini
    const estimations = parseInt(document.getElementById('estimations_target').value) || 0;
    const contacts = parseInt(document.getElementById('contacts_target').value) || 0;
    const transactions = parseInt(document.getElementById('transactions_target').value) || 0;
    const revenue = parseFloat(document.getElementById('revenue_target').value) || 0;
    
    if (estimations === 0 && contacts === 0 && transactions === 0 && revenue === 0) {
        e.preventDefault();
        alert('Veuillez définir au moins un objectif.');
        return false;
    }
});
</script>
