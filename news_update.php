<?php
require_once './app/controllers/ActualiteController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	error_log('Requête reçue : ' . print_r($_POST, true));
	$controller = new ActualiteController();
	$controller->update();
} else {
	http_response_code(405);
	echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
}