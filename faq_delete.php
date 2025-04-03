<?php
require_once './app/controllers/FaqController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$controller = new FaqController();
	$controller->delete();
}