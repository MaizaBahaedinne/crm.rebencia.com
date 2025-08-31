<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Client_model extends CI_Model {
    protected $table = 'crm_clients';
    public function all($limit=100,$offset=0,$filters=[]) {
        if(!$this->db->table_exists($this->table)) return [];
        if(!empty($filters['q'])) {
            $this->db->group_start()
                ->like('nom',$filters['q'])
                ->or_like('email',$filters['q'])
                ->group_end();
        }
        if(!empty($filters['type'])) $this->db->where('type',$filters['type']);
        if(!empty($filters['statut'])) $this->db->where('statut',$filters['statut']);
        return $this->db->order_by('created_at','DESC')->limit($limit,$offset)->get($this->table)->result_array();
    }
    public function count($filters=[]) {
        if(!$this->db->table_exists($this->table)) return 0;
        if(!empty($filters['q'])) {
            $this->db->group_start()->like('nom',$filters['q'])->or_like('email',$filters['q'])->group_end();
        }
        if(!empty($filters['type'])) $this->db->where('type',$filters['type']);
        if(!empty($filters['statut'])) $this->db->where('statut',$filters['statut']);
        return (int)$this->db->count_all_results($this->table);
    }
    public function get($id){ if(!$this->db->table_exists($this->table)) return null; return $this->db->where('id',(int)$id)->get($this->table)->row_array(); }
    public function create($data){ $data['created_at']=date('Y-m-d H:i:s'); $data['updated_at']=date('Y-m-d H:i:s'); $this->db->insert($this->table,$data); return (int)$this->db->insert_id(); }
    public function update($id,$data){ $data['updated_at']=date('Y-m-d H:i:s'); return $this->db->where('id',(int)$id)->update($this->table,$data); }
    public function delete($id){ return $this->db->where('id',(int)$id)->delete($this->table); }
}
