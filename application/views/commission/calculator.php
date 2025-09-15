<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- Titre de la page -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Calculatrice de Commission</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="<?php echo base_url('commission/settings'); ?>">Commissions</a></li>
                                <li class="breadcrumb-item active">Calculatrice</li>
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
                <!-- Formulaire de calcul -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ri-calculator-line me-2"></i>Calculer une Commission
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="<?php echo base_url('commission/calculator'); ?>" method="post" id="commissionForm">
                                <div class="mb-3">
                                    <label for="transaction_type" class="form-label">Type de Transaction</label>
                                    <select class="form-select" id="transaction_type" name="transaction_type" required>
                                        <option value="">Sélectionner le type</option>
                                        <option value="sale" <?php echo (isset($_POST['transaction_type']) && $_POST['transaction_type'] === 'sale') ? 'selected' : ''; ?>>
                                            Vente de Bien
                                        </option>
                                        <option value="rental" <?php echo (isset($_POST['transaction_type']) && $_POST['transaction_type'] === 'rental') ? 'selected' : ''; ?>>
                                            Location de Bien
                                        </option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="amount" class="form-label">Montant</label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control" 
                                               id="amount" 
                                               name="amount" 
                                               value="<?php echo isset($_POST['amount']) ? $_POST['amount'] : ''; ?>"
                                               step="0.01" 
                                               min="0" 
                                               placeholder="Ex: 300000 pour vente, 1500 pour location"
                                               required>
                                        <span class="input-group-text">€</span>
                                    </div>
                                    <div class="form-text" id="amount_help">
                                        Pour vente: prix de vente | Pour location: loyer mensuel
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="agent_id" class="form-label">Agent (optionnel)</label>
                                    <select class="form-select" id="agent_id" name="agent_id">
                                        <option value="">Sélectionner un agent</option>
                                        <?php if (!empty($agents)): ?>
                                            <?php foreach ($agents as $agent): ?>
                                                <option value="<?php echo $agent->ID; ?>" 
                                                        <?php echo (isset($_POST['agent_id']) && $_POST['agent_id'] == $agent->ID) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($agent->display_name); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ri-calculator-line me-2"></i>Calculer Commission
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="calculateAjax()">
                                        <i class="ri-flashlight-line me-2"></i>Calcul Rapide (AJAX)
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Paramètres actuels -->
                    <div class="card border-0 shadow-sm mt-3">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Exemple de Calcul</h6>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info mb-3">
                                <h6 class="alert-heading">
                                    <i class="ri-information-line me-2"></i>Comment ça marche ?
                                </h6>
                                <p class="mb-2"><strong>Pour une vente de 300 000€ :</strong></p>
                                <ul class="mb-0">
                                    <li>Commission totale = <strong>300 000€ × 5% = 15 000€</strong></li>
                                    <li>Commission Agent = <strong>15 000€ × 10% = 1 500€</strong></li>
                                    <li>Commission Agence = <strong>15 000€ × 90% = 13 500€</strong></li>
                                </ul>
                            </div>
                            
                            <div class="row">
                                <?php foreach ($settings as $setting): ?>
                                    <div class="col-6">
                                        <div class="border rounded p-3 text-center">
                                            <h6 class="text-uppercase mb-2">
                                                <?php echo $setting->type === 'sale' ? 'Vente' : 'Location'; ?>
                                            </h6>
                                            <?php if ($setting->type === 'sale'): ?>
                                                <p class="mb-1"><strong>Commission:</strong> 5% du prix</p>
                                                <p class="mb-1"><strong>Agent:</strong> 10% de la commission</p>
                                                <p class="mb-0"><strong>Agence:</strong> 90% de la commission</p>
                                            <?php else: ?>
                                                <p class="mb-1"><strong>Agent:</strong> <?php echo $setting->agent_rate; ?>%</p>
                                                <p class="mb-1"><strong>Agence:</strong> <?php echo $setting->agency_rate; ?>%</p>
                                                <?php if ($setting->type === 'rental'): ?>
                                                    <p class="mb-0"><strong>Mois:</strong> <?php echo $setting->rental_months; ?></p>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Résultats -->
                <div class="col-lg-6">
                    <?php if (isset($calculation) && $calculation): ?>
                        <div class="card border-0 shadow-sm border-success">
                            <div class="card-header bg-success-subtle">
                                <h5 class="card-title mb-0 text-success">
                                    <i class="ri-money-euro-circle-line me-2"></i>Résultat du Calcul
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="bg-light rounded p-3">
                                            <h6 class="text-muted mb-2">Type de Transaction</h6>
                                            <h5 class="mb-0">
                                                <?php echo $calculation['transaction_type'] === 'sale' ? 'Vente de Bien' : 'Location de Bien'; ?>
                                            </h5>
                                        </div>
                                    </div>
                                    
                                    <div class="col-6">
                                        <div class="text-center">
                                            <h6 class="text-muted">Montant de Base</h6>
                                            <h4 class="text-primary">
                                                <?php echo number_format($calculation['base_amount'], 2, ',', ' '); ?>€
                                            </h4>
                                        </div>
                                    </div>
                                    
                                    <?php if ($calculation['transaction_type'] === 'rental' && isset($calculation['rental_months'])): ?>
                                        <div class="col-6">
                                            <div class="text-center">
                                                <h6 class="text-muted">Base Commission</h6>
                                                <h4 class="text-info">
                                                    <?php echo number_format($calculation['commission_base'], 2, ',', ' '); ?>€
                                                </h4>
                                                <small class="text-muted">(<?php echo $calculation['rental_months']; ?> mois)</small>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="col-6">
                                            <div class="text-center">
                                                <h6 class="text-muted">Commission Totale</h6>
                                                <h4 class="text-info">
                                                    5%
                                                </h4>
                                                <small class="text-muted">du prix de vente</small>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <hr class="my-4">

                                <div class="row g-3">
                                    <div class="col-6">
                                        <div class="border rounded p-3 text-center bg-primary-subtle">
                                            <h6 class="text-muted mb-2">Commission Agent</h6>
                                            <h4 class="text-primary mb-1">
                                                <?php echo number_format($calculation['agent_commission'], 2, ',', ' '); ?>€
                                            </h4>
                                            <small class="text-muted">(10% de la commission totale)</small>
                                        </div>
                                    </div>
                                    
                                    <div class="col-6">
                                        <div class="border rounded p-3 text-center bg-secondary-subtle">
                                            <h6 class="text-muted mb-2">Commission Agence</h6>
                                            <h4 class="text-secondary mb-1">
                                                <?php echo number_format($calculation['agency_commission'], 2, ',', ' '); ?>€
                                            </h4>
                                            <small class="text-muted">(90% de la commission totale)</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <div class="border rounded p-4 text-center bg-success-subtle">
                                        <h5 class="text-muted mb-2">COMMISSION TOTALE</h5>
                                        <h2 class="text-success mb-0">
                                            <?php echo number_format($calculation['total_commission'], 2, ',', ' '); ?>€
                                        </h2>
                                    </div>
                                </div>

                                <!-- Formulaire pour sauvegarder -->
                                <form action="<?php echo base_url('commission/save'); ?>" method="post" class="mt-4">
                                    <input type="hidden" name="transaction_type" value="<?php echo $calculation['transaction_type']; ?>">
                                    <input type="hidden" name="base_amount" value="<?php echo $calculation['base_amount']; ?>">
                                    <input type="hidden" name="agent_commission" value="<?php echo $calculation['agent_commission']; ?>">
                                    <input type="hidden" name="agency_commission" value="<?php echo $calculation['agency_commission']; ?>">
                                    <input type="hidden" name="total_commission" value="<?php echo $calculation['total_commission']; ?>">
                                    <input type="hidden" name="agent_rate" value="<?php echo $calculation['agent_rate']; ?>">
                                    
                                    <div class="mb-3">
                                        <label for="save_agent_id" class="form-label">Agent pour cette commission</label>
                                        <select class="form-select" id="save_agent_id" name="agent_id" required>
                                            <option value="">Sélectionner un agent</option>
                                            <?php if (!empty($agents)): ?>
                                                <?php foreach ($agents as $agent): ?>
                                                    <option value="<?php echo $agent->ID; ?>">
                                                        <?php echo htmlspecialchars($agent->display_name); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="property_id" class="form-label">ID Propriété (optionnel)</label>
                                        <input type="number" class="form-control" id="property_id" name="property_id" placeholder="ID de la propriété">
                                    </div>
                                    
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="ri-save-line me-2"></i>Enregistrer cette Commission
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center py-5">
                                <i class="ri-calculator-line text-muted" style="font-size: 4rem;"></i>
                                <h5 class="mt-3 text-muted">Calculez une Commission</h5>
                                <p class="text-muted">Remplissez le formulaire à gauche pour calculer automatiquement les commissions selon vos paramètres.</p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Zone pour résultats AJAX -->
                    <div id="ajax-results" class="mt-3" style="display: none;"></div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
// Calcul AJAX rapide
function calculateAjax() {
    const form = document.getElementById('commissionForm');
    const formData = new FormData(form);
    
    // Validation rapide
    if (!formData.get('transaction_type') || !formData.get('amount')) {
        alert('Veuillez remplir le type de transaction et le montant.');
        return;
    }
    
    fetch('<?php echo base_url('commission/api_calculate'); ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const resultsDiv = document.getElementById('ajax-results');
        
        if (data.success) {
            const calc = data.data;
            resultsDiv.innerHTML = `
                <div class="card border-info">
                    <div class="card-header bg-info-subtle">
                        <h6 class="card-title mb-0 text-info">
                            <i class="ri-flashlight-line me-2"></i>Calcul Rapide
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-4">
                                <h6 class="text-muted">Agent</h6>
                                <h5 class="text-primary">${new Intl.NumberFormat('fr-FR', {style: 'currency', currency: 'EUR'}).format(calc.agent_commission)}</h5>
                            </div>
                            <div class="col-4">
                                <h6 class="text-muted">Agence</h6>
                                <h5 class="text-secondary">${new Intl.NumberFormat('fr-FR', {style: 'currency', currency: 'EUR'}).format(calc.agency_commission)}</h5>
                            </div>
                            <div class="col-4">
                                <h6 class="text-muted">Total</h6>
                                <h5 class="text-success">${new Intl.NumberFormat('fr-FR', {style: 'currency', currency: 'EUR'}).format(calc.total_commission)}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            resultsDiv.style.display = 'block';
        } else {
            resultsDiv.innerHTML = `
                <div class="alert alert-danger">
                    <i class="ri-error-warning-line me-2"></i>${data.error}
                </div>
            `;
            resultsDiv.style.display = 'block';
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors du calcul');
    });
}

// Mise à jour du texte d'aide selon le type
document.getElementById('transaction_type').addEventListener('change', function() {
    const helpText = document.getElementById('amount_help');
    const amountInput = document.getElementById('amount');
    
    if (this.value === 'sale') {
        helpText.textContent = 'Montant: prix de vente total du bien';
        amountInput.placeholder = 'Ex: 300000';
    } else if (this.value === 'rental') {
        helpText.textContent = 'Montant: loyer mensuel';
        amountInput.placeholder = 'Ex: 1500';
    } else {
        helpText.textContent = 'Pour vente: prix de vente | Pour location: loyer mensuel';
        amountInput.placeholder = 'Ex: 300000 pour vente, 1500 pour location';
    }
});
</script>
