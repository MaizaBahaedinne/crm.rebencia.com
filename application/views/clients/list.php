<?php $this->load->view('includes/header'); ?>
<div class="main-content"><div class="page-content"><div class="container-fluid">
<div class="d-flex justify-content-between align-items-center mb-3"><h4 class="mb-0">Clients (Houzez)</h4>
<span class="text-muted small">Source : utilisateurs WordPress</span></div>
<form method="get" class="row g-2 mb-3">
  <div class="col-md-3"><input name="q" value="<?= htmlspecialchars($filters['q']??''); ?>" class="form-control" placeholder="Recherche (nom, email, téléphone)..."></div>
  <div class="col-md-2"><select name="role" class="form-select"><option value="">Rôle</option><?php foreach(['buyer','owner','seller','agency','agent'] as $r):?><option value="<?= $r; ?>" <?= (isset($filters['role'])&&$filters['role']===$r)?'selected':'';?>><?= ucfirst($r);?></option><?php endforeach;?></select></div>
  <div class="col-md-2"><select name="statut" class="form-select"><option value="">Statut</option><?php foreach(['actif','inactif','pending'] as $s):?><option value="<?= $s; ?>" <?= (isset($filters['statut'])&&$filters['statut']===$s)?'selected':'';?>><?= ucfirst($s);?></option><?php endforeach;?></select></div>
  <div class="col-md-2"><button class="btn btn-outline-primary w-100">Filtrer</button></div>
</form>
<div class="card"><div class="card-body table-responsive">
<table class="table table-hover align-middle"><thead class="table-light"><tr><th>Nom complet</th><th>Email</th><th>Téléphone</th><th>Rôle</th><th>Statut</th></tr></thead><tbody>
<?php if(!empty($clients)): foreach($clients as $c): ?>
<tr>
  <td><?= htmlspecialchars(trim(($c['prenom']??'').' '.($c['nom']??'')));?></td>
  <td><?= htmlspecialchars($c['user_email']??$c['email']??''); ?></td>
  <td><?= htmlspecialchars($c['telephone']??''); ?></td>
  <td><span class="badge bg-info-subtle text-info"><?= htmlspecialchars($c['role_houzez']??'—'); ?></span></td>
  <td><span class="badge bg-<?= (isset($c['statut_compte']) && $c['statut_compte']==='actif')?'success':'secondary'; ?>"><?= htmlspecialchars($c['statut_compte']??''); ?></span></td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="5" class="text-center text-muted">Aucun client</td></tr>
<?php endif; ?>
</tbody></table>
</div></div>
</div></div></div>
<?php $this->load->view('includes/footer'); ?>
