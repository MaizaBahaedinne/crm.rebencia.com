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
    if(!empty($f['property_id'])) $this->db->where('property_id',(int)$f['property_id']);
        if(!empty($f['date_min'])) $this->db->where('date_cloture >=',$f['date_min']);
        if(!empty($f['date_max'])) $this->db->where('date_cloture <=',$f['date_max']);
        return $this->db->order_by('created_at','DESC')->limit($limit,$offset)->get($this->table)->result_array();
    }

    /**
     * Liste paginée + compte total pour dashboard admin
     */
    public function list_admin(array $f = [], $page=1, $perPage=10) {
        if(!$this->db->table_exists($this->table)) return ['rows'=>[], 'total'=>0, 'aggregats'=>[]];
        $offset = ($page-1)*$perPage;
        // Base query clone pour total
        $qb = clone $this->db;
        if(!empty($f['type'])) $qb->where('type',$f['type']);
        if(!empty($f['statut'])) $qb->where('statut',$f['statut']);
        if(!empty($f['q'])) $qb->like('titre',$f['q']);
        $total = $qb->count_all_results($this->table, TRUE); // TRUE pour reset query builder

        // Rebuild pour rows
        if(!empty($f['type'])) $this->db->where('type',$f['type']);
        if(!empty($f['statut'])) $this->db->where('statut',$f['statut']);
        if(!empty($f['q'])) $this->db->like('titre',$f['q']);
        $rows = $this->db->order_by('date_cloture','DESC')->limit($perPage,$offset)->get($this->table)->result_array();

        // Agrégats simples (montant total / gagné / en cours)
        $agg = ['total_montant'=>0,'total_gagne'=>0,'total_en_cours'=>0];
        foreach($rows as $r){
            $m = (float)($r['montant'] ?? 0); $agg['total_montant'] += $m;
            if(isset($r['statut']) && in_array(strtolower($r['statut']), ['gagné','won','closed'])) $agg['total_gagne'] += $m;
            if(isset($r['statut']) && in_array(strtolower($r['statut']), ['en cours','open','actif','active'])) $agg['total_en_cours'] += $m;
        }
        return ['rows'=>$rows,'total'=>$total,'aggregats'=>$agg,'page'=>$page,'per_page'=>$perPage];
    }
    
    /**
     * Récupérer les transactions d'un agent
     */
    public function get_transactions_by_agent($agent_id) {
        if(!$this->db->table_exists($this->table)) return [];
        return $this->db->where('commercial', $agent_id)
                       ->or_where('agent_id', $agent_id) // Si vous avez ce champ
                       ->order_by('created_at', 'DESC')
                       ->get($this->table)
                       ->result_array();
    }
}
