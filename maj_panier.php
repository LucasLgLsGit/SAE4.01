<?php
require_once './init.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cartKey = $_POST['cart_key'] ?? null;
    $quantite = isset($_POST['quantite']) ? (int)$_POST['quantite'] : null;

    if (!$cartKey || !isset($_SESSION['panier'][$cartKey])) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Produit introuvable dans le panier.']);
        exit;
    }

    if ($quantite > 0) {
        $_SESSION['panier'][$cartKey]['quantite'] = $quantite;
        echo json_encode(['success' => true, 'message' => 'Quantité mise à jour.', 'cart' => $_SESSION['panier']]);
    } else {
        unset($_SESSION['panier'][$cartKey]);
        echo json_encode(['success' => true, 'message' => 'Produit supprimé du panier.', 'cart' => $_SESSION['panier']]);
    }
}