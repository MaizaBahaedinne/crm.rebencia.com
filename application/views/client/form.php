<div class="container-fluid">
    <form method="post" action="<?= isset($client) ? base_url('client/update/'.$client->id) : base_url('client/add') ?>" class="row g-3" enctype="multipart/form-data">
        
        <!-- Agence et Agent -->
        <div class="col-md-6">
            <select name="agency_id" class="form-select" required>
                <option value="">Sélectionner une agence</option>
                <?php if(isset($agencies) && !empty($agencies)): foreach($agencies as $agency): ?>
                    <option value="<?= $agency->id ?>" <?= (isset($client) && $client->agency_id==$agency->id)?'selected':''; ?>>
                        <?= htmlspecialchars($agency->nom ?? $agency->name ?? $agency->libelle) ?>
                    </option>
                <?php endforeach; endif; ?>
            </select>
        </div>
        
        <div class="col-md-6">
            <select name="agent_id" class="form-select" required>
                <option value="">Sélectionner un agent</option>
                <?php if(isset($agents) && !empty($agents)): foreach($agents as $agent): ?>
                    <option value="<?= $agent->id ?>" <?= (isset($client) && $client->agent_id==$agent->id)?'selected':''; ?>>
                        <?= htmlspecialchars($agent->nom ?? $agent->name ?? $agent->prenom) ?>
                    </option>
                <?php endforeach; endif; ?>
            </select>
        </div>

        <div class="col-md-6">
            <input type="text" name="nom" class="form-control" placeholder="Nom complet / Raison sociale" value="<?= isset($client) ? htmlspecialchars($client->nom) : '' ?>" required>
        </div>
        <div class="col-md-6">
            <input type="text" name="prenom" class="form-control" placeholder="Prénom" value="<?= isset($client) ? htmlspecialchars($client->prenom) : '' ?>">
        </div>
        <div class="col-md-4">
            <select name="type_client" class="form-select" required>
                <option value="">Type de client</option>
                <option value="Acheteur" <?= (isset($client) && $client->type_client=='Acheteur')?'selected':''; ?>>Acheteur</option>
                <option value="Vendeur" <?= (isset($client) && $client->type_client=='Vendeur')?'selected':''; ?>>Vendeur</option>
                <option value="Investisseur" <?= (isset($client) && $client->type_client=='Investisseur')?'selected':''; ?>>Investisseur</option>
                <option value="Locataire" <?= (isset($client) && $client->type_client=='Locataire')?'selected':''; ?>>Locataire</option>
            </select>
        </div>
        <div class="col-md-4">
            <select name="identite_type" class="form-select">
                <option value="">Type d'identité</option>
                <option value="CIN" <?= (isset($client) && $client->identite_type=='CIN')?'selected':''; ?>>CIN</option>
                <option value="Passeport" <?= (isset($client) && $client->identite_type=='Passeport')?'selected':''; ?>>Passeport</option>
                <option value="Titre de séjour" <?= (isset($client) && $client->identite_type=='Titre de séjour')?'selected':''; ?>>Titre de séjour</option>
                <option value="Registre de commerce" <?= (isset($client) && $client->identite_type=='Registre de commerce')?'selected':''; ?>>Registre de commerce</option>
            </select>
        </div>
        <div class="col-md-4">
            <input type="text" name="identite_num" class="form-control" placeholder="Numéro d'identité / RC" value="<?= isset($client) ? htmlspecialchars($client->identite_num) : '' ?>">
        </div>
        <div class="col-md-4">
            <input type="file" name="identite_doc" class="form-control" accept="application/pdf,image/*">
            <?php if(isset($client) && !empty($client->identite_doc)): ?>
                <a href="<?= base_url('uploads/clients/'.$client->identite_doc) ?>" target="_blank">Document existant</a>
            <?php endif; ?>
        </div>
        <div class="col-md-4">
            <input type="email" name="email" class="form-control" placeholder="Email" value="<?= isset($client) ? htmlspecialchars($client->email) : '' ?>" required>
        </div>
        <div class="col-md-4">
            <input type="text" name="mobile" class="form-control" placeholder="Mobile" value="<?= isset($client) ? htmlspecialchars($client->mobile) : '' ?>" required>
        </div>
        <div class="col-md-4">
            <input type="text" name="whatsapp" class="form-control" placeholder="WhatsApp" value="<?= isset($client) ? htmlspecialchars($client->whatsapp) : '' ?>">
        </div>
        <div class="col-md-4">
            <input type="text" name="reseaux_sociaux" class="form-control" placeholder="Réseaux sociaux (Facebook, LinkedIn...)" value="<?= isset($client) ? htmlspecialchars($client->reseaux_sociaux) : '' ?>">
        </div>
        <div class="col-md-6">
            <input type="text" name="adresse" class="form-control" placeholder="Adresse complète" value="<?= isset($client) ? htmlspecialchars($client->adresse) : '' ?>">
        </div>
        <div class="col-md-6">
            <input type="text" name="source_info" class="form-control" placeholder="Source d'information (comment le client nous a connu)" value="<?= isset($client) ? htmlspecialchars($client->source_info) : '' ?>">
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <?php if(isset($client)): ?>
                <a href="<?= base_url('client/delete/'.$client->id) ?>" class="btn btn-danger ms-2" onclick="return confirm('Supprimer ce client ?')">Supprimer</a>
            <?php endif; ?>
        </div>
    </form>
</div>
