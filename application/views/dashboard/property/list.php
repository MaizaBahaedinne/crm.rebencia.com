<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">Propriétés</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Dashboard</a></li>
                                <li class="breadcrumb-item active">Liste des propriétés</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-body">
                    <?php if (!empty($properties)) : ?>
                    <div class="table-responsive">
                        <table id="propertiesTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Titre</th>
                                    <th>Type</th>
                                    <th>Statut</th>
                                    <th>Prix</th>
                                    <th>Ville</th>
                                    <th>Date de création</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($properties as $index => $property) : ?>
                                    <tr>
                                        <td><?php echo $index + 1; ?></td>
                                        <td><?php echo htmlspecialchars($property->post_title); ?></td>
                                        <td><?php echo isset($property->property_type) ? htmlspecialchars($property->property_type) : '-'; ?></td>
                                        <td><?php echo isset($property->property_status) ? htmlspecialchars($property->property_status) : '-'; ?></td>
                                        <td><?php echo isset($property->property_price) ? htmlspecialchars($property->property_price) : '-'; ?></td>
                                        <td><?php echo isset($property->property_city) ? htmlspecialchars($property->property_city) : '-'; ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($property->post_date)); ?></td>
                                        <td>
                                            <a href="<?php echo base_url('property/details/' . $property->ID); ?>" class="btn btn-sm btn-primary">
                                                <i class="mdi mdi-eye"></i> Détails
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <script>
                        $(document).ready(function() {
                            $('#propertiesTable').DataTable({
                                "language": {
                                    "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/fr-FR.json"
                                }
                            });
                        });
                    </script>
                    <?php else : ?>
                        <div class="alert alert-info">Aucune propriété trouvée.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
