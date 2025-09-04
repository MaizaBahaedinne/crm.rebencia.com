 <!-- nouisliderribute css -->
    <link rel="stylesheet" href="<?php echo base_url('assets/libs/nouislider/nouislider.min.css'); ?>">

    <!-- gridjs css -->
    <link rel="stylesheet" href="<?php echo base_url('assets/libs/gridjs/theme/mermaid.min.css'); ?>">

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">Biens immobiliers</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="<?= base_url('dashboard'); ?>">Tableau de bord</a></li>
                                <li class="breadcrumb-item active">Biens immobiliers</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Liste des biens</h5>
                            <a href="<?= base_url('property/add'); ?>" class="btn btn-success"><i class="ri-add-line align-bottom me-1"></i> Ajouter un bien</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Nom</th>
                                            <th>Type</th>
                                            <th>Zone</th>
                                            <th>Surface (m²)</th>
                                            <th>Prix (TND)</th>
                                            <th>Objectif</th>
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
                                                    <td><?= htmlspecialchars(isset($property->zone_nom) ? $property->zone_nom : (isset($property->property_zone) ? $property->property_zone : '-')); ?></td>
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
                                                    <td><?= htmlspecialchars(isset($property->objectif) ? $property->objectif : (isset($property->property_objectif) ? $property->property_objectif : '-')); ?></td>
                                                    <td><?= isset($property->created_at) && strtotime($property->created_at) ? date('d/m/Y', strtotime($property->created_at)) : (isset($property->post_date) && strtotime($property->post_date) ? date('d/m/Y', strtotime($property->post_date)) : '-'); ?></td>
                                                    <td>
                                                        <a href="<?= base_url('property/view/'.$property->ID); ?>" class="btn btn-sm btn-info">Voir</a>
                                                        <a href="<?= base_url('property/edit/'.$property->ID); ?>" class="btn btn-sm btn-warning">Modifier</a>
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