<div class="row">
  <div class="col-lg-10">
    <div class="card">
      <div class="card-body">
        <a href="<?= base_url('mail/inbox'); ?>" class="btn btn-sm btn-secondary mb-3">&larr; Retour</a>
        <h4><?= htmlspecialchars($message['subject']); ?></h4>
        <p><strong>De :</strong> <?= htmlspecialchars($message['from']); ?><br>
        <strong>Date :</strong> <?= htmlspecialchars($message['date']); ?></p>
        <?php if(!empty($message['attachments'])): ?>
          <div class="mb-3">
            <strong>Pièces jointes:</strong>
            <ul class="list-unstyled mb-0">
              <?php foreach($message['attachments'] as $att): ?>
                <li>📎 <?= htmlspecialchars($att['name']); ?> (<?= number_format($att['size']/1024,1); ?> Ko)
                  <a class="ms-2" href="<?= base_url('mail/download/'.$message['uid'].'/'.$att['part']); ?>">Télécharger</a>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>
        <hr>
        <div class="mail-body">
          <?php if($message['body_html']): ?>
            <?= $message['body_html']; ?>
          <?php else: ?>
            <pre class="mb-0" style="white-space:pre-wrap;">"<?= htmlspecialchars($message['body_text']); ?>"</pre>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>