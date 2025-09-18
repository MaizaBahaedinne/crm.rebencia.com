<?php
// Normalisation et sécurité: garantir $user tableau avec clés attendues pour éviter les notices
if (!isset($user)) { $user = []; }
if (is_object($user)) { $user = (array)$user; }
if (!is_array($user)) { $user = []; }

// Construire nom si absent - utiliser les bons champs de la table wp_Hrg8P_crm_agents
if (empty($user['name'])) {
    $agent_name = isset($user['agent_name']) ? trim($user['agent_name']) : '';
    $user_login = isset($user['user_login']) ? trim($user['user_login']) : '';
    $user['name'] = $agent_name ?: $user_login ?: 'Utilisateur'; 
}

// Valeurs par défaut sûres basées sur la structure de wp_Hrg8P_crm_agents
$defaults = [
    'user_login' => isset($user['user_login']) ? $user['user_login'] : '',
    'user_email' => isset($user['user_email']) ? $user['user_email'] : '',
    'agent_email' => isset($user['agent_email']) ? $user['agent_email'] : '',
    'phone' => isset($user['phone']) ? $user['phone'] : '',
    'mobile' => isset($user['mobile']) ? $user['mobile'] : '',
    'whatsapp' => isset($user['whatsapp']) ? $user['whatsapp'] : '',
    'skype' => isset($user['skype']) ? $user['skype'] : '',
    'user_status' => isset($user['user_status']) ? $user['user_status'] : 'active',
    'user_registered' => isset($user['registration_date']) ? $user['registration_date'] : (isset($user['user_registered']) ? $user['user_registered'] : ''),
    'agent_name' => isset($user['agent_name']) ? $user['agent_name'] : '',
    'agency_name' => isset($user['agency_name']) ? $user['agency_name'] : '',
    'agency_id' => isset($user['agency_id']) ? $user['agency_id'] : '',
    'agent_post_id' => isset($user['agent_post_id']) ? $user['agent_post_id'] : '',
    'position' => isset($user['position']) ? $user['position'] : '',
    'website' => isset($user['website']) ? $user['website'] : '',
    'agent_avatar' => isset($user['agent_avatar']) ? $user['agent_avatar'] : '',
    'location' => '',
    'bio' => '',
];

foreach ($defaults as $k=>$v) { if (!isset($user[$k])) $user[$k] = $v; }

// Email principal (priorité à agent_email puis user_email)
$primary_email = $user['agent_email'] ?: $user['user_email'];
$user['email'] = $primary_email;

// Rôles par défaut
$user['roles_string'] = $user['position'] ?: 'Agent Immobilier';
?>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- Messages d'alerte -->
            <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ri-error-warning-line me-2"></i>
                <?php echo $this->session->flashdata('error'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <!-- Header de profil avec image de fond -->
            <div class="profile-foreground position-relative mx-n4 mt-n4">
                <div class="profile-wid-bg">
                    <img src="<?php echo base_url(); ?>assets/images/profile-bg.jpg" alt="" class="profile-wid-img" 
                         style="width: 100%; height: 260px; object-fit: cover;" />
                </div>
            </div>
            
            <!-- Informations principales du profil -->
            <div class="pt-4 mb-4 mb-lg-3 pb-lg-4 profile-wrapper">
                <div class="row g-4">
                    <div class="col-auto">
                        <div class="avatar-lg">
                            <img src="<?php echo base_url(); ?>assets/images/users/avatar-1.jpg" 
                                 alt="user-img" class="img-thumbnail rounded-circle" 
                                 style="width: 120px; height: 120px; object-fit: cover;" />
                        </div>
                    </div>
                    
                    <div class="col">
                        <div class="p-2">
                            <h3 class="text-white mb-1"><?php echo $user['name']; ?></h3>
                            <p class="text-white text-opacity-75"><?php echo $user['roles_string'] ?: 'Agent Immobilier'; ?></p>
                            <div class="hstack text-white-50 gap-1">
                                <div class="me-2">
                                    <i class="ri-map-pin-user-line me-1 text-white text-opacity-75 fs-16 align-middle"></i>
                                    <?php echo $user['location'] ?: 'Non spécifié'; ?>
                                </div>
                                <div>
                                    <i class="ri-building-line me-1 text-white text-opacity-75 fs-16 align-middle"></i>
                                    <?php echo $user['agency_name'] ?: 'Rebencia CRM'; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12 col-lg-auto order-last order-lg-0">
                        <div class="row text-white-50 text-center">
                                    <div class="col-lg-6 col-4">
                                        <div class="p-2">
                                            <h4 class="text-white mb-1">
                                                <?php 
                                                $reg_date = $user['user_registered'] ?: date('Y-m-d');
                                                echo date('Y') - date('Y', strtotime($reg_date)); 
                                                ?>
                                            </h4>
                                            <p class="fs-14 mb-0">Années</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-4">
                                        <div class="p-2">
                                            <h4 class="text-white mb-1">
                                                <?php echo ucfirst($user['user_status'] ?: 'Actif'); ?>
                                            </h4>
                                            <p class="fs-14 mb-0">Statut</p>
                                        </div>
                                    </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation et contenu -->
            <div class="row">
                <div class="col-lg-12">
                    <div>
                        <div class="d-flex profile-wrapper">
                            <!-- Nav tabs -->
                            <ul class="nav nav-pills animation-nav profile-nav gap-2 gap-lg-3 flex-grow-1" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link fs-14 active" data-bs-toggle="tab" href="#overview-tab" role="tab">
                                        <i class="ri-airplay-fill d-inline-block d-md-none"></i> 
                                        <span class="d-none d-md-inline-block">Aperçu</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link fs-14" data-bs-toggle="tab" href="#contact-tab" role="tab">
                                        <i class="ri-phone-line d-inline-block d-md-none"></i> 
                                        <span class="d-none d-md-inline-block">Contact</span>
                                    </a>
                                </li>
                            </ul>
                            
                            <div class="flex-shrink-0">
                                <a href="<?php echo base_url('dashboard'); ?>" class="btn btn-primary">
                                    <i class="ri-dashboard-line align-bottom"></i> Retour au tableau de bord
                                </a>
                            </div>
                        </div>
                        
                        <!-- Tab panes -->
                        <div class="tab-content pt-4 text-muted">
                            <!-- Onglet Aperçu -->
                            <div class="tab-pane active" id="overview-tab" role="tabpanel">
                                <div class="row">
                                    <div class="col-xxl-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title mb-3">
                                                    <i class="ri-user-line me-2 text-primary"></i>
                                                    Informations personnelles
                                                </h5>
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-borderless mb-0">
                                                        <tbody>
                                                            <tr>
                                                                <th class="ps-0" scope="row">Nom d'utilisateur :</th>
                                                                <td class="text-muted"><?php echo $user['user_login'] ?: 'Non défini'; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="ps-0" scope="row">Nom d'agent :</th>
                                                                <td class="text-muted"><?php echo $user['agent_name'] ?: $user['name']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="ps-0" scope="row">ID Agent :</th>
                                                                <td class="text-muted"><?php echo $user['agent_post_id'] ?: 'Non assigné'; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="ps-0" scope="row">Position :</th>
                                                                <td class="text-muted"><?php echo $user['position'] ?: 'Agent Immobilier'; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="ps-0" scope="row">Date d'inscription :</th>
                                                                <td class="text-muted">
                                                                    <?php 
                                                                    $reg_date = $user['user_registered'];
                                                                    echo $reg_date ? date('d/m/Y', strtotime($reg_date)) : 'Non disponible'; 
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th class="ps-0" scope="row">Statut :</th>
                                                                <td class="text-muted">
                                                                    <span class="badge bg-success">
                                                                        <?php echo ucfirst($user['user_status'] ?: 'Actif'); ?>
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th class="ps-0" scope="row">Rôle :</th>
                                                                <td class="text-muted">
                                                                    <span class="badge bg-primary">
                                                                        <?php echo $user['roles_string'] ?: 'Agent'; ?>
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th class="ps-0" scope="row">Site web :</th>
                                                                <td class="text-muted">
                                                                    <?php if($user['website']): ?>
                                                                        <a href="<?php echo $user['website']; ?>" target="_blank" class="text-decoration-none">
                                                                            <?php echo $user['website']; ?>
                                                                        </a>
                                                                    <?php else: ?>
                                                                        Non spécifié
                                                                    <?php endif; ?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title mb-3">
                                                    <i class="ri-file-text-line me-2 text-primary"></i>
                                                    À propos
                                                </h5>
                                                <p class="text-muted">
                                                    Agent immobilier professionnel chez <?php echo $user['agency_name'] ?: 'Rebencia'; ?>.
                                                    <?php if($user['position']): ?>
                                                        <br>Position : <?php echo $user['position']; ?>
                                                    <?php endif; ?>
                                                    <?php if($user['website']): ?>
                                                        <br>Site web : <a href="<?php echo $user['website']; ?>" target="_blank"><?php echo $user['website']; ?></a>
                                                    <?php endif; ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xxl-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title mb-3">
                                                    <i class="ri-building-line me-2 text-primary"></i>
                                                    Informations professionnelles
                                                </h5>
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-borderless mb-0">
                                                        <tbody>
                                                            <tr>
                                                                <th class="ps-0" scope="row">ID Agence :</th>
                                                                <td class="text-muted"><?php echo $user['agency_id'] ?: 'Non assigné'; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="ps-0" scope="row">Nom Agence :</th>
                                                                <td class="text-muted"><?php echo $user['agency_name'] ?: 'Non assigné'; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="ps-0" scope="row">ID Post Agent :</th>
                                                                <td class="text-muted"><?php echo $user['agent_post_id'] ?: 'Non assigné'; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="ps-0" scope="row">Position :</th>
                                                                <td class="text-muted"><?php echo $user['position'] ?: 'Agent Immobilier'; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="ps-0" scope="row">Localisation :</th>
                                                                <td class="text-muted"><?php echo $user['location'] ?: 'Non spécifiée'; ?></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title mb-4">
                                                    <i class="ri-links-line me-2 text-primary"></i>
                                                    Liens rapides
                                                </h5>
                                                <div class="d-flex flex-wrap gap-2">
                                                    <div>
                                                        <a href="<?php echo base_url('dashboard'); ?>" class="avatar-xs d-block">
                                                            <span class="avatar-title rounded-circle fs-16 bg-primary material-shadow">
                                                                <i class="ri-dashboard-line"></i>
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div>
                                                        <a href="<?php echo base_url('dashboard/agent'); ?>" class="avatar-xs d-block">
                                                            <span class="avatar-title rounded-circle fs-16 bg-success material-shadow">
                                                                <i class="ri-user-line"></i>
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div>
                                                        <a href="<?php echo base_url('settings'); ?>" class="avatar-xs d-block">
                                                            <span class="avatar-title rounded-circle fs-16 bg-warning material-shadow">
                                                                <i class="ri-settings-line"></i>
                                                            </span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Onglet Contact -->
                            <div class="tab-pane" id="contact-tab" role="tabpanel">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title mb-3">
                                                    <i class="ri-mail-line me-2 text-primary"></i>
                                                    Informations de contact
                                                </h5>
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-borderless mb-0">
                                                        <tbody>
                                                            <tr>
                                                                <th class="ps-0" scope="row">E-mail :</th>
                                                                <td class="text-muted">
                                                                    <?php $email = $user['email']; ?>
                                                                    <?php if($email): ?>
                                                                        <a href="mailto:<?php echo $email; ?>" class="text-decoration-none">
                                                                            <?php echo $email; ?>
                                                                        </a>
                                                                    <?php else: ?>
                                                                        Non spécifié
                                                                    <?php endif; ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th class="ps-0" scope="row">Téléphone :</th>
                                                                <td class="text-muted">
                                                                    <?php if($user['phone']): ?>
                                                                        <a href="tel:<?php echo $user['phone']; ?>" class="text-decoration-none">
                                                                            <?php echo $user['phone']; ?>
                                                                        </a>
                                                                    <?php else: ?>
                                                                        Non spécifié
                                                                    <?php endif; ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th class="ps-0" scope="row">Mobile :</th>
                                                                <td class="text-muted">
                                                                    <?php if($user['mobile']): ?>
                                                                        <a href="tel:<?php echo $user['mobile']; ?>" class="text-decoration-none">
                                                                            <?php echo $user['mobile']; ?>
                                                                        </a>
                                                                    <?php else: ?>
                                                                        Non spécifié
                                                                    <?php endif; ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th class="ps-0" scope="row">WhatsApp :</th>
                                                                <td class="text-muted">
                                                                    <?php if($user['whatsapp']): ?>
                                                                        <a href="https://wa.me/<?php echo $user['whatsapp']; ?>" class="text-decoration-none" target="_blank">
                                                                            <?php echo $user['whatsapp']; ?>
                                                                        </a>
                                                                    <?php else: ?>
                                                                        Non spécifié
                                                                    <?php endif; ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th class="ps-0" scope="row">Skype :</th>
                                                                <td class="text-muted">
                                                                    <?php if($user['skype']): ?>
                                                                        <a href="skype:<?php echo $user['skype']; ?>?chat" class="text-decoration-none">
                                                                            <?php echo $user['skype']; ?>
                                                                        </a>
                                                                    <?php else: ?>
                                                                        Non spécifié
                                                                    <?php endif; ?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title mb-3">
                                                    <i class="ri-share-line me-2 text-primary"></i>
                                                    Actions rapides
                                                </h5>
                                                <div class="d-grid gap-2">
                                                    <?php $email = $user['email']; ?>
                                                    <?php if($email): ?>
                                                    <a href="mailto:<?php echo $email; ?>" class="btn btn-outline-primary">
                                                        <i class="ri-mail-line me-1"></i> Envoyer un e-mail
                                                    </a>
                                                    <?php endif; ?>
                                                    
                                                    <?php if($user['phone'] || $user['mobile']): ?>
                                                    <a href="tel:<?php echo $user['phone'] ?: $user['mobile']; ?>" class="btn btn-outline-success">
                                                        <i class="ri-phone-line me-1"></i> Appeler
                                                    </a>
                                                    <?php endif; ?>
                                                    
                                                    <?php if($user['whatsapp']): ?>
                                                    <a href="https://wa.me/<?php echo $user['whatsapp']; ?>" class="btn btn-outline-success" target="_blank">
                                                        <i class="ri-whatsapp-line me-1"></i> WhatsApp
                                                    </a>
                                                    <?php endif; ?>
                                                    
                                                    <?php if($user['website']): ?>
                                                    <a href="<?php echo $user['website']; ?>" class="btn btn-outline-info" target="_blank">
                                                        <i class="ri-global-line me-1"></i> Site web
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
            </div>
        </div>
    </div>
</div>

<style>
.profile-wid-bg {
    position: relative;
    border-radius: 0.5rem 0.5rem 0 0;
    overflow: hidden;
}

.profile-wid-img {
    border-radius: 0.5rem 0.5rem 0 0;
}

.profile-wrapper {
    position: relative;
    margin-top: -60px;
}

.avatar-lg {
    width: 120px;
    height: 120px;
}

.nav-pills .nav-link {
    border-radius: 0.375rem;
    margin-bottom: 0.5rem;
}

.nav-pills .nav-link.active {
    background-color: #405189;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.badge {
    font-size: 0.75em;
}

.material-shadow {
    box-shadow: 0 3px 1px -2px rgba(0, 0, 0, 0.2), 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 1px 5px 0 rgba(0, 0, 0, 0.12);
}

.avatar-xs {
    width: 2rem;
    height: 2rem;
}

.avatar-title {
    align-items: center;
    background-color: #405189;
    color: #fff;
    display: flex;
    font-weight: 500;
    height: 100%;
    justify-content: center;
    width: 100%;
}
</style>
