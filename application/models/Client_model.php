<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Client_model extends CI_Model {
    public function insert_client($data) {
        // Gestion de l'upload du document identité
        if (!empty($_FILES['identite_doc']['name'])) {
            $config['upload_path'] = './uploads/clients/';
            $config['allowed_types'] = 'pdf|jpg|jpeg|png';
            $config['max_size'] = 2048;
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('identite_doc')) {
                $fileData = $this->upload->data();
                $data['identite_doc'] = $fileData['file_name'];
            }
        }
        return $this->db->insert('clients', $data);
    }

    public function get_client($id) {
        return $this->db->get_where('clients', ['id' => $id])->row();
    }

    public function update_client($id, $data) {
        // Gestion de l'upload du document identité
        if (!empty($_FILES['identite_doc']['name'])) {
            $config['upload_path'] = './uploads/clients/';
            $config['allowed_types'] = 'pdf|jpg|jpeg|png';
            $config['max_size'] = 2048;
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('identite_doc')) {
                $fileData = $this->upload->data();
                $data['identite_doc'] = $fileData['file_name'];
            }
        }
        $this->db->where('id', $id);
        return $this->db->update('clients', $data);
    }

    public function delete_client($id) {
        $this->db->where('id', $id);
        return $this->db->delete('clients');
    }
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
