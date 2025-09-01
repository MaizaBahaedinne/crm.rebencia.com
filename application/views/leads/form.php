<?php $this->load->view('includes/header'); ?>
<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">
      <div class="row"><div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
          <h4 class="mb-sm-0"><?= isset($lead)?'Modifier lead':'Nouveau lead'; ?></h4>
          <div class="page-title-right">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="<?= base_url('leads'); ?>">Leads</a></li>
              <li class="breadcrumb-item active"><?= isset($lead)?'Modifier':'Nouveau'; ?></li>
            </ol>
          </div>
        </div>
      </div></div>

      <?php if(validation_errors()): ?><div class="alert alert-danger small mb-3"><?= validation_errors(); ?></div><?php endif; ?>

      <form method="post" action="<?= isset($lead)? base_url('leads/save/'.$lead['id']) : base_url('leads/save'); ?>" class="needs-validation" novalidate>
        <div class="card">
          <div class="card-body row g-3">
            <div class="col-md-4">
              <label class="form-label">Utilisateur WordPress (Client)</label>
              <select name="wp_user_id" class="form-select" required data-choices data-choices-search="true">
                <option value="">-- Sélectionner --</option>
                <?php if(!empty($wp_clients)): foreach($wp_clients as $c): $cid=(int)$c['user_id']; ?>
                  <option value="<?= $cid; ?>" <?= (!empty($lead['wp_user_id']) && (int)$lead['wp_user_id']===$cid)?'selected':''; ?>>#<?= $cid; ?> - <?= htmlspecialchars($c['full_name']); ?> (<?= htmlspecialchars($c['user_email']); ?>)</option>
                <?php endforeach; endif; ?>
              </select>
            </div>
            <div class="col-md-2">
              <label class="form-label">Type *</label>
              <select name="type" class="form-select" required>
                <?php foreach(['acheteur'=>'Acheteur','locataire'=>'Locataire'] as $k=>$v): ?>
                  <option value="<?= $k; ?>" <?= (($lead['type'] ?? '')===$k)?'selected':''; ?>><?= $v; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-2">
              <label class="form-label">Statut *</label>
              <select name="status" class="form-select" required>
                <?php foreach(['nouveau'=>'Nouveau','qualifie'=>'Qualifié','en_cours'=>'En cours','converti'=>'Converti','perdu'=>'Perdu'] as $k=>$v): ?>
                  <option value="<?= $k; ?>" <?= (($lead['status'] ?? '')===$k)?'selected':''; ?>><?= $v; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-2">
              <label class="form-label">Score</label>
              <input type="number" name="lead_score" class="form-control" value="<?= htmlspecialchars($lead['lead_score'] ?? ''); ?>">
            </div>
            <div class="col-md-6">
              <label class="form-label">Prénom</label>
              <input name="prenom" class="form-control" value="<?= htmlspecialchars($lead['prenom'] ?? ''); ?>">
            </div>
            <div class="col-md-6">
              <label class="form-label">Nom</label>
              <input name="nom" class="form-control" value="<?= htmlspecialchars($lead['nom'] ?? ''); ?>">
            </div>
            <!-- Bloc identité client -->
            <div class="col-12">
              <div class="card border border-primary mb-3">
                <div class="card-header bg-primary text-white">
                  Identité du client
                </div>
                <div class="card-body row g-3 align-items-end">
                  <div class="col-md-3">
                    <label class="form-label">Type de client *</label>
                    <select name="client_type" id="client_type" class="form-select" required>
                      <option value="">-- Sélectionner --</option>
                      <option value="personne" <?= (($lead['client_type'] ?? '')==='personne')?'selected':''; ?>>Personne physique</option>
                      <option value="societe" <?= (($lead['client_type'] ?? '')==='societe')?'selected':''; ?>>Société</option>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <label class="form-label">Type d'identité *</label>
                    <select name="client_identite_type" id="client_identite_type" class="form-select" required>
                      <option value="">-- Sélectionner --</option>
                      <option value="cin" <?= (($lead['client_identite_type'] ?? '')==='cin')?'selected':''; ?>>CIN</option>
                      <option value="passeport" <?= (($lead['client_identite_type'] ?? '')==='passeport')?'selected':''; ?>>Passeport</option>
                      <option value="titre_sejour" <?= (($lead['client_identite_type'] ?? '')==='titre_sejour')?'selected':''; ?>>Titre de séjour</option>
                      <option value="rc" <?= (($lead['client_identite_type'] ?? '')==='rc')?'selected':''; ?>>RC</option>
                      <option value="mf" <?= (($lead['client_identite_type'] ?? '')==='mf')?'selected':''; ?>>Matricule fiscal</option>
                      <option value="autre" <?= (($lead['client_identite_type'] ?? '')==='autre')?'selected':''; ?>>Autre</option>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <label class="form-label">Numéro d'identité *</label>
                    <input name="client_identite_numero" class="form-control" value="<?= htmlspecialchars($lead['client_identite_numero'] ?? ''); ?>" required>
                  </div>
                  <div class="col-md-3">
                    <label class="form-label">Date de délivrance</label>
                    <input type="date" name="client_identite_date" class="form-control" value="<?= htmlspecialchars($lead['client_identite_date'] ?? ''); ?>">
                  </div>
                  <div class="col-md-3">
                    <label class="form-label">Lieu de délivrance</label>
                    <input name="client_identite_lieu" class="form-control" value="<?= htmlspecialchars($lead['client_identite_lieu'] ?? ''); ?>">
                  </div>
                  <div class="col-md-3">
                    <label class="form-label">Date d'expiration</label>
                    <input type="date" name="client_identite_date_expiration" class="form-control" value="<?= htmlspecialchars($lead['client_identite_date_expiration'] ?? ''); ?>">
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($lead['email'] ?? ''); ?>">
            </div>
            <div class="col-md-4">
              <label class="form-label">Téléphone</label>
              <input name="telephone" class="form-control" value="<?= htmlspecialchars($lead['telephone'] ?? ''); ?>">
            </div>
            <div class="col-md-4">
              <label class="form-label">Téléphone alt.</label>
              <input name="telephone_alt" class="form-control" value="<?= htmlspecialchars($lead['telephone_alt'] ?? ''); ?>">
            </div>
            <div class="col-md-3">
              <label class="form-label">Whatsapp</label>
              <input name="whatsapp" class="form-control" value="<?= htmlspecialchars($lead['whatsapp'] ?? ''); ?>">
            </div>
            <div class="col-md-3">
              <label class="form-label">Pays</label>
              <input name="pays" class="form-control" value="<?= htmlspecialchars($lead['pays'] ?? ''); ?>">
            </div>
            <div class="col-md-3">
              <label class="form-label">Ville</label>
              <input name="ville" class="form-control" value="<?= htmlspecialchars($lead['ville'] ?? ''); ?>">
            </div>
            <div class="col-md-3">
              <label class="form-label">Code postal</label>
              <input name="code_postal" class="form-control" value="<?= htmlspecialchars($lead['code_postal'] ?? ''); ?>">
            </div>
            <div class="col-md-12">
              <label class="form-label">Adresse</label>
              <input name="adresse" class="form-control" value="<?= htmlspecialchars($lead['adresse'] ?? ''); ?>">
            </div>
            <div class="col-12">
              <label class="form-label">Notes interne</label>
              <textarea name="notes_interne" rows="3" class="form-control"><?= htmlspecialchars($lead['notes_interne'] ?? ''); ?></textarea>
            </div>
          </div>
          <div class="card-footer text-end">
            <a href="<?= base_url('leads'); ?>" class="btn btn-light">Annuler</a>
            <button class="btn btn-primary" type="submit">Enregistrer</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php $this->load->view('includes/footer'); ?>
