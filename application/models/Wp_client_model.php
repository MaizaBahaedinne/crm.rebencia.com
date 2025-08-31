<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Wp_client_model extends CI_Model {
    /** @var CI_DB_mysqli_driver */
    protected $wp_db;
    protected $table;
    public function __construct(){
        parent::__construct();
        $this->wp_db = $this->load->database('wordpress', TRUE);
        $this->table = $this->wp_db->dbprefix('clients'); // wp_Hrg8P_clients
    }

    public function all($limit=200,$offset=0,$filters=[]) {
        if(!$this->wp_db) return [];
        $sql = "SELECT user_id, user_login, user_email, user_registered, statut_compte, prenom, nom, telephone, role_houzez
                FROM {$this->table} WHERE 1=1";
        $params=[];
        if(!empty($filters['q'])) { $sql .= " AND (nom LIKE ? OR prenom LIKE ? OR user_email LIKE ? OR telephone LIKE ?)"; $like='%'.$filters['q'].'%'; $params[]=$like; $params[]=$like; $params[]=$like; $params[]=$like; }
        if(!empty($filters['role'])) { $sql .= " AND role_houzez = ?"; $params[]=$filters['role']; }
        if(!empty($filters['statut'])) { $sql .= " AND statut_compte = ?"; $params[]=$filters['statut']; }
        $sql .= " ORDER BY user_registered DESC LIMIT ? OFFSET ?";
        $params[]=(int)$limit; $params[]=(int)$offset;
        return $this->wp_db->query($sql,$params)->result_array();
    }
    public function count($filters=[]) {
        if(!$this->wp_db) return 0;
        $sql = "SELECT COUNT(*) c FROM {$this->table} WHERE 1=1"; $params=[];
        if(!empty($filters['q'])) { $sql .= " AND (nom LIKE ? OR prenom LIKE ? OR user_email LIKE ? OR telephone LIKE ?)"; $like='%'.$filters['q'].'%'; $params[]=$like; $params[]=$like; $params[]=$like; $params[]=$like; }
        if(!empty($filters['role'])) { $sql .= " AND role_houzez = ?"; $params[]=$filters['role']; }
        if(!empty($filters['statut'])) { $sql .= " AND statut_compte = ?"; $params[]=$filters['statut']; }
        $row = $this->wp_db->query($sql,$params)->row_array();
        return (int)($row['c']??0);
    }
    public function get($id){
        $row = $this->wp_db->where('user_id',(int)$id)->get($this->table)->row_array();
        return $row ?: null;
    }
}
