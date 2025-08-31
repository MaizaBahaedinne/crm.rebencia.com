<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">
      <div class="row mb-3">
        <div class="col"><h4 class="mb-0"><?= isset($zone)?'Modifier zone':'Nouvelle zone'; ?></h4></div>
        <div class="col text-end">
          <a href="<?= base_url('zones'); ?>" class="btn btn-light">Retour</a>
        </div>
      </div>
      <div class="card">
        <div class="card-body">
          <form method="post">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Nom</label>
                <input type="text" name="nom" class="form-control" value="<?= isset($zone)?htmlspecialchars($zone->nom):''; ?>" required>
              </div>
              <div class="col-md-3">
                <label class="form-label">Prix m² moyen</label>
                <input type="number" step="0.01" name="prix_m2_moyen" class="form-control" value="<?= isset($zone)?$zone->prix_m2_moyen:''; ?>" required>
              </div>
              <div class="col-md-3">
                <label class="form-label">Rendement locatif moyen (%)</label>
                <input type="number" step="0.01" name="rendement_locatif_moyen" class="form-control" value="<?= isset($zone)?$zone->rendement_locatif_moyen:''; ?>" required>
              </div>
              <div class="col-md-3">
                <label class="form-label">Latitude (opt.)</label>
                <input type="text" name="latitude" class="form-control" value="<?= isset($zone)?$zone->latitude:''; ?>">
              </div>
              <div class="col-md-3">
                <label class="form-label">Longitude (opt.)</label>
                <input type="text" name="longitude" class="form-control" value="<?= isset($zone)?$zone->longitude:''; ?>">
              </div>
            </div>
            <div class="mt-4">
              <button class="btn btn-primary" type="submit">Enregistrer</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
