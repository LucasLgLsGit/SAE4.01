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

	public function update() {
		$id = $this->getQueryParam('id_user');

		if ($id === null) {
			throw new Exception("L'identifiant utilisateur est requis !");
		}

		$data = $this->getAllPostParams();
		$errors = [];

		if (!empty($data)) {
			try {
				$userService = new UtilisateurService();
				$userService->update($id, $data);
				$this->redirectTo('utilisateurs.php');
			} catch (Exception $e) {
				$errors = explode(', ', $e->getMessage());
			}
		}

		$this->view('/utilisateur/update.html.twig', 'Modification d\'un utilisateur', ['errors' => $errors, 'data' => $data, 'id_user' => $id]);
	}
}