<?php

require_once './app/core/Controller.php';
require_once './app/controllers/EvenementController.php';

class HomeController extends Controller
{
	public function index() {
		$evenementController = new EvenementController();
		$upcomingEvents = $evenementController->getUpcomingEvents();
	
		$this->view('index.html.twig', ['upcomingEvents' => $upcomingEvents]);
	}
}