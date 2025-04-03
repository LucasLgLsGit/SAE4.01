<?php
try {
    require_once './init.php';
    require_once './app/repositories/ProduitRepository.php';

    header('Content-Type: application/json');

    if (session_status() === PHP_SESSION_NONE) {
        ini_set('session.cookie_samesite', 'Lax');
        session_start();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $titre_produit = $_POST['titre_produit'] ?? null;
        $taille = $_POST['selected_taille'] ?? null;
        $couleur = $_POST['selected_couleur'] ?? null;
        $quantite = isset($_POST['quantite']) ? (int)$_POST['quantite'] : null;

        // Collecter les erreurs
        $erreurs = [];

        if (!$titre_produit) {
            $erreurs[] = 'Titre du produit manquant.';
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

        // Débogage : afficher les valeurs reçues
        error_log("Valeurs reçues : titre_produit=$titre_produit, taille=$taille, couleur=$couleur, quantite=$quantite");

        $produitRepository = new ProduitRepository();
        $produit = $produitRepository->findByAttributes($titre_produit, $taille, $couleur);
        
        if (!$produit) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Produit introuvable avec les attributs spécifiés.']);
            exit;
        }

        $id_produit = $produit->getId_produit();

        // Créer une clé unique pour le panier basée sur id_produit, taille et couleur
        $panierKey = $id_produit . '_' . $taille . '_' . $couleur;

        if (isset($_SESSION['panier'][$panierKey])) {
            $_SESSION['panier'][$panierKey]['quantite'] += $quantite;
        } else {
            $_SESSION['panier'][$panierKey] = [
                'id_produit' => $id_produit,
                'titre_produit' => $produit->getTitre_produit(),
                'prix' => $produit->getPrix(),
                'taille' => $taille,
                'couleur' => $couleur,
                'quantite' => $quantite
            ];
        }

        echo json_encode(['success' => true, 'message' => 'Produit ajouté au panier.']);
    } else {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur serveur : ' . $e->getMessage()]);
}
?>