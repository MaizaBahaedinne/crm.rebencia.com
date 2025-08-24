<?php
// Récupération des infos utilisateur depuis la session
$session = \Config\Services::session();
$username = $session->get('user_login');
$role = $session->get('role');
?>
<div style="margin-bottom: 1em;">
	<strong>Utilisateur connecté :</strong> <?= htmlspecialchars($username ?? 'Inconnu', ENT_QUOTES, 'UTF-8') ?>
	<br>
	<strong>Rôle :</strong> <?= htmlspecialchars($role ?? 'Non défini', ENT_QUOTES, 'UTF-8') ?>
</div>
<h1>Bienvenue sur le dashboard</h1>
<a href="/logout" class="btn btn-danger">Logout</a>