<?php
echo "Test simple PHP: " . date('Y-m-d H:i:s') . "<br>";

// Test de base
if (file_exists('application/config/config.php')) {
    echo "✓ Fichier config existe<br>";
} else {
    echo "✗ Fichier config manquant<br>";
}

if (file_exists('index.php')) {
    echo "✓ Index.php existe<br>";
} else {
    echo "✗ Index.php manquant<br>";
}

// Test du contrôleur Agent
if (file_exists('application/controllers/Agent.php')) {
    echo "✓ Contrôleur Agent existe<br>";
    
    // Vérifier la taille du fichier
    $size = filesize('application/controllers/Agent.php');
    echo "Taille du fichier Agent.php: " . $size . " bytes<br>";
    
    if ($size > 1000) {
        echo "✓ Fichier Agent.php semble avoir du contenu<br>";
    } else {
        echo "⚠ Fichier Agent.php semble vide ou très petit<br>";
    }
} else {
    echo "✗ Contrôleur Agent manquant<br>";
}

// Test du modèle Agent
if (file_exists('application/models/Agent_model.php')) {
    echo "✓ Modèle Agent_model existe<br>";
} else {
    echo "✗ Modèle Agent_model manquant<br>";
}

// Test de la vue
if (file_exists('application/views/dashboard/agents/index.php')) {
    echo "✓ Vue agents/index existe<br>";
} else {
    echo "✗ Vue agents/index manquante<br>";
}

echo "<br>Test terminé.";
?>
