<?php $this->load->view('includes/header'); ?>
<div class="main-content"><div class="page-content"><div class="container-fluid">
<h4 class="mb-3"><?= isset($client)?'Modifier client':'Nouveau client'; ?></h4>
<form method="post" action="<?= isset($client)? base_url('clients/save/'.$client['id']) : base_url('clients/save'); ?>" class="row g-3">
  <div class="col-md-4"><label class="form-label">Nom *</label><input name="nom" required class="form-control" value="<?= htmlspecialchars($client['nom']??''); ?>"></div>
  <div class="col-md-4"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="<?= htmlspecialchars($client['email']??''); ?>"></div>
  <div class="col-md-4"><label class="form-label">Téléphone</label><input name="telephone" class="form-control" value="<?= htmlspecialchars($client['telephone']??''); ?>"></div>
  <div class="col-md-3"><label class="form-label">Type</label><select name="type" class="form-select"><?php foreach(['acheteur','vendeur','locataire','bailleur'] as $t):?><option value="<?= $t; ?>" <?= (($client['type']??'')===$t)?'selected':'';?>><?= ucfirst($t);?></option><?php endforeach; ?></select></div>
  <div class="col-md-3"><label class="form-label">Statut</label><select name="statut" class="form-select"><?php foreach(['actif','inactif'] as $s):?><option value="<?= $s; ?>" <?= (($client['statut']??'')===$s)?'selected':'';?>><?= ucfirst($s);?></option><?php endforeach; ?></select></div>
  <div class="col-md-3"><label class="form-label">Origine</label><input name="origine" class="form-control" value="<?= htmlspecialchars($client['origine']??''); ?>" placeholder="Site, Réseau, Réf...">
  </div>
  <div class="col-12"><label class="form-label">Notes</label><textarea name="notes" rows="3" class="form-control"><?= htmlspecialchars($client['notes']??''); ?></textarea></div>
  <div class="col-12 text-end"><a href="<?= base_url('clients'); ?>" class="btn btn-light">Annuler</a> <button class="btn btn-primary">Enregistrer</button></div>
</form>
</div></div></div>
<?php $this->load->view('includes/footer'); ?>
