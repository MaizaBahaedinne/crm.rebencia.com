<div class="row">
<?php foreach($clients as $c): ?>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title mb-2 text-truncate"><?= htmlspecialchars($c->nom) ?></h5>
                <p class="mb-1"><strong>Email :</strong> <?= htmlspecialchars($c->email) ?></p>
                <p class="mb-1"><strong>Téléphone :</strong> <?= htmlspecialchars($c->telephone) ?></p>
                <p class="mb-1"><strong>Adresse :</strong> <?= htmlspecialchars($c->adresse) ?></p>
                <a href="<?= base_url('client/view/'.$c->id); ?>" class="btn btn-sm btn-outline-primary w-100">Voir le client</a>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>
