<?php
require_once './app/controllers/ShopAdminController.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
	$controller = new ShopAdminController();
	$controller->createProduct();
} else {
	http_response_code(405);
	echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
}