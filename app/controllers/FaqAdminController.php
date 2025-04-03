<?php

require_once './app/core/Controller.php';
require_once './app/services/AuthService.php';
require_once './app/repositories/FaqRepository.php';

class FaqAdminController extends Controller
{
	public function index()
	{
		$faqsRepo = new FaqRepository();
		$faqs = $faqsRepo->findAll();

		$isLoggedIn = $this->isLoggedIn();
		$user = $this->getCurrentUser();
		$isAdmin = $user && $user->isAdmin();

		if($isAdmin) {
			$this->view('/admin/faqAdmin.html.twig', [
				'title' => 'FAQ admin',
				'isLoggedIn' => $isLoggedIn,
				'isAdmin' => $isAdmin,
				'faqs' => $faqs
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