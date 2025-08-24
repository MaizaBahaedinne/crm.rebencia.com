<h2>Liste des clients</h2>
<a href="/clients/create">Ajouter un client</a>
<table border="1" cellpadding="5">
    <tr>
        <th>ID</th><th>Nom</th><th>Prénom</th><th>Email</th><th>Téléphone</th><th>Type</th><th>Actions</th>
    </tr>
    <?php foreach ($clients as $client): ?>
    <tr>
        <td><?= esc($client['id']) ?></td>
        <td><?= esc($client['nom']) ?></td>
        <td><?= esc($client['prenom']) ?></td>
        <td><?= esc($client['email']) ?></td>
        <td><?= esc($client['telephone']) ?></td>
        <td><?= esc($client['type_client']) ?></td>
        <td>
            <a href="/clients/show/<?= $client['id'] ?>">Voir</a> |
            <a href="/clients/edit/<?= $client['id'] ?>">Modifier</a> |
            <a href="/clients/delete/<?= $client['id'] ?>" onclick="return confirm('Supprimer ce client ?');">Supprimer</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
