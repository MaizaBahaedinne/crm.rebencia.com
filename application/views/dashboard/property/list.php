 <!-- nouisliderribute css -->
    <link rel="stylesheet" href="<?php echo base_url('assets/libs/nouislider/nouislider.min.css'); ?>">

    <!-- gridjs css -->
    <link rel="stylesheet" href="<?php echo base_url('assets/libs/gridjs/theme/mermaid.min.css'); ?>">


<?php /* Structure Velzon/milieu.php */ ?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">Liste des propriétés</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Dashboard</a></li>
                                <li class="breadcrumb-item active">Propriétés</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>


            <div id="properties-grid">
                <div class="row">
                    <?php if(!empty($properties)): ?>
                        <?php foreach($properties as $property): ?>
                            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                                <div class="card h-100 product-card">
                                    <div class="card-img-top position-relative">
                                        <img src="<?= !empty($property->photo_principale) ? htmlspecialchars($property->photo_principale) : base_url('assets/images/default.jpg') ?>" class="img-fluid rounded-top" alt="Photo bien">
                                        <?php if(!empty($property->statut_houzez)): ?>
                                            <span class="badge bg-info position-absolute top-0 end-0 m-2"><?= htmlspecialchars($property->statut_houzez) ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title mb-2 text-truncate"><?= htmlspecialchars($property->nom) ?></h5>
                                        <div class="mb-2">
                                            <span class="badge bg-primary me-1"><?= htmlspecialchars($property->type_bien) ?></span>
                                            <span class="badge bg-light text-dark"><?= htmlspecialchars($property->zone_nom) ?></span>
                                        </div>
                                        <div class="mb-2">
                                            <strong><?= number_format($property->prix_demande, 0, ',', ' ') ?> TND</strong>
                                        </div>
                                        <div class="mb-2 small">
                                            <span><i class="ri-home-2-line"></i> <?= $property->surface_habitable ?> m²</span>
                                            <span class="ms-2"><i class="ri-hotel-bed-line"></i> <?= $property->fave_property_bedrooms ?> ch.</span>
                                            <span class="ms-2"><i class="ri-shower-line"></i> <?= $property->fave_property_bathrooms ?> sdb</span>
                                        </div>
                                        <div class="mb-2 text-muted small">
                                            <i class="ri-map-pin-line"></i> <?= htmlspecialchars($property->adresse_courte ?? $property->zone_nom) ?>
                                        </div>
                                        <a href="<?= base_url('property/view/'.$property->ID); ?>" class="btn btn-sm btn-outline-primary w-100">Voir le bien</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12 text-center">Aucun bien trouvé.</div>
                    <?php endif; ?>
                </div>
            </div>
                                        <option value="">Prix</option>
                                        <option value="<500" <?= (isset($_GET['prix_demande']) && $_GET['prix_demande']==='<500')?'selected':''; ?>>Moins de 500 TND</option>
                                        <option value="500-1000" <?= (isset($_GET['prix_demande']) && $_GET['prix_demande']==='500-1000')?'selected':''; ?>>500-1000 TND</option>
                                        <option value="1000-2000" <?= (isset($_GET['prix_demande']) && $_GET['prix_demande']==='1000-2000')?'selected':''; ?>>1000-2000 TND</option>
                                        <option value=">2000" <?= (isset($_GET['prix_demande']) && $_GET['prix_demande']==='>2000')?'selected':''; ?>>Plus de 2000 TND</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <select name="fave_property_bathrooms" id="fave_property_bathrooms" class="form-select">
                                        <option value="">Salles de bain</option>
                                        <option value="1" <?= (isset($_GET['fave_property_bathrooms']) && $_GET['fave_property_bathrooms']==='1')?'selected':''; ?>>1</option>
                                        <option value="2" <?= (isset($_GET['fave_property_bathrooms']) && $_GET['fave_property_bathrooms']==='2')?'selected':''; ?>>2</option>
                                        <option value="3" <?= (isset($_GET['fave_property_bathrooms']) && $_GET['fave_property_bathrooms']==='3')?'selected':''; ?>>3</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <select name="fave_property_bedrooms" id="fave_property_bedrooms" class="form-select">
                                        <option value="">Chambres</option>
                                        <option value="1" <?= (isset($_GET['fave_property_bedrooms']) && $_GET['fave_property_bedrooms']==='1')?'selected':''; ?>>1</option>
                                        <option value="2" <?= (isset($_GET['fave_property_bedrooms']) && $_GET['fave_property_bedrooms']==='2')?'selected':''; ?>>2</option>
                                        <option value="3" <?= (isset($_GET['fave_property_bedrooms']) && $_GET['fave_property_bedrooms']==='3')?'selected':''; ?>>3</option>
                                        <option value="4" <?= (isset($_GET['fave_property_bedrooms']) && $_GET['fave_property_bedrooms']==='4')?'selected':''; ?>>4</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <input type="number" name="fave_property_size" id="fave_property_size" class="form-control" placeholder="Surface exacte (m²)" value="<?= isset($_GET['fave_property_size']) ? htmlspecialchars($_GET['fave_property_size']) : '' ?>">
                                </div>
                                <div class="mb-3">
                                    <select name="fave_property_size_prefix" id="fave_property_size_prefix" class="form-select">
                                        <option value="">Unité</option>
                                        <option value="m" <?= (isset($_GET['fave_property_size_prefix']) && $_GET['fave_property_size_prefix']==='m')?'selected':''; ?>>m²</option>
                                        <option value="ha" <?= (isset($_GET['fave_property_size_prefix']) && $_GET['fave_property_size_prefix']==='ha')?'selected':''; ?>>hectare</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <select name="fave_property_garage" id="fave_property_garage" class="form-select">
                                        <option value="">Garage</option>
                                        <option value="1" <?= (isset($_GET['fave_property_garage']) && $_GET['fave_property_garage']==='1')?'selected':''; ?>>Oui</option>
                                        <option value="0" <?= (isset($_GET['fave_property_garage']) && $_GET['fave_property_garage']==='0')?'selected':''; ?>>Non</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <input type="number" name="fave_property_year" id="fave_property_year" class="form-control" placeholder="Année" value="<?= isset($_GET['fave_property_year']) ? htmlspecialchars($_GET['fave_property_year']) : '' ?>">
                                </div>
                                <div class="mb-3">
                                    <input type="number" name="fave_property_price" id="fave_property_price" class="form-control" placeholder="Prix exact" value="<?= isset($_GET['fave_property_price']) ? htmlspecialchars($_GET['fave_property_price']) : '' ?>">
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
    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <script>document.write(new Date().getFullYear())</script> © Velzon.
                </div>
                <div class="col-sm-6">
                    <div class="text-sm-end d-none d-sm-block">
                        Design & Develop by Rebencia
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>