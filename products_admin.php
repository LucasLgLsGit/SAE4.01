<?php
require_once './app/controllers/ShopAdminController.php';
require_once './app/controllers/ProductController.php';

$productController = new ProductController();
$shopAdminController = new ShopAdminController();

$action = $_GET['action'] ?? 'index';

switch ($action) {
	case 'createProduct':
		$productController->createProduct();
		break;
	default:
		$shopAdminController->index();
		break;
}