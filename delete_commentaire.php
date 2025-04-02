<?php
require_once './app/controllers/CommentaireController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new CommentaireController();
    $controller->delete();
}