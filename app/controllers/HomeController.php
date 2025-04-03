<?php

require_once './app/core/Controller.php';
require_once './app/services/AuthService.php';
require_once './app/controllers/EvenementController.php';
require_once './app/controllers/ActualiteController.php';


class HomeController extends Controller
{
	public function index()
	{
		$isLoggedIn = $this->isLoggedIn();
		$user = $this->getCurrentUser();
		$isAdmin = $user && $user->isAdmin();

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
			'title' => 'Accueil',
			'isLoggedIn' => $isLoggedIn,
			'isAdmin' => $isAdmin,
			'upcomingEvents' => $upcomingEvents,
			'lastActualites' => $lastActualites,
			'nombreMembres' => $nombreMembres,
			'nombreEvenements' => $nombreEvenements,
			'nombreAdherents' => $nombreAdherents,
			'currentYear' => $currentYear
		]);
	}
}