-- Test de la requête proposée pour récupérer les agents avec leurs images
-- Cette requête combine _thumbnail_id (featured image) et fave_author_custom_picture

SELECT 
    a.ID as agent_id, 
    a.post_title as agent_name,
    a.post_status,
    -- Image principale : _thumbnail_id (featured image)
    m1.meta_value as thumbnail_id,
    i1.guid as thumbnail_url,
    -- Image secondaire : fave_author_custom_picture 
    m2.meta_value as custom_picture_id,
    i2.guid as custom_picture_url,
    -- Image finale avec priorité
    COALESCE(
        REPLACE(i1.guid, 'http://localhost/', 'https://rebencia.com/'),
        REPLACE(i2.guid, 'http://localhost/', 'https://rebencia.com/'),
        NULL
    ) as final_image_url
FROM wp_Hrg8P_posts a
-- Join pour _thumbnail_id (featured image)
LEFT JOIN wp_Hrg8P_postmeta m1 ON a.ID = m1.post_id AND m1.meta_key = '_thumbnail_id'
LEFT JOIN wp_Hrg8P_posts i1 ON m1.meta_value = i1.ID AND i1.post_type = 'attachment'
-- Join pour fave_author_custom_picture
LEFT JOIN wp_Hrg8P_postmeta m2 ON a.ID = m2.post_id AND m2.meta_key = 'fave_author_custom_picture'
LEFT JOIN wp_Hrg8P_posts i2 ON m2.meta_value = i2.ID AND i2.post_type = 'attachment'
WHERE a.post_type = 'houzez_agent'
AND a.post_status = 'publish'
ORDER BY a.post_title;
