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
    
    // Récupérer les informations de session utilisateur
    session_start();
    $user_role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
    $user_id = isset($_SESSION['wp_id']) ? $_SESSION['wp_id'] : null;
    $is_admin = isset($_SESSION['isAdmin']) ? $_SESSION['isAdmin'] : false;
    
    $action = isset($_GET['action']) ? $_GET['action'] : 'test';
    $query = isset($_POST['query']) ? $_POST['query'] : (isset($_GET['query']) ? $_GET['query'] : '');
    
    if ($action === 'user_context') {
        // Endpoint pour récupérer le contexte utilisateur
        if (!$user_id) {
            echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté']);
            exit;
        }
        
        // Récupérer les informations complètes de l'utilisateur
        $stmt = $pdo->prepare("
            SELECT 
                u.ID as user_id,
                u.user_login,
                u.user_email,
                u.display_name,
                p.ID as agent_post_id,
                p.post_title as agent_name,
                a.ID as agency_id,
                a.post_title as agency_name,
                MAX(CASE WHEN pm_contact.meta_key = 'fave_agent_position' THEN pm_contact.meta_value END) AS position
            FROM {$prefix}users u
            LEFT JOIN {$prefix}postmeta pm_email ON pm_email.meta_value = u.user_email
            LEFT JOIN {$prefix}posts p ON (p.ID = pm_email.post_id AND p.post_type = 'houzez_agent')
            LEFT JOIN {$prefix}postmeta pm_agency ON (pm_agency.post_id = p.ID AND pm_agency.meta_key = 'fave_agent_agencies')
            LEFT JOIN {$prefix}posts a ON (a.ID = pm_agency.meta_value AND a.post_type = 'houzez_agency')
            LEFT JOIN {$prefix}postmeta pm_contact ON pm_contact.post_id = p.ID
            WHERE u.ID = :user_id
            GROUP BY u.ID, p.ID, a.ID
        ");
        $stmt->execute(['user_id' => $user_id]);
        $user_info = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $context = [
            'user_id' => $user_id,
            'role' => $user_role,
            'is_admin' => $is_admin,
            'agent_info' => $user_info
        ];
        
        // Déterminer les permissions selon le rôle
        if ($is_admin) {
            $context['permissions'] = [
                'can_choose_agency' => true,
                'can_choose_agent' => true,
                'auto_fill_agency' => false,
                'auto_fill_agent' => false,
                'description' => 'Admin - Accès complet à toutes les agences et agents'
            ];
        } elseif ($user_role === 'manager' && $user_info && $user_info['agency_id']) {
            $context['permissions'] = [
                'can_choose_agency' => false,
                'can_choose_agent' => true,
                'auto_fill_agency' => true,
                'auto_fill_agent' => false,
                'agency_id' => $user_info['agency_id'],
                'agency_name' => $user_info['agency_name'],
                'description' => 'Manager - Limité à son agence'
            ];
        } elseif ($user_role === 'agent' && $user_info) {
            $context['permissions'] = [
                'can_choose_agency' => false,
                'can_choose_agent' => false,
                'auto_fill_agency' => true,
                'auto_fill_agent' => true,
                'agency_id' => $user_info['agency_id'],
                'agency_name' => $user_info['agency_name'],
                'agent_id' => $user_info['user_id'],
                'agent_name' => $user_info['agent_name'],
                'description' => 'Agent - Remplissage automatique'
            ];
        } else {
            $context['permissions'] = [
                'can_choose_agency' => true,
                'can_choose_agent' => true,
                'auto_fill_agency' => false,
                'auto_fill_agent' => false,
                'description' => 'Utilisateur standard'
            ];
        }
        
        echo json_encode(['success' => true, 'context' => $context]);
        
    } elseif ($action === 'agencies') {
        // Autocomplétion des agences avec contrôle d'accès
        
        // Vérifier les permissions
        session_start();
        $user_role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
        $user_id = isset($_SESSION['wp_id']) ? $_SESSION['wp_id'] : null;
        $is_admin = isset($_SESSION['isAdmin']) ? $_SESSION['isAdmin'] : false;
        
        // Si manager ou agent, limiter aux agences autorisées
        if (!$is_admin && ($user_role === 'manager' || $user_role === 'agent')) {
            // Récupérer l'agence de l'utilisateur
            $stmt = $pdo->prepare("
                SELECT a.ID as agency_id, a.post_title as agency_name
                FROM {$prefix}users u
                LEFT JOIN {$prefix}postmeta pm_email ON pm_email.meta_value = u.user_email
                LEFT JOIN {$prefix}posts p ON (p.ID = pm_email.post_id AND p.post_type = 'houzez_agent')
                LEFT JOIN {$prefix}postmeta pm_agency ON (pm_agency.post_id = p.ID AND pm_agency.meta_key = 'fave_agent_agencies')
                LEFT JOIN {$prefix}posts a ON (a.ID = pm_agency.meta_value AND a.post_type = 'houzez_agency')
                WHERE u.ID = :user_id AND a.ID IS NOT NULL
            ");
            $stmt->execute(['user_id' => $user_id]);
            $user_agency = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user_agency) {
                echo json_encode([
                    'success' => true,
                    'agencies' => [[
                        'id' => $user_agency['agency_id'],
                        'name' => $user_agency['agency_name'],
                        'agent_count' => 1,
                        'display' => $user_agency['agency_name'] . ' (Votre agence)'
                    ]],
                    'count' => 1,
                    'restricted' => true,
                    'role' => $user_role
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Agence utilisateur non trouvée']);
            }
            exit;
        }
        
        // Admin - accès complet
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
        // Agents par agence avec contrôle d'accès par rôle
        $agency_id = isset($_POST['agency_id']) ? $_POST['agency_id'] : (isset($_GET['agency_id']) ? $_GET['agency_id'] : '');
        
        session_start();
        $user_role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
        $user_id = isset($_SESSION['wp_id']) ? $_SESSION['wp_id'] : null;
        $is_admin = isset($_SESSION['isAdmin']) ? $_SESSION['isAdmin'] : false;
        
        if (!$agency_id) {
            echo json_encode(['success' => false, 'message' => 'ID agence requis']);
            exit;
        }
        
        // Construire la requête selon le rôle
        $sql = "
            SELECT 
                u.ID AS user_id,
                u.user_login AS user_login,
                u.user_email AS user_email,
                p.ID AS agent_post_id,
                p.post_title AS agent_name,
                pm_email.meta_value AS agent_email,
                a.ID AS agency_id,
                a.post_title AS agency_name,
                MAX(CASE WHEN pm_contact.meta_key = 'fave_agent_phone' THEN pm_contact.meta_value END) AS phone,
                MAX(CASE WHEN pm_contact.meta_key = 'fave_agent_mobile' THEN pm_contact.meta_value END) AS mobile,
                MAX(CASE WHEN pm_contact.meta_key = 'fave_agent_position' THEN pm_contact.meta_value END) AS position
            FROM {$prefix}users u
            LEFT JOIN {$prefix}postmeta pm_email ON pm_email.meta_value = u.user_email
            LEFT JOIN {$prefix}posts p ON (p.ID = pm_email.post_id AND p.post_type = 'houzez_agent')
            LEFT JOIN {$prefix}postmeta pm_agency ON (pm_agency.post_id = p.ID AND pm_agency.meta_key = 'fave_agent_agencies')
            LEFT JOIN {$prefix}posts a ON (a.ID = pm_agency.meta_value AND a.post_type = 'houzez_agency')
            LEFT JOIN {$prefix}postmeta pm_contact ON pm_contact.post_id = p.ID
            WHERE p.post_type = 'houzez_agent'
            AND a.ID = :agency_id
            AND p.post_status = 'publish'
        ";
        
        $params = ['agency_id' => $agency_id];
        
        // Si manager, inclure lui-même + ses agents
        if ($user_role === 'manager' && !$is_admin) {
            // Ajouter le manager lui-même dans les résultats si il fait partie de cette agence
            $sql .= " AND (u.ID = :user_id OR u.ID != :user_id)";
            $params['user_id'] = $user_id;
        }
        
        // Si agent, seul lui-même
        if ($user_role === 'agent' && !$is_admin) {
            $sql .= " AND u.ID = :user_id";
            $params['user_id'] = $user_id;
        }
        
        // Ajouter filtre par nom si query fournie
        if ($query && strlen($query) >= 2) {
            $sql .= " AND p.post_title LIKE :query";
            $params['query'] = '%' . $query . '%';
        }
        
        $sql .= "
            GROUP BY u.ID, u.user_login, u.user_email, p.ID, p.post_title, pm_email.meta_value, a.ID, a.post_title
            ORDER BY 
                CASE WHEN u.ID = :current_user_id THEN 0 ELSE 1 END,
                p.post_title ASC
            LIMIT 10
        ";
        
        $params['current_user_id'] = $user_id;
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $agents = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $filtered_agents = [];
        foreach ($agents as $agent) {
            $contact_info = [];
            if (!empty($agent['phone'])) $contact_info[] = $agent['phone'];
            if (!empty($agent['mobile'])) $contact_info[] = $agent['mobile'];
            
            $display_name = $agent['agent_name'];
            
            // Marquer l'utilisateur connecté
            if ($agent['user_id'] == $user_id) {
                $display_name .= " (Vous)";
            }
            
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
                'is_current_user' => ($agent['user_id'] == $user_id)
            ];
        }
        
        echo json_encode([
            'success' => true, 
            'agents' => $filtered_agents,
            'role' => $user_role,
            'is_admin' => $is_admin,
            'restricted' => !$is_admin,
            'source' => 'Requête SQL HOUZEZ avec contrôle rôle'
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
