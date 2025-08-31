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

      <div class="card mt-4">
        <div class="card-header">Détails du bien</div>
        <div class="card-body table-responsive">
          <table class="table table-sm mb-0">
            <tbody>
              <tr><th style="width:30%">Zone</th><td><?= $property['zone_nom']; ?> (ID <?= $property['zone_id']; ?>)</td></tr>
              <tr><th>Surface habitable</th><td><?= $property['surface_habitable']; ?> m²</td></tr>
              <tr><th>Valeur min estimée</th><td><?= number_format($property['valeur_min_estimee'],0,'',' '); ?> TND</td></tr>
              <tr><th>Valeur max estimée</th><td><?= number_format($property['valeur_max_estimee'],0,'',' '); ?> TND</td></tr>
              <tr><th>Surface terrain</th><td><?= $property['surface_terrain']; ?> m²</td></tr>
              <tr><th>Pièces / Chambres</th><td><?= $property['nb_pieces']; ?></td></tr>
              <tr><th>Étage</th><td><?= $property['etage']; ?></td></tr>
              <tr><th>Ascenseur</th><td><?= $property['ascenseur']; ?></td></tr>
              <tr><th>Orientation</th><td><?= $property['orientation']; ?></td></tr>
              <tr><th>État</th><td><?= $property['etat_general']; ?></td></tr>
              <tr><th>Extérieur</th><td><?= $property['type_exterieur']; ?></td></tr>
              <tr><th>Parking</th><td><?= $property['parking']; ?></td></tr>
              <tr><th>Année construction</th><td><?= $property['annee_construction']; ?></td></tr>
              <tr><th>Type propriété</th><td><?= $property['type_propriete']; ?></td></tr>
              <tr><th>Type de bien</th><td><?= $property['type_bien']; ?></td></tr>
              <tr><th>Classe énergie</th><td><?= $property['energie_classe']; ?></td></tr>
              <tr><th>Titre foncier</th><td><?= $property['titre_foncier']; ?></td></tr>
              <tr><th>Charges</th><td><?= number_format($property['charges'],0,'',' '); ?> TND</td></tr>
              <tr><th>Taxes</th><td><?= number_format($property['taxes'],0,'',' '); ?> TND</td></tr>
              <tr><th>Prix demandé</th><td><?= number_format($property['prix_demande'],0,'',' '); ?> TND</td></tr>
              <tr><th>Piscine</th><td><?= $property['piscine']; ?></td></tr>
              <tr><th>Sécurité</th><td><?= $property['securite']; ?></td></tr>
              <tr><th>Syndic</th><td><?= $property['syndic']; ?></td></tr>
              <tr><th>Jardin</th><td><?= $property['jardin']; ?></td></tr>
              <tr><th>Latitude</th><td><?= $property['latitude']; ?></td></tr>
              <tr><th>Longitude</th><td><?= $property['longitude']; ?></td></tr>
              <tr><th>Équipements</th><td><?= $property['equipements']; ?></td></tr>
              <tr><th>Cave</th><td><?= $property['cave'] ?? ''; ?></td></tr>
              <tr><th>Cheminée</th><td><?= $property['cheminee'] ?? ''; ?></td></tr>
              <tr><th>Meublé</th><td><?= $property['meuble'] ?? ''; ?></td></tr>
              <tr><th>SDB type</th><td><?= $property['sdb_type'] ?? ''; ?></td></tr>
              <tr><th>Sol</th><td><?= $property['sol_type'] ?? ''; ?></td></tr>
              <tr><th>Portail auto</th><td><?= $property['portail_auto'] ?? ''; ?></td></tr>
              <tr><th>Gardien</th><td><?= $property['gardien'] ?? ''; ?></td></tr>
              <tr><th>Vidéo-surveillance</th><td><?= $property['videosurveillance'] ?? ''; ?></td></tr>
              <tr><th>Interphone</th><td><?= $property['interphone'] ?? ''; ?></td></tr>
              <tr><th>Alarme</th><td><?= $property['alarme'] ?? ''; ?></td></tr>
              <tr><th>Fibre</th><td><?= $property['fibre'] ?? ''; ?></td></tr>
              <tr><th>Lave-linge</th><td><?= $property['lave_linge'] ?? ''; ?></td></tr>
              <tr><th>Sèche-linge</th><td><?= $property['seche_linge'] ?? ''; ?></td></tr>
              <tr><th>Chauffe-eau</th><td><?= $property['chauffe_eau'] ?? ''; ?></td></tr>
              <tr><th>Gaz</th><td><?= $property['gaz_type'] ?? ''; ?></td></tr>
              <tr><th>Score transports (form)</th><td><?= $property['proximite_transports_score'] ?? ''; ?></td></tr>
              <tr><th>Score commodités (form)</th><td><?= $property['proximite_commodites_score'] ?? ''; ?></td></tr>
              <tr><th>Score écoles</th><td><?= $property['proximite_ecoles_score'] ?? ''; ?></td></tr>
              <tr><th>Score santé</th><td><?= $property['proximite_sante_score'] ?? ''; ?></td></tr>
              <tr><th>Score commerces</th><td><?= $property['proximite_commerces_score'] ?? ''; ?></td></tr>
              <tr><th>Score espaces verts</th><td><?= $property['proximite_espaces_verts_score'] ?? ''; ?></td></tr>
              <tr><th>Score plage</th><td><?= $property['proximite_plage_score'] ?? ''; ?></td></tr>
              <tr><th>Proposition agence</th><td><?= isset($property['proposition_agence'])?number_format($property['proposition_agence'],0,'',' '):''; ?> TND</td></tr>
              <tr><th>Commentaire agence</th><td><?= $property['proposition_commentaire'] ?? ''; ?></td></tr>
              <?php
                $badge = 'secondary';
                if($property['statut_dossier']==='valide') $badge='success';
                elseif($property['statut_dossier']==='rejete') $badge='danger';
                elseif($property['statut_dossier']==='en_cours') $badge='warning';
              ?>
              <tr><th>Statut dossier</th><td><span class="badge bg-<?= $badge; ?>"><?= $property['statut_dossier']; ?></span></td></tr>
            </tbody>
          </table>
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

      <div class="card mt-4">
        <div class="card-header">Proposition agence</div>
        <div class="card-body">
          <form method="post" action="<?= base_url('estimation/proposition/'.$property['id']); ?>" class="row g-3">
            <div class="col-md-3">
              <label class="form-label">Montant proposé (TND)</label>
              <input type="number" step="0.01" name="proposition_agence" class="form-control" value="<?= htmlspecialchars($property['proposition_agence'] ?? '', ENT_QUOTES); ?>">
            </div>
            <div class="col-md-9">
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
      </div>
    </div>
  </div>
</div>
