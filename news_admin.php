<?php
require_once './app/controllers/ActualiteController.php';
require_once './app/controllers/NewsAdminController.php';

$ActualiteController = new ActualiteController();
$NewsAdminController = new NewsAdminController();

$action = $_GET['action'] ?? 'index';

switch ($action) {
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ActualiteController->create();
        }
        break;
    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ActualiteController->update();
        }
        break;
    case 'delete':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ActualiteController->delete();
        }
        break;
    default:
        $NewsAdminController->index();
        break;
}