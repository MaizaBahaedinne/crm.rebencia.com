<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- Page Title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">Profil Agent</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="<?php echo base_url('agents'); ?>">Agents</a></li>
                                <li class="breadcrumb-item active"><?php echo htmlspecialchars($agent->agent_name); ?></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Agent Profile Header -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm overflow-hidden">
                        <div class="card-body p-0">
                            <!-- Cover Photo -->
                            <div class="profile-cover-img bg-primary" style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <div class="position-absolute top-0 end-0 p-3">
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="ri-more-line"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="mailto:<?php echo $agent->agent_email; ?>"><i class="ri-mail-line me-2"></i>Envoyer email</a></li>
                                            <li><a class="dropdown-item" href="<?php echo base_url('properties?agent=' . $agent->user_id); ?>"><i class="ri-home-line me-2"></i>Voir ses propriétés</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item" href="#"><i class="ri-edit-line me-2"></i>Modifier</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Profile Info -->
                            <div class="p-4 pt-5" style="margin-top: -75px;">
                                <div class="d-flex align-items-end">
                                    <div class="avatar-xxl flex-shrink-0 me-4">
                                        <img src="<?php echo $agent->agent_avatar; ?>" alt="<?php echo htmlspecialchars($agent->agent_name); ?>" 
                                             class="img-fluid rounded-circle border border-4 border-white shadow">
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                                            <div>
                                                <h4 class="mb-1"><?php echo htmlspecialchars($agent->agent_name); ?></h4>
                                                <p class="text-muted mb-1"><?php echo htmlspecialchars($agent->agent_email); ?></p>
                                                <?php if (!empty($agent->position)) : ?>
                                                <p class="text-muted mb-2"><i class="ri-briefcase-line me-1"></i><?php echo htmlspecialchars($agent->position); ?></p>
                                                <?php endif; ?>
                                                <div class="d-flex align-items-center gap-2 mb-2">
                                                    <span class="badge bg-<?php echo $agent->is_active ? 'success' : 'danger'; ?>-subtle text-<?php echo $agent->is_active ? 'success' : 'danger'; ?>">
                                                        <?php echo $agent->is_active ? 'Actif' : 'Inactif'; ?>
                                                    </span>
                                                    <?php if ($agent->agency_name): ?>
                                                    <span class="badge bg-primary-subtle text-primary">
                                                        <i class="ri-building-line me-1"></i><?php echo htmlspecialchars($agent->agency_name); ?>
                                                    </span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <div class="d-flex gap-2 mb-2">
                                                    <?php if (!empty($agent->phone)) : ?>
                                                    <a href="tel:<?php echo $agent->phone; ?>" class="btn btn-primary btn-sm">
                                                        <i class="ri-phone-line me-1"></i><?php echo $agent->phone; ?>
                                                    </a>
                                                    <?php endif; ?>
                                                    <?php if (!empty($agent->mobile)) : ?>
                                                    <a href="tel:<?php echo $agent->mobile; ?>" class="btn btn-success btn-sm">
                                                        <i class="ri-smartphone-line me-1"></i><?php echo $agent->mobile; ?>
                                                    </a>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if (!empty($agent->whatsapp)) : ?>
                                                <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $agent->whatsapp); ?>" class="btn btn-success btn-sm" target="_blank">
                                                    <i class="ri-whatsapp-line me-1"></i>WhatsApp
                                                </a>
                                                <?php endif; ?>
                                                <?php if (!empty($agent->website)) : ?>
                                                <a href="<?php echo $agent->website; ?>" class="btn btn-info btn-sm" target="_blank">
                                                    <i class="ri-global-line me-1"></i>Site Web
                                                </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Row -->
            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-3">
                                        <i class="ri-home-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-medium text-muted mb-1">Propriétés</p>
                                    <h4 class="mb-0"><?php echo $agent->properties_count; ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-success-subtle text-success rounded-circle fs-3">
                                        <i class="ri-eye-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-medium text-muted mb-1">Vues Totales</p>
                                    <h4 class="mb-0"><?php echo number_format($agent->total_views ?? 0); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-info-subtle text-info rounded-circle fs-3">
                                        <i class="ri-mail-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-medium text-muted mb-1">Contacts</p>
                                    <h4 class="mb-0"><?php echo $agent->contacts_count ?? 0; ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-warning-subtle text-warning rounded-circle fs-3">
                                        <i class="ri-star-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-medium text-muted mb-1">Note Moyenne</p>
                                    <h4 class="mb-0">4.8/5</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Agent Details & Properties -->
            <div class="row">
                <!-- Agent Information -->
                <div class="col-xl-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Informations personnelles</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="ps-0 text-muted">Email:</td>
                                            <td class="pe-0"><?php echo htmlspecialchars($agent->agent_email); ?></td>
                                        </tr>
                                        <?php if (!empty($agent->phone)) : ?>
                                        <tr>
                                            <td class="ps-0 text-muted">Téléphone:</td>
                                            <td class="pe-0"><?php echo htmlspecialchars($agent->phone); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($agent->mobile)) : ?>
                                        <tr>
                                            <td class="ps-0 text-muted">Mobile:</td>
                                            <td class="pe-0"><?php echo htmlspecialchars($agent->mobile); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($agent->whatsapp)) : ?>
                                        <tr>
                                            <td class="ps-0 text-muted">WhatsApp:</td>
                                            <td class="pe-0"><?php echo htmlspecialchars($agent->whatsapp); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($agent->website)) : ?>
                                        <tr>
                                            <td class="ps-0 text-muted">Site Web:</td>
                                            <td class="pe-0">
                                                <a href="<?php echo $agent->website; ?>" target="_blank" class="text-decoration-none">
                                                    <?php echo htmlspecialchars($agent->website); ?> <i class="ri-external-link-line"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($agent->position)) : ?>
                                        <tr>
                                            <td class="ps-0 text-muted">Poste:</td>
                                            <td class="pe-0"><?php echo htmlspecialchars($agent->position); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($agent->address)) : ?>
                                        <tr>
                                            <td class="ps-0 text-muted">Adresse:</td>
                                            <td class="pe-0"><?php echo htmlspecialchars($agent->address); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <tr>
                                            <td class="ps-0 text-muted">Inscrit le:</td>
                                            <td class="pe-0"><?php echo date('d/m/Y', strtotime($agent->registration_date)); ?></td>
                                        </tr>
                                        <?php if ($agent->agency_name): ?>
                                        <tr>
                                            <td class="ps-0 text-muted">Agence:</td>
                                            <td class="pe-0"><?php echo htmlspecialchars($agent->agency_name); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <?php if (!empty($agent->description)) : ?>
                            <hr>
                            <div>
                                <h6>Bio</h6>
                                <div class="text-muted"><?php echo strip_tags($agent->description, '<p><br><strong><em><ul><li><ol>'); ?></div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <?php if (!empty($agent->facebook) || !empty($agent->twitter) || !empty($agent->linkedin) || !empty($agent->instagram)) : ?>
                    <div class="card border-0 shadow-sm">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Réseaux sociaux</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-wrap gap-2">
                                <?php if (!empty($agent->facebook)) : ?>
                                <a href="<?php echo $agent->facebook; ?>" class="btn btn-primary btn-sm" target="_blank">
                                    <i class="ri-facebook-line me-1"></i>Facebook
                                </a>
                                <?php endif; ?>
                                <?php if (!empty($agent->twitter)) : ?>
                                <a href="<?php echo $agent->twitter; ?>" class="btn btn-info btn-sm" target="_blank">
                                    <i class="ri-twitter-line me-1"></i>Twitter
                                </a>
                                <?php endif; ?>
                                <?php if (!empty($agent->linkedin)) : ?>
                                <a href="<?php echo $agent->linkedin; ?>" class="btn btn-primary btn-sm" target="_blank">
                                    <i class="ri-linkedin-line me-1"></i>LinkedIn
                                </a>
                                <?php endif; ?>
                                <?php if (!empty($agent->instagram)) : ?>
                                <a href="<?php echo $agent->instagram; ?>" class="btn btn-danger btn-sm" target="_blank">
                                    <i class="ri-instagram-line me-1"></i>Instagram
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Agent Properties -->
                <div class="col-xl-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Propriétés de l'agent (<?php echo count($properties); ?>)</h5>
                            <a href="<?php echo base_url('properties?agent=' . $agent->user_id); ?>" class="btn btn-primary btn-sm">
                                Voir toutes
                            </a>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($properties)) : ?>
                            <div class="row">
                                <?php foreach (array_slice($properties, 0, 6) as $property) : ?>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card border h-100">
                                        <div class="position-relative">
                                            <img src="<?php echo $property->thumbnail; ?>" alt="<?php echo htmlspecialchars($property->title); ?>" class="card-img-top" style="height: 180px; object-fit: cover;">
                                            <div class="position-absolute top-0 start-0 p-2">
                                                <span class="badge bg-<?php echo $property->status == 'for-rent' ? 'info' : 'success'; ?>">
                                                    <?php echo $property->status == 'for-rent' ? 'À louer' : 'À vendre'; ?>
                                                </span>
                                            </div>
                                            <div class="position-absolute top-0 end-0 p-2">
                                                <span class="badge bg-dark"><?php echo $property->property_type; ?></span>
                                            </div>
                                        </div>
                                        <div class="card-body p-3">
                                            <h6 class="card-title mb-2">
                                                <a href="<?php echo base_url('properties/view/' . $property->ID); ?>" class="text-decoration-none">
                                                    <?php echo htmlspecialchars($property->title); ?>
                                                </a>
                                            </h6>
                                            <p class="text-muted small mb-2">
                                                <i class="ri-map-pin-line me-1"></i>
                                                <?php echo htmlspecialchars($property->location); ?>
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="fw-bold text-primary">
                                                    <?php echo number_format($property->price, 0, ',', ' '); ?> TND
                                                </div>
                                                <small class="text-muted">
                                                    <i class="ri-eye-line me-1"></i><?php echo $property->views; ?>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <?php if (count($properties) > 6) : ?>
                            <div class="text-center mt-3">
                                <a href="<?php echo base_url('properties?agent=' . $agent->user_id); ?>" class="btn btn-outline-primary">
                                    Voir les <?php echo count($properties) - 6; ?> propriétés restantes
                                </a>
                            </div>
                            <?php endif; ?>
                            
                            <?php else : ?>
                            <div class="text-center py-4">
                                <div class="avatar-lg mx-auto mb-3">
                                    <div class="avatar-title bg-light text-muted rounded-circle fs-24">
                                        <i class="ri-home-line"></i>
                                    </div>
                                </div>
                                <h6 class="mb-2">Aucune propriété</h6>
                                <p class="text-muted">Cet agent n'a pas encore ajouté de propriétés.</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-xxl {
    width: 6rem;
    height: 6rem;
}

.avatar-xxl img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.profile-cover-img {
    position: relative;
}
</style>
