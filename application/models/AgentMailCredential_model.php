<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Gestion des identifiants mail agent (IMAP/SMTP)
 * Table : agent_mail_credentials
 */
class AgentMailCredential_model extends CI_Model {
    protected $table = 'agent_mail_credentials';

    public function get_by_agent($agentId) {
        return $this->db->where('agent_id', $agentId)->get($this->table)->row();
    }

    public function upsert($agentId, array $data) {
        $exists = $this->get_by_agent($agentId);
        $data['agent_id'] = $agentId;
        if($exists) {
            $this->db->where('agent_id',$agentId)->update($this->table,$data);
            return $exists->id;
        } else {
            $this->db->insert($this->table,$data);
            return $this->db->insert_id();
        }
    }

    public function delete_for_agent($agentId) {
        return $this->db->where('agent_id',$agentId)->delete($this->table);
    }

    /**
     * Chiffrement simple (à renforcer) – clé issue de config/encryption_key si présente.
     * Utiliser openssl_encrypt pour prod.
     */
    public function encrypt_password($plain) {
        $key = config_item('encryption_key');
        if(!$key) { return $plain; } // fallback clair si pas de clé
        $method = 'AES-256-CTR';
        $iv = substr(hash('sha256',$key),0,16);
        return openssl_encrypt($plain,$method,$key,0,$iv);
    }
    public function decrypt_password($cipher) {
        $key = config_item('encryption_key');
        if(!$key) { return $cipher; }
        $method = 'AES-256-CTR';
        $iv = substr(hash('sha256',$key),0,16);
        return openssl_decrypt($cipher,$method,$key,0,$iv);
    }
}
