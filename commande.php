<?php
require_once './init.php';
require_once './app/controllers/CommandeController.php';
require_once './app/services/AuthService.php'; // Ajout pour utiliser AuthService

if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_samesite', 'Lax');
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Le panier est vide.']);
        exit;
    }

    // Utiliser AuthService pour récupérer l'utilisateur
    $authService = new AuthService();
    $user = $authService->getUser();

    if ($user === null || !method_exists($user, 'getId')) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté ou données invalides.']);
        exit;
    }

    $id_user = $user->getId(); // Récupérer l'ID de l'utilisateur

	$controller = new CommandeController();
	foreach ($_SESSION['panier'] as $article) {
		$controller->upsertCommande([
			'id_user' => $id_user,
			'id_produit' => $article['id_produit'],
			'quantite' => $article['quantite']
		]);
	}
	
	$_SESSION['panier'] = [];
	echo json_encode(['success' => true, 'message' => 'Commande(s) créée(s) ou mise(s) à jour avec succès.']);
	
	$_SESSION['panier'] = [];
	echo json_encode(['success' => true, 'message' => 'Commande(s) créée(s) ou mise(s) à jour avec succès.']);
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
}
?>