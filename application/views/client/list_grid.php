<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0"><?= $pageTitle ?? 'Clients CRM' ?></h4>
                <div class="page-title-right">
                    <a href="<?= base_url('client/add') ?>" class="btn btn-primary">
                        <i class="ri-add-line align-bottom me-1"></i> Nouveau client
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <?php if(!empty($clients)): ?>
            <?php foreach($clients as $c): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title mb-2 text-truncate">
                                <?= htmlspecialchars(isset($c['nom']) ? $c['nom'] : ($c->nom ?? 'Client')) ?>
                            </h5>
                            <p class="mb-1"><strong>Email :</strong> 
                                <?= htmlspecialchars(isset($c['email']) ? $c['email'] : ($c->email ?? 'Non renseigné')) ?>
                            </p>
                            <p class="mb-1"><strong>Téléphone :</strong> 
                                <?= htmlspecialchars(isset($c['telephone']) ? $c['telephone'] : ($c->telephone ?? 'Non renseigné')) ?>
                            </p>
                            <p class="mb-1"><strong>Adresse :</strong> 
                                <?= htmlspecialchars(isset($c['adresse']) ? $c['adresse'] : ($c->adresse ?? 'Non renseignée')) ?>
                            </p>
                            <div class="mt-3">
                                <a href="<?= base_url('client/edit/'.(isset($c['id']) ? $c['id'] : $c->id)); ?>" class="btn btn-sm btn-outline-primary">Modifier</a>
                                <a href="<?= base_url('client/delete/'.(isset($c['id']) ? $c['id'] : $c->id)); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer ce client ?')">Supprimer</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="ri-user-line display-4 text-muted mb-3"></i>
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
