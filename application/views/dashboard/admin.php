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
                            <div class="row row-cols-xxl-3 row-cols-md-3 row-cols-1 g-0">
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
