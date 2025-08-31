<?php $this->load->view('includes/header'); ?>
<div class="main-content"><div class="page-content"><div class="container-fluid">
<div class="d-flex justify-content-between align-items-center mb-3"><h4 class="mb-0">Clients (Houzez)</h4>
<span class="text-muted small">Source : utilisateurs WordPress</span></div>
<form method="get" class="row g-2 mb-3">
  <div class="col-md-3"><input name="q" value="<?= htmlspecialchars($filters['q']??''); ?>" class="form-control" placeholder="Recherche..."></div>
  <div class="col-md-2"><select name="type" class="form-select"><option value="">Type</option><?php foreach(['acheteur','vendeur','locataire','bailleur'] as $t):?><option value="<?= $t; ?>" <?= (isset($filters['type'])&&$filters['type']===$t)?'selected':'';?>><?= ucfirst($t);?></option><?php endforeach;?></select></div>
  <div class="col-md-2"><select name="statut" class="form-select"><option value="">Statut</option><?php foreach(['actif','inactif'] as $s):?><option value="<?= $s; ?>" <?= (isset($filters['statut'])&&$filters['statut']===$s)?'selected':'';?>><?= ucfirst($s);?></option><?php endforeach;?></select></div>
  <div class="col-md-2"><button class="btn btn-outline-primary w-100">Filtrer</button></div>
</form>
<div class="card"><div class="card-body table-responsive">
<table class="table table-hover align-middle"><thead class="table-light"><tr><th>Nom</th><th>Email</th><th>Téléphone</th><th>Type</th></tr></thead><tbody>
<?php if(!empty($clients)): foreach($clients as $c): ?>
<tr>
  <td><?= htmlspecialchars($c['nom']);?></td>
  <td><?= htmlspecialchars($c['email']??''); ?></td>
  <td><?= htmlspecialchars($c['telephone']??''); ?></td>
  <td><span class="badge bg-info-subtle text-info"><?= $c['type'] ?? '—'; ?></span></td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="5" class="text-center text-muted">Aucun client</td></tr>
<?php endif; ?>
</tbody></table>
</div></div>
</div></div></div>
<?php $this->load->view('includes/footer'); ?>
