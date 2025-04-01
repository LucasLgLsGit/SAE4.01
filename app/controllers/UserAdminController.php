<?php

require_once './app/core/Controller.php';
require_once './app/services/AuthService.php';
require_once './app/repositories/UtilisateurRepository.php';


class UserAdminController extends Controller
{
	public function index()
	{
		$usersRepo = new UtilisateurRepository();
		$users = $usersRepo->findAll();
		
		$isLoggedIn = $this->isLoggedIn();
		$user = $this->getCurrentUser();
		$isAdmin = $user && $user->isAdmin();
		
		if($isAdmin) {
			$this->view('/admin/usersAdmin.html.twig', [
				'users' => $users,
				'actualites' => $actualites,
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