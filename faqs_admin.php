<?php
require_once './app/controllers/FaqController.php';
require_once './app/controllers/FaqAdminController.php';

$Faqcontroller = new FaqController();
$FaqAdminController = new FaqAdminController();

$action = $_GET['action'] ?? 'index';

switch ($action) {
    case 'create':
        $Faqcontroller->create();
        break;
    case 'update':
        $Faqcontroller->update();
        break;
    case 'delete':
        $Faqcontroller->delete();
        break;
    default:
        $FaqAdminController->index();
        break;
}