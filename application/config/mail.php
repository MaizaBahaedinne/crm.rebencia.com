<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Configuration Mail (IMAP + SMTP)
| Adapter avec vos vraies valeurs. Ne PAS commiter de secrets en production.
| Vous pouvez aussi charger ces valeurs via variables d'environnement.
|--------------------------------------------------------------------------
*/
$config['imap_host']  = getenv('IMAP_HOST') ?: 'imap.mail.ovh.net'; // ou ssl0.ovh.net
$config['imap_port']  = getenv('IMAP_PORT') ?: 993; // SSL/TLS
// Pour un certificat valide OVH vous pouvez retirer novalidate-cert : '/imap/ssl'
$config['imap_flags'] = getenv('IMAP_FLAGS') ?: '/imap/ssl';
$config['imap_folder'] = getenv('IMAP_FOLDER') ?: 'INBOX';
$config['imap_user']  = getenv('IMAP_USER') ?: 'user@votre-domaine.tld';
$config['imap_pass']  = getenv('IMAP_PASS') ?: 'CHANGER_MOI';

// SMTP pour envoi (utilisé par la librairie email de CI)
$config['smtp_protocol'] = getenv('SMTP_PROTOCOL') ?: 'smtp';
$config['smtp_host']     = getenv('SMTP_HOST') ?: 'smtp.mail.ovh.net'; // ou ssl0.ovh.net
$config['smtp_port']     = getenv('SMTP_PORT') ?: 465; // SSL
$config['smtp_user']     = getenv('SMTP_USER') ?: 'user@votre-domaine.tld';
$config['smtp_pass']     = getenv('SMTP_PASS') ?: 'CHANGER_MOI';
$config['smtp_crypto']   = getenv('SMTP_CRYPTO') ?: 'ssl'; // ssl pour port 465
$config['from_email']    = getenv('MAIL_FROM_EMAIL') ?: 'no-reply@votre-domaine.tld';
$config['from_name']     = getenv('MAIL_FROM_NAME') ?: 'CRM';
$config['mailtype']      = 'html';
$config['charset']       = 'utf-8';
$config['newline']       = "\r\n";
$config['crlf']          = "\r\n";
