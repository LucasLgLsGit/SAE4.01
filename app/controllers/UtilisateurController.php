<?php

require_once './app/core/Controller.php';
require_once './app/repositories/UtilisateurRepository.php';
require_once './app/trait/FormTrait.php';
require_once './app/trait/AuthTrait.php';

class UtilisateurController extends Controller {

	use FormTrait;
	use AuthTrait;

	public function index()
	{
		$repository = new UtilisateurRepository();
		$utilisateurs = $repository->findAll();

		$this->view('/utilisateur/index.html.twig', ['utilisateurs' => $utilisateurs]);
	}


}