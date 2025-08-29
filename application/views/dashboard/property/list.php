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
                                    <th>meta_id</th>
                                    <th>post_id</th>
                                    <th>meta_key</th>
                                    <th>meta_value</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($properties as $meta) : ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($meta->meta_id); ?></td>
                                        <td><?php echo htmlspecialchars($meta->post_id); ?></td>
                                        <td><?php echo htmlspecialchars($meta->meta_key); ?></td>
                                        <td><?php echo htmlspecialchars($meta->meta_value); ?></td>
                                        <td>
                                            <a href="<?php echo base_url('property/edit_meta/' . $meta->meta_id); ?>" class="btn btn-sm btn-warning">Éditer</a>
                                            <a href="#" onclick="copyMeta('<?php echo addslashes($meta->meta_value); ?>')" class="btn btn-sm btn-info">Copier</a>
                                            <a href="<?php echo base_url('property/delete_meta/' . $meta->meta_id); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cette métadonnée ?');">Supprimer</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <script>
                                function copyMeta(value) {
                                    navigator.clipboard.writeText(value);
                                    alert('Valeur copiée !');
                                }
                                </script>
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
