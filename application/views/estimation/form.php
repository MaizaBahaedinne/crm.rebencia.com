<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="d-flex align-items-center flex-wrap gap-2 mb-3">
            <h4 class="mb-0">Nouvelle estimation immobilière</h4>
            <span class="badge bg-primary-subtle text-primary">Formulaire</span>
          </div>
  <!-- Leaflet CSS (spécifique à cette vue) -->
  <link rel="stylesheet" href="<?= base_url('assets/libs/leaflet/leaflet.css'); ?>" />
      <form id="estimation-form" class="needs-validation" method="post" action="<?= base_url('estimation/calcul'); ?>" enctype="multipart/form-data" novalidate>
        <div class="card mb-3">
          <div class="card-header">Localisation & Zone</div>
          <div class="card-body row g-3">
            <div class="col-md-4">
              <label class="form-label">Zone</label>
              <select name="zone_id" class="form-select" required>
                <option value="">-- Sélectionner --</option>
                <?php foreach($zones as $z): ?>
                  <option value="<?= $z->id; ?>"><?= $z->nom; ?> (<?= number_format($z->prix_m2_moyen,0,'',' '); ?> TND/m²)</option>
                <?php endforeach; ?>
              </select>
              <div class="invalid-feedback">Choisir une zone.</div>
            </div>
            <div class="col-md-4">
              <label class="form-label">Latitude</label>
              <input type="text" name="latitude" id="latitude" class="form-control" readonly>
            </div>
            <div class="col-md-4">
              <label class="form-label">Longitude</label>
              <input type="text" name="longitude" id="longitude" class="form-control" readonly>
            </div>
            <div class="col-12">
              <div id="map" style="height:300px;background:#f5f5f5;" class="rounded border d-flex align-items-center justify-content-center text-muted">Carte (auto localiser)</div>
            </div>
          </div>
        </div>

        <div class="card mb-3">
          <div class="card-header">Caractéristiques principales</div>
          <div class="card-body row g-3">
            <div class="col-md-3">
              <label class="form-label">Surface habitable (m²)</label>
              <input type="number" step="0.01" name="surface_habitable" class="form-control" required>
              <div class="invalid-feedback">Surface requise.</div>
            </div>
            <div class="col-md-3">
              <label class="form-label">Surface terrain (m²)</label>
              <input type="number" step="0.01" name="surface_terrain" class="form-control">
            </div>
            <div class="col-md-3">
              <label class="form-label">Pièces / Chambres</label>
              <input type="number" name="nb_pieces" class="form-control" min="0">
            </div>
            <div class="col-md-3">
              <label class="form-label">Étage</label>
              <input type="number" name="etage" class="form-control" min="0">
            </div>
            <div class="col-md-3">
              <label class="form-label">Ascenseur</label>
              <select name="ascenseur" class="form-select">
                <option value="">Non</option>
                <option value="oui">Oui</option>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Orientation</label>
              <select name="orientation" class="form-select">
                <option value="">--</option>
                <option value="nord">Nord</option>
                <option value="sud">Sud</option>
                <option value="est">Est</option>
                <option value="ouest">Ouest</option>
                <option value="sud-est">Sud-Est</option>
                <option value="sud-ouest">Sud-Ouest</option>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">État général</label>
              <select name="etat_general" class="form-select" required>
                <option value="">--</option>
                <option value="neuf">Neuf</option>
                <option value="renove">Rénové</option>
                <option value="ancien">Ancien</option>
                <option value="a_renover">À rénover</option>
              </select>
              <div class="invalid-feedback">Sélectionner l'état.</div>
            </div>
            <div class="col-md-3">
              <label class="form-label">Extérieur</label>
              <select name="type_exterieur" class="form-select">
                <option value="">Aucun</option>
                <option value="balcon">Balcon</option>
                <option value="terrasse">Terrasse</option>
                <option value="jardin">Jardin</option>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Parking / Garage</label>
              <select name="parking" class="form-select">
                <option value="">Non</option>
                <option value="oui">Oui</option>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Année construction</label>
              <input type="number" name="annee_construction" class="form-control" min="1800" max="<?= date('Y'); ?>">
            </div>
            <div class="col-md-3">
              <label class="form-label">Type propriété</label>
              <input type="text" name="type_propriete" class="form-control">
            </div>
            <div class="col-md-3">
              <label class="form-label">Type de bien</label>
              <select name="type_bien" class="form-select">
                <option value="">--</option>
                <option value="appartement">Appartement</option>
                <option value="maison">Maison</option>
                <option value="villa">Villa</option>
                <option value="terrain">Terrain</option>
                <option value="bureau">Bureau</option>
                <option value="local_commercial">Local commercial</option>
                <option value="immeuble">Immeuble</option>
                <option value="parking">Parking</option>
                <option value="entrepot">Entrepôt</option>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Classe énergie</label>
              <select name="energie_classe" class="form-select">
                <option value="">--</option>
                <?php foreach(['A','B','C','D','E','F','G'] as $c): ?>
                  <option value="<?= $c; ?>"><?= $c; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Titre foncier</label>
              <select name="titre_foncier" class="form-select">
                <option value="">--</option>
                <option value="oui">Oui</option>
                <option value="non">Non</option>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Charges mensuelles (TND)</label>
              <input type="number" step="0.01" name="charges" class="form-control">
            </div>
            <div class="col-md-3">
              <label class="form-label">Taxes annuelles (TND)</label>
              <input type="number" step="0.01" name="taxes" class="form-control">
            </div>
            <div class="col-md-3">
              <label class="form-label">Prix demandé (TND)</label>
              <input type="number" step="0.01" name="prix_demande" class="form-control">
            </div>
            <div class="col-md-3">
              <label class="form-label">Piscine</label>
              <select name="piscine" class="form-select"><option value="">Non</option><option value="oui">Oui</option></select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Sécurité</label>
              <select name="securite" class="form-select"><option value="">Non</option><option value="oui">Oui</option></select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Syndic</label>
              <select name="syndic" class="form-select"><option value="">Non</option><option value="oui">Oui</option></select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Jardin</label>
              <select name="jardin" class="form-select"><option value="">Non</option><option value="oui">Oui</option></select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Proximité transports (0-5)</label>
              <input type="number" min="0" max="5" name="proximite_transports_score" class="form-control">
            </div>
            <div class="col-md-3">
              <label class="form-label">Proximité commodités (0-5)</label>
              <input type="number" min="0" max="5" name="proximite_commodites_score" class="form-control">
            </div>
          </div>
        </div>

        <div class="card mb-3">
          <div class="card-header">Équipements</div>
          <div class="card-body row g-3">
            <?php $eqs = ['cuisine_equipee'=>'Cuisine équipée','climatisation'=>'Climatisation','chauffage'=>'Chauffage','isolation'=>'Isolation']; ?>
            <?php foreach($eqs as $k=>$lbl): ?>
              <div class="col-md-3 form-check">
                <input class="form-check-input" type="checkbox" name="equipements[]" value="<?= $k; ?>" id="eq_<?= $k; ?>">
                <label class="form-check-label" for="eq_<?= $k; ?>"><?= $lbl; ?></label>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="card mb-3">
          <div class="card-header">Confort & Commodités générales</div>
          <div class="card-body row g-3">
            <div class="col-md-3">
              <label class="form-label">Cave / Débarras</label>
              <select name="cave" class="form-select"><option value="">Non</option><option value="oui">Oui</option></select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Cheminée</label>
              <select name="cheminee" class="form-select"><option value="">Non</option><option value="oui">Oui</option></select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Meublé</label>
              <select name="meuble" class="form-select"><option value="">Non</option><option value="oui">Oui</option></select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Salle de bain</label>
              <select name="sdb_type" class="form-select">
                <option value="">Standard</option>
                <option value="baignoire">Baignoire</option>
                <option value="douche">Douche</option>
                <option value="mixte">Bain + Douche</option>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Sol principal</label>
              <select name="sol_type" class="form-select">
                <option value="">Carrelage</option>
                <option value="marbre">Marbre</option>
                <option value="parquet">Parquet</option>
              </select>
            </div>
          </div>
        </div>

        <div class="card mb-3">
          <div class="card-header">Sécurité</div>
          <div class="card-body row g-3">
            <?php $sec = [ 'portail_auto'=>'Portail automatique', 'gardien'=>'Gardien / Concierge', 'videosurveillance'=>'Vidéo-surveillance', 'interphone'=>'Interphone / Digicode', 'alarme'=>'Alarme' ];
            foreach($sec as $k=>$lbl): ?>
              <div class="col-md-3 form-check">
                <input type="checkbox" class="form-check-input" id="sec_<?= $k; ?>" name="<?= $k; ?>" value="oui">
                <label class="form-check-label" for="sec_<?= $k; ?>"><?= $lbl; ?></label>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="card mb-3">
          <div class="card-header">Services & Équipements</div>
          <div class="card-body row g-3">
            <div class="col-md-3 form-check">
              <input type="checkbox" class="form-check-input" id="srv_fibre" name="fibre" value="oui">
              <label class="form-check-label" for="srv_fibre">Fibre / Haut débit</label>
            </div>
            <div class="col-md-3 form-check">
              <input type="checkbox" class="form-check-input" id="srv_lave" name="lave_linge" value="oui">
              <label class="form-check-label" for="srv_lave">Lave-linge</label>
            </div>
            <div class="col-md-3 form-check">
              <input type="checkbox" class="form-check-input" id="srv_seche" name="seche_linge" value="oui">
              <label class="form-check-label" for="srv_seche">Sèche-linge</label>
            </div>
            <div class="col-md-3">
              <label class="form-label">Chauffe-eau</label>
              <select name="chauffe_eau" class="form-select"><option value="">Électrique</option><option value="gaz">Gaz</option><option value="solaire">Solaire</option></select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Gaz</label>
              <select name="gaz_type" class="form-select"><option value="">Aucun</option><option value="ville">Gaz de ville</option><option value="propane">Propane</option></select>
            </div>
          </div>
        </div>

        <div class="card mb-3">
          <div class="card-header">Proximités (0-5)</div>
          <div class="card-body row g-3">
            <?php $prox = [
              'proximite_ecoles_score'=>'Écoles / Universités',
              'proximite_sante_score'=>'Hôpitaux / Pharmacies',
              'proximite_commerces_score'=>'Commerces / Supermarchés',
              'proximite_espaces_verts_score'=>'Parcs / Espaces verts',
              'proximite_plage_score'=>'Plage / Mer (si applicable)'
            ];
            foreach($prox as $k=>$lbl): ?>
              <div class="col-md-3">
                <label class="form-label"><?= $lbl; ?></label>
                <input type="number" min="0" max="5" name="<?= $k; ?>" class="form-control">
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="card mb-3">
          <div class="card-header">Photos</div>
          <div class="card-body">
            <input type="file" name="photos[]" multiple accept="image/*" class="form-control" />
            <small class="text-muted">Formats: jpg, jpeg, png, webp. Max 4MB/fichier.</small>
          </div>
        </div>

        <div class="text-end mb-5">
          <button class="btn btn-primary" type="submit">Calculer l'estimation</button>
        </div>
      </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Leaflet JS -->
<script src="<?= base_url('assets/libs/leaflet/leaflet.js'); ?>"></script>
<script>
// Attendre la géolocalisation avant d'afficher la carte
(function(){
  var mapDiv = document.getElementById('map');
  if(!mapDiv) return;
  var defaultLat = 20.0, defaultLng = 0.0; // centre monde (Afrique/Europe/Atlantique)
  var latInput = document.getElementById('latitude');
  var lngInput = document.getElementById('longitude');
  var map, marker;
  var mapReady = false;
  function setLatLng(lat, lng, move) {
    if(latInput) latInput.value = lat.toFixed(6);
    if(lngInput) lngInput.value = lng.toFixed(6);
    if(marker && move) {
      marker.setLatLng([lat, lng]);
      map.panTo([lat, lng]);
    }
  }
  function showMap(lat, lng, zoom) {
    mapDiv.innerHTML = '';
    map = L.map('map').setView([lat, lng], zoom);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; OpenStreetMap'
    }).addTo(map);
    marker = L.marker([lat, lng], {draggable:true}).addTo(map);
    marker.on('dragend', function(e){
      var p = e.target.getLatLng();
      setLatLng(p.lat, p.lng, false);
    });
    map.on('click', function(e){ setLatLng(e.latlng.lat, e.latlng.lng, true); });
    setLatLng(lat, lng, false);
    mapReady = true;
  }
  function showError(msg) {
    // Message désactivé : ne rien faire pour une UX plus neutre
  }
  // Essayer la géoloc, timeout 3s
  var geoTimeout = setTimeout(function(){
    if(!mapReady) {
  showMap(defaultLat, defaultLng, 2);
  // showError désactivé
    }
  }, 3000);
  if(navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(pos){
      clearTimeout(geoTimeout);
      showMap(pos.coords.latitude, pos.coords.longitude, 14);
    }, function(err){
      clearTimeout(geoTimeout);
  showMap(defaultLat, defaultLng, 2);
  // showError désactivé
    }, { enableHighAccuracy:true, timeout:2500 });
  } else {
    // Pas de support geoloc
    setTimeout(function(){
      if(!mapReady) {
  showMap(defaultLat, defaultLng, 2);
  // showError désactivé
      }
    }, 1000);
  }
})();

// Validation bootstrap
(function(){
  var forms = document.querySelectorAll('.needs-validation');
  Array.prototype.slice.call(forms).forEach(function(form){
    form.addEventListener('submit', function (event) {
      if (!form.checkValidity()) { event.preventDefault(); event.stopPropagation(); }
      form.classList.add('was-validated');
    }, false);
  });
})();
</script>
