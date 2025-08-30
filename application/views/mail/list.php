<?php
// Préparation variables pour injection dans template original
function h($v){return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');}
$page = isset($pagination['page'])?(int)$pagination['page']:1;
$pages = isset($pagination['pages'])?(int)$pagination['pages']:1;
$total = isset($pagination['total'])?(int)$pagination['total']:count($emails);
$baseUrl = site_url('mail/inbox');
?>
<!-- Début structure template original adaptée -->
<div class="main-content">
 <div class="page-content">
    <div class="container-fluid">
     <div class="email-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
        <div class="email-menu-sidebar minimal-border">
         <div class="p-4 d-flex flex-column h-100">
            <div class="pb-4 border-bottom border-bottom-dashed">
             <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#composeModal"><i data-feather="plus-circle" class="icon-xs me-1 icon-dual-light"></i> Nouveau message</button>
            </div>
            <div class="mx-n4 px-4 email-menu-sidebar-scroll" data-simplebar>
             <div class="mail-list mt-3">
                <a href="<?= $baseUrl ?>" class="<?= ($filter!='unread'?'active':'') ?>"><i class="ri-mail-fill me-3 align-middle fw-medium"></i> <span class="mail-list-link">Tous</span> <span class="badge bg-success-subtle text-success ms-auto"><?= $total ?></span></a>
                <a href="<?= $baseUrl ?>?filter=unread" class="<?= ($filter=='unread'?'active':'') ?>"><i class="ri-inbox-archive-fill me-3 align-middle fw-medium"></i> <span class="mail-list-link">Non lus</span></a>
             </div>
            </div>
            <div class="mt-auto">
             <small class="text-muted">Total : <?= $total ?> message(s)</small>
            </div>
         </div>
        </div>
        <!-- email list -->
        <div class="email-content minimal-border">
         <div class="p-4 pb-0">
            <div class="border-bottom border-bottom-dashed">
             <div class="row mt-n2 mb-3 mb-sm-0">
                <div class="col-sm order-2">
                 <?php if($this->session->flashdata('success')): ?><div class="alert alert-success py-2 mb-2"><?= h($this->session->flashdata('success')) ?></div><?php endif; ?>
                 <?php if($this->session->flashdata('error')): ?><div class="alert alert-danger py-2 mb-2"><?= h($this->session->flashdata('error')) ?></div><?php endif; ?>
                </div>
                <div class="col-auto order-3">
                 <div class="text-muted small">Page <?= $page ?>/<?= $pages ?> (<?= $total ?>)</div>
                </div>
             </div>
             <div class="row align-items-end mt-3">
                <div class="col">
                 <div id="mail-filter-navlist">
                    <ul class="nav nav-tabs nav-tabs-custom nav-success gap-1 text-center border-bottom-0" role="tablist">
                     <li class="nav-item">
                        <button class="nav-link fw-semibold active" data-bs-toggle="pill" data-bs-target="#pills-primary" type="button" role="tab"><i class="ri-inbox-fill align-bottom d-inline-block"></i> <span class="ms-1 d-none d-sm-inline-block">Principale</span></button>
                     </li>
                    </ul>
                 </div>
                </div>
                <div class="col-auto">
                 <div class="btn-group btn-group-sm">
                    <?php if($pages>1): ?>
                     <a class="btn btn-outline-secondary<?= $page<=1?' disabled':'' ?>" href="<?= $page>1?$baseUrl.'?filter='.$filter.'&page='.($page-1):'#' ?>">&laquo;</a>
                     <a class="btn btn-outline-secondary<?= $page>=$pages?' disabled':'' ?>" href="<?= $page<$pages?$baseUrl.'?filter='.$filter.'&page='.($page+1):'#' ?>">&raquo;</a>
                    <?php endif; ?>
                    <a class="btn btn-outline-secondary" href="<?= $baseUrl.'?filter='.$filter.'&page='.$page ?>"><i class="ri-refresh-line"></i></a>
                 </div>
                </div>
             </div>
            </div>
            <div class="tab-content">
             <div class="tab-pane fade show active" id="pills-primary" role="tabpanel">
                <div class="message-list-content mx-n4 px-4 message-list-scroll">
                 <div id="elmLoader"></div>
                 <ul class="message-list" id="mail-list">
                    <?php if(empty($emails)): ?>
                        <li class="text-center py-4"><em>Aucun message.</em></li>
                    <?php else: foreach($emails as $idx=>$m):
                        $uid = (int)$m['uid'];
                        $unread = $m['seen']?'' : 'unread';
                        $star = '';
                        $from = h($m['from']);
                        $subject = h($m['subject']!==''?$m['subject']:'(Sans objet)');
                        $snippet = h($m['snippet']);
                        $date = h($m['date']);
                        $counted = '';
                        $userImg = base_url('assets/images/users/user-dummy-img.jpg');
                    ?>
                    <li class="<?= $unread ?>">
                     <div class="col-mail col-mail-1">
                        <div class="form-check checkbox-wrapper-mail fs-14">
                         <input class="form-check-input" type="checkbox" value="<?= $uid ?>" id="checkbox-<?= $uid ?>">
                         <label class="form-check-label" for="checkbox-<?= $uid ?>"></label>
                        </div>
                        <input type="hidden" value="<?= $userImg ?>" class="mail-userimg" />
                        <button type="button" class="btn avatar-xs p-0 material-shadow-none favourite-btn fs-15 <?= $star ?>"><i class="ri-star-fill"></i></button>
                        <a href="<?= site_url('mail/view/'.$uid) ?>" class="title"><span class="title-name"><?= $from ?></span> <?= $counted ?></a>
                     </div>
                     <div class="col-mail col-mail-2">
                        <a href="<?= site_url('mail/view/'.$uid) ?>" class="subject"><span class="subject-title"><?= $subject ?></span> – <span class="teaser"><?= $snippet ?></span></a>
                        <div class="date"><?= $date ?></div>
                     </div>
                    </li>
                    <?php endforeach; endif; ?>
                 </ul>
                </div>
             </div>
            </div>
         </div>
        </div>
        <!-- panneau détail resté dans template -->
        <div class="email-detail-content minimal-border">
         <div class="p-4 d-flex flex-column h-100">
            <div class="pb-4 border-bottom border-bottom-dashed">
             <div class="row"><div class="col"><button type="button" class="btn btn-soft-danger btn-icon btn-sm fs-16 close-btn-email"><i class="ri-close-fill align-bottom"></i></button></div></div>
            </div>
            <div class="mx-n4 px-4 email-detail-content-scroll" data-simplebar>
             <div class="mt-4 mb-3"><h5 class="fw-bold email-subject-title">Sujet</h5></div>
             <div class="accordion accordion-flush"><div class="accordion-item border-dashed left"><div class="accordion-header"><a class="btn w-100 text-start px-0 bg-transparent shadow-none collapsed"> <div class="d-flex align-items-center text-muted"><div class="flex-shrink-0 avatar-xs me-3"><img src="<?= base_url('assets/images/users/user-dummy-img.jpg') ?>" class="img-fluid rounded-circle" alt=""></div><div class="flex-grow-1 overflow-hidden"><h5 class="fs-14 text-truncate email-user-name mb-0">Expéditeur</h5><div class="text-truncate fs-12">à : moi</div></div><div class="flex-shrink-0 align-self-start"><div class="text-muted fs-12">—</div></div></div></a></div><div class="accordion-collapse show"><div class="accordion-body text-body px-0"><p>Sélectionnez un message pour afficher son contenu.</p></div></div></div></div>
            </div>
            <div class="mt-auto small text-muted">Vue de détail dynamique (JS)</div>
         </div>
        </div>
     </div>
    </div>
 </div>
</div>

<!-- Modal Composer -->
<div class="modal fade" id="composeModal" tabindex="-1" aria-hidden="true">
 <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
     <form action="<?= site_url('mail/send') ?>" method="post" enctype="multipart/form-data">
        <div class="modal-header"><h5 class="modal-title">Nouveau message</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
         <div class="mb-3"><label class="form-label">À</label><input type="email" name="to" class="form-control" required></div>
         <div class="mb-3"><label class="form-label">Sujet</label><input type="text" name="subject" class="form-control" required maxlength="255"></div>
         <div class="mb-3"><label class="form-label">Message</label><textarea name="message" rows="6" class="form-control" required></textarea></div>
         <div class="mb-3"><label class="form-label">Pièces jointes</label><input type="file" name="attachments[]" multiple class="form-control"></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button><button type="submit" class="btn btn-primary"><i class="ri-send-plane-2-fill"></i> Envoyer</button></div>
     </form>
    </div>
 </div>
</div>