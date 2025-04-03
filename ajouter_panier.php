<?php
require_once './init.php';
require_once './app/repositories/ProduitRepository.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_produit = $_POST['id_produit'] ?? null;
    $taille = $_POST['selected_taille'] ?? null;
    $couleur = $_POST['selected_couleur'] ?? null;
    $quantite = isset($_POST['quantite']) ? (int)$_POST['quantite'] : null;

    // Collecter les erreurs
    $erreurs = [];

    if (!$id_produit) {
        $erreurs[] = 'ID du produit manquant.';
    }
    if (!$taille) {
        $erreurs[] = 'Taille non sélectionnée.';
    }
    if (!$couleur) {
        $erreurs[] = 'Couleur non sélectionnée.';
    }
    if ($quantite === null || $quantite <= 0) {
        $erreurs[] = 'Quantité invalide.';
    }

    if (!empty($erreurs)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Erreurs : ' . implode(' ', $erreurs)
        ]);
        exit;
    }

    $produitRepository = new ProduitRepository();
    $produit = $produitRepository->findById((int)$id_produit);

    if (!$produit) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Produit introuvable.']);
        exit;
    }

    $titre_produit = $produit->getTitre_produit();

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

    echo json_encode(['success' => true, 'message' => 'Produit ajouté au panier.']);
}