<?php
// Script pour ajouter la configuration rebenciaBD

$database_file = 'application/config/database.php';
$content = file_get_contents($database_file);

// Vérifier si rebenciaBD existe déjà comme clé de configuration
if (strpos($content, "\$db['rebenciaBD']") === false) {
    echo "Ajout de la configuration rebenciaBD...\n";
    
    // Configuration à ajouter
    $rebenciaBD_config = "
// Configuration pour la base de données WordPress (alias rebenciaBD)
\$db['rebenciaBD'] = array(
	'dsn'	=> '',
	'hostname' => 'localhost',
	'username' => 'rebencia_rebencia',
	'password' => 'Rebencia1402!!',
	'database' => 'rebencia_RebenciaBD',
	'dbdriver' => 'mysqli',
	'dbprefix' => 'wp_Hrg8P_',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);
";
    
    // Ajouter à la fin du fichier
    $content .= $rebenciaBD_config;
    
    // Sauvegarder
    file_put_contents($database_file, $content);
    echo "✅ Configuration rebenciaBD ajoutée avec succès!\n";
} else {
    echo "⚠️ Configuration rebenciaBD existe déjà\n";
}

// Vérifier la configuration
echo "\nVérification des configurations disponibles:\n";
include $database_file;

if (isset($db)) {
    foreach ($db as $group => $config) {
        echo "- $group: {$config['database']}\n";
    }
}
?>
