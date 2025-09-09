<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- Page Title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">Profil Agent (Simple)</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="<?php echo base_url('agents'); ?>">Agents</a></li>
                                <li class="breadcrumb-item active"><?php echo htmlspecialchars($agent->agent_name ?? 'Agent'); ?></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Agent Basic Info -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Informations Agent</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <img src="<?php echo $agent->agent_avatar ?? base_url('assets/images/users/avatar-1.jpg'); ?>" 
                                         alt="<?php echo htmlspecialchars($agent->agent_name ?? 'Agent'); ?>" 
                                         class="img-fluid rounded-circle" style="max-width: 150px;">
                                </div>
                                <div class="col-md-9">
                                    <h4><?php echo htmlspecialchars($agent->agent_name ?? 'Nom non disponible'); ?></h4>
                                    <p class="text-muted"><?php echo htmlspecialchars($agent->agent_email ?? 'Email non disponible'); ?></p>
                                    
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <strong>User ID:</strong> <?php echo $agent->user_id ?? 'N/A'; ?><br>
                                            <strong>Agent ID:</strong> <?php echo $agent->agent_id ?? 'N/A'; ?><br>
                                            <strong>Statut:</strong> <?php echo $agent->is_active ? 'Actif' : 'Inactif'; ?><br>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Téléphone:</strong> <?php echo htmlspecialchars($agent->phone ?? 'Non renseigné'); ?><br>
                                            <strong>Mobile:</strong> <?php echo htmlspecialchars($agent->mobile ?? 'Non renseigné'); ?><br>
                                            <strong>Agence:</strong> <?php echo htmlspecialchars($agent->agency_name ?? 'Indépendant'); ?><br>
                                        </div>
                                    </div>

                                    <?php if (!empty($agent->description)) : ?>
                                    <div class="mt-3">
                                        <strong>Description:</strong><br>
                                        <p><?php echo nl2br(htmlspecialchars($agent->description)); ?></p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Properties Section -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Propriétés (<?php echo count($properties ?? []); ?>)</h5>
                            <?php if ($agent->agent_id ?? null) : ?>
                            <a href="<?php echo base_url('properties?agent=' . $agent->agent_id); ?>" class="btn btn-primary btn-sm">
                                Voir toutes
                            </a>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($properties)) : ?>
                            <div class="row">
                                <?php foreach ($properties as $property) : ?>
                                <div class="col-md-4 mb-3">
                                    <div class="card border">
                                        <?php if (!empty($property->thumbnail)) : ?>
                                        <img src="<?php echo $property->thumbnail; ?>" alt="<?php echo htmlspecialchars($property->title); ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                                        <?php endif; ?>
                                        <div class="card-body">
                                            <h6 class="card-title"><?php echo htmlspecialchars($property->title); ?></h6>
                                            <p class="text-muted small"><?php echo htmlspecialchars($property->location ?? ''); ?></p>
                                            <div class="d-flex justify-content-between">
                                                <span class="fw-bold text-primary"><?php echo number_format($property->price ?? 0, 0, ',', ' '); ?> TND</span>
                                                <small class="text-muted"><?php echo $property->views ?? 0; ?> vues</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php else : ?>
                            <div class="text-center py-4">
                                <i class="ri-home-line fs-1 text-muted"></i>
                                <h6 class="mt-2">Aucune propriété</h6>
                                <p class="text-muted">Cet agent n'a pas encore de propriétés.</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Debug Info -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Informations de débogage</h5>
                        </div>
                        <div class="card-body">
                            <pre><?php echo json_encode($agent, JSON_PRETTY_PRINT); ?></pre>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="row mt-4">
                <div class="col-12">
                    <a href="<?php echo base_url('agents'); ?>" class="btn btn-secondary">
                        <i class="ri-arrow-left-line me-1"></i>Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
