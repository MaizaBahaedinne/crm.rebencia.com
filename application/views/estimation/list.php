<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">
      <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2">
          <h4 class="mb-0">Estimations</h4>
          <span class="badge bg-info-subtle text-info">Liste</span>
        </div>
        <div>
          <a href="<?= base_url('estimation'); ?>" class="btn btn-primary btn-sm"><i class="ri-add-line me-1"></i>Nouvelle estimation</a>
        </div>
      </div>

  <form class="row g-2 mb-3" method="get">
    <div class="col-md-3">
      <select name="statut" class="form-select">
        <option value="">-- Statut --</option>
        <?php foreach($allowed_status as $s): ?>
          <option value="<?= $s; ?>" <?= ($this->input->get('statut')===$s)?'selected':''; ?>><?= $s; ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-3">
      <select name="zone_id" class="form-select">
        <option value="">-- Zone --</option>
        <?php foreach($zones as $z): ?>
          <option value="<?= $z->id; ?>" <?= ($this->input->get('zone_id')==$z->id)?'selected':''; ?>><?= $z->nom; ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-3">
      <button class="btn btn-outline-secondary">Filtrer</button>
    </div>
  </form>

  <div class="card">
    <div class="card-body table-responsive p-0">
      <table class="table table-striped table-sm mb-0 align-middle">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Date</th>
            <th>Zone</th>
            <th>Surface (m²)</th>
            <th>Valeur min</th>
            <th>Valeur moy</th>
            <th>Valeur max</th>
            <th>Loyer (TND/mois)</th>
            <th>Rentabilité (%)</th>
            <th>Agent</th>
            <th>Statut</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php if(empty($estimations)): ?>
            <tr><td colspan="9" class="text-center text-muted py-4">Aucune estimation.</td></tr>
          <?php else: foreach($estimations as $e): ?>
            <tr>
              <td><a href="<?= base_url('estimation/resultat/'.$e['id']); ?>">#<?= $e['id']; ?></a></td>
              <td><?= date('d/m/Y', strtotime($e['created_at'])); ?></td>
              <td><?= $e['zone_nom']; ?></td>
              <td><?= $e['surface_habitable']; ?></td>
              <td><?= isset($e['valeur_min_estimee'])?number_format($e['valeur_min_estimee'],0,'',' '):'-'; ?></td>
              <td><?= number_format($e['valeur_estimee'],0,'',' '); ?></td>
              <td><?= isset($e['valeur_max_estimee'])?number_format($e['valeur_max_estimee'],0,'',' '):'-'; ?></td>
              <td><?= number_format($e['loyer_potentiel'],0,'',' '); ?></td>
              <td><?= number_format($e['rentabilite'],2,',',' '); ?></td>
              <td>
                <?php if(!empty($e['agent_id'])): ?>
                  <?php $agent = isset($agents[$e['agent_id']]) ? $agents[$e['agent_id']] : null; ?>
                  <?= $agent ? htmlspecialchars($agent['display_name'] ?? $agent['user_login'] ?? $e['agent_id']) : 'Agent #'.$e['agent_id']; ?>
                <?php else: ?>
                  <span class="text-muted">-</span>
                <?php endif; ?>
              </td>
                <!-- Statut supprimé -->
              <td class="text-end">
                <div class="btn-group btn-group-sm">
                  <a class="btn btn-outline-success" href="<?= base_url('estimation/statut/'.$e['id'].'/valide'); ?>" title="Valider">✔</a>
                  <a class="btn btn-outline-danger" href="<?= base_url('estimation/statut/'.$e['id'].'/rejete'); ?>" title="Rejeter">✖</a>
                </div>
              </td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
    </div>
  </div>
</div>
