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
                                                    <td><?= $property['id']; ?></td>
                                                    <td><?= htmlspecialchars($property['nom'] ?? $property['type_bien']); ?></td>
                                                    <td><?= htmlspecialchars($property['type_bien']); ?></td>
                                                    <td><?= htmlspecialchars($property['zone_nom'] ?? '-'); ?></td>
                                                    <td><?= htmlspecialchars($property['surface_habitable']); ?></td>
                                                    <td><?= number_format($property['prix_demande'], 0, ',', ' '); ?></td>
                                                    <td><?= htmlspecialchars($property['objectif'] ?? '-'); ?></td>
                                                    <td><?= isset($property['created_at']) ? date('d/m/Y', strtotime($property['created_at'])) : '-'; ?></td>
                                                    <td>
                                                        <a href="<?= base_url('property/view/'.$property['id']); ?>" class="btn btn-sm btn-info">Voir</a>
                                                        <a href="<?= base_url('property/edit/'.$property['id']); ?>" class="btn btn-sm btn-warning">Modifier</a>
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