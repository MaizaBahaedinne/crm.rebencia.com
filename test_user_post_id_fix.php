<?php
session_start();

echo "<h2>Test de la correction user_post_id</h2>";

if (isset($_SESSION['vendor_id'])) {
    echo "<div style='background: #f0f8ff; padding: 15px; margin: 10px; border-radius: 5px;'>";
    echo "<h3>Session actuelle:</h3>";
    echo "<strong>vendor_id (User ID):</strong> " . ($_SESSION['vendor_id'] ?? 'Non défini') . "<br>";
    echo "<strong>user_post_id (Matricule):</strong> " . ($_SESSION['user_post_id'] ?? 'Non défini') . "<br>";
    echo "<strong>agent_id:</strong> " . ($_SESSION['agent_id'] ?? 'Non défini') . "<br>";
    echo "<strong>agency_id:</strong> " . ($_SESSION['agency_id'] ?? 'Non défini') . "<br>";
    echo "<strong>role:</strong> " . ($_SESSION['role'] ?? 'Non défini') . "<br>";
    echo "</div>";
    
    // Test la logique du header
    $userId = $_SESSION['vendor_id'] ?? 'N/A';
    $user_post_id = $_SESSION['user_post_id'] ?? 'N/A';
    
    echo "<div style='background: #fff3cd; padding: 15px; margin: 10px; border-radius: 5px;'>";
    echo "<h3>Test logique header:</h3>";
    echo "<strong>User ID:</strong> $userId<br>";
    echo "<strong>Matricule:</strong> ";
    
    if ($user_post_id === $userId || $user_post_id === 'N/A' || empty($user_post_id)) {
        echo '<span style="color: orange;">Pas de profil agent</span>';
    } else {
        echo $user_post_id;
    }
    echo "<br>";
    
    echo "<div style='margin-top: 10px; padding: 10px; background: #e7f3ff; border-radius: 3px;'>";
    echo "<strong>Diagnostic:</strong><br>";
    if ($user_post_id === $userId) {
        echo "❌ user_post_id identique à vendor_id - Problème de duplication détecté<br>";
    } elseif (empty($user_post_id) || $user_post_id === 'N/A') {
        echo "✅ user_post_id vide/null - Pas de profil agent (correct)<br>";
    } else {
        echo "✅ user_post_id différent de vendor_id - Profil agent trouvé<br>";
    }
    echo "</div>";
    echo "</div>";
    
} else {
    echo "<div style='background: #f8d7da; padding: 15px; margin: 10px; border-radius: 5px; color: #721c24;'>";
    echo "<strong>Utilisateur non connecté</strong><br>";
    echo "Veuillez vous connecter d'abord: <a href='index.php/login'>Se connecter</a>";
    echo "</div>";
}

echo "<div style='margin-top: 20px;'>";
echo "<a href='index.php/login' style='padding: 8px 16px; background: #007bff; color: white; text-decoration: none; border-radius: 4px;'>Aller à la connexion</a> ";
echo "<a href='index.php/dashboard' style='padding: 8px 16px; background: #28a745; color: white; text-decoration: none; border-radius: 4px;'>Aller au dashboard</a>";
echo "</div>";
?>
