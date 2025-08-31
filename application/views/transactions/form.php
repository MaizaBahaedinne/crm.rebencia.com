<?php $this->load->view('includes/header'); ?>
<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
            <h4 class="mb-sm-0"><?= isset($transaction)?'Modifier transaction':'Nouvelle transaction'; ?></h4>
            <div class="page-title-right">
              <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="<?= base_url('transactions'); ?>">Transactions</a></li>
                <li class="breadcrumb-item active"><?= isset($transaction)?'Modifier':'Nouvelle'; ?></li>
              </ol>
            </div>
          </div>
        </div>
      </div>

      <form method="post" action="<?= isset($transaction)? base_url('transactions/save/'.$transaction['id']) : base_url('transactions/save'); ?>" class="needs-validation" novalidate>
        <div class="card">
          <div class="card-body row g-3">
            <div class="col-md-5">
              <label class="form-label">Propriété (Houzez)</label>
              <select name="property_id" class="form-select" data-choices data-choices-search-false>
                <option value="">-- Sélectionner --</option>
                <?php if(!empty($properties)):
                  foreach($properties as $p): $sel = (!empty($transaction['property_id']) && (int)$transaction['property_id']===(int)$p['ID']) ? 'selected' : ''; ?>
                    <option value="<?= $p['ID']; ?>" <?= $sel; ?>>#<?= $p['ID']; ?> - <?= htmlspecialchars(mb_strimwidth($p['post_title'],0,70,'…','UTF-8')); ?></option>
                <?php endforeach; endif; ?>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Titre *</label>
              <input name="titre" required class="form-control" value="<?= htmlspecialchars($transaction['titre'] ?? '') ?>">
            </div>
            <div class="col-md-2">
              <label class="form-label">Type *</label>
              <select name="type" class="form-select" required id="tx-type">
                <?php $types=['vente'=>'Vente','location'=>'Location']; foreach($types as $k=>$v): ?>
                  <option value="<?= $k; ?>" <?= (($transaction['type'] ?? '')===$k)?'selected':''; ?>><?= $v; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Commercial</label>
              <input name="commercial" class="form-control" value="<?= htmlspecialchars($transaction['commercial'] ?? '') ?>">
            </div>
            <div class="col-md-3">
              <label class="form-label">Montant (€)</label>
              <input type="number" step="0.01" name="montant" class="form-control" value="<?= htmlspecialchars($transaction['montant'] ?? '') ?>">
            </div>
            <div class="col-md-3">
              <label class="form-label">Statut *</label>
              <select name="statut" class="form-select">
                <?php foreach(['nouveau','actif','cloture','annule'] as $s): ?>
                  <option value="<?= $s; ?>" <?= (($transaction['statut'] ?? '')===$s)?'selected':''; ?>><?= ucfirst($s); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Date clôture</label>
              <input type="date" name="date_cloture" class="form-control" value="<?= htmlspecialchars($transaction['date_cloture'] ?? '') ?>">
            </div>
            <div class="col-12">
              <label class="form-label">Notes</label>
              <textarea name="notes" rows="3" class="form-control"><?= htmlspecialchars($transaction['notes'] ?? '') ?></textarea>
            </div>
          </div>
          <div class="card-footer text-end">
            <a href="<?= base_url('transactions'); ?>" class="btn btn-light">Annuler</a>
            <button class="btn btn-primary" type="submit">Enregistrer</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php $this->load->view('includes/footer'); ?>
