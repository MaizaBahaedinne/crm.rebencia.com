<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="d-flex align-items-center flex-wrap gap-2 mb-3">
            <h4 class="mb-0">Résultat estimation</h4>
            <span class="badge bg-success-subtle text-success">Calcul</span>
          </div>
      <a href="<?= base_url('estimation'); ?>" class="btn btn-outline-secondary btn-sm mb-3">Nouvelle estimation</a>
  <div class="row g-3">
    <div class="col-md-4">
          <div class="card border-success">
            <div class="card-body">
      <h6 class="text-muted text-uppercase">Valeur estimée (moyenne)</h6>
      <h3><?= number_format($property['valeur_estimee'],0,'',' '); ?> TND</h3>
      <small class="text-muted d-block">Fourchette: <?= number_format($property['valeur_min_estimee'],0,'',' '); ?> - <?= number_format($property['valeur_max_estimee'],0,'',' '); ?> TND</small>
      <small class="text-muted">Coef global: <?= $property['coef_global']; ?></small>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card border-info">
            <div class="card-body">
              <h6 class="text-muted text-uppercase">Loyer potentiel</h6>
              <h3><?= number_format($property['loyer_potentiel'],0,'',' '); ?> TND / mois</h3>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card border-warning">
            <div class="card-body">
              <h6 class="text-muted text-uppercase">Rentabilité brute approx.</h6>
              <h3><?= number_format($property['rentabilite'],2,',',' '); ?> %</h3>
            </div>
          </div>
        </div>
      </div>


      <div class="row mt-4 g-3">
        <div class="col-lg-6">
          <div class="card h-100">
            <div class="card-header d-flex align-items-center gap-2">
              <i class="bi bi-geo-alt-fill text-primary"></i>
              <span class="fw-semibold">Localisation & Adresse</span>
            </div>
            <div class="card-body">
              <div id="map-result" style="height:220px;background:#f5f5f5;" class="rounded border mb-3"></div>
              <div class="mb-2">
                <i class="bi bi-geo-alt"></i>
                <span class="fw-semibold">Adresse :</span><br>
                <span class="text-muted">
                  <?= ($property['adresse_numero'] ? $property['adresse_numero'].' ' : '') ?><?= $property['adresse_rue']; ?><br>
                  <?= $property['adresse_cp']; ?> <?= $property['adresse_ville']; ?><br>
                  <?= $property['adresse_pays']; ?>
                </span>
              </div>
              <div class="row g-2">
                <div class="col-6"><i class="bi bi-globe2"></i> <span class="text-muted">Lat:</span> <?= $property['latitude']; ?></div>
                <div class="col-6"><i class="bi bi-globe2"></i> <span class="text-muted">Lng:</span> <?= $property['longitude']; ?></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="card h-100">
            <div class="card-header d-flex align-items-center gap-2">
              <i class="bi bi-info-circle-fill text-info"></i>
              <span class="fw-semibold">Détails du bien</span>
            </div>
            <div class="card-body">
              <div class="row g-2 mb-2">
                <div class="col-6"><span class="text-muted">Zone :</span> <?= $property['zone_nom']; ?></div>
                <div class="col-6"><span class="text-muted">Type :</span> <?= $property['type_bien']; ?></div>
                <div class="col-6"><span class="text-muted">Surface hab. :</span> <?= $property['surface_habitable']; ?> m²</div>
                <div class="col-6"><span class="text-muted">Terrain :</span> <?= $property['surface_terrain']; ?> m²</div>
                <div class="col-6"><span class="text-muted">Pièces :</span> <?= $property['nb_pieces']; ?></div>
                <div class="col-6"><span class="text-muted">Étage :</span> <?= $property['etage']; ?></div>
                <div class="col-6"><span class="text-muted">État :</span> <?= $property['etat_general']; ?></div>
                <div class="col-6"><span class="text-muted">Année :</span> <?= $property['annee_construction']; ?></div>
                <div class="col-6"><span class="text-muted">Prix demandé :</span> <?= number_format($property['prix_demande'],0,'',' '); ?> TND</div>
                <div class="col-6"><span class="text-muted">Charges :</span> <?= number_format($property['charges'],0,'',' '); ?> TND</div>
                <div class="col-6"><span class="text-muted">Taxes :</span> <?= number_format($property['taxes'],0,'',' '); ?> TND</div>
                <div class="col-6"><span class="text-muted">Classe énergie :</span> <?= $property['energie_classe']; ?></div>
                <div class="col-6"><span class="text-muted">Titre foncier :</span> <?= $property['titre_foncier']; ?></div>
              </div>
              <div class="mb-2"><span class="text-muted">Équipements :</span> <?= $property['equipements']; ?></div>
              <div class="mb-2"><span class="text-muted">Extérieur :</span> <?= $property['type_exterieur']; ?> <?= $property['jardin']==='oui'?'(Jardin)':''; ?> <?= $property['piscine']==='oui'?'(Piscine)':''; ?></div>
              <div class="mb-2"><span class="text-muted">Sécurité :</span> <?= $property['securite']; ?> <?= $property['syndic']==='oui'?'(Syndic)':''; ?></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Carte Leaflet -->
      <script src="<?= base_url('assets/libs/leaflet/leaflet.js'); ?>"></script>
      <link rel="stylesheet" href="<?= base_url('assets/libs/leaflet/leaflet.css'); ?>" />
      <script>
      (function(){
        var lat = parseFloat('<?= $property['latitude'] ?>');
        var lng = parseFloat('<?= $property['longitude'] ?>');
        if(!isNaN(lat) && !isNaN(lng) && lat && lng) {
          var map = L.map('map-result').setView([lat, lng], 15);
          L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
          }).addTo(map);
          L.marker([lat, lng]).addTo(map);
        } else {
          document.getElementById('map-result').innerHTML = '<span class="text-danger">Coordonnées non renseignées</span>';
        }
      })();
      </script>


      <!-- Carte Leaflet -->
      <div class="card mt-4">
        <div class="card-header">Localisation sur la carte</div>
        <div class="card-body">
          <div id="map-result" style="height:300px;background:#f5f5f5;" class="rounded border mb-2"></div>
          <script src="<?= base_url('assets/libs/leaflet/leaflet.js'); ?>"></script>
          <link rel="stylesheet" href="<?= base_url('assets/libs/leaflet/leaflet.css'); ?>" />
          <script>
          (function(){
            var lat = parseFloat('<?= $property['latitude'] ?>');
            var lng = parseFloat('<?= $property['longitude'] ?>');
            if(!isNaN(lat) && !isNaN(lng) && lat && lng) {
              var map = L.map('map-result').setView([lat, lng], 15);
              L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
              }).addTo(map);
              L.marker([lat, lng]).addTo(map);
            } else {
              document.getElementById('map-result').innerHTML = '<span class="text-danger">Coordonnées non renseignées</span>';
            }
          })();
          </script>
        </div>
      </div>

      <div class="card mt-4">
        <div class="card-header">Photos</div>
        <div class="card-body">
          <div class="row g-2">
            <?php if(!empty($property['photos'])): foreach($property['photos'] as $p): ?>
              <div class="col-6 col-md-3">
                <a href="<?= base_url($p['file']); ?>" target="_blank"><img src="<?= base_url($p['file']); ?>" class="img-fluid rounded border" alt="photo"></a>
              </div>
            <?php endforeach; else: ?>
              <p class="text-muted mb-0">Aucune photo.</p>
            <?php endif; ?>
          </div>
        </div>
      </div>



      <div class="row mt-4 g-3">
        <div class="col-lg-6">
          <div class="card h-100">
            <div class="card-header d-flex align-items-center gap-2">
              <i class="bi bi-currency-dollar text-success"></i>
              <span class="fw-semibold">Proposition agence</span>
            </div>
            <div class="card-body">
              <form method="post" action="<?= base_url('estimation/proposition/'.$property['id']); ?>" class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Montant proposé (TND)</label>
                  <input type="number" step="0.01" name="proposition_agence" class="form-control" value="<?= htmlspecialchars($property['proposition_agence'] ?? '', ENT_QUOTES); ?>">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Commentaire</label>
                  <textarea name="proposition_commentaire" class="form-control" rows="2"><?= htmlspecialchars($property['proposition_commentaire'] ?? ''); ?></textarea>
                </div>
                <div class="col-12 text-end">
                  <button class="btn btn-primary" type="submit">Enregistrer la proposition</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="card h-100">
            <div class="card-header d-flex align-items-center gap-2">
              <i class="bi bi-check-circle-fill text-success"></i>
              <span class="fw-semibold">Statut du dossier</span>
            </div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center">
              <?php
                $badge = 'secondary';
                if($property['statut_dossier']==='valide') $badge='success';
                elseif($property['statut_dossier']==='rejete') $badge='danger';
                elseif($property['statut_dossier']==='en_cours') $badge='warning';
              ?>
              <span class="badge bg-<?= $badge; ?> fs-5 mb-3 text-capitalize"><?= $property['statut_dossier']; ?></span>
              <div class="d-flex gap-2">
                <a href="<?= base_url('estimation/statut/'.$property['id'].'/valide'); ?>" class="btn btn-success" onclick="return confirm('Valider cette estimation ?');"><i class="bi bi-check-lg"></i> Accepter</a>
                <a href="<?= base_url('estimation/statut/'.$property['id'].'/rejete'); ?>" class="btn btn-danger" onclick="return confirm('Rejeter cette estimation ?');"><i class="bi bi-x-lg"></i> Rejeter</a>
              </div>
            </div>
          </div>
        </div>
      </div>

        </div>
      </div>
    </div>
  </div>
</div>
