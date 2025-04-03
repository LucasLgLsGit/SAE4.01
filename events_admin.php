<?php
require_once './app/controllers/EvenementController.php';
require_once './app/controllers/EventAdminController.php';

$Evenementcontroller = new EvenementController();
$EventAdminController = new EventAdminController();

$action = $_GET['action'] ?? 'index';

switch ($action) {
    case 'create':
        $Evenementcontroller->create();
        break;
    case 'update':
        $Evenementcontroller->update();
        break;
    case 'delete':
        $Evenementcontroller->delete();
        break;
    default:
        $EventAdminController->index();
        break;
}