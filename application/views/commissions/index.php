<?php $this->load->view('includes/header'); ?>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- Page Title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Paramétrage des Commissions</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Dashboard</a></li>
                                <li class="breadcrumb-item active">Commissions</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Commission Settings Cards -->
            <div class="row">
                <!-- Vente Settings -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-primary bg-gradient text-white">
                            <h5 class="card-title mb-0 text-white">
                                <i class="ri-home-4-line me-2"></i>Commission Vente
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if (isset($sale_settings)): ?>
                                <form action="<?php echo base_url('commissions/update'); ?>" method="POST">
                                    <input type="hidden" name="transaction_type" value="sale">
                                    <input type="hidden" name="id" value="<?php echo $sale_settings->id; ?>">
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Commission Totale (%)</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" name="total_commission_rate" 
                                                       value="<?php echo $sale_settings->total_commission_rate; ?>" 
                                                       min="0" max="100" step="0.01">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Commission Agence (%)</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" name="agency_commission_rate" 
                                                       value="<?php echo $sale_settings->agency_commission_rate; ?>" 
                                                       min="0" max="100" step="0.01">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Commission Agent (%)</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" name="agent_commission_rate" 
                                                       value="<?php echo $sale_settings->agent_commission_rate; ?>" 
                                                       min="0" max="100" step="0.01">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Statut</label>
                                            <select class="form-select" name="is_active">
                                                <option value="1" <?php echo $sale_settings->is_active ? 'selected' : ''; ?>>Actif</option>
                                                <option value="0" <?php echo !$sale_settings->is_active ? 'selected' : ''; ?>>Inactif</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ri-save-line me-1"></i>Mettre à jour
                                    </button>
                                </form>
                            <?php else: ?>
                                <div class="alert alert-warning">
                                    <i class="ri-alert-line me-2"></i>
                                    Aucun paramètre de commission pour la vente configuré.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Location Settings -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-success bg-gradient text-white">
                            <h5 class="card-title mb-0 text-white">
                                <i class="ri-key-2-line me-2"></i>Commission Location
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if (isset($rental_settings)): ?>
                                <form action="<?php echo base_url('commissions/update'); ?>" method="POST">
                                    <input type="hidden" name="transaction_type" value="rental">
                                    <input type="hidden" name="id" value="<?php echo $rental_settings->id; ?>">
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Commission Totale (%)</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" name="total_commission_rate" 
                                                       value="<?php echo $rental_settings->total_commission_rate; ?>" 
                                                       min="0" max="100" step="0.01">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Commission Agence (%)</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" name="agency_commission_rate" 
                                                       value="<?php echo $rental_settings->agency_commission_rate; ?>" 
                                                       min="0" max="100" step="0.01">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Commission Agent (%)</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" name="agent_commission_rate" 
                                                       value="<?php echo $rental_settings->agent_commission_rate; ?>" 
                                                       min="0" max="100" step="0.01">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Nombre de mois</label>
                                            <input type="number" class="form-control" name="rental_months" 
                                                   value="<?php echo $rental_settings->rental_months; ?>" 
                                                   min="1" max="12">
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Statut</label>
                                            <select class="form-select" name="is_active">
                                                <option value="1" <?php echo $rental_settings->is_active ? 'selected' : ''; ?>>Actif</option>
                                                <option value="0" <?php echo !$rental_settings->is_active ? 'selected' : ''; ?>>Inactif</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-success">
                                        <i class="ri-save-line me-1"></i>Mettre à jour
                                    </button>
                                </form>
                            <?php else: ?>
                                <div class="alert alert-warning">
                                    <i class="ri-alert-line me-2"></i>
                                    Aucun paramètre de commission pour la location configuré.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Commission Calculator -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-info bg-gradient text-white">
                            <h5 class="card-title mb-0 text-white">
                                <i class="ri-calculator-line me-2"></i>Calculatrice de Commission
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4">
                                    <label class="form-label">Type de Transaction</label>
                                    <select class="form-select" id="calc_transaction_type">
                                        <option value="sale">Vente</option>
                                        <option value="rental">Location</option>
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <label class="form-label">Montant de la Propriété (€)</label>
                                    <input type="number" class="form-control" id="calc_property_value" 
                                           placeholder="Ex: 250000" min="0" step="1000">
                                </div>
                                <div class="col-lg-4 d-flex align-items-end">
                                    <button type="button" class="btn btn-info w-100" onclick="calculateCommission()">
                                        <i class="ri-calculator-line me-1"></i>Calculer
                                    </button>
                                </div>
                            </div>
                            
                            <div id="commission_results" class="mt-4" style="display: none;">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="card bg-primary bg-gradient text-white">
                                            <div class="card-body text-center">
                                                <h6 class="text-white">Commission Totale</h6>
                                                <h4 class="text-white mb-0" id="total_commission">€0</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-success bg-gradient text-white">
                                            <div class="card-body text-center">
                                                <h6 class="text-white">Commission Agence</h6>
                                                <h4 class="text-white mb-0" id="agency_commission">€0</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-warning bg-gradient text-white">
                                            <div class="card-body text-center">
                                                <h6 class="text-white">Commission Agent</h6>
                                                <h4 class="text-white mb-0" id="agent_commission">€0</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-info bg-gradient text-white">
                                            <div class="card-body text-center">
                                                <h6 class="text-white">Montant Net</h6>
                                                <h4 class="text-white mb-0" id="net_amount">€0</h4>
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

<script>
function calculateCommission() {
    const transactionType = document.getElementById('calc_transaction_type').value;
    const propertyValue = parseFloat(document.getElementById('calc_property_value').value) || 0;
    
    if (propertyValue <= 0) {
        alert('Veuillez saisir un montant valide.');
        return;
    }
    
    // Récupérer les taux depuis les paramètres affichés
    let totalRate, agencyRate, agentRate;
    
    if (transactionType === 'sale') {
        totalRate = <?php echo isset($sale_settings) ? $sale_settings->total_commission_rate : 10; ?>;
        agencyRate = <?php echo isset($sale_settings) ? $sale_settings->agency_commission_rate : 5; ?>;
        agentRate = <?php echo isset($sale_settings) ? $sale_settings->agent_commission_rate : 5; ?>;
    } else {
        totalRate = <?php echo isset($rental_settings) ? $rental_settings->total_commission_rate : 10; ?>;
        agencyRate = <?php echo isset($rental_settings) ? $rental_settings->agency_commission_rate : 5; ?>;
        agentRate = <?php echo isset($rental_settings) ? $rental_settings->agent_commission_rate : 5; ?>;
        
        // Pour la location, calculer sur la base mensuelle
        const months = <?php echo isset($rental_settings) ? $rental_settings->rental_months : 1; ?>;
        propertyValue = propertyValue * months;
    }
    
    const totalCommission = (propertyValue * totalRate) / 100;
    const agencyCommission = (propertyValue * agencyRate) / 100;
    const agentCommissionAmount = (propertyValue * agentRate) / 100;
    const netAmount = propertyValue - totalCommission;
    
    // Afficher les résultats
    document.getElementById('total_commission').textContent = '€' + totalCommission.toLocaleString('fr-FR', {maximumFractionDigits: 0});
    document.getElementById('agency_commission').textContent = '€' + agencyCommission.toLocaleString('fr-FR', {maximumFractionDigits: 0});
    document.getElementById('agent_commission').textContent = '€' + agentCommissionAmount.toLocaleString('fr-FR', {maximumFractionDigits: 0});
    document.getElementById('net_amount').textContent = '€' + netAmount.toLocaleString('fr-FR', {maximumFractionDigits: 0});
    
    document.getElementById('commission_results').style.display = 'block';
}
</script>

<?php $this->load->view('includes/footer'); ?>
