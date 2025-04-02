<?php
require_once './app/controllers/ParticipationController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idEvent = $_POST['id_event'] ?? null;
    $idUser = $_POST['id_user'] ?? null;

    $controller = new ParticipationController();
    $controller->deleteParticipation($idUser, $idEvent);
}