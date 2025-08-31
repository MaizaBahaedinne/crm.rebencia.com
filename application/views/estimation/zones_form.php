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
                <label class="form-label">Prix m² min</label>
                <input type="number" step="0.01" name="prix_m2_min" class="form-control" value="<?= isset($zone)?$zone->prix_m2_min:''; ?>">
              </div>
              <div class="col-md-3">
                <label class="form-label">Prix m² max</label>
                <input type="number" step="0.01" name="prix_m2_max" class="form-control" value="<?= isset($zone)?$zone->prix_m2_max:''; ?>">
              </div>
              <div class="col-md-3">
                <label class="form-label">Rendement locatif moyen (%)</label>
                <input type="number" step="0.01" name="rendement_locatif_moyen" class="form-control" value="<?= isset($zone)?$zone->rendement_locatif_moyen:''; ?>" required>
              </div>
              <div class="col-md-3">
                <label class="form-label">Score transports (0-5)</label>
                <input type="number" min="0" max="5" name="transport_score" class="form-control" value="<?= isset($zone)?$zone->transport_score:''; ?>">
              </div>
              <div class="col-md-3">
                <label class="form-label">Score commodités (0-5)</label>
                <input type="number" min="0" max="5" name="commodites_score" class="form-control" value="<?= isset($zone)?$zone->commodites_score:''; ?>">
              </div>
              <div class="col-md-3">
                <label class="form-label">Score sécurité (0-5)</label>
                <input type="number" min="0" max="5" name="securite_score" class="form-control" value="<?= isset($zone)?$zone->securite_score:''; ?>">
              </div>
              <div class="col-md-3">
                <label class="form-label">Latitude (opt.)</label>
                <input type="text" name="latitude" class="form-control" value="<?= isset($zone)?$zone->latitude:''; ?>">
              </div>
              <div class="col-md-3">
                <label class="form-label">Longitude (opt.)</label>
                <input type="text" name="longitude" class="form-control" value="<?= isset($zone)?$zone->longitude:''; ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label">Description transports</label>
                <textarea name="transport_description" class="form-control" rows="2"><?= isset($zone)?htmlspecialchars($zone->transport_description):''; ?></textarea>
              </div>
              <div class="col-md-6">
                <label class="form-label">Description commodités</label>
                <textarea name="commodites_description" class="form-control" rows="2"><?= isset($zone)?htmlspecialchars($zone->commodites_description):''; ?></textarea>
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
