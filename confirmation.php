<?php
require_once './init.php';
require_once './app/services/AuthService.php';

if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_samesite', 'Lax');
    session_start();
}

// Vérifier si l'utilisateur est connecté
$authService = new AuthService();
$user = $authService->getUser();

if ($user === null || !method_exists($user, 'getId')) {
    header('Location: /login.php');
    exit;
}

// Récupérer le message de confirmation
$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : 'Merci d\'avoir passé commande !';

// Récupérer les articles de la dernière commande
$articlesCommandes = isset($_SESSION['derniere_commande']) ? $_SESSION['derniere_commande'] : [];

// Calculer le total de la commande
$total = 0;
foreach ($articlesCommandes as $article) {
    $total += ($article['quantite'] * $article['prix']);
}

// Récupérer le numéro de commande (le même pour tous les articles de cette commande)
$numero_commande = !empty($articlesCommandes) ? $articlesCommandes[0]['numero_commande'] : 'N/A';

// Vider la variable de session temporaire après utilisation
unset($_SESSION['derniere_commande']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de commande</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/assets/css/base.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4 text-center"><?php echo $message; ?></h2>
        <p class="text-center">Votre commande a été enregistrée avec succès. Numéro de commande : <?php echo htmlspecialchars($numero_commande); ?></p>

        <?php if (!empty($articlesCommandes)): ?>
            <div class="list-group mb-4">
                <?php foreach ($articlesCommandes as $key => $item): ?>
                    <div class="list-group-item d-flex align-items-center">
                        <img src="/assets/images/bde.webp" alt="Produit <?php echo htmlspecialchars($item['titre_produit'] ?? 'inconnu'); ?>" class="img-fluid me-3" style="width: auto; height: 100px;">
                        <div class="d-flex flex-column w-100">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-1"><?php echo htmlspecialchars($item['titre_produit'] ?? 'Produit inconnu'); ?></h5>
                            </div>
                            <div class="d-flex gap-3 align-items-center mb-2" style="align-items: center;">
                                <span>Taille: <?php echo htmlspecialchars($item['taille'] ?? 'N/A'); ?></span>
                                <span style="display: flex; align-items: center;">
                                    Couleur:  
                                    <div style="width: 20px; height: 20px; background-color: #<?php echo htmlspecialchars($item['couleur'] ?? '000'); ?>; border-radius: 50%; display: inline-block;"></div>
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Quantité: <?php echo htmlspecialchars($item['quantite'] ?? 0); ?></span>
                                <span><?php echo number_format($item['quantite'] * $item['prix'], 2); ?> €</span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center mb-4">
                <strong>Total de la commande : </strong><?php echo number_format($total, 2); ?> €
            </div>
        <?php else: ?>
            <p class="text-center">Aucun article n'a été trouvé dans votre commande.</p>
        <?php endif; ?>

        <div class="text-center">
            <a href="/users.php" class="btn btn-primary">Voir mes commandes</a>
            <a href="/shop.php" class="btn btn-secondary">Retour à la boutique</a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>