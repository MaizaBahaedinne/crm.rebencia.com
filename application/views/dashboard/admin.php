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
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                        <li class="breadcrumb-item active">CRM</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card crm-widget">
                                <div class="card-body p-0">
                                    <div class="row row-cols-xxl-5 row-cols-md-3 row-cols-1 g-0">
                                        <div class="col">
                                            <div class="py-4 px-3">
                                                <h5 class="text-muted text-uppercase fs-13">Nombre d'agences</h5>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <i class="ri-space-ship-line display-6 text-muted cfs-22"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h2 class="mb-0 cfs-22"><span class="counter-value" data-target="<?= (int)($count_agencies ?? 0); ?>">0</span></h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- end col -->
                                        <div class="col">
                                            <div class="mt-3 mt-md-0 py-4 px-3">
                                                <h5 class="text-muted text-uppercase fs-13">Nombre d'agents</h5>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <i class="ri-exchange-dollar-line display-6 text-muted cfs-22"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h2 class="mb-0 cfs-22"><span class="counter-value" data-target="<?= (int)($count_agents ?? 0); ?>">0</span></h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- end col -->
                                        <div class="col">
                                            <div class="mt-3 mt-md-0 py-4 px-3">
                                                <h5 class="text-muted text-uppercase fs-13">Nombre de clients</h5>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <i class="ri-pulse-line display-6 text-muted cfs-22"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h2 class="mb-0 cfs-22"><span class="counter-value" data-target="<?= (int)($count_clients ?? 0); ?>">0</span></h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- end col -->
                                        <div class="col">
                                            <div class="mt-3 mt-lg-0 py-4 px-3">
                                                <h5 class="text-muted text-uppercase fs-13">Nombre de transactions</h5>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <i class="ri-trophy-line display-6 text-muted cfs-22"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h2 class="mb-0 cfs-22"><span class="counter-value" data-target="<?= (int)($count_transactions ?? 0); ?>">0</span></h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- end col -->
                                        <div class="col">
                                            <div class="mt-3 mt-lg-0 py-4 px-3">
                                                <h5 class="text-muted text-uppercase fs-13">Nombre d'estimations</h5>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <i class="ri-service-line display-6 text-muted cfs-22"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h2 class="mb-0 cfs-22"><span class="counter-value" data-target="<?= (int)($count_estimations ?? 0); ?>">0</span></h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- end col -->
                                    </div><!-- end row -->
                                </div><!-- end card body -->
                            </div><!-- end card -->
                        </div><!-- end col -->
                    </div><!-- end row -->

                    <div class="row">
                        <div class="col-xxl-3 col-md-6">
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Prévisions des ventes</h4>
                                    <div class="flex-shrink-0">
                                        <div class="dropdown card-header-dropdown">
                                            <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="fw-semibold text-uppercase fs-12">Trier par : </span><span class="text-muted">Nov 2021<i class="mdi mdi-chevron-down ms-1"></i></span>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="#">Oct 2021</a>
                                                <a class="dropdown-item" href="#">Nov 2021</a>
                                                <a class="dropdown-item" href="#">Déc 2021</a>
                                                <a class="dropdown-item" href="#">Jan 2022</a>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- end card header -->
                                <div class="card-body pb-0">
                                    <div id="sales-forecast-chart" data-colors='["--vz-primary", "--vz-success", "--vz-warning"]' data-colors-minimal='["--vz-primary-rgb, 0.75", "--vz-primary", "--vz-primary-rgb, 0.55"]' data-colors-creative='["--vz-primary", "--vz-secondary", "--vz-info"]' data-colors-corporate='["--vz-primary", "--vz-success", "--vz-secondary"]' data-colors-galaxy='["--vz-primary", "--vz-secondary", "--vz-info"]' data-colors-classic='["--vz-primary", "--vz-warning", "--vz-secondary"]' class="apex-charts" dir="ltr"></div>
                                </div>
                            </div><!-- end card -->
                        </div><!-- end col -->

                        <div class="col-xxl-3 col-md-6">
                            <div class="card card-height-100">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Type de transaction</h4>
                                    <div class="flex-shrink-0">
                                        <div class="dropdown card-header-dropdown">
                                            <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="fw-semibold text-uppercase fs-12">Trier par : </span><span class="text-muted">Mensuel<i class="mdi mdi-chevron-down ms-1"></i></span>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="#">Aujourd'hui</a>
                                                <a class="dropdown-item" href="#">Hebdo</a>
                                                <a class="dropdown-item" href="#">Mensuel</a>
                                                <a class="dropdown-item" href="#">Annuel</a>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- end card header -->
                                <div class="card-body pb-0">
                                    <div id="deal-type-charts" data-colors='["--vz-warning", "--vz-danger", "--vz-success"]' data-colors-minimal='["--vz-primary-rgb, 0.15", "--vz-primary-rgb, 0.35", "--vz-primary-rgb, 0.45"]' data-colors-modern='["--vz-warning", "--vz-secondary", "--vz-success"]' data-colors-interactive='["--vz-warning", "--vz-info", "--vz-primary"]' data-colors-corporate='["--vz-secondary", "--vz-info", "--vz-success"]' data-colors-classic='["--vz-secondary", "--vz-danger", "--vz-success"]' class="apex-charts" dir="ltr"></div>
                                </div><!-- end card body -->
                            </div><!-- end card -->
                        </div><!-- end col -->
  
                        <div class="col-xxl-6">
                            <div class="card card-height-100">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Synthèse financière</h4>
                                    <div class="flex-shrink-0">
                                        <div class="dropdown card-header-dropdown">
                                            <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="fw-semibold text-uppercase fs-12">Trier par : </span><span class="text-muted">Année en cours<i class="mdi mdi-chevron-down ms-1"></i></span>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="#">Aujourd'hui</a>
                                                <a class="dropdown-item" href="#">Semaine passée</a>
                                                <a class="dropdown-item" href="#">Mois dernier</a>
                                                <a class="dropdown-item" href="#">Année en cours</a>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- end card header -->
                                <div class="card-body px-0">
                                    <ul class="list-inline main-chart text-center mb-0">
                                        <li class="list-inline-item chart-border-left me-0 border-0">
                                            <h4 class="text-primary">$584k <span class="text-muted d-inline-block fs-13 align-middle ms-2">Recettes</span></h4>
                                        </li>
                                        <li class="list-inline-item chart-border-left me-0">
                                            <h4>$497k<span class="text-muted d-inline-block fs-13 align-middle ms-2">Dépenses</span>
                                            </h4>
                                        </li>
                                        <li class="list-inline-item chart-border-left me-0">
                                            <h4><span data-plugin="counterup">3.6</span>%<span class="text-muted d-inline-block fs-13 align-middle ms-2">Marge</span></h4>
                                        </li>
                                    </ul>

                                    <div id="revenue-expenses-charts" data-colors='["--vz-success", "--vz-danger"]' data-colors-minimal='["--vz-primary", "--vz-info"]' data-colors-interactive='["--vz-info", "--vz-primary"]' data-colors-galaxy='["--vz-primary", "--vz-secondary"]' data-colors-classic='["--vz-primary", "--vz-secondary"]' class="apex-charts" dir="ltr"></div>
                                </div>
                            </div><!-- end card -->
                        </div><!-- end col -->
                    </div><!-- end row -->

                    <div class="row">
                        <div class="col-xl-7">
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Transactions</h4>
                                    <div class="flex-shrink-0">
                                        <div class="dropdown card-header-dropdown">
                                            <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="text-muted">02 Nov 2021 to 31 Dec 2021<i class="mdi mdi-chevron-down ms-1"></i></span>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="#">Today</a>
                                                <a class="dropdown-item" href="#">Last Week</a>
                                                <a class="dropdown-item" href="#">Last Month</a>
                                                <a class="dropdown-item" href="#">Current Year</a>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- end card header -->

                                <div class="card-body">
                                    <div class="table-responsive table-card">
                                        <table class="table table-borderless table-hover table-nowrap align-middle mb-0">
                                            <thead class="table-light">
                                                <tr class="text-muted">
                                                    <th scope="col">Nom</th>
                                                    <th scope="col" style="width: 20%;">Dernier contact</th>
                                                    <th scope="col">Commercial</th>
                                                    <th scope="col" style="width: 16%;">Statut</th>
                                                    <th scope="col" style="width: 12%;" class="text-end">Valeur</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                                if(!function_exists('tx_badge_class')){
                                                    function tx_badge_class($s){
                                                        $s = mb_strtolower(trim($s));
                                                        $map = [
                                                            'gagné'=>'success','won'=>'success','closed'=>'success',
                                                            'nouveau lead'=>'info','lead'=>'info','nouveau'=>'info',
                                                            'appel intro'=>'warning','en cours'=>'warning','open'=>'warning','actif'=>'warning',
                                                            'bloqué'=>'danger','perdu'=>'danger','lost'=>'danger','annulé'=>'secondary','cancelled'=>'secondary'
                                                        ];
                                                        return isset($map[$s]) ? $map[$s] : 'secondary';
                                                    }
                                                }
                                            ?>
                                            <?php if(!empty($recent_transactions)): ?>
                                                <?php foreach($recent_transactions as $tr): ?>
                                                    <?php
                                                        $titre = $tr['titre'] ?? '—';
                                                        $commercial = $tr['commercial'] ?? '—';
                                                        $statut = $tr['statut'] ?? '—';
                                                        $montant = isset($tr['montant']) ? number_format((float)$tr['montant'], 0, ',', ' ').' €' : '—';
                                                        // Dernier contact : date_cloture si existe sinon updated_at sinon created_at
                                                        $date_src = $tr['date_cloture'] ?? ($tr['updated_at'] ?? ($tr['created_at'] ?? null));
                                                        $dernier = $date_src ? date('d/m/Y', strtotime($date_src)) : '—';
                                                        $badgeClass = 'bg-'.tx_badge_class($statut).'-subtle text-'.tx_badge_class($statut);
                                                    ?>
                                                    <tr>
                                                        <td class="fw-medium"><?= htmlspecialchars($titre); ?></td>
                                                        <td><?= $dernier; ?></td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-grow-1">
                                                                    <span class="text-body fw-medium"><?= htmlspecialchars($commercial); ?></span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td><span class="badge <?= $badgeClass; ?> p-2" style="min-width:90px; display:inline-block;"><?= htmlspecialchars($statut); ?></span></td>
                                                        <td class="text-end"><div class="text-nowrap"><?= $montant; ?></div></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr><td colspan="5" class="text-center text-muted py-4">Aucune transaction trouvée</td></tr>
                                            <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php if(!empty($recent_transactions_total) && $recent_transactions_pages>1): ?>
                                        <div class="d-flex justify-content-between align-items-center pt-3">
                                            <small class="text-muted">Page <?= (int)$recent_transactions_page; ?> / <?= (int)$recent_transactions_pages; ?> (<?= (int)$recent_transactions_total; ?>)</small>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <?php for($p=1;$p<=$recent_transactions_pages;$p++): ?>
                                                    <a class="btn btn-outline-secondary <?= $p==$recent_transactions_page?'active':''; ?>" href="?tpage=<?= $p; ?>#transactions"><?= $p; ?></a>
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div><!-- end card -->
                        </div><!-- end col -->

                        <div class="col-xl-5">
                            <div class="card card-height-100">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Calendrier des RDV</h4>
                                    <div class="flex-shrink-0">
                                        <div class="dropdown card-header-dropdown">
                                            <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="text-muted"><i class="ri-settings-4-line align-bottom me-1 fs-15"></i>Paramètres</span>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="#">Edit</a>
                                                <a class="dropdown-item" href="#">Remove</a>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- end card header -->

                                <div class="card-body p-0">

                                    <div class="align-items-center p-3 justify-content-between d-flex">
                                        <div class="flex-shrink-0">
                                            <div class="text-muted"><span class="fw-semibold">4</span> sur <span class="fw-semibold">10</span> restantes</div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-success"><i class="ri-add-line align-middle me-1"></i> Ajouter tâche</button>
                                    </div><!-- end card header -->

                                    <div data-simplebar style="max-height: 219px;">
                                        <ul class="list-group list-group-flush border-dashed px-3">
                                            <li class="list-group-item ps-0">
                                                <div class="d-flex align-items-start">
                                                    <div class="form-check ps-0 flex-sharink-0">
                                                        <input type="checkbox" class="form-check-input ms-0" id="task_one">
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <label class="form-check-label mb-0 ps-2" for="task_one">Review and make sure nothing slips through cracks</label>
                                                    </div>
                                                    <div class="flex-shrink-0 ms-2">
                                                        <p class="text-muted fs-12 mb-0">15 Sep, 2021</p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item ps-0">
                                                <div class="d-flex align-items-start">
                                                    <div class="form-check ps-0 flex-sharink-0">
                                                        <input type="checkbox" class="form-check-input ms-0" id="task_two">
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <label class="form-check-label mb-0 ps-2" for="task_two">Send meeting invites for sales upcampaign</label>
                                                    </div>
                                                    <div class="flex-shrink-0 ms-2">
                                                        <p class="text-muted fs-12 mb-0">20 Sep, 2021</p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item ps-0">
                                                <div class="d-flex align-items-start">
                                                    <div class="form-check flex-sharink-0 ps-0">
                                                        <input type="checkbox" class="form-check-input ms-0" id="task_three">
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <label class="form-check-label mb-0 ps-2" for="task_three">Weekly closed sales won checking with sales team</label>
                                                    </div>
                                                    <div class="flex-shrink-0 ms-2">
                                                        <p class="text-muted fs-12 mb-0">24 Sep, 2021</p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item ps-0">
                                                <div class="d-flex align-items-start">
                                                    <div class="form-check ps-0 flex-sharink-0">
                                                        <input type="checkbox" class="form-check-input ms-0" id="task_four">
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <label class="form-check-label mb-0 ps-2" for="task_four">Add notes that can be viewed from the individual view</label>
                                                    </div>
                                                    <div class="flex-shrink-0 ms-2">
                                                        <p class="text-muted fs-12 mb-0">27 Sep, 2021</p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item ps-0">
                                                <div class="d-flex align-items-start">
                                                    <div class="form-check ps-0 flex-sharink-0">
                                                        <input type="checkbox" class="form-check-input ms-0" id="task_five">
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <label class="form-check-label mb-0 ps-2" for="task_five">Move stuff to another page</label>
                                                    </div>
                                                    <div class="flex-shrink-0 ms-2">
                                                        <p class="text-muted fs-12 mb-0">27 Sep, 2021</p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item ps-0">
                                                <div class="d-flex align-items-start">
                                                    <div class="form-check ps-0 flex-sharink-0">
                                                        <input type="checkbox" class="form-check-input ms-0" id="task_six">
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <label class="form-check-label mb-0 ps-2" for="task_six">Styling wireframe design and documentation for velzon admin</label>
                                                    </div>
                                                    <div class="flex-shrink-0 ms-2">
                                                        <p class="text-muted fs-12 mb-0">27 Sep, 2021</p>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul><!-- end ul -->
                                    </div>
                                    <div class="p-3 pt-2">
                                        <a href="javascript:void(0);" class="text-muted text-decoration-underline">Voir plus...</a>
                                    </div>
                                </div><!-- end card body -->
                            </div><!-- end card -->
                        </div><!-- end col -->
                    </div><!-- end row -->

                    <div class="row">
                        <div class="col-xxl-5">
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Activités à venir</h4>
                                    <div class="flex-shrink-0">
                                        <div class="dropdown card-header-dropdown">
                                            <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="text-muted fs-18"><i class="mdi mdi-dots-vertical"></i></span>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="#">Edit</a>
                                                <a class="dropdown-item" href="#">Remove</a>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- end card header -->
                                <div class="card-body pt-0">
                                    <ul class="list-group list-group-flush border-dashed">
                                        <?php if(!empty($upcoming_tasks)): ?>
                                            <?php foreach($upcoming_tasks as $task): ?>
                                                <li class="list-group-item ps-0">
                                                    <div class="row align-items-center g-3">
                                                        <div class="col-auto">
                                                            <div class="avatar-sm p-1 py-2 h-auto bg-light rounded-3 material-shadow">
                                                                <div class="text-center">
                                                                    <h5 class="mb-0"><?= date('d', strtotime($task['createdDtm'])) ?></h5>
                                                                    <div class="text-muted"><?= strftime('%a', strtotime($task['createdDtm'])) ?></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <h5 class="text-muted mt-0 mb-1 fs-13">&nbsp;</h5>
                                                            <a href="#" class="text-reset fs-14 mb-0"><?= htmlspecialchars($task['taskTitle']) ?></a>
                                                        </div>
                                                        <div class="col-sm-auto">
                                                            <span class="badge bg-info-subtle text-info">Tâche</span>
                                                        </div>
                                                    </div>
                                                </li>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <li class="list-group-item ps-0 text-center text-muted">Aucune activité à venir</li>
                                        <?php endif; ?>
                                    </ul><!-- end -->
                                    <div class="align-items-center mt-2 row g-3 text-center text-sm-start">
                                        <div class="col-sm">
                                            <div class="text-muted">Affichage de <span class="fw-semibold">4</span> sur <span class="fw-semibold">125</span> résultats
                                            </div>
                                        </div>
                                        <div class="col-sm-auto">
                                            <ul class="pagination pagination-separated pagination-sm justify-content-center justify-content-sm-start mb-0">
                                                <li class="page-item disabled">
                                                    <a href="#" class="page-link">←</a>
                                                </li>
                                                <li class="page-item">
                                                    <a href="#" class="page-link">1</a>
                                                </li>
                                                <li class="page-item active">
                                                    <a href="#" class="page-link">2</a>
                                                </li>
                                                <li class="page-item">
                                                    <a href="#" class="page-link">3</a>
                                                </li>
                                                <li class="page-item">
                                                    <a href="#" class="page-link">→</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div><!-- end card -->
                        </div><!-- end col -->

                        <div class="col-xxl-7">
                            <div class="card card-height-100">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Transactions clôturées</h4>
                                    <div class="flex-shrink-0">
                                        <select class="form-select form-select-sm" aria-label=".form-select-sm example">
                                            <option selected="">Clôturées</option>
                                            <option value="1">Actives</option>
                                            <option value="2">En pause</option>
                                            <option value="3">Annulées</option>
                                        </select>
                                    </div>
                                </div><!-- end card header -->

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-nowrap align-middle mb-0">
                                            <thead>
                                                <tr>
                                                    <th scope="col" style="width: 30%;">Transaction</th>
                                                    <th scope="col" style="width: 30%;">Commercial</th>
                                                    <th scope="col" style="width: 20%;">Montant</th>
                                                    <th scope="col" style="width: 20%;">Date clôture</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                            <?php if(!empty($recent_transactions)): ?>
                                                <?php foreach($recent_transactions as $t): ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($t['titre'] ?? ($t['libelle'] ?? 'Transaction')); ?></td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-grow-1">
                                                                    <span class="text-body fw-medium"><?= htmlspecialchars($t['commercial'] ?? '—'); ?></span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td><?= isset($t['montant']) ? number_format((float)$t['montant'], 0, ',', ' ') . ' €' : '—'; ?></td>
                                                        <td>
                                                            <?php if(!empty($t['date_cloture'])): ?>
                                                                <?= date('d/m/Y', strtotime($t['date_cloture'])); ?>
                                                            <?php else: ?>
                                                                —
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted">Aucune transaction récente</td>
                                                </tr>
                                            <?php endif; ?>
                                            </tbody><!-- end tbody -->
                                        </table><!-- end table -->
                                    </div><!-- end table responsive -->
                                </div><!-- end card body -->
                            </div><!-- end card -->
                        </div><!-- end col -->
                    </div><!-- end row -->

                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <script>document.write(new Date().getFullYear())</script> © Velzon.
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-end d-none d-sm-block">
                                Design & Develop by Themesbrand
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>