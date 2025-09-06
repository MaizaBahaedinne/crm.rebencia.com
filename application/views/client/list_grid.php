<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0"><?= $pageTitle ?? 'Clients CRM' ?></h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                                <li class="breadcrumb-item active">Clients CRM</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <!-- Filtres -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="get" class="row g-2 align-items-end">
                                <div class="col-md-3">
                                    <label class="form-label">Recherche</label>
                                    <input name="q" value="<?= htmlspecialchars($filters['q']??''); ?>" class="form-control" placeholder="Nom, email, téléphone...">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Type</label>
                                    <select name="type" class="form-select">
                                        <option value="">Tous types</option>
                                        <option value="Acheteur" <?= (isset($filters['type'])&&$filters['type']==='Acheteur')?'selected':'';?>>Acheteur</option>
                                        <option value="Vendeur" <?= (isset($filters['type'])&&$filters['type']==='Vendeur')?'selected':'';?>>Vendeur</option>
                                        <option value="Investisseur" <?= (isset($filters['type'])&&$filters['type']==='Investisseur')?'selected':'';?>>Investisseur</option>
                                        <option value="Locataire" <?= (isset($filters['type'])&&$filters['type']==='Locataire')?'selected':'';?>>Locataire</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Statut</label>
                                    <select name="statut" class="form-select">
                                        <option value="">Tous statuts</option>
                                        <option value="actif" <?= (isset($filters['statut'])&&$filters['statut']==='actif')?'selected':'';?>>Actif</option>
                                        <option value="inactif" <?= (isset($filters['statut'])&&$filters['statut']==='inactif')?'selected':'';?>>Inactif</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="ri-search-line align-bottom me-1"></i> Filtrer
                                    </button>
                                    <a href="<?= base_url('client/add') ?>" class="btn btn-success">
                                        <i class="ri-add-line align-bottom me-1"></i> Nouveau client
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste des clients -->
            <div class="row">
                <?php if(!empty($clients)): ?>
                    <?php foreach($clients as $c): ?>
                        <div class="col-xxl-3 col-lg-4 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-light text-primary rounded-3 fs-18">
                                                <i class="ri-user-line"></i>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="card-title mb-1">
                                                <?= htmlspecialchars(isset($c['nom']) ? $c['nom'] : ($c->nom ?? 'Client')) ?>
                                                <?= isset($c['prenom']) ? ' '.htmlspecialchars($c['prenom']) : (isset($c->prenom) ? ' '.htmlspecialchars($c->prenom) : '') ?>
                                            </h6>
                                            <?php if(isset($c['type_client']) || isset($c->type_client)): ?>
                                                <span class="badge bg-primary-subtle text-primary fs-11">
                                                    <?= htmlspecialchars(isset($c['type_client']) ? $c['type_client'] : $c->type_client) ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <?php if(isset($c['email']) || isset($c->email)): ?>
                                            <p class="text-muted mb-1">
                                                <i class="ri-mail-line align-bottom me-1"></i>
                                                <?= htmlspecialchars(isset($c['email']) ? $c['email'] : $c->email) ?>
                                            </p>
                                        <?php endif; ?>
                                        
                                        <?php if(isset($c['contact_principal']) || isset($c->contact_principal)): ?>
                                            <p class="text-muted mb-1">
                                                <i class="ri-phone-line align-bottom me-1"></i>
                                                <?= htmlspecialchars(isset($c['contact_principal']) ? $c['contact_principal'] : $c->contact_principal) ?>
                                            </p>
                                        <?php endif; ?>
                                        
                                        <?php if(isset($c['ville']) || isset($c->ville)): ?>
                                            <p class="text-muted mb-3">
                                                <i class="ri-map-pin-line align-bottom me-1"></i>
                                                <?= htmlspecialchars(isset($c['ville']) ? $c['ville'] : $c->ville) ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="hstack gap-2">
                                        <a href="<?= base_url('client/edit/'.(isset($c['id']) ? $c['id'] : $c->id)); ?>" class="btn btn-soft-primary btn-sm">
                                            <i class="ri-edit-box-line align-bottom"></i>
                                        </a>
                                        <a href="<?= base_url('client/delete/'.(isset($c['id']) ? $c['id'] : $c->id)); ?>" 
                                           class="btn btn-soft-danger btn-sm" 
                                           onclick="return confirm('Supprimer ce client ?')">
                                            <i class="ri-delete-bin-5-line align-bottom"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <div class="avatar-xl mx-auto mb-4">
                                    <div class="avatar-title bg-light text-muted rounded-4 fs-2">
                                        <i class="ri-user-line"></i>
                                    </div>
                                </div>
                                <h5 class="text-muted">Aucun client trouvé</h5>
                                <p class="text-muted mb-4">Commencez par ajouter votre premier client au CRM.</p>
                                <a href="<?= base_url('client/add') ?>" class="btn btn-primary">
                                    <i class="ri-add-line align-bottom me-1"></i> Ajouter un client
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>
