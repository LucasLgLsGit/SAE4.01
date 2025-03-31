<?php

require_once './app/core/Controller.php';
require_once './app/services/AuthService.php';
require_once './app/trait/FormTrait.php';
require_once './app/trait/AuthTrait.php';

class AuthController extends Controller {
	use FormTrait;
	use AuthTrait;

	public function login() {
		$authService = new AuthService();

		// Récupérer les données POST nettoyées
		$postData = $this->getAllPostParams();
		$data = [];

		// Si aucune donnée n'est envoyée en POST ou si la connexion échoue, afficher le formulaire
		if (!empty($postData))
		{
			$utilisateurRepository = new UtilisateurRepository();

			$user = $utilisateurRepository->findByEmail($this->getPostParam('mail'));

			if($user !== null && $this->verify($this->getPostParam('mdp'),$user->getMdp()))
			{
				$authService->setUser($user);
				$this->redirectTo('index.php');
			}
			$data= empty($postData) ? []:['error'=>'Email ou mot de passe invalide'];// si des données existent, elles ne sont pas valide

		}

		$this->view('/user/login.html.twig', $data ); // Affiche la vue login.php
	}
}
