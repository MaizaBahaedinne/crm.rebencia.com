-- ========================================
-- REQUÊTE SQL POUR VÉRIFIER LES USER IDs
-- ========================================

-- 1. Vue d'ensemble des utilisateurs et leurs profils agents
SELECT 
    u.ID as wordpress_user_id,
    u.user_login,
    u.display_name,
    a.agent_post_id,
    a.agency_id,
    a.agent_name,
    a.agent_email,
    CASE 
        WHEN a.agent_post_id IS NULL THEN 'PAS DE PROFIL AGENT'
        WHEN a.agent_post_id = u.ID THEN 'DUPLICATION DÉTECTÉE'
        ELSE 'PROFIL AGENT VALIDE'
    END as status_profil
FROM wp_Hrg8P_users u
LEFT JOIN wp_Hrg8P_crm_agents a ON u.ID = a.user_id
WHERE u.ID IN (SELECT DISTINCT user_id FROM wp_Hrg8P_usermeta WHERE meta_key LIKE '%capabilities%')
ORDER BY u.ID;

-- ========================================

-- 2. Vérification spécifique pour l'utilisateur Montasar Barkouti (ID: 7)
SELECT 
    'UTILISATEUR MONTASAR BARKOUTI' as verification,
    u.ID as wordpress_user_id,
    u.user_login,
    u.display_name,
    a.agent_post_id,
    a.agency_id,
    a.agent_name,
    CASE 
        WHEN a.agent_post_id IS NULL THEN '✅ PAS DE PROFIL AGENT (CORRECT)'
        WHEN a.agent_post_id = u.ID THEN '❌ DUPLICATION (PROBLÈME)'
        ELSE '✅ PROFIL AGENT DIFFÉRENT (CORRECT)'
    END as diagnostic
FROM wp_Hrg8P_users u
LEFT JOIN wp_Hrg8P_crm_agents a ON u.ID = a.user_id
WHERE u.ID = 7;

-- ========================================

-- 3. Compter les cas de duplication dans toute la base
SELECT 
    COUNT(*) as total_users,
    COUNT(a.agent_post_id) as users_with_agent_profile,
    COUNT(CASE WHEN a.agent_post_id = u.ID THEN 1 END) as duplications_detected,
    COUNT(CASE WHEN a.agent_post_id IS NULL THEN 1 END) as no_agent_profile
FROM wp_Hrg8P_users u
LEFT JOIN wp_Hrg8P_crm_agents a ON u.ID = a.user_id
WHERE u.ID IN (SELECT DISTINCT user_id FROM wp_Hrg8P_usermeta WHERE meta_key LIKE '%capabilities%');

-- ========================================

-- 4. Détail des agences et leurs agents
SELECT 
    a.agency_id,
    a.agency_name,
    COUNT(a.user_id) as nombre_agents,
    COUNT(a.agent_post_id) as agents_avec_profil,
    COUNT(CASE WHEN a.agent_post_id = a.user_id THEN 1 END) as duplications_dans_agence
FROM wp_Hrg8P_crm_agents a
WHERE a.agency_id IS NOT NULL
GROUP BY a.agency_id, a.agency_name
ORDER BY a.agency_id;

-- ========================================

-- 5. Vérification de l'agence Ben arous (ID: 18907)
SELECT 
    'AGENCE BEN AROUS' as verification,
    a.user_id,
    a.agent_post_id,
    a.agent_name,
    a.agent_email,
    u.user_login,
    CASE 
        WHEN a.agent_post_id IS NULL THEN 'Pas de profil agent'
        WHEN a.agent_post_id = a.user_id THEN 'Duplication détectée'
        ELSE 'Profil agent valide'
    END as status
FROM wp_Hrg8P_crm_agents a
LEFT JOIN wp_Hrg8P_users u ON a.user_id = u.ID
WHERE a.agency_id = 18907
ORDER BY a.user_id;
