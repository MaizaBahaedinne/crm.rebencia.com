<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Wp_client_model extends CI_Model {
    /** @var CI_DB_mysqli_driver */
    protected $wp_db;
    public function __construct(){
        parent::__construct();
        $this->wp_db = $this->load->database('wordpress', TRUE);
    }

    public function all($limit=200,$offset=0,$filters=[]) {
        if(!$this->wp_db) return [];
        $users = $this->wp_db->dbprefix('users');
        $umeta = $this->wp_db->dbprefix('usermeta');
        $sql = "SELECT u.ID, u.display_name AS nom, u.user_email AS email,
                MAX(CASE WHEN m.meta_key='fave_author_phone' THEN m.meta_value END) AS telephone,
                MAX(CASE WHEN m.meta_key='fave_author_type' THEN m.meta_value END) AS type
                FROM {$users} u
                LEFT JOIN {$umeta} m ON m.user_id = u.ID
                WHERE 1=1";
        $conds = [];$params=[];
        if(!empty($filters['q'])) { $sql .= " AND (u.display_name LIKE ? OR u.user_email LIKE ?)"; $params[]='%'.$filters['q'].'%'; $params[]='%'.$filters['q'].'%'; }
        if(!empty($filters['type'])) { $sql .= " AND EXISTS (SELECT 1 FROM {$umeta} mt WHERE mt.user_id=u.ID AND mt.meta_key='fave_author_type' AND mt.meta_value=?)"; $params[]=$filters['type']; }
        $sql .= " GROUP BY u.ID ORDER BY u.user_registered DESC LIMIT ? OFFSET ?";
        $params[] = (int)$limit; $params[]=(int)$offset;
        return $this->wp_db->query($sql,$params)->result_array();
    }

    public function count($filters=[]) {
        if(!$this->wp_db) return 0;
        $users = $this->wp_db->dbprefix('users');
        $umeta = $this->wp_db->dbprefix('usermeta');
        $sql = "SELECT COUNT(DISTINCT u.ID) c FROM {$users} u LEFT JOIN {$umeta} m ON m.user_id=u.ID WHERE 1=1";
        $params=[];
        if(!empty($filters['q'])) { $sql .= " AND (u.display_name LIKE ? OR u.user_email LIKE ?)"; $params[]='%'.$filters['q'].'%'; $params[]='%'.$filters['q'].'%'; }
        if(!empty($filters['type'])) { $sql .= " AND EXISTS (SELECT 1 FROM {$umeta} mt WHERE mt.user_id=u.ID AND mt.meta_key='fave_author_type' AND mt.meta_value=?)"; $params[]=$filters['type']; }
        $row = $this->wp_db->query($sql,$params)->row_array();
        return (int)($row['c']??0);
    }

    public function get($id){
        $res = $this->all(1,0,['q'=>null]); // fallback simple
        foreach($res as $r){ if((int)$r['ID']===(int)$id) return $r; }
        return null;
    }
}
