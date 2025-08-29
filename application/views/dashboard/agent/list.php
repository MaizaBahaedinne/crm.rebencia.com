
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">Agents de l'agence</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Dashboard</a></li>
                                <li class="breadcrumb-item active">Liste des agents</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-body">
                    <?php if (!empty($agents)) : ?>
                    <div class="table-responsive">
                        <table id="agentsTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nom affiché</th>
                                    <th>Agence</th>
                                    <th>Email</th>
                                    <th>Téléphone</th>
                                    <th>Date d'inscription</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($agents as $index => $agent) : ?>
                                    <tr>
                                        <td><?php echo $index + 1; ?></td>
                                        <td><?php echo htmlspecialchars($agent->display_name); ?></td>
                                        <td><?php echo htmlspecialchars($agent->agency_name ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($agent->user_email); ?></td>
                                        <td><?php echo htmlspecialchars($agent->agent_phone ?? ''); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($agent->user_registered)); ?></td>
                                        <td>
                                            <a href="<?php echo base_url('agent/details/' . $agent->ID); ?>" class="btn btn-sm btn-primary">
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
                            $('#agentsTable').DataTable({
                                "language": {
                                    "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/fr-FR.json"
                                }
                            });
                        });
                    </script>
                    <?php else : ?>
                        <div class="alert alert-info">Aucun agent trouvé.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
