<?php
// Version avec vraie base de données WordPress et requête SQL complète
header('Content-Type: application/json');

try {
    // Configuration de la base WordPress
    $host = 'localhost';
    $dbname = 'rebencia_RebenciaBD';
    $username = 'rebencia_rebencia';
    $password = 'Rebencia1402!!';
    $prefix = 'wp_Hrg8P_';
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $action = isset($_GET['action']) ? $_GET['action'] : 'test';
    $query = isset($_POST['query']) ? $_POST['query'] : (isset($_GET['query']) ? $_GET['query'] : '');
    
    if ($action === 'agencies') {
        // Autocomplétion des agences depuis la vraie requête
        if (!$query || strlen($query) < 2) {
            echo json_encode(['success' => false, 'message' => 'Minimum 2 caractères requis']);
            exit;
        }
        
        // Requête pour récupérer les agences distinctes avec compteur d'agents
        $sql = "
            SELECT DISTINCT 
                a.ID as agency_id,
                a.post_title as agency_name,
                COUNT(DISTINCT u.ID) as agent_count
            FROM {$prefix}posts a
            LEFT JOIN {$prefix}postmeta pm_agency ON (pm_agency.meta_value = a.ID AND pm_agency.meta_key = 'fave_agent_agencies')
            LEFT JOIN {$prefix}posts p ON (p.ID = pm_agency.post_id AND p.post_type = 'houzez_agent')
            LEFT JOIN {$prefix}postmeta pm_email ON (pm_email.post_id = p.ID AND pm_email.meta_key = 'fave_agent_email')
            LEFT JOIN {$prefix}users u ON (u.user_email = pm_email.meta_value)
            WHERE a.post_type = 'houzez_agency' 
            AND a.post_status = 'publish'
            AND a.post_title LIKE :query
            GROUP BY a.ID, a.post_title
            ORDER BY a.post_title ASC
            LIMIT 10
        ";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['query' => '%' . $query . '%']);
        $agencies = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $filtered_agencies = [];
        foreach ($agencies as $agency) {
            $count = intval($agency['agent_count']);
            $filtered_agencies[] = [
                'id' => $agency['agency_id'],
                'name' => $agency['agency_name'],
                'agent_count' => $count,
                'display' => $agency['agency_name'] . " ({$count} agent" . ($count > 1 ? 's' : '') . ")"
            ];
        }
        
        echo json_encode([
            'success' => true,
            'agencies' => $filtered_agencies,
            'count' => count($filtered_agencies),
            'query' => $query,
            'source' => 'Vraie base HOUZEZ'
        ]);
        
    } elseif ($action === 'agents') {
        // Agents par agence avec la requête SQL complète
        $agency_id = isset($_POST['agency_id']) ? $_POST['agency_id'] : (isset($_GET['agency_id']) ? $_GET['agency_id'] : '');
        
        if (!$agency_id) {
            echo json_encode(['success' => false, 'message' => 'ID agence requis']);
            exit;
        }
        
        // Votre requête SQL complète adaptée pour filtrer par agence
        $sql = "
            SELECT 
                u.ID AS user_id,
                u.user_login AS user_login,
                u.user_email AS user_email,
                u.user_status AS user_status,
                u.user_registered AS registration_date,
                p.ID AS agent_post_id,
                p.post_title AS agent_name,
                p.post_status AS post_status,
                pm_email.meta_value AS agent_email,
                a.ID AS agency_id,
                a.post_title AS agency_name,
                MAX(CASE WHEN pm_contact.meta_key = 'fave_agent_phone' THEN pm_contact.meta_value END) AS phone,
                MAX(CASE WHEN pm_contact.meta_key = 'fave_agent_mobile' THEN pm_contact.meta_value END) AS mobile,
                MAX(CASE WHEN pm_contact.meta_key = 'fave_agent_whatsapp' THEN pm_contact.meta_value END) AS whatsapp,
                MAX(CASE WHEN pm_contact.meta_key = 'fave_agent_skype' THEN pm_contact.meta_value END) AS skype,
                MAX(CASE WHEN pm_contact.meta_key = 'fave_agent_website' THEN pm_contact.meta_value END) AS website,
                MAX(CASE WHEN pm_contact.meta_key = 'fave_agent_picture' THEN media.guid END) AS agent_avatar,
                MAX(CASE WHEN pm_contact.meta_key = 'fave_agent_position' THEN pm_contact.meta_value END) AS position,
                MAX(CASE WHEN pm_contact.meta_key = 'fave_agent_facebook' THEN pm_contact.meta_value END) AS facebook,
                MAX(CASE WHEN pm_contact.meta_key = 'fave_agent_twitter' THEN pm_contact.meta_value END) AS twitter,
                MAX(CASE WHEN pm_contact.meta_key = 'fave_agent_linkedin' THEN pm_contact.meta_value END) AS linkedin,
                MAX(CASE WHEN pm_contact.meta_key = 'fave_agent_zip' THEN pm_contact.meta_value END) AS postal_code
            FROM {$prefix}users u
            LEFT JOIN {$prefix}postmeta pm_email ON pm_email.meta_value = u.user_email
            LEFT JOIN {$prefix}posts p ON (p.ID = pm_email.post_id AND p.post_type = 'houzez_agent')
            LEFT JOIN {$prefix}postmeta pm_agency ON (pm_agency.post_id = p.ID AND pm_agency.meta_key = 'fave_agent_agencies')
            LEFT JOIN {$prefix}posts a ON (a.ID = pm_agency.meta_value AND a.post_type = 'houzez_agency')
            LEFT JOIN {$prefix}postmeta pm_contact ON pm_contact.post_id = p.ID
            LEFT JOIN {$prefix}posts media ON (media.ID = pm_contact.meta_value AND pm_contact.meta_key = 'fave_agent_picture' AND media.post_type = 'attachment')
            WHERE p.post_type = 'houzez_agent'
            AND a.ID = :agency_id
            AND p.post_status = 'publish'
        ";
        
        // Ajouter filtre par nom si query fournie
        if ($query && strlen($query) >= 2) {
            $sql .= " AND p.post_title LIKE :query";
        }
        
        $sql .= "
            GROUP BY u.ID, u.user_login, u.user_email, u.user_status, u.user_registered, p.ID, p.post_title, p.post_status, pm_email.meta_value, a.ID, a.post_title
            ORDER BY p.post_title ASC
            LIMIT 10
        ";
        
        $stmt = $pdo->prepare($sql);
        $params = ['agency_id' => $agency_id];
        if ($query && strlen($query) >= 2) {
            $params['query'] = '%' . $query . '%';
        }
        $stmt->execute($params);
        $agents = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $filtered_agents = [];
        foreach ($agents as $agent) {
            $contact_info = [];
            if (!empty($agent['phone'])) $contact_info[] = $agent['phone'];
            if (!empty($agent['mobile'])) $contact_info[] = $agent['mobile'];
            
            $display_name = $agent['agent_name'];
            if (!empty($agent['position'])) {
                $display_name .= " - " . $agent['position'];
            }
            if (!empty($contact_info)) {
                $display_name .= " (" . implode(' / ', array_slice($contact_info, 0, 1)) . ")";
            }
            
            $filtered_agents[] = [
                'id' => $agent['user_id'],
                'name' => $agent['agent_name'],
                'display' => $display_name,
                'email' => $agent['agent_email'],
                'phone' => $agent['phone'],
                'mobile' => $agent['mobile'],
                'position' => $agent['position'],
                'agency_name' => $agent['agency_name'],
                'whatsapp' => $agent['whatsapp'],
                'website' => $agent['website'],
                'avatar' => $agent['agent_avatar']
            ];
        }
        
        echo json_encode([
            'success' => true, 
            'agents' => $filtered_agents,
            'source' => 'Requête SQL HOUZEZ complète'
        ]);
        
    } else {
        // Test de base avec info DB
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM {$prefix}posts WHERE post_type = 'houzez_agency'");
        $agencyCount = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM {$prefix}posts WHERE post_type = 'houzez_agent'");
        $agentCount = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true,
            'message' => 'Connexion WordPress réussie',
            'database' => $dbname,
            'agencies_count' => $agencyCount['total'],
            'agents_count' => $agentCount['total'],
            'timestamp' => time(),
            'method' => $_SERVER['REQUEST_METHOD'],
            'post_data' => $_POST,
            'get_data' => $_GET
        ]);
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'file' => __FILE__,
        'line' => $e->getLine()
    ]);
}
?>
