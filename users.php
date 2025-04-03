<?php
require_once './app/controllers/UtilisateurController.php';

$controller = new UtilisateurController();
$action = $_GET['action'] ?? 'index'; // Action par défaut : index

// Liste des actions publiques
$publicActions = ['index', 'create', 'updateMail', 'updateMdp'];
// Liste des actions réservées aux admins
$adminActions = ['updatePermission', 'update', 'deleteUser'];

// Vérification si l'utilisateur est admin pour les actions protégées
$isAdmin = $controller->isUserAdmin();

if (in_array($action, $adminActions) && !$isAdmin) {
    // Rediriger vers une page d'erreur ou de connexion si non-admin
    header('Location: /users.php?action=login&error=' . urlencode("Vous devez être administrateur pour cette action."));
    exit;
}

// Gestion des actions avec ou sans POST
switch ($action) {
    case 'index':
        $controller->index();
        break;
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->create();
        } else {
            $controller->create(); // Affiche le formulaire si GET
        }
        break;
    case 'updateMail':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->updateMail();
        }
        break;
    case 'updateMdp':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->updateMdp();
        }
        break;
    case 'updatePermission':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->updatePermission();
        }
        break;
    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->update();
        }
        break;
    case 'deleteUser':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->deleteUser();
        }
        break;
    default:
        // Action inconnue : redirection ou erreur
        header('Location: /users.php?action=index&error=' . urlencode("Action inconnue."));
        exit;
}