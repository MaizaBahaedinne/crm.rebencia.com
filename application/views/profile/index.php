
<?php $this->load->view('includes/header'); ?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">Ventes</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Dashboard</a></li>
                                <li class="breadcrumb-item active">Liste des ventes</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <img src="<?php echo !empty($user->profile_picture) ? base_url('uploads/profile_pictures/' . $user->profile_picture) : base_url('assets/images/default-avatar.png'); ?>" alt="Photo de profil" class="img-thumbnail mb-3" style="width: 150px; height: 150px;">
                            <h5><?php echo htmlspecialchars($user->display_name ?? $user->fullname); ?></h5>
                            <p class="text-muted"><?php echo htmlspecialchars($user->user_email ?? $user->email); ?></p>
                        </div>
                        <div class="col-md-8">
                            <h5 class="mb-3">Informations personnelles</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th>Nom affiché :</th>
                                    <td><?php echo htmlspecialchars($user->display_name ?? $user->fullname); ?></td>
                                </tr>
                                <tr>
                                    <th>Email :</th>
                                    <td><?php echo htmlspecialchars($user->user_email ?? $user->email); ?></td>
                                </tr>
                                <tr>
                                    <th>Téléphone :</th>
                                    <td><?php echo htmlspecialchars($user->user_phone ?? $user->phone ?? ''); ?></td>
                                </tr>
                                <tr>
                                    <th>Date d'inscription :</th>
                                    <td><?php echo isset($user->user_registered) ? date('d/m/Y', strtotime($user->user_registered)) : (isset($user->created_at) ? date('d/m/Y', strtotime($user->created_at)) : ''); ?></td>
                                </tr>
                            </table>
                            <?php if (isset($user->ID)) : ?>
                                <a href="<?php echo base_url('users/edit_wp/' . $user->ID); ?>" class="btn btn-primary">Modifier le profil</a>
                            <?php else : ?>
                                <a href="<?php echo base_url('users/edit/' . $user->id); ?>" class="btn btn-primary">Modifier le profil</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('includes/footer'); ?>
