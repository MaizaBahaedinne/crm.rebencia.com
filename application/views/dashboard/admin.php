<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">CRM</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard/admin'); ?>">Dashboard</a></li>
                                <li class="breadcrumb-item active">Vue Admin</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <!-- Statistiques globales -->
            <div class="row">
                <div class="col">
                    <div class="card crm-widget">
                        <div class="card-body p-0">
                            <div class="row row-cols-xxl-4 row-cols-md-4 row-cols-1 g-0">
                                <div class="col">
                                    <div class="py-4 px-3">
                                        <h5 class="text-muted text-uppercase fs-13">Leads</h5>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="ri-user-add-line display-6 text-muted cfs-22"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h2 class="mb-0 cfs-22"><?= $stats['leads']; ?></h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="py-4 px-3">
                                        <h5 class="text-muted text-uppercase fs-13">Propriétés</h5>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="ri-home-2-line display-6 text-muted cfs-22"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h2 class="mb-0 cfs-22"><?= $stats['properties']; ?></h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="py-4 px-3">
                                        <h5 class="text-muted text-uppercase fs-13">Ventes</h5>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="ri-shopping-bag-line display-6 text-muted cfs-22"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h2 class="mb-0 cfs-22"><?= $stats['sales']; ?></h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="py-4 px-3">
                                        <h5 class="text-muted text-uppercase fs-13">Nombre d'agents</h5>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="ri-team-line display-6 text-muted cfs-22"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h2 class="mb-0 cfs-22"><?= count($agents); ?></h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- end row -->
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->
            </div><!-- end row -->

            <!-- Graphique global -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Statistiques globales</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="chartGlobal" width="400" height="120"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste des agences -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header"><h4 class="card-title mb-0">Agences</h4></div>
                        <div class="card-body">
                            <ul>
                                <?php foreach($agencies as $agency): ?>
                                    <li><?= $agency->display_name; ?> (ID: <?= $agency->ID; ?>)</li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste des agents -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header"><h4 class="card-title mb-0">Agents</h4></div>
                        <div class="card-body">
                            <ul>
                                <?php foreach($agents as $agent): ?>
                                    <li><?= $agent->display_name; ?> (ID: <?= $agent->ID; ?>)</li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Deals (exemple tableau) -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header"><h4 class="card-title mb-0">Deals</h4></div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-nowrap align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Nom du deal</th>
                                            <th>Agent</th>
                                            <th>Montant</th>
                                            <th>Date de clôture</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($deals)): foreach($deals as $deal): ?>
                                            <tr>
                                                <td><?= $deal['name']; ?></td>
                                                <td><?= $deal['agent']; ?></td>
                                                <td><?= $deal['amount']; ?></td>
                                                <td><?= $deal['close_date']; ?></td>
                                            </tr>
                                        <?php endforeach; else: ?>
                                            <tr><td colspan="4">Aucun deal trouvé.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contacts (exemple section) -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header"><h4 class="card-title mb-0">Contacts</h4></div>
                        <div class="card-body">
                            <ul>
                                <?php if (!empty($contacts)): foreach($contacts as $contact): ?>
                                    <li><?= $contact['name']; ?> - <?= $contact['email']; ?> - <?= $contact['phone']; ?></li>
                                <?php endforeach; else: ?>
                                    <li>Aucun contact trouvé.</li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Classement des ventes par agence -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header"><h4 class="card-title mb-0">Classement des ventes par agence</h4></div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Agence</th>
                                            <th>Nombre de ventes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($sales_by_agency)): foreach($sales_by_agency as $row): ?>
                                            <tr>
                                                <td><?= $row['agency_name']; ?></td>
                                                <td><?= $row['sales_count']; ?></td>
                                            </tr>
                                        <?php endforeach; else: ?>
                                            <tr><td colspan="2">Aucune donnée.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- container-fluid -->
    </div><!-- End Page-content -->
</div><!-- end main content-->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('chartGlobal').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Leads', 'Propriétés', 'Ventes'],
            datasets: [{
                label: 'Stats globales',
                data: [<?= $stats['leads']; ?>, <?= $stats['properties']; ?>, <?= $stats['sales']; ?>],
                backgroundColor: ['#36a2eb', '#4bc0c0', '#ff6384']
            }]
        }
    });
</script>
