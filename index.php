<?php
ini_set('session.cookie_samesite', 'Lax');

require_once './app/controllers/HomeController.php';
(new HomeController())->index();