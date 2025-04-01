<?php

require_once './app/core/Controller.php';

class FaqController extends Controller
{
	public function index()
	{
		$isLoggedIn = $this->isLoggedIn();
		$user = $this->getCurrentUser();
		$isAdmin = $user && $user->isAdmin();

		$this->view('FAQ.html.twig', [
			'isLoggedIn' => $isLoggedIn,
			'isAdmin' => $isAdmin
		]);
	}
}