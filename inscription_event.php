<?php
require_once './app/controllers/ParticipationController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new ParticipationController();
    $controller->createParticipation(); 
}