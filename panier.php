<?php
require_once './init.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_produit = $_POST['id_produit'] ?? null;
    $taille = $_POST['selected_taille'] ?? null;
    $couleur = $_POST['selected_couleur'] ?? null;
    $quantite = isset($_POST['quantite']) ? (int)$_POST['quantite'] : null;

    if (!$id_produit || !$taille || !$couleur || $quantite <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Paramètres invalides.']);
        exit;
    }

    $cartKey = $id_produit . '_' . $taille . '_' . $couleur;

    if (isset($_SESSION['cart'][$cartKey])) {
        $_SESSION['cart'][$cartKey]['quantite'] += $quantite;
    } else {
        $_SESSION['cart'][$cartKey] = [
            'id_produit' => $id_produit,
            'taille' => $taille,
            'couleur' => $couleur,
            'quantite' => $quantite
        ];
    }

    // Debug: Affichez le contenu du panier
    error_log(print_r($_SESSION['cart'], true));

    echo json_encode(['success' => true, 'message' => 'Produit ajouté au panier.']);
}