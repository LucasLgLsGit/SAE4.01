<?php
require_once './app/controllers/ActualiteController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$controller = new ActualiteController();
	$controller->update();
}