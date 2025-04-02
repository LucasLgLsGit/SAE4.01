<?php
require_once './app/controllers/ActualiteController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	error_log('Requête reçue : ' . print_r($_POST, true));
	$controller = new ActualiteController();
	$controller->update();
}