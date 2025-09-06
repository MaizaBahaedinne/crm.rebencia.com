<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0"><?= $pageTitle ?? (isset($client) ? 'Modifier le client' : 'Ajouter un client') ?></h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="<?= base_url('client') ?>">Clients CRM</a></li>
                                <li class="breadcrumb-item active"><?= isset($client) ? 'Modifier' : 'Ajouter' ?></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <form method="post" action="<?= isset($client) ? base_url('client/update/'.$client->id) : base_url('client/add') ?>" enctype="multipart/form-data">
                
                <!-- Informations de base -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0">Informations générales</h4>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <!-- Agence et Agent -->
                                    <div class="col-md-6">
                                        <label class="form-label">Agence <span class="text-danger">*</span></label>
                                        <div class="position-relative">
                                            <input type="text" id="agency_search" class="form-control" placeholder="Rechercher une agence..." autocomplete="off" value="<?= isset($client) && isset($client->agency_name) ? htmlspecialchars($client->agency_name) : '' ?>" required>
                                            <input type="hidden" name="agency_id" id="agency_id" value="<?= isset($client) && isset($client->agency_id) ? $client->agency_id : '' ?>">
                                            <div id="agency_dropdown" class="dropdown-menu w-100" style="display: none; max-height: 200px; overflow-y: auto;"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label">Agent responsable <span class="text-danger">*</span></label>
                                        <div class="position-relative">
                                            <input type="text" id="agent_search" class="form-control" placeholder="Sélectionner d'abord une agence" autocomplete="off" disabled value="<?= isset($client) && isset($client->agent_name) ? htmlspecialchars($client->agent_name) : '' ?>" required>
                                            <input type="hidden" name="agent_id" id="agent_id" value="<?= isset($client) && isset($client->agent_id) ? $client->agent_id : '' ?>">
                                            <div id="agent_dropdown" class="dropdown-menu w-100" style="display: none; max-height: 200px; overflow-y: auto;"></div>
                                        </div>
                                        <div id="agent_loading" style="display: none;" class="mt-2">
                                            <small class="text-muted">
                                                <i class="ri-loader-4-line"></i> Chargement des agents...
                                            </small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Nom <span class="text-danger">*</span></label>
                                        <input type="text" name="nom" class="form-control" placeholder="Nom complet / Raison sociale" value="<?= isset($client) && isset($client->nom) ? htmlspecialchars($client->nom) : '' ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Prénom</label>
                                        <input type="text" name="prenom" class="form-control" placeholder="Prénom" value="<?= isset($client) && isset($client->prenom) ? htmlspecialchars($client->prenom) : '' ?>">
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label">Type de client <span class="text-danger">*</span></label>
                                        <select name="type_client" class="form-select" required>
                                            <option value="">Sélectionner le type</option>
                                            <option value="Acheteur" <?= (isset($client) && isset($client->type_client) && $client->type_client=='Acheteur')?'selected':''; ?>>Acheteur</option>
                                            <option value="Vendeur" <?= (isset($client) && isset($client->type_client) && $client->type_client=='Vendeur')?'selected':''; ?>>Vendeur</option>
                                            <option value="Investisseur" <?= (isset($client) && isset($client->type_client) && $client->type_client=='Investisseur')?'selected':''; ?>>Investisseur</option>
                                            <option value="Locataire" <?= (isset($client) && isset($client->type_client) && $client->type_client=='Locataire')?'selected':''; ?>>Locataire</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control" placeholder="Email" value="<?= isset($client) && isset($client->email) ? htmlspecialchars($client->email) : '' ?>" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Documents et identité -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0">Documents d'identité</h4>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Type d'identité</label>
                                        <select name="identite_type" class="form-select">
                                            <option value="">Sélectionner le type</option>
                                            <option value="CIN" <?= (isset($client) && isset($client->identite_type) && $client->identite_type=='CIN')?'selected':''; ?>>CIN</option>
                                            <option value="Passeport" <?= (isset($client) && isset($client->identite_type) && $client->identite_type=='Passeport')?'selected':''; ?>>Passeport</option>
                                            <option value="Titre de séjour" <?= (isset($client) && isset($client->identite_type) && $client->identite_type=='Titre de séjour')?'selected':''; ?>>Titre de séjour</option>
                                            <option value="Registre de commerce" <?= (isset($client) && isset($client->identite_type) && $client->identite_type=='Registre de commerce')?'selected':''; ?>>Registre de commerce</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Numéro d'identité</label>
                                        <input type="text" name="identite_numero" class="form-control" placeholder="Numéro d'identité / RC" value="<?= isset($client) && isset($client->identite_numero) ? htmlspecialchars($client->identite_numero) : '' ?>">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Document d'identité</label>
                                        <input type="file" name="identite_doc" class="form-control" accept="application/pdf,image/*">
                                        <?php if(isset($client) && isset($client->identite_doc) && !empty($client->identite_doc)): ?>
                                            <div class="mt-2">
                                                <a href="<?= base_url('uploads/clients/'.$client->identite_doc) ?>" target="_blank" class="text-primary">
                                                    <i class="ri-file-text-line me-1"></i>Document existant
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0">Informations de contact</h4>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Téléphone principal <span class="text-danger">*</span></label>
                                        <input type="text" name="contact_principal" class="form-control" placeholder="Téléphone principal" value="<?= isset($client) ? htmlspecialchars($client->contact_principal) : '' ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Téléphone secondaire</label>
                                        <input type="text" name="contact_secondaire" class="form-control" placeholder="Téléphone secondaire" value="<?= isset($client) ? htmlspecialchars($client->contact_secondaire) : '' ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Adresse -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0">Adresse</h4>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label">Adresse complète</label>
                                        <textarea name="adresse" class="form-control" rows="3" placeholder="Adresse complète"><?= isset($client) ? htmlspecialchars($client->adresse) : '' ?></textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Ville</label>
                                        <input type="text" name="ville" class="form-control" placeholder="Ville" value="<?= isset($client) ? htmlspecialchars($client->ville) : '' ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Code postal</label>
                                        <input type="text" name="code_postal" class="form-control" placeholder="Code postal" value="<?= isset($client) ? htmlspecialchars($client->code_postal) : '' ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Pays</label>
                                        <input type="text" name="pays" class="form-control" placeholder="Pays" value="<?= isset($client) ? htmlspecialchars($client->pays) : 'Maroc' ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations complémentaires -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0">Informations complémentaires</h4>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label">Source d'information</label>
                                        <div class="row g-2">
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="source" value="Site web" id="source_web" <?= (isset($client) && $client->source=='Site web')?'checked':''; ?>>
                                                    <label class="form-check-label" for="source_web">Site web</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="source" value="Réseaux sociaux" id="source_social" <?= (isset($client) && $client->source=='Réseaux sociaux')?'checked':''; ?>>
                                                    <label class="form-check-label" for="source_social">Réseaux sociaux</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="source" value="Bouche à oreille" id="source_bouche" <?= (isset($client) && $client->source=='Bouche à oreille')?'checked':''; ?>>
                                                    <label class="form-check-label" for="source_bouche">Bouche à oreille</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="source" value="Publicité" id="source_pub" <?= (isset($client) && $client->source=='Publicité')?'checked':''; ?>>
                                                    <label class="form-check-label" for="source_pub">Publicité</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="source" value="Partenaire" id="source_partenaire" <?= (isset($client) && $client->source=='Partenaire')?'checked':''; ?>>
                                                    <label class="form-check-label" for="source_partenaire">Partenaire</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="source" value="Prospection directe" id="source_prospection" <?= (isset($client) && $client->source=='Prospection directe')?'checked':''; ?>>
                                                    <label class="form-check-label" for="source_prospection">Prospection directe</label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="source" value="Autre" id="source_autre" <?= (isset($client) && !in_array($client->source, ['Site web', 'Réseaux sociaux', 'Bouche à oreille', 'Publicité', 'Partenaire', 'Prospection directe', '']))?'checked':''; ?>>
                                                    <label class="form-check-label" for="source_autre">Autre :</label>
                                                    <input type="text" name="source_autre_detail" class="form-control mt-2" placeholder="Précisez..." value="<?= (isset($client) && !in_array($client->source, ['Site web', 'Réseaux sociaux', 'Bouche à oreille', 'Publicité', 'Partenaire', 'Prospection directe', ''])) ? htmlspecialchars($client->source) : '' ?>" style="display: <?= (isset($client) && !in_array($client->source, ['Site web', 'Réseaux sociaux', 'Bouche à oreille', 'Publicité', 'Partenaire', 'Prospection directe', ''])) ? 'block' : 'none' ?>;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Notes</label>
                                        <textarea name="notes" class="form-control" rows="4" placeholder="Notes et observations"><?= isset($client) ? htmlspecialchars($client->notes) : '' ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="hstack gap-2 justify-content-end">
                                    <a href="<?= base_url('client') ?>" class="btn btn-light">
                                        <i class="ri-close-line align-bottom me-1"></i> Annuler
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ri-save-line align-bottom me-1"></i> 
                                        <?= isset($client) ? 'Modifier' : 'Ajouter' ?> le client
                                    </button>
                                    <?php if(isset($client) && isset($client->id)): ?>
                                        <a href="<?= base_url('client/delete/'.$client->id) ?>" class="btn btn-danger" onclick="return confirm('Supprimer ce client ?')">
                                            <i class="ri-delete-bin-line align-bottom me-1"></i> Supprimer
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<style>
/* Styles pour l'autocomplétion */
.dropdown-menu {
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    z-index: 1050;
}

.dropdown-item {
    padding: 0.5rem 1rem;
    border: none;
    background: none;
    width: 100%;
    text-align: left;
    transition: background-color 0.15s ease-in-out;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
    color: #212529;
}

.dropdown-item-text {
    padding: 0.5rem 1rem;
    color: #6c757d;
    font-size: 0.875rem;
}

.position-relative .form-control:focus + .dropdown-menu {
    border-color: #86b7fe;
}

/* Animation pour le loader */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.ri-loader-4-line {
    animation: spin 1s linear infinite;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sourceRadios = document.querySelectorAll('input[name="source"]');
    const sourceAutreDetail = document.querySelector('input[name="source_autre_detail"]');
    
    // Variables pour l'autocomplétion des agences
    const agencySearch = document.getElementById('agency_search');
    const agencyId = document.getElementById('agency_id');
    const agencyDropdown = document.getElementById('agency_dropdown');
    
    // Variables pour l'autocomplétion des agents
    const agentSearch = document.getElementById('agent_search');
    const agentId = document.getElementById('agent_id');
    const agentDropdown = document.getElementById('agent_dropdown');
    const agentLoading = document.getElementById('agent_loading');
    
    let agencySearchTimeout;
    let agentSearchTimeout;
    
    // Gestion du champ "Autre" pour la source
    sourceRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'Autre') {
                sourceAutreDetail.style.display = 'block';
                sourceAutreDetail.focus();
            } else {
                sourceAutreDetail.style.display = 'none';
                sourceAutreDetail.value = '';
            }
        });
    });
    
    // Si "Autre" est pré-sélectionné, afficher le champ
    const sourceAutre = document.getElementById('source_autre');
    if (sourceAutre && sourceAutre.checked) {
        sourceAutreDetail.style.display = 'block';
    }
    
    // ===== AUTOCOMPLETION AGENCES =====
    agencySearch.addEventListener('input', function() {
        const query = this.value.trim();
        
        clearTimeout(agencySearchTimeout);
        
        if (query.length < 2) {
            agencyDropdown.style.display = 'none';
            agencyId.value = '';
            resetAgentField();
            return;
        }
        
        agencySearchTimeout = setTimeout(() => {
            searchAgencies(query);
        }, 300);
    });
    
    function searchAgencies(query) {
        fetch('<?= base_url("client/search_agencies") ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'query=' + encodeURIComponent(query)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.agencies) {
                showAgencyDropdown(data.agencies);
            } else {
                agencyDropdown.style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Erreur recherche agences:', error);
            agencyDropdown.style.display = 'none';
        });
    }
    
    function showAgencyDropdown(agencies) {
        agencyDropdown.innerHTML = '';
        
        if (agencies.length === 0) {
            agencyDropdown.innerHTML = '<div class="dropdown-item-text">Aucune agence trouvée</div>';
        } else {
            agencies.forEach(agency => {
                const item = document.createElement('div');
                item.className = 'dropdown-item';
                item.style.cursor = 'pointer';
                item.textContent = agency.name;
                item.addEventListener('click', () => selectAgency(agency));
                agencyDropdown.appendChild(item);
            });
        }
        
        agencyDropdown.style.display = 'block';
    }
    
    function selectAgency(agency) {
        agencySearch.value = agency.name;
        agencyId.value = agency.id;
        agencyDropdown.style.display = 'none';
        
        // Réinitialiser et activer le champ agent
        resetAgentField();
        agentSearch.disabled = false;
        agentSearch.placeholder = 'Rechercher un agent...';
        
        // Charger tous les agents de cette agence
        loadAgentsByAgency(agency.id);
    }
    
    // ===== AUTOCOMPLETION AGENTS =====
    agentSearch.addEventListener('input', function() {
        const query = this.value.trim();
        const currentAgencyId = agencyId.value;
        
        if (!currentAgencyId) {
            return;
        }
        
        clearTimeout(agentSearchTimeout);
        
        if (query.length < 2) {
            // Charger tous les agents si moins de 2 caractères
            loadAgentsByAgency(currentAgencyId);
            return;
        }
        
        agentSearchTimeout = setTimeout(() => {
            searchAgentsByAgency(currentAgencyId, query);
        }, 300);
    });
    
    function searchAgentsByAgency(agencyId, query = '') {
        agentLoading.style.display = 'block';
        
        fetch('<?= base_url("client/search_agents_by_agency") ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'agency_id=' + encodeURIComponent(agencyId) + '&query=' + encodeURIComponent(query)
        })
        .then(response => response.json())
        .then(data => {
            agentLoading.style.display = 'none';
            
            if (data.success && data.agents) {
                showAgentDropdown(data.agents);
            } else {
                agentDropdown.style.display = 'none';
            }
        })
        .catch(error => {
            agentLoading.style.display = 'none';
            console.error('Erreur recherche agents:', error);
            agentDropdown.style.display = 'none';
        });
    }
    
    function loadAgentsByAgency(agencyId) {
        searchAgentsByAgency(agencyId, '');
    }
    
    function showAgentDropdown(agents) {
        agentDropdown.innerHTML = '';
        
        if (agents.length === 0) {
            agentDropdown.innerHTML = '<div class="dropdown-item-text">Aucun agent trouvé</div>';
        } else {
            agents.forEach(agent => {
                const item = document.createElement('div');
                item.className = 'dropdown-item';
                item.style.cursor = 'pointer';
                item.textContent = agent.name;
                item.addEventListener('click', () => selectAgent(agent));
                agentDropdown.appendChild(item);
            });
        }
        
        agentDropdown.style.display = 'block';
    }
    
    function selectAgent(agent) {
        agentSearch.value = agent.name;
        agentId.value = agent.id;
        agentDropdown.style.display = 'none';
    }
    
    function resetAgentField() {
        agentSearch.value = '';
        agentId.value = '';
        agentSearch.disabled = true;
        agentSearch.placeholder = 'Sélectionner d\'abord une agence';
        agentDropdown.style.display = 'none';
    }
    
    // Cacher les dropdowns en cliquant ailleurs
    document.addEventListener('click', function(e) {
        if (!agencySearch.contains(e.target) && !agencyDropdown.contains(e.target)) {
            agencyDropdown.style.display = 'none';
        }
        if (!agentSearch.contains(e.target) && !agentDropdown.contains(e.target)) {
            agentDropdown.style.display = 'none';
        }
    });
    
    // Si on est en mode édition avec des valeurs pré-remplies
    <?php if(isset($client) && isset($client->agency_id)): ?>
    if (agencyId.value) {
        agentSearch.disabled = false;
        agentSearch.placeholder = 'Rechercher un agent...';
        loadAgentsByAgency(agencyId.value);
    }
    <?php endif; ?>
});
</script>
