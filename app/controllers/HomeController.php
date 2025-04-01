<?php

require_once './app/core/Controller.php';
require_once './app/services/AuthService.php';
require_once './app/controllers/EvenementController.php';
require_once './app/controllers/ActualiteController.php';


class HomeController extends Controller
{
	public function index()
	{
		$authService = new AuthService();
		$isLoggedIn = $authService->isLoggedIn();

		// Vérifiez si l'utilisateur est administrateur
		$isAdmin = false;
		if ($isLoggedIn) {
			$user = $authService->getUser(); // Suppose que cette méthode retourne l'utilisateur connecté
			$isAdmin = $user && $user->isAdmin(); // Vérifie si l'utilisateur est admin
		}

		$evenementController = new EvenementController();
		$upcomingEvents = $evenementController->getUpcomingEvents();

		$actualiteController = new ActualiteController();
		$lastActualites = $actualiteController->getLastActualites(10);

		$utilisateurRepo = new UtilisateurRepository();
		$evenementRepo = new EvenementRepository();

		$currentYear = (int) date('Y');
		$nombreMembres = $utilisateurRepo->countMembres();
		$nombreEvenements = $evenementRepo->countEvenementsByYear($currentYear);
		$nombreAdherents = $utilisateurRepo->countAdherents();

		$this->view('index.html.twig', [
			'isLoggedIn' => $isLoggedIn,
			'isAdmin' => $isAdmin, // Passe la variable isAdmin à la vue
			'upcomingEvents' => $upcomingEvents,
			'lastActualites' => $lastActualites,
			'nombreMembres' => $nombreMembres,
			'nombreEvenements' => $nombreEvenements,
			'nombreAdherents' => $nombreAdherents,
			'currentYear' => $currentYear
		]);
	}
}