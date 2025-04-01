<?php

require_once './app/core/Controller.php';
require_once './app/services/UtilisateurService.php';
require_once './app/trait/FormTrait.php';
require_once './app/trait/AuthTrait.php';

class UtilisateurController extends Controller {

	use FormTrait;
	use AuthTrait;

	public function index()
	{
		$repository = new UtilisateurRepository();
		$utilisateurs = $repository->findAll();

		$this->view('/user/index.html.twig', ['utilisateurs' => $utilisateurs]);
	}

	public function create() {
		$data = $this->getAllPostParams();
		$errors = [];

		if (!empty($data)) {
			try {
				$userService = new UtilisateurService();
				$userService->create($data);
				$this->redirectTo('index.php');
			} catch (Exception $e) {
				$errors = explode(', ', $e->getMessage());
			}
		}

		$this->view('/user/signUp.html.twig', ['errors' => $errors, 'data' => $data]);
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

		$this->view('/user/profil.html.twig', 'Modification d\'un utilisateur', ['errors' => $errors, 'data' => $data, 'id_user' => $id]);
	}
}