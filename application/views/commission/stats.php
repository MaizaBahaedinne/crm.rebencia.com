<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- Titre de la page -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Statistiques des Commissions</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="<?php echo base_url('commission/settings'); ?>">Commissions</a></li>
                                <li class="breadcrumb-item active">Statistiques</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Messages Flash -->
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ri-check-line me-2"></i><?php echo $this->session->flashdata('success'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="ri-error-warning-line me-2"></i><?php echo $this->session->flashdata('error'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Métriques du mois actuel -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ri-calendar-line me-2"></i>Performances du Mois Actuel
                                <span class="badge bg-primary ms-2"><?php echo date('F Y'); ?></span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php 
                            $current_month_data = [
                                'total_transactions' => 0,
                                'total_commission' => 0,
                                'total_agent_commission' => 0,
                                'total_agency_commission' => 0,
                                'sales_count' => 0,
                                'rentals_count' => 0
                            ];
                            
                            if (isset($current_month_stats) && !empty($current_month_stats)) {
                                foreach ($current_month_stats as $stat) {
                                    $current_month_data['total_transactions'] += $stat->total_transactions;
                                    $current_month_data['total_commission'] += $stat->total_commission;
                                    $current_month_data['total_agent_commission'] += $stat->total_agent_commission;
                                    $current_month_data['total_agency_commission'] += $stat->total_agency_commission;
                                    
                                    if ($stat->transaction_type == 'sale') {
                                        $current_month_data['sales_count'] = $stat->total_transactions;
                                    } else {
                                        $current_month_data['rentals_count'] = $stat->total_transactions;
                                    }
                                }
                            }
                            ?>
                            <div class="row">
                                <div class="col-xxl-3 col-md-6">
                                    <div class="card card-height-100 border border-primary">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm flex-shrink-0">
                                                    <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-3">
                                                        <i class="ri-exchange-line"></i>
                                                    </span>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">Total Transactions</p>
                                                    <h4 class="mb-0">
                                                        <span class="counter-value" data-target="<?php echo $current_month_data['total_transactions']; ?>">0</span>
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-xxl-3 col-md-6">
                                    <div class="card card-height-100 border border-success">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm flex-shrink-0">
                                                    <span class="avatar-title bg-success-subtle text-success rounded-circle fs-3">
                                                        <i class="ri-money-euro-circle-line"></i>
                                                    </span>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">Commission Totale</p>
                                                    <h4 class="mb-0">
                                                        <span class="counter-value" data-target="<?php echo number_format($current_month_data['total_commission'], 0, '', ''); ?>">0</span> TND
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-xxl-3 col-md-6">
                                    <div class="card card-height-100 border border-info">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm flex-shrink-0">
                                                    <span class="avatar-title bg-info-subtle text-info rounded-circle fs-3">
                                                        <i class="ri-user-star-line"></i>
                                                    </span>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">Commission Agents</p>
                                                    <h4 class="mb-0">
                                                        <span class="counter-value" data-target="<?php echo number_format($current_month_data['total_agent_commission'], 0, '', ''); ?>">0</span> TND
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-xxl-3 col-md-6">
                                    <div class="card card-height-100 border border-warning">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm flex-shrink-0">
                                                    <span class="avatar-title bg-warning-subtle text-warning rounded-circle fs-3">
                                                        <i class="ri-building-line"></i>
                                                    </span>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">Commission Agence</p>
                                                    <h4 class="mb-0">
                                                        <span class="counter-value" data-target="<?php echo number_format($current_month_data['total_agency_commission'], 0, '', ''); ?>">0</span> TND
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comparaison mensuelle et graphiques -->
            <div class="row">
                <!-- Comparaison avec le mois précédent -->
                <div class="col-xl-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ri-bar-chart-line me-2"></i>Comparaison Mensuelle
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php 
                            $last_month_data = [
                                'total_transactions' => 0,
                                'total_commission' => 0
                            ];
                            
                            if (isset($last_month_stats) && !empty($last_month_stats)) {
                                foreach ($last_month_stats as $stat) {
                                    $last_month_data['total_transactions'] += $stat->total_transactions;
                                    $last_month_data['total_commission'] += $stat->total_commission;
                                }
                            }
                            
                            // Calcul des variations
                            $transactions_variation = 0;
                            $commission_variation = 0;
                            
                            if ($last_month_data['total_transactions'] > 0) {
                                $transactions_variation = (($current_month_data['total_transactions'] - $last_month_data['total_transactions']) / $last_month_data['total_transactions']) * 100;
                            }
                            
                            if ($last_month_data['total_commission'] > 0) {
                                $commission_variation = (($current_month_data['total_commission'] - $last_month_data['total_commission']) / $last_month_data['total_commission']) * 100;
                            }
                            ?>
                            
                            <div class="table-responsive">
                                <table class="table table-borderless align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Métrique</th>
                                            <th>Mois Actuel</th>
                                            <th>Mois Précédent</th>
                                            <th>Variation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-xs me-2">
                                                        <div class="avatar-title bg-soft-primary text-primary rounded">
                                                            <i class="ri-exchange-line"></i>
                                                        </div>
                                                    </div>
                                                    Transactions
                                                </div>
                                            </td>
                                            <td><span class="fw-semibold"><?php echo $current_month_data['total_transactions']; ?></span></td>
                                            <td><span class="text-muted"><?php echo $last_month_data['total_transactions']; ?></span></td>
                                            <td>
                                                <?php if ($transactions_variation >= 0): ?>
                                                    <span class="badge bg-soft-success text-success">
                                                        <i class="ri-arrow-up-line"></i> +<?php echo number_format($transactions_variation, 1); ?>%
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-soft-danger text-danger">
                                                        <i class="ri-arrow-down-line"></i> <?php echo number_format($transactions_variation, 1); ?>%
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-xs me-2">
                                                        <div class="avatar-title bg-soft-success text-success rounded">
                                                            <i class="ri-money-euro-circle-line"></i>
                                                        </div>
                                                    </div>
                                                    Commissions
                                                </div>
                                            </td>
                                            <td><span class="fw-semibold"><?php echo number_format($current_month_data['total_commission'], 2, ',', ' '); ?> TND</span></td>
                                            <td><span class="text-muted"><?php echo number_format($last_month_data['total_commission'], 2, ',', ' '); ?> TND</span></td>
                                            <td>
                                                <?php if ($commission_variation >= 0): ?>
                                                    <span class="badge bg-soft-success text-success">
                                                        <i class="ri-arrow-up-line"></i> +<?php echo number_format($commission_variation, 1); ?>%
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-soft-danger text-danger">
                                                        <i class="ri-arrow-down-line"></i> <?php echo number_format($commission_variation, 1); ?>%
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Répartition par type de transaction -->
                <div class="col-xl-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ri-pie-chart-line me-2"></i>Répartition par Type
                            </h5>
                        </div>
                        <div class="card-body">
                            <div id="transactionTypeChart" class="apex-charts" style="min-height: 300px;"></div>
                            
                            <div class="row mt-3">
                                <div class="col-6">
                                    <div class="border rounded p-3 text-center">
                                        <div class="d-flex align-items-center justify-content-center mb-2">
                                            <div class="avatar-xs me-2">
                                                <div class="avatar-title bg-soft-success text-success rounded">
                                                    <i class="ri-home-line"></i>
                                                </div>
                                            </div>
                                            <span class="fw-medium">Ventes</span>
                                        </div>
                                        <h4 class="mb-0 text-success"><?php echo $current_month_data['sales_count']; ?></h4>
                                        <p class="text-muted mb-0 fs-12">transactions</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="border rounded p-3 text-center">
                                        <div class="d-flex align-items-center justify-content-center mb-2">
                                            <div class="avatar-xs me-2">
                                                <div class="avatar-title bg-soft-info text-info rounded">
                                                    <i class="ri-key-line"></i>
                                                </div>
                                            </div>
                                            <span class="fw-medium">Locations</span>
                                        </div>
                                        <h4 class="mb-0 text-info"><?php echo $current_month_data['rentals_count']; ?></h4>
                                        <p class="text-muted mb-0 fs-12">transactions</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques annuelles -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ri-line-chart-line me-2"></i>Performance Annuelle 
                                <span class="badge bg-info ms-2"><?php echo date('Y'); ?></span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php 
                            $current_year_data = [
                                'total_transactions' => 0,
                                'total_commission' => 0,
                                'total_agent_commission' => 0,
                                'total_agency_commission' => 0,
                                'avg_agent_commission' => 0
                            ];
                            
                            if (isset($current_year_stats) && !empty($current_year_stats)) {
                                foreach ($current_year_stats as $stat) {
                                    $current_year_data['total_transactions'] += $stat->total_transactions;
                                    $current_year_data['total_commission'] += $stat->total_commission;
                                    $current_year_data['total_agent_commission'] += $stat->total_agent_commission;
                                    $current_year_data['total_agency_commission'] += $stat->total_agency_commission;
                                    $current_year_data['avg_agent_commission'] = $stat->avg_agent_commission;
                                }
                            }
                            ?>
                            
                            <div class="row">
                                <div class="col-xl-3 col-md-6">
                                    <div class="card border border-primary">
                                        <div class="card-body text-center">
                                            <div class="avatar-md mx-auto mb-3">
                                                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-2">
                                                    <i class="ri-exchange-line"></i>
                                                </div>
                                            </div>
                                            <h5 class="mb-1"><?php echo $current_year_data['total_transactions']; ?></h5>
                                            <p class="text-muted mb-0">Transactions Totales</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-xl-3 col-md-6">
                                    <div class="card border border-success">
                                        <div class="card-body text-center">
                                            <div class="avatar-md mx-auto mb-3">
                                                <div class="avatar-title bg-success-subtle text-success rounded-circle fs-2">
                                                    <i class="ri-money-euro-circle-line"></i>
                                                </div>
                                            </div>
                                            <h5 class="mb-1"><?php echo number_format($current_year_data['total_commission'], 0, ',', ' '); ?> TND</h5>
                                            <p class="text-muted mb-0">Commission Totale</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-xl-3 col-md-6">
                                    <div class="card border border-info">
                                        <div class="card-body text-center">
                                            <div class="avatar-md mx-auto mb-3">
                                                <div class="avatar-title bg-info-subtle text-info rounded-circle fs-2">
                                                    <i class="ri-user-star-line"></i>
                                                </div>
                                            </div>
                                            <h5 class="mb-1"><?php echo number_format($current_year_data['total_agent_commission'], 0, ',', ' '); ?> TND</h5>
                                            <p class="text-muted mb-0">Commission Agents</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-xl-3 col-md-6">
                                    <div class="card border border-warning">
                                        <div class="card-body text-center">
                                            <div class="avatar-md mx-auto mb-3">
                                                <div class="avatar-title bg-warning-subtle text-warning rounded-circle fs-2">
                                                    <i class="ri-building-line"></i>
                                                </div>
                                            </div>
                                            <h5 class="mb-1"><?php echo number_format($current_year_data['total_agency_commission'], 0, ',', ' '); ?> TND</h5>
                                            <p class="text-muted mb-0">Commission Agence</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Moyenne par transaction -->
                            <?php if ($current_year_data['total_transactions'] > 0): ?>
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="alert alert-info border-0">
                                            <h6 class="alert-heading mb-2">
                                                <i class="ri-information-line me-2"></i>Moyennes Calculées
                                            </h6>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <strong>Commission moyenne par transaction :</strong><br>
                                                    <span class="fs-5 text-primary">
                                                        <?php echo number_format($current_year_data['total_commission'] / $current_year_data['total_transactions'], 2, ',', ' '); ?> TND
                                                    </span>
                                                </div>
                                                <div class="col-md-4">
                                                    <strong>Commission agent moyenne :</strong><br>
                                                    <span class="fs-5 text-success">
                                                        <?php echo number_format($current_year_data['avg_agent_commission'], 2, ',', ' '); ?> TND
                                                    </span>
                                                </div>
                                                <div class="col-md-4">
                                                    <strong>Taux de conversion agence :</strong><br>
                                                    <span class="fs-5 text-warning">
                                                        <?php echo number_format(($current_year_data['total_agency_commission'] / $current_year_data['total_commission']) * 100, 1); ?>%
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ri-links-line me-2"></i>Actions Rapides
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <a href="<?php echo base_url('commission/history'); ?>" class="btn btn-outline-primary btn-lg w-100">
                                        <i class="ri-history-line me-2"></i>Historique Complet
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="<?php echo base_url('commission/calculator'); ?>" class="btn btn-outline-success btn-lg w-100">
                                        <i class="ri-calculator-line me-2"></i>Calculatrice
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="<?php echo base_url('commission/settings'); ?>" class="btn btn-outline-info btn-lg w-100">
                                        <i class="ri-settings-line me-2"></i>Paramètres
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-outline-warning btn-lg w-100" onclick="exportStatistics()">
                                        <i class="ri-download-line me-2"></i>Exporter Stats
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts@latest/dist/apexcharts.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation des compteurs
    const counters = document.querySelectorAll('.counter-value');
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-target'));
        let count = 0;
        const increment = target / 100;
        
        const updateCounter = () => {
            if (count < target) {
                count += increment;
                counter.textContent = Math.floor(count);
                setTimeout(updateCounter, 20);
            } else {
                counter.textContent = target;
            }
        };
        
        updateCounter();
    });

    // Graphique en secteurs pour les types de transactions
    const salesCount = <?php echo $current_month_data['sales_count']; ?>;
    const rentalsCount = <?php echo $current_month_data['rentals_count']; ?>;
    
    if (salesCount > 0 || rentalsCount > 0) {
        const options = {
            series: [salesCount, rentalsCount],
            chart: {
                type: 'donut',
                height: 300
            },
            labels: ['Ventes', 'Locations'],
            colors: ['#28a745', '#17a2b8'],
            legend: {
                position: 'bottom'
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%'
                    }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function (val, opts) {
                    return opts.w.config.series[opts.seriesIndex];
                }
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val + " transactions";
                    }
                }
            }
        };

        const chart = new ApexCharts(document.querySelector("#transactionTypeChart"), options);
        chart.render();
    } else {
        document.getElementById('transactionTypeChart').innerHTML = '<div class="text-center py-5"><p class="text-muted">Aucune donnée à afficher</p></div>';
    }
});

// Fonction pour exporter les statistiques
function exportStatistics() {
    const params = new URLSearchParams();
    params.append('export', 'statistics');
    params.append('format', 'pdf');
    
    window.open('<?php echo base_url("commission/export_stats"); ?>?' + params.toString(), '_blank');
}
</script>
