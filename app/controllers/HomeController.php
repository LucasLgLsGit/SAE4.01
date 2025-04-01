<?php

require_once './app/core/Controller.php';
require_once './app/services/AuthService.php';

class HomeController extends Controller
{
	public function index()
	{
        $authService = new AuthService();
        $isLoggedIn = $authService->isLoggedIn();

		$this->view('index.html.twig',  [
			'articles' => $articles,
			'isLoggedIn' => $isLoggedIn,
		]);
	}
}