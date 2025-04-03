<?php

require_once './app/core/Controller.php';
require_once './app/services/AuthService.php';
require_once './app/repositories/CommandeRepository.php';

class CommandeAdminController extends Controller
{
	public function index()
	{
		$commandeRepo = new CommandeRepository();
		$commandes = $commandeRepo->findAll();
		
		$isLoggedIn = $this->isLoggedIn();
		$user = $this->getCurrentUser();
		$isAdmin = $user && $user->isAdmin();
		
		if ($isAdmin) {
			$this->view('/admin/commandesAdmin.html.twig', [
				'title' => 'Commandes admin',
				'commandes' => $commandes,
				'isLoggedIn' => $isLoggedIn,
				'isAdmin' => $isAdmin,
				'utilisateur' => $user
			]);
		} else {
			$this->view('index.html.twig', [
				'title' => 'Accueil',
				'isLoggedIn' => $isLoggedIn,
				'isAdmin' => $isAdmin
			]);
		}
	}
}