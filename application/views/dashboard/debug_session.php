<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Session - CRM Rebencia</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 0; 
            padding: 20px; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .container { 
            max-width: 1200px; 
            margin: 0 auto; 
            background: white; 
            padding: 30px; 
            border-radius: 15px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        h1 { 
            color: #2c3e50; 
            text-align: center; 
            margin-bottom: 30px;
            font-size: 2.5em;
        }
        h2 { 
            color: #34495e; 
            background: linear-gradient(135deg, #ecf0f1, #bdc3c7); 
            padding: 15px; 
            border-left: 5px solid #3498db; 
            border-radius: 5px;
            margin: 25px 0 15px 0;
        }
        h3 {
            color: #2c3e50;
            border-bottom: 2px solid #ecf0f1;
            padding-bottom: 10px;
        }
        .info-box { 
            background: #f8f9fa; 
            border: 1px solid #dee2e6; 
            padding: 20px; 
            margin: 15px 0; 
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .success-box {
            background: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }
        .error-box {
            background: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
        .warning-box {
            background: #fff3cd;
            border-color: #ffeaa7;
            color: #856404;
        }
        pre { 
            background: #2c3e50; 
            color: #ecf0f1; 
            padding: 15px; 
            border-radius: 8px; 
            overflow-x: auto;
            font-size: 14px;
            line-height: 1.4;
        }
        .property { 
            margin: 8px 0; 
            padding: 10px; 
            background: #e8f4fd; 
            border-left: 4px solid #007bff; 
            border-radius: 4px;
        }
        .value { 
            font-weight: bold; 
            color: #007bff; 
        }
        .null { 
            color: #dc3545; 
            font-style: italic; 
        }
        .empty { 
            color: #ffc107; 
            font-style: italic; 
        }
        .datetime {
            text-align: center;
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th, td {
            border: 1px solid #dee2e6;
            padding: 8px 12px;
            text-align: left;
        }
        th {
            background: #f8f9fa;
            font-weight: bold;
        }
        .status-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 8px;
        }
        .status-success { background: #28a745; }
        .status-error { background: #dc3545; }
        .status-warning { background: #ffc107; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Debug Session - CRM Rebencia</h1>
        <div class="datetime">
            <strong>Date/Heure:</strong> <?= date('Y-m-d H:i:s') ?>
        </div>

        <!-- 1. Données de Session CodeIgniter -->
        <h2>📊 Données de Session CodeIgniter</h2>
        <div class="info-box">
            <?php if (!empty($debug_data['session_data'])): ?>
                <?php foreach ($debug_data['session_data'] as $key => $value): ?>
                    <div class="property">
                        <strong><?= htmlspecialchars($key) ?>:</strong> 
                        <?php if (is_null($value)): ?>
                            <span class="null">NULL</span>
                        <?php elseif (is_array($value) || is_object($value)): ?>
                            <pre><?= print_r($value, true) ?></pre>
                        <?php elseif (empty($value) && $value !== '0'): ?>
                            <span class="empty">EMPTY</span>
                        <?php else: ?>
                            <span class="value"><?= htmlspecialchars($value) ?></span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucune donnée de session trouvée</p>
            <?php endif; ?>
        </div>

        <!-- 2. Propriétés BaseController -->
        <h2>🏗️ Propriétés BaseController</h2>
        <div class="info-box">
            <?php foreach ($debug_data['base_controller'] as $prop => $value): ?>
                <div class="property">
                    <strong><?= $prop ?>:</strong> 
                    <?php if (is_null($value)): ?>
                        <span class="null">NULL</span>
                    <?php elseif (empty($value) && $value !== '0'): ?>
                        <span class="empty">EMPTY</span>
                    <?php else: ?>
                        <span class="value"><?= htmlspecialchars($value) ?></span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- 3. Variable Global -->
        <h2>🌐 Variable Global</h2>
        <div class="info-box">
            <?php if (!empty($debug_data['global_var'])): ?>
                <?php foreach ($debug_data['global_var'] as $key => $value): ?>
                    <div class="property">
                        <strong><?= htmlspecialchars($key) ?>:</strong> 
                        <?php if (is_null($value)): ?>
                            <span class="null">NULL</span>
                        <?php elseif (is_array($value) || is_object($value)): ?>
                            <pre><?= print_r($value, true) ?></pre>
                        <?php elseif (empty($value) && $value !== '0'): ?>
                            <span class="empty">EMPTY</span>
                        <?php else: ?>
                            <span class="value"><?= htmlspecialchars($value) ?></span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Variable global vide</p>
            <?php endif; ?>
        </div>

        <!-- 4. Informations Utilisateur WordPress -->
        <h2>👤 Informations Utilisateur WordPress</h2>
        <div class="info-box">
            <?php if (!empty($debug_data['wp_user_info'])): ?>
                <?php if (isset($debug_data['wp_user_info']['error'])): ?>
                    <div class="error-box">
                        <strong>Erreur:</strong> <?= htmlspecialchars($debug_data['wp_user_info']['error']) ?>
                    </div>
                <?php else: ?>
                    <?php if (isset($debug_data['wp_user_info']['user'])): ?>
                        <h3>Données utilisateur:</h3>
                        <pre><?= print_r($debug_data['wp_user_info']['user'], true) ?></pre>
                    <?php endif; ?>
                    
                    <?php if (isset($debug_data['wp_user_info']['meta'])): ?>
                        <h3>Métadonnées utilisateur:</h3>
                        <?php foreach ($debug_data['wp_user_info']['meta'] as $meta): ?>
                            <div class="property">
                                <strong><?= htmlspecialchars($meta->meta_key) ?>:</strong> 
                                <span class="value"><?= htmlspecialchars($meta->meta_value) ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endif; ?>
            <?php else: ?>
                <p>Aucun wp_id en session</p>
            <?php endif; ?>
        </div>

        <!-- 5. Test Agency ID -->
        <h2>🔍 Test Récupération Agency ID depuis wp_Hrg8P_crm_agents</h2>
        <div class="info-box">
            <div class="property">
                <strong>agencyId (BaseController):</strong> 
                <span class="value"><?= $debug_data['agency_test']['current_agency_id'] ?: 'EMPTY' ?></span>
                <?php if ($debug_data['agency_test']['current_agency_id']): ?>
                    <span class="status-indicator status-success"></span>
                <?php else: ?>
                    <span class="status-indicator status-error"></span>
                <?php endif; ?>
            </div>
            
            <div class="property">
                <strong>agency_id (Session):</strong> 
                <span class="value"><?= $debug_data['agency_test']['session_agency_id'] ?: 'EMPTY' ?></span>
            </div>
            
            <div class="property">
                <strong>user_post_id (Session):</strong> 
                <span class="value"><?= $debug_data['agency_test']['user_post_id'] ?: 'EMPTY' ?></span>
            </div>

            <?php if (isset($debug_data['agency_test']['view_exists'])): ?>
                <div class="property">
                    <strong>Vue wp_Hrg8P_crm_agents existe:</strong> 
                    <span class="value"><?= $debug_data['agency_test']['view_exists'] ? 'OUI' : 'NON' ?></span>
                    <?php if ($debug_data['agency_test']['view_exists']): ?>
                        <span class="status-indicator status-success"></span>
                    <?php else: ?>
                        <span class="status-indicator status-error"></span>
                    <?php endif; ?>
                </div>

                <?php if (isset($debug_data['agency_test']['agent_data'])): ?>
                    <div class="success-box">
                        <h3>✅ Agent trouvé par agent_post_id</h3>
                        <strong>Agency ID:</strong> <?= $debug_data['agency_test']['agent_data']->agency_id ?: 'NULL' ?><br>
                        <details>
                            <summary>Données complètes</summary>
                            <pre><?= print_r($debug_data['agency_test']['agent_data'], true) ?></pre>
                        </details>
                    </div>
                <?php elseif (isset($debug_data['agency_test']['agent_data_via_user_id'])): ?>
                    <div class="success-box">
                        <h3>✅ Agent trouvé par user_id</h3>
                        <strong>Agency ID:</strong> <?= $debug_data['agency_test']['agent_data_via_user_id']->agency_id ?: 'NULL' ?><br>
                        <details>
                            <summary>Données complètes</summary>
                            <pre><?= print_r($debug_data['agency_test']['agent_data_via_user_id'], true) ?></pre>
                        </details>
                    </div>
                <?php elseif (isset($debug_data['agency_test']['sample_data'])): ?>
                    <div class="warning-box">
                        <h3>⚠️ Aucun agent trouvé - Échantillon des données</h3>
                        <?php foreach ($debug_data['agency_test']['sample_data'] as $sample): ?>
                            <pre><?= print_r($sample, true) ?></pre>
                            <hr>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <?php if (isset($debug_data['agency_test']['error'])): ?>
                <div class="error-box">
                    <strong>Erreur:</strong> <?= htmlspecialchars($debug_data['agency_test']['error']) ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- 6. Variables Serveur -->
        <h2>🔧 Variables Serveur</h2>
        <div class="info-box">
            <?php if (!empty($debug_data['server_vars'])): ?>
                <?php foreach ($debug_data['server_vars'] as $var => $value): ?>
                    <div class="property">
                        <strong><?= $var ?>:</strong> 
                        <span class="value"><?= htmlspecialchars($value) ?></span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucune variable serveur disponible</p>
            <?php endif; ?>
        </div>

        <!-- Résumé Final -->
        <h2>🎯 Résumé</h2>
        <div class="info-box">
            <?php 
                $agency_id_final = $debug_data['agency_test']['current_agency_id'] ?: $debug_data['agency_test']['session_agency_id'] ?: 1;
                $status = $debug_data['agency_test']['current_agency_id'] ? 'success' : 'warning';
            ?>
            <div class="property">
                <strong>Agency ID final utilisé:</strong> 
                <span class="value"><?= $agency_id_final ?></span>
                <span class="status-indicator status-<?= $status ?>"></span>
            </div>
            
            <div class="<?= $status === 'success' ? 'success-box' : 'warning-box' ?>">
                <strong>Statut:</strong> 
                <?php if ($status === 'success'): ?>
                    ✅ Agency ID récupéré avec succès depuis wp_Hrg8P_crm_agents!
                <?php else: ?>
                    ⚠️ Agency ID utilise une valeur par défaut. Vérifier les données dans wp_Hrg8P_crm_agents.
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
