<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Login - Rebencia CRM</title>
</head>
<body>
    <h2>Connexion</h2>

    <?php if(session()->getFlashdata('error')): ?>
        <p style="color:red;"><?= session()->getFlashdata('error') ?></p>
    <?php endif; ?>

    <?php if(isset($validation)): ?>
        <ul>
            <?php foreach($validation->getErrors() as $error): ?>
                <li style="color:red;"><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form action="<?= base_url('login') ?>" method="post">
        <label>Email:</label>
        <input type="text" name="email" required><br><br>

        <label>Mot de passe:</label>
        <input type="password" name="password" required><br><br>

        <button type="submit">Se connecter</button>
    </form>
</body>
</html>
