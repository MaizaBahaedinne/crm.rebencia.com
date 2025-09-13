<?php
echo "<h1>🌐 Test de connectivité réseau</h1>";

$host = '146.59.94.214';
$port = 3306;
$timeout = 10;

echo "<h2>Test de ping et port</h2>";

// Test 1: Ping
echo "<h3>1. Test de ping</h3>";
$ping_result = shell_exec("ping -c 3 $host 2>&1");
echo "<pre>" . htmlspecialchars($ping_result) . "</pre>";

// Test 2: Test du port avec telnet/nc
echo "<h3>2. Test du port MySQL ($port)</h3>";
$port_test = shell_exec("nc -z -v $host $port 2>&1 || telnet $host $port 2>&1");
echo "<pre>" . htmlspecialchars($port_test) . "</pre>";

// Test 3: Test avec curl
echo "<h3>3. Test avec curl</h3>";
$curl_test = shell_exec("curl -v telnet://$host:$port 2>&1");
echo "<pre>" . htmlspecialchars($curl_test) . "</pre>";

// Test 4: Test de résolution DNS
echo "<h3>4. Test de résolution DNS</h3>";
$dns_test = shell_exec("nslookup $host 2>&1");
echo "<pre>" . htmlspecialchars($dns_test) . "</pre>";

// Test 5: Traceroute
echo "<h3>5. Traceroute vers le serveur</h3>";
$traceroute = shell_exec("traceroute $host 2>&1");
echo "<pre>" . htmlspecialchars($traceroute) . "</pre>";

echo "<hr>";
echo "<h2>💡 Solutions possibles</h2>";
echo "<ol>";
echo "<li><strong>Serveur MySQL non démarré:</strong> Vérifiez que MySQL est actif sur le serveur distant</li>";
echo "<li><strong>Pare-feu:</strong> Le port 3306 peut être bloqué par un pare-feu</li>";
echo "<li><strong>Configuration MySQL:</strong> MySQL peut être configuré pour n'accepter que les connexions locales</li>";
echo "<li><strong>Identifiants incorrects:</strong> Vérifiez le nom d'utilisateur et mot de passe</li>";
echo "<li><strong>Base de données inexistante:</strong> La base spécifiée n'existe peut-être pas</li>";
echo "</ol>";

echo "<h2>🔧 Commandes à essayer sur le serveur distant</h2>";
echo "<pre>";
echo "# Vérifier si MySQL est actif
sudo systemctl status mysql

# Vérifier les ports en écoute
sudo netstat -tlnp | grep 3306

# Vérifier la configuration MySQL
sudo mysql -e \"SHOW VARIABLES LIKE 'bind_address';\"

# Créer un utilisateur pour connexion externe
sudo mysql -e \"CREATE USER 'remote_user'@'%' IDENTIFIED BY 'password';\"
sudo mysql -e \"GRANT ALL PRIVILEGES ON *.* TO 'remote_user'@'%';\"
sudo mysql -e \"FLUSH PRIVILEGES;\"
";
echo "</pre>";
?>
