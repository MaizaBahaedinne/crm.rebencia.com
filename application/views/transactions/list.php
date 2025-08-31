<?php $this->load->view('includes/header'); ?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">Transactions</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="<?= base_url('dashboard'); ?>">Dashboard</a></li>
                                <li class="breadcrumb-item active">Transactions</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <form class="row g-2">
                        <div class="col-md-3">
                            <input name="q" class="form-control" placeholder="Recherche..." value="<?= htmlspecialchars($filters['q'] ?? '') ?>">
                        </div>
                        <div class="col-md-2">
                            <select name="type" class="form-select">
                                <option value="">Type</option>
                                <option value="vente" <?= (($filters['type']??'')==='vente')?'selected':''; ?>>Vente</option>
                                <option value="location" <?= (($filters['type']??'')==='location')?'selected':''; ?>>Location</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="statut" class="form-select">
                                <option value="">Statut</option>
                                <?php foreach(['nouveau','actif','cloture','annule'] as $s): ?>
                                    <option value="<?= $s; ?>" <?= (($filters['statut']??'')===$s)?'selected':''; ?>><?= ucfirst($s); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="date_min" class="form-control" value="<?= htmlspecialchars($filters['date_min'] ?? '') ?>" placeholder="Date min">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="date_max" class="form-control" value="<?= htmlspecialchars($filters['date_max'] ?? '') ?>" placeholder="Date max">
                        </div>
                        <div class="col-md-1 d-grid">
                            <button class="btn btn-primary">OK</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="text-end mb-3">
                <a href="<?= base_url('transactions/nouveau'); ?>" class="btn btn-success"><i class="ri-add-line"></i> Nouveau</a>
            </div>

            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Titre</th>
                                <th>Type</th>
                                <th>Statut</th>
                                <th>Montant</th>
                                <th>Commercial</th>
                                <th>Clôture</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if(!empty($transactions)): foreach($transactions as $t): ?>
                            <tr>
                                <td><?= htmlspecialchars($t['titre']); ?></td>
                                <td><span class="badge bg-secondary-subtle text-secondary"><?= $t['type']; ?></span></td>
                                <td><span class="badge bg-info-subtle text-info"><?= $t['statut']; ?></span></td>
                                <td><?= $t['montant']!==null ? number_format($t['montant'],0,',',' ') . ' €' : '—'; ?></td>
                                <td><?= htmlspecialchars($t['commercial'] ?? ''); ?></td>
                                <td><?= $t['date_cloture'] ? date('d/m/Y', strtotime($t['date_cloture'])) : '—'; ?></td>
                                <td class="text-end">
                                    <a href="<?= base_url('transactions/edit/'.$t['id']); ?>" class="btn btn-sm btn-outline-primary">Éditer</a>
                                    <a href="<?= base_url('transactions/delete/'.$t['id']); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer ?');">X</a>
                                </td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr><td colspan="7" class="text-center text-muted">Aucune transaction</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('includes/footer'); ?>
