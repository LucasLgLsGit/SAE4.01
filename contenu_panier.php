<?php
require_once './init.php';

$panier = $_SESSION['panier'] ?? [];

header('Content-Type: application/json');
echo json_encode($panier);
exit;