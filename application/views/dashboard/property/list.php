 <!-- nouisliderribute css -->
    <link rel="stylesheet" href="<?php echo base_url('assets/libs/nouislider/nouislider.min.css'); ?>">

    <!-- gridjs css -->
    <link rel="stylesheet" href="<?php echo base_url('assets/libs/gridjs/theme/mermaid.min.css'); ?>">

<div class="main-content">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="card h-100">
                                        <div class="card-header"><strong>Filtres</strong></div>
                                        <div class="card-body">
                                            <form method="get" class="row gy-3">
                                                <div class="mb-3">
                                                    <label for="nom" class="form-label">Nom du bien</label>
                                                    <input type="text" name="nom" id="nom" class="form-control" value="<?= isset($_GET['nom']) ? htmlspecialchars($_GET['nom']) : '' ?>" autocomplete="off">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="statut_houzez" class="form-label">Statut</label>
                                                    <select name="statut_houzez" id="statut_houzez" class="form-select">
                                                        <option value="">Tous</option>
                                                        <option value="Location" <?= (isset($_GET['statut_houzez']) && $_GET['statut_houzez']==='Location')?'selected':''; ?>>Location</option>
                                                        <option value="Vente" <?= (isset($_GET['statut_houzez']) && $_GET['statut_houzez']==='Vente')?'selected':''; ?>>Vente</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="type_bien" class="form-label">Type de bien</label>
                                                    <select name="type_bien" id="type_bien" class="form-select">
                                                        <option value="">Tous</option>
                                                        <option value="Appartement" <?= (isset($_GET['type_bien']) && $_GET['type_bien']==='Appartement')?'selected':''; ?>>Appartement</option>
                                                        <option value="Villa" <?= (isset($_GET['type_bien']) && $_GET['type_bien']==='Villa')?'selected':''; ?>>Villa</option>
                                                        <option value="Studio" <?= (isset($_GET['type_bien']) && $_GET['type_bien']==='Studio')?'selected':''; ?>>Studio</option>
                                                        <option value="Duplex" <?= (isset($_GET['type_bien']) && $_GET['type_bien']==='Duplex')?'selected':''; ?>>Duplex</option>
                                                        <option value="Terrain" <?= (isset($_GET['type_bien']) && $_GET['type_bien']==='Terrain')?'selected':''; ?>>Terrain</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="zone_nom" class="form-label">Ville</label>
                                                    <select name="zone_nom" id="zone_nom" class="form-select">
                                                        <option value="">Toutes</option>
                                                        <option value="Tunis" <?= (isset($_GET['zone_nom']) && $_GET['zone_nom']==='Tunis')?'selected':''; ?>>Tunis</option>
                                                        <option value="La Marsa" <?= (isset($_GET['zone_nom']) && $_GET['zone_nom']==='La Marsa')?'selected':''; ?>>La Marsa</option>
                                                        <option value="Carthage" <?= (isset($_GET['zone_nom']) && $_GET['zone_nom']==='Carthage')?'selected':''; ?>>Carthage</option>
                                                        <option value="Gammarth" <?= (isset($_GET['zone_nom']) && $_GET['zone_nom']==='Gammarth')?'selected':''; ?>>Gammarth</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="surface_habitable" class="form-label">Surface</label>
                                                    <select name="surface_habitable" id="surface_habitable" class="form-select">
                                                        <option value="">Toutes</option>
                                                        <option value="<50" <?= (isset($_GET['surface_habitable']) && $_GET['surface_habitable']==='<50')?'selected':''; ?>>Moins de 50 m²</option>
                                                        <option value="50-100" <?= (isset($_GET['surface_habitable']) && $_GET['surface_habitable']==='50-100')?'selected':''; ?>>50-100 m²</option>
                                                        <option value="100-150" <?= (isset($_GET['surface_habitable']) && $_GET['surface_habitable']==='100-150')?'selected':''; ?>>100-150 m²</option>
                                                        <option value=">150" <?= (isset($_GET['surface_habitable']) && $_GET['surface_habitable']==='>150')?'selected':''; ?>>Plus de 150 m²</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="prix_demande" class="form-label">Prix</label>
                                                    <select name="prix_demande" id="prix_demande" class="form-select">
                                                        <option value="">Tous</option>
                                                        <option value="<500" <?= (isset($_GET['prix_demande']) && $_GET['prix_demande']==='<500')?'selected':''; ?>>Moins de 500 TND</option>
                                                        <option value="500-1000" <?= (isset($_GET['prix_demande']) && $_GET['prix_demande']==='500-1000')?'selected':''; ?>>500-1000 TND</option>
                                                        <option value="1000-2000" <?= (isset($_GET['prix_demande']) && $_GET['prix_demande']==='1000-2000')?'selected':''; ?>>1000-2000 TND</option>
                                                        <option value=">2000" <?= (isset($_GET['prix_demande']) && $_GET['prix_demande']==='>2000')?'selected':''; ?>>Plus de 2000 TND</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="fave_property_bathrooms" class="form-label">Salles de bain</label>
                                                    <select name="fave_property_bathrooms" id="fave_property_bathrooms" class="form-select">
                                                        <option value="">Toutes</option>
                                                        <option value="1" <?= (isset($_GET['fave_property_bathrooms']) && $_GET['fave_property_bathrooms']==='1')?'selected':''; ?>>1</option>
                                                        <option value="2" <?= (isset($_GET['fave_property_bathrooms']) && $_GET['fave_property_bathrooms']==='2')?'selected':''; ?>>2</option>
                                                        <option value="3" <?= (isset($_GET['fave_property_bathrooms']) && $_GET['fave_property_bathrooms']==='3')?'selected':''; ?>>3</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="fave_property_bedrooms" class="form-label">Chambres</label>
                                                    <select name="fave_property_bedrooms" id="fave_property_bedrooms" class="form-select">
                                                        <option value="">Toutes</option>
                                                        <option value="1" <?= (isset($_GET['fave_property_bedrooms']) && $_GET['fave_property_bedrooms']==='1')?'selected':''; ?>>1</option>
                                                        <option value="2" <?= (isset($_GET['fave_property_bedrooms']) && $_GET['fave_property_bedrooms']==='2')?'selected':''; ?>>2</option>
                                                        <option value="3" <?= (isset($_GET['fave_property_bedrooms']) && $_GET['fave_property_bedrooms']==='3')?'selected':''; ?>>3</option>
                                                        <option value="4" <?= (isset($_GET['fave_property_bedrooms']) && $_GET['fave_property_bedrooms']==='4')?'selected':''; ?>>4</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="fave_property_size" class="form-label">Surface exacte (m²)</label>
                                                    <input type="number" name="fave_property_size" id="fave_property_size" class="form-control" value="<?= isset($_GET['fave_property_size']) ? htmlspecialchars($_GET['fave_property_size']) : '' ?>">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="fave_property_size_prefix" class="form-label">Unité</label>
                                                    <select name="fave_property_size_prefix" id="fave_property_size_prefix" class="form-select">
                                                        <option value="">Toutes</option>
                                                        <option value="m" <?= (isset($_GET['fave_property_size_prefix']) && $_GET['fave_property_size_prefix']==='m')?'selected':''; ?>>m²</option>
                                                        <option value="ha" <?= (isset($_GET['fave_property_size_prefix']) && $_GET['fave_property_size_prefix']==='ha')?'selected':''; ?>>hectare</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="fave_property_garage" class="form-label">Garage</label>
                                                    <select name="fave_property_garage" id="fave_property_garage" class="form-select">
                                                        <option value="">Tous</option>
                                                        <option value="1" <?= (isset($_GET['fave_property_garage']) && $_GET['fave_property_garage']==='1')?'selected':''; ?>>Oui</option>
                                                        <option value="0" <?= (isset($_GET['fave_property_garage']) && $_GET['fave_property_garage']==='0')?'selected':''; ?>>Non</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="fave_property_year" class="form-label">Année</label>
                                                    <input type="number" name="fave_property_year" id="fave_property_year" class="form-control" value="<?= isset($_GET['fave_property_year']) ? htmlspecialchars($_GET['fave_property_year']) : '' ?>">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="fave_property_price" class="form-label">Prix exact</label>
                                                    <input type="number" name="fave_property_price" id="fave_property_price" class="form-control" value="<?= isset($_GET['fave_property_price']) ? htmlspecialchars($_GET['fave_property_price']) : '' ?>">
                                                </div>
                                                <div class="d-flex gap-2">
                                                    <button type="submit" class="btn btn-primary flex-fill">Filtrer</button>
                                                    <a href="<?= base_url('property'); ?>" class="btn btn-outline-secondary flex-fill">Réinitialiser</a>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="card">
                                        <div class="card-header"><strong>Résultats</strong></div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                        <option value="4" <?= (isset($_GET['fave_property_bedrooms']) && $_GET['fave_property_bedrooms']==='4')?'selected':''; ?>>4</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="fave_property_size" class="form-label">Surface exacte (m²)</label>
                                    <input type="number" name="fave_property_size" id="fave_property_size" class="form-control" value="<?= isset($_GET['fave_property_size']) ? htmlspecialchars($_GET['fave_property_size']) : '' ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="fave_property_size_prefix" class="form-label">Unité</label>
                                    <select name="fave_property_size_prefix" id="fave_property_size_prefix" class="form-select">
                                        <option value="">Toutes</option>
                                        <option value="m" <?= (isset($_GET['fave_property_size_prefix']) && $_GET['fave_property_size_prefix']==='m')?'selected':''; ?>>m²</option>
                                        <option value="ha" <?= (isset($_GET['fave_property_size_prefix']) && $_GET['fave_property_size_prefix']==='ha')?'selected':''; ?>>hectare</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="fave_property_garage" class="form-label">Garage</label>
                                    <select name="fave_property_garage" id="fave_property_garage" class="form-select">
                                        <option value="">Tous</option>
                                        <option value="1" <?= (isset($_GET['fave_property_garage']) && $_GET['fave_property_garage']==='1')?'selected':''; ?>>Oui</option>
                                        <option value="0" <?= (isset($_GET['fave_property_garage']) && $_GET['fave_property_garage']==='0')?'selected':''; ?>>Non</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="fave_property_year" class="form-label">Année</label>
                                    <input type="number" name="fave_property_year" id="fave_property_year" class="form-control" value="<?= isset($_GET['fave_property_year']) ? htmlspecialchars($_GET['fave_property_year']) : '' ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="fave_property_price" class="form-label">Prix exact</label>
                                    <input type="number" name="fave_property_price" id="fave_property_price" class="form-control" value="<?= isset($_GET['fave_property_price']) ? htmlspecialchars($_GET['fave_property_price']) : '' ?>">
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary flex-fill">Filtrer</button>
                                    <a href="<?= base_url('property'); ?>" class="btn btn-outline-secondary flex-fill">Réinitialiser</a>
                                </div>
                            </form>
                                <div class="col-md-3">
                                    <input type="text" name="nom" class="form-control" placeholder="Nom du bien" value="<?= isset($_GET['nom']) ? htmlspecialchars($_GET['nom']) : '' ?>" autocomplete="off" id="autocomplete-nom">
                                </div>
                                <div class="col-md-2">
                                    <select name="statut_houzez" class="form-select">
                                        <option value="">Statut</option>
                                        <option value="Location" <?= (isset($_GET['statut_houzez']) && $_GET['statut_houzez']==='Location')?'selected':''; ?>>Location</option>
                                        <option value="Vente" <?= (isset($_GET['statut_houzez']) && $_GET['statut_houzez']==='Vente')?'selected':''; ?>>Vente</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="type_bien" class="form-select">
                                        <option value="">Type de bien</option>
                                        <option value="Appartement" <?= (isset($_GET['type_bien']) && $_GET['type_bien']==='Appartement')?'selected':''; ?>>Appartement</option>
                                        <option value="Villa" <?= (isset($_GET['type_bien']) && $_GET['type_bien']==='Villa')?'selected':''; ?>>Villa</option>
                                        <option value="Studio" <?= (isset($_GET['type_bien']) && $_GET['type_bien']==='Studio')?'selected':''; ?>>Studio</option>
                                        <option value="Duplex" <?= (isset($_GET['type_bien']) && $_GET['type_bien']==='Duplex')?'selected':''; ?>>Duplex</option>
                                        <option value="Terrain" <?= (isset($_GET['type_bien']) && $_GET['type_bien']==='Terrain')?'selected':''; ?>>Terrain</option>
                                        <!-- Ajoute ici les autres types de ta base -->
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="zone_nom" class="form-select">
                                        <option value="">Ville</option>
                                        <option value="Tunis" <?= (isset($_GET['zone_nom']) && $_GET['zone_nom']==='Tunis')?'selected':''; ?>>Tunis</option>
                                        <option value="La Marsa" <?= (isset($_GET['zone_nom']) && $_GET['zone_nom']==='La Marsa')?'selected':''; ?>>La Marsa</option>
                                        <option value="Carthage" <?= (isset($_GET['zone_nom']) && $_GET['zone_nom']==='Carthage')?'selected':''; ?>>Carthage</option>
                                        <option value="Gammarth" <?= (isset($_GET['zone_nom']) && $_GET['zone_nom']==='Gammarth')?'selected':''; ?>>Gammarth</option>
                                        <!-- Ajoute ici les autres villes de ta base -->
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="surface_habitable" class="form-select">
                                        <option value="">Surface</option>
                                        <option value="<50" <?= (isset($_GET['surface_habitable']) && $_GET['surface_habitable']==='<50')?'selected':''; ?>>Moins de 50 m²</option>
                                        <option value="50-100" <?= (isset($_GET['surface_habitable']) && $_GET['surface_habitable']==='50-100')?'selected':''; ?>>50-100 m²</option>
                                        <option value="100-150" <?= (isset($_GET['surface_habitable']) && $_GET['surface_habitable']==='100-150')?'selected':''; ?>>100-150 m²</option>
                                        <option value=">150" <?= (isset($_GET['surface_habitable']) && $_GET['surface_habitable']==='>150')?'selected':''; ?>>Plus de 150 m²</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="prix_demande" class="form-select">
                                        <option value="">Prix</option>
                                        <option value="<500" <?= (isset($_GET['prix_demande']) && $_GET['prix_demande']==='<500')?'selected':''; ?>>Moins de 500 TND</option>
                                        <option value="500-1000" <?= (isset($_GET['prix_demande']) && $_GET['prix_demande']==='500-1000')?'selected':''; ?>>500-1000 TND</option>
                                        <option value="1000-2000" <?= (isset($_GET['prix_demande']) && $_GET['prix_demande']==='1000-2000')?'selected':''; ?>>1000-2000 TND</option>
                                        <option value=">2000" <?= (isset($_GET['prix_demande']) && $_GET['prix_demande']==='>2000')?'selected':''; ?>>Plus de 2000 TND</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="fave_property_bathrooms" class="form-select">
                                        <option value="">Salles de bain</option>
                                        <option value="1" <?= (isset($_GET['fave_property_bathrooms']) && $_GET['fave_property_bathrooms']==='1')?'selected':''; ?>>1</option>
                                        <option value="2" <?= (isset($_GET['fave_property_bathrooms']) && $_GET['fave_property_bathrooms']==='2')?'selected':''; ?>>2</option>
                                        <option value="3" <?= (isset($_GET['fave_property_bathrooms']) && $_GET['fave_property_bathrooms']==='3')?'selected':''; ?>>3</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="fave_property_bedrooms" class="form-select">
                                        <option value="">Chambres</option>
                                        <option value="1" <?= (isset($_GET['fave_property_bedrooms']) && $_GET['fave_property_bedrooms']==='1')?'selected':''; ?>>1</option>
                                        <option value="2" <?= (isset($_GET['fave_property_bedrooms']) && $_GET['fave_property_bedrooms']==='2')?'selected':''; ?>>2</option>
                                        <option value="3" <?= (isset($_GET['fave_property_bedrooms']) && $_GET['fave_property_bedrooms']==='3')?'selected':''; ?>>3</option>
                                        <option value="4" <?= (isset($_GET['fave_property_bedrooms']) && $_GET['fave_property_bedrooms']==='4')?'selected':''; ?>>4</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="fave_property_size" class="form-control" placeholder="Surface exacte (m²)" value="<?= isset($_GET['fave_property_size']) ? htmlspecialchars($_GET['fave_property_size']) : '' ?>">
                                </div>
                                <div class="col-md-2">
                                    <select name="fave_property_size_prefix" class="form-select">
                                        <option value="">Unité</option>
                                        <option value="m" <?= (isset($_GET['fave_property_size_prefix']) && $_GET['fave_property_size_prefix']==='m')?'selected':''; ?>>m²</option>
                                        <option value="ha" <?= (isset($_GET['fave_property_size_prefix']) && $_GET['fave_property_size_prefix']==='ha')?'selected':''; ?>>hectare</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="fave_property_garage" class="form-select">
                                        <option value="">Garage</option>
                                        <option value="1" <?= (isset($_GET['fave_property_garage']) && $_GET['fave_property_garage']==='1')?'selected':''; ?>>Oui</option>
                                        <option value="0" <?= (isset($_GET['fave_property_garage']) && $_GET['fave_property_garage']==='0')?'selected':''; ?>>Non</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="fave_property_year" class="form-control" placeholder="Année" value="<?= isset($_GET['fave_property_year']) ? htmlspecialchars($_GET['fave_property_year']) : '' ?>">
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="fave_property_price" class="form-control" placeholder="Prix exact" value="<?= isset($_GET['fave_property_price']) ? htmlspecialchars($_GET['fave_property_price']) : '' ?>">
                                </div>
                                <div class="col-md-1">
                                    <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                                </div>
                            </form>
                            <script>
                            // Autocomplete JS (exemple simple, à remplacer par AJAX si besoin)
                            const noms = [
                                <?php if(!empty($properties)) foreach($properties as $p) echo '"'.addslashes($p->nom).'",'; ?>
                            ];
                            const inputNom = document.getElementById('autocomplete-nom');
                            if(inputNom) {
                                inputNom.addEventListener('input', function() {
                                    let val = this.value;
                                    this.setAttribute('list', 'noms-list');
                                });
                                let datalist = document.createElement('datalist');
                                datalist.id = 'noms-list';
                                noms.forEach(n => {
                                    let opt = document.createElement('option');
                                    opt.value = n;
                                    datalist.appendChild(opt);
                                });
                                document.body.appendChild(datalist);
                            }
                            </script>
                            <div class="table-responsive">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Nom</th>
                                            <th>Type</th>
                                            <th>Ville</th>
                                            <th>Surface (m²)</th>
                                            <th>Prix (TND)</th>
                                            <th>Statut</th>
                                            <th>Date création</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(!empty($properties)): ?>
                                            <?php foreach($properties as $property): ?>
                                                <tr>
                                                    <td><?= isset($property->ID) ? $property->ID : '-'; ?></td>
                                                    <td><?= htmlspecialchars(isset($property->nom) ? $property->nom : (isset($property->type_bien) ? $property->type_bien : '-')); ?></td>
                                                    <td><?= htmlspecialchars(isset($property->type_bien) ? $property->type_bien : (isset($property->property_type) ? $property->property_type : '-')); ?></td>
                                                    <td><?= htmlspecialchars(isset($property->zone_nom) ? $property->zone_nom : '-'); ?></td>
                                                    <td><?= isset($property->surface_habitable) && is_numeric($property->surface_habitable) ? htmlspecialchars($property->surface_habitable) : (isset($property->property_surface) && is_numeric($property->property_surface) ? htmlspecialchars($property->property_surface) : '-'); ?></td>
                                                    <td><?php
                                                        $prix = null;
                                                        if (isset($property->prix_demande) && is_numeric($property->prix_demande)) {
                                                            $prix = $property->prix_demande;
                                                        } elseif (isset($property->property_price) && is_numeric($property->property_price)) {
                                                            $prix = $property->property_price;
                                                        }
                                                        echo $prix !== null ? number_format((float)$prix, 0, ',', ' ') : '-';
                                                    ?></td>
                                                    <td><?= isset($property->statut_houzez) ? htmlspecialchars($property->statut_houzez) : '-'; ?></td>
                                                    <td><?= isset($property->created_at) && strtotime($property->created_at) ? date('d/m/Y', strtotime($property->created_at)) : (isset($property->post_date) && strtotime($property->post_date) ? date('d/m/Y', strtotime($property->post_date)) : '-'); ?></td>
                                                    <td>
                                                        <a href="<?= base_url('property/view/'.$property->ID); ?>" class="btn btn-sm btn-info">Voir</a>
                                                        <!-- Bouton Modifier supprimé (CRM = consultation uniquement) -->
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr><td colspan="9" class="text-center">Aucun bien trouvé.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
            </div>
        </div>
    </div>
</div>