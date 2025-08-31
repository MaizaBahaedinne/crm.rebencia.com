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
        $rows = $this->wp_db->query($sql,$params)->result_array();
        foreach($rows as &$r) {
            $r['full_name'] = trim(($r['prenom']??'').' '.($r['nom']??''));
            if($r['full_name'] === '') $r['full_name'] = $r['user_login'];
            $r['role_label'] = $this->formatRole($r['role_houzez'] ?? '');
            $r['statut_label'] = $this->formatStatut($r['statut_compte'] ?? '');
        }
        return $rows;
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
        if(!$row) return null;
        $row['full_name'] = trim(($row['prenom']??'').' '.($row['nom']??''));
        if($row['full_name']==='') $row['full_name']=$row['user_login'];
        $row['role_label'] = $this->formatRole($row['role_houzez'] ?? '');
        $row['statut_label'] = $this->formatStatut($row['statut_compte'] ?? '');
        return $row;
    }
    private function formatRole($raw) {
        if(!$raw) return '—';
        $labelsMap = [
            'houzez_owner'=>'Propriétaire',
            'houzez_buyer'=>'Acheteur',
            'houzez_seller'=>'Vendeur',
            'houzez_agent'=>'Agent',
            'houzez_agency'=>'Agence'
        ];
        $roles = [];
        // Si sérialisé PHP
        if(is_string($raw) && strpos($raw,'a:')===0) {
            $arr = @unserialize($raw);
            if(is_array($arr)) {
                foreach($arr as $k=>$v){ if($v && isset($labelsMap[$k])) $roles[]=$labelsMap[$k]; }
            }
        } elseif(is_string($raw) && isset($labelsMap[$raw])) {
            $roles[] = $labelsMap[$raw];
        } else {
            // tenter JSON
            $json = json_decode($raw,true);
            if(is_array($json)) {
                foreach($json as $k=>$v){ if($v && isset($labelsMap[$k])) $roles[]=$labelsMap[$k]; }
            }
        }
        if(empty($roles)) return '—';
        return implode(', ',$roles);
    }
    private function formatStatut($v) {
        if($v==='' || $v===null) return '—';
        if(is_numeric($v)) return ((int)$v===1)?'actif':'inactif';
        return $v;
    }
}
