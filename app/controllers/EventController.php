<?php

// app/controllers/EventController.php
require_once './app/core/Controller.php';
require_once './app/controllers/EvenementController.php';

class EventController extends Controller
{
	public function index()
	{
		setlocale(LC_TIME, 'fr_FR.UTF-8', 'fr_FR', 'french');

		$evenementController = new EvenementController();
		$upcomingEvents = $evenementController->getAllUpcomingEvents(); 

		$eventsByYear = [];
		foreach ($upcomingEvents as $event) {
			$year = $event->getDateDebut()->format('Y');
			if (!isset($eventsByYear[$year])) {
				$eventsByYear[$year] = [];
			}
			$eventsByYear[$year][] = $event;
		}
		krsort($eventsByYear);

		$this->view('events.html.twig', ['eventsByYear' => $eventsByYear]);
	}
}