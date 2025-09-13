<?php
echo "<h1>🔐 Solution avec tunnel SSH</h1>";
echo "<p>Si la connexion directe ne fonctionne pas, voici comment créer un tunnel SSH:</p>";

echo "<h2>🚀 Méthode 1: Tunnel SSH</h2>";
echo "<pre>";
echo "# Créer un tunnel SSH vers le serveur
ssh -L 3307:localhost:3306 username@146.59.94.214

# Puis se connecter via le tunnel local
mysql -h 127.0.0.1 -P 3307 -u rebencia_rebencia -p
";
echo "</pre>";

echo "<h2>🛠️ Méthode 2: Modification de MySQL</h2>";
echo "<p>Si vous avez accès au serveur, modifiez la configuration MySQL:</p>";
echo "<pre>";
echo "# Éditer le fichier de configuration MySQL
sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf

# Changer cette ligne:
# bind-address = 127.0.0.1
# en:
# bind-address = 0.0.0.0

# Redémarrer MySQL
sudo systemctl restart mysql

# Créer un utilisateur pour connexions externes
sudo mysql -e \"CREATE USER 'external'@'%' IDENTIFIED BY 'password';\"
sudo mysql -e \"GRANT ALL PRIVILEGES ON rebencia_RebenciaBD.* TO 'external'@'%';\"
sudo mysql -e \"FLUSH PRIVILEGES;\"
";
echo "</pre>";

echo "<h2>🔍 Méthode 3: Connexion via phpMyAdmin</h2>";
echo "<p>Si phpMyAdmin est installé sur le serveur, vous pouvez l'utiliser:</p>";
echo "<pre>";
echo "http://146.59.94.214/phpmyadmin/
# ou
https://146.59.94.214/phpmyadmin/
";
echo "</pre>";

echo "<h2>📊 Méthode 4: Export/Import de données</h2>";
echo "<p>Exporter les données depuis le serveur distant:</p>";
echo "<pre>";
echo "# Sur le serveur distant
mysqldump -u rebencia_rebencia -p rebencia_RebenciaBD > export.sql

# Télécharger le fichier
scp username@146.59.94.214:/path/to/export.sql ./

# Importer en local
mysql -u root -p wordpress < export.sql
";
echo "</pre>";

echo "<h2>☁️ Méthode 5: API REST</h2>";
echo "<p>Créer une API sur le serveur distant pour accéder aux données:</p>";
echo "<pre>";
echo "# Créer un fichier PHP sur le serveur distant: api.php
&lt;?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

\$pdo = new PDO('mysql:host=localhost;dbname=rebencia_RebenciaBD', 'rebencia_rebencia', 'password');

if (\$_GET['action'] === 'tables') {
    \$stmt = \$pdo->query('SHOW TABLES');
    echo json_encode(\$stmt->fetchAll(PDO::FETCH_COLUMN));
} elseif (\$_GET['action'] === 'describe' && \$_GET['table']) {
    \$stmt = \$pdo->query('DESCRIBE ' . \$_GET['table']);
    echo json_encode(\$stmt->fetchAll(PDO::FETCH_ASSOC));
}
?&gt;

# Puis l'utiliser depuis votre site:
# https://146.59.94.214/api.php?action=tables
# https://146.59.94.214/api.php?action=describe&table=wp_posts
";
echo "</pre>";

// Test de l'API si elle existe
echo "<h2>🧪 Test API (si disponible)</h2>";
$api_urls = [
    'https://146.59.94.214/api.php?action=tables',
    'http://146.59.94.214/api.php?action=tables',
    'https://rebencia.com/api.php?action=tables',
    'http://rebencia.com/api.php?action=tables'
];

foreach ($api_urls as $url) {
    echo "<p>Test: <a href='$url' target='_blank'>$url</a></p>";
}
?>
