<?php $this->load->view('includes/header'); ?>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- Page Title -->
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

            <!-- Objective Setting Form -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-primary bg-gradient text-white">
                            <h5 class="card-title mb-0 text-white">
                                <i class="ri-target-line me-2"></i>Définir les Objectifs par Agent
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="<?php echo base_url('objectives/save_objectives'); ?>" method="POST" id="objectives-form">
                                
                                <!-- Month Selection -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label">Mois cible <span class="text-danger">*</span></label>
                                        <input type="month" class="form-control" name="target_month" 
                                               value="<?php echo $selected_month ?? date('Y-m'); ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Sélectionner tous les agents</label>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" id="select_all_agents" 
                                                   onchange="toggleAllAgents()">
                                            <label class="form-check-label" for="select_all_agents">
                                                Sélectionner tous les agents
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Agents List -->
                                <?php if (!empty($agents)): ?>
                                    <div class="row">
                                        <?php foreach ($agents as $agent): ?>
                                            <div class="col-lg-6 mb-4">
                                                <div class="card border border-light">
                                                    <div class="card-header bg-light">
                                                        <div class="form-check">
                                                            <input class="form-check-input agent-checkbox" type="checkbox" 
                                                                   name="selected_agents[]" value="<?php echo $agent->ID; ?>" 
                                                                   id="agent_<?php echo $agent->ID; ?>"
                                                                   onchange="toggleAgentCard(<?php echo $agent->ID; ?>)">
                                                            <label class="form-check-label" for="agent_<?php echo $agent->ID; ?>">
                                                                <strong><?php echo htmlspecialchars($agent->display_name); ?></strong>
                                                                <small class="text-muted d-block">
                                                                    <?php echo htmlspecialchars($agent->user_email); ?>
                                                                </small>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="card-body agent-objectives" id="objectives_<?php echo $agent->ID; ?>" style="display: none;">
                                                        
                                                        <!-- Existing objectives if any -->
                                                        <?php if (isset($existing_objectives[$agent->ID])): ?>
                                                            <div class="alert alert-info alert-dismissible fade show" role="alert">
                                                                <i class="ri-information-line me-2"></i>
                                                                Des objectifs existent déjà pour cet agent ce mois-ci. 
                                                                Les nouvelles valeurs remplaceront les anciennes.
                                                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                                            </div>
                                                        <?php endif; ?>
                                                        
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">
                                                                    <i class="ri-file-list-3-line text-primary me-1"></i>
                                                                    Estimations Target
                                                                </label>
                                                                <input type="number" class="form-control" 
                                                                       name="estimations_target[<?php echo $agent->ID; ?>]" 
                                                                       value="<?php echo $existing_objectives[$agent->ID]->estimations_target ?? 0; ?>"
                                                                       min="0" placeholder="Ex: 20">
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">
                                                                    <i class="ri-phone-line text-success me-1"></i>
                                                                    Contacts Target
                                                                </label>
                                                                <input type="number" class="form-control" 
                                                                       name="contacts_target[<?php echo $agent->ID; ?>]" 
                                                                       value="<?php echo $existing_objectives[$agent->ID]->contacts_target ?? 0; ?>"
                                                                       min="0" placeholder="Ex: 50">
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">
                                                                    <i class="ri-exchange-line text-warning me-1"></i>
                                                                    Transactions Target
                                                                </label>
                                                                <input type="number" class="form-control" 
                                                                       name="transactions_target[<?php echo $agent->ID; ?>]" 
                                                                       value="<?php echo $existing_objectives[$agent->ID]->transactions_target ?? 0; ?>"
                                                                       min="0" placeholder="Ex: 5">
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">
                                                                    <i class="ri-money-euro-circle-line text-info me-1"></i>
                                                                    CA Target (€)
                                                                </label>
                                                                <input type="number" class="form-control" 
                                                                       name="revenue_target[<?php echo $agent->ID; ?>]" 
                                                                       value="<?php echo $existing_objectives[$agent->ID]->revenue_target ?? 0; ?>"
                                                                       min="0" step="1000" placeholder="Ex: 50000">
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Quick Actions -->
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="btn-group btn-group-sm" role="group">
                                                                    <button type="button" class="btn btn-outline-primary" 
                                                                            onclick="applyTemplate(<?php echo $agent->ID; ?>, 'low')">
                                                                        Template Faible
                                                                    </button>
                                                                    <button type="button" class="btn btn-outline-success" 
                                                                            onclick="applyTemplate(<?php echo $agent->ID; ?>, 'medium')">
                                                                        Template Moyen
                                                                    </button>
                                                                    <button type="button" class="btn btn-outline-warning" 
                                                                            onclick="applyTemplate(<?php echo $agent->ID; ?>, 'high')">
                                                                        Template Élevé
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-5">
                                        <div class="avatar-xl mx-auto mb-4">
                                            <div class="avatar-title bg-warning bg-gradient rounded-circle">
                                                <i class="ri-user-search-line display-4"></i>
                                            </div>
                                        </div>
                                        <h5>Aucun agent trouvé</h5>
                                        <p class="text-muted">Aucun agent n'est disponible pour définir des objectifs.</p>
                                    </div>
                                <?php endif; ?>

                                <!-- Submit Button -->
                                <?php if (!empty($agents)): ?>
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <div class="d-flex justify-content-end">
                                                <a href="<?php echo base_url('objectives'); ?>" class="btn btn-secondary me-2">
                                                    <i class="ri-arrow-left-line me-1"></i>Retour
                                                </a>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="ri-save-line me-1"></i>Enregistrer les Objectifs
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
// Templates prédéfinis
const templates = {
    low: {
        estimations: 10,
        contacts: 25,
        transactions: 2,
        revenue: 25000
    },
    medium: {
        estimations: 20,
        contacts: 50,
        transactions: 5,
        revenue: 50000
    },
    high: {
        estimations: 35,
        contacts: 80,
        transactions: 8,
        revenue: 75000
    }
};

function toggleAllAgents() {
    const selectAll = document.getElementById('select_all_agents');
    const agentCheckboxes = document.querySelectorAll('.agent-checkbox');
    
    agentCheckboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
        toggleAgentCard(checkbox.value);
    });
}

function toggleAgentCard(agentId) {
    const checkbox = document.getElementById('agent_' + agentId);
    const objectivesDiv = document.getElementById('objectives_' + agentId);
    
    if (checkbox.checked) {
        objectivesDiv.style.display = 'block';
    } else {
        objectivesDiv.style.display = 'none';
    }
}

function applyTemplate(agentId, templateType) {
    const template = templates[templateType];
    
    // Remplir les champs avec les valeurs du template
    document.querySelector(`input[name="estimations_target[${agentId}]"]`).value = template.estimations;
    document.querySelector(`input[name="contacts_target[${agentId}]"]`).value = template.contacts;
    document.querySelector(`input[name="transactions_target[${agentId}]"]`).value = template.transactions;
    document.querySelector(`input[name="revenue_target[${agentId}]"]`).value = template.revenue;
}

// Validation du formulaire
document.getElementById('objectives-form').addEventListener('submit', function(e) {
    const selectedAgents = document.querySelectorAll('.agent-checkbox:checked');
    
    if (selectedAgents.length === 0) {
        e.preventDefault();
        alert('Veuillez sélectionner au moins un agent.');
        return false;
    }
    
    // Vérifier que les objectifs sont remplis pour les agents sélectionnés
    let hasEmptyFields = false;
    selectedAgents.forEach(checkbox => {
        const agentId = checkbox.value;
        const estimations = document.querySelector(`input[name="estimations_target[${agentId}]"]`).value;
        const contacts = document.querySelector(`input[name="contacts_target[${agentId}]"]`).value;
        const transactions = document.querySelector(`input[name="transactions_target[${agentId}]"]`).value;
        const revenue = document.querySelector(`input[name="revenue_target[${agentId}]"]`).value;
        
        if (!estimations && !contacts && !transactions && !revenue) {
            hasEmptyFields = true;
        }
    });
    
    if (hasEmptyFields) {
        if (!confirm('Certains agents sélectionnés n\'ont aucun objectif défini. Continuer ?')) {
            e.preventDefault();
            return false;
        }
    }
});
</script>

<?php $this->load->view('includes/footer'); ?>
