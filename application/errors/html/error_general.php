<html>
<head><title>Error</title></head>
<body>
<h1>Une erreur est survenue</h1>
<p><?= isset($message) ? htmlspecialchars($message) : 'Erreur inconnue' ?></p>
<p>Fichier : <?= isset($filepath) ? htmlspecialchars($filepath) : '' ?> Ligne : <?= isset($line) ? $line : '' ?></p>
</body>
</html>
