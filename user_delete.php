<?php
error_log('Requête reçue : ' . print_r($_POST, true));
require_once './app/controllers/UtilisateurController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$controller = new UtilisateurController();
	$controller->deleteUser();
}