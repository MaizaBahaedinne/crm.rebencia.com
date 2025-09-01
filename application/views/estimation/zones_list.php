<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">
      <div class="row mb-3">
        <div class="col"><h4 class="mb-0">Zones</h4></div>
        <div class="col text-end">
          <a href="<?= base_url('zones/create'); ?>" class="btn btn-primary"><i class="bx bx-plus"></i> Nouvelle zone</a>
        </div>
      </div>
      <div class="card mb-4">
        <div class="card-body">
          <div id="zones-map" style="height:400px;background:#f5f5f5;" class="rounded border mb-2"></div>
          <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
          <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
          <script>
          (function(){
            var map = L.map('zones-map').setView([36.8, 10.2], 11);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
              maxZoom: 19,
              attribution: '&copy; OpenStreetMap'
            }).addTo(map);
            var zones = <?php
              $jsZones = [];
              if(!empty($zones)) foreach($zones as $z) {
                $jsZones[] = [
                  'id' => $z->id,
                  'nom' => $z->nom,
                  'prix_m2_min' => $z->prix_m2_min,
                  'prix_m2_moyen' => $z->prix_m2_moyen,
                  'prix_m2_max' => $z->prix_m2_max,
                  'rendement_locatif_moyen' => $z->rendement_locatif_moyen,
                  'geometry' => $z->geometry,
                  'transport_score' => $z->transport_score,
                  'commodites_score' => $z->commodites_score,
                  'securite_score' => $z->securite_score
                ];
              }
              echo json_encode($jsZones);
            ?>;
            var bounds = [];
            zones.forEach(function(z) {
              if(z.geometry) {
                try {
                  var geo = JSON.parse(z.geometry);
                  var layer = L.geoJSON({type:'Feature',geometry:geo}, {
                    style: {color:'#0d6efd', weight:2, fillOpacity:0.15},
                    onEachFeature: function (feature, lyr) {
                      lyr.on('mouseover', function(e){
                        var popup = L.popup({closeButton:false,offset:[0,-10]})
                          .setLatLng(e.latlng)
                          .setContent('<b>'+z.nom+'</b><br>Prix m² moyen : '+z.prix_m2_moyen+' TND<br>Rendement : '+z.rendement_locatif_moyen+'%<br>Transp : '+z.transport_score+' | Commo : '+z.commodites_score+' | Sécu : '+z.securite_score)
                          .openOn(map);
                      });
                      lyr.on('mouseout', function(){ map.closePopup(); });
                    }
                  }).addTo(map);
                  if(layer.getBounds) bounds.push(layer.getBounds());
                } catch(e){}
              }
            });
            if(bounds.length>0) {
              var all = bounds[0];
              for(var i=1;i<bounds.length;i++) all = all.extend(bounds[i]);
              map.fitBounds(all);
            }
          })();
          </script>
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
