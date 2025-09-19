<?php
/**
 * INDEX DOCUMENTATION BASES DE DONNÉES
 * Page d'accueil pour accéder aux structures des deux bases
 */
?>
<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Documentation Bases de Données - Rebencia CRM</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            padding: 20px; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .container { 
            max-width: 800px; 
            margin: 0 auto; 
            background: white; 
            padding: 30px; 
            border-radius: 15px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.2); 
        }
        h1 { 
            color: #2c3e50; 
            text-align: center; 
            margin-bottom: 10px;
            font-size: 2.5em;
        }
        .subtitle {
            text-align: center;
            color: #7f8c8d;
            margin-bottom: 40px;
            font-size: 1.1em;
        }
        .databases-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin: 40px 0;
        }
        .db-card {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 2px solid transparent;
        }
        .db-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        .db-card.crm {
            border-color: #3498db;
        }
        .db-card.wordpress {
            border-color: #9b59b6;
        }
        .db-icon {
            font-size: 3em;
            margin-bottom: 15px;
        }
        .db-title {
            font-size: 1.3em;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .db-description {
            color: #7f8c8d;
            margin-bottom: 20px;
            line-height: 1.5;
        }
        .db-button {
            display: inline-block;
            padding: 12px 25px;
            background: #34495e;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            transition: background 0.3s ease;
            font-weight: 500;
        }
        .db-button:hover {
            background: #2c3e50;
        }
        .db-button.crm {
            background: #3498db;
        }
        .db-button.crm:hover {
            background: #2980b9;
        }
        .db-button.wordpress {
            background: #9b59b6;
        }
        .db-button.wordpress:hover {
            background: #8e44ad;
        }
        .info-section {
            background: #e8f4fd;
            padding: 20px;
            border-radius: 10px;
            margin: 30px 0;
            border-left: 5px solid #3498db;
        }
        .quick-links {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        .quick-link {
            padding: 8px 15px;
            background: #ecf0f1;
            color: #2c3e50;
            text-decoration: none;
            border-radius: 20px;
            font-size: 0.9em;
            transition: all 0.3s ease;
        }
        .quick-link:hover {
            background: #bdc3c7;
            transform: translateY(-2px);
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }
        .stat-item {
            background: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .stat-number {
            font-size: 1.5em;
            font-weight: bold;
            color: #3498db;
        }
        .stat-label {
            color: #7f8c8d;
            font-size: 0.9em;
        }
        @media (max-width: 768px) {
            .databases-grid {
                grid-template-columns: 1fr;
            }
            .quick-links {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>📊 Documentation Bases de Données</h1>
        <p class='subtitle'>Rebencia CRM - Structures et Relations</p>
        
        <div class='info-section'>
            <h3>🎯 Objectif de cette Documentation</h3>
            <p>Cette documentation vivante vous permet d'explorer en temps réel la structure des deux bases de données du projet Rebencia CRM. Elle affiche les tables, colonnes, types de données, relations et échantillons pour faciliter le développement.</p>
        </div>
        
        <div class='databases-grid'>
            <!-- Base CRM -->
            <div class='db-card crm'>
                <div class='db-icon'>🏢</div>
                <div class='db-title'>rebencia_rebencia</div>
                <div class='db-description'>
                    Base de données principale du CRM contenant les propriétés, estimations, zones, et toute la logique métier.
                </div>
                <a href='db_structure_crm.php' class='db-button crm'>Explorer la Base CRM</a>
            </div>
            
            <!-- Base WordPress -->
            <div class='db-card wordpress'>
                <div class='db-icon'>🔌</div>
                <div class='db-title'>rebencia_RebenciaBD</div>
                <div class='db-description'>
                    Base de données WordPress contenant les utilisateurs, agents, agences et toute la gestion des rôles.
                </div>
                <a href='db_structure_wordpress.php' class='db-button wordpress'>Explorer la Base WordPress</a>
            </div>
        </div>
        
        <div class='info-section'>
            <h3>🔗 Relations Inter-Bases</h3>
            <div class='stats'>
                <div class='stat-item'>
                    <div class='stat-number'>2</div>
                    <div class='stat-label'>Bases de Données</div>
                </div>
                <div class='stat-item'>
                    <div class='stat-number'>Cross-DB</div>
                    <div class='stat-label'>Jointures</div>
                </div>
                <div class='stat-item'>
                    <div class='stat-number'>Live</div>
                    <div class='stat-label'>Documentation</div>
                </div>
            </div>
            
            <p><strong>Relations importantes identifiées :</strong></p>
            <ul>
                <li><code>crm_properties.agent_id</code> ↔ <code>wp_Hrg8P_crm_agents.agent_post_id</code></li>
                <li><code>wp_Hrg8P_crm_agents.user_id</code> ↔ <code>wp_Hrg8P_users.ID</code></li>
                <li><code>wp_Hrg8P_crm_agents.agency_id</code> ↔ <code>wp_Hrg8P_posts.ID</code></li>
            </ul>
        </div>
        
        <div class='quick-links'>
            <a href='/estimations' class='quick-link'>🏠 Retour CRM</a>
            <a href='/dashboard' class='quick-link'>📊 Dashboard</a>
            <a href='db_structure_crm.php#crm_properties' class='quick-link'>🏘️ Table Propriétés</a>
            <a href='db_structure_wordpress.php#wp_Hrg8P_crm_agents' class='quick-link'>👥 Vue Agents</a>
            <a href='https://github.com/MaizaBahaedinne/crm.rebencia.com' class='quick-link' target='_blank'>📂 Repo GitHub</a>
        </div>
        
        <div style='text-align: center; margin-top: 40px; color: #7f8c8d;'>
            <p>📅 Dernière mise à jour : <?= date('d/m/Y H:i:s') ?></p>
            <p>🔧 Généré automatiquement pour faciliter le développement</p>
        </div>
    </div>
</body>
</html>
