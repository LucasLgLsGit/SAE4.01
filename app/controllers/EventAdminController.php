<?php

require_once './app/core/Controller.php';
require_once './app/services/AuthService.php';
require_once './app/repositories/EvenementRepository.php';


class EventAdminController extends Controller
{
	public function index()
	{
		$eventRepo = new EvenementRepository();
		$events = $eventRepo->findAll();
		
		$isLoggedIn = $this->isLoggedIn();
		$user = $this->getCurrentUser();
		$isAdmin = $user && $user->isAdmin();
		
		if($isAdmin) {
			$this->view('/admin/eventsAdmin.html.twig', [
				'events' => $events,
				'isLoggedIn' => $isLoggedIn,
				'isAdmin' => $isAdmin
			]);
		} else {
			$this->view('index.html.twig', [
				'isLoggedIn' => $isLoggedIn,
				'isAdmin' => $isAdmin
			]);
		}
	}
}