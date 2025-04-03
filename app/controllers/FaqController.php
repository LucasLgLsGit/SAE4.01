<?php

require_once './app/core/Controller.php';
require_once './app/services/AuthService.php';
require_once './app/repositories/FaqRepository.php';

class FaqController extends Controller
{
	public function index()
	{
		$isLoggedIn = $this->isLoggedIn();
		$user = $this->getCurrentUser();
		$faqsRepo = new FaqRepository();
		$faqs = $faqsRepo->findAll();
		$isAdmin = $user && $user->isAdmin();

		$this->view('FAQ.html.twig', [
			'title' => 'FAQ',
			'isLoggedIn' => $isLoggedIn,
			'isAdmin' => $isAdmin,
			'faqs' => $faqs
		]);
	}
}