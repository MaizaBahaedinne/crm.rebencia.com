<?php
// Version ultra-simple sans CodeIgniter pour l'autocomplétion
header('Content-Type: application/json');

try {
    $action = isset($_GET['action']) ? $_GET['action'] : 'test';
    $query = isset($_POST['query']) ? $_POST['query'] : (isset($_GET['query']) ? $_GET['query'] : '');
    
    if ($action === 'agencies') {
        // Autocomplétion des agences
        if (!$query || strlen($query) < 2) {
            echo json_encode(['success' => false, 'message' => 'Minimum 2 caractères requis']);
            exit;
        }
        
        $all_agencies = [
            ['id' => 18907, 'name' => 'Agence Ben arous'],
            ['id' => 12345, 'name' => 'Agence Test'],
            ['id' => 67890, 'name' => 'Agence Centre Ville']
        ];
        
        $filtered_agencies = [];
        foreach ($all_agencies as $agency) {
            if (stripos($agency['name'], $query) !== false) {
                $agency['display'] = $agency['name'];
                $filtered_agencies[] = $agency;
            }
        }
        
        echo json_encode([
            'success' => true,
            'agencies' => $filtered_agencies,
            'count' => count($filtered_agencies),
            'query' => $query
        ]);
        
    } elseif ($action === 'agents') {
        // Agents par agence
        $agency_id = isset($_POST['agency_id']) ? $_POST['agency_id'] : (isset($_GET['agency_id']) ? $_GET['agency_id'] : '');
        
        if (!$agency_id) {
            echo json_encode(['success' => false, 'message' => 'ID agence requis']);
            exit;
        }
        
        $agents_by_agency = [
            18907 => [
                ['id' => 123, 'name' => 'Montasar Barkouti', 'display' => 'Montasar Barkouti - Agent Commercial'],
                ['id' => 124, 'name' => 'Ahmed Ben Ali', 'display' => 'Ahmed Ben Ali - Responsable Ventes']
            ],
            12345 => [
                ['id' => 125, 'name' => 'Sarah Trabelsi', 'display' => 'Sarah Trabelsi - Agent'],
                ['id' => 126, 'name' => 'Karim Souissi', 'display' => 'Karim Souissi - Manager']
            ]
        ];
        
        $agents = isset($agents_by_agency[$agency_id]) ? $agents_by_agency[$agency_id] : [];
        
        // Filtrer par query si fournie
        if ($query && strlen($query) >= 2) {
            $agents = array_filter($agents, function($agent) use ($query) {
                return stripos($agent['name'], $query) !== false;
            });
            $agents = array_values($agents);
        }
        
        echo json_encode(['success' => true, 'agents' => $agents]);
        
    } else {
        // Test de base
        $response = [
            'success' => true,
            'message' => 'Test direct PHP réussi',
            'timestamp' => time(),
            'method' => $_SERVER['REQUEST_METHOD'],
            'post_data' => $_POST,
            'get_data' => $_GET
        ];
        
        echo json_encode($response);
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'file' => __FILE__,
        'line' => __LINE__
    ]);
}
?>
