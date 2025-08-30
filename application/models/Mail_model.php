<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Mail_model : gestion stockage des emails envoyés via le CRM
 */
class Mail_model extends CI_Model
{
    private $table = 'emails';

    public function __construct()
    {
        parent::__construct();
    }

    public function listEmails($userId, $limit = 50)
    {
        $this->db->where('sender_id', (int)$userId)
                 ->order_by('created_at','DESC')
                 ->limit($limit);
        return $this->db->get($this->table)->result();
    }

    public function store(array $data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function updateStatus($id, $status)
    {
        $this->db->where('id', (int)$id)->update($this->table, [
            'status' => $status,
            'sent_at' => ($status === 'sent') ? date('Y-m-d H:i:s') : NULL
        ]);
    }

    /**
     * Retourne la config email (TODO: externaliser en DB / settings)
     */
    public function getEmailConfig()
    {
        // Placeholder: vous pouvez charger depuis table settings
        return [
            'protocol' => 'smtp',
            'smtp_host' => getenv('SMTP_HOST') ?: 'smtp.mailtrap.io',
            'smtp_port' => getenv('SMTP_PORT') ?: 2525,
            'smtp_user' => getenv('SMTP_USER') ?: 'demo_user',
            'smtp_pass' => getenv('SMTP_PASS') ?: 'demo_pass',
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'newline' => "\r\n",
            'crlf' => "\r\n",
        ];
    }
}

/* SQL SUGGERE :
CREATE TABLE `emails` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `sender_id` int unsigned NOT NULL,
  `recipient_email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` mediumtext NOT NULL,
  `status` enum('pending','sent','failed') NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL,
  `sent_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sender_id` (`sender_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
*/
?>