<?php
require_once 'vendor/autoload.php';
abstract class Controller {
	protected $viewPath = './app/views/'; // Chemin vers les vues


	protected function view(string $viewName, array $data = []) {
		$loader = new \Twig\Loader\FilesystemLoader('app/views');
		$twig = new \Twig\Environment($loader, [
			'cache' => false,
		]);
	
		// Ajoute un filtre personnalisé pour les dates en français
		$twig->addFilter(new \Twig\TwigFilter('date_fr', function(\DateTimeInterface $date, string $format = 'd F Y') {
			$englishMonths = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
			$frenchMonths = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];
			
			return str_replace(
				$englishMonths, 
				$frenchMonths, 
				$date->format($format)
			);
		}));
	
		$data['current_url'] = $_SERVER['REQUEST_URI'];
		echo $twig->render($viewName, $data);
	}

	protected function json($data, $status = 200) {
		header('Content-Type: application/json');
		http_response_code($status);
		echo json_encode($data);
		exit();
	}

	protected function redirectTo($url) {
		header("Location: $url");
		exit();
	}

	protected function isLoggedIn(): bool
	{
		if (session_status() === PHP_SESSION_NONE) {
			session_start(); // Démarre la session si elle n'est pas déjà démarrée
		}

		return isset($_SESSION['user']); // Vérifie si un utilisateur est stocké dans la session
	}

	protected function getCurrentUser(): ?Utilisateur
	{
		if (session_status() === PHP_SESSION_NONE) {
			session_start(); // Démarre la session si elle n'est pas déjà démarrée
		}

		// Inclure la classe Utilisateur avant de désérialiser
		require_once './app/entities/Utilisateur.php';

		return isset($_SESSION['user']) ? unserialize($_SESSION['user']) : null;
	}

	protected function isAdmin(): bool
	{
		$user = $this->getCurrentUser();
		return $user && $user->isAdmin();
	}
}
