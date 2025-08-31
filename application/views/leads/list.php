<?php $this->load->view('includes/header'); ?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row"><div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                    <h4 class="mb-sm-0">Leads</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('dashboard'); ?>">CRM</a></li>
                            <li class="breadcrumb-item active">Leads</li>
                        </ol>
                    </div>
                </div>
            </div></div>

            <?php if($this->session->flashdata('success')): ?><div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div><?php endif; ?>
            <?php if($this->session->flashdata('error')): ?><div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div><?php endif; ?>

            <div class="card" id="leadsList">
                <div class="card-header border-0">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">Recherche</label>
                            <form id="lead-search-form" method="get" action="<?= base_url('leads'); ?>" class="d-flex gap-2">
                                <input type="text" name="q" value="<?= htmlspecialchars($filters['q'] ?? '') ?>" class="form-control" placeholder="Nom / email / téléphone">
                            </form>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Type</label>
                            <select form="lead-search-form" name="type" class="form-select">
                                <option value="">Tous</option>
                                <?php foreach(['acheteur'=>'Acheteur','locataire'=>'Locataire'] as $k=>$v): ?>
                                    <option value="<?= $k; ?>" <?= (($filters['type']??'')===$k)?'selected':''; ?>><?= $v; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Statut</label>
                            <select form="lead-search-form" name="status" class="form-select">
                                <option value="">Tous</option>
                                <?php foreach(['nouveau'=>'Nouveau','qualifie'=>'Qualifié','en_cours'=>'En cours','converti'=>'Converti','perdu'=>'Perdu'] as $k=>$v): ?>
                                    <option value="<?= $k; ?>" <?= (($filters['status']??'')===$k)?'selected':''; ?>><?= $v; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex gap-2">
                            <button form="lead-search-form" class="btn btn-primary mt-3">Filtrer</button>
                            <a href="<?= base_url('leads'); ?>" class="btn btn-light mt-3">Reset</a>
                        </div>
                        <div class="col-md-2 text-end">
                            <a href="<?= base_url('leads/nouveau'); ?>" class="btn btn-success mt-3"><i class="ri-add-line"></i> Nouveau lead</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive table-card">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Nom</th>
                                    <th>Type</th>
                                    <th>Statut</th>
                                    <th>Score</th>
                                    <th>Email</th>
                                    <th>Téléphone</th>
                                    <th>Créé</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($leads)): ?>
                                    <tr><td colspan="9" class="text-center text-muted">Aucun lead</td></tr>
                                <?php else: foreach($leads as $ld): ?>
                                    <tr>
                                        <td>#<?= (int)$ld['id']; ?></td>
                                        <td><?= htmlspecialchars(trim(($ld['prenom']??'').' '.($ld['nom']??''))) ?: '—'; ?></td>
                                        <td><span class="badge bg-secondary-subtle text-secondary"><?= ucfirst($ld['type']); ?></span></td>
                                        <td>
                                            <?php $map=['nouveau'=>'info','qualifie'=>'primary','en_cours'=>'warning','converti'=>'success','perdu'=>'danger']; $stc=$map[$ld['status']]??'secondary'; ?>
                                            <span class="badge bg-<?= $stc; ?>-subtle text-<?= $stc; ?>"><?= ucfirst(str_replace('_',' ', $ld['status'])); ?></span>
                                        </td>
                                        <td><?= (int)($ld['lead_score'] ?? 0); ?></td>
                                        <td><?= htmlspecialchars($ld['email'] ?? ''); ?></td>
                                        <td><?= htmlspecialchars($ld['telephone'] ?? ''); ?></td>
                                        <td><?= htmlspecialchars($ld['created_at'] ?? ''); ?></td>
                                        <td class="text-end">
                                            <div class="hstack gap-2 justify-content-end">
                                                <a href="<?= base_url('leads/edit/'.$ld['id']); ?>" class="btn btn-sm btn-light" title="Modifier"><i class="ri-pencil-line"></i></a>
                                                <a href="<?= base_url('leads/delete/'.$ld['id']); ?>" class="btn btn-sm btn-soft-danger" onclick="return confirm('Supprimer ce lead ?');" title="Supprimer"><i class="ri-delete-bin-line"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if(!empty($pagination) && $pagination['total'] > $pagination['per_page']):
                            $pages = (int)ceil($pagination['total'] / $pagination['per_page']);
                            $cur = $pagination['page'];
                    ?>
                        <nav class="mt-3">
                            <ul class="pagination justify-content-end mb-0">
                                <li class="page-item <?= $cur<=1?'disabled':''; ?>"><a class="page-link" href="<?= $cur<=1?'#':base_url('leads/page/'.($cur-1).'?'.http_build_query(array_filter($filters))); ?>">&laquo;</a></li>
                                <?php for($p=max(1,$cur-2); $p<=min($pages,$cur+2); $p++): ?>
                                    <li class="page-item <?= $p==$cur?'active':''; ?>"><a class="page-link" href="<?= base_url('leads/page/'.$p.'?'.http_build_query(array_filter($filters))); ?>"><?= $p; ?></a></li>
                                <?php endfor; ?>
                                <li class="page-item <?= $cur>=$pages?'disabled':''; ?>"><a class="page-link" href="<?= $cur>=$pages?'#':base_url('leads/page/'.($cur+1).'?'.http_build_query(array_filter($filters))); ?>">&raquo;</a></li>
                            </ul>
                        </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('includes/footer'); ?>
