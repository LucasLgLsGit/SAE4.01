<?php
require_once './app/controllers/ActualiteController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$controller = new ActualiteController();
	$controller->delete();
} else {
	http_response_code(405);
	echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
}