<?php

require_once './app/core/Controller.php';
require_once './app/services/AuthService.php';
require_once './app/repositories/ActualiteRepository.php';


class NewsAdminController extends Controller
{
	public function index()
	{
		$newsRepo = new ActualiteRepository();
		$news = $newsRepo->findAll();
		
		$isLoggedIn = $this->isLoggedIn();
		$user = $this->getCurrentUser();
		$isAdmin = $user && $user->isAdmin();
		
		if($isAdmin) {
			$this->view('/admin/newsAdmin.html.twig', [
				'title' => 'Actus admin',
				'news' => $news,
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