<?php
// Version ultra-simple sans CodeIgniter pour identifier le problème
header('Content-Type: application/json');

try {
    // Test de base
    $response = [
        'success' => true,
        'message' => 'Test direct PHP réussi',
        'timestamp' => time(),
        'method' => 'GET/POST direct',
        'post_data' => $_POST,
        'get_data' => $_GET
    ];
    
    echo json_encode($response);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'file' => __FILE__,
        'line' => __LINE__
    ]);
}
?>
