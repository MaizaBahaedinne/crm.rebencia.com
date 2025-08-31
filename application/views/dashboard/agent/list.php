<script src="<?php echo base_url('assets/js/pages/team.init.js'); ?>"></script>

 <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                                <h4 class="mb-sm-0">Team</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Pages</a></li>
                                        <li class="breadcrumb-item active">Team</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="card">
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-sm-4">
                                    <div class="search-box">
                                        <input type="text" class="form-control" id="searchMemberList" placeholder="Search for name or designation...">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-sm-auto ms-auto">
                                    <div class="list-grid-nav hstack gap-1">
                                        <button type="button" id="grid-view-button" class="btn btn-soft-info nav-link btn-icon fs-14 active filter-button material-shadow-none"><i class="ri-grid-fill"></i></button>
                                        <button type="button" id="list-view-button" class="btn btn-soft-info nav-link  btn-icon fs-14 filter-button material-shadow-none"><i class="ri-list-unordered"></i></button>
                                        <button type="button" id="dropdownMenuLink1" data-bs-toggle="dropdown" aria-expanded="false" class="btn btn-soft-info btn-icon material-shadow-none fs-14"><i class="ri-more-2-fill"></i></button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink1">
                                            <li><a class="dropdown-item" href="#">All</a></li>
                                            <li><a class="dropdown-item" href="#">Last Week</a></li>
                                            <li><a class="dropdown-item" href="#">Last Month</a></li>
                                            <li><a class="dropdown-item" href="#">Last Year</a></li>
                                        </ul>
                                        <button class="btn btn-success addMembers-modal" data-bs-toggle="modal" data-bs-target="#addmemberModal"><i class="ri-add-fill me-1 align-bottom"></i> Add Members</button>
                                    </div>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                    </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card">
                                                    <div class="card-body table-responsive">
                                                        <table class="table table-sm align-middle">
                                                            <thead>
                                                                <tr>
                                                                    <th>ID</th>
                                                                    <th>Nom</th>
                                                                    <th>Email</th>
                                                                    <th>Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php if(!empty($agents)): foreach($agents as $a): ?>
                                                                    <tr>
                                                                        <td><?= (int)$a->ID; ?></td>
                                                                        <td><?= htmlspecialchars($a->display_name ?? ($a->user_login ?? '')); ?></td>
                                                                        <td><a href="mailto:<?= htmlspecialchars($a->user_email); ?>"><?= htmlspecialchars($a->user_email); ?></a></td>
                                                                        <td>
                                                                            <a href="<?= base_url('agents/'.(int)$a->ID); ?>" class="btn btn-xs btn-primary">Détails</a>
                                                                            <a href="<?= base_url('agents/'.(int)$a->ID.'/proprietes'); ?>" class="btn btn-xs btn-outline-secondary">Propriétés</a>
                                                                        </td>
                                                                    </tr>
                                                                <?php endforeach; else: ?>
                                                                    <tr><td colspan="4" class="text-center text-muted">Aucun agent</td></tr>
                                                                <?php endif; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                </div><!-- container-fluid -->
            </div><!-- End Page-content -->

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
        <!-- end main content-->
