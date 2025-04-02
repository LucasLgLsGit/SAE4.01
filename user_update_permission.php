<?php
require_once './app/controllers/UtilisateurController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$controller = new UtilisateurController();
	$controller->updatePermission();
} else {
	http_response_code(405);
	echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
}