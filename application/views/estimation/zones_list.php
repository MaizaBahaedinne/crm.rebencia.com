<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">
      <div class="row mb-3">
        <div class="col"><h4 class="mb-0">Zones</h4></div>
        <div class="col text-end">
          <a href="<?= base_url('zones/create'); ?>" class="btn btn-primary"><i class="bx bx-plus"></i> Nouvelle zone</a>
        </div>
      </div>
      <div class="card">
        <div class="card-body table-responsive">
          <table class="table table-striped align-middle">
            <thead>
              <tr>
                <th>#</th><th>Nom</th><th>Prix m² min</th><th>Prix m² moy</th><th>Prix m² max</th><th>Rend %</th><th>Transp</th><th>Commo</th><th>Sécu</th><th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if(!empty($zones)): foreach($zones as $z): ?>
                <tr>
                  <td><?= $z->id; ?></td>
                  <td><?= htmlspecialchars($z->nom); ?></td>
                  <td><?= $z->prix_m2_min ? number_format($z->prix_m2_min,0,'',' ') : '-'; ?></td>
                  <td><?= number_format($z->prix_m2_moyen,0,'',' '); ?></td>
                  <td><?= $z->prix_m2_max ? number_format($z->prix_m2_max,0,'',' ') : '-'; ?></td>
                  <td><?= number_format($z->rendement_locatif_moyen,2,',',' '); ?>%</td>
                  <td><?= $z->transport_score; ?></td>
                  <td><?= $z->commodites_score; ?></td>
                  <td><?= $z->securite_score; ?></td>
                  <td class="text-nowrap">
                    <a href="<?= base_url('zones/edit/'.$z->id); ?>" class="btn btn-sm btn-outline-secondary">Éditer</a>
                    <a href="<?= base_url('zones/delete/'.$z->id); ?>" onclick="return confirm('Supprimer cette zone ?');" class="btn btn-sm btn-outline-danger">✖</a>
                  </td>
                </tr>
              <?php endforeach; else: ?>
                <tr><td colspan="7" class="text-center">Aucune zone.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
