
<?php $this->load->view('includes/header'); ?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">Locations</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="<?= base_url('dashboard'); ?>">Dashboard</a></li>
                                <li class="breadcrumb-item active">Locations</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-end mb-3">
                <a href="<?= base_url('transactions/nouveau?type=location'); ?>" class="btn btn-success"><i class="ri-add-line"></i> Nouvelle location</a>
            </div>
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Titre</th><th>Statut</th><th>Montant</th><th>Commercial</th><th>Clôture</th><th></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if(!empty($transactions)): foreach($transactions as $t): ?>
                            <tr>
                                <td><?= htmlspecialchars($t['titre']); ?></td>
                                <td><span class="badge bg-info-subtle text-info"><?= $t['statut']; ?></span></td>
                                <td><?= $t['montant']!==null ? number_format($t['montant'],0,',',' ') . ' €' : '—'; ?></td>
                                <td><?= htmlspecialchars($t['commercial'] ?? ''); ?></td>
                                <td><?= $t['date_cloture'] ? date('d/m/Y', strtotime($t['date_cloture'])) : '—'; ?></td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-primary" href="<?= base_url('transactions/edit/'.$t['id']); ?>">Éditer</a>
                                </td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr><td colspan="6" class="text-center text-muted">Aucune location</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('includes/footer'); ?>
