<div class="row">
  <div class="col-lg-8">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Composer un email</h4>
        <?= validation_errors('<div class="alert alert-danger">','</div>'); ?>
        <?php if($this->session->flashdata('error')): ?>
          <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
        <?php endif; ?>
        <form method="post" action="<?= base_url('mail/send'); ?>" enctype="multipart/form-data">
          <div class="mb-3">
            <label class="form-label">Destinataire</label>
            <input type="email" name="to" class="form-control" required value="<?= set_value('to'); ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">Sujet</label>
            <input type="text" name="subject" maxlength="255" class="form-control" required value="<?= set_value('subject'); ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">Message (HTML autorisé basique)</label>
            <textarea name="message" rows="8" class="form-control" required><?= set_value('message'); ?></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Pièces jointes</label>
            <input type="file" name="attachments[]" multiple class="form-control" />
            <small class="text-muted">Taille cumulée recommandée &lt; 10 Mo</small>
          </div>
          <button type="submit" class="btn btn-success">Envoyer</button>
          <a href="<?= base_url('mail/inbox'); ?>" class="btn btn-light">Annuler</a>
        </form>
      </div>
    </div>
  </div>
</div>