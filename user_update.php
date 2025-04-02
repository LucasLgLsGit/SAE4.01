<?php
require_once './app/controllers/UtilisateurController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$controller = new UtilisateurController();
	$controller->update();
}