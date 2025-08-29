
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">Agences</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Dashboard</a></li>
                                <li class="breadcrumb-item active">Liste des agences</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-body">
                <?php if (!empty($agencies)) : ?>
                    <div class="table-responsive">
                        <table id="agenciesTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nom affiché</th>
                                    <th>Email WP</th>
                                    <th>Email agence</th>
                                    <th>Téléphone</th>
                                    <th>Adresse</th>
                                    <th>Site web</th>
                                    <th>Date d'inscription</th>
                                    <th>Rôle</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($agencies as $index => $agency) : ?>
                                    <tr>
                                        <td><?php echo $index + 1; ?></td>
                                        <td><?php echo htmlspecialchars($agency->display_name); ?></td>
                                        <td><?php echo htmlspecialchars($agency->user_email); ?></td>
                                        <td><?php echo htmlspecialchars($agency->agency_email); ?></td>
                                        <td><?php echo htmlspecialchars($agency->agency_phone); ?></td>
                                        <td><?php echo htmlspecialchars($agency->agency_address); ?></td>
                                        <td><?php echo htmlspecialchars($agency->agency_website); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($agency->user_registered)); ?></td>
                                        <td>houzez_agency</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <script>
                        $(document).ready(function() {
                            $('#agenciesTable').DataTable({
                                "language": {
                                    "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/fr-FR.json"
                                }
                            });
                        });
                    </script>
                <?php else : ?>
                    <div class="alert alert-info">Aucune agence trouvée.</div>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
