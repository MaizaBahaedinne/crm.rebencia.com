<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * MailClient : Accès IMAP (lecture) + SMTP (envoi via CI Email)
 * @property CI_Config $config
 * @property CI_Email $email
 */
class MailClient {
    /** @var CI_Controller */
    protected $CI;
    protected $cfg;
    protected $agentId = null;
    /** @var CI_Email */
    protected $email;

    public function __construct() {
        $this->CI =& get_instance();
        // Charger fichier config/mail.php puis récupérer tableau complet
    $this->CI->load->config('mail');
    $keys = ['imap_host','imap_port','imap_flags','imap_folder','imap_user','imap_pass','smtp_protocol','smtp_host','smtp_port','smtp_user','smtp_pass','smtp_crypto','from_email','from_name','mailtype','charset','newline','crlf'];
    $c = [];
    foreach($keys as $k){ $c[$k] = config_item($k); }
    $this->cfg = $c;
        // Filtrer uniquement les clés mail.* si nécessaire sinon garder variables individuelles
    }

    /**
     * Spécifie un agent pour utiliser ses identifiants personnalisés (table agent_mail_credentials)
     */
    public function setAgent($agentId): self {
        $this->agentId = (int)$agentId;
        $this->hydrateAgentCredentials();
        return $this;
    }

    protected function hydrateAgentCredentials(): void {
        if(!$this->agentId) return;
        if(!function_exists('agent_mail_config')) {
            $this->CI->load->helper('agent_mail');
        }
        if(function_exists('agent_mail_config')) {
            $cred = agent_mail_config($this->agentId);
            // ne remplacer que si valeurs présentes
            foreach($cred as $k=>$v) {
                if($v !== null && $v !== '') {
                    $this->cfg[$k] = $v;
                }
            }
        }
    }

    protected function mailboxString(): string {
        return '{'.$this->cfg['imap_host'].':'.$this->cfg['imap_port'].$this->cfg['imap_flags'].'}'.$this->cfg['imap_folder'];
    }

    protected function open() {
        if(!function_exists('imap_open')) {
            throw new Exception('Extension PHP IMAP non activée');
        }
        // si agent défini mais pas encore hydraté (appel direct) tenter hydratation
        if($this->agentId && (!isset($this->cfg['imap_user']) || $this->cfg['imap_user']==='user@votre-domaine.tld')) {
            $this->hydrateAgentCredentials();
        }
        $mb = @imap_open($this->mailboxString(), $this->cfg['imap_user'], $this->cfg['imap_pass']);
        if(!$mb) {
            throw new Exception('Connexion IMAP échouée: '.imap_last_error());
        }
        return $mb;
    }

    public function listMessages(int $limit=50, int $page=1, string $criteria='ALL'): array {
        try {
            $mb = $this->open();
            $crit = strtoupper(trim($criteria));
            if(!in_array($crit, ['ALL','UNSEEN'])) { $crit = 'ALL'; }
            $uids = imap_search($mb, $crit, SE_UID) ?: [];
            rsort($uids);
            $total = count($uids);
            $pages = $limit ? (int)ceil($total / $limit) : 1;
            $page = max(1, min($page, $pages));
            $offset = ($page-1)*$limit;
            $slice = array_slice($uids, $offset, $limit);
            $messages = [];
            foreach($slice as $uid) {
                $ov = imap_fetch_overview($mb, $uid, FT_UID);
                if(!$ov) continue;
                $o = $ov[0];
                $subject = $this->decodeHeader($o->subject ?? '');
                $from = $this->decodeHeader($o->from ?? '');
                $date = $o->date ?? '';
                $bodySnippet = $this->getBodySnippet($mb, $uid);
                $hasAttachments = $this->hasAttachments($mb, $uid);
                $messages[] = [
                    'uid' => $uid,
                    'from' => $from,
                    'subject' => $subject,
                    'date' => $date,
                    'snippet' => $bodySnippet,
                    'seen' => isset($o->seen) && $o->seen == 1,
                    'attachments' => $hasAttachments
                ];
            }
            imap_close($mb);
            return [ 'messages' => $messages, 'pagination' => [ 'total' => $total, 'pages' => $pages, 'page' => $page, 'limit' => $limit ] ];
        } catch(Exception $e) {
            log_message('error', 'MailClient listMessages: '.$e->getMessage());
            return [ 'messages'=>[], 'pagination'=>['total'=>0,'pages'=>0,'page'=>1,'limit'=>$limit] ];
        }
    }

    public function getMessage(int $uid): ?array {
        try {
            $mb = $this->open();
            $ov = imap_fetch_overview($mb, $uid, FT_UID);
            if(!$ov) { imap_close($mb); return null; }
            $o = $ov[0];
            $subject = $this->decodeHeader($o->subject ?? '');
            $from = $this->decodeHeader($o->from ?? '');
            $date = $o->date ?? '';
            $bodyHtml = $this->fetchBody($mb, $uid, 'html');
            $bodyText = $bodyHtml ? strip_tags($bodyHtml) : $this->fetchBody($mb, $uid, 'plain');
            $attachments = $this->extractAttachments($mb, $uid);
            imap_close($mb);
            return [
                'uid' => $uid,
                'from' => $from,
                'subject' => $subject,
                'date' => $date,
                'body_html' => $bodyHtml,
                'body_text' => $bodyText,
                'attachments' => $attachments
            ];
        } catch(Exception $e) {
            log_message('error', 'MailClient getMessage: '.$e->getMessage());
            return null;
        }
    }

    public function send(string $to, string $subject, string $message, array $attachmentsPaths = []): bool {
        $config = [
            'protocol' => $this->cfg['smtp_protocol'],
            'smtp_host' => $this->cfg['smtp_host'],
            'smtp_port' => $this->cfg['smtp_port'],
            'smtp_user' => $this->cfg['smtp_user'],
            'smtp_pass' => $this->cfg['smtp_pass'],
            'smtp_crypto' => $this->cfg['smtp_crypto'],
            'mailtype' => $this->cfg['mailtype'],
            'charset' => $this->cfg['charset'],
            'newline' => $this->cfg['newline'],
            'crlf' => $this->cfg['crlf']
        ];
    // Charger la classe puis instancier directement (évite propriété dynamique inconnue de l'analyseur)
    $this->CI->load->library('email');
    $this->email = new CI_Email($config);
    $this->email->from($this->cfg['from_email'], $this->cfg['from_name']);
    $this->email->to($to);
    $this->email->subject($subject);
    $this->email->message($message);
    foreach($attachmentsPaths as $p) { if(is_file($p)) $this->email->attach($p); }
    return $this->email->send();
    }

    protected function decodeHeader(string $str): string {
        $decoded = imap_mime_header_decode($str);
        $out='';
        foreach($decoded as $d){ $out .= $d->text; }
        return $out;
    }

    protected function getBodySnippet($mb, $uid, int $len=120): string {
        $body = $this->fetchBody($mb, $uid, 'plain');
        if(!$body) $body = strip_tags($this->fetchBody($mb, $uid, 'html'));
        $body = trim(preg_replace('/\s+/',' ', (string)$body));
        return mb_substr($body,0,$len).(mb_strlen($body)>$len?'…':'');
    }

    protected function fetchBody($mb, $uid, string $type) {
        $structure = imap_fetchstructure($mb, $uid, FT_UID);
        if(!$structure) return null;
        $body = $this->walkParts($mb, $uid, $structure, $type);
        return $body;
    }

    protected function walkParts($mb, $uid, $structure, $typeWanted, $partNo='') {
        $targetSubType = $typeWanted==='html' ? 'HTML' : 'PLAIN';
        if(isset($structure->parts) && count($structure->parts)) {
            $i=1; foreach($structure->parts as $part) {
                $newPartNo = $partNo ? $partNo.'.'.$i : (string)$i;
                $res = $this->walkParts($mb, $uid, $part, $typeWanted, $newPartNo);
                if($res) return $res; $i++; }
        } else {
            $isText = ($structure->type==TYPETEXT);
            $sub = strtoupper($structure->subtype ?? '');
            if($isText && $sub===$targetSubType) {
                $raw = imap_fetchbody($mb, $uid, $partNo?:'1', FT_UID);
                if($structure->encoding==3) $raw = base64_decode($raw);
                elseif($structure->encoding==4) $raw = quoted_printable_decode($raw);
                return $raw;
            }
        }
        return null;
    }

    protected function hasAttachments($mb, $uid): bool {
        $structure = imap_fetchstructure($mb, $uid, FT_UID);
        if(!$structure || empty($structure->parts)) return false;
        foreach($structure->parts as $part) {
            if(isset($part->disposition) && in_array(strtolower($part->disposition), ['attachment','inline'])) {
                return true;
            }
        }
        return false;
    }

    protected function extractAttachments($mb, $uid): array {
        $structure = imap_fetchstructure($mb, $uid, FT_UID);
        $attachments = [];
        if(!$structure || empty($structure->parts)) return $attachments;
        $i=1; foreach($structure->parts as $part) {
            $isAttachment = false;
            $fileName='';
            if(isset($part->dparameters)) {
                foreach($part->dparameters as $obj) {
                    if(strtolower($obj->attribute)=='filename') { $isAttachment=true; $fileName=$obj->value; }
                }
            }
            if(isset($part->parameters)) {
                foreach($part->parameters as $obj) {
                    if(strtolower($obj->attribute)=='name') { $isAttachment=true; $fileName=$obj->value; }
                }
            }
            if($isAttachment) {
                $body = imap_fetchbody($mb, $uid, $i, FT_UID);
                if($part->encoding==3) { $body = base64_decode($body); }
                elseif($part->encoding==4) { $body = quoted_printable_decode($body); }
                $attachments[] = [
                    'name' => $fileName,
                    'size' => strlen($body),
                    'content' => $body,
                    'part' => $i
                ];
            }
            $i++;
        }
        return $attachments;
    }

    public function saveAttachmentContent(array $att, string $destDir): ?string {
        if(!is_dir($destDir)) @mkdir($destDir, 0775, true);
        $safe = preg_replace('/[^A-Za-z0-9._-]/','_', $att['name']);
        $path = rtrim($destDir,'/').'/'.date('Ymd_His').'_'.$safe;
        if(file_put_contents($path, $att['content'])!==false) return $path;
        return null;
    }

    /**
     * Marquer un message comme lu/non-lu
     */
    public function setFlag(int $uid, string $flag='\\Seen', bool $unset=false): bool {
        try {
            $mb = $this->open();
            if($unset) {
                $res = imap_clearflag_full($mb, (string)$uid, $flag, ST_UID);
            } else {
                $res = imap_setflag_full($mb, (string)$uid, $flag, ST_UID);
            }
            imap_close($mb);
            return (bool)$res;
        } catch(Exception $e) {
            log_message('error','MailClient setFlag: '.$e->getMessage());
            return false;
        }
    }

    /**
     * Récupérer une pièce jointe spécifique par numéro de part (enregistré dans extractAttachments)
     */
    public function getAttachment(int $uid, int $partNo): ?array {
        try {
            $mb = $this->open();
            $atts = $this->extractAttachments($mb, $uid);
            imap_close($mb);
            foreach($atts as $a) {
                if((int)$a['part'] === (int)$partNo) {
                    // Déterminer mime approximatif
                    $finfo = function_exists('finfo_open') ? finfo_open(FILEINFO_MIME_TYPE) : null;
                    $mime = ($finfo && ($m=finfo_buffer($finfo, $a['content']))) ? $m : 'application/octet-stream';
                    if($finfo) finfo_close($finfo);
                    return [
                        'name' => $a['name'] ?: ('attachment-'.$partNo),
                        'mime' => $mime,
                        'size' => $a['size'],
                        'content' => $a['content']
                    ];
                }
            }
            return null;
        } catch(Exception $e) {
            log_message('error','MailClient getAttachment: '.$e->getMessage());
            return null;
        }
    }
}
