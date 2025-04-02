<?php
require_once './init.php';
require_once './app/repositories/ProduitRepository.php';

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

    // Récupérer le produit depuis le repository
    $produitRepository = new ProduitRepository();
    $produit = $produitRepository->findById((int)$id_produit);

if (!$produit) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Produit introuvable.']);
    exit;
}

// Récupérer le titre du produit
$titre_produit = $produit->getTitre_produit(); // Utilisation de la méthode getter

// Ajouter le produit au panier
$panierKey = $id_produit . '_'  . $titre_produit . '_' . $taille . '_' . $couleur;

if (isset($_SESSION['panier'][$panierKey])) {
    $_SESSION['panier'][$panierKey]['quantite'] += $quantite;
} else {
    $_SESSION['panier'][$panierKey] = [
        'id_produit' => $id_produit,
        'titre_produit' => $titre_produit,
        'taille' => $taille,
        'couleur' => $couleur,
        'quantite' => $quantite
    ];
    }
}

echo json_encode(['success' => true, 'message' => 'Produit ajouté au panier.']);