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

		$this->view('index.html.twig',  [
			'isLoggedIn' => $isLoggedIn,
			'articles' => $articles,
			'upcomingEvents' => $upcomingEvents,
			'lastActualites' => $lastActualites
		]);
	}
}