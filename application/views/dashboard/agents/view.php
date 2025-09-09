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
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <!-- Profile Info -->
                            <div class="d-flex align-items-start">
                                <div class="avatar-xxl flex-shrink-0 me-4">
                                    <?php
                                    // Fonction pour obtenir l'avatar avec fallback Gravatar
                                    function get_agent_avatar($agent) {
                                        if (!empty($agent->agent_avatar) && filter_var($agent->agent_avatar, FILTER_VALIDATE_URL)) {
                                            return $agent->agent_avatar;
                                        }
                                        // Fallback vers Gravatar
                                        $email = !empty($agent->agent_email) ? $agent->agent_email : $agent->user_email;
                                        if (!empty($email)) {
                                            $hash = md5(strtolower(trim($email)));
                                            return "https://www.gravatar.com/avatar/{$hash}?d=identicon&s=200";
                                        }
                                        // Dernier fallback
                                        return base_url('assets/images/users/avatar-1.jpg');
                                    }
                                    $avatar_url = get_agent_avatar($agent);
                                    ?>
                                    <img src="<?php echo $avatar_url; ?>" alt="<?php echo htmlspecialchars($agent->agent_name); ?>" 
                                         class="img-fluid rounded-circle border border-3 border-primary shadow-sm"
                                         onerror="this.onerror=null; this.src='<?php echo base_url('assets/images/users/avatar-1.jpg'); ?>';">
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex flex-wrap align-items-start justify-content-between">
                                        <div>
                                            <h4 class="mb-2"><?php echo htmlspecialchars($agent->agent_name); ?></h4>
                                            <p class="text-muted mb-2"><i class="ri-mail-line me-1"></i><?php echo htmlspecialchars($agent->agent_email); ?></p>
                                            <?php if (!empty($agent->position)) : ?>
                                            <p class="text-muted mb-3"><i class="ri-briefcase-line me-1"></i><?php echo htmlspecialchars($agent->position); ?></p>
                                            <?php endif; ?>
                                            <div class="d-flex align-items-center gap-2 mb-3">
                                                <span class="badge bg-<?php echo $agent->is_active ? 'success' : 'danger'; ?>-subtle text-<?php echo $agent->is_active ? 'success' : 'danger'; ?>">
                                                    <i class="ri-<?php echo $agent->is_active ? 'check' : 'close'; ?>-line me-1"></i><?php echo $agent->is_active ? 'Actif' : 'Inactif'; ?>
                                                </span>
                                                <?php if ($agent->agency_name): ?>
                                                <span class="badge bg-primary-subtle text-primary">
                                                    <i class="ri-building-line me-1"></i><?php echo htmlspecialchars($agent->agency_name); ?>
                                                </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="dropdown mb-3">
                                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    <i class="ri-more-line me-1"></i>Actions
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="mailto:<?php echo $agent->agent_email; ?>"><i class="ri-mail-line me-2"></i>Envoyer email</a></li>
                                                    <li><a class="dropdown-item" href="<?php echo base_url('properties?agent=' . $agent->user_id); ?>"><i class="ri-home-line me-2"></i>Voir ses propriétés</a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item" href="#"><i class="ri-edit-line me-2"></i>Modifier</a></li>
                                                </ul>
                                            </div>
                                            <div class="d-flex flex-wrap gap-2 justify-content-end">
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
            <div class="row g-3">
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-md flex-shrink-0">
                                    <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-2">
                                        <i class="ri-home-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-medium text-muted mb-1">Propriétés</p>
                                    <h3 class="mb-0 text-primary"><?php echo $agent->properties_count ?? 0; ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-md flex-shrink-0">
                                    <span class="avatar-title bg-info-subtle text-info rounded-circle fs-2">
                                        <i class="ri-calculator-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-medium text-muted mb-1">Estimations</p>
                                    <h3 class="mb-0 text-info"><?php echo $agent->estimations_count ?? 0; ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-md flex-shrink-0">
                                    <span class="avatar-title bg-success-subtle text-success rounded-circle fs-2">
                                        <i class="ri-exchange-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-medium text-muted mb-1">Transactions</p>
                                    <h3 class="mb-0 text-success"><?php echo $agent->transactions_count ?? 0; ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row g-3 mt-2">
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-md flex-shrink-0">
                                    <span class="avatar-title bg-warning-subtle text-warning rounded-circle fs-2">
                                        <i class="ri-money-dollar-circle-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-medium text-muted mb-1">Commissions</p>
                                    <h3 class="mb-0 text-warning"><?php echo number_format($agent->total_commission ?? 0, 0, ',', ' '); ?> <small class="fs-6">TND</small></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-md flex-shrink-0">
                                    <span class="avatar-title bg-secondary-subtle text-secondary rounded-circle fs-2">
                                        <i class="ri-eye-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-medium text-muted mb-1">Vues Totales</p>
                                    <h3 class="mb-0 text-secondary"><?php echo number_format($agent->total_views ?? 0); ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-md flex-shrink-0">
                                    <span class="avatar-title bg-danger-subtle text-danger rounded-circle fs-2">
                                        <i class="ri-contacts-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-medium text-muted mb-1">Contacts</p>
                                    <h3 class="mb-0 text-danger"><?php echo $agent->contacts_count ?? 0; ?></h3>
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

                    <!-- Monthly Performance -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Performance du mois</h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="border-end">
                                        <h4 class="text-info"><?php echo $agent->estimations_this_month ?? 0; ?></h4>
                                        <p class="text-muted mb-0 small">Estimations</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h4 class="text-success"><?php echo $agent->transactions_this_month ?? 0; ?></h4>
                                    <p class="text-muted mb-0 small">Transactions</p>
                                </div>
                            </div>

                            <?php if (($agent->avg_estimation_value ?? 0) > 0) : ?>
                            <hr>
                            <div class="text-center">
                                <h6 class="text-muted mb-1">Valeur moyenne des estimations</h6>
                                <h5 class="text-primary mb-0"><?php echo number_format($agent->avg_estimation_value, 0, ',', ' '); ?> TND</h5>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
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

            <!-- Estimations & Transactions Row -->
            <div class="row mt-4">
                <!-- Estimations Section -->
                <div class="col-xl-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="ri-calculator-line me-2 text-info"></i>Estimations Récentes (<?php echo count($estimations ?? []); ?>)
                            </h5>
                            <a href="<?php echo base_url('estimations?agent=' . $agent->user_id); ?>" class="btn btn-info btn-sm">
                                Voir toutes
                            </a>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($estimations)) : ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($estimations as $estimation) : ?>
                                <div class="list-group-item border-0 px-0">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1"><?php echo htmlspecialchars($estimation->type_bien); ?></h6>
                                            <p class="text-muted small mb-1">
                                                <i class="ri-map-pin-line me-1"></i>
                                                <?php echo htmlspecialchars($estimation->adresse); ?>
                                            </p>
                                            <div class="d-flex align-items-center gap-3">
                                                <span class="text-muted small">
                                                    <i class="ri-ruler-line me-1"></i><?php echo $estimation->superficie; ?> m²
                                                </span>
                                                <?php if ($estimation->zone_nom) : ?>
                                                <span class="badge bg-light text-dark"><?php echo htmlspecialchars($estimation->zone_nom); ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-bold text-info fs-6">
                                                <?php echo number_format($estimation->prix_estime, 0, ',', ' '); ?> TND
                                            </div>
                                            <small class="text-muted">
                                                <?php echo date('d/m/Y', strtotime($estimation->date_creation)); ?>
                                            </small>
                                            <br>
                                            <span class="badge bg-<?php 
                                                echo $estimation->statut == 'valide' ? 'success' : 
                                                    ($estimation->statut == 'rejete' ? 'danger' : 'warning'); 
                                            ?>-subtle text-<?php 
                                                echo $estimation->statut == 'valide' ? 'success' : 
                                                    ($estimation->statut == 'rejete' ? 'danger' : 'warning'); 
                                            ?>">
                                                <?php echo ucfirst($estimation->statut); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>

                            <?php if (($agent->estimations_count ?? 0) > count($estimations)) : ?>
                            <div class="text-center mt-3">
                                <a href="<?php echo base_url('estimations?agent=' . $agent->user_id); ?>" class="btn btn-outline-info">
                                    Voir les <?php echo ($agent->estimations_count ?? 0) - count($estimations); ?> estimations restantes
                                </a>
                            </div>
                            <?php endif; ?>

                            <?php else : ?>
                            <div class="text-center py-4">
                                <div class="avatar-lg mx-auto mb-3">
                                    <div class="avatar-title bg-info-subtle text-info rounded-circle fs-24">
                                        <i class="ri-calculator-line"></i>
                                    </div>
                                </div>
                                <h6 class="mb-2">Aucune estimation</h6>
                                <p class="text-muted">Cet agent n'a pas encore créé d'estimations.</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Transactions Section -->
                <div class="col-xl-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="ri-exchange-line me-2 text-success"></i>Transactions Récentes (<?php echo count($transactions ?? []); ?>)
                            </h5>
                            <a href="<?php echo base_url('transactions?agent=' . $agent->user_id); ?>" class="btn btn-success btn-sm">
                                Voir toutes
                            </a>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($transactions)) : ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($transactions as $transaction) : ?>
                                <div class="list-group-item border-0 px-0">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">
                                                <?php echo ucfirst($transaction->type); ?>
                                                <?php if ($transaction->property_type) : ?>
                                                - <?php echo htmlspecialchars($transaction->property_type); ?>
                                                <?php endif; ?>
                                            </h6>
                                            <?php if ($transaction->property_address) : ?>
                                            <p class="text-muted small mb-1">
                                                <i class="ri-map-pin-line me-1"></i>
                                                <?php echo htmlspecialchars($transaction->property_address); ?>
                                            </p>
                                            <?php endif; ?>
                                            <?php if ($transaction->client_nom || $transaction->client_prenom) : ?>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="text-muted small">
                                                    <i class="ri-user-line me-1"></i>
                                                    <?php echo htmlspecialchars(trim($transaction->client_prenom . ' ' . $transaction->client_nom)); ?>
                                                </span>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-bold text-success fs-6">
                                                <?php echo number_format($transaction->montant ?? 0, 0, ',', ' '); ?> TND
                                            </div>
                                            <?php if ($transaction->commission) : ?>
                                            <div class="small text-muted">
                                                Commission: <?php echo number_format($transaction->commission, 0, ',', ' '); ?> TND
                                            </div>
                                            <?php endif; ?>
                                            <small class="text-muted">
                                                <?php echo date('d/m/Y', strtotime($transaction->date_cloture)); ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>

                            <?php if (($agent->transactions_count ?? 0) > count($transactions)) : ?>
                            <div class="text-center mt-3">
                                <a href="<?php echo base_url('transactions?agent=' . $agent->user_id); ?>" class="btn btn-outline-success">
                                    Voir les <?php echo ($agent->transactions_count ?? 0) - count($transactions); ?> transactions restantes
                                </a>
                            </div>
                            <?php endif; ?>

                            <?php else : ?>
                            <div class="text-center py-4">
                                <div class="avatar-lg mx-auto mb-3">
                                    <div class="avatar-title bg-success-subtle text-success rounded-circle fs-24">
                                        <i class="ri-exchange-line"></i>
                                    </div>
                                </div>
                                <h6 class="mb-2">Aucune transaction</h6>
                                <p class="text-muted">Cet agent n'a pas encore finalisé de transactions.</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Debug Links (pour développement) -->
            <?php if (ENVIRONMENT === 'development' || ($this->session->userdata('user_type') === 'admin')) : ?>
            <div class="row mt-3">
                <div class="col-12">
                    <div class="card border-warning">
                        <div class="card-header bg-warning-subtle">
                            <h6 class="card-title mb-0 text-warning"><i class="ri-bug-line me-1"></i>Outils de débogage</h6>
                        </div>
                        <div class="card-body">
                            <div class="btn-group" role="group">
                                <a href="<?php echo base_url('agents/debug_properties/' . $agent->user_id); ?>" class="btn btn-outline-warning btn-sm">
                                    <i class="ri-search-line me-1"></i>Debug Propriétés
                                </a>
                                <a href="<?php echo base_url('agents/explore_structure'); ?>" class="btn btn-outline-info btn-sm">
                                    <i class="ri-database-line me-1"></i>Explorer Structure
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
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
