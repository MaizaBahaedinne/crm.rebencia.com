<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Wp_property_model extends CI_Model {
    /** @var CI_DB_mysqli_driver */
    protected $wp_db;

    public function __construct() {
        parent::__construct();
        // Charge la connexion wordpress définie dans database.php
        $this->wp_db = $this->load->database('wordpress', TRUE);
    }

    public function list_simple($limit = 100) { return $this->list_with_meta($limit); }

    public function list_with_meta($limit = 100, $type = null) {
        if(!$this->wp_db) return [];
        $posts = $this->wp_db->dbprefix('posts');
        $meta  = $this->wp_db->dbprefix('postmeta');
        $filterSql = '';
        $params = [];
        if($type){
            // On mappe vente/location vers meta clé 'fave_property_status' si existant
            $filterSql = " AND p.ID IN (SELECT post_id FROM {$meta} WHERE meta_key='fave_property_status' AND meta_value LIKE ?)";
            $params[] = '%'.($type=='vente'?'sale':'rent').'%';
        }
        $params[] = (int)$limit;
        $sql = "SELECT p.ID, p.post_title,
                MAX(CASE WHEN m.meta_key='fave_property_price' THEN m.meta_value END) AS price,
                MAX(CASE WHEN m.meta_key='fave_property_status' THEN m.meta_value END) AS wp_status
                FROM {$posts} p
                LEFT JOIN {$meta} m ON m.post_id=p.ID AND m.meta_key IN ('fave_property_price','fave_property_status')
                WHERE p.post_type='property' AND p.post_status IN ('publish','pending') {$filterSql}
                GROUP BY p.ID
                ORDER BY p.post_date DESC
                LIMIT ?";
        return $this->wp_db->query($sql, $params)->result_array();
    }

    public function search($q, $limit=50) {
        if(!$this->wp_db) return [];
    $posts = $this->wp_db->dbprefix('posts');
    $meta  = $this->wp_db->dbprefix('postmeta');
    $sql = "SELECT p.ID, p.post_title,
        MAX(CASE WHEN m.meta_key='fave_property_price' THEN m.meta_value END) AS price,
        MAX(CASE WHEN m.meta_key='fave_property_status' THEN m.meta_value END) AS wp_status
        FROM {$posts} p
        LEFT JOIN {$meta} m ON m.post_id=p.ID AND m.meta_key IN ('fave_property_price','fave_property_status')
        WHERE p.post_type='property' AND p.post_title LIKE ?
        GROUP BY p.ID
        ORDER BY p.post_date DESC
        LIMIT ?";
    return $this->wp_db->query($sql, ['%'.$q.'%', (int)$limit])->result_array();
    }
}
