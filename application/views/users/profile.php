<?php
$userId = $userInfo->userId;
$name = $userInfo->name;
$email = $userInfo->email;
$mobile = $userInfo->mobile;
$roleId = $userInfo->roleId;
$role = $userInfo->role;
?>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <!-- Header Premium -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h4 class="mb-2 text-dark fw-bold">
                                <i class="ri-user-settings-line me-2 text-primary"></i>
                                Mon Profil
                            </h4>
                            <p class="text-muted mb-0">
                                <i class="ri-information-line me-1"></i>
                                Gérez vos informations personnelles et paramètres de sécurité
                            </p>
                        </div>
                        <div class="page-title-right">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?= base_url('dashboard'); ?>">Dashboard</a></li>
                                <li class="breadcrumb-item active">Profil</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Messages d'alerte -->
            <div class="row">
                <div class="col-12">
                    <?php
                        $error = $this->session->flashdata('error');
                        if($error) {
                    ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="ri-error-warning-line me-2"></i>
                        <strong>Erreur!</strong> <?php echo $error; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php } ?>
                    
                    <?php  
                        $success = $this->session->flashdata('success');
                        if($success) {
                    ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="ri-check-line me-2"></i>
                        <strong>Succès!</strong> <?php echo $success; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php } ?>

                    <?php  
                        $noMatch = $this->session->flashdata('nomatch');
                        if($noMatch) {
                    ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="ri-alert-line me-2"></i>
                        <strong>Attention!</strong> <?php echo $noMatch; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php } ?>
                    
                    <?php echo validation_errors('<div class="alert alert-danger alert-dismissible fade show" role="alert"><i class="ri-error-warning-line me-2"></i>', '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>'); ?>
                </div>
            </div>

            <div class="row">
                <!-- Colonne gauche - Carte de profil -->
                <div class="col-xl-4 col-lg-5">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="profile-user position-relative d-inline-block mx-auto mb-4">
                                <img src="<?php echo base_url(); ?>assets/dist/img/avatar.png" 
                                     class="rounded-circle avatar-xl img-thumbnail user-profile-image shadow" 
                                     alt="Avatar utilisateur">
                                <div class="avatar-xs p-0 rounded-circle profile-photo-edit position-absolute end-0 bottom-0">
                                    <input id="profile-img-file-input" type="file" class="profile-img-file-input d-none">
                                    <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                        <span class="avatar-title rounded-circle bg-light text-body shadow">
                                            <i class="ri-camera-fill"></i>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            
                            <h5 class="fs-16 mb-1"><?= $name ?></h5>
                            <p class="text-muted mb-0"><?= $role ?></p>
                        </div>
                        
                        <div class="card-body pt-0">
                            <div class="row">
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="ri-mail-line text-primary fs-18"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-1 fs-14">Email</h6>
                                            <p class="text-muted mb-0 fs-12"><?= $email ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="ri-phone-line text-primary fs-18"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-1 fs-14">Mobile</h6>
                                            <p class="text-muted mb-0 fs-12"><?= $mobile ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Statistiques rapides -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="ri-bar-chart-line me-2"></i>Statistiques du compte
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="p-3 bg-primary-subtle rounded text-center">
                                        <h5 class="mb-1 text-primary">15</h5>
                                        <p class="text-muted mb-0 fs-13">Connexions ce mois</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 bg-success-subtle rounded text-center">
                                        <h5 class="mb-1 text-success">95%</h5>
                                        <p class="text-muted mb-0 fs-13">Taux d'activité</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Colonne droite - Formulaires -->
                <div class="col-xl-8 col-lg-7">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header">
                            <ul class="nav nav-tabs-custom nav-success nav-justified mb-3" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link <?= ($active == "details")? "active" : "" ?>" data-bs-toggle="tab" href="#profile-details" role="tab">
                                        <i class="ri-user-line me-1"></i>Informations personnelles
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?= ($active == "changepass")? "active" : "" ?>" data-bs-toggle="tab" href="#change-password" role="tab">
                                        <i class="ri-lock-line me-1"></i>Changer le mot de passe
                                    </a>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="card-body">
                            <div class="tab-content">
                                <!-- Onglet Détails -->
                                <div class="tab-pane <?= ($active == "details")? "active" : "" ?>" id="profile-details" role="tabpanel">
                                    <form action="<?php echo base_url() ?>profileUpdate" method="post" id="editProfile" class="needs-validation" novalidate>
                                        <?php $this->load->helper('form'); ?>
                                        
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="fname" class="form-label">Nom complet <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="ri-user-line"></i></span>
                                                        <input type="text" class="form-control" id="fname" name="fname" 
                                                               placeholder="Entrez votre nom complet"
                                                               value="<?php echo set_value('fname', $name); ?>" 
                                                               maxlength="128" required />
                                                        <div class="invalid-feedback">
                                                            Veuillez saisir votre nom complet.
                                                        </div>
                                                    </div>
                                                    <input type="hidden" value="<?php echo $userId; ?>" name="userId" id="userId" />
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="email" class="form-label">Adresse email <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="ri-mail-line"></i></span>
                                                        <input type="email" class="form-control" id="email" name="email" 
                                                               placeholder="Entrez votre email"
                                                               value="<?php echo set_value('email', $email); ?>" required>
                                                        <div class="invalid-feedback">
                                                            Veuillez saisir une adresse email valide.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="mobile" class="form-label">Numéro de mobile</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="ri-phone-line"></i></span>
                                                        <input type="tel" class="form-control" id="mobile" name="mobile" 
                                                               placeholder="Entrez votre numéro de mobile"
                                                               value="<?php echo set_value('mobile', $mobile); ?>" maxlength="15">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="role" class="form-label">Rôle</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="ri-shield-user-line"></i></span>
                                                        <input type="text" class="form-control" value="<?= $role ?>" readonly>
                                                    </div>
                                                    <div class="form-text">Votre rôle est défini par l'administrateur système.</div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="text-end">
                                            <button type="reset" class="btn btn-light me-2">
                                                <i class="ri-refresh-line me-1"></i>Réinitialiser
                                            </button>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="ri-save-line me-1"></i>Sauvegarder les modifications
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                
                                <!-- Onglet Changer mot de passe -->
                                <div class="tab-pane <?= ($active == "changepass")? "active" : "" ?>" id="change-password" role="tabpanel">
                                    <form role="form" action="<?php echo base_url() ?>changePassword" method="post" class="needs-validation" novalidate>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label for="inputOldPassword" class="form-label">Mot de passe actuel <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="ri-lock-line"></i></span>
                                                        <input type="password" class="form-control" id="inputOldPassword" 
                                                               placeholder="Entrez votre mot de passe actuel" 
                                                               name="oldPassword" maxlength="20" required>
                                                        <div class="invalid-feedback">
                                                            Veuillez saisir votre mot de passe actuel.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="inputPassword1" class="form-label">Nouveau mot de passe <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="ri-lock-password-line"></i></span>
                                                        <input type="password" class="form-control" id="inputPassword1" 
                                                               placeholder="Entrez le nouveau mot de passe" 
                                                               name="newPassword" maxlength="20" required>
                                                        <div class="invalid-feedback">
                                                            Veuillez saisir un nouveau mot de passe.
                                                        </div>
                                                    </div>
                                                    <div class="form-text">
                                                        <i class="ri-information-line"></i>
                                                        Le mot de passe doit contenir au moins 8 caractères.
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="inputPassword2" class="form-label">Confirmer le nouveau mot de passe <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="ri-lock-password-line"></i></span>
                                                        <input type="password" class="form-control" id="inputPassword2" 
                                                               placeholder="Confirmez le nouveau mot de passe" 
                                                               name="cNewPassword" maxlength="20" required>
                                                        <div class="invalid-feedback">
                                                            Veuillez confirmer votre nouveau mot de passe.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="alert alert-info">
                                            <i class="ri-shield-check-line me-2"></i>
                                            <strong>Conseils de sécurité :</strong>
                                            <ul class="mb-0 mt-2">
                                                <li>Utilisez une combinaison de lettres, chiffres et caractères spéciaux</li>
                                                <li>Évitez d'utiliser des informations personnelles</li>
                                                <li>Changez votre mot de passe régulièrement</li>
                                            </ul>
                                        </div>
                                        
                                        <div class="text-end">
                                            <button type="reset" class="btn btn-light me-2">
                                                <i class="ri-refresh-line me-1"></i>Réinitialiser
                                            </button>
                                            <button type="submit" class="btn btn-success">
                                                <i class="ri-shield-check-line me-1"></i>Changer le mot de passe
                                            </button>
                                        </div>
                                    </form>
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
.profile-user {
    position: relative;
}

.profile-photo-edit {
    cursor: pointer;
}

.avatar-xl {
    width: 7.5rem;
    height: 7.5rem;
}

.bg-primary-subtle {
    background-color: rgba(13, 110, 253, 0.1) !important;
}

.bg-success-subtle {
    background-color: rgba(25, 135, 84, 0.1) !important;
}

.nav-tabs-custom .nav-link {
    border: 1px solid transparent;
    border-radius: 0.375rem 0.375rem 0 0;
    color: #6c757d;
    background: none;
    padding: 0.75rem 1rem;
}

.nav-tabs-custom .nav-link.active {
    color: #198754;
    background-color: #fff;
    border-color: #dee2e6 #dee2e6 #fff;
}

.nav-tabs-custom .nav-link:hover {
    border-color: #e9ecef #e9ecef #dee2e6;
    isolation: isolate;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
}

.input-group-text {
    background-color: #f8f9fa;
    border-right: none;
}

.input-group .form-control {
    border-left: none;
}

.input-group .form-control:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.btn {
    border-radius: 0.375rem;
    font-weight: 500;
    padding: 0.5rem 1rem;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    border: none;
}

.alert {
    border: none;
    border-radius: 0.5rem;
}

.fs-16 { font-size: 1rem !important; }
.fs-14 { font-size: 0.875rem !important; }
.fs-13 { font-size: 0.8125rem !important; }
.fs-12 { font-size: 0.75rem !important; }
.fs-18 { font-size: 1.125rem !important; }
</style>

<script>
// Validation Bootstrap 5
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();

// Validation des mots de passe correspondants
document.addEventListener('DOMContentLoaded', function() {
    const newPassword = document.getElementById('inputPassword1');
    const confirmPassword = document.getElementById('inputPassword2');
    
    if (newPassword && confirmPassword) {
        confirmPassword.addEventListener('input', function() {
            if (newPassword.value !== confirmPassword.value) {
                confirmPassword.setCustomValidity('Les mots de passe ne correspondent pas');
            } else {
                confirmPassword.setCustomValidity('');
            }
        });
        
        newPassword.addEventListener('input', function() {
            if (confirmPassword.value && newPassword.value !== confirmPassword.value) {
                confirmPassword.setCustomValidity('Les mots de passe ne correspondent pas');
            } else {
                confirmPassword.setCustomValidity('');
            }
        });
    }
});
</script>