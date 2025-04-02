<?php
require_once './init.php';

$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    echo '<p>Votre panier est vide.</p>';
} else {
    echo '<div class="list-group">';
    foreach ($cart as $key => $item) {
        echo '<div class="list-group-item d-flex align-items-center">';
        // Image du produit (remplacez par le chemin réel de l'image)
        echo '<img src="/assets/images/bde.webp" alt="Produit ' . htmlspecialchars($item['id_produit']) . '" class="img-fluid me-3" style="width: auto; height: 100px;">';
        echo '<div class="d-flex flex-column w-100">';
        // Titre du produit et bouton de suppression
        echo '<div class="d-flex justify-content-between align-items-center">';
        echo '<h5 class="mb-1">Produit ' . htmlspecialchars($item['id_produit']) . '</h5>';
        echo '<button class="btn btn-danger btn-sm" onclick="removeFromCart(\'' . htmlspecialchars($key) . '\')"><i class="bi bi-trash"></i></button>';
        echo '</div>';
        // Couleur et taille
        echo '<div class="d-flex gap-3 align-items-center mb-2">';
        echo '<div class="d-flex align-items-center">';
        echo '<span class="ms-2">Couleur</span>';
        echo '<div class="color-swatch" style="width: 20px; height: 20px; background-color: #' . htmlspecialchars($item['couleur']) . '; border-radius: 50%; margin-left: 10px; border: 1px solid #ccc;"></div>';
        echo '</div>';
        echo '<span>Taille: ' . htmlspecialchars($item['taille']) . '</span>';
        echo '</div>';
        // Quantité et prix
        echo '<div class="d-flex justify-content-between align-items-center">';
        echo '<div class="d-flex align-items-center">';
        echo '<button class="btn btn-outline-secondary btn-sm me-2" onclick="updateQuantity(\'' . htmlspecialchars($key) . '\', -1)">-</button>';
        echo '<span>' . htmlspecialchars($item['quantite']) . '</span>';
        echo '<button class="btn btn-outline-secondary btn-sm ms-2" onclick="updateQuantity(\'' . htmlspecialchars($key) . '\', 1)">+</button>';
        echo '</div>';
        echo '<div class="price">';
        echo '<p class="mb-0">€' . number_format($item['quantite'] * 15, 2) . '</p>'; // Exemple de calcul de prix
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
    echo '</div>';
}