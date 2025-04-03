<?php
require_once './init.php';
require_once './app/controllers/CommandeController.php';
require_once './app/services/AuthService.php';

if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_samesite', 'Lax');
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])) {
        header('Location: /confirmation.php?message=' . urlencode('Erreur : Le panier est vide.'));
        exit;
    }

    // Utiliser AuthService pour récupérer l'utilisateur
    $authService = new AuthService();
    $user = $authService->getUser();

    if ($user === null || !method_exists($user, 'getId')) {
        header('Location: /login.php');
        exit;
    }

    $id_user = $user->getId(); // Récupérer l'ID de l'utilisateur

    // Générer un numéro de commande initial pour cette commande
    $numero_commande = uniqid('CMD_');

    $controller = new CommandeController();
    $articlesAvecNumero = [];

    // Traiter chaque article du panier
    foreach ($_SESSION['panier'] as $key => $article) {
        $commandeData = [
            'id_user' => $id_user,
            'id_produit' => $article['id_produit'],
            'quantite' => $article['quantite'],
            'numero_commande' => $numero_commande
        ];

        // Insérer la commande dans la table Commande
        // upsertCommande va s'assurer que le numero_commande est unique
        $numero_commande = $controller->upsertCommande($commandeData);

        // Ajouter le numéro de commande à l'article
        $article['numero_commande'] = $numero_commande;
        $article['date_commande'] = date('Y-m-d H:i:s');
        $articlesAvecNumero[$key] = $article;
    }

    // Sauvegarder les articles avec leurs numéros de commande dans une variable de session temporaire
    $_SESSION['derniere_commande'] = $articlesAvecNumero;

    // Vider le panier après la commande
    $_SESSION['panier'] = [];

    // Rediriger vers la page de confirmation
    header('Location: /confirmation.php?message=' . urlencode('Merci d\'avoir passé commande !'));
    exit;
} else {
    header('Location: /confirmation.php?message=' . urlencode('Erreur : Méthode non autorisée.'));
    exit;
}
?>