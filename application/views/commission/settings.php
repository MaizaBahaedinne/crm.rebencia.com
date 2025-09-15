<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- Titre de la page -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Paramètres des Commissions</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Dashboard</a></li>
                                <li class="breadcrumb-item active">Paramètres Commissions</li>
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
                <!-- Card pour Commissions Vente -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-primary-subtle">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="ri-home-4-line text-primary fs-24"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="card-title mb-1">Commissions Vente</h5>
                                    <p class="card-text text-muted mb-0">Paramètres pour les transactions de vente</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php 
                            $sale_settings = null;
                            foreach ($settings as $setting) {
                                if ($setting->type === 'sale') {
                                    $sale_settings = $setting;
                                    break;
                                }
                            }
                            ?>
                            <form action="<?php echo base_url('commission/update_settings'); ?>" method="post">
                                <div class="mb-3">
                                    <label for="sale_agent_rate" class="form-label">Commission Agent (%)</label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control" 
                                               id="sale_agent_rate" 
                                               name="sale_agent_rate" 
                                               value="<?php echo $sale_settings ? $sale_settings->agent_rate : 5; ?>" 
                                               step="0.01" 
                                               min="0" 
                                               max="100" 
                                               required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <div class="form-text">Pourcentage de commission pour l'agent sur le prix de vente</div>
                                </div>

                                <div class="mb-3">
                                    <label for="sale_agency_rate" class="form-label">Commission Agence (%)</label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control" 
                                               id="sale_agency_rate" 
                                               name="sale_agency_rate" 
                                               value="<?php echo $sale_settings ? $sale_settings->agency_rate : 5; ?>" 
                                               step="0.01" 
                                               min="0" 
                                               max="100" 
                                               required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <div class="form-text">Pourcentage de commission pour l'agence sur le prix de vente</div>
                                </div>

                                <div class="alert alert-info">
                                    <h6 class="alert-heading">
                                        <i class="ri-information-line me-2"></i>Exemple de Calcul
                                    </h6>
                                    <p class="mb-0">
                                        Pour une vente de <strong>300 000 TND</strong> :<br>
                                        • Commission Agent : <span id="sale_agent_example">15 000 TND</span><br>
                                        • Commission Agence : <span id="sale_agency_example">15 000 TND</span><br>
                                        • <strong>Total : <span id="sale_total_example">30 000 TND</span></strong>
                                    </p>
                                </div>
                        </div>
                    </div>
                </div>

                <!-- Card pour Commissions Location -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-success-subtle">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="ri-key-2-line text-success fs-24"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="card-title mb-1">Commissions Location</h5>
                                    <p class="card-text text-muted mb-0">Paramètres pour les transactions de location</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php 
                            $rental_settings = null;
                            foreach ($settings as $setting) {
                                if ($setting->type === 'rental') {
                                    $rental_settings = $setting;
                                    break;
                                }
                            }
                            ?>
                            <div class="mb-3">
                                <label for="rental_agent_rate" class="form-label">Commission Agent (%)</label>
                                <div class="input-group">
                                    <input type="number" 
                                           class="form-control" 
                                           id="rental_agent_rate" 
                                           name="rental_agent_rate" 
                                           value="<?php echo $rental_settings ? $rental_settings->agent_rate : 10; ?>" 
                                           step="0.01" 
                                           min="0" 
                                           max="100" 
                                           required>
                                    <span class="input-group-text">%</span>
                                </div>
                                <div class="form-text">Pourcentage de commission pour l'agent</div>
                            </div>

                            <div class="mb-3">
                                <label for="rental_months" class="form-label">Nombre de Mois</label>
                                <div class="input-group">
                                    <input type="number" 
                                           class="form-control" 
                                           id="rental_months" 
                                           name="rental_months" 
                                           value="<?php echo $rental_settings ? $rental_settings->rental_months : 1; ?>" 
                                           min="1" 
                                           max="12" 
                                           required>
                                    <span class="input-group-text">mois</span>
                                </div>
                                <div class="form-text">Commission calculée sur X mois de loyer</div>
                            </div>

                            <div class="alert alert-success">
                                <h6 class="alert-heading">
                                    <i class="ri-information-line me-2"></i>Exemple de Calcul
                                </h6>
                                <p class="mb-0">
                                    Pour un loyer de <strong>1 500 TND/mois</strong> :<br>
                                    • Base de calcul : <span id="rental_base_example">1 500 TND</span><br>
                                    • Commission Agent : <span id="rental_agent_example">150 TND</span><br>
                                    • Commission Agence : <strong>0 TND</strong> (Location)
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <button type="submit" class="btn btn-primary btn-lg me-3">
                                <i class="ri-save-line me-2"></i>Sauvegarder les Paramètres
                            </button>
                            <a href="<?php echo base_url('commission/calculator'); ?>" class="btn btn-outline-secondary btn-lg me-3">
                                <i class="ri-calculator-line me-2"></i>Calculatrice
                            </a>
                            <a href="<?php echo base_url('commission/stats'); ?>" class="btn btn-outline-info btn-lg">
                                <i class="ri-bar-chart-line me-2"></i>Statistiques
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            </form>

        </div>
    </div>
</div>

<script>
// Calcul automatique des exemples
function updateExamples() {
    // Paramètres
    const saleAgentRate = parseFloat(document.getElementById('sale_agent_rate').value) || 0;
    const saleAgencyRate = parseFloat(document.getElementById('sale_agency_rate').value) || 0;
    const rentalAgentRate = parseFloat(document.getElementById('rental_agent_rate').value) || 0;
    const rentalMonths = parseInt(document.getElementById('rental_months').value) || 1;
    
    // Exemples vente (300 000 TND)
    const salePrice = 300000;
    const saleAgentCommission = (salePrice * saleAgentRate) / 100;
    const saleAgencyCommission = (salePrice * saleAgencyRate) / 100;
    const saleTotalCommission = saleAgentCommission + saleAgencyCommission;
    
    document.getElementById('sale_agent_example').textContent = 
        new Intl.NumberFormat('fr-FR', {style: 'currency', currency: 'EUR'}).format(saleAgentCommission);
    document.getElementById('sale_agency_example').textContent = 
        new Intl.NumberFormat('fr-FR', {style: 'currency', currency: 'EUR'}).format(saleAgencyCommission);
    document.getElementById('sale_total_example').textContent = 
        new Intl.NumberFormat('fr-FR', {style: 'currency', currency: 'EUR'}).format(saleTotalCommission);
    
    // Exemples location (1 500 TND/mois)
    const monthlyRent = 1500;
    const rentalBase = monthlyRent * rentalMonths;
    const rentalAgentCommission = (rentalBase * rentalAgentRate) / 100;
    
    document.getElementById('rental_base_example').textContent = 
        new Intl.NumberFormat('fr-FR', {style: 'currency', currency: 'EUR'}).format(rentalBase);
    document.getElementById('rental_agent_example').textContent = 
        new Intl.NumberFormat('fr-FR', {style: 'currency', currency: 'EUR'}).format(rentalAgentCommission);
}

// Événements pour mise à jour automatique
document.addEventListener('DOMContentLoaded', function() {
    updateExamples();
    
    ['sale_agent_rate', 'sale_agency_rate', 'rental_agent_rate', 'rental_months'].forEach(id => {
        document.getElementById(id).addEventListener('input', updateExamples);
    });
});
</script>
