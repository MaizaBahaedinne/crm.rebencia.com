<?php
// Debug temporaire pour l'agent Montasar Barkouti
require_once 'index.php';

// Simuler une requête pour débugger
$_GET['debug'] = '1';
$CI =& get_instance();
$CI->load->model('agent_model');

echo "<h2>Debug Agent ID 7 (Montasar Barkouti)</h2>";

$agent = $CI->agent_model->get_agent_by_user_id(7);
if ($agent) {
    echo "<h3>Données agent récupérées :</h3>";
    echo "<pre>";
    print_r($agent);
    echo "</pre>";
    
    echo "<h3>Données problématiques identifiées :</h3>";
    echo "<ul>";
    echo "<li><strong>Mobile :</strong> " . ($agent->mobile ?? 'N/A') . " - " . (($agent->mobile == '321 456 9874') ? '<span style="color:red">FAUSSE DONNÉE</span>' : '<span style="color:green">OK</span>') . "</li>";
    echo "<li><strong>WhatsApp :</strong> " . ($agent->whatsapp ?? 'N/A') . " - " . (($agent->whatsapp == '321 456 9874') ? '<span style="color:red">FAUSSE DONNÉE</span>' : '<span style="color:green">OK</span>') . "</li>";
    echo "<li><strong>Site Web :</strong> " . ($agent->website ?? 'N/A') . " - " . (strpos($agent->website ?? '', 'rebenecia.com') ? '<span style="color:red">FAUTE DE FRAPPE (rebencia -> rebenecia)</span>' : '<span style="color:green">OK</span>') . "</li>";
    echo "<li><strong>Contacts :</strong> " . ($agent->contacts_count ?? 'N/A') . " - " . (($agent->contacts_count == 14) ? '<span style="color:orange">À vérifier si réel</span>' : '<span style="color:green">OK</span>') . "</li>";
    echo "</ul>";
    
    // Vérifier les stats complètes
    $complete_stats = $CI->agent_model->get_agent_complete_stats($agent->agent_id);
    echo "<h3>Statistiques complètes :</h3>";
    echo "<pre>";
    print_r($complete_stats);
    echo "</pre>";
} else {
    echo "<p>Aucun agent trouvé pour l'ID 7</p>";
}
?>
