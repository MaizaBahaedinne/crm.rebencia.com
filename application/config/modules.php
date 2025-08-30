<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| MIME TYPES
| -------------------------------------------------------------------
| This file contains an array of mime types.  It is used by the
| Upload class to help identify allowed file types.
|
*/
$config['moduleList'] = array(
    array('module'=>'Task',
    	'total_access'=>0, 'list'=>0, 'create_records'=>0, 'edit_records'=>0, 'delete_records'=>0),
    array('module'=>'Booking',
    	'total_access'=>0, 'list'=>0, 'create_records'=>0, 'edit_records'=>0, 'delete_records'=>0)
);
// Ajout module Mail pour gestion de la messagerie IMAP/SMTP
// IMPORTANT : après ajout, exécuter la régénération de la matrice ou éditer chaque rôle pour accorder les droits
// Champs : list (lecture inbox), create_records (composer/envoyer), edit_records (marquer lu/non lu), delete_records (supprimer futur), total_access (surclasse tout)
$config['moduleList'][] = array('module'=>'Mail',
	'total_access'=>0, 'list'=>1, 'create_records'=>1, 'edit_records'=>1, 'delete_records'=>0);