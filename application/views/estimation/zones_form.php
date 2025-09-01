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
            <div class="row mt-3">
              <div class="col-12">
                <label class="form-label">Délimitation sur la carte (optionnel)</label>
                <div id="zone-map" style="height:300px;background:#f5f5f5;" class="rounded border mb-2"></div>
                <textarea name="geometry" id="geometry" class="form-control d-none"><?= isset($zone)&&isset($zone->geometry)?htmlspecialchars($zone->geometry):''; ?></textarea>
                <small class="text-muted">Dessinez le contour de la zone (polygone). Les coordonnées seront enregistrées en GeoJSON.</small>
              </div>
            </div>
            <div class="mt-4">
              <button class="btn btn-primary" type="submit">Enregistrer</button>
            </div>
          </form>
          <!-- Leaflet + Leaflet.draw -->
          <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
          <link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css" />
          <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
          <script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>
          <script>
          (function(){
            var map = L.map('zone-map').setView([36.8, 10.2], 11);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
              maxZoom: 19,
              attribution: '&copy; OpenStreetMap'
            }).addTo(map);
            var drawnItems = new L.FeatureGroup();
            map.addLayer(drawnItems);
            var geometryInput = document.getElementById('geometry');
            // Si déjà une géométrie, l'afficher
            if(geometryInput.value) {
              try {
                var geo = JSON.parse(geometryInput.value);
                var layer = L.geoJSON(geo).getLayers()[0];
                if(layer) {
                  drawnItems.addLayer(layer);
                  map.fitBounds(layer.getBounds());
                }
              } catch(e) {}
            }
            var drawControl = new L.Control.Draw({
              edit: { featureGroup: drawnItems },
              draw: { polygon: true, polyline: false, rectangle: false, circle: false, marker: false, circlemarker: false }
            });
            map.addControl(drawControl);
            map.on(L.Draw.Event.CREATED, function (e) {
              drawnItems.clearLayers();
              drawnItems.addLayer(e.layer);
              geometryInput.value = JSON.stringify(e.layer.toGeoJSON().geometry);
            });
            map.on(L.Draw.Event.EDITED, function (e) {
              var layers = e.layers.getLayers();
              if(layers.length>0) geometryInput.value = JSON.stringify(layers[0].toGeoJSON().geometry);
            });
            map.on(L.Draw.Event.DELETED, function (e) {
              geometryInput.value = '';
            });
          })();
          </script>
        </div>
      </div>
    </div>
  </div>
</div>
