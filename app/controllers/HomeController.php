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

    $evenementController = new EvenementController();
    $upcomingEvents = $evenementController->getUpcomingEvents();

    $actualiteController = new ActualiteController();
    $lastActualites = $actualiteController->getLastActualites(10);

    $utilisateurRepo = new UtilisateurRepository();
    $evenementRepo = new EvenementRepository();

    $nombreMembres = $utilisateurRepo->countMembres();
    $nombreEvenements = $evenementRepo->countEvenements2025();
    $nombreAdherents = $utilisateurRepo->countAdherents();

    $this->view('index.html.twig', [
        'isLoggedIn' => $isLoggedIn,
        'upcomingEvents' => $upcomingEvents,
        'lastActualites' => $lastActualites,
        'nombreMembres' => $nombreMembres,
        'nombreEvenements' => $nombreEvenements,
        'nombreAdherents' => $nombreAdherents,
    ]);
}
}