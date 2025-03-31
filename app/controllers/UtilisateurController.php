<?php

require_once './app/core/Controller.php';
require_once './app/repositories/UtilisateurService.php';
require_once './app/trait/FormTrait.php';

class UtilisateurController extends Controller {

	use FormTrait;
	use AuthTrait;

	public function index()
	{
		$repository = new UtilisateurRepository();
		$utilisateurs = $repository->findAll();

		$this->view('/utilisateur/index.html.twig', ['utilisateurs' => $utilisateurs]);
	}

	public function create() {
		$data = $this->getAllPostParams();
		$errors = [];

		if (!empty($data)) {
			try {
				$userService = new UtilisateurService();
				$userService->create($data);
				$this->redirectTo('utilisateurs.php');
			} catch (Exception $e) {
				$errors = explode(', ', $e->getMessage());
			}
		}

		$this->view('/utilisateur/create.html.twig', 'Création d\'un utilisateur', ['errors' => $errors, 'data' => $data]);
	}
}