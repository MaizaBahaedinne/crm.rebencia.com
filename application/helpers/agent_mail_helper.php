<?php defined('BASEPATH') OR exit('No direct script access allowed');

if(!function_exists('agent_mail_config')) {
    /**
     * Retourne tableau config mail spécifique agent, fallback config globale.
     */
    function agent_mail_config($agentId) {
        $CI =& get_instance();
        if(!class_exists('AgentMailCredential_model')) {
            require_once APPPATH.'models/AgentMailCredential_model.php';
        }
        $model = new AgentMailCredential_model();
        $row = $model->get_by_agent($agentId);
        $CI->load->config('mail');
        $base = [
            'imap_user'=>config_item('imap_user'),
            'imap_pass'=>config_item('imap_pass'),
            'imap_host'=>config_item('imap_host'),
            'imap_port'=>config_item('imap_port'),
            'imap_flags'=>config_item('imap_flags'),
            'imap_folder'=>config_item('imap_folder'),
            'smtp_host'=>config_item('smtp_host'),
            'smtp_port'=>config_item('smtp_port'),
            'smtp_user'=>config_item('smtp_user'),
            'smtp_pass'=>config_item('smtp_pass'),
            'smtp_crypto'=>config_item('smtp_crypto')
        ];
        if(!$row) return [
            'imap_user' => $base['imap_user'] ?? null,
            'imap_pass' => $base['imap_pass'] ?? null,
            'imap_host' => $base['imap_host'] ?? null,
            'imap_port' => $base['imap_port'] ?? null,
            'imap_flags'=> $base['imap_flags']?? '/imap/ssl',
            'imap_folder'=> $base['imap_folder']??'INBOX',
            'smtp_host'=> $base['smtp_host']??null,
            'smtp_port'=> $base['smtp_port']??null,
            'smtp_user'=> $base['smtp_user']??null,
            'smtp_pass'=> $base['smtp_pass']??null,
            'smtp_crypto'=>$base['smtp_crypto']??'ssl'
        ];
    $pass = $row->password_encrypted;
    $dec = method_exists($model,'decrypt_password') ? $model->decrypt_password($pass) : $pass;
        return [
            'imap_user' => $row->email_address,
            'imap_pass' => $dec,
            'imap_host' => $row->imap_host ?: ($base['imap_host'] ?? null),
            'imap_port' => $row->imap_port ?: ($base['imap_port'] ?? 993),
            'imap_flags'=> $row->imap_flags ?: ($base['imap_flags'] ?? '/imap/ssl'),
            'imap_folder'=> $row->imap_folder ?: ($base['imap_folder'] ?? 'INBOX'),
            'smtp_host'=> $row->smtp_host ?: ($base['smtp_host'] ?? null),
            'smtp_port'=> $row->smtp_port ?: ($base['smtp_port'] ?? 465),
            'smtp_user'=> $row->email_address,
            'smtp_pass'=> $dec,
            'smtp_crypto'=> $row->smtp_crypto ?: ($base['smtp_crypto'] ?? 'ssl')
        ];
    }
}
