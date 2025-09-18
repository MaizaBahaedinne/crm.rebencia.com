<div class="content-wrapper">
    <!-- En-tête de page -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <i class="fas fa-calculator"></i>
                        Estimation #<?= $estimation['id'] ?>
                        <span class="badge badge-<?= $estimation['statut_dossier'] === 'valide' ? 'success' : ($estimation['statut_dossier'] === 'rejete' ? 'danger' : 'warning') ?> ml-2">
                            <?= ucfirst($estimation['statut_dossier']) ?>
                        </span>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('estimations') ?>">Estimations</a></li>
                        <li class="breadcrumb-item active">Détails #<?= $estimation['id'] ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- Informations principales -->
                <div class="col-md-8">
                    <!-- Détails de la propriété -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-home"></i>
                                Détails de la Propriété
                            </h3>
                            <div class="card-tools">
                                <?php if ($user_role === 'admin' || $estimation['agent_id'] == $user_info['id']): ?>
                                <a href="<?= base_url('estimations/edit/' . $estimation['id']) ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Modifier
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="40%">Adresse</th>
                                            <td><?= $estimation['adresse'] ?? 'N/A' ?></td>
                                        </tr>
                                        <tr>
                                            <th>Ville</th>
                                            <td><?= $estimation['ville'] ?? 'N/A' ?></td>
                                        </tr>
                                        <tr>
                                            <th>Code Postal</th>
                                            <td><?= $estimation['code_postal'] ?? 'N/A' ?></td>
                                        </tr>
                                        <tr>
                                            <th>Type de Bien</th>
                                            <td>
                                                <span class="badge badge-info">
                                                    <?= ucfirst($estimation['type_bien'] ?? 'N/A') ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Surface</th>
                                            <td><?= $estimation['surface'] ?? 'N/A' ?> m²</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="40%">Nombre de Pièces</th>
                                            <td><?= $estimation['nb_pieces'] ?? 'N/A' ?></td>
                                        </tr>
                                        <tr>
                                            <th>Nombre de Chambres</th>
                                            <td><?= $estimation['nb_chambres'] ?? 'N/A' ?></td>
                                        </tr>
                                        <tr>
                                            <th>Étage</th>
                                            <td><?= $estimation['etage'] ?? 'N/A' ?></td>
                                        </tr>
                                        <tr>
                                            <th>Année Construction</th>
                                            <td><?= $estimation['annee_construction'] ?? 'N/A' ?></td>
                                        </tr>
                                        <tr>
                                            <th>État du Bien</th>
                                            <td>
                                                <span class="badge badge-secondary">
                                                    <?= ucfirst($estimation['etat_bien'] ?? 'N/A') ?>
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            
                            <?php if (!empty($estimation['description'])): ?>
                            <div class="mt-3">
                                <h5>Description</h5>
                                <p class="text-muted"><?= nl2br(htmlspecialchars($estimation['description'])) ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Estimation et Prix -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-calculator"></i>
                                Estimation et Prix
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="info-box bg-primary">
                                        <span class="info-box-icon"><i class="fas fa-tag"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Prix d'Estimation</span>
                                            <span class="info-box-number">
                                                <?= number_format($estimation['prix_estimation'] ?? 0, 0, ',', ' ') ?> TND
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <?php if (!empty($estimation['prix_demande'])): ?>
                                <div class="col-md-4">
                                    <div class="info-box bg-warning">
                                        <span class="info-box-icon"><i class="fas fa-hand-holding-usd"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Prix Demandé</span>
                                            <span class="info-box-number">
                                                <?= number_format($estimation['prix_demande'], 0, ',', ' ') ?> TND
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($estimation['proposition_agence'])): ?>
                                <div class="col-md-4">
                                    <div class="info-box bg-success">
                                        <span class="info-box-icon"><i class="fas fa-building"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Proposition Agence</span>
                                            <span class="info-box-number">
                                                <?= number_format($estimation['proposition_agence'], 0, ',', ' ') ?> TND
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <?php if (!empty($estimation['commentaire_estimation'])): ?>
                            <div class="mt-3">
                                <h5>Commentaire d'Estimation</h5>
                                <div class="alert alert-info">
                                    <?= nl2br(htmlspecialchars($estimation['commentaire_estimation'])) ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($estimation['proposition_commentaire'])): ?>
                            <div class="mt-3">
                                <h5>Commentaire de l'Agence</h5>
                                <div class="alert alert-success">
                                    <?= nl2br(htmlspecialchars($estimation['proposition_commentaire'])) ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Informations latérales -->
                <div class="col-md-4">
                    <!-- Informations Client -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-user"></i>
                                Informations Client
                            </h3>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($estimation['client_nom'])): ?>
                            <table class="table table-sm">
                                <tr>
                                    <th>Nom</th>
                                    <td><?= $estimation['client_nom'] ?> <?= $estimation['client_prenom'] ?? '' ?></td>
                                </tr>
                                <?php if (!empty($estimation['client_email'])): ?>
                                <tr>
                                    <th>Email</th>
                                    <td>
                                        <a href="mailto:<?= $estimation['client_email'] ?>">
                                            <?= $estimation['client_email'] ?>
                                        </a>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <?php if (!empty($estimation['client_phone'])): ?>
                                <tr>
                                    <th>Téléphone</th>
                                    <td>
                                        <a href="tel:<?= $estimation['client_phone'] ?>">
                                            <?= $estimation['client_phone'] ?>
                                        </a>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <?php if (!empty($estimation['client_adresse'])): ?>
                                <tr>
                                    <th>Adresse</th>
                                    <td><?= $estimation['client_adresse'] ?></td>
                                </tr>
                                <?php endif; ?>
                            </table>
                            <?php else: ?>
                            <p class="text-muted text-center">
                                <i class="fas fa-user-slash fa-2x mb-2"></i><br>
                                Aucune information client disponible
                            </p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Informations Agent/Agence -->
                    <?php if ($user_role !== 'agent'): ?>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-user-tie"></i>
                                Agent & Agence
                            </h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <tr>
                                    <th>Agent</th>
                                    <td><?= $estimation['agent_name'] ?? 'N/A' ?></td>
                                </tr>
                                <?php if (!empty($estimation['agent_email'])): ?>
                                <tr>
                                    <th>Email Agent</th>
                                    <td>
                                        <a href="mailto:<?= $estimation['agent_email'] ?>">
                                            <?= $estimation['agent_email'] ?>
                                        </a>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <?php if (!empty($estimation['agency_name'])): ?>
                                <tr>
                                    <th>Agence</th>
                                    <td><?= $estimation['agency_name'] ?></td>
                                </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Dates et Statut -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-calendar"></i>
                                Historique
                            </h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <tr>
                                    <th>Créée le</th>
                                    <td><?= date('d/m/Y à H:i', strtotime($estimation['created_at'])) ?></td>
                                </tr>
                                <?php if ($estimation['updated_at'] !== $estimation['created_at']): ?>
                                <tr>
                                    <th>Modifiée le</th>
                                    <td><?= date('d/m/Y à H:i', strtotime($estimation['updated_at'])) ?></td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <th>Statut</th>
                                    <td>
                                        <span class="badge badge-<?= $estimation['statut_dossier'] === 'valide' ? 'success' : ($estimation['statut_dossier'] === 'rejete' ? 'danger' : 'warning') ?>">
                                            <?= ucfirst($estimation['statut_dossier']) ?>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Actions rapides -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-cogs"></i>
                                Actions
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <?php if ($user_role === 'admin' || $estimation['agent_id'] == $user_info['id']): ?>
                                <a href="<?= base_url('estimations/edit/' . $estimation['id']) ?>" class="btn btn-warning btn-block">
                                    <i class="fas fa-edit"></i> Modifier l'Estimation
                                </a>
                                <?php endif; ?>
                                
                                <button type="button" class="btn btn-info btn-block" onclick="printEstimation()">
                                    <i class="fas fa-print"></i> Imprimer
                                </button>
                                
                                <button type="button" class="btn btn-success btn-block" onclick="exportPDF()">
                                    <i class="fas fa-file-pdf"></i> Exporter PDF
                                </button>
                                
                                <a href="<?= base_url('estimations') ?>" class="btn btn-secondary btn-block">
                                    <i class="fas fa-arrow-left"></i> Retour à la Liste
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function printEstimation() {
    window.print();
}

function exportPDF() {
    window.open('<?= base_url("estimations/export_pdf/" . $estimation['id']) ?>', '_blank');
}
</script>

<style>
@media print {
    .content-wrapper {
        margin: 0 !important;
        padding: 0 !important;
    }
    
    .card {
        border: 1px solid #ddd !important;
        box-shadow: none !important;
        page-break-inside: avoid;
    }
    
    .btn, .card-tools {
        display: none !important;
    }
    
    .breadcrumb {
        display: none !important;
    }
}

.info-box {
    border-radius: 10px;
}

.info-box .info-box-icon {
    border-radius: 10px 0 0 10px;
}

.card {
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
    border: none;
}

.table th {
    background-color: #f8f9fa;
    border-top: none;
    font-weight: 600;
}

.alert {
    border-radius: 10px;
}

.badge {
    font-size: 0.875em;
}
</style>
