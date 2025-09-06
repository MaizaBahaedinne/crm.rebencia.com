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
                                        <select name="agency_id" class="form-select" required>
                                            <option value="">Sélectionner une agence</option>
                                            <?php if(isset($agencies) && !empty($agencies)): foreach($agencies as $agency): ?>
                                                <?php 
                                                $agency_id = isset($agency['id']) ? $agency['id'] : (isset($agency->id) ? $agency->id : '');
                                                $agency_name = isset($agency['nom']) ? $agency['nom'] : (isset($agency['name']) ? $agency['name'] : (isset($agency['libelle']) ? $agency['libelle'] : (isset($agency->nom) ? $agency->nom : (isset($agency->name) ? $agency->name : (isset($agency->libelle) ? $agency->libelle : 'Agence')))));
                                                ?>
                                                <option value="<?= $agency_id ?>" <?= (isset($client) && isset($client->agency_id) && $client->agency_id==$agency_id)?'selected':''; ?>>
                                                    <?= htmlspecialchars($agency_name) ?>
                                                </option>
                                            <?php endforeach; endif; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label">Agent responsable <span class="text-danger">*</span></label>
                                        <select name="agent_id" class="form-select" required>
                                            <option value="">Sélectionner un agent</option>
                                            <?php if(isset($agents) && !empty($agents)): foreach($agents as $agent): ?>
                                                <?php 
                                                $agent_id = isset($agent['id']) ? $agent['id'] : (isset($agent->id) ? $agent->id : '');
                                                $agent_name = isset($agent['nom']) ? $agent['nom'] : (isset($agent['name']) ? $agent['name'] : (isset($agent['prenom']) ? $agent['prenom'] : (isset($agent->nom) ? $agent->nom : (isset($agent->name) ? $agent->name : (isset($agent->prenom) ? $agent->prenom : 'Agent')))));
                                                ?>
                                                <option value="<?= $agent_id ?>" <?= (isset($client) && isset($client->agent_id) && $client->agent_id==$agent_id)?'selected':''; ?>>
                                                    <?= htmlspecialchars($agent_name) ?>
                                                </option>
                                            <?php endforeach; endif; ?>
                                        </select>
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
                                            <option value="CIN" <?= (isset($client) && $client->identite_type=='CIN')?'selected':''; ?>>CIN</option>
                                            <option value="Passeport" <?= (isset($client) && $client->identite_type=='Passeport')?'selected':''; ?>>Passeport</option>
                                            <option value="Titre de séjour" <?= (isset($client) && $client->identite_type=='Titre de séjour')?'selected':''; ?>>Titre de séjour</option>
                                            <option value="Registre de commerce" <?= (isset($client) && $client->identite_type=='Registre de commerce')?'selected':''; ?>>Registre de commerce</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Numéro d'identité</label>
                                        <input type="text" name="identite_numero" class="form-control" placeholder="Numéro d'identité / RC" value="<?= isset($client) ? htmlspecialchars($client->identite_numero) : '' ?>">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Document d'identité</label>
                                        <input type="file" name="identite_doc" class="form-control" accept="application/pdf,image/*">
                                        <?php if(isset($client) && !empty($client->identite_doc)): ?>
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
                                        <input type="text" name="source" class="form-control" placeholder="Comment le client nous a-t-il connu ?" value="<?= isset($client) ? htmlspecialchars($client->source) : '' ?>">
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
                                    <?php if(isset($client)): ?>
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
