<html>
<head><title>PHP Error</title></head>
<body>
<h1>PHP Error</h1>
<p><?= htmlspecialchars($message) ?></p>
<p>File: <?= htmlspecialchars($filepath) ?> Line: <?= $line ?></p>
</body>
</html>
