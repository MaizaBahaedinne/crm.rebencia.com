<?php
// Variables attendues : $emails (array), $pagination (array), $filter (string)
// Helpers simples
function h($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
$page = isset($pagination['page']) ? (int)$pagination['page'] : 1;
$pages = isset($pagination['pages']) ? (int)$pagination['pages'] : 1;
$total = isset($pagination['total']) ? (int)$pagination['total'] : count($emails);
$baseUrl = site_url('mail/inbox');
$queryBase = function($params=[]) use ($filter,$page){
    $base = ['filter'=>$filter,'page'=>$page];
    $merged = array_merge($base,$params);
    return '?'.http_build_query(array_filter($merged, fn($v)=>$v!==null && $v!==''));
};
?>

<div class="container-fluid mt-3">
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
        <h4 class="mb-2">Boîte de réception</h4>
        <div class="btn-group mb-2">
            <a href="<?= $baseUrl ?>" class="btn btn-outline-secondary btn-sm<?= ($filter!='unread'?' active':'') ?>">Tous</a>
            <a href="<?= $baseUrl ?>?filter=unread" class="btn btn-outline-secondary btn-sm<?= ($filter=='unread'?' active':'') ?>">Non lus</a>
        </div>
        <div class="mb-2">
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#composeModal"><i class="ri-add-line"></i> Composer</button>
            <a href="<?= $baseUrl.$queryBase(['page'=>$page]) ?>" class="btn btn-light btn-sm"><i class="ri-refresh-line"></i></a>
        </div>
    </div>

    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success py-2"><?= h($this->session->flashdata('success')) ?></div>
    <?php endif; ?>
    <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-danger py-2"><?= h($this->session->flashdata('error')) ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <strong><?= number_format($total,0,'',' ') ?></strong> message(s)
                <?php if($filter=='unread'): ?> - affichage des non lus<?php endif; ?>
            </div>
            <div class="d-flex align-items-center gap-2">
                <?php if($pages>1): ?>
                    <nav aria-label="Pagination emails">
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item<?= $page<=1?' disabled':'' ?>">
                                <a class="page-link" href="<?= $page>1? $baseUrl.$queryBase(['page'=>$page-1]):'#' ?>" aria-label="Précédent">&laquo;</a>
                            </li>
                            <?php
                            $window = 5; // fenêtre simple
                            $start = max(1, $page - 2);
                            $end = min($pages, $start + $window -1);
                            if($end - $start < $window-1) { $start = max(1, $end-$window+1); }
                            for($p=$start;$p<=$end;$p++): ?>
                                <li class="page-item<?= $p==$page?' active':'' ?>">
                                    <a class="page-link" href="<?= $baseUrl.$queryBase(['page'=>$p]) ?>"><?= $p ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item<?= $page>=$pages?' disabled':'' ?>">
                                <a class="page-link" href="<?= $page<$pages? $baseUrl.$queryBase(['page'=>$page+1]):'#' ?>" aria-label="Suivant">&raquo;</a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead class="table-light">
                <tr>
                    <th style="width:30px"></th>
                    <th>Expéditeur</th>
                    <th>Sujet</th>
                    <th>Extrait</th>
                    <th class="text-nowrap">Date</th>
                    <th style="width:120px">Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php if(empty($emails)): ?>
                    <tr><td colspan="6" class="text-center py-4">Aucun message.</td></tr>
                <?php else: foreach($emails as $m): ?>
                    <?php
                        $rowClass = $m['seen'] ? '' : 'table-warning';
                        $uid = (int)$m['uid'];
                        $subject = $m['subject']!=='' ? $m['subject'] : '(Sans objet)';
                    ?>
                    <tr class="<?= $rowClass ?>">
                        <td class="text-center">
                            <?php if($m['attachments']): ?><i class="ri-attachment-2"></i><?php endif; ?>
                        </td>
                        <td class="fw-semibold small"><?= h($m['from']) ?></td>
                        <td class="small">
                            <a href="<?= site_url('mail/view/'.$uid) ?>" class="text-decoration-none<?= $m['seen']?' text-muted':'' ?>"><?= h($subject) ?></a>
                        </td>
                        <td class="text-muted small"><?= h($m['snippet']) ?></td>
                        <td class="small text-nowrap"><?= h($m['date']) ?></td>
                        <td class="text-nowrap">
                            <?php if(!$m['seen']): ?>
                                <a class="btn btn-success btn-sm" title="Marquer comme lu" href="<?= site_url('mail/markRead/'.$uid) ?>"><i class="ri-check-fill"></i></a>
                            <?php else: ?>
                                <a class="btn btn-outline-secondary btn-sm" title="Marquer non lu" href="<?= site_url('mail/markUnread/'.$uid) ?>"><i class="ri-mail-unread-line"></i></a>
                            <?php endif; ?>
                            <a class="btn btn-outline-primary btn-sm" title="Ouvrir" href="<?= site_url('mail/view/'.$uid) ?>"><i class="ri-arrow-right-line"></i></a>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Composer -->
<div class="modal fade" id="composeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form action="<?= site_url('mail/send') ?>" method="post" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title">Nouveau message</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">À</label>
            <input type="email" name="to" class="form-control" required placeholder="destinataire@domaine.com">
          </div>
          <div class="mb-3">
            <label class="form-label">Sujet</label>
            <input type="text" name="subject" class="form-control" maxlength="255" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Message</label>
            <textarea name="message" rows="6" class="form-control" required></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Pièces jointes</label>
            <input type="file" name="attachments[]" class="form-control" multiple>
            <div class="form-text">Taille max selon configuration serveur.</div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-primary"><i class="ri-send-plane-2-fill"></i> Envoyer</button>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
/* Allègement visuel */
table tbody tr.table-warning { --bs-table-bg: #fff8e1; }
</style>