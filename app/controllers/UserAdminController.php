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

		$this->view('/admin/usersAdmin.html.twig', [
			'users' => $users,
		]);
	}
}