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
}
