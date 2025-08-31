<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Lead_model extends CI_Model {
    protected $table = 'crm_leads';
    /** @var CI_DB_query_builder */
    protected $wp_db; // connexion WordPress
    protected $tag_pivot = 'crm_lead_tags';
    protected $tags_table = 'crm_tags';
    protected $ptype_pivot = 'crm_lead_property_types';
    protected $ptype_table = 'crm_property_types';

    public function __construct() {
        parent::__construct();
        $this->wp_db = $this->load->database('wordpress', TRUE);
    }

    private function baseFilters(array $f) {
        if(!empty($f['type'])) $this->db->where('type',$f['type']);
        if(!empty($f['status'])) $this->db->where('status',$f['status']);
        if(!empty($f['q'])) {
            $this->db->group_start()
                ->like('nom',$f['q'])
                ->or_like('prenom',$f['q'])
                ->or_like('email',$f['q'])
                ->or_like('telephone',$f['q'])
            ->group_end();
        }
    }

    public function filter(array $f=[], $limit=50, $offset=0) {
        if(!$this->db->table_exists($this->table)) return [];
        $this->baseFilters($f);
        return $this->db->order_by('created_at','DESC')->limit($limit,$offset)->get($this->table)->result_array();
    }

    public function count(array $f=[]) {
        if(!$this->db->table_exists($this->table)) return 0;
        $this->baseFilters($f);
        return (int)$this->db->count_all_results($this->table);
    }

    public function get($id) {
        if(!$this->db->table_exists($this->table)) return null;
        $row = $this->db->where('id',(int)$id)->get($this->table)->row_array();
        if(!$row) return null;
        // Tags
        if($this->db->table_exists($this->tag_pivot)) {
            $tags = $this->db->select('t.id,t.slug,t.libelle')
                ->from($this->tag_pivot.' lt')
                ->join($this->tags_table.' t','t.id = lt.tag_id','left')
                ->where('lt.lead_id',(int)$id)->get()->result_array();
            $row['tags'] = $tags;
        }
        // Property types
        if($this->db->table_exists($this->ptype_pivot)) {
            $pt = $this->db->select('p.id,p.code,p.libelle')
                ->from($this->ptype_pivot.' lp')
                ->join($this->ptype_table.' p','p.id = lp.property_type_id','left')
                ->where('lp.lead_id',(int)$id)->get()->result_array();
            $row['property_types'] = $pt;
        }
        return $row;
    }

    public function list_for_select($type=null, $limit=200) {
        if(!$this->db->table_exists($this->table)) return [];
        if($type) $this->db->where('type',$type);
        return $this->db->select('id, prenom, nom, email, telephone, type, status')->order_by('created_at','DESC')->limit($limit)->get($this->table)->result_array();
    }

    private function wp_user_exists($wp_user_id) {
        if(!$this->wp_db) return false;
        $row = $this->wp_db->select('ID')->where('ID',(int)$wp_user_id)->get($this->wp_db->dbprefix('users'))->row_array();
        return !empty($row['ID']);
    }

    public function create(array $data) {
        if(!$this->db->table_exists($this->table)) return 0;
        if(empty($data['wp_user_id']) || !$this->wp_user_exists($data['wp_user_id'])) return 0; // invalide
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        try {
            $this->db->insert($this->table,$data);
            return (int)$this->db->insert_id();
        } catch(Exception $e) {
            return 0; // duplication ou autre
        }
    }

    public function update($id, array $data) {
        if(!$this->db->table_exists($this->table)) return false;
        if(isset($data['wp_user_id']) && !$this->wp_user_exists($data['wp_user_id'])) return false;
        $data['updated_at'] = date('Y-m-d H:i:s');
        try {
            $ok = $this->db->where('id',(int)$id)->update($this->table,$data);
            return $ok;
        } catch(Exception $e) { return false; }
    }

    /** Gestion tags: remplace entièrement l’ensemble pour un lead */
    public function set_tags($lead_id, array $tag_ids) {
        if(!$this->db->table_exists($this->tag_pivot)) return false;
        $lead_id = (int)$lead_id;
        $this->db->where('lead_id',$lead_id)->delete($this->tag_pivot);
        $insert=[];
        foreach(array_unique($tag_ids) as $tid){ $tid=(int)$tid; if($tid>0) $insert[]=['lead_id'=>$lead_id,'tag_id'=>$tid]; }
        if(!empty($insert)) $this->db->insert_batch($this->tag_pivot,$insert);
        return true;
    }

    public function set_property_types($lead_id, array $ptype_ids) {
        if(!$this->db->table_exists($this->ptype_pivot)) return false;
        $lead_id=(int)$lead_id;
        $this->db->where('lead_id',$lead_id)->delete($this->ptype_pivot);
        $insert=[]; foreach(array_unique($ptype_ids) as $pid){ $pid=(int)$pid; if($pid>0) $insert[]=['lead_id'=>$lead_id,'property_type_id'=>$pid]; }
        if(!empty($insert)) $this->db->insert_batch($this->ptype_pivot,$insert);
        return true;
    }

    public function delete($id) {
        if(!$this->db->table_exists($this->table)) return false;
        return $this->db->where('id',(int)$id)->delete($this->table);
    }
}
