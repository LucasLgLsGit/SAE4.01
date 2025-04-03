<?php
require_once './init.php';
require_once './app/repositories/ProduitRepository.php';

header('Content-Type: application/json');

$titre = $_GET['titre'] ?? null;
$taille = $_GET['taille'] ?? null;
$couleur = $_GET['couleur'] ?? null;

// Débogage : afficher les valeurs reçues
error_log("get_product_id.php - Valeurs reçues : titre=$titre, taille=$taille, couleur=$couleur");

if (!$titre || !$taille || !$couleur) {
    echo json_encode(['success' => false, 'message' => 'Paramètres manquants.']);
    exit;
}

$produitRepository = new ProduitRepository();
$produit = $produitRepository->findByAttributes($titre, $taille, $couleur);

if ($produit) {
    echo json_encode(['success' => true, 'id_produit' => $produit->getId_produit()]);
} else {
    echo json_encode(['success' => false, 'message' => 'Produit introuvable.']);
}
?>