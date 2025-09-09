<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- Page Title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">Debug Agent</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="<?php echo base_url('agents'); ?>">Agents</a></li>
                                <li class="breadcrumb-item active">Debug</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Debug Info -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Informations de débogage</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-warning">
                                <strong>User ID recherché :</strong> <?php echo htmlspecialchars($user_id); ?><br>
                                <strong>Message :</strong> <?php echo htmlspecialchars($debug_info); ?>
                            </div>

                            <h6 class="mt-4">Agents disponibles :</h6>
                            <?php if (!empty($all_agents)) : ?>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>User ID</th>
                                                <th>Agent ID</th>
                                                <th>Nom</th>
                                                <th>Email</th>
                                                <th>Agence</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($all_agents as $agent) : ?>
                                            <tr>
                                                <td><?php echo $agent->user_id; ?></td>
                                                <td><?php echo $agent->agent_id; ?></td>
                                                <td><?php echo htmlspecialchars($agent->agent_name); ?></td>
                                                <td><?php echo htmlspecialchars($agent->agent_email); ?></td>
                                                <td><?php echo htmlspecialchars($agent->agency_name ?: 'Indépendant'); ?></td>
                                                <td>
                                                    <a href="<?php echo base_url('agents/view/' . $agent->user_id); ?>" class="btn btn-sm btn-primary">
                                                        Voir
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else : ?>
                                <div class="alert alert-info">
                                    Aucun agent trouvé dans la base de données.
                                </div>
                            <?php endif; ?>

                            <div class="mt-4">
                                <a href="<?php echo base_url('agents'); ?>" class="btn btn-secondary">
                                    <i class="ri-arrow-left-line me-1"></i>Retour à la liste
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
