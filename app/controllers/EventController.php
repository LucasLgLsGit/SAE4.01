<?php

require_once './app/core/Controller.php';
require_once './app/controllers/EvenementController.php';

class EventController extends Controller
{
	public function index()
    {
        $evenementController = new EvenementController();
        $upcomingEvents = $evenementController->getUpcomingEvents();

        // Regroupement par année côté PHP
        $eventsByYear = [];
        foreach ($upcomingEvents as $event) {
            $year = $event->getDateDebut()->format('Y');
            if (!isset($eventsByYear[$year])) {
                $eventsByYear[$year] = [];
            }
            $eventsByYear[$year][] = $event;
        }

        // Trie les années par ordre décroissant
        krsort($eventsByYear);

        $this->view('events.html.twig', ['eventsByYear' => $eventsByYear]);
    }
}