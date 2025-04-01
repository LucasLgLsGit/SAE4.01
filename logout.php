<?php
require_once './app/controllers/AuthController.php';
require_once './app/services/AuthService.php';
(new AuthController())->logout();
?>