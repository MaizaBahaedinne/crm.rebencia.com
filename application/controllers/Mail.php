<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Mail (MailController)
 * Gestion de l'envoi et de la boîte d'envoi simple (historique) des emails
 * @property CI_Form_validation $form_validation
 * @property CI_Security $security
 * @property CI_Input $input
 * @property CI_Email $email
 * @property CI_Session $session
 * @property MailClient $mailclient
 */
class Mail extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('MailClient', NULL, 'mailclient');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('user_agent');
        $this->isLoggedIn();
      
    }

    public function index()
    {
        redirect('mail/inbox');
    }

    /**
     * Liste simple des emails envoyés (inbox fictive = emails créés via le module)
     */
    public function inbox()
    {
   
        $page = (int)$this->input->get('page'); if($page<1) $page=1;
        $filter = $this->input->get('filter'); // all | unseen
        $criteria = ($filter==='unread' || $filter==='unseen') ? 'UNSEEN' : 'ALL';
        $limit = 20;
        $result = $this->mailclient->listMessages($limit, $page, $criteria);
        $data['emails'] = $result['messages'];
        $data['pagination'] = $result['pagination'];
        $data['filter'] = $filter;
        $this->global['pageTitle'] = 'CRM : Emails';
        $this->loadViews('mail/list', $this->global, $data, NULL);
    }

    /**
     * Formulaire de composition
     */
    public function compose()
    {
        if(!$this->hasCreateAccess()) { return $this->loadThis(); }
        $this->global['pageTitle'] = 'CRM : Nouveau mail';
        $this->loadViews('mail/compose', $this->global, NULL, NULL);
    }

    /**
     * Envoi + enregistrement
     */
    public function send()
    {
        if(!$this->hasCreateAccess()) { return $this->loadThis(); }

        $this->form_validation->set_rules('to','Destinataire','trim|required|valid_email');
        $this->form_validation->set_rules('subject','Sujet','trim|required|max_length[255]');
        $this->form_validation->set_rules('message','Message','trim|required');

        if($this->form_validation->run() === FALSE) {
            return $this->compose();
        }

        $to = $this->security->xss_clean($this->input->post('to'));
        $subject = $this->security->xss_clean($this->input->post('subject'));
        $message = $this->input->post('message');

        // Gestion upload pièces jointes
        $uploadedPaths = [];
        if(!empty($_FILES['attachments']['name'][0])) {
            $count = count($_FILES['attachments']['name']);
            $uploadDir = FCPATH.'uploads/mail_temp/';
            if(!is_dir($uploadDir)) @mkdir($uploadDir,0775,true);
            for($i=0;$i<$count;$i++) {
                if($_FILES['attachments']['error'][$i]!==UPLOAD_ERR_OK) continue;
                $tmpName = $_FILES['attachments']['tmp_name'][$i];
                $origName = preg_replace('/[^A-Za-z0-9._-]/','_', $_FILES['attachments']['name'][$i]);
                $dest = $uploadDir.time().'_'.$i.'_'.$origName;
                if(move_uploaded_file($tmpName, $dest)) {
                    $uploadedPaths[] = $dest;
                }
            }
        }

        $sent = $this->mailclient->send($to, $subject, $message, $uploadedPaths);

        if($sent) {
            $this->session->set_flashdata('success', 'Email envoyé');
        } else {
            $this->session->set_flashdata('error', 'Échec envoi email');
        }

        redirect('mail/inbox');
    }

    /**
     * Lecture d'un email (UID IMAP)
     */
    public function view($uid = null)
    {
        if(!$this->hasListAccess()) { return $this->loadThis(); }
        if(!$uid) return redirect('mail/inbox');
        $msg = $this->mailclient->getMessage((int)$uid);
        if(!$msg) {
            $this->session->set_flashdata('error','Email introuvable');
            return redirect('mail/inbox');
        }
        $data['message'] = $msg;
        $this->global['pageTitle'] = 'CRM : '.$msg['subject'];
        $this->loadViews('mail/view', $this->global, $data, NULL);
    }

    public function markRead($uid=null) {
        if(!$this->hasUpdateAccess()) { return $this->loadThis(); }
        if($uid) $this->mailclient->setFlag((int)$uid,'\\Seen', false);
        redirect($this->agent->referrer() ?: 'mail/inbox');
    }

    public function markUnread($uid=null) {
        if(!$this->hasUpdateAccess()) { return $this->loadThis(); }
        if($uid) $this->mailclient->setFlag((int)$uid,'\\Seen', true);
        redirect($this->agent->referrer() ?: 'mail/inbox');
    }

    public function download($uid=null,$part=null) {
        if(!$this->hasListAccess()) { return $this->loadThis(); }
        if(!$uid || !$part) return redirect('mail/view/'.$uid);
        $att = $this->mailclient->getAttachment((int)$uid, (int)$part);
        if(!$att) { $this->session->set_flashdata('error','Pièce jointe introuvable'); return redirect('mail/view/'.$uid); }
        header('Content-Type: '.$att['mime']);
        header('Content-Disposition: attachment; filename="'.basename($att['name']).'"');
        header('Content-Length: '.$att['size']);
        echo $att['content'];
        exit;
    }
}

?>