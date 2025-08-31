<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction_model extends CI_Model {
    protected $table = 'crm_transactions'; // adapter si autre nom

    public function __construct() {
        parent::__construct();
    }

    public function recent($limit = 5) {
        if(!$this->db->table_exists($this->table)) return [];
        return $this->db->order_by('date_cloture','DESC')->limit($limit)->get($this->table)->result_array();
    }

    public function list($type = null, $limit = 100, $offset = 0) {
        if(!$this->db->table_exists($this->table)) return [];
        if($type) $this->db->where('type', $type); // type: vente|location
        return $this->db->order_by('date_cloture','DESC')->limit($limit,$offset)->get($this->table)->result_array();
    }

    public function count($type = null) {
        if(!$this->db->table_exists($this->table)) return 0;
        if($type) $this->db->where('type',$type);
        return (int)$this->db->count_all_results($this->table);
    }

    public function get($id) {
        if(!$this->db->table_exists($this->table)) return null;
        return $this->db->where('id',(int)$id)->get($this->table)->row_array();
    }

    public function create(array $data) {
        if(!$this->db->table_exists($this->table)) return 0;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table,$data);
        return (int)$this->db->insert_id();
    }

    public function update($id, array $data) {
        if(!$this->db->table_exists($this->table)) return false;
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->where('id',(int)$id)->update($this->table,$data);
    }

    public function delete($id) {
        if(!$this->db->table_exists($this->table)) return false;
        return $this->db->where('id',(int)$id)->delete($this->table);
    }

    public function filter(array $f = [], $limit=50, $offset=0) {
        if(!$this->db->table_exists($this->table)) return [];
        if(!empty($f['type'])) $this->db->where('type',$f['type']);
        if(!empty($f['statut'])) $this->db->where('statut',$f['statut']);
        if(!empty($f['q'])) $this->db->like('titre',$f['q']);
        if(!empty($f['date_min'])) $this->db->where('date_cloture >=',$f['date_min']);
        if(!empty($f['date_max'])) $this->db->where('date_cloture <=',$f['date_max']);
        return $this->db->order_by('created_at','DESC')->limit($limit,$offset)->get($this->table)->result_array();
    }
}
