<div class="row">
<?php foreach($properties as $p): ?>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <img src="<?= htmlspecialchars($p->photo_principale) ?>" class="card-img-top" alt="Photo">
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($p->titre) ?></h5>
                <p class="card-text">
                    <span class="badge bg-info"><?= htmlspecialchars($p->statut) ?></span>
                    <span class="badge bg-primary"><?= htmlspecialchars($p->type) ?></span>
                </p>
                <p>
                    <strong>Prix :</strong> <?= number_format($p->prix, 0, ',', ' ') ?> TND<br>
                    <strong>Surface :</strong> <?= $p->surface ?> m²<br>
                    <strong>Chambres :</strong> <?= $p->chambres ?> | <strong>Sdb :</strong> <?= $p->salles_de_bain ?><br>
                    <strong>Adresse :</strong> <?= htmlspecialchars($p->adresse_courte) ?>
                </p>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>
<!-- Ajoute ici la pagination si besoin -->
